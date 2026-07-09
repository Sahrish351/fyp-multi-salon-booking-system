{{-- FILE: resources/views/client/reviews/index.blade.php --}}
@extends('layouts.client')
@section('title', 'My Reviews — Glamora')
@section('content')

<style>
:root { --pink:#E91E8C; --pink-lt:#fce4ec; --pink-bg:#fff5f9; }
.rev-card { background:#fff; border-radius:18px; border:1px solid var(--pink-lt); padding:1.4rem; transition:all .25s; }
.rev-card:hover { border-color:var(--pink); box-shadow:0 8px 24px rgba(233,30,140,.08); transform:translateY(-2px); }
.status-badge { display:inline-flex; align-items:center; gap:.35rem; padding:.28rem .85rem; border-radius:20px; font-size:.71rem; font-weight:700; }
.badge-reviewed    { background:#f0fdf4; color:#16a34a; }
.badge-notreviewed { background:#fff8e1; color:#b45309; }
.star-display i { font-size:.95rem; }
.action-btn {
    display:inline-flex; align-items:center; gap:.3rem;
    padding:.38rem .85rem; border-radius:8px; font-size:.76rem;
    font-weight:700; text-decoration:none; border:1.5px solid;
    transition:all .15s; cursor:pointer; background:none; font-family:inherit;
}
.btn-view   { background:var(--pink-bg); color:var(--pink); border-color:rgba(233,30,140,.2); }
.btn-view:hover { background:var(--pink); color:#fff; }
.btn-edit   { background:#f0f4ff; color:#3b5bdb; border-color:#d0daff; }
.btn-edit:hover { background:#3b5bdb; color:#fff; border-color:#3b5bdb; }
.btn-delete { background:#fff0f0; color:#dc2626; border-color:#fecaca; }
.btn-delete:hover { background:#dc2626; color:#fff; border-color:#dc2626; }
.filter-pill { display:inline-flex; align-items:center; gap:.35rem; padding:.42rem 1rem; border-radius:999px; font-size:.78rem; font-weight:700; text-decoration:none; border:1.5px solid #e5e5e5; color:#999; transition:all .15s; }
.filter-pill.on { background:var(--pink); color:#fff; border-color:var(--pink); }
.filter-pill:hover:not(.on) { border-color:var(--pink); color:var(--pink); }
</style>

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1" style="color:#333;font-family:'Playfair Display',serif;">
            <i class="fas fa-star me-2" style="color:#ffc107;"></i>My Reviews
        </h4>
        <p style="color:#aaa;font-size:.85rem;margin:0;">Your completed appointments and reviews</p>
    </div>
    <a href="{{ route('client.appointments.index') }}" class="btn btn-sm rounded-pill px-4"
       style="background:linear-gradient(135deg,#E91E8C,#c2185b);color:#fff;border:none;font-weight:600;">
        <i class="fas fa-calendar-check me-1"></i>My Appointments
    </a>
</div>

{{-- Filters --}}
<div class="d-flex gap-2 mb-4 flex-wrap">
    @php $cur = request('status','all'); @endphp
    <a href="{{ route('client.reviews.index') }}"                            class="filter-pill {{ $cur==='all'          ? 'on' : '' }}"><i class="fas fa-th-list" style="font-size:.65rem;"></i> All</a>
    <a href="{{ route('client.reviews.index',['status'=>'not_reviewed']) }}" class="filter-pill {{ $cur==='not_reviewed' ? 'on' : '' }}"><i class="fas fa-clock"   style="font-size:.65rem;"></i> Not Reviewed</a>
    <a href="{{ route('client.reviews.index',['status'=>'reviewed']) }}"     class="filter-pill {{ $cur==='reviewed'     ? 'on' : '' }}"><i class="fas fa-check"   style="font-size:.65rem;"></i> Reviewed</a>
</div>

{{-- Session alerts --}}
@if(session('success'))
<div class="alert d-flex align-items-center gap-2 mb-4 rounded-3" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;font-size:.88rem;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="alert d-flex align-items-center gap-2 mb-4 rounded-3" style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;font-size:.88rem;">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

{{-- Cards --}}
<div class="row g-4">
    @forelse($appointments as $appt)
    @php $review = $appt->review; @endphp
    <div class="col-lg-6">
        <div class="rev-card">

            {{-- Top row --}}
            <div class="d-flex align-items-start gap-3 mb-3">
                <img src="{{ $appt->salon->logo_url ?? '' }}"
                     class="rounded-2 flex-shrink-0" width="48" height="48" style="object-fit:cover;"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($appt->salon->name ?? 'S') }}&background=E91E8C&color=fff'">
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-1" style="color:#1a1a1a;font-size:.95rem;">{{ $appt->salon->name }}</h6>
                    <div style="color:#888;font-size:.78rem;">
                        <i class="fas fa-spa me-1" style="color:var(--pink);font-size:.7rem;"></i>{{ $appt->service->name ?? '' }}
                        &nbsp;·&nbsp;
                        <i class="fas fa-calendar me-1" style="color:#6c757d;font-size:.7rem;"></i>{{ $appt->appointment_date->format('d M Y') }}
                    </div>
                </div>
                @if($review)
                <span class="status-badge badge-reviewed"><i class="fas fa-check-circle" style="font-size:.65rem;"></i> Reviewed</span>
                @else
                <span class="status-badge badge-notreviewed"><i class="fas fa-clock" style="font-size:.65rem;"></i> Not Reviewed</span>
                @endif
            </div>

            {{-- If reviewed: show stars + comment preview --}}
            @if($review)
            <div class="d-flex align-items-center gap-1 mb-2 star-display">
                @for($i=1;$i<=5;$i++)
                <i class="fas fa-star" style="color:{{ $i<=$review->rating ? '#ffc107' : '#e5e7eb' }};"></i>
                @endfor
                <span class="fw-bold ms-1" style="color:#333;font-size:.85rem;">{{ $review->rating }}/5</span>
                <span style="color:#aaa;font-size:.75rem;margin-left:auto;">{{ $review->created_at->diffForHumans() }}</span>
            </div>
            <p style="color:#666;font-size:.83rem;line-height:1.6;margin-bottom:.9rem;">
                {{ Str::limit($review->comment, 100) }}
            </p>

            {{-- Owner replied badge --}}
            @if($review->reply)
            <div class="d-flex align-items-center gap-2 p-2 rounded-3 mb-3" style="background:rgba(233,30,140,.05);border-left:3px solid var(--pink);">
                <i class="fas fa-reply" style="color:var(--pink);font-size:.75rem;"></i>
                <span style="color:var(--pink);font-size:.75rem;font-weight:700;">Salon owner has replied</span>
            </div>
            @endif

            {{-- Actions --}}
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('client.reviews.show', $review->id) }}" class="action-btn btn-view">
                    <i class="fas fa-eye"></i> View
                </a>
                @if(!$review->reply)
                <a href="{{ route('client.reviews.edit', $review->id) }}" class="action-btn btn-edit">
                    <i class="fas fa-pen"></i> Edit
                </a>
                <form action="{{ route('client.reviews.destroy', $review->id) }}" method="POST"
                      onsubmit="return confirm('Are you sure you want to delete this review?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="action-btn btn-delete">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
                @endif
            </div>

            @else
            {{-- Not reviewed yet --}}
            <p style="color:#aaa;font-size:.82rem;margin-bottom:.9rem;">
                <i class="fas fa-info-circle me-1"></i>You haven't reviewed this appointment yet.
            </p>
            <a href="{{ route('client.reviews.create', $appt->id) }}" class="action-btn btn-view">
                <i class="fas fa-star"></i> Leave a Review
            </a>
            @endif

        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5 bg-white rounded-4" style="border:2px dashed var(--pink-lt);">
            <i class="fas fa-star fa-4x mb-3" style="color:rgba(255,193,7,.3);"></i>
            <h5 style="color:#333;">No completed appointments yet</h5>
            <p style="color:#aaa;max-width:350px;margin:0 auto 1rem;">Complete an appointment to share your experience!</p>
            <a href="{{ route('client.appointments.index') }}" class="btn rounded-pill px-5"
               style="background:linear-gradient(135deg,#ffc107,#f59e0b);color:#fff;border:none;font-weight:600;">
                <i class="fas fa-calendar-check me-2"></i>My Appointments
            </a>
        </div>
    </div>
    @endforelse
</div>

@if($appointments->hasPages())
<div class="mt-4">{{ $appointments->links() }}</div>
@endif
@endsection