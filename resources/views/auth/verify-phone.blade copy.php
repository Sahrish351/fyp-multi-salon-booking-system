@extends('layouts.app')
@section('title', 'Verify Phone — Glamora')

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
.btn-verify {
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
.btn-verify:hover {
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
                            <i class="fas fa-mobile-alt fa-3x text-white"></i>
                        </div>
                        <h4 class="fw-bold text-white mb-2" style="font-family:'Playfair Display',serif;">Verify Your Phone</h4>
                        <p style="color:rgba(255,255,255,0.6);">
                            We'll send a 6-digit verification code to your phone number.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('phone.verify.send') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label text-white fw-semibold">Phone Number</label>
                            <div class="input-icon-wrap">
                                <span class="icon"><i class="fas fa-phone"></i></span>
                                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', Auth::user()->phone ?? '') }}" 
                                       placeholder="+92 300 1234567" required>
                            </div>
                            @error('phone')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn-verify">
                            <i class="fas fa-paper-plane me-2"></i>Send Verification Code
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