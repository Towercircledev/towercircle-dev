<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use App\Filament\Resources\PaymentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $title = 'Payments';

    protected static string|\BackedEnum|null $icon = 'heroicon-o-banknotes';

    public function form(Schema $schema): Schema
    {
        return PaymentResource::form($schema, withOwner: false);
    }

    public function table(Table $table): Table
    {
        return PaymentResource::table($table, withOwner: false)
            ->headerActions([CreateAction::make()->label('Add payment / invoice')]);
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return (bool) auth()->user()?->isAdmin();
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
