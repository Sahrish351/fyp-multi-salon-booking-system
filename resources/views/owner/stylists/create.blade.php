{{--
    ===========================================================
    TEAM MEMBER CREATE PAGE (resources/views/owner/stylists/create.blade.php)
    Route: GET /owner/stylists/create --> owner.stylists.create
    ===========================================================
--}}
@extends('layouts.owner')

@section('title', 'Add Team Member')

@section('content')

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

            {{-- ===================== LEFT: PHOTO UPLOAD ===================== --}}
            <div class="col-lg-4">
                <div class="panel-card text-center">
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

            {{-- ===================== RIGHT: STAFF DETAILS FORM ===================== --}}
            <div class="col-lg-8">
                <div class="panel-card">
                    <div class="panel-title">Staff Details</div>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label-custom">Full Name</label>
                            <input type="text" name="name" class="form-control input-custom"
                                   placeholder="e.g. Emma Wilson" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Role / Title</label>
                            <input type="text" name="role" class="form-control input-custom"
                                   placeholder="e.g. Senior Hair Stylist" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Email</label>
                            <input type="email" name="email" class="form-control input-custom"
                                   placeholder="emma@glowaura.com" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Phone</label>
                            <input type="text" name="phone" class="form-control input-custom"
                                   placeholder="+1 (555) 123-4567" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Specialization</label>
                            <select name="specialization" class="form-select input-custom">
                                <option value="">Select specialization</option>
                                <option>Hair Styling</option>
                                <option>Nail Care</option>
                                <option>Facial</option>
                                <option>Spa &amp; Massage</option>
                                <option>Makeup</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Experience (years)</label>
                            <input type="number" name="experience_years" class="form-control input-custom"
                                   placeholder="5" min="0">
                        </div>

                        <div class="col-12">
                            <label class="form-label-custom">Bio <span class="text-muted">(optional)</span></label>
                            <textarea name="bio" class="form-control input-custom" rows="4"
                                      placeholder="A short bio about this team member, their experience, and specialties..."></textarea>
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
    .btn-back {
        background: var(--white); border: 1px solid var(--blush-200); color: var(--plum-800);
        font-weight: 600; font-size: 14.5px; padding: 10px 20px; border-radius: 10px;
        display: inline-flex; align-items: center; transition: all 0.18s ease;
    }
    .btn-back:hover { background: var(--blush-50); color: var(--plum-900); }

    .stylist-photo-box {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: var(--blush-50);
        border: 2px dashed var(--rose-300);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 56px;
        color: var(--rose-400);
        overflow: hidden;
    }
    .stylist-photo-box img { width: 100%; height: 100%; object-fit: cover; }

    .image-hint { font-size: 12px; color: var(--ink-500); margin-top: 10px; margin-bottom: 0; }

    .btn-change-logo {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        color: var(--plum-900); font-weight: 600; font-size: 14px;
        padding: 9px 20px; border-radius: 10px; border: none; cursor: pointer;
        box-shadow: 0 4px 12px rgba(217, 164, 65, 0.3); transition: all 0.18s ease;
        display: inline-flex; align-items: center;
    }
    .btn-change-logo:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(217, 164, 65, 0.45); }

    .form-label-custom { display: block; font-size: 13.5px; font-weight: 600; color: var(--ink-700); margin-bottom: 6px; }
    .input-custom {
        background: var(--blush-50) !important; border: 1px solid var(--blush-200) !important;
        border-radius: var(--radius-sm) !important; color: var(--ink-900) !important;
        font-size: 14.5px; padding: 11px 14px !important;
    }
    .input-custom:focus { background: #fff !important; border-color: var(--rose-400) !important; box-shadow: 0 0 0 3px rgba(240, 143, 180, 0.2) !important; outline: none; }

    .btn-save-changes {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        color: var(--plum-900); font-weight: 700; padding: 11px 26px; border-radius: 10px; border: none;
        display: inline-flex; align-items: center;
    }
    .btn-save-changes:hover { color: var(--plum-900); transform: translateY(-1px); box-shadow: 0 6px 16px rgba(217, 164, 65, 0.4); }

    .btn-cancel-modal {
        background: var(--white); border: 1px solid var(--blush-200); color: var(--ink-700);
        font-weight: 600; padding: 11px 26px; border-radius: 10px; display: inline-flex; align-items: center;
    }
    .btn-cancel-modal:hover { background: var(--blush-50); color: var(--ink-900); }
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
