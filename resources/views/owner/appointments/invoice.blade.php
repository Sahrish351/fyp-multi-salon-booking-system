
@extends('layouts.owner')

@section('title', 'Invoice')

@section('content')

    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>Invoice #{{ $appointment['id'] }}</h2>
            <p>GlowAura Luxury Salon</p>
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

        <div class="invoice-header">
            <div>
                <h3 class="invoice-salon-name">GlowAura Luxury Salon</h3>
                <p class="invoice-salon-address">123 Luxury Avenue, Beverly Hills, CA 90210</p>
            </div>
            <div class="text-end">
                <p class="invoice-label">Invoice #{{ $appointment['id'] }}</p>
                <p class="invoice-label">{{ $appointment['date'] }}</p>
            </div>
        </div>

        <hr class="my-4">

        <div class="row mb-4">
            <div class="col-6">
                <p class="invoice-section-title">Billed To</p>
                <p class="invoice-detail-text">{{ $appointment['client_name'] }}</p>
                <p class="invoice-detail-text">{{ $appointment['client_email'] }}</p>
                <p class="invoice-detail-text">{{ $appointment['client_phone'] }}</p>
            </div>
            <div class="col-6 text-end">
                <p class="invoice-section-title">Service Provided By</p>
                <p class="invoice-detail-text">{{ $appointment['stylist'] }}</p>
                <p class="invoice-detail-text">{{ $appointment['time_range'] }}</p>
            </div>
        </div>

        <table class="table-custom mb-4">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="cell-name">{{ $appointment['service'] }}</td>
                    <td class="text-end amount-gold">${{ $appointment['price'] }}</td>
                </tr>
            </tbody>
        </table>

        <div class="invoice-total-row">
            <span>Total</span>
            <span class="invoice-total-amount">${{ $appointment['price'] }}</span>
        </div>

        <p class="invoice-footer-note mt-4">Thank you for choosing GlowAura Luxury Salon!</p>

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
        display: inline-flex; align-items: center;
    }
    .btn-edit-action:hover { color: var(--plum-900); }

    .invoice-card { max-width: 720px; margin: 0 auto; }

    .invoice-header { display: flex; justify-content: space-between; align-items: flex-start; }
    .invoice-salon-name { font-size: 20px; font-weight: 700; color: var(--plum-800); margin: 0 0 4px; }
    .invoice-salon-address { font-size: 13px; color: var(--ink-700); margin: 0; }
    .invoice-label { font-size: 13.5px; color: var(--ink-700); margin: 0; }

    .invoice-section-title { font-size: 12.5px; font-weight: 700; text-transform: uppercase; color: var(--ink-500); margin-bottom: 6px; }
    .invoice-detail-text { font-size: 14px; color: var(--plum-900); margin: 0 0 2px; }

    .invoice-total-row {
        display: flex;
        justify-content: space-between;
        font-size: 18px;
        font-weight: 700;
        color: var(--plum-900);
        border-top: 2px solid var(--blush-200);
        padding-top: 16px;
    }
    .invoice-total-amount { color: var(--gold-600); }

    .invoice-footer-note { text-align: center; font-size: 13px; color: var(--ink-500); }

    @media print {
        .sidebar, .page-header a, .page-header button { display: none !important; }
    }
</style>
@endsection
