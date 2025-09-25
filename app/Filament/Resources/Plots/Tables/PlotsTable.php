<?php

namespace App\Filament\Resources\Plots\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class PlotsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lot.section.cemetery.name')
                    ->label('Cemetery')
                    ->sortable()
                    ->searchable(),


                TextColumn::make('lot.section.name')
                    ->label('Section')
                    ->sortable()
                    ->searchable(),


                TextColumn::make('lot.lot_label')
                    ->label('Lot #')
                    ->sortable()
                    ->searchable(),


                TextColumn::make('plot_number')
                    ->label('Plot #')
                    ->sortable()
                    ->searchable(),


                TextColumn::make('max_traditional_burials')
                    ->label('Max Trad.')
                    ->sortable(),


                TextColumn::make('max_cremation_burials')
                    ->label('Max Crem.')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
