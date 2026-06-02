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
use Illuminate\Support\Facades\Auth;

class DonationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('collection_id')
                    ->label('Collection')
                    ->options(fn () => self::collectionOptions())
                    ->searchable()
                    ->required()
                    ->default(fn () => self::collectionQuery()->where('is_active', true)->orderBy('id')->value('id')),
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

    private static function collectionQuery()
    {
        $query = Collection::query()->orderBy('name');
        $user = Auth::user();

        if (! $user?->isAdmin()) {
            $query->where('user_id', $user?->id);
        }

        return $query;
    }

    private static function collectionOptions(): array
    {
        return self::collectionQuery()->pluck('name', 'id')->all();
    }
}
