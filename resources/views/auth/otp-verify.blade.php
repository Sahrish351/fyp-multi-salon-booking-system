@extends('layouts.app')
@section('title', 'Verify OTP — Glamora')

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
.otp-input {
    width: 52px;
    height: 62px;
    text-align: center;
    font-size: 1.6rem;
    font-weight: 700;
    background: rgba(255,255,255,0.06);
    border: 2px solid rgba(255,255,255,0.12);
    color: #fff;
    border-radius: 12px;
    transition: all .3s;
}
.otp-input:focus {
    border-color: #E91E8C;
    box-shadow: 0 0 0 3px rgba(233,30,140,0.2);
    outline: none;
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
                            We've sent a 6-digit OTP to <strong>{{ session('otp_phone') }}</strong><br>
                            Enter it below to continue.
                        </p>
                    </div>

                    @if(session('error'))
                    <div class="alert alert-danger text-center rounded-3 mb-4">
                        {{ session('error') }}
                    </div>
                    @endif

                    <form action="{{ route('otp.verify.post') }}" method="POST" id="otpForm">
                        @csrf
                        <input type="hidden" name="phone" value="{{ session('otp_phone') }}">
                        <input type="hidden" name="otp" id="otpFull">

                        <div class="d-flex justify-content-center gap-3 mb-5" id="otpInputs">
                            @for($i = 0; $i < 6; $i++)
                                <input type="text" maxlength="1" 
                                       class="otp-input"
                                       oninput="moveNext(this, {{ $i }})"
                                       onkeydown="movePrev(event, {{ $i }})"
                                       autocomplete="off">
                            @endfor
                        </div>

                        <button type="submit" class="btn-verify" onclick="collectOtp()">
                            <i class="fas fa-check-circle me-2"></i> Verify OTP
                        </button>
                    </form>

                    <div class="mt-4">
                        <p style="color:rgba(255,255,255,0.5);font-size:0.9rem;">
                            Didn't receive the code? 
                            <button type="button" onclick="resendOtp()" 
                                    style="background:none;border:none;color:#E91E8C;font-weight:600;cursor:pointer;">
                                Resend OTP
                            </button>
                        </p>
                    </div>

                    <div class="text-center mt-5">
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

@push('scripts')
<script>
const inputs = document.querySelectorAll('.otp-input');

function moveNext(el, index) {
    if (el.value.length === 1 && index < 5) {
        inputs[index + 1].focus();
    }
}

function movePrev(e, index) {
    if (e.key === 'Backspace' && index > 0 && !inputs[index].value) {
        inputs[index - 1].focus();
    }
}

function collectOtp() {
    let otp = '';
    inputs.forEach(input => otp += input.value);
    document.getElementById('otpFull').value = otp;
}

function resendOtp() {
    if (confirm('Resend OTP?')) {
        fetch('{{ route("otp.resend") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ phone: '{{ session("otp_phone") }}' })
        }).then(() => {
            alert('OTP has been resent successfully!');
        });
    }
}

// Auto focus first input
window.onload = () => inputs[0].focus();
</script>
@endpush