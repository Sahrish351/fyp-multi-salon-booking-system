@extends('layouts.auth')
@section('title', 'Select Login Type — Glamora')

@push('styles')
<style>
.login-selector-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    margin: 0;
}

.selector-card {
    background: #ffffff;
    border-radius: 28px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.08);
    padding: 2.5rem;
    max-width: 850px;
    width: 100%;
    margin: 0 auto;
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

.selector-header {
    text-align: center;
    margin-bottom: 2rem;
}

.selector-header .icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #E91E8C, #c2185b);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.selector-header .icon i {
    font-size: 2rem;
    color: white;
}

.selector-header h2 {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    font-weight: 700;
    background: linear-gradient(135deg, #E91E8C, #C9A96E);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 0.5rem;
}

.selector-header p {
    color: #6c757d;
    font-size: 0.9rem;
}

.role-card {
    background: #f8f9fa;
    border-radius: 20px;
    padding: 1.8rem 1.2rem;
    text-align: center;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    height: 100%;
    text-decoration: none;
    display: block;
}

.role-card:hover {
    transform: translateY(-5px);
    border-color: #E91E8C;
    background: #ffffff;
    box-shadow: 0 10px 25px rgba(233,30,140,0.12);
}

.role-icon {
    width: 65px;
    height: 65px;
    background: linear-gradient(135deg, rgba(233,30,140,0.1), rgba(201,169,110,0.05));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.role-icon i {
    font-size: 1.8rem;
    color: #E91E8C;
}

.role-card h4 {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.6rem;
}

.role-card p {
    font-size: 0.8rem;
    color: #6c757d;
    line-height: 1.4;
    margin-bottom: 0.8rem;
    min-height: 55px;
}

.role-badge {
    display: inline-block;
    padding: 0.3rem 1rem;
    background: rgba(233,30,140,0.1);
    color: #E91E8C;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 600;
}

.role-card:hover .role-badge {
    background: #E91E8C;
    color: white;
}

.footer-link {
    text-align: center;
    margin-top: 2rem;
    padding-top: 0.5rem;
}

.footer-link p {
    color: #6c757d;
    font-size: 0.85rem;
    margin: 0;
}

.footer-link a {
    color: #E91E8C;
    font-weight: 600;
    text-decoration: none;
}

.footer-link a:hover {
    text-decoration: underline;
}

/* Row and Column */
.row {
    display: flex;
    justify-content: center;
    margin: 0 -0.75rem;
}

.col-md-4 {
    padding: 0 0.75rem;
    display: flex;
}

@media (max-width: 768px) {
    .selector-card {
        padding: 1.5rem;
        max-width: 95%;
    }
    .role-card {
        padding: 1.2rem;
    }
    .role-icon {
        width: 50px;
        height: 50px;
    }
    .role-icon i {
        font-size: 1.4rem;
    }
    .role-card h4 {
        font-size: 1rem;
    }
    .role-card p {
        font-size: 0.7rem;
        min-height: auto;
    }
}
</style>
@endpush

@section('content')
<div class="login-selector-page">
    <div class="selector-card">
        <div class="selector-header">
            <div class="icon">
                <i class="fas fa-spa"></i>
            </div>
            <h2>Welcome to Glamora</h2>
            <p>Select how you want to login</p>
        </div>

        <div class="row">
            <!-- Client Login -->
            <div class="col-md-4">
                <a href="{{ route('client.login.form') }}" class="role-card">
                    <div class="role-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <h4>Client</h4>
                    <p>Book appointments, discover salons, and manage your beauty routine</p>
                    <span class="role-badge">Recommended</span>
                </a>
            </div>

            <!-- Owner Login -->
            <div class="col-md-4">
                <a href="{{ route('owner.login.form') }}" class="role-card">
                    <div class="role-icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <h4>Salon Owner</h4>
                    <p>Manage your salon, appointments, and business analytics</p>
                    <span class="role-badge">Business</span>
                </a>
            </div>

            <!-- Admin Login -->
            <div class="col-md-4">
                <a href="{{ route('admin.login.form') }}" class="role-card">
                    <div class="role-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>Administrator</h4>
                    <p>Platform management, user oversight, and system controls</p>
                    <span class="role-badge">Admin Only</span>
                </a>
            </div>
        </div>

        <div class="footer-link">
            <p>Don't have an account? <a href="{{ route('register.selector') }}">Register Now</a></p>
        </div>
    </div>
</div>
@endsection