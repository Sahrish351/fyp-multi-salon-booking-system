@extends('layouts.admin')
@section('title', 'Notification Details')

@section('content')
<style>
:root { --pk:#FF6B9D; --pk-lt:#fce4ec; --pk-bg:#fff0f7; }

.btn-back {
    display:inline-flex; align-items:center; gap:.5rem; padding:.55rem 1.2rem; border:1.5px solid var(--pk-lt);
    border-radius:50px; font-size:.87rem; font-weight:600; color:var(--pk); text-decoration:none; background:#fff;
    margin-bottom:1.5rem; transition:all .18s ease;
}
.btn-back:hover { background:var(--pk); color:#fff; border-color:var(--pk); box-shadow:0 4px 12px rgba(255,107,157,.3); }

/* ---- flash alerts (same style as index) ---- */
.alert-glam {
    display:flex; align-items:center; gap:10px; padding:.9rem 1.2rem; border-radius:14px; font-size:.88rem;
    font-weight:600; margin-bottom:1.2rem;
}
.alert-glam-success { background:#e9fbf1; color:#16a34a; border:1px solid #bdf0d3; }
.alert-glam-error   { background:#fdeef0; color:#dc2626; border:1px solid #f8c9cf; }

.dcard { background:#fff; border:1px solid #ebebeb; border-radius:16px; overflow:hidden; margin-bottom:1.2rem; }
.dcard-head { padding:1rem 1.4rem; border-bottom:1px solid #f5f2ee; display:flex; justify-content:space-between; align-items:center; }
.dcard-title { font-weight:700; font-size:.95rem; color:#1a1a1a; }
.dcard-body { padding:1.5rem 1.4rem; }

.badge-status { padding:.35rem 1rem; border-radius:20px; font-size:.74rem; font-weight:700; }
.badge-unread { background:#fef3c7; color:#b45309; }
.badge-read   { background:#dcfce7; color:#16a34a; }

.badge-broadcast {
    display:inline-flex; align-items:center; gap:6px; padding:.3rem 1rem; border-radius:20px;
    background:var(--pk-lt); color:var(--pk); font-size:.72rem; font-weight:700; text-transform:uppercase;
    letter-spacing:.04em; margin-left:8px;
}

.msg-box { background:var(--pk-bg); border:1px solid var(--pk-lt); border-radius:14px; padding:1.4rem; }

.info-grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-top:1.3rem; }
.info-item .lbl { font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#aaa; }
.info-item .val { font-size:.9rem; color:#1a1a1a; font-weight:600; margin-top:2px; }

.act-btn {
    display:inline-flex; align-items:center; gap:.55rem; padding:.7rem 1.5rem; border-radius:50px;
    font-weight:700; font-size:.88rem; border:none; cursor:pointer; text-decoration:none;
    transition:all .18s ease;
}
.act-btn:hover { transform:translateY(-2px); }

.act-success { background:linear-gradient(135deg,#34d399,#16a34a); color:#fff; box-shadow:0 4px 14px rgba(22,163,74,.3); }
.act-success:hover { box-shadow:0 6px 18px rgba(22,163,74,.4); }

.act-danger  { background:linear-gradient(135deg,#f87171,#dc2626); color:#fff; box-shadow:0 4px 14px rgba(220,38,38,.3); }
.act-danger:hover { box-shadow:0 6px 18px rgba(220,38,38,.4); }

.act-primary { background:linear-gradient(135deg,var(--pk),#E85588); color:#fff; box-shadow:0 4px 14px rgba(255,107,157,.35); }
.act-primary:hover { box-shadow:0 6px 18px rgba(255,107,157,.45); }
</style>

{{-- ✅ Flash messages so success/error from mark-read / delete actually show here too --}}
@if(session('success'))
    <div class="alert-glam alert-glam-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert-glam alert-glam-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
@endif

<a href="{{ route('admin.notifications.index') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Notifications</a>

<div class="dcard">
    <div class="dcard-head">
        <span class="dcard-title"><i class="fas fa-bell me-2" style="color:var(--pk);"></i>Notification Details</span>
        <span class="badge-status {{ is_null($notification->read_at) ? 'badge-unread' : 'badge-read' }}">
            {{ is_null($notification->read_at) ? 'Unread' : 'Read' }}
        </span>
    </div>
    <div class="dcard-body">
        <h4 style="font-weight:800;color:#1a1a1a;font-size:1.2rem;margin-bottom:.4rem;">
            {{ $notification->data['title'] ?? 'Notification' }}
            @if($notification->type === \App\Notifications\CustomNotification::class && empty($notification->data['action_url']))
                <span class="badge-broadcast"><i class="fas fa-bullhorn"></i> Broadcast</span>
            @endif
        </h4>
        <div style="color:#aaa;font-size:.84rem;margin-bottom:1.3rem;">
            {{ class_basename($notification->type) }} &middot; {{ $notification->created_at->format('d M Y, h:i A') }}
        </div>

        <div class="msg-box">
            <p style="margin:0;color:#333;font-size:.95rem;line-height:1.8;">{{ $notification->data['message'] ?? 'No message content.' }}</p>
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