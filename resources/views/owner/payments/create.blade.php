@extends('layouts.owner')
 
@section('title', 'Record Payment')
 
@section('content')
 
    
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>Record Payment</h2>
            <p>Manually record a payment transaction</p>
        </div>
        <a href="{{ route('owner.payments.index') }}" class="btn btn-back">
            <i class="bi bi-arrow-left me-2"></i> Back to Payments
        </a>
    </div>
 
    <form action="{{ route('owner.payments.store') }}" method="POST">
        @csrf
 
        <div class="row g-4">
 
            <div class="col-lg-6">
                <div class="panel-card">
                    <div class="panel-title">Client &amp; Service</div>
 
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label-custom">Client Name</label>
                            <input type="text" name="client_name" class="form-control input-custom"
                                   placeholder="e.g. Sarah Johnson" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Email</label>
                            <input type="email" name="client_email" class="form-control input-custom"
                                   placeholder="sarah.j@email.com" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Service</label>
                            <select name="service" class="form-select input-custom" required>
                                <option value="">Select service</option>
                                @foreach ($services ?? [] as $service)
                                    <option>{{ $service }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
 
            <div class="col-lg-6">
                <div class="panel-card">
                    <div class="panel-title">Payment Details</div>
 
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label-custom">Amount ($)</label>
                            <input type="number" name="amount" class="form-control input-custom"
                                   placeholder="120" min="0" step="0.01" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label-custom">Method</label>
                            <select name="method" class="form-select input-custom" required>
                                <option value="Credit Card">Credit Card</option>
                                <option value="Debit Card">Debit Card</option>
                                <option value="Cash">Cash</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label-custom">Date</label>
                            <input type="date" name="date" class="form-control input-custom" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label-custom">Time</label>
                            <input type="time" name="time" class="form-control input-custom" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Status</label>
                            <select name="status" class="form-select input-custom">
                                <option value="Completed" selected>Completed</option>
                                <option value="Pending">Pending</option>
                                <option value="Refunded">Refunded</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
 
            <div class="col-12">
                <div class="panel-card">
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-save-changes">
                            <i class="bi bi-check-circle-fill me-2"></i> Record Payment
                        </button>
                        <a href="{{ route('owner.payments.index') }}" class="btn btn-cancel-modal">Cancel</a>
                    </div>
                </div>
            </div>
 
        </div>
 
    </form>
 
@endsection
 
@section('extra-css')
<style>
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

    /* ===== BACK BUTTON ===== */
    .btn-back {
        background: #fff;
        border: 1px solid #f0e8ed;
        color: #2d1f2c;
        font-weight: 600;
        font-size: 14.5px;
        padding: 10px 20px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        transition: all 0.18s ease;
        text-decoration: none;
    }
    .btn-back:hover {
        background: #fcf6f9;
        border-color: #E85588;
        color: #E85588;
    }
 
    /* ===== FORM ===== */
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

    .form-label-custom {
        display: block;
        font-size: 13.5px;
        font-weight: 600;
        color: #4a3a48;
        margin-bottom: 6px;
    }
    .input-custom {
        background: #fcf6f9 !important;
        border: 1px solid #f0e8ed !important;
        border-radius: 10px !important;
        color: #2d1f2c !important;
        font-size: 14.5px;
        padding: 11px 14px !important;
        width: 100%;
    }
    .input-custom:focus {
        background: #fff !important;
        border-color: #E85588 !important;
        box-shadow: 0 0 0 3px rgba(232, 85, 136, 0.15) !important;
        outline: none;
    }
    .input-custom[type="file"] {
        padding: 8px 12px !important;
    }

    /* ============================================================
       RECORD PAYMENT BUTTON - PINK
       ============================================================ */
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
        text-decoration: none;
    }
    .btn-save-changes:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(232, 85, 136, 0.45);
        color: #ffffff !important;
    }

    /* ============================================================
       CANCEL BUTTON - PINK OUTLINE
       ============================================================ */
    .btn-cancel-modal {
        background: #fff;
        border: 1.5px solid #FF6B9D;
        color: #E85588;
        font-weight: 600;
        padding: 11px 26px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        transition: all 0.18s ease;
        text-decoration: none;
    }
    .btn-cancel-modal:hover {
        background: #E85588;
        color: #ffffff !important;
        border-color: #E85588;
    }

    /* ============================================================
       RESPONSIVE
       ============================================================ */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch !important;
        }
        .btn-back {
            justify-content: center;
            width: 100%;
        }
        .d-flex.gap-3 {
            flex-wrap: wrap;
        }
        .btn-save-changes,
        .btn-cancel-modal {
            flex: 1;
            justify-content: center;
        }
    }
</style>
@endsection