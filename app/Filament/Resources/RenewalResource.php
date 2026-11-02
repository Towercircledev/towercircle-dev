<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RenewalResource\Pages;
use App\Models\Activity;
use App\Models\Payment;
use App\Models\Renewal;
use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RenewalResource extends Resource
{
    protected static ?string $model = Renewal::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-path-rounded-square';

    protected static string|\UnitEnum|null $navigationGroup = 'Clients';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema, bool $withOwner = true): Schema
    {
        return $schema->columns(2)->components(array_filter([
            $withOwner ? Select::make('client_id')->label('Client')->relationship('client', 'name')->searchable()->preload()->required()->live() : null,
            Select::make('client_site_id')->label('Website')
                ->options(fn (Get $get, $livewire) => \App\Models\ClientSite::where('client_id', $withOwner ? $get('client_id') : $livewire->getOwnerRecord()->id)->pluck('domain', 'id'))
                ->placeholder('—'),
            Select::make('type')->options(Renewal::TYPES)->required()->default('domain'),
            TextInput::make('description')->maxLength(150)->placeholder('e.g. Business Premium, 3 mailboxes'),
            TextInput::make('provider')->placeholder('GoDaddy, Hostinger, Google…'),
            Select::make('cycle')->options(Renewal::CYCLES)->default('yearly')->required(),
            DatePicker::make('due_on')->label('Next due / expiry date')->required()->native(false),
            TextInput::make('amount')->label('You charge the client (₹, before GST)')->numeric()->prefix('₹'),
            TextInput::make('cost')->label('It costs you (₹)')->numeric()->prefix('₹')->helperText('Optional – to see your margin'),
            Toggle::make('is_active')->label('Active')->default(true)->inline(false),
            Textarea::make('notes')->rows(2)->columnSpanFull(),
        ]));
    }

    public static function table(Table $table, bool $withOwner = true): Table
    {
        $admin = fn () => (bool) auth()->user()?->isAdmin();

        return $table
            ->defaultSort('due_on')
            ->columns(array_filter([
                TextColumn::make('due_on')->label('Due')->date('d M Y')->sortable()
                    ->description(fn ($record) => $record->due_on->isPast() ? 'Overdue '.$record->due_on->diffForHumans(null, true) : 'in '.today()->diffInDays($record->due_on).' days')
                    ->color(fn ($record) => $record->due_on->isPast() ? 'danger' : ($record->due_on->lte(today()->addDays(30)) ? 'warning' : null)),
                $withOwner ? TextColumn::make('client.name')->label('Client')->searchable()->sortable()->weight('bold') : null,
                TextColumn::make('type')->formatStateUsing(fn ($state, $record) => $record->label())->wrap(),
                TextColumn::make('cycle')->formatStateUsing(fn ($state) => Renewal::CYCLES[$state] ?? $state)->toggleable(),
                TextColumn::make('amount')->formatStateUsing(fn ($state) => $state ? inr($state) : '—')->visible($admin),
            ]))
            ->filters([
                Filter::make('soon')->label('Due in next 60 days')->default()->query(fn (Builder $query) => $query->where('is_active', true)->whereDate('due_on', '<=', today()->addDays(60))),
                SelectFilter::make('type')->options(Renewal::TYPES),
                Filter::make('active')->label('Active only')->default()->query(fn (Builder $query) => $query->where('is_active', true)),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('invoice')->label('Create invoice')->icon('heroicon-o-document-plus')->visible($admin)
                        ->requiresConfirmation()->modalDescription('Creates a pending payment for this renewal that you can send as a PDF invoice.')
                        ->action(function (Renewal $record) {
                            $p = Payment::create([
                                'client_id' => $record->client_id, 'renewal_id' => $record->id, 'purpose' => 'renewal',
                                'description' => $record->label().' – '.(Renewal::CYCLES[$record->cycle] ?? '').' renewal', 'amount' => (int) $record->amount,
                                'gst_rate' => Setting::get('invoice.default_gst', 18), 'invoice_date' => today(), 'due_on' => $record->due_on,
                            ]);
                            Notification::make()->title('Invoice '.$p->number.' created')->success()->send();
                        }),
                    Action::make('renewed')->label('Mark renewed')->icon('heroicon-o-check-badge')->color('success')
                        ->requiresConfirmation()->modalDescription('Moves the due date forward by one cycle and resets the reminders.')
                        ->action(function (Renewal $record) {
                            $old = $record->due_on->format('d M Y');
                            $record->advance();
                            Activity::log($record->client, 'system', $record->label().' renewed (was due '.$old.', next '.($record->is_active ? $record->due_on->format('d M Y') : 'none').')');
                            Notification::make()->title('Renewed. Next due '.$record->due_on->format('d M Y'))->success()->send();
                        }),
                    Action::make('remind')->label('WhatsApp reminder')->icon('heroicon-o-chat-bubble-left-right')->color('success')
                        ->visible(fn (Renewal $record) => (bool) $record->client->whatsapp())
                        ->url(fn (Renewal $record) => $record->client->whatsapp(
                            'Hi '.($record->client->contact_name ?: $record->client->name).', a quick reminder from '.site('name').': your '.$record->label().' is due for renewal on '.$record->due_on->format('d M Y')
                            .($record->amount ? '. Amount: '.inr((int) round($record->amount * (1 + Setting::get('invoice.default_gst', 18) / 100))).' (incl. GST)' : '')
                            .(Setting::get('invoice.upi') ? '. UPI: '.Setting::get('invoice.upi') : '').'. Please let us know once paid, or if you have any questions. Thank you!'
                        ), true),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        $n = Renewal::dueWithin(30)->count();

        return $n ? (string) $n : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageRenewals::route('/')];
    }
}
