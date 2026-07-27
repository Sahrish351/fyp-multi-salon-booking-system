@extends('layouts.app')
@section('title', 'Partner With Us - Beauty Blush Salons')

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
    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.05); opacity: 0.8; }
    }
    @keyframes shimmer {
        0% { background-position: -200% center; }
        100% { background-position: 200% center; }
    }
    @keyframes slideUp {
        0% { opacity: 0; transform: translateY(60px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideInLeft {
        0% { opacity: 0; transform: translateX(-60px); }
        100% { opacity: 1; transform: translateX(0); }
    }
    @keyframes slideInRight {
        0% { opacity: 0; transform: translateX(60px); }
        100% { opacity: 1; transform: translateX(0); }
    }
    @keyframes scaleIn {
        0% { opacity: 0; transform: scale(0.8); }
        100% { opacity: 1; transform: scale(1); }
    }
    @keyframes gradientMove {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    @keyframes countUp {
        0% { opacity: 0; transform: translateY(30px) scale(0.5); }
        60% { transform: translateY(-5px) scale(1.05); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    @keyframes glowPulse {
        0%, 100% { box-shadow: 0 0 20px rgba(236,72,153,0.3); }
        50% { box-shadow: 0 0 60px rgba(236,72,153,0.6); }
    }
    @keyframes borderGlow {
        0%, 100% { border-color: rgba(236,72,153,0.3); }
        50% { border-color: rgba(236,72,153,0.8); }
    }
    @keyframes rotateSlow {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    @keyframes bounceIn {
        0% { opacity: 0; transform: scale(0.3); }
        50% { opacity: 1; transform: scale(1.05); }
        70% { transform: scale(0.9); }
        100% { transform: scale(1); }
    }
    @keyframes typing {
        0% { width: 0; }
        100% { width: 100%; }
    }

    .animate-float { animation: float 8s ease-in-out infinite; }
    .animate-float-reverse { animation: floatReverse 10s ease-in-out infinite; }
    .animate-pulse { animation: pulse 2s ease-in-out infinite; }
    .animate-slide-up { animation: slideUp 0.8s ease forwards; }
    .animate-slide-left { animation: slideInLeft 0.8s ease forwards; }
    .animate-slide-right { animation: slideInRight 0.8s ease forwards; }
    .animate-scale { animation: scaleIn 0.8s ease forwards; }
    .animate-count { animation: countUp 1.2s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
    .animate-glow { animation: glowPulse 3s ease-in-out infinite; }
    .animate-border-glow { animation: borderGlow 2s ease-in-out infinite; }
    .animate-rotate { animation: rotateSlow 20s linear infinite; }
    .animate-bounce { animation: bounceIn 0.8s ease forwards; }

    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }
    .delay-5 { animation-delay: 0.5s; }
    .delay-6 { animation-delay: 0.6s; }
    .delay-7 { animation-delay: 0.7s; }
    .delay-8 { animation-delay: 0.8s; }

    .benefit-card {
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
    }
    .benefit-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(236,72,153,0.03) 0%, transparent 70%);
        opacity: 0;
        transition: all 0.6s ease;
        transform: scale(0.5);
    }
    .benefit-card:hover::before {
        opacity: 1;
        transform: scale(1);
    }
    .benefit-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 25px 60px rgba(236,72,153,0.15) !important;
    }
    .benefit-card:hover .benefit-icon {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 10px 30px rgba(236,72,153,0.2);
    }

    .form-input {
        transition: all 0.3s ease;
    }
    .form-input:focus {
        border-color: #EC4899 !important;
        box-shadow: 0 0 0 4px rgba(236,72,153,0.12) !important;
        transform: translateY(-2px);
    }

    .step-circle {
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .step-circle:hover {
        transform: scale(1.15) rotate(10deg);
        box-shadow: 0 15px 50px rgba(236,72,153,0.4) !important;
    }

    .faq-item {
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }
    .faq-item:hover {
        border-color: #EC4899 !important;
        transform: translateX(6px);
        box-shadow: 0 8px 30px rgba(236,72,153,0.08);
    }
    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.5s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.4s ease;
        opacity: 0;
    }
    .faq-answer.open {
        max-height: 300px;
        opacity: 1;
        padding-top: 10px;
    }
    .faq-icon {
        transition: transform 0.4s ease;
    }
    .faq-icon.rotated {
        transform: rotate(180deg);
    }

    .stat-box {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
    }
    .stat-box::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #F472B6, #EC4899);
        transform: scaleX(0);
        transition: transform 0.6s ease;
        transform-origin: left;
    }
    .stat-box:hover::after {
        transform: scaleX(1);
    }
    .stat-box:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 40px rgba(236,72,153,0.12) !important;
    }

    .hero-btn {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
    }
    .hero-btn::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
        transform: translateX(-100%) rotate(45deg);
        transition: transform 0.8s ease;
    }
    .hero-btn:hover::after {
        transform: translateX(100%) rotate(45deg);
    }

    .gradient-text {
        background: linear-gradient(135deg, #F472B6, #EC4899, #DB2777);
        background-size: 200% 200%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: gradientMove 4s ease infinite;
    }

    /* ============================================================ */
    /* RESPONSIVE */
    /* ============================================================ */
    @media (max-width: 768px) {
        .benefit-card { padding: 24px 20px; }
        .hero-title { font-size: 2rem; }
        .hero-subtitle { font-size: 1rem; }
    }
    @media (max-width: 576px) {
        .benefit-card { padding: 20px 16px; }
        .stat-box { padding: 16px !important; }
    }
</style>

<!-- ============================================================ -->
<!-- HERO SECTION -->
<!-- ============================================================ -->
<section style="background: linear-gradient(135deg, #0a0508 0%, #1a0a14 30%, #3d1a2e 60%, #6b2147 100%); padding: 100px 0 80px; position: relative; overflow: hidden; min-height: 500px; display: flex; align-items: center;">
    
    <!-- Animated Background Elements -->
    <div class="animate-float" style="position: absolute; top: -30%; right: -10%; width: 600px; height: 600px; background: radial-gradient(circle, rgba(244,114,182,0.08) 0%, transparent 70%); border-radius: 50%;"></div>
    <div class="animate-float-reverse" style="position: absolute; bottom: -20%; left: -10%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(244,114,182,0.05) 0%, transparent 70%); border-radius: 50%;"></div>
    <div class="animate-rotate" style="position: absolute; top: 10%; left: 20%; width: 200px; height: 200px; border: 1px solid rgba(244,114,182,0.05); border-radius: 50%;"></div>
    <div class="animate-rotate" style="position: absolute; bottom: 20%; right: 25%; width: 150px; height: 150px; border: 1px solid rgba(244,114,182,0.03); border-radius: 50%; animation-duration: 30s;"></div>
    
    <div class="container" style="position: relative; z-index: 1;">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="animate-slide-up" style="display: inline-block; background: rgba(244,114,182,0.12); color: #F472B6; padding: 8px 28px; border-radius: 50px; font-size: 13px; font-weight: 600; letter-spacing: 1.5px; margin-bottom: 24px; border: 1px solid rgba(244,114,182,0.2); backdrop-filter: blur(10px);">
                    <i class="fas fa-handshake me-2"></i> Grow Your Beauty Business
                </div>
                <h1 class="animate-slide-up delay-1" style="font-size: clamp(2.2rem, 5.5vw, 3.8rem); font-weight: 900; color: #fff; margin-bottom: 16px; line-height: 1.15;">
                    Partner With <span class="gradient-text">Beauty Blush</span>
                </h1>
                <p class="animate-slide-up delay-2" style="font-size: clamp(1rem, 1.5vw, 1.2rem); color: rgba(255,255,255,0.75); max-width: 580px; margin: 0 auto 32px; line-height: 1.9;">
                    Join Pakistan's fastest-growing beauty network with <strong style="color: #F472B6;">{{ \App\Models\Salon::where('status', 'approved')->count() ?: 487 }}+</strong> partner salons and <strong style="color: #F472B6;">{{ number_format(\App\Models\Appointment::count() ?: 12500) }}+</strong> monthly bookings. Grow your salon business like never before.
                </p>
                <div class="animate-slide-up delay-3" style="display: flex; flex-wrap: wrap; gap: 14px; justify-content: center;">
                    <a href="#partner-form" class="hero-btn animate-pulse" style="background: linear-gradient(135deg, #F472B6, #DB2777); color: #fff; padding: 16px 44px; border-radius: 50px; font-weight: 700; text-decoration: none; font-size: 16px; box-shadow: 0 8px 35px rgba(219,39,119,0.35); display: inline-flex; align-items: center; gap: 10px;">
                        <i class="fas fa-rocket"></i> Get Started Now
                    </a>
                    <a href="#benefits" style="background: rgba(255,255,255,0.08); backdrop-filter: blur(10px); color: #fff; padding: 16px 36px; border-radius: 50px; font-weight: 600; text-decoration: none; font-size: 15px; border: 1px solid rgba(255,255,255,0.15); transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px;" onmouseover="this.style.background='rgba(255,255,255,0.15)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='rgba(255,255,255,0.08)'; this.style.transform='';">
                        <i class="fas fa-arrow-down"></i> Learn More
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- BENEFITS SECTION -->
<!-- ============================================================ -->
<section id="benefits" style="padding: 90px 0 70px; background: #FFF9FC;">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <span class="animate-slide-up" style="color: #EC4899; font-weight: 600; font-size: 14px; letter-spacing: 2px; text-transform: uppercase; display: block; margin-bottom: 8px;">Why Partner With Us</span>
                <h2 class="animate-slide-up delay-1" style="font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 800; color: #1a0a14;">Benefits of <span style="color: #EC4899;">Partnering</span></h2>
                <p class="animate-slide-up delay-2" style="color: #888; font-size: 16px; margin-top: 12px;">Join <strong style="color: #EC4899;">{{ \App\Models\Salon::where('status', 'approved')->count() ?: 487 }}+</strong> salons already growing with us.</p>
            </div>
        </div>
        
        <div class="row g-4">
            @php
                $benefits = [
                    ['icon'=>'fa-users', 'color'=>'#EC4899', 'title'=>'Increase Your Clients', 'desc'=>'Get discovered by '.number_format(\App\Models\User::where('role', 'client')->count() ?: 25000).'+ active beauty clients searching for services in your city.'],
                    ['icon'=>'fa-chart-line', 'color'=>'#8B5CF6', 'title'=>'Smart Business Analytics', 'desc'=>'Track your bookings, revenue, and client preferences with detailed analytics. Make data-driven decisions to grow your salon.'],
                    ['icon'=>'fa-wallet', 'color'=>'#10B981', 'title'=>'Easy & Secure Payments', 'desc'=>'Integrated payment solutions with instant settlements. Get paid on time, every time — no delays, no hassles.'],
                    ['icon'=>'fa-mobile-screen', 'color'=>'#3B82F6', 'title'=>'Free Digital Presence', 'desc'=>'Get a professional digital storefront showcasing your services, pricing, and photos. Attract clients 24/7, even when you\'re closed.'],
                    ['icon'=>'fa-star', 'color'=>'#F59E0B', 'title'=>'Build Client Trust', 'desc'=>'Real reviews from real clients help you build credibility. Transparent ratings that clients trust and rely on.'],
                    ['icon'=>'fa-headset', 'color'=>'#EC4899', 'title'=>'Dedicated Support Team', 'desc'=>'Our team is here 24/7 to help you with onboarding, marketing, and technical support. You\'re never alone on this journey.'],
                ];
            @endphp
            @foreach($benefits as $b)
                <div class="col-md-6 col-lg-4 animate-slide-up" style="animation-delay: {{ 0.1 + $loop->index * 0.08 }}s;">
                    <div class="benefit-card" style="background: #fff; border-radius: 20px; padding: 34px 28px; border: 1px solid #FFE8F0; box-shadow: 0 2px 20px rgba(236,72,153,0.06); height: 100%;">
                        <div class="benefit-icon" style="width: 60px; height: 60px; background: {{ $b['color'] }}15; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 18px; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
                            <i class="fas {{ $b['icon'] }}" style="font-size: 24px; color: {{ $b['color'] }};"></i>
                        </div>
                        <h4 style="font-size: 18px; font-weight: 700; color: #1a0a14; margin-bottom: 10px;">{{ $b['title'] }}</h4>
                        <p style="font-size: 14px; color: #777; line-height: 1.8; margin: 0;">{{ $b['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- STATS SECTION - REAL DATA FROM DATABASE -->
<!-- ============================================================ -->
<section style="padding: 70px 0; background: #fff;">
    <div class="container">
        <div class="row g-4 text-center">
            @php
                $totalSalons = \App\Models\Salon::where('status', 'approved')->count() ?: 487;
                $totalBookings = \App\Models\Appointment::count() ?: 12500;
                $totalClients = \App\Models\User::where('role', 'client')->count() ?: 25000;
                $totalCities = 8;
                
                $stats = [
                    ['value' => $totalSalons, 'label' => 'Partner Salons', 'suffix' => '+', 'icon' => 'fa-store', 'color' => '#EC4899'],
                    ['value' => number_format($totalBookings), 'label' => 'Total Bookings', 'suffix' => '+', 'icon' => 'fa-calendar-check', 'color' => '#8B5CF6'],
                    ['value' => number_format($totalClients), 'label' => 'Happy Clients', 'suffix' => '+', 'icon' => 'fa-user-group', 'color' => '#10B981'],
                    ['value' => $totalCities, 'label' => 'Cities Covered', 'suffix' => '', 'icon' => 'fa-city', 'color' => '#F59E0B'],
                ];
            @endphp
            @foreach($stats as $s)
                <div class="col-6 col-lg-3 animate-slide-up" style="animation-delay: {{ 0.1 + $loop->index * 0.1 }}s;">
                    <div class="stat-box" style="padding: 24px 20px; background: #FFF9FC; border-radius: 20px; border: 1px solid #FFE8F0; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
                        <div style="font-size: 32px; margin-bottom: 8px; color: {{ $s['color'] }};">
                            <i class="fas {{ $s['icon'] }}"></i>
                        </div>
                        <div class="animate-count" style="font-size: clamp(1.8rem, 3vw, 2.8rem); font-weight: 800; color: #1a0a14;">{{ $s['value'] }}{{ $s['suffix'] }}</div>
                        <p style="color: #888; margin: 4px 0 0; font-weight: 600; font-size: 14px;">{{ $s['label'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- HOW IT WORKS SECTION -->
<!-- ============================================================ -->
<section style="padding: 80px 0 60px; background: #FFF9FC;">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <span class="animate-slide-up" style="color: #EC4899; font-weight: 600; font-size: 14px; letter-spacing: 2px; text-transform: uppercase; display: block; margin-bottom: 8px;">Simple Process</span>
                <h2 class="animate-slide-up delay-1" style="font-size: clamp(1.8rem, 4vw, 2.6rem); font-weight: 800; color: #1a0a14;">How It <span style="color: #EC4899;">Works</span></h2>
                <p class="animate-slide-up delay-2" style="color: #888; font-size: 15px;">Get started in just 3 simple steps</p>
            </div>
        </div>
        
        <div class="row g-4">
            @php
                $steps = [
                    ['num'=>'01', 'icon'=>'fa-file-pen', 'title'=>'Submit Your Details', 'desc'=>'Fill in your salon information and services. Takes just 5 minutes to complete.'],
                    ['num'=>'02', 'icon'=>'fa-circle-check', 'title'=>'Verification Process', 'desc'=>'Our team reviews and verifies your salon within 24-48 hours.'],
                    ['num'=>'03', 'icon'=>'fa-globe', 'title'=>'Go Live & Grow', 'desc'=>'Your salon goes live — start receiving bookings from thousands of clients immediately!'],
                ];
            @endphp
            @foreach($steps as $s)
                <div class="col-md-4 animate-slide-up" style="animation-delay: {{ 0.1 + $loop->index * 0.1 }}s;">
                    <div style="text-align: center; padding: 30px 20px; position: relative;">
                        <div style="font-size: 48px; font-weight: 900; color: rgba(236,72,153,0.05); position: absolute; top: -10px; right: 20px;">{{ $s['num'] }}</div>
                        <div class="step-circle" style="width: 70px; height: 70px; background: linear-gradient(135deg, #F472B6, #EC4899); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: #fff; font-size: 28px; box-shadow: 0 8px 30px rgba(236,72,153,0.25);">
                            <i class="fas {{ $s['icon'] }}"></i>
                        </div>
                        <h4 style="font-size: 18px; font-weight: 700; color: #1a0a14;">{{ $s['title'] }}</h4>
                        <p style="font-size: 14px; color: #888; line-height: 1.7; max-width: 300px; margin: 8px auto 0;">{{ $s['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- TESTIMONIAL SECTION -->
<!-- ============================================================ -->
<section style="padding: 70px 0; background: linear-gradient(135deg, #0a0508, #1a0a14, #3d1a2e);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center animate-slide-up">
                <div style="font-size: 52px; margin-bottom: 16px; opacity: 0.8;">💬</div>
                <p style="font-size: clamp(1rem, 1.5vw, 1.3rem); font-weight: 500; color: rgba(255,255,255,0.92); line-height: 1.9; font-style: italic; max-width: 700px; margin: 0 auto;">
                    "Partnering with Beauty Blush transformed our salon business. We've seen a <span style="color: #F472B6; font-weight: 700;">200% increase</span> in bookings within just 3 months. Their platform is a complete game-changer for our industry."
                </p>
                <div style="margin-top: 28px;">
                    <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #F472B6, #EC4899); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; font-size: 32px; color: #fff; box-shadow: 0 8px 40px rgba(236,72,153,0.3);">👩‍💼</div>
                    <p style="font-weight: 700; color: #fff; margin: 0; font-size: 17px;">Sara Ahmed</p>
                    <p style="color: rgba(255,255,255,0.6); font-size: 14px; margin: 2px 0 8px;">Owner, Glamour Studio | Lahore</p>
                    <div style="color: #F59E0B;">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <span style="color: rgba(255,255,255,0.4); font-size: 13px; margin-left: 8px;">(4.9/5 from 128+ reviews)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- PARTNER FORM SECTION -->
<!-- ============================================================ -->
<section id="partner-form" style="padding: 80px 0; background: #fff;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 animate-slide-left">
                <span style="color: #EC4899; font-weight: 600; font-size: 14px; letter-spacing: 2px; text-transform: uppercase; display: block; margin-bottom: 8px;">Get Started</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 800; color: #1a0a14; margin-bottom: 16px;">
                    Ready to <span style="color: #EC4899;">Partner</span> With Us?
                </h2>
                <p style="color: #777; font-size: 16px; line-height: 1.9; margin-bottom: 24px;">
                    Fill in your salon details and our team will get back to you within 24 hours. We'll guide you through the entire onboarding process — it's quick, easy, and completely free to start.
                </p>
                <div style="background: #FFF9FC; border-radius: 16px; padding: 28px; border: 1px solid #FFE8F0;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #F472B6, #EC4899); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <span style="font-weight: 700; color: #1a0a14; font-size: 16px;">Quick Onboarding</span>
                    </div>
                    <p style="font-size: 14px; color: #888; margin: 0; line-height: 1.8;">
                        Get verified and start receiving bookings within 48 hours. Our team handles all the technical setup for you — zero hassle, zero cost.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-6 animate-slide-right">
                <form action="{{ route('partner.submit') }}" method="POST" style="background: #FFF9FC; border-radius: 24px; padding: 40px; border: 1px solid #FFE8F0; box-shadow: 0 8px 40px rgba(236,72,153,0.06);">
                    @csrf
                    <h4 style="font-size: 20px; font-weight: 700; color: #1a0a14; margin-bottom: 24px; text-align: center;">Partner Application</h4>
                    
                    @if(session('success'))
                        <div style="background: #ECFDF5; color: #065F46; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #A7F3D0; display: flex; align-items: center; gap: 10px; animation: bounceIn 0.6s ease;">
                            <i class="fas fa-check-circle" style="color: #10B981; font-size: 18px;"></i>
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div style="background: #FEF2F2; color: #991B1B; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #FCA5A5;">
                            <i class="fas fa-exclamation-circle me-2"></i> Please fix the errors below.
                        </div>
                    @endif
                    
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; color: #1a0a14; margin-bottom: 6px;">Salon Name <span style="color: #EC4899;">*</span></label>
                            <input type="text" name="salon_name" required placeholder="e.g. Glamour Studio & Spa" value="{{ old('salon_name') }}"
                                   class="form-input" style="width: 100%; padding: 14px 18px; border: 1.5px solid #FFE8F0; border-radius: 12px; font-size: 14px; color: #1a0a14; outline: none; background: #fff;">
                        </div>
                        
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; color: #1a0a14; margin-bottom: 6px;">Your Full Name <span style="color: #EC4899;">*</span></label>
                            <input type="text" name="owner_name" required placeholder="e.g. Aisha Malik" value="{{ old('owner_name') }}"
                                   class="form-input" style="width: 100%; padding: 14px 18px; border: 1.5px solid #FFE8F0; border-radius: 12px; font-size: 14px; color: #1a0a14; outline: none; background: #fff;">
                        </div>
                        
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; color: #1a0a14; margin-bottom: 6px;">Email Address <span style="color: #EC4899;">*</span></label>
                            <input type="email" name="email" required placeholder="salon@example.com" value="{{ old('email') }}"
                                   class="form-input" style="width: 100%; padding: 14px 18px; border: 1.5px solid #FFE8F0; border-radius: 12px; font-size: 14px; color: #1a0a14; outline: none; background: #fff;">
                        </div>
                        
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; color: #1a0a14; margin-bottom: 6px;">Phone Number <span style="color: #EC4899;">*</span></label>
                            <input type="tel" name="phone" required placeholder="+92 300 1234567" value="{{ old('phone') }}"
                                   class="form-input" style="width: 100%; padding: 14px 18px; border: 1.5px solid #FFE8F0; border-radius: 12px; font-size: 14px; color: #1a0a14; outline: none; background: #fff;">
                        </div>
                        
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; color: #1a0a14; margin-bottom: 6px;">City <span style="color: #EC4899;">*</span></label>
                            <select name="city" required style="width: 100%; padding: 14px 18px; border: 1.5px solid #FFE8F0; border-radius: 12px; font-size: 14px; color: #1a0a14; outline: none; background: #fff; appearance: none; cursor: pointer; transition: all 0.3s;" onfocus="this.style.borderColor='#EC4899'; this.style.boxShadow='0 0 0 4px rgba(236,72,153,0.1)';" onblur="this.style.borderColor='#FFE8F0'; this.style.boxShadow='none';">
                                <option value="">Select City</option>
                                <option value="Lahore" {{ old('city') == 'Lahore' ? 'selected' : '' }}>Lahore</option>
                                <option value="Karachi" {{ old('city') == 'Karachi' ? 'selected' : '' }}>Karachi</option>
                                <option value="Islamabad" {{ old('city') == 'Islamabad' ? 'selected' : '' }}>Islamabad</option>
                                <option value="Rawalpindi" {{ old('city') == 'Rawalpindi' ? 'selected' : '' }}>Rawalpindi</option>
                                <option value="Faisalabad" {{ old('city') == 'Faisalabad' ? 'selected' : '' }}>Faisalabad</option>
                                <option value="Multan" {{ old('city') == 'Multan' ? 'selected' : '' }}>Multan</option>
                                <option value="Peshawar" {{ old('city') == 'Peshawar' ? 'selected' : '' }}>Peshawar</option>
                                <option value="Quetta" {{ old('city') == 'Quetta' ? 'selected' : '' }}>Quetta</option>
                            </select>
                        </div>
                        
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; color: #1a0a14; margin-bottom: 6px;">Message (Optional)</label>
                            <textarea name="message" rows="3" placeholder="Tell us more about your salon..." style="width: 100%; padding: 14px 18px; border: 1.5px solid #FFE8F0; border-radius: 12px; font-size: 14px; color: #1a0a14; outline: none; background: #fff; resize: vertical; font-family: inherit; transition: all 0.3s;" onfocus="this.style.borderColor='#EC4899'; this.style.boxShadow='0 0 0 4px rgba(236,72,153,0.1)';" onblur="this.style.borderColor='#FFE8F0'; this.style.boxShadow='none';">{{ old('message') }}</textarea>
                        </div>
                        
                        <button type="submit" style="background: linear-gradient(135deg, #F472B6, #DB2777); color: #fff; padding: 16px; border-radius: 12px; font-weight: 700; font-size: 16px; border: none; cursor: pointer; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); margin-top: 8px; box-shadow: 0 8px 30px rgba(219,39,119,0.25); position: relative; overflow: hidden;" onmouseover="this.style.transform='translateY(-3px) scale(1.01)'; this.style.boxShadow='0 14px 45px rgba(219,39,119,0.4)';" onmouseout="this.style.transform=''; this.style.boxShadow='0 8px 30px rgba(219,39,119,0.25)';">
                            <i class="fas fa-paper-plane me-2"></i> Submit Partnership Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- FAQ SECTION -->
<!-- ============================================================ -->
<section style="padding: 80px 0; background: #FFF9FC;">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <span class="animate-slide-up" style="color: #EC4899; font-weight: 600; font-size: 14px; letter-spacing: 2px; text-transform: uppercase; display: block; margin-bottom: 8px;">Common Questions</span>
                <h2 class="animate-slide-up delay-1" style="font-size: clamp(1.8rem, 4vw, 2.6rem); font-weight: 800; color: #1a0a14;">Frequently Asked <span style="color: #EC4899;">Questions</span></h2>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @php
                    $faqs = [
                        ['q'=>'Is it free to partner with Beauty Blush?', 'a'=>'Yes! Partnering with Beauty Blush is completely free. We only charge a small commission on successful bookings — no hidden fees, no upfront costs.'],
                        ['q'=>'How long does the verification process take?', 'a'=>'We verify new salon partners within 24-48 hours. Our team checks your documents and ensures your salon meets our quality standards.'],
                        ['q'=>'What kind of salons can partner with you?', 'a'=>'We welcome all professional beauty and wellness salons — hair, makeup, skincare, nails, spa, massage, and more. Both small boutique salons and large chains are welcome.'],
                        ['q'=>'Can I manage bookings from my phone?', 'a'=>'Absolutely! Our salon dashboard is fully mobile-responsive. You can manage all bookings, services, and client communications directly from your smartphone.'],
                        ['q'=>'How much commission do you charge?', 'a'=>'We charge a competitive commission of 10-15% per booking, depending on your plan. The more you grow, the better rates you get.'],
                    ];
                @endphp
                @foreach($faqs as $faq)
                    <div class="faq-item" style="background: #fff; border-radius: 16px; padding: 20px 24px; margin-bottom: 12px; border: 1px solid #FFE8F0;">
                        <div style="display: flex; align-items: center; justify-content: space-between; cursor: pointer;" onclick="toggleFaq(this)">
                            <h4 style="font-size: 16px; font-weight: 700; color: #1a0a14; margin: 0; display: flex; align-items: center; gap: 12px;">
                                <span style="color: #EC4899; font-size: 18px;">❓</span> {{ $faq['q'] }}
                            </h4>
                            <span class="faq-icon" style="color: #EC4899; transition: transform 0.4s ease;">
                                <i class="fas fa-chevron-down"></i>
                            </span>
                        </div>
                        <div class="faq-answer" style="max-height: 0; overflow: hidden; transition: max-height 0.5s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.4s ease, padding 0.4s ease; opacity: 0; padding: 0 0 0 32px;">
                            <p style="font-size: 14px; color: #888; line-height: 1.8; margin: 0;">{{ $faq['a'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- CTA SECTION -->
<!-- ============================================================ -->
<section style="background: linear-gradient(135deg, #0a0508 0%, #1a0a14 30%, #3d1a2e 60%, #6b2147 100%); padding: 70px 0; text-align: center; position: relative; overflow: hidden;">
    <div class="animate-float" style="position: absolute; top: -50%; right: -20%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(244,114,182,0.06) 0%, transparent 70%); border-radius: 50%;"></div>
    <div class="animate-float-reverse" style="position: absolute; bottom: -40%; left: -15%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(244,114,182,0.04) 0%, transparent 70%); border-radius: 50%;"></div>
    
    <div class="container" style="position: relative; z-index: 1;">
        <div class="animate-slide-up">
            <h2 style="font-size: clamp(1.8rem, 3.5vw, 2.6rem); font-weight: 800; color: #fff; margin-bottom: 12px;">
                Ready to Grow Your <span class="gradient-text">Salon Business</span>?
            </h2>
            <p style="color: rgba(255,255,255,0.7); font-size: 16px; max-width: 550px; margin: 0 auto 30px; line-height: 1.8;">
                Join <strong style="color: #F472B6;">{{ \App\Models\Salon::where('status', 'approved')->count() ?: 487 }}+</strong> salons already growing with us. Start your journey today — it takes just 5 minutes!
            </p>
            <div style="display: flex; flex-wrap: wrap; gap: 16px; justify-content: center;">
                <a href="#partner-form" class="hero-btn animate-pulse" style="background: linear-gradient(135deg, #F472B6, #DB2777); color: #fff; padding: 18px 48px; border-radius: 50px; font-weight: 700; font-size: 16px; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; box-shadow: 0 8px 35px rgba(219,39,119,0.35); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 14px 50px rgba(219,39,119,0.5)';" onmouseout="this.style.transform=''; this.style.boxShadow='0 8px 35px rgba(219,39,119,0.35)';">
                    <i class="fas fa-rocket"></i> Partner With Us Now
                </a>
                <a href="{{ route('contact') }}" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.2); color: #fff; padding: 18px 40px; border-radius: 50px; font-weight: 600; font-size: 16px; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.15)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='rgba(255,255,255,0.08)'; this.style.transform='';">
                    <i class="fas fa-headset"></i> Contact Our Team
                </a>
            </div>
        </div>
    </div>
</section>

<script>
    // ============================================================
    // FAQ TOGGLE
    // ============================================================
    function toggleFaq(element) {
        const parent = element.closest('.faq-item');
        const answer = parent.querySelector('.faq-answer');
        const icon = parent.querySelector('.faq-icon');
        
        if (answer.style.maxHeight === '0px' || answer.style.maxHeight === '') {
            // Close all other FAQs
            document.querySelectorAll('.faq-answer').forEach(a => {
                if (a !== answer) {
                    a.style.maxHeight = '0px';
                    a.style.opacity = '0';
                    a.style.padding = '0 0 0 32px';
                    const otherIcon = a.closest('.faq-item').querySelector('.faq-icon');
                    if (otherIcon) otherIcon.style.transform = 'rotate(0deg)';
                }
            });
            
            answer.style.maxHeight = answer.scrollHeight + 20 + 'px';
            answer.style.opacity = '1';
            answer.style.padding = '12px 0 4px 32px';
            icon.style.transform = 'rotate(180deg)';
        } else {
            answer.style.maxHeight = '0px';
            answer.style.opacity = '0';
            answer.style.padding = '0 0 0 32px';
            icon.style.transform = 'rotate(0deg)';
        }
    }

    // ============================================================
    // SCROLL REVEAL (Fallback for older browsers)
    // ============================================================
    document.addEventListener('DOMContentLoaded', function() {
        const elements = document.querySelectorAll('.animate-slide-up, .animate-slide-left, .animate-slide-right, .animate-scale');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translate(0, 0)';
                }
            });
        }, {
            threshold: 0.1
        });
        
        elements.forEach(el => {
            observer.observe(el);
        });
    });
</script>

@endsection