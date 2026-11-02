<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Models\Lead;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-inbox-stack';

    protected static string|\UnitEnum|null $navigationGroup = 'Leads';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public const STATUS_COLORS = [
        'new' => 'warning', 'contacted' => 'info', 'qualified' => 'primary', 'proposal' => 'primary',
        'won' => 'success', 'lost' => 'gray', 'spam' => 'danger',
    ];

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(3)->components([
            Section::make('Enquiry')->columnSpan(2)->columns(2)->schema([
                TextInput::make('name')->required(),
                TextInput::make('phone')->tel()->required()
                    ->suffixAction(Action::make('wa')->icon('heroicon-o-chat-bubble-left-right')
                        ->url(fn ($state) => $state ? 'https://wa.me/91'.substr(preg_replace('/\D/', '', $state), -10) : null, true)),
                TextInput::make('email')->email(),
                TextInput::make('company'),
                TextInput::make('website'),
                TextInput::make('service'),
                Select::make('budget')->options(Lead::BUDGETS),
                Select::make('timeline')->options(Lead::TIMELINES),
                Textarea::make('message')->rows(4)->columnSpanFull(),
            ]),
            Section::make('Follow-up')->columnSpan(1)->schema([
                Select::make('status')->options(Lead::STATUSES)->required()->default('new'),
                TextInput::make('deal_value')->label('Deal value (₹)')->numeric()->prefix('₹')
                    ->helperText('Fill when won. Sent to Google Ads with the conversion.'),
                DatePicker::make('follow_up_on')->label('Follow up on'),
                Textarea::make('notes')->rows(5),
            ]),
            Section::make('Where this lead came from')->columnSpanFull()->collapsed()->columns(3)->schema([
                TextEntry::make('source')->state(fn (?Lead $record) => $record?->sourceLabel()),
                TextEntry::make('form')->placeholder('—'),
                TextEntry::make('created_at')->label('Received')->dateTime('d M Y, h:i A'),
                TextEntry::make('utm_campaign')->label('Campaign')->placeholder('—'),
                TextEntry::make('utm_term')->label('Keyword')->placeholder('—'),
                TextEntry::make('gclid')->label('Google click ID')->placeholder('—')->limit(30),
                TextEntry::make('landing_url')->label('First page visited')->placeholder('—')->columnSpanFull(),
                TextEntry::make('page_url')->label('Form submitted on')->placeholder('—')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')->label('Received')->since()->sortable()
                    ->tooltip(fn ($record) => $record->created_at->format('d M Y, h:i A')),
                TextColumn::make('name')->searchable()->weight('bold')->description(fn ($record) => $record->phone),
                TextColumn::make('service')->placeholder('—')->toggleable(),
                TextColumn::make('budget')->formatStateUsing(fn ($state) => Lead::BUDGETS[$state] ?? $state)->placeholder('—'),
                TextColumn::make('source')->state(fn (Lead $record) => $record->sourceLabel())->badge()->color('gray'),
                TextColumn::make('status')->badge()
                    ->formatStateUsing(fn ($state) => Lead::STATUSES[$state] ?? $state)
                    ->color(fn ($state) => self::STATUS_COLORS[$state] ?? 'gray'),
                TextColumn::make('follow_up_on')->label('Follow up')->date('d M')->placeholder('—')->sortable()
                    ->color(fn ($record) => $record->follow_up_on?->isPast() ? 'danger' : null),
                TextColumn::make('phone')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email')->searchable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')->options(Lead::STATUSES)->multiple(),
                SelectFilter::make('budget')->options(Lead::BUDGETS)->multiple(),
                Filter::make('google_ads')->label('From Google Ads')->query(fn (Builder $query) => $query->whereNotNull('gclid')),
                Filter::make('due')->label('Follow-up due')->query(fn (Builder $query) => $query->whereDate('follow_up_on', '<=', today())),
            ])
            ->recordActions([
                Action::make('whatsapp')->icon('heroicon-o-chat-bubble-left-right')->color('success')->label('')
                    ->tooltip('WhatsApp')
                    ->url(fn (Lead $record) => 'https://wa.me/91'.substr(preg_replace('/\D/', '', $record->phone), -10), true),
                EditAction::make()->label('Open'),
            ])
            ->toolbarActions([
                BulkAction::make('mark_spam')->label('Mark as spam')->icon('heroicon-o-no-symbol')->color('danger')
                    ->requiresConfirmation()->action(fn (Collection $records) => $records->each->update(['status' => 'spam'])),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        $n = Lead::where('status', 'new')->count();

        return $n ? (string) $n : null;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
        ];
    }
}
