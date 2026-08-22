@extends('layouts.client')
@section('title', 'Reschedule Appointment — Glamora')

@push('styles')
<style>
    :root {
        --pink: #FF6B9D;
        --pink-dark: #E85588;
        --pink-light: #fce4ec;
        --pink-bg: #fdf2f8;
        --purple-soft: #f3f0ff;
        --purple-accent: #6d28d9;
    }

    .reschedule-card {
        background: #ffffff;
        border-radius: 28px;
        box-shadow: 0 15px 35px rgba(255, 107, 157, 0.08);
        border: 1px solid var(--pink-light);
        overflow: hidden;
    }

    .reschedule-header {
        background: #fff5f9;
        color: #333333;
        padding: 28px 32px;
        position: relative;
        border-bottom: 2px solid var(--pink-light);
    }

    .reschedule-header h4 {
        color: #333333;
    }

    .reschedule-header h4 i {
        color: var(--pink);
    }

    .reschedule-header p.opacity-75 {
        color: #888888;
        opacity: 1 !important;
    }

    .info-card {
        background: var(--pink-bg);
        border-radius: 20px;
        padding: 20px 24px;
        border: 1.5px dashed #f7c9de;
    }

    .info-label {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 800;
        color: #888888;
    }

    .info-value {
        font-size: 0.95rem;
        font-weight: 800;
        color: #1a1a1a;
    }

    .form-label-custom {
        font-size: 0.82rem;
        font-weight: 800;
        color: #444444;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-label-custom i {
        color: var(--pink);
    }

    .form-control-custom {
        border: 2px solid var(--pink-light);
        border-radius: 14px;
        padding: 12px 16px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #333;
        transition: all 0.2s ease;
        background-color: #fafafa;
    }

    .form-control-custom:focus {
        border-color: var(--pink);
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(255, 107, 157, 0.12);
        outline: none;
    }

    .btn-submit-reschedule {
        background: linear-gradient(135deg, var(--pink), var(--pink-dark));
        color: #ffffff;
        border: none;
        border-radius: 50px;
        padding: 12px 30px;
        font-size: 0.88rem;
        font-weight: 800;
        box-shadow: 0 8px 20px rgba(255, 107, 157, 0.25);
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-submit-reschedule:hover {
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(255, 107, 157, 0.35);
    }

    .btn-back {
        background: #f2f2f2;
        color: #555555;
        border-radius: 50px;
        padding: 12px 24px;
        font-size: 0.85rem;
        font-weight: 700;
        border: none;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-back:hover {
        background: #e5e5e5;
        color: #222222;
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center py-4">
    <div class="col-lg-8 col-md-10">
        <div class="reschedule-card">
            
            {{-- Header --}}
            <div class="reschedule-header">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-bold mb-1" style="font-family:'Playfair Display', serif;">
                            <i class="fas fa-calendar-alt me-2"></i>Reschedule Booking
                        </h4>
                        <p class="mb-0 opacity-75 fs-6">Select a new suitable date and time slot</p>
                    </div>
                    <span class="badge rounded-pill bg-white text-dark px-3 py-2 fw-bold shadow-sm" style="font-size: 0.75rem; color: var(--pink-dark) !important;">
                        ID: #{{ $appointment->booking_ref ?? $appointment->id }}
                    </span>
                </div>
            </div>

            <div class="p-4 p-md-5">

                {{-- Appointment Summary Card --}}
                <div class="info-card mb-4">
                    <div class="row g-3">
                        <div class="col-md-4 col-6">
                            <span class="info-label">Salon</span>
                            <div class="info-value text-truncate"><i class="fas fa-store text-danger me-1"></i>{{ $appointment->salon->name ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4 col-6">
                            <span class="info-label">Service</span>
                            <div class="info-value text-truncate"><i class="fas fa-spa me-1" style="color:var(--pink);"></i>{{ $appointment->service->name ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4 col-12">
                            <span class="info-label">Stylist</span>
                            <div class="info-value text-truncate"><i class="fas fa-user-check text-primary me-1"></i>{{ $appointment->stylist->name ?? 'N/A' }}</div>
                        </div>
                        <div class="col-12 mt-3 pt-3 border-top">
                            <span class="info-label d-block mb-1">Current Booked Time</span>
                            <div class="badge px-3 py-2" style="background:#fce4ec; color:var(--pink-dark); font-size:0.85rem; font-weight:800;">
                                <i class="fas fa-clock me-1"></i>
                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M, Y') }} at {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Reschedule Form (POST Method Fixed) --}}
                <form action="{{ route('client.appointments.reschedule', $appointment->id) }}" method="POST">
                    @csrf

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label-custom">
                                <i class="fas fa-calendar-day"></i> Select New Date
                            </label>
                            <input type="date" name="new_date" class="form-control form-control-custom" 
                                   min="{{ date('Y-m-d') }}" value="{{ old('new_date', \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">
                                <i class="fas fa-clock"></i> Select New Time
                            </label>
                            <input type="time" name="new_time" class="form-control form-control-custom" 
                                   value="{{ old('new_time', \Carbon\Carbon::parse($appointment->start_time)->format('H:i')) }}" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">
                            <i class="fas fa-comment-dots"></i> Reason for Rescheduling <span class="text-muted fw-normal">(Optional)</span>
                        </label>
                        <textarea name="reschedule_reason" rows="3" class="form-control form-control-custom" 
                                  placeholder="Mention any specific reason or preferred timings...">{{ old('reschedule_reason') }}</textarea>
                    </div>

                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                        <a href="{{ route('client.appointments.index') }}" class="btn-back">
                            <i class="fas fa-arrow-left me-1"></i> Back to Appointments
                        </a>
                        <button type="submit" class="btn-submit-reschedule">
                            <i class="fas fa-check-circle"></i> Confirm New Schedule
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection