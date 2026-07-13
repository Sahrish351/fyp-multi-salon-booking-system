@extends('layouts.admin')
@section('title', 'Complaint #' . $complaint->id)

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">Complaint #{{ $complaint->id }}</h2>
            <p class="text-muted">
                Submitted by {{ $complaint->client->name ?? 'N/A' }} 
                on {{ $complaint->created_at->format('M d, Y h:i A') }}
            </p>
        </div>
        <a href="{{ route('admin.complaints.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Back
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">

            {{-- Complaint Details --}}
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5>{{ $complaint->subject }}</h5>
                            <p class="text-muted">
                                <i class="bi bi-tag me-1"></i> {{ $complaint->type_label }}
                                <span class="mx-2">|</span>
                                <i class="bi bi-shop me-1"></i> {{ $complaint->salon->name ?? 'N/A' }}
                                <span class="mx-2">|</span>
                                <i class="bi bi-person me-1"></i> Owner: {{ $complaint->owner->name ?? 'N/A' }}
                            </p>
                        </div>
                        <span class="badge {{ $complaint->status_badge }} fs-6">{{ $complaint->status_label }}</span>
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

                    {{-- Owner Reply --}}
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="fw-bold">
                            <i class="bi bi-shop me-1"></i> Owner's Reply
                            <small class="text-muted ms-2">{{ $complaint->owner_replied_at ? $complaint->owner_replied_at->format('M d, Y h:i A') : '' }}</small>
                        </h6>
                        <p class="mb-0">{{ $complaint->owner_reply ?? 'No reply from owner.' }}</p>
                    </div>

                    {{-- Client Action --}}
                    <div class="mt-3">
                        <h6 class="fw-bold">Client Action</h6>
                        @if($complaint->client_action == 'escalate')
                            <span class="badge bg-danger">Escalated to Admin</span>
                            <small class="text-muted ms-2">{{ $complaint->client_actioned_at ? $complaint->client_actioned_at->format('M d, Y h:i A') : '' }}</small>
                        @elseif($complaint->client_action == 'accept')
                            <span class="badge bg-success">Accepted</span>
                            <small class="text-muted ms-2">{{ $complaint->client_actioned_at ? $complaint->client_actioned_at->format('M d, Y h:i A') : '' }}</small>
                        @else
                            <span class="badge bg-secondary">Pending</span>
                        @endif
                    </div>

                    @if($complaint->rejection_reason)
                        <div class="mt-4 p-3 bg-danger bg-opacity-10 rounded border border-danger">
                            <h6 class="fw-bold text-danger">
                                <i class="bi bi-x-circle me-1"></i> Rejection Reason
                            </h6>
                            <p class="mb-0">{{ $complaint->rejection_reason }}</p>
                        </div>
                    @endif

                    {{-- Admin Response --}}
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
            </div>

            {{-- Admin Response Form --}}
            @if($complaint->status == 'escalated')
                <div class="card">
                    <div class="card-body">
                        <h6 class="fw-bold"><i class="bi bi-shield-fill me-1 text-warning"></i> Admin Response</h6>
                        <form action="{{ route('admin.complaints.respond', $complaint->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Final Response <span class="text-danger">*</span></label>
                                <textarea name="admin_response" class="form-control" rows="5" required placeholder="Write your final response to resolve this complaint..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-2"></i> Respond & Close
                            </button>
                        </form>
                    </div>
                </div>
            @endif

        </div>

        <div class="col-md-4">

            {{-- Actions --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="fw-bold">Actions</h6>

                    @if($complaint->status == 'escalated')
                        <form action="{{ route('admin.complaints.close', $complaint->id) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-x-circle me-2"></i> Close Without Response
                            </button>
                        </form>
                    @endif

                    @if($complaint->status == 'closed')
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            This complaint is closed.
                        </div>
                    @endif

                    @if($complaint->status == 'rejected')
                        <div class="alert alert-danger">
                            <i class="bi bi-x-circle me-2"></i>
                            This complaint was rejected.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Status Timeline --}}
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-bold">Status Timeline</h6>
                    <ul class="list-unstyled">
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

                        @if($complaint->status == 'in_progress')
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-info me-2"></i>
                                In Progress
                            </li>
                        @endif

                        @if($complaint->status == 'resolved')
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-primary me-2"></i>
                                Resolved
                            </li>
                        @endif

                        @if($complaint->client_action == 'escalate')
                            <li class="mb-2">
                                <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                                Escalated to Admin ({{ $complaint->client_actioned_at->format('M d, Y h:i A') }})
                            </li>
                        @endif

                        @if($complaint->client_action == 'accept')
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                Client Accepted ({{ $complaint->client_actioned_at->format('M d, Y h:i A') }})
                            </li>
                        @endif

                        @if($complaint->admin_response)
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-warning me-2"></i>
                                Admin Reviewed ({{ $complaint->admin_actioned_at->format('M d, Y h:i A') }})
                            </li>
                        @endif

                        @if($complaint->status == 'closed')
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                Closed
                            </li>
                        @endif

                        @if($complaint->status == 'rejected')
                            <li class="mb-2">
                                <i class="bi bi-x-circle-fill text-danger me-2"></i>
                                Rejected ({{ $complaint->rejected_at ? $complaint->rejected_at->format('M d, Y h:i A') : '' }})
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

        </div>
    </div>

</div>

@endsection