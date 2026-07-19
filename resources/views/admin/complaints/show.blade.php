@extends('layouts.admin')
@section('title', 'Complaint #' . $complaint->id)

@section('content')

<style>
    :root {
        --pk: #c2185b;
        --pk-light: #fce4ec;
        --pk-bg: #fff0f7;
    }

    /* ── Page Header ── */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }
    .page-header h1 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1a1a1a;
        margin: 0;
    }
    .page-header h1 i {
        color: var(--pk);
        margin-right: 10px;
    }
    .page-header p {
        color: #999;
        font-size: 0.85rem;
        margin: 2px 0 0 0;
    }

    .btn-back {
        background: #fff;
        border: 1px solid #e5e0e5;
        color: #666;
        font-weight: 600;
        font-size: 14px;
        padding: 10px 22px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-back:hover {
        border-color: var(--pk);
        color: var(--pk);
        background: var(--pk-bg);
    }

    /* ── Cards ── */
    .panel-card {
        background: #fff;
        border: 1px solid #f0edf0;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    }
    .panel-title {
        font-weight: 700;
        font-size: 0.95rem;
        color: #1a1a1a;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .panel-title i {
        color: var(--pk);
    }

    /* ── Status Badge ── */
    .badge-status {
        display: inline-block;
        padding: 5px 16px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-progress { background: #dbeafe; color: #1e40af; }
    .badge-resolved { background: #d1fae5; color: #065f46; }
    .badge-closed { background: #e5e7eb; color: #4b5563; }
    .badge-escalated { background: #fee2e2; color: #991b1b; }
    .badge-rejected { background: #f3f4f6; color: #6b7280; }

    /* ── Info Grid ── */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-bottom: 16px;
    }
    .info-item {
        background: #f9f8fa;
        padding: 12px 16px;
        border-radius: 10px;
    }
    .info-item .label {
        font-size: 0.6rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #aaa;
    }
    .info-item .value {
        font-size: 0.9rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-top: 2px;
    }

    /* ── Description Box ── */
    .desc-box {
        background: #fcf6f9;
        border-radius: 12px;
        padding: 16px 20px;
        border-left: 4px solid var(--pk);
        margin-top: 4px;
    }
    .desc-box p {
        margin: 0;
        font-size: 0.9rem;
        color: #333;
        line-height: 1.8;
    }

    /* ── Reply Bubbles ── */
    .reply-bubble {
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 12px;
    }
    .reply-bubble.client {
        background: #f0f7ff;
        border-left: 4px solid #3b82f6;
    }
    .reply-bubble.owner {
        background: #fdf0f5;
        border-left: 4px solid var(--pk);
    }
    .reply-bubble.admin {
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
    }
    .reply-bubble .reply-header {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 6px;
    }
    .reply-bubble .reply-name {
        font-weight: 700;
        font-size: 0.85rem;
        color: #1a1a1a;
    }
    .reply-bubble .reply-role {
        font-size: 0.6rem;
        font-weight: 700;
        padding: 2px 12px;
        border-radius: 50px;
        text-transform: uppercase;
    }
    .reply-bubble .reply-role.client { background: #dbeafe; color: #1e40af; }
    .reply-bubble .reply-role.owner { background: #fce4ec; color: var(--pk); }
    .reply-bubble .reply-role.admin { background: #fef3c7; color: #92400e; }
    .reply-bubble .reply-time {
        font-size: 0.7rem;
        color: #999;
    }
    .reply-bubble .reply-text {
        font-size: 0.88rem;
        color: #333;
        line-height: 1.7;
        margin: 0;
    }

    /* ── Action Buttons ── */
    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 22px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.85rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        width: 100%;
        text-decoration: none;
    }
    .btn-action:hover {
        transform: translateY(-2px);
    }
    .btn-action.btn-respond {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: #fff;
        box-shadow: 0 4px 14px rgba(34, 197, 94, 0.3);
    }
    .btn-action.btn-respond:hover {
        box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
    }
    .btn-action.btn-close {
        background: #ef4444;
        color: #fff;
        box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3);
    }
    .btn-action.btn-close:hover {
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
    }

    /* ── Timeline ── */
    .timeline {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .timeline li {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #f5f0f5;
    }
    .timeline li:last-child {
        border-bottom: none;
    }
    .timeline .tl-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .timeline .tl-icon.success { background: #d1fae5; color: #065f46; }
    .timeline .tl-icon.info { background: #dbeafe; color: #1e40af; }
    .timeline .tl-icon.danger { background: #fee2e2; color: #991b1b; }
    .timeline .tl-icon.warning { background: #fef3c7; color: #92400e; }
    .timeline .tl-icon.secondary { background: #e5e7eb; color: #4b5563; }
    .timeline .tl-text { flex: 1; }
    .timeline .tl-text strong { font-size: 0.85rem; color: #1a1a1a; display: block; }
    .timeline .tl-text span { font-size: 0.7rem; color: #999; }

    /* ── Alert Boxes ── */
    .alert-box {
        padding: 14px 18px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    .alert-box.success { background: #d1fae5; color: #065f46; border-left: 4px solid #22c55e; }
    .alert-box.danger { background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; }
    .alert-box.warning { background: #fef3c7; color: #92400e; border-left: 4px solid #f59e0b; }

    /* ── Form ── */
    .form-control {
        border: 1.5px solid #e5e0e5;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 0.9rem;
        width: 100%;
        transition: border 0.2s;
        font-family: inherit;
    }
    .form-control:focus {
        border-color: var(--pk);
        outline: none;
        box-shadow: 0 0 0 3px rgba(194, 24, 91, 0.1);
    }
    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #333;
        margin-bottom: 6px;
        display: block;
    }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch;
        }
        .btn-back {
            justify-content: center;
        }
        .info-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
</style>

{{-- ── Page Header ── --}}
<div class="page-header">
    <div>
        <h1><i class="fas fa-exclamation-circle"></i> Complaint #{{ $complaint->id }}</h1>
        <p>Submitted by {{ $complaint->client->name ?? 'N/A' }} on {{ \Carbon\Carbon::parse($complaint->created_at)->format('M d, Y h:i A') }}</p>
    </div>
    <a href="{{ route('admin.complaints.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to Complaints
    </a>
</div>

<div class="row g-4">

    {{-- ── LEFT COLUMN ── --}}
    <div class="col-lg-8">

        {{-- Complaint Details --}}
        <div class="panel-card">
            <div class="panel-title"><i class="fas fa-info-circle"></i> Complaint Details</div>

            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                <h5 style="font-weight:700;color:#1a1a1a;margin:0;">{{ $complaint->subject }}</h5>
                <span class="badge-status {{ $complaint->status_badge }}">{{ $complaint->status_label }}</span>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="label">Type</div>
                    <div class="value">{{ $complaint->type_label }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Salon</div>
                    <div class="value">{{ $complaint->salon->name ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Owner</div>
                    <div class="value">{{ $complaint->owner->name ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Appointment</div>
                    <div class="value">{{ $complaint->appointment->appointment_date ? \Carbon\Carbon::parse($complaint->appointment->appointment_date)->format('M d, Y') : 'N/A' }}</div>
                </div>
            </div>

            <div class="desc-box">
                <p>{{ $complaint->description }}</p>
            </div>

            @if($complaint->image)
                <div class="mt-3">
                    <a href="{{ asset('storage/' . $complaint->image) }}" target="_blank" class="btn" style="background:#e8f0fe;color:#1e40af;border-radius:8px;padding:6px 16px;font-size:0.8rem;font-weight:600;text-decoration:none;">
                        <i class="fas fa-image me-1"></i> View Attachment
                    </a>
                </div>
            @endif

            @if($complaint->rejection_reason)
                <div class="mt-3" style="background:#fee2e2;border-radius:12px;padding:14px 18px;border-left:4px solid #ef4444;">
                    <strong style="color:#991b1b;font-size:0.8rem;"><i class="fas fa-times-circle me-1"></i> Rejection Reason</strong>
                    <p class="mb-0" style="color:#374151;font-size:0.85rem;margin-top:4px;">{{ $complaint->rejection_reason }}</p>
                </div>
            @endif
        </div>

        {{-- Conversation --}}
        <div class="panel-card">
            <div class="panel-title"><i class="fas fa-comments"></i> Conversation</div>

            {{-- Client --}}
            <div class="reply-bubble client">
                <div class="reply-header">
                    <span class="reply-name">{{ $complaint->client->name ?? 'Client' }}</span>
                    <span class="reply-role client">Client</span>
                    <span class="reply-time">{{ \Carbon\Carbon::parse($complaint->created_at)->format('M d, Y h:i A') }}</span>
                </div>
                <p class="reply-text">{{ $complaint->description }}</p>
            </div>

            {{-- Owner Reply --}}
            @if($complaint->owner_reply)
                <div class="reply-bubble owner">
                    <div class="reply-header">
                        <span class="reply-name">{{ $complaint->owner->name ?? 'Salon Owner' }}</span>
                        <span class="reply-role owner">Owner</span>
                        <span class="reply-time">{{ $complaint->owner_replied_at ? \Carbon\Carbon::parse($complaint->owner_replied_at)->format('M d, Y h:i A') : '' }}</span>
                    </div>
                    <p class="reply-text">{{ $complaint->owner_reply }}</p>
                </div>
            @endif

            {{-- Client Action --}}
            @if($complaint->client_action == 'escalate')
                <div style="background:#fee2e2;border-radius:12px;padding:12px 18px;border-left:4px solid #ef4444;margin-bottom:12px;">
                    <p class="mb-0" style="color:#991b1b;font-weight:600;font-size:0.85rem;">
                        <i class="fas fa-exclamation-triangle me-1"></i> Client escalated this complaint to Admin
                        <span style="font-weight:400;color:#6b7280;">({{ $complaint->client_actioned_at ? \Carbon\Carbon::parse($complaint->client_actioned_at)->format('M d, Y h:i A') : '' }})</span>
                    </p>
                </div>
            @elseif($complaint->client_action == 'accept')
                <div style="background:#d1fae5;border-radius:12px;padding:12px 18px;border-left:4px solid #22c55e;margin-bottom:12px;">
                    <p class="mb-0" style="color:#065f46;font-weight:600;font-size:0.85rem;">
                        <i class="fas fa-check-circle me-1"></i> Client accepted the resolution
                        <span style="font-weight:400;color:#6b7280;">({{ $complaint->client_actioned_at ? \Carbon\Carbon::parse($complaint->client_actioned_at)->format('M d, Y h:i A') : '' }})</span>
                    </p>
                </div>
            @endif

            {{-- Admin Response --}}
            @if($complaint->admin_response)
                <div class="reply-bubble admin">
                    <div class="reply-header">
                        <span class="reply-name">Admin</span>
                        <span class="reply-role admin">Admin</span>
                        <span class="reply-time">{{ $complaint->admin_actioned_at ? \Carbon\Carbon::parse($complaint->admin_actioned_at)->format('M d, Y h:i A') : '' }}</span>
                    </div>
                    <p class="reply-text">{{ $complaint->admin_response }}</p>
                </div>
            @endif
        </div>

        {{-- Admin Response Form --}}
        @if($complaint->status == 'escalated')
            <div class="panel-card">
                <div class="panel-title"><i class="fas fa-shield-alt" style="color:#f59e0b;"></i> Admin Response</div>
                <form action="{{ route('admin.complaints.respond', $complaint->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Final Response <span style="color:#ef4444;">*</span></label>
                        <textarea name="admin_response" class="form-control" rows="5" required placeholder="Write your final response to resolve this complaint..."></textarea>
                    </div>
                    <button type="submit" class="btn-action btn-respond" style="width:auto;padding:11px 30px;">
                        <i class="fas fa-check-circle me-2"></i> Respond & Close
                    </button>
                </form>
            </div>
        @endif

    </div>

    {{-- ── RIGHT COLUMN ── --}}
    <div class="col-lg-4">

        {{-- Actions --}}
        <div class="panel-card">
            <div class="panel-title"><i class="fas fa-cog"></i> Actions</div>

            @if($complaint->status == 'escalated')
                <form action="{{ route('admin.complaints.close', $complaint->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-action btn-close">
                        <i class="fas fa-times-circle me-2"></i> Close Without Response
                    </button>
                </form>
            @endif

            @if($complaint->status == 'closed')
                <div class="alert-box success">
                    <i class="fas fa-check-circle me-2"></i> This complaint is closed.
                </div>
            @endif

            @if($complaint->status == 'rejected')
                <div class="alert-box danger">
                    <i class="fas fa-times-circle me-2"></i> This complaint was rejected.
                </div>
            @endif
        </div>

        {{-- Status Timeline --}}
        <div class="panel-card">
            <div class="panel-title"><i class="fas fa-clock"></i> Status Timeline</div>
            <ul class="timeline">
                <li>
                    <span class="tl-icon success"><i class="fas fa-check"></i></span>
                    <div class="tl-text">
                        <strong>Submitted</strong>
                        <span>{{ \Carbon\Carbon::parse($complaint->created_at)->format('M d, Y h:i A') }}</span>
                    </div>
                </li>

                @if($complaint->owner_replied_at)
                    <li>
                        <span class="tl-icon info"><i class="fas fa-reply"></i></span>
                        <div class="tl-text">
                            <strong>Owner Replied</strong>
                            <span>{{ \Carbon\Carbon::parse($complaint->owner_replied_at)->format('M d, Y h:i A') }}</span>
                        </div>
                    </li>
                @endif

                @if($complaint->status == 'in_progress')
                    <li>
                        <span class="tl-icon info"><i class="fas fa-spinner"></i></span>
                        <div class="tl-text">
                            <strong>In Progress</strong>
                        </div>
                    </li>
                @endif

                @if($complaint->status == 'resolved')
                    <li>
                        <span class="tl-icon success"><i class="fas fa-check-circle"></i></span>
                        <div class="tl-text">
                            <strong>Resolved</strong>
                        </div>
                    </li>
                @endif

                @if($complaint->client_action == 'escalate')
                    <li>
                        <span class="tl-icon danger"><i class="fas fa-exclamation-triangle"></i></span>
                        <div class="tl-text">
                            <strong>Escalated to Admin</strong>
                            <span>{{ $complaint->client_actioned_at ? \Carbon\Carbon::parse($complaint->client_actioned_at)->format('M d, Y h:i A') : '' }}</span>
                        </div>
                    </li>
                @endif

                @if($complaint->client_action == 'accept')
                    <li>
                        <span class="tl-icon success"><i class="fas fa-check-double"></i></span>
                        <div class="tl-text">
                            <strong>Client Accepted</strong>
                            <span>{{ $complaint->client_actioned_at ? \Carbon\Carbon::parse($complaint->client_actioned_at)->format('M d, Y h:i A') : '' }}</span>
                        </div>
                    </li>
                @endif

                @if($complaint->admin_response)
                    <li>
                        <span class="tl-icon warning"><i class="fas fa-shield-alt"></i></span>
                        <div class="tl-text">
                            <strong>Admin Reviewed</strong>
                            <span>{{ $complaint->admin_actioned_at ? \Carbon\Carbon::parse($complaint->admin_actioned_at)->format('M d, Y h:i A') : '' }}</span>
                        </div>
                    </li>
                @endif

                @if($complaint->status == 'closed')
                    <li>
                        <span class="tl-icon success"><i class="fas fa-check-double"></i></span>
                        <div class="tl-text">
                            <strong>Closed</strong>
                        </div>
                    </li>
                @endif

                @if($complaint->status == 'rejected')
                    <li>
                        <span class="tl-icon danger"><i class="fas fa-times"></i></span>
                        <div class="tl-text">
                            <strong>Rejected</strong>
                            <span>{{ $complaint->rejected_at ? \Carbon\Carbon::parse($complaint->rejected_at)->format('M d, Y h:i A') : '' }}</span>
                        </div>
                    </li>
                @endif
            </ul>
        </div>

    </div>

</div>

@endsection