
@extends('layouts.guest')
@section('title', 'Find Best Salons - Glamora')
 
@push('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 3rem 0;
        position: relative;
        overflow: hidden;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }
    .hero-section::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .hero-section h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        font-family: 'Playfair Display', serif;
        position: relative;
        z-index: 1;
    }
    .hero-section p {
        color: rgba(255,255,255,0.9);
        font-size: 1rem;
        position: relative;
        z-index: 1;
    }
 
    .filter-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        margin-top: -2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        position: relative;
        z-index: 2;
    }
    .filter-card .form-control,
    .filter-card .form-select {
        border-radius: 12px;
        padding: 0.75rem 1rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    .filter-card .form-control:focus,
    .filter-card .form-select:focus {
        border-color: #E91E8C;
        box-shadow: 0 0 0 3px rgba(233,30,140,0.1);
    }
    .btn-filter {
        background: linear-gradient(135deg, #E91E8C, #c2185b);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        width: 100%;
        transition: all 0.3s ease;
    }
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(233,30,140,0.3);
    }
 
    .salons-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-top: 1rem;
    }
 
    .salon-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .salon-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(233,30,140,0.15);
    }
 
    .salon-image {
        height: 200px;
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
        background: #f0f0f0;
    }
    .salon-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .salon-card:hover .salon-image img { transform: scale(1.05); }
 
    .salon-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(5px);
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
    }
 
    .salon-content {
        padding: 1.2rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .salon-name { font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-bottom: 0.3rem; }
    .salon-location { font-size: 0.8rem; color: #E91E8C; margin-bottom: 0.8rem; }
    .salon-location i { margin-right: 0.3rem; }
    .salon-desc { font-size: 0.8rem; color: #64748b; line-height: 1.5; margin-bottom: 1rem; flex: 1; }
    .salon-rating { font-size: 0.8rem; color: #1a1a1a; font-weight: 700; margin-bottom: 0.6rem; }
    .salon-rating .star { color: #ffc107; }
 
    .btn-view {
        background: #fdf2f8;
        color: #E91E8C;
        border: none;
        border-radius: 50px;
        padding: 0.3rem 1.2rem;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-block;
        text-align: center;
        text-decoration: none;
        width: auto;
        margin-top: auto;
    }
    .btn-view:hover { background: #E91E8C; color: white; }
 
    .result-count { font-size: 0.9rem; color: #64748b; margin-bottom: 1rem; padding-left: 0.5rem; }
 
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        grid-column: 1 / -1;
    }
    .empty-state i { font-size: 3rem; color: #e8e8e8; margin-bottom: 16px; display: block; }
    .empty-state h5 { font-weight: 700; color: #1a1a1a; margin-bottom: 8px; }
    .empty-state p { color: #888; font-size: 0.88rem; }
 
    @media (max-width: 992px) {
        .salons-grid { grid-template-columns: repeat(2, 1fr); gap: 1.2rem; }
    }
    @media (max-width: 576px) {
        .salons-grid { grid-template-columns: 1fr; gap: 1rem; }
        .hero-section h1 { font-size: 1.8rem; }
    }
</style>
@endpush
 
@section('content')
 
<section class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1>Find Your Perfect Salon</h1>
                <p class="mt-3">Discover the best salons in your city. Book appointments with top-rated professionals.</p>
            </div>
        </div>
    </div>
</section>
 
<div class="container">
 
    {{-- ✅ Filter form — submits to the same route with GET params,
         which PublicSalonController@index already reads via request() --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('salons.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="🔍 Search by salon name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="city" class="form-select">
                    <option value="">📍 All Cities</option>
                    @foreach($cities as $cityOption)
                    <option value="{{ $cityOption }}" {{ request('city') === $cityOption ? 'selected' : '' }}>{{ $cityOption }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">📂 All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-search me-1"></i> Search
                </button>
            </div>
        </form>
    </div>
 
    <div class="result-count">
        <i class="fas fa-store me-1"></i> <strong>{{ $salons->total() }}</strong> premium salon{{ $salons->total() === 1 ? '' : 's' }} found
    </div>
 
    {{-- ✅ Real database-driven salon cards — replaces the old hardcoded list --}}
    <div class="salons-grid">
        @forelse($salons as $salon)
        <div class="salon-card">
            <div class="salon-image">
                <img src="{{ $salon->cover_url ?? 'https://images.unsplash.com/photo-1560066984-138dadb4c035?w=500' }}"
                     alt="{{ $salon->name }}"
                     onerror="this.src='https://images.unsplash.com/photo-1560066984-138dadb4c035?w=500'">
                <div class="salon-badge"><i class="fas fa-map-marker-alt me-1"></i>{{ $salon->city }}</div>
            </div>
            <div class="salon-content">
                <h3 class="salon-name">{{ $salon->name }}</h3>
                <div class="salon-location"><i class="fas fa-location-dot"></i> {{ $salon->address }}, {{ $salon->city }}</div>
                <div class="salon-rating">
                    <span class="star"><i class="fas fa-star"></i></span>
                    {{ number_format($salon->rating, 1) }}
                    <span style="color:#aaa;font-weight:400;">({{ $salon->reviews->count() }} reviews)</span>
                </div>
                <p class="salon-desc">{{ Str::limit($salon->description ?? 'Premium salon offering professional beauty services with experienced staff.', 110) }}</p>
                {{-- ✅ THIS is the link that opens the real detail page —
                     uses the salon's actual slug from the database --}}
                <a href="{{ route('salons.show', $salon->slug) }}" class="btn-view">
                    View Details →
                </a>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-store-slash"></i>
            <h5>No salons found</h5>
            <p>Try adjusting your search filters, or check back soon as more salons join Glamora.</p>
        </div>
        @endforelse
    </div>
 
    {{-- Pagination --}}
    @if($salons->hasPages())
    <div class="d-flex justify-content-center mt-5 mb-4">
        {{ $salons->links() }}
    </div>
    @endif
 
</div>
 
@endsection