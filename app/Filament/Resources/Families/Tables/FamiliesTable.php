<?php

namespace App\Filament\Resources\Families\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FamiliesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Family')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                TextColumn::make('members_with_roles')
                    ->label('Members')
                    ->html()
                    ->wrap(),
                TextColumn::make('family_contacts')
                    ->label('Family Contacts')
                    ->html()
                    ->wrap(),
                TextColumn::make('organizations.name')
                    ->label('Organizations')
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                TextColumn::make('organization_contacts')
                    ->label('Organization Contacts')
                    ->html()
                    ->wrap(),
                TextColumn::make('owned_plots_summary')
                    ->label('Owned Plots')
                    ->html()
                    ->wrap(),
                TextColumn::make('interments_summary')
                    ->label('Interments')
                    ->html()
                    ->wrap(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id')
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
