@extends('layouts.owner')

@section('title', 'Service Details')

@section('content')

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>{{ $service['name'] }}</h2>
            <p>{{ $service['category'] }} &middot; Service Details</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('owner.services.edit', $service['id']) }}" class="btn btn-edit-action">
                <i class="bi bi-pencil-square me-2"></i> Edit
            </a>
            <a href="{{ route('owner.services.index') }}" class="btn btn-back">
                <i class="bi bi-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-4">

        <!-- LEFT COLUMN - SERVICE IMAGE & INFO -->
        <div class="col-lg-4">
            <div class="panel-card text-center">
                <div class="service-image-box mx-auto">
                    @if(!empty($service['image_url']))
                        <img src="{{ $service['image_url'] }}" alt="{{ $service['name'] }}">
                    @else
                        <i class="bi bi-scissors"></i>
                    @endif
                </div>

                <h4 class="mt-3 mb-1" style="color:var(--plum-800); font-weight:700;">{{ $service['name'] }}</h4>
                <span class="badge-status {{ $service['status'] === 'Active' ? 'badge-confirmed' : 'badge-cancelled' }} mb-3">
                    {{ $service['status'] }}
                </span>

                <div class="price-row">
                    <span class="price-current">PKR {{ number_format($service['price']) }}</span>
                </div>

                <div class="detail-meta-row">
                    <div class="meta-item">
                        <i class="bi bi-clock-fill"></i>
                        <span>{{ $service['duration'] }} min</span>
                    </div>
                    <div class="meta-item">
                        <i class="bi bi-star-fill" style="color:#D9A441;"></i>
                        <span>{{ $service['rating'] }} Rating</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN - STATS & DETAILS -->
        <div class="col-lg-8">

            <!-- STATS CARDS -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-card-sm">
                        <div class="stat-icon icon-gold"><i class="bi bi-calendar-check-fill"></i></div>
                        <div>
                            <div class="stat-label-sm">Total Bookings</div>
                            <div class="stat-value-sm">{{ $service['bookings'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-sm">
                        <div class="stat-icon icon-green"><i class="bi bi-currency-dollar"></i></div>
                        <div>
                            <div class="stat-label-sm">Total Revenue</div>
                            <div class="stat-value-sm">PKR {{ number_format($service['revenue']) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-sm">
                        <div class="stat-icon icon-blue"><i class="bi bi-clock-fill"></i></div>
                        <div>
                            <div class="stat-label-sm">Duration</div>
                            <div class="stat-value-sm">{{ $service['duration'] }} min</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DESCRIPTION -->
            <div class="panel-card mb-4">
                <div class="panel-title">Description</div>
                <p class="service-description">{{ $service['description'] ?? 'No description provided.' }}</p>

                @if (!empty($service['client_notes']))
                    <div class="client-notes-box">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        {{ $service['client_notes'] }}
                    </div>
                @endif
            </div>

            <!-- RECENT BOOKINGS -->
            <div class="panel-card">
                <div class="panel-title">Recent Bookings</div>
                <div class="table-responsive">
                    @if(count($recentBookings) > 0)
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Date</th>
                                    <th>Stylist</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentBookings as $booking)
                                    <tr>
                                        <td class="cell-name">{{ $booking['client'] }}</td>
                                        <td>{{ $booking['date'] }}</td>
                                        <td>{{ $booking['stylist'] }}</td>
                                        <td>
                                            <span class="badge-status {{ $booking['status'] === 'Completed' ? 'badge-completed' : 'badge-confirmed' }}">
                                                {{ $booking['status'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-3">
                            <p class="text-muted mb-0">No bookings yet for this service.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>

@endsection

@section('extra-css')
<style>
    .btn-back {
        background: var(--white);
        border: 1px solid var(--blush-200);
        color: var(--plum-800);
        font-weight: 600;
        font-size: 14.5px;
        padding: 10px 20px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        transition: all 0.18s ease;
    }
    .btn-back:hover {
        background: var(--blush-50);
        color: var(--plum-900);
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
    }
    .btn-edit-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(232, 85, 136, 0.45);
        color: #ffffff !important;
    }

    .service-image-box {
        width: 100%;
        height: 180px;
        border-radius: var(--radius-md);
        background: linear-gradient(135deg, #FF6B9D, #E85588);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 56px;
        color: #fff;
        overflow: hidden;
        margin-bottom: 6px;
        box-shadow: 0 4px 15px rgba(232, 85, 136, 0.3);
    }
    .service-image-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .price-row {
        margin: 12px 0;
    }
    .price-current {
        font-size: 26px;
        font-weight: 700;
        color: #E85588;
    }

    .detail-meta-row {
        display: flex;
        justify-content: center;
        gap: 24px;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid var(--blush-100);
    }
    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        color: var(--ink-700);
        font-weight: 600;
    }
    .meta-item i {
        color: #E85588;
    }

    .stat-card-sm {
        background: var(--white);
        border-radius: var(--radius-lg);
        border: 1px solid var(--blush-200);
        box-shadow: var(--shadow-card);
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        height: 80px;
        transition: all 0.3s ease;
    }
    .stat-card-sm:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-card-hover);
    }
    .stat-card-sm .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        font-size: 18px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
    }
    .stat-label-sm {
        font-size: 12px;
        color: var(--ink-700);
        margin-bottom: 1px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .stat-value-sm {
        font-size: 18px;
        font-weight: 700;
        color: var(--plum-900);
    }

    .icon-gold { background: linear-gradient(135deg, #D9A441, #C4903A); }
    .icon-green { background: linear-gradient(135deg, #2EAE7D, #1E8E64); }
    .icon-blue { background: linear-gradient(135deg, #4A7FE0, #3568C4); }

    .service-description {
        color: var(--ink-700);
        font-size: 14.5px;
        line-height: 1.7;
        margin-bottom: 0;
    }

    .client-notes-box {
        background: var(--blush-50);
        border-left: 3px solid #E85588;
        border-radius: var(--radius-sm);
        padding: 12px 16px;
        margin-top: 16px;
        font-size: 13.5px;
        color: var(--plum-800);
    }

    .badge-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-confirmed { background: #E8F5E9; color: #2EAE7D; }
    .badge-completed { background: #D4EDDA; color: #155724; }
    .badge-cancelled { background: #FCE4EC; color: #E14D6A; }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
    }
    .table-custom thead th {
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: var(--ink-500);
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 0 10px 12px;
        border-bottom: 1px solid var(--blush-200);
    }
    .table-custom tbody td {
        padding: 12px 10px;
        font-size: 14px;
        color: var(--ink-900);
        border-bottom: 1px solid var(--blush-100);
    }
    .table-custom tbody tr:last-child td {
        border-bottom: none;
    }
    .table-custom tbody tr:hover {
        background: var(--blush-50);
    }
    .cell-name {
        font-weight: 600;
        color: var(--plum-800);
    }

    .panel-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid rgba(0,0,0,0.04);
        transition: all 0.3s ease;
        height: 100%;
    }
    .panel-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    .panel-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2d1f2c;
        margin-bottom: 1rem;
    }

    .alert {
        border-radius: 12px;
        border: none;
        padding: 0.8rem 1.2rem;
    }
    .alert-danger {
        background: #FCE4EC;
        color: #880E4F;
    }

    .page-header {
        margin-bottom: 1.5rem;
    }
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
</style>
@endsection