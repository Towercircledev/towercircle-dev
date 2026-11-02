<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LogoResource\Pages;
use App\Filament\Support\Fields;
use App\Models\Logo;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LogoResource extends Resource
{
    protected static ?string $model = Logo::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Logos (clients & platforms)';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            TextInput::make('name')->required()->maxLength(80)->helperText('Used as the image description for Google'),
            Select::make('group')->options(Logo::GROUPS)->required()->default('client')
                ->helperText('Clients show under "Trusted by", platforms under "Platforms we work with"'),
            Fields::image('image', 'logos', 'Logo')->required()->columnSpanFull()
                ->helperText('PNG or SVG with a transparent background works best. Shown about 48 px tall.'),
            TextInput::make('url')->label('Link (optional)')->url(),
            Toggle::make('is_visible')->label('Show on website')->default(true)->inline(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->reorderable('sort')->defaultSort('sort')
            ->columns([
                ImageColumn::make('image')->disk('public')->imageHeight(36),
                TextColumn::make('name')->weight('bold'),
                TextColumn::make('group')->badge()->formatStateUsing(fn ($state) => Logo::GROUPS[$state] ?? $state),
                ToggleColumn::make('is_visible')->label('Visible'),
            ])
            ->filters([SelectFilter::make('group')->options(Logo::GROUPS)])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageLogos::route('/')];
    }
}
