<?php

namespace App\Filament\Resources\LogoResource\Pages;

use App\Filament\Resources\LogoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageLogos extends ManageRecords
{
    protected static string $resource = LogoResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
