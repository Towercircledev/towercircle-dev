<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use App\Filament\Resources\RenewalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class RenewalsRelationManager extends RelationManager
{
    protected static string $relationship = 'renewals';

    protected static string|\BackedEnum|null $icon = 'heroicon-o-arrow-path-rounded-square';

    public function form(Schema $schema): Schema
    {
        return RenewalResource::form($schema, withOwner: false);
    }

    public function table(Table $table): Table
    {
        return RenewalResource::table($table, withOwner: false)
            ->headerActions([CreateAction::make()->label('Add renewal')]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
