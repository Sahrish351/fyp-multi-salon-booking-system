@extends('layouts.guest')
@section('title', 'About Us - Glamora')

@section('content')

<!-- Simple Hero Section - No Pink Bar -->
<section class="py-4 pt-5">
    <div class="container">
        <div class="text-center">
            <h1 class="display-5 fw-bold" style="color: #1f2a3e;">About <span style="color: #c8506e;">Glamora</span></h1>
            <div class="divider-line mx-auto" style="width: 60px; height: 3px; background: #c8506e; margin: 12px auto;"></div>
            <p class="text-muted">Pakistan's premier multi-salon booking platform</p>
        </div>
    </div>
</section>

<!-- Our Story Section -->
<section class="py-4">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=600&q=80" 
                     class="img-fluid rounded-4 shadow-sm" 
                     alt="About Glamora"
                     style="width: 100%;">
            </div>
            <div class="col-lg-6">
                <h3 class="fw-bold mb-3" style="color: #1f2a3e;">Your Beauty, <span style="color: #c8506e;">Our Passion</span></h3>
                <p class="text-muted" style="line-height: 1.8;">Glamora was born with a simple vision — to make salon booking effortless, transparent, and delightful. We connect beauty enthusiasts with Pakistan's finest salons, ensuring a seamless experience from discovery to confirmation.</p>
                <p class="text-muted mt-3" style="line-height: 1.8;">Today, we're proud to serve thousands of happy clients across major cities, partnering with verified salons that share our commitment to quality and service excellence.</p>
                
                <div class="row mt-4 g-2">
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle" style="color: #c8506e;"></i>
                            <span class="small">100% Verified Salons</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle" style="color: #c8506e;"></i>
                            <span class="small">Secure Payments</span>
                        </div>
                    </div>
                    <div class="col-6 mt-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle" style="color: #c8506e;"></i>
                            <span class="small">Real-time Booking</span>
                        </div>
                    </div>
                    <div class="col-6 mt-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle" style="color: #c8506e;"></i>
                            <span class="small">24/7 Support</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5" style="background: #f8f9fc;">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3 col-6">
                <div class="display-4 fw-bold" style="color: #c8506e;">{{ $stats['salons'] ?? '100' }}+</div>
                <p class="text-muted mb-0">Verified Salons</p>
            </div>
            <div class="col-md-3 col-6">
                <div class="display-4 fw-bold" style="color: #c8506e;">{{ $stats['clients'] ?? '5k' }}+</div>
                <p class="text-muted mb-0">Happy Clients</p>
            </div>
            <div class="col-md-3 col-6">
                <div class="display-4 fw-bold" style="color: #c8506e;">{{ $stats['bookings'] ?? '10k' }}+</div>
                <p class="text-muted mb-0">Appointments</p>
            </div>
            <div class="col-md-3 col-6">
                <div class="display-4 fw-bold" style="color: #c8506e;">{{ $stats['years'] ?? '3' }}+</div>
                <p class="text-muted mb-0">Years</p>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-bullseye fa-2x" style="color: #c8506e;"></i>
                        </div>
                        <h4 class="fw-bold mb-3" style="font-size: 1.3rem;">Our Mission</h4>
                        <p class="text-muted">To revolutionize the beauty industry by providing a seamless, transparent, and reliable platform that connects clients with the best salons across Pakistan.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-eye fa-2x" style="color: #c8506e;"></i>
                        </div>
                        <h4 class="fw-bold mb-3" style="font-size: 1.3rem;">Our Vision</h4>
                        <p class="text-muted">To become Pakistan's most trusted beauty booking platform, empowering local businesses and enhancing the beauty experience for everyone.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-4">
            <h4 class="fw-bold" style="color: #1f2a3e;">Why Choose <span style="color: #c8506e;">Glamora?</span></h4>
            <div class="divider-line mx-auto" style="width: 50px; height: 2px; background: #c8506e; margin: 8px auto;"></div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-center h-100">
                    <div class="card-body p-4">
                        <i class="fas fa-shield-alt fa-2x mb-3" style="color: #c8506e;"></i>
                        <h6 class="fw-bold">Verified Salons</h6>
                        <p class="text-muted small">All salons verified for quality</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-center h-100">
                    <div class="card-body p-4">
                        <i class="fas fa-clock fa-2x mb-3" style="color: #c8506e;"></i>
                        <h6 class="fw-bold">Real-time Booking</h6>
                        <p class="text-muted small">Instant confirmation</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-center h-100">
                    <div class="card-body p-4">
                        <i class="fas fa-credit-card fa-2x mb-3" style="color: #c8506e;"></i>
                        <h6 class="fw-bold">Secure Payments</h6>
                        <p class="text-muted small">100% secure transactions</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5">
    <div class="container text-center">
        <h4 class="fw-bold" style="color: #1f2a3e;">Ready to Experience Glamora?</h4>
        <p class="text-muted mb-4">Join thousands of happy clients who trust us for their beauty needs</p>
        <a href="{{ route('salons.index') }}" class="btn px-4 py-2 text-white" style="background: #c8506e; border-radius: 30px;">
            <i class="fas fa-calendar-check me-2"></i> Book Now
        </a>
    </div>
</section>

<style>
    .card {
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important;
    }
</style>

@endsection