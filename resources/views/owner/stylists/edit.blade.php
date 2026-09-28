@extends('layouts.owner')

@section('title', 'Edit Team Member')

@section('content')

    {{-- ===== ERROR MESSAGES ===== --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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

    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>Edit Team Member</h2>
            <p>Update details for "{{ $stylist['name'] }}"</p>
        </div>
        <a href="{{ route('owner.stylists.index') }}" class="btn btn-back">
            <i class="bi bi-arrow-left me-2"></i> Back to Team
        </a>
    </div>

    @php
        $specs = ['Hair Styling', 'Nail Care', 'Facial', 'Spa & Massage', 'Makeup', 'Barber', 'Bridal'];
        $currentSpec = old('specialization', $stylist['specialization']);
        // Agar current specialization list mein nahi to usay bhi option bana do
        if (!empty($currentSpec) && !in_array($currentSpec, $specs)) {
            array_unshift($specs, $currentSpec);
        }
        $currentStatus = strtolower(old('status', $stylist['status']));
        $checkedServices = old('services', $selectedServiceIds ?? []);
    @endphp

    <form action="{{ route('owner.stylists.update', ['stylist' => $stylist['id']]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- ===== LEFT: photo + status (sticky) ===== --}}
            <div class="col-lg-4">
                <div class="panel-card text-center left-sticky">
                    <div class="stylist-photo-box mx-auto" id="photoPreviewBox">
                        @if (!empty($stylist['photo_url']))
                            <img src="{{ $stylist['photo_url'] }}" alt="{{ $stylist['name'] }}">
                        @else
                            <i class="bi bi-person-fill"></i>
                        @endif
                    </div>

                    <label for="stylistPhoto" class="btn btn-change-logo mt-3">
                        <i class="bi bi-camera-fill me-2"></i> Change Photo
                    </label>
                    <input type="file" id="stylistPhoto" name="photo" accept="image/*" hidden>
                    <p class="image-hint">Recommended: square image, JPG or PNG, max 2MB</p>

                    <hr class="my-4">

                    <div class="text-start">
                        <label class="form-label-custom">Status</label>
                        <select name="status" class="form-select input-custom">
                            <option value="Active" {{ $currentStatus === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ $currentStatus === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- ===== RIGHT: details + services ===== --}}
            <div class="col-lg-8">
                <div class="panel-card">
                    <div class="panel-title">Staff Details</div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control input-custom"
                                   value="{{ old('name', $stylist['name']) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Role / Title <span class="text-danger">*</span></label>
                            <input type="text" name="role" class="form-control input-custom"
                                   placeholder="e.g. Senior Hair Stylist"
                                   value="{{ old('role', $stylist['role']) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Email <span class="text-muted">(optional)</span></label>
                            <input type="email" name="email" class="form-control input-custom"
                                   value="{{ old('email', $stylist['email']) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control input-custom"
                                   value="{{ old('phone', $stylist['phone']) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Specialization</label>
                            <select name="specialization" class="form-select input-custom">
                                <option value="">Select specialization</option>
                                @foreach ($specs as $spec)
                                    <option value="{{ $spec }}" {{ $currentSpec === $spec ? 'selected' : '' }}>{{ $spec }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Experience (years)</label>
                            <input type="number" name="experience_years" class="form-control input-custom"
                                   value="{{ old('experience_years', $stylist['experience_years']) }}" min="0">
                        </div>

                        <div class="col-12">
                            <label class="form-label-custom">Bio <span class="text-muted">(optional)</span></label>
                            <textarea name="bio" class="form-control input-custom" rows="4">{{ old('bio', $stylist['bio']) }}</textarea>
                        </div>

                        {{-- ===== SERVICES OFFERED ===== --}}
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label-custom mb-0">
                                    Services offered
                                    <span class="text-muted">(jo services ye team member karta hai)</span>
                                </label>
                                @if ($services->isNotEmpty())
                                    <button type="button" class="select-all-link" id="toggleAllServices">Select all</button>
                                @endif
                            </div>

                            @if ($services->isEmpty())
                                <div class="services-empty">
                                    Abhi is salon mein koi active service nahi hai. Pehle Services page se services add karo.
                                </div>
                            @else
                                <div class="services-grid">
                                    @foreach ($services as $service)
                                        <label class="service-check">
                                            <input type="checkbox" name="services[]" value="{{ $service->id }}"
                                                   {{ in_array($service->id, $checkedServices) ? 'checked' : '' }}>
                                            <span class="sc-name">{{ $service->name }}</span>
                                            <span class="sc-price">Rs. {{ number_format($service->price) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                            @error('services.*')
                                <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-save-changes">
                            <i class="bi bi-check-circle-fill me-2"></i> Save Changes
                        </button>
                        <a href="{{ route('owner.stylists.index') }}" class="btn btn-cancel-modal">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@section('extra-css')
<style>
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

    .alert {
        border-radius: 12px;
        border: none;
        padding: 0.8rem 1.2rem;
    }
    .alert-danger {
        background: #FCE4EC;
        color: #880E4F;
    }
    .alert ul {
        padding-left: 1.2rem;
        margin-bottom: 0;
    }

    .btn-back {
        background: #fff;
        border: 1px solid #f0e8ed;
        color: #2d1f2c;
        font-weight: 600;
        font-size: 14.5px;
        padding: 10px 20px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        transition: all 0.18s ease;
        text-decoration: none;
    }
    .btn-back:hover {
        background: #fcf6f9;
        border-color: #E85588;
        color: #E85588;
    }

    .panel-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f0e8ed;
        height: auto !important;
    }
    .panel-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2d1f2c;
        margin-bottom: 1rem;
    }

    /* ===== LEFT CARD: scroll karne par saath rahe ===== */
    @media (min-width: 992px) {
        .left-sticky {
            position: sticky;
            top: 90px;
        }
    }

    .stylist-photo-box {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: #fcf6f9;
        border: 2px dashed #f0d8e0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 56px;
        color: #E85588;
        overflow: hidden;
    }
    .stylist-photo-box img { width: 100%; height: 100%; object-fit: cover; }

    .image-hint { font-size: 12px; color: #8a7a88; margin-top: 10px; margin-bottom: 0; }

    .btn-change-logo {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 14px;
        padding: 9px 20px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.3);
        transition: all 0.18s ease;
        display: inline-flex;
        align-items: center;
    }
    .btn-change-logo:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(232, 85, 136, 0.45);
        color: #ffffff !important;
    }

    .form-label-custom { display: block; font-size: 13.5px; font-weight: 600; color: #4a3a48; margin-bottom: 6px; }
    .form-label-custom .text-danger { color: #E85588; }
    .form-label-custom .text-muted { font-weight: 400; font-size: 12.5px; color: #8a7a88; }

    .input-custom {
        background: #fcf6f9 !important;
        border: 1px solid #f0e8ed !important;
        border-radius: 10px !important;
        color: #2d1f2c !important;
        font-size: 14.5px;
        padding: 11px 14px !important;
        width: 100%;
    }
    .input-custom:focus {
        background: #fff !important;
        border-color: #E85588 !important;
        box-shadow: 0 0 0 3px rgba(232, 85, 136, 0.15) !important;
        outline: none;
    }

    /* ===== SERVICES CHECKBOXES ===== */
    .services-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
    .service-check {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #fcf6f9;
        border: 1px solid #f0e8ed;
        border-radius: 10px;
        padding: 10px 14px;
        cursor: pointer;
        transition: all 0.15s ease;
        margin: 0;
    }
    .service-check:hover { border-color: #E85588; }
    .service-check input {
        accent-color: #E85588;
        width: 17px;
        height: 17px;
        flex-shrink: 0;
        cursor: pointer;
    }
    .service-check .sc-name {
        flex: 1;
        font-size: 14px;
        font-weight: 600;
        color: #2d1f2c;
    }
    .service-check .sc-price {
        font-size: 12.5px;
        font-weight: 700;
        color: #D9A441;
        white-space: nowrap;
    }
    .services-empty {
        background: #fcf6f9;
        border: 1px dashed #f0d8e0;
        border-radius: 10px;
        padding: 14px;
        font-size: 13.5px;
        color: #8a7a88;
    }
    .select-all-link {
        background: none;
        border: none;
        padding: 0;
        font-size: 12.5px;
        font-weight: 600;
        color: #E85588;
        cursor: pointer;
    }
    .select-all-link:hover { text-decoration: underline; }

    .btn-save-changes {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        padding: 11px 26px;
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.35);
        display: inline-flex;
        align-items: center;
        transition: all 0.18s ease;
        text-decoration: none;
    }
    .btn-save-changes:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(232, 85, 136, 0.45);
        color: #ffffff !important;
    }

    .btn-cancel-modal {
        background: #fff;
        border: 1.5px solid #FF6B9D;
        color: #E85588;
        font-weight: 600;
        padding: 11px 26px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        transition: all 0.18s ease;
        text-decoration: none;
    }
    .btn-cancel-modal:hover {
        background: #E85588;
        color: #ffffff !important;
        border-color: #E85588;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch !important;
        }
        .btn-back {
            justify-content: center;
            width: 100%;
        }
        .d-flex.gap-3 {
            flex-wrap: wrap;
        }
        .btn-save-changes,
        .btn-cancel-modal {
            flex: 1;
            justify-content: center;
        }
        .services-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('extra-js')
<script>
    const photoInput = document.getElementById('stylistPhoto');
    const previewBox = document.getElementById('photoPreviewBox');

    if (photoInput) {
        photoInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewBox.innerHTML = `<img src="${e.target.result}" alt="Staff photo">`;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    // Select all / Clear all services
    const toggleAll = document.getElementById('toggleAllServices');
    if (toggleAll) {
        toggleAll.addEventListener('click', function () {
            const boxes = document.querySelectorAll('input[name="services[]"]');
            const allChecked = Array.from(boxes).every(b => b.checked);
            boxes.forEach(b => b.checked = !allChecked);
            this.textContent = allChecked ? 'Select all' : 'Clear all';
        });
    }
</script>
@endsection