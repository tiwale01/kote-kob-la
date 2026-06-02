<?php

namespace App\Console\Commands;

use App\Models\Donation;
use Illuminate\Console\Command;

class VerifyDonationSeed extends Command
{
    protected $signature = 'donations:verify-seed';

    protected $description = 'Verify the seeded donation totals.';

    public function handle(): int
    {
        $totals = [
            'Total Paid' => (float) Donation::query()->where('is_paid', true)->sum('amount'),
            'Total Unpaid' => (float) Donation::query()->where('is_paid', false)->sum('amount'),
            'Grand Total' => (float) Donation::query()->sum('amount'),
            'Donation Count' => Donation::query()->count(),
        ];

        $trouCaiman = Donation::query()
            ->where('lokalite', 'Trou-Caïman')
            ->selectRaw('count(*) as donation_count, sum(amount) as total_amount, sum(case when is_paid = 1 then 1 else 0 end) as paid_count')
            ->first();

        $vipTcho = Donation::query()
            ->where('donor_name', 'VIP Tcho')
            ->first(['donor_name', 'lakou', 'lokalite', 'amount', 'is_paid']);

        foreach ($totals as $label => $value) {
            $this->line("{$label}: {$value}");
        }

        $this->line("Trou-Caïman total: {$trouCaiman->total_amount}");
        $this->line("Trou-Caïman paid donations: {$trouCaiman->paid_count}");
        $this->line("VIP Tcho: {$vipTcho->lakou}, {$vipTcho->lokalite}, {$vipTcho->amount}, " . ($vipTcho->is_paid ? 'paid' : 'unpaid'));

        return self::SUCCESS;
    }
}
