
@extends('layouts.owner')
 
@section('title', 'Waitlist Entry Details')
 
@section('content')
 
    @php
        $priorityBadge = [
            'High'   => 'badge-priority-high',
            'Medium' => 'badge-priority-medium',
            'Low'    => 'badge-priority-low',
        ];
    @endphp
 
  
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>{{ $entry['client_name'] }}</h2>
            <p>Waitlist Entry Details</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('owner.waitlist.edit', ['waitlist' => $entry['id']]) }}" class="btn btn-edit-action">
                <i class="bi bi-pencil-square me-2"></i> Edit
            </a>
            <a href="{{ route('owner.waitlist.index') }}" class="btn btn-back">
                <i class="bi bi-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>
 
    <div class="row g-4">
 
       
        <div class="col-lg-4">
            <div class="panel-card text-center">
                <span class="badge-priority-lg {{ $priorityBadge[$entry['priority']] ?? 'badge-priority-low' }}">
                    {{ $entry['priority'] }} Priority
                </span>
 
                <p class="waiting-since mt-3">Added on {{ $entry['added_date'] }}</p>
 
                <hr class="my-4">
 
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('owner.appointments.create') }}?client={{ urlencode($entry['client_name']) }}&service={{ urlencode($entry['service']) }}"
                       class="btn btn-action-book w-100">
                        <i class="bi bi-calendar-check-fill me-2"></i> Book Appointment
                    </a>
 
                    <form action="{{ route('owner.waitlist.notify', ['id' => $entry['id']]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-action-notify w-100">
                            <i class="bi bi-bell-fill me-2"></i> Notify Client
                        </button>
                    </form>
 
                    <button type="button" class="btn btn-action-remove w-100"
                            data-bs-toggle="modal" data-bs-target="#removeWaitlistModal">
                        <i class="bi bi-trash3-fill me-2"></i> Remove from Waitlist
                    </button>
                </div>
            </div>
        </div>
 
       
        <div class="col-lg-8">
 
            <div class="panel-card mb-4">
                <div class="panel-title">Client Information</div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Name</span>
                        <span class="info-value">{{ $entry['client_name'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $entry['client_email'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Phone</span>
                        <span class="info-value">{{ $entry['client_phone'] }}</span>
                    </div>
                </div>
            </div>
 
            <div class="panel-card mb-4">
                <div class="panel-title">Waitlist Details</div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Service</span>
                        <span class="info-value">{{ $entry['service'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Preferred Date</span>
                        <span class="info-value">{{ $entry['preferred_date'] }}</span>
                    </div>
                </div>
            </div>
 
            @if (!empty($entry['notes']))
                <div class="panel-card">
                    <div class="panel-title">Notes</div>
                    <p class="entry-notes-text">{{ $entry['notes'] }}</p>
                </div>
            @endif
 
        </div>
 
    </div>
 
@endsection
 

@push('modals')
 
    <div class="modal fade" id="removeWaitlistModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <form action="{{ route('owner.waitlist.destroy', ['waitlist' => $entry['id']]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body text-center py-4">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size:42px; color:#E14D6A;"></i>
                        <h5 class="mt-3" style="color:#5C2142; font-weight:700;">Remove from Waitlist?</h5>
                        <p class="mb-0" style="color:#6B4F62;">
                            Remove "{{ $entry['client_name'] }}" from the waitlist? This action cannot be undone.
                        </p>
                    </div>
                    <div class="modal-footer modal-footer-custom justify-content-center">
                        <button type="button" class="btn btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-delete-confirm">Remove</button>
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
 
    .badge-priority-lg {
        display: inline-block;
        padding: 8px 22px;
        border-radius: 30px;
        font-size: 15px;
        font-weight: 700;
    }
    .badge-priority-high   { background: var(--red-50); color: var(--red-500); }
    .badge-priority-medium { background: #FCEFDE; color: var(--orange-500); }
    .badge-priority-low    { background: var(--blue-50); color: var(--blue-500); }
 
    .waiting-since { font-size: 13px; color: var(--ink-500); margin: 0; }
 
    .btn-action-book {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600)); color: var(--plum-900);
        font-weight: 700; padding: 11px; border-radius: 10px; border: none;
        display: inline-flex; align-items: center; justify-content: center; text-decoration: none;
    }
    .btn-action-book:hover { color: var(--plum-900); box-shadow: 0 4px 14px rgba(217, 164, 65, 0.35); }
 
    .btn-action-notify {
        background: var(--blush-100); color: var(--rose-600);
        font-weight: 700; padding: 11px; border-radius: 10px; border: none;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .btn-action-notify:hover { background: var(--rose-500); color: #fff; }
 
    .btn-action-remove {
        background: var(--red-50); color: var(--red-500);
        font-weight: 700; padding: 11px; border-radius: 10px; border: 1px solid #FBD0D9;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .btn-action-remove:hover { background: var(--red-500); color: #fff; }
 
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px 24px;
    }
    .info-item { display: flex; flex-direction: column; gap: 4px; }
    .info-label { font-size: 12.5px; color: var(--ink-500); }
    .info-value { font-size: 14.5px; font-weight: 600; color: var(--plum-900); }
 
    .entry-notes-text {
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