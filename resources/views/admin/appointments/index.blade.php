@extends('layouts.admin')
@section('title', 'Appointments — Glamora Admin')

@section('content')
<style>
:root {
    --pk:      #E91E8C;
    --pk-lt:   #fce4ec;
    --pk-bg:   #fff0f7;
    --pk-h:    #E91E8C;
    --sage:    #5a8a62; --sage-lt:  #eaf3eb;
    --amber:   #c47f00; --amber-lt: #fff8e1;
    --purple:  #6d5cae; --purple-lt:#f0eeff;
    --crimson: #c0392b; --crimson-lt:#fdecea;
    --slate:   #3d6b8a; --slate-lt: #e8f2f8;
}

/* ── Header ── */
.pg-hdr { display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem; margin-bottom:1.6rem; }
.pg-hdr h1 { font-size:1.55rem; font-weight:700; margin:0 0 .2rem; color:#1a1a1a; }
.pg-hdr p  { margin:0; color:#9a9a9a; font-size:.86rem; }

/* ══ STATUS PILLS ══ */
.pills-wrap { display:flex; flex-wrap:wrap; gap:.45rem; }
.sp {
    display:inline-flex; align-items:center; gap:.35rem;
    padding:.45rem 1.1rem; border-radius:999px;
    font-size:.78rem; font-weight:700; text-decoration:none;
    border:2px solid transparent; transition:all .15s; white-space:nowrap; line-height:1;
}
.sp-off { background:#f3f3f3; color:#999; border-color:#e5e5e5; }

/* ---- ALL PILLS USE LIGHT PINK WHEN ACTIVE ---- */
.sp-all.on, .sp-confirmed.on, .sp-pending.on, .sp-completed.on, .sp-cancelled.on {
    background:#E91E8C !important;
    color:#fff !important;
    border-color:#E91E8C !important;
}

/* ---- ALL PILLS USE LIGHT PINK ON HOVER ---- */
.sp:hover, a.sp:hover, .sp-off:hover {
    background:#E91E8C !important;
    color:#fff !important;
    border-color:#E91E8C !important;
}
.sp:hover i { color:#fff !important; }

/* ══ SUMMARY — compact muted-color tiles, clickable filters ── */
.sum-strip { display:flex; flex-wrap:wrap; gap:.8rem; margin-bottom:1.6rem; }
.sum-tile {
    width:112px; height:112px; flex:0 0 112px;
    border-radius:15px;
    display:flex; flex-direction:column; align-items:center; justify-content:center; gap:.28rem;
    text-decoration:none; cursor:pointer;
    position:relative; overflow:hidden; padding:.7rem;
    transition:border-color .18s, background .18s;
    box-sizing:border-box;
    border:2px solid transparent;
    box-shadow:0 2px 6px rgba(0,0,0,.12);
}
@media(max-width:480px){ .sum-tile { width:calc(50% - .4rem); flex:0 0 calc(50% - .4rem); height:104px; } }
.sum-tile-icon {
    position:absolute; top:10px; left:10px;
    width:28px; height:28px; border-radius:50%;
    background:rgba(255,255,255,.25);
    display:flex; align-items:center; justify-content:center;
    font-size:.78rem; color:#fff; z-index:1;
}
.sum-tile-lbl  { font-size:.6rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:rgba(255,255,255,.92); position:relative; z-index:1; }
.sum-tile-val  { font-size:1.35rem; font-weight:800; line-height:1; color:#fff; position:relative; z-index:1; }
.sum-tile-check {
    position:absolute; top:8px; right:8px; width:16px; height:16px; border-radius:50%;
    background:#fff; display:flex; align-items:center; justify-content:center;
    font-size:.5rem; z-index:2;
}
/* ---- NO OUTLINE ON HOVER/ACTIVE ---- */
.sum-tile:hover, .sum-tile.active { border-color:transparent !important; }

/* Total / All — muted orange */
.st-total   { background:#fd7e14; }
.st-total .sum-tile-check { color:#c4650f; }

/* Today — muted purple */
.st-today   { background:#7e57c2; }
.st-today .sum-tile-check { color:#5e3aa3; }

/* Pending — muted golden yellow */
.st-pending { background:#f1c232; }
.st-pending .sum-tile-check { color:#a87b00; }

/* Cancelled — muted red */
.st-cancel  { background:#d32f2f; }
.st-cancel .sum-tile-check { color:#b71c1c; }

/* ── Filter Card ── */
.filter-card { background:#fff; border:1px solid #ebebeb; border-radius:13px; overflow:hidden; margin-bottom:1.4rem; }
.fc-head { padding:.85rem 1.3rem; border-bottom:1px solid #f3f3f3; display:flex; align-items:center; gap:.5rem; }
.fc-head i { color:var(--pk); font-size:.88rem; }
.fc-title { font-weight:700; font-size:.88rem; color:#1a1a1a; }
.fc-body  { padding:1.1rem 1.3rem; }
.f-row { display:flex; flex-wrap:wrap; gap:.9rem; align-items:flex-end; }
.fg { display:flex; flex-direction:column; gap:.35rem; flex:1; min-width:145px; }
.fg label { font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.055em; color:#aaa; }
.fi {
    width:100%; padding:.63rem .92rem; border:1.5px solid #e5e5e5;
    border-radius:9px; font-size:.88rem; color:#1a1a1a;
    background:#fafafa; outline:none; transition:all .2s; box-sizing:border-box; font-family:inherit;
}
.fi:focus { border-color:var(--pk); box-shadow:0 0 0 3px rgba(233,30,140,.1); background:#fff; }
.fi-sw { position:relative; }
.fi-sw i { position:absolute; left:.85rem; top:50%; transform:translateY(-50%); color:#ccc; font-size:.8rem; pointer-events:none; }
.fi-sw .fi { padding-left:2.2rem; }
.btn-go {
    padding:.63rem 1.15rem; border-radius:9px; font-size:.86rem; font-weight:700;
    cursor:pointer; border:none; background:var(--pk); color:#fff; transition:all .18s; white-space:nowrap;
}
.btn-go:hover { background:var(--pk-h); }
.btn-clr {
    padding:.63rem .95rem; border-radius:9px; font-size:.86rem; font-weight:600;
    cursor:pointer; background:transparent; border:1.5px solid #e5e5e5; color:#aaa;
    text-decoration:none; transition:all .15s; white-space:nowrap; text-align:center;
}
.btn-clr:hover { border-color:var(--pk); color:var(--pk); }

/* ── Export ── */
.export-row { display:flex; justify-content:flex-end; margin-bottom:1rem; }
.btn-xls {
    display:inline-flex; align-items:center; gap:.42rem;
    padding:.6rem 1.15rem; border-radius:9px; font-size:.84rem; font-weight:700;
    background:#1d6f42; color:#fff; text-decoration:none; transition:all .18s; border:none;
}
.btn-xls:hover { background:#155a34; color:#fff; transform:translateY(-1px); }

/* ── Table Card ── */
.tcard { background:#fff; border:1px solid #ebebeb; border-radius:13px; overflow:hidden; }
.tc-head {
    display:flex; justify-content:space-between; align-items:center;
    padding:.9rem 1.3rem; border-bottom:1px solid #f3f3f3; flex-wrap:wrap; gap:.5rem;
}
.tc-title { font-weight:700; font-size:.9rem; color:#1a1a1a; }
.tc-count { font-size:.75rem; color:#aaa; background:#f3f3f3; padding:.2rem .62rem; border-radius:20px; }

.dt { width:100%; border-collapse:collapse; }
.dt thead tr { background:#fafafa; }
.dt thead th {
    padding:.75rem .95rem; font-size:.67rem; font-weight:700;
    text-transform:uppercase; letter-spacing:.06em; color:#bbb;
    text-align:left; white-space:nowrap; border-bottom:1px solid #ebebeb;
}
.dt tbody tr { border-bottom:1px solid #f5f5f5; transition:background .15s; }
.dt tbody tr:last-child { border-bottom:none; }
.dt tbody tr:hover { background:#fdf5fa; }
.dt td { padding:.82rem .95rem; font-size:.86rem; color:#444; vertical-align:middle; }

.ref  { font-family:monospace; font-weight:700; color:var(--pk); font-size:.8rem; }
.cn   { font-weight:600; color:#1a1a1a; }
.csub { font-size:.7rem; color:#aaa; margin-top:.06rem; }

.sbadge {
    display:inline-flex; align-items:center; gap:.28rem;
    padding:.25rem .7rem; border-radius:20px; font-size:.71rem; font-weight:700; white-space:nowrap;
}
.sdot { width:6px; height:6px; border-radius:50%; display:inline-block; flex-shrink:0; }

.vbtn {
    display:inline-flex; align-items:center; gap:.28rem;
    padding:.38rem .82rem; border-radius:8px; font-size:.76rem; font-weight:700;
    background:var(--pk-lt); color:var(--pk);
    border:1.5px solid rgba(233,30,140,.2); text-decoration:none; transition:all .15s;
}
.vbtn:hover { background:var(--pk); color:#fff; }

.empty-st { text-align:center; padding:3rem 1rem; color:#ccc; }
.empty-st i { font-size:2.2rem; margin-bottom:.7rem; opacity:.3; display:block; }
.empty-st p { color:#999; font-size:.88rem; }
.pgn-wrap { padding:.95rem 1.3rem; border-top:1px solid #f3f3f3; }
</style>

{{-- ── Header ── --}}
<div class="pg-hdr">
    <div>
        <h1><i class="fas fa-calendar-check" style="color:var(--pk);margin-right:.5rem;font-size:1.25rem;"></i>All Appointments</h1>
        <p>Monitor all bookings across all salons</p>
    </div>

    {{-- Pills --}}
    @php
    $cur = request('status','all');
    $pills = [
        'all'             => ['label'=>'All',       'icon'=>'fa-th-list',      'cls'=>'sp-all'],
        'confirmed'       => ['label'=>'Confirmed', 'icon'=>'fa-check-circle', 'cls'=>'sp-confirmed'],
        'pending_payment' => ['label'=>'Pending',   'icon'=>'fa-clock',        'cls'=>'sp-pending'],
        'completed'       => ['label'=>'Completed', 'icon'=>'fa-star',         'cls'=>'sp-completed'],
        'cancelled'       => ['label'=>'Cancelled', 'icon'=>'fa-ban',          'cls'=>'sp-cancelled'],
    ];
    @endphp
    <div class="pills-wrap">
        @foreach($pills as $val => $p)
        <a href="{{ route('admin.appointments.index', array_merge(request()->except('status'),['status'=>$val])) }}"
           class="sp {{ $p['cls'] }} {{ $cur===$val?'on':'sp-off' }}">
            <i class="fas {{ $p['icon'] }}" style="font-size:.65rem;"></i> {{ $p['label'] }}
        </a>
        @endforeach
    </div>
</div>

{{-- ── Summary Tiles ── --}}
@php
$totalCount     = $appointments->total();
$todayCount     = \App\Models\Appointment::whereDate('appointment_date', today())->count();
$pendingCount   = \App\Models\Appointment::where('status','pending_payment')->count();
$cancelledCount = \App\Models\Appointment::where('status','cancelled')->count();

$todayStr        = \Carbon\Carbon::today()->format('Y-m-d');
$isTodayActive   = request('date') === $todayStr;
$isPendingActive = $cur === 'pending_payment';
$isCancelActive  = $cur === 'cancelled';
$isTotalActive   = !$isTodayActive && !$isPendingActive && !$isCancelActive;

$hrefTotal   = route('admin.appointments.index', array_merge(request()->except(['status','date']), ['status'=>'all']));
$hrefToday   = route('admin.appointments.index', array_merge(request()->except('date'), ['date'=>$todayStr]));
$hrefPending = route('admin.appointments.index', array_merge(request()->except('status'), ['status'=>'pending_payment']));
$hrefCancel  = route('admin.appointments.index', array_merge(request()->except('status'), ['status'=>'cancelled']));
@endphp
<div class="sum-strip">
    <a href="{{ $hrefTotal }}" class="sum-tile st-total {{ $isTotalActive ? 'active' : '' }}">
        @if($isTotalActive)<span class="sum-tile-check"><i class="fas fa-check"></i></span>@endif
        <div class="sum-tile-icon"><i class="fas fa-calendar-check"></i></div>
        <div class="sum-tile-lbl">Total</div>
        <div class="sum-tile-val">{{ number_format($totalCount) }}</div>
    </a>
    <a href="{{ $hrefToday }}" class="sum-tile st-today {{ $isTodayActive ? 'active' : '' }}">
        @if($isTodayActive)<span class="sum-tile-check"><i class="fas fa-check"></i></span>@endif
        <div class="sum-tile-icon"><i class="fas fa-sun"></i></div>
        <div class="sum-tile-lbl">Today</div>
        <div class="sum-tile-val">{{ number_format($todayCount) }}</div>
    </a>
    <a href="{{ $hrefPending }}" class="sum-tile st-pending {{ $isPendingActive ? 'active' : '' }}">
        @if($isPendingActive)<span class="sum-tile-check"><i class="fas fa-check"></i></span>@endif
        <div class="sum-tile-icon"><i class="fas fa-clock"></i></div>
        <div class="sum-tile-lbl">Pending</div>
        <div class="sum-tile-val">{{ number_format($pendingCount) }}</div>
    </a>
    <a href="{{ $hrefCancel }}" class="sum-tile st-cancel {{ $isCancelActive ? 'active' : '' }}">
        @if($isCancelActive)<span class="sum-tile-check"><i class="fas fa-check"></i></span>@endif
        <div class="sum-tile-icon"><i class="fas fa-times-circle"></i></div>
        <div class="sum-tile-lbl">Cancelled</div>
        <div class="sum-tile-val">{{ number_format($cancelledCount) }}</div>
    </a>
</div>

{{-- ── Filter ── --}}
<div class="filter-card">
    <div class="fc-head"><i class="fas fa-filter"></i><span class="fc-title">Filter Appointments</span></div>
    <div class="fc-body">
        <form method="GET">
            <div class="f-row">
                <div class="fg" style="flex:2;min-width:190px;">
                    <label>Search Client</label>
                    <div class="fi-sw">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" class="fi" placeholder="Search by client name…" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="fg" style="min-width:145px;">
                    <label>Date</label>
                    <input type="date" name="date" class="fi" value="{{ request('date') }}">
                </div>
                <div class="fg" style="min-width:170px;">
                    <label>Salon</label>
                    <select name="salon_id" class="fi">
                        <option value="">All Salons</option>
                        @foreach(\App\Models\Salon::where('status','approved')->get() as $s)
                            <option value="{{ $s->id }}" {{ request('salon_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex;gap:.5rem;align-items:flex-end;">
                    <button type="submit" class="btn-go"><i class="fas fa-search"></i> Filter</button>
                    <a href="{{ route('admin.appointments.index') }}" class="btn-clr">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ── Export ── --}}
<div class="export-row">
    <a href="{{ route('admin.appointments.export') }}" class="btn-xls">
        <i class="fas fa-file-excel"></i> Export Excel
    </a>
</div>

{{-- ── Table ── --}}
<div class="tcard">
    <div class="tc-head">
        <span class="tc-title"><i class="fas fa-list" style="color:var(--pk);margin-right:.4rem;"></i>Appointment List</span>
        <span class="tc-count">{{ $appointments->total() }} records</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="dt">
            <thead>
                <tr>
                    <th>Ref #</th><th>Client</th><th>Salon</th><th>Service</th>
                    <th>Stylist</th><th>Date & Time</th><th>Amount</th>
                    <th>Status</th><th>Payment</th><th>Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse($appointments as $appt)
            @php
            $sb = [
                'confirmed'        =>['bg'=>'#eaf3eb','color'=>'#3d7045','dot'=>'#5a8a62','label'=>'Confirmed'],
                'pending_payment'  =>['bg'=>'#fff8e1','color'=>'#a06800','dot'=>'#c47f00','label'=>'Pending'],
                'payment_submitted'=>['bg'=>'#fde9f4','color'=>'#b0156a','dot'=>'#e91e8c','label'=>'Pay Submitted'],
                'completed'        =>['bg'=>'#f0eeff','color'=>'#5248a0','dot'=>'#6d5cae','label'=>'Completed'],
                'cancelled'        =>['bg'=>'#fdecea','color'=>'#a02820','dot'=>'#c0392b','label'=>'Cancelled'],
            ];
            $sc = $sb[$appt->status] ?? ['bg'=>'#f5f5f5','color'=>'#888','dot'=>'#aaa','label'=>ucfirst($appt->status)];
            $pb = [
                'approved'=>['bg'=>'#eaf3eb','color'=>'#3d7045','dot'=>'#5a8a62','label'=>'Approved'],
                'pending' =>['bg'=>'#fff8e1','color'=>'#a06800','dot'=>'#c47f00','label'=>'Pending'],
                'rejected'=>['bg'=>'#fdecea','color'=>'#a02820','dot'=>'#c0392b','label'=>'Rejected'],
            ];
            $pc = ($appt->payment) ? ($pb[$appt->payment->status] ?? ['bg'=>'#f5f5f5','color'=>'#888','dot'=>'#aaa','label'=>'—']) : null;
            @endphp
            <tr>
                <td><span class="ref">{{ $appt->booking_ref ?? '#'.$appt->id }}</span></td>
                <td>
                    <div class="cn">{{ $appt->client->name ?? 'N/A' }}</div>
                    <div class="csub">{{ $appt->client->phone ?? '' }}</div>
                </td>
                <td><span class="cn" style="font-weight:500;">{{ Str::limit($appt->salon->name ?? 'N/A',20) }}</span></td>
                <td style="font-size:.8rem;">{{ Str::limit($appt->service->name ?? 'N/A',18) }}</td>
                <td style="font-size:.8rem;">{{ $appt->stylist->name ?? '—' }}</td>
                <td>
                    <div style="font-weight:600;font-size:.84rem;">{{ \Carbon\Carbon::parse($appt->appointment_date)->format('d M Y') }}</div>
                    <div class="csub">{{ \Carbon\Carbon::parse($appt->start_time)->format('h:i A') }}</div>
                </td>
                <td style="font-weight:700;color:var(--pk);">Rs. {{ number_format($appt->total_amount ?? 0) }}</td>
                <td>
                    <span class="sbadge" style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }};">
                        <span class="sdot" style="background:{{ $sc['dot'] }};"></span>{{ $sc['label'] }}
                    </span>
                </td>
                <td>
                    @if($pc)
                    <span class="sbadge" style="background:{{ $pc['bg'] }};color:{{ $pc['color'] }};">
                        <span class="sdot" style="background:{{ $pc['dot'] }};"></span>{{ $pc['label'] }}
                    </span>
                    @else<span style="color:#ccc;font-size:.76rem;">—</span>@endif
                </td>
                <td>
                    <a href="{{ route('admin.appointments.show', $appt->id) }}" class="vbtn">
                        <i class="fas fa-eye"></i> View
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="10">
                <div class="empty-st">
                    <i class="fas fa-calendar-times"></i>
                    <p>No appointments found</p>
                    <a href="{{ route('admin.appointments.index') }}" class="btn-go" style="display:inline-flex;margin-top:.5rem;text-decoration:none;">Clear Filters</a>
                </div>
            </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($appointments->hasPages())
    <div class="pgn-wrap">{{ $appointments->links() }}</div>
    @endif
</div>

@endsection