<?php

namespace App\Filament\Resources\Lots\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Table;

class LotsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultGroup('section.name')
            ->columns([
                Split::make([
                    TextColumn::make('lot_label')
                        ->searchable(),
                    TextColumn::make('grid_reference')
                        ->searchable(),
                    TextColumn::make('created_at')
                        ->dateTime()
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                    TextColumn::make('updated_at')
                        ->dateTime()
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                ]),

                Panel::make(function ($record) {
                    if (! $record) {
                        return [];
                    }
                    return [
                        Split::make([
                            TextColumn::make('header_plot')->label('Plot #')->getStateUsing(fn() => 'Plot #')->weight('bold'),
                            TextColumn::make('header_owner')->label('Owner')->getStateUsing(fn() => 'Owner')->weight('bold'),
                            TextColumn::make('header_trad')->label('Trad')->getStateUsing(fn() => 'Trad')->weight('bold'),
                            TextColumn::make('header_crem')->label('Cremation')->getStateUsing(fn() => 'Cremation')->weight('bold'),
                            TextColumn::make('header_maus')->label('Mausoleum')->getStateUsing(fn() => 'Mausoleum')->weight('bold'),
                            TextColumn::make('header_col')->label('Columbarium')->getStateUsing(fn() => 'Columbarium')->weight('bold'),
                        ]),
                        ...$record->plots->map(function ($plot, $index) {
                            return Split::make([
                                TextColumn::make("plot_number_{$index}")
                                    ->getStateUsing(fn() => $plot->plot_number),

                                TextColumn::make("owner_{$index}")
                                    ->getStateUsing(fn() => $plot->current_owner?->name ?? '—'),

                                TextColumn::make("trad_{$index}")
                                    ->getStateUsing(fn() => "{$plot->traditional_burials}/{$plot->max_traditional_burials}"),

                                TextColumn::make("crem_{$index}")
                                    ->getStateUsing(fn() => "{$plot->cremation_burials}/{$plot->max_cremation_burials}"),

                                TextColumn::make("maus_{$index}")
                                    ->getStateUsing(fn() => "{$plot->mausoleum_entombments}/{$plot->max_mausoleum_entombments}"),

                                TextColumn::make("col_{$index}")
                                    ->getStateUsing(fn() => "{$plot->columbarium_entombments}/{$plot->max_columbarium_entombments}"),
                            ]);
                        })->toArray(),
                    ];
                })->collapsed(false),
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
