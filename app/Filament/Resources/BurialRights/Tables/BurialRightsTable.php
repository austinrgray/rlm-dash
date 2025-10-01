<?php

namespace App\Filament\Resources\BurialRights\Tables;

use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BurialRightsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('owner.name')
                    ->label('Owner')
                    ->sortable()
                    ->searchable(),

                // TextColumn::make('section_name')
                //     ->label('Section')
                //     ->getStateUsing(fn($record) => $record->plot?->section?->name)
                //     ->sortable(query: function (Builder $query, string $direction) {
                //         $query->join('plots', 'plots.id', '=', 'burial_rights.plot_id')
                //             ->join('lots', 'lots.id', '=', 'plots.lot_id')
                //             ->join('sections', 'sections.id', '=', 'lots.section_id')
                //             ->orderBy('sections.name', $direction);
                //     }),

                TextColumn::make('plot.lot.lot_label')
                    ->label('Lot'),

                TextColumn::make('plot.plot_number')
                    ->label('Plot'),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'active',
                        'secondary' => 'transferred',
                        'danger' => 'void',
                    ]),
            ])
            ->defaultSort('id', 'desc')
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
