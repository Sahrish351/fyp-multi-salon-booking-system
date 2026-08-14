@extends('layouts.app')
@section('title', 'About Us - Beauty Blush Salons')

@section('content')

<style>
    /* ============================================================ */
    /* PREMIUM ANIMATIONS */
    /* ============================================================ */
    @keyframes float {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(30px, -30px) scale(1.05); }
    }
    @keyframes floatReverse {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(-20px, 20px) scale(1.08); }
    }
    @keyframes gradientMove {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    @keyframes slideUp {
        0% { opacity: 0; transform: translateY(50px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes scaleIn {
        0% { opacity: 0; transform: scale(0.85); }
        100% { opacity: 1; transform: scale(1); }
    }
    @keyframes rotateSlow {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    @keyframes shimmer {
        0% { background-position: -200% center; }
        100% { background-position: 200% center; }
    }
    @keyframes glowPulse {
        0%, 100% { box-shadow: 0 0 20px rgba(236,72,153,0.15); }
        50% { box-shadow: 0 0 40px rgba(236,72,153,0.25); }
    }
    @keyframes borderGlow {
        0%, 100% { border-color: rgba(236,72,153,0.1); }
        50% { border-color: rgba(236,72,153,0.25); }
    }

    .animate-float { animation: float 8s ease-in-out infinite; }
    .animate-float-reverse { animation: floatReverse 10s ease-in-out infinite; }
    .animate-slide-up { animation: slideUp 0.8s ease forwards; }
    .animate-scale { animation: scaleIn 0.8s ease forwards; }
    .animate-rotate { animation: rotateSlow 20s linear infinite; }
    .animate-glow { animation: glowPulse 3s ease-in-out infinite; }

    .delay-1 { animation-delay: 0.05s; }
    .delay-2 { animation-delay: 0.1s; }
    .delay-3 { animation-delay: 0.15s; }
    .delay-4 { animation-delay: 0.2s; }
    .delay-5 { animation-delay: 0.25s; }
    .delay-6 { animation-delay: 0.3s; }
    .delay-7 { animation-delay: 0.35s; }

    .gradient-text {
        background: linear-gradient(135deg, #F472B6, #EC4899, #DB2777);
        background-size: 200% 200%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: gradientMove 4s ease infinite;
    }

    .page-header {
        padding: 50px 0 20px;
        background: #fff;
        text-align: center;
        border-bottom: 1px solid rgba(236,72,153,0.06);
    }

    /* ============================================================ */
    /* VALUE CARDS - PREMIUM */
    /* ============================================================ */
    .value-card {
        background: #fff;
        border-radius: 20px;
        padding: 32px 24px;
        border: 1px solid rgba(236,72,153,0.06);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-align: center;
        height: 100%;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }
    .value-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #F472B6, #EC4899, #DB2777);
        background-size: 200% 100%;
        transform: scaleX(0);
        transition: transform 0.6s ease;
        transform-origin: left;
    }
    .value-card::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(236,72,153,0.03) 0%, transparent 70%);
        opacity: 0;
        transition: all 0.6s ease;
    }
    .value-card:hover::before {
        transform: scaleX(1);
    }
    .value-card:hover::after {
        opacity: 1;
    }
    .value-card:hover {
        border-color: rgba(236,72,153,0.15);
        box-shadow: 0 20px 60px rgba(236,72,153,0.08);
        transform: translateY(-8px) scale(1.01);
    }
    .value-card .icon {
        font-size: 38px;
        margin-bottom: 12px;
        display: block;
        transition: all 0.5s ease;
    }
    .value-card:hover .icon {
        transform: scale(1.1) rotate(-5deg);
    }
    .value-card h6 {
        font-size: 16px;
        font-weight: 700;
        color: #1a0a14;
        margin-bottom: 6px;
    }
    .value-card p {
        font-size: 13px;
        color: #888;
        margin: 0;
        line-height: 1.8;
    }

    /* ============================================================ */
    /* STORY IMAGE */
    /* ============================================================ */
    .story-image {
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid rgba(236,72,153,0.06);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .story-image:hover {
        border-color: rgba(236,72,153,0.12);
        box-shadow: 0 20px 60px rgba(236,72,153,0.06);
        transform: scale(1.005);
    }
    .story-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: all 0.8s ease;
    }
    .story-image:hover img {
        transform: scale(1.02);
    }

    /* ============================================================ */
    /* MISSION/VISION/VALUES CARDS */
    /* ============================================================ */
    .mv-card {
        background: #fff;
        border-radius: 20px;
        padding: 36px 28px;
        border: 1px solid rgba(236,72,153,0.06);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        height: 100%;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }
    .mv-card::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #F472B6, #EC4899, #DB2777);
        background-size: 200% 100%;
        transform: scaleX(0);
        transition: transform 0.6s ease;
        transform-origin: left;
    }
    .mv-card:hover::before {
        transform: scaleX(1);
    }
    .mv-card:hover {
        border-color: rgba(236,72,153,0.12);
        box-shadow: 0 20px 60px rgba(236,72,153,0.08);
        transform: translateY(-8px);
    }
    .mv-card .icon-wrap {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        background: rgba(236,72,153,0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        color: #EC4899;
        font-size: 26px;
        transition: all 0.5s ease;
    }
    .mv-card:hover .icon-wrap {
        background: linear-gradient(135deg, #F472B6, #EC4899);
        color: #fff;
        transform: rotate(10deg) scale(1.05);
        box-shadow: 0 8px 30px rgba(236,72,153,0.2);
    }
    .mv-card h4 {
        font-size: 20px;
        font-weight: 800;
        color: #1a0a14;
        margin-bottom: 8px;
    }
    .mv-card p {
        font-size: 15px;
        color: #666;
        line-height: 1.9;
        margin: 0;
    }

    /* ============================================================ */
    /* TEAM CARDS */
    /* ============================================================ */
    .team-card {
        background: #fff;
        border-radius: 20px;
        padding: 32px 20px;
        text-align: center;
        border: 1px solid rgba(236,72,153,0.06);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }
    .team-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #F472B6, #EC4899, #DB2777);
        background-size: 200% 100%;
        transform: scaleX(0);
        transition: transform 0.6s ease;
        transform-origin: left;
    }
    .team-card:hover::before {
        transform: scaleX(1);
    }
    .team-card:hover {
        border-color: rgba(236,72,153,0.12);
        box-shadow: 0 20px 60px rgba(236,72,153,0.08);
        transform: translateY(-8px) scale(1.02);
    }
    .team-card .avatar {
        font-size: 52px;
        margin-bottom: 12px;
        display: block;
        transition: all 0.5s ease;
    }
    .team-card:hover .avatar {
        transform: scale(1.1) rotate(-5deg);
    }
    .team-card .name {
        font-size: 17px;
        font-weight: 700;
        color: #1a0a14;
        margin-bottom: 2px;
    }
    .team-card .role {
        font-size: 13px;
        color: #EC4899;
        font-weight: 600;
        margin-bottom: 4px;
    }
    .team-card .city {
        font-size: 12px;
        color: #999;
        margin: 0;
    }
    .team-card .social {
        margin-top: 12px;
        display: flex;
        gap: 10px;
        justify-content: center;
    }
    .team-card .social a {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: rgba(236,72,153,0.06);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #888;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .team-card .social a:hover {
        background: linear-gradient(135deg, #F472B6, #EC4899);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(236,72,153,0.2);
    }

    /* ============================================================ */
    /* TESTIMONIAL CARDS */
    /* ============================================================ */
    .testimonial-card {
        background: #fff;
        border-radius: 20px;
        padding: 28px 24px;
        border: 1px solid rgba(236,72,153,0.06);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        height: 100%;
        position: relative;
        cursor: pointer;
    }
    .testimonial-card::before {
        content: '"';
        position: absolute;
        top: -10px;
        right: 20px;
        font-size: 80px;
        color: rgba(236,72,153,0.04);
        font-family: Georgia, serif;
    }
    .testimonial-card:hover {
        border-color: rgba(236,72,153,0.12);
        box-shadow: 0 20px 60px rgba(236,72,153,0.06);
        transform: translateY(-6px);
    }
    .testimonial-card .stars {
        color: #F59E0B;
        font-size: 14px;
        margin-bottom: 8px;
    }
    .testimonial-card .text {
        font-size: 14px;
        color: #666;
        line-height: 1.9;
        font-style: italic;
        margin-bottom: 12px;
    }
    .testimonial-card .author {
        font-weight: 700;
        color: #1a0a14;
        font-size: 14px;
    }
    .testimonial-card .author-role {
        font-size: 12px;
        color: #999;
    }

    /* ============================================================ */
    /* RESPONSIVE */
    /* ============================================================ */
    @media (max-width: 768px) {
        .value-card {
            padding: 24px 18px;
        }
        .mv-card {
            padding: 28px 20px;
        }
        .mv-card h4 {
            font-size: 18px;
        }
        .mv-card p {
            font-size: 14px;
        }
        .page-header h1 {
            font-size: 30px !important;
        }
        .story-image {
            min-height: 250px;
        }
        .story-image img {
            min-height: 250px;
        }
        .team-card {
            padding: 24px 16px;
        }
        .team-card .avatar {
            font-size: 44px;
        }
        .testimonial-card {
            padding: 22px 18px;
        }
    }
    @media (max-width: 576px) {
        .value-card {
            padding: 20px 14px;
        }
        .value-card .icon {
            font-size: 30px;
        }
        .mv-card {
            padding: 22px 16px;
        }
        .mv-card .icon-wrap {
            width: 48px;
            height: 48px;
            font-size: 20px;
        }
        .mv-card h4 {
            font-size: 16px;
        }
        .mv-card p {
            font-size: 13px;
        }
        .page-header h1 {
            font-size: 26px !important;
        }
        .page-header p {
            font-size: 14px !important;
        }
        .story-image {
            min-height: 200px;
        }
        .story-image img {
            min-height: 200px;
        }
        .team-card .avatar {
            font-size: 38px;
        }
        .team-card .name {
            font-size: 15px;
        }
        .team-card .role {
            font-size: 12px;
        }
        .testimonial-card {
            padding: 18px 14px;
        }
        .testimonial-card .text {
            font-size: 13px;
        }
    }
</style>

<!-- ============================================================ -->
<!-- PAGE HEADER -->
<!-- ============================================================ -->
<section class="page-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center animate-slide-up">
                <div style="display: inline-block; background: rgba(236,72,153,0.08); color: #EC4899; padding: 4px 16px; border-radius: 50px; font-size: 12px; font-weight: 600; letter-spacing: 1px; margin-bottom: 10px; border: 1px solid rgba(236,72,153,0.1);">
                    <i class="fas fa-info-circle me-1"></i> About Us
                </div>
                <h1 style="font-size: 36px; font-weight: 900; color: #1a0a14; margin-bottom: 6px;">About <span class="gradient-text">Beauty Blush</span></h1>
                <p style="font-size: 16px; color: #888; margin: 0 auto; max-width: 500px; line-height: 1.7;">Pakistan's premier multi-salon booking platform, connecting beauty enthusiasts with the finest salons across the country.</p>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- OUR STORY SECTION -->
<!-- ============================================================ -->
<section style="padding: 50px 0; background: #fff;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 animate-slide-up">
                <div class="story-image">
                    <img src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=600&q=80" alt="About Beauty Blush">
                </div>
            </div>
            <div class="col-lg-6 animate-slide-up delay-2">
                <div style="display: inline-block; background: rgba(236,72,153,0.08); color: #EC4899; padding: 3px 14px; border-radius: 50px; font-size: 11px; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 10px; border: 1px solid rgba(236,72,153,0.1);">
                    Our Story
                </div>
                <h2 style="font-size: 30px; font-weight: 800; color: #1a0a14; margin-bottom: 12px; line-height: 1.2;">Your Beauty, <span class="gradient-text">Our Passion</span></h2>
                <p style="font-size: 15px; color: #666; line-height: 1.9; margin-bottom: 12px;">Beauty Blush was born with a simple vision — to make salon booking effortless, transparent, and delightful. We connect beauty enthusiasts with Pakistan's finest salons, ensuring a seamless experience from discovery to confirmation.</p>
                <p style="font-size: 15px; color: #666; line-height: 1.9; margin-bottom: 18px;">Today, we're proud to serve thousands of happy clients across major cities, partnering with verified salons that share our commitment to quality and service excellence.</p>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <div style="display: flex; align-items: center; gap: 8px; background: #FFF9FC; padding: 8px 12px; border-radius: 8px; border: 1px solid #FFE8F0;">
                        <i class="fas fa-check-circle" style="color: #EC4899; font-size: 14px;"></i>
                        <span style="font-size: 13px; color: #555;">100% Verified Salons</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; background: #FFF9FC; padding: 8px 12px; border-radius: 8px; border: 1px solid #FFE8F0;">
                        <i class="fas fa-check-circle" style="color: #EC4899; font-size: 14px;"></i>
                        <span style="font-size: 13px; color: #555;">Secure Payments</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; background: #FFF9FC; padding: 8px 12px; border-radius: 8px; border: 1px solid #FFE8F0;">
                        <i class="fas fa-check-circle" style="color: #EC4899; font-size: 14px;"></i>
                        <span style="font-size: 13px; color: #555;">Real-time Booking</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; background: #FFF9FC; padding: 8px 12px; border-radius: 8px; border: 1px solid #FFE8F0;">
                        <i class="fas fa-check-circle" style="color: #EC4899; font-size: 14px;"></i>
                        <span style="font-size: 13px; color: #555;">24/7 Support</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- MISSION, VISION & VALUES SECTION -->
<!-- ============================================================ -->
<section style="padding: 50px 0; background: #FAFAFA;">
    <div class="container">
        <div class="row g-4">
            <!-- Mission -->
            <div class="col-md-4 animate-slide-up">
                <div class="mv-card">
                    <div class="icon-wrap">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h4>Our Mission</h4>
                    <p>To revolutionize the beauty industry by providing a seamless, transparent, and reliable platform that connects clients with the best salons across Pakistan.</p>
                </div>
            </div>
            
            <!-- Vision -->
            <div class="col-md-4 animate-slide-up delay-2">
                <div class="mv-card">
                    <div class="icon-wrap">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h4>Our Vision</h4>
                    <p>To become Pakistan's most trusted beauty booking platform, empowering local businesses and enhancing the beauty experience for everyone.</p>
                </div>
            </div>
            
            <!-- Core Values -->
            <div class="col-md-4 animate-slide-up delay-3">
                <div class="mv-card">
                    <div class="icon-wrap">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4>Our Core Values</h4>
                    <p>Trust, transparency, and customer satisfaction are at the heart of everything we do. We believe in empowering both clients and salon owners through innovation and integrity.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- WHY CHOOSE US SECTION -->
<!-- ============================================================ -->
<section style="padding: 50px 0; background: #fff;">
    <div class="container">
        <div class="text-center mb-4 animate-slide-up">
            <span style="color: #EC4899; font-weight: 600; font-size: 12px; letter-spacing: 2px; text-transform: uppercase; display: block; margin-bottom: 4px;">Why Choose Us</span>
            <h2 style="font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 800; color: #1a0a14;">Why Choose <span class="gradient-text">Beauty Blush?</span></h2>
            <p style="color: #888; font-size: 14px; margin-top: 4px;">We're committed to providing the best beauty booking experience</p>
        </div>

        <div class="row g-3">
            @php
                $values = [
                    ['icon' => '🛡️', 'title' => 'Verified Salons', 'desc' => 'All salons verified for quality and service excellence.'],
                    ['icon' => '⚡', 'title' => 'Real-time Booking', 'desc' => 'Instant confirmation with real-time availability.'],
                    ['icon' => '💳', 'title' => 'Secure Payments', 'desc' => '100% secure transactions with multiple payment options.'],
                    ['icon' => '🎧', 'title' => '24/7 Support', 'desc' => 'Dedicated support team always ready to assist you.'],
                    ['icon' => '⭐', 'title' => 'Premium Services', 'desc' => 'Access to Pakistan\'s finest salons and beauty services.'],
                    ['icon' => '📱', 'title' => 'Easy Booking', 'desc' => 'Book your favorite salon in under 60 seconds.'],
                ];
            @endphp
            @foreach($values as $value)
                <div class="col-md-4 animate-slide-up" style="animation-delay: {{ 0.1 + $loop->index * 0.05 }}s;">
                    <div class="value-card">
                        <span class="icon">{{ $value['icon'] }}</span>
                        <h6>{{ $value['title'] }}</h6>
                        <p>{{ $value['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- TESTIMONIALS SECTION -->
<!-- ============================================================ -->
<section style="padding: 50px 0; background: #FAFAFA;">
    <div class="container">
        <div class="text-center mb-4 animate-slide-up">
            <span style="color: #EC4899; font-weight: 600; font-size: 12px; letter-spacing: 2px; text-transform: uppercase; display: block; margin-bottom: 4px;">Testimonials</span>
            <h2 style="font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 800; color: #1a0a14;">What Our <span class="gradient-text">Clients Say</span></h2>
        </div>

        <div class="row g-3">
            @php
                $testimonials = [
                    ['name' => 'Sarah Khan', 'role' => 'Lahore', 'text' => 'Beauty Blush made booking my salon appointments so easy! I found the perfect salon within minutes and the service was exceptional. Highly recommended!', 'rating' => 5],
                    ['name' => 'Amina Malik', 'role' => 'Karachi', 'text' => 'I love how convenient it is to book appointments on Beauty Blush. The platform is user-friendly and the salons are always top-notch. Great experience!', 'rating' => 5],
                    ['name' => 'Zara Ahmed', 'role' => 'Islamabad', 'text' => 'The best beauty booking platform in Pakistan! I\'ve discovered amazing salons through Beauty Blush and the booking process is seamless every time.', 'rating' => 5],
                ];
            @endphp
            @foreach($testimonials as $testimonial)
                <div class="col-md-4 animate-slide-up" style="animation-delay: {{ 0.1 + $loop->index * 0.05 }}s;">
                    <div class="testimonial-card">
                        <div class="stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                        </div>
                        <p class="text">"{{ $testimonial['text'] }}"</p>
                        <div class="author">{{ $testimonial['name'] }}</div>
                        <div class="author-role">{{ $testimonial['role'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- TEAM SECTION - 3 MEMBERS -->
<!-- ============================================================ -->
<section style="padding: 50px 0; background: #fff;">
    <div class="container">
        <div class="text-center mb-4 animate-slide-up">
            <span style="color: #EC4899; font-weight: 600; font-size: 12px; letter-spacing: 2px; text-transform: uppercase; display: block; margin-bottom: 4px;">The People Behind It</span>
            <h2 style="font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 800; color: #1a0a14;">Meet Our <span class="gradient-text">Team</span></h2>
            <p style="color: #888; font-size: 14px;">Passionate professionals dedicated to transforming Pakistan's beauty industry</p>
        </div>

        <div class="row g-4 justify-content-center">
            @php
                $team = [
                    ['emoji' => '👩‍💼', 'name' => 'Sahrihs Yaseen', 'role' => 'CEO & Co-founder', 'city' => 'Lahore'],
                    ['emoji' => '👩‍💻', 'name' => 'Mahnoor NS', 'role' => 'Head of Operations', 'city' => 'Karachi'],
                    ['emoji' => '🌸', 'name' => 'Nadia Hussain', 'role' => 'Customer Experience', 'city' => 'Lahore'],
                ];
            @endphp
            @foreach($team as $member)
                <div class="col-md-4 animate-slide-up" style="animation-delay: {{ 0.1 + $loop->index * 0.05 }}s;">
                    <div class="team-card">
                        <span class="avatar">{{ $member['emoji'] }}</span>
                        <div class="name">{{ $member['name'] }}</div>
                        <div class="role">{{ $member['role'] }}</div>
                        <div class="city"><i class="fas fa-map-marker-alt me-1" style="color: #EC4899; font-size: 10px;"></i>{{ $member['city'] }}</div>
                        <div class="social">
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- CTA SECTION -->
<!-- ============================================================ -->
<section style="background: linear-gradient(135deg, #0a0508 0%, #1a0a14 30%, #3d1a2e 60%, #6b2147 100%); padding: 45px 0; text-align: center; position: relative; overflow: hidden;">
    <div class="animate-float" style="position: absolute; top: -40%; right: -20%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(244,114,182,0.06) 0%, transparent 70%); border-radius: 50%;"></div>
    <div class="animate-float-reverse" style="position: absolute; bottom: -30%; left: -15%; width: 250px; height: 250px; background: radial-gradient(circle, rgba(244,114,182,0.04) 0%, transparent 70%); border-radius: 50%;"></div>
    
    <div class="container" style="position: relative; z-index: 1;">
        <div class="animate-slide-up">
            <h2 style="font-size: clamp(1.6rem, 2.5vw, 2.2rem); font-weight: 800; color: #fff; margin-bottom: 6px;">
                Ready to <span class="gradient-text">Experience</span> Beauty Blush?
            </h2>
            <p style="color: rgba(255,255,255,0.7); font-size: 15px; max-width: 400px; margin: 0 auto 16px; line-height: 1.6;">
                Join thousands of happy clients who trust us for their beauty needs.
            </p>
            <div style="display: flex; flex-wrap: wrap; gap: 12px; justify-content: center;">
                <a href="{{ route('salons.index') }}" style="background: linear-gradient(135deg, #F472B6, #DB2777); color: #fff; padding: 12px 34px; border-radius: 50px; font-weight: 700; font-size: 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 6px 25px rgba(219,39,119,0.3); transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);" onmouseover="this.style.transform='translateY(-3px) scale(1.02)'; this.style.boxShadow='0 12px 40px rgba(219,39,119,0.4)';" onmouseout="this.style.transform=''; this.style.boxShadow='0 6px 25px rgba(219,39,119,0.3)';">
                    <i class="fas fa-calendar-check"></i> Book Now
                </a>
                <a href="{{ route('contact') }}" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.2); color: #fff; padding: 12px 34px; border-radius: 50px; font-weight: 600; font-size: 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);" onmouseover="this.style.background='rgba(255,255,255,0.15)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='rgba(255,255,255,0.08)'; this.style.transform='';">
                    <i class="fas fa-envelope"></i> Contact Us
                </a>
            </div>
        </div>
    </div>
</section>

@endsection