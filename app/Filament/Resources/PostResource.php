<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Support\Fields;
use App\Models\Post;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-newspaper';

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Blog posts';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(3)->components([
            Group::make()->columnSpan(2)->schema([
                Section::make()->columns(2)->schema([
                    Fields::title(),
                    Fields::slug('blog'),
                    Textarea::make('excerpt')->label('Summary')->rows(2)->maxLength(300)->columnSpanFull(),
                    RichEditor::make('body')->required()->columnSpanFull()
                        ->fileAttachmentsDisk('public')->fileAttachmentsDirectory('blog'),
                ]),
                Fields::seo(),
            ]),
            Group::make()->columnSpan(1)->schema([
                Section::make('Publishing')->schema([
                    Toggle::make('is_published')->label('Published'),
                    DateTimePicker::make('published_at')->label('Publish date')->default(now())->native(false),
                    TextInput::make('author')->default(fn () => auth()->user()?->name),
                    TagsInput::make('tags'),
                ]),
                Section::make('Cover')->schema([Fields::image('cover', 'blog', 'Cover image')]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->defaultSort('published_at', 'desc')
            ->columns([
                ImageColumn::make('cover')->disk('public')->imageHeight(40),
                TextColumn::make('title')->searchable()->weight('bold')->wrap(),
                TextColumn::make('published_at')->date('d M Y')->sortable(),
                ToggleColumn::make('is_published')->label('Published'),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
