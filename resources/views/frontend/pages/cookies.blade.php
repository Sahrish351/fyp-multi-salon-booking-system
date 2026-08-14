@extends('layouts.app')
@section('title', 'Cookie Policy - Beauty Blush Salons')

@section('content')

<style>
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
    @keyframes rotateSlow {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .animate-float { animation: float 8s ease-in-out infinite; }
    .animate-float-reverse { animation: floatReverse 10s ease-in-out infinite; }
    .animate-slide-up { animation: slideUp 0.8s ease forwards; }
    .animate-rotate { animation: rotateSlow 20s linear infinite; }

    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }
    .delay-5 { animation-delay: 0.5s; }

    .gradient-text {
        background: linear-gradient(135deg, #F472B6, #EC4899, #DB2777);
        background-size: 200% 200%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: gradientMove 4s ease infinite;
    }

    .legal-section {
        background: #fff;
        border-radius: 20px;
        padding: 32px 36px;
        margin-bottom: 20px;
        border: 1px solid rgba(236,72,153,0.06);
        box-shadow: 0 2px 15px rgba(0,0,0,0.02);
        transition: all 0.3s ease;
    }
    .legal-section:hover {
        border-color: rgba(236,72,153,0.12);
        box-shadow: 0 8px 30px rgba(236,72,153,0.04);
    }
    .legal-section h3 {
        font-size: 18px;
        font-weight: 800;
        color: #1a0a14;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .legal-section h3 .section-number {
        color: #EC4899;
        font-size: 14px;
    }
    .legal-section p, .legal-section li {
        font-size: 15px;
        color: #666;
        line-height: 1.9;
    }
    .legal-section ul {
        padding-left: 20px;
        margin: 10px 0;
    }
    .legal-section ul li {
        margin-bottom: 6px;
        list-style-type: none;
        padding-left: 20px;
        position: relative;
    }
    .legal-section ul li::before {
        content: '▸';
        position: absolute;
        left: 0;
        color: #EC4899;
        font-weight: 700;
    }

    .cookie-card {
        background: #FFF9FC;
        border-radius: 14px;
        padding: 20px 24px;
        border: 1px solid #FFE8F0;
        transition: all 0.3s ease;
        margin-bottom: 12px;
    }
    .cookie-card:hover {
        border-color: #EC4899;
        transform: translateX(4px);
        box-shadow: 0 4px 20px rgba(236,72,153,0.06);
    }
    .cookie-card .cookie-icon {
        font-size: 24px;
        margin-right: 12px;
    }
    .cookie-card .cookie-name {
        font-weight: 700;
        color: #1a0a14;
        font-size: 15px;
    }
    .cookie-card .cookie-desc {
        font-size: 13px;
        color: #888;
        margin-top: 4px;
    }

    @media (max-width: 768px) {
        .legal-section {
            padding: 24px 20px;
        }
        .legal-section h3 {
            font-size: 16px;
        }
        .legal-section p, .legal-section li {
            font-size: 14px;
        }
        .cookie-card {
            padding: 16px 18px;
        }
    }
    @media (max-width: 576px) {
        .legal-section {
            padding: 18px 16px;
        }
        .legal-section h3 {
            font-size: 15px;
        }
        .legal-section p, .legal-section li {
            font-size: 13px;
        }
        .cookie-card {
            padding: 14px 14px;
        }
        .cookie-card .cookie-name {
            font-size: 13px;
        }
        .cookie-card .cookie-desc {
            font-size: 12px;
        }
    }
</style>

<!-- HERO SECTION -->
<section style="background: linear-gradient(135deg, #0a0508 0%, #1a0a14 30%, #3d1a2e 60%, #6b2147 100%); padding: 100px 0 70px; position: relative; overflow: hidden;">
    <div class="animate-float" style="position: absolute; top: -30%; right: -10%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(244,114,182,0.08) 0%, transparent 70%); border-radius: 50%;"></div>
    <div class="animate-float-reverse" style="position: absolute; bottom: -20%; left: -10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(244,114,182,0.05) 0%, transparent 70%); border-radius: 50%;"></div>
    <div class="animate-rotate" style="position: absolute; top: 15%; left: 20%; width: 200px; height: 200px; border: 1px solid rgba(244,114,182,0.05); border-radius: 50%;"></div>
    
    <div class="container" style="position: relative; z-index: 1;">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="animate-slide-up" style="display: inline-block; background: rgba(244,114,182,0.12); color: #F472B6; padding: 8px 28px; border-radius: 50px; font-size: 13px; font-weight: 600; letter-spacing: 1.5px; margin-bottom: 24px; border: 1px solid rgba(244,114,182,0.2); backdrop-filter: blur(10px);">
                    <i class="fas fa-cookie-bite me-2"></i> Your Privacy Choice
                </div>
                <h1 class="animate-slide-up delay-1" style="font-size: clamp(2.2rem, 5.5vw, 3.8rem); font-weight: 900; color: #fff; margin-bottom: 16px; line-height: 1.15;">
                    Cookie <span class="gradient-text">Policy</span>
                </h1>
                <p class="animate-slide-up delay-2" style="font-size: clamp(1rem, 1.5vw, 1.2rem); color: rgba(255,255,255,0.75); max-width: 580px; margin: 0 auto; line-height: 1.9;">
                    Learn how we use cookies to enhance your experience on Beauty Blush Salons.
                </p>
                <div class="animate-slide-up delay-3" style="margin-top: 20px; display: flex; flex-wrap: wrap; gap: 16px; justify-content: center;">
                    <span style="background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.6); padding: 6px 20px; border-radius: 50px; font-size: 13px; border: 1px solid rgba(255,255,255,0.06);">
                        <i class="far fa-calendar-alt me-2"></i> Last Updated: January 2026
                    </span>
                    <span style="background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.6); padding: 6px 20px; border-radius: 50px; font-size: 13px; border: 1px solid rgba(255,255,255,0.06);">
                        <i class="far fa-clock me-2"></i> 3 min read
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CONTENT -->
<section style="padding: 60px 0 70px; background: #FFF9FC;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="legal-section animate-slide-up">
                    <h3><span class="section-number">01</span> What Are Cookies?</h3>
                    <p>Cookies are small text files that websites place on your device to store information about your preferences and activities. They help us provide you with a better browsing experience by remembering your choices and personalizing content.</p>
                </div>

                <div class="legal-section animate-slide-up delay-1">
                    <h3><span class="section-number">02</span> How We Use Cookies</h3>
                    <p>We use cookies to enhance your experience on Beauty Blush Salons. Cookies allow us to:</p>
                    <ul>
                        <li>Remember your login details and preferences.</li>
                        <li>Understand how you interact with our platform.</li>
                        <li>Personalize content and recommendations for you.</li>
                        <li>Improve our platform's performance and functionality.</li>
                        <li>Deliver relevant promotions and offers.</li>
                    </ul>
                </div>

                <div class="legal-section animate-slide-up delay-2">
                    <h3><span class="section-number">03</span> Types of Cookies We Use</h3>
                    <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 12px;">
                        <div class="cookie-card">
                            <div style="display: flex; align-items: center;">
                                <span class="cookie-icon">🍪</span>
                                <div>
                                    <div class="cookie-name">Essential Cookies</div>
                                    <div class="cookie-desc">Required for basic platform functionality, security, and account management. Cannot be disabled.</div>
                                </div>
                            </div>
                        </div>
                        <div class="cookie-card">
                            <div style="display: flex; align-items: center;">
                                <span class="cookie-icon">📊</span>
                                <div>
                                    <div class="cookie-name">Performance Cookies</div>
                                    <div class="cookie-desc">Help us understand how users interact with our platform and improve user experience.</div>
                                </div>
                            </div>
                        </div>
                        <div class="cookie-card">
                            <div style="display: flex; align-items: center;">
                                <span class="cookie-icon">⚙️</span>
                                <div>
                                    <div class="cookie-name">Functional Cookies</div>
                                    <div class="cookie-desc">Remember your preferences, language settings, and previous actions.</div>
                                </div>
                            </div>
                        </div>
                        <div class="cookie-card">
                            <div style="display: flex; align-items: center;">
                                <span class="cookie-icon">🎯</span>
                                <div>
                                    <div class="cookie-name">Marketing Cookies</div>
                                    <div class="cookie-desc">Used to deliver relevant content, promotions, and advertisements based on your interests.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="legal-section animate-slide-up delay-3">
                    <h3><span class="section-number">04</span> Managing Your Cookie Preferences</h3>
                    <p>You have the right to manage your cookie preferences at any time. Most browsers allow you to:</p>
                    <ul>
                        <li>Accept or reject all cookies.</li>
                        <li>Delete existing cookies from your device.</li>
                        <li>Set preferences for specific websites.</li>
                        <li>Get notified when cookies are being set.</li>
                    </ul>
                    <p style="margin-top: 10px;">Please note that disabling certain cookies may affect your experience on our platform.</p>
                </div>

                <div class="legal-section animate-slide-up delay-4">
                    <h3><span class="section-number">05</span> Contact Us</h3>
                    <p>If you have any questions about our Cookie Policy, please contact us:</p>
                    <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-top: 12px;">
                        <div style="display: flex; align-items: center; gap: 10px; background: #FFF9FC; padding: 12px 20px; border-radius: 12px; border: 1px solid #FFE8F0;">
                            <i class="fas fa-envelope" style="color: #EC4899;"></i>
                            <span style="font-size: 14px; color: #555;">privacy@beautyblush.pk</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px; background: #FFF9FC; padding: 12px 20px; border-radius: 12px; border: 1px solid #FFE8F0;">
                            <i class="fas fa-phone" style="color: #EC4899;"></i>
                            <span style="font-size: 14px; color: #555;">+92 300 1234567</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section style="background: linear-gradient(135deg, #0a0508 0%, #1a0a14 30%, #3d1a2e 60%, #6b2147 100%); padding: 50px 0; text-align: center;">
    <div class="container">
        <div class="animate-slide-up">
            <h2 style="font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 800; color: #fff; margin-bottom: 8px;">
                Questions About <span class="gradient-text">Cookies?</span>
            </h2>
            <p style="color: rgba(255,255,255,0.7); font-size: 15px; max-width: 450px; margin: 0 auto 20px; line-height: 1.7;">
                We're here to help you understand our cookie usage.
            </p>
            <a href="{{ route('contact') }}" style="background: linear-gradient(135deg, #F472B6, #DB2777); color: #fff; padding: 14px 40px; border-radius: 50px; font-weight: 700; font-size: 15px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 8px 30px rgba(219,39,119,0.35); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 14px 50px rgba(219,39,119,0.5)';" onmouseout="this.style.transform=''; this.style.boxShadow='0 8px 30px rgba(219,39,119,0.35)';">
                <i class="fas fa-headset"></i> Contact Us
            </a>
        </div>
    </div>
</section>

@endsection