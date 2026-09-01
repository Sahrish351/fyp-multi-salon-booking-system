{{-- ============================================================ --}}
{{-- FILE: resources/views/components/sidebar-client.blade.php    --}}
{{-- ============================================================ --}}
<div class="sidebar d-flex flex-column p-0" style="background: linear-gradient(180deg, #1A0A1E 0%, #7B1450 100%); border-right: 1px solid rgba(255,255,255,0.08); height: fit-content;">
    
    {{-- Logo Section (Single Line Clean Branding) --}}
    <div class="p-3 border-bottom" style="border-color: rgba(255,255,255,0.08) !important;">
        <a href="{{ route('home') }}" class="text-decoration-none">
            <h5 class="fw-bold mb-0 text-nowrap" style="font-family:'Playfair Display',serif; font-size: 1.05rem; letter-spacing: 0.3px;">
                <span style="color:#fff;">Beauty Blush</span> <span style="color:#FF6B9D;">Salons</span>
            </h5>
        </a>
        <small style="color: rgba(255,255,255,0.55); font-size:0.68rem; display: block; margin-top: 2px;">My Beauty Account</small>
    </div>

    {{-- User Info --}}
    <div class="p-3 border-bottom" style="border-color: rgba(255,255,255,0.08) !important;">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ Auth::user()->avatar_url }}" 
                 class="rounded-circle" 
                 width="38" 
                 height="38" 
                 alt="avatar" 
                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=FF6B9D&color=fff&size=100'"
                 style="border:2px solid #fff; object-fit:cover;">
            <div>
                <div style="color:#fff; font-size:0.85rem; font-weight:500;">{{ Auth::user()->name }}</div>
                <div style="color: rgba(255,255,255,0.55); font-size:0.7rem;">
                    <i class="fas fa-map-marker-alt me-1" style="color:#FF6B9D;"></i>{{ Auth::user()->city ?? 'Lahore' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Navigation Links --}}
    <div class="py-3">
        @php
            $clientLinks = [
                ['route' => 'client.dashboard', 'icon' => 'fa-home', 'label' => 'Dashboard'],
                ['route' => 'client.appointments.index', 'icon' => 'fa-calendar-check', 'label' => 'My Appointments'],
                ['route' => 'client.payments.index', 'icon' => 'fa-credit-card', 'label' => 'Payments'],
                ['route' => 'client.waitlist.index', 'icon' => 'fa-list-ol', 'label' => 'My Waitlist'],
                ['route' => 'client.favorites.index', 'icon' => 'fa-heart', 'label' => 'Saved Salons'],
                ['route' => 'client.reviews.index', 'icon' => 'fa-star', 'label' => 'My Reviews'],
                ['route' => 'client.complaints.index', 'icon' => 'fa-exclamation-circle', 'label' => 'Complaints'],
                ['route' => 'client.notifications.index', 'icon' => 'fa-bell', 'label' => 'Notifications'],
            ];
        @endphp

        @foreach($clientLinks as $link)
        <a href="{{ route($link['route']) }}"
           class="d-flex align-items-center gap-3 px-4 py-2 text-decoration-none mb-1 mx-2 rounded-3
                  {{ request()->routeIs($link['route'].'*') ? 'fw-semibold' : '' }}"
           style="{{ request()->routeIs($link['route'].'*') 
               ? 'background: linear-gradient(135deg, #FF6B9D, #E85588); color: #fff; box-shadow: 0 4px 15px rgba(232,85,136,0.35);' 
               : 'color: rgba(255,255,255,0.72);' }}
                  font-size:0.85rem;
                  transition: all 0.2s ease;"
           onmouseover="this.style.background='rgba(255,107,157,0.15)'; this.style.color='#fff';"
           onmouseout="this.style.background='{{ request()->routeIs($link['route'].'*') ? 'linear-gradient(135deg, #FF6B9D, #E85588)' : 'transparent' }}'; this.style.color='{{ request()->routeIs($link['route'].'*') ? '#fff' : 'rgba(255,255,255,0.72)' }}';">
            <i class="fas {{ $link['icon'] }}" style="width:18px; color: {{ request()->routeIs($link['route'].'*') ? '#ffffff' : 'rgba(255,255,255,0.72)' }};"></i>
            <span>{{ $link['label'] }}</span>
        </a>
        @endforeach

        <hr class="my-2 mx-3" style="border-color: rgba(255,255,255,0.12);">

        {{-- Visit Website --}}
        <a href="{{ url('/') }}"
           target="_blank"
           class="d-flex align-items-center gap-3 px-4 py-2 text-decoration-none mb-1 mx-2 rounded-3"
           style="color: rgba(255,255,255,0.72); font-size:0.85rem; transition: all 0.2s ease;"
           onmouseover="this.style.background='rgba(255,107,157,0.15)'; this.style.color='#fff';"
           onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.72)';">
            <i class="fas fa-globe" style="width:18px; color: #FF6B9D;"></i>
            <span>Visit Website</span>
        </a>

        {{-- My Profile --}}
        <a href="{{ route('client.profile.index') }}"
           class="d-flex align-items-center gap-3 px-4 py-2 text-decoration-none mb-1 mx-2 rounded-3
                  {{ request()->routeIs('client.profile.index*') ? 'fw-semibold' : '' }}"
           style="{{ request()->routeIs('client.profile.index*') 
               ? 'background: linear-gradient(135deg, #FF6B9D, #E85588); color: #fff; box-shadow: 0 4px 15px rgba(232,85,136,0.35);' 
               : 'color: rgba(255,255,255,0.72);' }}
                  font-size:0.85rem;
                  transition: all 0.2s ease;"
           onmouseover="this.style.background='rgba(255,107,157,0.15)'; this.style.color='#fff';"
           onmouseout="this.style.background='{{ request()->routeIs('client.profile.index*') ? 'linear-gradient(135deg, #FF6B9D, #E85588)' : 'transparent' }}'; this.style.color='{{ request()->routeIs('client.profile.index*') ? '#fff' : 'rgba(255,255,255,0.72)' }}';">
            <i class="fas fa-user-edit" style="width:18px; color: {{ request()->routeIs('client.profile.index*') ? '#ffffff' : 'rgba(255,255,255,0.72)' }};"></i>
            <span>My Profile</span>
        </a>

        {{-- Logout Button --}}
        <div class="px-2 mt-2 pb-2">
            <form action="{{ route('logout') }}" method="POST" id="sidebar-logout-form">
                @csrf
                <button type="submit" 
                        class="d-flex align-items-center gap-3 w-100 px-4 py-2 border-0 rounded-3 text-start" 
                        style="color: #FF6B9D; background: rgba(255,107,157,0.12); font-size: 0.85rem; font-weight: 500; transition: all 0.2s ease;" 
                        onmouseover="this.style.background='rgba(255,107,157,0.25)'; this.style.color='#fff';" 
                        onmouseout="this.style.background='rgba(255,107,157,0.12)'; this.style.color='#FF6B9D';">
                    <i class="fas fa-sign-out-alt" style="width:18px;"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</div>