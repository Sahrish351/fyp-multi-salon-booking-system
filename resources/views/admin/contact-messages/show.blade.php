@extends('layouts.admin')

@section('title', 'Message Details — Glamora')

@push('styles')
<style>
    :root {
        --pk: #E91E8C;
        --pk-dark: #c2185b;
        --ink: #232323;
        --ink-mid: #6b6b6b;
        --ink-lt: #a3a3a3;
        --line: #ececec;
        --paper: #fcfcfc;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--ink-lt);
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        transition: color 0.2s;
    }
    .back-link:hover { color: var(--pk); }

    .msg-page-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--ink);
        margin: 10px 0 0;
    }
    .msg-page-title i { color: var(--pk); margin-right: 10px; }

    /* ============================================================ */
    /* MAIN CARD */
    /* ============================================================ */
    .detail-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid var(--line);
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
    }

    /* --- Toolbar: subject + status + small icon actions, like a real inbox --- */
    .msg-toolbar {
        padding: 1rem 1.4rem;
        border-bottom: 1px solid var(--line);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }
    .msg-toolbar .left { display: flex; align-items: center; gap: 10px; min-width: 0; }
    .msg-toolbar .subject-line {
        font-weight: 600;
        color: var(--ink);
        font-size: 0.95rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 340px;
    }

    .status-badge {
        padding: 3px 12px;
        border-radius: 50px;
        font-size: 0.68rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        flex-shrink: 0;
    }
    .status-badge.unread  { background: #fdecec; color: #c0392b; }
    .status-badge.unread i { font-size: 0.38rem; }
    .status-badge.read    { background: #eaf7ee; color: #1e8449; }
    .status-badge.replied { background: #eaf1fb; color: #2563a8; }

    /* --- Icon action buttons: small, round, natural size --- */
    .icon-actions { display: flex; align-items: center; gap: 6px; flex-shrink: 0; }
    .icon-btn {
        width: 34px; height: 34px;
        border-radius: 8px;
        border: 1px solid var(--line);
        background: #fff;
        color: var(--ink-mid);
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.85rem;
        cursor: pointer;
        text-decoration: none;
        transition: all .15s;
    }
    .icon-btn:hover { background: var(--paper); border-color: #d8d8d8; color: var(--ink); }
    .icon-btn.danger:hover { background: #fdecec; border-color: #f3c5c0; color: #c0392b; }
    .icon-btn.amber:hover { background: #fdf6e8; border-color: #eddca3; color: #a3720e; }

    /* ============================================================ */
    /* BODY */
    /* ============================================================ */
    .detail-card .card-body { padding: 1.5rem 1.6rem; }

    .sender-info {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 18px;
        padding-bottom: 18px;
        border-bottom: 1px solid var(--line);
    }
    .sender-avatar {
        width: 44px; height: 44px;
        border-radius: 50%;
        background: var(--pk);
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 1rem; flex-shrink: 0;
    }
    .sender-details .name { font-weight: 600; color: var(--ink); font-size: 0.95rem; }
    .sender-details .email { color: var(--ink-mid); font-size: 0.8rem; margin-top: 2px; }
    .sender-details .phone {
        color: var(--ink-lt); font-size: 0.76rem; margin-top: 3px;
        display: flex; align-items: center; gap: 5px;
    }

    .meta-info { display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 18px; }
    .meta-info .meta-item {
        color: var(--ink-mid); font-size: 0.76rem;
        display: flex; align-items: center; gap: 6px;
    }
    .meta-info .meta-item i { color: var(--ink-lt); font-size: 0.7rem; width: 14px; }

    .message-body {
        background: var(--paper);
        border-radius: 10px;
        padding: 1.15rem 1.3rem;
        border-left: 3px solid var(--pk);
    }
    .message-body p {
        color: #444; font-size: 0.89rem; line-height: 1.8; margin: 0;
        white-space: pre-wrap; word-break: break-word;
    }

    /* ============================================================ */
    /* REPLY */
    /* ============================================================ */
    .reply-section { margin-top: 22px; padding-top: 20px; border-top: 1px solid var(--line); }
    .reply-section .reply-label {
        font-size: 0.7rem; font-weight: 700; color: var(--ink-mid);
        text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 10px;
        display: flex; align-items: center; gap: 8px;
    }
    .reply-section .reply-label i { color: var(--pk); }
    .reply-textarea {
        border-radius: 10px; border: 1.5px solid var(--line);
        padding: 12px 16px; font-size: 0.86rem; width: 100%; resize: vertical;
        min-height: 84px; font-family: inherit; background: var(--paper); color: var(--ink);
        transition: border-color .2s, box-shadow .2s;
    }
    .reply-textarea:focus {
        border-color: var(--pk); outline: none;
        box-shadow: 0 0 0 3px rgba(233, 30, 140, 0.08); background: #fff;
    }
    .reply-textarea::placeholder { color: #bbb; }

    /* --- Natural-width buttons, NOT full width --- */
    .reply-actions { display: flex; gap: 10px; align-items: center; margin-top: 12px; flex-wrap: wrap; }

    .btn-sm-pink {
        background: var(--pk); color: #fff; border: none; border-radius: 8px;
        padding: 8px 18px; font-weight: 600; font-size: 0.79rem;
        transition: background .18s; cursor: pointer;
        display: inline-flex; align-items: center; gap: 7px;
        text-decoration: none; outline: none; width: auto;
    }
    .btn-sm-pink:hover { background: var(--pk-dark); color: #fff; }

    .btn-sm-outline {
        background: #fff; color: var(--ink-mid); border: 1.5px solid var(--line);
        border-radius: 8px; padding: 8px 16px; font-weight: 600; font-size: 0.79rem;
        transition: all .18s; cursor: pointer;
        display: inline-flex; align-items: center; gap: 7px;
        text-decoration: none; outline: none; width: auto;
    }
    .btn-sm-outline:hover { background: var(--paper); border-color: #d8d8d8; color: var(--ink); }

    /* ============================================================ */
    /* EXISTING REPLY */
    /* ============================================================ */
    .existing-reply {
        background: #f6f9fd; border-radius: 10px; padding: .95rem 1.2rem;
        border-left: 3px solid #2563a8; margin-top: 18px;
    }
    .existing-reply .reply-head {
        font-weight: 700; color: #2563a8; font-size: 0.72rem;
        text-transform: uppercase; letter-spacing: 0.04em;
        display: flex; align-items: center; gap: 6px;
    }
    .existing-reply .reply-text { color: #333; font-size: 0.86rem; line-height: 1.7; margin: 8px 0 0 0; }
    .existing-reply .reply-time { color: #9db3d6; font-size: 0.67rem; margin-top: 8px; }

    /* ============================================================ */
    /* SIDEBAR — compact info only, no stretched buttons */
    /* ============================================================ */
    .sidebar-card {
        background: #fff; border-radius: 14px; border: 1px solid var(--line);
        overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.04);
    }
    .sidebar-card .side-head {
        padding: .9rem 1.3rem; background: var(--paper); border-bottom: 1px solid var(--line);
        font-weight: 600; color: var(--ink); font-size: 0.83rem;
        display: flex; align-items: center; gap: 8px;
    }
    .sidebar-card .side-head i { color: var(--pk); }
    .sidebar-card .side-body { padding: 1.1rem 1.3rem; }

    .info-item {
        display: flex; justify-content: space-between; align-items: center;
        padding: 8px 0; border-bottom: 1px solid var(--line);
    }
    .info-item:last-child { border-bottom: none; }
    .info-item .label { color: var(--ink-lt); font-size: 0.75rem; }
    .info-item .value { color: var(--ink); font-weight: 600; font-size: 0.81rem; }

    @media (max-width: 768px) {
        .msg-toolbar { flex-direction: column; align-items: flex-start; }
        .msg-toolbar .subject-line { max-width: 100%; }
        .reply-actions { flex-wrap: wrap; }
        .sender-info { flex-wrap: wrap; }
    }
</style>
@endpush

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.contact-messages.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Messages
    </a>
    <h4 class="msg-page-title">
        <i class="fas fa-envelope-open-text"></i>Message Details
    </h4>
</div>

<div class="row g-4">

    <div class="col-lg-8">
        <div class="detail-card">

            {{-- Toolbar: subject + status + small icon actions --}}
            <div class="msg-toolbar">
                <div class="left">
                    <span class="subject-line">{{ $message->subject }}</span>
                    <span class="status-badge {{ $message->status === 'unread' ? 'unread' : ($message->status === 'replied' ? 'replied' : 'read') }}">
                        @if($message->status === 'unread')
                            <i class="fas fa-circle"></i> Unread
                        @elseif($message->status === 'replied')
                            <i class="fas fa-reply"></i> Replied
                        @else
                            <i class="fas fa-check"></i> Read
                        @endif
                    </span>
                </div>

                <div class="icon-actions">
                    @if($message->status === 'read')
                    <form action="{{ route('admin.contact-messages.mark-unread', $message->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="icon-btn amber" title="Mark as Unread">
                            <i class="fas fa-undo"></i>
                        </button>
                    </form>
                    @endif

                    <a href="mailto:{{ $message->email }}" class="icon-btn" title="Open in Email">
                        <i class="fas fa-envelope"></i>
                    </a>

                    <form action="{{ route('admin.contact-messages.destroy', $message->id) }}" method="POST"
                          onsubmit="return confirm('Delete this message?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="icon-btn danger" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="card-body">

                {{-- Sender --}}
                <div class="sender-info">
                    <div class="sender-avatar">
                        {{ strtoupper(substr($message->name, 0, 1)) }}
                    </div>
                    <div class="sender-details">
                        <div class="name">{{ $message->name }}</div>
                        <div class="email">{{ $message->email }}</div>
                        @if($message->phone)
                        <div class="phone"><i class="fas fa-phone"></i> {{ $message->phone }}</div>
                        @endif
                    </div>
                </div>

                {{-- Meta --}}
                <div class="meta-info">
                    <span class="meta-item"><i class="fas fa-calendar"></i> {{ $message->created_at->format('d M Y, h:i A') }}</span>
                    <span class="meta-item"><i class="fas fa-clock"></i> {{ $message->created_at->diffForHumans() }}</span>
                    @if($message->ip_address)
                    <span class="meta-item"><i class="fas fa-network-wired"></i> {{ $message->ip_address }}</span>
                    @endif
                </div>

                {{-- Message body --}}
                <div class="message-body">
                    <p>{{ $message->message }}</p>
                </div>

                {{-- Existing Reply --}}
                @if($message->reply)
                <div class="existing-reply">
                    <div class="reply-head">
                        <i class="fas fa-reply"></i> Your Reply
                    </div>
                    <p class="reply-text">{{ $message->reply }}</p>
                    @if($message->replied_at)
                    <div class="reply-time">Sent: {{ $message->replied_at->format('d M Y, h:i A') }}</div>
                    @endif
                </div>
                @endif

                {{-- Reply Form --}}
                <div class="reply-section">
                    <div class="reply-label">
                        <i class="fas fa-reply"></i> Reply
                    </div>
                    <form action="{{ route('admin.contact-messages.reply', $message->id) }}" method="POST">
                        @csrf
                        <textarea name="reply" class="reply-textarea" rows="3" placeholder="Write your reply..."></textarea>
                        <div class="reply-actions">
                            <button type="submit" class="btn-sm-pink">
                                <i class="fas fa-paper-plane"></i> Send Reply
                            </button>
                            <a href="mailto:{{ $message->email }}" class="btn-sm-outline" target="_blank">
                                <i class="fas fa-envelope"></i> Email Client
                            </a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- SIDEBAR --}}
    <div class="col-lg-4">
        <div class="sidebar-card">
            <div class="side-head">
                <i class="fas fa-info-circle"></i> Message Info
            </div>
            <div class="side-body">
                <div class="info-item">
                    <span class="label">ID</span>
                    <span class="value">#{{ $message->id }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Status</span>
                    <span class="value">
                        @if($message->status === 'unread')
                            <span style="color:#c0392b;">Unread</span>
                        @elseif($message->status === 'replied')
                            <span style="color:#2563a8;">Replied</span>
                        @else
                            <span style="color:#1e8449;">Read</span>
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="label">Priority</span>
                    <span class="value">
                        @if($message->priority === 'high')
                            <span style="color:#c0392b;">High</span>
                        @elseif($message->priority === 'medium')
                            <span style="color:#a3720e;">Medium</span>
                        @else
                            <span style="color:#1e8449;">Low</span>
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="label">Received</span>
                    <span class="value">{{ $message->created_at->format('d M Y') }}</span>
                </div>
                @if($message->replied_at)
                <div class="info-item">
                    <span class="label">Replied</span>
                    <span class="value">{{ $message->replied_at->format('d M Y') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection