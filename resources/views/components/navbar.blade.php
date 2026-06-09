
<nav class="navbar navbar-expand-lg sticky-top" style="background: rgba(255,255,255,0.97); backdrop-filter: blur(10px); border-bottom: 1px solid #fce4ec; z-index: 999;">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="{{ route('home') }}">
            <span style="color:#E91E8C; font-family:'Playfair Display',serif;">Glam</span><span style="color:#C9A96E; font-family:'Playfair Display',serif;">ora</span>
        </a>
 
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
 
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto gap-1">
                <li class="nav-item"><a class="nav-link px-3 fw-500 {{ request()->routeIs('home') ? 'active text-danger' : '' }}" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link px-3 {{ request()->routeIs('salons.*') ? 'active text-danger' : '' }}" href="{{ route('salons.index') }}">Salons</a></li>
                <li class="nav-item"><a class="nav-link px-3 {{ request()->routeIs('services.*') ? 'active text-danger' : '' }}" href="{{ route('services.index') }}">Services</a></li>
                <!-- <li class="nav-item"><a class="nav-link px-3 {{ request()->routeIs('stylists.*') ? 'active text-danger' : '' }}" href="{{ route('stylists.index') }}">Stylists</a></li> -->
                <!-- <li class="nav-item"><a class="nav-link px-3 {{ request()->routeIs('gallery.*') ? 'active text-danger' : '' }}" href="{{ route('gallery.index') }}">Gallery</a></li> -->
                <!-- <li class="nav-item"><a class="nav-link px-3" href="{{ route('gallery.index') }}">Gallery</a></li> -->
                <li class="nav-item"><a class="nav-link px-3 {{ request()->routeIs('about') ? 'active text-danger' : '' }}" href="{{ route('about') }}">About</a></li>
                <li class="nav-item"><a class="nav-link px-3 {{ request()->routeIs('contact') ? 'active text-danger' : '' }}" href="{{ route('contact') }}">Contact</a></li>
            </ul>
 
            <div class="d-flex align-items-center gap-2">
             
                <button class="btn btn-sm btn-outline-secondary rounded-circle" id="themeToggle" style="width:36px;height:36px;">
                    <i class="fas fa-moon" style="font-size:0.8rem;"></i>
                </button>
 
                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-danger">Admin Panel</a>
                    @elseif(Auth::user()->isOwner())
                        <a href="{{ route('owner.dashboard') }}" class="btn btn-sm btn-outline-warning">My Salons</a>
                    @else
                        <a href="{{ route('client.dashboard') }}" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-user me-1"></i>My Account
                        </a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-danger">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-danger">Login</a>
                    <a href="{{ route('register.client') }}" class="btn btn-sm btn-danger px-3">Book Now</a>
                @endauth
            </div>
        </div>
    </div>
</nav>