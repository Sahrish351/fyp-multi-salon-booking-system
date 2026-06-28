
@extends('layouts.owner')
 
@section('title', 'Payment Details')
 
@section('content')
 
    @php
        $statusBadge = [
            'Completed' => 'badge-completed',
            'Pending'   => 'badge-pending',
            'Failed'    => 'badge-cancelled',
            'Refunded'  => 'badge-progress',
        ];
    @endphp
 
   
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>{{ $payment['payment_id'] }}</h2>
            <p>Payment Details &middot; Invoice {{ $payment['invoice_no'] }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('owner.payments.edit', ['payment' => $payment['id']]) }}" class="btn btn-edit-action">
                <i class="bi bi-pencil-square me-2"></i> Edit
            </a>
            <a href="{{ route('owner.payments.index') }}" class="btn btn-back">
                <i class="bi bi-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>
 
    <div class="row g-4">
 
       
        <div class="col-lg-4">
            <div class="panel-card text-center">
                <span class="badge-status-lg {{ $statusBadge[$payment['status']] ?? 'badge-pending' }}">
                    {{ $payment['status'] }}
                </span>
 
                <div class="price-display mt-3">${{ $payment['amount'] }}</div>
                <p class="price-label">{{ $payment['method'] }}</p>
 
                <hr class="my-4">
 
                <div class="d-flex flex-column gap-2">
 
                    @if ($payment['status'] === 'Pending')
                        <form action="{{ route('owner.payments.approve', ['payment' => $payment['id']]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-action-confirm w-100">
                                <i class="bi bi-check-circle-fill me-2"></i> Approve Payment
                            </button>
                        </form>
                        <form action="{{ route('owner.payments.reject', ['payment' => $payment['id']]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-action-cancel w-100">
                                <i class="bi bi-x-circle-fill me-2"></i> Reject Payment
                            </button>
                        </form>
                    @endif
 
                    <a href="{{ route('owner.appointments.invoice', ['id' => $payment['id']]) }}" class="btn btn-action-invoice w-100">
                        <i class="bi bi-download me-2"></i> Download Invoice
                    </a>
 
                </div>
            </div>
        </div>
 
      
        <div class="col-lg-8">
 
            <div class="panel-card mb-4">
                <div class="panel-title">Client Information</div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Name</span>
                        <span class="info-value">{{ $payment['client_name'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $payment['client_email'] }}</span>
                    </div>
                </div>
            </div>
 
            <div class="panel-card">
                <div class="panel-title">Transaction Information</div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Service</span>
                        <span class="info-value">{{ $payment['service'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Payment Method</span>
                        <span class="info-value">{{ $payment['method'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date</span>
                        <span class="info-value">{{ $payment['date'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Time</span>
                        <span class="info-value">{{ $payment['time'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Invoice Number</span>
                        <span class="info-value">{{ $payment['invoice_no'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Payment ID</span>
                        <span class="info-value">{{ $payment['payment_id'] }}</span>
                    </div>
                </div>
            </div>
 
        </div>
 
    </div>
 
@endsection
 
@section('extra-css')
<style>
    .btn-back {
        background: var(--white); border: 1px solid var(--blush-200); color: var(--plum-800);
        font-weight: 600; font-size: 14.5px; padding: 10px 20px; border-radius: 10px;
        display: inline-flex; align-items: center; transition: all 0.18s ease;
    }
    .btn-back:hover { background: var(--blush-50); color: var(--plum-900); }
 
    .btn-edit-action {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        color: var(--plum-900); font-weight: 700; font-size: 14.5px;
        padding: 10px 22px; border-radius: 10px; border: none;
        box-shadow: 0 4px 14px rgba(217, 164, 65, 0.35); transition: all 0.18s ease;
        display: inline-flex; align-items: center;
    }
    .btn-edit-action:hover { transform: translateY(-1px); color: var(--plum-900); box-shadow: 0 6px 18px rgba(217, 164, 65, 0.5); }
 
    .badge-status-lg {
        display: inline-block;
        padding: 8px 22px;
        border-radius: 30px;
        font-size: 15px;
        font-weight: 700;
    }
    .badge-status-lg.badge-completed { background: var(--green-50); color: var(--green-500); }
    .badge-status-lg.badge-pending   { background: var(--orange-50); color: var(--orange-500); }
    .badge-status-lg.badge-cancelled { background: var(--red-50); color: var(--red-500); }
    .badge-status-lg.badge-progress  { background: var(--blue-50); color: var(--blue-500); }
 
    .price-display { font-size: 32px; font-weight: 700; color: var(--gold-600); }
    .price-label { font-size: 13px; color: var(--ink-500); margin: 0; }
 
    .btn-action-confirm {
        background: linear-gradient(135deg, #38C495, var(--green-500)); color: #fff;
        font-weight: 700; padding: 11px; border-radius: 10px; border: none;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .btn-action-confirm:hover { color: #fff; box-shadow: 0 4px 14px rgba(46, 174, 125, 0.35); }
 
    .btn-action-cancel {
        background: var(--red-50); color: var(--red-500);
        font-weight: 700; padding: 11px; border-radius: 10px; border: 1px solid #FBD0D9;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .btn-action-cancel:hover { background: var(--red-500); color: #fff; }
 
    .btn-action-invoice {
        background: var(--blush-50); color: var(--plum-800);
        font-weight: 700; padding: 11px; border-radius: 10px; border: 1px solid var(--blush-200);
        display: inline-flex; align-items: center; justify-content: center; text-decoration: none;
    }
    .btn-action-invoice:hover { background: var(--blush-100); color: var(--plum-900); }
 
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px 24px;
    }
    .info-item { display: flex; flex-direction: column; gap: 4px; }
    .info-label { font-size: 12.5px; color: var(--ink-500); }
    .info-value { font-size: 14.5px; font-weight: 600; color: var(--plum-900); }
</style>
@endsection
 