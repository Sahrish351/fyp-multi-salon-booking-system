@extends('layouts.auth')
@section('title', 'Join Glamora — Register')

@push('styles')
<style>
    /* ── Base ── */
    .register-selector-page {
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

    .register-selector-page::before {
        content: '';
        position: absolute;
        width: 600px;
        height: 600px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(233,30,140,0.06), transparent 70%);
        top: -200px;
        left: -200px;
        pointer-events: none;
    }

    .register-selector-page::after {
        content: '';
        position: absolute;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(201,169,110,0.05), transparent 70%);
        bottom: -100px;
        right: -100px;
        pointer-events: none;
    }

    .selector-card {
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(20px);
        border-radius: 40px;
        box-shadow: 
            0 30px 80px rgba(0,0,0,0.08),
            0 10px 30px rgba(233,30,140,0.05),
            inset 0 1px 0 rgba(255,255,255,0.8);
        padding: 3rem 2.5rem;
        max-width: 850px;
        width: 100%;
        margin: 0 auto;
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid rgba(255,255,255,0.3);
        position: relative;
        z-index: 1;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(40px) scale(0.96);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .selector-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .selector-header .icon-wrapper {
        width: 80px;
        height: 80px;
        background: linear-gradient(145deg, #E91E8C, #c2185b);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        box-shadow: 0 12px 35px rgba(233,30,140,0.25);
        position: relative;
    }

    .selector-header .icon-wrapper::after {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 50%;
        background: linear-gradient(145deg, rgba(233,30,140,0.2), rgba(201,169,110,0.2));
        z-index: -1;
        animation: pulseGlow 2s ease-in-out infinite;
    }

    @keyframes pulseGlow {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.6; }
    }

    .selector-header .icon-wrapper i {
        font-size: 2.2rem;
        color: white;
    }

    .selector-header h2 {
        font-family: 'Playfair Display', serif;
        font-size: 2.2rem;
        font-weight: 800;
        background: linear-gradient(135deg, #E91E8C, #C9A96E);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0.3rem;
        letter-spacing: -0.5px;
    }

    .selector-header p {
        color: #8e8e9a;
        font-size: 0.95rem;
        font-weight: 400;
        letter-spacing: 0.3px;
    }

    /* ── Role Cards ── */
    .role-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.2rem;
        margin-bottom: 2rem;
    }

    .role-option {
        background: #faf8f9;
        border-radius: 24px;
        padding: 2rem 1.5rem;
        text-align: center;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        border: 2px solid transparent;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        min-height: 230px;
        position: relative;
        overflow: hidden;
    }

    .role-option::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(145deg, rgba(233,30,140,0.03), rgba(201,169,110,0.02));
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .role-option:hover::before {
        opacity: 1;
    }

    .role-option:hover {
        transform: translateY(-8px);
        border-color: #E91E8C;
        background: #ffffff;
        box-shadow: 0 20px 50px rgba(233,30,140,0.12);
    }

    .role-icon {
        width: 72px;
        height: 72px;
        background: linear-gradient(145deg, rgba(233,30,140,0.08), rgba(201,169,110,0.04));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.8rem;
        flex-shrink: 0;
        transition: all 0.4s ease;
    }

    .role-option:hover .role-icon {
        background: linear-gradient(145deg, rgba(233,30,140,0.15), rgba(201,169,110,0.08));
        transform: scale(1.05);
    }

    .role-icon i {
        font-size: 2rem;
        color: #E91E8C;
        transition: all 0.4s ease;
    }

    .role-option:hover .role-icon i {
        color: #c2185b;
        transform: scale(1.1);
    }

    .role-option h3 {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 0.3rem;
        letter-spacing: -0.3px;
    }

    .role-option p {
        font-size: 0.8rem;
        color: #8e8e9a;
        line-height: 1.5;
        margin-bottom: 0.8rem;
        flex-grow: 1;
        max-width: 220px;
    }

    .role-badge {
        display: inline-block;
        padding: 0.3rem 1.2rem;
        background: rgba(233,30,140,0.08);
        color: #E91E8C;
        border-radius: 50px;
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-top: auto;
        transition: all 0.4s ease;
    }

    .role-option:hover .role-badge {
        background: #E91E8C;
        color: white;
        box-shadow: 0 8px 25px rgba(233,30,140,0.25);
    }

    /* Special badge colors */
    .role-option.client .role-badge {
        background: rgba(233,30,140,0.08);
        color: #E91E8C;
    }
    .role-option.client:hover .role-badge {
        background: #E91E8C;
        color: white;
    }

    .role-option.owner .role-badge {
        background: rgba(201,169,110,0.15);
        color: #C9A96E;
    }
    .role-option.owner:hover .role-badge {
        background: #C9A96E;
        color: white;
    }

    /* ── Footer ── */
    .footer-link {
        text-align: center;
        padding-top: 0.8rem;
        border-top: 1px solid rgba(0,0,0,0.04);
    }

    .footer-link p {
        color: #8e8e9a;
        font-size: 0.85rem;
        margin: 0;
    }

    .footer-link a {
        color: #E91E8C;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
    }

    .footer-link a::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: #E91E8C;
        transition: width 0.3s ease;
    }

    .footer-link a:hover::after {
        width: 100%;
    }

    /* ── Responsive ── */
    @media (max-width: 992px) {
        .selector-card { padding: 2.5rem 1.8rem; }
        .role-grid { gap: 1rem; }
        .role-option { padding: 1.5rem 1.2rem; min-height: 190px; }
        .role-option p { font-size: 0.72rem; }
    }

    @media (max-width: 768px) {
        .register-selector-page { padding: 1rem; }
        .selector-card { padding: 1.8rem 1.2rem; border-radius: 28px; }
        .selector-header .icon-wrapper { width: 60px; height: 60px; }
        .selector-header .icon-wrapper i { font-size: 1.6rem; }
        .selector-header h2 { font-size: 1.6rem; }

        .role-grid {
            grid-template-columns: 1fr;
            gap: 0.8rem;
        }

        .role-option {
            flex-direction: row;
            align-items: center;
            text-align: left;
            padding: 1rem 1.2rem;
            min-height: auto;
            gap: 1rem;
        }

        .role-icon {
            width: 50px;
            height: 50px;
            margin-bottom: 0;
            flex-shrink: 0;
        }
        .role-icon i { font-size: 1.4rem; }

        .role-option .role-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .role-option h3 { font-size: 1rem; margin-bottom: 0.1rem; }
        .role-option p { font-size: 0.72rem; margin-bottom: 0.2rem; max-width: none; }
        .role-badge { align-self: flex-start; font-size: 0.55rem; padding: 0.15rem 0.8rem; margin-top: 0.2rem; }
        .role-option:hover { transform: none; }
    }

    @media (max-width: 400px) {
        .selector-card { padding: 1.2rem 0.8rem; }
        .role-option { padding: 0.8rem 1rem; gap: 0.8rem; }
        .role-icon { width: 40px; height: 40px; }
        .role-icon i { font-size: 1.1rem; }
        .role-option h3 { font-size: 0.85rem; }
        .role-option p { font-size: 0.65rem; }
        .selector-header h2 { font-size: 1.3rem; }
        .footer-link p { font-size: 0.75rem; }
    }
</style>
@endpush

@section('content')
<div class="register-selector-page">
    <div class="selector-card">
        <div class="selector-header">
            <div class="icon-wrapper">
                <i class="fas fa-spa"></i>
            </div>
            <h2>Join Glamora</h2>
            <p>Choose how you want to be part of our beauty community</p>
        </div>

        <div class="role-grid">
            <!-- Register as Client -->
            <a href="{{ route('register.client') }}" class="role-option client">
                <div class="role-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="role-content">
                    <h3>Register as Client</h3>
                    <p>Book appointments, discover salons, and get exclusive beauty offers</p>
                    <span class="role-badge">For Customers</span>
                </div>
            </a>

            <!-- Register as Salon Owner -->
            <a href="{{ route('register.owner') }}" class="role-option owner">
                <div class="role-icon">
                    <i class="fas fa-store"></i>
                </div>
                <div class="role-content">
                    <h3>Register as Salon Owner</h3>
                    <p>List your salon, manage appointments, and grow your business</p>
                    <span class="role-badge">For Business</span>
                </div>
            </a>
        </div>

        <div class="footer-link">
            <p>Already have an account? <a href="{{ route('select.login') }}">Login Here</a></p>
        </div>
    </div>
</div>
@endsection