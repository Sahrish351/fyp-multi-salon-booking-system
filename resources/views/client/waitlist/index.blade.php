{{-- ============================================================ --}}
{{-- FILE: resources/views/client/waitlist/index.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.client')
@section('title', 'My Waitlist — Glamora')

@push('styles')
<style>
.waitlist-card {
    background: #fff;
    border: 1px solid #fce4ec;
    border-radius: 20px;
    padding: 1.5rem;
    transition: all .3s;
    position: relative;
    overflow: hidden;
}
.waitlist-card:hover {
    border-color: #E91E8C;
    box-shadow: 0 10px 30px rgba(233,30,140,0.12);
    transform: translateY(-4px);
}
.waitlist-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(180deg, #E91E8C, #c2185b);
    border-radius: 20px 0 0 20px;
}
.position-badge {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: linear-gradient(135deg, #E91E8C, #c2185b);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    font-weight: 800;
    color: #fff;
    flex-shrink: 0;
    box-shadow: 0 4px 15px rgba(233,30,140,0.3);
}
.info-pill {
    background: #fff5f9;
    border: 1px solid #fce4ec;
    border-radius: 10px;
    padding: 0.5rem 0.75rem;
}
.info-pill .pill-label { color: #aaa; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; }
.info-pill .pill-value { color: #333; font-size: 0.82rem; font-weight: 600; }
.notified-banner {
    background: linear-gradient(135deg, rgba(233,30,140,0.08), rgba(192,132,252,0.08));
    border: 1px solid rgba(233,30,140,0.2);
    border-radius: 12px;
    padding: 1rem 1.25rem;
}
.btn-accept {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 0.6rem 1.5rem;
    font-weight: 600;
    font-size: 0.88rem;
    transition: all .3s;
}
.btn-accept:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(34,197,94,0.4); color: #fff; }
.btn-decline {
    background: #fff0f7;
    color: #ef4444;
    border: 1px solid #fce4ec;
    border-radius: 10px;
    padding: 0.6rem 1.5rem;
    font-weight: 600;
    font-size: 0.88rem;
    transition: all .3s;
}
.btn-decline:hover { background: #fff5f5; border-color: #fca5a5; }
.empty-state { text-align: center; padding: 4rem 2rem; background: #fff; border-radius: 20px; border: 2px dashed #fce4ec; }
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1" style="color:#333;font-family:'Playfair Display',serif;">
            <i class="fas fa-list-ol me-2" style="color:#E91E8C;"></i>My Waitlist
        </h4>
        <p style="color:#aaa;font-size:0.85rem;margin:0;">
            You'll be notified when your preferred slot becomes available
        </p>
    </div>
    <a href="{{ route('salons.index') }}" class="btn btn-sm rounded-pill px-4"
       style="background:linear-gradient(135deg,#E91E8C,#c2185b);color:#fff;border:none;font-weight:600;">
        <i class="fas fa-search me-1"></i>Find More Salons
    </a>
</div>

{{-- Status Tabs --}}
<div class="d-flex gap-2 mb-4 flex-wrap">
    @foreach(['all'=>'All','waiting'=>'Waiting','notified'=>'Notified','accepted'=>'Accepted','expired'=>'Expired'] as $val=>$lbl)
    <a href="{{ route('client.waitlist.index',['status'=>$val]) }}"
       class="btn btn-sm rounded-pill"
       style="{{ request('status')===$val || (!request('status') && $val==='all') ? 'background:#E91E8C;color:#fff;border:none;font-weight:600;' : 'background:#fff;color:#888;border:1px solid #fce4ec;' }}font-size:0.82rem;padding:6px 16px;">
        {{ $lbl }}
        @if($val === 'notified')
        @php $notifiedCount = $waitlists->where('status','notified')->count(); @endphp
        @if($notifiedCount > 0)
        <span class="badge rounded-pill ms-1" style="background:#ef4444;font-size:0.65rem;">{{ $notifiedCount }}</span>
        @endif
        @endif
    </a>
    @endforeach
</div>

{{-- Notified Alert --}}
@php $notifiedItems = $waitlists->where('status','notified'); @endphp
@if($notifiedItems->count() > 0)
<div class="p-3 rounded-3 mb-4" style="background:linear-gradient(135deg,rgba(233,30,140,0.08),rgba(192,132,252,0.08));border:1px solid rgba(233,30,140,0.2);">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:44px;height:44px;background:rgba(233,30,140,0.15);">
            <i class="fas fa-bell" style="color:#E91E8C;font-size:1rem;"></i>
        </div>
        <div>
            <div class="fw-bold" style="color:#333;font-size:0.9rem;">
                🎉 Slot Available! {{ $notifiedItems->count() }} waitlist slot{{ $notifiedItems->count() > 1 ? 's' : '' }} opened up for you!
            </div>
            <div style="color:#888;font-size:0.82rem;">Please accept or decline within the time limit to confirm your booking.</div>
        </div>
    </div>
</div>
@endif

{{-- Waitlist Items --}}
<div class="row g-4">
    @forelse($waitlists as $wl)
    <div class="col-lg-6">
        <div class="waitlist-card">

            {{-- Header --}}
            <div class="d-flex align-items-start gap-3 mb-3">
                <div class="position-badge">#{{ $wl->position }}</div>
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-1" style="color:#333;font-size:0.95rem;">{{ $wl->salon->name }}</h6>
                    <div style="color:#888;font-size:0.8rem;">
                        <i class="fas fa-map-marker-alt me-1" style="color:#E91E8C;font-size:0.72rem;"></i>
                        {{ $wl->salon->area ? $wl->salon->area.', ' : '' }}{{ $wl->salon->city }}
                    </div>
                </div>
                {{-- Status Badge --}}
                @php
                    $wsc = [
                        'waiting'  => ['#E91E8C', 'Waiting'],
                        'notified' => ['#3b82f6', 'Slot Available!'],
                        'accepted' => ['#22c55e', 'Accepted'],
                        'rejected' => ['#ef4444', 'Declined'],
                        'expired'  => ['#aaa',    'Expired'],
                    ][$wl->status] ?? ['#aaa', ucfirst($wl->status)];
                @endphp
                <span style="background:{{ $wsc[0] }}18;color:{{ $wsc[0] }};padding:5px 14px;border-radius:20px;font-size:0.75rem;font-weight:700;flex-shrink:0;">
                    {{ $wsc[1] }}
                </span>
            </div>

            {{-- Info Pills --}}
            <div class="row g-2 mb-3">
                <div class="col-6">
                    <div class="info-pill">
                        <div class="pill-label"><i class="fas fa-spa me-1" style="color:#E91E8C;"></i>Service</div>
                        <div class="pill-value">{{ Str::limit($wl->service->name, 20) }}</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="info-pill">
                        <div class="pill-label"><i class="fas fa-user-circle me-1" style="color:#C9A96E;"></i>Stylist</div>
                        <div class="pill-value">{{ $wl->stylist->name }}</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="info-pill">
                        <div class="pill-label"><i class="fas fa-calendar me-1" style="color:#3b82f6;"></i>Preferred Date</div>
                        <div class="pill-value">{{ $wl->preferred_date->format('d M Y') }}</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="info-pill">
                        <div class="pill-label"><i class="fas fa-clock me-1" style="color:#8b5cf6;"></i>Time Slot</div>
                        <div class="pill-value">
                            {{ \Carbon\Carbon::parse($wl->timeSlot->start_time)->format('h:i A') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Price Info --}}
            <div class="d-flex align-items-center justify-content-between mb-3 p-2 rounded-3" style="background:#fff5f9;">
                <div style="color:#888;font-size:0.8rem;">Service Price</div>
                <div style="color:#E91E8C;font-weight:700;font-size:0.95rem;">Rs. {{ number_format($wl->service->price) }}</div>
            </div>

            {{-- Notified Action Buttons --}}
            @if($wl->status === 'notified')
            <div class="notified-banner mb-3">
                <div class="fw-semibold mb-1" style="color:#E91E8C;font-size:0.85rem;">
                    <i class="fas fa-bell me-1"></i>Your slot is ready!
                </div>
                <p style="color:#555;font-size:0.78rem;margin:0;line-height:1.6;">
                    This slot has been reserved for you. Accept within
                    @if($wl->expires_at)
                    <strong style="color:#ef4444;">{{ \Carbon\Carbon::now()->diffInMinutes($wl->expires_at) }} minutes</strong>
                    @else
                    <strong style="color:#ef4444;">10 minutes</strong>
                    @endif
                    to secure your booking.
                </p>
                @if($wl->expires_at)
                <div class="mt-2 fw-bold" style="color:#ef4444;font-family:monospace;font-size:1rem;" id="wlTimer_{{ $wl->id }}">
                    Loading...
                </div>
                @endif
            </div>
            <div class="d-flex gap-2">
                <form action="{{ route('client.waitlist.accept', $wl->id) }}" method="POST" class="flex-grow-1">
                    @csrf
                    <button type="submit" class="btn-accept w-100">
                        <i class="fas fa-check me-1"></i>Accept Slot
                    </button>
                </form>
                <form action="{{ route('client.waitlist.reject', $wl->id) }}" method="POST" class="flex-grow-1">
                    @csrf
                    <button type="submit" class="btn-decline w-100">
                        <i class="fas fa-times me-1"></i>Decline
                    </button>
                </form>
            </div>

            {{-- Waiting Status --}}
            @elseif($wl->status === 'waiting')
            <div class="d-flex align-items-center justify-content-between">
                <div style="color:#aaa;font-size:0.78rem;">
                    <i class="fas fa-clock me-1"></i>Joined {{ $wl->created_at->diffForHumans() }}
                </div>
                <div style="color:#E91E8C;font-size:0.78rem;font-weight:600;">
                    <i class="fas fa-users me-1"></i>Position #{{ $wl->position }} in queue
                </div>
            </div>

            {{-- Accepted Status --}}
            @elseif($wl->status === 'accepted')
            <div class="p-3 rounded-3" style="background:rgba(34,197,94,0.06);border:1px solid rgba(34,197,94,0.15);">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fas fa-check-circle" style="color:#22c55e;"></i>
                    <span class="fw-semibold" style="color:#22c55e;font-size:0.85rem;">Slot Accepted!</span>
                </div>
                <p style="color:#555;font-size:0.78rem;margin:0;">
                    You accepted this slot on {{ $wl->responded_at?->format('d M Y, h:i A') ?? 'Recently' }}.
                    Complete your booking to confirm.
                </p>
                <a href="{{ route('client.booking.step4', $wl->salon_id) }}" class="btn btn-sm mt-2 rounded-3 fw-semibold"
                   style="background:linear-gradient(135deg,#22c55e,#16a34a);color:#fff;border:none;font-size:0.8rem;">
                    <i class="fas fa-calendar-plus me-1"></i>Complete Booking
                </a>
            </div>

            {{-- Expired/Declined --}}
            @elseif(in_array($wl->status, ['expired','rejected']))
            <div class="p-3 rounded-3" style="background:rgba(239,68,68,0.04);border:1px solid rgba(239,68,68,0.1);">
                <div style="color:#ef4444;font-size:0.82rem;font-weight:500;">
                    <i class="fas fa-times-circle me-1"></i>
                    {{ $wl->status === 'expired' ? 'This slot offer has expired.' : 'You declined this slot.' }}
                </div>
                <div style="color:#aaa;font-size:0.75rem;margin-top:4px;">{{ $wl->responded_at?->diffForHumans() ?? $wl->updated_at->diffForHumans() }}</div>
            </div>
            @endif

        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="empty-state">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                 style="width:100px;height:100px;background:rgba(233,30,140,0.08);">
                <i class="fas fa-list-ol fa-3x" style="color:rgba(233,30,140,0.3);"></i>
            </div>
            <h5 class="fw-bold mb-2" style="color:#333;">You're not on any waitlist</h5>
            <p style="color:#aaa;max-width:350px;margin:0 auto 1.5rem;">
                When your preferred salon slot is fully booked, you can join the waitlist and get notified when a slot opens up!
            </p>
            <a href="{{ route('salons.index') }}" class="btn rounded-pill px-5 py-2 fw-semibold"
               style="background:linear-gradient(135deg,#E91E8C,#c2185b);color:#fff;border:none;font-size:0.95rem;">
                <i class="fas fa-search me-2"></i>Browse Salons
            </a>
        </div>
    </div>
    @endforelse
</div>

@if($waitlists->hasPages())
<div class="mt-4">{{ $waitlists->links() }}</div>
@endif

@endsection

@push('scripts')
<script>
// Countdown timers for notified waitlists
@foreach($waitlists->where('status','notified') as $wl)
@if($wl->expires_at)
(function() {
    const expiresAt = new Date('{{ $wl->expires_at->toIso8601String() }}').getTime();
    const timerId = 'wlTimer_{{ $wl->id }}';
    const interval = setInterval(() => {
        const now = new Date().getTime();
        const diff = expiresAt - now;
        if (diff <= 0) {
            clearInterval(interval);
            const el = document.getElementById(timerId);
            if (el) el.textContent = 'Time expired!';
            return;
        }
        const m = Math.floor(diff / 60000).toString().padStart(2,'0');
        const s = Math.floor((diff % 60000) / 1000).toString().padStart(2,'0');
        const el = document.getElementById(timerId);
        if (el) el.textContent = `⏰ ${m}:${s} remaining`;
    }, 1000);
})();
@endif
@endforeach
</script>
@endpush