@extends('layouts.app')

@section('title', 'Confirm Password - Glamora')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5" 
     style="background: linear-gradient(135deg, #f5e6f5 0%, #fce8f3 100%);">
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card border-0 shadow-lg" style="border-radius: 24px; overflow: hidden;">
                    <div class="card-body p-5">
                        
                        <div class="text-center mb-5">
                            <div class="mx-auto mb-4" style="width: 70px; height: 70px; background: linear-gradient(135deg, #E91E8C, #9333ea); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-shield-alt fa-3x text-white"></i>
                            </div>
                            <h3 class="fw-bold mb-2" style="font-family: 'Playfair Display', serif; color: #1a1a1a;">
                                Confirm Password
                            </h3>
                            <p class="text-muted">For your security, please confirm your password before continuing.</p>
                        </div>

                        <form method="POST" action="{{ route('password.confirm') }}">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label fw-semibold text-dark">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-lock" style="color:#E91E8C;"></i>
                                    </span>
                                    <input type="password" name="password" 
                                           class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                           required autofocus placeholder="Enter your password">
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn w-100 py-3 fw-bold text-white" 
                                    style="background: linear-gradient(135deg, #E91E8C, #9333ea); border-radius: 50px; font-size: 1.02rem;">
                                <i class="fas fa-check-circle me-2"></i> Confirm Password
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="{{ route('logout') }}" class="text-muted small">
                                <i class="fas fa-sign-out-alt me-1"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection