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
                TextColumn::make('members')
                    ->label('Members')
                    ->html()
                    ->getStateUsing(
                        fn($record) =>
                        $record->familyMembers
                            ->map(
                                fn($fm) =>
                                $fm->person
                                    ? "{$fm->person->first_name} {$fm->person->last_name} ({$fm->role->value})"
                                    : '[Unknown Person]'
                            )
                            ->implode('<br>')
                    )
                    ->wrap(),
                // TextColumn::make('family_contacts')
                //     ->label('Family Contacts')
                //     ->html()
                //     ->wrap(),
                TextColumn::make('organizations.name')
                    ->label('Organizations')
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                // TextColumn::make('organization_contacts')
                //     ->label('Organization Contacts')
                //     ->html()
                //     ->wrap(),
                TextColumn::make('owned_plots_summary')
                    ->label('Owned Plots')
                    ->html()
                    ->formatStateUsing(function ($state, $record) {
                        $rights = $record->burialRights->unique('plot_id'); // use loaded relation

                        if ($rights->isEmpty()) {
                            return '<span class="text-gray-400">—</span>';
                        }

                        $items = $rights->map(function ($right) {
                            $section = $right->plot?->lot?->section?->name ?? 'Unknown Section';
                            $lotNum  = $right->plot?->lot?->lot_number ?? '?';
                            $lotLet  = $right->plot?->lot?->lot_letter ?? '';
                            $plotNum = $right->plot?->plot_number ?? '?';

                            return "<li>{$section} - {$lotNum}{$lotLet} - Plot {$plotNum}</li>";
                        });

                        return '<ul class="list-disc list-inside space-y-1">' . $items->implode('') . '</ul>';
                    })
                    ->wrap(),
                // TextColumn::make('interments_summary')
                //     ->label('Interments')
                //     ->html()
                //     ->wrap(),
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
