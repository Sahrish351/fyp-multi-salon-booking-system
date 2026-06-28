<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 10.5px;
        color: #222;
        margin: 0;
        padding: 20px;
    }
    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
        border-bottom: 2px solid #c2185b;
        padding-bottom: 12px;
    }
    h2 {
        color: #c2185b;
        margin: 0 0 4px;
        font-size: 18px;
    }
    .meta {
        font-size: 9.5px;
        color: #777;
        margin: 0;
    }
    .total-badge {
        background: #fce4ec;
        color: #c2185b;
        padding: 4px 12px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 11px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 4px;
    }
    thead tr {
        background: #c2185b;
        color: #fff;
    }
    th {
        padding: 7px 8px;
        text-align: left;
        font-size: 9.5px;
        font-weight: 700;
        letter-spacing: .03em;
        text-transform: uppercase;
    }
    td {
        padding: 6px 8px;
        border-bottom: 1px solid #f3f3f3;
        font-size: 10px;
        vertical-align: middle;
    }
    tr:nth-child(even) td { background: #fce4ec22; }
    tr:nth-child(odd)  td { background: #fff; }
    .badge-active {
        color: #065f46;
        background: #d1fae5;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 9px;
        font-weight: 700;
    }
    .badge-suspended {
        color: #991b1b;
        background: #fee2e2;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 9px;
        font-weight: 700;
    }
    .footer {
        margin-top: 14px;
        font-size: 9px;
        color: #aaa;
        text-align: right;
        border-top: 1px solid #eee;
        padding-top: 6px;
    }
</style>
</head>
<body>

<div class="header">
    <div>
        <h2>Glamora — Clients Report</h2>
        <p class="meta">Generated: {{ now()->format('d M Y, h:i A') }}</p>
    </div>
    <span class="total-badge">Total: {{ $clients->count() }} clients</span>
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>City</th>
            <th>Bookings</th>
            <th>Status</th>
            <th>Provider</th>
            <th>Joined</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clients as $i => $client)
        <tr>
            <td style="color:#999;">{{ $client->id }}</td>
            <td><strong>{{ $client->name }}</strong></td>
            <td>{{ $client->email }}</td>
            <td>{{ $client->phone ?? '—' }}</td>
            <td>{{ $client->city ?? '—' }}</td>
            <td style="text-align:center;">{{ $client->appointments_count ?? 0 }}</td>
            <td>
                <span class="{{ $client->is_active ? 'badge-active' : 'badge-suspended' }}">
                    {{ $client->is_active ? 'Active' : 'Suspended' }}
                </span>
            </td>
            <td>{{ ucfirst($client->auth_provider ?? 'email') }}</td>
            <td>{{ $client->created_at->format('d M Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">Glamora Admin Panel &nbsp;·&nbsp; Confidential</div>

</body>
</html>