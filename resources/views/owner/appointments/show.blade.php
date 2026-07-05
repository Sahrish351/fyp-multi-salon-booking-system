@extends('layouts.owner')

@section('title', 'Appointment Details')

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $statusBadge = [
            'confirmed' => 'badge-confirmed',
            'pending_payment' => 'badge-pending',
            'in_progress' => 'badge-progress',
            'completed' => 'badge-completed',
            'cancelled' => 'badge-cancelled',
        ];
        $statusDisplay = [
            'pending_payment' => 'Pending',
            'confirmed' => 'Confirmed',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];
    @endphp

    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>
                <i class="bi bi-calendar-check text-primary me-2"></i>
                Appointment #{{ $appointment['id'] }}
            </h2>
            <p>
                <span class="badge-status {{ $statusBadge[$appointment['status_raw']] ?? 'badge-pending' }}">
                    {{ $statusDisplay[$appointment['status_raw']] ?? ucfirst($appointment['status_raw']) }}
                </span>
                <span class="mx-2 text-muted">|</span>
                {{ $appointment['booking_ref'] }}
                <span class="mx-2 text-muted">|</span>
                {{ $appointment['date'] }}
                <span class="mx-2 text-muted">|</span>
                {{ $appointment['time_range'] }}
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('owner.appointments.edit', ['appointment' => $appointment['id']]) }}" class="btn btn-edit-action">
                <i class="bi bi-pencil-square me-2"></i> Edit
            </a>
            <a href="{{ route('owner.appointments.index') }}" class="btn btn-back">
                <i class="bi bi-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-4">

        <!-- ==================== LEFT COLUMN ==================== -->
        <div class="col-lg-4">
            <div class="panel-card text-center">
                <!-- Status Badge -->
                <div class="status-badge-wrapper">
                    <span class="badge-status-lg {{ $statusBadge[$appointment['status_raw']] ?? 'badge-pending' }}">
                        {{ $statusDisplay[$appointment['status_raw']] ?? ucfirst($appointment['status_raw']) }}
                    </span>
                </div>

                <!-- Amount -->
                <div class="price-display mt-3">PKR {{ number_format($appointment['price']) }}</div>
                <p class="price-label">Total Amount</p>

                @if($appointment['advance_amount'] > 0)
                    <div class="advance-display">Advance: PKR {{ number_format($appointment['advance_amount']) }}</div>
                @endif

                <!-- Payment Status -->
                <div class="payment-status mt-3">
                    <span class="badge-status {{ $appointment['payment_status'] === 'approved' ? 'badge-completed' : 'badge-pending' }}">
                        <i class="bi bi-credit-card me-1"></i> {{ ucfirst($appointment['payment_status']) }}
                    </span>
                    <span class="text-muted ms-2">|</span>
                    <span class="text-muted ms-2">{{ ucfirst($appointment['payment_method']) }}</span>
                </div>

                <hr class="my-4">

                <!-- ACTION BUTTONS -->
                <div class="d-flex flex-column gap-2">

                    @if ($appointment['status_raw'] === 'pending_payment')
                        <form action="{{ route('owner.appointments.confirm', ['id' => $appointment['id']]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-action-confirm w-100">
                                <i class="bi bi-check-circle-fill me-2"></i> Confirm Appointment
                            </button>
                        </form>
                    @endif

                    @if ($appointment['payment_status'] === 'pending' && $appointment['status_raw'] === 'pending_payment')
                        <form action="{{ route('owner.appointments.verify-payment', ['id' => $appointment['id']]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-action-verify-payment w-100">
                                <i class="bi bi-credit-card-fill me-2"></i> Verify Payment
                            </button>
                        </form>
                    @endif

                    @if ($appointment['payment_status'] === 'pending' && $appointment['status_raw'] === 'pending_payment')
                        <form action="{{ route('owner.appointments.reject-payment', ['id' => $appointment['id']]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-action-reject-payment w-100">
                                <i class="bi bi-x-circle-fill me-2"></i> Reject Payment
                            </button>
                        </form>
                    @endif

                    @if (in_array($appointment['status_raw'], ['confirmed', 'in_progress']))
                        <form action="{{ route('owner.appointments.complete', ['id' => $appointment['id']]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-action-complete w-100">
                                <i class="bi bi-check2-all me-2"></i> Mark as Completed
                            </button>
                        </form>
                    @endif

                    @if (!in_array($appointment['status_raw'], ['completed', 'cancelled']))
                        <button type="button" class="btn btn-action-cancel w-100"
                                data-bs-toggle="modal" data-bs-target="#cancelApptModal">
                            <i class="bi bi-x-circle-fill me-2"></i> Cancel Appointment
                        </button>
                    @endif

                    <a href="{{ route('owner.appointments.invoice', ['id' => $appointment['id']]) }}" class="btn btn-action-invoice w-100">
                        <i class="bi bi-receipt me-2"></i> View Invoice
                    </a>

                </div>
            </div>
        </div>

        <!-- ==================== RIGHT COLUMN ==================== -->
        <div class="col-lg-8">

            <!-- Client Information -->
            <div class="panel-card mb-4">
                <div class="panel-title">
                    <i class="bi bi-person-circle me-2 text-primary"></i> Client Information
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label"><i class="bi bi-person me-1"></i> Name</span>
                        <span class="info-value">{{ $appointment['client_name'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="bi bi-envelope me-1"></i> Email</span>
                        <span class="info-value">{{ $appointment['client_email'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="bi bi-telephone me-1"></i> Phone</span>
                        <span class="info-value">{{ $appointment['client_phone'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Appointment Information -->
            <div class="panel-card mb-4">
                <div class="panel-title">
                    <i class="bi bi-info-circle me-2 text-primary"></i> Appointment Details
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label"><i class="bi bi-tag me-1"></i> Service</span>
                        <span class="info-value">{{ $appointment['service'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="bi bi-person-badge me-1"></i> Stylist</span>
                        <span class="info-value">{{ $appointment['stylist'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="bi bi-calendar3 me-1"></i> Date</span>
                        <span class="info-value">{{ $appointment['date'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="bi bi-clock me-1"></i> Time</span>
                        <span class="info-value">{{ $appointment['time_range'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="bi bi-upc-scan me-1"></i> Booking Ref</span>
                        <span class="info-value"><code>{{ $appointment['booking_ref'] }}</code></span>
                    </div>
                </div>
            </div>

            @if (!empty($appointment['notes']))
                <div class="panel-card">
                    <div class="panel-title">
                        <i class="bi bi-sticky-fill me-2 text-primary"></i> Notes
                    </div>
                    <p class="appt-notes-text">{{ $appointment['notes'] }}</p>
                </div>
            @endif

        </div>

    </div>

@endsection

@push('modals')
    <!-- Cancel Modal -->
    <div class="modal fade" id="cancelApptModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <form action="{{ route('owner.appointments.cancel', ['id' => $appointment['id']]) }}" method="POST">
                    @csrf
                    <div class="modal-body text-center py-4">
                        <div class="modal-icon-wrapper">
                            <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                        </div>
                        <h5 class="mt-3" style="color:#5C2142; font-weight:700;">Cancel this Appointment?</h5>
                        <p class="mb-0" style="color:#6B4F62;">
                            This will notify <strong>{{ $appointment['client_name'] }}</strong> that their appointment has been cancelled.
                        </p>
                    </div>
                    <div class="modal-footer modal-footer-custom justify-content-center">
                        <button type="button" class="btn btn-cancel-modal" data-bs-dismiss="modal">
                            <i class="bi bi-arrow-left me-2"></i> Keep Appointment
                        </button>
                        <button type="submit" class="btn btn-delete-confirm">
                            <i class="bi bi-check-circle-fill me-2"></i> Cancel Appointment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@section('extra-css')
<style>
    /* ===================== BUTTONS ===================== */
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

    /* ===================== STATUS BADGE ===================== */
    .badge-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-confirmed { background: #E8F5E9; color: #2EAE7D; }
    .badge-pending { background: #FFF3CD; color: #856404; }
    .badge-progress { background: #D1ECF1; color: #0C5460; }
    .badge-completed { background: #D4EDDA; color: #155724; }
    .badge-cancelled { background: #F8D7DA; color: #721C24; }

    .badge-status-lg {
        display: inline-block;
        padding: 8px 22px;
        border-radius: 30px;
        font-size: 15px;
        font-weight: 700;
    }
    .badge-status-lg.badge-confirmed { background: #E8F5E9; color: #2EAE7D; }
    .badge-status-lg.badge-pending   { background: #FFF3CD; color: #856404; }
    .badge-status-lg.badge-progress  { background: #D1ECF1; color: #0C5460; }
    .badge-status-lg.badge-completed { background: #D4EDDA; color: #155724; }
    .badge-status-lg.badge-cancelled { background: #F8D7DA; color: #721C24; }

    /* ===================== PRICE ===================== */
    .price-display {
        font-size: 32px;
        font-weight: 700;
        color: #E85588;
    }
    .price-label {
        font-size: 13px;
        color: var(--ink-500);
        margin: 0;
    }
    .advance-display {
        font-size: 14px;
        color: var(--ink-700);
        margin-top: 4px;
    }
    .payment-status {
        font-size: 14px;
        margin-top: 6px;
    }

    /* ===================== ACTION BUTTONS ===================== */
    .btn-action-confirm {
        background: linear-gradient(135deg, #38C495, #2EAE7D);
        color: #fff;
        font-weight: 700;
        padding: 11px;
        border-radius: 10px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    .btn-action-confirm:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(46, 174, 125, 0.35);
    }

    .btn-action-verify-payment {
        background: linear-gradient(135deg, #4A7FE0, #3568C4);
        color: #fff;
        font-weight: 700;
        padding: 11px;
        border-radius: 10px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    .btn-action-verify-payment:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(74, 127, 224, 0.35);
    }

    .btn-action-reject-payment {
        background: linear-gradient(135deg, #F0708C, #E14D6A);
        color: #fff;
        font-weight: 700;
        padding: 11px;
        border-radius: 10px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    .btn-action-reject-payment:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(225, 77, 106, 0.35);
    }

    .btn-action-complete {
        background: linear-gradient(135deg, #D9A441, #C4903A);
        color: #2d1f2c;
        font-weight: 700;
        padding: 11px;
        border-radius: 10px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    .btn-action-complete:hover {
        color: #2d1f2c;
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(217, 164, 65, 0.35);
    }

    .btn-action-cancel {
        background: #FCE4EC;
        color: #E14D6A;
        font-weight: 700;
        padding: 11px;
        border-radius: 10px;
        border: 1px solid #FBD0D9;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    .btn-action-cancel:hover {
        background: #E14D6A;
        color: #fff;
        transform: translateY(-2px);
    }

    .btn-action-invoice {
        background: var(--blush-50);
        color: var(--plum-800);
        font-weight: 700;
        padding: 11px;
        border-radius: 10px;
        border: 1px solid var(--blush-200);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .btn-action-invoice:hover {
        background: var(--blush-100);
        color: var(--plum-900);
        transform: translateY(-2px);
    }

    /* ===================== INFO GRID ===================== */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px 24px;
    }
    .info-item {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .info-label {
        font-size: 12px;
        color: var(--ink-500);
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .info-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--plum-900);
    }

    .appt-notes-text {
        color: var(--ink-700);
        font-size: 14.5px;
        line-height: 1.7;
        margin-bottom: 0;
        padding: 8px 12px;
        background: var(--blush-50);
        border-radius: 8px;
        border-left: 3px solid #E85588;
    }

    /* ===================== MODAL ===================== */
    .modal-content-custom {
        border-radius: var(--radius-lg);
        border: none;
        overflow: hidden;
    }
    .modal-body { padding: 22px 24px; }
    .modal-footer-custom {
        border-top: 1px solid var(--blush-100);
        padding: 16px 24px;
        gap: 10px;
    }
    .modal-icon-wrapper i {
        font-size: 48px;
    }

    .btn-cancel-modal {
        background: var(--white);
        border: 1px solid var(--blush-200);
        color: var(--ink-700);
        font-weight: 600;
        padding: 9px 20px;
        border-radius: 10px;
        transition: all 0.18s ease;
    }
    .btn-cancel-modal:hover { background: var(--blush-50); }

    .btn-delete-confirm {
        background: linear-gradient(135deg, #F0708C, #E14D6A);
        color: #fff;
        font-weight: 700;
        padding: 9px 24px;
        border-radius: 10px;
        border: none;
        transition: all 0.18s ease;
    }
    .btn-delete-confirm:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(225, 77, 106, 0.4);
    }

    /* ===================== PANEL ===================== */
    .panel-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid rgba(0,0,0,0.04);
        transition: all 0.3s ease;
        height: auto !important;  /* ✅ FIX: Extra space remove */
    }
    .panel-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    .panel-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2d1f2c;
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
    }

    /* ===================== ALERTS ===================== */
    .alert {
        border-radius: 12px;
        border: none;
        padding: 0.8rem 1.2rem;
    }
    .alert-success {
        background: #E8F5E9;
        color: #1B5E20;
    }
    .alert-danger {
        background: #FCE4EC;
        color: #880E4F;
    }

    /* ===================== PAGE HEADER ===================== */
    .page-header {
        margin-bottom: 1.5rem;
    }
    .page-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d1f2c;
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
    }
    .page-header p {
        color: #8a7a88;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 4px;
    }
    .page-header p .mx-2 {
        color: #d5cdd5;
    }

    .text-primary {
        color: #E85588 !important;
    }
    .text-muted {
        color: #8a7a88 !important;
    }

    code {
        background: var(--blush-50);
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 12px;
        color: #E85588;
    }

    .status-badge-wrapper {
        margin-bottom: 4px;
    }

    /* ===================== RESPONSIVE ===================== */
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr !important;
            gap: 8px 0 !important;
        }
        .page-header h2 {
            font-size: 1.2rem;
        }
        .page-header p {
            font-size: 0.85rem;
        }
        .panel-card {
            padding: 1rem !important;
        }
        .price-display {
            font-size: 26px;
        }
        .badge-status-lg {
            font-size: 13px;
            padding: 6px 16px;
        }
    }

    @media (max-width: 576px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start !important;
        }
        .page-header .d-flex.gap-2 {
            width: 100%;
            flex-wrap: wrap;
        }
        .page-header .d-flex.gap-2 a,
        .page-header .d-flex.gap-2 button {
            flex: 1;
            justify-content: center;
            font-size: 13px;
            padding: 8px 12px;
        }
        .col-lg-4 .panel-card {
            padding: 1rem !important;
        }
        .btn-action-confirm,
        .btn-action-verify-payment,
        .btn-action-reject-payment,
        .btn-action-complete,
        .btn-action-cancel,
        .btn-action-invoice {
            font-size: 13px;
            padding: 9px !important;
        }
    }
</style>
@endsection