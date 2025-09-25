<?php

namespace App\Filament\Resources\ExternalCemeteries\Pages;

use App\Filament\Resources\ExternalCemeteries\ExternalCemeteryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListExternalCemeteries extends ListRecords
{
    protected static string $resource = ExternalCemeteryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
