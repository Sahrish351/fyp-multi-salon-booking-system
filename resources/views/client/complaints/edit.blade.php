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
    <p style="color:#aaa;font-size:0.85rem;margin:0;">Update your subject or description while it's still open</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="bg-white rounded-4 p-4" style="border:1px solid #fce4ec;">

            {{-- Appointment shown read-only — it can't be changed once filed --}}
            <div class="p-3 rounded-3 mb-4" style="background:#fff5f9;border:1px solid #fce4ec;">
                <div style="color:#aaa;font-size:0.72rem;"><i class="fas fa-calendar-check me-1" style="color:#E91E8C;font-size:0.7rem;"></i>Appointment</div>
                <div style="color:#333;font-weight:600;font-size:0.88rem;">
                    #{{ $complaint->appointment_id }} — {{ $complaint->salon->name ?? 'N/A' }}
                </div>
            </div>

            <form action="{{ route('client.complaints.update', $complaint->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label style="color:#555;font-size:0.85rem;font-weight:600;" class="mb-2 d-block">Subject *</label>
                    <input type="text" name="subject" class="form-control" required value="{{ old('subject', $complaint->subject) }}"
                           style="border:2px solid #fce4ec;border-radius:10px;padding:10px 14px;font-size:0.88rem;"
                           onfocus="this.style.borderColor='#E91E8C'" onblur="this.style.borderColor='#fce4ec'">
                    @error('subject')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label style="color:#555;font-size:0.85rem;font-weight:600;" class="mb-2 d-block">Description *</label>
                    <textarea name="description" rows="5" class="form-control" required
                              style="border:2px solid #fce4ec;border-radius:10px;padding:10px 14px;font-size:0.88rem;"
                              onfocus="this.style.borderColor='#E91E8C'" onblur="this.style.borderColor='#fce4ec'">{{ old('description', $complaint->description) }}</textarea>
                    @error('description')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

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