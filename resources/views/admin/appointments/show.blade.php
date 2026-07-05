@extends('layouts.admin')
@section('title', 'Appointment Details — Glamora')

@section('content')
<style>
:root {
    --rose:   #d4637a; --rose-lt:  #fdf0f3; --rose-h: #bf4f65;
    --sage:   #6b8f71; --sage-lt:  #f0f5f1;
    --slate:  #5c7a8a; --slate-lt: #eef3f6;
    --amber:  #b07d3a; --amber-lt: #fdf6ec;
    --mist:   #7a6b8a; --mist-lt:  #f4f1f7;
    --red:    #b84444; --red-lt:   #fdf0f0;
    --green:  #6b8f71; --green-lt: #f0f5f1;
}

.btn-back {
    display:inline-flex; align-items:center; gap:.5rem;
    padding:.5rem 1.1rem; border:1.5px solid #e5e0d8; border-radius:9px;
    font-size:.86rem; font-weight:600; color:#8a8a8a; text-decoration:none;
    background:#fff; transition:all .15s; margin-bottom:1.75rem;
}
.btn-back:hover { border-color:var(--rose); color:var(--rose); }

/* ── Status Hero Strip ── */
.status-hero {
    border-radius:14px; padding:1.25rem 1.5rem;
    display:flex; align-items:center; justify-content:space-between;
    flex-wrap:wrap; gap:.75rem; margin-bottom:1.5rem; border:1px solid transparent;
}
.status-hero-left { display:flex; align-items:center; gap:.85rem; }
.status-hero-icon {
    width:46px; height:46px; border-radius:12px;
    display:flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0;
}
.status-hero-label { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; opacity:.7; margin-bottom:.2rem; }
.status-hero-val   { font-size:1.1rem; font-weight:800; }
.ref-badge { font-family:monospace; font-weight:700; font-size:.92rem; padding:.3rem .85rem; border-radius:8px; }

/* ── Two-col layout ── */
.detail-grid { display:grid; grid-template-columns:1fr 300px; gap:1.4rem; align-items:start; }
@media(max-width:900px){ .detail-grid { grid-template-columns:1fr; } }

