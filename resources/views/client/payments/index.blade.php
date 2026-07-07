{{-- ============================================================ --}}
{{-- FILE: resources/views/client/payments/index.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.client')
@section('title', 'My Payments — Glamora')

@push('styles')
<style>
    .pay-stat-card {
        background: #fff;
        border: 1px solid #fce4ec;
        border-radius: 16px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 14px;
        height: 100%;
    }
    .pay-stat-card .icon-box {
        width: 46px; height: 46px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; font-size: 1.05rem;
    }
    .pay-stat-card .val { font-size: 1.4rem; font-weight: 800; color: #333; line-height: 1; }
    .pay-stat-card .lbl { color: #aaa; font-size: 0.78rem; margin-top: 4px; }

    .pay-status-chip {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 12px; border-radius: 50px;
        font-size: 0.72rem; font-weight: 700;
    }
    .pay-status-chip.paid      { background: rgba(34,197,94,0.1);  color: #16a34a; }
    .pay-status-chip.pending   { background: rgba(255,193,7,0.12); color: #b45309; }
    .pay-status-chip.cancelled { background: rgba(239,68,68,0.1);  color: #dc2626; }

    .filter-pill {
        padding: 6px 16px; border-radius: 50px; font-size: 0.82rem; font-weight: 600;
        text-decoration: none;
    }
    .filter-pill.active { background: linear-gradient(135deg,#E91E8C,#c2185b); color: #fff; }
    .filter-pill:not(.active) { background: #fff; color: #888; border: 1px solid #fce4ec; }

    .pay-table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
    }
    .pay-table th {
        text-align: left;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #aaa;
        font-weight: 700;
        padding: 12px 16px;
        background: #fff5f9;
        border-bottom: 1px solid #fce4ec;
    }
    .pay-table td {
        padding: 14px 16px;
        font-size: 0.85rem;
        color: #333;
        border-bottom: 1px solid #fce4ec;
        vertical-align: middle;
    }
    .pay-table tr:last-child td { border-bottom: none; }
    .pay-view-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 14px; border-radius: 8px;
        font-size: 0.78rem; font-weight: 600;
        background: #fff0f7; color: #E91E8C; border: 1px solid #fce4ec;
        text-decoration: none;
    }
    .pay-view-btn:hover { background: #E91E8C; color: #fff; }
</style>
@endpush

@section('content')

<div class="mb-4">
    <h4 class="fw-bold mb-1" style="color:#333;font-family:'Playfair Display',serif;">
        <i class="fas fa-credit-card me-2" style="color:#E91E8C;"></i>My Payments
    </h4>
    <p style="color:#aaa;font-size:0.85rem;margin:0;">Track all your appointment payments and their status</p>
</div>

{{-- Top Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="pay-stat-card" style="background:#f3e8ff;border-color:#e9d5ff;">
            <div class="icon-box" style="background:#e9d5ff;color:#7c3aed;">
                <i class="fas fa-receipt"></i>
            </div>
            <div>
                <div class="val" style="color:#6b21a8;">{{ $counts['total'] }}</div>
                <div class="lbl" style="color:#a78bda;">Total Payments</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="pay-stat-card" style="background:#dcfce7;border-color:#bbf7d0;">
            <div class="icon-box" style="background:#bbf7d0;color:#16a34a;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <div class="val" style="color:#15803d;">{{ $counts['paid'] }}</div>
                <div class="lbl" style="color:#4d9d6b;">Paid</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="pay-stat-card" style="background:#fef9c3;border-color:#fef08a;">
            <div class="icon-box" style="background:#fef08a;color:#b45309;">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div>
                <div class="val" style="color:#a16207;">{{ $counts['pending'] }}</div>
                <div class="lbl" style="color:#b58f3d;">Pending</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="pay-stat-card" style="background:#fee2e2;border-color:#fecaca;">
            <div class="icon-box" style="background:#fecaca;color:#dc2626;">
                <i class="fas fa-times-circle"></i>
            </div>
            <div>
                <div class="val" style="color:#b91c1c;">{{ $counts['cancelled'] }}</div>
                <div class="lbl" style="color:#c96b6b;">Cancelled</div>
            </div>
        </div>
    </div>
</div>

{{-- Status Filter --}}
<div class="d-flex gap-2 mb-4 flex-wrap">
    <a href="{{ route('client.payments.index') }}" class="filter-pill {{ !request('status') ? 'active' : '' }}">All</a>
    <a href="{{ route('client.payments.index', ['status' => 'approved']) }}" class="filter-pill {{ request('status') === 'approved' ? 'active' : '' }}">Paid</a>
    <a href="{{ route('client.payments.index', ['status' => 'pending']) }}" class="filter-pill {{ request('status') === 'pending' ? 'active' : '' }}">Pending</a>
    <a href="{{ route('client.payments.index', ['status' => 'rejected']) }}" class="filter-pill {{ request('status') === 'rejected' ? 'active' : '' }}">Cancelled</a>
</div>

{{-- Payments Table --}}
<div class="rounded-4 overflow-hidden" style="border:1px solid #fce4ec;">
    <div style="overflow-x:auto;">
        <table class="pay-table">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Appointment ID</th>
                    <th>Salon</th>
                    <th>Service</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                @php
                    $statusMap = [
                        'approved' => ['label' => 'Paid',      'class' => 'paid'],
                        'pending'  => ['label' => 'Pending',   'class' => 'pending'],
                        'rejected' => ['label' => 'Cancelled', 'class' => 'cancelled'],
                    ];
                    $st = $statusMap[$payment->status] ?? ['label' => ucfirst($payment->status), 'class' => 'pending'];
                @endphp
                <tr>
                    <td>#{{ $payment->id }}</td>
                    <td>#{{ $payment->appointment->id }}</td>
                    <td>{{ $payment->appointment->salon->name ?? '—' }}</td>
                    <td>{{ $payment->appointment->service->name ?? '—' }}</td>
                    <td class="fw-semibold" style="color:#E91E8C;">Rs. {{ number_format($payment->appointment->advance_amount) }}</td>
                    <td>{{ $payment->created_at->format('d M Y') }}</td>
                    <td><span class="pay-status-chip {{ $st['class'] }}">{{ $st['label'] }}</span></td>
                    <td>
                        <a href="{{ route('client.payments.show', $payment->id) }}" class="pay-view-btn">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="text-center py-5">
                            <i class="fas fa-receipt fa-3x mb-3" style="color:rgba(233,30,140,0.2);"></i>
                            <h6 style="color:#333;">No payments yet</h6>
                            <p style="color:#aaa;font-size:0.85rem;margin:0;">Your appointment payments will show up here</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($payments->hasPages())
<div class="mt-4">{{ $payments->links() }}</div>
@endif

@endsection