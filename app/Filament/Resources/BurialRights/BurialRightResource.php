<?php

namespace App\Filament\Resources\BurialRights;

use App\Filament\Resources\BurialRights\Pages\CreateBurialRight;
use App\Filament\Resources\BurialRights\Pages\EditBurialRight;
use App\Filament\Resources\BurialRights\Pages\ListBurialRights;
use App\Filament\Resources\BurialRights\Schemas\BurialRightForm;
use App\Filament\Resources\BurialRights\Tables\BurialRightsTable;
use App\Models\BurialRight;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BurialRightResource extends Resource
{
    protected static ?string $model = BurialRight::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'display_label';

    public static function form(Schema $schema): Schema
    {
        return BurialRightForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BurialRightsTable::configure($table);
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
            'index' => ListBurialRights::route('/'),
            'create' => CreateBurialRight::route('/create'),
            'edit' => EditBurialRight::route('/{record}/edit'),
        ];
    }
}
