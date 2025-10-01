<?php

namespace App\Filament\Resources\Lots\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LotForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('internal_cemetery_id')
                    ->required()
                    ->numeric(),
                Select::make('section_id')
                    ->relationship('section', 'name')
                    ->required(),
                TextInput::make('lot_number'),
                TextInput::make('lot_letter'),
                TextInput::make('grid_reference')
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
