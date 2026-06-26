@extends('layouts.admin')
@section('title', 'Edit Salon')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Salon</h5>
                <a href="{{ route('admin.salons.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to Salons
                </a>
            </div>
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <strong><i class="fas fa-exclamation-circle me-2"></i>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.salons.update', $salon->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        {{-- Salon Name --}}
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">Salon Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $salon->name) }}" 
                                   placeholder="Enter salon name" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Owner --}}
                        <div class="col-md-6">
                            <label for="owner_id" class="form-label fw-semibold">Owner <span class="text-danger">*</span></label>
                            <select name="owner_id" 
                                    id="owner_id" 
                                    class="form-select @error('owner_id') is-invalid @enderror" 
                                    required>
                                <option value="">-- Select Owner --</option>
                                @foreach($owners as $owner)
                                    <option value="{{ $owner->id }}" {{ old('owner_id', $salon->owner_id) == $owner->id ? 'selected' : '' }}>
                                        {{ $owner->name }} ({{ $owner->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('owner_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- City --}}
                        <div class="col-md-6">
                            <label for="city" class="form-label fw-semibold">City <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="city" 
                                   id="city" 
                                   class="form-control @error('city') is-invalid @enderror" 
                                   value="{{ old('city', $salon->city) }}" 
                                   placeholder="e.g., Lahore" 
                                   required>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Area --}}
                        <div class="col-md-6">
                            <label for="area" class="form-label fw-semibold">Area <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="area" 
                                   id="area" 
                                   class="form-control @error('area') is-invalid @enderror" 
                                   value="{{ old('area', $salon->area) }}" 
                                   placeholder="e.g., Gulberg" 
                                   required>
                            @error('area')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Full Address --}}
                        <div class="col-12">
                            <label for="address" class="form-label fw-semibold">Full Address <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="address" 
                                   id="address" 
                                   class="form-control @error('address') is-invalid @enderror" 
                                   value="{{ old('address', $salon->address) }}" 
                                   placeholder="Complete street address" 
                                   required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="phone" 
                                   id="phone" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone', $salon->phone) }}" 
                                   placeholder="e.g., 0300-1234567" 
                                   required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $salon->email) }}" 
                                   placeholder="salon@example.com" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" 
                                    id="status" 
                                    class="form-select @error('status') is-invalid @enderror" 
                                    required>
                                <option value="approved" {{ old('status', $salon->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="pending" {{ old('status', $salon->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="suspended" {{ old('status', $salon->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="4" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      placeholder="Describe the salon, services, ambiance...">{{ old('description', $salon->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Logo Upload --}}
                        <div class="col-12">
                            <label for="logo" class="form-label fw-semibold">Logo</label>

                            @if($salon->logo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/'.$salon->logo) }}" 
                                         style="max-width:150px; max-height:150px; border:1px solid #ddd; padding:5px; border-radius:6px;">
                                    <div class="form-text">Current logo</div>
                                </div>
                            @endif

                            <input type="file" 
                                   name="logo" 
                                   id="logo" 
                                   class="form-control @error('logo') is-invalid @enderror" 
                                   accept="image/*">
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div id="logoPreview" class="mt-2" style="display:none;">
                                <img id="logoPreviewImg" src="#" style="max-width:150px; max-height:150px; border:1px solid #ddd; padding:5px; border-radius:6px;">
                                <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="clearLogoPreview()">
                                    <i class="fas fa-times me-1"></i> Remove
                                </button>
                            </div>
                            <div class="form-text">Supported: JPG, PNG, GIF (Max 2MB)</div>
                        </div>

                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Salon
                        </button>
                        <a href="{{ route('admin.salons.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('logo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                const preview = document.getElementById('logoPreview');
                const img = document.getElementById('logoPreviewImg');
                img.src = ev.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });

    function clearLogoPreview() {
        document.getElementById('logoPreview').style.display = 'none';
        document.getElementById('logo').value = '';
    }
</script>
@endpush

@endsection