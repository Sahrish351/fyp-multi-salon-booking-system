@extends('layouts.app')
@section('title', 'Contact Us - Beauty Blush Salons')

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
        0% { opacity: 0; transform: translateY(40px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .animate-float { animation: float 8s ease-in-out infinite; }
    .animate-float-reverse { animation: floatReverse 10s ease-in-out infinite; }
    .animate-slide-up { animation: slideUp 0.6s ease forwards; }

    .delay-1 { animation-delay: 0.05s; }
    .delay-2 { animation-delay: 0.1s; }
    .delay-3 { animation-delay: 0.15s; }
    .delay-4 { animation-delay: 0.2s; }

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

    .contact-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px 20px;
        border: 1px solid rgba(236,72,153,0.06);
        box-shadow: 0 2px 12px rgba(0,0,0,0.02);
        transition: all 0.3s ease;
        height: 100%;
        text-align: center;
    }
    .contact-card:hover {
        border-color: rgba(236,72,153,0.12);
        box-shadow: 0 6px 24px rgba(236,72,153,0.04);
        transform: translateY(-3px);
    }
    .contact-card .icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        font-size: 20px;
    }
    .contact-card h5 {
        font-size: 15px;
        font-weight: 700;
        color: #1a0a14;
        margin-bottom: 2px;
    }
    .contact-card p {
        font-size: 13px;
        color: #888;
        margin: 0;
        line-height: 1.6;
    }
    .contact-card a {
        color: #EC4899;
        text-decoration: none;
        font-weight: 600;
    }
    .contact-card a:hover {
        color: #DB2777;
    }

    .form-control-custom {
        width: 100%;
        padding: 12px 16px;
        border: 1.5px solid #FFE8F0;
        border-radius: 10px;
        font-size: 14px;
        color: #1a0a14;
        outline: none;
        background: #fff;
        transition: all 0.3s ease;
    }
    .form-control-custom:focus {
        border-color: #EC4899;
        box-shadow: 0 0 0 4px rgba(236,72,153,0.06);
        transform: translateY(-1px);
    }

    .form-label-custom {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #1a0a14;
        margin-bottom: 4px;
    }
    .form-label-custom .required {
        color: #EC4899;
    }

    .map-container {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(236,72,153,0.06);
        box-shadow: 0 2px 12px rgba(0,0,0,0.02);
        height: 100%;
        min-height: 480px;
        transition: all 0.3s ease;
        background: #f5f5f5;
    }
    .map-container:hover {
        border-color: rgba(236,72,153,0.12);
        box-shadow: 0 6px 24px rgba(236,72,153,0.04);
    }
    .map-container iframe {
        display: block;
        width: 100%;
        height: 100%;
        min-height: 480px;
        border: 0;
    }

    .form-container {
        background: #FFF9FC;
        border-radius: 16px;
        padding: 32px 28px;
        border: 1px solid rgba(236,72,153,0.06);
        box-shadow: 0 2px 12px rgba(0,0,0,0.02);
        height: 100%;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }
    .form-container:hover {
        border-color: rgba(236,72,153,0.12);
        box-shadow: 0 6px 24px rgba(236,72,153,0.04);
    }

    @media (max-width: 768px) {
        .contact-card {
            padding: 18px 14px;
        }
        .contact-card .icon {
            width: 42px;
            height: 42px;
            font-size: 16px;
        }
        .form-container {
            padding: 24px 18px;
        }
        .map-container {
            min-height: 300px;
        }
        .map-container iframe {
            min-height: 300px;
        }
    }
    @media (max-width: 576px) {
        .contact-card {
            padding: 14px 12px;
        }
        .contact-card .icon {
            width: 36px;
            height: 36px;
            font-size: 14px;
        }
        .form-container {
            padding: 18px 14px;
        }
        .map-container {
            min-height: 250px;
        }
        .map-container iframe {
            min-height: 250px;
        }
    }
</style>

<!-- PAGE HEADER - Simple without hero card -->
<section class="page-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center animate-slide-up">
                <div style="display: inline-block; background: rgba(236,72,153,0.08); color: #EC4899; padding: 4px 16px; border-radius: 50px; font-size: 12px; font-weight: 600; letter-spacing: 1px; margin-bottom: 10px; border: 1px solid rgba(236,72,153,0.1);">
                    <i class="fas fa-envelope me-1"></i> Get In Touch
                </div>
                <h1 style="font-size: 34px; font-weight: 900; color: #1a0a14; margin-bottom: 6px;">Contact <span class="gradient-text">Us</span></h1>
                <p style="font-size: 16px; color: #888; margin: 0 auto; max-width: 500px; line-height: 1.7;">Have a question, feedback, or need help? Our friendly team is here for you.</p>
            </div>
        </div>
    </div>
</section>



