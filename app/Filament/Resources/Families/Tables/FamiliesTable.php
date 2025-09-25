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
                    ->getStateUsing(
                        fn($record) =>
                        $record->familyMembers
                            ->map(
                                fn($fm) =>
                                $fm->person
                                    ? "{$fm->person->first_name} {$fm->person->last_name} ({$fm->role})"
                                    : '[Unknown Person]'
                            )
                            ->join(', ')
                    )
                    ->wrap(),
                TextColumn::make('contactCards')
                    ->label('Family Contacts')
                    ->html()
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->contactCards->isEmpty()) {
                            return '<span class="text-gray-400">—</span>';
                        }

                        return $record->contactCards
                            ->map(function ($card) {
                                $lines = [];
                                if ($card->phone) {
                                    $lines[] = "📞 {$card->phone}";
                                }
                                if ($card->email) {
                                    $lines[] = "✉️ {$card->email}";
                                }
                                return implode('<br>', $lines);
                            })
                            ->implode('<br>──────────────<br>'); // separator between multiple cards
                    })
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
                TextColumn::make('intermentRecords')
                    ->label('Interments')
                    ->formatStateUsing(
                        fn($record) =>
                        $record->intermentRecords->count() > 0
                            ? 'View (' . $record->intermentRecords->count() . ')'
                            : '-'
                    )
                    ->url(
                        fn($record) =>
                        $record->intermentRecords->count() > 0
                            ? route('filament.resources.interment-records.index', [
                                'tableFilters[family_id][value]' => $record->id,
                            ])
                            : null
                    )
                    ->openUrlInNewTab(),
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
