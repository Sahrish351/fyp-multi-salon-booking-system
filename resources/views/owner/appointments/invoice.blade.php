@extends('layouts.owner')

@section('title', 'Invoice')

@section('content')

    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>
                <i class="bi bi-receipt me-2 text-primary"></i>
                Invoice #{{ $appointment['id'] }}
            </h2>
            <p>{{ $appointment['booking_ref'] }} &middot; {{ $appointment['date'] }}</p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-edit-action">
                <i class="bi bi-printer-fill me-2"></i> Print
            </button>
            <a href="{{ route('owner.appointments.show', ['appointment' => $appointment['id']]) }}" class="btn btn-back">
                <i class="bi bi-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>

    <div class="panel-card invoice-card">

        <!-- Invoice Header -->
        <div class="invoice-header">
            <div class="invoice-brand">
                <div class="brand-icon-wrapper">
                    <i class="bi bi-stars"></i>
                </div>
                <div>
                    <h3 class="invoice-salon-name">{{ $salon->name ?? 'Salon Name' }}</h3>
                    <p class="invoice-salon-address">{{ $salon->address ?? 'Salon Address' }}</p>
                    <p class="invoice-salon-address">{{ $salon->phone ?? 'Phone' }}</p>
                </div>
            </div>
            <div class="text-end">
                <div class="invoice-number">Invoice #{{ $appointment['id'] }}</div>
                <div class="invoice-date">{{ $appointment['date'] }}</div>
                <div class="invoice-ref">Ref: {{ $appointment['booking_ref'] }}</div>
            </div>
        </div>

        <hr class="my-4">

        <!-- Client & Service Info -->
        <div class="row g-4 mb-4">
            <div class="col-6">
                <div class="invoice-section">
                    <p class="invoice-section-title">Billed To</p>
                    <p class="invoice-detail-text"><strong>{{ $appointment['client_name'] }}</strong></p>
                    <p class="invoice-detail-text">{{ $appointment['client_email'] }}</p>
                    <p class="invoice-detail-text">{{ $appointment['client_phone'] }}</p>
                </div>
            </div>
            <div class="col-6 text-end">
                <div class="invoice-section">
                    <p class="invoice-section-title">Service Provided By</p>
                    <p class="invoice-detail-text"><strong>{{ $appointment['stylist'] }}</strong></p>
                    <p class="invoice-detail-text">{{ $appointment['time_range'] }}</p>
                </div>
            </div>
        </div>

        <!-- Invoice Table -->
        <div class="table-responsive">
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="text-end">Amount (PKR)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <span class="fw-semibold">{{ $appointment['service'] }}</span>
                        </td>
                        <td class="text-end amount-gold">{{ number_format($appointment['price']) }}</td>
                    </tr>
                    @if(isset($appointment['advance_amount']) && $appointment['advance_amount'] > 0)
                    <tr>
                        <td><span class="text-muted">Advance Payment</span></td>
                        <td class="text-end text-muted">- {{ number_format($appointment['advance_amount']) }}</td>
                    </tr>
                    <tr class="border-top">
                        <td><strong>Balance Due</strong></td>
                        <td class="text-end amount-gold"><strong>{{ number_format($appointment['price'] - $appointment['advance_amount']) }}</strong></td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Total -->
        <div class="invoice-total-row">
            <span>Total</span>
            <span class="invoice-total-amount">PKR {{ number_format($appointment['price']) }}</span>
        </div>

        <!-- Payment Status -->
        <div class="invoice-payment-status mt-3">
            <div class="payment-status-badge">
                <span class="badge-status {{ $appointment['payment_status'] === 'approved' ? 'badge-completed' : 'badge-pending' }}">
                    <i class="bi bi-credit-card me-1"></i> {{ ucfirst($appointment['payment_status'] ?? 'Pending') }}
                </span>
                <span class="mx-2 text-muted">|</span>
                <span class="text-muted">Method: <strong>{{ ucfirst($appointment['payment_method'] ?? 'N/A') }}</strong></span>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="invoice-footer">
            <p class="invoice-footer-note">Thank you for choosing <strong>{{ $salon->name ?? 'us' }}</strong>!</p>
            <p class="invoice-footer-note-small">For any queries, please contact us at {{ $salon->phone ?? 'N/A' }}</p>
        </div>

    </div>

