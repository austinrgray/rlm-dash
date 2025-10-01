<?php

namespace App\Filament\Resources\BurialRights\Schemas;

use App\Enums\BurialRightStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BurialRightForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('family_id')
                    ->relationship('family', 'id'),
                Select::make('organization_id')
                    ->relationship('organization', 'name'),
                Select::make('plot_id')
                    ->relationship('plot', 'id')
                    ->required(),
                Select::make('status')
                    ->options(BurialRightStatus::class)
                    ->default('active')
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
