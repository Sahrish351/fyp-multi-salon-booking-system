{{-- ============================================================ --}}
{{-- FILE: resources/views/client/reviews/index.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.client')
@section('title', 'My Reviews — Glamora')
@section('content')

<div class="mb-4">
    <h4 class="fw-bold mb-1" style="color:#333;font-family:'Playfair Display',serif;">
        <i class="fas fa-star me-2" style="color:#ffc107;"></i>My Reviews
    </h4>
    <p style="color:#aaa;font-size:0.85rem;margin:0;">Reviews you've submitted for your salon visits</p>
</div>

{{-- Status Filter --}}
<div class="d-flex gap-2 mb-4 flex-wrap">
    <a href="{{ route('client.reviews.index') }}" 
       class="btn btn-sm rounded-pill {{ !request('status') ? 'active' : '' }}"
       style="{{ !request('status') ? 'background:linear-gradient(135deg,#E91E8C,#c2185b);color:#fff;border:none;font-weight:600;' : 'background:#fff;color:#888;border:1px solid #fce4ec;' }}font-size:0.82rem;padding:6px 16px;">
        All
    </a>
    <a href="{{ route('client.reviews.index', ['status' => 'approved']) }}" 
       class="btn btn-sm rounded-pill {{ request('status') === 'approved' ? 'active' : '' }}"
       style="{{ request('status') === 'approved' ? 'background:linear-gradient(135deg,#E91E8C,#c2185b);color:#fff;border:none;font-weight:600;' : 'background:#fff;color:#888;border:1px solid #fce4ec;' }}font-size:0.82rem;padding:6px 16px;">
        Approved
    </a>
    <a href="{{ route('client.reviews.index', ['status' => 'pending']) }}" 
       class="btn btn-sm rounded-pill {{ request('status') === 'pending' ? 'active' : '' }}"
       style="{{ request('status') === 'pending' ? 'background:linear-gradient(135deg,#E91E8C,#c2185b);color:#fff;border:none;font-weight:600;' : 'background:#fff;color:#888;border:1px solid #fce4ec;' }}font-size:0.82rem;padding:6px 16px;">
        Pending
    </a>
</div>

<div class="row g-4">
    @forelse($reviews as $review)
    <div class="col-lg-6">
        <div class="bg-white rounded-4 p-4" style="border:1px solid #fce4ec;transition:all .3s;" 
             onmouseover="this.style.borderColor='#ffc107';this.style.boxShadow='0 6px 20px rgba(255,193,7,0.1)'" 
             onmouseout="this.style.borderColor='#fce4ec';this.style.boxShadow='none'">
            
            <div class="d-flex align-items-start gap-3 mb-3">
                <img src="{{ $review->salon->logo_url ?? '' }}" class="rounded-2 flex-shrink-0" 
                     width="48" height="48" style="object-fit:cover;" 
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($review->salon->name) }}&background=E91E8C&color=fff'">
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-0" style="color:#333;">{{ $review->salon->name }}</h6>
                    @if($review->appointment)
                    <div style="color:#888;font-size:0.78rem;">
                        <i class="fas fa-spa me-1" style="color:#E91E8C;font-size:0.7rem;"></i>
                        {{ $review->appointment->service->name ?? 'Service' }}
                    </div>
                    @endif
                </div>
                <div>
                    @if($review->is_approved)
                        <span style="background:rgba(34,197,94,0.1);color:#22c55e;padding:2px 8px;border-radius:10px;font-size:0.7rem;font-weight:600;">Published</span>
                    @else
                        <span style="background:rgba(255,193,7,0.1);color:#ffc107;padding:2px 8px;border-radius:10px;font-size:0.7rem;font-weight:600;">Pending</span>
                    @endif
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 mb-3">
                @for($i=1;$i<=5;$i++)
                <i class="fas fa-star" style="font-size:1rem;color:{{ $i<=$review->rating ? '#ffc107' : '#e5e7eb' }};"></i>
                @endfor
                <span class="fw-bold" style="color:#333;font-size:0.88rem;">{{ $review->rating }}/5</span>
                <span style="color:#aaa;font-size:0.78rem;margin-left:auto;">{{ $review->created_at->diffForHumans() }}</span>
            </div>

            <p style="color:#555;font-size:0.85rem;line-height:1.7;margin-bottom:1rem;">{{ $review->comment }}</p>

            <a href="{{ route('client.reviews.show', $review->id) }}" class="btn btn-sm" 
               style="background:#fff0f7;color:#E91E8C;border:1px solid #fce4ec;border-radius:8px;font-size:0.78rem;">
                <i class="fas fa-eye me-1"></i>View Details
            </a>

            @if($review->reply)
            <div class="mt-3 p-3 rounded-3" style="background:rgba(233,30,140,0.04);border-left:3px solid #E91E8C;">
                <div style="color:#E91E8C;font-size:0.72rem;font-weight:700;margin-bottom:4px;">
                    <i class="fas fa-reply me-1"></i>SALON REPLIED
                </div>
                <p style="color:#666;font-size:0.82rem;line-height:1.6;margin:0;">{{ $review->reply->reply }}</p>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5 bg-white rounded-4" style="border:2px dashed #fce4ec;">
            <i class="fas fa-star fa-4x mb-3" style="color:rgba(255,193,7,0.3);"></i>
            <h5 style="color:#333;">No reviews yet</h5>
            <p style="color:#aaa;">Complete an appointment and share your experience!</p>
            <a href="{{ route('client.appointments.index') }}" class="btn rounded-pill px-5 mt-2" 
               style="background:linear-gradient(135deg,#ffc107,#f59e0b);color:#fff;border:none;font-weight:600;">
                <i class="fas fa-calendar-check me-2"></i>My Appointments
            </a>
        </div>
    </div>
    @endforelse
</div>

@if($reviews->hasPages())
<div class="mt-4">{{ $reviews->links() }}</div>
@endif

@endsection