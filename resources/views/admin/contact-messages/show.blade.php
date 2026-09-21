@extends('layouts.admin')
 
@section('title', 'Message Details — Beauty Blush Salons')
 
@push('styles')
<style>
    :root {
        --pk: #FF6B9D;
        --pk-dark: #E85588;
        --pk-lt: #fce4ec;
        --pk-bg: #fff0f7;
        --ink: #232323;
        --ink-mid: #6b6b6b;
        --ink-lt: #a3a3a3;
        --line: #ececec;
        --paper: #fcfcfc;
        --blue: #2563a8;
    }
 
    /* ================= TOP BAR ================= */
    .back-link {
        display: inline-flex; align-items: center; gap: 8px;
        color: var(--ink-lt); text-decoration: none;
        font-size: .85rem; font-weight: 500; transition: all .2s;
    }
    .back-link:hover { color: var(--pk); transform: translateX(-3px); }
 
    .msg-page-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem; font-weight: 700; color: var(--ink);
        margin: 10px 0 0; display: flex; align-items: center; gap: 12px;
    }
    .msg-page-title .title-icon {
        width: 40px; height: 40px; border-radius: 12px;
        background: linear-gradient(135deg, var(--pk), var(--pk-dark));
        color: #fff; display: inline-flex; align-items: center; justify-content: center;
        font-size: .95rem; box-shadow: 0 6px 16px rgba(255,107,157,.3);
    }
 
    /* ================= MAIN CARD ================= */
    .detail-card {
        background: #fff; border-radius: 18px; border: 1px solid var(--line);
        overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.03);
    }
 
    .msg-toolbar {
        padding: 1.1rem 1.5rem; border-bottom: 1px solid var(--line);
        display: flex; align-items: flex-start; justify-content: space-between;
        gap: 14px; flex-wrap: wrap;
        background: linear-gradient(180deg, #fff 0%, #fffafc 100%);
    }
    .msg-toolbar .left { min-width: 0; flex: 1; }
    .msg-toolbar .subject-line {
        font-weight: 700; color: var(--ink); font-size: 1.05rem;
        line-height: 1.4; word-break: break-word; margin-bottom: 8px;
    }
    .msg-toolbar .sub-meta {
        display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
        font-size: .76rem; color: var(--ink-lt);
    }
    .msg-toolbar .sub-meta i { margin-right: 4px; }
 
    /* Status badge */
    .status-badge {
        padding: 4px 13px; border-radius: 50px; font-size: .7rem; font-weight: 700;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .status-badge i { font-size: .65rem; margin: 0; }
    .status-badge.unread   { background: #fdecec; color: #c0392b; }
    .status-badge.unread i { font-size: .4rem; }
    .status-badge.read     { background: #eaf7ee; color: #1e8449; }
    .status-badge.replied  { background: #eaf1fb; color: var(--blue); }
    .status-badge.spam     { background: #fef3c7; color: #d97706; }
    .status-badge.archived { background: #f3f4f6; color: #6b7280; }
 
    /* Icon buttons */
    .icon-actions { display: flex; align-items: center; gap: 6px; flex-shrink: 0; }
    .icon-actions form { margin: 0; display: inline-flex; }
    .icon-btn {
        width: 36px; height: 36px; border-radius: 10px;
        border: 1px solid var(--line); background: #fff; color: var(--ink-mid);
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .85rem; cursor: pointer; text-decoration: none; transition: all .18s;
    }
    /* Sab hover sidebar jaisa light pink */
    .icon-btn:hover, .icon-btn.danger:hover, .icon-btn.amber:hover {
        background: var(--pk-lt); border-color: var(--pk); color: var(--pk-dark);
        transform: translateY(-1px);
    }
 
    /* ================= THREAD ================= */
    .detail-card .card-body { padding: 1.6rem; }
 
    .thread-item { display: flex; gap: 14px; margin-bottom: 20px; }
    .thread-item.outgoing { flex-direction: row-reverse; }
 
    .t-avatar {
        width: 42px; height: 42px; border-radius: 50%; flex-shrink: 0;
        background: linear-gradient(135deg, var(--pk), var(--pk-dark));
        color: #fff; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: .95rem; box-shadow: 0 3px 8px rgba(255,107,157,.25);
    }
    .thread-item.outgoing .t-avatar {
        background: linear-gradient(135deg, #60a5fa, var(--blue));
        box-shadow: 0 3px 8px rgba(37,99,235,.2); font-size: .85rem;
    }
 
    .t-content { flex: 1; min-width: 0; max-width: 92%; }
    .thread-item.outgoing .t-content { display: flex; flex-direction: column; align-items: flex-end; }
 
    .t-head {
        display: flex; align-items: baseline; gap: 10px; margin-bottom: 6px; flex-wrap: wrap;
    }
    .t-head .who { font-weight: 700; color: var(--ink); font-size: .88rem; }
    .t-head .when { color: var(--ink-lt); font-size: .72rem; }
 
    .bubble {
        padding: 1rem 1.25rem; border-radius: 4px 16px 16px 16px;
        background: var(--paper); border: 1px solid var(--line);
        border-left: 3px solid var(--pk);
        color: #3d3d3d; font-size: .9rem; line-height: 1.8;
        white-space: pre-wrap; word-break: break-word; margin: 0;
    }
    .thread-item.outgoing .bubble {
        background: #f3f8fe; border: 1px solid #dbe8f8; border-right: 3px solid var(--blue);
        border-radius: 16px 4px 16px 16px;
    }
 
    /* ================= REPLY FORM ================= */
    .reply-section {
        margin-top: 8px; padding: 1.3rem; border-radius: 16px;
        background: linear-gradient(180deg, #fffafc, #fff);
        border: 1.5px dashed var(--pk-lt);
    }
    .reply-label {
        font-size: .72rem; font-weight: 700; color: var(--ink-mid);
        text-transform: uppercase; letter-spacing: .05em; margin-bottom: 12px;
        display: flex; align-items: center; gap: 8px;
    }
    .reply-label i { color: var(--pk); }
 
    .quick-replies { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 12px; }
    .chip {
        background: #fff; border: 1.5px solid var(--pk-lt); color: var(--ink-mid);
        border-radius: 50px; padding: 5px 14px; font-size: .72rem; font-weight: 600;
        cursor: pointer; transition: all .18s;
    }
    .chip:hover { background: var(--pk-lt); border-color: var(--pk); color: var(--pk-dark); }
 
    .reply-textarea {
        border-radius: 12px; border: 1.5px solid var(--line);
        padding: 13px 16px; font-size: .88rem; width: 100%; resize: vertical;
        min-height: 120px; font-family: inherit; background: #fff; color: var(--ink);
        line-height: 1.7; transition: border-color .2s, box-shadow .2s;
    }
    .reply-textarea:focus {
        border-color: var(--pk); outline: none; box-shadow: 0 0 0 3px rgba(255,107,157,.1);
    }
    .reply-textarea.is-invalid { border-color: #ef4444; }
    .reply-textarea::placeholder { color: #bbb; }
    .field-error { color: #dc2626; font-size: .75rem; margin-top: 6px; }
 
    .reply-actions {
        display: flex; gap: 10px; align-items: center; margin-top: 14px; flex-wrap: wrap;
    }
    .char-count { margin-left: auto; font-size: .72rem; color: var(--ink-lt); }
 
    .btn-sm-pink {
        background: linear-gradient(135deg, var(--pk), var(--pk-dark)); color: #fff;
        border: none; border-radius: 10px; padding: 9px 20px; font-weight: 600;
        font-size: .8rem; transition: all .2s; cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px; text-decoration: none; width: auto;
    }
    .btn-sm-pink:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(255,107,157,.35); color: #fff; }
 
    .btn-sm-outline {
        background: #fff; color: var(--ink-mid); border: 1.5px solid var(--line);
        border-radius: 10px; padding: 9px 18px; font-weight: 600; font-size: .8rem;
        transition: all .18s; cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px; text-decoration: none; width: auto;
    }
    .btn-sm-outline:hover { background: var(--pk-lt); border-color: var(--pk); color: var(--pk-dark); }
 
    /* ================= SIDEBAR ================= */
    .sidebar-stack { display: flex; flex-direction: column; gap: 18px; }
    .sidebar-card {
        background: #fff; border-radius: 18px; border: 1px solid var(--line);
        overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.03);
    }
    .sidebar-card .side-head {
        padding: .9rem 1.3rem; background: var(--paper); border-bottom: 1px solid var(--line);
        font-weight: 700; color: var(--ink); font-size: .83rem;
        display: flex; align-items: center; gap: 8px;
    }
    .sidebar-card .side-head i { color: var(--pk); }
    .sidebar-card .side-body { padding: 1.1rem 1.3rem; }
 
    /* Contact card */
    .contact-hero { text-align: center; padding: 1.4rem 1.3rem 1.2rem; }
    .contact-hero .big-avatar {
        width: 68px; height: 68px; border-radius: 50%; margin: 0 auto 12px;
        background: linear-gradient(135deg, var(--pk), var(--pk-dark)); color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem; font-weight: 700; box-shadow: 0 8px 20px rgba(255,107,157,.3);
    }
    .contact-hero .c-name { font-weight: 700; color: var(--ink); font-size: 1rem; }
    .contact-hero .c-email { color: var(--ink-mid); font-size: .8rem; margin-top: 3px; word-break: break-all; }
    .contact-hero .c-phone { color: var(--ink-lt); font-size: .78rem; margin-top: 5px; }
    .contact-hero .c-phone i { margin-right: 5px; color: var(--pk); }
    .contact-btns { display: flex; justify-content: center; gap: 8px; margin-top: 14px; }
 
    .info-item {
        display: flex; justify-content: space-between; align-items: center;
        padding: 9px 0; border-bottom: 1px solid #f4f4f4; gap: 10px;
    }
    .info-item:last-child { border-bottom: none; padding-bottom: 0; }
    .info-item:first-child { padding-top: 0; }
    .info-item .label { color: var(--ink-lt); font-size: .76rem; }
    .info-item .value { color: var(--ink); font-weight: 600; font-size: .82rem; text-align: right; }
 
    .prio { padding: 2px 10px; border-radius: 50px; font-size: .7rem; font-weight: 700; }
    .prio.high   { background: #fdecec; color: #c0392b; }
    .prio.medium { background: #fdf3dc; color: #a3720e; }
    .prio.low    { background: #eaf7ee; color: #1e8449; }
 
    @media (max-width: 768px) {
        .msg-toolbar { flex-direction: column; }
        .detail-card .card-body { padding: 1.1rem; }
        .t-content { max-width: 100%; }
        .t-avatar { width: 36px; height: 36px; font-size: .85rem; }
        .char-count { margin-left: 0; width: 100%; }
    }
</style>
@endpush
 
@section('content')
 
@php
    $status = $message->status;
    $statusMap = [
        'unread'   => ['label' => 'Unread',   'icon' => 'fa-circle'],
        'read'     => ['label' => 'Read',     'icon' => 'fa-check'],
        'replied'  => ['label' => 'Replied',  'icon' => 'fa-reply'],
        'spam'     => ['label' => 'Spam',     'icon' => 'fa-exclamation-triangle'],
        'archived' => ['label' => 'Archived', 'icon' => 'fa-box-archive'],
    ];
    $statusInfo = $statusMap[$status] ?? $statusMap['read'];
    $statusClass = array_key_exists($status, $statusMap) ? $status : 'read';
    $firstName = explode(' ', trim($message->name))[0];
@endphp
 
<div class="mb-4">
    <a href="{{ route('admin.contact-messages.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Messages
    </a>
    <h4 class="msg-page-title">
        <span class="title-icon"><i class="fas fa-envelope-open-text"></i></span>
        Message Details
    </h4>
</div>
 
<div class="row g-4">
 
    {{-- ================= MAIN ================= --}}
    <div class="col-lg-8">
        <div class="detail-card">
 
            {{-- Toolbar --}}
            <div class="msg-toolbar">
                <div class="left">
                    <div class="subject-line">{{ $message->subject }}</div>
                    <div class="sub-meta">
                        <span class="status-badge {{ $statusClass }}">
                            <i class="fas {{ $statusInfo['icon'] }}"></i> {{ $statusInfo['label'] }}
                        </span>
                        <span><i class="fas fa-calendar"></i>{{ $message->created_at->format('d M Y, h:i A') }}</span>
                        <span><i class="fas fa-clock"></i>{{ $message->created_at->diffForHumans() }}</span>
                    </div>
                </div>
 
                <div class="icon-actions">
                    @if($status === 'read')
                    <form action="{{ route('admin.contact-messages.mark-unread', $message->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="icon-btn amber" title="Mark as Unread">
                            <i class="fas fa-undo"></i>
                        </button>
                    </form>
                    @endif
 
                    <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: ' . $message->subject) }}" class="icon-btn" title="Open in Email">
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
 
                {{-- Incoming message --}}
                <div class="thread-item">
                    <div class="t-avatar">{{ strtoupper(substr($message->name, 0, 1)) }}</div>
                    <div class="t-content">
                        <div class="t-head">
                            <span class="who">{{ $message->name }}</span>
                            <span class="when">{{ $message->created_at->format('d M Y, h:i A') }}</span>
                        </div>
                        <p class="bubble">{{ $message->message }}</p>
                    </div>
                </div>
 
                {{-- Existing reply --}}
                @if($message->reply)
                <div class="thread-item outgoing">
                    <div class="t-avatar"><i class="fas fa-user-shield"></i></div>
                    <div class="t-content">
                        <div class="t-head">
                            <span class="who">You</span>
                            @if($message->replied_at)
                            <span class="when">{{ $message->replied_at->format('d M Y, h:i A') }}</span>
                            @endif
                        </div>
                        <p class="bubble">{{ $message->reply }}</p>
                    </div>
                </div>
                @endif
 
                {{-- Reply form --}}
                <div class="reply-section" id="reply">
                    <div class="reply-label">
                        <i class="fas fa-reply"></i>
                        {{ $message->reply ? 'Send Another Reply' : 'Reply to ' . $firstName }}
                    </div>
 
                    <div class="quick-replies">
                        <button type="button" class="chip" data-reply="Hi {{ $firstName }}, thank you for contacting Beauty Blush Salons! We have received your message and will get back to you shortly.">Thank you</button>
                        <button type="button" class="chip" data-reply="Hi {{ $firstName }}, thanks for reaching out. Could you please share a few more details so we can assist you better?">Need more info</button>
                        <button type="button" class="chip" data-reply="Hi {{ $firstName }}, you can book your appointment directly from our website by choosing your preferred salon, service, and time slot. Let us know if you need any help!">Booking help</button>
                    </div>
 
                    <form action="{{ route('admin.contact-messages.reply', $message->id) }}" method="POST">
                        @csrf
                        <textarea name="reply" id="replyBox" maxlength="2000"
                                  class="reply-textarea @error('reply') is-invalid @enderror"
                                  rows="5" placeholder="Apna reply yahan likhein...">{{ old('reply') }}</textarea>
                        @error('reply')
                        <div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
 
                        <div class="reply-actions">
                            <button type="submit" class="btn-sm-pink">
                                <i class="fas fa-paper-plane"></i> Send Reply
                            </button>
                            <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: ' . $message->subject) }}" class="btn-sm-outline" target="_blank">
                                <i class="fas fa-envelope"></i> Email Client
                            </a>
                            <span class="char-count"><span id="charNow">0</span> / 2000</span>
                        </div>
                    </form>
                </div>
 
            </div>
        </div>
    </div>
 
    {{-- ================= SIDEBAR ================= --}}
    <div class="col-lg-4">
        <div class="sidebar-stack">
 
            {{-- Contact --}}
            <div class="sidebar-card">
                <div class="contact-hero">
                    <div class="big-avatar">{{ strtoupper(substr($message->name, 0, 1)) }}</div>
                    <div class="c-name">{{ $message->name }}</div>
                    <div class="c-email">{{ $message->email }}</div>
                    @if($message->phone)
                    <div class="c-phone"><i class="fas fa-phone"></i>{{ $message->phone }}</div>
                    @endif
                    <div class="contact-btns">
                        <a href="mailto:{{ $message->email }}" class="icon-btn" title="Email"><i class="fas fa-envelope"></i></a>
                        @if($message->phone)
                        <a href="tel:{{ preg_replace('/[^\d+]/', '', $message->phone) }}" class="icon-btn" title="Call"><i class="fas fa-phone"></i></a>
                        @endif
                    </div>
                </div>
            </div>
 
            {{-- Info --}}
            <div class="sidebar-card">
                <div class="side-head"><i class="fas fa-info-circle"></i> Message Info</div>
                <div class="side-body">
                    <div class="info-item">
                        <span class="label">ID</span>
                        <span class="value">#{{ $message->id }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Status</span>
                        <span class="value">
                            <span class="status-badge {{ $statusClass }}">{{ $statusInfo['label'] }}</span>
                        </span>
                    </div>
                    @if($message->priority)
                    <div class="info-item">
                        <span class="label">Priority</span>
                        <span class="value">
                            <span class="prio {{ in_array($message->priority, ['high','medium']) ? $message->priority : 'low' }}">
                                {{ ucfirst($message->priority) }}
                            </span>
                        </span>
                    </div>
                    @endif
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
                    @if($message->ip_address)
                    <div class="info-item">
                        <span class="label">IP Address</span>
                        <span class="value">{{ $message->ip_address }}</span>
                    </div>
                    @endif
                </div>
            </div>
 
        </div>
    </div>
</div>
 
<script>
    (function () {
        var box = document.getElementById('replyBox');
        var counter = document.getElementById('charNow');
        if (!box) return;
 
        function updateCount() { counter.textContent = box.value.length; }
        box.addEventListener('input', updateCount);
        updateCount();
 
        document.querySelectorAll('.chip[data-reply]').forEach(function (chip) {
            chip.addEventListener('click', function () {
                box.value = chip.getAttribute('data-reply');
                updateCount();
                box.focus();
            });
        });
    })();
</script>
 
@endsection