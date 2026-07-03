{{-- ============================================================ --}}
{{-- FILE: resources/views/client/appointments/index.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.client')
@section('title', 'My Appointments — Glamora')

@push('styles')
<style>
    /* ---- Card ---- */
    .card-appointment {
        background: #ffffff;
        border: 1px solid #fce4ec;
        border-radius: 20px;
        transition: all .3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        height: 100%;
    }
    .card-appointment:hover {
        border-color: #E91E8C;
        box-shadow: 0 8px 25px rgba(233,30,140,0.08);
        transform: translateY(-3px);
    }
    .card-appointment .date-box {
        background: linear-gradient(135deg, #E91E8C, #c2185b);
        color: #fff;
        border-radius: 14px;
        padding: 12px 10px;
        text-align: center;
        min-width: 58px;
        flex-shrink: 0;
    }
    .card-appointment .date-box .day {
        font-size: 1.3rem;
        font-weight: 800;
        line-height: 1;
    }
    .card-appointment .date-box .month {
        font-size: .6rem;
        text-transform: uppercase;
        letter-spacing: .5px;
        opacity: .85;
    }
    .card-appointment .date-box .year {
        font-size: .55rem;
        opacity: .7;
    }

    .card-appointment .appt-info h5 {
        font-size: .95rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 2px;
    }
    .card-appointment .appt-info .meta {
        font-size: .78rem;
        color: #888;
        display: flex;
        flex-wrap: wrap;
        gap: 4px 14px;
    }
    .card-appointment .appt-info .meta i {
        color: #E91E8C;
        width: 16px;
    }
    .card-appointment .appt-info .amount {
        font-size: .9rem;
        font-weight: 700;
        color: #E91E8C;
        margin-top: 4px;
    }

    .card-appointment .status-section {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 4px;
    }

    /* ---- Status Badges ---- */
    .status-badge {
        padding: 4px 14px;
        border-radius: 50px;
        font-size: .7rem;
        font-weight: 700;
        text-transform: capitalize;
        display: inline-block;
    }
    .status-pending_payment { background: #fef3c7; color: #d97706; }
    .status-payment_submitted { background: #fce4ec; color: #E91E8C; }
    .status-confirmed { background: #d1fae5; color: #059669; }
    .status-completed { background: #e0e7ff; color: #4f46e5; }
    .status-cancelled { background: #fee2e2; color: #dc2626; }

    .payment-status {
        font-size: .65rem;
        font-weight: 600;
        padding: 2px 12px;
        border-radius: 50px;
        display: inline-block;
    }
    .payment-approved { background: #d1fae5; color: #059669; }
    .payment-pending { background: #fef3c7; color: #d97706; }
    .payment-rejected { background: #fee2e2; color: #dc2626; }
    .payment-na { background: #f3f4f6; color: #6b7280; }

    /* ---- Action Buttons (Logical Sequence) ---- */
    .btn-action {
        padding: 5px 14px;
        border-radius: 50px;
        font-size: .7rem;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all .2s;
        border: 1.5px solid transparent;
        background: transparent;
    }

    /* View — neutral/slate (VS Code vibe) */
    .btn-view {
        border-color: #e5e7eb;
        color: #4b5563;
    }
    .btn-view:hover {
        background: #f3f4f6;
        border-color: #9ca3af;
        color: #1f2937;
    }

    /* Reschedule — nice blue */
    .btn-reschedule {
        border-color: #93c5fd;
        color: #2563eb;
    }
    .btn-reschedule:hover {
        background: #eff6ff;
        border-color: #60a5fa;
        color: #1d4ed8;
    }

    /* Cancel — pure red */
    .btn-cancel {
        border-color: #fecaca;
        color: #dc2626;
    }
    .btn-cancel:hover {
        background: #fee2e2;
        border-color: #f87171;
    }

    /* Review — gold/yellow */
    .btn-review {
        border-color: #fcd34d;
        color: #d97706;
    }
    .btn-review:hover {
        background: #fef3c7;
        border-color: #f59e0b;
    }

    /* ---- Empty State ---- */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: #fff;
        border-radius: 20px;
        border: 2px dashed #fce4ec;
    }

    /* ---- Cancel Modal ---- */
    #cancelModal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        z-index: 999;
        align-items: center;
        justify-content: center;
    }
    #cancelModal.show { display: flex; }
    .modal-box {
        background: #fff;
        border-radius: 20px;
        padding: 30px;
        max-width: 400px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }
    .modal-box h3 { font-size: 1.1rem; font-weight: 700; color: #1a1a1a; margin-bottom: 8px; }
    .modal-box p { color: #888; font-size: .85rem; margin-bottom: 16px; }
    .modal-box textarea { width: 100%; border: 1.5px solid #e5daf0; border-radius: 12px; padding: 10px 14px; font-size: .85rem; font-family: 'Inter', sans-serif; }
    .modal-box .btn-row { display: flex; gap: 10px; justify-content: flex-end; margin-top: 16px; }
    .modal-box .btn-keep { padding: 8px 20px; border: 1.5px solid #e5daf0; border-radius: 50px; background: transparent; font-weight: 700; font-size: .8rem; color: #555; cursor: pointer; }
    .modal-box .btn-keep:hover { background: #f5f0fc; }
    .modal-box .btn-confirm-cancel { padding: 8px 20px; border: none; border-radius: 50px; background: #dc2626; color: #fff; font-weight: 700; font-size: .8rem; cursor: pointer; }
    .modal-box .btn-confirm-cancel:hover { background: #b91c1c; }

    /* ---- Responsive ---- */
    @media (max-width: 768px) {
        .card-appointment .status-section {
            align-items: flex-start;
            margin-top: 8px;
        }
        .card-appointment .actions-wrap {
            justify-content: flex-start !important;
        }
    }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1" style="color:#333;font-family:'Playfair Display',serif;">
            <i class="fas fa-calendar-check me-2" style="color:#E91E8C;"></i>My Appointments
        </h4>
        <p style="color:#aaa;font-size:0.85rem;margin:0;">
            All your salon bookings in one place
        </p>
    </div>
    <a href="{{ route('salons.index') }}" class="btn btn-sm rounded-pill px-4"
       style="background:linear-gradient(135deg,#E91E8C,#c2185b);color:#fff;border:none;font-weight:600;">
        <i class="fas fa-plus me-1"></i>New Booking
    </a>
</div>

{{-- Tabs --}}
<div class="d-flex gap-2 mb-4 flex-wrap">
    @php
        $tabs = ['all'=>'All', 'confirmed'=>'Confirmed', 'pending_payment'=>'Pending', 'completed'=>'Completed', 'cancelled'=>'Cancelled'];
        $cur = request('status', 'all');
    @endphp
    @foreach($tabs as $val => $lbl)
        <a href="{{ route('client.appointments.index', ['status' => $val]) }}"
           class="btn btn-sm rounded-pill"
           style="{{ $cur === $val ? 'background:#E91E8C;color:#fff;border:none;font-weight:600;' : 'background:#fff;color:#888;border:1px solid #fce4ec;' }}font-size:0.82rem;padding:6px 16px;">
            {{ $lbl }}
        </a>
    @endforeach
</div>

{{-- Cards --}}
<div class="row g-4">
    @forelse($appointments as $appt)
    <div class="col-xl-6">
        <div class="card-appointment">
            <div class="row g-3 align-items-start">

                {{-- Date Box --}}
                <div class="col-auto">
                    <div class="date-box">
                        <div class="day">{{ $appt->appointment_date->format('d') }}</div>
                        <div class="month">{{ $appt->appointment_date->format('M') }}</div>
                        <div class="year">{{ $appt->appointment_date->format('Y') }}</div>
                    </div>
                </div>

                {{-- Info --}}
                <div class="col-md-5">
                    <div class="appt-info">
                        <h5>{{ $appt->salon->name }}</h5>
                        <div class="meta">
                            <span><i class="fas fa-spa"></i> {{ Str::limit($appt->service->name, 18) }}</span>
                            <span><i class="fas fa-user"></i> {{ $appt->stylist->name }}</span>
                            <span><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($appt->start_time)->format('h:i A') }}</span>
                        </div>
                        <div class="amount">Rs. {{ number_format($appt->total_amount) }}</div>
                    </div>
                </div>

                {{-- Status + Actions --}}
                <div class="col-md-4 ms-auto">
                    <div class="status-section">
                        {{-- Appointment Status --}}
                        @php
                            $statusMap = [
                                'pending_payment' => ['class' => 'status-pending_payment', 'label' => 'Pending Payment'],
                                'payment_submitted' => ['class' => 'status-payment_submitted', 'label' => 'Payment Submitted'],
                                'confirmed' => ['class' => 'status-confirmed', 'label' => 'Confirmed'],
                                'completed' => ['class' => 'status-completed', 'label' => 'Completed'],
                                'cancelled' => ['class' => 'status-cancelled', 'label' => 'Cancelled'],
                            ];
                            $st = $statusMap[$appt->status] ?? ['class' => 'status-pending_payment', 'label' => ucfirst($appt->status)];
                        @endphp
                        <span class="status-badge {{ $st['class'] }}">{{ $st['label'] }}</span>

                        {{-- Payment Status --}}
                        @if($appt->payment)
                            @php
                                $pay = $appt->payment->status;
                                $payClass = $pay === 'approved' ? 'payment-approved' : ($pay === 'pending' ? 'payment-pending' : 'payment-rejected');
                                $payLabel = $pay === 'approved' ? 'Approved' : ($pay === 'pending' ? 'Pending' : 'Rejected');
                            @endphp
                            <span class="payment-status {{ $payClass }}">Payment: {{ $payLabel }}</span>
                        @else
                            <span class="payment-status payment-na">Payment: N/A</span>
                        @endif

                        {{-- Actions (Logical Sequence: View → Reschedule → Cancel → Review) --}}
                        <div class="d-flex gap-2 justify-content-end flex-wrap mt-2 actions-wrap">
                            {{-- 1. View (always) --}}
                            <a href="{{ route('client.appointments.show', $appt->id) }}" class="btn-action btn-view">
                                <i class="fas fa-eye"></i> View
                            </a>

                            {{-- 2. Reschedule (if not cancelled/completed) --}}
                            @if(!in_array($appt->status, ['cancelled', 'completed']))
                            <a href="{{ route('client.appointments.reschedule', $appt->id) }}" class="btn-action btn-reschedule">
                                <i class="fas fa-calendar-alt"></i> Reschedule
                            </a>
                            @endif

                            {{-- 3. Cancel (if not cancelled/completed) --}}
                            @if(!in_array($appt->status, ['cancelled', 'completed']))
                            <button class="btn-action btn-cancel" onclick="cancelModal({{ $appt->id }})">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                            @endif

                            {{-- 4. Review (if completed and not reviewed) --}}
                            @if($appt->status === 'completed' && !$appt->review)
                            <a href="{{ route('client.reviews.create', $appt->id) }}" class="btn-action btn-review">
                                <i class="fas fa-star"></i> Review
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="empty-state">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                 style="width:100px;height:100px;background:rgba(233,30,140,0.08);">
                <i class="fas fa-calendar-times fa-3x" style="color:rgba(233,30,140,0.3);"></i>
            </div>
            <h5 class="fw-bold mb-2" style="color:#333;">No appointments yet</h5>
            <p style="color:#aaa;max-width:350px;margin:0 auto 1.5rem;">
                Start your beauty journey by booking your first appointment.
            </p>
            <a href="{{ route('salons.index') }}" class="btn rounded-pill px-5 py-2 fw-semibold"
               style="background:linear-gradient(135deg,#E91E8C,#c2185b);color:#fff;border:none;font-size:0.95rem;">
                <i class="fas fa-search me-2"></i>Find a Salon
            </a>
        </div>
    </div>
    @endforelse
</div>

@if($appointments->hasPages())
<div class="mt-4">{{ $appointments->links() }}</div>
@endif

{{-- Cancel Modal --}}
<div id="cancelModal" class="modal-box-wrapper">
    <div class="modal-box">
        <h3>Cancel Appointment</h3>
        <p>Are you sure you want to cancel this appointment? This action cannot be undone.</p>
        <form id="cancelForm" method="POST">
            @csrf
            <textarea name="cancellation_reason" rows="3" placeholder="Please tell us why you're cancelling..." required></textarea>
            <div class="btn-row">
                <button type="button" class="btn-keep" onclick="closeCancelModal()">Keep Appointment</button>
                <button type="submit" class="btn-confirm-cancel">Cancel Appointment</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function cancelModal(id) {
        document.getElementById('cancelForm').action = `/client/appointments/${id}/cancel`;
        document.getElementById('cancelModal').classList.add('show');
    }
    function closeCancelModal() {
        document.getElementById('cancelModal').classList.remove('show');
    }
    document.getElementById('cancelModal').addEventListener('click', function(e) {
        if (e.target === this) closeCancelModal();
    });
</script>
@endpush