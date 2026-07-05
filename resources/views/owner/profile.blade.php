@extends('layouts.owner')

@section('title', 'Salon Profile')

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-header">
        <h2>Salon Profile</h2>
        <p>Manage your salon information</p>
    </div>

    <form action="{{ route('owner.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">

            <!-- Left Column - Logo -->
            <div class="col-lg-4">
                <div class="panel-card text-center">
                    <div class="logo-circle mx-auto">
                        @if (!empty($salon->logo))
                            <img src="{{ asset('storage/' . $salon->logo) }}" alt="Salon Logo">
                        @else
                            <i class="bi bi-shop"></i>
                        @endif
                    </div>

                    <h4 class="logo-salon-name">{{ $salon->name ?? 'Salon Name' }}</h4>
                    <p class="logo-salon-tagline">{{ $salon->tagline ?? 'Luxury Beauty & Wellness' }}</p>

                    <!-- ✅ PINK BUTTON -->
                    <label for="logoUpload" class="btn btn-change-logo">
                        <i class="bi bi-camera-fill me-2"></i> Change Logo
                    </label>
                    <input type="file" id="logoUpload" name="logo" accept="image/*" hidden>
                    <div id="logoFileName" class="logo-filename"></div>
                    <small class="text-muted d-block mt-1">Max 2MB (JPG, PNG, SVG)</small>
                </div>
            </div>

            <!-- Right Column - Salon Information -->
            <div class="col-lg-8">
                <div class="panel-card">
                    <div class="panel-title mb-3">
                        <i class="bi bi-building me-2"></i> Salon Information
                    </div>

                    <div class="row g-3">

                        <!-- Salon Name -->
                        <div class="col-md-6">
                            <label class="form-label-custom">
                                <i class="bi bi-shop me-1"></i> Salon Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control input-custom" name="name"
                                   value="{{ old('name', $salon->name ?? '') }}" 
                                   placeholder="Enter salon name" required>
                        </div>

                        <!-- Tagline -->
                        <div class="col-md-6">
                            <label class="form-label-custom">
                                <i class="bi bi-tag me-1"></i> Tagline
                            </label>
                            <input type="text" class="form-control input-custom" name="tagline"
                                   value="{{ old('tagline', $salon->tagline ?? '') }}" 
                                   placeholder="e.g. Luxury Beauty & Wellness">
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6">
                            <label class="form-label-custom">
                                <i class="bi bi-telephone me-1"></i> Phone <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control input-custom" name="phone"
                                   value="{{ old('phone', $salon->phone ?? '') }}" 
                                   placeholder="+92 300 1234567" required>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label class="form-label-custom">
                                <i class="bi bi-envelope me-1"></i> Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control input-custom" name="email"
                                   value="{{ old('email', $salon->email ?? '') }}" 
                                   placeholder="salon@email.com" required>
                        </div>

                        <!-- Website -->
                        <div class="col-md-6">
                            <label class="form-label-custom">
                                <i class="bi bi-globe me-1"></i> Website
                            </label>
                            <input type="text" class="form-control input-custom" name="website"
                                   value="{{ old('website', $salon->website ?? '') }}" 
                                   placeholder="www.yoursalon.com">
                        </div>

                        <!-- City -->
                        <div class="col-md-6">
                            <label class="form-label-custom">
                                <i class="bi bi-geo-alt me-1"></i> City
                            </label>
                            <input type="text" class="form-control input-custom" name="city"
                                   value="{{ old('city', $salon->city ?? '') }}" 
                                   placeholder="Karachi, Lahore, etc.">
                        </div>

                        <!-- Area -->
                        <div class="col-md-6">
                            <label class="form-label-custom">
                                <i class="bi bi-pin-map me-1"></i> Area
                            </label>
                            <input type="text" class="form-control input-custom" name="area"
                                   value="{{ old('area', $salon->area ?? '') }}" 
                                   placeholder="DHA, Gulberg, etc.">
                        </div>

                        <!-- Opening Time -->
                        <div class="col-md-3">
                            <label class="form-label-custom">
                                <i class="bi bi-clock me-1"></i> Open Time
                            </label>
                            <input type="time" class="form-control input-custom" name="open_time"
                                   value="{{ old('open_time', $salon->open_time ?? '09:00') }}">
                        </div>

                        <!-- Closing Time -->
                        <div class="col-md-3">
                            <label class="form-label-custom">
                                <i class="bi bi-clock me-1"></i> Close Time
                            </label>
                            <input type="time" class="form-control input-custom" name="close_time"
                                   value="{{ old('close_time', $salon->close_time ?? '21:00') }}">
                        </div>

                        <!-- Address -->
                        <div class="col-12">
                            <label class="form-label-custom">
                                <i class="bi bi-house-door me-1"></i> Address <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control input-custom" name="address"
                                   value="{{ old('address', $salon->address ?? '') }}" 
                                   placeholder="Street, Building, Area" required>
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <label class="form-label-custom">
                                <i class="bi bi-text-paragraph me-1"></i> Description
                            </label>
                            <textarea class="form-control input-custom" name="description" 
                                      rows="4" placeholder="Tell clients about your salon...">{{ old('description', $salon->description ?? '') }}</textarea>
                        </div>

                    </div>

                    <!-- ✅ PINK BUTTON -->
                    <button type="submit" class="btn btn-save-changes mt-4">
                        <i class="bi bi-save-fill me-2"></i> Save Changes
                    </button>

                </div>
            </div>

        </div>

    </form>

@endsection

