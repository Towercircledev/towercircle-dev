<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RedirectResource\Pages;
use App\Models\Redirect;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class RedirectResource extends Resource
{
    use \App\Filament\Concerns\AdminOnly;

    protected static ?string $model = Redirect::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-uturn-right';

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            TextInput::make('from')->label('Old address')->required()->maxLength(250)
                ->placeholder('/old-page-name')->helperText('Only the part after the domain, e.g. /web-design-services/')
                ->unique(ignoreRecord: true),
            TextInput::make('to')->label('Send visitors to')->required()->maxLength(250)
                ->placeholder('/services/website-design-development')->helperText('A path on this site or a full https:// link'),
            Select::make('code')->label('Type')->options([301 => 'Permanent (301) – use this', 302 => 'Temporary (302)'])->default(301)->required(),
            Toggle::make('is_active')->label('Active')->default(true)->inline(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->defaultSort('from')
            ->columns([
                TextColumn::make('from')->searchable()->weight('bold'),
                TextColumn::make('to')->searchable()->limit(50),
                TextColumn::make('code'),
                TextColumn::make('hits')->sortable()->description(fn ($record) => $record->last_hit_at?->diffForHumans()),
                ToggleColumn::make('is_active')->label('Active'),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageRedirects::route('/')];
    }
}
