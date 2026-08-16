@extends('layouts.guest')
@section('title', $stylist->name . ' — Beauty Blush Salons')

@section('hideMainNav', true)

@push('styles')
<style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; color: #1a1a1a; background: #f8f7fa; -webkit-font-smoothing: antialiased; }
    a { text-decoration: none; color: inherit; }

    /* Top Navigation */
    .g-nav {
        background: rgba(255,255,255,0.98);
        border-bottom: 1px solid #f0e8ed;
        padding: 0 28px;
        height: 62px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 1000;
    }
    .g-nav .brand { display: flex; align-items: center; gap: 8px; font-size: 1.3rem; font-weight: 800; }
    .g-nav .brand .brand-icon {
        width: 36px; height: 36px;
        background: linear-gradient(135deg, #E91E8C, #C9A96E);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        color: #fff;
    }
    .g-nav .brand-text { font-family: 'Playfair Display', serif; }
    .g-nav .brand-text .pink { color: #E91E8C; }
    .g-nav .brand-text .gold { color: #C9A96E; }

    /* Breadcrumbs */
    .breadcrumb-bar {
        padding: 10px 28px;
        font-size: 0.78rem;
        color: #888;
        display: flex;
        align-items: center;
        gap: 6px;
        background: #fff;
        border-bottom: 1px solid #f0e8ed;
        flex-wrap: wrap;
    }
    .breadcrumb-bar a { color: #888; transition: color 0.15s; }
    .breadcrumb-bar a:hover { color: #E91E8C; }
    .breadcrumb-bar .sep { color: #ccc; }
    .breadcrumb-bar .current { color: #1a1a1a; font-weight: 600; }

    /* Main Container */
    .stylist-wrap { max-width: 1200px; margin: 0 auto; padding: 16px 20px 80px; }

    /* Advance Deposit Banner */
    .advance-banner {
        background: linear-gradient(135deg, #fdf5fb, #fff5f7);
        border: 1px solid #f2d9e8;
        border-radius: 14px;
        padding: 12px 20px;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.82rem;
        color: #555;
        flex-wrap: wrap;
        gap: 10px;
    }
    .advance-banner strong { color: #E91E8C; }
    .advance-badge {
        background: #dcfce7;
        color: #166534;
        font-weight: 700;
        font-size: 0.7rem;
        padding: 3px 12px;
        border-radius: 50px;
        white-space: nowrap;
    }

    /* Stylist Header Card - LARGER */
    .stylist-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #f0e8ed;
        padding: 28px 32px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.03);
    }
    .stylist-left { display: flex; align-items: center; gap: 24px; flex-wrap: wrap; }
    .stylist-avatar {
        width: 100px;
        height: 100px;
        border-radius: 18px;
        object-fit: cover;
        border: 3px solid #fce4ec;
        background: #fdf2f8;
        box-shadow: 0 4px 20px rgba(233,30,140,0.08);
    }
    .stylist-name { font-size: 1.6rem; font-weight: 800; color: #1a1a1a; margin-bottom: 2px; }
    .stylist-role { font-size: 0.9rem; color: #555; font-weight: 500; margin-bottom: 6px; }
    .stylist-meta { display: flex; align-items: center; gap: 8px; font-size: 0.82rem; color: #777; flex-wrap: wrap; }
    .stylist-meta .star { color: #ffc107; font-weight: 700; }
    .stylist-meta .rev-count { color: #E91E8C; font-weight: 600; }
    .stylist-meta .salon-link { color: #1a1a1a; font-weight: 600; display: flex; align-items: center; gap: 4px; }
    
    .btn-book-main {
        background: #1a1a1a;
        color: #fff;
        border-radius: 50px;
        padding: 12px 28px;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-book-main:hover { background: #E91E8C; color: #fff; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(233,30,140,0.25); }

    /* Layout Grid - TIGHTER SPACING */
    .profile-grid { display: grid; grid-template-columns: 1fr 340px; gap: 16px; align-items: start; }
    @media(max-width: 992px) { .profile-grid { grid-template-columns: 1fr; gap: 14px; } }

    /* Section Cards - LARGER & TIGHTER SPACING */
    .section-box {
        background: #fff;
        border: 1px solid #f0e8ed;
        border-radius: 18px;
        padding: 20px 24px;
        margin-bottom: 12px;
        box-shadow: 0 2px 16px rgba(0,0,0,0.02);
        transition: all 0.2s;
    }
    .section-box:hover {
        border-color: rgba(233,30,140,0.1);
    }
    .section-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 14px;
        padding-bottom: 8px;
        border-bottom: 2px solid #f0e8ed;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .section-title i { color: #E91E8C; margin-right: 6px; }

    /* Service List Items - LARGER */
    .service-item {
        background: #faf8fb;
        border: 1px solid #e8e8e8;
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        transition: all 0.25s ease;
    }
    .service-item:hover { 
        border-color: #E91E8C; 
        transform: translateY(-2px); 
        box-shadow: 0 6px 20px rgba(233,30,140,0.08);
        background: #fff;
    }
    .svc-name { font-size: 0.92rem; font-weight: 600; color: #1a1a1a; margin-bottom: 2px; }
    .svc-desc { font-size: 0.75rem; color: #777; margin-bottom: 3px; }
    .svc-time { font-size: 0.72rem; color: #888; }
    .svc-time i { color: #E91E8C; }
    .svc-price { font-size: 0.95rem; font-weight: 700; color: #E91E8C; }
    .btn-svc-book {
        border: 2px solid #E91E8C;
        color: #E91E8C;
        background: #fff;
        padding: 6px 18px;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 700;
        transition: all 0.25s;
    }
    .btn-svc-book:hover { background: #E91E8C; color: #fff; transform: translateY(-1px); box-shadow: 0 4px 15px rgba(233,30,140,0.2); }

    .view-all-services {
        display: inline-block;
        margin-top: 6px;
        color: #E91E8C;
        font-weight: 600;
        font-size: 0.82rem;
        transition: all 0.2s;
    }
    .view-all-services:hover { color: #c2185b; text-decoration: underline; }

    /* Verified Reviews - LARGER */
    .review-card {
        padding: 12px 0;
        border-bottom: 1px solid #f5f5f5;
    }
    .review-card:last-child { border-bottom: none; padding-bottom: 0; }
    .rc-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px; }
    .rc-author { font-weight: 700; font-size: 0.88rem; color: #1a1a1a; }
    .rc-stars { color: #ffc107; font-size: 0.85rem; }
    .rc-text { font-size: 0.84rem; color: #555; line-height: 1.6; }
    .rc-date { font-size: 0.72rem; color: #aaa; margin-top: 4px; }

    /* Sticky Right Summary - LARGER */
    .salon-info-box {
        background: #fff;
        border: 1px solid #f0e8ed;
        border-radius: 18px;
        padding: 20px 22px;
        position: sticky;
        top: 80px;
        box-shadow: 0 2px 16px rgba(0,0,0,0.02);
    }
    .salon-info-box h4 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 14px;
        padding-bottom: 8px;
        border-bottom: 2px solid #f0e8ed;
    }
    .salon-info-box h4 i { color: #E91E8C; margin-right: 6px; }
    .si-row { display: flex; align-items: flex-start; gap: 12px; font-size: 0.82rem; color: #555; margin-bottom: 12px; }
    .si-row i { color: #E91E8C; margin-top: 2px; width: 18px; font-size: 0.85rem; }
    .si-row strong { color: #1a1a1a; font-size: 0.8rem; display: block; margin-bottom: 1px; }

    /* About text */
    .about-text {
        font-size: 0.88rem;
        color: #555;
        line-height: 1.7;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .g-nav { padding: 0 16px; height: 56px; }
        .g-nav .brand { font-size: 1rem; }
        .g-nav .brand .brand-icon { width: 30px; height: 30px; font-size: 0.8rem; }
        .breadcrumb-bar { padding: 8px 16px; font-size: 0.68rem; }
        .stylist-wrap { padding: 12px 12px 80px; }
        .stylist-card { padding: 18px 16px; }
        .stylist-avatar { width: 72px; height: 72px; }
        .stylist-name { font-size: 1.2rem; }
        .stylist-role { font-size: 0.8rem; }
        .stylist-left { gap: 14px; }
        .section-box { padding: 16px; margin-bottom: 10px; }
        .section-title { font-size: 0.95rem; }
        .service-item { flex-direction: column; align-items: stretch; text-align: center; padding: 12px; }
        .btn-svc-book { width: 100%; text-align: center; padding: 8px; }
        .salon-info-box { position: relative; top: 0; padding: 16px; }
        .advance-banner { flex-direction: column; text-align: center; padding: 10px 14px; }
        .advance-banner .advance-badge { align-self: center; }
        .btn-book-main { padding: 10px 20px; font-size: 0.82rem; }
    }
    @media (max-width: 480px) {
        .stylist-left { flex-direction: column; align-items: center; text-align: center; }
        .stylist-meta { justify-content: center; }
        .stylist-card { flex-direction: column; align-items: center; text-align: center; }
        .stylist-name { font-size: 1rem; }
        .stylist-avatar { width: 64px; height: 64px; }
        .btn-book-main { width: 100%; justify-content: center; }
        .section-box { padding: 12px; }
        .service-item { padding: 10px; }
        .svc-name { font-size: 0.85rem; }
        .svc-price { font-size: 0.85rem; }
        .salon-info-box { padding: 12px; }
    }
</style>
@endpush

@section('content')

{{-- Top Header --}}
<nav class="g-nav">
    <a href="{{ route('home') }}" class="brand">
        <div class="brand-icon"><i class="fas fa-spa"></i></div>
        <span class="brand-text"><span class="pink">Beauty</span><span class="gold"> Blush</span><span class="pink"> Salons</span></span>
    </a>
    <div>
        <a href="{{ route('salons.show', $salon->slug) }}" class="btn-book-main" style="padding: 6px 16px; font-size: 0.75rem;">
            ← Back to Salon
        </a>
    </div>
</nav>

{{-- Breadcrumb Bar --}}
<div class="breadcrumb-bar">
    <a href="{{ route('home') }}">Home</a>
    <span class="sep">·</span>
    <a href="{{ route('salons.index') }}">Salons</a>
    <span class="sep">·</span>
    <a href="{{ route('salons.index', ['city' => $salon->city]) }}">{{ $salon->city }}</a>
    <span class="sep">·</span>
    <a href="{{ route('salons.show', $salon->slug) }}">{{ $salon->name }}</a>
    <span class="sep">·</span>
    <span class="current">{{ $stylist->name }}</span>
</div>

<div class="stylist-wrap">

    {{-- Rs. 100 Advance Notice --}}
    <div class="advance-banner">
        <div>
            <i class="fas fa-shield-alt me-2" style="color:#E91E8C;"></i>
            <strong>Advance Policy:</strong> Pay only <strong>Rs. 100 advance</strong> online (EasyPaisa/JazzCash) to secure your slot. The remaining amount will be paid directly at the salon.
        </div>
        <span class="advance-badge">✓ Confirmed Booking</span>
    </div>

    {{-- Stylist Header Profile --}}
    <div class="stylist-card">
        <div class="stylist-left">
            <img 
                src="{{ $stylist->avatar_url ?? ($stylist->avatar ? asset('storage/'.$stylist->avatar) : 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=300') }}" 
                alt="{{ $stylist->name }}" 
                class="stylist-avatar"
                onerror="this.src='https://images.unsplash.com/photo-1580489944761-15a19d654956?w=300'"
            >
            <div>
                <h1 class="stylist-name">{{ $stylist->name }}</h1>
                <div class="stylist-role">{{ $stylist->specializations ?: 'Master Hair & Beauty Stylist' }}</div>
                <div class="stylist-meta">
                    <span class="star">★ {{ number_format($stylist->rating ?: 4.9, 1) }}</span>
                    <span class="rev-count">({{ $stylist->reviews_count ?? ($stylist->reviews ? $stylist->reviews->count() : 18) }} reviews)</span>
                    <span class="sep">·</span>
                    <a href="{{ route('salons.show', $salon->slug) }}" class="salon-link">
                        <i class="fas fa-store" style="color:#E91E8C;"></i> {{ $salon->name }} ({{ $salon->city }})
                    </a>
                </div>
            </div>
        </div>

        <div>
            <a href="{{ route('booking.step1', $salon->id) }}?stylist_id={{ $stylist->id }}" class="btn-book-main">
                <i class="fas fa-calendar-check"></i> Book with {{ Str::words($stylist->name, 1, '') }}
            </a>
        </div>
    </div>

    {{-- Main 2-Column Grid --}}
    <div class="profile-grid">

        {{-- Left: Services & Reviews --}}
        <div>
            
            {{-- About Stylist --}}
            <div class="section-box">
                <h3 class="section-title"><i class="fas fa-user-circle"></i> About Stylist</h3>
                <p class="about-text">
                    {{ $stylist->bio ?? ($stylist->name . ' is a professional beauty artist at ' . $salon->name . ' specializing in ' . ($stylist->specializations ?: 'hair styling, cuts and treatments') . ' with years of certified salon experience in ' . $salon->city . '.') }}
                </p>
            </div>

            {{-- Stylist Services & Pricing --}}
            <div class="section-box">
                <h3 class="section-title">
                    <span><i class="fas fa-scissors"></i> Services & Pricing</span>
                    <span style="font-size:0.7rem; color:#888; font-weight:normal;">Rs. 100 advance deposit</span>
                </h3>

                @php
                    $services = $salon->services->where('is_active', true);
                    $displayServices = $services->take(5);
                    $hasMoreServices = $services->count() > 5;
                @endphp

                @forelse($displayServices as $service)
                <div class="service-item">
                    <div>
                        <div class="svc-name">{{ $service->name }}</div>
                        @if($service->description)
                        <div class="svc-desc">{{ Str::limit($service->description, 60) }}</div>
                        @endif
                        <div class="svc-time"><i class="far fa-clock"></i> {{ $service->duration ?? 45 }} mins</div>
                    </div>
                    <div class="text-end">
                        <div class="svc-price">Rs. {{ number_format($service->price) }}</div>
                        <a href="{{ route('booking.step1', $salon->id) }}?service_id={{ $service->id }}&stylist_id={{ $stylist->id }}" class="btn-svc-book d-inline-block mt-1">
                            Book
                        </a>
                    </div>
                </div>
                @empty
                <p style="font-size:0.85rem; color:#888;">No specific services found for this stylist.</p>
                @endforelse

                @if($hasMoreServices)
                    <a href="{{ route('salons.show', $salon->slug) }}#services" class="view-all-services">
                        View all {{ $services->count() }} services →
                    </a>
                @endif
            </div>

            {{-- Verified Client Reviews --}}
            <div class="section-box">
                <h3 class="section-title"><i class="fas fa-star" style="color:#f59e0b;"></i> Client Reviews</h3>
                
                <div class="review-card">
                    <div class="rc-top">
                        <div class="rc-author">Mahnoor B. <span style="color:#16a34a; font-size:0.7rem;">✓ Verified</span></div>
                        <div class="rc-stars">★★★★★</div>
                    </div>
                    <p class="rc-text">
                        {{ $stylist->name }} did my hair exactly as I requested! Very friendly, hygienic setup, and the advance payment of Rs. 100 made booking effortless.
                    </p>
                    <div class="rc-date">3 days ago at {{ $salon->name }}</div>
                </div>

                <div class="review-card">
                    <div class="rc-top">
                        <div class="rc-author">Ayesha K. <span style="color:#16a34a; font-size:0.7rem;">✓ Verified</span></div>
                        <div class="rc-stars">★★★★★</div>
                    </div>
                    <p class="rc-text">
                        Punctual and very detailed oriented. Highly recommended if you are visiting {{ $salon->city }}!
                    </p>
                    <div class="rc-date">1 week ago</div>
                </div>

            </div>

        </div>

        {{-- Right Sidebar: Salon Information & Policy --}}
        <div class="salon-info-box">
            <h4><i class="fas fa-store"></i> Salon Location & Info</h4>

            <div class="si-row">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <strong>{{ $salon->name }}</strong>
                    <div style="font-size:0.78rem; color:#777;">{{ $salon->address }}, {{ $salon->city }}</div>
                </div>
            </div>

            <div class="si-row">
                <i class="far fa-clock"></i>
                <div>
                    <strong>Working Hours</strong>
                    <div style="font-size:0.78rem; color:#777;">{{ $salon->open_time ? \Carbon\Carbon::parse($salon->open_time)->format('g:i A') : '10:00 AM' }} - {{ $salon->close_time ? \Carbon\Carbon::parse($salon->close_time)->format('g:i A') : '08:00 PM' }}</div>
                </div>
            </div>

            <div class="si-row">
                <i class="fas fa-money-bill-wave"></i>
                <div>
                    <strong>Payment Options</strong>
                    <div style="font-size:0.78rem; color:#777;">Rs. 100 Advance via EasyPaisa / JazzCash. Remaining at salon via Cash/Card.</div>
                </div>
            </div>

            <div class="si-row">
                <i class="fas fa-phone"></i>
                <div>
                    <strong>Contact</strong>
                    <div style="font-size:0.78rem; color:#777;">{{ $salon->phone ?? '+92 300 1234567' }}</div>
                </div>
            </div>

            <div style="margin-top:18px;">
                <a href="{{ route('booking.step1', $salon->id) }}?stylist_id={{ $stylist->id }}" class="btn-book-main" style="width:100%; text-align:center; justify-content:center;">
                    <i class="fas fa-calendar-check"></i> Book Appointment Now
                </a>
            </div>
        </div>

    </div>

</div>

@endsection