<x-filament-panels::page>
    <style>
        .rp-wrap { display: grid; gap: 18px; }
        .rp-hero {
            background: #ffffff;
            border: 1px solid #d9dee8;
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, .06);
            display: grid;
            gap: 16px;
            grid-template-columns: minmax(0, 1fr) auto;
            padding: 18px;
        }
        .rp-title { color: #0f172a; font-size: 18px; font-weight: 800; margin: 0 0 4px; }
        .rp-copy { color: #475569; margin: 0; }
        .rp-actions { align-items: center; display: flex; flex-wrap: wrap; gap: 8px; }
        .rp-btn {
            background: #1d4ed8;
            border: 1px solid #1d4ed8;
            border-radius: 6px;
            color: #ffffff !important;
            display: inline-flex;
            font-weight: 800;
            padding: 9px 12px;
            text-decoration: none !important;
        }
        .rp-btn.secondary {
            background: #ffffff;
            border-color: #cbd5e1;
            color: #1f2937 !important;
        }
        .rp-metrics { display: grid; gap: 12px; grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .rp-card {
            background: #ffffff;
            border: 1px solid #d9dee8;
            border-radius: 8px;
            padding: 14px;
        }
        .rp-label { color: #64748b; font-size: 12px; font-weight: 800; text-transform: uppercase; }
        .rp-value { color: #0f172a; font-size: 24px; font-weight: 900; margin-top: 5px; }
        .rp-grid { display: grid; gap: 16px; grid-template-columns: 1fr 1fr; }
        .rp-panel {
            background: #ffffff;
            border: 1px solid #d9dee8;
            border-radius: 8px;
            overflow: hidden;
        }
        .rp-panel h3 {
            background: #f8fafc;
            border-bottom: 1px solid #d9dee8;
            font-size: 15px;
            margin: 0;
            padding: 12px 14px;
        }
        .rp-sort { display: flex; flex-wrap: wrap; gap: 8px; padding: 14px; }
        .rp-sort a {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            color: #1f2937 !important;
            font-weight: 700;
            padding: 8px 10px;
            text-decoration: none !important;
        }
        @media (max-width: 900px) {
            .rp-hero, .rp-metrics, .rp-grid { grid-template-columns: 1fr; }
        }
    </style>

    <div class="rp-wrap">
        <section class="rp-hero">
            <div>
                <h2 class="rp-title">Donation Reports</h2>
                <p class="rp-copy">Open a spreadsheet-style report, sort it, print it, or export the donation records.</p>
            </div>
            <div class="rp-actions">
                <a class="rp-btn" href="{{ route('reports.print') }}" target="_blank">Open Report</a>
                <a class="rp-btn secondary" href="{{ route('donations.export') }}">Excel Export</a>
                <a class="rp-btn secondary" href="{{ route('filament.admin.resources.donations.index') }}">Enter Donations</a>
            </div>
        </section>

        <section class="rp-metrics">
            <div class="rp-card"><div class="rp-label">Total Paid</div><div class="rp-value">${{ number_format($totals['total_paid'], 2) }}</div></div>
            <div class="rp-card"><div class="rp-label">Total Unpaid</div><div class="rp-value">${{ number_format($totals['total_unpaid'], 2) }}</div></div>
            <div class="rp-card"><div class="rp-label">Grand Total</div><div class="rp-value">${{ number_format($totals['grand_total'], 2) }}</div></div>
            <div class="rp-card"><div class="rp-label">Donations</div><div class="rp-value">{{ number_format($totals['donation_count']) }}</div></div>
        </section>

        <section class="rp-grid">
            <div class="rp-panel">
                <h3>Quick Report Sorts</h3>
                <div class="rp-sort">
                    <a href="{{ route('reports.print', ['sort' => 'amount', 'direction' => 'desc']) }}" target="_blank">Montan high to low</a>
                    <a href="{{ route('reports.print', ['sort' => 'amount', 'direction' => 'asc']) }}" target="_blank">Montan low to high</a>
                    <a href="{{ route('reports.print', ['sort' => 'lokalite', 'direction' => 'asc']) }}" target="_blank">Lokalite A-Z</a>
                    <a href="{{ route('reports.print', ['sort' => 'lakou', 'direction' => 'asc']) }}" target="_blank">Lakou A-Z</a>
                    <a href="{{ route('reports.print', ['sort' => 'donor_name', 'direction' => 'asc']) }}" target="_blank">Non A-Z</a>
                    <a href="{{ route('reports.print', ['sort' => 'is_paid', 'direction' => 'asc']) }}" target="_blank">Unpaid first</a>
                </div>
            </div>

            <div class="rp-panel">
                <h3>Report Contents</h3>
                <div class="rp-sort">
                    <span>Lokalite groups: {{ $localities->count() }}</span>
                    <span>Lakou groups: {{ $lakous->count() }}</span>
                    <span>All totals are database totals.</span>
                </div>
            </div>
        </section>
    </div>
</x-filament-panels::page>
