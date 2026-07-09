{{--
    ===========================================================
    NOTIFICATIONS PAGE
    resources/views/owner/notifications/index.blade.php
    Route: GET /owner/notifications --> owner.notifications.index
    ===========================================================
--}}
@extends('layouts.owner')
@section('title', 'Notifications')
 
@section('content')
 
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
 
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
 
    {{-- ===== PAGE HEADER ===== --}}
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h2 class="page-title">
                Notifications
                @if($unreadCount > 0)
                    <span class="unread-badge">{{ $unreadCount }}</span>
                @endif
            </h2>
            <p class="page-sub">Stay updated with salon activities</p>
        </div>
 
        @if($unreadCount > 0)
            <form action="{{ route('owner.notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-mark-all">
                    <i class="bi bi-check2-all me-2"></i> Mark All as Read
                </button>
            </form>
        @endif
    </div>
 
    {{-- ===== STATS ROW ===== --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="notif-stat-card">
                <div class="notif-stat-icon" style="background:#FDE0EC; color:#E85588;">
                    <i class="bi bi-bell-fill"></i>
                </div>
                <div>
                    <div class="notif-stat-label">Total</div>
                    <div class="notif-stat-value">{{ $totalCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="notif-stat-card">
                <div class="notif-stat-icon" style="background:#FEF3DC; color:#C4903A;">
                    <i class="bi bi-bell-slash-fill"></i>
                </div>
                <div>
                    <div class="notif-stat-label">Unread</div>
                    <div class="notif-stat-value">{{ $unreadCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="notif-stat-card">
                <div class="notif-stat-icon" style="background:#E3F7EF; color:#2EAE7D;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div>
                    <div class="notif-stat-label">Read</div>
                    <div class="notif-stat-value">{{ $totalCount - $unreadCount }}</div>
                </div>
            </div>
        </div>
    </div>
 
    {{-- ===== FILTER TABS ===== --}}
    <div class="filter-tabs-row mb-4">
        <a href="{{ route('owner.notifications.index') }}"
           class="filter-tab {{ $filter === 'all' ? 'active' : '' }}">
            All
            @if($totalCount > 0)
                <span class="tab-count">{{ $totalCount }}</span>
            @endif
        </a>
        <a href="{{ route('owner.notifications.index', ['filter' => 'unread']) }}"
           class="filter-tab {{ $filter === 'unread' ? 'active' : '' }}">
            Unread
            @if($unreadCount > 0)
                <span class="tab-count unread">{{ $unreadCount }}</span>
            @endif
        </a>
        <a href="{{ route('owner.notifications.index', ['filter' => 'read']) }}"
           class="filter-tab {{ $filter === 'read' ? 'active' : '' }}">
            Read
        </a>
    </div>
 
    {{-- ===== NOTIFICATIONS LIST ===== --}}
    @if(count($notifications) > 0)
        <div class="notifications-list">
            @foreach($notifications as $notif)
                <div class="notif-card {{ $notif['is_read'] ? 'notif-read' : 'notif-unread' }}"
                     id="notif-{{ $notif['id'] }}">
 
                    {{-- Icon --}}
                    <div class="notif-icon-wrap" style="background:{{ $notif['icon_bg'] }};">
                        <i class="bi {{ $notif['icon'] }}" style="color:{{ $notif['icon_color'] }};"></i>
                    </div>
 
                    {{-- Content --}}
                    <div class="notif-content">
                        <div class="notif-title">
                            @if($notif['link'])
                                <a href="{{ $notif['link'] }}" class="notif-link">{{ $notif['title'] }}</a>
                            @else
                                {{ $notif['title'] }}
                            @endif
                        </div>
                        @if(!empty($notif['message']))
                            <div class="notif-message">{{ $notif['message'] }}</div>
                        @endif
                        <div class="notif-time">
                            <i class="bi bi-clock me-1"></i>{{ $notif['time_ago'] }}
                            <span class="notif-date-full">· {{ $notif['date'] }}</span>
                        </div>
                    </div>
 
                    {{-- Actions --}}
                    <div class="notif-actions">
                        @if(!$notif['is_read'])
                            <form action="{{ route('owner.notifications.read', ['id' => $notif['id']]) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="notif-action-btn read-btn" title="Mark as read">
                                    <i class="bi bi-check2"></i>
                                </button>
                            </form>
                        @else
                            <span class="read-tick" title="Read">
                                <i class="bi bi-check2-all"></i>
                            </span>
                        @endif
 
                        {{-- Unread dot --}}
                        @if(!$notif['is_read'])
                            <span class="unread-dot"></span>
                        @endif
                    </div>
 
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <div class="empty-notif-state">
            <div class="empty-notif-icon">
                <i class="bi bi-bell-slash-fill"></i>
            </div>
            <h5>No notifications yet</h5>
            <p>
                @if($filter === 'unread')
                    You have no unread notifications. You're all caught up!
                @elseif($filter === 'read')
                    No read notifications found.
                @else
                    Notifications will appear here when clients book appointments, make payments, or leave reviews.
                @endif
            </p>
        </div>
    @endif
 
@endsection
 
@section('extra-css')
<style>
    /* ── Page Header ── */
    .page-title {
        font-size: 1.6rem; font-weight: 700; color: #2d1f2c;
        margin: 0 0 4px; display: flex; align-items: center; gap: 10px;
    }
    .page-sub { color: #9E7B95; margin: 0; font-size: 14px; }
 
    .unread-badge {
        background: linear-gradient(135deg, #FF6B9D, #E85588);
        color: #fff; font-size: 13px; font-weight: 700;
        padding: 3px 10px; border-radius: 20px;
        display: inline-flex; align-items: center;
    }
 
    /* ── Mark All Button ── */
    .btn-mark-all {
        background: linear-gradient(135deg, #FF6B9D, #E85588);
        color: #fff; font-weight: 600; font-size: 14px;
        padding: 10px 22px; border-radius: 10px; border: none;
        box-shadow: 0 4px 14px rgba(232,85,136,0.35);
        display: inline-flex; align-items: center; transition: all 0.2s;
    }
    .btn-mark-all:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(232,85,136,0.45); color: #fff; }
 
    /* ── Stat Cards ── */
    .notif-stat-card {
        background: #fff; border-radius: 14px; border: 1px solid #FBD5E8;
        box-shadow: 0 2px 10px rgba(232,85,136,0.06);
        padding: 16px 20px; display: flex; align-items: center; gap: 14px;
        transition: all 0.2s;
    }
    .notif-stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 16px rgba(232,85,136,0.12); }
    .notif-stat-icon {
        width: 46px; height: 46px; border-radius: 12px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 20px;
    }
    .notif-stat-label { font-size: 12px; color: #9E7B95; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 2px; }
    .notif-stat-value { font-size: 22px; font-weight: 700; color: #2d1f2c; }
 
    /* ── Filter Tabs ── */
    .filter-tabs-row {
        display: flex; gap: 8px; flex-wrap: wrap;
        background: #fff; border: 1px solid #FBD5E8;
        border-radius: 14px; padding: 8px; box-shadow: 0 2px 8px rgba(232,85,136,0.06);
    }
    .filter-tab {
        padding: 8px 20px; border-radius: 10px; font-size: 14px; font-weight: 600;
        color: #9E7B95; text-decoration: none; transition: all 0.15s;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .filter-tab:hover { background: #FFF0F6; color: #E85588; }
    .filter-tab.active { background: linear-gradient(135deg, #FF6B9D, #E85588); color: #fff; }
    .tab-count {
        background: rgba(255,255,255,0.25); color: inherit;
        font-size: 11px; padding: 1px 7px; border-radius: 10px; font-weight: 700;
    }
    .tab-count.unread { background: #E85588; color: #fff; }
    .filter-tab.active .tab-count { background: rgba(255,255,255,0.3); }
 
    /* ── Notification Cards ── */
    .notifications-list { display: flex; flex-direction: column; gap: 10px; }
 
    .notif-card {
        background: #fff; border-radius: 14px; border: 1px solid #FBD5E8;
        padding: 18px 20px; display: flex; align-items: flex-start; gap: 16px;
        transition: all 0.2s; position: relative;
    }
    .notif-card:hover { box-shadow: 0 4px 16px rgba(232,85,136,0.10); transform: translateX(2px); }
    .notif-unread { border-left: 4px solid #FF6B9D; background: #FFF9FC; }
    .notif-read   { border-left: 4px solid transparent; opacity: 0.85; }
 
    .notif-icon-wrap {
        width: 48px; height: 48px; border-radius: 14px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 20px;
    }
 
    .notif-content { flex: 1; min-width: 0; }
    .notif-title {
        font-size: 15px; font-weight: 600; color: #2d1f2c;
        margin-bottom: 4px; line-height: 1.4;
    }
    .notif-link { color: #2d1f2c; text-decoration: none; }
    .notif-link:hover { color: #E85588; }
    .notif-message {
        font-size: 13.5px; color: #9E7B95; margin-bottom: 6px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .notif-time { font-size: 12.5px; color: #B090A0; display: flex; align-items: center; gap: 4px; }
    .notif-date-full { color: #C0A0B0; font-size: 12px; }
 
    .notif-actions {
        display: flex; flex-direction: column; align-items: center;
        gap: 8px; flex-shrink: 0;
    }
    .notif-action-btn {
        width: 32px; height: 32px; border-radius: 50%; border: none;
        display: flex; align-items: center; justify-content: center;
        font-size: 15px; cursor: pointer; transition: all 0.15s;
        background: #E3F7EF; color: #2EAE7D;
    }
    .notif-action-btn:hover { background: #2EAE7D; color: #fff; transform: scale(1.1); }
 
    .read-tick { color: #2EAE7D; font-size: 18px; }
 
    .unread-dot {
        width: 10px; height: 10px; border-radius: 50%;
        background: linear-gradient(135deg, #FF6B9D, #E85588);
        display: inline-block;
        box-shadow: 0 0 6px rgba(232,85,136,0.5);
    }
 
    /* ── Empty State ── */
    .empty-notif-state {
        text-align: center; padding: 80px 20px;
        background: #fff; border-radius: 16px;
        border: 2px dashed #F0C0D8;
    }
    .empty-notif-icon {
        width: 90px; height: 90px; border-radius: 50%;
        background: #FDE0EC; color: #E85588; font-size: 38px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 20px;
    }
    .empty-notif-state h5 { color: #2d1f2c; font-weight: 700; font-size: 18px; margin-bottom: 8px; }
    .empty-notif-state p  { color: #9E7B95; max-width: 400px; margin: 0 auto; line-height: 1.6; }
 
    /* ── Alerts ── */
    .alert { border-radius: 12px; border: none; padding: 12px 18px; margin-bottom: 20px; }
    .alert-success { background: #E8F5E9; color: #1B5E20; }
    .alert-danger  { background: #FCE4EC; color: #880E4F; }
</style>
@endsection