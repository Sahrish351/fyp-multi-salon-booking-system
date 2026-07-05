{{-- ============================================================ --}}
{{-- FILE: resources/views/client/dashboard.blade.php            --}}
{{-- ============================================================ --}}
@extends('layouts.client')
@section('title', 'Dashboard — Glamora')
@section('content')

<div class="ps-4">

<style>
    .card-dashboard {
        background: #ffffff;
        border: 1px solid #fce4ec;
        border-radius: 20px;
        transition: all .3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .card-dashboard:hover {
        border-color: #E91E8C;
        box-shadow: 0 8px 25px rgba(233,30,140,0.08);
        transform: translateY(-2px);
    }
    .btn-pink-gradient {
        background: linear-gradient(135deg, #E91E8C, #c2185b);
        color: #fff; border: none; font-weight: 600; transition: all 0.3s;
    }
    .btn-pink-gradient:hover {
        background: linear-gradient(135deg, #d81b60, #a31545);
        color: #fff; transform: scale(1.02);
    }
    .btn-pink-outline {
        border: 1px solid #E91E8C; color: #E91E8C;
        background: transparent; font-weight: 600; transition: all 0.3s;
    }
    .btn-pink-outline:hover { background: #E91E8C; color: #fff; }

    /* ── Stat Cards — ORIGINAL colors restored ── */
    .stat-mini {
        border-radius: 14px;
        padding: 14px 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        border: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        cursor: default;
        min-height: 90px;
    }
    .stat-mini:hover { transform: translateY(-3px); }
    .stat-mini .s-icon {
        width: 32px; height: 32px;
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem; margin-bottom: 8px;
        background: rgba(255,255,255,0.28);
    }
    .stat-mini .s-val {
        font-size: 1.45rem; font-weight: 800; color: #fff;
        line-height: 1; text-shadow: 0 1px 3px rgba(0,0,0,0.12);
    }
    .stat-mini .s-lbl {
        font-size: 0.6rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.8px; color: rgba(255,255,255,0.82); margin-top: 4px;
    }

    .sm-pink   { background: #E0177D; box-shadow: 0 4px 14px rgba(224,23,125,0.28); }
    .sm-chrome { background: #607D8B; box-shadow: 0 4px 14px rgba(96,125,139,0.28); }
    .sm-purple { background: #7B5EA7; box-shadow: 0 4px 14px rgba(123,94,167,0.28); }
    .sm-xampp  { background: #F26522; box-shadow: 0 4px 14px rgba(242,101,34,0.28); }
    .sm-teal   { background: #0097A7; box-shadow: 0 4px 14px rgba(0,151,167,0.28); }
    .sm-amber  { background: #C9A800; box-shadow: 0 4px 14px rgba(201,168,0,0.28); }
    .sm-amber .s-val,
    .sm-amber .s-lbl { color: #fff; text-shadow: 0 1px 3px rgba(0,0,0,0.12); }
    .sm-amber .s-lbl { color: rgba(255,255,255,0.82); }

    /* ---- Bar Chart (UPDATED: Bigger & Beautiful) ---- */
    .chart-container {
        height: 290px; /* Increased for bigger look */
        display: flex;
        align-items: flex-end;
        justify-content: space-around;
        padding: 0 10px;
        gap: 12px; /* Slightly more spacing */
    }
    .chart-bar-wrapper { display: flex; flex-direction: column; align-items: center; flex: 1; cursor: pointer; }
    .chart-bar {
        width: 100%;
        max-width: 52px; /* Increased width */
        border-radius: 12px 12px 0 0; /* Softer corners for beauty */
        min-height: 4px;
        box-shadow: 0 2px 6px rgba(233, 30, 140, 0.08); /* Subtle shadow for elegance */
        transition: height 0.8s ease, background 0.4s ease, transform 0.2s ease, box-shadow 0.2s ease;
    }
    .chart-bar-wrapper:hover .chart-bar {
        opacity: .9;
        transform: scaleY(1.02) translateY(-2px); /* Slight lift on hover */
        box-shadow: 0 6px 16px rgba(233, 30, 140, 0.2); /* Deep shadow on hover */
    }
    .chart-bar-label {
        font-size: 0.6rem; color: #aaa; margin-top: 6px;
        text-transform: uppercase; font-weight: 600; letter-spacing: 0.3px;
    }
    .chart-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px 0; }
    .chart-empty i { font-size: 2.5rem; color: rgba(233,30,140,0.12); margin-bottom: 8px; }
    .chart-empty p { color: #bbb; font-size: 0.85rem; margin: 0; }

    /* ---- Donut period toggle ---- */
    .donut-toggle .btn {
        padding: 5px 16px; font-weight: 700; font-size: 0.68rem; border: 1px solid #ddd; color: #888;
    }
    .donut-toggle .btn.active { background: #E91E8C !important; color: #fff !important; border-color: #E91E8C !important; }
    .donut-toggle .btn:first-child { border-radius: 50px 0 0 50px; }
    .donut-toggle .btn:last-child  { border-radius: 0 50px 50px 0; }

    /* ---- Donut wrap: responsive, grows/shrinks with its container ---- */
    .donut-wrap {
        position: relative;
        width: 100%;
        max-width: 230px;
        aspect-ratio: 1 / 1;
        margin: 0 auto;
    }
    .donut-svg circle { cursor: pointer; transition: opacity .15s; }
    .donut-svg circle:hover { opacity: .8; }

    /* ---- Simple legend rows: dot + label + COUNT (not percentage) ---- */
    .breakdown-row { margin-bottom: 8px; cursor: default; }
    .breakdown-row .br-top { display: flex; justify-content: space-between; align-items: center; font-size: .74rem; padding-bottom: 6px; border-bottom: 1px solid #f5eaf0; }
    .breakdown-row .br-top .lbl { display: flex; align-items: center; gap: 6px; color: #555; font-weight: 600; }
    .breakdown-row .br-top .dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
    .breakdown-row .br-top .cnt { font-weight: 800; }

    /* ---- Floating tooltip ---- */
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

    /* ---- Payment Activity — prettier box ---- */
    .pay-summary-row {
        display: flex; align-items: center; gap: 12px;
        background: #fdf5fb; border-radius: 14px; padding: 12px 14px; margin-bottom: 12px;
    }
    .pay-summary-row .psr-ic {
        width: 40px; height: 40px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; color: #fff;
    }
    .pay-summary-row.paid .psr-ic { background: linear-gradient(135deg,#E91E8C,#c2185b); }
    .pay-summary-row.due .psr-ic  { background: linear-gradient(135deg,#f59e0b,#d97706); }
    .pay-summary-row .psr-body { flex: 1; }
    .pay-summary-row .psr-top { display: flex; justify-content: space-between; font-size: .74rem; color: #888; font-weight: 700; text-transform: uppercase; letter-spacing: .3px; margin-bottom: 4px; }
    .pay-summary-row .psr-amt { font-size: 1.05rem; font-weight: 900; }
    .pay-summary-row.paid .psr-amt { color: #E91E8C; }
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
    .txn-amt   { font-size: .85rem; font-weight: 800; color: #E91E8C; }
</style>

{{-- Floating tooltip element (shared) --}}
<div id="chartTooltip"></div>

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1" style="color:#333;font-family:'Playfair Display',serif;">
            <i class="fas fa-home me-2" style="color:#E91E8C;"></i>
            Welcome back, {{ auth()->user()->first_name ?? explode(' ', auth()->user()->name)[0] }}
        </h4>
        <p style="color:#aaa;font-size:0.85rem;margin:0;">Here's everything happening with your bookings</p>
    </div>
</div>

{{-- ===== 6 STAT CARDS ===== --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-mini sm-pink">
            <div class="s-icon"><i class="fas fa-calendar-check" style="color:#fff;"></i></div>
            <div class="s-val">{{ $stats['upcoming'] ?? 0 }}</div>
            <div class="s-lbl">Upcoming</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-mini sm-chrome">
            <div class="s-icon"><i class="fas fa-clock" style="color:#fff;"></i></div>
            <div class="s-val">{{ $stats['pending'] ?? 0 }}</div>
            <div class="s-lbl">Pending</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-mini sm-purple">
            <div class="s-icon"><i class="fas fa-hourglass-half" style="color:#fff;"></i></div>
            <div class="s-val">{{ $stats['waitlist'] ?? 0 }}</div>
            <div class="s-lbl">Waitlist</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-mini sm-xampp">
            <div class="s-icon"><i class="fas fa-comment" style="color:#fff;"></i></div>
            <div class="s-val">{{ $stats['complaints'] ?? 0 }}</div>
            <div class="s-lbl">Complaints</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-mini sm-teal">
            <div class="s-icon"><i class="fas fa-credit-card" style="color:#fff;"></i></div>
            <div class="s-val">{{ $stats['payments'] ?? 0 }}</div>
            <div class="s-lbl">Payments</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-mini sm-amber">
            <div class="s-icon"><i class="fas fa-bell" style="color:#fff;"></i></div>
            <div class="s-val">{{ $stats['alerts'] ?? 0 }}</div>
            <div class="s-lbl">Alerts</div>
        </div>
    </div>
</div>

{{-- ===== HERO + QUICK ACTIONS ===== --}}
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card-dashboard p-5 h-100 d-flex flex-column justify-content-center text-center">
            <h3 class="fw-bold" style="font-family:'Playfair Display',serif;color:#333;font-size:2.5rem;">Ready for a glow-up?</h3>
            <p class="text-muted" style="font-size:1.1rem;max-width:550px;margin:10px auto 20px;">Our experts are waiting to make you feel extraordinary. Explore our premium treatments and secure your spot today.</p>
            <div>
                <a href="{{ route('salons.index') }}" class="btn btn-lg rounded-pill px-5 btn-pink-gradient">
                    <i class="fas fa-plus-circle me-2"></i> Book Appointment Now
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-dashboard p-4 h-100 d-flex flex-column justify-content-center">
            <h5 class="fw-bold mb-3" style="font-family:'Playfair Display',serif;color:#333;font-size:1.1rem;">Quick Actions</h5>
            <div class="row g-2">
                <div class="col-4">
                    <a href="{{ route('salons.index') }}" class="d-block text-center p-3 rounded-3 text-decoration-none" style="background:#fafafa;border:1px solid #fce4ec;transition:all .2s;" onmouseover="this.style.background='#fce4ec';this.style.borderColor='#E91E8C'" onmouseout="this.style.background='#fafafa';this.style.borderColor='#fce4ec'">
                        <i class="fas fa-store" style="color:#E91E8C;font-size:1.3rem;"></i>
                        <div style="color:#666;font-size:0.6rem;text-transform:uppercase;font-weight:600;letter-spacing:0.3px;margin-top:4px;">Book</div>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('client.complaints.index') }}" class="d-block text-center p-3 rounded-3 text-decoration-none" style="background:#fafafa;border:1px solid #fce4ec;transition:all .2s;" onmouseover="this.style.background='#fce4ec';this.style.borderColor='#E91E8C'" onmouseout="this.style.background='#fafafa';this.style.borderColor='#fce4ec'">
                        <i class="fas fa-exclamation-circle" style="color:#E91E8C;font-size:1.3rem;"></i>
                        <div style="color:#666;font-size:0.6rem;text-transform:uppercase;font-weight:600;letter-spacing:0.3px;margin-top:4px;">Complaints</div>
                    </a>
                </div>
                {{-- "Schedule" replaced with "Notifications" as requested --}}
                <div class="col-4">
                    <a href="{{ route('client.notifications.index') }}" class="d-block text-center p-3 rounded-3 text-decoration-none" style="background:#fafafa;border:1px solid #fce4ec;transition:all .2s;" onmouseover="this.style.background='#fce4ec';this.style.borderColor='#E91E8C'" onmouseout="this.style.background='#fafafa';this.style.borderColor='#fce4ec'">
                        <i class="fas fa-bell" style="color:#E91E8C;font-size:1.3rem;"></i>
                        <div style="color:#666;font-size:0.6rem;text-transform:uppercase;font-weight:600;letter-spacing:0.3px;margin-top:4px;">Notifications</div>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('client.favorites.index') }}" class="d-block text-center p-3 rounded-3 text-decoration-none" style="background:#fafafa;border:1px solid #fce4ec;transition:all .2s;" onmouseover="this.style.background='#fce4ec';this.style.borderColor='#E91E8C'" onmouseout="this.style.background='#fafafa';this.style.borderColor='#fce4ec'">
                        <i class="fas fa-bookmark" style="color:#E91E8C;font-size:1.3rem;"></i>
                        <div style="color:#666;font-size:0.6rem;text-transform:uppercase;font-weight:600;letter-spacing:0.3px;margin-top:4px;">Salons</div>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('client.notifications.index') }}" class="d-block text-center p-3 rounded-3 text-decoration-none" style="background:#fafafa;border:1px solid #fce4ec;transition:all .2s;" onmouseover="this.style.background='#fce4ec';this.style.borderColor='#E91E8C'" onmouseout="this.style.background='#fafafa';this.style.borderColor='#fce4ec'">
                        <i class="fas fa-bell" style="color:#E91E8C;font-size:1.3rem;"></i>
                        <div style="color:#666;font-size:0.6rem;text-transform:uppercase;font-weight:600;letter-spacing:0.3px;margin-top:4px;">Alerts</div>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('client.waitlist.index') }}" class="d-block text-center p-3 rounded-3 text-decoration-none" style="background:#fafafa;border:1px solid #fce4ec;transition:all .2s;" onmouseover="this.style.background='#fce4ec';this.style.borderColor='#E91E8C'" onmouseout="this.style.background='#fafafa';this.style.borderColor='#fce4ec'">
                        <i class="fas fa-hourglass-half" style="color:#E91E8C;font-size:1.3rem;"></i>
                        <div style="color:#666;font-size:0.6rem;text-transform:uppercase;font-weight:600;letter-spacing:0.3px;margin-top:4px;">Waitlist</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== APPOINTMENT OVERVIEW (real multi-color donut) + PAYMENT ACTIVITY — equal height ===== --}}
<div class="row g-4 mb-4 align-items-stretch">
    <div class="col-lg-6 d-flex">
        <div class="card-dashboard p-4 w-100 d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h5 class="fw-bold mb-0" style="font-family:'Playfair Display',serif;color:#333;font-size:1.1rem;">Appointment Overview</h5>
                <div class="btn-group donut-toggle" role="group">
                    <button type="button" class="btn active" id="donut-weekly"  data-range="weekly">Weekly</button>
                    <button type="button" class="btn" id="donut-monthly" data-range="monthly">Monthly</button>
                    <button type="button" class="btn" id="donut-yearly"  data-range="yearly">Yearly</button>
                </div>
            </div>
            <div class="text-center py-3 flex-grow-1 d-flex flex-column">
                <div class="donut-wrap">
                    <svg class="donut-svg" viewBox="0 0 100 100" style="width:100%;height:100%;transform:rotate(-90deg);">
                        <circle cx="50" cy="50" r="40" fill="transparent" stroke="#f1f1f1" stroke-width="12" />
                        <g id="donutSegments"></g>
                    </svg>
                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                        <div class="fw-bold" style="color:#E91E8C;font-size:2rem;" id="donutTotal">0</div>
                        <div class="text-uppercase small text-muted" style="letter-spacing:1px;font-size:0.62rem;">Bookings</div>
                    </div>
                </div>

                <div class="mt-4 text-start flex-grow-1" id="donutBreakdownList">
                    {{-- filled by JS --}}
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 d-flex">
        <div class="card-dashboard p-4 w-100 d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0" style="font-family:'Playfair Display',serif;color:#333;font-size:1.1rem;">Payment Activity</h5>
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;color:#E91E8C;">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>

            <div class="pay-summary-row paid">
                <div class="psr-ic"><i class="fas fa-check-circle"></i></div>
                <div class="psr-body">
                    <div class="psr-top"><span>Paid Total</span><span>{{ $paidPercent ?? 0 }}%</span></div>
                    <div class="psr-amt">Rs. {{ number_format($paidAmount ?? 0, 0) }}</div>
                    <div class="progress"><div class="progress-bar" style="width:{{ $paidPercent ?? 0 }}%;background:linear-gradient(135deg,#E91E8C,#c2185b);border-radius:10px;"></div></div>
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
                <i class="fas fa-receipt fa-3x mb-2" style="color:rgba(233,30,140,0.15);"></i>
                <h6 class="fw-bold" style="color:#333;font-size:0.9rem;">No transaction history yet</h6>
                <p class="small text-muted" style="font-size:0.8rem;">Your premium payment summaries will appear here.</p>
            </div>
            @else
            <div class="mt-2 flex-grow-1">
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

{{-- ===== BOOKING ACTIVITY HISTORY (UPDATED: Bigger & Beautiful) ===== --}}
<div class="card-dashboard p-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0" style="font-family:'Playfair Display',serif;color:#333;">
            <i class="fas fa-chart-line me-2" style="color:#E91E8C;"></i> Booking Activity History
        </h5>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm active" id="filter-weekly"  data-range="weekly"  style="background:#E91E8C;color:#fff;border-radius:50px 0 0 50px;padding:6px 18px;font-weight:600;font-size:0.7rem;border:none;">Weekly</button>
            <button type="button" class="btn btn-sm btn-outline-secondary"      id="filter-monthly" data-range="monthly" style="border-radius:0;padding:6px 18px;font-weight:600;font-size:0.7rem;border-color:#ddd;color:#888;">Monthly</button>
            <button type="button" class="btn btn-sm btn-outline-secondary"      id="filter-yearly"  data-range="yearly"  style="border-radius:0 50px 50px 0;padding:6px 18px;font-weight:600;font-size:0.7rem;border-color:#ddd;color:#888;">Yearly</button>
        </div>
    </div>
    <div class="chart-container" id="chart-container"></div>
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
                <h5 class="modal-title fw-bold" style="color:#333;">Cancel Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="cancelForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p style="color:#888;font-size:0.88rem;">Are you sure you want to cancel this appointment? This action cannot be undone.</p>
                    <label style="color:#555;font-size:0.85rem;font-weight:600;" class="mb-2">Reason for Cancellation *</label>
                    <textarea name="cancellation_reason" rows="3" class="form-control" required placeholder="Please tell us why you're cancelling..."
                              style="border:2px solid #fce4ec;border-radius:10px;" onfocus="this.style.borderColor='#E91E8C'" onblur="this.style.borderColor='#fce4ec'"></textarea>
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
    // ================= SHARED TOOLTIP =================
    const tooltipEl = document.getElementById('chartTooltip');
    function showTooltip(x, y, title, sub) {
        tooltipEl.innerHTML = `<div class="tt-title">${title}</div>${sub ? `<div class="tt-sub">${sub}</div>` : ''}`;
        tooltipEl.style.left = (x + 14) + 'px';
        tooltipEl.style.top = (y + 14) + 'px';
        tooltipEl.classList.add('show');
    }
    function hideTooltip() { tooltipEl.classList.remove('show'); }

    // ================= BOOKING ACTIVITY BAR CHART =================
    const bookingChartData = @json($chartDataForJs);

    function barColor(val, maxVal) {
        if (!val || val <= 0) return '#f3d9e5';
        const ratio = val / maxVal;
        if (ratio >= 0.7) return '#ad1457';
        if (ratio >= 0.35) return '#E91E8C';
        return '#f48fb1';
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
            const height = (val / maxVal) * 110 + 4;
            const color = barColor(val, maxVal);

            const wrapper = document.createElement('div');
            wrapper.className = 'chart-bar-wrapper';
            wrapper.innerHTML = `
                <div class="chart-bar" style="height:${height}px;background:${color};"></div>
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
            this.style.background = '#E91E8C';
            this.style.color = '#fff';
            renderChart(this.dataset.range);
        });
    });

    renderChart('weekly');

    // ================= APPOINTMENT OVERVIEW — REAL MULTI-COLOR DONUT (FIXED) =================
    const donutData = @json($donutDataForJs);
    const RC = {{ $ringCircumference ?? 251.2 }}; // ring circumference, matches r=40

    // Thematic colors — matched to each status (no blue unless Confirmed exists)
    const donutColors = {
        'Completed': '#10b981',
        'Confirmed': '#3b82f6',
        'Pending':   '#f59e0b',
        'Cancelled': '#ef4444',
        'Upcoming':  '#E91E8C',
        'Waitlist':  '#06b6d4',
    };
    const SVGNS = 'http://www.w3.org/2000/svg';

    function renderDonut(range) {
        const data = donutData[range] || { total: 0, completion: 0, breakdown: {}, counts: {} };
        const breakdown = data.breakdown || {};
        const counts = data.counts || {};
        const total = data.total ?? 0;

        document.getElementById('donutTotal').textContent = total;

        const group = document.getElementById('donutSegments');
        group.innerHTML = '';

        // Build an array of statuses that actually have > 0 bookings
        const activeLabels = Object.keys(donutColors).filter(label => (breakdown[label] || 0) > 0);

        if (activeLabels.length === 0) {
            // No data – show a gray empty ring
            const emptyCircle = document.createElementNS(SVGNS, 'circle');
            emptyCircle.setAttribute('cx', 50);
            emptyCircle.setAttribute('cy', 50);
            emptyCircle.setAttribute('r', 40);
            emptyCircle.setAttribute('fill', 'transparent');
            emptyCircle.setAttribute('stroke', '#f1f1f1');
            emptyCircle.setAttribute('stroke-width', 12);
            emptyCircle.addEventListener('mousemove', (e) => showTooltip(e.clientX, e.clientY, 'No bookings yet', 'for this period'));
            emptyCircle.addEventListener('mouseleave', hideTooltip);
            group.appendChild(emptyCircle);

            // Legend: show nothing or a message
            const list = document.getElementById('donutBreakdownList');
            list.innerHTML = `<div class="text-muted small text-center py-3">No appointments in this period</div>`;
            return;
        }

        // Calculate total percentage sum (should be 100, but we'll use it for offsets)
        const totalPct = activeLabels.reduce((sum, lbl) => sum + (breakdown[lbl] || 0), 0);

        let cumulative = 0;
        const GAP = 1.2; // small gap between arcs for cleaner look

        activeLabels.forEach(label => {
            const pct = breakdown[label] || 0;
            const color = donutColors[label];
            const count = counts[label] ?? 0;

            // Arc length in circumference units
            const rawArcLen = (pct / totalPct) * RC; // use totalPct to ensure proper proportions
            const arcLen = Math.max(rawArcLen - GAP, rawArcLen > 0 ? 1 : 0);
            const dashArray = `${arcLen} ${RC - arcLen}`;
            const dashOffset = -(cumulative / totalPct) * RC;

            const circle = document.createElementNS(SVGNS, 'circle');
            circle.setAttribute('cx', 50);
            circle.setAttribute('cy', 50);
            circle.setAttribute('r', 40);
            circle.setAttribute('fill', 'transparent');
            circle.setAttribute('stroke', color);
            circle.setAttribute('stroke-width', 12);
            circle.setAttribute('stroke-linecap', 'round');
            circle.setAttribute('stroke-dasharray', dashArray);
            circle.setAttribute('stroke-dashoffset', dashOffset);

            circle.addEventListener('mousemove', (e) => {
                showTooltip(e.clientX, e.clientY, `${label}: ${pct}%`, `${count} of ${total} bookings`);
            });
            circle.addEventListener('mouseleave', hideTooltip);

            group.appendChild(circle);
            cumulative += pct;
        });

        // Legend: show label + count (no percentage)
        const list = document.getElementById('donutBreakdownList');
        list.innerHTML = '';
        activeLabels.forEach(label => {
            const pct = breakdown[label] || 0;
            const color = donutColors[label];
            const count = counts[label] ?? 0;
            const row = document.createElement('div');
            row.className = 'breakdown-row';
            row.innerHTML = `
                <div class="br-top">
                    <span class="lbl"><span class="dot" style="background:${color};"></span>${label}</span>
                    <span class="cnt" style="color:${color};">${count}</span>
                </div>
            `;
            row.addEventListener('mousemove', (e) => {
                showTooltip(e.clientX, e.clientY, `${label}: ${pct}%`, `${count} of ${total} bookings`);
            });
            row.addEventListener('mouseleave', hideTooltip);
            list.appendChild(row);
        });
    }

    document.querySelectorAll('#donut-weekly, #donut-monthly, #donut-yearly').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('#donut-weekly, #donut-monthly, #donut-yearly').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            renderDonut(this.dataset.range);
        });
    });

    renderDonut('weekly');

    // ================= CANCEL MODAL =================
    function cancelModal(id) {
        document.getElementById('cancelForm').action = `/client/appointments/${id}/cancel`;
        new bootstrap.Modal(document.getElementById('cancelModal')).show();
    }
</script>
@endpush