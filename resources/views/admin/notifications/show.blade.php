@extends('layouts.admin')
@section('title', 'Notification Details')

@section('content')
<style>
:root { --pk:#E91E8C; --pk-lt:#fce4ec; --pk-bg:#fff0f7; }

.btn-back {
    display:inline-flex; align-items:center; gap:.5rem; padding:.5rem 1.1rem; border:1.5px solid var(--pk-lt);
    border-radius:9px; font-size:.86rem; font-weight:600; color:#c2185b; text-decoration:none; background:#fff;
    margin-bottom:1.5rem; transition:all .15s;
}
.btn-back:hover { background:var(--pk); color:#fff; border-color:var(--pk); }

.dcard { background:#fff; border:1px solid #ebebeb; border-radius:14px; overflow:hidden; margin-bottom:1.2rem; }
.dcard-head { padding:.9rem 1.4rem; border-bottom:1px solid #f5f2ee; display:flex; justify-content:space-between; align-items:center; }
.dcard-title { font-weight:700; font-size:.9rem; color:#1a1a1a; }
.dcard-body { padding:1.4rem; }

.badge-status { padding:.3rem .9rem; border-radius:20px; font-size:.72rem; font-weight:700; }
.badge-unread { background:#fef3c7; color:#b45309; }
.badge-read   { background:#dcfce7; color:#16a34a; }

.msg-box { background:var(--pk-bg); border:1px solid var(--pk-lt); border-radius:14px; padding:1.3rem; }

.info-grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-top:1.3rem; }
.info-item .lbl { font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#aaa; }
.info-item .val { font-size:.88rem; color:#1a1a1a; font-weight:600; margin-top:2px; }

.act-btn {
    display:inline-flex; align-items:center; gap:.5rem; padding:.6rem 1.3rem; border-radius:9px;
    font-weight:700; font-size:.85rem; border:none; cursor:pointer; text-decoration:none;
}
.act-success { background:linear-gradient(135deg,#16a34a,#0d8a3e); color:#fff; }
.act-danger  { background:linear-gradient(135deg,#dc2626,#991b1b); color:#fff; }
.act-primary { background:linear-gradient(135deg,var(--pk),#c2185b); color:#fff; }
</style>

<a href="{{ route('admin.notifications.index') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Notifications</a>

<div class="dcard">
    <div class="dcard-head">
        <span class="dcard-title"><i class="fas fa-bell me-2" style="color:var(--pk);"></i>Notification Details</span>
        <span class="badge-status {{ is_null($notification->read_at) ? 'badge-unread' : 'badge-read' }}">
            {{ is_null($notification->read_at) ? 'Unread' : 'Read' }}
        </span>
    </div>
    <div class="dcard-body">
        <h4 style="font-weight:700;color:#1a1a1a;margin-bottom:.3rem;">{{ $notification->data['title'] ?? 'Notification' }}</h4>
        <div style="color:#aaa;font-size:.82rem;margin-bottom:1.2rem;">
            {{ class_basename($notification->type) }} &middot; {{ $notification->created_at->format('d M Y, h:i A') }}
        </div>

        <div class="msg-box">
            <p style="margin:0;color:#333;font-size:.9rem;line-height:1.7;">{{ $notification->data['message'] ?? 'No message content.' }}</p>
        </div>

        @if(!empty($notification->data['action_url']))
        <div class="mt-3">
            <a href="{{ $notification->data['action_url'] }}" class="act-btn act-primary">
                <i class="fas fa-arrow-right"></i> View Related Item
            </a>
        </div>
        @endif

        <div class="info-grid">
            <div class="info-item">
                <div class="lbl">Notification ID</div>
                <div class="val">#{{ $notification->id }}</div>
            </div>
            <div class="info-item">
                <div class="lbl">Sent At</div>
                <div class="val">{{ $notification->created_at->format('d M Y, h:i A') }}</div>
            </div>
            @if($notification->read_at)
            <div class="info-item">
                <div class="lbl">Read At</div>
                <div class="val">{{ $notification->read_at->format('d M Y, h:i A') }}</div>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="dcard">
    <div class="dcard-head"><span class="dcard-title"><i class="fas fa-bolt me-2" style="color:var(--pk);"></i>Actions</span></div>
    <div class="dcard-body d-flex gap-3 flex-wrap">
        @if(is_null($notification->read_at))
        <form action="{{ route('admin.notifications.mark-read', $notification->id) }}" method="POST">
            @csrf @method('PUT')
            <button type="submit" class="act-btn act-success"><i class="fas fa-check"></i> Mark as Read</button>
        </form>
        @endif
        <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Delete this notification?');">
            @csrf @method('DELETE')
            <button type="submit" class="act-btn act-danger"><i class="fas fa-trash"></i> Delete</button>
        </form>
    </div>
</div>

@endsection