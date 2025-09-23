<?php

namespace App\Filament\Resources\Families\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class FamilyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
