
@extends('layouts.app')
@section('title', 'Reset Password — Glamora')

@push('styles')
<style>
.auth-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    display: flex;
    align-items: center;
    padding: 2rem 0;
    position: relative;
    overflow: hidden;
}
.auth-page::before {
    content: '';
    position: absolute;
    top: -30%;
    right: -15%;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(233,30,140,0.15) 0%, transparent 70%);
    border-radius: 50%;
}
.auth-card {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 24px;
    padding: 3rem 2.5rem;
    text-align: center;
}
.auth-card .form-control {
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.12);
    color: #fff;
    border-radius: 12px;
    padding: 0.9rem 1rem 0.9rem 3.2rem;
    font-size: 0.95rem;
}
.auth-card .form-control:focus {
    background: rgba(255,255,255,0.1);
    border-color: #E91E8C;
    box-shadow: 0 0 0 3px rgba(233,30,140,0.2);
}
.input-icon-wrap .icon {
    position: absolute;
    left: 1.2rem;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255,255,255,0.5);
}
.btn-reset {
    background: linear-gradient(135deg, #E91E8C, #c2185b);
    color: #fff;
    border: none;
    border-radius: 50px;
    padding: 0.95rem;
    font-weight: 600;
    font-size: 1.05rem;
    width: 100%;
    transition: all .3s;
}
.btn-reset:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 25px rgba(233,30,140,0.4);
}
</style>
@endpush

@section('content')
<div class="auth-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="auth-card">
                    <div class="mb-5">
                        <div class="mx-auto mb-4 d-flex align-items-center justify-content-center" 
                             style="width:75px;height:75px;background:linear-gradient(135deg,#E91E8C,#9333ea);border-radius:50%;">
                            <i class="fas fa-lock fa-3x text-white"></i>
                        </div>
                        <h4 class="fw-bold text-white mb-2" style="font-family:'Playfair Display',serif;">Reset Your Password</h4>
                        <p style="color:rgba(255,255,255,0.6);">Please create a new strong password for your account.</p>
                    </div>

                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="form-label text-white fw-semibold">Email Address</label>
                            <div class="input-icon-wrap">
                                <span class="icon"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" required placeholder="your@email.com">
                            </div>
                            @error('email')<div class="text-danger mt-1 small">{{ $message }}</div>@enderror
                        </div>

                        <!-- New Password -->
                        <div class="mb-4">
                            <label class="form-label text-white fw-semibold">New Password</label>
                            <div class="input-icon-wrap">
                                <span class="icon"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                                       required placeholder="Min 8 characters" oninput="checkStrength(this.value)">
                            </div>
                            <div class="mt-1">
                                <div id="strengthBar" class="strength-bar" style="background:#ef4444;width:0;"></div>
                                <small id="strengthText" class="text-danger" style="font-size:0.78rem;"></small>
                            </div>
                            @error('password')<div class="text-danger mt-1 small">{{ $message }}</div>@enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label class="form-label text-white fw-semibold">Confirm New Password</label>
                            <div class="input-icon-wrap">
                                <span class="icon"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password_confirmation" class="form-control" 
                                       required placeholder="Repeat new password">
                            </div>
                        </div>

                        <button type="submit" class="btn-reset">
                            <i class="fas fa-check-circle me-2"></i>Reset My Password
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" style="color:#E91E8C;text-decoration:none;">
                            <i class="fas fa-arrow-left me-1"></i>Back to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
@endpush