@extends('layouts.auth')
@section('title', 'Client Registration — Glamora')

@push('styles')
<style>
.auth-page {
    min-height: 100vh;
    background: linear-gradient(145deg, #f8f9fa 0%, #e9ecef 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}

.register-wrapper {
    max-width: 550px;
    width: 100%;
    margin: 0 auto;
}

.auth-card {
    background: #ffffff;
    border-radius: 32px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.08);
    padding: 2.5rem;
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

.form-control, .form-select {
    border-radius: 12px;
    padding: 0.75rem 1rem;
    border: 2px solid #e9ecef;
}

.form-control:focus, .form-select:focus {
    border-color: #E91E8C;
    box-shadow: 0 0 0 3px rgba(233,30,140,0.1);
}

.btn-register {
    background: linear-gradient(135deg, #E91E8C, #c2185b);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 0.85rem;
    font-weight: 600;
    width: 100%;
    transition: all 0.3s ease;
}

.btn-register:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(233,30,140,0.3);
}

.strength-bar {
    height: 4px;
    border-radius: 4px;
    transition: all 0.3s ease;
    margin-top: 5px;
}
</style>
@endpush

@section('content')
<div class="auth-page">
    <div class="register-wrapper">
        <div class="auth-card">
            <div class="text-center mb-4">
                <i class="fas fa-user-plus fa-3x" style="color: #E91E8C;"></i>
                <h3 class="mt-2 fw-bold">Create Client Account</h3>
                <p class="text-muted">Join Glamora and discover beauty</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.client.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Full Name *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" placeholder="Enter your full name" required>
                    @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email Address *</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" placeholder="your@email.com" required>
                        @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Phone Number *</label>
                        <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone') }}" placeholder="03xxxxxxxxx" required>
                        @error('phone')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3 mt-3">
                    <label class="form-label fw-semibold">City *</label>
                    <select name="city" class="form-select @error('city') is-invalid @enderror" required>
                        <option value="">Select your city</option>
                        <option value="Lahore" {{ old('city')=='Lahore' ? 'selected' : '' }}>Lahore</option>
                        <option value="Karachi" {{ old('city')=='Karachi' ? 'selected' : '' }}>Karachi</option>
                        <option value="Islamabad" {{ old('city')=='Islamabad' ? 'selected' : '' }}>Islamabad</option>
                        <option value="Rawalpindi" {{ old('city')=='Rawalpindi' ? 'selected' : '' }}>Rawalpindi</option>
                    </select>
                    @error('city')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Password *</label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Min 8 characters" required oninput="checkStrength(this.value)">
                        <div id="strengthBar" class="strength-bar" style="background:#e9ecef; width:0;"></div>
                        <small id="strengthText" class="text-muted"></small>
                        @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Confirm Password *</label>
                        <input type="password" name="password_confirmation" class="form-control" 
                               placeholder="Confirm password" required>
                    </div>
                </div>

                <div class="mb-3 form-check mt-3">
                    <input type="checkbox" class="form-check-input" name="terms" id="terms" required>
                    <label class="form-check-label" for="terms">
                        I agree to the <a href="#" style="color:#E91E8C;">Terms & Conditions</a>
                    </label>
                </div>

                <button type="submit" class="btn-register">
                    <i class="fas fa-user-plus me-2"></i>Create Account
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="text-muted small">
                    Already have an account? 
                    <a href="{{ route('client.login.form') }}" style="color: #E91E8C; font-weight: 600;">Sign In</a>
                </p>
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