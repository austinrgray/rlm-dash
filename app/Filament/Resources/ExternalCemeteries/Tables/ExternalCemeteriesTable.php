<?php

namespace App\Filament\Resources\ExternalCemeteries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExternalCemeteriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('primaryContactCard.phone')->label('Phone')
                    ->label('Phone')
                    ->sortable(false)
                    ->searchable(),
                TextColumn::make('primaryContactCard.email')->label('Email')
                    ->label('Email')
                    ->sortable(false)
                    ->searchable(),
                TextColumn::make('primaryContactCard.city')->label('City')
                    ->label('City')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('primaryContactCard.state')->label('State')
                    ->label('State')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
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
