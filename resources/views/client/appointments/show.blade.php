<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Appointment {{ $appointment->booking_ref }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.1/sweetalert2.min.css">
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: 'Inter', sans-serif;
        background: #fdf2f8;
        min-height: 100vh;
        display: flex; flex-direction: column; align-items: center;
        padding: 0 16px 60px;
        color: #1a1a1a;
    }
    .top-bar { width: 100%; max-width: 560px; display: flex; align-items: center; justify-content: space-between; padding: 18px 0 24px; }
    .back-btn { width: 40px; height: 40px; border-radius: 50%; background: #fff; border: 1.5px solid #fce4ec; display: flex; align-items: center; justify-content: center; color: #555; text-decoration: none; font-size: .9rem; transition: all .15s; }
    .back-btn:hover { border-color: #E91E8C; color: #E91E8C; }
    .brand { font-size: 1.1rem; font-weight: 900; font-style: italic; background: linear-gradient(135deg, #E91E8C, #c2185b); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }

    .card { width: 100%; max-width: 560px; background: #fff; border-radius: 24px; padding: 28px 26px; box-shadow: 0 4px 40px rgba(233,30,140,0.08); margin-bottom: 20px; }

    .status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 50px; font-size: .74rem; font-weight: 800; text-transform: uppercase; letter-spacing: .4px; margin-bottom: 18px; }
    .status-pending_payment { background: #fef3c7; color: #b45309; }
    .status-confirmed       { background: #dcfce7; color: #15803d; }
    .status-completed       { background: #dbeafe; color: #1d4ed8; }
    .status-cancelled       { background: #fee2e2; color: #b91c1c; }

    .ref { font-size: .78rem; color: #999; margin-bottom: 4px; }
    .ref b { color: #1a1a1a; font-family: monospace; letter-spacing: .5px; }

    h1 { font-size: 1.3rem; font-weight: 900; margin-bottom: 6px; }
    .sub { font-size: .84rem; color: #888; margin-bottom: 22px; }

    .row { display: flex; align-items: flex-start; gap: 12px; padding: 12px 0; border-bottom: 1px solid #fdeef5; }
    .row:last-child { border-bottom: none; }
    .row i { width: 20px; margin-top: 2px; color: #E91E8C; }
    .row .lbl { font-size: .72rem; color: #999; text-transform: uppercase; letter-spacing: .4px; font-weight: 700; margin-bottom: 3px; }
    .row .val { font-size: .92rem; font-weight: 600; color: #1a1a1a; }
    .row .sub-val { font-size: .78rem; color: #888; margin-top: 2px; }

    .sec-title { font-size: .78rem; font-weight: 800; color: #c2185b; text-transform: uppercase; letter-spacing: .6px; margin: 24px 0 10px; }

    .amount-strip { background: linear-gradient(135deg, #E91E8C, #c2185b); border-radius: 16px; padding: 18px 20px; color: #fff; margin-top: 18px; display: flex; align-items: center; justify-content: space-between; }
    .amount-strip .lbl { font-size: .68rem; color: rgba(255,255,255,.8); text-transform: uppercase; letter-spacing: .5px; font-weight: 700; margin-bottom: 3px; }
    .amount-strip .amt { font-size: 1.5rem; font-weight: 900; }

    .payment-box { background: #fdf5fb; border: 1.5px solid #fce4ec; border-radius: 14px; padding: 14px 16px; margin-top: 14px; }
    .payment-box .p-row { display: flex; justify-content: space-between; font-size: .82rem; padding: 6px 0; }
    .payment-box .p-key { color: #888; }
    .payment-box .p-val { font-weight: 700; color: #1a1a1a; }
    .pstatus { display: inline-block; padding: 3px 10px; border-radius: 50px; font-size: .68rem; font-weight: 800; text-transform: uppercase; }
    .pstatus-pending  { background: #fef3c7; color: #b45309; }
    .pstatus-approved { background: #dcfce7; color: #15803d; }
    .pstatus-rejected { background: #fee2e2; color: #b91c1c; }

    .screenshot-link { display: inline-flex; align-items: center; gap: 6px; margin-top: 8px; font-size: .78rem; color: #c2185b; font-weight: 700; text-decoration: none; }
    .screenshot-link:hover { text-decoration: underline; }

    .actions { display: flex; gap: 10px; margin-top: 22px; }
    .btn { flex: 1; padding: 13px; border-radius: 12px; font-size: .86rem; font-weight: 800; text-align: center; cursor: pointer; border: none; font-family: 'Inter', sans-serif; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .btn-cancel { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; }
    .btn-cancel:hover { opacity: .92; }
    .btn-reschedule { background: #fff0f7; border: 1.5px solid #f7c9de; color: #c2185b; }
    .btn-reschedule:hover { background: #fce4ec; }
    .btn-review { background: linear-gradient(135deg, #E91E8C, #c2185b); color: #fff; }
    .btn-review:hover { opacity: .92; }

    .note-box { background: #fff7ed; border: 1px solid #fed7aa; border-radius: 12px; padding: 12px 14px; font-size: .78rem; color: #9a3412; margin-top: 18px; line-height: 1.5; }

    /* --- Modals (cute, matches dashboard pink) --- */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(30,10,25,.45); z-index: 999; align-items: center; justify-content: center; padding: 16px; }
    .modal-overlay.active { display: flex; }
    .modal-box { background: #fff; border-radius: 22px; width: 100%; max-width: 420px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,.25); }
    .modal-head { padding: 18px 22px; display:flex; align-items:center; justify-content:space-between; }
    .modal-head.cancel-head { background: linear-gradient(135deg,#fff0f0,#fff); }
    .modal-head.resched-head { background: linear-gradient(135deg,#fff0f7,#fff); }
    .modal-head h3 { font-size: 1.05rem; font-weight: 900; }
    .modal-head.cancel-head h3 { color: #dc2626; }
    .modal-head.resched-head h3 { color: #E91E8C; }
    .modal-close { width:32px;height:32px;border-radius:50%;border:none;background:#f3f3f3;color:#666;cursor:pointer;font-size:.9rem; }
    .modal-body { padding: 18px 22px; }
    .modal-body p.hint { font-size: .82rem; color: #888; margin-bottom: 14px; line-height:1.5; }
    .field-lbl { font-size: .78rem; font-weight: 700; color: #555; margin-bottom: 6px; display:block; }
    .field-input { width: 100%; padding: 11px 14px; border-radius: 10px; border: 2px solid #fce4ec; font-family: 'Inter',sans-serif; font-size: .86rem; margin-bottom: 14px; }
    .field-input:focus { outline: none; border-color: #E91E8C; }
    .modal-foot { padding: 14px 22px 20px; display: flex; gap: 10px; }
    .modal-foot button { flex:1; padding: 12px; border-radius: 10px; font-weight: 800; font-size: .84rem; border: none; cursor: pointer; font-family:'Inter',sans-serif; }
    .btn-keep { background: #f2f2f2; color: #555; }
    .btn-confirm-cancel { background: linear-gradient(135deg,#ef4444,#dc2626); color: #fff; }
    .btn-confirm-resched { background: linear-gradient(135deg,#E91E8C,#c2185b); color: #fff; }
    </style>
</head>
<body>

<div class="top-bar">
    <a href="{{ route('client.appointments.index') }}" class="back-btn"><i class="fas fa-arrow-left"></i></a>
    <span class="brand">glamora</span>
    <span style="width:40px;"></span>
</div>

<div class="card">
    @php
        $statusLabels = [
            'pending_payment' => 'Pending Approval',
            'confirmed'       => 'Confirmed',
            'completed'       => 'Completed',
            'cancelled'       => 'Cancelled',
        ];
        $statusClass = 'status-' . $appointment->status;
    @endphp

    <span class="status-badge {{ $statusClass }}">
        <i class="fas fa-circle" style="font-size:.5rem;"></i>
        {{ $statusLabels[$appointment->status] ?? ucfirst(str_replace('_',' ',$appointment->status)) }}
    </span>

    <div class="ref">Booking Reference: <b>{{ $appointment->booking_ref }}</b></div>
    <h1>{{ $appointment->service->name ?? 'Service' }}</h1>
    <div class="sub">at {{ $appointment->salon->name ?? '—' }}</div>

    @if($appointment->status === 'pending_payment')
    <div class="note-box">
        <i class="fas fa-info-circle"></i>
        Your payment screenshot has been submitted and is awaiting admin approval. You'll be notified once it's confirmed.
    </div>
    @endif

    <div class="sec-title">Appointment Details</div>

    <div class="row">
        <i class="fas fa-calendar"></i>
        <div>
            <div class="lbl">Date</div>
            <div class="val">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, d F Y') }}</div>
        </div>
    </div>

    <div class="row">
        <i class="fas fa-clock"></i>
        <div>
            <div class="lbl">Time</div>
            <div class="val">{{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}</div>
        </div>
    </div>

    <div class="row">
        <i class="fas fa-user"></i>
        <div>
            <div class="lbl">Stylist</div>
            <div class="val">{{ $appointment->stylist->name ?? '—' }}</div>
        </div>
    </div>

    <div class="row">
        <i class="fas fa-map-marker-alt"></i>
        <div>
            <div class="lbl">Salon</div>
            <div class="val">{{ $appointment->salon->name ?? '—' }}</div>
            <div class="sub-val">{{ $appointment->salon->city ?? '' }}</div>
        </div>
    </div>

    @if($appointment->notes)
    <div class="row">
        <i class="fas fa-sticky-note"></i>
        <div>
            <div class="lbl">Notes</div>
            <div class="val">{{ $appointment->notes }}</div>
        </div>
    </div>
    @endif

    @if($appointment->status === 'cancelled' && $appointment->cancellation_reason)
    <div class="row">
        <i class="fas fa-ban"></i>
        <div>
            <div class="lbl">Cancellation Reason</div>
            <div class="val">{{ $appointment->cancellation_reason }}</div>
        </div>
    </div>
    @endif

    <div class="amount-strip">
        <div>
            <div class="lbl">Total Amount</div>
            <div class="amt">Rs. {{ number_format($appointment->total_amount) }}</div>
        </div>
        <div style="text-align:right;">
            <div class="lbl">Advance Paid</div>
            <div class="amt" style="font-size:1.1rem;">Rs. {{ number_format($appointment->advance_amount) }}</div>
        </div>
    </div>

    @if($appointment->payment)
    <div class="sec-title">Payment Details</div>
    <div class="payment-box">
        <div class="p-row">
            <span class="p-key">Method</span>
            <span class="p-val">{{ ucfirst($appointment->payment->method) }}</span>
        </div>
        <div class="p-row">
            <span class="p-key">Transaction Ref</span>
            <span class="p-val">{{ $appointment->payment->transaction_ref }}</span>
        </div>
        <div class="p-row">
            <span class="p-key">Status</span>
            <span class="pstatus pstatus-{{ $appointment->payment->status }}">{{ $appointment->payment->status }}</span>
        </div>
        @if($appointment->payment->screenshot)
        <a href="{{ asset('storage/'.$appointment->payment->screenshot) }}" target="_blank" class="screenshot-link">
            <i class="fas fa-image"></i> View uploaded screenshot
        </a>
        @endif
    </div>
    @endif

    @if(in_array($appointment->status, ['pending_payment', 'confirmed']))
    <div class="actions">
        <button type="button" class="btn btn-reschedule" onclick="openModal('rescheduleOverlay')">
            <i class="fas fa-calendar-alt"></i> Reschedule
        </button>
        <button type="button" class="btn btn-cancel" onclick="openModal('cancelOverlay')">
            <i class="fas fa-times-circle"></i> Cancel Appointment
        </button>
    </div>
    @endif

    @if($appointment->status === 'completed' && !$appointment->review)
    <div class="actions">
        <a href="{{ route('client.reviews.create', $appointment->id) }}" class="btn btn-review">
            <i class="fas fa-star"></i> Leave a Review
        </a>
    </div>
    @endif
</div>

{{-- ================= Cancel Modal ================= --}}
<div class="modal-overlay" id="cancelOverlay">
    <div class="modal-box">
        <div class="modal-head cancel-head">
            <h3><i class="fas fa-heart-crack me-2"></i>Cancel Appointment</h3>
            <button class="modal-close" onclick="closeModal('cancelOverlay')">&times;</button>
        </div>
        <form action="{{ route('client.appointments.cancel', $appointment->id) }}" method="POST">
            @csrf
            <div class="modal-body">
                <p class="hint">Are you sure you want to cancel this appointment? This action cannot be undone.</p>
                <label class="field-lbl">Reason for Cancellation *</label>
                <textarea class="field-input" name="cancellation_reason" rows="3" required placeholder="Please tell us why you're cancelling..."></textarea>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn-keep" onclick="closeModal('cancelOverlay')">Keep Appointment</button>
                <button type="submit" class="btn-confirm-cancel"><i class="fas fa-times-circle"></i> Yes, Cancel It</button>
            </div>
        </form>
    </div>
</div>

{{-- ================= Reschedule Modal ================= --}}
<div class="modal-overlay" id="rescheduleOverlay">
    <div class="modal-box">
        <div class="modal-head resched-head">
            <h3><i class="fas fa-calendar-check me-2"></i>Reschedule Appointment</h3>
            <button class="modal-close" onclick="closeModal('rescheduleOverlay')">&times;</button>
        </div>
        <form action="{{ route('client.appointments.reschedule', $appointment->id) }}" method="POST">
            @csrf
            <div class="modal-body">
                <p class="hint">Pick a new date and time. We'll check the stylist's availability before confirming.</p>
                <label class="field-lbl">New Date *</label>
                <input type="date" class="field-input" name="new_date" required
                       min="{{ now()->addDay()->format('Y-m-d') }}"
                       value="{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}">
                <label class="field-lbl">New Time *</label>
                <input type="time" class="field-input" name="new_time" required
                       value="{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}">
                <label class="field-lbl">Reason (optional)</label>
                <textarea class="field-input" name="reschedule_reason" rows="2" placeholder="Why are you rescheduling? (optional)"></textarea>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn-keep" onclick="closeModal('rescheduleOverlay')">Never Mind</button>
                <button type="submit" class="btn-confirm-resched"><i class="fas fa-check-circle"></i> Confirm New Time</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.1/sweetalert2.all.min.js"></script>
<script>
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }
window.addEventListener('click', function (e) {
    document.querySelectorAll('.modal-overlay.active').forEach(function (ov) {
        if (e.target === ov) ov.classList.remove('active');
    });
});

document.addEventListener('DOMContentLoaded', function () {
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Yay! 💖',
        text: @json(session('success')),
        confirmButtonColor: '#E91E8C',
        confirmButtonText: 'Great!',
        background: '#fff7fb',
        customClass: { popup: 'rounded-4' }
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Oops!',
        text: @json(session('error')),
        confirmButtonColor: '#ef4444',
        background: '#fff7fb',
        customClass: { popup: 'rounded-4' }
    });
    @endif

    @if($errors->any())
    Swal.fire({
        icon: 'warning',
        title: 'Please check',
        text: @json($errors->first()),
        confirmButtonColor: '#c2185b',
        background: '#fff7fb'
    });
    @endif
});
</script>

</body>
</html>