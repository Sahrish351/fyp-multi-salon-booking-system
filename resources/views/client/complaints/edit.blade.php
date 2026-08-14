{{-- resources/views/client/complaints/edit.blade.php --}}
@extends('layouts.client')

@section('title', 'Edit Complaint — Glamora')

@section('content')

<div class="mb-4">
    <a href="{{ route('client.complaints.show', $complaint->id) }}" style="color:#aaa;text-decoration:none;font-size:0.85rem;">
        <i class="fas fa-arrow-left me-2"></i>Back to Complaint
    </a>
    <h4 class="fw-bold mt-2 mb-1" style="color:#333;font-family:'Playfair Display',serif;">
        <i class="fas fa-pen me-2" style="color:#E91E8C;"></i>Edit Complaint
    </h4>
    <p style="color:#aaa;font-size:0.85rem;margin:0;">Update your complaint details</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="bg-white rounded-4 p-4" style="border:1px solid #fce4ec;">

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Appointment shown read-only — it can't be changed once filed --}}
            <div class="p-3 rounded-3 mb-4" style="background:#fff5f9;border:1px solid #fce4ec;">
                <div style="color:#aaa;font-size:0.72rem;"><i class="fas fa-calendar-check me-1" style="color:#E91E8C;font-size:0.7rem;"></i>Appointment</div>
                <div style="color:#333;font-weight:600;font-size:0.88rem;">
                    #{{ $complaint->appointment_id }} — {{ $complaint->salon->name ?? 'N/A' }}
                </div>
            </div>

            <form action="{{ route('client.complaints.update', $complaint->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- ============================================ --}}
                {{-- ✅ COMPLAINT TYPE DROPDOWN --}}
                {{-- ============================================ --}}
                <div class="mb-3">
                    <label style="color:#555;font-size:0.85rem;font-weight:600;" class="mb-2 d-block">Complaint Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-control" required style="border:2px solid #fce4ec;border-radius:10px;padding:10px 14px;font-size:0.88rem;">
                        <option value="service" {{ old('type', $complaint->type) == 'service' ? 'selected' : '' }}>Service Issue</option>
                        <option value="staff" {{ old('type', $complaint->type) == 'staff' ? 'selected' : '' }}>Staff Behavior</option>
                        <option value="payment" {{ old('type', $complaint->type) == 'payment' ? 'selected' : '' }}>Payment Issue</option>
                        <option value="product" {{ old('type', $complaint->type) == 'product' ? 'selected' : '' }}>Product Issue</option>
                        <option value="other" {{ old('type', $complaint->type) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('type')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- ============================================ --}}
                {{-- Subject --}}
                {{-- ============================================ --}}
                <div class="mb-3">
                    <label style="color:#555;font-size:0.85rem;font-weight:600;" class="mb-2 d-block">Subject <span class="text-danger">*</span></label>
                    <input type="text" name="subject" class="form-control" required value="{{ old('subject', $complaint->subject) }}"
                           style="border:2px solid #fce4ec;border-radius:10px;padding:10px 14px;font-size:0.88rem;"
                           onfocus="this.style.borderColor='#E91E8C'" onblur="this.style.borderColor='#fce4ec'">
                    @error('subject')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- ============================================ --}}
                {{-- Description --}}
                {{-- ============================================ --}}
                <div class="mb-3">
                    <label style="color:#555;font-size:0.85rem;font-weight:600;" class="mb-2 d-block">Description <span class="text-danger">*</span></label>
                    <textarea name="description" rows="5" class="form-control" required
                              style="border:2px solid #fce4ec;border-radius:10px;padding:10px 14px;font-size:0.88rem;"
                              onfocus="this.style.borderColor='#E91E8C'" onblur="this.style.borderColor='#fce4ec'">{{ old('description', $complaint->description) }}</textarea>
                    @error('description')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- ============================================ --}}
                {{-- Current Attachment --}}
                {{-- ============================================ --}}
                @if($complaint->image)
                    <div class="mb-3">
                        <label style="color:#555;font-size:0.85rem;font-weight:600;" class="mb-2 d-block">Current Attachment</label>
                        <div class="d-flex align-items-center gap-3">
                            <a href="{{ asset('storage/' . $complaint->image) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-image me-1"></i> View Image
                            </a>
                            <span class="text-muted small">(Upload new to replace)</span>
                        </div>
                    </div>
                @endif

                {{-- ============================================ --}}
                {{-- Replace Attachment --}}
                {{-- ============================================ --}}
                <div class="mb-3">
                    <label style="color:#555;font-size:0.85rem;font-weight:600;" class="mb-2 d-block">Replace Attachment <span class="text-muted">(Optional)</span></label>
                    <input type="file" name="image" class="form-control" accept="image/*"
                           style="border:2px solid #fce4ec;border-radius:10px;padding:10px 14px;font-size:0.88rem;">
                    <small class="text-muted">Max 2MB (JPG, PNG, GIF)</small>
                    @error('image')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- ============================================ --}}
                {{-- Status (Read Only) --}}
                {{-- ============================================ --}}
                <div class="mb-3">
                    <label style="color:#555;font-size:0.85rem;font-weight:600;" class="mb-2 d-block">Status</label>
                    <input type="text" class="form-control" value="{{ ucfirst($complaint->status) }}" disabled
                           style="border:2px solid #fce4ec;border-radius:10px;padding:10px 14px;font-size:0.88rem;background:#f9f9f9;">
                    <small class="text-muted">Status cannot be changed manually</small>
                </div>

                {{-- ============================================ --}}
                {{-- Buttons --}}
                {{-- ============================================ --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn rounded-pill px-4" style="background:linear-gradient(135deg,#E91E8C,#c2185b);color:#fff;border:none;font-weight:600;">
                        <i class="fas fa-check me-2"></i>Save Changes
                    </button>
                    <a href="{{ route('client.complaints.show', $complaint->id) }}" class="btn rounded-pill px-4" style="background:#f0f0f0;color:#555;border:none;font-weight:600;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection