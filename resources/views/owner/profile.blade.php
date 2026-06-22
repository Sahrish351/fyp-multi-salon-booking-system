
@extends('layouts.owner')
 
@section('title', 'Salon Profile')
 
@section('content')
 

    <div class="page-header">
        <h2>Salon Profile</h2>
        <p>Manage your salon information</p>
    </div>
 
    <form action="{{ route('owner.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
 
        <div class="row g-4">
 
           
            <div class="col-lg-4">
                <div class="panel-card text-center">
 
                    <div class="logo-circle mx-auto">
                        @if (!empty($salon['logo_url']))
                            <img src="{{ $salon['logo_url'] }}" alt="Salon Logo">
                        @else
                            <i class="bi bi-shop"></i>
                        @endif
                    </div>
 
                    <h4 class="logo-salon-name">{{ $salon['name'] ?? 'GlowAura Salon' }}</h4>
                    <p class="logo-salon-tagline">{{ $salon['tagline'] ?? 'Luxury Beauty & Wellness' }}</p>
 
                    <label for="logoUpload" class="btn btn-change-logo">
                        <i class="bi bi-camera-fill me-2"></i> Change Logo
                    </label>
                    <input type="file" id="logoUpload" name="logo" accept="image/*" hidden>
                    <div id="logoFileName" class="logo-filename"></div>
 
                </div>
            </div>
 
           
            <div class="col-lg-8">
                <div class="panel-card">
                    <div class="panel-title">Contact Information</div>
 
                    <div class="row g-3">
 
                        <div class="col-md-6">
                            <label class="form-label-custom">Salon Name</label>
                            <input type="text" class="form-control input-custom" name="salon_name"
                                   value="{{ $salon['name'] ?? 'GlowAura Luxury Salon' }}">
                        </div>
 
                        <div class="col-md-6">
                            <label class="form-label-custom">Phone</label>
                            <input type="text" class="form-control input-custom" name="phone"
                                   value="{{ $salon['phone'] ?? '+1 (555) 123-4567' }}">
                        </div>
 
                        <div class="col-md-6">
                            <label class="form-label-custom">Email</label>
                            <input type="email" class="form-control input-custom" name="email"
                                   value="{{ $salon['email'] ?? 'contact@glowaura.com' }}">
                        </div>
 
                        <div class="col-md-6">
                            <label class="form-label-custom">Website</label>
                            <input type="text" class="form-control input-custom" name="website"
                                   value="{{ $salon['website'] ?? 'www.glowaura.com' }}">
                        </div>
 
                        <div class="col-12">
                            <label class="form-label-custom">Address</label>
                            <input type="text" class="form-control input-custom" name="address"
                                   value="{{ $salon['address'] ?? '123 Luxury Avenue, Beverly Hills, CA 90210' }}">
                        </div>
 
                        <div class="col-12">
                            <label class="form-label-custom">Description</label>
                            <textarea class="form-control input-custom" name="description" rows="3">{{ $salon['description'] ?? 'GlowAura is a premier luxury salon offering world-class beauty and wellness services. Experience the ultimate in relaxation and transformation.' }}</textarea>
                        </div>
 
                    </div>
 
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
   
    .logo-circle {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 52px;
        color: #fff;
        margin-bottom: 18px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(217, 164, 65, 0.35);
    }
 
    .logo-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
 
    .logo-salon-name {
        font-size: 19px;
        font-weight: 700;
        color: var(--plum-800);
        margin-bottom: 4px;
    }
 
    .logo-salon-tagline {
        font-size: 13.5px;
        color: var(--ink-700);
        margin-bottom: 18px;
    }
 
    .btn-change-logo {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        color: var(--plum-900);
        font-weight: 600;
        font-size: 14.5px;
        padding: 10px 22px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(217, 164, 65, 0.3);
        transition: all 0.18s ease;
        display: inline-flex;
        align-items: center;
    }
 
    .btn-change-logo:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(217, 164, 65, 0.45);
    }
 
    .logo-filename {
        font-size: 12.5px;
        color: var(--ink-500);
        margin-top: 10px;
        word-break: break-all;
    }
 
    .form-label-custom {
        display: block;
        font-size: 13.5px;
        font-weight: 600;
        color: var(--ink-700);
        margin-bottom: 6px;
    }
 
    .input-custom {
        background: var(--blush-50) !important;
        border: 1px solid var(--blush-200) !important;
        border-radius: var(--radius-sm) !important;
        color: var(--ink-900) !important;
        font-size: 14.5px;
        padding: 11px 14px !important;
    }
 
    .input-custom:focus {
        background: #fff !important;
        border-color: var(--rose-400) !important;
        box-shadow: 0 0 0 3px rgba(240, 143, 180, 0.2) !important;
        outline: none;
    }
 
    .input-custom::placeholder {
        color: var(--ink-500);
    }
 
    .btn-save-changes {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        color: var(--plum-900);
        font-weight: 700;
        font-size: 15px;
        padding: 11px 28px;
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 14px rgba(217, 164, 65, 0.35);
        transition: all 0.18s ease;
        display: inline-flex;
        align-items: center;
    }
 
    .btn-save-changes:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(217, 164, 65, 0.5);
        color: var(--plum-900);
    }
</style>
@endsection
 
@section('extra-js')
<script>
   
    const logoInput = document.getElementById('logoUpload');
    const logoFileName = document.getElementById('logoFileName');
    const logoCircle = document.querySelector('.logo-circle');
 
    if (logoInput) {
        logoInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                logoFileName.textContent = this.files[0].name;
 
                const reader = new FileReader();
                reader.onload = function (e) {
                    logoCircle.innerHTML = `<img src="${e.target.result}" alt="Salon Logo">`;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
</script>
@endsection