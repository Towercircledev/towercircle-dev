<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Support\Fields;
use App\Models\Service;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
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

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(3)->components([
            Group::make()->columnSpan(2)->schema([
                Section::make()->columns(2)->schema([
                    Fields::title(),
                    Fields::slug('services'),
                    Textarea::make('short_description')->label('Short summary')->rows(2)->maxLength(300)->columnSpanFull()
                        ->helperText('Shown on service cards'),
                    TextInput::make('price_from')->label('Starting price')->numeric()->prefix('₹')
                        ->helperText('Shown as "Starting from ₹…". Leave empty to hide.'),
                    TextInput::make('price_note')->maxLength(100)->placeholder('e.g. one-time, incl. GST'),
                ]),
                Section::make('Page content')->schema([
                    RichEditor::make('body')->hiddenLabel()->fileAttachmentsDisk('public')->fileAttachmentsDirectory('services'),
                    Repeater::make('features')->label('What\'s included')->simple(TextInput::make('item')->required()->maxLength(150))
                        ->addActionLabel('Add item')->reorderable()->defaultItems(0),
                ]),
                Section::make('Questions & answers')->description('Shown on this service page and given to Google as FAQ data.')->collapsed()->schema([
                    Repeater::make('faqs')->hiddenLabel()->schema([
                        TextInput::make('q')->label('Question')->required(),
                        Textarea::make('a')->label('Answer')->rows(3)->required(),
                    ])->itemLabel(fn (array $state) => $state['q'] ?? null)->collapsible()->defaultItems(0)->addActionLabel('Add question'),
                ]),
                Fields::seo(),
            ]),
            Group::make()->columnSpan(1)->schema([
                Section::make('Icon')->schema([Fields::image('icon', 'services', 'Icon')->imagePreviewHeight('80')]),
                Fields::visibility(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->reorderable('sort')->defaultSort('sort')
            ->columns([
                ImageColumn::make('icon')->disk('public')->square()->imageHeight(36),
                TextColumn::make('title')->searchable()->weight('bold')->description(fn ($record) => '/services/'.$record->slug),
                TextColumn::make('price_from')->label('From')->money('INR', 0)->placeholder('—'),
                ToggleColumn::make('is_featured')->label('Homepage'),
                ToggleColumn::make('is_visible')->label('Visible'),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
