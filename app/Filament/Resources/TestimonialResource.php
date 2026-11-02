<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Filament\Support\Fields;
use App\Models\Testimonial;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-star';

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Client reviews';

    protected static ?string $modelLabel = 'review';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            TextInput::make('name')->required()->maxLength(80),
            TextInput::make('company')->label('Business / role')->maxLength(100),
            Textarea::make('quote')->label('Review')->required()->rows(4)->maxLength(600)->columnSpanFull()
                ->helperText('Use the client\'s real words, e.g. copied from your Google reviews.'),
            Select::make('rating')->options([5 => '★★★★★', 4 => '★★★★'])->default(5),
            Fields::image('photo', 'reviews', 'Photo or logo (optional)')->avatar(),
            Toggle::make('is_visible')->label('Show on website')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->reorderable('sort')->defaultSort('sort')
            ->columns([
                TextColumn::make('name')->weight('bold')->description(fn ($record) => $record->company),
                TextColumn::make('quote')->limit(80)->wrap(),
                ToggleColumn::make('is_visible')->label('Visible'),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageTestimonials::route('/')];
    }
}
