<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        @page { margin: 30px 28px 60px 28px; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #1f2937;
            margin: 0;
        }

        /* ── Header ── */
        .head-table { width: 100%; border-collapse: collapse; border-bottom: 3px solid #FF6B9D; }
        .head-table td { border: none; padding: 0 0 10px 0; vertical-align: bottom; }
        .brand { color: #FF6B9D; font-size: 9px; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; }
        .title { font-size: 20px; font-weight: bold; color: #111827; margin: 4px 0 0; }
        .meta { text-align: right; color: #6b7280; font-size: 9.5px; line-height: 1.7; }
        .meta b { color: #111827; }

        .filters {
            margin: 12px 0 0; padding: 7px 12px; background: #fff0f7; border: 1px solid #fce4ec;
            color: #E85588; font-size: 9px;
        }

        /* ── Table ── */
        table.logs { width: 100%; border-collapse: collapse; margin-top: 14px; table-layout: fixed; }
        table.logs thead { display: table-header-group; }
        table.logs th {
            background: #FF6B9D; color: #ffffff; font-size: 8.5px; font-weight: bold; text-transform: uppercase;
            letter-spacing: 0.5px; padding: 8px 7px; text-align: left; border: 1px solid #FF6B9D;
        }
        table.logs td {
            padding: 6px 7px; border: 1px solid #e5e7eb; font-size: 9px; vertical-align: middle;
            word-wrap: break-word;
        }
        table.logs tr { page-break-inside: avoid; }
        tr.even td { background: #fdf6f9; }

        .muted { color: #9ca3af; }
        .bold  { font-weight: bold; }

        .badge { padding: 2px 8px; font-size: 8.5px; font-weight: bold; }
        .badge-success { background: #dcfce7; color: #16a34a; }
        .badge-failed  { background: #fee2e2; color: #dc2626; }
        .badge-pending { background: #fef3c7; color: #d97706; }

        .empty { text-align: center; padding: 35px 10px; color: #9ca3af; font-size: 12px; }

        /* ── Footer (repeats on every page) ── */
        .footer {
            position: fixed; bottom: -38px; left: 0; right: 0; height: 26px;
            border-top: 1px solid #e5e7eb; padding-top: 6px; font-size: 8.5px; color: #9ca3af;
        }
        .footer table { width: 100%; border-collapse: collapse; }
        .footer td { border: none; padding: 0; }
        .pagenum:before { content: "Page " counter(page); }
    </style>
</head>
<body>

    @php
        $filters = collect([
            'Search' => request('search'),
            'Action' => request('action'),
            'Role'   => request('role'),
            'From'   => request('date_from'),
            'To'     => request('date_to'),
            'Status' => request('status'),
        ])->filter()->map(fn ($v, $k) => $k . ': ' . $v)->implode('   |   ');
    @endphp

    <div class="footer">
        <table>
            <tr>
                <td>Beauty Blush Salons &mdash; Admin Audit Log</td>
                <td style="text-align:right;"><span class="pagenum"></span></td>
            </tr>
        </table>
    </div>

    <table class="head-table">
        <tr>
            <td>
                <div class="brand">Beauty Blush Salons</div>
                <div class="title">{{ $title }}</div>
            </td>
            <td class="meta">
                Generated on <b>{{ $date }}</b><br>
                Total Entries: <b>{{ $total }}</b>
            </td>
        </tr>
    </table>

    @if($filters !== '')
        <div class="filters">Filters applied &nbsp;&raquo;&nbsp; {{ $filters }}</div>
    @endif

    <table class="logs">
        <thead>
            <tr>
                <th style="width:6%;">#</th>
                <th style="width:17%;">User</th>
                <th style="width:10%;">Role</th>
                <th style="width:13%;">Action</th>
                <th style="width:13%;">Module</th>
                <th style="width:13%;">IP Address</th>
                <th style="width:9%;">Status</th>
                <th style="width:19%;">Date &amp; Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                @php $status = $log->status ?? 'success'; @endphp
                <tr class="{{ $loop->even ? 'even' : '' }}">
                    <td class="muted bold">#{{ $log->id }}</td>
                    <td class="bold">{{ $log->user->name ?? 'System' }}</td>
                    <td>{{ $log->role_label ?? ucfirst($log->user->role ?? 'N/A') }}</td>
                    <td>{{ ucfirst($log->action) }}</td>
                    <td>{{ $log->module ?? '—' }}</td>
                    <td>{{ $log->ip_address ?? '—' }}</td>
                    <td><span class="badge badge-{{ $status }}">{{ ucfirst($status) }}</span></td>
                    <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="empty">No audit logs found for the selected filters</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>