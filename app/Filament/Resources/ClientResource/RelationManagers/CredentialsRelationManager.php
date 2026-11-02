<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use App\Models\Credential;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * Saved logins for this client. Admin-only. Passwords are encrypted in the database,
 * never shown in the list, and every "Show" is recorded with who and when.
 */
class CredentialsRelationManager extends RelationManager
{
    protected static string $relationship = 'credentials';

    protected static ?string $title = 'Passwords';

    protected static string|\BackedEnum|null $icon = 'heroicon-o-key';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return (bool) auth()->user()?->isAdmin();
    }

    public function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            Select::make('type')->options(Credential::TYPES)->required()->default('website_admin'),
            Select::make('client_site_id')->label('Website')->options(fn () => $this->getOwnerRecord()->sites()->pluck('domain', 'id'))->placeholder('—'),
            TextInput::make('label')->placeholder('e.g. WordPress admin, info@ mailbox')->maxLength(100),
            TextInput::make('url')->label('Login link')->url(),
            TextInput::make('username')->label('Username / email')->autocomplete('off'),
            TextInput::make('password')->password()->revealable()->autocomplete('new-password')
                ->dehydrated(fn ($state) => filled($state))
                ->helperText(fn (string $operation) => $operation === 'edit' ? 'Leave empty to keep the saved password' : 'Stored encrypted'),
            Textarea::make('notes')->rows(2)->columnSpanFull()->placeholder('2FA on owner\'s phone, security question, etc.'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('type')
            ->columns([
                TextColumn::make('label')->weight('bold')
                    ->state(fn (Credential $record) => $record->label ?: Credential::TYPES[$record->type] ?? $record->type)
                    ->description(fn (Credential $record) => trim((Credential::TYPES[$record->type] ?? '').($record->site ? ' · '.$record->site->domain : ''), ' ·')),
                TextColumn::make('username')->copyable()->copyMessage('Username copied')->placeholder('—')->icon('heroicon-o-document-duplicate')->iconPosition('after'),
                TextColumn::make('pw')->label('Password')->state('••••••••'),
                TextColumn::make('url')->label('Login')->url(fn ($state) => $state, true)->limit(30)->placeholder('—')->color('primary'),
                TextColumn::make('last_view')->label('Last viewed')
                    ->state(fn (Credential $record) => ($v = $record->views()->with('user')->first()) ? $v->created_at->diffForHumans().' by '.($v->user?->name ?? '?') : '—')
                    ->color('gray')->toggleable(),
            ])
            ->headerActions([CreateAction::make()->label('Add login')])
            ->recordActions([
                Action::make('show')->label('Show / copy')->icon('heroicon-o-eye')->color('primary')
                    ->modalHeading(fn (Credential $record) => $record->label ?: (Credential::TYPES[$record->type] ?? 'Login'))
                    ->modalDescription('This view has been recorded.')
                    ->fillForm(function (Credential $record) {
                        $record->logView('view');

                        return ['u' => $record->username, 'p' => $record->password, 'l' => $record->url, 'n' => $record->notes];
                    })
                    ->schema([
                        TextInput::make('l')->label('Login link')->readOnly()->copyable()->visible(fn ($state) => filled($state)),
                        TextInput::make('u')->label('Username')->readOnly()->copyable(),
                        TextInput::make('p')->label('Password')->readOnly()->copyable(),
                        Textarea::make('n')->label('Notes')->readOnly()->visible(fn ($state) => filled($state)),
                    ])
                    ->modalSubmitAction(false)->modalCancelActionLabel('Close'),
                Action::make('log')->label('')->icon('heroicon-o-clock')->color('gray')->tooltip('Who viewed this')
                    ->modalHeading('View history')
                    ->modalContent(fn (Credential $record) => view('filament.credential-log', ['views' => $record->views()->with('user')->take(30)->get()]))
                    ->modalSubmitAction(false)->modalCancelActionLabel('Close'),
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
