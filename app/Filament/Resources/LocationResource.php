<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocationResource\Pages;
use App\Filament\Support\Fields;
use App\Models\Location;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static ?int $navigationSort = 7;

    protected static ?string $navigationLabel = 'Location pages';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(3)->components([
            Group::make()->columnSpan(2)->schema([
                Section::make()->columns(2)->schema([
                    TextInput::make('city')->required()->maxLength(60)->live(onBlur: true)
                        ->afterStateUpdated(function (Set $set, ?string $state, $operation) {
                            if ($operation === 'create') {
                                $set('slug', Str::slug((string) $state));
                                $set('heading', 'Website Design & Development Company in '.$state);
                            }
                        }),
                    Fields::slug('in'),
                    TextInput::make('heading')->required()->maxLength(120)->columnSpanFull()
                        ->helperText('Use the words people search, e.g. "Website Design Company in Hyderabad"'),
                    Textarea::make('intro')->rows(3)->maxLength(400)->columnSpanFull(),
                ]),
                Section::make('Page content')->description('Write something specific to this city — areas you serve, local clients. Pages that only swap the city name can be ignored by Google.')
                    ->schema([RichEditor::make('body')->hiddenLabel()]),
                Section::make('Questions & answers')->collapsed()->schema([
                    Repeater::make('faqs')->hiddenLabel()->schema([
                        TextInput::make('q')->label('Question')->required(),
                        Textarea::make('a')->label('Answer')->rows(2)->required(),
                    ])->defaultItems(0)->addActionLabel('Add question')->itemLabel(fn (array $state) => $state['q'] ?? null)->collapsible(),
                ]),
                Fields::seo(),
            ]),
            Group::make()->columnSpan(1)->schema([Fields::visibility()]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->reorderable('sort')->defaultSort('sort')
            ->columns([
                TextColumn::make('city')->weight('bold')->searchable()->description(fn ($record) => '/in/'.$record->slug),
                TextColumn::make('heading')->limit(60),
                ToggleColumn::make('is_visible')->label('Visible'),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocations::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'edit' => Pages\EditLocation::route('/{record}/edit'),
        ];
    }
}
