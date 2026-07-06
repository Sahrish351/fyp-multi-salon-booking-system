{{-- ============================================================ --}}
{{-- FILE: resources/views/client/payments/show.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.client')
@section('title', 'Payment Details — Glamora')

@push('styles')
<style>
    @media print {
        .no-print { display: none !important; }
    }
    .pay-detail-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 12px 0; border-bottom: 1px dashed #fce4ec;
        font-size: 0.88rem;
    }
    .pay-detail-row:last-child { border-bottom: none; }
    .pay-detail-row .k { color: #999; font-weight: 600; }
    .pay-detail-row .v { color: #1a1a1a; font-weight: 700; }
    .pay-status-chip {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 16px; border-radius: 50px;
        font-size: 0.8rem; font-weight: 700;
    }
    .pay-status-chip.paid      { background: rgba(34,197,94,0.1);  color: #16a34a; }
    .pay-status-chip.pending   { background: rgba(255,193,7,0.12); color: #b45309; }
    .pay-status-chip.cancelled { background: rgba(239,68,68,0.1);  color: #dc2626; }
</style>
@endpush

@section('content')
@php
    $statusMap = [
        'approved' => ['label' => 'Paid',      'class' => 'paid',      'icon' => 'fa-check-circle'],
        'pending'  => ['label' => 'Pending',   'class' => 'pending',   'icon' => 'fa-hourglass-half'],
        'rejected' => ['label' => 'Cancelled', 'class' => 'cancelled', 'icon' => 'fa-times-circle'],
    ];
    $st = $statusMap[$payment->status] ?? ['label' => ucfirst($payment->status), 'class' => 'pending', 'icon' => 'fa-circle'];
@endphp

<div class="mb-4 no-print">
    <a href="{{ route('client.payments.index') }}" style="color:#aaa;text-decoration:none;font-size:0.85rem;">
        <i class="fas fa-arrow-left me-2"></i>Back to Payments
    </a>
    <h4 class="fw-bold mt-2 mb-0" style="color:#333;font-family:'Playfair Display',serif;">
        <i class="fas fa-credit-card me-2" style="color:#E91E8C;"></i>Payment Details
    </h4>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="bg-white rounded-4 p-4" style="border:1px solid #fce4ec;">

            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <div style="color:#aaa;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.4px;">Payment ID</div>
                    <div style="color:#333;font-size:1.1rem;font-weight:800;">#{{ $payment->id }}</div>
                </div>
                <span class="pay-status-chip {{ $st['class'] }}">
                    <i class="fas {{ $st['icon'] }}"></i> {{ $st['label'] }}
                </span>
            </div>

            <div class="pay-detail-row">
                <span class="k">Salon</span>
                <span class="v">{{ $payment->appointment->salon->name ?? '—' }}</span>
            </div>
            <div class="pay-detail-row">
                <span class="k">Service</span>
                <span class="v">{{ $payment->appointment->service->name ?? '—' }}</span>
            </div>
            <div class="pay-detail-row">
                <span class="k">Amount</span>
                <span class="v" style="color:#E91E8C;">Rs. {{ number_format($payment->appointment->advance_amount) }}</span>
            </div>
            <div class="pay-detail-row">
                <span class="k">Payment Method</span>
                <span class="v">{{ ucfirst($payment->method ?? '—') }}</span>
            </div>
            <div class="pay-detail-row">
                <span class="k">Payment Date</span>
                <span class="v">{{ $payment->created_at->format('d M Y, h:i A') }}</span>
            </div>
            @if($payment->status === 'rejected' && ($payment->rejection_reason ?? false))
            <div class="pay-detail-row">
                <span class="k">Reason for Rejection</span>
                <span class="v" style="color:#dc2626;">{{ $payment->rejection_reason }}</span>
            </div>
            @endif

            @if($payment->screenshot)
            <div class="mt-4">
                <div style="color:#aaa;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.4px;margin-bottom:8px;">Uploaded Screenshot</div>
                <a href="{{ asset('storage/'.$payment->screenshot) }}" target="_blank">
                    <img src="{{ asset('storage/'.$payment->screenshot) }}" alt="Payment screenshot"
                         style="max-width:100%;border-radius:12px;border:1px solid #fce4ec;">
                </a>
            </div>
            @endif

            <div class="d-flex gap-3 mt-4 no-print">
                @if($payment->status === 'approved')
                <button onclick="window.print()" class="btn rounded-pill px-4 fw-semibold"
                        style="background:#fff0f7;color:#E91E8C;border:1px solid #fce4ec;font-size:0.88rem;">
                    <i class="fas fa-print me-2"></i>Print
                </button>
                <a href="{{ route('client.payments.receipt', $payment->id) }}"
                   class="btn rounded-pill px-4 fw-semibold"
                   style="background:linear-gradient(135deg,#E91E8C,#c2185b);color:#fff;border:none;font-size:0.88rem;">
                    <i class="fas fa-file-pdf me-2"></i>Download PDF
                </a>
                @endif
                <a href="{{ route('client.appointments.show', $payment->appointment->id) }}"
                   class="btn btn-outline-secondary rounded-pill px-4">
                    View Appointment
                </a>
            </div>
        </div>
    </div>
</div>
@endsection