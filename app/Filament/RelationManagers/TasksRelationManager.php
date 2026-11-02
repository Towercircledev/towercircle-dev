<?php

namespace App\Filament\RelationManagers;

use App\Filament\Resources\TaskResource;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    protected static string|\BackedEnum|null $icon = 'heroicon-o-check-circle';

    public function form(Schema $schema): Schema
    {
        return TaskResource::form($schema, withOwner: false);
    }

    public function table(Table $table): Table
    {
        return TaskResource::table($table, withOwner: false)
            ->headerActions([CreateAction::make()->label('Add task')->mutateDataUsing(fn (array $d) => $d + ['assigned_to' => auth()->id()])])
            ->recordActions([EditAction::make()->iconButton(), DeleteAction::make()->iconButton()]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
