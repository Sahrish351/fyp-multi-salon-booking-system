@extends('layouts.guest')
@section('title', $stylist->name . ' — Beauty Blush Salons')
 
@push('styles')
<style>
    /* Sab styles .stylist-page ke andar hain, taake website ke navbar/footer par asar na pade */
    .stylist-page { background: #f8f7fa; color: #1a1a1a; font-family: 'Poppins', sans-serif; }
    .stylist-page a { text-decoration: none; }
 
    .stylist-wrap { max-width: 1200px; margin: 0 auto; padding: 24px 20px 80px; }
 
    .advance-banner {
        background: linear-gradient(135deg, #fdf5fb, #fff5f7);
        border: 1px solid #f2d9e8; border-radius: 14px;
        padding: 12px 20px; margin-bottom: 14px;
        display: flex; align-items: center; justify-content: space-between;
        font-size: 0.82rem; color: #555; flex-wrap: wrap; gap: 10px;
    }
    .advance-banner strong { color: #E91E8C; }
    .advance-badge {
        background: #dcfce7; color: #166534; font-weight: 700;
        font-size: 0.7rem; padding: 3px 12px; border-radius: 50px; white-space: nowrap;
    }
 
    .stylist-card {
        background: #fff; border-radius: 20px; border: 1px solid #f0e8ed;
        padding: 28px 32px; margin-bottom: 16px;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 20px; box-shadow: 0 2px 20px rgba(0,0,0,0.03);
    }
    .stylist-left { display: flex; align-items: center; gap: 24px; flex-wrap: wrap; }
 
    /* Photo aur placeholder (dashboard jaisa pink circle + icon) */
    .stylist-avatar {
        width: 100px; height: 100px; border-radius: 50%; object-fit: cover;
        border: 3px solid #fce4ec; box-shadow: 0 4px 20px rgba(233,30,140,0.08);
    }
    .stylist-avatar-placeholder {
        background: linear-gradient(135deg, #FF6B9D, #E85588);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 40px;
    }
 
    .stylist-name { font-size: 1.6rem; font-weight: 800; margin: 0 0 2px; }
    .stylist-role { font-size: 0.95rem; color: #555; font-weight: 600; margin-bottom: 2px; }
    .stylist-spec { font-size: 0.82rem; color: #888; margin-bottom: 6px; }
    .stylist-meta { display: flex; align-items: center; gap: 8px; font-size: 0.82rem; color: #777; flex-wrap: wrap; }
    .stylist-meta .star { color: #ffc107; font-weight: 700; }
    .stylist-meta .rev-count { color: #E91E8C; font-weight: 600; }
    .stylist-meta .salon-link { color: #1a1a1a; font-weight: 600; }
 
    .btn-book-main {
        background: #1a1a1a; color: #fff; border-radius: 50px;
        padding: 12px 28px; font-weight: 700; font-size: 0.9rem;
        transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-book-main:hover { background: #E91E8C; color: #fff; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(233,30,140,0.25); }
 
    .profile-grid { display: grid; grid-template-columns: 1fr 340px; gap: 16px; align-items: start; }
    @media (max-width: 992px) { .profile-grid { grid-template-columns: 1fr; gap: 14px; } }
 
    .section-box {
        background: #fff; border: 1px solid #f0e8ed; border-radius: 18px;
        padding: 20px 24px; margin-bottom: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.02);
    }
    .section-title {
        font-size: 1.05rem; font-weight: 700; margin: 0 0 14px; padding-bottom: 8px;
        border-bottom: 2px solid #f0e8ed; display: flex; justify-content: space-between; align-items: center;
    }
    .section-title i { color: #E91E8C; margin-right: 6px; }
 
    .service-item {
        background: #faf8fb; border: 1px solid #e8e8e8; border-radius: 12px;
        padding: 14px 18px; margin-bottom: 8px;
        display: flex; align-items: center; justify-content: space-between; gap: 14px;
        transition: all 0.25s ease;
    }
    .service-item:hover { border-color: #E91E8C; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(233,30,140,0.08); background: #fff; }
    .svc-name { font-size: 0.92rem; font-weight: 600; margin-bottom: 2px; }
    .svc-desc { font-size: 0.75rem; color: #777; margin-bottom: 3px; }
    .svc-time { font-size: 0.72rem; color: #888; }
    .svc-time i { color: #E91E8C; }
    .svc-price { font-size: 0.95rem; font-weight: 700; color: #E91E8C; }
    .btn-svc-book {
        border: 2px solid #E91E8C; color: #E91E8C; background: #fff;
        padding: 6px 18px; border-radius: 50px; font-size: 0.78rem; font-weight: 700; transition: all 0.25s;
    }
    .btn-svc-book:hover { background: #E91E8C; color: #fff; }
    .view-all-services { display: inline-block; margin-top: 6px; color: #E91E8C; font-weight: 600; font-size: 0.82rem; }
 
    .review-card { padding: 12px 0; border-bottom: 1px solid #f5f5f5; }
    .review-card:last-child { border-bottom: none; padding-bottom: 0; }
    .rc-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px; }
    .rc-author { font-weight: 700; font-size: 0.88rem; }
    .rc-stars { color: #ffc107; font-size: 0.85rem; letter-spacing: 1px; }
    .rc-text { font-size: 0.84rem; color: #555; line-height: 1.6; margin: 0; }
    .rc-date { font-size: 0.72rem; color: #aaa; margin-top: 4px; }
 
    .salon-info-box {
        background: #fff; border: 1px solid #f0e8ed; border-radius: 18px;
        padding: 20px 22px; position: sticky; top: 100px; box-shadow: 0 2px 16px rgba(0,0,0,0.02);
    }
    .salon-info-box h4 { font-size: 0.95rem; font-weight: 700; margin-bottom: 14px; padding-bottom: 8px; border-bottom: 2px solid #f0e8ed; }
    .salon-info-box h4 i { color: #E91E8C; margin-right: 6px; }
    .si-row { display: flex; align-items: flex-start; gap: 12px; font-size: 0.82rem; color: #555; margin-bottom: 12px; }
    .si-row i { color: #E91E8C; margin-top: 2px; width: 18px; font-size: 0.85rem; }
    .si-row strong { color: #1a1a1a; font-size: 0.8rem; display: block; margin-bottom: 1px; }
 
    .about-text { font-size: 0.88rem; color: #555; line-height: 1.7; margin: 0; }
 
    @media (max-width: 768px) {
        .stylist-wrap { padding: 16px 12px 80px; }
        .stylist-card { padding: 18px 16px; }
        .stylist-avatar { width: 72px; height: 72px; }
        .stylist-avatar-placeholder { font-size: 30px; }
        .stylist-name { font-size: 1.2rem; }
        .stylist-left { gap: 14px; }
        .section-box { padding: 16px; }
        .service-item { flex-direction: column; align-items: stretch; text-align: center; padding: 12px; }
        .btn-svc-book { width: 100%; text-align: center; padding: 8px; }
        .salon-info-box { position: relative; top: 0; padding: 16px; }
        .advance-banner { flex-direction: column; text-align: center; padding: 10px 14px; }
    }
    @media (max-width: 480px) {
        .stylist-left { flex-direction: column; align-items: center; text-align: center; }
        .stylist-meta { justify-content: center; }
        .stylist-card { flex-direction: column; align-items: center; text-align: center; }
        .btn-book-main { width: 100%; justify-content: center; }
    }
</style>
@endpush
 
@section('content')
<div class="stylist-page">
 
    <div class="stylist-wrap">
 
        {{-- Advance policy --}}
        <div class="advance-banner">
            <div>
                <i class="fas fa-shield-alt me-2" style="color:#E91E8C;"></i>
                <strong>Advance Policy:</strong> Pay only <strong>Rs. 100 advance</strong> online (EasyPaisa/JazzCash) to secure your slot. The remaining amount will be paid directly at the salon.
            </div>
            <span class="advance-badge">✓ Confirmed Booking</span>
        </div>
 
        {{-- Stylist header --}}
        <div class="stylist-card">
            <div class="stylist-left">
                @if ($stylist->avatar)
                    <img src="{{ asset('storage/' . $stylist->avatar) }}" alt="{{ $stylist->name }}" class="stylist-avatar">
                @else
                    <div class="stylist-avatar stylist-avatar-placeholder"><i class="fas fa-user"></i></div>
                @endif
 
                <div>
                    <h1 class="stylist-name">{{ $stylist->name }}</h1>
                    @if ($stylist->role)
                        <div class="stylist-role">{{ $stylist->role }}</div>
                    @endif
                    @if ($stylist->specializations)
                        <div class="stylist-spec">{{ $stylist->specializations }}</div>
                    @endif
                    <div class="stylist-meta">
                        <span class="star">★ {{ number_format((float) $avgRating, 1) }}</span>
                        <span class="rev-count">({{ $reviewsCount }} {{ \Illuminate\Support\Str::plural('review', $reviewsCount) }})</span>
                        <span>·</span>
                        <a href="{{ route('salons.show', $salon->slug) }}" class="salon-link">
                            <i class="fas fa-store" style="color:#E91E8C;"></i> {{ $salon->name }} ({{ $salon->city }})
                        </a>
                    </div>
                </div>
            </div>
 
            <div>
                <a href="{{ route('booking.step1', $salon->id) }}?stylist_id={{ $stylist->id }}" class="btn-book-main">
                    <i class="fas fa-calendar-check"></i> Book with {{ \Illuminate\Support\Str::words($stylist->name, 1, '') }}
                </a>
            </div>
        </div>
 
        <div class="profile-grid">
 
            {{-- Left column --}}
            <div>
 
                {{-- About --}}
                <div class="section-box">
                    <h3 class="section-title"><span><i class="fas fa-user-circle"></i> About Stylist</span></h3>
                    <p class="about-text">
                        {{ $stylist->bio ?: ($stylist->name . ' is a professional beauty artist at ' . $salon->name . ' specializing in ' . ($stylist->specializations ?: 'hair styling, cuts and treatments') . '.') }}
                    </p>
                </div>
 
                {{-- Services --}}
                <div class="section-box">
                    <h3 class="section-title">
                        <span><i class="fas fa-scissors"></i> Services & Pricing</span>
                        <span style="font-size:0.7rem; color:#888; font-weight:normal;">Rs. 100 advance deposit</span>
                    </h3>
 
                    @forelse ($services as $service)
                        <div class="service-item">
                            <div>
                                <div class="svc-name">{{ $service->name }}</div>
                                @if ($service->description)
                                    <div class="svc-desc">{{ \Illuminate\Support\Str::limit($service->description, 60) }}</div>
                                @endif
                                <div class="svc-time"><i class="far fa-clock"></i> {{ $service->duration ?? 45 }} mins</div>
                            </div>
                            <div class="text-end">
                                <div class="svc-price">Rs. {{ number_format($service->price) }}</div>
                                <a href="{{ route('booking.step1', $salon->id) }}?service_id={{ $service->id }}&stylist_id={{ $stylist->id }}" class="btn-svc-book d-inline-block mt-1">Book</a>
                            </div>
                        </div>
                    @empty
                        <p style="font-size:0.85rem; color:#888; margin:0;">No services available right now.</p>
                    @endforelse
 
                </div>
 
                {{-- Reviews (asli, database se) --}}
                <div class="section-box">
                    <h3 class="section-title">
                        <span><i class="fas fa-star" style="color:#f59e0b;"></i> Client Reviews</span>
                        <span style="font-size:0.75rem; color:#888; font-weight:normal;">{{ $reviewsCount }} total</span>
                    </h3>
 
                    @forelse ($recentReviews as $review)
                        @php
                            $parts = explode(' ', trim($review->reviewer_name));
                            $shortName = $parts[0] . (isset($parts[1]) && $parts[1] !== '' ? ' ' . strtoupper(substr($parts[1], 0, 1)) . '.' : '');
                            $stars = max(0, min(5, (int) $review->rating));
                        @endphp
                        <div class="review-card">
                            <div class="rc-top">
                                <div class="rc-author">
                                    {{ $shortName }}
                                    @if ($review->appointment_id)
                                        <span style="color:#16a34a; font-size:0.7rem;">✓ Verified</span>
                                    @endif
                                </div>
                                <div class="rc-stars">{{ str_repeat('★', $stars) }}{{ str_repeat('☆', 5 - $stars) }}</div>
                            </div>
                            @if ($review->comment)
                                <p class="rc-text">{{ $review->comment }}</p>
                            @endif
                            <div class="rc-date">{{ $review->created_at->diffForHumans() }}</div>
                        </div>
                    @empty
                        <p style="font-size:0.85rem; color:#888; margin:0;">No reviews yet. Book an appointment and be the first to review!</p>
                    @endforelse
                </div>
 
            </div>
 
            {{-- Right sidebar --}}
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
                        <div style="font-size:0.78rem; color:#777;">
                            {{ $salon->open_time ? \Carbon\Carbon::parse($salon->open_time)->format('g:i A') : '10:00 AM' }}
                            - {{ $salon->close_time ? \Carbon\Carbon::parse($salon->close_time)->format('g:i A') : '08:00 PM' }}
                        </div>
                    </div>
                </div>
 
                <div class="si-row">
                    <i class="fas fa-money-bill-wave"></i>
                    <div>
                        <strong>Payment Options</strong>
                        <div style="font-size:0.78rem; color:#777;">Rs. 100 Advance via EasyPaisa / JazzCash. Remaining at salon via Cash/Card.</div>
                    </div>
                </div>
 
                @if ($salon->phone)
                    <div class="si-row">
                        <i class="fas fa-phone"></i>
                        <div>
                            <strong>Contact</strong>
                            <div style="font-size:0.78rem; color:#777;">{{ $salon->phone }}</div>
                        </div>
                    </div>
                @endif
 
                <div style="margin-top:18px;">
                    <a href="{{ route('booking.step1', $salon->id) }}?stylist_id={{ $stylist->id }}" class="btn-book-main" style="width:100%; justify-content:center;">
                        <i class="fas fa-calendar-check"></i> Book Appointment Now
                    </a>
                </div>
            </div>
 
        </div>
    </div>
</div>
@endsection
 