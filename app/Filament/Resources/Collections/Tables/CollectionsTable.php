<?php

namespace App\Filament\Resources\Collections\Tables;

use App\Models\Collection;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CollectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Owner')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('donations_count')
                    ->label('Donation Count')
                    ->counts('donations')
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->label('Grand Total')
                    ->state(fn (Collection $record): string => number_format((float) $record->donations()->sum('amount'), 2))
                    ->alignEnd(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
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
