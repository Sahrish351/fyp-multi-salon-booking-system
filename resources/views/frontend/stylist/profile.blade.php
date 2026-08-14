<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $stylist->name }} - Professional stylist at {{ $salon->name }}. Book appointments and view reviews.">
    <title>{{ $stylist->name }} — Stylist Profile | Beauty Blush Salons</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        /* ============================================================ */
        /* GLOBAL */
        /* ============================================================ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: 'Inter', sans-serif; 
            background: #f8f7fa; 
            min-height: 100vh; 
            -webkit-font-smoothing: antialiased; 
        }
        a { text-decoration: none; color: inherit; }

        /* ============================================================ */
        /* TOP NAV - PREMIUM */
        /* ============================================================ */
        .top-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            height: 68px;
            z-index: 200;
            background: #fff;
            border-bottom: 1px solid #f0e8ed;
            box-shadow: 0 2px 20px rgba(0,0,0,0.02);
        }
        .top-nav .brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 900;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #E91E8C, #C9A96E);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-style: italic;
        }
        .top-nav .nav-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .nav-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1.5px solid #e8e8e8;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.95rem;
            color: #1a1a1a;
            transition: all .3s ease;
            text-decoration: none;
        }
        .nav-btn:hover { border-color: #E91E8C; color: #E91E8C; transform: scale(1.05); }
        .nav-btn i { font-size: 0.9rem; }

        /* ============================================================ */
        /* HERO SECTION - STUNNING */
        /* ============================================================ */
        .profile-hero {
            padding-top: 68px;
            background: linear-gradient(180deg, #fff 0%, #f8f7fa 100%);
            position: relative;
            overflow: hidden;
        }
        .profile-hero::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(233,30,140,0.04), transparent 70%);
            border-radius: 50%;
        }
        .profile-hero .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 28px 30px;
            position: relative;
            z-index: 1;
        }
        .hero-content {
            display: flex;
            align-items: center;
            gap: 40px;
        }
        @media(max-width:768px) {
            .hero-content {
                flex-direction: column;
                text-align: center;
                gap: 24px;
            }
        }

        .hero-avatar {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: linear-gradient(135deg, #fce4ec, #f8f0f5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
            font-weight: 700;
            color: #E91E8C;
            overflow: hidden;
            border: 4px solid #fff;
            box-shadow: 0 8px 50px rgba(233,30,140,0.08);
            flex-shrink: 0;
        }
        .hero-avatar img { width: 100%; height: 100%; object-fit: cover; }

        .hero-info { flex: 1; }
        .hero-info .badge {
            display: inline-block;
            background: #fce4ec;
            color: #E91E8C;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 4px 16px;
            border-radius: 50px;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        .hero-info .badge i { margin-right: 4px; }
        .hero-info h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.6rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 2px;
            line-height: 1.1;
        }
        .hero-info .role {
            font-size: 1.05rem;
            color: #888;
            margin-bottom: 10px;
        }
        .hero-info .rating-row {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        @media(max-width:768px) {
            .hero-info .rating-row { justify-content: center; }
        }
        .hero-info .rating-row .stars {
            display: flex;
            align-items: center;
            gap: 3px;
            color: #ffc107;
            font-size: 0.95rem;
        }
        .hero-info .rating-row .rating-num {
            font-weight: 700;
            font-size: 1rem;
            color: #1a1a1a;
        }
        .hero-info .rating-row .review-count {
            color: #888;
            font-size: 0.85rem;
        }
        .hero-info .rating-row .divider { color: #ddd; }
        .hero-info .rating-row .appointments {
            color: #888;
            font-size: 0.85rem;
        }
        .hero-info .rating-row .appointments i {
            color: #E91E8C;
            margin-right: 4px;
        }

        .hero-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            flex-shrink: 0;
            min-width: 180px;
        }
        @media(max-width:768px) {
            .hero-actions { width: 100%; }
        }
        .btn-book {
            background: linear-gradient(135deg, #E91E8C, #c2185b);
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 14px 32px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 25px rgba(233,30,140,0.2);
            width: 100%;
        }
        .btn-book:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 40px rgba(233,30,140,0.3);
        }
        .btn-back {
            color: #E91E8C;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            transition: all .2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-back:hover { color: #c2185b; text-decoration: underline; }

        /* ============================================================ */
        /* STATS BAR */
        /* ============================================================ */
        .stats-bar {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px 28px;
            background: #fff;
            border-radius: 16px;
            border: 1px solid #f0e8ed;
            box-shadow: 0 4px 30px rgba(0,0,0,0.02);
            margin-bottom: 32px;
        }
        @media(max-width:768px) {
            .stats-bar {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
                padding: 16px 20px;
                margin: 0 16px 24px;
            }
        }
        .stat-item {
            text-align: center;
            padding: 0 12px;
            border-right: 1px solid #f0e8ed;
        }
        .stat-item:last-child { border-right: none; }
        @media(max-width:768px) {
            .stat-item { border-right: none; }
        }
        .stat-item .number {
            font-size: 1.6rem;
            font-weight: 800;
            color: #1a1a1a;
            font-family: 'Playfair Display', serif;
        }
        .stat-item .number .pink { color: #E91E8C; }
        .stat-item .label {
            font-size: 0.75rem;
            color: #888;
            font-weight: 500;
            margin-top: 2px;
        }

        /* ============================================================ */
        /* MAIN LAYOUT */
        /* ============================================================ */
        .main-content {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 28px 60px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
        }
        @media(max-width:768px) {
            .main-content {
                grid-template-columns: 1fr;
                gap: 24px;
                padding: 0 20px 40px;
            }
        }

        /* ============================================================ */
        /* SECTION TITLES */
        /* ============================================================ */
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-title .pink { color: #E91E8C; }
        .section-title i { color: #E91E8C; font-size: 1.1rem; }
        .section-title .count {
            font-family: 'Inter', sans-serif;
            font-size: 0.75rem;
            font-weight: 600;
            color: #aaa;
            background: #f5f5f5;
            padding: 2px 12px;
            border-radius: 50px;
            margin-left: auto;
        }

        .info-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #f0e8ed;
            padding: 24px 28px;
            box-shadow: 0 4px 30px rgba(0,0,0,0.02);
            margin-bottom: 24px;
        }
        @media(max-width:576px) {
            .info-card { padding: 18px 16px; }
        }

        /* ============================================================ */
        /* ABOUT / DETAILS */
        /* ============================================================ */
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px 24px;
        }
        @media(max-width:576px) {
            .detail-grid { grid-template-columns: 1fr; }
        }
        .detail-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .detail-item .icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #fce4ec;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #E91E8C;
            font-size: 0.8rem;
            flex-shrink: 0;
        }
        .detail-item .info label {
            font-size: 0.65rem;
            font-weight: 700;
            color: #aaa;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
        }
        .detail-item .info .value {
            font-size: 0.9rem;
            font-weight: 600;
            color: #1a1a1a;
        }

        .bio-text {
            font-size: 0.92rem;
            color: #555;
            line-height: 1.8;
            margin-top: 12px;
            padding-left: 16px;
            border-left: 3px solid #E91E8C;
        }

        /* ============================================================ */
        /* SERVICES - PREMIUM CARDS */
        /* ============================================================ */
        .services-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        @media(max-width:480px) {
            .services-grid { grid-template-columns: 1fr; }
        }
        .service-item {
            background: #fff;
            border: 1px solid #f0e8ed;
            border-radius: 12px;
            padding: 12px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all .3s ease;
        }
        .service-item:hover {
            border-color: #E91E8C;
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(233,30,140,0.05);
        }
        .service-item .name {
            font-size: 0.85rem;
            font-weight: 600;
            color: #1a1a1a;
        }
        .service-item .price {
            font-size: 0.85rem;
            font-weight: 700;
            color: #E91E8C;
        }

        /* ============================================================ */
        /* REVIEWS - PREMIUM */
        /* ============================================================ */
        .review-item {
            background: #fff;
            border: 1px solid #f0e8ed;
            border-radius: 14px;
            padding: 16px 20px;
            margin-bottom: 12px;
            transition: all .3s ease;
        }
        .review-item:hover {
            border-color: #E91E8C;
            box-shadow: 0 4px 20px rgba(233,30,140,0.04);
        }
        .review-item .review-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 6px;
        }
        .review-item .review-header .reviewer {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .review-item .review-header .reviewer .av {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #E91E8C;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 0.75rem;
        }
        .review-item .review-header .reviewer .name {
            font-weight: 700;
            font-size: 0.88rem;
            color: #1a1a1a;
        }
        .review-item .review-header .date {
            font-size: 0.72rem;
            color: #aaa;
        }
        .review-item .review-stars {
            color: #ffc107;
            font-size: 0.85rem;
            margin-bottom: 4px;
        }
        .review-item .review-text {
            font-size: 0.85rem;
            color: #555;
            line-height: 1.6;
        }

        .empty-state {
            text-align: center;
            padding: 30px 0;
            color: #888;
        }
        .empty-state i {
            font-size: 2.5rem;
            color: #f0e8ed;
            display: block;
            margin-bottom: 8px;
        }
        .empty-state p { font-size: 0.88rem; }

        /* ============================================================ */
        /* SIMILAR STYLISTS */
        /* ============================================================ */
        .similar-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 14px;
        }
        @media(max-width:480px) {
            .similar-grid { grid-template-columns: 1fr 1fr; }
        }
        .similar-card {
            background: #fff;
            border: 1px solid #f0e8ed;
            border-radius: 14px;
            padding: 18px 14px;
            text-align: center;
            transition: all .3s ease;
            cursor: pointer;
            text-decoration: none;
        }
        .similar-card:hover {
            border-color: #E91E8C;
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(233,30,140,0.06);
        }
        .similar-card .s-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: #fce4ec;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            font-weight: 700;
            color: #E91E8C;
            margin: 0 auto 8px;
        }
        .similar-card .s-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .similar-card .s-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: #1a1a1a;
        }
        .similar-card .s-role {
            font-size: 0.7rem;
            color: #888;
        }
        .similar-card .s-rating {
            font-size: 0.75rem;
            color: #ffc107;
            margin-top: 4px;
        }

        /* ============================================================ */
        /* RESPONSIVE */
        /* ============================================================ */
        @media (max-width: 768px) {
            .top-nav { padding: 0 16px; height: 60px; }
            .top-nav .brand { font-size: 1.1rem; }
            .nav-btn { width: 36px; height: 36px; font-size: 0.85rem; }
            .profile-hero .container { padding: 24px 16px 20px; }
            .hero-avatar { width: 100px; height: 100px; font-size: 2.5rem; }
            .hero-info h1 { font-size: 1.8rem; }
            .hero-info .role { font-size: 0.9rem; }
            .stats-bar { margin-bottom: 20px; }
            .stat-item .number { font-size: 1.3rem; }
            .main-content { padding: 0 16px 30px; }
            .section-title { font-size: 1rem; }
            .info-card { padding: 18px 16px; }
        }
        @media (max-width: 480px) {
            .hero-avatar { width: 80px; height: 80px; font-size: 2rem; }
            .hero-info h1 { font-size: 1.4rem; }
            .stats-bar { grid-template-columns: 1fr 1fr; gap: 12px; padding: 12px 16px; }
            .stat-item .number { font-size: 1.1rem; }
            .stat-item .label { font-size: 0.65rem; }
            .similar-grid { grid-template-columns: 1fr 1fr; }
            .services-grid { grid-template-columns: 1fr; }
            .btn-book { padding: 12px 20px; font-size: 0.9rem; }
        }
    </style>
