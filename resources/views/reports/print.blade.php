@php
    $sortUrl = function (string $column) use ($sort, $direction) {
        $nextDirection = $sort === $column && $direction === 'asc' ? 'desc' : 'asc';

        return route('reports.print', ['sort' => $column, 'direction' => $nextDirection]);
    };

    $sortMark = fn (string $column) => $sort === $column ? ($direction === 'asc' ? ' ↑' : ' ↓') : '';
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Donation Report</title>
    <style>
        * { box-sizing: border-box; }
        html, body { min-height: 100%; }
        body {
            background: #f6f7f9;
            color: #111827;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 12px;
        }
        a { color: inherit; text-decoration: none; }
        .toolbar {
            align-items: center;
            display: flex;
            gap: 8px;
            justify-content: space-between;
            margin: 0 auto 10px;
            max-width: 1180px;
        }
        .toolbar-actions { display: flex; gap: 8px; }
        .btn {
            background: #1d4ed8;
            border: 1px solid #1d4ed8;
            border-radius: 5px;
            color: #ffffff;
            cursor: pointer;
            font: inherit;
            font-weight: 700;
            padding: 7px 10px;
        }
        .btn.secondary {
            background: #ffffff;
            color: #1f2937;
            border-color: #cbd5e1;
        }
        .hint { color: #475569; font-size: 12px; }
        .sheet {
            background: #ffffff;
            border: 1px solid #111827;
            display: grid;
            grid-template-columns: minmax(0, 2fr) minmax(330px, .95fr);
            margin: 0 auto;
            max-width: 1180px;
            min-height: 760px;
        }
        .left { border-right: 1px solid #111827; }
        .left,
        .right {
            background-image:
                linear-gradient(to right, rgba(15, 23, 42, .14) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(15, 23, 42, .14) 1px, transparent 1px);
            background-size: 84px 16px;
        }
        .title {
            background: #2f5bd4;
            color: #ffffff;
            font-size: 16px;
            font-weight: 700;
            line-height: 28px;
            text-align: center;
        }
        .spacer { height: 16px; }
        table { border-collapse: collapse; table-layout: fixed; width: 100%; }
        .totals td {
            background: #e8f0fb;
            border: 1px solid #cbd5e1;
            font-weight: 700;
            height: 24px;
            text-align: center;
        }
        .data th,
        .data td,
        .summary th,
        .summary td {
            border: 1px solid rgba(15, 23, 42, .22);
            height: 16px;
            overflow: hidden;
            padding: 1px 4px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .data th {
            background: #2f5bd4;
            border-color: #2f5bd4;
            color: #ffffff;
            font-weight: 700;
            text-align: center;
        }
        .data th a {
            color: #ffffff;
            display: block;
        }
        .paid td { background: #e8f5ee; }
        .unpaid td { background: #f8dfdf; }
        .amount, .percent, .count { text-align: right; }
        .paid-mark { text-align: center; }
        .right-pad { padding: 32px 10px 0 24px; }
        .summary-panel { margin-top: 22px; }
        .summary-panel.lakou { margin-top: 250px; }
        .summary th {
            background: #11823f;
            border-color: #11823f;
            color: #ffffff;
            font-weight: 700;
            text-align: center;
        }
        .summary td { background: #ffffff; }
        @page { margin: .25in; size: 11in 8.5in; }
        @media (max-width: 900px) {
            body { overflow-x: auto; }
            .toolbar, .sheet { min-width: 980px; }
        }
        @media print {
            body {
                background: #ffffff;
                font-size: 11px;
                padding: 0;
            }
            .toolbar { display: none; }
            .sheet {
                border: 1px solid #111827;
                grid-template-columns: minmax(0, 1.85fr) minmax(250px, .8fr);
                max-width: none;
                min-height: 0;
                width: 100%;
            }
            .title {
                font-size: 15px;
                line-height: 24px;
            }
            .spacer {
                height: 12px;
            }
            .totals td {
                height: 22px;
            }
            .data th,
            .data td,
            .summary th,
            .summary td {
                height: 15px;
                padding: 1px 3px;
            }
            .right-pad {
                padding: 28px 8px 0 18px;
            }
            .summary-panel {
                margin-top: 18px;
            }
            .summary-panel.lakou {
                margin-top: 230px;
            }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <div class="hint">Click a blue column header to sort the report.</div>
        <div class="toolbar-actions">
            <a class="btn secondary" href="{{ route('filament.admin.pages.reports') }}">Reports</a>
            <a class="btn secondary" href="{{ route('filament.admin.resources.donations.index') }}">Enter Donations</a>
            <button class="btn" onclick="window.print()">Print</button>
        </div>
    </div>

    <main class="sheet">
        <section class="left">
            <div class="title">{{ $collection?->name ?? 'Donation Collection' }}</div>
            <div class="spacer"></div>
            <table class="totals" aria-label="Totals">
                <tr>
                    <td>Total Paid</td>
                    <td>${{ number_format($totals['total_paid'], 0) }}</td>
                    <td>Total Unpaid</td>
                    <td>${{ number_format($totals['total_unpaid'], 0) }}</td>
                    <td>Total</td>
                    <td>${{ number_format($totals['grand_total'], 0) }}</td>
                </tr>
            </table>
            <div class="spacer"></div>
            <table class="data" aria-label="Donations">
                <colgroup>
                    <col style="width: 28%">
                    <col style="width: 15%">
                    <col style="width: 18%">
                    <col style="width: 12%">
                    <col style="width: 8%">
                    <col style="width: 19%">
                </colgroup>
                <thead>
                    <tr>
                        <th><a href="{{ $sortUrl('donor_name') }}">Non{{ $sortMark('donor_name') }}</a></th>
                        <th><a href="{{ $sortUrl('lakou') }}">Lakou{{ $sortMark('lakou') }}</a></th>
                        <th><a href="{{ $sortUrl('lokalite') }}">Lokalite{{ $sortMark('lokalite') }}</a></th>
                        <th><a href="{{ $sortUrl('amount') }}">Montan{{ $sortMark('amount') }}</a></th>
                        <th><a href="{{ $sortUrl('is_paid') }}">Paid?{{ $sortMark('is_paid') }}</a></th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($donations as $donation)
                        <tr class="{{ $donation->is_paid ? 'paid' : 'unpaid' }}">
                            <td>{{ $donation->donor_name }}</td>
                            <td>{{ $donation->lakou }}</td>
                            <td>{{ $donation->lokalite }}</td>
                            <td class="amount">${{ number_format((float) $donation->amount, 0) }}</td>
                            <td class="paid-mark">{{ $donation->is_paid ? 'P' : '' }}</td>
                            <td>{{ $donation->notes }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <aside class="right">
            <div class="right-pad">
                <table class="totals" aria-label="Donation count">
                    <tr>
                        <td>Donasyon</td>
                        <td class="count">{{ number_format($totals['donation_count']) }}</td>
                    </tr>
                </table>

                <div class="summary-panel">
                    <table class="summary" aria-label="Lokalite Summary">
                        <colgroup>
                            <col style="width: 50%">
                            <col style="width: 25%">
                            <col style="width: 25%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>Lokalite</th>
                                <th>Total</th>
                                <th>% of Overall</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($localities as $row)
                                <tr>
                                    <td>{{ $row['name'] }} ({{ $row['donation_count'] }})</td>
                                    <td class="amount">${{ number_format($row['total_amount'], 0) }}</td>
                                    <td class="percent">{{ number_format($row['percentage'], 2) }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="summary-panel lakou">
                    <table class="summary" aria-label="Lakou Summary">
                        <colgroup>
                            <col style="width: 50%">
                            <col style="width: 25%">
                            <col style="width: 25%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>Lakou</th>
                                <th>Total</th>
                                <th>% of Overall</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lakous as $row)
                                <tr>
                                    <td>{{ $row['name'] }} ({{ $row['donation_count'] }})</td>
                                    <td class="amount">${{ number_format($row['total_amount'], 0) }}</td>
                                    <td class="percent">{{ number_format($row['percentage'], 2) }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </aside>
    </main>
</body>
</html>
