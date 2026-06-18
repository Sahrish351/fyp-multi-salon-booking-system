{{-- ============================================================ --}}
{{-- FILE: resources/views/client/notifications/index.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.client')
@section('title', 'Notifications — Glamora')
@section('content')
 
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#333;font-family:'Playfair Display',serif;"><i class="fas fa-bell me-2" style="color:#E91E8C;"></i>Notifications</h4>
        <p style="color:#aaa;font-size:0.85rem;margin:0;">{{ Auth::user()->unreadNotifications->count() }} unread notifications</p>
    </div>
    @if(Auth::user()->unreadNotifications->count() > 0)
    <form action="{{ route('client.notifications.mark-read') }}" method="POST">
        @csrf
        <button class="btn btn-sm rounded-pill px-4" style="background:#fff0f7;color:#E91E8C;border:1px solid #fce4ec;font-weight:600;">
            <i class="fas fa-check-double me-1"></i>Mark All Read
        </button>
    </form>
    @endif
</div>
 
<div class="bg-white rounded-4 overflow-hidden" style="border:1px solid #fce4ec;">
    @forelse(Auth::user()->notifications as $notif)
    <div class="d-flex align-items-start gap-3 p-4" style="border-bottom:1px solid #fce4ec;{{ !$notif->read_at ? 'background:#fff8fb;' : '' }}">
        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:44px;height:44px;background:{{ !$notif->read_at ? 'linear-gradient(135deg,#E91E8C,#c2185b)' : '#f5f5f5' }};">
            <i class="fas fa-bell" style="color:{{ !$notif->read_at ? '#fff' : '#aaa' }};font-size:0.9rem;"></i>
        </div>
        <div class="flex-grow-1">
            <div style="color:#333;font-size:0.9rem;font-weight:{{ !$notif->read_at ? '600' : '400' }};">
                {{ $notif->data['message'] ?? 'New notification' }}
            </div>
            @if(!empty($notif->data['description']))
            <div style="color:#888;font-size:0.8rem;margin-top:2px;">{{ $notif->data['description'] }}</div>
            @endif
            <div style="color:#aaa;font-size:0.75rem;margin-top:4px;">{{ $notif->created_at->diffForHumans() }}</div>
        </div>
        @if(!$notif->read_at)
        <div class="rounded-circle flex-shrink-0" style="width:10px;height:10px;background:#E91E8C;margin-top:8px;"></div>
        @endif
    </div>
    @empty
    <div class="text-center py-5">
        <i class="fas fa-bell-slash fa-4x mb-3" style="color:rgba(233,30,140,0.2);"></i>
        <h5 style="color:#333;">No notifications yet</h5>
        <p style="color:#aaa;">You'll receive notifications about bookings, payments, and waitlist updates here</p>
    </div>
    @endforelse
</div>
@endsection