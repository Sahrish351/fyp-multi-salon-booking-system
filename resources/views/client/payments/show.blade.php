@extends('layouts.client')
@section('title', 'Payment Details — Beauty Blush Salons')
 
@push('styles')
<style>
    @media print {
        .no-print { display: none !important; }
    }
 
    /* ── Common card (har container ke liye) ── */
    .pd-card {
        background: #fff;
        border: 1px solid #fce4ec;
        border-radius: 18px;
        padding: 22px;
        margin-bottom: 20px;
        box-shadow: 0 2px 12px rgba(255, 107, 157, 0.05);
    }
    .pd-card-title {
        display: flex; align-items: center; gap: 10px;
        font-size: 0.95rem; font-weight: 800; color: #333;
        margin-bottom: 14px;
    }
    .pd-card-title .pd-ico {
        width: 32px; height: 32px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        background: #fff0f7; color: #FF6B9D; font-size: 0.85rem;
        flex-shrink: 0;
    }
 
    /* ── Detail rows ── */
    .pay-detail-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 12px 0; border-bottom: 1px dashed #fce4ec;
        font-size: 0.88rem;
    }
    .pay-detail-row:last-child { border-bottom: none; padding-bottom: 0; }
    .pay-detail-row .k { color: #999; font-weight: 600; }
    .pay-detail-row .v { color: #1a1a1a; font-weight: 700; text-align: right; }
 
    .pay-status-chip {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 16px; border-radius: 50px;
        font-size: 0.8rem; font-weight: 700;
    }
    .pay-status-chip.paid      { background: rgba(34,197,94,0.1);  color: #16a34a; }
    .pay-status-chip.pending   { background: rgba(255,193,7,0.12); color: #b45309; }
    .pay-status-chip.cancelled { background: rgba(239,68,68,0.1);  color: #dc2626; }
 
    /* ── Status containers (Rejected / Pending / Paid) ── */
    .pd-reject { background: #fff5f5; border: 1px solid #fecaca; }
    .pd-reject .pd-card-title { color: #b91c1c; }
    .pd-reject .pd-card-title .pd-ico { background: rgba(239,68,68,0.12); color: #dc2626; }
    .pd-reject-text { color: #b91c1c; font-size: 0.88rem; font-weight: 600; line-height: 1.6; margin: 0; }
 
    .pd-wait { background: #fffbeb; border: 1px solid rgba(255,193,7,0.45); }
    .pd-wait .pd-card-title { color: #92400e; }
    .pd-wait .pd-card-title .pd-ico { background: rgba(255,193,7,0.2); color: #b45309; }
    .pd-wait-text { color: #92400e; font-size: 0.86rem; line-height: 1.6; margin: 0; }
 
    .pd-ok { background: #f0fdf4; border: 1px solid #bbf7d0; }
    .pd-ok .pd-card-title { color: #15803d; }
    .pd-ok .pd-card-title .pd-ico { background: rgba(34,197,94,0.15); color: #16a34a; }
    .pd-ok-text { color: #166534; font-size: 0.86rem; line-height: 1.6; margin: 0; }
 
    /* ── Screenshot container ── */
    .pd-shot-wrap {
        max-height: 460px;
        overflow-y: auto;
        border-radius: 12px;
        border: 1px solid #fce4ec;
        background: #fffafc;
    }
    .pd-shot-wrap img { width: 100%; display: block; }
    .pd-shot-hint { font-size: 0.72rem; color: #aaa; margin-top: 8px; }
    .pd-shot-empty {
        text-align: center; padding: 30px 10px; color: #bbb; font-size: 0.85rem;
    }
    .pd-shot-empty i { font-size: 2rem; color: rgba(255,107,157,0.25); display: block; margin-bottom: 8px; }
 
    /* ── Resubmit box ── */
    .resubmit-box {
        background: #fff8fb;
        border: 1.5px solid #fce4ec;
        scroll-margin-top: 90px;
    }
    .resubmit-box h6 { font-weight: 800; color: #333; margin-bottom: 4px; }
    .resubmit-deadline {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,193,7,0.14); color: #b45309;
        border-radius: 50px; padding: 5px 14px;
        font-size: 0.76rem; font-weight: 700; margin: 6px 0 14px;
    }
    .rs-field { margin-bottom: 14px; }
    .rs-field label { display: block; font-size: 0.76rem; font-weight: 700; color: #555; margin-bottom: 6px; }
    .rs-field input[type="text"],
    .rs-field input[type="tel"],
    .rs-field select,
    .rs-field input[type="file"] {
        width: 100%; border: 1.5px solid #f0d5e0; border-radius: 12px;
        padding: 11px 14px; font-size: 0.86rem; background: #fff; color: #1a1a1a;
    }
    .rs-field input:focus, .rs-field select:focus { outline: none; border-color: #FF6B9D; }
    .rs-error { color: #dc2626; font-size: 0.74rem; margin-top: 4px; }
    .rs-errors-box {
        background: #fff5f5; border: 1px solid #fecaca; border-radius: 10px;
        padding: 10px 14px; font-size: 0.78rem; color: #dc2626; margin-bottom: 14px;
    }
    .rs-submit {
        width: 100%; border: none; border-radius: 50px; padding: 12px;
        background: #FF6B9D; color: #fff; font-weight: 700; font-size: 0.9rem;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        cursor: pointer; transition: all 0.2s;
    }
    .rs-submit:hover { background: #e85588; }
    .rs-submit:disabled { opacity: 0.65; cursor: not-allowed; }
 
    .info-notice {
        border-radius: 14px; padding: 14px 18px; margin-bottom: 20px;
        font-size: 0.84rem; line-height: 1.5;
    }
    .info-notice.warn { background: #fff5f5; border: 1px solid #fecaca; color: #b91c1c; }
    .info-notice.wait { background: rgba(255,193,7,0.1); border: 1px solid rgba(255,193,7,0.4); color: #92400e; }
</style>
@endpush
 
@section('content')
@php
    $statusMap = [
        'approved' => ['label' => 'Paid',     'class' => 'paid',      'icon' => 'fa-check-circle'],
        'pending'  => ['label' => 'Pending',  'class' => 'pending',   'icon' => 'fa-hourglass-half'],
        'rejected' => ['label' => 'Rejected', 'class' => 'cancelled', 'icon' => 'fa-times-circle'],
    ];
    $st = $statusMap[$payment->status] ?? ['label' => ucfirst($payment->status), 'class' => 'pending', 'icon' => 'fa-circle'];
 
    $isRejected       = $payment->status === 'rejected';
    $canResubmit      = $payment->canResubmit();
    $deadline         = $payment->resubmitDeadline();
    $bookingCancelled = $isRejected && optional($payment->appointment)->status === 'cancelled';
@endphp
 
<div class="mb-4 no-print">
    <a href="{{ route('client.payments.index') }}" style="color:#aaa;text-decoration:none;font-size:0.85rem;">
        <i class="fas fa-arrow-left me-2"></i>Back to Payments
    </a>
    <h4 class="fw-bold mt-2 mb-0" style="color:#333;font-family:'Playfair Display',serif;">
        <i class="fas fa-credit-card me-2" style="color:#FF6B9D;"></i>Payment Details
    </h4>
</div>
 
<div class="row g-4">
 
    {{-- ================= LEFT SIDE ================= --}}
    <div class="col-12 col-lg-6">
 
        {{-- 1) Payment info container --}}
        <div class="pd-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
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
                <span class="v" style="color:#FF6B9D;">Rs. {{ number_format($payment->appointment->advance_amount) }}</span>
            </div>
            <div class="pay-detail-row">
                <span class="k">Payment Method</span>
                <span class="v">{{ ucfirst($payment->method ?? '—') }}</span>
            </div>
            <div class="pay-detail-row">
                <span class="k">Payment Date</span>
                <span class="v">{{ $payment->created_at->format('d M Y, h:i A') }}</span>
            </div>
        </div>
 
        {{-- 2) Status container: Rejected / Pending / Paid --}}
        @if($isRejected)
            <div class="pd-card pd-reject">
                <div class="pd-card-title">
                    <span class="pd-ico"><i class="fas fa-exclamation-triangle"></i></span>
                    Payment Rejected
                </div>
                <div style="color:#999;font-size:0.72rem;font-weight:700;margin-bottom:4px;">Reason for Rejection</div>
                <p class="pd-reject-text">
                    {{ ($payment->rejection_reason ?? false) ? $payment->rejection_reason : 'No reason was given by the salon.' }}
                </p>
            </div>
        @elseif($payment->status === 'pending')
            <div class="pd-card pd-wait">
                <div class="pd-card-title">
                    <span class="pd-ico"><i class="fas fa-hourglass-half"></i></span>
                    Waiting for Approval
                </div>
                <p class="pd-wait-text">
                    Your payment has been sent to the salon. They will check it and confirm your booking soon.
                    You will get a notification as soon as it is reviewed.
                </p>
            </div>
        @elseif($payment->status === 'approved')
            <div class="pd-card pd-ok">
                <div class="pd-card-title">
                    <span class="pd-ico"><i class="fas fa-check-circle"></i></span>
                    Payment Confirmed
                </div>
                <p class="pd-ok-text">
                    The salon has verified your payment. You can download the receipt below.
                </p>
            </div>
        @endif
 
        {{-- 3) Buttons --}}
        <div class="d-flex flex-wrap gap-3 no-print">
            @if($payment->status === 'approved')
            <button onclick="window.print()" class="btn rounded-pill px-4 fw-semibold"
                    style="background:#fff0f7;color:#FF6B9D;border:1px solid #fce4ec;font-size:0.88rem;">
                <i class="fas fa-print me-2"></i>Print
            </button>
            @endif
            <a href="{{ route('client.payments.receipt', $payment->id) }}"
               class="btn rounded-pill px-4 fw-semibold"
               style="background:#FF6B9D;color:#fff;border:none;font-size:0.88rem;">
                <i class="fas fa-file-pdf me-2"></i>Download Receipt
            </a>
            <a href="{{ route('client.appointments.show', $payment->appointment->id) }}"
               class="btn btn-outline-secondary rounded-pill px-4">
                View Appointment
            </a>
        </div>
 
    </div>
 
    {{-- ================= RIGHT SIDE ================= --}}
    <div class="col-12 col-lg-6">
 
        {{-- 4) Rejected payment: dobara bhejne ka container --}}
        @if($isRejected)
 
            @if($canResubmit)
                <div class="pd-card resubmit-box no-print" id="resubmitCard">
                    <h6><i class="fas fa-redo me-2" style="color:#FF6B9D;"></i>Submit Payment Again</h6>
                    <p style="color:#888;font-size:0.82rem;margin:0;">
                        Please send a clear screenshot of your payment. Use the same account details shown on the booking payment page.
                    </p>
 
                    <div class="resubmit-deadline">
                        <i class="fas fa-clock"></i>
                        Resubmit before {{ $deadline->format('d M Y, h:i A') }}, or your booking will be cancelled
                    </div>
 
                    @if($errors->any())
                        <div class="rs-errors-box">
                            @foreach($errors->all() as $err)<div>• {{ $err }}</div>@endforeach
                        </div>
                    @endif
 
                    <form action="{{ route('client.payments.resubmit', $payment->id) }}" method="POST"
                          enctype="multipart/form-data" id="resubmitForm">
                        @csrf
 
                        <div class="rs-field">
                            <label>Payment Method</label>
                            <select name="method" required>
                                <option value="easypaisa" {{ old('method', $payment->method) === 'easypaisa' ? 'selected' : '' }}>EasyPaisa</option>
                                <option value="jazzcash"  {{ old('method', $payment->method) === 'jazzcash'  ? 'selected' : '' }}>JazzCash</option>
                                <option value="bank"      {{ old('method', $payment->method) === 'bank'      ? 'selected' : '' }}>Bank</option>
                            </select>
                        </div>
 
                        <div class="rs-field">
                            <label>Transaction / Reference Number</label>
                            <input type="text" name="transaction_ref" required maxlength="100"
                                   placeholder="e.g. TXN0012345678" value="{{ old('transaction_ref') }}">
                        </div>
 
                        <div class="rs-field">
                            <label>Your Mobile Number (paid from)</label>
                            <input type="tel" name="sender_number" required maxlength="20"
                                   placeholder="03XX-XXXXXXX" value="{{ old('sender_number', $payment->sender_number) }}">
                        </div>
 
                        <div class="rs-field">
                            <label>New Payment Screenshot</label>
                            <input type="file" name="screenshot" accept="image/*" required>
                            <div style="font-size:0.7rem;color:#aaa;margin-top:4px;">JPG · PNG · WEBP · max 5 MB</div>
                        </div>
 
                        <button type="submit" class="rs-submit" id="resubmitBtn">
                            <i class="fas fa-paper-plane"></i> Submit Payment Again
                        </button>
                    </form>
                </div>
 
            @elseif($bookingCancelled)
                <div class="info-notice warn no-print">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    This booking was cancelled because the payment was not re-submitted in time.
                    You can make a new booking whenever you like.
                    <div class="mt-2">
                        <a href="{{ route('salons.index') }}" style="color:#b91c1c;font-weight:700;">Browse salons</a>
                    </div>
                </div>
 
            @else
                <div class="info-notice wait no-print">
                    <i class="fas fa-hourglass-end me-2"></i>
                    The time to re-submit this payment has ended. Your booking will be cancelled shortly.
                </div>
            @endif
 
        @endif
 
        {{-- 5) Uploaded screenshot container (hamesha right side par, taake side khali na rahe) --}}
        <div class="pd-card">
            <div class="pd-card-title">
                <span class="pd-ico"><i class="fas fa-image"></i></span>
                {{ $isRejected ? 'Rejected Screenshot' : 'Uploaded Screenshot' }}
            </div>
 
            @if($payment->screenshot)
                <div class="pd-shot-wrap">
                    <a href="{{ asset('storage/'.$payment->screenshot) }}" target="_blank">
                        <img src="{{ asset('storage/'.$payment->screenshot) }}" alt="Payment screenshot">
                    </a>
                </div>
                <div class="pd-shot-hint no-print">Click the image to open it full size.</div>
            @else
                <div class="pd-shot-empty">
                    <i class="fas fa-image"></i>
                    No screenshot uploaded for this payment.
                </div>
            @endif
        </div>
 
    </div>
 
</div>
 
@if($isRejected && $canResubmit)
<script>
    document.getElementById('resubmitForm').addEventListener('submit', function () {
        var b = document.getElementById('resubmitBtn');
        b.disabled = true;
        b.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
    });
</script>
@endif
@endsection
 