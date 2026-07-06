{{-- ============================================================ --}}
{{-- FILE: resources/views/client/appointments/index.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.client')
@section('title', 'My Appointments — Glamora')

@push('styles')
<style>
    :root {
        --pink: #E91E8C;
        --pink-dark: #c2185b;
        --pink-light: #fce4ec;
        --pink-bg: #fdf2f8;
        --cancel-a: #ef4444; --cancel-b: #dc2626;
        --vscode-blue: #007acc;
        --vscode-blue-dark: #005a9e;
    }

    .timeframe-tabs { display: flex; gap: 10px; margin-bottom: 14px; flex-wrap: wrap; }
    .timeframe-tab {
        padding: 10px 22px; border-radius: 50px; font-size: .86rem; font-weight: 800;
        text-decoration: none; transition: background .18s, color .18s, border-color .18s; display: inline-flex; align-items: center; gap: 7px;
        border: none;
    }
    .timeframe-tab.active { background: linear-gradient(135deg, var(--pink), var(--pink-dark)); color: #fff; box-shadow: 0 6px 16px rgba(233,30,140,0.22); }
    .timeframe-tab:not(.active) { background: #fff; color: #999; border: 1.5px solid var(--pink-light); }
    .timeframe-tab:not(.active):hover { border-color: var(--pink); color: var(--pink); }

    .status-chips { display: flex; gap: 8px; margin-bottom: 26px; flex-wrap: wrap; }
    .status-chip {
        padding: 5px 15px; border-radius: 50px; font-size: .72rem; font-weight: 700;
        text-decoration: none; transition: all .18s;
    }
    .status-chip.active { background: #fdeef5; color: var(--pink-dark); border: 1.5px solid #f7c9de; }
    .status-chip:not(.active) { background: transparent; color: #aaa; border: 1.5px solid #eee; }
    .status-chip:not(.active):hover { color: var(--pink); border-color: var(--pink-light); }

    .appt-card {
        background: #fff;
        border-radius: 22px;
        overflow: hidden;
        box-shadow: 0 3px 14px rgba(233,30,140,0.05);
        border: 1px solid var(--pink-light);
        height: 100%;
    }
    .appt-card .card-top {
        background: var(--pink-bg);
        padding: 16px 20px;
        display: flex; align-items: center; justify-content: space-between;
        color: var(--pink-dark);
    }
    .appt-card .date-chip { display: flex; align-items: baseline; gap: 6px; }
    .appt-card .date-chip .d { font-size: 1.4rem; font-weight: 900; line-height: 1; color: var(--pink-dark); }
    .appt-card .date-chip .m-y { font-size: .68rem; text-transform: uppercase; opacity: .85; font-weight: 700; letter-spacing: .4px; color: var(--pink-dark); }
    .top-status {
        padding: 5px 14px; border-radius: 50px; font-size: .66rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: .4px; display: inline-flex; align-items: center; gap: 5px;
        background: var(--pink-light); color: var(--pink-dark);
    }
    .appt-card .card-body { padding: 18px 20px 8px; }
    .appt-card .salon-name { font-size: 1.02rem; font-weight: 800; color: #1a1a1a; margin-bottom: 6px; }
    .appt-card .meta-list { display: flex; flex-direction: column; gap: 6px; margin-bottom: 12px; }
    .appt-card .meta-list span { font-size: .8rem; color: #888; display: flex; align-items: center; gap: 8px; }
    .appt-card .meta-list i { color: var(--pink); width: 14px; }
    .appt-card .price-row {
        display: flex; align-items: center; justify-content: space-between;
        background: var(--pink-bg); border-radius: 12px; padding: 10px 14px; margin-bottom: 12px;
    }
    .appt-card .price-row .amt { font-weight: 900; color: var(--pink); font-size: .98rem; }
    .appt-card .screenshot-pill {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: .68rem; font-weight: 700; padding: 4px 12px; border-radius: 50px; margin-bottom: 12px;
    }
    .screenshot-approved { background: #ecfdf5; color: #059669; }
    .screenshot-pending  { background: #fffbeb; color: #b45309; }
    .screenshot-rejected { background: #fef2f2; color: #dc2626; }
    .appt-card .card-actions {
        display: flex; gap: 8px; flex-wrap: wrap;
        padding: 14px 20px 18px; border-top: 1px solid #fdeef5;
    }

    .btn-soft {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 7px 13px; border-radius: 10px;
        text-decoration: none; font-size: .74rem; font-weight: 700;
        border: 1.5px solid; cursor: pointer;
        transition: background .15s, color .15s;
    }
    .btn-soft.view { background: var(--vscode-blue); color: #fff; border-color: var(--vscode-blue); }
    .btn-soft.view:hover { background: var(--pink-dark); border-color: var(--pink-dark); color: #fff; }

    /* ── Reschedule — soft purple/violet, pyara sa ── */
    .btn-soft.reschedule {
        background: #f3f0ff;
        color: #6d28d9;
        border-color: #ddd6fe;
    }
    .btn-soft.reschedule:hover { background: #6d28d9; color: #fff; border-color: #6d28d9; }

    .btn-soft.review { background: #fff8e1; color: #b45309; border-color: #fde68a; }
    .btn-soft.review:hover { background: #fff3cf; }

    .btn-bold {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 7px 13px; border-radius: 10px;
        text-decoration: none; font-size: .74rem; font-weight: 800;
        border: none; cursor: pointer; color: #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,.08);
        transition: filter .15s;
    }
    .btn-bold:hover { filter: brightness(1.06); color: #fff; }
    .btn-bold.cancel { background: linear-gradient(135deg, var(--cancel-a), var(--cancel-b)); }

    .empty-state {
        text-align: center; padding: 4rem 2rem; background: #fff;
        border-radius: 24px; border: 2px dashed var(--pink-light);
    }

    #cancelModal { display: none; position: fixed; inset: 0; background: rgba(30,10,25,0.45); z-index: 999; align-items: center; justify-content: center; padding: 16px; }
    #cancelModal.show { display: flex; }
    .modal-box { background: #fff; border-radius: 20px; padding: 0; max-width: 400px; width: 100%; box-shadow: 0 20px 60px rgba(0,0,0,0.2); overflow: hidden; }
    .modal-box .modal-head { background: linear-gradient(135deg, var(--cancel-a), var(--cancel-b)); padding: 18px 22px; }
    .modal-box h3 { font-size: 1rem; font-weight: 800; color: #fff; margin: 0; }
    .modal-box .modal-body { padding: 18px 22px; }
    .modal-box p { color: #888; font-size: .84rem; margin-bottom: 14px; line-height: 1.5; }
    .modal-box textarea { width: 100%; border: 2px solid var(--pink-light); border-radius: 12px; padding: 10px 14px; font-size: .85rem; font-family: 'Inter', sans-serif; }
    .modal-box textarea:focus { outline: none; border-color: var(--pink); }
    .modal-box .btn-row { display: flex; gap: 10px; justify-content: flex-end; padding: 14px 22px 20px; }
    .modal-box .btn-keep { padding: 10px 20px; border: none; border-radius: 10px; background: #f2f2f2; font-weight: 700; font-size: .8rem; color: #555; cursor: pointer; }
    .modal-box .btn-confirm-cancel { padding: 10px 20px; border: none; border-radius: 10px; background: linear-gradient(135deg, var(--cancel-a), var(--cancel-b)); color: #fff; font-weight: 800; font-size: .8rem; cursor: pointer; }
</style>
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1" style="color:#333;font-family:'Playfair Display',serif;">
            <i class="fas fa-calendar-check me-2" style="color:var(--pink);"></i>My Appointments
        </h4>
        <p style="color:#aaa;font-size:0.85rem;margin:0;">All your salon bookings in one place</p>
    </div>
    <a href="{{ route('salons.index') }}" class="btn btn-sm rounded-pill px-4"
       style="background:linear-gradient(135deg,#E91E8C,#c2185b);color:#fff;border:none;font-weight:600;">
        <i class="fas fa-plus me-1"></i>New Booking
    </a>
</div>

@php
    $curTimeframe = request('timeframe', 'all');
    $curStatus    = request('status', 'all');
    $timeframes = ['all' => ['label' => 'All Time', 'icon' => 'fa-layer-group'],
                   'upcoming' => ['label' => 'Upcoming', 'icon' => 'fa-arrow-trend-up'],
                   'past' => ['label' => 'Past', 'icon' => 'fa-clock-rotate-left']];
@endphp
<div class="timeframe-tabs">
    @foreach($timeframes as $val => $info)
        <a href="{{ route('client.appointments.index', ['timeframe' => $val, 'status' => $curStatus]) }}"
           class="timeframe-tab {{ $curTimeframe === $val ? 'active' : '' }}">
            <i class="fas {{ $info['icon'] }}"></i> {{ $info['label'] }}
        </a>
    @endforeach
</div>

@php
    $statuses = ['all'=>'All', 'confirmed'=>'Confirmed', 'pending_payment'=>'Pending', 'completed'=>'Completed', 'cancelled'=>'Cancelled'];
@endphp
<div class="status-chips">
    @foreach($statuses as $val => $lbl)
        <a href="{{ route('client.appointments.index', ['timeframe' => $curTimeframe, 'status' => $val]) }}"
           class="status-chip {{ $curStatus === $val ? 'active' : '' }}">
            {{ $lbl }}
        </a>
    @endforeach
</div>

<div class="row g-4">
    @forelse($appointments as $appt)
    <div class="col-lg-4 col-md-6">
        <div class="appt-card">

            <div class="card-top">
                <div class="date-chip">
                    <span class="d">{{ $appt->appointment_date->format('d') }}</span>
                    <div class="m-y">
                        {{ $appt->appointment_date->format('M') }}<br>{{ $appt->appointment_date->format('Y') }}
                    </div>
                </div>
                @php
                    $statusMap = [
                        'pending_payment' => ['label' => 'Awaiting Approval', 'icon' => 'fa-hourglass-half'],
                        'confirmed'       => ['label' => 'Confirmed',         'icon' => 'fa-check'],
                        'completed'       => ['label' => 'Completed',         'icon' => 'fa-flag-checkered'],
                        'cancelled'       => ['label' => 'Cancelled',         'icon' => 'fa-ban'],
                    ];
                    $st = $statusMap[$appt->status] ?? ['label' => ucfirst(str_replace('_',' ',$appt->status)), 'icon' => 'fa-circle'];
                @endphp
                <span class="top-status"><i class="fas {{ $st['icon'] }}"></i> {{ $st['label'] }}</span>
            </div>

            <div class="card-body">
                <div class="salon-name">{{ $appt->salon->name }}</div>
                <div class="meta-list">
                    <span><i class="fas fa-spa"></i> {{ Str::limit($appt->service->name, 24) }}</span>
                    <span><i class="fas fa-user"></i> {{ $appt->stylist->name }}</span>
                    <span><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($appt->start_time)->format('h:i A') }}</span>
                </div>

                <div class="price-row">
                    <span style="font-size:.75rem;color:#999;font-weight:600;">Total Amount</span>
                    <span class="amt">Rs. {{ number_format($appt->total_amount) }}</span>
                </div>

                @if($appt->payment)
                    @php
                        $pay = $appt->payment->status;
                        $payClass = $pay === 'approved' ? 'screenshot-approved' : ($pay === 'pending' ? 'screenshot-pending' : 'screenshot-rejected');
                        $payLabel = $pay === 'approved' ? 'Screenshot Approved' : ($pay === 'pending' ? 'Screenshot Under Review' : 'Screenshot Rejected');
                    @endphp
                    <span class="screenshot-pill {{ $payClass }}"><i class="fas fa-image"></i> {{ $payLabel }}</span>
                @endif
            </div>

            <div class="card-actions">
                <a href="{{ route('client.appointments.show', $appt->id) }}" class="btn-soft view">
                    <i class="fas fa-eye"></i> View
                </a>

                @if(!in_array($appt->status, ['cancelled', 'completed']))
                    <button type="button" class="btn-bold cancel" onclick="cancelModal({{ $appt->id }})">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    {{-- ✅ FIXED: added client. prefix to reschedule route --}}
                    <a href="{{ route('client.appointments.reschedule.create', $appt->id) }}" class="btn-soft reschedule">
                        <i class="fas fa-calendar-alt"></i> Reschedule
                    </a>
                @endif

                @if($appt->status === 'completed' && !$appt->review)
                <a href="{{ route('client.reviews.create', $appt->id) }}" class="btn-soft review">
                    <i class="fas fa-star"></i> Review
                </a>
                @endif
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
            <h5 class="fw-bold mb-2" style="color:#333;">No appointments found</h5>
            <p style="color:#aaa;max-width:350px;margin:0 auto 1.5rem;">
                Try a different filter, or start your beauty journey with a new booking.
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

<div id="cancelModal">
    <div class="modal-box">
        <div class="modal-head">
            <h3><i class="fas fa-heart-crack me-2"></i>Cancel Appointment</h3>
        </div>
        <form id="cancelForm" method="POST">
            @csrf
            <div class="modal-body">
                <p>Are you sure you want to cancel this appointment? This action cannot be undone.</p>
                <textarea name="cancellation_reason" rows="3" placeholder="Please tell us why you're cancelling..." required></textarea>
            </div>
            <div class="btn-row">
                <button type="button" class="btn-keep" onclick="closeCancelModal()">Keep Appointment</button>
                <button type="submit" class="btn-confirm-cancel"><i class="fas fa-times-circle"></i> Cancel Appointment</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.1/sweetalert2.all.min.js"></script>
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
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Yay! 💖', text: @json(session('success')), confirmButtonColor: '#E91E8C', confirmButtonText: 'Great!', background: '#fff7fb' });
        @endif
        @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Oops!', text: @json(session('error')), confirmButtonColor: '#ef4444', background: '#fff7fb' });
        @endif
    });
</script>
@endpush