<!-- CONTACT FORM + MAP - EQUAL SIZE -->
<section style="padding: 30px 0 60px; background: #fff;">
    <div class="container">
        <div class="row g-4 align-items-stretch">

            <!-- FORM -->
            <div class="col-lg-6 animate-slide-up">
                <div class="form-container">
                    <h3 style="font-size: 20px; font-weight: 800; color: #1a0a14; margin-bottom: 2px;">Send a <span style="color: #EC4899;">Message</span></h3>
                    <p style="color: #999; font-size: 13px; margin-bottom: 18px;">Fill in the form and we'll get back to you shortly.</p>

                    @if(session('success'))
                        <div style="background: #ECFDF5; color: #065F46; padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; border: 1px solid #A7F3D0; display: flex; align-items: center; gap: 8px; font-size: 14px;">
                            <i class="fas fa-check-circle" style="color: #10B981; font-size: 16px;"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div style="background: #FEF2F2; color: #991B1B; padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; border: 1px solid #FCA5A5; font-size: 14px;">
                            <i class="fas fa-exclamation-circle me-2"></i> Please fix the errors below.
                        </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST" style="flex: 1; display: flex; flex-direction: column;">
                        @csrf
                        <div style="display: flex; flex-direction: column; gap: 12px; flex: 1;">
                            <div>
                                <label class="form-label-custom">Your Name <span class="required">*</span></label>
                                <input type="text" name="name" class="form-control-custom" placeholder="John Doe" value="{{ old('name') }}" required>
                                @error('name')
                                    <p style="color: #EF4444; font-size: 12px; margin-top: 3px;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="form-label-custom">Email Address <span class="required">*</span></label>
                                <input type="email" name="email" class="form-control-custom" placeholder="hello@example.com" value="{{ old('email') }}" required>
                                @error('email')
                                    <p style="color: #EF4444; font-size: 12px; margin-top: 3px;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="form-label-custom">Phone Number</label>
                                <input type="tel" name="phone" class="form-control-custom" placeholder="+92 300 1234567" value="{{ old('phone') }}">
                                @error('phone')
                                    <p style="color: #EF4444; font-size: 12px; margin-top: 3px;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="form-label-custom">Subject <span class="required">*</span></label>
                                <input type="text" name="subject" class="form-control-custom" placeholder="How can we help you?" value="{{ old('subject') }}" required>
                                @error('subject')
                                    <p style="color: #EF4444; font-size: 12px; margin-top: 3px;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div style="flex: 1;">
                                <label class="form-label-custom">Message <span class="required">*</span></label>
                                <textarea name="message" class="form-control-custom" rows="4" placeholder="Tell us about your inquiry..." required style="resize: vertical; font-family: inherit; min-height: 80px;">{{ old('message') }}</textarea>
                                @error('message')
                                    <p style="color: #EF4444; font-size: 12px; margin-top: 3px;">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" style="background: linear-gradient(135deg, #F472B6, #DB2777); color: #fff; padding: 14px; border-radius: 10px; font-weight: 700; font-size: 15px; border: none; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 20px rgba(219,39,119,0.2); margin-top: 4px;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 30px rgba(219,39,119,0.35)';" onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 20px rgba(219,39,119,0.2)';">
                                <i class="fas fa-paper-plane me-2"></i> Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- MAP - EQUAL SIZE -->
            <div class="col-lg-6 animate-slide-up delay-2">
                <div style="background: #FFF9FC; border-radius: 16px; padding: 32px 28px; border: 1px solid rgba(236,72,153,0.06); box-shadow: 0 2px 12px rgba(0,0,0,0.02); height: 100%; display: flex; flex-direction: column; transition: all 0.3s ease;" onmouseover="this.style.borderColor='rgba(236,72,153,0.12)'; this.style.boxShadow='0 6px 24px rgba(236,72,153,0.04)';" onmouseout="this.style.borderColor='rgba(236,72,153,0.06)'; this.style.boxShadow='0 2px 12px rgba(0,0,0,0.02)';">
                    <h3 style="font-size: 20px; font-weight: 800; color: #1a0a14; margin-bottom: 2px;">Find <span style="color: #EC4899;">Us</span></h3>
                    <p style="color: #999; font-size: 13px; margin-bottom: 14px;">Visit our office or get in touch with us.</p>

                    <div class="map-container" style="flex: 1; min-height: 380px; border-radius: 12px; overflow: hidden; border: 1px solid rgba(236,72,153,0.06); background: #f0f0f0;">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d217421.68792101564!2d74.25309344711804!3d31.48282093178553!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39190483e4a07b5d%3A0x9633fec5e27f2686!2sGulberg%20III%2C%20Lahore%2C%20Punjab%2C%20Pakistan!5e0!3m2!1sen!2s!4v1700000000000!5m2!1sen!2s" 
                            allowfullscreen="" 
                            loading="lazy"
                            style="width: 100%; height: 100%; min-height: 380px; border: 0; display: block;">
                        </iframe>
                    </div>

                    <div style="margin-top: 14px; display: flex; flex-wrap: wrap; gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 8px; background: #fff; padding: 8px 14px; border-radius: 8px; border: 1px solid #eee; font-size: 13px; color: #555;">
                            <i class="fas fa-map-marker-alt" style="color: #EC4899; font-size: 14px;"></i>
                            Gulberg III, Lahore, Pakistan
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px; background: #fff; padding: 8px 14px; border-radius: 8px; border: 1px solid #eee; font-size: 13px; color: #555;">
                            <i class="fas fa-clock" style="color: #EC4899; font-size: 14px;"></i>
                            Mon–Sat: 9am–7pm
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- FAQ SECTION -->
<section style="padding: 50px 0; background: #FAFAFA;">
    <div class="container">
        <div class="text-center mb-4 animate-slide-up">
            <span style="color: #EC4899; font-weight: 600; font-size: 12px; letter-spacing: 2px; text-transform: uppercase; display: block; margin-bottom: 4px;">Quick Answers</span>
            <h2 style="font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 800; color: #1a0a14;">Frequently Asked <span style="color: #EC4899;">Questions</span></h2>
        </div>

        <div class="row g-3 justify-content-center">
            <div class="col-lg-10">
                <div class="row g-3">
                    @php
                        $faqs = [
                            ['icon' => 'fa-clock', 'q' => 'What are your working hours?', 'a' => 'Monday to Saturday: 9:00 AM – 7:00 PM<br>Sunday: 10:00 AM – 4:00 PM'],
                            ['icon' => 'fa-calendar-check', 'q' => 'Do I need an appointment?', 'a' => 'Yes, appointments are recommended to ensure service availability and avoid waiting time.'],
                            ['icon' => 'fa-credit-card', 'q' => 'What payment methods do you accept?', 'a' => 'We accept Cash, Credit/Debit Cards, Easypaisa, JazzCash, and Bank Transfers.'],
                            ['icon' => 'fa-undo-alt', 'q' => 'What is your cancellation policy?', 'a' => 'Free cancellation up to 24 hours before appointment. Late cancellations may incur a fee.'],
                        ];
                    @endphp
                    @foreach($faqs as $faq)
                        <div class="col-md-6 animate-slide-up" style="animation-delay: {{ 0.1 + $loop->index * 0.05 }}s;">
                            <div style="background: #fff; border-radius: 14px; padding: 16px 20px; border: 1px solid #FFE8F0; box-shadow: 0 2px 8px rgba(0,0,0,0.02); transition: all 0.3s ease; height: 100%;" onmouseover="this.style.borderColor='#EC4899'; this.style.boxShadow='0 4px 16px rgba(236,72,153,0.04)';" onmouseout="this.style.borderColor='#FFE8F0'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.02)';">
                                <div style="display: flex; gap: 12px;">
                                    <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(236,72,153,0.08); display: flex; align-items: center; justify-content: center; color: #EC4899; flex-shrink: 0; font-size: 14px;">
                                        <i class="fas {{ $faq['icon'] }}"></i>
                                    </div>
                                    <div>
                                        <h6 style="font-size: 14px; font-weight: 700; color: #1a0a14; margin-bottom: 3px;">{{ $faq['q'] }}</h6>
                                        <p style="font-size: 13px; color: #888; margin: 0; line-height: 1.6;">{!! $faq['a'] !!}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA SECTION -->
<section style="background: linear-gradient(135deg, #0a0508 0%, #1a0a14 30%, #3d1a2e 60%, #6b2147 100%); padding: 40px 0; text-align: center;">
    <div class="container">
        <div class="animate-slide-up">
            <h2 style="font-size: clamp(1.4rem, 2.5vw, 1.8rem); font-weight: 800; color: #fff; margin-bottom: 6px;">
                Ready to <span class="gradient-text">Experience</span> Beauty Blush?
            </h2>
            <p style="color: rgba(255,255,255,0.7); font-size: 14px; max-width: 400px; margin: 0 auto 16px; line-height: 1.6;">
                Join thousands of happy clients who trust us for their beauty needs.
            </p>
            <a href="{{ route('salons.index') }}" style="background: linear-gradient(135deg, #F472B6, #DB2777); color: #fff; padding: 12px 34px; border-radius: 50px; font-weight: 700; font-size: 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 6px 25px rgba(219,39,119,0.3); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 40px rgba(219,39,119,0.4)';" onmouseout="this.style.transform=''; this.style.boxShadow='0 6px 25px rgba(219,39,119,0.3)';">
                <i class="fas fa-calendar-check"></i> Book Now
            </a>
        </div>
    </div>
</section>

@endsection