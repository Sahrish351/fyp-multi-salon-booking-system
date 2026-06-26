
@extends('layouts.owner')

@section('title', 'Appointment Details')

@section('content')

    @php
        $statusBadge = [
            'Confirmed'   => 'badge-confirmed',
            'Pending'     => 'badge-pending',
            'In Progress' => 'badge-progress',
            'Completed'   => 'badge-completed',
            'Cancelled'   => 'badge-cancelled',
        ];
    @endphp

  
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>Appointment #{{ $appointment['id'] }}</h2>
            <p>{{ $appointment['date'] }} &middot; {{ $appointment['time_range'] }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('owner.appointments.edit', ['appointment' => $appointment['id']]) }}" class="btn btn-edit-action">
                <i class="bi bi-pencil-square me-2"></i> Edit
            </a>
            <a href="{{ route('owner.appointments.index') }}" class="btn btn-back">
                <i class="bi bi-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-4">

      
        <div class="col-lg-4">
            <div class="panel-card text-center">
                <span class="badge-status-lg {{ $statusBadge[$appointment['status']] ?? 'badge-pending' }}">
                    {{ $appointment['status'] }}
                </span>

                <div class="price-display mt-3">${{ $appointment['price'] }}</div>
                <p class="price-label">Total Amount</p>

                <hr class="my-4">

                <div class="d-flex flex-column gap-2">

                    @if ($appointment['status'] === 'Pending')
                        <form action="{{ route('owner.appointments.confirm', ['id' => $appointment['id']]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-action-confirm w-100">
                                <i class="bi bi-check-circle-fill me-2"></i> Confirm Appointment
                            </button>
                        </form>
                    @endif

                    @if (in_array($appointment['status'], ['Confirmed', 'In Progress']))
                        <form action="{{ route('owner.appointments.complete', ['id' => $appointment['id']]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-action-complete w-100">
                                <i class="bi bi-check2-all me-2"></i> Mark as Completed
                            </button>
                        </form>
                    @endif

                    @if (!in_array($appointment['status'], ['Completed', 'Cancelled']))
                        <button type="button" class="btn btn-action-cancel w-100"
                                data-bs-toggle="modal" data-bs-target="#cancelApptModal">
                            <i class="bi bi-x-circle-fill me-2"></i> Cancel Appointment
                        </button>
                    @endif

                    <a href="{{ route('owner.appointments.invoice', ['id' => $appointment['id']]) }}" class="btn btn-action-invoice w-100">
                        <i class="bi bi-receipt me-2"></i> View Invoice
                    </a>

                </div>
            </div>
        </div>

       
        <div class="col-lg-8">

           
            <div class="panel-card mb-4">
                <div class="panel-title">Client Information</div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Name</span>
                        <span class="info-value">{{ $appointment['client_name'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $appointment['client_email'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Phone</span>
                        <span class="info-value">{{ $appointment['client_phone'] }}</span>
                    </div>
                </div>
            </div>

            
            <div class="panel-card mb-4">
                <div class="panel-title">Appointment Information</div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Service</span>
                        <span class="info-value">{{ $appointment['service'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Stylist</span>
                        <span class="info-value">{{ $appointment['stylist'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date</span>
                        <span class="info-value">{{ $appointment['date'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Time</span>
                        <span class="info-value">{{ $appointment['time_range'] }}</span>
                    </div>
                </div>
            </div>

            @if (!empty($appointment['notes']))
                <div class="panel-card">
                    <div class="panel-title">Notes</div>
                    <p class="appt-notes-text">{{ $appointment['notes'] }}</p>
                </div>
            @endif

        </div>

    </div>

@endsection


@push('modals')

    <div class="modal fade" id="cancelApptModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <form action="{{ route('owner.appointments.cancel', ['id' => $appointment['id']]) }}" method="POST">
                    @csrf
                    <div class="modal-body text-center py-4">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size:42px; color:#E14D6A;"></i>
                        <h5 class="mt-3" style="color:#5C2142; font-weight:700;">Cancel this Appointment?</h5>
                        <p class="mb-0" style="color:#6B4F62;">
                            This will notify {{ $appointment['client_name'] }} that their appointment has been cancelled.
                        </p>
                    </div>
                    <div class="modal-footer modal-footer-custom justify-content-center">
                        <button type="button" class="btn btn-cancel-modal" data-bs-dismiss="modal">Keep Appointment</button>
                        <button type="submit" class="btn btn-delete-confirm">Cancel Appointment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endpush

@section('extra-css')
<style>
    .btn-back {
        background: var(--white); border: 1px solid var(--blush-200); color: var(--plum-800);
        font-weight: 600; font-size: 14.5px; padding: 10px 20px; border-radius: 10px;
        display: inline-flex; align-items: center; transition: all 0.18s ease;
    }
    .btn-back:hover { background: var(--blush-50); color: var(--plum-900); }

    .btn-edit-action {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        color: var(--plum-900); font-weight: 700; font-size: 14.5px;
        padding: 10px 22px; border-radius: 10px; border: none;
        box-shadow: 0 4px 14px rgba(217, 164, 65, 0.35); transition: all 0.18s ease;
        display: inline-flex; align-items: center;
    }
    .btn-edit-action:hover { transform: translateY(-1px); color: var(--plum-900); box-shadow: 0 6px 18px rgba(217, 164, 65, 0.5); }

    .badge-status-lg {
        display: inline-block;
        padding: 8px 22px;
        border-radius: 30px;
        font-size: 15px;
        font-weight: 700;
    }
    .badge-status-lg.badge-confirmed { background: var(--green-50); color: var(--green-500); }
    .badge-status-lg.badge-pending   { background: var(--orange-50); color: var(--orange-500); }
    .badge-status-lg.badge-progress  { background: var(--blue-50); color: var(--blue-500); }
    .badge-status-lg.badge-completed { background: var(--green-50); color: var(--green-500); }
    .badge-status-lg.badge-cancelled { background: var(--red-50); color: var(--red-500); }

    .price-display { font-size: 32px; font-weight: 700; color: var(--gold-600); }
    .price-label { font-size: 13px; color: var(--ink-500); margin: 0; }

    .btn-action-confirm {
        background: linear-gradient(135deg, #38C495, var(--green-500)); color: #fff;
        font-weight: 700; padding: 11px; border-radius: 10px; border: none;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .btn-action-confirm:hover { color: #fff; box-shadow: 0 4px 14px rgba(46, 174, 125, 0.35); }

    .btn-action-complete {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600)); color: var(--plum-900);
        font-weight: 700; padding: 11px; border-radius: 10px; border: none;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .btn-action-complete:hover { color: var(--plum-900); box-shadow: 0 4px 14px rgba(217, 164, 65, 0.35); }

    .btn-action-cancel {
        background: var(--red-50); color: var(--red-500);
        font-weight: 700; padding: 11px; border-radius: 10px; border: 1px solid #FBD0D9;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .btn-action-cancel:hover { background: var(--red-500); color: #fff; }

    .btn-action-invoice {
        background: var(--blush-50); color: var(--plum-800);
        font-weight: 700; padding: 11px; border-radius: 10px; border: 1px solid var(--blush-200);
        display: inline-flex; align-items: center; justify-content: center; text-decoration: none;
    }
    .btn-action-invoice:hover { background: var(--blush-100); color: var(--plum-900); }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px 24px;
    }
    .info-item { display: flex; flex-direction: column; gap: 4px; }
    .info-label { font-size: 12.5px; color: var(--ink-500); }
    .info-value { font-size: 14.5px; font-weight: 600; color: var(--plum-900); }

    .appt-notes-text {
        color: var(--ink-700);
        font-size: 14.5px;
        line-height: 1.7;
        margin-bottom: 0;
    }

    .modal-content-custom { border-radius: var(--radius-lg); border: none; overflow: hidden; }
    .modal-body { padding: 22px 24px; }
    .modal-footer-custom { border-top: 1px solid var(--blush-100); padding: 16px 24px; }

    .btn-cancel-modal {
        background: var(--white); border: 1px solid var(--blush-200); color: var(--ink-700);
        font-weight: 600; padding: 9px 20px; border-radius: 10px;
    }
    .btn-cancel-modal:hover { background: var(--blush-50); }

    .btn-delete-confirm {
        background: linear-gradient(135deg, #F0708C, var(--red-500));
        color: #fff; font-weight: 700; padding: 9px 24px; border-radius: 10px; border: none;
    }
    .btn-delete-confirm:hover { color: #fff; box-shadow: 0 4px 14px rgba(225, 77, 106, 0.4); }
</style>
@endsection
