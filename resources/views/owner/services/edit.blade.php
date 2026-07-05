@extends('layouts.owner')

@section('title', 'Edit Service')

@section('content')

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

    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>Edit Service</h2>
            <p>Update details for "{{ $service['name'] }}"</p>
        </div>
        <a href="{{ route('owner.services.index') }}" class="btn btn-back">
            <i class="bi bi-arrow-left me-2"></i> Back to Services
        </a>
    </div>

    <form action="{{ route('owner.services.update', $service['id']) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">

            <!-- LEFT COLUMN - IMAGE -->
            <div class="col-lg-4">
                <div class="panel-card text-center">
                    <div class="service-image-box mx-auto" id="imagePreviewBox">
                        @if (!empty($service['image_url']))
                            <img src="{{ $service['image_url'] }}" alt="{{ $service['name'] }}">
                        @else
                            <i class="bi bi-image"></i>
                            <span>No image uploaded</span>
                        @endif
                    </div>

                    <label for="serviceImage" class="btn btn-change-logo mt-3">
                        <i class="bi bi-camera-fill me-2"></i> Change Image
                    </label>
                    <input type="file" id="serviceImage" name="image" accept="image/*" hidden>
                    <p class="image-hint">Recommended: 600x400px, JPG or PNG, max 2MB</p>

                    <hr class="my-4">

                    <div class="text-start">
                        <label class="form-label-custom">Status</label>
                        <select name="status" class="form-select input-custom">
                            <option value="Active" {{ $service['status'] === 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ $service['status'] === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN - SERVICE DETAILS -->
            <div class="col-lg-8">
                <div class="panel-card">
                    <div class="panel-title">Service Details</div>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label-custom">Service Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control input-custom"
                                   value="{{ old('name', $service['name']) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select input-custom" required>
                                <option value="">Select category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ old('category_id', $service['category_id']) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-custom">Duration (minutes) <span class="text-danger">*</span></label>
                            <input type="number" name="duration" class="form-control input-custom"
                                   value="{{ old('duration', $service['duration']) }}" min="1" max="480" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-custom">Price (PKR) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control input-custom"
                                   value="{{ old('price', $service['price']) }}" min="0" step="0.01" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-custom">Discount Price (PKR) <span class="text-muted">(optional)</span></label>
                            <input type="number" name="discount_price" class="form-control input-custom"
                                   value="{{ old('discount_price', $service['discount_price']) }}" min="0" step="0.01">
                        </div>

                        <div class="col-12">
                            <label class="form-label-custom">Short Description</label>
                            <textarea name="description" class="form-control input-custom" rows="4">{{ old('description', $service['description']) }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label-custom">Notes for Clients <span class="text-muted">(optional)</span></label>
                            <textarea name="client_notes" class="form-control input-custom" rows="2">{{ old('client_notes', $service['client_notes']) }}</textarea>
                        </div>

                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-save-changes">
                            <i class="bi bi-check-circle-fill me-2"></i> Save Changes
                        </button>
                        <a href="{{ route('owner.services.index') }}" class="btn btn-cancel-modal">Cancel</a>
                    </div>

                </div>
            </div>

        </div>

    </form>

@endsection

@section('extra-css')
<style>
    .btn-back {
        background: var(--white);
        border: 1px solid var(--blush-200);
        color: var(--plum-800);
        font-weight: 600;
        font-size: 14.5px;
        padding: 10px 20px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        transition: all 0.18s ease;
    }
    .btn-back:hover {
        background: var(--blush-50);
        color: var(--plum-900);
    }

    .service-image-box {
        width: 100%;
        height: 200px;
        border-radius: var(--radius-md);
        background: var(--blush-50);
        border: 2px dashed var(--rose-300);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: var(--ink-500);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .service-image-box:hover {
        border-color: #FF6B9D;
    }
    .service-image-box i {
        font-size: 36px;
        color: var(--rose-400);
    }
    .service-image-box span {
        font-size: 13px;
    }
    .service-image-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-hint {
        font-size: 12px;
        color: var(--ink-500);
        margin-top: 10px;
        margin-bottom: 0;
    }

    .btn-change-logo {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 14px;
        padding: 9px 20px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(232, 85, 136, 0.3);
        transition: all 0.18s ease;
        display: inline-flex;
        align-items: center;
    }
    .btn-change-logo:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(232, 85, 136, 0.4);
        color: #ffffff !important;
    }

    .form-label-custom {
        display: block;
        font-size: 13.5px;
        font-weight: 600;
        color: var(--ink-700);
        margin-bottom: 6px;
    }
    .form-label-custom .text-danger {
        color: #E96A98;
        font-weight: 700;
    }
    .form-label-custom .text-muted {
        font-weight: 400;
        font-size: 12.5px;
    }

    .input-custom {
        background: var(--blush-50) !important;
        border: 1.5px solid var(--blush-200) !important;
        border-radius: var(--radius-sm) !important;
        color: var(--ink-900) !important;
        font-size: 14.5px;
        padding: 11px 14px !important;
        transition: all 0.25s ease;
        width: 100%;
    }
    .input-custom:focus {
        background: #fff !important;
        border-color: #FF6B9D !important;
        box-shadow: 0 0 0 3px rgba(255, 107, 157, 0.15) !important;
        outline: none;
    }

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
    }
    .btn-save-changes:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(232, 85, 136, 0.45);
        color: #ffffff !important;
    }

    .btn-cancel-modal {
        background: var(--white);
        border: 1px solid var(--blush-200);
        color: var(--ink-700);
        font-weight: 600;
        padding: 11px 26px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        transition: all 0.18s ease;
    }
    .btn-cancel-modal:hover {
        background: var(--blush-50);
        color: var(--ink-900);
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

    .panel-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid rgba(0,0,0,0.04);
        transition: all 0.3s ease;
        height: 100%;
    }
    .panel-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    .panel-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2d1f2c;
        margin-bottom: 1.2rem;
    }

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
</style>
@endsection

@section('extra-js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const serviceImageInput = document.getElementById('serviceImage');
        const previewBox = document.getElementById('imagePreviewBox');

        if (serviceImageInput) {
            serviceImageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const fileSize = file.size / 1024 / 1024;

                    if (fileSize > 2) {
                        alert('File size must be less than 2MB. Current: ' + fileSize.toFixed(2) + 'MB');
                        this.value = '';
                        return;
                    }

                    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp'];
                    if (!validTypes.includes(file.type)) {
                        alert('Please upload a valid image file (JPG, PNG, GIF, SVG, WEBP)');
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewBox.innerHTML = `<img src="${e.target.result}" alt="Service preview">`;
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }

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