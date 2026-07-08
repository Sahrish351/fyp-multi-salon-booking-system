{{--
    PAYMENT SHOW PAGE - REAL DATA
    resources/views/owner/payments/show.blade.php
--}}
@extends('layouts.owner')
@section('title', 'Payment Details')

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $statusConfig = [
            'Completed' => ['class' => 'status-approved', 'icon' => 'bi-check-circle-fill', 'label' => 'Approved'],
            'Pending'   => ['class' => 'status-pending',  'icon' => 'bi-hourglass-split',    'label' => 'Pending'],
            'Failed'    => ['class' => 'status-rejected', 'icon' => 'bi-x-circle-fill',      'label' => 'Rejected'],
            'Refunded'  => ['class' => 'status-refunded', 'icon' => 'bi-arrow-counterclockwise', 'label' => 'Refunded'],
        ];
        $sc = $statusConfig[$payment['status']] ?? ['class' => 'status-pending', 'icon' => 'bi-hourglass-split', 'label' => $payment['status']];
    @endphp

    {{-- Header --}}
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h2 class="page-title">{{ $payment['payment_id'] }}</h2>
            <p class="page-sub">Invoice {{ $payment['invoice_no'] }} &middot; {{ $payment['date'] }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('owner.payments.index') }}" class="btn btn-back-outline">
                <i class="bi bi-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- ===== LEFT: Status Card + Actions (STICKY + FULL HEIGHT) ===== --}}
        <div class="col-lg-4">
            <div class="sticky-card-wrapper">
                <div class="panel-card text-center" style="height:100%; min-height:500px;">

                    {{-- Status Badge --}}
                    <div class="status-circle {{ $sc['class'] }}">
                        <i class="bi {{ $sc['icon'] }}"></i>
                    </div>
                    <h5 class="status-label mt-3">{{ $sc['label'] }}</h5>

                    {{-- Amount --}}
                    <div class="amount-big mt-2">PKR {{ $payment['amount'] }}</div>
                    <p class="method-label">
                        <i class="bi bi-credit-card-fill me-1"></i> {{ $payment['method'] }}
                    </p>

                    @if($payment['verified_at'])
                        <p class="verified-tag">
                            <i class="bi bi-shield-check me-1"></i> Verified {{ $payment['verified_at'] }}
                        </p>
                    @endif

                    <hr class="my-3">

                    {{-- Actions --}}
                    <div class="d-flex flex-column gap-2">

                        @if($payment['status'] === 'Pending')
                            {{-- Approve --}}
                            <form action="{{ route('owner.payments.approve', ['payment' => $payment['id']]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-approve-pay w-100">
                                    <i class="bi bi-check-circle-fill me-2"></i> Approve Payment
                                </button>
                            </form>

                            {{-- Reject with reason --}}
                            <button type="button" class="btn btn-reject-pay w-100"
                                    data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="bi bi-x-circle-fill me-2"></i> Reject Payment
                            </button>
                        @endif

                        @if($payment['screenshot'])
                            <a href="{{ $payment['screenshot'] }}" target="_blank" class="btn btn-screenshot w-100">
                                <i class="bi bi-image-fill me-2"></i> View Screenshot
                            </a>
                        @endif

                        <a href="{{ route('owner.payments.edit', ['payment' => $payment['id']]) }}"
                           class="btn btn-edit-pay w-100">
                            <i class="bi bi-pencil-square me-2"></i> Edit Payment
                        </a>

                    </div>

                    {{-- Extra space at bottom to fill height --}}
                    <div style="flex:1;"></div>

                </div>
            </div>
        </div>

        {{-- ===== RIGHT: Details ===== --}}
        <div class="col-lg-8">

            {{-- Client Info --}}
            <div class="panel-card mb-4" style="height:auto; padding:1.25rem 1.5rem;">
                <div class="detail-section-title">
                    <i class="bi bi-person-fill me-2"></i> Client Information
                </div>
                <div class="info-grid-2">
                    <div class="info-row">
                        <span class="info-key">Name</span>
                        <span class="info-val">{{ $payment['client_name'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Email</span>
                        <span class="info-val">{{ $payment['client_email'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Transaction Info --}}
            <div class="panel-card mb-4" style="height:auto; padding:1.25rem 1.5rem;">
                <div class="detail-section-title">
                    <i class="bi bi-receipt me-2"></i> Transaction Information
                </div>
                <div class="info-grid-2">
                    <div class="info-row">
                        <span class="info-key">Service</span>
                        <span class="info-val">{{ $payment['service'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Payment Method</span>
                        <span class="info-val">{{ $payment['method'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Transaction Ref</span>
                        <span class="info-val font-mono">{{ $payment['transaction_ref'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Sender Number</span>
                        <span class="info-val">{{ $payment['sender_number'] ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Date</span>
                        <span class="info-val">{{ $payment['date'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Time</span>
                        <span class="info-val">{{ $payment['time'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Invoice Number</span>
                        <span class="info-val">{{ $payment['invoice_no'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Payment ID</span>
                        <span class="info-val">{{ $payment['payment_id'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Screenshot --}}
            @if($payment['screenshot'])
                <div class="panel-card mb-4" style="height:auto; padding:1.25rem 1.5rem;">
                    <div class="detail-section-title">
                        <i class="bi bi-image-fill me-2"></i> Payment Screenshot
                    </div>
                    <div class="screenshot-wrap">
                        <img src="{{ $payment['screenshot'] }}" alt="Payment Screenshot" class="screenshot-img"
                             onclick="window.open(this.src, '_blank')">
                        <p class="screenshot-hint">Click to open full size</p>
                    </div>
                </div>
            @endif

            {{-- Rejection Reason --}}
            @if($payment['status'] === 'Failed' && !empty($payment['rejection_reason']))
                <div class="rejection-box">
                    <div class="rejection-title">
                        <i class="bi bi-x-circle-fill me-2"></i> Rejection Reason
                    </div>
                    <p class="rejection-text">{{ $payment['rejection_reason'] }}</p>
                </div>
            @endif

        </div>

    </div>

@endsection

@push('modals')
    {{-- Reject Modal --}}
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px; border:none; overflow:hidden;">
                <form action="{{ route('owner.payments.reject', ['payment' => $payment['id']]) }}" method="POST">
                    @csrf
                    <div class="modal-header" style="background:#FFF0F6; border-bottom:1px solid #FBD5E8; padding:18px 24px;">
                        <h5 class="modal-title" style="font-weight:700; color:#2d1f2c;">Reject Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" style="padding:22px 24px;">
                        <label style="display:block; font-size:13.5px; font-weight:600; color:#5A4A55; margin-bottom:6px;">
                            Reason for Rejection <span style="color:#E85588;">*</span>
                        </label>
                        <textarea name="reason" rows="3" required
                                  style="width:100%; background:#FFF6FA; border:1.5px solid #F0C0D8; border-radius:10px; padding:11px 14px; font-size:14px; color:#2d1f2c; outline:none;"
                                  placeholder="e.g. Transaction reference not found, amount mismatch..."></textarea>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #FEE8F2; padding:16px 24px; gap:10px;">
                        <button type="button" class="btn btn-back-outline" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-reject-pay" style="padding:9px 24px;">Reject Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@section('extra-css')
<style>
    /* ===== STICKY LEFT CARD - FULL HEIGHT ===== */
    .sticky-card-wrapper {
        position: sticky;
        top: 20px;
        height: 600px;
    }

    .sticky-card-wrapper .panel-card {
        height: 100% !important;
        min-height: 500px;
        display: flex;
        flex-direction: column;
    }

    .page-title { font-size:1.5rem; font-weight:700; color:#2d1f2c; margin:0 0 4px; }
    .page-sub   { color:#9E7B95; margin:0; font-size:14px; }

    .btn-back-outline {
        background:#fff; border:1.5px solid #F0C0D8; color:#C0547A !important;
        font-weight:600; font-size:14px; padding:10px 20px; border-radius:10px;
        display:inline-flex; align-items:center; transition:all 0.2s; text-decoration:none;
    }
    .btn-back-outline:hover { background:#FFF0F6; }

    .panel-card {
        background:#fff; border-radius:16px; padding:1.25rem 1.5rem;
        box-shadow:0 2px 12px rgba(0,0,0,0.05); border:1px solid #FBD5E8;
        height:auto !important;
        min-height:auto !important;
    }

    /* Status Circle */
    .status-circle {
        width:80px; height:80px; border-radius:50%;
        display:flex; align-items:center; justify-content:center;
        font-size:32px; margin:0 auto 4px;
    }
    .status-approved { background:#E8F5E9; color:#2EAE7D; }
    .status-pending  { background:#FEF3DC; color:#C4903A; }
    .status-rejected { background:#FCE4EC; color:#E14D6A; }
    .status-refunded { background:#E8F0FD; color:#4A7FE0; }

    .status-label { font-size:16px; font-weight:700; color:#2d1f2c; margin:0; }
    .amount-big   { font-size:2rem; font-weight:800; color:#E85588; }
    .method-label { font-size:13.5px; color:#9E7B95; margin:4px 0 0; }
    .verified-tag { font-size:12px; color:#2EAE7D; background:#E8F5E9; padding:5px 12px; border-radius:20px; display:inline-block; margin-top:6px; }

    .btn-approve-pay {
        background:linear-gradient(135deg, #2EAE7D, #1E8E64); color:#fff;
        font-weight:600; padding:11px; border-radius:10px; border:none;
        display:inline-flex; align-items:center; justify-content:center; transition:all 0.2s;
    }
    .btn-approve-pay:hover { color:#fff; transform:translateY(-2px); box-shadow:0 4px 14px rgba(46,174,125,0.35); }

    .btn-reject-pay {
        background:#FCE4EC; color:#E14D6A; border:1px solid #FBD0D9;
        font-weight:600; padding:11px; border-radius:10px;
        display:inline-flex; align-items:center; justify-content:center; transition:all 0.2s;
    }
    .btn-reject-pay:hover { background:#E14D6A; color:#fff; }

    .btn-screenshot {
        background:#FFF0F6; color:#C0547A; border:1px solid #F0C0D8;
        font-weight:600; padding:11px; border-radius:10px;
        display:inline-flex; align-items:center; justify-content:center; text-decoration:none; transition:all 0.2s;
    }
    .btn-screenshot:hover { background:#FFE0EE; color:#A03060; }

    .btn-edit-pay {
        background:linear-gradient(135deg, #FF6B9D, #E85588); color:#fff;
        font-weight:600; padding:11px; border-radius:10px; border:none;
        display:inline-flex; align-items:center; justify-content:center; text-decoration:none; transition:all 0.2s;
    }
    .btn-edit-pay:hover { color:#fff; transform:translateY(-2px); box-shadow:0 4px 14px rgba(232,85,136,0.35); }

    /* Info Grid */
    .detail-section-title {
        font-size:14.5px; font-weight:700; color:#2d1f2c;
        margin-bottom:12px; padding-bottom:8px;
        border-bottom:1px solid #FEE8F2;
        display:flex; align-items:center;
    }
    .detail-section-title i { color:#E85588; }

    .info-grid-2 { display:flex; flex-direction:column; gap:8px; }
    .info-row    { display:flex; justify-content:space-between; align-items:center; padding:8px 14px; background:#FFF6FA; border-radius:10px; }
    .info-key    { font-size:13px; color:#9E7B95; font-weight:600; }
    .info-val    { font-size:14px; font-weight:600; color:#2d1f2c; text-align:right; }
    .font-mono   { font-family:monospace; font-size:13px; }

    /* Screenshot */
    .screenshot-wrap { text-align:center; }
    .screenshot-img  { max-width:100%; max-height:250px; border-radius:12px; cursor:pointer; border:1px solid #FBD5E8; transition:transform 0.2s; }
    .screenshot-img:hover { transform:scale(1.02); }
    .screenshot-hint { font-size:12px; color:#B090A0; margin-top:6px; }

    /* Rejection */
    .rejection-box   { background:#FFF0F2; border:1px solid #FBD0D9; border-radius:12px; padding:14px 18px; }
    .rejection-title { font-size:14px; font-weight:700; color:#E14D6A; margin-bottom:6px; display:flex; align-items:center; }
    .rejection-text  { font-size:14px; color:#5A3040; margin:0; }

    /* Alerts */
    .alert { border-radius:12px; border:none; padding:10px 16px; margin-bottom:16px; }
    .alert-success { background:#E8F5E9; color:#1B5E20; }
    .alert-danger  { background:#FCE4EC; color:#880E4F; }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .page-title { font-size:1.2rem; }
        .amount-big { font-size:1.5rem; }
        .info-row { flex-direction:column; align-items:flex-start; gap:4px; }
        .info-val { text-align:left; }
        .panel-card { padding:1rem; }
        .sticky-card-wrapper {
            position: relative;
            top: 0;
            height: auto;
        }
        .sticky-card-wrapper .panel-card {
            min-height: auto;
            height: auto !important;
        }
    }
</style>
@endsection