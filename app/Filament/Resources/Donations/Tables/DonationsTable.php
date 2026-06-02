<?php

namespace App\Filament\Resources\Donations\Tables;

use App\Models\Donation;
use App\Models\Lakou;
use App\Models\Locality;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class DonationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('collection.name')
                    ->label('Collection')
                    ->sortable()
                    ->toggleable(),
                TextInputColumn::make('donor_name')
                    ->label('Donor')
                    ->rules(['required', 'max:255'])
                    ->searchable()
                    ->sortable(),
                TextInputColumn::make('lakou')
                    ->label('Lakou')
                    ->rules(['nullable', 'max:255'])
                    ->searchable()
                    ->sortable(),
                TextInputColumn::make('lokalite')
                    ->label('Lokalite')
                    ->rules(['nullable', 'max:255'])
                    ->searchable()
                    ->sortable(),
                TextInputColumn::make('amount')
                    ->label('Amount')
                    ->type('number')
                    ->step('0.01')
                    ->prefix('$')
                    ->rules(['required', 'numeric', 'min:0'])
                    ->sortable()
                    ->alignEnd(),
                ToggleColumn::make('is_paid')
                    ->label('Paid')
                    ->sortable(),
                TextInputColumn::make('notes')
                    ->rules(['nullable', 'max:1000'])
                    ->toggleable(),
            ])
            ->filters([
                TernaryFilter::make('is_paid')
                    ->label('Paid Status')
                    ->placeholder('All donations')
                    ->trueLabel('Paid only')
                    ->falseLabel('Unpaid only'),
                SelectFilter::make('lokalite')
                    ->label('Lokalite')
                    ->options(fn () => Locality::query()->orderBy('name')->pluck('name', 'name')->all())
                    ->searchable(),
                SelectFilter::make('lakou')
                    ->label('Lakou')
                    ->options(fn () => Lakou::query()->orderBy('name')->pluck('name', 'name')->all())
                    ->searchable(),
            ])
            ->headerActions([
                Action::make('export')
                    ->label('Excel Export')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(route('donations.export')),
                Action::make('print_report')
                    ->label('Print Report')
                    ->icon('heroicon-o-printer')
                    ->url(route('reports.print'))
                    ->openUrlInNewTab(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->recordClasses(fn (Donation $record): string => $record->is_paid
                ? 'bg-green-50 dark:bg-green-950/30'
                : 'bg-rose-50 dark:bg-rose-950/30');
    }
}
