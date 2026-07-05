{{-- ============================================================ --}}
{{-- FILE: resources/views/components/sidebar-client.blade.php --}}
{{-- ============================================================ --}}
<div class="sidebar d-flex flex-column" style="background: linear-gradient(180deg, #c2185b 0%, #880e4f 100%); border-right: 1px solid rgba(255,255,255,0.1);">
    
    {{-- Logo Section --}}
    <div class="p-4 border-bottom" style="border-color: rgba(255,255,255,0.1) !important;">
        <a href="{{ route('home') }}" class="text-decoration-none">
            <h4 class="fw-bold mb-0" style="font-family:'Playfair Display',serif;">
                <span style="color:#fff;">Glam</span><span style="color:#f8bbd0;">ora</span>
            </h4>
        </a>
        <small style="color: rgba(255,255,255,0.6); font-size:0.7rem;">My Beauty Account</small>
    </div>

    {{-- User Info --}}
    <div class="p-3 border-bottom" style="border-color: rgba(255,255,255,0.1) !important;">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ Auth::user()->avatar_url }}" class="rounded-circle" width="40" height="40" alt="avatar" style="border:2px solid #fff; object-fit:cover;">
            <div>
                <div style="color:#fff; font-size:0.85rem; font-weight:500;">{{ Auth::user()->name }}</div>
                <div style="color: rgba(255,255,255,0.6); font-size:0.7rem;">
                    <i class="fas fa-map-marker-alt me-1" style="color:#f8bbd0;"></i>{{ Auth::user()->city ?? 'Pakistan' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Navigation Links --}}
    <nav class="flex-grow-1 py-3 overflow-auto">
        @php
            $clientLinks = [
                ['route' => 'client.dashboard', 'icon' => 'fa-home', 'label' => 'Dashboard'],
                ['route' => 'client.appointments.index', 'icon' => 'fa-calendar-check', 'label' => 'My Appointments'],
                ['route' => 'client.waitlist.index', 'icon' => 'fa-list-ol', 'label' => 'My Waitlist'],
                ['route' => 'client.favorites.index', 'icon' => 'fa-heart', 'label' => 'Saved Salons'],
                ['route' => 'client.reviews.index', 'icon' => 'fa-star', 'label' => 'My Reviews'],
                ['route' => 'client.complaints.index', 'icon' => 'fa-exclamation-circle', 'label' => 'Complaints'],
                ['route' => 'client.notifications.index', 'icon' => 'fa-bell', 'label' => 'Notifications'],
                ['route' => 'client.profile.index', 'icon' => 'fa-user-edit', 'label' => 'My Profile'],
            ];
        @endphp

        @foreach($clientLinks as $link)
        <a href="{{ route($link['route']) }}"
           class="d-flex align-items-center gap-3 px-4 py-2 text-decoration-none mb-1 mx-2 rounded-3
                  {{ request()->routeIs($link['route'].'*') ? 'fw-semibold' : '' }}"
           style="{{ request()->routeIs($link['route'].'*') 
               ? 'background: rgba(255,255,255,0.15); color: #fff; border-left: 3px solid #f8bbd0;' 
               : 'color: rgba(255,255,255,0.8);' }}
                  font-size:0.85rem;
                  transition: all 0.2s ease;"
           onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='#fff';"
           onmouseout="this.style.background='{{ request()->routeIs($link['route'].'*') ? 'rgba(255,255,255,0.15)' : 'transparent' }}'; this.style.color='{{ request()->routeIs($link['route'].'*') ? '#fff' : 'rgba(255,255,255,0.8)' }}';">
            <i class="fas {{ $link['icon'] }}" style="width:18px; color: {{ request()->routeIs($link['route'].'*') ? '#f8bbd0' : 'rgba(255,255,255,0.6)' }};"></i>
            <span>{{ $link['label'] }}</span>
        </a>
        @endforeach
    </nav>

    {{-- Logout Button --}}
    <div class="p-3 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm w-100 text-start" style="color: #fff; background: rgba(255,255,255,0.1); border: none; border-radius: 10px; padding: 10px 16px; transition: all 0.2s ease;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </button>
        </form>
    </div>
</div>