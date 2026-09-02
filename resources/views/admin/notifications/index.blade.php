@extends('layouts.admin')
@section('title', 'Notifications - Glamora')

@section('content')
<style>
:root { --pk:#FF6B9D; --pk-lt:#fce4ec; --pk-bg:#fff0f7; }

.pg-hdr { display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem; margin-bottom:1.6rem; }
.pg-hdr h1 { font-size:1.55rem; font-weight:700; margin:0 0 .2rem; color:#1a1a1a; }
.pg-hdr p  { margin:0; color:#9a9a9a; font-size:.86rem; }

.hdr-actions { display:flex; gap:10px; flex-wrap:wrap; }

.btn-mark-all, .btn-broadcast-toggle {
    display:inline-flex; align-items:center; gap:.5rem; padding:.65rem 1.35rem; border-radius:50px;
    background:linear-gradient(135deg,var(--pk),#E85588); color:#fff; border:none; font-weight:700; font-size:.85rem;
    box-shadow:0 4px 14px rgba(255,107,157,.35); cursor:pointer; transition:all .18s ease;
}
.btn-mark-all:hover, .btn-broadcast-toggle:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(255,107,157,.45); }

.btn-broadcast-toggle { background:#fff; color:var(--pk); border:1.5px solid var(--pk-lt); box-shadow:none; }
.btn-broadcast-toggle:hover { background:var(--pk); color:#fff; box-shadow:0 4px 12px rgba(255,107,157,.3); }

/* ---- flash alerts ---- */
.alert-glam {
    display:flex; align-items:center; gap:10px; padding:.9rem 1.2rem; border-radius:14px; font-size:.88rem;
    font-weight:600; margin-bottom:1.2rem;
}
.alert-glam-success { background:#e9fbf1; color:#16a34a; border:1px solid #bdf0d3; }
.alert-glam-error   { background:#fdeef0; color:#dc2626; border:1px solid #f8c9cf; }

/* ---- broadcast composer ---- */
.broadcast-card {
    background:#fff; border:1px solid #ebebeb; border-radius:16px; overflow:hidden; margin-bottom:1.4rem;
    display:none;
}
.broadcast-card.open { display:block; }
.broadcast-head {
    padding:1rem 1.4rem; border-bottom:1px solid #f5f2ee; background:linear-gradient(135deg,var(--pk-bg),#fff);
    font-weight:700; font-size:.95rem; color:#1a1a1a; display:flex; align-items:center; gap:8px;
}
.broadcast-body { padding:1.3rem 1.4rem; }
.bc-row { margin-bottom:1rem; }
.bc-row label { display:block; font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:#999; margin-bottom:6px; }
.bc-input, .bc-textarea {
    width:100%; border:1.5px solid #eee; border-radius:12px; padding:.7rem 1rem; font-size:.9rem; color:#1a1a1a;
    transition:border-color .18s ease; font-family:inherit;
}
.bc-input:focus, .bc-textarea:focus { outline:none; border-color:var(--pk); }
.bc-textarea { min-height:90px; resize:vertical; }
.bc-actions { display:flex; gap:10px; flex-wrap:wrap; margin-top:1.1rem; }
.bc-btn {
    display:inline-flex; align-items:center; gap:.5rem; padding:.65rem 1.3rem; border-radius:50px; border:none;
    font-weight:700; font-size:.85rem; cursor:pointer; transition:all .18s ease; color:#fff;
}
.bc-btn:hover { transform:translateY(-2px); }
.bc-btn-all    { background:linear-gradient(135deg,var(--pk),#E85588); box-shadow:0 4px 14px rgba(255,107,157,.35); }
.bc-btn-all:hover { box-shadow:0 6px 18px rgba(255,107,157,.45); }
.bc-btn-owners { background:linear-gradient(135deg,#7c3aed,#5b21b6); box-shadow:0 4px 14px rgba(124,58,237,.3); }
.bc-btn-owners:hover { box-shadow:0 6px 18px rgba(124,58,237,.4); }

.notif-card { background:#fff; border:1px solid #ebebeb; border-radius:16px; overflow:hidden; }
.notif-row {
    display:flex; align-items:flex-start; gap:16px; padding:1.2rem 1.4rem;
    border-bottom:1px solid #f5f5f5; cursor:pointer; transition:background .18s ease, transform .18s ease;
    position:relative;
}
.notif-row:last-child { border-bottom:none; }
.notif-row:hover { background:var(--pk-bg); }
.notif-row.unread { background:#fdf5fa; }
.notif-row.unread:hover { background:#fbeaf3; }

.notif-icon {
    width:46px; height:46px; border-radius:14px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:1.1rem; color:#fff;
    box-shadow:0 4px 10px rgba(0,0,0,.12);
}
.ic-salon    { background:linear-gradient(135deg,#7c3aed,#5b21b6); }
.ic-complaint{ background:linear-gradient(135deg,#d97706,#b45309); }
.ic-contact  { background:linear-gradient(135deg,#0891b2,#0e7490); }
.ic-approved { background:linear-gradient(135deg,#16a34a,#0d8a3e); }
.ic-rejected { background:linear-gradient(135deg,#dc2626,#991b1b); }
.ic-review   { background:linear-gradient(135deg,#f59e0b,#b45309); }
.ic-broadcast{ background:linear-gradient(135deg,var(--pk),#E85588); }
.ic-default  { background:linear-gradient(135deg,var(--pk),#E85588); }

.notif-title { font-size:1rem; color:#1a1a1a; line-height:1.4; }
.notif-title.unread-title { font-weight:800; }
.notif-msg { font-size:.9rem; color:#888; margin-top:3px; line-height:1.5; }
.notif-time { font-size:.78rem; color:#bbb; margin-top:6px; }
.notif-type-badge {
    display:inline-block; font-size:.66rem; font-weight:700; text-transform:uppercase; letter-spacing:.04em;
    padding:3px 11px; border-radius:20px; background:var(--pk-lt); color:var(--pk); margin-left:8px;
}

.unread-dot {
    width:10px; height:10px; border-radius:50%; background:var(--pk); flex-shrink:0; margin-top:8px;
    position:relative;
}
.unread-dot::after {
    content:''; position:absolute; inset:-5px; border-radius:50%;
    border:2px solid var(--pk); opacity:.5; animation:notifPulse 1.8s ease-out infinite;
}
@keyframes notifPulse {
    0%   { transform:scale(.6); opacity:.6; }
    100% { transform:scale(1.8); opacity:0; }
}

.notif-actions { display:flex; gap:8px; flex-shrink:0; }
.nact-btn {
    width:36px; height:36px; border-radius:10px; border:none; display:flex; align-items:center; justify-content:center;
    font-size:.82rem; cursor:pointer; transition:all .18s ease; box-shadow:0 2px 6px rgba(0,0,0,.08);
}
.nact-btn:hover { transform:translateY(-2px) scale(1.06); }

.nact-read {
    background:linear-gradient(135deg,#34d399,#16a34a); color:#fff;
    box-shadow:0 4px 10px rgba(22,163,74,.3);
}
.nact-read:hover { box-shadow:0 6px 14px rgba(22,163,74,.4); }

.nact-delete {
    background:linear-gradient(135deg,#f87171,#dc2626); color:#fff;
    box-shadow:0 4px 10px rgba(220,38,38,.3);
}
.nact-delete:hover { box-shadow:0 6px 14px rgba(220,38,38,.4); }

.empty-st { text-align:center; padding:3.5rem 1rem; color:#ccc; }
.empty-st i { font-size:2.4rem; margin-bottom:.8rem; opacity:.3; display:block; color:var(--pk); }
.empty-st p { color:#999; font-size:.92rem; }
</style>

{{-- ✅ Flash messages (controller already sends these back via ->with(), template just wasn't showing them) --}}
@if(session('success'))
    <div class="alert-glam alert-glam-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert-glam alert-glam-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
@endif

<div class="pg-hdr">
    <div>
        <h1><i class="fas fa-bell" style="color:var(--pk);margin-right:.5rem;"></i>System Notifications</h1>
        <p>New salon requests, complaints, contact messages, and salon approval activity</p>
    </div>
    <div class="hdr-actions">
        <button type="button" class="btn-broadcast-toggle" onclick="document.getElementById('broadcastCard').classList.toggle('open')">
            <i class="fas fa-bullhorn"></i> Broadcast Notification
        </button>
        <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST">
            @csrf
            @method('PUT')
            <button type="submit" class="btn-mark-all"><i class="fas fa-check-double"></i> Mark All Read</button>
        </form>
    </div>
</div>

{{-- ✅ Broadcast panel: wired up to NotificationController@sendToAll and @sendToOwners --}}
<div class="broadcast-card" id="broadcastCard">
    <div class="broadcast-head"><i class="fas fa-bullhorn" style="color:var(--pk);"></i> Send a Broadcast Notification</div>
    <div class="broadcast-body">
        <form id="broadcastForm" method="POST" action="{{ route('admin.notifications.send-to-all') }}">
            @csrf
            <div class="bc-row">
                <label for="bc-title">Title</label>
                <input type="text" id="bc-title" name="title" class="bc-input" maxlength="255" required placeholder="e.g. New Feature Live!">
            </div>
            <div class="bc-row">
                <label for="bc-message">Message</label>
                <textarea id="bc-message" name="message" class="bc-textarea" required placeholder="Write the notification message..."></textarea>
            </div>
            <div class="bc-actions">
                <button type="submit" class="bc-btn bc-btn-all" formaction="{{ route('admin.notifications.send-to-all') }}">
                    <i class="fas fa-users"></i> Send to All Users
                </button>
                <button type="submit" class="bc-btn bc-btn-owners" formaction="{{ route('admin.notifications.send-to-owners') }}">
                    <i class="fas fa-store"></i> Send to Salon Owners
                </button>
            </div>
        </form>
    </div>
</div>

<div class="notif-card">
    @forelse($notifications as $notif)
        @php
            $title = $notif->data['title'] ?? 'Notification';
            $iconClass = 'ic-default'; $icon = 'fa-bell';
            $lower = strtolower($title);
            if (str_contains($lower, 'salon') && str_contains($lower, 'approv')) { $iconClass='ic-approved'; $icon='fa-check-circle'; }
            elseif (str_contains($lower, 'salon') && str_contains($lower, 'reject')) { $iconClass='ic-rejected'; $icon='fa-times-circle'; }
            elseif (str_contains($lower, 'salon')) { $iconClass='ic-salon'; $icon='fa-store'; }
            elseif (str_contains($lower, 'complaint')) { $iconClass='ic-complaint'; $icon='fa-exclamation-circle'; }
            elseif (str_contains($lower, 'contact')) { $iconClass='ic-contact'; $icon='fa-envelope'; }
            elseif (str_contains($lower, 'review')) { $iconClass='ic-review'; $icon='fa-flag'; }
            elseif ($notif->type === \App\Notifications\CustomNotification::class && empty($notif->data['action_url'])) { $iconClass='ic-broadcast'; $icon='fa-bullhorn'; }
        @endphp
        <div class="notif-row {{ !$notif->read_at ? 'unread' : '' }}" onclick="window.location='{{ route('admin.notifications.show', $notif->id) }}'">
            <div class="notif-icon {{ $iconClass }}"><i class="fas {{ $icon }}"></i></div>
            <div class="flex-grow-1">
                <div class="notif-title {{ !$notif->read_at ? 'unread-title' : '' }}">
                    {{ $title }}
                    <span class="notif-type-badge">{{ class_basename($notif->type) }}</span>
                </div>
                <div class="notif-msg">{{ $notif->data['message'] ?? '' }}</div>
                <div class="notif-time"><i class="far fa-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}</div>
            </div>
            @if(!$notif->read_at)
                <div class="unread-dot"></div>
            @endif
            <div class="notif-actions" onclick="event.stopPropagation();">
                @if(!$notif->read_at)
                <form action="{{ route('admin.notifications.mark-read', $notif->id) }}" method="POST">
                    @csrf @method('PUT')
                    <button type="submit" class="nact-btn nact-read" title="Mark as read"><i class="fas fa-check"></i></button>
                </form>
                @endif
                <form action="{{ route('admin.notifications.destroy', $notif->id) }}" method="POST" onsubmit="return confirm('Delete this notification?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="nact-btn nact-delete" title="Delete"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        </div>
    @empty
        <div class="empty-st">
            <i class="fas fa-bell-slash"></i>
            <p>No notifications yet</p>
        </div>
    @endforelse
</div>

@if($notifications->hasPages())
    <div class="mt-4">{{ $notifications->links() }}</div>
@endif

@endsection