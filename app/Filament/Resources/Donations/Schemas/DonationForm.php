<?php

namespace App\Filament\Resources\Donations\Schemas;

use App\Models\Collection;
use App\Models\Lakou;
use App\Models\Locality;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DonationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('collection_id')
                    ->label('Collection')
                    ->options(fn () => Collection::query()->orderBy('name')->pluck('name', 'id')->all())
                    ->searchable()
                    ->required()
                    ->default(fn () => Collection::query()->where('is_active', true)->orderBy('id')->value('id')),
                TextInput::make('donor_name')
                    ->label('Donor Name')
                    ->required()
                    ->maxLength(255),
                Select::make('lakou')
                    ->label('Lakou')
                    ->options(fn () => Lakou::query()->orderBy('name')->pluck('name', 'name')->all())
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('New Lakou')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->createOptionUsing(fn (array $data): string => Lakou::query()->firstOrCreate([
                        'name' => trim($data['name']),
                    ])->name),
                Select::make('lokalite')
                    ->label('Lokalite')
                    ->options(fn () => Locality::query()->orderBy('name')->pluck('name', 'name')->all())
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('New Lokalite')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->createOptionUsing(fn (array $data): string => Locality::query()->firstOrCreate([
                        'name' => trim($data['name']),
                    ])->name),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('$'),
                Toggle::make('is_paid')
                    ->label('Paid')
                    ->default(false),
                Textarea::make('notes')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
