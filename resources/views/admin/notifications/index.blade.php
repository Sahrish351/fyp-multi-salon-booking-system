@extends('layouts.admin')
@section('title', 'Notifications - Glamora')

@section('content')
<style>
:root { --pk:#E91E8C; --pk-lt:#fce4ec; --pk-bg:#fff0f7; }

.pg-hdr { display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem; margin-bottom:1.6rem; }
.pg-hdr h1 { font-size:1.55rem; font-weight:700; margin:0 0 .2rem; color:#1a1a1a; }
.pg-hdr p  { margin:0; color:#9a9a9a; font-size:.86rem; }

.btn-mark-all {
    display:inline-flex; align-items:center; gap:.4rem; padding:.6rem 1.2rem; border-radius:9px;
    background:linear-gradient(135deg,var(--pk),#c2185b); color:#fff; border:none; font-weight:700; font-size:.84rem;
}

.notif-card { background:#fff; border:1px solid #ebebeb; border-radius:14px; overflow:hidden; }
.notif-row {
    display:flex; align-items:flex-start; gap:14px; padding:1.1rem 1.3rem;
    border-bottom:1px solid #f5f5f5; cursor:pointer; transition:background .15s;
}
.notif-row:last-child { border-bottom:none; }
.notif-row:hover { background:var(--pk-bg); }
.notif-row.unread { background:#fdf5fa; }

.notif-icon {
    width:42px; height:42px; border-radius:12px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:1rem; color:#fff;
}
.ic-salon    { background:linear-gradient(135deg,#7c3aed,#5b21b6); }
.ic-complaint{ background:linear-gradient(135deg,#d97706,#b45309); }
.ic-contact  { background:linear-gradient(135deg,#0891b2,#0e7490); }
.ic-approved { background:linear-gradient(135deg,#16a34a,#0d8a3e); }
.ic-rejected { background:linear-gradient(135deg,#dc2626,#991b1b); }
.ic-review   { background:linear-gradient(135deg,#f59e0b,#b45309); }
.ic-default  { background:linear-gradient(135deg,var(--pk),#c2185b); }

.notif-title { font-size:.9rem; color:#1a1a1a; }
.notif-title.unread-title { font-weight:700; }
.notif-time { font-size:.74rem; color:#aaa; margin-top:3px; }
.notif-type-badge {
    display:inline-block; font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.04em;
    padding:2px 10px; border-radius:20px; background:var(--pk-lt); color:#c2185b; margin-left:8px;
}
.unread-dot { width:9px; height:9px; border-radius:50%; background:var(--pk); flex-shrink:0; margin-top:6px; }

.notif-actions { display:flex; gap:6px; flex-shrink:0; }
.nact-btn {
    width:32px; height:32px; border-radius:8px; border:none; display:flex; align-items:center; justify-content:center;
    font-size:.78rem; cursor:pointer;
}
.nact-read   { background:#dcfce7; color:#16a34a; }
.nact-delete { background:#fee2e2; color:#dc2626; }

.empty-st { text-align:center; padding:3.5rem 1rem; color:#ccc; }
.empty-st i { font-size:2.4rem; margin-bottom:.8rem; opacity:.3; display:block; }
.empty-st p { color:#999; font-size:.9rem; }
</style>

<div class="pg-hdr">
    <div>
        <h1><i class="fas fa-bell" style="color:var(--pk);margin-right:.5rem;"></i>System Notifications</h1>
        <p>New salon requests, complaints, contact messages, and salon approval activity</p>
    </div>
    <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST">
        @csrf
        @method('PUT')
        <button type="submit" class="btn-mark-all"><i class="fas fa-check-double"></i> Mark All Read</button>
    </form>
</div>

<div class="notif-card">
    @forelse($notifications as $notif)
        @php
            $title = $notif->data['title'] ?? 'Notification';
            // Icon by keyword match on the title — keeps this generic
            // instead of needing a separate "type" column.
            $iconClass = 'ic-default'; $icon = 'fa-bell';
            $lower = strtolower($title);
            if (str_contains($lower, 'salon') && str_contains($lower, 'approv')) { $iconClass='ic-approved'; $icon='fa-check-circle'; }
            elseif (str_contains($lower, 'salon') && str_contains($lower, 'reject')) { $iconClass='ic-rejected'; $icon='fa-times-circle'; }
            elseif (str_contains($lower, 'salon')) { $iconClass='ic-salon'; $icon='fa-store'; }
            elseif (str_contains($lower, 'complaint')) { $iconClass='ic-complaint'; $icon='fa-exclamation-circle'; }
            elseif (str_contains($lower, 'contact')) { $iconClass='ic-contact'; $icon='fa-envelope'; }
            elseif (str_contains($lower, 'review')) { $iconClass='ic-review'; $icon='fa-flag'; }
        @endphp
        <div class="notif-row {{ !$notif->read_at ? 'unread' : '' }}" onclick="window.location='{{ route('admin.notifications.show', $notif->id) }}'">
            <div class="notif-icon {{ $iconClass }}"><i class="fas {{ $icon }}"></i></div>
            <div class="flex-grow-1">
                <div class="notif-title {{ !$notif->read_at ? 'unread-title' : '' }}">
                    {{ $title }}
                    <span class="notif-type-badge">{{ class_basename($notif->type) }}</span>
                </div>
                <div style="font-size:.82rem;color:#888;margin-top:2px;">{{ $notif->data['message'] ?? '' }}</div>
                <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
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