</head>
<body>

{{-- ============================================================ --}}
{{-- TOP NAV --}}
{{-- ============================================================ --}}
<div class="top-nav">
    <a href="{{ route('home') }}" class="brand">Beauty Blush</a>
    <div class="nav-right">
        <a href="{{ route('salons.show', $salon->slug) }}" class="nav-btn" title="Back to Salon">
            <i class="fas fa-times"></i>
        </a>
    </div>
</div>

{{-- ============================================================ --}}
{{-- HERO SECTION --}}
{{-- ============================================================ --}}
<div class="profile-hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-avatar">
                @if($stylist->avatar)
                    <img src="{{ $stylist->avatar_url }}" alt="{{ $stylist->name }}">
                @else
                    {{ substr($stylist->name, 0, 1) }}
                @endif
            </div>

            <div class="hero-info">
                <div class="badge"><i class="fas fa-check-circle"></i> Verified Professional</div>
                <h1>{{ $stylist->name }}</h1>
                <div class="role">{{ $stylist->specializations ?? 'Professional Stylist' }}</div>
                <div class="rating-row">
                    <div class="stars">
                        @for($i=1; $i<=5; $i++)
                            <i class="fas fa-star" style="color:{{ $i <= round($avgRating) ? '#ffc107' : '#e5e7eb' }};"></i>
                        @endfor
                    </div>
                    <span class="rating-num">{{ number_format($avgRating, 1) }}</span>
                    <span class="review-count">({{ $reviewsCount }} reviews)</span>
                    <span class="divider">·</span>
                    <span class="appointments">
                        <i class="fas fa-calendar-check"></i> {{ $totalAppointments }} appointments
                    </span>
                </div>
            </div>

            <div class="hero-actions">
                <a href="{{ route('booking.step2', ['salon_id' => $salon->id]) }}?stylist_id={{ $stylist->id }}" class="btn-book">
                    <i class="fas fa-calendar-check"></i> Book Now
                </a>
                <a href="{{ route('salons.show', $salon->slug) }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Back to Salon
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- STATS BAR --}}
{{-- ============================================================ --}}
<div class="stats-bar">
    <div class="stat-item">
        <div class="number"><span class="pink">{{ $reviewsCount }}</span></div>
        <div class="label">Total Reviews</div>
    </div>
    <div class="stat-item">
        <div class="number"><span class="pink">{{ number_format($avgRating, 1) }}</span></div>
        <div class="label">Average Rating</div>
    </div>
    <div class="stat-item">
        <div class="number><span class="pink">{{ $totalAppointments }}</span></div>
        <div class="label">Appointments</div>
    </div>
    <div class="stat-item">
        <div class="number"><span class="pink">{{ $stylist->experience ?? '5+' }}</span></div>
        <div class="label">Years Experience</div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MAIN CONTENT --}}
{{-- ============================================================ --}}
<div class="main-content">

    {{-- LEFT COLUMN --}}
    <div>

        {{-- About --}}
        <div class="info-card">
            <div class="section-title">
                <i class="fas fa-user"></i> About <span class="pink">{{ $stylist->name }}</span>
            </div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="icon"><i class="fas fa-briefcase"></i></div>
                    <div class="info">
                        <label>Experience</label>
                        <div class="value">{{ $stylist->experience ?? '5+ years' }}</div>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="icon"><i class="fas fa-tag"></i></div>
                    <div class="info">
                        <label>Specialization</label>
                        <div class="value">{{ $stylist->specializations ?? 'Beauty Expert' }}</div>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="icon"><i class="fas fa-store"></i></div>
                    <div class="info">
                        <label>Salon</label>
                        <div class="value">{{ $salon->name }}</div>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="icon"><i class="fas fa-clock"></i></div>
                    <div class="info">
                        <label>Availability</label>
                        <div class="value">{{ $stylist->availability ?? 'Mon-Sat, 10AM-7PM' }}</div>
                    </div>
                </div>
            </div>
            @if($stylist->bio)
            <div class="bio-text">
                <i class="fas fa-quote-left" style="color:#E91E8C; opacity:0.3; margin-right:4px;"></i>
                {{ $stylist->bio }}
            </div>
            @endif
        </div>

        {{-- Services --}}
        @if($services->count() > 0)
        <div class="info-card">
            <div class="section-title">
                <i class="fas fa-scissors"></i> Services <span class="pink">Offered</span>
                <span class="count">{{ $services->count() }}</span>
            </div>
            <div class="services-grid">
                @foreach($services as $service)
                <div class="service-item">
                    <span class="name">{{ $service->name }}</span>
                    <span class="price">Rs. {{ number_format($service->price) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- RIGHT COLUMN --}}
    <div>

        {{-- Reviews --}}
        <div class="info-card">
            <div class="section-title">
                <i class="fas fa-star" style="color:#ffc107;"></i> Client <span class="pink">Reviews</span>
                <span class="count">{{ $reviewsCount }}</span>
            </div>

            @if($recentReviews->count() > 0)
                @foreach($recentReviews as $review)
                <div class="review-item">
                    <div class="review-header">
                        <div class="reviewer">
                            <div class="av">{{ substr($review->client->name ?? 'U', 0, 1) }}</div>
                            <div>
                                <div class="name">{{ $review->client->name ?? 'Anonymous' }}</div>
                            </div>
                        </div>
                        <div class="date">{{ $review->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="review-stars">
                        @for($i=1; $i<=5; $i++)
                            <i class="fas fa-star" style="color:{{ $i <= $review->rating ? '#ffc107' : '#e5e7eb' }};"></i>
                        @endfor
                    </div>
                    @if($review->comment)
                    <div class="review-text">{{ $review->comment }}</div>
                    @endif
                </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="fas fa-star"></i>
                    <p>No reviews yet. Be the first to review!</p>
                </div>
            @endif
        </div>

        {{-- Similar Stylists --}}
        @if($similarStylists->count() > 0)
        <div class="info-card">
            <div class="section-title">
                <i class="fas fa-users"></i> Similar <span class="pink">Stylists</span>
            </div>
            <div class="similar-grid">
                @foreach($similarStylists as $similar)
                <a href="{{ route('stylist.profile', ['salonSlug' => $salon->slug, 'stylistId' => $similar->id]) }}" class="similar-card">
                    <div class="s-avatar">
                        @if($similar->avatar)
                            <img src="{{ $similar->avatar_url }}" alt="{{ $similar->name }}">
                        @else
                            {{ substr($similar->name, 0, 1) }}
                        @endif
                    </div>
                    <div class="s-name">{{ $similar->name }}</div>
                    <div class="s-role">{{ $similar->specializations ? Str::limit($similar->specializations, 15) : 'Stylist' }}</div>
                    <div class="s-rating">
                        <i class="fas fa-star"></i>
                        {{ number_format($similar->rating ?: 5.0, 1) }}
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>

</div>

</body>
</html>