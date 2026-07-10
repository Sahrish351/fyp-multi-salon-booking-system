<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 20px;
        }
        body {
            font-family: 'DejaVu Sans', 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #fff;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #E85588;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #E85588;
            font-size: 22px;
            margin: 0;
            font-weight: 700;
        }
        .header p {
            color: #999;
            font-size: 12px;
            margin: 5px 0 0;
        }
        .header .salon-name {
            color: #2d1f2c;
            font-size: 14px;
            font-weight: 600;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 11px;
        }
        table thead th {
            background: linear-gradient(135deg, #FF6B9D, #E85588);
            color: #fff;
            padding: 8px 10px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.3px;
        }
        table tbody td {
            padding: 6px 10px;
            border-bottom: 1px solid #f0e8ed;
            color: #333;
        }
        table tbody tr:nth-child(even) {
            background: #fcf6f9;
        }
        table tbody tr:hover {
            background: #fce8f0;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .amount {
            font-weight: 700;
            color: #E85588;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-completed { background: #E8F5ED; color: #1E8E64; }
        .status-pending { background: #FDF6E8; color: #C4903A; }
        .status-cancelled { background: #FCE4EC; color: #D45482; }
        .status-approved { background: #E8F5ED; color: #1E8E64; }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #f0e8ed;
            text-align: center;
            font-size: 10px;
            color: #bbb;
        }
        .footer .company {
            color: #E85588;
            font-weight: 600;
        }
        .summary {
            margin-top: 15px;
            padding: 10px 15px;
            background: #fcf6f9;
            border-radius: 8px;
            font-size: 12px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .summary-item {
            font-weight: 600;
        }
        .summary-item span {
            color: #E85588;
        }
        .total-row {
            font-weight: 700;
            background: #fde0ec !important;
        }
        .total-row td {
            border-top: 2px solid #E85588;
        }
        .badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-vip { background: #FDF6E8; color: #C4903A; }
        .badge-regular { background: #E8F0FE; color: #4A7FE0; }
        .badge-new { background: #E8F5ED; color: #1E8E64; }
        @media (max-width: 600px) {
            table { font-size: 9px; }
            table thead th, table tbody td { padding: 4px 6px; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p class="salon-name">{{ auth()->user()->name ?? config('app.name') }}</p>
        <p>Generated on: {{ \Carbon\Carbon::now()->format('F d, Y h:i A') }}</p>
    </div>

    @if(count($data) > 0)
        <table>
            <thead>
                <tr>
                    @foreach(array_keys($data[0]) as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php $totalRevenue = 0; @endphp
                @foreach($data as $row)
                    <tr>
                        @foreach($row as $key => $value)
                            <td>
                                @if(str_contains($key, 'Revenue') || str_contains($key, 'Amount') || str_contains($key, 'Spent'))
                                    <span class="amount">{{ $value }}</span>
                                    @php 
                                        $totalRevenue += (int) str_replace(',', '', $value);
                                    @endphp
                                @elseif(str_contains($key, 'Status'))
                                    <span class="badge status-{{ strtolower($value) }}">{{ $value }}</span>
                                @elseif(str_contains($key, 'Status'))
                                    <span class="badge badge-{{ strtolower($value) }}">{{ $value }}</span>
                                @else
                                    {{ $value }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if(isset($totalRevenue) && $totalRevenue > 0)
            <div class="summary">
                <div class="summary-item">Total Records: <span>{{ count($data) }}</span></div>
                <div class="summary-item">Total Revenue: <span>PKR {{ number_format($totalRevenue, 0) }}</span></div>
            </div>
        @endif
    @else
        <p style="text-align:center; color:#999; padding:40px 0;">No data available for this report.</p>
    @endif

    <div class="footer">
        <span class="company">{{ config('app.name', 'GlowAura') }}</span>
        &nbsp;·&nbsp; Generated by: {{ auth()->user()->name ?? 'System' }}
        &nbsp;·&nbsp; {{ \Carbon\Carbon::now()->format('F d, Y') }}
    </div>
</body>
</html>