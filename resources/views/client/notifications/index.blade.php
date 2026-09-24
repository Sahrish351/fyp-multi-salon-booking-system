@extends('layouts.client')
 
@section('title', 'Notifications — Beauty Blush Salons')
 
@push('styles')
<style>
    
    .notif-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1.5rem;
        border-bottom: 1px solid #fce4ec;
    }
    .notif-icon {
        width: 44px;
        height: 44px;
        background: #fff0f7;
        border: 2px solid #FF6B9D;
    }
    .notif-body {
        flex: 1 1 0;
        min-width: 0;                 
        overflow-wrap: anywhere;      
    }
    .notif-dot {
        width: 10px;
        height: 10px;
        flex-shrink: 0;
    }
    .notif-dot.unread { background: #FF6B9D; margin-top: 8px; }
    .notif-action { flex-shrink: 0; }
 
    /* ── Mobile ── */
    @media (max-width: 575.98px) {
        .notif-item {
            flex-wrap: wrap;          
            gap: 0.6rem 0.75rem;
            padding: 1rem;
        }
        .notif-icon { width: 38px; height: 38px; }
        .notif-icon i { font-size: 0.85rem !important; }
        .notif-dot.read { display: none; }
        .notif-action {
            flex: 0 0 100%;
            padding-left: calc(38px + 0.75rem);  
        }
    }
</style>
@endpush
 
@section('content')
 
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#333;font-family:'Playfair Display',serif;">
            <i class="fas fa-bell me-2" style="color:#FF6B9D;"></i>Notifications
        </h4>
        <p style="color:#aaa;font-size:0.85rem;margin:0;">
            {{ Auth::user()->unreadNotifications->count() }} unread notifications
        </p>
    </div>
    @if(Auth::user()->unreadNotifications->count() > 0)
    <form action="{{ route('client.notifications.read-all') }}" method="POST">
        @csrf
        <button class="btn btn-sm rounded-pill px-4" style="background:#fff0f7;color:#FF6B9D;border:1px solid #fce4ec;font-weight:600;transition:all 0.3s;">
            <i class="fas fa-check-double me-1"></i>Mark All Read
        </button>
    </form>
    @endif
</div>
 

<div class="bg-white rounded-4 overflow-hidden" style="border:1px solid #fce4ec;">
    @forelse(Auth::user()->notifications as $notif)
    <div class="notif-item" style="{{ !$notif->read_at ? 'background:#fff8fb;' : '' }}">
        
        
        <div class="notif-icon rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
            @if($notif->data['icon'] ?? false)
                <i class="fas {{ $notif->data['icon'] }}" style="color:#FF6B9D;font-size:1rem;"></i>
            @else
                <i class="fas fa-bell" style="color:#FF6B9D;font-size:1rem;"></i>
            @endif
        </div>
        
       
        <div class="notif-body">
            {{-- Title --}}
            @if($notif->data['title'] ?? false)
            <div style="color:#333;font-size:0.9rem;font-weight:{{ !$notif->read_at ? '600' : '400' }};">
                {{ $notif->data['title'] }}
            </div>
            @endif
            
            {{-- Message --}}
            <div style="color:#555;font-size:0.85rem;margin-top:2px;{{ !$notif->read_at ? 'font-weight:500;' : '' }}">
                {{ $notif->data['message'] ?? 'New notification' }}
            </div>
            
            {{-- Description (if any) --}}
            @if(!empty($notif->data['description']))
            <div style="color:#888;font-size:0.8rem;margin-top:2px;">{{ $notif->data['description'] }}</div>
            @endif
            
            {{-- Time --}}
            <div style="color:#aaa;font-size:0.75rem;margin-top:4px;">
                <i class="far fa-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}
            </div>
        </div>
        
        
        @if(!$notif->read_at)
        <div class="notif-dot unread rounded-circle"></div>
        @else
        <div class="notif-dot read"></div>
        @endif
        
      
        @if(($notif->data['action_url'] ?? $notif->data['link'] ?? false))
        <div class="notif-action">
            <a href="{{ route('client.notifications.read', $notif->id) }}" class="btn btn-sm rounded-pill" style="background:#fff0f7;color:#FF6B9D;border:1px solid #FF6B9D;font-weight:600;padding:4px 16px;text-decoration:none;transition:all 0.3s;">
                View
            </a>
        </div>
        @endif
    </div>
    @empty
    
    
    <div class="text-center py-5">
        <i class="fas fa-bell-slash fa-4x mb-3" style="color:rgba(255, 107, 157,0.2);"></i>
        <h5 style="color:#333;font-weight:600;">No notifications yet</h5>
        <p style="color:#aaa;font-size:0.9rem;max-width:400px;margin:0 auto;">
            You'll receive notifications about bookings, payments, and waitlist updates here
        </p>
    </div>
    @endforelse
</div>
 

@if(method_exists(Auth::user()->notifications, 'links') && Auth::user()->notifications->hasPages())
<div class="mt-4 d-flex justify-content-center">
    {{ Auth::user()->notifications->links() }}
</div>
@endif
 
@endsection
 