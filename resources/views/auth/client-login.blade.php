@extends('layouts.auth')
@section('title', 'Client Login — Glamora')

@push('styles')
<style>
    /* ── Base ── */
    .client-login-page {
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
    .client-login-page::before {
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

    .client-login-page::after {
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

    .login-card {
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
    .login-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .login-header .icon-wrapper {
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

    .login-header .icon-wrapper::after {
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

    .login-header .icon-wrapper i {
        font-size: 1.6rem;
        color: white;
    }

    .login-header h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 0.1rem;
        letter-spacing: -0.3px;
    }

    .login-header p {
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

    /* ── Checkbox ── */
    .checkbox-group {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.2rem;
        gap: 0.5rem;
        flex-wrap: nowrap;
    }

    .checkbox-group .left {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-shrink: 0;
    }

    .checkbox-group input[type="checkbox"] {
        width: 1rem;
        height: 1rem;
        cursor: pointer;
        accent-color: #E91E8C;
        border-radius: 4px;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .checkbox-group label {
        color: #6c757d;
        font-size: 0.8rem;
        cursor: pointer;
        margin: 0;
        font-weight: 500;
        white-space: nowrap;
    }

    .forgot-link {
        color: #8e8e9a;
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.3s ease;
        position: relative;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .forgot-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: #E91E8C;
        transition: width 0.3s ease;
    }

    .forgot-link:hover {
        color: #E91E8C;
    }

    .forgot-link:hover::after {
        width: 100%;
    }

    /* ── Login Button ── */
    .btn-login {
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

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(233,30,140,0.25);
    }

    .btn-login:active {
        transform: translateY(0);
    }

    .btn-login i {
        font-size: 0.9rem;
    }

    /* ── Divider ── */
    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        color: #b0b0b8;
        font-size: 0.75rem;
        margin: 1.2rem 0;
        gap: 0.8rem;
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #e9ecef;
    }

    /* ── Social Buttons ── */
    .social-buttons {
        display: flex;
        gap: 0.8rem;
        margin-bottom: 1.2rem;
    }

    .social-btn {
        flex: 1;
        padding: 0.6rem;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        background: rgba(255,255,255,0.8);
        font-weight: 600;
        font-size: 0.8rem;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        text-align: center;
    }

    .social-btn.google {
        color: #db4437;
        border-color: rgba(219,68,55,0.15);
    }

    .social-btn.google:hover {
        background: #db4437;
        color: white;
        border-color: #db4437;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(219,68,55,0.25);
    }

    .social-btn.facebook {
        color: #4267B2;
        border-color: rgba(66,103,178,0.15);
    }

    .social-btn.facebook:hover {
        background: #4267B2;
        color: white;
        border-color: #4267B2;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(66,103,178,0.25);
    }

    .social-btn i {
        font-size: 0.9rem;
    }

    /* ── Links ── */
    .register-link {
        text-align: center;
        margin-top: 1rem;
    }

    .register-link p {
        color: #8e8e9a;
        font-size: 0.8rem;
        margin: 0;
    }

    .register-link a {
        color: #E91E8C;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
    }

    .register-link a::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: #E91E8C;
        transition: width 0.3s ease;
    }

    .register-link a:hover::after {
        width: 100%;
    }

    .back-link {
        text-align: center;
        margin-top: 1rem;
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
        .login-card {
            padding: 1.8rem 1.8rem 1.4rem;
            max-width: 400px;
        }
    }

    /* Mobile */
    @media (max-width: 768px) {
        .client-login-page {
            padding: 1rem;
        }

        .login-card {
            padding: 1.8rem 1.5rem 1.2rem;
            border-radius: 28px;
            max-width: 100%;
        }

        .login-header .icon-wrapper {
            width: 60px;
            height: 60px;
        }

        .login-header .icon-wrapper i {
            font-size: 1.5rem;
        }

        .login-header h3 {
            font-size: 1.3rem;
        }

        .login-header p {
            font-size: 0.8rem;
        }

        .login-header {
            margin-bottom: 1.2rem;
        }

        .input-wrapper .form-control {
            padding: 0.7rem 0.9rem 0.7rem 2.4rem;
            font-size: 0.85rem;
        }

        .input-wrapper i.input-icon {
            font-size: 0.85rem;
            left: 0.7rem;
        }

        .btn-login {
            padding: 0.7rem;
            font-size: 0.85rem;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.4rem;
            flex-wrap: nowrap;
        }

        .checkbox-group .left {
            flex-shrink: 0;
        }

        .checkbox-group label {
            font-size: 0.78rem;
            white-space: nowrap;
        }

        .forgot-link {
            font-size: 0.78rem;
            flex-shrink: 0;
            white-space: nowrap;
        }

        .social-buttons {
            flex-direction: column;
            gap: 0.5rem;
        }

        .social-btn {
            padding: 0.55rem;
            font-size: 0.78rem;
            border-radius: 10px;
        }

        .register-link p {
            font-size: 0.78rem;
        }

        .back-link a {
            font-size: 0.78rem;
        }

        .divider {
            font-size: 0.72rem;
            margin: 1rem 0;
        }

        .alert-danger {
            font-size: 0.75rem;
            padding: 0.4rem 0.7rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
    }

    /* Small Mobile */
    @media (max-width: 400px) {
        .login-card {
            padding: 1.2rem 1rem 1rem;
            border-radius: 24px;
        }

        .login-header .icon-wrapper {
            width: 50px;
            height: 50px;
        }

        .login-header .icon-wrapper i {
            font-size: 1.3rem;
        }

        .login-header h3 {
            font-size: 1.15rem;
        }

        .login-header p {
            font-size: 0.75rem;
        }

        .login-header {
            margin-bottom: 1rem;
        }

        .form-group {
            margin-bottom: 0.7rem;
        }

        .form-group label {
            font-size: 0.75rem;
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

        .btn-login {
            padding: 0.6rem;
            font-size: 0.8rem;
            border-radius: 10px;
        }

        .checkbox-group label {
            font-size: 0.72rem;
        }

        .forgot-link {
            font-size: 0.72rem;
        }

        .register-link p {
            font-size: 0.72rem;
        }

        .back-link a {
            font-size: 0.72rem;
        }

        .social-btn {
            padding: 0.45rem;
            font-size: 0.72rem;
            border-radius: 8px;
        }

        .alert-danger {
            font-size: 0.7rem;
            padding: 0.3rem 0.6rem;
        }

        .back-link {
            margin-top: 0.8rem;
            padding-top: 0.6rem;
        }

        .divider {
            font-size: 0.65rem;
            margin: 0.8rem 0;
        }
    }
</style>
@endpush

@section('content')
<div class="client-login-page">
    <div class="login-card">
        <div class="login-header">
            <div class="icon-wrapper">
                <i class="fas fa-spa"></i>
            </div>
            <h3>Client Login</h3>
            <p>Welcome back! Please login to your account</p>
        </div>

        @if(session('error'))
            <div class="alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('client.login.submit') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Email Address</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" name="email" class="form-control" placeholder="your@email.com" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="checkbox-group">
                <div class="left">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Remember me</label>
                </div>
                <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>

        <div class="divider">
            <span>Or continue with</span>
        </div>

        <div class="social-buttons">
            <a href="{{ route('auth.google') }}" class="social-btn google">
                <i class="fab fa-google"></i> Google
            </a>
            <a href="{{ route('facebook.redirect') }}" class="social-btn facebook">
                <i class="fab fa-facebook-f"></i> Facebook
            </a>
        </div>

        <div class="register-link">
            <p>Don't have an account? <a href="{{ route('register.client') }}">Create Account</a></p>
        </div>

        <div class="back-link">
            <a href="{{ route('select.login') }}"><i class="fas fa-arrow-left"></i> Back to login options</a>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const password = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    
    if (password.type === 'password') {
        password.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        password.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#E91E8C',
        confirmButtonText: 'Login Now'
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops!',
        text: '{{ session('error') }}',
        confirmButtonColor: '#E91E8C',
        confirmButtonText: 'Try Again'
    });
</script>
@endif

@endsection