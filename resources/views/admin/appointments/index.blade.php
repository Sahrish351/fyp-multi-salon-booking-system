@extends('layouts.admin')
@section('title', 'Appointments — Beauty Blush Salons Admin')

@section('content')
<style>
:root {
    --pk:        #FF6B9D;
    --pk-lt:     #fce4ec;
    --pk-bg:     #fff0f7;
    --pk-h:      #E85588;
    --text-main: #2D2631;
    --text-muted:#8C8289;
    --border-clr:#EAE5EC;
    --card-bg:   #FFFFFF;
}

/* ── Page Header & Export Row ── */
.pg-hdr { display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem; margin-bottom:1.6rem; }
.pg-hdr-left h1 { font-size:1.65rem; font-weight:800; margin:0 0 .2rem; color:var(--text-main); letter-spacing:-0.4px; }
.pg-hdr-left p  { margin:0; color:var(--text-muted); font-size:.88rem; font-weight:500; }
.pg-hdr-right { display:flex; align-items:center; gap:.8rem; flex-wrap:wrap; }

.btn-xls {
    display:inline-flex; align-items:center; gap:.5rem;
    padding:.68rem 1.3rem; border-radius:12px; font-size:.86rem; font-weight:800;
    background:#1D6F42; color:#fff; text-decoration:none; transition:all .2s ease; border:none;
    box-shadow:0 4px 12px rgba(29,111,66,.2); white-space:nowrap;
}
.btn-xls:hover { background:#155A34; color:#fff; transform:translateY(-1px); }

/* ══ STATUS PILLS ══ */
.pills-wrap { display:flex; flex-wrap:wrap; gap:.5rem; margin-bottom:1.6rem; }
.sp {
    display:inline-flex; align-items:center; gap:.4rem;
    padding:.5rem 1.2rem; border-radius:999px;
    font-size:.8rem; font-weight:700; text-decoration:none;
    border:2px solid transparent; transition:all .2s ease; white-space:nowrap; line-height:1;
}
.sp-off { background:#F4F2F5; color:var(--text-muted); border-color:#E9E4E8; }

.sp-all.on, .sp-confirmed.on, .sp-pending.on, .sp-completed.on, .sp-cancelled.on {
    background:var(--pk) !important;
    color:#fff !important;
    border-color:var(--pk) !important;
    box-shadow:0 4px 14px rgba(255,107,157,.35);
}

.sp:hover, a.sp:hover, .sp-off:hover {
    background:var(--pk-h) !important;
    color:#fff !important;
    border-color:var(--pk-h) !important;
    transform:translateY(-1px);
}
.sp:hover i { color:#fff !important; }

/* ══ SUMMARY CARDS (White Dashboard Style) ══ */
.sum-strip { display:flex; flex-wrap:wrap; gap:1.1rem; margin-bottom:1.6rem; }
.sum-tile {
    flex:1 1 0; min-width:170px; height:110px;
    background: var(--card-bg);
    border-radius:18px;
    display:flex; flex-direction:column; align-items:flex-start; justify-content:center; gap:.25rem;
    text-decoration:none; cursor:pointer;
    position:relative; overflow:hidden; padding:1.2rem 1.4rem;
    transition:all .25s ease;
    box-sizing:border-box;
    border:1px solid var(--border-clr);
    box-shadow:0 4px 18px rgba(0,0,0,.02);
}
.sum-tile:hover { transform:translateY(-3px); box-shadow:0 8px 25px rgba(0,0,0,.06); border-color:var(--pk); }
@media(max-width:700px){ .sum-tile { flex:1 1 calc(50% - .5rem); min-width:calc(50% - .5rem); height:105px; } }

.sum-tile-top { display:flex; align-items:center; justify-content:space-between; width:100%; margin-bottom:.1rem; }
.sum-tile-icon {
    width:34px; height:34px; border-radius:10px;
    display:flex; align-items:center; justify-content:center;
    font-size:.85rem; z-index:1;
}
.sum-tile-check {
    width:18px; height:18px; border-radius:50%;
    background:var(--pk); color:#fff; display:flex; align-items:center; justify-content:center;
    font-size:.55rem; z-index:2; box-shadow:0 2px 5px rgba(255,107,157,.3);
}

.sum-tile-lbl  { font-size:.68rem; font-weight:800; text-transform:uppercase; letter-spacing:.07em; color:var(--text-muted); }
.sum-tile-val  { font-size:1.45rem; font-weight:800; line-height:1; color:var(--text-main); }

.st-total .sum-tile-icon { background:#FFF0F7; color:var(--pk); }
.st-today .sum-tile-icon { background:#F0EEFF; color:#6D5CAE; }
.st-pending .sum-tile-icon { background:#FFF8E1; color:#C47F00; }
.st-cancel .sum-tile-icon { background:#FDECEA; color:#C0392B; }

/* ── Filter Card ── */
.filter-card { background:var(--card-bg); border:1px solid var(--border-clr); border-radius:18px; overflow:hidden; margin-bottom:1.6rem; box-shadow:0 4px 20px rgba(0,0,0,.02); }
.fc-head { padding:1rem 1.4rem; border-bottom:1px solid #F2EDF1; display:flex; align-items:center; gap:.6rem; background:#FCF9FC; }
.fc-head i { color:var(--pk); font-size:.92rem; }
.fc-title { font-weight:800; font-size:.92rem; color:var(--text-main); }
.fc-body  { padding:1.3rem 1.4rem; }
.f-row { display:flex; flex-wrap:wrap; gap:1rem; align-items:flex-end; }
.fg { display:flex; flex-direction:column; gap:.4rem; flex:1; min-width:150px; }
.fg label { font-size:.7rem; font-weight:800; text-transform:uppercase; letter-spacing:.06em; color:var(--text-muted); }
.fi {
    width:100%; padding:.7rem 1rem; border:1.5px solid #E7E0E5;
    border-radius:12px; font-size:.88rem; color:var(--text-main);
    background:#FAF8FA; outline:none; transition:all .2s ease; box-sizing:border-box; font-family:inherit;
}
.fi:focus { border-color:var(--pk); box-shadow:0 0 0 4px rgba(255,107,157,.12); background:#fff; }
.fi-sw { position:relative; }
.fi-sw i { position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:#B09CB0; font-size:.85rem; pointer-events:none; }
.fi-sw .fi { padding-left:2.5rem; }

.btn-go {
    padding:.7rem 1.4rem; border-radius:12px; font-size:.88rem; font-weight:800;
    cursor:pointer; border:none; background:var(--pk); color:#fff; transition:all .2s ease; white-space:nowrap;
    box-shadow:0 4px 12px rgba(255,107,157,.25);
}
.btn-go:hover { background:var(--pk-h); transform:translateY(-1px); }
.btn-clr {
    padding:.7rem 1.2rem; border-radius:12px; font-size:.88rem; font-weight:700;
    cursor:pointer; background:transparent; border:1.5px solid #E7E0E5; color:var(--text-muted);
    text-decoration:none; transition:all .2s ease; white-space:nowrap; text-align:center; display:inline-flex; align-items:center; justify-content:center;
}
.btn-clr:hover { border-color:var(--pk); color:var(--pk); background:#FFF5F8; }

/* ── Table Card ── */
.tcard { background:var(--card-bg); border:1px solid var(--border-clr); border-radius:18px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,.02); }
.tc-head {
    display:flex; justify-content:space-between; align-items:center;
    padding:1.1rem 1.4rem; border-bottom:1px solid #F2EDF1; flex-wrap:wrap; gap:.5rem; background:#FCF9FC;
}
.tc-title { font-weight:800; font-size:.95rem; color:var(--text-main); display:flex; align-items:center; gap:.6rem; }
.tc-count { font-size:.78rem; font-weight:700; color:var(--text-muted); background:#F0ECEF; padding:.3rem .8rem; border-radius:20px; }

.dt { width:100%; border-collapse:collapse; }
.dt thead tr { background:#F9F6F9; }
.dt thead th {
    padding:.9rem 1rem; font-size:.68rem; font-weight:800;
    text-transform:uppercase; letter-spacing:.07em; color:var(--text-muted);
    text-align:left; white-space:nowrap; border-bottom:1px solid var(--border-clr);
}
.dt tbody tr { border-bottom:1px solid #F6F3F6; transition:background .15s ease; }
.dt tbody tr:last-child { border-bottom:none; }
.dt tbody tr:hover { background:#FFF5F9; }
.dt td { padding:.95rem 1rem; font-size:.86rem; color:#372F3A; vertical-align:middle; }

.ref  { font-family:monospace; font-weight:800; color:var(--pk); font-size:.82rem; background:#FFF0F7; padding:.25rem .5rem; border-radius:6px; border:1px solid #FFD6E6; }
.cn   { font-weight:700; color:var(--text-main); }
.csub { font-size:.72rem; color:var(--text-muted); margin-top:.1rem; font-weight:500; }

.sbadge {
    display:inline-flex; align-items:center; gap:.35rem;
    padding:.3rem .8rem; border-radius:20px; font-size:.73rem; font-weight:800; white-space:nowrap;
}
.sdot { width:6px; height:6px; border-radius:50%; display:inline-block; flex-shrink:0; }

.vbtn {
    display:inline-flex; align-items:center; gap:.35rem;
    padding:.45rem .95rem; border-radius:10px; font-size:.78rem; font-weight:800;
    background:#E0F7FA; color:#00838F;
    border:1.5px solid rgba(0,131,143,.2); text-decoration:none; transition:all .2s ease;
}
.vbtn:hover { background:#00838F; color:#fff; box-shadow:0 3px 10px rgba(0,131,143,.25); }

.empty-st { text-align:center; padding:4rem 1rem; color:#B09CB0; }
.empty-st i { font-size:2.8rem; margin-bottom:.8rem; opacity:.4; display:block; color:var(--pk); }
.empty-st p { color:var(--text-muted); font-size:.92rem; font-weight:600; }

/* ══ MODERN PAGINATION STYLING ══ */
.pgn-wrap {
    padding: 1.25rem 1.4rem; border-top: 1px solid #F2EDF1; background: #FCF9FC;
    display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;
    border-bottom-left-radius: 18px; border-bottom-right-radius: 18px;
}
.pagination { display: flex; list-style: none; padding: 0; margin: 0; gap: .4rem; align-items: center; }
.pagination li { display: inline-block; }
.pagination li a, .pagination li span {
    display: inline-flex; align-items: center; justify-content: center;
    padding: .48rem .95rem; border-radius: 10px; font-size: .82rem; font-weight: 700;
    text-decoration: none; border: 1.5px solid #E7E0E5; background: #fff; color: #5A4E5E;
    transition: all .15s ease; box-shadow: 0 2px 5px rgba(0,0,0,.02);
}
.pagination li.active span { background: var(--pk); border-color: var(--pk); color: #fff; box-shadow: 0 4px 12px rgba(255,107,157,.3); }
.pagination li a:hover { border-color: var(--pk); color: var(--pk); background: #FFF5F8; transform: translateY(-1px); }
.pagination li.disabled span { background: #F4F2F5; color: #B09CB0; border-color: #E9E4E8; box-shadow: none; }
</style>

{{-- ── Header with Export Button on Right ── --}}
<div class="pg-hdr">
    <div class="pg-hdr-left">
        <h1><i class="fas fa-calendar-check" style="color:var(--pk);margin-right:.6rem;"></i>All Appointments</h1>
        <p>Monitor all bookings and schedule updates across all registered salons</p>
    </div>
    <div class="pg-hdr-right">
        <a href="{{ route('admin.appointments.export', request()->query()) }}" class="btn-xls">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
    </div>
</div>

{{-- ── Status Filter Pills ── --}}
@php
$cur = request('status','all');
$pills = [
    'all'             => ['label'=>'All',        'icon'=>'fa-th-list',      'cls'=>'sp-all'],
    'confirmed'       => ['label'=>'Confirmed',  'icon'=>'fa-check-circle', 'cls'=>'sp-confirmed'],
    'pending_payment' => ['label'=>'Pending',    'icon'=>'fa-clock',        'cls'=>'sp-pending'],
    'completed'       => ['label'=>'Completed',  'icon'=>'fa-star',         'cls'=>'sp-completed'],
    'cancelled'       => ['label'=>'Cancelled',  'icon'=>'fa-ban',          'cls'=>'sp-cancelled'],
];
@endphp
<div class="pills-wrap">
    @foreach($pills as $val => $p)
    <a href="{{ route('admin.appointments.index', array_merge(request()->except('status'), $val === 'all' ? [] : ['status'=>$val])) }}"
       class="sp {{ $p['cls'] }} {{ ($cur===$val || ($val==='all' && !request()->has('status')))?'on':'sp-off' }}">
        <i class="fas {{ $p['icon'] }}" style="font-size:.7rem;"></i> {{ $p['label'] }}
    </a>
    @endforeach
</div>

{{-- ── Summary Cards ── --}}
@php
$totalCount     = \App\Models\Appointment::count();
$todayCount     = \App\Models\Appointment::whereDate('appointment_date', today())->count();
$pendingCount   = \App\Models\Appointment::where('status','pending_payment')->count();
$cancelledCount = \App\Models\Appointment::where('status','cancelled')->count();

$todayStr        = \Carbon\Carbon::today()->format('Y-m-d');
$isTodayActive   = request('date') === $todayStr;
$isPendingActive = $cur === 'pending_payment';
$isCancelActive  = $cur === 'cancelled';
$isTotalActive   = !$isTodayActive && !$isPendingActive && !$isCancelActive && ($cur === 'all' || !request()->has('status'));

$hrefTotal   = route('admin.appointments.index', array_merge(request()->except(['status','date'])));
$hrefToday   = route('admin.appointments.index', array_merge(request()->except('date'), ['date'=>$todayStr]));
$hrefPending = route('admin.appointments.index', array_merge(request()->except('status'), ['status'=>'pending_payment']));
$hrefCancel  = route('admin.appointments.index', array_merge(request()->except('status'), ['status'=>'cancelled']));
@endphp
<div class="sum-strip">
    <a href="{{ $hrefTotal }}" class="sum-tile st-total">
        <div class="sum-tile-top">
            <div class="sum-tile-icon"><i class="fas fa-calendar-check"></i></div>
            @if($isTotalActive)<span class="sum-tile-check"><i class="fas fa-check"></i></span>@endif
        </div>
        <div class="sum-tile-val">{{ number_format($totalCount) }}</div>
        <div class="sum-tile-lbl">Total Bookings</div>
    </a>
    <a href="{{ $hrefToday }}" class="sum-tile st-today">
        <div class="sum-tile-top">
            <div class="sum-tile-icon"><i class="fas fa-sun"></i></div>
            @if($isTodayActive)<span class="sum-tile-check"><i class="fas fa-check"></i></span>@endif
        </div>
        <div class="sum-tile-val">{{ number_format($todayCount) }}</div>
        <div class="sum-tile-lbl">Today</div>
    </a>
    <a href="{{ $hrefPending }}" class="sum-tile st-pending">
        <div class="sum-tile-top">
            <div class="sum-tile-icon"><i class="fas fa-clock"></i></div>
            @if($isPendingActive)<span class="sum-tile-check"><i class="fas fa-check"></i></span>@endif
        </div>
        <div class="sum-tile-val">{{ number_format($pendingCount) }}</div>
        <div class="sum-tile-lbl">Pending Payment</div>
    </a>
    <a href="{{ $hrefCancel }}" class="sum-tile st-cancel">
        <div class="sum-tile-top">
            <div class="sum-tile-icon"><i class="fas fa-times-circle"></i></div>
            @if($isCancelActive)<span class="sum-tile-check"><i class="fas fa-check"></i></span>@endif
        </div>
        <div class="sum-tile-val">{{ number_format($cancelledCount) }}</div>
        <div class="sum-tile-lbl">Cancelled</div>
    </a>
</div>

{{-- ── Filter Card ── --}}
<div class="filter-card">
    <div class="fc-head"><i class="fas fa-filter"></i><span class="fc-title">Filter & Search Appointments</span></div>
    <div class="fc-body">
        <form method="GET">
            @if(request('status') && request('status') !== 'all')
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="f-row">
                <div class="fg" style="flex:2;min-width:200px;">
                    <label>Search Client</label>
                    <div class="fi-sw">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" class="fi" placeholder="Search by client name or phone…" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="fg" style="min-width:150px;">
                    <label>Date</label>
                    <input type="date" name="date" class="fi" value="{{ request('date') }}">
                </div>
                <div class="fg" style="min-width:180px;">
                    <label>Salon</label>
                    <select name="salon_id" class="fi">
                        <option value="">All Salons</option>
                        @foreach(\App\Models\Salon::where('status','approved')->get() as $s)
                            <option value="{{ $s->id }}" {{ request('salon_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex;gap:.5rem;align-items:flex-end;">
                    <button type="submit" class="btn-go"><i class="fas fa-search" style="margin-right:.3rem;"></i> Filter</button>
                    <a href="{{ route('admin.appointments.index') }}" class="btn-clr">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ── Table Card ── --}}
<div class="tcard">
    <div class="tc-head">
        <span class="tc-title"><i class="fas fa-list" style="color:var(--pk);"></i>Appointment Record Directory</span>
        <span class="tc-count">{{ $appointments->total() }} total records</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="dt">
            <thead>
                <tr>
                    <th>Ref #</th>
                    <th>Client</th>
                    <th>Salon</th>
                    <th>Service</th>
                    <th>Stylist</th>
                    <th>Date & Time</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse($appointments as $appt)
            @php
            $sb = [
                'confirmed'         =>['bg'=>'#eaf3eb','color'=>'#3d7045','dot'=>'#5a8a62','label'=>'Confirmed'],
                'pending_payment'   =>['bg'=>'#fff8e1','color'=>'#a06800','dot'=>'#c47f00','label'=>'Pending'],
                'payment_submitted' =>['bg'=>'#fde9f4','color'=>'#b0156a','dot'=>'#FF6B9D','label'=>'Pay Submitted'],
                'completed'         =>['bg'=>'#f0eeff','color'=>'#5248a0','dot'=>'#6d5cae','label'=>'Completed'],
                'cancelled'         =>['bg'=>'#fdecea','color'=>'#a02820','dot'=>'#c0392b','label'=>'Cancelled'],
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
                <td><span class="cn" style="font-weight:600;">{{ Str::limit($appt->salon->name ?? 'N/A', 18) }}</span></td>
                <td style="font-size:.84rem; font-weight:600;">{{ Str::limit($appt->service->name ?? 'N/A', 16) }}</td>
                <td style="font-size:.82rem; color:#5a4e5e;">{{ $appt->stylist->name ?? '—' }}</td>
                <td>
                    <div style="font-weight:700;font-size:.84rem;">{{ \Carbon\Carbon::parse($appt->appointment_date)->format('d M Y') }}</div>
                    <div class="csub">{{ \Carbon\Carbon::parse($appt->start_time)->format('h:i A') }}</div>
                </td>
                <td style="font-weight:800;color:var(--pk);">Rs. {{ number_format($appt->total_amount ?? 0) }}</td>
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
                    @else<span style="color:#B09CB0;font-size:.78rem;">—</span>@endif
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
                    <p>No appointments match your criteria</p>
                    <a href="{{ route('admin.appointments.index') }}" class="btn-go" style="display:inline-flex;margin-top:.6rem;text-decoration:none;">Clear Filters</a>
                </div>
            </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- ── Table Footer & Clean Pagination Links ── --}}
    @if($appointments->hasPages())
    <div class="pgn-wrap">
        <div style="font-size:.82rem;color:var(--text-muted);font-weight:600;">
            Showing {{ $appointments->firstItem() ?? 0 }} to {{ $appointments->lastItem() ?? 0 }} of {{ $appointments->total() }} results
        </div>
        <div>
            {{ $appointments->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
    @endif
</div>

@endsection