<?php

namespace App\Filament\Resources\ClientSiteResource\Pages;

use App\Filament\Resources\ClientSiteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageClientSites extends ManageRecords
{
    protected static string $resource = ClientSiteResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
