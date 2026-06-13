@extends('layouts.auth')
@section('title', 'Client Login — Glamora')

@push('styles')
<style>
.client-login-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}

.login-card {
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

.login-header {
    text-align: center;
    margin-bottom: 2rem;
}

.login-header .icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #E91E8C, #c2185b);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.login-header .icon i {
    font-size: 2rem;
    color: white;
}

.login-header h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.3rem;
}

.login-header p {
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

.btn-login {
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

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(233,30,140,0.3);
}

.divider {
    display: flex;
    align-items: center;
    text-align: center;
    color: #adb5bd;
    font-size: 0.8rem;
    margin: 1.5rem 0;
}

.divider::before,
.divider::after {
    content: '';
    flex: 1;
    border-bottom: 1px solid #e9ecef;
}

.divider::before {
    margin-right: 1rem;
}

.divider::after {
    margin-left: 1rem;
}

.social-buttons {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.social-btn {
    flex: 1;
    padding: 0.7rem;
    border: 2px solid #e9ecef;
    border-radius: 14px;
    background: white;
    font-weight: 500;
    font-size: 0.85rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.social-btn.google {
    color: #db4437;
}

.social-btn.google:hover {
    background: #db4437;
    color: white;
    border-color: #db4437;
}

.social-btn.facebook {
    color: #4267B2;
}

.social-btn.facebook:hover {
    background: #4267B2;
    color: white;
    border-color: #4267B2;
}

.checkbox-group {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
}

.checkbox-group .left {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.checkbox-group input {
    width: 1rem;
    height: 1rem;
    cursor: pointer;
    accent-color: #E91E8C;
}

.checkbox-group label {
    color: #6c757d;
    font-size: 0.85rem;
    cursor: pointer;
    margin: 0;
}

.forgot-link {
    color: #E91E8C;
    text-decoration: none;
    font-size: 0.85rem;
}

.register-link {
    text-align: center;
    margin-top: 1.5rem;
}

.register-link p {
    color: #6c757d;
    font-size: 0.85rem;
}

.register-link a {
    color: #E91E8C;
    font-weight: 600;
    text-decoration: none;
}

.back-link {
    text-align: center;
    margin-top: 1rem;
}

.back-link a {
    color: #6c757d;
    text-decoration: none;
    font-size: 0.8rem;
}

@media (max-width: 480px) {
    .login-card {
        padding: 1.5rem;
    }
}
</style>
@endpush

@section('content')
<div class="client-login-page">
    <div class="login-card">
        <div class="login-header">
            <div class="icon">
                <i class="fas fa-spa"></i>
            </div>
            <h3>Client Login</h3>
            <p>Welcome back! Please login to your account</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger text-center" style="border-radius: 14px; font-size: 0.85rem;">
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
                <i class="fas fa-sign-in-alt me-2"></i>Sign In
            </button>
        </form>

        <div class="divider">
            <span>Or continue with</span>
        </div>

        <div class="social-buttons">
            <a href="{{ route('auth.google') }}" class="social-btn google" style="text-decoration: none; display: inline-block; text-align: center;">
                <i class="fab fa-google me-2"></i>Google
            </a>
            <button type="button" class="social-btn facebook" onclick="alert('Facebook login coming soon!')">
                <i class="fab fa-facebook-f me-2"></i>Facebook
            </button>
        </div>

        <div class="register-link">
            <p>Don't have an account? <a href="{{ route('register.client') }}">Create Account</a></p>
        </div>

        <div class="back-link">
            <a href="{{ route('select.login') }}">← Back to login options</a>
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