@extends('layouts.app')
@section('title', 'Support - Beauty Blush Salons')

@section('content')

<style>
    .support-page-wrap {
        background: linear-gradient(180deg, #FFF9FC 0%, #FDEAF3 100%);
        padding: 50px 0 60px;
        min-height: 80vh;
    }

    .support-top {
        text-align: center;
        margin-bottom: 32px;
    }
    .support-top .icon-circle {
        width: 60px; height: 60px; border-radius: 18px;
        background: linear-gradient(135deg, #F472B6, #DB2777);
        color: #fff; display: flex; align-items: center; justify-content: center;
        font-size: 24px; margin: 0 auto 14px;
        box-shadow: 0 8px 24px rgba(219,39,119,0.25);
    }
    .support-top h1 {
        font-size: 1.7rem; font-weight: 800; color: #1a0a14; margin-bottom: 8px;
    }
    .support-top p {
        font-size: 14px; color: #888; margin: 0;
        max-width: 420px; margin: 0 auto;
    }

    .ticket-card {
        background: #fff;
        border-radius: 20px;
        padding: 32px;
        border: 1px solid #FFE8F0;
        box-shadow: 0 8px 30px rgba(236,72,153,0.06);
        height: 100%;
    }

    .form-input {
        width: 100%;
        padding: 11px 14px;
        border: 1.5px solid #FFE8F0;
        border-radius: 10px;
        font-size: 13.5px;
        color: #1a0a14;
        outline: none;
        background: #fff;
        transition: all 0.2s ease;
    }
    .form-input:focus {
        border-color: #EC4899;
        box-shadow: 0 0 0 3px rgba(236,72,153,0.08);
    }
    .form-label {
        display: block;
        font-size: 12.5px;
        font-weight: 600;
        color: #1a0a14;
        margin-bottom: 5px;
    }
    .form-label .required { color: #EC4899; }

    /* Side panel */
    .side-panel {
        background: linear-gradient(160deg, #2d1220 0%, #4a1830 100%);
        border-radius: 20px;
        padding: 30px 26px;
        color: #fff;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .side-panel h3 {
        font-size: 1.05rem;
        font-weight: 800;
        margin-bottom: 6px;
    }
    .side-panel > p {
        font-size: 13px;
        color: rgba(255,255,255,0.65);
        margin-bottom: 22px;
        line-height: 1.6;
    }

    .side-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 18px;
    }
    .side-item .si-icon {
        width: 36px; height: 36px; border-radius: 10px;
        background: rgba(244,114,182,0.15);
        color: #F9A8D4;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; flex-shrink: 0;
    }
    .side-item strong { display: block; font-size: 13.5px; color: #fff; margin-bottom: 2px; }
    .side-item span { font-size: 12px; color: rgba(255,255,255,0.6); }

    .side-panel .wa-btn {
        margin-top: auto;
        background: linear-gradient(135deg, #25D366, #128C7E);
        color: #fff;
        text-decoration: none;
        padding: 12px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 13.5px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: opacity 0.2s ease;
    }
    .side-panel .wa-btn:hover { opacity: 0.9; color: #fff; }

    @media (max-width: 992px) {
        .side-panel { margin-top: 20px; }
    }
</style>

<div class="support-page-wrap">
    <div class="container">

        <div class="support-top">
            <div class="icon-circle"><i class="fas fa-headset"></i></div>
            <h1>Contact Support</h1>
            <p>Have a question or an issue? Fill out the form below and our team will get back to you soon.</p>
        </div>

        <div class="row g-4 justify-content-center">

            <div class="col-lg-7">
                <div class="ticket-card">

                    @if(session('success'))
                        <div style="background:#ECFDF5; color:#065F46; padding:10px 14px; border-radius:10px; margin-bottom:16px; border:1px solid #A7F3D0; font-size:13px;">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div style="background:#FEF2F2; color:#991B1B; padding:10px 14px; border-radius:10px; margin-bottom:16px; border:1px solid #FCA5A5; font-size:13px;">
                            <i class="fas fa-exclamation-circle"></i> Please fix the errors below.
                        </div>
                    @endif

                    <form action="{{ route('support.submit') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name <span class="required">*</span></label>
                                <input type="text" name="name" class="form-input" placeholder="e.g. Aisha Malik" value="{{ old('name') }}" required>
                                @error('name')<p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email <span class="required">*</span></label>
                                <input type="email" name="email" class="form-input" placeholder="your@email.com" value="{{ old('email', request('email')) }}" required>
                                @error('email')<p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="tel" name="phone" class="form-input" placeholder="+92 306 9734142" value="{{ old('phone') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Category <span class="required">*</span></label>
                                <select name="category" class="form-input" required style="cursor:pointer;">
                                    <option value="">Select category</option>
                                    <option value="Booking" {{ old('category')=='Booking'?'selected':'' }}>Booking Issue</option>
                                    <option value="Payment" {{ old('category')=='Payment'?'selected':'' }}>Payment Problem</option>
                                    <option value="Salon" {{ old('category')=='Salon'?'selected':'' }}>Salon Related</option>
                                    <option value="Account" {{ old('category')=='Account'?'selected':'' }}>Account Help</option>
                                    <option value="Technical" {{ old('category')=='Technical'?'selected':'' }}>Technical Issue</option>
                                    <option value="Other" {{ old('category')=='Other'?'selected':'' }}>Other</option>
                                </select>
                                @error('category')<p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Message <span class="required">*</span></label>
                                <textarea name="message" class="form-input" rows="4" placeholder="Describe your issue in detail..." required style="resize:vertical;">{{ old('message') }}</textarea>
                                @error('message')<p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p>@enderror
                            </div>

                            <div class="col-12">
                                <button type="submit" style="background:linear-gradient(135deg,#F472B6,#DB2777); color:#fff; padding:12px; width:100%; border-radius:10px; font-weight:700; font-size:14.5px; border:none; cursor:pointer; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                                    <i class="fas fa-paper-plane me-1"></i> Submit Ticket
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

            <div class="col-lg-4">
                <div class="side-panel">
                    <h3>Need help fast?</h3>
                    <p>Here's what to expect when you reach out to us.</p>

                    <div class="side-item">
                        <div class="si-icon"><i class="fas fa-clock"></i></div>
                        <div>
                            <strong>Response Time</strong>
                            <span>Email: within 12 hours · WhatsApp: within 2 hours</span>
                        </div>
                    </div>

                    <div class="side-item">
                        <div class="si-icon"><i class="fas fa-calendar-check"></i></div>
                        <div>
                            <strong>Business Hours</strong>
                            <span>Mon–Sat: 9AM–7PM · Sun: 10AM–4PM</span>
                        </div>
                    </div>

                    <div class="side-item">
                        <div class="si-icon"><i class="fas fa-shield-heart"></i></div>
                        <div>
                            <strong>Our Promise</strong>
                            <span>Friendly, professional support — every time</span>
                        </div>
                    </div>

                    <a href="https://wa.me/923069734142" target="_blank" class="wa-btn">
                        <i class="fab fa-whatsapp"></i> Chat on WhatsApp Instead
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@endsection