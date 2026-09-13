@extends('layouts.auth')
@section('title', 'Verify Your Email — Beauty Blush Salons')

@push('styles')
<style>
    .verify-page {
        min-height: 100vh;
        background: linear-gradient(160deg, #f8f0f5 0%, #fce4ec 30%, #f3e5f5 60%, #e8eaf6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
    }

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
        padding: 2.2rem 2rem 1.8rem;
        max-width: 440px;
        width: 100%;
        text-align: center;
        animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid rgba(255,255,255,0.3);
        position: relative;
        z-index: 1;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px) scale(0.97); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .verify-card .icon-wrapper {
        width: 70px;
        height: 70px;
        background: linear-gradient(145deg, #E91E8C, #c2185b);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        box-shadow: 0 8px 25px rgba(233,30,140,0.2);
        position: relative;
    }

    .verify-card .icon-wrapper::after {
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

    .verify-card .icon-wrapper i {
        font-size: 1.7rem;
        color: white;
    }

    .verify-card h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 0.4rem;
        letter-spacing: -0.3px;
    }

    .verify-card p {
        color: #8e8e9a;
        font-size: 0.85rem;
        line-height: 1.5;
        margin-bottom: 1.6rem;
    }

    .verify-card p strong {
        color: #1a1a2e;
        font-weight: 600;
    }

    /* ── OTP boxes ── */
    .otp-group {
        display: flex;
        justify-content: center;
        gap: 0.6rem;
        margin-bottom: 1.4rem;
    }

    .otp-box {
        width: 48px;
        height: 56px;
        border: 2px solid #e9ecef;
        border-radius: 14px;
        text-align: center;
        font-size: 1.4rem;
        font-weight: 700;
        color: #1a1a2e;
        background: rgba(255,255,255,0.8);
        transition: all 0.3s ease;
    }

    .otp-box:focus {
        border-color: #E91E8C;
        box-shadow: 0 0 0 3px rgba(233,30,140,0.08);
        outline: none;
        background: #ffffff;
    }

    .btn-verify {
        width: 100%;
        padding: 0.8rem;
        background: linear-gradient(145deg, #E91E8C, #c2185b);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 1.2rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        letter-spacing: 0.3px;
        box-shadow: 0 4px 15px rgba(233,30,140,0.15);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .btn-verify:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(233,30,140,0.25);
    }

    .resend-row {
        color: #8e8e9a;
        font-size: 0.8rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(0,0,0,0.04);
    }

    .resend-row button {
        background: none;
        border: none;
        color: #E91E8C;
        font-weight: 600;
        font-size: 0.8rem;
        cursor: pointer;
        padding: 0;
    }

    .resend-row button:hover {
        text-decoration: underline;
    }

    .alert-success, .alert-danger {
        border-radius: 12px;
        padding: 0.6rem 0.9rem;
        font-size: 0.8rem;
        margin-bottom: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .alert-success {
        background: rgba(46,204,113,0.08);
        color: #1e8449;
        border: 1px solid rgba(46,204,113,0.2);
    }

    .alert-danger {
        background: rgba(233,30,140,0.05);
        color: #c2185b;
        border: 1px solid rgba(233,30,140,0.12);
    }

    /* ── Responsive ── */
    @media (max-width: 480px) {
        .verify-card {
            padding: 1.8rem 1.4rem 1.4rem;
            border-radius: 24px;
        }

        .verify-card .icon-wrapper {
            width: 60px;
            height: 60px;
        }

        .verify-card h3 {
            font-size: 1.25rem;
        }

        .otp-box {
            width: 40px;
            height: 50px;
            font-size: 1.2rem;
            border-radius: 12px;
        }

        .otp-group {
            gap: 0.4rem;
        }
    }

    @media (max-width: 360px) {
        .otp-box {
            width: 34px;
            height: 44px;
            font-size: 1.05rem;
        }

        .otp-group {
            gap: 0.3rem;
        }
    }
</style>
@endpush

@section('content')
<div class="verify-page">
    <div class="verify-card">
        <div class="icon-wrapper">
            <i class="fas fa-envelope-open-text"></i>
        </div>
        <h3>Verify Your Email</h3>
        <p>We've sent a 6-digit code to <strong>{{ auth()->user()->email }}</strong>. Enter it below to verify your Beauty Blush Salons account.</p>

        @if(session('success'))
            <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert-danger"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
        @endif

        <form action="{{ route('verification.verify') }}" method="POST" id="otpForm">
            @csrf
            <input type="hidden" name="otp" id="otpHidden">

            <div class="otp-group">
                @for ($i = 0; $i < 6; $i++)
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-box" data-index="{{ $i }}" autofocus="{{ $i === 0 ? 'true' : 'false' }}">
                @endfor
            </div>

            <button type="submit" class="btn-verify">
                <i class="fas fa-check-circle"></i> Verify Email
            </button>
        </form>

        <div class="resend-row">
            Didn't get the code?
            <form action="{{ route('verification.send') }}" method="GET" style="display:inline;">
                <button type="submit">Resend Code</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const boxes = document.querySelectorAll('.otp-box');
    const hidden = document.getElementById('otpHidden');
    const form = document.getElementById('otpForm');

    boxes[0].focus();

    boxes.forEach((box, index) => {
        box.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value && index < boxes.length - 1) {
                boxes[index + 1].focus();
            }
            updateHidden();
        });

        box.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && !this.value && index > 0) {
                boxes[index - 1].focus();
            }
        });

        box.addEventListener('paste', function (e) {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
            pasted.split('').forEach((char, i) => {
                if (boxes[i]) boxes[i].value = char;
            });
            updateHidden();
            const nextEmpty = Array.from(boxes).find(b => !b.value);
            (nextEmpty || boxes[boxes.length - 1]).focus();
        });
    });

    function updateHidden() {
        hidden.value = Array.from(boxes).map(b => b.value).join('');
    }

    form.addEventListener('submit', updateHidden);
});
</script>
@endsection