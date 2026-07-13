@extends('layouts.owner')

@section('title', 'Complaint #' . $complaint->id)

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>Complaint #{{ $complaint->id }}</h2>
            <p>Submitted by {{ $complaint->client->name ?? 'N/A' }} on {{ $complaint->created_at->format('M d, Y h:i A') }}</p>
        </div>
        <a href="{{ route('owner.complaints.index') }}" class="btn btn-back">
            <i class="bi bi-arrow-left me-2"></i> Back to Complaints
        </a>
    </div>

    <div class="row g-4">

        {{-- LEFT COLUMN --}}
        <div class="col-lg-8">

            {{-- Complaint Details --}}
            <div class="panel-card mb-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="fw-bold">{{ $complaint->subject }}</h5>
                        <p class="text-muted">
                            <i class="bi bi-tag me-1"></i> {{ $complaint->type_label }}
                            <span class="mx-2">|</span>
                            <i class="bi bi-calendar me-1"></i> {{ $complaint->appointment->appointment_date->format('M d, Y') ?? 'N/A' }}
                            <span class="mx-2">|</span>
                            <i class="bi bi-shop me-1"></i> {{ $complaint->salon->name ?? 'N/A' }}
                        </p>
                    </div>
                    <span class="badge-status {{ $complaint->status_badge }} fs-6">{{ $complaint->status_label }}</span>
                </div>

                <hr>

                <h6 class="fw-bold">Description</h6>
                <p>{{ $complaint->description }}</p>

                @if($complaint->image)
                    <div class="mt-3">
                        <h6 class="fw-bold">Attachment</h6>
                        <a href="{{ asset('storage/' . $complaint->image) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-image me-1"></i> View Image
                        </a>
                    </div>
                @endif

                @if($complaint->rejection_reason)
                    <div class="mt-4 p-3 bg-danger bg-opacity-10 rounded border border-danger">
                        <h6 class="fw-bold text-danger">
                            <i class="bi bi-x-circle me-1"></i> Rejection Reason
                        </h6>
                        <p class="mb-0">{{ $complaint->rejection_reason }}</p>
                    </div>
                @endif

                @if($complaint->admin_response)
                    <div class="mt-4 p-3 bg-warning bg-opacity-10 rounded border border-warning">
                        <h6 class="fw-bold">
                            <i class="bi bi-shield-fill me-1"></i> Admin's Response
                            <small class="text-muted ms-2">{{ $complaint->admin_actioned_at ? $complaint->admin_actioned_at->format('M d, Y h:i A') : '' }}</small>
                        </h6>
                        <p class="mb-0">{{ $complaint->admin_response }}</p>
                    </div>
                @endif
            </div>

            {{-- Owner Reply Section --}}
            <div class="panel-card mb-4">
                <h6 class="fw-bold"><i class="bi bi-chat-fill me-2" style="color:#E85588;"></i> Your Reply</h6>

                @if($complaint->owner_reply)
                    <div class="p-3 bg-light rounded">
                        <p class="mb-0">{{ $complaint->owner_reply }}</p>
                        <small class="text-muted">{{ $complaint->owner_replied_at ? $complaint->owner_replied_at->format('M d, Y h:i A') : '' }}</small>
                    </div>
                @else
                    <p class="text-muted">No reply yet.</p>
                @endif

                @if($complaint->status != 'closed' && $complaint->status != 'rejected' && $complaint->status != 'escalated')
                    <form action="{{ route('owner.complaints.reply', $complaint->id) }}" method="POST" class="mt-3">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label-custom">Reply to Client</label>
                            <textarea name="owner_reply" class="form-control input-custom" rows="4" placeholder="Write your reply...">{{ old('owner_reply') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-save-changes">
                            <i class="bi bi-send me-2"></i> Send Reply
                        </button>
                    </form>
                @endif
            </div>

        </div>

        {{-- RIGHT COLUMN --}}
        <div class="col-lg-4">

            {{-- Actions --}}
            <div class="panel-card mb-4">
                <h6 class="fw-bold"><i class="bi bi-gear-fill me-2" style="color:#E85588;"></i> Actions</h6>

                @if($complaint->isPending())
                    <form action="{{ route('owner.complaints.in-progress', $complaint->id) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-progress w-100">
                            <i class="bi bi-clock-history me-2"></i> Mark In Progress
                        </button>
                    </form>
                @endif

                @if($complaint->isInProgress())
                    <form action="{{ route('owner.complaints.resolve', $complaint->id) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-resolve w-100">
                            <i class="bi bi-check-circle me-2"></i> Resolve Complaint
                        </button>
                    </form>
                @endif

                @if($complaint->isPending() || $complaint->isInProgress())
                    <button type="button" class="btn btn-reject-pay w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="bi bi-x-circle me-2"></i> Reject Complaint
                    </button>
                @endif

                @if($complaint->status == 'escalated')
                    <div class="alert alert-warning mt-2">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        This complaint has been escalated to Admin.
                    </div>
                @endif

                @if($complaint->status == 'closed')
                    <div class="alert alert-success mt-2">
                        <i class="bi bi-check-circle me-2"></i>
                        This complaint is closed.
                    </div>
                @endif

                @if($complaint->status == 'rejected')
                    <div class="alert alert-danger mt-2">
                        <i class="bi bi-x-circle me-2"></i>
                        This complaint has been rejected.
                    </div>
                @endif
            </div>

            {{-- Status Timeline --}}
            <div class="panel-card">
                <h6 class="fw-bold"><i class="bi bi-clock-history me-2" style="color:#E85588;"></i> Status Timeline</h6>
                <ul class="list-unstyled timeline">
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        Submitted ({{ $complaint->created_at->format('M d, Y h:i A') }})
                    </li>

                    @if($complaint->owner_replied_at)
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-info me-2"></i>
                            Owner Replied ({{ $complaint->owner_replied_at->format('M d, Y h:i A') }})
                        </li>
                    @endif

                    @if($complaint->status == 'in_progress' || $complaint->status == 'resolved' || $complaint->status == 'closed')
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-info me-2"></i>
                            In Progress
                        </li>
                    @endif

                    @if($complaint->status == 'resolved' || $complaint->status == 'closed')
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-primary me-2"></i>
                            Resolved
                        </li>
                    @endif

                    @if($complaint->client_action == 'accept')
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Client Accepted ({{ $complaint->client_actioned_at->format('M d, Y h:i A') }})
                        </li>
                    @endif

                    @if($complaint->status == 'closed')
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Closed
                        </li>
                    @endif

                    @if($complaint->status == 'escalated')
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-danger me-2"></i>
                            Escalated to Admin ({{ $complaint->client_actioned_at->format('M d, Y h:i A') }})
                        </li>
                    @endif

                    @if($complaint->status == 'rejected')
                        <li class="mb-2">
                            <i class="bi bi-x-circle-fill text-danger me-2"></i>
                            Rejected ({{ $complaint->rejected_at->format('M d, Y h:i A') }})
                        </li>
                    @endif
                </ul>
            </div>

        </div>

    </div>

@endsection

@push('modals')
    {{-- Reject Modal --}}
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <form action="{{ route('owner.complaints.reject', $complaint->id) }}" method="POST">
                    @csrf
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title">Reject Complaint</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label-custom">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" class="form-control input-custom" rows="4" required placeholder="Why are you rejecting this complaint?"></textarea>
                    </div>
                    <div class="modal-footer modal-footer-custom">
                        <button type="button" class="btn btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-reject-pay">Reject Complaint</button>
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

    .panel-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f0e8ed;
        height: auto !important;
    }

    .badge-status {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .badge-pending { background: #FDF6E8; color: #C4903A; }
    .badge-progress { background: #E8F0FE; color: #3568C4; }
    .badge-resolved { background: #E8F5ED; color: #1E8E64; }
    .badge-closed { background: #F3F4F6; color: #6B7280; }
    .badge-escalated { background: #FCE4EC; color: #D45482; }
    .badge-rejected { background: #FEE2E2; color: #DC2626; }

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

    .btn-save-changes {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        padding: 9px 22px;
        border-radius: 10px;
        border: none;
        transition: all 0.15s ease;
    }
    .btn-save-changes:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.35);
        color: #ffffff !important;
    }

    .btn-progress {
        background: linear-gradient(135deg, #4A7FE0, #3568C4) !important;
        color: #ffffff !important;
        font-weight: 600;
        padding: 11px;
        border-radius: 10px;
        border: none;
        transition: all 0.15s ease;
    }
    .btn-progress:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(74, 127, 224, 0.35);
        color: #ffffff !important;
    }

    .btn-resolve {
        background: linear-gradient(135deg, #2EAE7D, #1E8E64) !important;
        color: #ffffff !important;
        font-weight: 600;
        padding: 11px;
        border-radius: 10px;
        border: none;
        transition: all 0.15s ease;
    }
    .btn-resolve:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(46, 174, 125, 0.35);
        color: #ffffff !important;
    }

    .btn-reject-pay {
        background: #FCE4EC;
        color: #E14D6A;
        border: 1px solid #FBD0D9;
        font-weight: 600;
        padding: 11px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
    }
    .btn-reject-pay:hover {
        background: #E14D6A;
        color: #fff;
    }

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

    .modal-content-custom {
        border-radius: 16px;
        border: none;
        overflow: hidden;
    }
    .modal-header-custom {
        background: #fcf6f9;
        border-bottom: 1px solid #f5eef2;
        padding: 18px 24px;
    }
    .modal-header-custom .modal-title {
        font-weight: 700;
        color: #2d1f2c;
    }
    .modal-body { padding: 22px 24px; }
    .modal-footer-custom {
        border-top: 1px solid #f5eef2;
        padding: 16px 24px;
        gap: 10px;
    }

    .timeline {
        padding-left: 0;
    }
    .timeline li {
        padding: 6px 0;
        border-bottom: 1px solid #f5eef2;
        font-size: 13px;
        color: #4a3a48;
    }
    .timeline li:last-child {
        border-bottom: none;
    }

    .alert {
        border-radius: 12px;
        border: none;
        padding: 0.8rem 1.2rem;
    }
    .alert-success { background: #E8F5E9; color: #1B5E20; }
    .alert-danger { background: #FCE4EC; color: #880E4F; }
    .alert-warning { background: #FDF6E8; color: #856404; }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch !important;
        }
        .btn-back {
            justify-content: center;
            width: 100%;
        }
    }
</style>
@endsection