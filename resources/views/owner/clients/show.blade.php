@extends('layouts.owner')
 
@section('title', 'Client Details')
 
@section('content')
 
    @php
        $statusBadge = [
            'VIP' => 'badge-vip',
            'Regular' => 'badge-regular',
            'New' => 'badge-new',
            'Inactive' => 'badge-inactive',
        ];
    @endphp
 
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>{{ $client['name'] }}</h2>
            <p>Client since {{ $client['join_date'] }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('owner.clients.edit', ['client' => $client['id']]) }}" class="btn btn-edit-action">
                <i class="bi bi-pencil-square me-2"></i> Edit
            </a>
            <a href="{{ route('owner.clients.index') }}" class="btn btn-back">
                <i class="bi bi-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>
 
    <div class="row g-4">
 
        <div class="col-lg-4">
            <div class="panel-card text-center">
                <div class="client-avatar mx-auto">
                    <i class="bi bi-person-fill"></i>
                </div>
 
                <h4 class="mt-3 mb-1" style="color:#2d1f2c; font-weight:700;">{{ $client['name'] }}</h4>
 
                <span class="badge-status {{ $statusBadge[$client['status']] ?? 'badge-regular' }} mb-3">
                    {{ $client['status'] }}
                </span>
 
                <hr class="my-4">
 
                <div class="text-start contact-info-list">
                    <div class="contact-item">
                        <i class="bi bi-envelope-fill"></i>
                        <span>{{ $client['email'] }}</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-telephone-fill"></i>
                        <span>{{ $client['phone'] }}</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-calendar-event-fill"></i>
                        <span>Joined {{ $client['join_date'] }}</span>
                    </div>
                </div>
 
                @if (!empty($client['notes']))
                    <hr class="my-4">
                    <div class="text-start">
                        <p class="info-label mb-2">Notes</p>
                        <p class="client-notes-text">{{ $client['notes'] }}</p>
                    </div>
                @endif
            </div>
        </div>
 
        <div class="col-lg-8">
 
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-card-sm">
                        <div class="stat-icon icon-blue"><i class="bi bi-calendar-check-fill"></i></div>
                        <div>
                            <div class="stat-label-sm">Total Visits</div>
                            <div class="stat-value-sm">{{ $client['total_visits'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-sm">
                        <div class="stat-icon icon-green"><i class="bi bi-currency-dollar"></i></div>
                        <div>
                            <div class="stat-label-sm">Total Spent</div>
                            <div class="stat-value-sm">PKR {{ number_format($client['total_spent']) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-sm">
                        <div class="stat-icon icon-gold"><i class="bi bi-clock-history"></i></div>
                        <div>
                            <div class="stat-label-sm">Last Visit</div>
                            <div class="stat-value-sm" style="font-size:16px;">{{ $client['last_visit'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
 
            <div class="panel-card">
                <div class="panel-title">Visit History</div>
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Stylist</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($visitHistory ?? [] as $visit)
                                <tr>
                                    <td class="cell-name">{{ $visit['service'] }}</td>
                                    <td>{{ $visit['stylist'] }}</td>
                                    <td>{{ $visit['date'] }}</td>
                                    <td class="amount-gold">PKR {{ number_format($visit['amount']) }}</td>
                                    <td>
                                        <span class="badge-status badge-completed">{{ $visit['status'] }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4" style="color:#8a7a88;">
                                        No visit history found yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
 
        </div>
 
    </div>
 
@endsection
 
@section('extra-css')
<style>
    .page-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d1f2c;
        margin-bottom: 0.25rem;
    }
    .page-header p {
        color: #8a7a88;
        margin-bottom: 0;
    }

    .btn-back {
        background: #fff;
        border: 1px solid #f0e8ed;
        color: #2d1f2c;
        font-weight: 600;
        font-size: 14.5px;
        padding: 10px 20px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        transition: all 0.18s ease;
        text-decoration: none;
    }
    .btn-back:hover {
        background: #fcf6f9;
        border-color: #E85588;
        color: #E85588;
    }

    .btn-edit-action {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 14.5px;
        padding: 10px 22px;
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.35);
        transition: all 0.18s ease;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    .btn-edit-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(232, 85, 136, 0.45);
        color: #ffffff !important;
    }

    .panel-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f0e8ed;
        height: auto !important;
    }
    .panel-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2d1f2c;
        margin-bottom: 1rem;
    }

    .badge-vip { background: #FDF6E8; color: #C4903A; }
    .badge-regular { background: #E8F0FE; color: #3568C4; }
    .badge-new { background: #E8F5ED; color: #1E8E64; }
    .badge-inactive { background: #FCE4EC; color: #D45482; }

    .client-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FF6B9D, #E85588);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        color: #fff;
    }

    .contact-info-list { display: flex; flex-direction: column; gap: 12px; }
    .contact-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13.5px;
        color: #4a3a48;
    }
    .contact-item i { color: #E85588; width: 18px; text-align: center; }

    .info-label { font-size: 12.5px; color: #8a7a88; margin: 0; }
    .client-notes-text { font-size: 13.5px; color: #4a3a48; line-height: 1.6; margin: 0; }

    .stat-card-sm {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #f0e8ed;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        height: 100%;
    }
    .stat-card-sm .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        font-size: 20px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
    }
    .icon-blue { background: linear-gradient(135deg, #4A7FE0, #3568C4); }
    .icon-green { background: linear-gradient(135deg, #2EAE7D, #1E8E64); }
    .icon-gold { background: linear-gradient(135deg, #D9A441, #C4903A); }
    .stat-label-sm { font-size: 13.5px; color: #8a7a88; margin-bottom: 2px; }
    .stat-value-sm { font-size: 22px; font-weight: 700; color: #2d1f2c; }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
    }
    .table-custom thead th {
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        color: #8a7a88;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 0 10px 12px;
        border-bottom: 1.5px solid #f0e8ed;
    }
    .table-custom tbody td {
        padding: 12px 10px;
        font-size: 14px;
        color: #2d1f2c;
        border-bottom: 1px solid #f5eef2;
        vertical-align: middle;
    }
    .table-custom tbody tr:last-child td {
        border-bottom: none;
    }
    .table-custom tbody tr:hover {
        background: #fcf6f9;
    }
    .cell-name { font-weight: 600; color: #2d1f2c; }
    .amount-gold { font-weight: 700; color: #D9A441; }

    .badge-status {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .badge-completed { background: #E8F5ED; color: #1E8E64; }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch !important;
        }
        .btn-back {
            justify-content: center;
            width: 100%;
        }
        .stat-card-sm {
            padding: 12px 14px;
        }
        .stat-value-sm {
            font-size: 18px;
        }
        .client-avatar {
            width: 80px;
            height: 80px;
            font-size: 32px;
        }
        .panel-card {
            padding: 1rem;
        }
    }
</style>
@endsection