<?php

namespace App\Filament\Resources\ExternalCemeteries\Pages;

use App\Filament\Resources\ExternalCemeteries\ExternalCemeteryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditExternalCemetery extends EditRecord
{
    protected static string $resource = ExternalCemeteryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
