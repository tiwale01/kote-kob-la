<?php

namespace App\Filament\Pages;

use App\Support\DonationMetrics;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class Reports extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPrinter;

    protected static ?string $navigationLabel = 'Reports';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.reports';

    protected function getViewData(): array
    {
        return [
            'totals' => DonationMetrics::totals(),
            'localities' => DonationMetrics::localitySummary(),
            'lakous' => DonationMetrics::lakouSummary(),
        ];
    }
}