@endsection

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
        display: inline-flex;
        align-items: center;
        transition: all 0.18s ease;
    }
    .btn-edit-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(232, 85, 136, 0.45);
        color: #ffffff !important;
    }

    /* ===================== INVOICE CARD ===================== */
    .invoice-card {
        max-width: 720px;
        margin: 0 auto;
        padding: 2rem !important;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    /* ===================== INVOICE HEADER ===================== */
    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 16px;
    }
    .invoice-brand {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .brand-icon-wrapper {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        background: linear-gradient(135deg, #FF6B9D, #E85588);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #fff;
        box-shadow: 0 4px 12px rgba(232, 85, 136, 0.3);
        flex-shrink: 0;
    }
    .invoice-salon-name {
        font-size: 20px;
        font-weight: 700;
        color: var(--plum-800);
        margin: 0 0 2px;
    }
    .invoice-salon-address {
        font-size: 13px;
        color: var(--ink-700);
        margin: 0;
        line-height: 1.5;
    }

    .invoice-number {
        font-size: 18px;
        font-weight: 700;
        color: #E85588;
    }
    .invoice-date {
        font-size: 14px;
        color: var(--ink-700);
        margin-top: 4px;
    }
    .invoice-ref {
        font-size: 13px;
        color: var(--ink-500);
        margin-top: 2px;
    }

    /* ===================== INVOICE SECTIONS ===================== */
    .invoice-section-title {
        font-size: 12.5px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--ink-500);
        margin-bottom: 6px;
        letter-spacing: 0.5px;
    }
    .invoice-detail-text {
        font-size: 14px;
        color: var(--plum-900);
        margin: 0 0 2px;
        line-height: 1.6;
    }

    /* ===================== INVOICE TABLE ===================== */
    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin: 16px 0;
    }
    .invoice-table thead th {
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: var(--ink-500);
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 10px 12px;
        border-bottom: 2px solid var(--blush-200);
    }
    .invoice-table tbody td {
        padding: 12px 12px;
        font-size: 14px;
        color: var(--ink-900);
        border-bottom: 1px solid var(--blush-100);
    }
    .invoice-table tbody tr:last-child td {
        border-bottom: none;
    }
    .invoice-table .border-top td {
        border-top: 2px solid var(--blush-200);
        padding-top: 14px;
    }
    .amount-gold {
        color: #E85588;
        font-weight: 700;
    }
    .text-end {
        text-align: right;
    }
    .fw-semibold {
        font-weight: 600;
    }

    /* ===================== TOTAL ROW ===================== */
    .invoice-total-row {
        display: flex;
        justify-content: space-between;
        font-size: 20px;
        font-weight: 700;
        color: var(--plum-900);
        border-top: 2px solid var(--blush-200);
        padding-top: 18px;
        margin-top: 8px;
    }
    .invoice-total-amount {
        color: #E85588;
        font-size: 22px;
    }

    /* ===================== PAYMENT STATUS ===================== */
    .invoice-payment-status {
        display: flex;
        justify-content: center;
        align-items: center;
        padding-top: 16px;
        margin-top: 16px;
        border-top: 1px solid var(--blush-100);
    }
    .payment-status-badge {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        color: var(--ink-700);
    }
    .badge-status {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-completed { background: #D4EDDA; color: #155724; }
    .badge-pending { background: #FFF3CD; color: #856404; }
    .text-muted {
        color: #8a7a88 !important;
    }
    .mx-2 {
        margin-left: 8px;
        margin-right: 8px;
    }

    /* ===================== FOOTER ===================== */
    .invoice-footer {
        text-align: center;
        margin-top: 24px;
        padding-top: 16px;
        border-top: 1px solid var(--blush-100);
    }
    .invoice-footer-note {
        font-size: 15px;
        color: var(--ink-700);
        margin: 0;
    }
    .invoice-footer-note-small {
        font-size: 12px;
        color: var(--ink-500);
        margin: 4px 0 0;
    }

    /* ===================== HR ===================== */
    hr {
        border-color: var(--blush-200);
        opacity: 0.5;
        margin: 16px 0;
    }

    /* ===================== RESPONSIVE ===================== */
    @media (max-width: 576px) {
        .invoice-card {
            padding: 1.2rem !important;
        }
        .invoice-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .invoice-brand {
            width: 100%;
        }
        .text-end {
            text-align: left !important;
            width: 100%;
        }
        .invoice-number {
            font-size: 16px;
        }
        .invoice-total-row {
            font-size: 17px;
        }
        .invoice-total-amount {
            font-size: 19px;
        }
        .info-grid {
            grid-template-columns: 1fr !important;
        }
        .row.g-4 {
            flex-direction: column;
        }
        .col-6 {
            width: 100%;
        }
    }

    /* ===================== PRINT ===================== */
    @media print {
        .sidebar, .page-header a, .page-header button {
            display: none !important;
        }
        .page-header h2, .page-header p {
            text-align: center !important;
        }
        .invoice-card {
            box-shadow: none !important;
            border: none !important;
            padding: 20px !important;
        }
        .brand-icon-wrapper {
            background: #E85588 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .badge-status {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }

    /* ===================== GENERAL ===================== */
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
    }
    .text-primary {
        color: #E85588 !important;
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
</style>
@endsection