@extends('layouts.app')
@section('title', 'Forgot Password — Glamora')

@section('content')
<div style="min-height:100vh;background:linear-gradient(135deg,#1a1a2e,#16213e,#0f3460);display:flex;align-items:center;justify-content:center;padding:2rem 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div style="background:rgba(255,255,255,0.05);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.1);border-radius:24px;padding:3rem 2.5rem;">
                    
                    <div class="text-center mb-5">
                        <div class="mx-auto mb-4 d-flex align-items-center justify-content-center" 
                             style="width:75px;height:75px;background:linear-gradient(135deg,#E91E8C,#9333ea);border-radius:50%;">
                            <i class="fas fa-key fa-3x text-white"></i>
                        </div>
                        <h3 class="fw-bold text-white mb-2" style="font-family:'Playfair Display',serif;">Forgot Password?</h3>
                        <p style="color:rgba(255,255,255,0.6);max-width:280px;margin:0 auto;">
                            No problem! Just enter your email and we'll send you a password reset link.
                        </p>
                    </div>

                    @if(session('status'))
                    <div class="alert alert-success text-center rounded-3 mb-4">
                        <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                    </div>
                    @endif

                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label text-white fw-semibold">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0" style="color:#E91E8C;">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" name="email" class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" required autofocus 
                                       placeholder="your@email.com"
                                       style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);color:#fff;border-radius:12px;">
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn w-100 py-3 fw-bold" 
                                style="background:linear-gradient(135deg,#E91E8C,#9333ea);color:#fff;border:none;border-radius:50px;font-size:1.05rem;">
                            <i class="fas fa-paper-plane me-2"></i> Send Reset Link
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" style="color:#E91E8C;text-decoration:none;">
                            <i class="fas fa-arrow-left me-1"></i> Back to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection