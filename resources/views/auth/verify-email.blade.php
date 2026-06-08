@extends('layouts.app')
@section('title', 'Verify Email — Glamora')

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
.btn-resend {
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
.btn-resend:hover {
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
                             style="width:80px;height:80px;background:linear-gradient(135deg,#E91E8C,#9333ea);border-radius:50%;">
                            <i class="fas fa-envelope-open-text fa-3x text-white"></i>
                        </div>
                        <h4 class="fw-bold text-white mb-2" style="font-family:'Playfair Display',serif;">Verify Your Email</h4>
                        <p style="color:rgba(255,255,255,0.6);">
                            We've sent a verification link to <br>
                            <strong style="color:#E91E8C;">{{ Auth::user()->email }}</strong>
                        </p>
                    </div>

                    @if(session('status'))
                    <div class="alert alert-success rounded-3 mb-4">
                        <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn-resend">
                            <i class="fas fa-paper-plane me-2"></i>Resend Verification Email
                        </button>
                    </form>

                    <div class="mt-4">
                        <p class="small" style="color:rgba(255,255,255,0.5);">
                            Didn't receive the email? Check your spam folder or
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                               style="color:#E91E8C;text-decoration:none;">
                                logout and try again
                            </a>
                        </p>
                    </div>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection