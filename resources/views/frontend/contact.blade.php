@extends('layouts.guest')
@section('title', 'Contact Us - Glamora')

@section('content')

<!-- Simple Header -->
<section class="py-5">
    <div class="container">
        <div class="text-center">
            <h1 class="display-4 fw-bold" style="color: #1f2a3e;">Contact <span style="color: #c8506e;">Us</span></h1>
            <div class="divider-line mx-auto" style="width: 80px; height: 3px; background: #c8506e; margin: 10px auto;"></div>
            <p class="text-muted fs-5">We'd love to hear from you! Get in touch with our team.</p>
        </div>
    </div>
</section>

<!-- Contact Form Section - Card Clearly Visible -->
<section class="py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Card with clear border and shadow -->
                <div class="card border rounded-4 shadow-lg" style="border-color: #e0e4e8 !important; background: white; ">
                    <div class="card-body p-4 p-lg-5">
                        <h4 class="fw-bold mb-4" style="color: #1f2a3e; text-align: center; margin-botton: 10px;">Send us a <span style="color: #c8506e;">Message</span></h4>
                        
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i> Please check the form for errors.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        <form action="{{ route('contact.send') }}" method="POST" id="contactForm">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Your Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           placeholder="John Doe" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           placeholder="hello@example.com" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Phone Number</label>
                                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                           placeholder="+92 300 1234567" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                                    <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" 
                                           placeholder="How can we help you?" value="{{ old('subject') }}" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                                    <textarea name="message" class="form-control @error('message') is-invalid @enderror" 
                                              rows="5" placeholder="Tell us about your inquiry..." required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn px-5 py-3 text-white fw-bold" 
                                            style="background: linear-gradient(135deg, #c8506e, #8b2252); border: none; border-radius: 10px; transition: all 0.3s;">
                                        <i class="fas fa-paper-plane me-2"></i> Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-5">
    <div class="container">
        <div class="card border rounded-4 shadow-lg overflow-hidden" style="border-color: #e0e4e8 !important;">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d217421.68792101564!2d74.25309344711804!3d31.48282093178553!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39190483e4a07b5d%3A0x9633fec5e27f2686!2sGulberg%20III%2C%20Lahore%2C%20Punjab%2C%20Pakistan!5e0!3m2!1sen!2s!4v1700000000000!5m2!1sen!2s" 
                width="100%" 
                height="350" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h3 class="fw-bold" style="color: #1f2a3e;">Frequently Asked <span style="color: #c8506e;">Questions</span></h3>
            <div class="divider-line mx-auto" style="width: 60px; height: 3px; background: #c8506e; margin: 15px auto;"></div>
            <p class="text-muted">Find quick answers to common questions</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border rounded-4 shadow-sm h-100" style="border-color: #e0e4e8 !important; background: white;">
                    <div class="card-body p-4">
                        <div class="d-flex gap-3">
                            <div><i class="fas fa-clock fa-lg" style="color: #c8506e;"></i></div>
                            <div>
                                <h6 class="fw-bold">What are your working hours?</h6>
                                <p class="text-muted mb-0">Monday to Saturday: 10:00 AM - 8:00 PM<br>Sunday: Closed</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card border rounded-4 shadow-sm h-100" style="border-color: #e0e4e8 !important; background: white;">
                    <div class="card-body p-4">
                        <div class="d-flex gap-3">
                            <div><i class="fas fa-calendar-alt fa-lg" style="color: #c8506e;"></i></div>
                            <div>
                                <h6 class="fw-bold">Do I need an appointment?</h6>
                                <p class="text-muted mb-0">Yes, appointments are recommended to ensure service availability.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card border rounded-4 shadow-sm h-100" style="border-color: #e0e4e8 !important; background: white;">
                    <div class="card-body p-4">
                        <div class="d-flex gap-3">
                            <div><i class="fas fa-credit-card fa-lg" style="color: #c8506e;"></i></div>
                            <div>
                                <h6 class="fw-bold">What payment methods do you accept?</h6>
                                <p class="text-muted mb-0">Cash, Credit Card, Debit Card, and Online Payments.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card border rounded-4 shadow-sm h-100" style="border-color: #e0e4e8 !important; background: white;">
                    <div class="card-body p-4">
                        <div class="d-flex gap-3">
                            <div><i class="fas fa-undo-alt fa-lg" style="color: #c8506e;"></i></div>
                            <div>
                                <h6 class="fw-bold">What is your cancellation policy?</h6>
                                <p class="text-muted mb-0">Free cancellation up to 24 hours before appointment.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Card border and shadow clearly visible */
    .card {
        background: white;
        border: 1px solid #e0e4e8 !important;
    }
    
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(200, 80, 110, 0.3);
    }
    
    .form-control {
        border: 1px solid #e0e4e8;
        border-radius: 10px;
        padding: 12px 15px;
        transition: all 0.3s;
    }
    
    .form-control:focus {
        border-color: #c8506e;
        box-shadow: 0 0 0 0.2rem rgba(200, 80, 110, 0.25);
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
    }
    
    /* Map card specific */
    .overflow-hidden {
        overflow: hidden;
    }
</style>

<script>
    document.getElementById('contactForm')?.addEventListener('submit', function(e) {
        let submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Sending...';
        
        setTimeout(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Send Message';
        }, 3000);
    });
</script>

@endsection