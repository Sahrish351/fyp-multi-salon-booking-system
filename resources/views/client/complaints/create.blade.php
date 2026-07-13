@extends('layouts.client')

@section('title', 'Submit Complaint — Glamora')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-exclamation-circle" style="color:#E91E8C;"></i> Submit Complaint</h3>
                    <p style="color:#888;font-size:0.85rem;">Select an appointment and describe your issue</p>
                </div>
                <div class="card-body">
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

                    <form action="{{ route('client.complaints.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- ============================================ --}}
                        {{-- Appointment Dropdown --}}
                        {{-- ============================================ --}}
                        <div class="form-group mb-3">
                            <label for="appointment_id"><i class="fas fa-calendar-check" style="color:#E91E8C;"></i> Select Appointment <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <select name="appointment_id" id="appointment_id" class="form-control" required style="appearance: none; -webkit-appearance: none; -moz-appearance: none; padding-right: 40px;">
                                    <option value="">-- Select Appointment --</option>
                                    @foreach($appointments as $appt)
                                    <option value="{{ $appt->id }}" {{ isset($appointment) && $appointment->id == $appt->id ? 'selected' : '' }}>
                                        #{{ $appt->id }} - {{ $appt->salon->name ?? 'N/A' }} ({{ $appt->appointment_date ?? 'N/A' }})
                                    </option>
                                    @endforeach
                                </select>
                                <span style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #E91E8C; font-size: 14px;">
                                    <i class="fas fa-chevron-down"></i>
                                </span>
                            </div>
                            @error('appointment_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ============================================ --}}
                        {{-- ✅ COMPLAINT TYPE DROPDOWN --}}
                        {{-- ============================================ --}}
                        <div class="form-group mb-3">
                            <label for="type"><i class="fas fa-tag" style="color:#E91E8C;"></i> Complaint Type <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <select name="type" id="type" class="form-control" required style="appearance: none; -webkit-appearance: none; -moz-appearance: none; padding-right: 40px;">
                                    <option value="">-- Select Type --</option>
                                    <option value="service" {{ old('type') == 'service' ? 'selected' : '' }}>Service Issue</option>
                                    <option value="staff" {{ old('type') == 'staff' ? 'selected' : '' }}>Staff Behavior</option>
                                    <option value="payment" {{ old('type') == 'payment' ? 'selected' : '' }}>Payment Issue</option>
                                    <option value="product" {{ old('type') == 'product' ? 'selected' : '' }}>Product Issue</option>
                                    <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <span style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #E91E8C; font-size: 14px;">
                                    <i class="fas fa-chevron-down"></i>
                                </span>
                            </div>
                            @error('type')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ============================================ --}}
                        {{-- Subject / Category Dropdown --}}
                        {{-- ============================================ --}}
                        <div class="form-group mb-3">
                            <label for="complaintSubject"><i class="fas fa-heading" style="color:#E91E8C;"></i> Subject <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <select name="subject" id="complaintSubject" class="form-control" required style="appearance: none; -webkit-appearance: none; -moz-appearance: none; padding-right: 40px;">
                                    <option value="">-- Select Subject --</option>
                                    <option value="Payment Issue" {{ old('subject') == 'Payment Issue' ? 'selected' : '' }}>💰 Payment Issue</option>
                                    <option value="Staff Behavior" {{ old('subject') == 'Staff Behavior' ? 'selected' : '' }}>👤 Staff Behavior</option>
                                    <option value="Poor Service" {{ old('subject') == 'Poor Service' ? 'selected' : '' }}>⭐ Poor Service</option>
                                    <option value="Wrong Service" {{ old('subject') == 'Wrong Service' ? 'selected' : '' }}>❌ Wrong Service</option>
                                    <option value="Overcharging" {{ old('subject') == 'Overcharging' ? 'selected' : '' }}>💸 Overcharging</option>
                                    <option value="Hygiene Issue" {{ old('subject') == 'Hygiene Issue' ? 'selected' : '' }}>🧹 Hygiene Issue</option>
                                    <option value="Appointment Issue" {{ old('subject') == 'Appointment Issue' ? 'selected' : '' }}>📅 Appointment Issue</option>
                                    <option value="Cancellation Dispute" {{ old('subject') == 'Cancellation Dispute' ? 'selected' : '' }}>🚫 Cancellation Dispute</option>
                                    <option value="Other" {{ old('subject') == 'Other' ? 'selected' : '' }}>📝 Other</option>
                                </select>
                                <span style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #E91E8C; font-size: 14px;">
                                    <i class="fas fa-chevron-down"></i>
                                </span>
                            </div>
                            @error('subject')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ============================================ --}}
                        {{-- Custom Subject Input --}}
                        {{-- ============================================ --}}
                        <div class="form-group mb-3" id="customSubjectWrapper" style="display:none;">
                            <label for="customSubject"><i class="fas fa-pen" style="color:#E91E8C;"></i> Please specify</label>
                            <input type="text" name="custom_subject" id="customSubject" class="form-control" placeholder="Enter your subject..." value="{{ old('custom_subject') }}">
                        </div>

                        {{-- ============================================ --}}
                        {{-- Description --}}
                        {{-- ============================================ --}}
                        <div class="form-group mb-3">
                            <label for="description"><i class="fas fa-align-left" style="color:#E91E8C;"></i> Description <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control" rows="5" required placeholder="Describe your complaint in detail...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ============================================ --}}
                        {{-- Image Attachment --}}
                        {{-- ============================================ --}}
                        <div class="form-group mb-3">
                            <label for="image"><i class="fas fa-image" style="color:#E91E8C;"></i> Attachment <span class="text-muted">(Optional)</span></label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            <small class="text-muted">Max 2MB (JPG, PNG, GIF)</small>
                            @error('image')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ============================================ --}}
                        {{-- Buttons --}}
                        {{-- ============================================ --}}
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn" style="background:linear-gradient(135deg,#E91E8C,#c2185b);color:#fff;border:none;border-radius:50px;padding:10px 30px;font-weight:600;">
                                <i class="fas fa-paper-plane me-2"></i>Submit Complaint
                            </button>
                            <a href="{{ route('client.complaints.index') }}" class="btn" style="background:#f0f0f0;color:#555;border:none;border-radius:50px;padding:10px 30px;">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var subjectDropdown = document.getElementById('complaintSubject');
        var customWrapper = document.getElementById('customSubjectWrapper');
        var customInput = document.getElementById('customSubject');

        subjectDropdown.addEventListener('change', function() {
            if (this.value === 'Other') {
                customWrapper.style.display = 'block';
                customInput.required = true;
            } else {
                customWrapper.style.display = 'none';
                customInput.required = false;
                customInput.value = '';
            }
        });
    });
</script>
@endpush

@endsection