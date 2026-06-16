{{-- ============================================================ --}}
{{-- FILE: resources/views/client/favorites/index.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.client')
@section('title', 'Saved Salons — Glamora')
@section('content')
 
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#333;font-family:'Playfair Display',serif;"><i class="fas fa-heart me-2" style="color:#E91E8C;"></i>Saved Salons</h4>
        <p style="color:#aaa;font-size:0.85rem;margin:0;">{{ $favorites->total() }} salons saved</p>
    </div>
    <a href="{{ route('salons.index') }}" class="btn btn-sm rounded-pill px-4" style="background:linear-gradient(135deg,#E91E8C,#c2185b);color:#fff;border:none;font-weight:600;">
        <i class="fas fa-search me-1"></i>Discover More
    </a>
</div>
 
<div class="row g-4">
    @forelse($favorites as $salon)
    <div class="col-lg-4 col-md-6">
        <div class="bg-white rounded-4 overflow-hidden" style="border:1px solid #fce4ec;transition:all .3s;" onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 15px 40px rgba(233,30,140,0.15)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
            <div style="height:180px;overflow:hidden;position:relative;">
                <img src="{{ $salon->cover_url }}" style="width:100%;height:100%;object-fit:cover;" onerror="this.src='https://images.unsplash.com/photo-1560066984-138dadb4c035?w=400&q=60'">
                <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.5),transparent);"></div>
                <form action="{{ route('client.favorites.toggle',$salon->id) }}" method="POST" style="position:absolute;top:12px;right:12px;">
                    @csrf
                    <button style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,0.9);border:none;display:flex;align-items:center;justify-content:center;cursor:pointer;" title="Remove from favorites">
                        <i class="fas fa-heart" style="color:#E91E8C;font-size:0.9rem;"></i>
                    </button>
                </form>
                <div style="position:absolute;bottom:12px;left:12px;">
                    <div class="d-flex align-items-center gap-1">
                        <i class="fas fa-star text-warning" style="font-size:0.78rem;"></i>
                        <span style="color:#fff;font-size:0.82rem;font-weight:600;">{{ number_format($salon->rating,1) }}</span>
                        <span style="color:rgba(255,255,255,0.7);font-size:0.75rem;">({{ $salon->total_reviews }} reviews)</span>
                    </div>
                </div>
            </div>
            <div class="p-4">
                <h6 class="fw-bold mb-1" style="color:#333;">{{ $salon->name }}</h6>
                <div style="color:#888;font-size:0.82rem;margin-bottom:0.75rem;">
                    <i class="fas fa-map-marker-alt me-1" style="color:#E91E8C;"></i>{{ $salon->area ? $salon->area.', ' : '' }}{{ $salon->city }}
                </div>
                <div class="d-flex flex-wrap gap-1 mb-3">
                    @foreach($salon->services->take(3) as $service)
                    <span style="background:#fff0f7;color:#E91E8C;border:1px solid #fce4ec;padding:2px 8px;border-radius:10px;font-size:0.72rem;font-weight:500;">{{ $service->name }}</span>
                    @endforeach
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('salons.show',$salon->slug) }}" class="btn btn-sm flex-grow-1" style="background:#fff0f7;color:#E91E8C;border:1px solid #fce4ec;border-radius:10px;font-weight:600;">
                        <i class="fas fa-eye me-1"></i>View Salon
                    </a>
                    <a href="{{ route('client.booking.step1',$salon->id) }}" class="btn btn-sm flex-grow-1" style="background:linear-gradient(135deg,#E91E8C,#c2185b);color:#fff;border:none;border-radius:10px;font-weight:600;">
                        <i class="fas fa-calendar-plus me-1"></i>Book Now
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5 bg-white rounded-4" style="border:2px dashed #fce4ec;">
            <i class="fas fa-heart fa-4x mb-3" style="color:rgba(233,30,140,0.2);"></i>
            <h5 style="color:#333;">No saved salons yet</h5>
            <p style="color:#aaa;">Tap the heart icon on any salon to save it here</p>
            <a href="{{ route('salons.index') }}" class="btn rounded-pill px-5 mt-2" style="background:linear-gradient(135deg,#E91E8C,#c2185b);color:#fff;border:none;font-weight:600;">
                <i class="fas fa-search me-2"></i>Browse Salons
            </a>
        </div>
    </div>
    @endforelse
</div>
@if($favorites->hasPages())
<div class="mt-4">{{ $favorites->links() }}</div>
@endif
@endsection