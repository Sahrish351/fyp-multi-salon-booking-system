@extends('layouts.app')
@section('title', 'Support - Beauty Blush Salons')

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
    @keyframes shimmerBtn {
        0% { background-position: -200% center; }
        100% { background-position: 200% center; }
    }
    @keyframes ring {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .animate-float { animation: float 8s ease-in-out infinite; }
    .animate-float-reverse { animation: floatReverse 10s ease-in-out infinite; }
    .animate-slide-up { animation: slideUp 0.8s ease forwards; }
    .animate-slide-left { animation: slideInLeft 0.8s ease forwards; }
    .animate-slide-right { animation: slideInRight 0.8s ease forwards; }
    .animate-scale { animation: scaleIn 0.8s ease forwards; }
    .animate-glow { animation: glowPulse 3s ease-in-out infinite; }
    .animate-rotate { animation: rotateSlow 20s linear infinite; }

    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }
    .delay-5 { animation-delay: 0.5s; }
    .delay-6 { animation-delay: 0.6s; }

    /* ============================================================ */
    /* SUPPORT CARDS */
    /* ============================================================ */
    .support-card {
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
        background: #fff;
        border-radius: 24px;
        border: 1px solid rgba(236,72,153,0.08);
        box-shadow: 0 2px 20px rgba(236,72,153,0.04);
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 36px 28px 32px;
        z-index: 1;
    }
    .support-card::before {
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
        z-index: -1;
    }
    .support-card:hover::before {
        opacity: 1;
        transform: scale(1);
    }
    .support-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 60px rgba(236,72,153,0.10) !important;
        border-color: rgba(236,72,153,0.15);
        z-index: 5;
    }
    .support-card:hover .support-icon {
        transform: scale(1.1) rotate(-5deg);
        box-shadow: 0 12px 40px rgba(236,72,153,0.2);
    }
    .support-card:hover .support-btn {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(236,72,153,0.3);
    }

    .support-icon {
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 32px;
    }

    .support-btn {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 32px;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        font-size: 14px;
        margin-top: auto;
        border: none;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    .support-btn::after {
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
    .support-btn:hover::after {
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
    /* FORM STYLES */
    /* ============================================================ */
    .form-input {
        transition: all 0.3s ease;
        width: 100%;
        padding: 14px 18px;
        border: 1.5px solid #FFE8F0;
        border-radius: 12px;
        font-size: 14px;
        color: #1a0a14;
        outline: none;
        background: #fff;
    }
    .form-input:focus {
        border-color: #EC4899;
        box-shadow: 0 0 0 4px rgba(236,72,153,0.08);
        transform: translateY(-2px);
    }
    .form-input.error {
        border-color: #EF4444;
        box-shadow: 0 0 0 4px rgba(239,68,68,0.08);
    }
    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #1a0a14;
        margin-bottom: 6px;
    }
    .form-label .required {
        color: #EC4899;
    }

    /* ============================================================ */
    /* RESPONSIVE */
    /* ============================================================ */
    @media (max-width: 768px) {
        .support-card {
            padding: 28px 20px;
        }
        .support-icon {
            width: 64px;
            height: 64px;
            font-size: 26px;
        }
        .support-btn {
            padding: 10px 24px;
            font-size: 13px;
        }
    }
    @media (max-width: 576px) {
        .support-card {
            padding: 24px 16px;
        }
        .support-icon {
            width: 56px;
            height: 56px;
            font-size: 22px;
        }
        .form-input {
            padding: 12px 14px;
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
                    <i class="fas fa-headset me-2"></i> We're Here to Help
                </div>
                <h1 class="animate-slide-up delay-1" style="font-size: clamp(2.2rem, 5.5vw, 3.8rem); font-weight: 900; color: #fff; margin-bottom: 16px; line-height: 1.15;">
                    How Can We <span class="gradient-text">Help You?</span>
                </h1>
                <p class="animate-slide-up delay-2" style="font-size: clamp(1rem, 1.5vw, 1.2rem); color: rgba(255,255,255,0.75); max-width: 580px; margin: 0 auto; line-height: 1.9;">
                    Our dedicated support team is available 24/7 to assist you with any questions, issues, or concerns. We're just a message away.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- SUPPORT CHANNELS SECTION -->
<!-- ============================================================ -->
<section style="padding: 60px 0 70px; background: #FFF9FC;">
    <div class="container">

        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto animate-slide-up">
                <span style="color: #EC4899; font-weight: 600; font-size: 14px; letter-spacing: 2px; text-transform: uppercase; display: block; margin-bottom: 8px;">Support Channels</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.6rem); font-weight: 800; color: #1a0a14;">Get in <span style="color: #EC4899;">Touch</span></h2>
                <p style="color: #888; font-size: 15px; margin-top: 12px;">Choose the channel that works best for you</p>
            </div>
        </div>

        <div class="row g-4">
            @php
                $channels = [
                    [
                        'icon' => 'fa-comment-dots',
                        'color' => '#EC4899',
                        'bg' => 'rgba(236,72,153,0.08)',
                        'title' => 'Live Chat',
                        'desc' => 'Chat with our support team instantly. Get real-time assistance for any issue.',
                        'action' => 'Start Chat',
                        'link' => '#',
                        'btn_bg' => 'linear-gradient(135deg, #F472B6, #DB2777)'
                    ],
                    [
                        'icon' => 'fa-envelope',
                        'color' => '#8B5CF6',
                        'bg' => 'rgba(139,92,246,0.08)',
                        'title' => 'Email Support',
                        'desc' => 'Send us an email and we\'ll respond within 12 hours. Perfect for detailed queries.',
                        'action' => 'Email Us',
                        'link' => 'mailto:support@beautyblush.pk',
                        'btn_bg' => 'linear-gradient(135deg, #8B5CF6, #6D28D9)'
                    ],
                    [
                        'icon' => 'fa-phone-alt',
                        'color' => '#10B981',
                        'bg' => 'rgba(16,185,129,0.08)',
                        'title' => 'Phone Support',
                        'desc' => 'Call us during business hours. Speak directly with a support representative.',
                        'action' => 'Call Us',
                        'link' => 'tel:+923001234567',
                        'btn_bg' => 'linear-gradient(135deg, #10B981, #059669)'
                    ],
                    [
                        'icon' => 'fa-whatsapp',
                        'color' => '#25D366',
                        'bg' => 'rgba(37,211,102,0.08)',
                        'title' => 'WhatsApp',
                        'desc' => 'Message us on WhatsApp for quick responses. Available 24/7 for your convenience.',
                        'action' => 'Chat on WhatsApp',
                        'link' => 'https://wa.me/923001234567',
                        'btn_bg' => 'linear-gradient(135deg, #25D366, #128C7E)'
                    ],
                    [
                        'icon' => 'fa-facebook-messenger',
                        'color' => '#0084FF',
                        'bg' => 'rgba(0,132,255,0.08)',
                        'title' => 'Messenger',
                        'desc' => 'Reach out via Facebook Messenger. Our team is active and ready to help.',
                        'action' => 'Message Us',
                        'link' => '#',
                        'btn_bg' => 'linear-gradient(135deg, #0084FF, #0063D4)'
                    ],
                    [
                        'icon' => 'fa-clock',
                        'color' => '#F59E0B',
                        'bg' => 'rgba(245,158,11,0.08)',
                        'title' => '24/7 Support',
                        'desc' => 'Our support team is available round the clock. We\'re always here for you.',
                        'action' => 'Learn More',
                        'link' => '#',
                        'btn_bg' => 'linear-gradient(135deg, #F59E0B, #D97706)'
                    ],
                ];
            @endphp
            @foreach($channels as $channel)
                <div class="col-lg-4 col-md-6 animate-slide-up" style="animation-delay: {{ 0.1 + $loop->index * 0.05 }}s;">
                    <div class="support-card">
                        <div class="support-icon" style="background: {{ $channel['bg'] }}; color: {{ $channel['color'] }};">
                            <i class="fas {{ $channel['icon'] }}"></i>
                        </div>
                        <h4 style="font-size: 18px; font-weight: 800; color: #1a0a14; margin-bottom: 8px;">{{ $channel['title'] }}</h4>
                        <p style="font-size: 14px; color: #888; line-height: 1.7; margin-bottom: 20px; flex: 1;">{{ $channel['desc'] }}</p>
                        <a href="{{ $channel['link'] }}" class="support-btn" style="background: {{ $channel['btn_bg'] }}; color: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                            {{ $channel['action'] }}
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Business Hours -->
        <div class="row justify-content-center mt-5 animate-slide-up delay-4">
            <div class="col-lg-8">
                <div style="background: #fff; border-radius: 20px; padding: 30px 24px; border: 1px solid #FFE8F0; text-align: center;">
                    <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 30px;">
                        <div>
                            <i class="fas fa-clock" style="color: #EC4899; font-size: 20px;"></i>
                            <h6 style="font-weight: 700; color: #1a0a14; margin-top: 8px; font-size: 14px;">Business Hours</h6>
                            <p style="font-size: 13px; color: #888; margin: 0;">Monday – Saturday: 9:00 AM – 7:00 PM</p>
                            <p style="font-size: 13px; color: #888; margin: 0;">Sunday: 10:00 AM – 4:00 PM</p>
                        </div>
                        <div>
                            <i class="fas fa-clock" style="color: #10B981; font-size: 20px;"></i>
                            <h6 style="font-weight: 700; color: #1a0a14; margin-top: 8px; font-size: 14px;">Support Hours</h6>
                            <p style="font-size: 13px; color: #888; margin: 0;">24/7 Live Chat Support</p>
                            <p style="font-size: 13px; color: #888; margin: 0;">Email Response: Within 12 Hours</p>
                        </div>
                        <div>
                            <i class="fas fa-headset" style="color: #8B5CF6; font-size: 20px;"></i>
                            <h6 style="font-weight: 700; color: #1a0a14; margin-top: 8px; font-size: 14px;">Average Response Time</h6>
                            <p style="font-size: 13px; color: #888; margin: 0;">Live Chat: 2-3 Minutes</p>
                            <p style="font-size: 13px; color: #888; margin: 0;">Email: 2-4 Hours</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- TICKET FORM SECTION -->
<!-- ============================================================ -->
<section style="padding: 70px 0; background: #fff;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 animate-slide-left">
                <span style="color: #EC4899; font-weight: 600; font-size: 14px; letter-spacing: 2px; text-transform: uppercase; display: block; margin-bottom: 8px;">Submit a Ticket</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.6rem); font-weight: 800; color: #1a0a14; margin-bottom: 16px;">
                    We'll Get Back to You <span style="color: #EC4899;">Quickly</span>
                </h2>
                <p style="color: #777; font-size: 16px; line-height: 1.8; margin-bottom: 20px;">
                    Fill out the form below and our support team will respond to you within 24 hours. For urgent issues, please use our live chat feature.
                </p>
                
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="display: flex; align-items: center; gap: 12px; background: #FFF9FC; padding: 14px 18px; border-radius: 12px; border: 1px solid #FFE8F0;">
                        <i class="fas fa-check-circle" style="color: #10B981;"></i>
                        <span style="font-size: 14px; color: #555;">100% response rate</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 12px; background: #FFF9FC; padding: 14px 18px; border-radius: 12px; border: 1px solid #FFE8F0;">
                        <i class="fas fa-check-circle" style="color: #10B981;"></i>
                        <span style="font-size: 14px; color: #555;">Average reply time: 2-4 hours</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 12px; background: #FFF9FC; padding: 14px 18px; border-radius: 12px; border: 1px solid #FFE8F0;">
                        <i class="fas fa-check-circle" style="color: #10B981;"></i>
                        <span style="font-size: 14px; color: #555;">Friendly and professional support</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 animate-slide-right">
                <form action="{{ route('support.submit') }}" method="POST" style="background: #FFF9FC; border-radius: 24px; padding: 40px; border: 1px solid #FFE8F0; box-shadow: 0 8px 40px rgba(236,72,153,0.06);">
                    @csrf
                    <h4 style="font-size: 20px; font-weight: 700; color: #1a0a14; margin-bottom: 24px; text-align: center;">Submit a Support Ticket</h4>
                    
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
                            <label class="form-label">Your Full Name <span class="required">*</span></label>
                            <input type="text" name="name" class="form-input" placeholder="e.g. Aisha Malik" value="{{ old('name') }}" required>
                            @error('name')
                                <p style="color: #EF4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Email Address <span class="required">*</span></label>
                            <input type="email" name="email" class="form-input" placeholder="your@email.com" value="{{ old('email') }}" required>
                            @error('email')
                                <p style="color: #EF4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-input" placeholder="+92 300 1234567" value="{{ old('phone') }}">
                        </div>

                        <div>
                            <label class="form-label">Issue Category <span class="required">*</span></label>
                            <select name="category" class="form-input" required style="appearance: none; cursor: pointer;">
                                <option value="">Select a category</option>
                                <option value="Booking" {{ old('category') == 'Booking' ? 'selected' : '' }}>Booking Issue</option>
                                <option value="Payment" {{ old('category') == 'Payment' ? 'selected' : '' }}>Payment Problem</option>
                                <option value="Salon" {{ old('category') == 'Salon' ? 'selected' : '' }}>Salon Related</option>
                                <option value="Account" {{ old('category') == 'Account' ? 'selected' : '' }}>Account Help</option>
                                <option value="Technical" {{ old('category') == 'Technical' ? 'selected' : '' }}>Technical Issue</option>
                                <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('category')
                                <p style="color: #EF4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Message <span class="required">*</span></label>
                            <textarea name="message" class="form-input" rows="4" placeholder="Describe your issue in detail..." required style="resize: vertical; font-family: inherit;">{{ old('message') }}</textarea>
                            @error('message')
                                <p style="color: #EF4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" style="background: linear-gradient(135deg, #F472B6, #DB2777); color: #fff; padding: 16px; border-radius: 12px; font-weight: 700; font-size: 16px; border: none; cursor: pointer; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); margin-top: 8px; box-shadow: 0 8px 30px rgba(219,39,119,0.25);" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 40px rgba(219,39,119,0.4)';" onmouseout="this.style.transform=''; this.style.boxShadow='0 8px 30px rgba(219,39,119,0.25)';">
                            <i class="fas fa-paper-plane me-2"></i> Submit Ticket
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
<section style="padding: 70px 0; background: #FFF9FC;">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto animate-slide-up">
                <span style="color: #EC4899; font-weight: 600; font-size: 14px; letter-spacing: 2px; text-transform: uppercase; display: block; margin-bottom: 8px;">Common Questions</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.6rem); font-weight: 800; color: #1a0a14;">Support <span style="color: #EC4899;">FAQs</span></h2>
                <p style="color: #888; font-size: 15px; margin-top: 12px;">Quick answers to common support questions</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                @php
                    $faqs = [
                        ['q'=>'How quickly will I get a response?', 'a'=>'Our average response time is 2-4 hours. For live chat, response is within 2-3 minutes. We prioritize urgent issues and resolve them as quickly as possible.'],
                        ['q'=>'Can I get support 24/7?', 'a'=>'Yes! Our live chat and WhatsApp support are available 24/7. Email support is available 24/7, but responses may be slower outside business hours.'],
                        ['q'=>'How do I cancel my subscription?', 'a'=>'You can cancel your subscription anytime from your account settings. Go to Settings → Subscription → Cancel. No questions asked.'],
                        ['q'=>'What if I have a technical issue with the platform?', 'a'=>'Please contact our support team immediately. We have technical experts available to help you resolve any platform-related issues.'],
                        ['q'=>'How do I report a problem with a salon?', 'a'=>'If you have an issue with a salon, please contact us via live chat or email. We take all complaints seriously and will investigate within 24 hours.'],
                    ];
                @endphp
                @foreach($faqs as $faq)
                    <div class="faq-item" style="background: #fff; border-radius: 16px; padding: 20px 24px; margin-bottom: 12px; border: 1px solid #FFE8F0; transition: all 0.3s ease; cursor: pointer;" onclick="toggleFaq(this)">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
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
<section style="background: linear-gradient(135deg, #0a0508 0%, #1a0a14 30%, #3d1a2e 60%, #6b2147 100%); padding: 60px 0; text-align: center; position: relative; overflow: hidden;">
    <div class="animate-float" style="position: absolute; top: -40%; right: -20%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(244,114,182,0.06) 0%, transparent 70%); border-radius: 50%;"></div>
    <div class="animate-float-reverse" style="position: absolute; bottom: -30%; left: -15%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(244,114,182,0.04) 0%, transparent 70%); border-radius: 50%;"></div>
    
    <div class="container" style="position: relative; z-index: 1;">
        <div class="animate-slide-up">
            <h2 style="font-size: clamp(1.8rem, 3.5vw, 2.6rem); font-weight: 800; color: #fff; margin-bottom: 12px;">
                Still Need <span class="gradient-text">Help?</span>
            </h2>
            <p style="color: rgba(255,255,255,0.7); font-size: 16px; max-width: 500px; margin: 0 auto 28px; line-height: 1.8;">
                Our team is here for you. Don't hesitate to reach out — we're always happy to help.
            </p>
            <a href="#" style="background: linear-gradient(135deg, #F472B6, #DB2777); color: #fff; padding: 16px 44px; border-radius: 50px; font-weight: 700; font-size: 15px; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; box-shadow: 0 8px 35px rgba(219,39,119,0.35); transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 14px 50px rgba(219,39,119,0.5)';" onmouseout="this.style.transform=''; this.style.boxShadow='0 8px 35px rgba(219,39,119,0.35)';">
                <i class="fas fa-headset"></i> Contact Support
            </a>
        </div>
    </div>
</section>

<script>
    // ============================================================
    // FAQ TOGGLE
    // ============================================================
    function toggleFaq(element) {
        const parent = element;
        const answer = parent.querySelector('.faq-answer');
        const icon = parent.querySelector('.faq-icon');
        
        if (answer.style.maxHeight === '0px' || answer.style.maxHeight === '') {
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
    // SCROLL REVEAL
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
        }, { threshold: 0.1 });
        elements.forEach(el => observer.observe(el));
    });
</script>

@endsection