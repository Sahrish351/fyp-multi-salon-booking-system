@extends('layouts.auth')
@section('title', 'Salon Owner Registration — Glamora')

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

.text-center {
    text-align: center;
    margin-bottom: 1.5rem;
}

.icon-circle {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #E91E8C, #c2185b);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.icon-circle i {
    font-size: 2rem;
    color: white;
}

h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.3rem;
}

.subtitle {
    color: #6c757d;
    font-size: 0.85rem;
}

.form-control, .form-select {
    border-radius: 12px;
    padding: 0.85rem 1rem;
    border: 2px solid #e9ecef;
    width: 100%;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #E91E8C;
    box-shadow: 0 0 0 3px rgba(233,30,140,0.1);
    outline: none;
}

.form-label {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    display: block;
    font-size: 0.85rem;
}

.btn-register {
    background: linear-gradient(135deg, #E91E8C, #c2185b);
    color: white;
    border: none;
    border-radius: 14px;
    padding: 0.9rem;
    font-weight: 600;
    font-size: 1rem;
    width: 100%;
    transition: all 0.3s ease;
    cursor: pointer;
    margin-top: 0.5rem;
}

.btn-register:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(233,30,140,0.3);
}

.row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -0.5rem;
}

.col-md-6 {
    width: 50%;
    padding: 0 0.5rem;
}

.col-md-12 {
    width: 100%;
    padding: 0 0.5rem;
}

.mb-3 {
    margin-bottom: 1rem;
}

.mt-3 {
    margin-top: 1rem;
}

.mt-4 {
    margin-top: 1.5rem;
}

.form-check {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-check-input {
    width: 1rem;
    height: 1rem;
    cursor: pointer;
    accent-color: #E91E8C;
}

.form-check-label {
    color: #6c757d;
    font-size: 0.85rem;
}

.form-check-label a {
    color: #E91E8C;
    text-decoration: none;
}

.form-check-label a:hover {
    text-decoration: underline;
}

.alert {
    padding: 0.75rem 1rem;
    border-radius: 12px;
    margin-bottom: 1rem;
    font-size: 0.85rem;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: none;
}

.alert-danger ul {
    margin: 0;
    padding-left: 1rem;
}

.login-link {
    text-align: center;
    margin-top: 1.5rem;
}

.login-link p {
    color: #6c757d;
    font-size: 0.85rem;
    margin: 0;
}

.login-link a {
    color: #E91E8C;
    font-weight: 600;
    text-decoration: none;
}

.login-link a:hover {
    text-decoration: underline;
}

@media (max-width: 576px) {
    .auth-card {
        padding: 1.5rem;
    }
    .col-md-6 {
        width: 100%;
    }
    .register-wrapper {
        max-width: 95%;
    }
}
</style>
@endpush

@section('content')
<div class="auth-page">
    <div class="register-wrapper">
        <div class="auth-card">
            <div class="text-center">
                <div class="icon-circle">
                    <i class="fas fa-store"></i>
                </div>
                <h3>Register Your Salon</h3>
                <p class="subtitle">Join Pakistan's fastest growing beauty platform</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.owner.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" placeholder="Your full name" required>
                    @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" placeholder="business@email.com" required>
                            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Phone Number *</label>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone') }}" placeholder="03XXXXXXXXX" required>
                            @error('phone')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">CNIC Number *</label>
                            <input type="text" name="cnic" class="form-control @error('cnic') is-invalid @enderror" 
                                   value="{{ old('cnic') }}" placeholder="XXXXX-XXXXXXX-X" required>
                            @error('cnic')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">City *</label>
                            <select name="city" class="form-select @error('city') is-invalid @enderror" required>
                                <option value="">Select city</option>
                                <option value="Lahore" {{ old('city')=='Lahore' ? 'selected' : '' }}>Lahore</option>
                                <option value="Karachi" {{ old('city')=='Karachi' ? 'selected' : '' }}>Karachi</option>
                                <option value="Islamabad" {{ old('city')=='Islamabad' ? 'selected' : '' }}>Islamabad</option>
                                <option value="Rawalpindi" {{ old('city')=='Rawalpindi' ? 'selected' : '' }}>Rawalpindi</option>
                                <option value="Faisalabad" {{ old('city')=='Faisalabad' ? 'selected' : '' }}>Faisalabad</option>
                                <option value="Multan" {{ old('city')=='Multan' ? 'selected' : '' }}>Multan</option>
                            </select>
                            @error('city')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Salon Name *</label>
                    <input type="text" name="salon_name" class="form-control @error('salon_name') is-invalid @enderror" 
                           value="{{ old('salon_name') }}" placeholder="Your salon name" required>
                    @error('salon_name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Salon Address *</label>
                    <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                              rows="2" placeholder="Complete salon address" required>{{ old('address') }}</textarea>
                    @error('address')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Password *</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="Min 8 characters" required>
                            @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Confirm Password *</label>
                            <input type="password" name="password_confirmation" class="form-control" 
                                   placeholder="Confirm password" required>
                        </div>
                    </div>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" name="terms" id="terms" required>
                    <label class="form-check-label" for="terms">
                        I agree to the <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a>
                    </label>
                </div>

                <button type="submit" class="btn-register">
                    <i class="fas fa-store me-2"></i>Register Salon
                </button>
            </form>

            <div class="login-link">
                <p>Already have an account? <a href="{{ route('owner.login.form') }}">Login Here</a></p>
            </div>
        </div>
    </div>
</div>
@endsection