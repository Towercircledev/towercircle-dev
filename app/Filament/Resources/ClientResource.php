<?php

namespace App\Filament\Resources;

use App\Filament\RelationManagers\ActivitiesRelationManager;
use App\Filament\RelationManagers\TasksRelationManager;
use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static string|\UnitEnum|null $navigationGroup = 'Clients';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(3)->components([
            Section::make('Client')->columnSpan(2)->columns(2)->schema([
                TextInput::make('name')->label('Business name')->required()->maxLength(150),
                TextInput::make('contact_name')->label('Contact person')->maxLength(100),
                TextInput::make('phone')->label('Phone / WhatsApp')->tel()
                    ->suffixAction(Action::make('wa')->icon('heroicon-o-chat-bubble-left-right')
                        ->url(fn ($state) => $state ? 'https://wa.me/91'.substr(preg_replace('/\D/', '', $state), -10) : null, true)),
                TextInput::make('alt_phone')->label('Other phone')->tel(),
                TextInput::make('email')->email(),
                TextInput::make('gstin')->label('GSTIN')->maxLength(15),
                Textarea::make('address')->rows(2),
                TextInput::make('city'),
            ]),
            Section::make('Status')->columnSpan(1)->schema([
                Select::make('status')->options(Client::STATUSES)->default('active')->required(),
                Textarea::make('notes')->rows(6)->helperText('Anything to remember about this client'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')->label('Client')->searchable()->sortable()->weight('bold')
                    ->description(fn (Client $record) => trim($record->contact_name.' · '.$record->phone, ' ·')),
                TextColumn::make('sites.domain')->label('Websites')->badge()->color('gray')->searchable()->limitList(2)->expandableLimitedList(),
                TextColumn::make('next_renewal')->label('Next renewal')
                    ->state(fn (Client $record) => $record->renewals()->where('is_active', true)->orderBy('due_on')->first())
                    ->formatStateUsing(fn ($state) => $state ? $state->due_on->format('d M Y').' · '.(\App\Models\Renewal::TYPES[$state->type] ?? '') : null)
                    ->color(fn ($state) => $state && $state->due_on->lte(today()->addDays(30)) ? 'warning' : null)->placeholder('—'),
                TextColumn::make('outstanding')->label('Owes')
                    ->state(fn (Client $record) => $record->outstanding())
                    ->formatStateUsing(fn ($state) => $state ? inr($state) : '—')
                    ->color(fn ($state) => $state ? 'danger' : 'gray')
                    ->visible(fn () => auth()->user()?->isAdmin()),
                TextColumn::make('status')->badge()->formatStateUsing(fn ($state) => Client::STATUSES[$state] ?? $state)
                    ->color(fn ($state) => ['active' => 'success', 'paused' => 'warning', 'former' => 'gray'][$state] ?? 'gray'),
                TextColumn::make('email')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('phone')->searchable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([SelectFilter::make('status')->options(Client::STATUSES)->default('active')])
            ->recordActions([
                Action::make('wa')->label('')->tooltip('WhatsApp')->icon('heroicon-o-chat-bubble-left-right')->color('success')
                    ->url(fn (Client $record) => $record->whatsapp(), true)->visible(fn (Client $record) => (bool) $record->whatsapp()),
                EditAction::make()->label('Open'),
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'contact_name', 'phone', 'email', 'sites.domain'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return array_filter(['Contact' => $record->contact_name, 'Phone' => $record->phone]);
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with('sites');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SitesRelationManager::class,
            RelationManagers\CredentialsRelationManager::class,
            RelationManagers\RenewalsRelationManager::class,
            RelationManagers\PaymentsRelationManager::class,
            TasksRelationManager::class,
            RelationManagers\FilesRelationManager::class,
            ActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
