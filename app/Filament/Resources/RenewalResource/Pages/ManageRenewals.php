<?php

namespace App\Filament\Resources\RenewalResource\Pages;

use App\Filament\Resources\RenewalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageRenewals extends ManageRecords
{
    protected static string $resource = RenewalResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
