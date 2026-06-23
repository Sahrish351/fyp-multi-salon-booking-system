
@extends('layouts.owner')

@section('title', 'Edit Service')

@section('content')

    @php
        // Demo/dummy data — BAAD ME ye $service controller se aayega (Service::findOrFail($id))
        $service = $service ?? [
            'id'             => 1,
            'name'           => 'Premium Haircut',
            'category'       => 'Hair Styling',
            'duration'       => 45,
            'price'          => 85,
            'discount_price' => null,
            'description'    => 'A precision haircut tailored to your face shape and style preference, finished with a professional blow-dry.',
            'client_notes'   => 'Please arrive 10 minutes early for a consultation.',
            'status'         => 'Active',
            'image_url'      => null,
        ];
    @endphp

  
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>Edit Service</h2>
            <p>Update details for "{{ $service['name'] }}"</p>
        </div>
        <a href="{{ route('owner.services.index') }}" class="btn btn-back">
            <i class="bi bi-arrow-left me-2"></i> Back to Services
        </a>
    </div>

    <form action="{{ route('owner.services.update', ['service' => $service['id']]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">

            
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

            
            <div class="col-lg-8">
                <div class="panel-card">
                    <div class="panel-title">Service Details</div>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label-custom">Service Name</label>
                            <input type="text" name="name" class="form-control input-custom"
                                   value="{{ $service['name'] }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Category</label>
                            <select name="category" class="form-select input-custom" required>
                                @foreach ($categories ?? ['Hair Styling', 'Nail Care', 'Facial', 'Spa', 'Makeup'] as $cat)
                                    <option value="{{ $cat }}" {{ $service['category'] === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-custom">Duration (minutes)</label>
                            <input type="number" name="duration" class="form-control input-custom"
                                   value="{{ $service['duration'] }}" min="1" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-custom">Price ($)</label>
                            <input type="number" name="price" class="form-control input-custom"
                                   value="{{ $service['price'] }}" min="0" step="0.01" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-custom">Discount Price ($) <span class="text-muted">(optional)</span></label>
                            <input type="number" name="discount_price" class="form-control input-custom"
                                   value="{{ $service['discount_price'] }}" min="0" step="0.01">
                        </div>

                        <div class="col-12">
                            <label class="form-label-custom">Short Description</label>
                            <textarea name="description" class="form-control input-custom" rows="4">{{ $service['description'] }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label-custom">Notes for Clients <span class="text-muted">(optional)</span></label>
                            <textarea name="client_notes" class="form-control input-custom" rows="2">{{ $service['client_notes'] }}</textarea>
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
        background: var(--white); border: 1px solid var(--blush-200); color: var(--plum-800);
        font-weight: 600; font-size: 14.5px; padding: 10px 20px; border-radius: 10px;
        display: inline-flex; align-items: center; transition: all 0.18s ease;
    }
    .btn-back:hover { background: var(--blush-50); color: var(--plum-900); }

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
    }
    .service-image-box i { font-size: 36px; color: var(--rose-400); }
    .service-image-box span { font-size: 13px; }
    .service-image-box img { width: 100%; height: 100%; object-fit: cover; }

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
    const serviceImageInput = document.getElementById('serviceImage');
    const previewBox = document.getElementById('imagePreviewBox');

    if (serviceImageInput) {
        serviceImageInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewBox.innerHTML = `<img src="${e.target.result}" alt="Service preview">`;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
</script>
@endsection
