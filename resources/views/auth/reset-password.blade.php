@extends('layouts.auth')
@section('title', 'Reset Password — Glamora')

@push('styles')
<style>
    /* ── Base ── */
    .reset-page {
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
    .reset-page::before {
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

    .reset-page::after {
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

    .reset-card {
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
    .reset-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .reset-header .icon-wrapper {
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

    .reset-header .icon-wrapper::after {
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

    .reset-header .icon-wrapper i {
        font-size: 1.6rem;
        color: white;
    }

    .reset-header h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 0.1rem;
        letter-spacing: -0.3px;
    }

    .reset-header p {
        color: #8e8e9a;
        font-size: 0.8rem;
        font-weight: 400;
        letter-spacing: 0.2px;
        margin: 0;
    }

    /* ── Alert ── */
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

    /* ── Form ── */
    .form-group {
        margin-bottom: 0.9rem;
    }

    .form-group label {
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0.3rem;
        display: block;
        font-size: 0.8rem;
        letter-spacing: 0.2px;
    }

    .input-wrapper {
        position: relative;
    }

    .input-wrapper i.input-icon {
        position: absolute;
        left: 0.9rem;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
        font-size: 0.9rem;
        transition: color 0.3s ease;
        z-index: 2;
    }

    .input-wrapper .form-control {
        width: 100%;
        padding: 0.7rem 1rem 0.7rem 2.6rem;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        background: rgba(255,255,255,0.8);
        color: #1a1a2e;
    }

    .input-wrapper .form-control:focus {
        border-color: #E91E8C;
        box-shadow: 0 0 0 3px rgba(233,30,140,0.06);
        outline: none;
        background: #ffffff;
    }

    .input-wrapper .form-control:focus ~ i.input-icon {
        color: #E91E8C;
    }

    .input-wrapper .form-control::placeholder {
        color: #b0b0b8;
        font-size: 0.8rem;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(220,53,69,0.06);
    }

    .text-danger {
        color: #dc3545;
        font-size: 0.7rem;
        margin-top: 0.2rem;
        display: block;
    }

    .password-toggle {
        position: absolute;
        right: 0.9rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: #adb5bd;
        z-index: 10;
        padding: 0.3rem;
        transition: all 0.3s ease;
        border-radius: 50%;
    }

    .password-toggle:hover {
        color: #E91E8C;
        background: rgba(233,30,140,0.05);
    }

    /* ── Password Strength ── */
    .strength-bar {
        height: 3px;
        border-radius: 3px;
        transition: all 0.3s ease;
        margin-top: 4px;
        background: #e9ecef;
        width: 0;
    }

    #strengthText {
        font-size: 0.65rem;
        font-weight: 500;
        display: block;
        margin-top: 1px;
    }

    /* ── Submit Button ── */
    .btn-reset {
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
        margin-top: 0.2rem;
    }

    .btn-reset:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(233,30,140,0.25);
    }

    .btn-reset:active {
        transform: translateY(0);
    }

    .btn-reset i {
        font-size: 0.9rem;
    }

    /* ── Back Link ── */
    .back-link {
        text-align: center;
        margin-top: 1.2rem;
        padding-top: 0.8rem;
        border-top: 1px solid rgba(0,0,0,0.04);
    }

    .back-link a {
        color: #8e8e9a;
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        position: relative;
    }

    .back-link a::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: #E91E8C;
        transition: width 0.3s ease;
    }

    .back-link a:hover {
        color: #E91E8C;
    }

    .back-link a:hover::after {
        width: 100%;
    }

    /* ── Responsive ── */

    /* Tablet */
    @media (max-width: 992px) {
        .reset-card {
            padding: 1.8rem 1.8rem 1.4rem;
            max-width: 400px;
        }
    }

    /* Mobile */
    @media (max-width: 768px) {
        .reset-page {
            padding: 1rem;
        }

        .reset-card {
            padding: 1.8rem 1.5rem 1.2rem;
            border-radius: 28px;
            max-width: 100%;
        }

        .reset-header .icon-wrapper {
            width: 60px;
            height: 60px;
        }

        .reset-header .icon-wrapper i {
            font-size: 1.5rem;
        }

        .reset-header h3 {
            font-size: 1.3rem;
        }

        .reset-header p {
            font-size: 0.8rem;
        }

        .reset-header {
            margin-bottom: 1.2rem;
        }

        .form-group {
            margin-bottom: 0.8rem;
        }

        .form-group label {
            font-size: 0.78rem;
        }

        .input-wrapper .form-control {
            padding: 0.7rem 0.9rem 0.7rem 2.4rem;
            font-size: 0.85rem;
            border-radius: 12px;
        }

        .input-wrapper i.input-icon {
            font-size: 0.85rem;
            left: 0.8rem;
        }

        .btn-reset {
            padding: 0.7rem;
            font-size: 0.85rem;
            border-radius: 12px;
        }

        .back-link a {
            font-size: 0.78rem;
        }

        .back-link {
            margin-top: 1rem;
            padding-top: 0.7rem;
        }

        .alert-danger, .alert-success {
            font-size: 0.75rem;
            padding: 0.4rem 0.7rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
    }

    /* Small Mobile */
    @media (max-width: 400px) {
        .reset-card {
            padding: 1.2rem 1rem 1rem;
            border-radius: 24px;
        }

        .reset-header .icon-wrapper {
            width: 50px;
            height: 50px;
        }

        .reset-header .icon-wrapper i {
            font-size: 1.3rem;
        }

        .reset-header h3 {
            font-size: 1.15rem;
        }

        .reset-header p {
            font-size: 0.72rem;
        }

        .reset-header {
            margin-bottom: 1rem;
        }

        .form-group label {
            font-size: 0.72rem;
        }

        .input-wrapper .form-control {
            padding: 0.6rem 0.8rem 0.6rem 2.2rem;
            font-size: 0.8rem;
            border-radius: 10px;
        }

        .input-wrapper i.input-icon {
            font-size: 0.8rem;
            left: 0.7rem;
        }

        .btn-reset {
            padding: 0.6rem;
            font-size: 0.8rem;
            border-radius: 10px;
        }

        .back-link a {
            font-size: 0.72rem;
        }

        .back-link {
            margin-top: 0.8rem;
            padding-top: 0.6rem;
        }

        .alert-danger, .alert-success {
            font-size: 0.7rem;
            padding: 0.3rem 0.6rem;
            border-radius: 8px;
        }
    }
</style>
@endpush

@section('content')
<div class="reset-page">
    <div class="reset-card">
        <div class="reset-header">
            <div class="icon-wrapper">
                <i class="fas fa-lock"></i>
            </div>
            <h3>Reset Your Password</h3>
            <p>Please create a new strong password for your account</p>
        </div>

        @if(session('error'))
            <div class="alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email -->
            <div class="form-group">
                <label>Email Address</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ $email ?? old('email') }}" required placeholder="your@email.com">
                </div>
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- New Password -->
            <div class="form-group">
                <label>New Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                           required placeholder="Min 8 characters" oninput="checkStrength(this.value)">
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="fas fa-eye" id="eyeIconPassword"></i>
                    </button>
                </div>
                <div id="strengthBar" class="strength-bar"></div>
                <span id="strengthText" style="font-size:0.65rem;font-weight:500;"></span>
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label>Confirm New Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" 
                           required placeholder="Repeat new password">
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                        <i class="fas fa-eye" id="eyeIconConfirm"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-reset">
                <i class="fas fa-check-circle"></i> Reset My Password
            </button>
        </form>

        <div class="back-link">
            <a href="{{ route('client.login.form') }}">
                <i class="fas fa-arrow-left"></i> Back to Login
            </a>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const eyeIcon = document.getElementById(fieldId === 'password' ? 'eyeIconPassword' : 'eyeIconConfirm');
    
    if (field.type === 'password') {
        field.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}

function checkStrength(password) {
    const bar = document.getElementById('strengthBar');
    const text = document.getElementById('strengthText');
    
    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    
    const percentage = (strength / 5) * 100;
    const colors = ['#dc3545', '#fd7e14', '#ffc107', '#20c997', '#28a745'];
    const messages = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
    const index = Math.min(Math.floor(percentage / 20), 4);
    
    bar.style.width = percentage + '%';
    bar.style.backgroundColor = colors[index];
    text.textContent = password.length > 0 ? messages[index] : '';
    text.style.color = colors[index];
}
</script>
@endsection