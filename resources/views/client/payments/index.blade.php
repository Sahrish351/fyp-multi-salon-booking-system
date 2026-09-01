{{-- ============================================================ --}}
{{-- FILE: resources/views/client/payments/index.blade.php      --}}
{{-- ============================================================ --}}
@extends('layouts.client')
@section('title', 'My Payments — Glamora')

@push('styles')
<style>
    /* Global Font */
    .dashboard-font {
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
    }

    /* ── Stat Cards (Dashboard Match) ── */
    .stat-card-owner {
        background: #ffffff;
        border: 1px solid #fce4ec;
        border-radius: 14px;
        padding: 14px 18px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .stat-card-owner:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(255, 107, 157, 0.1);
        border-color: #FF6B9D;
    }
    .stat-card-owner .card-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        color: #fff;
        flex-shrink: 0;
        box-shadow: 0 3px 8px rgba(0,0,0,0.05);
    }
    .stat-card-owner .card-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: right;
    }
    .stat-card-owner .card-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #888888;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }
    .stat-card-owner .card-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: #222222;
        line-height: 1;
    }

    .bg-icon-purple { background: linear-gradient(135deg, #8A97E0, #6C7BD1); }
    .bg-icon-teal   { background: linear-gradient(135deg, #4FBE99, #3A9B7A); }
    .bg-icon-orange { background: linear-gradient(135deg, #FF9A54, #E67E36); }
    .bg-icon-red    { background: linear-gradient(135deg, #FF6B6B, #E54B4B); }

    /* ── Sleek Filter Tab Switcher ── */
    .filter-tab-container {
        background: #f8f9fa;
        border: 1px solid #fce4ec;
        border-radius: 50px;
        padding: 3px;
        display: inline-flex;
        gap: 2px;
    }
    .filter-tab-btn {
        padding: 5px 18px;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 700;
        color: #777;
        text-decoration: none !important;
        transition: all 0.2s ease;
        border: none;
    }
    .filter-tab-btn.active {
        background: #FF6B9D;
        color: #ffffff !important;
        box-shadow: 0 2px 8px rgba(255, 107, 157, 0.3);
    }
    .filter-tab-btn:hover:not(.active) {
        color: #FF6B9D;
        background: rgba(255, 107, 157, 0.08);
    }

    /* ── Table & Badges ── */
    .pay-status-chip {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 12px; border-radius: 50px;
        font-size: 0.72rem; font-weight: 700;
    }
    .pay-status-chip.paid      { background: rgba(34,197,94,0.1);  color: #16a34a; }
    .pay-status-chip.pending   { background: rgba(255,193,7,0.12); color: #b45309; }
    .pay-status-chip.cancelled { background: rgba(239,68,68,0.1);  color: #dc2626; }

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
        background: #fff0f7; color: #FF6B9D; border: 1px solid #fce4ec;
        text-decoration: none; transition: all 0.2s;
    }
    .pay-view-btn:hover { background: #FF6B9D; color: #fff; }

    /* ── Custom Pink Pagination (Appointments Style) ── */
    .custom-pink-pagination-wrap {
        display: flex;
        justify-content: center;
        margin-top: 24px;
        margin-bottom: 12px;
    }
    .custom-pink-pagination-wrap ul {
        display: flex;
        align-items: center;
        gap: 6px;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .custom-pink-pagination-wrap li a,
    .custom-pink-pagination-wrap li span {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #f4f5f7;
        color: #555;
        font-weight: 700;
        font-size: 0.85rem;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }
    .custom-pink-pagination-wrap li.active span {
        background: #FF6B9D !important;
        color: #ffffff !important;
        box-shadow: 0 4px 10px rgba(255, 107, 157, 0.35);
    }
    .custom-pink-pagination-wrap li a:hover {
        background: #fce4ec;
        color: #FF6B9D;
    }
    .custom-pink-pagination-wrap li.disabled span {
        background: #f8f9fa;
        color: #ccc;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')

<div class="mb-3">
    <h4 class="fw-bold mb-1 dashboard-font" style="color:#333;">
        <i class="fas fa-credit-card me-2" style="color:#FF6B9D;"></i>My Payments
    </h4>
    <p style="color:#aaa;font-size:0.85rem;margin:0;">Track all your appointment payments and their status</p>
</div>

{{-- Top Summary Cards (Dashboard Grid Style) --}}
<div class="row g-3 mb-3">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card-owner">
            <div class="card-icon bg-icon-purple">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="card-content">
                <div class="card-label">Total Payments</div>
                <div class="card-value">{{ $counts['total'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card-owner">
            <div class="card-icon bg-icon-teal">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="card-content">
                <div class="card-label">Paid</div>
                <div class="card-value">{{ $counts['paid'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card-owner">
            <div class="card-icon bg-icon-orange">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="card-content">
                <div class="card-label">Pending</div>
                <div class="card-value">{{ $counts['pending'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card-owner">
            <div class="card-icon bg-icon-red">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="card-content">
                <div class="card-label">Cancelled</div>
                <div class="card-value">{{ $counts['cancelled'] }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Modern Segmented Filter Pills --}}
<div class="mb-3">
    <div class="filter-tab-container">
        <a href="{{ route('client.payments.index') }}" class="filter-tab-btn {{ !request('status') ? 'active' : '' }}">All</a>
        <a href="{{ route('client.payments.index', ['status' => 'approved']) }}" class="filter-tab-btn {{ request('status') === 'approved' ? 'active' : '' }}">Paid</a>
        <a href="{{ route('client.payments.index', ['status' => 'pending']) }}" class="filter-tab-btn {{ request('status') === 'pending' ? 'active' : '' }}">Pending</a>
        <a href="{{ route('client.payments.index', ['status' => 'rejected']) }}" class="filter-tab-btn {{ request('status') === 'rejected' ? 'active' : '' }}">Cancelled</a>
    </div>
</div>

{{-- Payments Table --}}
<div class="rounded-4 overflow-hidden" style="border:1px solid #fce4ec; background:#fff;">
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
                    <td class="fw-semibold" style="color:#FF6B9D;">Rs. {{ number_format($payment->appointment->advance_amount) }}</td>
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
                            <i class="fas fa-receipt fa-3x mb-3" style="color:rgba(255, 107, 157,0.2);"></i>
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

{{-- Clean Pink Pagination Controls --}}
@if($payments->hasPages())
<div class="custom-pink-pagination-wrap">
    <ul>
        {{-- Previous Page Link --}}
        @if ($payments->onFirstPage())
            <li class="disabled"><span>&lsaquo;</span></li>
        @else
            <li><a href="{{ $payments->previousPageUrl() }}" rel="prev">&lsaquo;</a></li>
        @endif

        {{-- Page Numbers --}}
        @foreach ($payments->getUrlRange(1, $payments->lastPage()) as $page => $url)
            @if ($page == $payments->currentPage())
                <li class="active"><span>{{ $page }}</span></li>
            @else
                <li><a href="{{ $url }}">{{ $page }}</a></li>
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($payments->hasMorePages())
            <li><a href="{{ $payments->nextPageUrl() }}" rel="next">&rsaquo;</a></li>
        @else
            <li class="disabled"><span>&rsaquo;</span></li>
        @endif
    </ul>
</div>
@endif

@endsection