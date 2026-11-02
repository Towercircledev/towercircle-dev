<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\AdminOnly;
use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Activity;
use App\Models\Payment;
use App\Models\Setting;
use App\Services\InvoicePdf;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PaymentResource extends Resource
{
    use AdminOnly;

    protected static ?string $model = Payment::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static string|\UnitEnum|null $navigationGroup = 'Clients';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Payments & invoices';

    protected static ?string $recordTitleAttribute = 'number';

    public static function form(Schema $schema, bool $withOwner = true): Schema
    {
        return $schema->columns(2)->components(array_filter([
            $withOwner ? Select::make('client_id')->label('Client')->relationship('client', 'name')->searchable()->preload()->required() : null,
            Select::make('purpose')->options(Payment::PURPOSES)->default('project')->required(),
            TextInput::make('description')->required()->maxLength(200)->columnSpanFull()
                ->placeholder('e.g. Shopify store development – 50% advance'),
            TextInput::make('amount')->label('Amount before GST (₹)')->numeric()->prefix('₹')->required()->live(onBlur: true),
            Select::make('gst_rate')->label('GST')->options(['0' => 'No GST', '18' => '18%', '12' => '12%', '5' => '5%'])
                ->default(fn () => (string) Setting::get('invoice.default_gst', 18))->required()->live(),
            Placeholder::make('total_preview')->label('Total')
                ->content(fn (Get $get) => inr((int) $get('amount') + (int) round((int) $get('amount') * (float) $get('gst_rate') / 100))),
            DatePicker::make('invoice_date')->default(today())->required()->native(false),
            DatePicker::make('due_on')->label('Due date')->default(today()->addDays(7))->native(false),
            Select::make('status')->options(Payment::STATUSES)->default('pending')->required()->live(),
            DatePicker::make('paid_on')->native(false)->visible(fn (Get $get) => $get('status') === 'paid'),
            Select::make('method')->options(Payment::METHODS)->visible(fn (Get $get) => $get('status') === 'paid'),
            TextInput::make('reference')->label('UTR / reference')->visible(fn (Get $get) => $get('status') === 'paid'),
            Textarea::make('notes')->rows(2)->columnSpanFull(),
        ]));
    }

    public static function table(Table $table, bool $withOwner = true): Table
    {
        return $table
            ->defaultSort('invoice_date', 'desc')
            ->columns(array_filter([
                TextColumn::make('number')->label('Invoice')->searchable()->weight('bold')
                    ->description(fn ($record) => $record->invoice_date->format('d M Y')),
                $withOwner ? TextColumn::make('client.name')->label('Client')->searchable()->sortable() : null,
                TextColumn::make('description')->wrap()->limit(60),
                TextColumn::make('total')->formatStateUsing(fn ($state) => inr($state))->sortable()
                    ->summarize(Sum::make()->label('Total')->formatStateUsing(fn ($state) => inr($state))),
                TextColumn::make('status')->badge()
                    ->formatStateUsing(fn ($state, $record) => $record->isOverdue() ? 'Overdue' : (Payment::STATUSES[$state] ?? $state))
                    ->color(fn ($state, $record) => $record->isOverdue() ? 'danger' : ['paid' => 'success', 'pending' => 'warning', 'cancelled' => 'gray'][$state] ?? 'gray')
                    ->description(fn ($record) => $record->status === 'paid' ? ($record->paid_on?->format('d M').' · '.(Payment::METHODS[$record->method] ?? '')) : ($record->due_on ? 'due '.$record->due_on->format('d M') : null)),
            ]))
            ->filters([
                SelectFilter::make('status')->options(Payment::STATUSES),
                Filter::make('overdue')->query(fn (Builder $query) => $query->where('status', 'pending')->whereDate('due_on', '<', today())),
                Filter::make('this_month')->label('This month')->query(fn (Builder $query) => $query->whereBetween('invoice_date', [now()->startOfMonth(), now()->endOfMonth()])),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('paid')->label('Mark as paid')->icon('heroicon-o-check-circle')->color('success')
                        ->visible(fn (Payment $record) => $record->status === 'pending')
                        ->schema([
                            DatePicker::make('paid_on')->default(today())->required()->native(false),
                            Select::make('method')->options(Payment::METHODS)->default('upi')->required(),
                            TextInput::make('reference')->label('UTR / reference'),
                        ])
                        ->action(function (Payment $record, array $data) {
                            $record->update($data + ['status' => 'paid']);
                            Activity::log($record->client, 'payment', 'Received '.inr($record->total).' for '.$record->number.' ('.(Payment::METHODS[$record->method] ?? '').')');
                            if ($record->renewal && $record->renewal->due_on->lte(today()->addDays(60))) {
                                $record->renewal->advance();
                            }
                            Notification::make()->title('Payment recorded')->success()->send();
                        }),
                    Action::make('pdf')->label('Download invoice PDF')->icon('heroicon-o-arrow-down-tray')
                        ->action(fn (Payment $record) => response()->streamDownload(
                            fn () => print(InvoicePdf::render($record)),
                            str_replace('/', '-', $record->number).'.pdf',
                            ['Content-Type' => 'application/pdf']
                        )),
                    Action::make('remind')->label('WhatsApp payment reminder')->icon('heroicon-o-chat-bubble-left-right')->color('success')
                        ->visible(fn (Payment $record) => $record->status === 'pending' && $record->client->whatsapp())
                        ->url(fn (Payment $record) => $record->client->whatsapp(
                            'Hi '.($record->client->contact_name ?: $record->client->name).', a gentle reminder from '.site('name').' about invoice '.$record->number.' for '.inr($record->total)
                            .' ('.$record->description.')'.($record->due_on ? ', due on '.$record->due_on->format('d M Y') : '').'.'
                            .(Setting::get('invoice.upi') ? ' UPI: '.Setting::get('invoice.upi').'.' : '').' Please share the payment reference once done. Thank you!'
                        ), true),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        if (! auth()->user()?->isAdmin()) {
            return null;
        }
        $n = Payment::where('status', 'pending')->whereDate('due_on', '<', today())->count();

        return $n ? $n.' overdue' : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManagePayments::route('/')];
    }
}
