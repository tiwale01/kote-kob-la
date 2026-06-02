<x-filament-panels::page>
    <style>
        .dc-dashboard { display: grid; gap: 18px; }
        .dc-metrics { display: grid; gap: 12px; grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .dc-card {
            background: #ffffff;
            border: 1px solid #d9dee8;
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, .06);
            padding: 16px;
        }
        .dc-label { color: #64748b; font-size: 12px; font-weight: 700; letter-spacing: .02em; text-transform: uppercase; }
        .dc-value { color: #0f172a; font-size: 26px; font-weight: 800; margin-top: 6px; }
        .dc-value.paid { color: #15803d; }
        .dc-value.unpaid { color: #be123c; }
        .dc-grid { display: grid; gap: 16px; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); }
        .dc-panel {
            background: #ffffff;
            border: 1px solid #d9dee8;
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, .06);
            overflow: hidden;
        }
        .dc-panel-title {
            align-items: center;
            background: #f8fafc;
            border-bottom: 1px solid #d9dee8;
            display: flex;
            font-size: 15px;
            font-weight: 800;
            justify-content: space-between;
            padding: 12px 14px;
        }
        .dc-table { border-collapse: collapse; width: 100%; }
        .dc-table th {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            color: #64748b;
            font-size: 11px;
            padding: 9px 12px;
            text-align: left;
            text-transform: uppercase;
        }
        .dc-table td { border-bottom: 1px solid #eef2f7; padding: 9px 12px; }
        .dc-table tr:last-child td { border-bottom: 0; }
        .dc-right { text-align: right !important; }
        .dc-name { color: #0f172a; font-weight: 700; }
        .dc-bar {
            background: #e2e8f0;
            border-radius: 999px;
            height: 7px;
            margin-top: 5px;
            overflow: hidden;
        }
        .dc-bar span { background: #2563eb; display: block; height: 100%; }
        @media (max-width: 900px) {
            .dc-metrics, .dc-grid { grid-template-columns: 1fr; }
        }
    </style>

    <div class="dc-dashboard">
        <section class="dc-metrics">
            <div class="dc-card">
                <div class="dc-label">Total Paid</div>
                <div class="dc-value paid">${{ number_format($totals['total_paid'], 2) }}</div>
            </div>
            <div class="dc-card">
                <div class="dc-label">Total Unpaid</div>
                <div class="dc-value unpaid">${{ number_format($totals['total_unpaid'], 2) }}</div>
            </div>
            <div class="dc-card">
                <div class="dc-label">Grand Total</div>
                <div class="dc-value">${{ number_format($totals['grand_total'], 2) }}</div>
            </div>
            <div class="dc-card">
                <div class="dc-label">Donation Count</div>
                <div class="dc-value">{{ number_format($totals['donation_count']) }}</div>
            </div>
        </section>

        <section class="dc-grid">
            <div class="dc-panel">
                <div class="dc-panel-title">
                    <span>Lokalite Summary</span>
                    <span>{{ $localities->count() }} groups</span>
                </div>
                <table class="dc-table">
                    <thead>
                        <tr>
                            <th>Lokalite</th>
                            <th class="dc-right">Total</th>
                            <th class="dc-right">Percent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($localities as $row)
                            <tr>
                                <td>
                                    <div class="dc-name">{{ $row['name'] }} ({{ $row['donation_count'] }})</div>
                                    <div class="dc-bar"><span style="width: {{ min(100, $row['percentage']) }}%"></span></div>
                                </td>
                                <td class="dc-right">${{ number_format($row['total_amount'], 2) }}</td>
                                <td class="dc-right">{{ number_format($row['percentage'], 1) }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="dc-panel">
                <div class="dc-panel-title">
                    <span>Lakou Summary</span>
                    <span>{{ $lakous->count() }} groups</span>
                </div>
                <table class="dc-table">
                    <thead>
                        <tr>
                            <th>Lakou</th>
                            <th class="dc-right">Total</th>
                            <th class="dc-right">Percent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lakous as $row)
                            <tr>
                                <td>
                                    <div class="dc-name">{{ $row['name'] }} ({{ $row['donation_count'] }})</div>
                                    <div class="dc-bar"><span style="width: {{ min(100, $row['percentage']) }}%"></span></div>
                                </td>
                                <td class="dc-right">${{ number_format($row['total_amount'], 2) }}</td>
                                <td class="dc-right">{{ number_format($row['percentage'], 1) }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-filament-panels::page>
