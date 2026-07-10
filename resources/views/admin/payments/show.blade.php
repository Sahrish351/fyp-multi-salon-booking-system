@extends('layouts.admin')
@section('title', 'Payment Details')

@section('content')
<style>
    :root {
        --pm-pink:   #d4637a;
        --pm-green:  #6b8f71;
        --pm-green-lt:#f0f5f1;
        --pm-amber:  #b07d3a;
        --pm-amber-lt:#fdf6ec;
        --pm-red:    #b84444;
        --pm-red-lt: #fdf0f0;
        --pm-text:   #2d2d2d;
        --pm-text-mid:#8a8a8a;
        --pm-border: #ede9e4;
    }

    .pm-back-row { margin-bottom: 20px; }

    .pm-field-grid { display: flex; flex-wrap: wrap; gap: 22px 26px; padding: 22px; }
    .pm-field { flex: 1 1 220px; }
    .pm-field-full { flex: 1 1 100%; }

    .pm-label { display: block; margin-bottom: 6px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: var(--pm-text-mid); }
    .pm-value { margin: 0; color: var(--pm-text); font-size: 0.92rem; }
    .pm-value-strong { margin: 0; font-weight: 700; color: var(--pm-text); font-size: 1rem; }

    .badge { display: inline-flex; align-items: center; padding: 5px 14px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
    .badge-success { background: var(--pm-green-lt); color: var(--pm-green); }
    .badge-warning { background: var(--pm-amber-lt); color: var(--pm-amber); }
    .badge-danger  { background: var(--pm-red-lt);   color: var(--pm-red); }

    .pm-screenshot-link {
        display: inline-flex; align-items: center; gap: 8px;
        background: #faf8f6; border: 1.5px solid var(--pm-border);
        color: var(--pm-pink); padding: 10px 16px; border-radius: 10px;
        text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: all .15s;
    }
    .pm-screenshot-link:hover { border-color: var(--pm-pink); background: #fdf0f3; }
</style>

<div class="pm-back-row">
    <a href="{{ route('admin.payments.index') }}" class="btn-outline">
        <i class="fas fa-arrow-left"></i> Back to Payments
    </a>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title">Payment Information</span>
        <span class="badge {{ $payment->status == 'approved' ? 'badge-success' : ($payment->status == 'rejected' ? 'badge-danger' : 'badge-warning') }}">
            {{ ucfirst($payment->status) }}
        </span>
    </div>

    <div class="pm-field-grid">
        <div class="pm-field">
            <span class="pm-label">Client</span>
            <p class="pm-value-strong">{{ $payment->client->name ?? 'N/A' }}</p>
            @if($payment->client->phone ?? false)
            <p class="pm-value" style="font-size:.78rem; color: var(--pm-text-mid); margin-top:2px;">{{ $payment->client->phone }}</p>
            @endif
        </div>

        <div class="pm-field">
            <span class="pm-label">Salon</span>
            <p class="pm-value-strong">{{ $payment->salon->name ?? ($payment->appointment->salon->name ?? 'N/A') }}</p>
        </div>

        @if($payment->appointment->service->name ?? false)
        <div class="pm-field">
            <span class="pm-label">Service</span>
            <p class="pm-value">{{ $payment->appointment->service->name }}</p>
        </div>
        @endif

        @if($payment->appointment->booking_ref ?? false)
        <div class="pm-field">
            <span class="pm-label">Booking Ref</span>
            <p class="pm-value" style="font-family: monospace;">{{ $payment->appointment->booking_ref }}</p>
        </div>
        @endif

        <div class="pm-field">
            <span class="pm-label">Method</span>
            <p class="pm-value">{{ ucfirst($payment->method) }}</p>
        </div>

        <div class="pm-field">
            <span class="pm-label">Sender Number</span>
            <p class="pm-value">{{ $payment->sender_number ?? 'N/A' }}</p>
        </div>

        <div class="pm-field">
            <span class="pm-label">Amount</span>
            <p class="pm-value-strong" style="font-size:1.2rem; color: var(--pm-pink);">Rs. {{ number_format($payment->amount ?? 0) }}</p>
        </div>

        <div class="pm-field">
            <span class="pm-label">Submitted At</span>
            <p class="pm-value">{{ $payment->created_at->format('d M Y, h:i A') }}</p>
        </div>

        @if($payment->screenshot)
        <div class="pm-field-full">
            <span class="pm-label">Payment Proof</span>
            <a href="{{ $payment->screenshot_url }}" target="_blank" class="pm-screenshot-link">
                <i class="fas fa-image"></i> View Screenshot
            </a>
        </div>
        @endif
    </div>
</div>
@endsection