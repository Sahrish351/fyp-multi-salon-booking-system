@extends('layouts.owner')

@section('title', $service['name'] . ' - Service Details')

@section('content')

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="padding:0.5rem 1rem; margin-bottom:0.75rem; font-size:13px;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- PAGE HEADER -->
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>{{ $service['name'] }}</h2>
            <p>{{ $service['category'] }} &middot; Service Details</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('owner.services.edit', $service['id']) }}" class="btn btn-edit-action">
                <i class="bi bi-pencil-square me-2"></i> Edit Service
            </a>
            <a href="{{ route('owner.services.index') }}" class="btn btn-back">
                <i class="bi bi-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="row g-4">

        <!-- LEFT COLUMN - Service Info Card -->
        <div class="col-lg-4">
            <div class="panel-card text-center">
                <!-- Image -->
                <div class="service-image-box mx-auto">
                    @if(!empty($service['image_url']))
                        <img src="{{ $service['image_url'] }}" alt="{{ $service['name'] }}">
                    @else
                        <i class="bi bi-scissors"></i>
                        <span>No Image</span>
                    @endif
                </div>

                <!-- Status Badge -->
                <div class="mt-3">
                    <span class="badge-status {{ $service['status'] === 'Active' ? 'badge-active' : 'badge-inactive' }}">
                        <i class="bi bi-{{ $service['status'] === 'Active' ? 'check-circle-fill' : 'x-circle-fill' }} me-1"></i>
                        {{ $service['status'] }}
                    </span>
                </div>

                <!-- Service Name -->
                <h4 class="mt-3 mb-1" style="color:var(--plum-800); font-weight:700;">{{ $service['name'] }}</h4>
                <p style="color:var(--ink-500); font-size:14px;">{{ $service['category'] }}</p>

                <!-- Price -->
                <div class="price-row">
                    <span class="price-current">PKR {{ number_format($service['price']) }}</span>
                    @if(!empty($service['discount_price']))
                        <span class="price-old">PKR {{ number_format($service['discount_price']) }}</span>
                    @endif
                </div>

                <!-- Quick Meta -->
                <div class="detail-meta-row">
                    <div class="meta-item">
                        <i class="bi bi-clock-fill"></i>
                        <span>{{ $service['duration'] }} min</span>
                    </div>
                    <div class="meta-item">
                        <i class="bi bi-star-fill" style="color:#D9A441;"></i>
                        <span>{{ $service['rating'] ?? '0' }} Rating</span>
                    </div>
                    <div class="meta-item">
                        <i class="bi bi-calendar-check-fill" style="color:#2EAE7D;"></i>
                        <span>{{ $service['bookings'] }} Bookings</span>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Quick Stats -->
                <div class="row g-2">
                    <div class="col-6">
                        <div class="stat-mini">
                            <div class="stat-mini-icon gold"><i class="bi bi-calendar-check-fill"></i></div>
                            <div>
                                <div class="stat-mini-label">Bookings</div>
                                <div class="stat-mini-value">{{ $service['bookings'] }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-mini">
                            <div class="stat-mini-icon green"><i class="bi bi-currency-dollar"></i></div>
                            <div>
                                <div class="stat-mini-label">Revenue</div>
                                <div class="stat-mini-value">PKR {{ number_format($service['revenue']) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN - Details -->
        <div class="col-lg-8">

            <!-- ===== DESCRIPTION CARD ===== -->
            <div class="panel-card panel-card-compact mb-3">
                <div class="panel-title panel-title-compact">
                    <i class="bi bi-file-text-fill" style="color:#E85588; margin-right:6px;"></i>
                    Description
                </div>
                <p class="service-description service-description-compact">
                    {{ $service['description'] ?? 'No description provided for this service.' }}
                </p>

                @if (!empty($service['client_notes']))
                    <div class="client-notes-box client-notes-box-compact">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        {{ $service['client_notes'] }}
                    </div>
                @endif
            </div>

            
            <!-- ===== RECENT BOOKINGS ===== -->
            <div class="panel-card panel-card-compact" style="min-height: 300px !important;">
                <div class="panel-title panel-title-compact d-flex justify-content-between align-items-center">
                    <span>
                        <i class="bi bi-clock-history-fill" style="color:#E85588; margin-right:6px;"></i>
                        Recent Bookings
                    </span>
                    <span class="badge bg-light text-dark" style="font-size:10px; padding:2px 10px;">{{ count($recentBookings) }} total</span>
                </div>

                <div class="table-responsive">
                    @if(count($recentBookings) > 0)
                        <table class="table-custom table-custom-compact">
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
                                        <td class="cell-name cell-name-compact">
                                            <div class="client-avatar client-avatar-compact">
                                                <span>{{ substr($booking['client'], 0, 2) }}</span>
                                            </div>
                                            {{ $booking['client'] }}
                                        </td>
                                        <td>
                                            <i class="bi bi-calendar3 me-1" style="color:#8a7a88;"></i>
                                            {{ $booking['date'] }}
                                        </td>
                                        <td>
                                            <i class="bi bi-person-badge me-1" style="color:#8a7a88;"></i>
                                            {{ $booking['stylist'] }}
                                        </td>
                                        <td>
                                            <span class="badge-status badge-status-compact {{ strtolower($booking['status']) }}">
                                                {{ $booking['status'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-2">
                            <i class="bi bi-calendar-x" style="font-size:24px; color:#d4c4d0; display:block; margin-bottom:4px;"></i>
                            <p class="text-muted mb-0" style="font-size:13px;">No bookings yet for this service.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>

@endsection

@section('extra-css')
<style>
    /* ===== PAGE HEADER ===== */
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

    /* ===== BUTTONS ===== */
    .btn-back {
        background: #fff;
        border: 1px solid #f0e8ed;
        color: #2d1f2c;
        font-weight: 600;
        font-size: 14px;
        padding: 10px 20px;
        border-radius: 12px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
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
        font-size: 14px;
        padding: 10px 22px;
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 15px rgba(232, 85, 136, 0.35);
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    .btn-edit-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(232, 85, 136, 0.5);
        color: #ffffff !important;
    }

    /* ===== PANEL CARD - HEIGHT AUTO ===== */
    .panel-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid rgba(0,0,0,0.04);
        transition: all 0.3s ease;
        height: auto !important; /* ✅ HEIGHT AUTO */
        min-height: auto !important;
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

    /* ===== COMPACT CARD ===== */
    .panel-card-compact {
        padding: 0.75rem 1.25rem !important;
    }
    .panel-title-compact {
        font-size: 0.85rem !important;
        margin-bottom: 0.3rem !important;
    }
    .service-description-compact {
        font-size: 13px !important;
        line-height: 1.5 !important;
    }
    .client-notes-box-compact {
        margin-top: 6px !important;
        padding: 6px 12px !important;
        font-size: 12px !important;
    }

    /* ===== COMPACT TABLE ===== */
    .table-custom-compact thead th {
        font-size: 14px !important;
        padding: 0 8px 6px !important;
    }
    .table-custom-compact tbody td {
        padding: 6px 8px !important;
        font-size: 12px !important;
    }
    .cell-name-compact {
        gap: 6px !important;
        font-size: 12px !important;
    }
    .client-avatar-compact {
        width: 24px !important;
        height: 24px !important;
        font-size: 9px !important;
    }
    .badge-status-compact {
        font-size: 9px !important;
        padding: 2px 10px !important;
    }

    /* ===== SERVICE IMAGE ===== */
    .service-image-box {
        width: 100%;
        height: 200px;
        border-radius: 12px;
        background: linear-gradient(135deg, #fce8f0, #fce4ec);
        border: 2px dashed #f0d8e0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        color: #E85588;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .service-image-box:hover {
        border-color: #E85588;
    }
    .service-image-box i {
        font-size: 48px;
        color: #E85588;
    }
    .service-image-box span {
        font-size: 13px;
        color: #8a7a88;
    }
    .service-image-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* ===== STATUS BADGE ===== */
    .badge-status {
        display: inline-block;
        padding: 5px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .badge-active {
        background: #E8F5ED;
        color: #1E8E64;
    }
    .badge-inactive {
        background: #FCE4EC;
        color: #D45482;
    }

    /* ===== PRICE ===== */
    .price-row {
        margin: 10px 0;
    }
    .price-current {
        font-size: 24px;
        font-weight: 700;
        color: #E85588;
    }
    .price-old {
        font-size: 16px;
        color: #b0a0ae;
        text-decoration: line-through;
        margin-left: 8px;
    }

    /* ===== META ROW ===== */
    .detail-meta-row {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #f5eef2;
        flex-wrap: wrap;
    }
    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #4a3a48;
        font-weight: 600;
    }
    .meta-item i {
        color: #E85588;
        font-size: 14px;
    }

    /* ===== STAT MINI ===== */
    .stat-mini {
        background: #fcf6f9;
        border-radius: 12px;
        padding: 10px 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: background 0.2s;
    }
    .stat-mini:hover {
        background: #fce8f0;
    }
    .stat-mini-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: #fff;
        flex-shrink: 0;
    }
    .stat-mini-icon.gold { background: linear-gradient(135deg, #D9A441, #C4903A); }
    .stat-mini-icon.green { background: linear-gradient(135deg, #2EAE7D, #1E8E64); }
    .stat-mini-label {
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #8a7a88;
        font-weight: 600;
        line-height: 1;
    }
    .stat-mini-value {
        font-size: 13px;
        font-weight: 700;
        color: #2d1f2c;
        line-height: 1.2;
    }

    /* ===== STAT CARDS ===== */
    .stat-card-sm {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #f0e8ed;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 14px;
        height: auto !important;
        transition: all 0.3s ease;
    }
    .stat-card-sm:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
    }
    .stat-card-sm .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        font-size: 16px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
    }
    .stat-label-sm {
        font-size: 11px;
        color: #8a7a88;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        font-weight: 600;
    }
    .stat-value-sm {
        font-size: 17px;
        font-weight: 700;
        color: #2d1f2c;
        line-height: 1.2;
    }

    .icon-gold { background: linear-gradient(135deg, #D9A441, #C4903A); }
    .icon-green { background: linear-gradient(135deg, #2EAE7D, #1E8E64); }
    .icon-blue { background: linear-gradient(135deg, #4A7FE0, #3568C4); }

    /* ===== DESCRIPTION ===== */
    .service-description {
        color: #4a3a48;
        font-size: 14.5px;
        line-height: 1.8;
        margin-bottom: 0;
        height: 200px;
    }

    .client-notes-box {
        margin-top: 14px;
        padding: 12px 16px;
        background: #fcf6f9;
        border-left: 3px solid #E85588;
        border-radius: 8px;
        font-size: 13.5px;
        color: #4a3a48;
        display: flex;
        align-items: start;
        gap: 8px;
    }
    .client-notes-box i {
        color: #E85588;
        margin-top: 2px;
    }

    /* ===== TABLE ===== */
    .table-responsive {
        overflow-x: auto;
    }
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

    .cell-name {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        color: #2d1f2c;
    }
    .client-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FF6B9D, #E85588);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 11px;
        flex-shrink: 0;
    }

    /* ===== TABLE STATUS BADGES ===== */
    .badge-status.completed {
        background: #E8F5ED;
        color: #1E8E64;
    }
    .badge-status.confirmed {
        background: #E8F0FE;
        color: #3568C4;
    }
    .badge-status.pending {
        background: #FDF6E8;
        color: #C4903A;
    }
    .badge-status.cancelled {
        background: #FCE4EC;
        color: #D45482;
    }
    .badge-status.in_progress {
        background: #F0E8FD;
        color: #7E56B0;
    }

    /* ===== ALERT ===== */
    .alert {
        border-radius: 12px;
        border: none;
        padding: 0.8rem 1.2rem;
    }
    .alert-danger {
        background: #FCE4EC;
        color: #880E4F;
    }
    .alert .btn-close {
        font-size: 0.8rem;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .detail-meta-row {
            gap: 12px;
        }
        .stat-card-sm {
            height: auto;
            padding: 12px 14px;
        }
        .service-image-box {
            height: 140px;
        }
        .page-header h2 {
            font-size: 1.2rem;
        }
        .btn-edit-action, .btn-back {
            font-size: 12px;
            padding: 8px 14px;
        }
        .panel-card-compact {
            padding: 0.5rem 0.8rem !important;
        }
    }
    @media (max-width: 576px) {
        .stat-mini {
            flex-direction: column;
            text-align: center;
            padding: 10px;
        }
        .stat-mini-icon {
            width: 28px;
            height: 28px;
            font-size: 12px;
        }
        .stat-mini-value {
            font-size: 12px;
        }
        .stat-mini-label {
            font-size: 8px;
        }
    }
</style>
@endsection