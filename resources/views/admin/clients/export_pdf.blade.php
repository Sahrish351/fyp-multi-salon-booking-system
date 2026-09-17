<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    /* A4 Page Setup for PDF Engines */
    @page {
        size: A4 portrait;
        margin: 15mm 12mm 15mm 12mm;
    }

    body {
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 9px;
        color: #2D2631;
        margin: 0;
        padding: 0;
        background-color: #FFFFFF;
        width: 100%;
    }
    
    /* Header Section */
    .header {
        width: 100%;
        margin-bottom: 12px;
        border-bottom: 2px solid #FF6B9D;
        padding-bottom: 8px;
    }
    .header-table {
        width: 100%;
        border-collapse: collapse;
    }
    .header-table td {
        padding: 0;
        border: none;
        background: transparent !important;
        vertical-align: middle;
    }
    .brand-title {
        color: #FF6B9D;
        margin: 0 0 2px;
        font-size: 16px;
        font-weight: 800;
        letter-spacing: -0.3px;
    }
    .meta {
        font-size: 8px;
        color: #9C8294;
        margin: 0;
        font-weight: 500;
    }
    .total-badge-wrap {
        text-align: right;
    }
    .total-badge {
        background: #FDEAF3;
        color: #FF6B9D;
        padding: 4px 10px;
        border-radius: 10px;
        font-weight: 800;
        font-size: 8.5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 1px solid #F1DCE9;
        display: inline-block;
    }

    /* Data Table Styling */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed; /* Fixes width to match container properly */
        margin-top: 4px;
    }
    .data-table thead tr {
        background: #FF6B9D;
        color: #ffffff;
    }
    .data-table th {
        padding: 7px 6px;
        text-align: left;
        font-size: 7.5px;
        font-weight: 800;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        color: #ffffff;
        word-wrap: break-word;
    }
    .data-table td {
        padding: 6px 6px;
        border-bottom: 1px solid #F1DCE9;
        font-size: 8.5px;
        vertical-align: middle;
        color: #372F3A;
        word-wrap: break-word;
    }
    
    /* Clean Row Striping */
    .data-table tr:nth-child(even) td { background: #FAFAFC; }
    .data-table tr:nth-child(odd) td { background: #FFFFFF; }

    /* Status Badges */
    .badge-active {
        color: #1E8E3E;
        background: #E3F6E9;
        padding: 2px 6px;
        border-radius: 6px;
        font-size: 7.5px;
        font-weight: 800;
        text-transform: uppercase;
    }
    .badge-suspended {
        color: #D93025;
        background: #FCE8E6;
        padding: 2px 6px;
        border-radius: 6px;
        font-size: 7.5px;
        font-weight: 800;
        text-transform: uppercase;
    }

    /* Footer Section */
    .footer {
        margin-top: 12px;
        font-size: 7.5px;
        color: #9C8294;
        text-align: right;
        border-top: 1px solid #F1DCE9;
        padding-top: 5px;
        font-weight: 600;
    }
</style>
</head>
<body>

<div class="header">
    <table class="header-table">
        <tr>
            <td>
                <h2 class="brand-title">Beauty Blush Salons</h2>
                <p class="meta">Clients Directory Report &nbsp;·&nbsp; Generated on {{ now()->format('d M Y, h:i A') }}</p>
            </td>
            <td class="total-badge-wrap">
                <span class="total-badge">Total: {{ $clients->count() }} Clients</span>
            </td>
        </tr>
    </table>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th style="width: 5%;">#</th>
            <th style="width: 18%;">Name</th>
            <th style="width: 23%;">Email</th>
            <th style="width: 12%;">Phone</th>
            <th style="width: 11%;">City</th>
            <th style="width: 8%; text-align: center;">Bookings</th>
            <th style="width: 9%;">Status</th>
            <th style="width: 8%;">Provider</th>
            <th style="width: 6%;">Joined</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clients as $i => $client)
        <tr>
            <td style="color:#9C8294; font-weight:700;">{{ $client->id }}</td>
            <td><strong style="color: #2D2631;">{{ $client->name }}</strong></td>
            <td style="color: #5A4E5E;">{{ $client->email }}</td>
            <td style="color: #5A4E5E;">{{ $client->phone ?? '—' }}</td>
            <td>{{ $client->city ?? '—' }}</td>
            <td style="text-align: center; font-weight: 800; color: #FF6B9D;">{{ $client->appointments_count ?? 0 }}</td>
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

<div class="footer">Beauty Blush Salons Admin Panel &nbsp;·&nbsp; Confidential Management Report</div>

</body>
</html>