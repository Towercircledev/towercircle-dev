<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\AdminOnly;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
    use AdminOnly;

    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 9;

    protected static ?string $navigationLabel = 'Admin users';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            TextInput::make('name')->required()->maxLength(80),
            TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
            TextInput::make('phone')->tel(),
            Select::make('role')->options(User::ROLES)->required()->default('staff')
                ->helperText('Staff cannot see passwords, payments, settings or other users.'),
            TextInput::make('password')->password()->revealable()->minLength(10)
                ->required(fn (string $operation) => $operation === 'create')
                ->dehydrated(fn ($state) => filled($state))
                ->helperText(fn (string $operation) => $operation === 'edit' ? 'Leave empty to keep the current password' : 'At least 10 characters. They set up 2FA on first login.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->weight('bold')->description(fn ($record) => $record->email),
                TextColumn::make('role')->badge()->color(fn ($state) => $state === 'admin' ? 'primary' : 'gray'),
                IconColumn::make('two_factor')->label('2FA on')->boolean()
                    ->state(fn ($record) => filled($record->app_authentication_secret)),
                TextColumn::make('created_at')->label('Added')->date('d M Y'),
            ])
            ->recordActions([
                Action::make('reset2fa')->label('Reset 2FA')->icon('heroicon-o-key')->color('warning')
                    ->visible(fn ($record) => filled($record->app_authentication_secret))
                    ->requiresConfirmation()->modalDescription('Use this if they lost their phone. They will set up 2FA again on next login.')
                    ->action(function ($record) {
                        $record->forceFill(['app_authentication_secret' => null, 'app_authentication_recovery_codes' => null])->save();
                        Notification::make()->title('2FA reset')->success()->send();
                    }),
                EditAction::make(),
                DeleteAction::make()->hidden(fn ($record) => $record->id === auth()->id()),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageUsers::route('/')];
    }
}
