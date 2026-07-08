@extends('layouts.owner')

@section('title', 'Team Member Details')

@section('content')

    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>{{ $stylist['name'] }}</h2>
            <p>{{ $stylist['role'] }} &middot; Team Member Details</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('owner.stylists.edit', ['stylist' => $stylist['id']]) }}" class="btn btn-edit-action">
                <i class="bi bi-pencil-square me-2"></i> Edit
            </a>
            <a href="{{ route('owner.stylists.index') }}" class="btn btn-back">
                <i class="bi bi-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-lg-4">
            <div class="panel-card text-center">
                <div class="stylist-avatar-lg mx-auto">
                    @if (!empty($stylist['photo_url']))
                        <img src="{{ $stylist['photo_url'] }}" alt="{{ $stylist['name'] }}">
                    @else
                        <i class="bi bi-person-fill"></i>
                    @endif
                </div>

                <h4 class="mt-3 mb-1" style="color:#2d1f2c; font-weight:700;">{{ $stylist['name'] }}</h4>
                <p class="mb-2" style="color:#8a7a88; font-size:14px;">{{ $stylist['role'] }}</p>

                <span class="badge-status {{ $stylist['status'] === 'Active' ? 'badge-confirmed' : 'badge-cancelled' }} mb-3">
                    {{ $stylist['status'] }}
                </span>

                <div class="stylist-rating-lg">
                    <i class="bi bi-star-fill"></i> {{ $stylist['rating'] }} Rating
                </div>

                <hr class="my-4">

                <div class="text-start contact-info-list">
                    <div class="contact-item">
                        <i class="bi bi-envelope-fill"></i>
                        <span>{{ $stylist['email'] }}</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-telephone-fill"></i>
                        <span>{{ $stylist['phone'] }}</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-award-fill"></i>
                        <span>{{ $stylist['specialization'] }}</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-clock-history"></i>
                        <span>{{ $stylist['experience_years'] }} years experience</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-card-sm">
                        <div class="stat-icon icon-blue"><i class="bi bi-people-fill"></i></div>
                        <div>
                            <div class="stat-label-sm">Total Clients</div>
                            <div class="stat-value-sm">{{ $stylist['clients'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-sm">
                        <div class="stat-icon icon-green"><i class="bi bi-currency-dollar"></i></div>
                        <div>
                            <div class="stat-label-sm">Total Revenue</div>
                            <div class="stat-value-sm">PKR {{ number_format($stylist['revenue']) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-sm">
                        <div class="stat-icon icon-gold"><i class="bi bi-calendar-check-fill"></i></div>
                        <div>
                            <div class="stat-label-sm">Appointments</div>
                            <div class="stat-value-sm">{{ $stylist['total_appointments'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>

            @if (!empty($stylist['bio']))
                <div class="panel-card mb-4">
                    <div class="panel-title">About</div>
                    <p class="stylist-bio-text">{{ $stylist['bio'] }}</p>
                </div>
            @endif

            <div class="panel-card">
                <div class="panel-title">Recent Appointments</div>
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Service</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentAppointments ?? [] as $appt)
                                <tr>
                                    <td class="cell-name">{{ $appt['client'] }}</td>
                                    <td>{{ $appt['service'] }}</td>
                                    <td>{{ $appt['date'] }}</td>
                                    <td>
                                        <span class="badge-status {{ $appt['status'] === 'Completed' ? 'badge-completed' : 'badge-confirmed' }}">
                                            {{ $appt['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4" style="color:#8a7a88;">
                                        No appointments found yet.
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

    .stylist-avatar-lg {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FF6B9D, #E85588);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 52px;
        color: #fff;
        overflow: hidden;
    }
    .stylist-avatar-lg img { width: 100%; height: 100%; object-fit: cover; }

    .stylist-rating-lg {
        font-size: 15px;
        font-weight: 700;
        color: #D9A441;
        margin-bottom: 4px;
    }
    .stylist-rating-lg i { color: #D9A441; margin-right: 4px; }

    .contact-info-list { display: flex; flex-direction: column; gap: 12px; }
    .contact-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13.5px;
        color: #4a3a48;
    }
    .contact-item i { color: #E85588; width: 18px; text-align: center; }

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

    .stylist-bio-text {
        color: #4a3a48;
        font-size: 14.5px;
        line-height: 1.7;
        margin-bottom: 0;
    }

    .badge-status {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .badge-confirmed { background: #E8F5ED; color: #1E8E64; }
    .badge-completed { background: #E8F5ED; color: #1E8E64; }
    .badge-cancelled { background: #FCE4EC; color: #D45482; }

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

    @media (max-width: 768px) {
        .stylist-avatar-lg {
            width: 100px;
            height: 100px;
            font-size: 36px;
        }
        .stat-card-sm {
            padding: 12px 14px;
        }
        .stat-value-sm {
            font-size: 18px;
        }
    }
</style>
@endsection