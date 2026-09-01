{{-- ============================================================ --}}
{{-- FILE: resources/views/client/dashboard.blade.php            --}}
{{-- ============================================================ --}}
@extends('layouts.client')
@section('title', 'Dashboard — Glamora')
@section('content')

<div class="ps-4">

<style>
    /* Global Font Consistency */
    .dashboard-font {
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
    }

    .card-dashboard {
        background: #ffffff;
        border: 1px solid #fce4ec;
        border-radius: 20px;
        transition: all .3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .card-dashboard:hover {
        border-color: #FF6B9D;
        box-shadow: 0 8px 25px rgba(255,107,157,0.08);
        transform: translateY(-2px);
    }

    /* ── Soft Pink Buttons ── */
    .btn-pink-gradient {
        background: linear-gradient(135deg, #FF6B9D, #FF8DAF);
        color: #fff; 
        border: none; 
        font-weight: 600; 
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(255, 107, 157, 0.2);
    }
    .btn-pink-gradient:hover, 
    .btn-pink-gradient:focus, 
    .btn-pink-gradient:active {
        background: linear-gradient(135deg, #FF7BA6, #FF99B9) !important;
        color: #fff !important; 
        box-shadow: 0 6px 15px rgba(255, 107, 157, 0.3);
        transform: translateY(-1px);
    }

    .btn-pink-outline {
        border: 1px solid #FF6B9D; color: #FF6B9D;
        background: transparent; font-weight: 600; transition: all 0.3s;
    }
    .btn-pink-outline:hover { background: #FF6B9D; color: #fff; }

    /* ── Stat Cards ── */
    .stat-card-owner {
        background: #ffffff;
        border: 1px solid #fce4ec;
        border-radius: 18px;
        padding: 22px 24px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        min-height: 110px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }
    .stat-card-owner:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(255, 107, 157, 0.12);
        border-color: #FF6B9D;
    }
    .stat-card-owner .card-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: #fff;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    .stat-card-owner .card-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: right;
    }
    .stat-card-owner .card-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #888888;
        letter-spacing: 0.6px;
        margin-bottom: 4px;
    }
    .stat-card-owner .card-value {
        font-size: 2rem;
        font-weight: 800;
        color: #222222;
        line-height: 1;
    }

    .bg-icon-pink    { background: linear-gradient(135deg, #FF6B9D, #E85588); }
    .bg-icon-purple  { background: linear-gradient(135deg, #8A97E0, #6C7BD1); }
    .bg-icon-teal    { background: linear-gradient(135deg, #4FBE99, #3A9B7A); }
    .bg-icon-orange  { background: linear-gradient(135deg, #FF9A54, #E67E36); }

    /* Quick Actions */
    .quick-action-box {
        background: #fafafa;
        border: 1px solid #fce4ec;
        transition: all .2s;
        padding: 10px 4px;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-decoration: none !important;
        overflow: hidden;
    }
    .quick-action-box:hover {
        background: #fce4ec !important;
        border-color: #FF6B9D !important;
    }
    .quick-action-label {
        color: #666;
        font-size: 0.58rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0;
        margin-top: 5px;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        max-width: 100%;
        text-align: center;
    }

    /* ---- Bar Chart ---- */
    .chart-axis-wrap {
        position: relative;
        padding: 10px 16px 28px 16px;
    }
    .chart-container {
        position: relative;
        display: flex;
        align-items: flex-end;
        justify-content: space-around;
        padding: 0 10px;
        gap: 14px;
        height: 200px;
    }
    .chart-bar-wrapper { display: flex; flex-direction: column; align-items: center; flex: 1; cursor: pointer; max-width: 60px; position: relative; }
    .chart-bar {
        width: 100%;
        max-width: 34px;
        border-radius: 14px 14px 5px 5px;
        min-height: 4px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 6px 14px rgba(194,70,126,0.22);
        transition: height 0.8s cubic-bezier(.34,1.56,.64,1), transform 0.2s ease, box-shadow 0.2s ease;
    }
    .chart-bar-wrapper:hover .chart-bar {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(194,70,126,0.32);
    }
    .chart-bar-label {
        font-size: 0.62rem; color: #b98; margin-top: 8px;
        text-transform: uppercase; font-weight: 700; letter-spacing: 0.3px;
    }
    .chart-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px 0; }
    .chart-empty i { font-size: 2.5rem; color: rgba(255,107,157,0.12); margin-bottom: 8px; }
    .chart-empty p { color: #bbb; font-size: 0.85rem; margin: 0; }

    /* ---- Donut Toggle ---- */
    .donut-toggle .btn {
        padding: 5px 16px; font-weight: 700; font-size: 0.68rem; border: 1px solid #ddd; color: #888;
    }
    .donut-toggle .btn.active { background: #FF6B9D !important; color: #fff !important; border-color: #FF6B9D !important; }
    .donut-toggle .btn:first-child { border-radius: 50px 0 0 50px; }
    .donut-toggle .btn:last-child  { border-radius: 0 50px 50px 0; }

    /* ---- Donut + Optimized Spacing ---- */
    .donut-flex {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-evenly;
        gap: 14px;
        width: 100%;
        height: 100%;
    }
    
    /* Bigger Donut Circle */
    .donut-wrap {
        position: relative;
        width: 240px;
        height: 240px;
        flex-shrink: 0;
    }
    .donut-svg circle { cursor: pointer; transition: opacity .15s; }
    .donut-svg circle:hover { opacity: .8; }

    /* Optimized Compact 2-Column Grid */
    .donut-legend-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(130px, 1fr));
        gap: 8px 24px;
        width: 100%;
        max-width: 360px;
        justify-items: center;
        align-items: center;
        margin: 0 auto;
    }
    .leg-item { display: flex; align-items: center; gap: 8px; cursor: default; }
    .leg-item .dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
    .leg-item .leg-name { font-size: 0.83rem; font-weight: 700; color: #444; white-space: nowrap; }

    /* ---- Tooltip ---- */
    #chartTooltip {
        position: fixed; z-index: 3000; pointer-events: none;
        background: #1a1a1a; color: #fff; padding: 8px 12px; border-radius: 10px;
        font-size: .74rem; line-height: 1.4; box-shadow: 0 8px 20px rgba(0,0,0,.25);
        opacity: 0; transform: translateY(4px); transition: opacity .12s, transform .12s;
        white-space: nowrap;
    }
    #chartTooltip.show { opacity: 1; transform: translateY(0); }
    #chartTooltip .tt-title { font-weight: 800; margin-bottom: 2px; }
    #chartTooltip .tt-sub { color: #ddd; font-size: .68rem; }

    /* ---- Payment Activity ---- */
    .pay-summary-row {
        display: flex; align-items: center; gap: 12px;
        background: #fdf5fb; border-radius: 14px; padding: 12px 14px; margin-bottom: 12px;
    }
    .pay-summary-row .psr-ic {
        width: 40px; height: 40px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; color: #fff;
    }
    .pay-summary-row.paid .psr-ic { background: linear-gradient(135deg,#FF6B9D,#E85588); }
    .pay-summary-row.due .psr-ic  { background: linear-gradient(135deg,#f59e0b,#d97706); }
    .pay-summary-row .psr-body { flex: 1; }
    .pay-summary-row .psr-top { display: flex; justify-content: space-between; font-size: .74rem; color: #888; font-weight: 700; text-transform: uppercase; letter-spacing: .3px; margin-bottom: 4px; }
    .pay-summary-row .psr-amt { font-size: 1.05rem; font-weight: 900; }
    .pay-summary-row.paid .psr-amt { color: #FF6B9D; }
    .pay-summary-row.due .psr-amt  { color: #b45309; }
    .pay-summary-row .progress { height: 7px; border-radius: 10px; background: #f0e3ec; margin-top: 6px; }

    .txn-row { display: flex; align-items: center; justify-content: space-between; padding: 10px 4px; border-bottom: 1px solid #f5eaf0; }
    .txn-row:last-child { border-bottom: none; }
    .txn-left { display: flex; align-items: center; gap: 10px; }
    .txn-ic { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: .85rem; flex-shrink: 0; }
    .txn-ic.approved { background: #ecfdf5; color: #059669; }
    .txn-ic.pending  { background: #fffbeb; color: #b45309; }
    .txn-ic.rejected { background: #fef2f2; color: #dc2626; }
    .txn-salon { font-size: .82rem; font-weight: 700; color: #333; }
    .txn-date  { font-size: .68rem; color: #aaa; }
    .txn-amt   { font-size: .85rem; font-weight: 800; color: #FF6B9D; }
</style>

{{-- Tooltip element --}}
<div id="chartTooltip"></div>

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1 dashboard-font" style="color:#333;">
            <i class="fas fa-home me-2" style="color:#FF6B9D;"></i>
            Welcome back, {{ auth()->user()->first_name ?? explode(' ', auth()->user()->name)[0] }}
        </h4>
        <p style="color:#aaa;font-size:0.85rem;margin:0;">Here's everything happening with your bookings</p>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card-owner">
            <div class="card-icon bg-icon-pink">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="card-content">
                <div class="card-label">Upcoming</div>
                <div class="card-value">{{ $stats['upcoming'] ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card-owner">
            <div class="card-icon bg-icon-orange">
                <i class="fas fa-clock"></i>
            </div>
            <div class="card-content">
                <div class="card-label">Pending</div>
                <div class="card-value">{{ $stats['pending'] ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card-owner">
            <div class="card-icon bg-icon-purple">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="card-content">
                <div class="card-label">Waitlist</div>
                <div class="card-value">{{ $stats['waitlist'] ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card-owner">
            <div class="card-icon bg-icon-teal">
                <i class="fas fa-credit-card"></i>
            </div>
            <div class="card-content">
                <div class="card-label">Payments</div>
                <div class="card-value">{{ $stats['payments'] ?? 0 }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Hero + Quick Actions --}}
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card-dashboard p-4 h-100 d-flex flex-column justify-content-center text-center">
            <h4 class="fw-bold dashboard-font" style="color:#333;font-size:1.5rem;">Ready for a glow-up?</h4>
            <p class="text-muted" style="font-size:0.95rem;max-width:500px;margin:8px auto 18px;">Our experts are waiting to make you feel extraordinary. Explore our premium treatments and secure your spot today.</p>
            <div>
                <a href="{{ route('salons.index') }}" class="btn rounded-pill px-4 py-2 btn-pink-gradient" style="font-size:0.9rem;">
                    <i class="fas fa-plus-circle me-1"></i> Book Appointment Now
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-dashboard p-4 h-100 d-flex flex-column justify-content-center">
            <h5 class="fw-bold mb-3 dashboard-font" style="color:#333;font-size:1.1rem;">Quick Actions</h5>
            <div class="row g-2">
                <div class="col-4">
                    <a href="{{ route('salons.index') }}" class="quick-action-box">
                        <i class="fas fa-store" style="color:#FF6B9D;font-size:1.2rem;"></i>
                        <div class="quick-action-label">Book</div>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('client.complaints.index') }}" class="quick-action-box">
                        <i class="fas fa-exclamation-circle" style="color:#FF6B9D;font-size:1.2rem;"></i>
                        <div class="quick-action-label">Complaints</div>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('client.notifications.index') }}" class="quick-action-box">
                        <i class="fas fa-bell" style="color:#FF6B9D;font-size:1.2rem;"></i>
                        <div class="quick-action-label" title="Notifications">Notifications</div>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('client.favorites.index') }}" class="quick-action-box">
                        <i class="fas fa-bookmark" style="color:#FF6B9D;font-size:1.2rem;"></i>
                        <div class="quick-action-label">Salons</div>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('client.notifications.index') }}" class="quick-action-box">
                        <i class="fas fa-bell" style="color:#FF6B9D;font-size:1.2rem;"></i>
                        <div class="quick-action-label">Alerts</div>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('client.waitlist.index') }}" class="quick-action-box">
                        <i class="fas fa-hourglass-half" style="color:#FF6B9D;font-size:1.2rem;"></i>
                        <div class="quick-action-label">Waitlist</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== APPOINTMENT OVERVIEW + PAYMENT ACTIVITY (EQUAL HEIGHT CARDS) ===== --}}
<div class="row g-4 mb-4 align-items-stretch">
    <div class="col-lg-6 d-flex">
        <div class="card-dashboard p-4 w-100 d-flex flex-column justify-content-between">
            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                <h5 class="fw-bold mb-0 dashboard-font" style="color:#333;font-size:1.1rem;">Appointment Overview</h5>
                <div class="btn-group donut-toggle" role="group">
                    <button type="button" class="btn active" id="donut-weekly"  data-range="weekly">Weekly</button>
                    <button type="button" class="btn" id="donut-monthly" data-range="monthly">Monthly</button>
                    <button type="button" class="btn" id="donut-yearly"  data-range="yearly">Yearly</button>
                </div>
            </div>
            
            <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center py-1">
                <div class="donut-flex">
                    <div class="donut-wrap">
                        <svg class="donut-svg" viewBox="0 0 100 100" style="width:100%;height:100%;transform:rotate(-90deg);">
                            <circle cx="50" cy="50" r="40" fill="transparent" stroke="#f1f1f1" stroke-width="12" />
                            <g id="donutSegments"></g>
                        </svg>
                        <div class="position-absolute top-50 start-50 translate-middle text-center">
                            <div class="fw-bold" style="color:#FF6B9D;font-size:2.2rem;line-height:1.1;" id="donutTotal">0</div>
                            <div class="text-uppercase small text-muted" style="letter-spacing:1px;font-size:0.65rem;">Bookings</div>
                        </div>
                    </div>

                    {{-- 2 Columns Grid for Legend Labels --}}
                    <div class="donut-legend-grid" id="donutBreakdownList">
                        {{-- filled by JS --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 d-flex">
        <div class="card-dashboard p-4 w-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 dashboard-font" style="color:#333;font-size:1.1rem;">Payment Activity</h5>
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;color:#FF6B9D;">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>

                <div class="pay-summary-row paid">
                    <div class="psr-ic"><i class="fas fa-check-circle"></i></div>
                    <div class="psr-body">
                        <div class="psr-top"><span>Paid Total</span><span>{{ $paidPercent ?? 0 }}%</span></div>
                        <div class="psr-amt">Rs. {{ number_format($paidAmount ?? 0, 0) }}</div>
                        <div class="progress"><div class="progress-bar" style="width:{{ $paidPercent ?? 0 }}%;background:linear-gradient(135deg,#C98AA6,#A8617D);border-radius:10px;"></div></div>
                    </div>
                </div>

                <div class="pay-summary-row due">
                    <div class="psr-ic"><i class="fas fa-hourglass-half"></i></div>
                    <div class="psr-body">
                        <div class="psr-top"><span>Pending Dues</span><span>{{ $pendingDuesPercent ?? 0 }}%</span></div>
                        <div class="psr-amt">Rs. {{ number_format($pendingDuesAmount ?? 0, 0) }}</div>
                        <div class="progress"><div class="progress-bar" style="width:{{ $pendingDuesPercent ?? 0 }}%;background:linear-gradient(135deg,#f59e0b,#d97706);border-radius:10px;"></div></div>
                    </div>
                </div>

                @if(($recentPayments ?? collect())->isEmpty())
                <div class="text-center py-3 rounded-4 flex-grow-1 d-flex flex-column justify-content-center" style="border:2px dashed #fce4ec;background:#fafafa;">
                    <i class="fas fa-receipt fa-3x mb-2" style="color:rgba(255,107,157,0.15);"></i>
                    <h6 class="fw-bold" style="color:#333;font-size:0.9rem;">No transaction history yet</h6>
                    <p class="small text-muted" style="font-size:0.8rem;">Your premium payment summaries will appear here.</p>
                </div>
                @else
                <div class="mt-2">
                    <div class="text-uppercase small text-muted fw-bold mb-1" style="font-size:.65rem;letter-spacing:.4px;">Recent Transactions</div>
                    @foreach($recentPayments as $txn)
                        @php
                            $txnClass = $txn->status === 'approved' ? 'approved' : ($txn->status === 'pending' ? 'pending' : 'rejected');
                            $txnIcon  = $txn->status === 'approved' ? 'fa-check' : ($txn->status === 'pending' ? 'fa-hourglass-half' : 'fa-times');
                        @endphp
                        <div class="txn-row">
                            <div class="txn-left">
                                <div class="txn-ic {{ $txnClass }}"><i class="fas {{ $txnIcon }}"></i></div>
                                <div>
                                    <div class="txn-salon">{{ $txn->appointment->salon->name ?? 'Salon' }}</div>
                                    <div class="txn-date">{{ $txn->created_at->format('d M, h:i A') }}</div>
                                </div>
                            </div>
                            <div class="txn-amt">Rs. {{ number_format($txn->amount ?? $txn->appointment->advance_amount ?? 0) }}</div>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-success bg-opacity-10 rounded-circle p-2">
                        <i class="fas fa-check-circle text-success"></i>
                    </div>
                    <div>
                        <div class="small fw-bold text-uppercase" style="color:#333;font-size:0.65rem;">Flawless Record</div>
                        <div class="small text-muted" style="font-size:0.6rem;">No active complaints on file.</div>
                    </div>
                </div>
                <a href="{{ route('contact') }}" class="btn btn-sm rounded-pill px-4 btn-pink-outline" style="font-size:0.7rem;">Support Hub</a>
            </div>
        </div>
    </div>
</div>

{{-- Booking Activity --}}
<div class="card-dashboard p-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0 dashboard-font" style="color:#333;font-size:1.1rem;">
            <i class="fas fa-chart-line me-2" style="color:#FF6B9D;"></i> Booking Activity History
        </h5>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm active" id="filter-weekly"  data-range="weekly"  style="background:#FF6B9D;color:#fff;border-radius:50px 0 0 50px;padding:6px 18px;font-weight:600;font-size:0.7rem;border:none;">Weekly</button>
            <button type="button" class="btn btn-sm btn-outline-secondary"      id="filter-monthly" data-range="monthly" style="border-radius:0;padding:6px 18px;font-weight:600;font-size:0.7rem;border-color:#ddd;color:#888;">Monthly</button>
            <button type="button" class="btn btn-sm btn-outline-secondary"      id="filter-yearly"  data-range="yearly"  style="border-radius:0 50px 50px 0;padding:6px 18px;font-weight:600;font-size:0.7rem;border-color:#ddd;color:#888;">Yearly</button>
        </div>
    </div>
    <div class="chart-axis-wrap">
        <div class="chart-container" id="chart-container"></div>
    </div>
    <div class="chart-empty" id="chart-empty" style="display:none;">
        <i class="fas fa-chart-simple"></i>
        <p>No activity recorded for this period</p>
    </div>
    <div class="text-center small text-muted mt-2" style="font-size:0.65rem;letter-spacing:0.5px;">
        Hover a bar or donut slice for full details · darkest bar = busiest period
    </div>
</div>

{{-- Cancel Modal --}}
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4" style="border:1px solid #fce4ec;">
            <div class="modal-header" style="border-color:#fce4ec;">
                <h5 class="modal-title fw-bold dashboard-font" style="color:#333;">Cancel Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="cancelForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p style="color:#888;font-size:0.88rem;">Are you sure you want to cancel this appointment? This action cannot be undone.</p>
                    <label style="color:#555;font-size:0.85rem;font-weight:600;" class="mb-2">Reason for Cancellation *</label>
                    <textarea name="cancellation_reason" rows="3" class="form-control" required placeholder="Please tell us why you're cancelling..."
                              style="border:2px solid #fce4ec;border-radius:10px;" onfocus="this.style.borderColor='#FF6B9D'" onblur="this.style.borderColor='#fce4ec'"></textarea>
                </div>
                <div class="modal-footer" style="border-color:#fce4ec;">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Keep Appointment</button>
                    <button type="submit" class="btn rounded-pill px-4" style="background:#ef4444;color:#fff;border:none;font-weight:600;">Cancel Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>
@endsection

@php
    $chartDataForJs = $chartData ?? [
        'weekly'  => ['labels' => [], 'values' => []],
        'monthly' => ['labels' => [], 'values' => []],
        'yearly'  => ['labels' => [], 'values' => []],
    ];

    $donutDataForJs = $donutData ?? [
        'weekly'  => ['total' => 0, 'completion' => 0, 'breakdown' => [], 'counts' => []],
        'monthly' => ['total' => 0, 'completion' => 0, 'breakdown' => [], 'counts' => []],
        'yearly'  => ['total' => 0, 'completion' => 0, 'breakdown' => [], 'counts' => []],
    ];
@endphp

@push('scripts')
<script>
    // SHARED TOOLTIP
    const tooltipEl = document.getElementById('chartTooltip');
    function showTooltip(x, y, title, sub) {
        tooltipEl.innerHTML = `<div class="tt-title">${title}</div>${sub ? `<div class="tt-sub">${sub}</div>` : ''}`;
        tooltipEl.style.left = (x + 14) + 'px';
        tooltipEl.style.top = (y + 14) + 'px';
        tooltipEl.classList.add('show');
    }
    function hideTooltip() { tooltipEl.classList.remove('show'); }

    // BAR CHART
    const bookingChartData = @json($chartDataForJs);

    function mutedShade(ratio) {
        const light = { r: 252, g: 228, b: 241 }; 
        const dark  = { r: 194, g: 70,  b: 126 }; 
        const r = Math.round(light.r + (dark.r - light.r) * ratio);
        const g = Math.round(light.g + (dark.g - light.g) * ratio);
        const b = Math.round(light.b + (dark.b - light.b) * ratio);
        return `rgb(${r}, ${g}, ${b})`;
    }
    function barColor(val, maxVal) {
        if (!val || val <= 0) return '#FBE7F0';
        const ratio = maxVal > 0 ? val / maxVal : 0;
        return mutedShade(ratio);
    }

    function renderChart(range) {
        const dataset = bookingChartData[range] || { labels: [], values: [] };
        const values = dataset.values || [];
        const labels = dataset.labels || [];
        const maxVal = Math.max(...values, 0) > 0 ? Math.max(...values) : 1;
        const total = values.reduce((a, b) => a + b, 0);

        const container = document.getElementById('chart-container');
        const empty = document.getElementById('chart-empty');

        container.innerHTML = '';
        labels.forEach((label, i) => {
            const val = values[i] || 0;
            const height = val > 0 ? (val / maxVal) * 150 + 10 : 0;
            const bg = barColor(val, maxVal);

            const wrapper = document.createElement('div');
            wrapper.className = 'chart-bar-wrapper';
            wrapper.innerHTML = `
                <div class="chart-bar" style="height:${height}px;background:${bg};"></div>
                <div class="chart-bar-label">${label}</div>
            `;
            wrapper.addEventListener('mousemove', (e) => {
                showTooltip(e.clientX, e.clientY, `${label}: ${val} booking${val === 1 ? '' : 's'}`, `${total > 0 ? Math.round(val / total * 100) : 0}% of this period`);
            });
            wrapper.addEventListener('mouseleave', hideTooltip);
            container.appendChild(wrapper);
        });

        empty.style.display = total === 0 ? 'flex' : 'none';
    }

    document.querySelectorAll('#filter-weekly, #filter-monthly, #filter-yearly').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('#filter-weekly, #filter-monthly, #filter-yearly').forEach(b => {
                b.classList.remove('active');
                b.style.background = '';
                b.style.color = '';
                b.classList.add('btn-outline-secondary');
            });
            this.classList.remove('btn-outline-secondary');
            this.classList.add('active');
            this.style.background = '#FF6B9D';
            this.style.color = '#fff';
            renderChart(this.dataset.range);
        });
    });

    renderChart('weekly');

    // DONUT CHART
    const donutData = @json($donutDataForJs);
    const RC = {{ $ringCircumference ?? 251.2 }}; 

    const statusColors = {
        'Completed': '#4FBE99',
        'Confirmed': '#FF6B9D',
        'Approved':  '#FF6B9D',
        'Pending':   '#FF9A54',
        'Cancelled': '#EA8FCB',
        'Upcoming':  '#FF6B9D',
        'Waitlist':  '#8A97E0'
    };

    function renderDonut(range) {
        const data = donutData[range] || { total: 0, breakdown: {}, counts: {} };
        const totalEl = document.getElementById('donutTotal');
        const segGroup = document.getElementById('donutSegments');
        const legendList = document.getElementById('donutBreakdownList');

        totalEl.innerText = data.total || 0;
        segGroup.innerHTML = '';
        legendList.innerHTML = '';

        const counts = data.counts || {};
        const breakdown = data.breakdown || {};
        let accumulatedOffset = 0;

        const keys = Object.keys(counts);

        if (keys.length === 0 || data.total === 0) {
            legendList.innerHTML = `<div class="text-muted small text-center" style="grid-column: span 2;">No data for this period</div>`;
            return;
        }

        keys.forEach(status => {
            const count = counts[status] || 0;
            const percent = breakdown[status] || 0;
            const dashArray = (percent / 100) * RC;
            const dashOffset = -accumulatedOffset;
            const color = statusColors[status] || '#FF6B9D';

            const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
            circle.setAttribute('cx', '50');
            circle.setAttribute('cy', '50');
            circle.setAttribute('r', '40');
            circle.setAttribute('fill', 'transparent');
            circle.setAttribute('stroke', color);
            circle.setAttribute('stroke-width', '12');
            circle.setAttribute('stroke-dasharray', `${dashArray} ${RC - dashArray}`);
            circle.setAttribute('stroke-dashoffset', dashOffset);

            circle.addEventListener('mousemove', (e) => {
                showTooltip(e.clientX, e.clientY, `${status}: ${count}`, `${percent}% of total`);
            });
            circle.addEventListener('mouseleave', hideTooltip);

            segGroup.appendChild(circle);

            const item = document.createElement('div');
            item.className = 'leg-item';
            item.innerHTML = `
                <div class="dot" style="background:${color};"></div>
                <div class="leg-name">${status} (${count})</div>
            `;
            legendList.appendChild(item);

            accumulatedOffset += dashArray;
        });
    }

    document.querySelectorAll('#donut-weekly, #donut-monthly, #donut-yearly').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('#donut-weekly, #donut-monthly, #donut-yearly').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            renderDonut(this.dataset.range);
        });
    });

    renderDonut('weekly');
</script>
@endpush