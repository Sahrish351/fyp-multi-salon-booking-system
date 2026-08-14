@extends('layouts.auth')
@section('title', 'Client Registration — Glamora')

@push('styles')
<style>
    /* ── Base ── */
    .auth-page {
        min-height: 100vh;
        background: linear-gradient(160deg, #f8f0f5 0%, #fce4ec 30%, #f3e5f5 60%, #e8eaf6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.2rem;
        margin: 0;
        position: relative;
        overflow: hidden;
    }

    .auth-page::before {
        content: '';
        position: absolute;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(233,30,140,0.06), transparent 70%);
        top: -150px;
        right: -150px;
        pointer-events: none;
    }

    .auth-page::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(201,169,110,0.05), transparent 70%);
        bottom: -100px;
        left: -100px;
        pointer-events: none;
    }

    .register-wrapper {
        max-width: 480px;
        width: 100%;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }

    .auth-card {
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(20px);
        border-radius: 32px;
        box-shadow: 
            0 20px 60px rgba(0,0,0,0.06),
            0 8px 20px rgba(233,30,140,0.04),
            inset 0 1px 0 rgba(255,255,255,0.8);
        padding: 2rem 2rem 1.8rem;
        animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid rgba(255,255,255,0.3);
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
    .auth-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .auth-header .icon-wrapper {
        width: 60px;
        height: 60px;
        background: linear-gradient(145deg, #E91E8C, #c2185b);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.7rem;
        box-shadow: 0 8px 25px rgba(233,30,140,0.2);
        position: relative;
    }

    .auth-header .icon-wrapper::after {
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

    .auth-header .icon-wrapper i {
        font-size: 1.6rem;
        color: white;
    }

    .auth-header h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 0.1rem;
        letter-spacing: -0.3px;
    }

    .auth-header p {
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
    }

    .alert-danger ul {
        margin: 0;
        padding-left: 1rem;
    }

    .alert-danger li {
        margin-bottom: 0.1rem;
    }

    /* ── Form ── */
    .form-group {
        margin-bottom: 0.8rem;
    }

    .form-group label {
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0.3rem;
        display: block;
        font-size: 0.78rem;
        letter-spacing: 0.2px;
    }

    .form-control, .form-select {
        width: 100%;
        padding: 0.65rem 0.9rem;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        font-size: 0.82rem;
        transition: all 0.3s ease;
        background: rgba(255,255,255,0.8);
        color: #1a1a2e;
        appearance: none;
    }

    .form-control:focus, .form-select:focus {
        border-color: #E91E8C;
        box-shadow: 0 0 0 3px rgba(233,30,140,0.06);
        outline: none;
        background: #ffffff;
    }

    .form-control::placeholder {
        color: #b0b0b8;
        font-size: 0.8rem;
    }

    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%236c757d' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.9rem center;
        background-size: 14px 10px;
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(220,53,69,0.06);
    }

    .text-danger {
        color: #dc3545;
        font-size: 0.7rem;
        margin-top: 0.2rem;
        display: block;
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

    /* ── Checkbox ── */
    .form-check {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.8rem;
        margin-bottom: 1rem;
    }

    .form-check-input {
        width: 0.95rem;
        height: 0.95rem;
        cursor: pointer;
        accent-color: #E91E8C;
        border-radius: 4px;
        flex-shrink: 0;
        margin: 0;
    }

    .form-check-label {
        color: #6c757d;
        font-size: 0.78rem;
        cursor: pointer;
        font-weight: 500;
    }

    .form-check-label a {
        color: #E91E8C;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .form-check-label a:hover {
        text-decoration: underline;
    }

    /* ── Register Button ── */
    .btn-register {
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

    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(233,30,140,0.25);
    }

    .btn-register:active {
        transform: translateY(0);
    }

    .btn-register i {
        font-size: 0.9rem;
    }

    /* ── Footer Link ── */
    .footer-link {
        text-align: center;
        margin-top: 1.2rem;
        padding-top: 0.8rem;
        border-top: 1px solid rgba(0,0,0,0.04);
    }

    .footer-link p {
        color: #8e8e9a;
        font-size: 0.78rem;
        margin: 0;
    }

    .footer-link a {
        color: #E91E8C;
        font-weight: 600;
        text-decoration: none;
    }

    .footer-link a:hover {
        text-decoration: underline;
    }

    /* ── Responsive ── */

    /* Tablet */
    @media (max-width: 992px) {
        .auth-card {
            padding: 1.8rem 1.8rem 1.5rem;
        }
        .register-wrapper {
            max-width: 440px;
        }
    }

    /* Mobile */
    @media (max-width: 768px) {
        .auth-page {
            padding: 1rem;
        }

        .auth-card {
            padding: 1.5rem 1.2rem 1.2rem;
            border-radius: 24px;
        }

        .register-wrapper {
            max-width: 100%;
        }

        .auth-header .icon-wrapper {
            width: 50px;
            height: 50px;
        }

        .auth-header .icon-wrapper i {
            font-size: 1.3rem;
        }

        .auth-header h3 {
            font-size: 1.2rem;
        }

        .auth-header p {
            font-size: 0.75rem;
        }

        .auth-header {
            margin-bottom: 1.2rem;
        }

        .form-group {
            margin-bottom: 0.7rem;
        }

        .form-group label {
            font-size: 0.75rem;
            margin-bottom: 0.25rem;
        }

        .form-control, .form-select {
            padding: 0.6rem 0.8rem;
            font-size: 0.8rem;
            border-radius: 10px;
        }

        .form-control::placeholder {
            font-size: 0.75rem;
        }

        .btn-register {
            padding: 0.6rem;
            font-size: 0.82rem;
            border-radius: 10px;
        }

        .form-check {
            margin-top: 0.6rem;
            margin-bottom: 0.8rem;
        }

        .form-check-label {
            font-size: 0.75rem;
        }

        .form-check-input {
            width: 0.9rem;
            height: 0.9rem;
        }

        .footer-link {
            margin-top: 1rem;
            padding-top: 0.6rem;
        }

        .footer-link p {
            font-size: 0.75rem;
        }

        .alert-danger {
            font-size: 0.75rem;
            padding: 0.4rem 0.7rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }

        .text-danger {
            font-size: 0.68rem;
        }

        .strength-bar {
            height: 3px;
        }
    }

    /* Small Mobile */
    @media (max-width: 400px) {
        .auth-card {
            padding: 1.2rem 0.9rem 1rem;
            border-radius: 20px;
        }

        .auth-header .icon-wrapper {
            width: 44px;
            height: 44px;
        }

        .auth-header .icon-wrapper i {
            font-size: 1.1rem;
        }

        .auth-header h3 {
            font-size: 1.05rem;
        }

        .auth-header p {
            font-size: 0.7rem;
        }

        .auth-header {
            margin-bottom: 1rem;
        }

        .form-group {
            margin-bottom: 0.6rem;
        }

        .form-group label {
            font-size: 0.7rem;
        }

        .form-control, .form-select {
            padding: 0.5rem 0.7rem;
            font-size: 0.75rem;
            border-radius: 8px;
        }

        .form-control::placeholder {
            font-size: 0.7rem;
        }

        .btn-register {
            padding: 0.55rem;
            font-size: 0.78rem;
            border-radius: 8px;
        }

        .form-check-label {
            font-size: 0.7rem;
        }

        .form-check-input {
            width: 0.85rem;
            height: 0.85rem;
        }

        .footer-link p {
            font-size: 0.7rem;
        }

        .alert-danger {
            font-size: 0.7rem;
            padding: 0.3rem 0.6rem;
        }
    }
</style>
@endpush

@section('content')
<div class="auth-page">
    <div class="register-wrapper">
        <div class="auth-card">
            <div class="auth-header">
                <div class="icon-wrapper">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h3>Create Client Account</h3>
                <p>Join Glamora and discover beauty</p>
            </div>

            @if($errors->any())
                <div class="alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.client.store') }}" method="POST">
                @csrf

                <!-- Full Name -->
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" placeholder="Enter your full name" required>
                    @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label>Email Address *</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" placeholder="your@email.com" required>
                    @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <!-- Phone -->
                <div class="form-group">
                    <label>Phone Number *</label>
                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                           value="{{ old('phone') }}" placeholder="03xxxxxxxxx" required>
                    @error('phone')<span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <!-- City -->
                <div class="form-group">
                    <label>City *</label>
                    <select name="city" class="form-select @error('city') is-invalid @enderror" required>
                        <option value="">Select your city</option>
                        <option value="Lahore" {{ old('city')=='Lahore' ? 'selected' : '' }}>Lahore</option>
                        <option value="Karachi" {{ old('city')=='Karachi' ? 'selected' : '' }}>Karachi</option>
                        <option value="Islamabad" {{ old('city')=='Islamabad' ? 'selected' : '' }}>Islamabad</option>
                        <option value="Rawalpindi" {{ old('city')=='Rawalpindi' ? 'selected' : '' }}>Rawalpindi</option>
                    </select>
                    @error('city')<span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label>Password *</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Min 8 characters" required oninput="checkStrength(this.value)">
                    <div id="strengthBar" class="strength-bar"></div>
                    <span id="strengthText" style="font-size:0.65rem;font-weight:500;"></span>
                    @error('password')<span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label>Confirm Password *</label>
                    <input type="password" name="password_confirmation" class="form-control" 
                           placeholder="Confirm password" required>
                </div>

                <!-- Terms -->
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="terms" id="terms" required>
                    <label class="form-check-label" for="terms">
                        I agree to the <a href="#">Terms &amp; Conditions</a>
                    </label>
                </div>

                <button type="submit" class="btn-register">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
            </form>

            <div class="footer-link">
                <p>Already have an account? <a href="{{ route('client.login.form') }}">Sign In</a></p>
            </div>
        </div>
    </div>
</div>

<script>
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