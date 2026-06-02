<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Support\DonationMetrics;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PrintReportController extends Controller
{
    public function __invoke(Request $request): View
    {
        $sort = $request->query('sort', 'amount');
        $direction = $request->query('direction', 'desc') === 'asc' ? 'asc' : 'desc';

        $allowedSorts = [
            'donor_name',
            'lakou',
            'lokalite',
            'amount',
            'is_paid',
        ];

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'amount';
        }

        return view('reports.print', [
            'collection' => $request->user()?->isAdmin()
                ? Collection::query()->where('is_active', true)->orderBy('id')->first()
                : Collection::query()->where('user_id', $request->user()?->id)->where('is_active', true)->orderBy('id')->first(),
            'totals' => DonationMetrics::totals(),
            'localities' => DonationMetrics::localitySummary(),
            'lakous' => DonationMetrics::lakouSummary(),
            'sort' => $sort,
            'direction' => $direction,
            'donations' => DonationMetrics::donationQuery()
                ->with('collection')
                ->orderBy($sort, $direction)
                ->orderBy('id')
                ->get(),
        ]);
    }
}
