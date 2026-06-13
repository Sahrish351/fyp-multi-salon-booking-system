@extends('layouts.auth')
@section('title', 'Reset Password — Glamora')

@push('styles')
<style>
.reset-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}

.reset-card {
    background: #ffffff;
    border-radius: 28px;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15);
    padding: 2.5rem;
    max-width: 450px;
    width: 100%;
    animation: fadeInUp 0.5s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.reset-header {
    text-align: center;
    margin-bottom: 2rem;
}

.reset-header .icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #E91E8C, #c2185b);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.reset-header .icon i {
    font-size: 2rem;
    color: white;
}

.reset-header h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.3rem;
}

.reset-header p {
    color: #6c757d;
    font-size: 0.85rem;
}

.form-group {
    margin-bottom: 1.2rem;
}

.form-group label {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    display: block;
    font-size: 0.85rem;
}

.input-wrapper {
    position: relative;
}

.input-wrapper i.input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #adb5bd;
    font-size: 1rem;
    z-index: 1;
}

.input-wrapper .form-control {
    width: 100%;
    padding: 0.85rem 3rem 0.85rem 2.8rem;
    border: 2px solid #e9ecef;
    border-radius: 14px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.input-wrapper .form-control:focus {
    border-color: #E91E8C;
    box-shadow: 0 0 0 3px rgba(233,30,140,0.1);
    outline: none;
}

.password-toggle {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: #adb5bd;
    z-index: 10;
    padding: 0;
}

.password-toggle:hover {
    color: #E91E8C;
}

.btn-reset {
    width: 100%;
    padding: 0.85rem;
    background: linear-gradient(135deg, #E91E8C, #c2185b);
    color: white;
    border: none;
    border-radius: 14px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-reset:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(233,30,140,0.3);
}

.back-link {
    text-align: center;
    margin-top: 1.5rem;
}

.back-link a {
    color: #E91E8C;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 500;
}

.back-link a:hover {
    text-decoration: underline;
}

.strength-bar {
    height: 4px;
    border-radius: 2px;
    transition: all 0.3s ease;
    margin-top: 5px;
}

#strengthText {
    font-size: 0.7rem;
}

@media (max-width: 480px) {
    .reset-card {
        padding: 1.5rem;
    }
}
</style>
@endpush

@section('content')
<div class="reset-page">
    <div class="reset-card">
        <div class="reset-header">
            <div class="icon">
                <i class="fas fa-lock"></i>
            </div>
            <h3>Reset Your Password</h3>
            <p>Please create a new strong password for your account.</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger text-center" style="border-radius: 14px; font-size: 0.85rem;">
                {{ session('error') }}
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
                    <div class="text-danger mt-1 small">{{ $message }}</div>
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
                <div class="mt-1">
                    <div id="strengthBar" class="strength-bar" style="background:#ef4444; width:0;"></div>
                    <small id="strengthText" class="text-danger" style="font-size:0.7rem;"></small>
                </div>
                @error('password')
                    <div class="text-danger mt-1 small">{{ $message }}</div>
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
                <i class="fas fa-check-circle me-2"></i>Reset My Password
            </button>
        </form>

        <div class="back-link">
            <a href="{{ route('client.login.form') }}">
                <i class="fas fa-arrow-left me-1"></i>Back to Login
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
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;

    const colors = ['#ef4444', '#f97316', '#eab308', '#22c55e'];
    const labels = ['Weak', 'Fair', 'Good', 'Strong'];

    if (password.length > 0) {
        bar.style.width = (strength * 25) + '%';
        bar.style.background = colors[strength - 1] || '#ef4444';
        text.textContent = labels[strength - 1] || 'Weak';
        text.style.color = colors[strength - 1] || '#ef4444';
    } else {
        bar.style.width = '0%';
        text.textContent = '';
    }
}
</script>
@endsection