<?php

namespace App\Filament\Pages;

use App\Support\DonationMetrics;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class DonationDashboard extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.donation-dashboard';

    protected function getViewData(): array
    {
        return [
            'totals' => DonationMetrics::totals(),
            'localities' => DonationMetrics::localitySummary(),
            'lakous' => DonationMetrics::lakouSummary(),
        ];
    }
}
