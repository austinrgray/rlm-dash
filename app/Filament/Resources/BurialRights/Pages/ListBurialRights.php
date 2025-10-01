<?php

namespace App\Filament\Resources\BurialRights\Pages;

use App\Filament\Resources\BurialRights\BurialRightResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBurialRights extends ListRecords
{
    protected static string $resource = BurialRightResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
