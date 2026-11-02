<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Support\Fields;
use App\Models\Project;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Projects (portfolio)';

    public const CATEGORIES = ['Shopify' => 'Shopify store', 'Website' => 'Website', 'Web app' => 'Web app', 'E-commerce' => 'E-commerce', 'Branding' => 'Branding'];

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(3)->components([
            Group::make()->columnSpan(2)->schema([
                Section::make()->columns(2)->schema([
                    Fields::title(),
                    Fields::slug('work'),
                    TextInput::make('client')->maxLength(100),
                    Select::make('category')->options(self::CATEGORIES)->searchable()
                        ->createOptionForm([TextInput::make('name')->required()])
                        ->createOptionUsing(fn ($data) => $data['name']),
                    TextInput::make('live_url')->label('Live website link')->url()->maxLength(300),
                    TextInput::make('location')->maxLength(100)->placeholder('e.g. Hyderabad'),
                    DatePicker::make('completed_on'),
                    Textarea::make('summary')->rows(2)->maxLength(300)->columnSpanFull()->helperText('One or two lines shown on the portfolio card'),
                ]),
                Section::make('Case study')->schema([
                    RichEditor::make('body')->hiddenLabel()->fileAttachmentsDisk('public')->fileAttachmentsDirectory('projects'),
                    Repeater::make('highlights')->label('Highlights / results')->simple(TextInput::make('item')->required()->maxLength(150))
                        ->defaultItems(0)->addActionLabel('Add highlight'),
                ]),
                Fields::seo(),
            ]),
            Group::make()->columnSpan(1)->schema([
                Section::make('Images')->schema([
                    Fields::image('cover', 'projects', 'Cover screenshot')->required()->imageEditor(),
                    Fields::image('gallery', 'projects', 'More screenshots')->multiple()->reorderable()->maxFiles(8),
                ]),
                Fields::visibility(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->reorderable('sort')->defaultSort('sort')
            ->columns([
                ImageColumn::make('cover')->disk('public')->imageHeight(48),
                TextColumn::make('title')->searchable()->weight('bold')->description(fn ($record) => $record->client),
                TextColumn::make('category')->badge()->placeholder('—'),
                ToggleColumn::make('is_featured')->label('Homepage'),
                ToggleColumn::make('is_visible')->label('Visible'),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
