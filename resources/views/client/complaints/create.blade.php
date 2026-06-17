@extends('layouts.client')

@section('title', 'Submit Complaint')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3>Submit Complaint</h3>
                    <p style="color:#888;font-size:0.85rem;">Select an appointment and describe your issue</p>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('client.complaints.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label>Select Appointment</label>
                            <select name="appointment_id" class="form-control" required>
                                <option value="">-- Select Appointment --</option>
                                @foreach($appointments as $appt)
                                <option value="{{ $appt->id }}" {{ isset($appointment) && $appointment->id == $appt->id ? 'selected' : '' }}>
                                    #{{ $appt->id }} - {{ $appt->salon->name ?? 'N/A' }} ({{ $appt->appointment_date ?? 'N/A' }})
                                </option>
                                @endforeach
                            </select>
                            @error('appointment_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label>Subject</label>
                            <input type="text" name="subject" class="form-control" required placeholder="Brief subject of complaint" value="{{ old('subject') }}">
                            @error('subject')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="5" required placeholder="Describe your complaint in detail">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn" style="background:linear-gradient(135deg,#E91E8C,#c2185b);color:#fff;border:none;border-radius:50px;padding:10px 30px;font-weight:600;">Submit Complaint</button>
                        <a href="{{ route('client.complaints.index') }}" class="btn" style="background:#f0f0f0;color:#555;border:none;border-radius:50px;padding:10px 30px;">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection