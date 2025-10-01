<?php

namespace App\Filament\Resources\BurialRights\Pages;

use App\Filament\Resources\BurialRights\BurialRightResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBurialRight extends EditRecord
{
    protected static string $resource = BurialRightResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
