<?php

namespace App\Support;

use App\Models\Donation;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;

class DonationMetrics
{
    public static function totals(): array
    {
        $totalPaid = (float) Donation::query()->where('is_paid', true)->sum('amount');
        $totalUnpaid = (float) Donation::query()->where('is_paid', false)->sum('amount');
        $grandTotal = $totalPaid + $totalUnpaid;

        return [
            'total_paid' => $totalPaid,
            'total_unpaid' => $totalUnpaid,
            'grand_total' => $grandTotal,
            'donation_count' => Donation::query()->count(),
        ];
    }

    public static function localitySummary(): SupportCollection
    {
        return self::summaryFor('lokalite');
    }

    public static function lakouSummary(): SupportCollection
    {
        return self::summaryFor('lakou');
    }

    private static function summaryFor(string $column): SupportCollection
    {
        $grandTotal = (float) Donation::query()->sum('amount');

        return Donation::query()
            ->select([
                DB::raw("coalesce(nullif(trim({$column}), ''), 'Unspecified') as name"),
                DB::raw('count(*) as donation_count'),
                DB::raw('sum(amount) as total_amount'),
            ])
            ->groupBy('name')
            ->orderByDesc('total_amount')
            ->get()
            ->map(fn ($row) => [
                'name' => $row->name,
                'donation_count' => (int) $row->donation_count,
                'total_amount' => (float) $row->total_amount,
                'percentage' => $grandTotal > 0 ? ((float) $row->total_amount / $grandTotal) * 100 : 0,
            ]);
    }
}
