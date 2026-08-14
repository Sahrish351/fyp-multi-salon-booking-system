@extends('layouts.app')
@section('title', 'FAQ - Beauty Blush Salons')

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
    @keyframes scaleIn {
        0% { opacity: 0; transform: scale(0.8); }
        100% { opacity: 1; transform: scale(1); }
    }
    @keyframes glowPulse {
        0%, 100% { box-shadow: 0 0 20px rgba(236,72,153,0.3); }
        50% { box-shadow: 0 0 60px rgba(236,72,153,0.6); }
    }
    @keyframes rotateSlow {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    @keyframes bounceIn {
        0% { opacity: 0; transform: scale(0.3); }
        50% { opacity: 1; transform: scale(1.05); }
        70% { transform: scale(0.95); }
        100% { transform: scale(1); }
    }

    .animate-float { animation: float 8s ease-in-out infinite; }
    .animate-float-reverse { animation: floatReverse 10s ease-in-out infinite; }
    .animate-slide-up { animation: slideUp 0.8s ease forwards; }
    .animate-scale { animation: scaleIn 0.8s ease forwards; }
    .animate-glow { animation: glowPulse 3s ease-in-out infinite; }
    .animate-rotate { animation: rotateSlow 20s linear infinite; }

    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }
    .delay-5 { animation-delay: 0.5s; }

    /* ============================================================ */
    /* CATEGORY CARDS */
    /* ============================================================ */
    .category-card {
        flex: 0 0 auto;
        width: 200px;
        padding: 18px 16px;
        border-radius: 16px;
        text-align: center;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 2px solid transparent;
        background: #fff;
        box-shadow: 0 2px 15px rgba(0,0,0,0.04);
        position: relative;
        overflow: hidden;
        scroll-snap-align: start;
    }
    .category-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(236,72,153,0.04) 0%, transparent 70%);
        opacity: 0;
        transition: all 0.6s ease;
        transform: scale(0.5);
    }
    .category-card:hover::before {
        opacity: 1;
        transform: scale(1);
    }
    .category-card:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 15px 45px rgba(236,72,153,0.10);
    }
    .category-card.active {
        border-color: #EC4899;
        background: linear-gradient(135deg, #FFF5F8, #fff);
        box-shadow: 0 8px 35px rgba(236,72,153,0.12);
        transform: translateY(-4px);
    }
    .category-card.active .category-icon {
        background: linear-gradient(135deg, #F472B6, #EC4899);
        color: #fff;
        transform: scale(1.1);
    }
    .category-card.active .category-name {
        color: #EC4899;
    }

    .category-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-size: 20px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .category-card:hover .category-icon {
        transform: scale(1.05) rotate(-5deg);
    }

    .category-name {
        font-size: 13px;
        font-weight: 700;
        color: #1a0a14;
        transition: all 0.3s ease;
        line-height: 1.3;
    }
    .category-count {
        font-size: 11px;
        color: #999;
        margin-top: 2px;
    }

    .category-scroll {
        display: flex;
        gap: 16px;
        overflow-x: auto;
        padding: 10px 4px 20px;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: #EC4899 #f0f0f0;
    }
    .category-scroll::-webkit-scrollbar {
        height: 6px;
    }
    .category-scroll::-webkit-scrollbar-track {
        background: #f0f0f0;
        border-radius: 10px;
    }
    .category-scroll::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #F472B6, #EC4899);
        border-radius: 10px;
    }

    /* ============================================================ */
    /* FAQ ITEMS */
    /* ============================================================ */
    .faq-item {
        background: #fff;
        border-radius: 16px;
        margin-bottom: 12px;
        border: 1px solid rgba(236,72,153,0.08);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .faq-item:hover {
        border-color: rgba(236,72,153,0.2);
        box-shadow: 0 8px 30px rgba(236,72,153,0.06);
    }
    .faq-item.active {
        border-color: #EC4899;
        box-shadow: 0 8px 40px rgba(236,72,153,0.10);
    }

    .faq-question {
        padding: 18px 24px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s ease;
        gap: 16px;
    }
    .faq-question:hover .faq-icon-wrap {
        transform: scale(1.1);
    }
    .faq-question .faq-number {
        font-size: 13px;
        font-weight: 800;
        color: #EC4899;
        min-width: 28px;
        opacity: 0.5;
    }
    .faq-question h4 {
        font-size: 15px;
        font-weight: 700;
        color: #1a0a14;
        margin: 0;
        flex: 1;
        line-height: 1.5;
    }
    .faq-icon-wrap {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(236,72,153,0.06);
        color: #EC4899;
        font-size: 13px;
        flex-shrink: 0;
    }
    .faq-icon-wrap.rotated {
        transform: rotate(180deg);
        background: linear-gradient(135deg, #F472B6, #EC4899);
        color: #fff;
    }

    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.6s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.5s ease, padding 0.5s ease;
        opacity: 0;
        padding: 0 24px;
    }
    .faq-answer.open {
        max-height: 500px;
        opacity: 1;
        padding: 0 24px 20px;
    }
    .faq-answer p {
        font-size: 15px;
        color: #666;
        line-height: 1.8;
        margin: 0;
        padding-top: 4px;
    }
    .faq-answer .answer-icon {
        color: #10B981;
        margin-right: 8px;
    }

    .gradient-text {
        background: linear-gradient(135deg, #F472B6, #EC4899, #DB2777);
        background-size: 200% 200%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: gradientMove 4s ease infinite;
    }

    .faq-category-section {
        display: block;
    }
    .faq-category-section.hidden {
        display: none;
    }

    .faq-category-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 40px 0 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid rgba(236,72,153,0.08);
    }
    .faq-category-title .title-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: #fff;
        flex-shrink: 0;
    }

    /* ============================================================ */
    /* RESPONSIVE */
    /* ============================================================ */
    @media (max-width: 768px) {
        .category-card {
            width: 160px;
            padding: 14px 12px;
        }
        .category-icon {
            width: 40px;
            height: 40px;
            font-size: 17px;
        }
        .category-name {
            font-size: 12px;
        }
        .faq-question {
            padding: 14px 18px;
        }
        .faq-question h4 {
            font-size: 14px;
        }
        .faq-answer p {
            font-size: 14px;
        }
    }
    @media (max-width: 576px) {
        .category-card {
            width: 130px;
            padding: 12px 10px;
        }
        .category-icon {
            width: 36px;
            height: 36px;
            font-size: 14px;
        }
        .category-name {
            font-size: 11px;
        }
        .category-count {
            font-size: 10px;
        }
        .faq-question {
            padding: 12px 14px;
        }
        .faq-question h4 {
            font-size: 13px;
        }
        .faq-icon-wrap {
            width: 28px;
            height: 28px;
            font-size: 11px;
        }
        .faq-answer {
            padding: 0 14px;
        }
        .faq-answer.open {
            padding: 0 14px 14px;
        }
        .faq-answer p {
            font-size: 13px;
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
                    <i class="fas fa-question-circle me-2"></i> Got Questions?
                </div>
                <h1 class="animate-slide-up delay-1" style="font-size: clamp(2.2rem, 5.5vw, 3.8rem); font-weight: 900; color: #fff; margin-bottom: 16px; line-height: 1.15;">
                    Frequently Asked <span class="gradient-text">Questions</span>
                </h1>
                <p class="animate-slide-up delay-2" style="font-size: clamp(1rem, 1.5vw, 1.2rem); color: rgba(255,255,255,0.75); max-width: 580px; margin: 0 auto; line-height: 1.9;">
                    Find answers to the most common questions about Beauty Blush Salons. Click a category below to filter questions.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- CATEGORY CARDS -->
<!-- ============================================================ -->
<section style="padding: 30px 0 10px; background: #FFF9FC;">
    <div class="container">
        <div class="animate-slide-up">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                <span style="font-size: 14px; font-weight: 600; color: #888; letter-spacing: 1px; text-transform: uppercase;">
                    <i class="fas fa-grid-2" style="color: #EC4899; margin-right: 8px;"></i> Browse by Category
                </span>
                <span style="font-size: 12px; color: #aaa; display: flex; align-items: center; gap: 4px;">
                    <i class="fas fa-arrow-right"></i> Scroll
                </span>
            </div>
            
            <div class="category-scroll">
                @php
                    $categories = [
                        ['icon' => 'fa-info-circle', 'color' => '#8B5CF6', 'bg' => 'rgba(139,92,246,0.1)', 'name' => 'General', 'count' => 4, 'id' => 'general'],
                        ['icon' => 'fa-calendar-check', 'color' => '#EC4899', 'bg' => 'rgba(236,72,153,0.1)', 'name' => 'Bookings', 'count' => 4, 'id' => 'bookings'],
                        ['icon' => 'fa-wallet', 'color' => '#10B981', 'bg' => 'rgba(16,185,129,0.1)', 'name' => 'Payments', 'count' => 4, 'id' => 'payments'],
                        ['icon' => 'fa-store', 'color' => '#F59E0B', 'bg' => 'rgba(245,158,11,0.1)', 'name' => 'Salon Owners', 'count' => 4, 'id' => 'owners'],
                        ['icon' => 'fa-headset', 'color' => '#3B82F6', 'bg' => 'rgba(59,130,246,0.1)', 'name' => 'Technical', 'count' => 4, 'id' => 'technical'],
                    ];
                @endphp
                @foreach($categories as $cat)
                    <div class="category-card active" data-category="{{ $cat['id'] }}" onclick="filterCategory('{{ $cat['id'] }}', this)">
                        <div class="category-icon" style="background: {{ $cat['bg'] }}; color: {{ $cat['color'] }};">
                            <i class="fas {{ $cat['icon'] }}"></i>
                        </div>
                        <div class="category-name">{{ $cat['name'] }}</div>
                        <div class="category-count">{{ $cat['count'] }} questions</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- ALL FAQ SECTIONS -->
<!-- ============================================================ -->
<section style="padding: 20px 0 70px; background: #fff;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                @php
                    $faqData = [
                        'general' => [
                            'icon' => 'fa-info-circle',
                            'color' => '#8B5CF6',
                            'bg' => 'rgba(139,92,246,0.1)',
                            'title' => 'General',
                            'questions' => [
                                ['q' => 'What is Beauty Blush Salons?', 'a' => 'Beauty Blush Salons is Pakistan\'s premier multi-salon booking platform. We connect beauty enthusiasts with verified salons across major cities, making it easy to discover and book premium beauty services.'],
                                ['q' => 'Is it free to use Beauty Blush Salons?', 'a' => 'Yes! Booking appointments is completely free for clients. We believe in making beauty services accessible to everyone. You only pay for the services you book.'],
                                ['q' => 'How do I book an appointment?', 'a' => 'Simply search for salons, browse their services, select your preferred time slot, and confirm your booking. The entire process takes less than 60 seconds!'],
                                ['q' => 'Do I need to create an account to book?', 'a' => 'Yes, you need to create a free account to book appointments. This helps us keep track of your bookings, send you reminders, and provide personalized recommendations.'],
                            ]
                        ],
                        'bookings' => [
                            'icon' => 'fa-calendar-check',
                            'color' => '#EC4899',
                            'bg' => 'rgba(236,72,153,0.1)',
                            'title' => 'Bookings & Appointments',
                            'questions' => [
                                ['q' => 'How do I cancel or reschedule a booking?', 'a' => 'You can cancel or reschedule your booking from your account dashboard up to 24 hours before the appointment time. Late cancellations may incur a fee as per the salon\'s policy.'],
                                ['q' => 'Can I book multiple services at once?', 'a' => 'Absolutely! You can select multiple services while booking. The total price will be calculated automatically based on your selections.'],
                                ['q' => 'What happens if I\'m late to my appointment?', 'a' => 'We recommend arriving 5-10 minutes early. If you\'re running late, please contact the salon directly. The salon reserves the right to shorten or reschedule your appointment.'],
                                ['q' => 'Do you send appointment reminders?', 'a' => 'Yes! We send email and SMS reminders 24 hours before your appointment. You\'ll also receive a confirmation immediately after booking.'],
                            ]
                        ],
                        'payments' => [
                            'icon' => 'fa-wallet',
                            'color' => '#10B981',
                            'bg' => 'rgba(16,185,129,0.1)',
                            'title' => 'Payments & Pricing',
                            'questions' => [
                                ['q' => 'What payment methods do you accept?', 'a' => 'We accept all major credit/debit cards, bank transfers, Easypaisa, and JazzCash. All payments are processed securely through our integrated payment gateway with 256-bit encryption.'],
                                ['q' => 'Is my payment information secure?', 'a' => 'Yes! We use industry-standard SSL encryption and do not store your payment details on our servers. All transactions are processed by certified payment providers.'],
                                ['q' => 'Do you charge any hidden fees?', 'a' => 'No hidden fees! The price you see is the price you pay. Service prices are set by the salons and are clearly displayed before you book.'],
                                ['q' => 'Can I get a refund if I cancel?', 'a' => 'Refunds are processed based on the salon\'s cancellation policy. Typically, cancellations made 24+ hours in advance receive a full refund. Please check the salon\'s policy before booking.'],
                            ]
                        ],
                        'owners' => [
                            'icon' => 'fa-store',
                            'color' => '#F59E0B',
                            'bg' => 'rgba(245,158,11,0.1)',
                            'title' => 'Salon Owners',
                            'questions' => [
                                ['q' => 'How do I register my salon on Beauty Blush?', 'a' => 'Click on "List Your Salon" in the header, fill in your salon details, and submit your application. Our team will review and verify your salon within 24-48 hours.'],
                                ['q' => 'How much does it cost to partner with you?', 'a' => 'Partnering with Beauty Blush is completely free! We only charge a small commission on successful bookings. Check our Pricing page for detailed plan information.'],
                                ['q' => 'How do I manage my bookings?', 'a' => 'Once registered, you\'ll get access to our salon dashboard. From there you can manage all bookings, services, staff, and track your revenue in real-time.'],
                                ['q' => 'Can I customize my salon profile?', 'a' => 'Yes! You can add your logo, photos, service descriptions, pricing, and working hours. A complete profile helps attract more clients and build trust.'],
                            ]
                        ],
                        'technical' => [
                            'icon' => 'fa-headset',
                            'color' => '#3B82F6',
                            'bg' => 'rgba(59,130,246,0.1)',
                            'title' => 'Technical Support',
                            'questions' => [
                                ['q' => 'What should I do if I experience a technical issue?', 'a' => 'Please contact our support team immediately via live chat or email. Our technical experts are available 24/7 to help resolve any platform-related issues you encounter.'],
                                ['q' => 'Is the platform mobile-friendly?', 'a' => 'Yes! Beauty Blush is fully optimized for all devices — desktop, tablet, and mobile. You can book appointments, manage your salon, and chat with clients on the go.'],
                                ['q' => 'How do I reset my password?', 'a' => 'Click on "Forgot Password" on the login page. Enter your registered email address, and we\'ll send you a link to reset your password securely.'],
                                ['q' => 'What browsers are supported?', 'a' => 'We support all modern browsers including Chrome, Firefox, Safari, Edge, and Opera. For the best experience, we recommend using the latest version of your preferred browser.'],
                            ]
                        ],
                    ];
                @endphp

                <!-- All FAQ Categories -->
                @foreach($faqData as $key => $category)
                    <div id="category-{{ $key }}" class="faq-category-section">
                        <div class="faq-category-title">
                            <div class="title-icon" style="background: {{ $category['bg'] }}; color: {{ $category['color'] }};">
                                <i class="fas {{ $category['icon'] }}"></i>
                            </div>
                            <h3 style="font-size: 20px; font-weight: 800; color: #1a0a14; margin: 0;">{{ $category['title'] }}</h3>
                            <span style="margin-left: auto; font-size: 13px; color: #999;">{{ count($category['questions']) }} Questions</span>
                        </div>

                        @foreach($category['questions'] as $index => $faq)
                            <div class="faq-item animate-slide-up" style="animation-delay: {{ 0.1 + $index * 0.05 }}s;">
                                <div class="faq-question" onclick="toggleFaq(this)">
                                    <span class="faq-number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    <h4>{{ $faq['q'] }}</h4>
                                    <span class="faq-icon-wrap">
                                        <i class="fas fa-chevron-down"></i>
                                    </span>
                                </div>
                                <div class="faq-answer">
                                    <p>
                                        <i class="fas fa-arrow-right answer-icon"></i>
                                        {{ $faq['a'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- CTA SECTION -->
<!-- ============================================================ -->
<section style="background: linear-gradient(135deg, #0a0508 0%, #1a0a14 30%, #3d1a2e 60%, #6b2147 100%); padding: 60px 0; text-align: center; position: relative; overflow: hidden;">
    <div class="animate-float" style="position: absolute; top: -40%; right: -20%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(244,114,182,0.06) 0%, transparent 70%); border-radius: 50%;"></div>
    <div class="animate-float-reverse" style="position: absolute; bottom: -30%; left: -15%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(244,114,182,0.04) 0%, transparent 70%); border-radius: 50%;"></div>
    
    <div class="container" style="position: relative; z-index: 1;">
        <div class="animate-slide-up">
            <h2 style="font-size: clamp(1.8rem, 3.5vw, 2.6rem); font-weight: 800; color: #fff; margin-bottom: 12px;">
                Still Have <span class="gradient-text">Questions?</span>
            </h2>
            <p style="color: rgba(255,255,255,0.7); font-size: 16px; max-width: 500px; margin: 0 auto 28px; line-height: 1.8;">
                Our support team is ready to help. Reach out to us anytime — we're always happy to assist you.
            </p>
            <div style="display: flex; flex-wrap: wrap; gap: 16px; justify-content: center;">
                <a href="{{ route('support') }}" style="background: linear-gradient(135deg, #F472B6, #DB2777); color: #fff; padding: 16px 44px; border-radius: 50px; font-weight: 700; font-size: 15px; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; box-shadow: 0 8px 35px rgba(219,39,119,0.35); transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 14px 50px rgba(219,39,119,0.5)';" onmouseout="this.style.transform=''; this.style.boxShadow='0 8px 35px rgba(219,39,119,0.35)';">
                    <i class="fas fa-headset"></i> Contact Support
                </a>
                <a href="{{ route('home') }}" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.2); color: #fff; padding: 16px 36px; border-radius: 50px; font-weight: 600; font-size: 15px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.15)';" onmouseout="this.style.background='rgba(255,255,255,0.08)';">
                    <i class="fas fa-home"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</section>

<script>
    // ============================================================
    // CATEGORY FILTER
    // ============================================================
    function filterCategory(categoryId, element) {
        // Update active state on cards
        document.querySelectorAll('.category-card').forEach(card => {
            card.classList.remove('active');
        });
        element.classList.add('active');

        // Show/Hide FAQ sections
        document.querySelectorAll('.faq-category-section').forEach(section => {
            if (section.id === 'category-' + categoryId) {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        });

        // Scroll to the selected category
        const targetSection = document.getElementById('category-' + categoryId);
        if (targetSection) {
            setTimeout(() => {
                targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
        }

        // Close all open FAQs
        document.querySelectorAll('.faq-answer').forEach(answer => {
            answer.classList.remove('open');
        });
        document.querySelectorAll('.faq-icon-wrap').forEach(icon => {
            icon.classList.remove('rotated');
        });
        document.querySelectorAll('.faq-item').forEach(item => {
            item.classList.remove('active');
        });
    }

    // ============================================================
    // FAQ TOGGLE
    // ============================================================
    function toggleFaq(element) {
        const parent = element.closest('.faq-item');
        const answer = parent.querySelector('.faq-answer');
        const icon = parent.querySelector('.faq-icon-wrap');
        const isActive = parent.classList.contains('active');

        // Close other FAQs in same category
        const categorySection = parent.closest('.faq-category-section');
        if (categorySection) {
            categorySection.querySelectorAll('.faq-item').forEach(item => {
                if (item !== parent) {
                    item.classList.remove('active');
                    item.querySelector('.faq-answer').classList.remove('open');
                    item.querySelector('.faq-icon-wrap').classList.remove('rotated');
                }
            });
        }

        if (isActive) {
            parent.classList.remove('active');
            answer.classList.remove('open');
            icon.classList.remove('rotated');
        } else {
            parent.classList.add('active');
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