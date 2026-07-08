@extends('layouts.owner')

@section('title', 'Add Team Member')

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

    {{-- Page Header --}}
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>Add Team Member</h2>
            <p>Add a new stylist or staff member to your salon</p>
        </div>
        <a href="{{ route('owner.stylists.index') }}" class="btn btn-back">
            <i class="bi bi-arrow-left me-2"></i> Back to Team
        </a>
    </div>

    <form action="{{ route('owner.stylists.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">

            <div class="col-lg-4 d-flex">
                <div class="panel-card text-center w-100">
                    <div class="stylist-photo-box mx-auto" id="photoPreviewBox">
                        <i class="bi bi-person-fill"></i>
                    </div>

                    <label for="stylistPhoto" class="btn btn-change-logo mt-3">
                        <i class="bi bi-camera-fill me-2"></i> Upload Photo
                    </label>
                    <input type="file" id="stylistPhoto" name="photo" accept="image/*" hidden>
                    <p class="image-hint">Recommended: square image, JPG or PNG, max 2MB</p>

                    <hr class="my-4">

                    <div class="text-start">
                        <label class="form-label-custom">Status</label>
                        <select name="status" class="form-select input-custom">
                            <option value="Active" selected>Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 d-flex">
                <div class="panel-card w-100">
                    <div class="panel-title">Staff Details</div>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label-custom">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control input-custom @error('name') is-invalid @enderror"
                                   placeholder="e.g. Ayesha Khan" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Role / Title <span class="text-danger">*</span></label>
                            <input type="text" name="role" class="form-control input-custom @error('role') is-invalid @enderror"
                                   placeholder="e.g. Senior Hair Stylist" value="{{ old('role') }}" required>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Email <span class="text-muted">(optional)</span></label>
                            <input type="email" name="email" class="form-control input-custom @error('email') is-invalid @enderror"
                                   placeholder="ayesha@glowaura.pk" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control input-custom @error('phone') is-invalid @enderror"
                                   placeholder="+92 300 1234567" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Specialization</label>
                            <select name="specialization" class="form-select input-custom @error('specialization') is-invalid @enderror">
                                <option value="">Select specialization</option>
                                <option value="Hair Styling" {{ old('specialization') == 'Hair Styling' ? 'selected' : '' }}>Hair Styling</option>
                                <option value="Barber" {{ old('specialization') == 'Barber' ? 'selected' : '' }}>Barber</option>
                                <option value="Nail Care" {{ old('specialization') == 'Nail Care' ? 'selected' : '' }}>Nail Care</option>
                                <option value="Facial" {{ old('specialization') == 'Facial' ? 'selected' : '' }}>Facial</option>
                                <option value="Spa & Massage" {{ old('specialization') == 'Spa & Massage' ? 'selected' : '' }}>Spa & Massage</option>
                                <option value="Makeup" {{ old('specialization') == 'Makeup' ? 'selected' : '' }}>Makeup</option>
                            </select>
                            @error('specialization')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Experience (years)</label>
                            <input type="number" name="experience_years" class="form-control input-custom @error('experience_years') is-invalid @enderror"
                                   placeholder="5" min="0" value="{{ old('experience_years', 0) }}">
                            @error('experience_years')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label-custom">Bio <span class="text-muted">(optional)</span></label>
                            <textarea name="bio" class="form-control input-custom @error('bio') is-invalid @enderror" rows="4"
                                      placeholder="A short bio about this team member...">{{ old('bio') }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-save-changes">
                            <i class="bi bi-check-circle-fill me-2"></i> Add Team Member
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
    /* ===== PAGE HEADER ===== */
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

    /* ===== BACK BUTTON ===== */
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

    /* ===== ALERTS ===== */
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

    /* ===== PANEL CARD ===== */
    .panel-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f0e8ed;
        height: 100% !important;
        display: flex;
        flex-direction: column;
    }

    .panel-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2d1f2c;
        margin-bottom: 1rem;
        flex-shrink: 0;
    }

    /* ===== STYLIST PHOTO ===== */
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
        flex-shrink: 0;
    }
    .stylist-photo-box img { width: 100%; height: 100%; object-fit: cover; }

    .image-hint {
        font-size: 12px;
        color: #8a7a88;
        margin-top: 10px;
        margin-bottom: 0;
    }

    /* ===== CHANGE PHOTO BUTTON - PINK ===== */
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

    /* ===== FORM ===== */
    .form-label-custom {
        display: block;
        font-size: 13.5px;
        font-weight: 600;
        color: #4a3a48;
        margin-bottom: 6px;
    }
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

    .is-invalid {
        border-color: #E85588 !important;
    }
    .invalid-feedback {
        color: #E85588;
        font-size: 12px;
        margin-top: 4px;
    }

    /* ============================================================
       SAVE/ADD BUTTON - PINK
       ============================================================ */
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

    /* ============================================================
       CANCEL BUTTON - PINK OUTLINE
       ============================================================ */
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

    /* ============================================================
       RESPONSIVE
       ============================================================ */
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
        .col-lg-4.d-flex,
        .col-lg-8.d-flex {
            flex: 0 0 100%;
            max-width: 100%;
        }
        .panel-card {
            height: auto !important;
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
</script>
@endsection