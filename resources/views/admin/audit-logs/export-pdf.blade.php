<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #E91E8C;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #E91E8C;
            font-size: 20px;
            margin: 0;
        }
        .header .subtitle {
            color: #888;
            font-size: 12px;
            margin: 5px 0 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th {
            background: #f8f9fa;
            color: #333;
            font-weight: 600;
            padding: 8px 10px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }
        table td {
            padding: 6px 10px;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        table tr:nth-child(even) {
            background: #f8f9fa;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            color: #aaa;
            font-size: 10px;
        }
        .badge-success {
            color: #16a34a;
            font-weight: 600;
        }
        .badge-failed {
            color: #dc2626;
            font-weight: 600;
        }
        .badge-pending {
            color: #d97706;
            font-weight: 600;
        }
        .text-center {
            text-align: center;
        }
        .empty-row td {
            padding: 30px;
            text-align: center;
            color: #aaa;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p class="subtitle">Generated on {{ $date }} | Total Entries: {{ $total }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Role</th>
                <th>Action</th>
                <th>Module</th>
                <th>IP Address</th>
                <th>Status</th>
                <th>Date & Time</th>
            </tr>
        </thead>
        <tbody>
            @if($logs->count() > 0)
                @foreach($logs as $log)
                <tr>
                    <td>#{{ $log->id }}</td>
                    <td>{{ $log->user->name ?? 'System' }}</td>
                    <td>{{ $log->user->role ?? 'N/A' }}</td>
                    <td>{{ ucfirst($log->action) }}</td>
                    <td>{{ $log->module ?? '—' }}</td>
                    <td>{{ $log->ip_address ?? '—' }}</td>
                    <td>
                        <span class="badge-{{ $log->status ?? 'success' }}">
                            {{ ucfirst($log->status ?? 'Success') }}
                        </span>
                    </td>
                    <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                </tr>
                @endforeach
            @else
                <tr class="empty-row">
                    <td colspan="8">No audit logs found for the selected filters</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>This report was generated automatically by Glamora System</p>
    </div>
</body>
</html>