/* ── Detail Card ── */
.dcard { background:#fff; border:1px solid #ede9e4; border-radius:14px; overflow:hidden; margin-bottom:1.2rem; }
.dcard:last-child { margin-bottom:0; }
.dcard-head { padding:.9rem 1.4rem; border-bottom:1px solid #f5f2ee; display:flex; align-items:center; gap:.5rem; }
.dcard-head i { color:var(--rose); font-size:.88rem; }
.dcard-title { font-weight:700; font-size:.9rem; color:#2d2d2d; }
.dcard-body  { padding:1.4rem; }

/* ── Info Grid ── */
.info-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.1rem; }
@media(max-width:600px){ .info-grid { grid-template-columns:1fr; } }
.info-item {}
.info-lbl { font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#9a9a9a; margin-bottom:.35rem; display:block; }
.info-val  { font-size:.92rem; color:#2d2d2d; font-weight:500; margin:0; }
.info-val-strong { font-size:1rem; font-weight:700; color:#2d2d2d; margin:0; }
.info-sub  { font-size:.75rem; color:#9a9a9a; margin:.08rem 0 0; }

/* ── Status Badge (inline) ── */
.sbadge {
    display:inline-flex; align-items:center; gap:.3rem;
    padding:.28rem .75rem; border-radius:20px; font-size:.75rem; font-weight:700;
}

/* ── Payment Receipt ── */
.receipt-row { display:flex; justify-content:space-between; align-items:center; padding:.6rem 0; border-bottom:1px solid #f5f2ee; }
.receipt-row:last-child { border-bottom:none; }
.receipt-lbl { font-size:.82rem; color:#9a9a9a; }
.receipt-val { font-size:.88rem; font-weight:600; color:#2d2d2d; }
.receipt-total .receipt-lbl { font-weight:700; color:#2d2d2d; font-size:.9rem; }
.receipt-total .receipt-val { font-size:1.1rem; font-weight:800; color:var(--rose); }

/* ── Action Buttons ── */
.act-btn {
    display:flex; align-items:center; justify-content:center; gap:.5rem;
    width:100%; padding:.75rem 1rem; border-radius:10px; font-size:.9rem; font-weight:700;
    cursor:pointer; border:none; transition:all .18s; margin-bottom:.65rem; text-decoration:none;
    box-sizing:border-box;
}
.act-btn:last-child { margin-bottom:0; }
.act-btn-primary { background:var(--rose); color:#fff; }
.act-btn-primary:hover { background:var(--rose-h); transform:translateY(-1px); box-shadow:0 4px 14px rgba(212,99,122,.3); color:#fff; }
.act-btn-sage { background:var(--sage-lt); color:var(--sage); border:1.5px solid rgba(107,143,113,.3); }
.act-btn-sage:hover { background:var(--sage); color:#fff; }
.act-btn-ghost { background:#f5f2ee; color:#8a8a8a; border:1.5px solid #e8e3dc; }
.act-btn-ghost:hover { background:#ece8e3; color:#5a5a5a; }

/* ── Status visual ── */
.status-visual { text-align:center; padding:1rem 0; }
.status-visual-icon { font-size:2.2rem; margin-bottom:.5rem; display:block; }
.status-visual-text { font-size:.88rem; color:#8a8a8a; margin:0; }

/* ── Timeline ── */
.timeline { position:relative; padding-left:1.5rem; }
.timeline::before { content:''; position:absolute; left:.45rem; top:0; bottom:0; width:2px; background:#f0ece8; border-radius:2px; }
.tl-item { position:relative; margin-bottom:1rem; }
.tl-item:last-child { margin-bottom:0; }
.tl-dot {
    position:absolute; left:-1.5rem; top:.2rem;
    width:14px; height:14px; border-radius:50%;
    border:2px solid #fff; box-shadow:0 0 0 2px #e0dbd3;
    background:#c0b8b0; flex-shrink:0;
}
.tl-dot-done { background:var(--sage); box-shadow:0 0 0 2px rgba(107,143,113,.3); }
.tl-dot-cur  { background:var(--rose); box-shadow:0 0 0 2px rgba(212,99,122,.3); }
.tl-label { font-size:.83rem; font-weight:600; color:#4a4a4a; }
.tl-sub   { font-size:.72rem; color:#9a9a9a; margin-top:.1rem; }
</style>

@php
$badges = [
    'confirmed'        => ['bg'=>'#f0f5f1','color'=>'#6b8f71','icon'=>'fa-check-circle','label'=>'Confirmed',        'hero_bg'=>'#f0f5f1','hero_border'=>'rgba(107,143,113,.2)'],
    'pending_payment'  => ['bg'=>'#fdf6ec','color'=>'#b07d3a','icon'=>'fa-clock',       'label'=>'Pending Payment',  'hero_bg'=>'#fdf6ec','hero_border'=>'rgba(176,125,58,.2)'],
    'payment_submitted'=> ['bg'=>'#fdf0f3','color'=>'#d4637a','icon'=>'fa-paper-plane',  'label'=>'Payment Submitted','hero_bg'=>'#fdf0f3','hero_border'=>'rgba(212,99,122,.2)'],
    'completed'        => ['bg'=>'#f4f1f7','color'=>'#7a6b8a','icon'=>'fa-star',         'label'=>'Completed',        'hero_bg'=>'#f4f1f7','hero_border'=>'rgba(122,107,138,.2)'],
    'cancelled'        => ['bg'=>'#fdf0f0','color'=>'#b84444','icon'=>'fa-ban',          'label'=>'Cancelled',        'hero_bg'=>'#fdf0f0','hero_border'=>'rgba(184,68,68,.2)'],
];
$sc = $badges[$appointment->status] ?? ['bg'=>'#f5f5f5','color'=>'#888','icon'=>'fa-circle','label'=>ucfirst($appointment->status),'hero_bg'=>'#f5f5f5','hero_border'=>'#eee'];

$payBadges = [
    'approved'=> ['bg'=>'#f0f5f1','color'=>'#6b8f71','dot'=>'#6b8f71','label'=>'Approved'],
    'pending' => ['bg'=>'#fdf6ec','color'=>'#b07d3a','dot'=>'#b07d3a','label'=>'Pending'],
    'rejected'=> ['bg'=>'#fdf0f0','color'=>'#b84444','dot'=>'#b84444','label'=>'Rejected'],
];
$pc = isset($appointment->payment) ? ($payBadges[$appointment->payment->status] ?? ['bg'=>'#f5f5f5','color'=>'#888','dot'=>'#888','label'=>'—']) : null;
@endphp

{{-- Back --}}
<a href="{{ route('admin.appointments.index') }}" class="btn-back">
    <i class="fas fa-arrow-left"></i> Back to Appointments
</a>

{{-- Status Hero Strip --}}
<div class="status-hero" style="background:{{ $sc['hero_bg'] }};border-color:{{ $sc['hero_border'] }};">
    <div class="status-hero-left">
        <div class="status-hero-icon" style="background:rgba(255,255,255,.7);color:{{ $sc['color'] }};">
            <i class="fas {{ $sc['icon'] }}"></i>
        </div>
        <div>
            <div class="status-hero-label" style="color:{{ $sc['color'] }};">Appointment Status</div>
            <div class="status-hero-val" style="color:{{ $sc['color'] }};">{{ $sc['label'] }}</div>
        </div>
    </div>
    <span class="ref-badge" style="background:rgba(255,255,255,.7);color:{{ $sc['color'] }};">
        {{ $appointment->booking_ref ?? '#'.$appointment->id }}
    </span>
</div>

<div class="detail-grid">

    {{-- ══ LEFT ══ --}}
    <div>

        {{-- Appointment Details --}}
        <div class="dcard">
            <div class="dcard-head"><i class="fas fa-calendar-check"></i><span class="dcard-title">Appointment Details</span></div>
            <div class="dcard-body">
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-lbl">Client</span>
                        <p class="info-val-strong">{{ $appointment->client->name ?? 'N/A' }}</p>
                        @if($appointment->client->phone ?? false)
                        <p class="info-sub"><i class="fas fa-phone" style="font-size:.62rem;"></i> {{ $appointment->client->phone }}</p>
                        @endif
                    </div>
                    <div class="info-item">
                        <span class="info-lbl">Salon</span>
                        <p class="info-val-strong">{{ $appointment->salon->name ?? 'N/A' }}</p>
                        @if($appointment->salon->city ?? false)
                        <p class="info-sub"><i class="fas fa-map-marker-alt" style="font-size:.62rem;"></i> {{ $appointment->salon->city }}</p>
                        @endif
                    </div>
                    <div class="info-item">
                        <span class="info-lbl">Service</span>
                        <p class="info-val">{{ $appointment->service->name ?? 'N/A' }}</p>
                        @if($appointment->service->duration ?? false)
                        <p class="info-sub"><i class="fas fa-clock" style="font-size:.62rem;"></i> {{ $appointment->service->duration }} min</p>
                        @endif
                    </div>
                    <div class="info-item">
                        <span class="info-lbl">Stylist</span>
                        <p class="info-val">{{ $appointment->stylist->name ?? 'Not assigned' }}</p>
                    </div>
                    <div class="info-item">
                        <span class="info-lbl">Date</span>
                        <p class="info-val-strong">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}</p>
                        <p class="info-sub">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l') }}</p>
                    </div>
                    <div class="info-item">
                        <span class="info-lbl">Time</span>
                        <p class="info-val-strong">{{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}</p>
                        @if($appointment->end_time)
                        <p class="info-sub">Ends {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}</p>
                        @endif
                    </div>
                    <div class="info-item">
                        <span class="info-lbl">Booked On</span>
                        <p class="info-val">{{ $appointment->created_at->format('d M Y') }}</p>
                        <p class="info-sub">{{ $appointment->created_at->format('h:i A') }}</p>
                    </div>
                    <div class="info-item">
                        <span class="info-lbl">Status</span>
                        <span class="sbadge" style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }};">
                            <i class="fas {{ $sc['icon'] }}" style="font-size:.65rem;"></i> {{ $sc['label'] }}
                        </span>
                    </div>
                </div>

                @if($appointment->notes ?? false)
                <div style="margin-top:1.1rem;padding:1rem;background:#faf8f6;border-radius:10px;border-left:3px solid var(--rose);">
                    <span class="info-lbl" style="margin-bottom:.3rem;display:block;">Client Notes</span>
                    <p style="margin:0;font-size:.88rem;color:#4a4a4a;">{{ $appointment->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Payment Info --}}
        @if($appointment->payment)
        <div class="dcard">
            <div class="dcard-head"><i class="fas fa-credit-card"></i><span class="dcard-title">Payment Information</span></div>
            <div class="dcard-body">
                <div class="receipt-row">
                    <span class="receipt-lbl">Payment Method</span>
                    <span class="receipt-val">{{ ucfirst($appointment->payment->method ?? '—') }}</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-lbl">Payment Status</span>
                    <span class="receipt-val">
                        @if($pc)
                        <span class="sbadge" style="background:{{ $pc['bg'] }};color:{{ $pc['color'] }};">
                            <span style="width:6px;height:6px;border-radius:50%;background:{{ $pc['dot'] }};display:inline-block;"></span>
                            {{ $pc['label'] }}
                        </span>
                        @else — @endif
                    </span>
                </div>
                @if($appointment->payment->transaction_id ?? false)
                <div class="receipt-row">
                    <span class="receipt-lbl">Transaction ID</span>
                    <span class="receipt-val" style="font-family:monospace;font-size:.82rem;">{{ $appointment->payment->transaction_id }}</span>
                </div>
                @endif
                @if($appointment->payment->paid_at ?? false)
                <div class="receipt-row">
                    <span class="receipt-lbl">Paid At</span>
                    <span class="receipt-val">{{ \Carbon\Carbon::parse($appointment->payment->paid_at)->format('d M Y, h:i A') }}</span>
                </div>
                @endif
                <div class="receipt-row receipt-total" style="margin-top:.4rem;padding-top:.75rem;border-top:2px solid #ede9e4;">
                    <span class="receipt-lbl">Total Amount</span>
                    <span class="receipt-val">Rs. {{ number_format($appointment->total_amount ?? 0) }}</span>
                </div>
            </div>
        </div>
        @else
        {{-- No payment yet —  amount only --}}
        <div class="dcard">
            <div class="dcard-head"><i class="fas fa-credit-card"></i><span class="dcard-title">Amount</span></div>
            <div class="dcard-body">
                <div class="receipt-row receipt-total">
                    <span class="receipt-lbl">Total Amount</span>
                    <span class="receipt-val">Rs. {{ number_format($appointment->total_amount ?? 0) }}</span>
                </div>
                <p style="font-size:.8rem;color:#9a9a9a;margin:.75rem 0 0;">No payment record yet.</p>
            </div>
        </div>
        @endif

    </div>

    {{-- ══ RIGHT ══ --}}
    <div>

        {{-- Status (view-only — action buttons removed, this card now just shows what's happening) --}}
        <div class="dcard">
            <div class="dcard-head"><i class="fas fa-bolt"></i><span class="dcard-title">Status</span></div>
            <div class="dcard-body">

                @if($appointment->status === 'confirmed')
                    <div class="status-visual">
                        <i class="fas fa-check-circle status-visual-icon" style="color:var(--sage);"></i>
                        <p class="status-visual-text">Appointment is confirmed and scheduled.</p>
                    </div>

                @elseif($appointment->status === 'pending_payment')
                    <div class="status-visual">
                        <i class="fas fa-clock status-visual-icon" style="color:var(--amber);"></i>
                        <p class="status-visual-text">Waiting for client to complete payment.</p>
                    </div>

                @elseif($appointment->status === 'payment_submitted')
                    <div class="status-visual">
                        <i class="fas fa-paper-plane status-visual-icon" style="color:var(--rose);"></i>
                        <p class="status-visual-text">Payment submitted by client, awaiting verification.</p>
                    </div>

                @elseif($appointment->status === 'completed')
                    <div class="status-visual">
                        <i class="fas fa-star status-visual-icon" style="color:var(--mist);"></i>
                        <p class="status-visual-text">This appointment has been completed successfully.</p>
                    </div>

                @elseif($appointment->status === 'cancelled')
                    <div class="status-visual">
                        <i class="fas fa-ban status-visual-icon" style="color:var(--red);"></i>
                        <p class="status-visual-text">This appointment was cancelled.</p>
                    </div>

                @else
                    <p style="color:#9a9a9a;font-size:.85rem;margin:0;text-align:center;">No status information available.</p>
                @endif

            </div>
        </div>

        {{-- Status Timeline --}}
        <div class="dcard">
            <div class="dcard-head"><i class="fas fa-stream"></i><span class="dcard-title">Status Timeline</span></div>
            <div class="dcard-body">
                @php
                $steps = [
                    'pending_payment'   => 'Pending Payment',
                    'payment_submitted' => 'Payment Submitted',
                    'confirmed'         => 'Confirmed',
                    'completed'         => 'Completed',
                ];
                $order = array_keys($steps);
                $curIdx = array_search($appointment->status, $order);
                @endphp
                @if($appointment->status === 'cancelled')
                <div class="timeline">
                    <div class="tl-item">
                        <div class="tl-dot tl-dot-done"></div>
                        <div class="tl-label">Booking Created</div>
                        <div class="tl-sub">{{ $appointment->created_at->format('d M Y, h:i A') }}</div>
                    </div>
                    <div class="tl-item">
                        <div class="tl-dot" style="background:var(--red);box-shadow:0 0 0 2px rgba(184,68,68,.3);"></div>
                        <div class="tl-label" style="color:var(--red);">Cancelled</div>
                        <div class="tl-sub">{{ $appointment->updated_at->format('d M Y, h:i A') }}</div>
                    </div>
                </div>
                @else
                <div class="timeline">
                    <div class="tl-item">
                        <div class="tl-dot tl-dot-done"></div>
                        <div class="tl-label">Booking Created</div>
                        <div class="tl-sub">{{ $appointment->created_at->format('d M Y') }}</div>
                    </div>
                    @foreach($steps as $key => $label)
                    @php $idx = array_search($key, $order); @endphp
                    <div class="tl-item">
                        <div class="tl-dot {{ $curIdx !== false && $idx < $curIdx ? 'tl-dot-done' : ($appointment->status===$key ? 'tl-dot-cur' : '') }}"></div>
                        <div class="tl-label" style="{{ $appointment->status===$key ? 'color:var(--rose);font-weight:700;' : ($curIdx!==false && $idx<$curIdx ? 'color:#6b8f71;' : 'color:#c0b8b0;') }}">
                            {{ $label }}
                        </div>
                        @if($appointment->status === $key)
                        <div class="tl-sub" style="color:var(--rose);">Current status</div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- Quick Info --}}
        <div class="dcard">
            <div class="dcard-head"><i class="fas fa-info-circle"></i><span class="dcard-title">Quick Info</span></div>
            <div class="dcard-body">
                <div class="receipt-row">
                    <span class="receipt-lbl">Appointment ID</span>
                    <span class="receipt-val" style="font-family:monospace;">#{{ $appointment->id }}</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-lbl">Created</span>
                    <span class="receipt-val">{{ $appointment->created_at->diffForHumans() }}</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-lbl">Last Updated</span>
                    <span class="receipt-val">{{ $appointment->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection