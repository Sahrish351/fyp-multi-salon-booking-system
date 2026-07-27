@extends('layouts.app')
@section('title', 'Pricing - Beauty Blush Salons')

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
        0% { opacity: 0; transform: translateY(60px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes glowPulse {
        0%, 100% { box-shadow: 0 0 20px rgba(236,72,153,0.3); }
        50% { box-shadow: 0 0 60px rgba(236,72,153,0.6); }
    }
    @keyframes shimmer {
        0% { background-position: -200% center; }
        100% { background-position: 200% center; }
    }
    @keyframes rotateSlow {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    @keyframes priceFloat {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    .animate-float { animation: float 8s ease-in-out infinite; }
    .animate-float-reverse { animation: floatReverse 10s ease-in-out infinite; }
    .animate-slide-up { animation: slideUp 0.8s ease forwards; }
    .animate-glow { animation: glowPulse 3s ease-in-out infinite; }
    .animate-rotate { animation: rotateSlow 20s linear infinite; }
    .animate-price { animation: priceFloat 3s ease-in-out infinite; }

    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }
    .delay-5 { animation-delay: 0.5s; }

    /* ============================================================ */
    /* PRICING CARDS - FIXED ALL ISSUES */
    /* ============================================================ */
    .pricing-card {
        transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: visible !important;
        background: #fff;
        border-radius: 28px;
        border: 1px solid rgba(236,72,153,0.08);
        box-shadow: 0 2px 20px rgba(236,72,153,0.04);
        height: 100%;
        display: flex;
        flex-direction: column;
        backdrop-filter: blur(10px);
        padding: 36px 30px 28px;
        z-index: 1;
    }
    .pricing-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(236,72,153,0.04) 0%, transparent 70%);
        opacity: 0;
        transition: all 0.8s ease;
        transform: scale(0.5);
        z-index: -1;
    }
    .pricing-card:hover::before {
        opacity: 1;
        transform: scale(1);
    }
    .pricing-card:hover {
        transform: translateY(-16px) scale(1.02);
        box-shadow: 0 40px 80px rgba(236,72,153,0.12) !important;
        border-color: rgba(236,72,153,0.15);
        z-index: 10;
    }
    .pricing-card:hover .pricing-icon-wrap {
        transform: scale(1.1) rotate(-5deg);
        box-shadow: 0 12px 40px rgba(236,72,153,0.2);
    }

    .pricing-card.popular {
        border: 2px solid #EC4899;
        box-shadow: 0 8px 50px rgba(236,72,153,0.15);
        transform: scale(1.04);
        background: linear-gradient(145deg, #fff, #FFF5F8);
        overflow: visible !important;
        z-index: 2;
    }
    .pricing-card.popular:hover {
        transform: translateY(-16px) scale(1.05);
        box-shadow: 0 40px 80px rgba(236,72,153,0.2) !important;
        z-index: 10;
    }

    .pricing-icon-wrap {
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        width: 72px;
        height: 72px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        position: relative;
    }
    .pricing-icon-wrap::after {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 50%;
        padding: 2px;
        background: linear-gradient(135deg, rgba(236,72,153,0.2), transparent);
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
    }

    /* ============================================================ */
    /* POPULAR BADGE - FULLY VISIBLE */
    /* ============================================================ */
    .popular-badge {
        position: absolute;
        top: -16px;
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, #F472B6, #DB2777);
        color: #fff;
        padding: 8px 32px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 1px;
        box-shadow: 0 6px 35px rgba(219,39,119,0.5);
        animation: glowPulse 2.5s ease-in-out infinite;
        white-space: nowrap;
        z-index: 999;
        border: 2px solid rgba(255,255,255,0.2);
        text-transform: uppercase;
    }
    .popular-badge i {
        color: #FFD700;
        margin-right: 6px;
    }

    .pricing-btn {
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 16px 36px;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        width: 100%;
        border: none;
        cursor: pointer;
        font-size: 15px;
        margin-top: auto;
    }
    .pricing-btn::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255,255,255,0.15), transparent);
        transform: translateX(-100%) rotate(45deg);
        transition: transform 0.8s ease;
    }
    .pricing-btn:hover::after {
        transform: translateX(100%) rotate(45deg);
    }
    .pricing-btn:hover {
        transform: translateY(-3px) scale(1.01);
    }

    .pricing-feature {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid rgba(236,72,153,0.05);
        transition: all 0.3s ease;
    }
    .pricing-feature:hover {
        transform: translateX(6px);
    }
    .pricing-feature:last-child {
        border-bottom: none;
    }

    .price-amount {
        font-size: clamp(2.5rem, 4.5vw, 3.8rem);
        font-weight: 900;
        color: #1a0a14;
        letter-spacing: -1px;
    }
    .price-currency {
        font-size: clamp(1rem, 1.5vw, 1.4rem);
        font-weight: 700;
        color: #EC4899;
        vertical-align: super;
    }

    .gradient-text {
        background: linear-gradient(135deg, #F472B6, #EC4899, #DB2777);
        background-size: 200% 200%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: gradientMove 4s ease infinite;
    }

    .feature-check {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 10px;
        color: #fff;
    }

    /* ============================================================ */
    /* TOGGLE BUTTONS - FIXED Z-INDEX */
    /* ============================================================ */
    .toggle-container {
        position: relative;
        z-index: 20;
    }
    .toggle-btn {
        padding: 6px 24px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: 50px;
    }
    .toggle-btn.active {
        background: linear-gradient(135deg, #F472B6, #EC4899);
        color: #fff;
    }
    .toggle-btn.inactive {
        background: transparent;
        color: #999;
    }
    .toggle-btn.inactive:hover {
        color: #EC4899;
    }

    /* ============================================================ */
    /* FAQ */
    /* ============================================================ */
    .faq-item {
        background: #FFF9FC;
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 12px;
        border: 1px solid #FFE8F0;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .faq-item:hover {
        border-color: #EC4899;
        transform: translateX(4px);
        box-shadow: 0 4px 20px rgba(236,72,153,0.06);
    }
    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.5s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.4s ease, padding 0.4s ease;
        opacity: 0;
        padding: 0 0 0 32px;
    }
    .faq-answer.open {
        max-height: 300px;
        opacity: 1;
        padding: 12px 0 4px 32px;
    }
    .faq-icon {
        transition: transform 0.4s ease;
        color: #EC4899;
    }
    .faq-icon.rotated {
        transform: rotate(180deg);
    }

    /* ============================================================ */
    /* RESPONSIVE */
    /* ============================================================ */
    @media (max-width: 991px) {
        .pricing-card.popular {
            transform: scale(1);
        }
        .pricing-card.popular:hover {
            transform: translateY(-12px) scale(1.01);
        }
        .pricing-card {
            padding: 28px 24px !important;
        }
        .popular-badge {
            font-size: 11px;
            padding: 6px 20px;
            top: -14px;
        }
    }
    @media (max-width: 768px) {
        .pricing-card {
            padding: 24px 20px !important;
        }
        .price-amount {
            font-size: 2.2rem;
        }
        .pricing-card.popular {
            order: -1;
        }
        .popular-badge {
            font-size: 10px;
            padding: 5px 16px;
            top: -12px;
        }
        .toggle-btn {
            padding: 4px 14px;
            font-size: 11px;
        }
    }
    @media (max-width: 576px) {
        .pricing-card {
            padding: 20px 16px !important;
        }
        .popular-badge {
            font-size: 9px;
            padding: 4px 14px;
            top: -12px;
        }
        .pricing-btn {
            padding: 14px 20px;
            font-size: 14px;
        }
        .toggle-btn {
            padding: 3px 10px;
            font-size: 10px;
        }
        .toggle-btn span {
            font-size: 8px;
            padding: 1px 6px;
        }
    }
</style>

<!-- ============================================================ -->
<!-- HERO SECTION -->
<!-- ============================================================ -->
<section style="background: linear-gradient(135deg, #0a0508 0%, #1a0a14 30%, #3d1a2e 60%, #6b2147 100%); padding: 100px 0 70px; position: relative; overflow: hidden;">
    
    <div class="animate-float" style="position: absolute; top: -30%; right: -10%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(244,114,182,0.08) 0%, transparent 70%); border-radius: 50%;"></div>
    <div class="animate-float-reverse" style="position: absolute; bottom: -20%; left: -10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(244,114,182,0.05) 0%, transparent 70%); border-radius: 50%;"></div>
    <div class="animate-rotate" style="position: absolute; top: 15%; left: 20%; width: 200px; height: 200px; border: 1px solid rgba(244,114,182,0.05); border-radius: 50%;"></div>
    <div class="animate-rotate" style="position: absolute; bottom: 20%; right: 15%; width: 120px; height: 120px; border: 1px solid rgba(244,114,182,0.03); border-radius: 50%; animation-duration: 30s;"></div>
    
    <div class="container" style="position: relative; z-index: 1;">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="animate-slide-up" style="display: inline-block; background: rgba(244,114,182,0.12); color: #F472B6; padding: 8px 28px; border-radius: 50px; font-size: 13px; font-weight: 600; letter-spacing: 1.5px; margin-bottom: 24px; border: 1px solid rgba(244,114,182,0.2); backdrop-filter: blur(10px);">
                    <i class="fas fa-tag me-2"></i> Simple & Transparent Pricing
                </div>
                <h1 class="animate-slide-up delay-1" style="font-size: clamp(2.2rem, 5.5vw, 3.8rem); font-weight: 900; color: #fff; margin-bottom: 16px; line-height: 1.15;">
                    Choose Your <span class="gradient-text">Perfect Plan</span>
                </h1>
                <p class="animate-slide-up delay-2" style="font-size: clamp(1rem, 1.5vw, 1.2rem); color: rgba(255,255,255,0.75); max-width: 580px; margin: 0 auto; line-height: 1.9;">
                    No hidden fees. No surprises. Pick the plan that fits your salon's needs and start growing your business today.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- PRICING SECTION -->
<!-- ============================================================ -->
<section style="padding: 60px 0 80px; background: #FFF9FC;">
    <div class="container">

        @php
            $totalSalons = \App\Models\Salon::where('status', 'approved')->count() ?: 487;
            
            $monthlyPlans = [
                [
                    'name' => 'Starter',
                    'subtitle' => 'Perfect for new salons',
                    'price' => '0',
                    'period' => '/month',
                    'icon' => 'fa-seedling',
                    'color' => '#8B5CF6',
                    'bg' => 'rgba(139,92,246,0.08)',
                    'popular' => false,
                    'features' => [
                        ['icon' => 'fa-check', 'text' => 'List your salon on our platform'],
                        ['icon' => 'fa-check', 'text' => 'Basic salon profile page'],
                        ['icon' => 'fa-check', 'text' => 'Receive unlimited bookings'],
                        ['icon' => 'fa-check', 'text' => 'Email support (48hr response)'],
                        ['icon' => 'fa-check', 'text' => 'Basic analytics dashboard'],
                        ['icon' => 'fa-check', 'text' => 'Free for 30 days'],
                    ],
                    'btn_text' => 'Start Free Trial',
                    'btn_link' => route('register.owner'),
                    'btn_bg' => 'linear-gradient(135deg, #8B5CF6, #6D28D9)'
                ],
                [
                    'name' => 'Professional',
                    'subtitle' => 'Most popular choice',
                    'price' => '2,999',
                    'period' => '/month',
                    'icon' => 'fa-crown',
                    'color' => '#EC4899',
                    'bg' => 'rgba(236,72,153,0.08)',
                    'popular' => true,
                    'features' => [
                        ['icon' => 'fa-check', 'text' => 'Everything in Starter'],
                        ['icon' => 'fa-star', 'text' => 'Premium salon listing (top results)'],
                        ['icon' => 'fa-check', 'text' => 'Advanced analytics & insights'],
                        ['icon' => 'fa-check', 'text' => 'Dedicated account manager'],
                        ['icon' => 'fa-check', 'text' => 'Priority email & phone support'],
                        ['icon' => 'fa-check', 'text' => 'Promotional tools & campaigns'],
                        ['icon' => 'fa-check', 'text' => 'WhatsApp & SMS notifications'],
                        ['icon' => 'fa-check', 'text' => 'Unlimited service management'],
                    ],
                    'btn_text' => 'Get Started',
                    'btn_link' => route('partner') . '#partner-form',
                    'btn_bg' => 'linear-gradient(135deg, #F472B6, #DB2777)'
                ],
                [
                    'name' => 'Premium',
                    'subtitle' => 'For enterprise salons',
                    'price' => '5,999',
                    'period' => '/month',
                    'icon' => 'fa-gem',
                    'color' => '#F59E0B',
                    'bg' => 'rgba(245,158,11,0.08)',
                    'popular' => false,
                    'features' => [
                        ['icon' => 'fa-check', 'text' => 'Everything in Professional'],
                        ['icon' => 'fa-check', 'text' => 'Multi-city salon listing'],
                        ['icon' => 'fa-check', 'text' => 'Exclusive marketing campaigns'],
                        ['icon' => 'fa-check', 'text' => 'Custom branding & design'],
                        ['icon' => 'fa-check', 'text' => 'Advanced API access'],
                        ['icon' => 'fa-check', 'text' => 'Priority support (24/7)'],
                        ['icon' => 'fa-check', 'text' => 'AI-powered business recommendations'],
                        ['icon' => 'fa-check', 'text' => 'Detailed monthly performance reports'],
                        ['icon' => 'fa-check', 'text' => 'Dedicated team for your growth'],
                    ],
                    'btn_text' => 'Get Started',
                    'btn_link' => route('partner') . '#partner-form',
                    'btn_bg' => 'linear-gradient(135deg, #F59E0B, #D97706)'
                ]
            ];

            $yearlyPlans = [
                [
                    'name' => 'Starter',
                    'subtitle' => 'Perfect for new salons',
                    'price' => '0',
                    'period' => '/year',
                    'icon' => 'fa-seedling',
                    'color' => '#8B5CF6',
                    'bg' => 'rgba(139,92,246,0.08)',
                    'popular' => false,
                    'features' => [
                        ['icon' => 'fa-check', 'text' => 'List your salon on our platform'],
                        ['icon' => 'fa-check', 'text' => 'Basic salon profile page'],
                        ['icon' => 'fa-check', 'text' => 'Receive unlimited bookings'],
                        ['icon' => 'fa-check', 'text' => 'Email support (48hr response)'],
                        ['icon' => 'fa-check', 'text' => 'Basic analytics dashboard'],
                        ['icon' => 'fa-check', 'text' => 'Free for 30 days'],
                    ],
                    'btn_text' => 'Start Free Trial',
                    'btn_link' => route('register.owner'),
                    'btn_bg' => 'linear-gradient(135deg, #8B5CF6, #6D28D9)'
                ],
                [
                    'name' => 'Professional',
                    'subtitle' => 'Most popular choice',
                    'price' => '28,790',
                    'period' => '/year',
                    'icon' => 'fa-crown',
                    'color' => '#EC4899',
                    'bg' => 'rgba(236,72,153,0.08)',
                    'popular' => true,
                    'features' => [
                        ['icon' => 'fa-check', 'text' => 'Everything in Starter'],
                        ['icon' => 'fa-star', 'text' => 'Premium salon listing (top results)'],
                        ['icon' => 'fa-check', 'text' => 'Advanced analytics & insights'],
                        ['icon' => 'fa-check', 'text' => 'Dedicated account manager'],
                        ['icon' => 'fa-check', 'text' => 'Priority email & phone support'],
                        ['icon' => 'fa-check', 'text' => 'Promotional tools & campaigns'],
                        ['icon' => 'fa-check', 'text' => 'WhatsApp & SMS notifications'],
                        ['icon' => 'fa-check', 'text' => 'Unlimited service management'],
                        ['icon' => 'fa-gift', 'text' => '🎁 2 Months Free (Save 20%)'],
                    ],
                    'btn_text' => 'Get Started',
                    'btn_link' => route('partner') . '#partner-form',
                    'btn_bg' => 'linear-gradient(135deg, #F472B6, #DB2777)'
                ],
                [
                    'name' => 'Premium',
                    'subtitle' => 'For enterprise salons',
                    'price' => '57,590',
                    'period' => '/year',
                    'icon' => 'fa-gem',
                    'color' => '#F59E0B',
                    'bg' => 'rgba(245,158,11,0.08)',
                    'popular' => false,
                    'features' => [
                        ['icon' => 'fa-check', 'text' => 'Everything in Professional'],
                        ['icon' => 'fa-check', 'text' => 'Multi-city salon listing'],
                        ['icon' => 'fa-check', 'text' => 'Exclusive marketing campaigns'],
                        ['icon' => 'fa-check', 'text' => 'Custom branding & design'],
                        ['icon' => 'fa-check', 'text' => 'Advanced API access'],
                        ['icon' => 'fa-check', 'text' => 'Priority support (24/7)'],
                        ['icon' => 'fa-check', 'text' => 'AI-powered business recommendations'],
                        ['icon' => 'fa-check', 'text' => 'Detailed monthly performance reports'],
                        ['icon' => 'fa-check', 'text' => 'Dedicated team for your growth'],
                        ['icon' => 'fa-gift', 'text' => '🎁 2 Months Free (Save 20%)'],
                    ],
                    'btn_text' => 'Get Started',
                    'btn_link' => route('partner') . '#partner-form',
                    'btn_bg' => 'linear-gradient(135deg, #F59E0B, #D97706)'
                ]
            ];
        @endphp

        <!-- Toggle / Switch - FIXED Z-INDEX -->
        <div class="row justify-content-center mb-5 animate-slide-up toggle-container">
            <div class="col-auto">
                <div style="background: #fff; border-radius: 50px; padding: 4px; border: 1px solid #FFE8F0; display: inline-flex; box-shadow: 0 2px 15px rgba(0,0,0,0.04);">
                    <span class="toggle-btn active" id="monthlyToggle" onclick="switchPricing('monthly')">Monthly</span>
                    <span class="toggle-btn inactive" id="yearlyToggle" onclick="switchPricing('yearly')">Annual <span style="color: #10B981; font-size: 10px; background: rgba(16,185,129,0.1); padding: 2px 10px; border-radius: 20px; margin-left: 4px;">Save 20%</span></span>
                </div>
            </div>
        </div>

        <!-- Monthly Plans -->
        <div id="monthlyPlans" class="row g-4 align-items-stretch">
            @foreach($monthlyPlans as $plan)
                <div class="col-lg-4 animate-slide-up" style="animation-delay: {{ 0.1 + $loop->index * 0.1 }}s;">
                    <div class="pricing-card {{ $plan['popular'] ? 'popular' : '' }}">
                        
                        @if($plan['popular'])
                            <div class="popular-badge">
                                <i class="fas fa-star"></i> MOST POPULAR
                            </div>
                        @endif
                        
                        <div style="text-align: center; margin-bottom: 28px;">
                            <div class="pricing-icon-wrap" style="background: {{ $plan['bg'] }};">
                                <i class="fas {{ $plan['icon'] }}" style="font-size: 30px; color: {{ $plan['color'] }};"></i>
                            </div>
                            <h3 style="font-size: 22px; font-weight: 800; color: #1a0a14; margin-bottom: 2px;">{{ $plan['name'] }}</h3>
                            <p style="font-size: 14px; color: #999; margin: 0;">{{ $plan['subtitle'] }}</p>
                        </div>
                        
                        <div style="text-align: center; margin-bottom: 28px; border-bottom: 1px solid rgba(236,72,153,0.06); padding-bottom: 24px;">
                            @if($plan['price'] == '0')
                                <span style="font-size: 20px; font-weight: 700; color: #10B981; background: rgba(16,185,129,0.1); padding: 4px 20px; border-radius: 50px;">
                                    <i class="fas fa-gift me-1"></i> FREE
                                </span>
                            @else
                                <span class="price-currency animate-price">PKR</span>
                                <span class="price-amount animate-price">{{ $plan['price'] }}</span>
                                <span style="font-size: 14px; color: #999;">{{ $plan['period'] }}</span>
                                <div style="font-size: 13px; color: #10B981; font-weight: 600; margin-top: 6px;">
                                    <i class="fas fa-check-circle"></i> Billed monthly
                                </div>
                            @endif
                        </div>
                        
                        <div style="flex: 1; margin-bottom: 28px;">
                            @foreach($plan['features'] as $feature)
                                <div class="pricing-feature">
                                    <span class="feature-check" style="background: {{ $plan['color'] }};">
                                        <i class="fas {{ $feature['icon'] }}" style="font-size: 10px;"></i>
                                    </span>
                                    <span style="font-size: 14px; color: #555;">{{ $feature['text'] }}</span>
                                </div>
                            @endforeach
                        </div>
                        
                        <a href="{{ $plan['btn_link'] }}" 
                           class="pricing-btn" 
                           style="background: {{ $plan['btn_bg'] }}; 
                                  color: #fff; 
                                  box-shadow: 0 8px 30px {{ $plan['color'] }}40;">
                            <i class="fas {{ $plan['price'] == '0' ? 'fa-rocket' : 'fa-arrow-right' }}"></i> 
                            {{ $plan['btn_text'] }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Yearly Plans (Hidden by default) -->
        <div id="yearlyPlans" class="row g-4 align-items-stretch" style="display: none;">
            @foreach($yearlyPlans as $plan)
                <div class="col-lg-4 animate-slide-up" style="animation-delay: {{ 0.1 + $loop->index * 0.1 }}s;">
                    <div class="pricing-card {{ $plan['popular'] ? 'popular' : '' }}">
                        
                        @if($plan['popular'])
                            <div class="popular-badge">
                                <i class="fas fa-star"></i> MOST POPULAR
                            </div>
                        @endif
                        
                        <div style="text-align: center; margin-bottom: 28px;">
                            <div class="pricing-icon-wrap" style="background: {{ $plan['bg'] }};">
                                <i class="fas {{ $plan['icon'] }}" style="font-size: 30px; color: {{ $plan['color'] }};"></i>
                            </div>
                            <h3 style="font-size: 22px; font-weight: 800; color: #1a0a14; margin-bottom: 2px;">{{ $plan['name'] }}</h3>
                            <p style="font-size: 14px; color: #999; margin: 0;">{{ $plan['subtitle'] }}</p>
                        </div>
                        
                        <div style="text-align: center; margin-bottom: 28px; border-bottom: 1px solid rgba(236,72,153,0.06); padding-bottom: 24px;">
                            @if($plan['price'] == '0')
                                <span style="font-size: 20px; font-weight: 700; color: #10B981; background: rgba(16,185,129,0.1); padding: 4px 20px; border-radius: 50px;">
                                    <i class="fas fa-gift me-1"></i> FREE
                                </span>
                            @else
                                <span class="price-currency animate-price">PKR</span>
                                <span class="price-amount animate-price">{{ $plan['price'] }}</span>
                                <span style="font-size: 14px; color: #999;">{{ $plan['period'] }}</span>
                                <div style="font-size: 13px; color: #F59E0B; font-weight: 600; margin-top: 6px;">
                                    <i class="fas fa-gift"></i> Save 20% annually
                                </div>
                            @endif
                        </div>
                        
                        <div style="flex: 1; margin-bottom: 28px;">
                            @foreach($plan['features'] as $feature)
                                <div class="pricing-feature">
                                    <span class="feature-check" style="background: {{ $plan['color'] }};">
                                        <i class="fas {{ $feature['icon'] }}" style="font-size: 10px;"></i>
                                    </span>
                                    <span style="font-size: 14px; color: #555;">{{ $feature['text'] }}</span>
                                </div>
                            @endforeach
                        </div>
                        
                        <a href="{{ $plan['btn_link'] }}" 
                           class="pricing-btn" 
                           style="background: {{ $plan['btn_bg'] }}; 
                                  color: #fff; 
                                  box-shadow: 0 8px 30px {{ $plan['color'] }}40;">
                            <i class="fas {{ $plan['price'] == '0' ? 'fa-rocket' : 'fa-arrow-right' }}"></i> 
                            {{ $plan['btn_text'] }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Trust Badges -->
        <div class="row justify-content-center mt-5 animate-slide-up delay-4">
            <div class="col-lg-10">
                <div style="background: #fff; border-radius: 24px; padding: 32px 20px; border: 1px solid #FFE8F0; text-align: center; box-shadow: 0 2px 20px rgba(0,0,0,0.03);">
                    <p style="color: #999; font-size: 13px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 20px;">Trusted by salon owners across Pakistan</p>
                    <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 40px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 40px; height: 40px; background: rgba(236,72,153,0.08); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-store" style="color: #EC4899; font-size: 18px;"></i>
                            </div>
                            <div style="text-align: left;">
                                <div style="font-weight: 800; color: #1a0a14; font-size: 18px;">{{ $totalSalons }}+</div>
                                <div style="color: #999; font-size: 12px;">Partner Salons</div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 40px; height: 40px; background: rgba(245,158,11,0.08); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-star" style="color: #F59E0B; font-size: 18px;"></i>
                            </div>
                            <div style="text-align: left;">
                                <div style="font-weight: 800; color: #1a0a14; font-size: 18px;">4.8/5</div>
                                <div style="color: #999; font-size: 12px;">Average Rating</div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 40px; height: 40px; background: rgba(16,185,129,0.08); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-headset" style="color: #10B981; font-size: 18px;"></i>
                            </div>
                            <div style="text-align: left;">
                                <div style="font-weight: 800; color: #1a0a14; font-size: 18px;">24/7</div>
                                <div style="color: #999; font-size: 12px;">Customer Support</div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 40px; height: 40px; background: rgba(59,130,246,0.08); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-lock" style="color: #3B82F6; font-size: 18px;"></i>
                            </div>
                            <div style="text-align: left;">
                                <div style="font-weight: 800; color: #1a0a14; font-size: 18px;">100%</div>
                                <div style="color: #999; font-size: 12px;">Secure Payments</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- FAQ SECTION -->
<!-- ============================================================ -->
<section style="padding: 70px 0; background: #fff;">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto animate-slide-up">
                <span style="color: #EC4899; font-weight: 600; font-size: 14px; letter-spacing: 2px; text-transform: uppercase; display: block; margin-bottom: 8px;">Common Questions</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.6rem); font-weight: 800; color: #1a0a14;">Pricing <span style="color: #EC4899;">FAQs</span></h2>
                <p style="color: #888; font-size: 15px; margin-top: 12px;">Everything you need to know about our pricing plans</p>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @php
                    $faqs = [
                        ['q'=>'What payment methods do you accept?', 'a'=>'We accept all major credit/debit cards, bank transfers, Easypaisa, and JazzCash. All payments are processed securely through our integrated payment gateway with 256-bit encryption.'],
                        ['q'=>'Can I switch plans later?', 'a'=>'Absolutely! You can upgrade or downgrade your plan at any time from your salon dashboard. The changes will take effect from your next billing cycle with no penalty fees.'],
                        ['q'=>'Is there a long-term contract?', 'a'=>'No, we believe in flexibility. All our plans are month-to-month with no long-term commitments. You can cancel anytime with just one click.'],
                        ['q'=>'Do you offer discounts for annual billing?', 'a'=>'Yes! We offer up to 20% discount on annual billing. This means you get 2 months free when you pay for a full year in advance.'],
                        ['q'=>'What happens if I need more features later?', 'a'=>'You can easily upgrade to a higher plan as your business grows. Our team will help you migrate seamlessly without any disruption to your bookings.'],
                    ];
                @endphp
                @foreach($faqs as $faq)
                    <div class="faq-item" onclick="toggleFaq(this)">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <h4 style="font-size: 16px; font-weight: 700; color: #1a0a14; margin: 0; display: flex; align-items: center; gap: 12px;">
                                <span style="color: #EC4899; font-size: 18px;">❓</span> {{ $faq['q'] }}
                            </h4>
                            <span class="faq-icon">
                                <i class="fas fa-chevron-down"></i>
                            </span>
                        </div>
                        <div class="faq-answer">
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
    <div class="animate-float" style="position: absolute; top: -40%; right: -20%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(244,114,182,0.06) 0%, transparent 70%); border-radius: 50%;"></div>
    <div class="animate-float-reverse" style="position: absolute; bottom: -30%; left: -15%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(244,114,182,0.04) 0%, transparent 70%); border-radius: 50%;"></div>
    
    <div class="container" style="position: relative; z-index: 1;">
        <div class="animate-slide-up">
            <h2 style="font-size: clamp(1.8rem, 3.5vw, 2.6rem); font-weight: 800; color: #fff; margin-bottom: 12px;">
                Ready to <span class="gradient-text">Grow Your Salon</span> Business?
            </h2>
            <p style="color: rgba(255,255,255,0.7); font-size: 16px; max-width: 500px; margin: 0 auto 30px; line-height: 1.8;">
                Join <strong style="color: #F472B6;">{{ $totalSalons }}+</strong> salons already growing with us. Start your free trial today!
            </p>
            <a href="{{ route('register.owner') }}" style="background: linear-gradient(135deg, #F472B6, #DB2777); color: #fff; padding: 18px 48px; border-radius: 50px; font-weight: 700; font-size: 16px; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; box-shadow: 0 8px 35px rgba(219,39,119,0.35); transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);" onmouseover="this.style.transform='translateY(-4px) scale(1.02)'; this.style.boxShadow='0 16px 60px rgba(219,39,119,0.5)';" onmouseout="this.style.transform=''; this.style.boxShadow='0 8px 35px rgba(219,39,119,0.35)';">
                <i class="fas fa-rocket"></i> Start Free Trial
            </a>
        </div>
    </div>
</section>

<script>
    // ============================================================
    // PRICING TOGGLE - MONTHLY / YEARLY
    // ============================================================
    function switchPricing(type) {
        const monthlyToggle = document.getElementById('monthlyToggle');
        const yearlyToggle = document.getElementById('yearlyToggle');
        const monthlyPlans = document.getElementById('monthlyPlans');
        const yearlyPlans = document.getElementById('yearlyPlans');
        
        if (type === 'monthly') {
            monthlyToggle.className = 'toggle-btn active';
            yearlyToggle.className = 'toggle-btn inactive';
            monthlyPlans.style.display = 'flex';
            yearlyPlans.style.display = 'none';
        } else {
            yearlyToggle.className = 'toggle-btn active';
            monthlyToggle.className = 'toggle-btn inactive';
            monthlyPlans.style.display = 'none';
            yearlyPlans.style.display = 'flex';
        }
    }

    // ============================================================
    // FAQ TOGGLE
    // ============================================================
    function toggleFaq(element) {
        const parent = element;
        const answer = parent.querySelector('.faq-answer');
        const icon = parent.querySelector('.faq-icon');
        
        if (answer.classList.contains('open')) {
            answer.classList.remove('open');
            icon.classList.remove('rotated');
        } else {
            document.querySelectorAll('.faq-answer').forEach(a => {
                a.classList.remove('open');
                a.closest('.faq-item').querySelector('.faq-icon').classList.remove('rotated');
            });
            answer.classList.add('open');
            icon.classList.add('rotated');
        }
    }

    // ============================================================
    // SCROLL REVEAL
    // ============================================================
    document.addEventListener('DOMContentLoaded', function() {
        const elements = document.querySelectorAll('.animate-slide-up, .animate-scale');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });
        elements.forEach(el => observer.observe(el));
    });
</script>

@endsection