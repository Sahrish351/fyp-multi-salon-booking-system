@extends('layouts.auth')
@section('title', 'Verify Email — Glamora')

@push('styles')
<style>
    /* ── Base ── */
    .verify-page {
        min-height: 100vh;
        background: linear-gradient(160deg, #f8f0f5 0%, #fce4ec 30%, #f3e5f5 60%, #e8eaf6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        margin: 0;
        position: relative;
        overflow: hidden;
    }

    /* Decorative circles */
    .verify-page::before {
        content: '';
        position: absolute;
        width: 500px;
        height: 500px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(233,30,140,0.06), transparent 70%);
        top: -150px;
        right: -150px;
        pointer-events: none;
    }

    .verify-page::after {
        content: '';
        position: absolute;
        width: 350px;
        height: 350px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(201,169,110,0.05), transparent 70%);
        bottom: -100px;
        left: -100px;
        pointer-events: none;
    }

    .verify-card {
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(20px);
        border-radius: 32px;
        box-shadow: 
            0 20px 60px rgba(0,0,0,0.06),
            0 8px 20px rgba(233,30,140,0.04),
            inset 0 1px 0 rgba(255,255,255,0.8);
        padding: 2rem 2rem 1.6rem;
        max-width: 420px;
        width: 100%;
        animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid rgba(255,255,255,0.3);
        position: relative;
        z-index: 1;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.97);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* ── Header ── */
    .verify-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .verify-header .icon-wrapper {
        width: 65px;
        height: 65px;
        background: linear-gradient(145deg, #E91E8C, #c2185b);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.6rem;
        box-shadow: 0 8px 25px rgba(233,30,140,0.2);
        position: relative;
    }

    .verify-header .icon-wrapper::after {
        content: '';
        position: absolute;
        inset: -3px;
        border-radius: 50%;
        background: linear-gradient(145deg, rgba(233,30,140,0.15), rgba(201,169,110,0.15));
        z-index: -1;
        animation: pulseGlow 2s ease-in-out infinite;
    }

    @keyframes pulseGlow {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.08); opacity: 0.5; }
    }

    .verify-header .icon-wrapper i {
        font-size: 1.6rem;
        color: white;
    }

    .verify-header h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 0.1rem;
        letter-spacing: -0.3px;
    }

    .verify-header p {
        color: #8e8e9a;
        font-size: 0.8rem;
        font-weight: 400;
        letter-spacing: 0.2px;
        margin: 0;
    }

    .verify-header .email {
        color: #E91E8C;
        font-weight: 600;
        font-size: 0.85rem;
        display: block;
        margin-top: 0.3rem;
    }

    /* ── Alert ── */
    .alert-success {
        background: rgba(16,185,129,0.08);
        border: 1px solid rgba(16,185,129,0.15);
        border-radius: 12px;
        color: #059669;
        font-size: 0.78rem;
        padding: 0.5rem 0.8rem;
        margin-bottom: 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .alert-success i {
        font-size: 1rem;
        color: #059669;
    }

    .alert-danger {
        background: rgba(233,30,140,0.05);
        border: 1px solid rgba(233,30,140,0.12);
        border-radius: 12px;
        color: #c2185b;
        font-size: 0.78rem;
        padding: 0.5rem 0.8rem;
        margin-bottom: 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .alert-danger i {
        font-size: 1rem;
        color: #E91E8C;
    }

    /* ── Resend Button ── */
    .btn-resend {
        width: 100%;
        padding: 0.7rem;
        background: linear-gradient(145deg, #E91E8C, #c2185b);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.88rem;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        letter-spacing: 0.3px;
        box-shadow: 0 4px 15px rgba(233,30,140,0.15);
    }

    .btn-resend:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(233,30,140,0.25);
    }

    .btn-resend:active {
        transform: translateY(0);
    }

    .btn-resend i {
        font-size: 0.9rem;
    }

    /* ── Footer Links ── */
    .footer-links {
        text-align: center;
        margin-top: 1.2rem;
        padding-top: 0.8rem;
        border-top: 1px solid rgba(0,0,0,0.04);
    }

    .footer-links p {
        color: #8e8e9a;
        font-size: 0.75rem;
        margin: 0;
        line-height: 1.6;
    }

    .footer-links a {
        color: #E91E8C;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        position: relative;
    }

    .footer-links a::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: #E91E8C;
        transition: width 0.3s ease;
    }

    .footer-links a:hover::after {
        width: 100%;
    }

    .footer-links .logout-link {
        display: inline-block;
        margin-top: 0.3rem;
    }

    /* ── Responsive ── */

    /* Tablet */
    @media (max-width: 992px) {
        .verify-card {
            padding: 1.8rem 1.8rem 1.4rem;
            max-width: 400px;
        }
    }

    /* Mobile */
    @media (max-width: 768px) {
        .verify-page {
            padding: 1rem;
        }

        .verify-card {
            padding: 1.8rem 1.5rem 1.2rem;
            border-radius: 28px;
            max-width: 100%;
        }

        .verify-header .icon-wrapper {
            width: 60px;
            height: 60px;
        }

        .verify-header .icon-wrapper i {
            font-size: 1.5rem;
        }

        .verify-header h3 {
            font-size: 1.3rem;
        }

        .verify-header p {
            font-size: 0.8rem;
        }

        .verify-header .email {
            font-size: 0.82rem;
        }

        .verify-header {
            margin-bottom: 1.2rem;
        }

        .btn-resend {
            padding: 0.7rem;
            font-size: 0.85rem;
            border-radius: 12px;
        }

        .footer-links p {
            font-size: 0.72rem;
        }

        .footer-links {
            margin-top: 1rem;
            padding-top: 0.7rem;
        }

        .alert-success, .alert-danger {
            font-size: 0.75rem;
            padding: 0.4rem 0.7rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
    }

    /* Small Mobile */
    @media (max-width: 400px) {
        .verify-card {
            padding: 1.2rem 1rem 1rem;
            border-radius: 24px;
        }

        .verify-header .icon-wrapper {
            width: 50px;
            height: 50px;
        }

        .verify-header .icon-wrapper i {
            font-size: 1.3rem;
        }

        .verify-header h3 {
            font-size: 1.15rem;
        }

        .verify-header p {
            font-size: 0.72rem;
        }

        .verify-header .email {
            font-size: 0.75rem;
        }

        .verify-header {
            margin-bottom: 1rem;
        }

        .btn-resend {
            padding: 0.6rem;
            font-size: 0.8rem;
            border-radius: 10px;
        }

        .footer-links p {
            font-size: 0.65rem;
        }

        .footer-links {
            margin-top: 0.8rem;
            padding-top: 0.6rem;
        }

        .alert-success, .alert-danger {
            font-size: 0.7rem;
            padding: 0.3rem 0.6rem;
            border-radius: 8px;
        }
    }
</style>
@endpush

@section('content')
<div class="verify-page">
    <div class="verify-card">
        <div class="verify-header">
            <div class="icon-wrapper">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <h3>Verify Your Email</h3>
            <p>We've sent a verification link to</p>
            <span class="email">{{ Auth::user()->email ?? 'your@email.com' }}</span>
        </div>

        @if(session('status'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('status') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-resend">
                <i class="fas fa-paper-plane"></i> Resend Verification Email
            </button>
        </form>

        <div class="footer-links">
            <p>
                Didn't receive the email? Check your spam folder or
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    logout and try again
                </a>
            </p>
            <a href="{{ route('logout') }}" class="logout-link" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</div>
@endsection