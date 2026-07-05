@extends('layouts.owner')

@section('title', 'Edit Appointment')

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
            <h2>Edit Appointment</h2>
            <p>Update details for "{{ $appointment['client_name'] }}"</p>
        </div>
        <a href="{{ route('owner.appointments.index') }}" class="btn btn-back">
            <i class="bi bi-arrow-left me-2"></i> Back to Appointments
        </a>
    </div>

    <form action="{{ route('owner.appointments.update', ['appointment' => $appointment['id']]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">

            <!-- Client Information -->
            <div class="col-lg-6">
                <div class="panel-card">
                    <div class="panel-title">
                        <i class="bi bi-person-circle me-2"></i> Client Information
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label-custom">Select Client <span class="text-danger">*</span></label>
                            <select name="client_id" class="form-select input-custom" required>
                                <option value="">Select client</option>
                                @foreach ($clients ?? [] as $id => $name)
                                    <option value="{{ $id }}" {{ old('client_id', $appointment['client_id']) == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Notes <span class="text-muted">(optional)</span></label>
                            <textarea name="notes" class="form-control input-custom" rows="3" placeholder="Any special requests...">{{ old('notes', $appointment['notes']) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appointment Details -->
            <div class="col-lg-6">
                <div class="panel-card">
                    <div class="panel-title">
                        <i class="bi bi-calendar-event me-2"></i> Appointment Details
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label-custom">Service <span class="text-danger">*</span></label>
                            <select name="service_id" class="form-select input-custom" required>
                                <option value="">Select service</option>
                                @foreach ($services ?? [] as $id => $name)
                                    <option value="{{ $id }}" {{ old('service_id', $appointment['service_id']) == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Stylist <span class="text-danger">*</span></label>
                            <select name="stylist_id" class="form-select input-custom" required>
                                <option value="">Select stylist</option>
                                @foreach ($stylists ?? [] as $id => $name)
                                    <option value="{{ $id }}" {{ old('stylist_id', $appointment['stylist_id']) == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label-custom">Date <span class="text-danger">*</span></label>
                            <input type="date" name="appointment_date" class="form-control input-custom"
                                   value="{{ old('appointment_date', $appointment['date_raw']) }}" required>
                        </div>
                        <div class="col-3">
                            <label class="form-label-custom">Start Time <span class="text-danger">*</span></label>
                            <input type="time" name="start_time" class="form-control input-custom"
                                   value="{{ old('start_time', $appointment['start_time_raw']) }}" required>
                        </div>
                        <div class="col-3">
                            <label class="form-label-custom">End Time <span class="text-danger">*</span></label>
                            <input type="time" name="end_time" class="form-control input-custom"
                                   value="{{ old('end_time', $appointment['end_time_raw']) }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment & Status -->
            <div class="col-12">
                <div class="panel-card">
                    <div class="panel-title">
                        <i class="bi bi-credit-card me-2"></i> Payment &amp; Status
                    </div>

                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label-custom">Total Amount (PKR) <span class="text-danger">*</span></label>
                            <input type="number" name="total_amount" class="form-control input-custom"
                                   value="{{ old('total_amount', $appointment['price']) }}" min="0" step="0.01" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">Advance Amount (PKR)</label>
                            <input type="number" name="advance_amount" class="form-control input-custom"
                                   value="{{ old('advance_amount', $appointment['advance_amount'] ?? 0) }}" min="0" step="0.01">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">Payment Status</label>
                            <select name="payment_status" class="form-select input-custom">
                                <option value="pending" {{ old('payment_status', $appointment['payment_status'] ?? 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ old('payment_status', $appointment['payment_status'] ?? 'pending') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ old('payment_status', $appointment['payment_status'] ?? 'pending') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">Payment Method</label>
                            <select name="payment_method" class="form-select input-custom">
                                <option value="cash" {{ old('payment_method', $appointment['payment_method'] ?? 'cash') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="credit_card" {{ old('payment_method', $appointment['payment_method'] ?? 'cash') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                <option value="debit_card" {{ old('payment_method', $appointment['payment_method'] ?? 'cash') == 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                                <option value="online" {{ old('payment_method', $appointment['payment_method'] ?? 'cash') == 'online' ? 'selected' : '' }}>Online</option>
                                <option value="other" {{ old('payment_method', $appointment['payment_method'] ?? 'cash') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Appointment Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select input-custom" required>
                                <option value="pending_payment" {{ old('status', $appointment['status']) == 'pending_payment' ? 'selected' : '' }}>Pending Payment</option>
                                <option value="confirmed" {{ old('status', $appointment['status']) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="in_progress" {{ old('status', $appointment['status']) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status', $appointment['status']) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status', $appointment['status']) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="col-12">
                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-save-changes">
                        <i class="bi bi-check-circle-fill me-2"></i> Save Changes
                    </button>
                    <a href="{{ route('owner.appointments.index') }}" class="btn btn-cancel-modal">Cancel</a>
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
        color: #b0a5ae;
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
        display: flex;
        align-items: center;
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
        // AUTO-DISMISS ALERTS
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