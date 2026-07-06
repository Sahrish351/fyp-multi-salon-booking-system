{{-- ============================================================ --}}
{{-- FILE: resources/views/client/reschedule/create.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.client')
@section('title', 'Reschedule Appointment — Glamora')

@push('styles')
<style>
.reschedule-header {
    background: linear-gradient(135deg, #fff5f9, #fce4ec);
    border-radius: 20px;
    padding: 1.5rem;
    border: 1px solid #fce4ec;
    margin-bottom: 1.5rem;
}
.info-card {
    background: #fff;
    border: 1px solid #fce4ec;
    border-radius: 16px;
    padding: 1.5rem;
}
.field-control {
    border: 2px solid #fce4ec;
    border-radius: 12px;
    padding: 0.85rem 1rem;
    font-size: 0.9rem;
    width: 100%;
    transition: border-color .15s;
}
.field-control:focus {
    outline: none;
    border-color: #E91E8C;
}
</style>
@endpush

@section('content')

<div class="mb-4">
    <a href="{{ route('client.appointments.show', $appointment->id) }}"
       style="color:#aaa;text-decoration:none;font-size:0.85rem;">
        <i class="fas fa-arrow-left me-2"></i>Back to Appointment
    </a>
    <h4 class="fw-bold mt-2 mb-0" style="color:#333;font-family:'Playfair Display',serif;">
        <i class="fas fa-calendar-edit me-2" style="color:#E91E8C;"></i>Reschedule Appointment
    </h4>
</div>

{{-- Current Appointment Summary --}}
<div class="reschedule-header">
    <div class="d-flex align-items-start gap-3 flex-wrap">
        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
             style="width:56px;height:56px;background:linear-gradient(135deg,#E91E8C,#c2185b);">
            <i class="fas fa-calendar-times text-white" style="font-size:1.2rem;"></i>
        </div>
        <div class="flex-grow-1">
            <div style="color:#aaa;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">
                Current Appointment
            </div>
            <h6 class="fw-bold mb-1" style="color:#333;">{{ $appointment->salon->name }}</h6>
            <div class="d-flex flex-wrap gap-3">
                <span style="color:#888;font-size:0.82rem;">
                    <i class="fas fa-spa me-1" style="color:#E91E8C;"></i>{{ $appointment->service->name }}
                </span>
                <span style="color:#888;font-size:0.82rem;">
                    <i class="fas fa-user-circle me-1" style="color:#C9A96E;"></i>{{ $appointment->stylist->name }}
                </span>
                <span style="color:#888;font-size:0.82rem;">
                    <i class="fas fa-calendar me-1" style="color:#E91E8C;"></i>
                    {{ $appointment->appointment_date->format('d M Y') }}
                </span>
                <span style="color:#888;font-size:0.82rem;">
                    <i class="fas fa-clock me-1" style="color:#c2185b;"></i>
                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}
                </span>
            </div>
        </div>
        <div class="text-end">
            <div style="color:#E91E8C;font-weight:700;font-size:0.95rem;">
                Rs. {{ number_format($appointment->total_amount) }}
            </div>
            <div style="color:#aaa;font-size:0.75rem;">{{ $appointment->booking_ref }}</div>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- Reschedule Form --}}
    <div class="col-lg-8">
        <div class="info-card">
            <h5 class="fw-bold mb-1" style="color:#333;font-family:'Playfair Display',serif;">
                Pick New Date & Time
            </h5>
            <p style="color:#aaa;font-size:0.85rem;margin-bottom:1.5rem;">
                Choose a new date and time for your appointment with {{ $appointment->stylist->name }}
            </p>

            <form action="{{ route('client.appointments.reschedule', $appointment->id) }}" method="POST" id="rescheduleForm">
                @csrf

                <div class="row g-4">
                    {{-- Date Picker --}}
                    <div class="col-md-6">
                        <label style="color:#555;font-size:0.85rem;font-weight:600;" class="mb-2 d-block">
                            <i class="fas fa-calendar me-1" style="color:#E91E8C;"></i>New Date
                        </label>
                        <input type="date" name="new_date" id="newDate" class="field-control" required
                               min="{{ now()->format('Y-m-d') }}"
                               value="{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}">
                        <div style="color:#aaa;font-size:0.75rem;margin-top:6px;">
                            <i class="fas fa-info-circle me-1"></i>
                            Please select today or a future date
                        </div>
                    </div>

                    {{-- Time Picker --}}
                    <div class="col-md-6">
                        <label style="color:#555;font-size:0.85rem;font-weight:600;" class="mb-2 d-block">
                            <i class="fas fa-clock me-1" style="color:#E91E8C;"></i>New Time
                        </label>
                        <input type="time" name="new_time" id="newTime" class="field-control" required
                               value="{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}">
                    </div>
                </div>

                {{-- Reason --}}
                <div class="mt-4">
                    <label style="color:#555;font-size:0.85rem;font-weight:600;" class="mb-2 d-block">
                        Reason for Rescheduling (optional)
                    </label>
                    <textarea name="reschedule_reason" rows="3" class="field-control"
                              placeholder="e.g. Schedule conflict, personal emergency..."
                              style="resize:none;"></textarea>
                </div>

                {{-- Notice --}}
                <div class="p-3 rounded-3 mt-4" style="background:rgba(255,193,7,0.06);border:1px solid rgba(255,193,7,0.2);">
                    <div class="d-flex gap-2">
                        <i class="fas fa-exclamation-triangle" style="color:#ffc107;margin-top:2px;flex-shrink:0;"></i>
                        <div style="color:#555;font-size:0.82rem;line-height:1.6;">
                            <strong>Please Note:</strong> Your existing advance payment of
                            <strong>Rs. {{ $appointment->advance_amount }}</strong> will remain valid for the new date.
                            Your appointment will be rescheduled immediately once confirmed.
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="submit" id="rescheduleBtn" class="btn rounded-pill px-5 py-2 fw-semibold"
                            style="background:linear-gradient(135deg,#E91E8C,#c2185b);color:#fff;border:none;font-size:0.95rem;">
                        <i class="fas fa-calendar-check me-2"></i>Confirm Reschedule
                    </button>
                    <a href="{{ route('client.appointments.show', $appointment->id) }}"
                       class="btn btn-outline-secondary rounded-pill px-4">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Sidebar Info --}}
    <div class="col-lg-4">

        {{-- Stylist Card --}}
        <div class="info-card mb-4">
            <h6 class="fw-bold mb-3" style="color:#333;font-size:0.9rem;">
                <i class="fas fa-user-circle me-2" style="color:#C9A96E;"></i>Your Stylist
            </h6>
            <div class="text-center">
                <img src="{{ $appointment->stylist->avatar_url }}" class="rounded-circle mb-2"
                     width="70" height="70"
                     style="object-fit:cover;border:3px solid #fce4ec;"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($appointment->stylist->name) }}&background=E91E8C&color=fff'">
                <h6 class="fw-bold mb-0" style="color:#333;">{{ $appointment->stylist->name }}</h6>
                <div class="d-flex align-items-center justify-content-center gap-1 mt-1">
                    <i class="fas fa-star text-warning" style="font-size:0.78rem;"></i>
                    <span style="color:#555;font-size:0.82rem;font-weight:600;">{{ number_format($appointment->stylist->rating,1) }}</span>
                </div>
                @if($appointment->stylist->specializations)
                <p style="color:#aaa;font-size:0.78rem;margin-top:6px;margin-bottom:0;">
                    {{ Str::limit($appointment->stylist->specializations, 60) }}
                </p>
                @endif
            </div>
        </div>

        {{-- Rescheduling Policy --}}
        <div class="info-card">
            <h6 class="fw-bold mb-3" style="color:#333;font-size:0.9rem;">
                <i class="fas fa-info-circle me-2" style="color:#E91E8C;"></i>Rescheduling Policy
            </h6>
            @foreach([
                ['Reschedule anytime before your appointment', 'fa-check', '#22c55e'],
                ['Advance payment stays valid for the new date', 'fa-exchange-alt', '#E91E8C'],
                ['Same stylist will be assigned', 'fa-user-circle', '#C9A96E'],
                ['Change is applied immediately, no waiting', 'fa-bolt', '#c2185b'],
            ] as [$text, $icon, $color])
            <div class="d-flex align-items-start gap-2 mb-2">
                <i class="fas {{ $icon }}" style="color:{{ $color }};font-size:0.78rem;margin-top:3px;flex-shrink:0;"></i>
                <span style="color:#555;font-size:0.8rem;line-height:1.5;">{{ $text }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection