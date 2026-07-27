@extends('layouts.app')
@section('title', 'Privacy Policy - Beauty Blush Salons')

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
    .delay-6 { animation-delay: 0.6s; }

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
                    <i class="fas fa-shield-alt me-2"></i> Your Privacy Matters
                </div>
                <h1 class="animate-slide-up delay-1" style="font-size: clamp(2.2rem, 5.5vw, 3.8rem); font-weight: 900; color: #fff; margin-bottom: 16px; line-height: 1.15;">
                    Privacy <span class="gradient-text">Policy</span>
                </h1>
                <p class="animate-slide-up delay-2" style="font-size: clamp(1rem, 1.5vw, 1.2rem); color: rgba(255,255,255,0.75); max-width: 580px; margin: 0 auto; line-height: 1.9;">
                    We take your privacy seriously. Learn how we collect, use, and protect your personal information.
                </p>
                <div class="animate-slide-up delay-3" style="margin-top: 20px; display: flex; flex-wrap: wrap; gap: 16px; justify-content: center;">
                    <span style="background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.6); padding: 6px 20px; border-radius: 50px; font-size: 13px; border: 1px solid rgba(255,255,255,0.06);">
                        <i class="far fa-calendar-alt me-2"></i> Last Updated: January 2026
                    </span>
                    <span style="background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.6); padding: 6px 20px; border-radius: 50px; font-size: 13px; border: 1px solid rgba(255,255,255,0.06);">
                        <i class="far fa-clock me-2"></i> 5 min read
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
                    <h3><span class="section-number">01</span> Information We Collect</h3>
                    <p>We collect personal information that you provide directly when using our platform. This includes:</p>
                    <ul>
                        <li><strong>Account Information:</strong> Name, email address, phone number, and password when you create an account.</li>
                        <li><strong>Booking Information:</strong> Service preferences, appointment history, and salon interactions.</li>
                        <li><strong>Payment Information:</strong> Payment method details (processed securely through our payment partners).</li>
                        <li><strong>Usage Data:</strong> How you interact with our platform, including pages visited and features used.</li>
                        <li><strong>Location Data:</strong> City and area information when you search for salons near you.</li>
                    </ul>
                </div>

                <div class="legal-section animate-slide-up delay-1">
                    <h3><span class="section-number">02</span> How We Use Your Information</h3>
                    <p>We use your personal information to provide, improve, and personalize our services:</p>
                    <ul>
                        <li><strong>Process Bookings:</strong> To confirm appointments and communicate with you about your reservations.</li>
                        <li><strong>Improve Services:</strong> To understand user preferences and enhance our platform experience.</li>
                        <li><strong>Send Updates:</strong> To notify you about appointment reminders, promotions, and important platform updates.</li>
                        <li><strong>Security:</strong> To protect against fraud, unauthorized access, and ensure platform integrity.</li>
                        <li><strong>Customer Support:</strong> To assist you with any questions, issues, or feedback you may have.</li>
                    </ul>
                </div>

                <div class="legal-section animate-slide-up delay-2">
                    <h3><span class="section-number">03</span> Information Sharing</h3>
                    <p>We respect your privacy and do not sell your personal information. We share information only in specific circumstances:</p>
                    <ul>
                        <li><strong>With Salons:</strong> To process your bookings and provide services you request.</li>
                        <li><strong>Payment Providers:</strong> To securely process your payments and transactions.</li>
                        <li><strong>Legal Requirements:</strong> When required by law or to protect our rights and interests.</li>
                        <li><strong>Service Providers:</strong> With trusted partners who help us operate and improve our platform.</li>
                    </ul>
                </div>

                <div class="legal-section animate-slide-up delay-3">
                    <h3><span class="section-number">04</span> Data Security</h3>
                    <p>We implement industry-standard security measures to protect your information:</p>
                    <ul>
                        <li><strong>Encryption:</strong> All sensitive data is encrypted using 256-bit SSL technology.</li>
                        <li><strong>Access Control:</strong> Only authorized personnel have access to your personal information.</li>
                        <li><strong>Regular Audits:</strong> We conduct security audits to identify and address vulnerabilities.</li>
                        <li><strong>Secure Storage:</strong> Your data is stored on secure servers with multiple layers of protection.</li>
                    </ul>
                </div>

                <div class="legal-section animate-slide-up delay-4">
                    <h3><span class="section-number">05</span> Your Rights</h3>
                    <p>You have the following rights regarding your personal data:</p>
                    <ul>
                        <li><strong>Access:</strong> Request a copy of the personal data we hold about you.</li>
                        <li><strong>Correction:</strong> Update or correct inaccurate information in your account.</li>
                        <li><strong>Deletion:</strong> Request deletion of your account and associated data.</li>
                        <li><strong>Opt-Out:</strong> Unsubscribe from marketing communications at any time.</li>
                        <li><strong>Data Portability:</strong> Request a copy of your data in a portable format.</li>
                    </ul>
                </div>

                <div class="legal-section animate-slide-up delay-5">
                    <h3><span class="section-number">06</span> Cookies & Tracking</h3>
                    <p>We use cookies to enhance your experience and understand how our platform is used:</p>
                    <ul>
                        <li><strong>Essential Cookies:</strong> Required for basic platform functionality and security.</li>
                        <li><strong>Performance Cookies:</strong> Help us understand how users interact with our platform.</li>
                        <li><strong>Functional Cookies:</strong> Remember your preferences and settings.</li>
                        <li><strong>Marketing Cookies:</strong> Used to deliver relevant content and promotions.</li>
                    </ul>
                    <p style="margin-top: 10px;">You can manage your cookie preferences through your browser settings.</p>
                </div>

                <div class="legal-section animate-slide-up delay-6">
                    <h3><span class="section-number">07</span> Contact Us</h3>
                    <p>If you have any questions about this Privacy Policy or how we handle your data, please reach out to us:</p>
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
                Have Questions About Your <span class="gradient-text">Privacy?</span>
            </h2>
            <p style="color: rgba(255,255,255,0.7); font-size: 15px; max-width: 450px; margin: 0 auto 20px; line-height: 1.7;">
                Our team is here to address any privacy concerns you may have.
            </p>
            <a href="{{ route('contact') }}" style="background: linear-gradient(135deg, #F472B6, #DB2777); color: #fff; padding: 14px 40px; border-radius: 50px; font-weight: 700; font-size: 15px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 8px 30px rgba(219,39,119,0.35); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 14px 50px rgba(219,39,119,0.5)';" onmouseout="this.style.transform=''; this.style.boxShadow='0 8px 30px rgba(219,39,119,0.35)';">
                <i class="fas fa-headset"></i> Contact Us
            </a>
        </div>
    </div>
</section>

@endsection