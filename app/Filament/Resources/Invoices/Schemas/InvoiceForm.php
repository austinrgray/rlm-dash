<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Enums\InvoiceStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('family_id')
                    ->relationship('family', 'id'),
                Select::make('organization_id')
                    ->relationship('organization', 'name'),
                Select::make('status')
                    ->options(InvoiceStatus::class)
                    ->default('open')
                    ->required(),
                DatePicker::make('due_date'),
            ]);
    }
}
