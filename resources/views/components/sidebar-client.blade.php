{{-- ============================================================ --}}
{{-- FILE: resources/views/components/sidebar-client.blade.php --}}
{{-- ============================================================ --}}
<div class="sidebar d-flex flex-column bg-white" style="border-right:1px solid #fce4ec;">
    <div class="p-4 border-bottom" style="border-color:#fce4ec !important;">
        <a href="{{ route('home') }}" class="text-decoration-none">
            <h4 class="fw-bold mb-0" style="font-family:'Playfair Display',serif;">
                <span style="color:#E91E8C;">Glam</span><span style="color:#C9A96E;">ora</span>
            </h4>
        </a>
        <small style="color:#aaa;font-size:0.7rem;">My Beauty Account</small>
    </div>
 
    <div class="p-3 border-bottom" style="border-color:#fce4ec !important;">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ Auth::user()->avatar_url }}" class="rounded-circle" width="40" height="40" alt="avatar" style="border:2px solid #E91E8C;object-fit:cover;">
            <div>
                <div style="color:#333;font-size:0.85rem;font-weight:500;">{{ Auth::user()->name }}</div>
                <div style="color:#aaa;font-size:0.7rem;"><i class="fas fa-map-marker-alt me-1" style="color:#E91E8C;"></i>{{ Auth::user()->city ?? 'Pakistan' }}</div>
            </div>
        </div>
    </div>
 
    <nav class="flex-grow-1 py-3 overflow-auto">
        @php
            $clientLinks = [
                ['route' => 'client.dashboard', 'icon' => 'fa-home', 'label' => 'Dashboard'],
                ['route' => 'client.appointments.index', 'icon' => 'fa-calendar-check', 'label' => 'My Appointments'],
                ['route' => 'client.waitlist.index', 'icon' => 'fa-list-ol', 'label' => 'My Waitlist'],
                ['route' => 'client.favorites.index', 'icon' => 'fa-heart', 'label' => 'Saved Salons'],
                ['route' => 'client.complaints.index', 'icon' => 'fa-exclamation-circle', 'label' => 'Complaints'],
                ['route' => 'client.notifications.index', 'icon' => 'fa-bell', 'label' => 'Notifications'],
                ['route' => 'client.profile.index', 'icon' => 'fa-user-edit', 'label' => 'My Profile'],
            ];
        @endphp
 
        @foreach($clientLinks as $link)
        <a href="{{ route($link['route']) }}"
           class="d-flex align-items-center gap-3 px-4 py-2 text-decoration-none mb-1 mx-2 rounded-3
                  {{ request()->routeIs($link['route'].'*') ? 'fw-semibold' : 'text-secondary' }}"
           style="{{ request()->routeIs($link['route'].'*') ? 'background:#fff0f7;color:#E91E8C;border-left:3px solid #E91E8C;' : '' }}
                  font-size:0.85rem;">
            <i class="fas {{ $link['icon'] }}" style="width:18px; {{ request()->routeIs($link['route'].'*') ? 'color:#E91E8C' : '' }}"></i>
            <span>{{ $link['label'] }}</span>
        </a>
        @endforeach
 
        <!-- <div class="px-3 mt-3">
            <a href="{{ route('salons.index') }}" class="btn w-100 btn-sm fw-semibold" style="background:linear-gradient(135deg,#E91E8C,#c2185b);color:#fff;border-radius:10px;">
                <i class="fas fa-calendar-plus me-2"></i>Book New Appointment
            </a>
        </div> -->
    </nav>
 
    <div class="p-3 border-top" style="border-color:#fce4ec !important;">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm w-100 text-start" style="color:#E91E8C;background:#fff0f7;border:none;">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </button>
        </form>
    </div>
</div>