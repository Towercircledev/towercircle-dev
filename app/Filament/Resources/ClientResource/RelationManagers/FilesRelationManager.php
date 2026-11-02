<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

/** Agreements, quotes, logos… stored privately (not reachable from the website). */
class FilesRelationManager extends RelationManager
{
    protected static string $relationship = 'files';

    protected static string|\BackedEnum|null $icon = 'heroicon-o-paper-clip';

    public function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            TextInput::make('title')->required()->maxLength(150)->placeholder('e.g. Signed agreement, Logo files'),
            FileUpload::make('path')->label('File')->required()->disk('local')->directory('client-files')
                ->maxSize(20480)->storeFileNamesIn('original_name')
                ->helperText('PDF, Word, Excel, images or ZIP, up to 20 MB. Stored privately.'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')->weight('bold')->description(fn ($record) => $record->original_name),
                TextColumn::make('created_at')->label('Added')->date('d M Y'),
            ])
            ->headerActions([CreateAction::make()->label('Upload file')])
            ->recordActions([
                Action::make('download')->icon('heroicon-o-arrow-down-tray')->iconButton()
                    ->action(fn ($record) => Storage::disk('local')->download($record->path, $record->original_name ?: basename($record->path))),
                DeleteAction::make()->iconButton(),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
