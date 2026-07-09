@extends('layouts.admin')
@section('title', 'Complaint Details - Glamora Admin')

@push('styles')
<style>
    :root {
        --pk: #E91E8C;
        --pk-dark: #c2185b;
        --pk-light: #fce4ec;
        --pk-bg: #fff0f7;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        border-radius: 50px;
        border: 1.5px solid #e5e0e5;
        color: #666;
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        margin-bottom: 20px;
    }
    .back-link:hover {
        border-color: var(--pk);
        color: var(--pk);
        background: var(--pk-bg);
    }

    .detail-card {
        background: #fff;
        border: 1px solid #f0edf0;
        border-radius: 18px;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .detail-card .card-head {
        padding: 18px 24px;
        border-bottom: 1px solid #f5f0f5;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .detail-card .card-title {
        font-weight: 700;
        font-size: 1rem;
        color: #1a1a1a;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .detail-card .card-title i {
        color: var(--pk);
    }
    .detail-card .card-body {
        padding: 24px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }
    .info-item .label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #aaa;
        font-weight: 600;
        margin-bottom: 4px;
    }
    .info-item .value {
        font-size: 0.92rem;
        font-weight: 600;
        color: #1a1a1a;
    }
    .info-item .value .sub {
        font-size: 0.78rem;
        font-weight: 400;
        color: #999;
    }

    .description-box {
        background: #faf8fa;
        border-radius: 12px;
        padding: 16px 20px;
        border-left: 3px solid var(--pk);
        margin-bottom: 20px;
    }
    .description-box .label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #aaa;
        font-weight: 600;
        margin-bottom: 6px;
    }
    .description-box p {
        margin: 0;
        font-size: 0.88rem;
        color: #444;
        line-height: 1.7;
    }

    .reply-item {
        display: flex;
        gap: 14px;
        padding: 14px 0;
        border-bottom: 1px solid #f5f0f5;
    }
    .reply-item:last-child {
        border-bottom: none;
    }
    .reply-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--pk), var(--pk-dark));
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.75rem;
        flex-shrink: 0;
    }
    .reply-avatar.admin {
        background: linear-gradient(135deg, #1a1a2e, #0f3460);
    }
    .reply-content {
        flex: 1;
    }
    .reply-content .meta {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 4px;
    }
    .reply-content .meta .name {
        font-weight: 700;
        font-size: 0.85rem;
        color: #1a1a1a;
    }
    .reply-content .meta .tag {
        font-size: 0.65rem;
        font-weight: 700;
        padding: 2px 10px;
        border-radius: 50px;
        background: var(--pk-bg);
        color: var(--pk);
    }
    .reply-content .meta .tag.admin-tag {
        background: rgba(26,26,46,0.08);
        color: #1a1a2e;
    }
    .reply-content .meta .time {
        font-size: 0.72rem;
        color: #aaa;
    }
    .reply-content .message {
        font-size: 0.86rem;
        color: #444;
        line-height: 1.6;
        margin: 0;
    }

    .reply-form textarea {
        width: 100%;
        border: 1.5px solid #e5e0e5;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 0.85rem;
        font-family: 'Inter', sans-serif;
        resize: vertical;
        min-height: 80px;
        transition: border 0.2s;
        outline: none;
    }
    .reply-form textarea:focus {
        border-color: var(--pk);
        box-shadow: 0 0 0 3px rgba(233,30,140,0.06);
    }
    .reply-form .btn-send {
        background: var(--pk);
        color: #fff;
        border: none;
        padding: 10px 28px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.82rem;
        cursor: pointer;
        transition: background 0.2s;
        margin-top: 10px;
    }
    .reply-form .btn-send:hover {
        background: var(--pk-dark);
    }

    .btn-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.78rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-status.resolve {
        background: #d1fae5;
        color: #059669;
    }
    .btn-status.resolve:hover {
        background: #059669;
        color: #fff;
    }
    .btn-status.delete {
        background: #fee2e2;
        color: #dc2626;
    }
    .btn-status.delete:hover {
        background: #dc2626;
        color: #fff;
    }
    .btn-status.status-update {
        background: var(--pk-bg);
        color: var(--pk);
        border: 1.5px solid var(--pk-light);
    }
    .btn-status.status-update:hover {
        background: var(--pk);
        color: #fff;
    }

    .status-select {
        padding: 8px 14px;
        border: 1.5px solid #e5e0e5;
        border-radius: 10px;
        font-size: 0.82rem;
        background: #fff;
        outline: none;
        transition: border 0.2s;
    }
    .status-select:focus {
        border-color: var(--pk);
    }

    .action-group {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #f5f0f5;
    }
</style>
@endpush

@section('content')

<a href="{{ route('admin.complaints.index') }}" class="back-link">
    <i class="fas fa-arrow-left"></i> Back to Complaints
</a>

{{-- Complaint Details --}}
<div class="detail-card">
    <div class="card-head">
        <div class="card-title">
            <i class="fas fa-exclamation-circle"></i>
            Complaint #{{ $complaint->id }}
        </div>
        <div>
            <span class="badge-status badge-{{ $complaint->status }}">
                {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
            </span>
        </div>
    </div>
    <div class="card-body">

        {{-- Info Grid --}}
        <div class="info-grid">
            <div class="info-item">
                <div class="label">Client</div>
                <div class="value">{{ $complaint->client->name ?? 'N/A' }}</div>
                <div class="value sub">{{ $complaint->client->email ?? '' }}</div>
            </div>
            <div class="info-item">
                <div class="label">Salon</div>
                <div class="value">{{ $complaint->salon->name ?? 'N/A' }}</div>
                <div class="value sub">{{ $complaint->salon->city ?? '' }}</div>
            </div>
            <div class="info-item">
                <div class="label">Appointment</div>
                <div class="value">#{{ $complaint->appointment_id ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="label">Priority</div>
                <div class="value">
                    <span class="badge-priority priority-{{ $complaint->priority }}">
                        {{ ucfirst($complaint->priority) }}
                    </span>
                </div>
            </div>
            <div class="info-item">
                <div class="label">Submitted</div>
                <div class="value">{{ $complaint->created_at->format('d M Y, h:i A') }}</div>
            </div>
        </div>

        {{-- Subject --}}
        <div style="margin-bottom:12px;">
            <div class="label" style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.5px;color:#aaa;font-weight:600;margin-bottom:4px;">Subject</div>
            <div style="font-size:1rem;font-weight:700;color:#1a1a1a;">{{ $complaint->subject }}</div>
        </div>

        {{-- Description --}}
        <div class="description-box">
            <div class="label">Description</div>
            <p>{{ $complaint->description }}</p>
        </div>

        {{-- Replies --}}
        <div style="margin-top:20px;">
            <h4 style="font-size:0.9rem;font-weight:700;color:#1a1a1a;margin-bottom:12px;">
                <i class="fas fa-comments" style="color:var(--pk);margin-right:8px;"></i>Conversation
            </h4>

            @forelse($complaint->replies as $reply)
            <div class="reply-item">
                <div class="reply-avatar {{ $reply->sender_type === 'admin' ? 'admin' : '' }}">
                    {{ strtoupper(substr($reply->user->name ?? 'A', 0, 1)) }}
                </div>
                <div class="reply-content">
                    <div class="meta">
                        <span class="name">{{ $reply->user->name ?? 'Support' }}</span>
                        @if($reply->sender_type === 'admin')
                            <span class="tag admin-tag">Admin</span>
                        @endif
                        <span class="time">{{ $reply->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="message">{{ $reply->message }}</p>
                </div>
            </div>
            @empty
            <div style="color:#aaa;font-size:0.85rem;padding:12px 0;">No replies yet.</div>
            @endforelse

            {{-- Reply Form --}}
            <form method="POST" action="{{ route('admin.complaints.reply', $complaint->id) }}" class="reply-form" style="margin-top:16px;padding-top:16px;border-top:1px solid #f5f0f5;">
                @csrf
                <textarea name="message" placeholder="Write a reply..." required></textarea>
                <button type="submit" class="btn-send"><i class="fas fa-paper-plane"></i> Send Reply</button>
            </form>
        </div>

        {{-- Actions --}}
        <div class="action-group">
            <form method="POST" action="{{ route('admin.complaints.resolve', $complaint->id) }}" style="display:inline;">
                @csrf
                <button type="submit" class="btn-status resolve">
                    <i class="fas fa-check-circle"></i> Mark as Resolved
                </button>
            </form>

            <form method="POST" action="{{ route('admin.complaints.update-status', $complaint->id) }}" style="display:inline-flex;align-items:center;gap:8px;">
                @csrf
                <select name="status" class="status-select">
                    <option value="open" {{ $complaint->status == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_review" {{ $complaint->status == 'in_review' ? 'selected' : '' }}>In Review</option>
                    <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ $complaint->status == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                <button type="submit" class="btn-status status-update">
                    <i class="fas fa-sync-alt"></i> Update
                </button>
            </form>

            <form method="POST" action="{{ route('admin.complaints.destroy', $complaint->id) }}" style="display:inline;" onsubmit="return confirm('Delete this complaint permanently?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-status delete">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
        </div>
    </div>
</div>

@endsection