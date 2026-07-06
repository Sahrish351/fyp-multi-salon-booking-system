{{-- ============================================================ --}}
{{-- FILE: resources/views/client/appointments/show.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.client')
@section('title', 'Appointment ' . $appointment->booking_ref . ' — Glamora')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.1/sweetalert2.min.css">
<style>
    :root {
        --pink: #E91E8C;
        --pink-dark: #c2185b;
        --pink-light: #fce4ec;
        --pink-bg: #fdf2f8;

        --cancel-a: #ef4444; --cancel-b: #dc2626;
    }

    .back-pill {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 7px 16px; border-radius: 50px; font-size: .8rem; font-weight: 700;
        background: #fff; color: var(--pink-dark); border: 1.5px solid var(--pink-light);
        text-decoration: none; margin-bottom: 16px; transition: background .18s, color .18s;
    }
    .back-pill:hover { background: var(--pink-bg); color: var(--pink-dark); border-color: var(--pink); }

    .appt-hero {
        background: linear-gradient(135deg, var(--pink-bg), #fdeef5);
        border: 1.5px solid var(--pink-light);
        border-radius: 18px;
        padding: 16px 20px;
        color: #333;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .appt-hero .hero-left { display: flex; align-items: center; gap: 12px; }
    .appt-hero .hero-icon {
        width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
        background: var(--pink-light);
        display: flex; align-items: center; justify-content: center;
        color: var(--pink-dark); font-size: 1.05rem;
    }
    .appt-hero .ref { font-size: .7rem; opacity: .85; letter-spacing: .3px; margin-bottom: 2px; color: var(--pink-dark); }
    .appt-hero h2 { font-size: 1.08rem; font-weight: 800; margin-bottom: 1px; color: #1a1a1a; }
    .appt-hero .sub { font-size: .8rem; color: #999; }

    .status-pill {
        padding: 7px 16px;
        border-radius: 50px;
        font-size: .7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .4px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .status-pill.awaiting  { background: var(--pink-light); color: var(--pink-dark); }
    .status-pill.confirmed { background: #ecfdf5; color: #059669; }
    .status-pill.completed { background: #eef6ff; color: #0284c7; }
    .status-pill.cancelled { background: #fff0f0; color: #dc2626; }

    .glam-card {
        background: #fff;
        border: 1px solid var(--pink-light);
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        margin-bottom: 20px;
    }
    .glam-card .card-title {
        font-size: .78rem;
        font-weight: 800;
        color: var(--pink-dark);
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 16px;
        display: flex; align-items: center; gap: 10px;
    }
    .glam-card .card-title .ti-ic {
        width: 28px; height: 28px; border-radius: 9px;
        background: var(--pink-light); color: var(--pink);
        display: flex; align-items: center; justify-content: center; font-size: .8rem;
    }

    .detail-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
    @media (max-width: 640px) { .detail-grid { grid-template-columns: 1fr; } }
    .detail-item { display: flex; align-items: flex-start; gap: 12px; }
    .detail-item .ic {
        width: 40px; height: 40px; border-radius: 11px;
        background: var(--pink-light); color: var(--pink);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; font-size: .95rem;
    }
    .detail-item .lbl { font-size: .68rem; color: #999; text-transform: uppercase; letter-spacing: .4px; font-weight: 700; margin-bottom: 2px; }
    .detail-item .val { font-size: .92rem; font-weight: 700; color: #1a1a1a; }
    .detail-item .val-sub { font-size: .76rem; color: #888; margin-top: 1px; }

    .note-box { background: #fff7ed; border: 1px solid #fed7aa; border-radius: 14px; padding: 14px 18px; font-size: .82rem; color: #9a3412; line-height: 1.6; display: flex; gap: 10px; align-items: flex-start; }
    .note-box i { margin-top: 2px; }

    .amount-box {
        background: var(--pink-bg);
        border: 1.5px solid var(--pink-light);
        border-radius: 16px;
        padding: 18px 20px;
    }
    .amount-row { display: flex; justify-content: space-between; align-items: center; padding: 6px 0; }
    .amount-row .k { font-size: .8rem; color: #888; font-weight: 600; }
    .amount-row .v { font-size: 1.05rem; font-weight: 900; color: var(--pink); }
    .amount-row.total { border-top: 1.5px dashed #f3c9dc; margin-top: 6px; padding-top: 12px; }
    .amount-row.total .v { font-size: 1.2rem; }

    .pstatus { display: inline-flex; align-items: center; gap: 6px; padding: 5px 14px; border-radius: 50px; font-size: .72rem; font-weight: 800; text-transform: uppercase; letter-spacing: .3px; margin-bottom: 14px; }
    .pstatus-pending  { background: #fef3c7; color: #b45309; }
    .pstatus-approved { background: #dcfce7; color: #15803d; }
    .pstatus-rejected { background: #fee2e2; color: #b91c1c; }

    .pay-detail-list { margin-top: 4px; }
    .pay-detail-row { display: flex; justify-content: space-between; align-items: center; padding: 9px 0; border-bottom: 1px dashed #f3e3ec; font-size: .82rem; }
    .pay-detail-row:last-child { border-bottom: none; }
    .pay-detail-row .k { color: #999; font-weight: 600; }
    .pay-detail-row .v { color: #1a1a1a; font-weight: 700; }

    .rejection-note { background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 10px 14px; font-size: .78rem; color: #b91c1c; margin-bottom: 14px; }

    .screenshot-link { display: inline-flex; align-items: center; gap: 6px; margin-top: 12px; font-size: .8rem; color: var(--pink-dark); font-weight: 700; text-decoration: none; padding: 8px 16px; background: var(--pink-bg); border-radius: 50px; }
    .screenshot-link:hover { background: #fce4ec; }

    /* ---- Manage Booking: row stays white/neutral, ONLY the small
           trailing button gets bold color (not the whole row/box) ---- */
    .manage-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 12px 14px;
        border: 1.5px solid var(--pink-light);
        border-radius: 14px;
        background: #fff;
        margin-bottom: 10px;
    }
    .manage-row:last-child { margin-bottom: 0; }
    .manage-row .row-left { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .manage-row .ic-box-mini {
        width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
        background: var(--pink-light); color: var(--pink);
        display: flex; align-items: center; justify-content: center; font-size: .9rem;
    }
    .manage-row .txt .t1 { font-size: .86rem; font-weight: 800; color: #1a1a1a; }
    .manage-row .txt .t2 { font-size: .7rem; color: #999; }

    .mini-btn {
        flex-shrink: 0;
        padding: 8px 18px;
        border-radius: 50px;
        font-weight: 800;
        font-size: .76rem;
        border: none;
        color: #fff;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px;
        box-shadow: 0 4px 10px rgba(0,0,0,.12);
        transition: filter .15s, transform .15s;
    }
    .mini-btn:hover { filter: brightness(1.06); color: #fff; transform: translateY(-1px); }
    .mini-btn.cancel { background: linear-gradient(135deg, var(--cancel-a), var(--cancel-b)); }
    .mini-btn.review     { background: linear-gradient(135deg, var(--pink), var(--pink-dark)); }

    /* --- Modals --- */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(30,10,25,.45); z-index: 999; align-items: center; justify-content: center; padding: 16px; }
    .modal-overlay.active { display: flex; }
    .modal-box { background: #fff; border-radius: 20px; width: 100%; max-width: 440px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,.25); }
    .modal-head { padding: 18px 24px; display:flex; align-items:center; justify-content:space-between; }
    .modal-head.cancel-head { background: linear-gradient(135deg, var(--cancel-a), var(--cancel-b)); }
    .modal-head h3 { font-size: 1rem; font-weight: 800; color: #fff; }
    .modal-close { width:30px;height:30px;border-radius:8px;border:none;background:rgba(255,255,255,.3);color:#fff;cursor:pointer;font-size:.85rem; }
    .modal-body { padding: 20px 24px; }
    .modal-body p.hint { font-size: .83rem; color: #888; margin-bottom: 16px; line-height:1.5; }
    .field-lbl { font-size: .78rem; font-weight: 700; color: #555; margin-bottom: 6px; display:block; }
    .field-input { width: 100%; padding: 11px 14px; border-radius: 10px; border: 2px solid var(--pink-light); font-family: 'Inter',sans-serif; font-size: .86rem; margin-bottom: 14px; }
    .field-input:focus { outline: none; border-color: var(--pink); }
    .modal-foot { padding: 14px 24px 22px; display: flex; gap: 10px; }
    .modal-foot button { flex:1; padding: 11px; border-radius: 10px; font-weight: 800; font-size: .84rem; border: none; cursor: pointer; font-family:'Inter',sans-serif; }
    .btn-keep { background: #f2f2f2 !important; color: #555 !important; }
    .btn-confirm-cancel { background: linear-gradient(135deg, var(--cancel-a), var(--cancel-b)); color: #fff; }
</style>
@endpush

@section('content')
@php
    $statusMap = [
        'pending_payment' => ['label' => 'Awaiting Approval', 'icon' => 'fa-hourglass-half', 'cls' => 'awaiting'],
        'confirmed'       => ['label' => 'Confirmed',         'icon' => 'fa-check-circle',    'cls' => 'confirmed'],
        'completed'       => ['label' => 'Completed',         'icon' => 'fa-flag-checkered',  'cls' => 'completed'],
        'cancelled'       => ['label' => 'Cancelled',         'icon' => 'fa-ban',              'cls' => 'cancelled'],
    ];
    $st = $statusMap[$appointment->status] ?? ['label' => ucfirst(str_replace('_',' ',$appointment->status)), 'icon' => 'fa-circle', 'cls' => 'awaiting'];
@endphp

{{-- Back link --}}
<a href="{{ route('client.appointments.index') }}" class="back-pill">
    <i class="fas fa-arrow-left"></i> Back to My Appointments
</a>

{{-- Small, cute hero --}}
<div class="appt-hero">
    <div class="hero-left">
        <div class="hero-icon"><i class="fas fa-spa"></i></div>
        <div>
            <div class="ref">Ref: {{ $appointment->booking_ref }}</div>
            <h2>{{ $appointment->service->name ?? 'Service' }}</h2>
            <div class="sub"><i class="fas fa-map-marker-alt me-1"></i>{{ $appointment->salon->name ?? '—' }}</div>
        </div>
    </div>
    <span class="status-pill {{ $st['cls'] }}"><i class="fas {{ $st['icon'] }}"></i> {{ $st['label'] }}</span>
</div>

<div class="row g-4">
    {{-- LEFT: Details --}}
    <div class="col-lg-8">

        @if($appointment->status === 'pending_payment')
            @if($appointment->payment && $appointment->payment->status === 'approved')
            <div class="note-box mb-4">
                <i class="fas fa-info-circle"></i>
                <div>Your screenshot was approved, but this booking hasn't been confirmed yet. Please contact support with your booking reference above.</div>
            </div>
            @else
            <div class="note-box mb-4">
                <i class="fas fa-info-circle"></i>
                <div>Your payment screenshot has been submitted and is awaiting admin approval. You'll be notified here and by email once it's confirmed.</div>
            </div>
            @endif
        @endif

        <div class="glam-card">
            <div class="card-title"><span class="ti-ic"><i class="fas fa-calendar-day"></i></span> Appointment Details</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="ic"><i class="fas fa-calendar"></i></div>
                    <div>
                        <div class="lbl">Date</div>
                        <div class="val">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, d F Y') }}</div>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="ic"><i class="fas fa-clock"></i></div>
                    <div>
                        <div class="lbl">Time</div>
                        <div class="val">{{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}</div>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="ic"><i class="fas fa-user"></i></div>
                    <div>
                        <div class="lbl">Stylist</div>
                        <div class="val">{{ $appointment->stylist->name ?? '—' }}</div>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="ic"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <div class="lbl">Salon</div>
                        <div class="val">{{ $appointment->salon->name ?? '—' }}</div>
                        <div class="val-sub">{{ $appointment->salon->city ?? '' }}</div>
                    </div>
                </div>
                @if($appointment->notes)
                <div class="detail-item">
                    <div class="ic"><i class="fas fa-sticky-note"></i></div>
                    <div>
                        <div class="lbl">Notes</div>
                        <div class="val">{{ $appointment->notes }}</div>
                    </div>
                </div>
                @endif
                @if($appointment->duration_minutes ?? false)
                <div class="detail-item">
                    <div class="ic"><i class="fas fa-hourglass-half"></i></div>
                    <div>
                        <div class="lbl">Duration</div>
                        <div class="val">{{ $appointment->duration_minutes }} mins</div>
                    </div>
                </div>
                @endif
                @if($appointment->status === 'cancelled' && $appointment->cancellation_reason)
                <div class="detail-item">
                    <div class="ic" style="background:#fee2e2;color:#dc2626;"><i class="fas fa-ban"></i></div>
                    <div>
                        <div class="lbl">Cancellation Reason</div>
                        <div class="val">{{ $appointment->cancellation_reason }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        @if($appointment->payment)
            @php
                $pay = $appointment->payment;
                $payLabelMap = ['approved' => 'Approved', 'rejected' => 'Rejected', 'pending' => 'Pending'];
                $payIconMap  = ['approved' => 'fa-check', 'rejected' => 'fa-xmark', 'pending' => 'fa-hourglass-half'];
            @endphp
            <div class="glam-card">
                <div class="card-title"><span class="ti-ic"><i class="fas fa-receipt"></i></span> Payment Reference</div>

                <span class="pstatus pstatus-{{ $pay->status }}">
                    <i class="fas {{ $payIconMap[$pay->status] ?? 'fa-circle' }}"></i>
                    {{ $payLabelMap[$pay->status] ?? ucfirst($pay->status) }}
                </span>

                @if($pay->status === 'rejected' && ($pay->rejection_reason ?? false))
                <div class="rejection-note">
                    <i class="fas fa-comment-dots me-1"></i> {{ $pay->rejection_reason }}
                </div>
                @endif

                <div class="pay-detail-list">
                    <div class="pay-detail-row">
                        <span class="k">Method</span>
                        <span class="v">{{ ucfirst($pay->method) }}</span>
                    </div>
                    <div class="pay-detail-row">
                        <span class="k">Transaction Ref</span>
                        <span class="v">{{ $pay->transaction_ref }}</span>
                    </div>
                    <div class="pay-detail-row">
                        <span class="k">Amount Paid</span>
                        <span class="v">Rs. {{ number_format($appointment->advance_amount) }}</span>
                    </div>
                    @if($pay->created_at ?? false)
                    <div class="pay-detail-row">
                        <span class="k">Submitted On</span>
                        <span class="v">{{ $pay->created_at->format('d M Y, h:i A') }}</span>
                    </div>
                    @endif
                    @if(($pay->reviewed_at ?? false) && $pay->status !== 'pending')
                    <div class="pay-detail-row">
                        <span class="k">Reviewed On</span>
                        <span class="v">{{ \Carbon\Carbon::parse($pay->reviewed_at)->format('d M Y, h:i A') }}</span>
                    </div>
                    @endif
                </div>

                @if($pay->screenshot)
                <a href="{{ asset('storage/'.$pay->screenshot) }}" target="_blank" class="screenshot-link">
                    <i class="fas fa-image"></i> View uploaded screenshot
                </a>
                @endif
            </div>
        @endif
    </div>

    {{-- RIGHT: Amount + Actions --}}
    <div class="col-lg-4">
        <div class="glam-card">
            <div class="card-title"><span class="ti-ic"><i class="fas fa-wallet"></i></span> Payment Summary</div>
            <div class="amount-box">
                <div class="amount-row">
                    <span class="k">Advance Paid</span>
                    <span class="v" style="font-size:1rem;">Rs. {{ number_format($appointment->advance_amount) }}</span>
                </div>
                <div class="amount-row total">
                    <span class="k" style="font-weight:800;color:#1a1a1a;">Total Amount</span>
                    <span class="v">Rs. {{ number_format($appointment->total_amount) }}</span>
                </div>
            </div>
        </div>

        @if(in_array($appointment->status, ['pending_payment', 'confirmed']))
        <div class="glam-card">
            <div class="card-title"><span class="ti-ic"><i class="fas fa-sliders-h"></i></span> Manage Booking</div>

            {{-- Row stays white/neutral — only the small pill button is colored --}}
            <div class="manage-row">
                <div class="row-left">
                    <span class="ic-box-mini" style="background:#fee2e2;color:#dc2626;"><i class="fas fa-times-circle"></i></span>
                    <div class="txt">
                        <div class="t1">Cancel Appointment</div>
                        <div class="t2">This can't be undone</div>
                    </div>
                </div>
                <button type="button" class="mini-btn cancel" onclick="openModal('cancelOverlay')">
                    <i class="fas fa-times-circle"></i> Cancel
                </button>
            </div>
        </div>
        @endif

        @if($appointment->status === 'completed' && !$appointment->review)
        <div class="glam-card">
            <div class="manage-row">
                <div class="row-left">
                    <span class="ic-box-mini"><i class="fas fa-star"></i></span>
                    <div class="txt">
                        <div class="t1">Leave a Review</div>
                        <div class="t2">Share your experience</div>
                    </div>
                </div>
                <a href="{{ route('client.reviews.create', $appointment->id) }}" class="mini-btn review">
                    <i class="fas fa-star"></i> Review
                </a>
            </div>
        </div>
        @endif
    </div>
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

@endsection

@push('scripts')
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
    Swal.fire({ icon: 'success', title: 'Yay! 💖', text: @json(session('success')), confirmButtonColor: '#E91E8C', confirmButtonText: 'Great!', background: '#fff7fb' });
    @endif

    @if(session('error'))
    Swal.fire({ icon: 'error', title: 'Oops!', text: @json(session('error')), confirmButtonColor: '#ef4444', background: '#fff7fb' });
    @endif

    @if($errors->any())
    Swal.fire({ icon: 'warning', title: 'Please check', text: @json($errors->first()), confirmButtonColor: '#c2185b', background: '#fff7fb' });
    @endif
});
</script>
@endpush