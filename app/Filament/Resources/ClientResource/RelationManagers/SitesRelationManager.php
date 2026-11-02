<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use App\Filament\Resources\ClientSiteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SitesRelationManager extends RelationManager
{
    protected static string $relationship = 'sites';

    protected static ?string $title = 'Websites';

    protected static string|\BackedEnum|null $icon = 'heroicon-o-globe-alt';

    public function form(Schema $schema): Schema
    {
        return ClientSiteResource::form($schema, withOwner: false);
    }

    public function table(Table $table): Table
    {
        return ClientSiteResource::table($table, withOwner: false)
            ->headerActions([CreateAction::make()->label('Add website')]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
