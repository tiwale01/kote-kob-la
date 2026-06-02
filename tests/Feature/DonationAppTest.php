<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonationAppTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeded_totals_match_spreadsheet_starting_values(): void
    {
        $this->seed();

        $this->assertDatabaseCount('donations', 84);

        $this->assertSame('9870', (string) Donation::query()->where('is_paid', true)->sum('amount'));
        $this->assertSame('770', (string) Donation::query()->where('is_paid', false)->sum('amount'));
        $this->assertSame('10640', (string) Donation::query()->sum('amount'));
    }

    public function test_authenticated_dashboard_and_report_render_database_totals(): void
    {
        $this->seed();

        $user = User::query()->where('email', 'admin@example.com')->firstOrFail();

        $this->actingAs($user)
            ->get('/admin/donation-dashboard')
            ->assertOk()
            ->assertSee('Total Paid')
            ->assertSee('9,870.00')
            ->assertSee('10,640.00')
            ->assertSee('Trou-Caïman');

        $this->get('/reports/print')
            ->assertOk()
            ->assertSee('VIP Tcho')
            ->assertSee('Madan Blanc')
            ->assertSee('Debas');
    }
}
