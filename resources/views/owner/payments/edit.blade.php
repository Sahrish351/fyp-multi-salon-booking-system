@extends('layouts.owner')
 
@section('title', 'Edit Payment')
 
@section('content')
 
    @php
        $paymentCode = 'PAY-' . str_pad($payment->id, 3, '0', STR_PAD_LEFT);
 
        // Values controller ki validation se match karti hain
        $methods = [
            'easypaisa'   => 'Easypaisa',
            'jazzcash'    => 'JazzCash',
            'bank'        => 'Bank Transfer',
            'cash'        => 'Cash',
            'credit_card' => 'Credit Card',
            'debit_card'  => 'Debit Card',
            'online'      => 'Online',
            'other'       => 'Other',
        ];
 
        $statuses = [
            'pending'  => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ];
 
        $currentMethod = old('method', $payment->method);
        $currentStatus = old('status', $payment->status);
 
        $quickReasons = [
            'Blurry screenshot'      => 'Your payment screenshot is blurry or not clear. Please upload a clear screenshot of the payment.',
            'Transaction ID missing' => 'Transaction ID / reference number is not visible in the screenshot. Please upload a full screenshot.',
            'Amount mismatch'        => 'The amount in the screenshot does not match the required advance amount. Please pay the correct amount.',
            'Payment not received'   => 'We could not find this payment in our account. Please check the details and submit again.',
        ];
    @endphp
 
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
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
            <h2>Edit Payment</h2>
            <p>Update details for "{{ $paymentCode }}"</p>
        </div>
        <a href="{{ route('owner.payments.show', ['payment' => $payment->id]) }}" class="btn btn-back">
            <i class="bi bi-arrow-left me-2"></i> Back to Payment
        </a>
    </div>
 
    <form action="{{ route('owner.payments.update', ['payment' => $payment->id]) }}" method="POST">
        @csrf
        @method('PUT')
 
        <div class="row g-4">
 
            {{-- ===== LEFT: Client & Service (sirf dekhne ke liye) ===== --}}
            <div class="col-lg-6">
                <div class="panel-card">
                    <div class="panel-title">Client &amp; Service</div>
 
                    <div class="info-list">
                        <div class="info-row">
                            <span class="info-key">Payment ID</span>
                            <span class="info-val">{{ $paymentCode }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Client</span>
                            <span class="info-val">{{ $payment->appointment->client->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Email</span>
                            <span class="info-val">{{ $payment->appointment->client->email ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Service</span>
                            <span class="info-val">{{ $payment->appointment->service->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Transaction Ref</span>
                            <span class="info-val info-mono">{{ $payment->transaction_ref ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Sender Number</span>
                            <span class="info-val">{{ $payment->sender_number ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Submitted</span>
                            <span class="info-val">{{ $payment->created_at ? $payment->created_at->format('M d, Y h:i A') : 'N/A' }}</span>
                        </div>
                    </div>
 
                    @if($payment->screenshot)
                        <a href="{{ asset('storage/' . $payment->screenshot) }}" target="_blank" class="btn btn-view-shot mt-3">
                            <i class="bi bi-image-fill me-2"></i> View Screenshot
                        </a>
                    @endif
 
                    <p class="form-hint mt-3 mb-0">
                        Client and service details come from the booking, so they can't be changed here.
                    </p>
                </div>
            </div>
 
            {{-- ===== RIGHT: Editable Payment Details ===== --}}
            <div class="col-lg-6">
                <div class="panel-card">
                    <div class="panel-title">Payment Details</div>
 
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label-custom">Amount (PKR)</label>
                            <input type="number" name="amount" class="form-control input-custom"
                                   value="{{ old('amount', $payment->amount) }}" min="0" step="0.01" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label-custom">Method</label>
                            <select name="method" class="form-select input-custom" required>
                                @if($currentMethod && !array_key_exists($currentMethod, $methods))
                                    <option value="{{ $currentMethod }}" selected>{{ ucfirst(str_replace('_', ' ', $currentMethod)) }}</option>
                                @endif
                                @foreach ($methods as $value => $label)
                                    <option value="{{ $value }}" {{ $currentMethod === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Status</label>
                            <select name="status" id="statusSelect" class="form-select input-custom" required>
                                @foreach ($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ $currentStatus === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <p class="form-hint mb-0 mt-1">
                                Changing the status to Approved or Rejected will notify the client.
                            </p>
                        </div>
 
                        {{-- Reason: sirf tab dikhta hai jab status Rejected ho --}}
                        <div class="col-12" id="reasonWrap" style="display:none;">
                            <label class="form-label-custom">
                                Reason for Rejection <span style="color:#E85588;">*</span>
                            </label>
 
                            <div class="d-flex flex-wrap gap-2 mb-2">
                                @foreach ($quickReasons as $label => $text)
                                    <button type="button" class="quick-reason-btn"
                                            data-reason="{{ $text }}">{{ $label }}</button>
                                @endforeach
                            </div>
 
                            <textarea name="rejection_reason" id="rejectionReason" rows="3" maxlength="255"
                                      class="form-control input-custom"
                                      placeholder="Choose a quick reason above or write your own...">{{ old('rejection_reason', $payment->rejection_reason) }}</textarea>
                            <p class="form-hint mb-0 mt-1">The client will see this reason.</p>
                        </div>
                    </div>
                </div>
            </div>
 
            <div class="col-12">
                <div class="panel-card">
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-save-changes">
                            <i class="bi bi-check-circle-fill me-2"></i> Save Changes
                        </button>
                        <a href="{{ route('owner.payments.show', ['payment' => $payment->id]) }}" class="btn btn-cancel-modal">Cancel</a>
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
 
    .alert { border-radius: 12px; border: none; padding: 10px 16px; margin-bottom: 16px; }
    .alert-danger { background: #FCE4EC; color: #880E4F; }
    .alert ul { padding-left: 1.2rem; }
 

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
 
  
    .info-list { display: flex; flex-direction: column; gap: 8px; }
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 9px 14px;
        background: #fcf6f9;
        border-radius: 10px;
    }
    .info-key { font-size: 13px; color: #8a7a88; font-weight: 600; }
    .info-val { font-size: 14px; font-weight: 600; color: #2d1f2c; text-align: right; word-break: break-word; }
    .info-mono { font-family: monospace; font-size: 13px; }
 
    .btn-view-shot {
        background: #FFF0F6;
        color: #C0547A;
        border: 1px solid #F0C0D8;
        font-weight: 600;
        padding: 9px 18px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        transition: all 0.18s ease;
    }
    .btn-view-shot:hover { background: #FFE0EE; color: #A03060; }
 
    .form-hint { font-size: 12.5px; color: #8a7a88; }
 
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
 
    .quick-reason-btn {
        background: #FFF0F6;
        color: #C0547A;
        border: 1px solid #F0C0D8;
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .quick-reason-btn:hover { background: #E85588; color: #fff; border-color: #E85588; }
 
  
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
        .info-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 2px;
        }
        .info-val { text-align: left; }
    }
</style>
@endsection
 
@section('extra-js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusSelect = document.getElementById('statusSelect');
        const reasonWrap   = document.getElementById('reasonWrap');
        const reasonInput  = document.getElementById('rejectionReason');
 
        function toggleReason() {
            const isRejected = statusSelect.value === 'rejected';
            reasonWrap.style.display = isRejected ? 'block' : 'none';
            reasonInput.required = isRejected;
        }
 
        statusSelect.addEventListener('change', toggleReason);
        toggleReason();
 
        document.querySelectorAll('.quick-reason-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                reasonInput.value = this.dataset.reason;
            });
        });
    });
</script>
@endsection
 