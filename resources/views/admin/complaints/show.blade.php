@extends('layouts.admin')
@section('title', 'Complaint Details')

@push('styles')
<style>
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 50px;
        border: 1.5px solid #e5e0da;
        color: #6d4c41;
        font-size: 0.8rem;
        font-weight: 700;
        text-decoration: none;
        margin-bottom: 24px;
        transition: all 0.15s;
    }
    .back-link:hover { background: #6d4c41; color: #fff; border-color: #6d4c41; }

    .glam-card {
        background: #fff;
        border-radius: 18px;
        border: 1px solid #eee;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        overflow: hidden;
    }
    .glam-card .card-header {
        padding: 18px 22px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .glam-card .card-title { font-size: 1rem; font-weight: 700; color: #333; }
    .glam-card .card-body { padding: 24px; }

    .badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 14px; border-radius: 50px;
        font-size: 0.72rem; font-weight: 700; text-transform: capitalize;
    }
    .badge-danger  { background: #fdeceb; color: #d9534f; }
    .badge-warning { background: #fff6e0; color: #c9982f; }
    .badge-success { background: #e9f7ef; color: #3fa46a; }

    .complaint-subject {
        font-size: 1.2rem;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 8px;
    }
    .complaint-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px 8px;
        color: #999;
        font-size: 0.82rem;
        margin-bottom: 18px;
    }
    .complaint-meta i { color: #bbb; margin-right: 3px; }
    .complaint-meta .dot { color: #ddd; }

    .complaint-description {
        background: #faf6f2;
        border: 1px solid #f0e8e0;
        border-radius: 14px;
        padding: 18px 20px;
        color: #444;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 8px;
    }

    .section-title {
        font-size: 0.78rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #8d6e63;
        margin: 32px 0 16px;
    }

    /* ---- Conversation ---- */
    .reply-row { display: flex; gap: 12px; margin-bottom: 16px; }
    .reply-row.from-admin { flex-direction: row-reverse; }
    .reply-avatar {
        width: 38px; height: 38px; border-radius: 50%; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.85rem; color: #fff;
    }
    .reply-row.from-client .reply-avatar { background: linear-gradient(135deg,#8d6e63,#6d4c41); }
    .reply-row.from-admin  .reply-avatar { background: linear-gradient(135deg,#E91E8C,#c2185b); }

    .reply-body { max-width: 75%; }
    .reply-row.from-admin .reply-body { text-align: right; }
    .reply-meta { font-size: 0.72rem; color: #aaa; margin-bottom: 4px; }
    .reply-bubble {
        display: inline-block;
        text-align: left;
        background: #f5f5f5;
        border-radius: 14px;
        padding: 12px 16px;
        font-size: 0.86rem;
        color: #333;
        line-height: 1.5;
    }
    .reply-row.from-admin .reply-bubble {
        background: linear-gradient(135deg, #fce4ec, #fbd5e4);
        color: #1a1a1a;
    }

    .no-replies {
        text-align: center;
        color: #bbb;
        font-size: 0.85rem;
        padding: 20px 0;
        border: 2px dashed #eee;
        border-radius: 14px;
    }

    /* ---- Reply form + resolve action (fixed: siblings, not nested) ---- */
    .reply-form-wrap { margin-top: 24px; }
    .reply-form-wrap textarea {
        width: 100%;
        border: 1.5px solid #e5e0da;
        border-radius: 12px;
        padding: 12px 14px;
        font-size: 0.88rem;
        font-family: inherit;
        resize: vertical;
    }
    .reply-form-wrap textarea:focus {
        outline: none;
        border-color: #8d6e63;
    }
    .reply-actions {
        display: flex;
        gap: 10px;
        margin-top: 12px;
        flex-wrap: wrap;
    }

    .btn-primary {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 22px; border-radius: 50px;
        background: linear-gradient(135deg,#8d6e63,#6d4c41);
        color: #fff; border: none; font-weight: 700; font-size: 0.85rem;
        cursor: pointer; transition: opacity 0.15s;
    }
    .btn-primary:hover { opacity: 0.9; }
    .btn-primary.btn-resolve { background: linear-gradient(135deg,#4caf7d,#3fa46a); }

    .btn-outline {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 22px; border-radius: 50px;
        border: 1.5px solid #e5e0da; color: #6d4c41;
        font-weight: 700; font-size: 0.85rem; text-decoration: none;
        transition: all 0.15s;
    }
    .btn-outline:hover { background: #6d4c41; color: #fff; border-color: #6d4c41; }

    .resolved-banner {
        display: flex; align-items: center; gap: 10px;
        background: #e9f7ef; color: #3fa46a; border-radius: 12px;
        padding: 12px 16px; margin-top: 24px; font-size: 0.85rem; font-weight: 600;
    }

    /* ---- Sidebar info card ---- */
    .info-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 12px 0; border-bottom: 1px solid #f4f4f4; font-size: 0.86rem;
    }
    .info-row:last-of-type { border-bottom: none; }
    .info-row span:first-child { color: #999; }
    .info-row strong { color: #1a1a1a; font-weight: 700; }
    .text-danger  { color: #d9534f !important; }
    .text-warning { color: #c9982f !important; }
    .text-success { color: #3fa46a !important; }

    .btn-email {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        width: 100%;
        padding: 11px; margin-top: 16px;
        border-radius: 50px;
        border: 1.5px solid #e5e0da;
        color: #6d4c41; font-weight: 700; font-size: 0.85rem;
        text-decoration: none; transition: all 0.15s;
    }
    .btn-email:hover { background: #6d4c41; color: #fff; border-color: #6d4c41; }
</style>
@endpush

@section('content')

<a href="{{ route('admin.complaints.index') }}" class="back-link">
    <i class="fas fa-arrow-left"></i> Back to Complaints
</a>

<div class="row g-4">
    {{-- LEFT: Complaint + conversation --}}
    <div class="col-lg-8">
        <div class="glam-card">
            <div class="card-header">
                <span class="card-title">Complaint #{{ $complaint->id }}</span>
                <span class="badge {{ $complaint->status == 'open' ? 'badge-danger' : ($complaint->status == 'resolved' ? 'badge-success' : 'badge-warning') }}">
                    {{ ucfirst($complaint->status) }}
                </span>
            </div>

            <div class="card-body">
                <div class="complaint-subject">{{ $complaint->subject }}</div>
                <div class="complaint-meta">
                    <span><i class="fas fa-user"></i>{{ $complaint->client->name ?? 'N/A' }}</span>
                    <span class="dot">•</span>
                    <span><i class="fas fa-store"></i>{{ $complaint->salon->name ?? 'N/A' }}</span>
                    <span class="dot">•</span>
                    <span>{{ $complaint->created_at->diffForHumans() }}</span>
                </div>

                <div class="complaint-description">{{ $complaint->description }}</div>

                <div class="section-title">Conversation</div>

                @forelse($complaint->replies as $reply)
                    @php $isAdmin = $reply->sender_type === 'admin'; @endphp
                    <div class="reply-row {{ $isAdmin ? 'from-admin' : 'from-client' }}">
                        <div class="reply-avatar">{{ strtoupper(substr($reply->user->name ?? 'A', 0, 1)) }}</div>
                        <div class="reply-body">
                            <div class="reply-meta">{{ $reply->user->name ?? 'Unknown' }} • {{ $reply->created_at->diffForHumans() }}</div>
                            <div class="reply-bubble">{{ $reply->message }}</div>
                        </div>
                    </div>
                @empty
                    <div class="no-replies">No replies yet — be the first to respond.</div>
                @endforelse

                @if($complaint->status != 'resolved')
                <div class="reply-form-wrap">
                    <form action="{{ route('admin.complaints.reply', $complaint->id) }}" method="POST">
                        @csrf
                        <textarea name="message" rows="3" placeholder="Write a reply..." required></textarea>
                        <div class="reply-actions">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-paper-plane"></i> Send Reply
                            </button>
                        </div>
                    </form>

                    {{-- Separate form — was previously (incorrectly) nested inside the reply form above --}}
                    <form action="{{ route('admin.complaints.resolve', $complaint->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-primary btn-resolve" style="margin-top:10px;">
                            <i class="fas fa-check-circle"></i> Mark as Resolved
                        </button>
                    </form>
                </div>
                @else
                <div class="resolved-banner">
                    <i class="fas fa-check-circle"></i> This complaint has been resolved.
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- RIGHT: Info sidebar --}}
    <div class="col-lg-4">
        <div class="glam-card">
            <div class="card-header">
                <span class="card-title">Complaint Info</span>
            </div>
            <div class="card-body" style="padding:1.2rem 1.5rem;">
                <div class="info-row">
                    <span>Type</span>
                    <strong>{{ ucfirst(str_replace('_',' ',$complaint->type)) }}</strong>
                </div>
                <div class="info-row">
                    <span>Priority</span>
                    <strong class="{{ $complaint->priority == 'high' ? 'text-danger' : ($complaint->priority == 'medium' ? 'text-warning' : 'text-success') }}">
                        {{ ucfirst($complaint->priority) }}
                    </strong>
                </div>
                <div class="info-row">
                    <span>Submitted</span>
                    <strong>{{ $complaint->created_at->format('d M Y') }}</strong>
                </div>
                <div class="info-row">
                    <span>Status</span>
                    <strong>{{ ucfirst($complaint->status) }}</strong>
                </div>

                <a href="mailto:{{ $complaint->client->email }}" class="btn-email">
                    <i class="fas fa-envelope"></i> Email Client
                </a>
            </div>
        </div>
    </div>
</div>
@endsection