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
 
    .btn-edit-action {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 14.5px;
        padding: 10px 22px;
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.35);
        transition: all 0.18s ease;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    .btn-edit-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(232, 85, 136, 0.45);
        color: #ffffff !important;
    }
 
    .badge-priority-lg {
        display: inline-block;
        padding: 8px 22px;
        border-radius: 30px;
        font-size: 15px;
        font-weight: 700;
    }
    .badge-priority-high   { background: #FCE4EC; color: #D45482; }
    .badge-priority-medium { background: #FDF6E8; color: #C4903A; }
    .badge-priority-low    { background: #E8F0FE; color: #3568C4; }
 
    .waiting-since { font-size: 13px; color: #8a7a88; margin: 0; }
 
    /* ===== ACTION BUTTONS - PINK ===== */
    .btn-action-book {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        padding: 11px;
        border-radius: 10px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.18s ease;
    }
    .btn-action-book:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.35);
        color: #ffffff !important;
    }
 
    .btn-action-notify {
        background: #FCE8F0;
        color: #E85588;
        font-weight: 600;
        padding: 11px;
        border-radius: 10px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.18s ease;
    }
    .btn-action-notify:hover {
        background: #E85588;
        color: #ffffff !important;
    }
 
    .btn-action-remove {
        background: #fff;
        color: #E85588;
        font-weight: 600;
        padding: 11px;
        border-radius: 10px;
        border: 1.5px solid #FF6B9D;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.18s ease;
    }
    .btn-action-remove:hover {
        background: #E85588;
        color: #ffffff !important;
        border-color: #E85588;
    }
 
    .panel-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f0e8ed;
        height: auto !important;
    }
    .panel-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2d1f2c;
        margin-bottom: 1rem;
    }
 
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px 24px;
    }
    .info-item { display: flex; flex-direction: column; gap: 4px; }
    .info-label { font-size: 12.5px; color: #8a7a88; }
    .info-value { font-size: 14.5px; font-weight: 600; color: #2d1f2c; }
 
    .entry-notes-text {
        color: #4a3a48;
        font-size: 14.5px;
        line-height: 1.7;
        margin-bottom: 0;
    }
 
    .modal-content-custom { border-radius: 16px; border: none; overflow: hidden; }
    .modal-body { padding: 22px 24px; }
    .modal-footer-custom { border-top: 1px solid #f5eef2; padding: 16px 24px; }
 
    .btn-cancel-modal {
        background: #fff;
        border: 1.5px solid #FF6B9D;
        color: #E85588;
        font-weight: 600;
        padding: 9px 20px;
        border-radius: 10px;
        transition: all 0.15s ease;
    }
    .btn-cancel-modal:hover {
        background: #E85588;
        color: #ffffff !important;
        border-color: #E85588;
    }
 
    .btn-delete-confirm {
        background: linear-gradient(135deg, #F0708C, #E85588);
        color: #fff;
        font-weight: 700;
        padding: 9px 24px;
        border-radius: 10px;
        border: none;
        transition: all 0.15s ease;
    }
    .btn-delete-confirm:hover {
        color: #fff;
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.4);
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }
    }
</style>
@endsection