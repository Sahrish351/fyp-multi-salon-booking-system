@extends('layouts.app')

@section('title', 'Partner With Us - Beauty Blush Salons')

@section('content')

<style>
    /* ===== PARTNER PAGE STYLES ===== */
    .partner-hero {
        background: linear-gradient(135deg, #fce4ec 0%, #fdf5fb 50%, #fff 100%);
        padding: 100px 0 80px;
        text-align: center;
        border-bottom: 1px solid #f0e8ed;
        position: relative;
        overflow: hidden;
    }
    .partner-hero::before {
        content: '';
        position: absolute;
        width: 600px;
        height: 600px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(233,30,140,0.05), transparent 70%);
        top: -300px;
        right: -200px;
        pointer-events: none;
    }
    .partner-hero h1 {
        font-size: 3.2rem;
        font-weight: 900;
        color: #1a1a1a;
        margin-bottom: 16px;
        font-family: 'Playfair Display', serif;
    }
    .partner-hero h1 span {
        color: #E91E8C;
    }
    .partner-hero p {
        font-size: 1.1rem;
        color: #777;
        max-width: 600px;
        margin: 0 auto 30px;
        line-height: 1.8;
    }
    .partner-hero .btn-partner {
        background: linear-gradient(135deg, #E91E8C, #C9A96E);
        color: #fff;
        border: none;
        padding: 14px 44px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(233,30,140,0.3);
    }
    .partner-hero .btn-partner:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(233,30,140,0.4);
        color: #fff;
    }
    .partner-hero .btn-outline {
        background: transparent;
        border: 2px solid #1a1a1a;
        color: #1a1a1a;
        padding: 12px 36px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        margin-left: 12px;
    }
    .partner-hero .btn-outline:hover {
        background: #1a1a1a;
        color: #fff;
    }

    /* Stats Section */
    .partner-stats {
        padding: 60px 0;
        background: #fff;
        border-bottom: 1px solid #f0e8ed;
    }
    .partner-stats .stat-item {
        text-align: center;
        padding: 10px;
    }
    .partner-stats .stat-item .number {
        font-size: 3rem;
        font-weight: 900;
        color: #E91E8C;
        font-family: 'Playfair Display', serif;
    }
    .partner-stats .stat-item .label {
        font-size: 0.9rem;
        color: #888;
        margin-top: 4px;
    }

    /* One Platform Section */
    .partner-platform {
        padding: 80px 0;
        background: #fff;
        text-align: center;
    }
    .partner-platform h2 {
        font-size: 2.5rem;
        font-weight: 900;
        font-family: 'Playfair Display', serif;
        color: #1a1a1a;
        margin-bottom: 12px;
    }
    .partner-platform h2 span {
        color: #E91E8C;
    }
    .partner-platform p {
        color: #888;
        font-size: 1rem;
        max-width: 700px;
        margin: 0 auto 40px;
        line-height: 1.8;
    }

    /* Business Types Section */
    .partner-business-types {
        padding: 60px 0;
        background: #f8f5f7;
        border-bottom: 1px solid #f0e8ed;
    }
    .partner-business-types .types-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 20px;
        max-width: 900px;
        margin: 0 auto;
    }
    .partner-business-types .type-item {
        background: #fff;
        border: 1px solid #f0e8ed;
        border-radius: 16px;
        padding: 24px 16px;
        text-align: center;
        transition: all 0.3s ease;
    }
    .partner-business-types .type-item:hover {
        border-color: #E91E8C;
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.06);
    }
    .partner-business-types .type-item .icon {
        font-size: 2rem;
        color: #E91E8C;
        margin-bottom: 8px;
        display: block;
    }
    .partner-business-types .type-item .label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #1a1a1a;
    }
    @media (max-width: 768px) {
        .partner-business-types .types-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    @media (max-width: 480px) {
        .partner-business-types .types-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Everything You Need Section */
    .partner-everything {
        padding: 80px 0;
        background: #fff;
    }
    .partner-everything .section-title {
        font-size: 2.2rem;
        font-weight: 900;
        text-align: center;
        font-family: 'Playfair Display', serif;
        margin-bottom: 30px;
    }
    .partner-everything .section-title span {
        color: #E91E8C;
    }
    .partner-everything .sub-title {
        text-align: center;
        color: #888;
        font-size: 1rem;
        max-width: 700px;
        margin: 0 auto 40px;
        line-height: 1.8;
    }
    .partner-everything .feature-box {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        margin-top: 30px;
    }
    .partner-everything .feature-item {
        background: #f8f5f7;
        border-radius: 16px;
        padding: 30px 24px;
        border: 1px solid #f0e8ed;
        transition: all 0.3s ease;
        text-align: center;
    }
    .partner-everything .feature-item:hover {
        border-color: #E91E8C;
        transform: translateY(-4px);
    }
    .partner-everything .feature-item .icon {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        background: linear-gradient(135deg, #fce4ec, #fdf5fb);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 1.6rem;
        color: #E91E8C;
    }
    .partner-everything .feature-item h5 {
        font-weight: 700;
        font-size: 1.05rem;
        color: #1a1a1a;
        margin-bottom: 8px;
    }
    .partner-everything .feature-item p {
        color: #888;
        font-size: 0.9rem;
        line-height: 1.7;
        margin-bottom: 0;
    }
    @media (max-width: 768px) {
        .partner-everything .feature-box {
            grid-template-columns: 1fr 1fr;
        }
    }
    @media (max-width: 480px) {
        .partner-everything .feature-box {
            grid-template-columns: 1fr;
        }
    }

    /* Boss Your Business Section */
    .partner-boss {
        padding: 80px 0;
        background: #f8f5f7;
        border-bottom: 1px solid #f0e8ed;
    }
    .partner-boss .section-title {
        font-size: 2.2rem;
        font-weight: 900;
        text-align: center;
        font-family: 'Playfair Display', serif;
        margin-bottom: 8px;
    }
    .partner-boss .section-title span {
        color: #E91E8C;
    }
    .partner-boss .sub-title {
        text-align: center;
        color: #888;
        font-size: 1rem;
        margin-bottom: 40px;
    }
    .partner-boss .boss-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }
    .partner-boss .boss-item {
        background: #fff;
        border-radius: 16px;
        padding: 30px 24px;
        border: 1px solid #f0e8ed;
        text-align: center;
        transition: all 0.3s ease;
    }
    .partner-boss .boss-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.06);
    }
    .partner-boss .boss-item .number {
        font-size: 2.5rem;
        font-weight: 900;
        color: #E91E8C;
        font-family: 'Playfair Display', serif;
    }
    .partner-boss .boss-item h5 {
        font-weight: 700;
        font-size: 1.05rem;
        color: #1a1a1a;
        margin: 8px 0;
    }
    .partner-boss .boss-item p {
        color: #888;
        font-size: 0.9rem;
        line-height: 1.7;
        margin-bottom: 0;
    }
    @media (max-width: 768px) {
        .partner-boss .boss-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
    @media (max-width: 480px) {
        .partner-boss .boss-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Committed to Success Section */
    .partner-committed {
        padding: 80px 0;
        background: #fff;
        border-bottom: 1px solid #f0e8ed;
    }
    .partner-committed .section-title {
        font-size: 2.2rem;
        font-weight: 900;
        text-align: center;
        font-family: 'Playfair Display', serif;
        margin-bottom: 40px;
    }
    .partner-committed .section-title span {
        color: #E91E8C;
    }
    .partner-committed .commit-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
    }
    .partner-committed .commit-item {
        background: #f8f5f7;
        border-radius: 16px;
        padding: 28px 20px;
        border: 1px solid #f0e8ed;
        text-align: center;
        transition: all 0.3s ease;
    }
    .partner-committed .commit-item:hover {
        border-color: #E91E8C;
        transform: translateY(-4px);
    }
    .partner-committed .commit-item .icon {
        font-size: 2rem;
        color: #E91E8C;
        margin-bottom: 12px;
        display: block;
    }
    .partner-committed .commit-item h5 {
        font-weight: 700;
        font-size: 1rem;
        color: #1a1a1a;
        margin-bottom: 6px;
    }
    .partner-committed .commit-item p {
        color: #888;
        font-size: 0.85rem;
        line-height: 1.6;
        margin-bottom: 0;
    }
    @media (max-width: 768px) {
        .partner-committed .commit-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
    @media (max-width: 480px) {
        .partner-committed .commit-grid {
            grid-template-columns: 1fr;
        }
    }

    /* 24/7 Support Section */
    .partner-support {
        padding: 60px 0;
        background: #f8f5f7;
        border-bottom: 1px solid #f0e8ed;
        text-align: center;
    }
    .partner-support h2 {
        font-size: 2.2rem;
        font-weight: 900;
        font-family: 'Playfair Display', serif;
        margin-bottom: 8px;
    }
    .partner-support h2 span {
        color: #E91E8C;
    }
    .partner-support .support-links {
        display: flex;
        justify-content: center;
        gap: 40px;
        margin-top: 30px;
    }
    .partner-support .support-links a {
        color: #1a1a1a;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        transition: color 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .partner-support .support-links a i {
        color: #E91E8C;
    }
    .partner-support .support-links a:hover {
        color: #E91E8C;
    }
    @media (max-width: 576px) {
        .partner-support .support-links {
            flex-direction: column;
            gap: 16px;
        }
    }

    /* FAQ Section */
    .partner-faq {
        padding: 80px 0;
        background: #fff;
        border-bottom: 1px solid #f0e8ed;
    }
    .partner-faq .section-title {
        font-size: 2.2rem;
        font-weight: 900;
        text-align: center;
        font-family: 'Playfair Display', serif;
        margin-bottom: 40px;
    }
    .partner-faq .section-title span {
        color: #E91E8C;
    }
    .faq-item {
        border-bottom: 1px solid #f0e8ed;
        padding: 18px 0;
        cursor: pointer;
        max-width: 800px;
        margin: 0 auto;
    }
    .faq-item:last-child {
        border-bottom: none;
    }
    .faq-item .question {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        font-size: 1rem;
        color: #1a1a1a;
        transition: color 0.2s;
    }
    .faq-item .question:hover {
        color: #E91E8C;
    }
    .faq-item .question i {
        color: #E91E8C;
        transition: transform 0.3s;
        font-size: 0.9rem;
    }
    .faq-item .answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease, padding 0.3s ease;
        color: #777;
        font-size: 0.92rem;
        line-height: 1.7;
    }
    .faq-item.active .answer {
        max-height: 400px;
        padding-top: 12px;
    }
    .faq-item.active .question i {
        transform: rotate(180deg);
    }

    /* CTA Section */
    .partner-cta {
        padding: 80px 0;
        background: linear-gradient(135deg, #1a1a1a, #2d1f2c);
        text-align: center;
    }
    .partner-cta h2 {
        font-size: 2.5rem;
        font-weight: 900;
        color: #fff;
        font-family: 'Playfair Display', serif;
        margin-bottom: 12px;
    }
    .partner-cta p {
        color: #aaa;
        font-size: 1rem;
        margin-bottom: 30px;
    }
    .partner-cta .btn-cta {
        background: linear-gradient(135deg, #E91E8C, #C9A96E);
        color: #fff;
        border: none;
        padding: 14px 44px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(233,30,140,0.3);
    }
    .partner-cta .btn-cta:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(233,30,140,0.4);
        color: #fff;
    }

    @media (max-width: 768px) {
        .partner-hero h1 {
            font-size: 2.2rem;
        }
        .partner-hero .btn-partner {
            display: block;
            width: 100%;
            margin-bottom: 12px;
        }
        .partner-hero .btn-outline {
            display: block;
            width: 100%;
            margin-left: 0;
        }
        .partner-platform h2 {
            font-size: 1.8rem;
        }
        .partner-stats .stat-item .number {
            font-size: 2rem;
        }
        .partner-everything .section-title {
            font-size: 1.6rem;
        }
        .partner-boss .section-title {
            font-size: 1.6rem;
        }
        .partner-committed .section-title {
            font-size: 1.6rem;
        }
        .partner-support h2 {
            font-size: 1.6rem;
        }
        .partner-faq .section-title {
            font-size: 1.6rem;
        }
        .partner-cta h2 {
            font-size: 1.8rem;
        }
    }
</style>

<!-- ===== HERO SECTION ===== -->
<section class="partner-hero">
    <div class="container">
        <h1>Partner With <span>Beauty Blush</span></h1>
        <p>Join Pakistan's premium multi-salon booking platform and grow your beauty business. Reach thousands of clients looking for their next appointment.</p>
        <a href="{{ route('register.owner') }}" class="btn-partner">
            <i class="fas fa-rocket me-2"></i> Get Started Now
        </a>
        <a href="#" class="btn-outline">
            <i class="fas fa-play-circle me-2"></i> Watch an overview
        </a>
    </div>
</section>

<!-- ===== STATS SECTION ===== -->
<section class="partner-stats">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <div class="number">130K+</div>
                    <div class="label">Partner Businesses</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <div class="number">450K+</div>
                    <div class="label">Professionals</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <div class="number">1B+</div>
                    <div class="label">Appointments Booked</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <div class="number">120+</div>
                    <div class="label">Countries</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== ONE PLATFORM SECTION ===== -->
<section class="partner-platform">
    <div class="container">
        <h2>One platform, <span>infinite possibilities</span></h2>
        <p>Everything you need to grow and thrive. Beauty Blush is packed with tools to boost sales, manage your calendar, and retain clients, so you can focus on what you do best.</p>
        <a href="{{ route('register.owner') }}" class="btn-partner" style="background:linear-gradient(135deg, #E91E8C, #C9A96E); color:#fff; border:none; padding:14px 44px; border-radius:50px; font-weight:700; font-size:1rem; transition:all 0.3s ease; box-shadow:0 4px 20px rgba(233,30,140,0.3);">
            Get Started Now
        </a>
    </div>
</section>

<!-- ===== BUSINESS TYPES SECTION ===== -->
<section class="partner-business-types">
    <div class="container">
        <div class="types-grid">
            <div class="type-item">
                <span class="icon"><i class="fas fa-cut"></i></span>
                <span class="label">Barbers</span>
            </div>
            <div class="type-item">
                <span class="icon"><i class="fas fa-fire"></i></span>
                <span class="label">Waxing Salon</span>
            </div>
            <div class="type-item">
                <span class="icon"><i class="fas fa-hospital"></i></span>
                <span class="label">Medspa</span>
            </div>
            <div class="type-item">
                <span class="icon"><i class="fas fa-eye"></i></span>
                <span class="label">Eyebrow Bar</span>
            </div>
            <class="type-item">
                <span class="icon"><i class="fas fa-hand-sparkles"></i></span>
                <span class="label">Therapy Center</span>
            </div>
            <div class="type-item">
                <span class="icon"><i class="fas fa-scissors"></i></span>
                <span class="label">Salon</span>
            </div>
            <div class="type-item">
                <span class="icon"><i class="fas fa-dumbbell"></i></span>
                <span class="label">Personal Trainer</span>
            </div>
            <div class="type-item">
                <span class="icon"><i class="fas fa-running"></i></span>
                <span class="label">Fitness</span>
            </div>
        </div>
    </div>
</section>

<!-- ===== EVERYTHING YOU NEED SECTION ===== -->
<section class="partner-everything">
    <div class="container">
        <h2 class="section-title">Everything you need to <span>run your business</span></h2>
        <p class="sub-title">Beauty Blush offers innovative features that bring convenience, efficiency, and an improved experience for both your team members and clients.</p>

        <div class="feature-box">
            <div class="feature-item">
                <div class="icon"><i class="fas fa-calendar-check"></i></div>
                <h5>Manage</h5>
                <p>Manage bookings, sales, clients, locations, and team members. Analyse your business with advanced reporting and analytics.</p>
            </div>
            <div class="feature-item">
                <div class="icon"><i class="fas fa-chart-line"></i></div>
                <h5>Grow</h5>
                <p>Win new clients on the world's largest beauty and wellness marketplace. Keep them coming back with marketing features.</p>
            </div>
            <div class="feature-item">
                <div class="icon"><i class="fas fa-credit-card"></i></div>
                <h5>Get Paid</h5>
                <p>Get paid fast with seamless payment processing. Reduce no-shows with upfront payments and simplify checkout.</p>
            </div>
        </div>
    </div>
</section>

<!-- ===== BOSS YOUR BUSINESS SECTION ===== -->
<section class="partner-boss">
    <div class="container">
        <h2 class="section-title">Boss Your <span>Business</span></h2>
        <p class="sub-title">See how businesses are doing on Beauty Blush</p>

        <div class="boss-grid">
            <div class="boss-item">
                <div class="number">26%</div>
                <h5>More Clients</h5>
                <p>Win new clients and keep them coming back on the world's largest beauty and wellness marketplace.</p>
            </div>
            <div class="boss-item">
                <div class="number">89%</div>
                <h5>Fewer No-Shows</h5>
                <p>Reduce no-shows and cancellations by taking a deposit or a full payment upfront.</p>
            </div>
            <div class="boss-item">
                <div class="number">20%</div>
                <h5>More Sales</h5>
                <p>Generate more sales by upselling services when clients book online.</p>
            </div>
        </div>
    </div>
</section>

<!-- ===== COMMITTED TO SUCCESS SECTION ===== -->
<section class="partner-committed">
    <div class="container">
        <h2 class="section-title">Committed to Your <span>Success</span></h2>

        <div class="commit-grid">
            <div class="commit-item">
                <span class="icon"><i class="fas fa-user-tie"></i></span>
                <h5>Customer Success Manager</h5>
                <p>Get dedicated help to maximize your potential on Beauty Blush</p>
            </div>
            <div class="commit-item">
                <span class="icon"><i class="fas fa-network-wired"></i></span>
                <h5>Access Our Network</h5>
                <p>Use an Enterprise-certified account manager to bring your business to life</p>
            </div>
            <div class="commit-item">
                <span class="icon"><i class="fas fa-headset"></i></span>
                <h5>24/7 Priority Support</h5>
                <p>Talk with our customer care team anytime. We're here to help.</p>
            </div>
            <div class="commit-item">
                <span class="icon"><i class="fas fa-hand-holding-heart"></i></span>
                <h5>Migration Support</h5>
                <p>Our team can help bring your data from other platforms</p>
            </div>
        </div>
    </div>
</section>

<!-- ===== 24/7 SUPPORT SECTION ===== -->
<section class="partner-support">
    <div class="container">
        <h2>You are never alone, <br><span>award winning customer support 24/7</span></h2>
        <div class="support-links">
            <a href="#">
                <i class="fas fa-book-open"></i> Help Center
            </a>
            <a href="{{ route('contact') }}">
                <i class="fas fa-envelope"></i> Contact us
            </a>
        </div>
    </div>
</section>

<!-- ===== FAQ SECTION ===== -->
<section class="partner-faq">
    <div class="container">
        <h2 class="section-title">Frequently Asked <span>Questions</span></h2>

        <div class="faq-list">
            @php
                $faqs = [
                    ['What makes Beauty Blush the leading platform for businesses in beauty and wellness?', "We're the world's largest booking platform for beauty and wellness, trusted by over 130,000 businesses for their operations. Businesses choose us because of our powerful, easy-to-use features, including online booking, payment processing, marketing tools, and team management. Our automation simplifies daily tasks, saves time, and enhances efficiency, so you can focus on what matters most. With our global marketplace, we connect your business to millions of potential customers, providing unmatched opportunities for growth, making us the number one platform in beauty and wellness."],
                    ['How does Beauty Blush help my business grow?', "Beauty Blush helps your business grow by connecting you with thousands of potential clients through our marketplace. Our platform offers marketing tools, automated booking reminders, and client retention features that help you build a loyal customer base. You'll also get access to advanced analytics to track your business performance."],
                    ['Are there any hidden costs?', "No! We believe in complete transparency. There are no hidden fees or surprise charges. You only pay for what you use."],
                    ['Is there a minimum commitment or contract?', "No minimum commitment required. You can cancel anytime. We're confident you'll love our platform."],
                    ['Does Beauty Blush support businesses of all sizes?', "Absolutely! Whether you're a solo stylist or a large salon chain, Beauty Blush has the tools you need to succeed."],
                    ['What types of businesses can use Beauty Blush?', "Beauty Blush is designed for all beauty and wellness businesses including salons, spas, barbershops, medspas, brow bars, therapy centers, personal trainers, and fitness studios."],
                ];
            @endphp

            @foreach($faqs as $faq)
            <div class="faq-item" onclick="toggleFaq(this)">
                <div class="question">
                    {{ $faq[0] }}
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="answer">{{ $faq[1] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ===== CTA SECTION ===== -->
<section class="partner-cta">
    <div class="container">
        <h2>What are you waiting for?</h2>
        <p>Partner with Beauty Blush and start growing your business today</p>
        <a href="{{ route('register.owner') }}" class="btn-cta">
            <i class="fas fa-rocket me-2"></i> Get Started Now
        </a>
    </div>
</section>

<script>
function toggleFaq(el) {
    el.classList.toggle('active');
}
</script>

@endsection