@section('extra-css')
<style>
    /* ===================== PAGE HEADER ===================== */
    .page-header {
        margin-bottom: 1.5rem;
    }

    .page-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d1f2c;
        margin-bottom: 0.25rem;
    }

    .page-header p {
        color: #8a7a88;
        margin-bottom: 0;
    }

    /* ===================== ALERTS ===================== */
    .alert {
        border-radius: 12px;
        border: none;
        padding: 0.8rem 1.2rem;
    }

    .alert-success {
        background: #E8F5E9;
        color: #1B5E20;
    }

    .alert-danger {
        background: #FCE4EC;
        color: #880E4F;
    }

    .alert ul {
        padding-left: 1.2rem;
        margin-bottom: 0;
    }

    /* ===================== PANEL CARD ===================== */
    .panel-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid rgba(0,0,0,0.04);
        transition: all 0.3s ease;
    }

    .panel-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .panel-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2d1f2c;
        display: flex;
        align-items: center;
    }

    /* ===================== LOGO CIRCLE ===================== */
    .logo-circle {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FF6B9D, #E85588);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 52px;
        color: #fff;
        margin-bottom: 18px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(217, 164, 65, 0.35);
        transition: all 0.3s ease;
    }

    .logo-circle:hover {
        transform: scale(1.02);
        box-shadow: 0 10px 30px rgba(217, 164, 65, 0.45);
    }

    .logo-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .logo-salon-name {
        font-size: 19px;
        font-weight: 700;
        color: #2d1f2c;
        margin-bottom: 2px;
    }

    .logo-salon-tagline {
        font-size: 13.5px;
        color: #8a7a88;
        margin-bottom: 18px;
    }

    /* ===================== BUTTONS - PINK LIKE DASHBOARD ===================== */
    .btn-change-logo {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 14px;
        padding: 10px 22px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(232, 85, 136, 0.3);
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
    }

    .btn-change-logo:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(232, 85, 136, 0.4);
        color: #ffffff !important;
    }

    .btn-save-changes {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 15px;
        padding: 11px 28px;
        border-radius: 8px;
        border: none;
        box-shadow: 0 2px 8px rgba(232, 85, 136, 0.3);
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-save-changes:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(232, 85, 136, 0.4);
        color: #ffffff !important;
    }

    .logo-filename {
        font-size: 12.5px;
        color: #8a7a88;
        margin-top: 8px;
        word-break: break-all;
    }

    /* ===================== FORM LABELS ===================== */
    .form-label-custom {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #4a3a48;
        margin-bottom: 5px;
    }

    .form-label-custom .text-danger {
        color: #E96A98;
        font-weight: 700;
    }

    /* ===================== INPUT FIELDS ===================== */
    .input-custom {
        background: #fcf9fc !important;
        border: 1.5px solid #f0eef0 !important;
        border-radius: 10px !important;
        color: #2d1f2c !important;
        font-size: 14px;
        padding: 10px 14px !important;
        transition: all 0.25s ease;
        width: 100%;
    }

    .input-custom:focus {
        background: #ffffff !important;
        border-color: #FF6B9D !important;
        box-shadow: 0 0 0 4px rgba(255, 107, 157, 0.15) !important;
        outline: none;
    }

    .input-custom::placeholder {
        color: #b0a5ae;
        font-weight: 400;
    }

    /* ===================== RESPONSIVE ===================== */
    @media (max-width: 768px) {
        .panel-card {
            padding: 1rem;
        }

        .logo-circle {
            width: 100px;
            height: 100px;
            font-size: 40px;
        }

        .logo-salon-name {
            font-size: 17px;
        }

        .btn-change-logo {
            font-size: 13px;
            padding: 8px 16px;
        }

        .btn-save-changes {
            font-size: 14px;
            padding: 10px 20px;
            width: 100%;
        }

        .page-header h2 {
            font-size: 1.25rem;
        }
    }

    @media (max-width: 576px) {
        .panel-card {
            padding: 0.75rem;
        }

        .logo-circle {
            width: 80px;
            height: 80px;
            font-size: 32px;
        }

        .input-custom {
            font-size: 13px;
            padding: 8px 12px !important;
        }

        .form-label-custom {
            font-size: 12px;
        }
    }
</style>
@endsection

@section('extra-js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===================== LOGO UPLOAD PREVIEW =====================
        const logoInput = document.getElementById('logoUpload');
        const logoFileName = document.getElementById('logoFileName');
        const logoCircle = document.querySelector('.logo-circle');

        if (logoInput) {
            logoInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const fileSize = file.size / 1024 / 1024; // MB

                    // Validate file size (max 2MB)
                    if (fileSize > 2) {
                        alert('File size must be less than 2MB. Current: ' + fileSize.toFixed(2) + 'MB');
                        this.value = '';
                        logoFileName.textContent = '';
                        return;
                    }

                    // Validate file type
                    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp'];
                    if (!validTypes.includes(file.type)) {
                        alert('Please upload a valid image file (JPG, PNG, GIF, SVG, WEBP)');
                        this.value = '';
                        logoFileName.textContent = '';
                        return;
                    }

                    logoFileName.textContent = '📷 ' + file.name + ' (' + fileSize.toFixed(2) + ' MB)';

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        logoCircle.innerHTML = `<img src="${e.target.result}" alt="Salon Logo">`;
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }

        // ===================== AUTO-DISMISS ALERTS =====================
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                alert.classList.remove('show');
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 300);
            }, 5000);
        });
    });
</script>
@endsection