<?php

namespace App\Filament\Resources\ExternalCemeteries;

use App\Filament\Resources\ExternalCemeteries\Pages\CreateExternalCemetery;
use App\Filament\Resources\ExternalCemeteries\Pages\EditExternalCemetery;
use App\Filament\Resources\ExternalCemeteries\Pages\ListExternalCemeteries;
use App\Filament\Resources\ExternalCemeteries\Schemas\ExternalCemeteryForm;
use App\Filament\Resources\ExternalCemeteries\Tables\ExternalCemeteriesTable;
use App\Models\ExternalCemetery;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ExternalCemeteryResource extends Resource
{
    protected static ?string $model = ExternalCemetery::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ExternalCemeteryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExternalCemeteriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExternalCemeteries::route('/'),
            'create' => CreateExternalCemetery::route('/create'),
            'edit' => EditExternalCemetery::route('/{record}/edit'),
        ];
    }
}
