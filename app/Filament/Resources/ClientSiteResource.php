<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientSiteResource\Pages;
use App\Models\ClientSite;
use App\Services\SiteMonitor;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ClientSiteResource extends Resource
{
    protected static ?string $model = ClientSite::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-globe-alt';

    protected static string|\UnitEnum|null $navigationGroup = 'Clients';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Websites';

    protected static ?string $modelLabel = 'website';

    protected static ?string $recordTitleAttribute = 'domain';

    public static function form(Schema $schema, bool $withOwner = true): Schema
    {
        return $schema->columns(2)->components([
            Section::make('Website')->columns(2)->columnSpanFull()->schema(array_filter([
                $withOwner ? Select::make('client_id')->label('Client')->relationship('client', 'name')->searchable()->preload()->required() : null,
                TextInput::make('domain')->required()->placeholder('example.com')->maxLength(150),
                Select::make('platform')->options(ClientSite::PLATFORMS),
                TextInput::make('live_url')->label('Website link')->url()->placeholder('Filled from the domain if empty'),
                TextInput::make('admin_url')->label('Admin / login link')->url(),
            ])),
            Section::make('Domain & hosting')->columns(2)->columnSpanFull()
                ->description('Logins are stored encrypted and shown only to admins.')
                ->schema([
                    TextInput::make('registrar')->label('Domain registrar')->placeholder('GoDaddy, Hostinger, BigRock…')->datalist(['GoDaddy', 'Hostinger', 'BigRock', 'Namecheap', 'Cloudflare', 'Google Domains', 'ResellerClub']),
                    TextInput::make('hosting_provider')->placeholder('Hostinger, AWS Lightsail, Shopify…')->datalist(['Hostinger', 'AWS Lightsail', 'DigitalOcean', 'GoDaddy', 'Shopify', 'Vercel', 'Netlify', 'cPanel reseller']),
                    TextInput::make('domain_login')->label('Domain login email / username')->placeholder('client.domain@gmail.com')->copyable()->autocomplete('off')->visible(fn () => auth()->user()?->isAdmin()),
                    TextInput::make('hosting_login')->label('Hosting login email / username')->placeholder('client.hosting@gmail.com')->copyable()->autocomplete('off')->visible(fn () => auth()->user()?->isAdmin()),
                    TextInput::make('domain_password')->label('Domain password')->password()->revealable()->copyable()->autocomplete('new-password')->visible(fn () => auth()->user()?->isAdmin()),
                    TextInput::make('hosting_password')->label('Hosting password')->password()->revealable()->copyable()->autocomplete('new-password')->visible(fn () => auth()->user()?->isAdmin()),
                ]),
            Section::make('Email')->columns(2)->columnSpanFull()->schema([
                Select::make('email_provider')->options(ClientSite::EMAIL_PROVIDERS),
                TextInput::make('mailboxes')->label('Number of mailboxes')->numeric()->minValue(0),
            ]),
            Section::make('Monitoring & notes')->columns(2)->columnSpanFull()->schema([
                Toggle::make('monitor')->label('Check this site every 5 minutes and alert me if it goes down')->default(true)->columnSpanFull(),
                Textarea::make('notes')->rows(3)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table, bool $withOwner = true): Table
    {
        return $table
            ->defaultSort('domain')
            ->columns(array_filter([
                TextColumn::make('domain')->searchable()->weight('bold')
                    ->url(fn ($record) => $record->live_url, true)
                    ->description(fn ($record) => ClientSite::PLATFORMS[$record->platform] ?? null),
                $withOwner ? TextColumn::make('client.name')->label('Client')->searchable()->sortable() : null,
                TextColumn::make('hosting_provider')->label('Hosting')->placeholder('—')->toggleable(),
                TextColumn::make('email_provider')->label('Email')->formatStateUsing(fn ($state) => ClientSite::EMAIL_PROVIDERS[$state] ?? $state)->placeholder('—')->toggleable(),
                TextColumn::make('uptime_status')->label('Status')->badge()
                    ->formatStateUsing(fn ($state, $record) => ! $record->monitor ? 'Not monitored' : ['up' => 'Online', 'down' => 'DOWN', 'unknown' => 'Not checked'][$state] ?? $state)
                    ->color(fn ($state, $record) => ! $record->monitor ? 'gray' : ['up' => 'success', 'down' => 'danger'][$state] ?? 'gray')
                    ->description(fn ($record) => $record->last_checked_at ? $record->last_checked_at->diffForHumans().($record->last_response_ms ? ' · '.$record->last_response_ms.' ms' : '') : null),
                TextColumn::make('ssl_expires_on')->label('SSL expires')->date('d M Y')->sortable()->placeholder('—')
                    ->color(fn ($state) => $state && $state->lte(today()->addDays(14)) ? 'danger' : null),
            ]))
            ->filters([
                SelectFilter::make('uptime_status')->label('Status')->options(['up' => 'Online', 'down' => 'Down', 'unknown' => 'Not checked']),
                SelectFilter::make('platform')->options(ClientSite::PLATFORMS),
            ])
            ->recordActions([
                Action::make('check')->label('Check now')->icon('heroicon-o-arrow-path')->color('gray')->iconButton()->tooltip('Check now')
                    ->action(function ($record) {
                        SiteMonitor::check($record, alert: false);
                        SiteMonitor::checkSsl($record);
                        $record->refresh();
                        Notification::make()->title($record->domain.': '.($record->uptime_status === 'up' ? 'online' : 'NOT reachable'))
                            ->{$record->uptime_status === 'up' ? 'success' : 'danger'}()->send();
                    }),
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        $n = ClientSite::where('monitor', true)->where('uptime_status', 'down')->count();

        return $n ? $n.' down' : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageClientSites::route('/')];
    }
}
