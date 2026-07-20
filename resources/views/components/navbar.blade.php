<nav class="navbar navbar-expand-lg sticky-top" style="background: rgba(255,255,255,0.97); backdrop-filter: blur(10px); border-bottom: 1px solid #fce4ec; z-index: 999; box-shadow: 0 2px 20px rgba(233,30,140,0.06);">
    <div class="container">
        <!-- ===== BRAND NAME - 1 LINE ===== -->
        <a class="navbar-brand" href="{{ route('home') }}" style="display:flex; align-items:center; gap:12px; text-decoration:none; transition: all 0.3s ease; padding:4px 0;">
            
            <!-- Icon -->
            <div style="width:44px; height:44px; background:linear-gradient(135deg, #E91E8C, #C9A96E); border-radius:12px; display:flex; align-items:center; justify-content:center; box-shadow: 0 4px 14px rgba(233,30,140,0.25); transition: all 0.3s ease;">
                <i class="fas fa-spa" style="color:#fff; font-size:1.2rem;"></i>
            </div>
            
            <!-- Brand Text - 1 LINE -->
            <span style="font-family:'Playfair Display',serif; font-size:1.5rem; font-weight:800; letter-spacing:-0.5px;">
                <span style="color:#E91E8C;">Beauty</span>
                <span style="color:#C9A96E;"> Blush</span>
                <span style="color:#E91E8C;"> Salons</span>
            </span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" style="padding:8px 12px; border-radius:10px; background:linear-gradient(135deg, #fce4ec, #f5e6f5);">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto gap-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}" 
                       style="font-weight:500; font-size:0.92rem; color:#444; transition: all 0.3s ease; position:relative; padding:10px 18px; border-radius:8px;">
                        Home
                        <span class="nav-underline"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('salons.*') ? 'active' : '' }}" href="{{ route('salons.index') }}"
                       style="font-weight:500; font-size:0.92rem; color:#444; transition: all 0.3s ease; position:relative; padding:10px 18px; border-radius:8px;">
                        Salons
                        <span class="nav-underline"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}" href="{{ route('services.index') }}"
                       style="font-weight:500; font-size:0.92rem; color:#444; transition: all 0.3s ease; position:relative; padding:10px 18px; border-radius:8px;">
                        Services
                        <span class="nav-underline"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}"
                       style="font-weight:500; font-size:0.92rem; color:#444; transition: all 0.3s ease; position:relative; padding:10px 18px; border-radius:8px;">
                        About
                        <span class="nav-underline"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}"
                       style="font-weight:500; font-size:0.92rem; color:#444; transition: all 0.3s ease; position:relative; padding:10px 18px; border-radius:8px;">
                        Contact
                        <span class="nav-underline"></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
/* ===== NAVBAR UNDERLINE ANIMATION ===== */
.navbar .nav-link {
    position: relative;
    text-decoration: none;
}

.navbar .nav-underline {
    position: absolute;
    bottom: 4px;
    left: 50%;
    transform: translateX(-50%) scaleX(0);
    width: 60%;
    height: 2.5px;
    background: linear-gradient(90deg, #E91E8C, #C9A96E);
    border-radius: 10px;
    transition: all 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.navbar .nav-link:hover {
    color: #E91E8C !important;
    background: rgba(233, 30, 140, 0.05);
}

.navbar .nav-link:hover .nav-underline {
    transform: translateX(-50%) scaleX(1);
}

.navbar .nav-link.active {
    color: #E91E8C !important;
    font-weight: 600 !important;
    background: rgba(233, 30, 140, 0.05);
}

.navbar .nav-link.active .nav-underline {
    transform: translateX(-50%) scaleX(1);
}

/* Brand hover */
.navbar-brand:hover {
    transform: scale(1.02);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 991.98px) {
    .navbar .nav-link {
        padding: 12px 20px !important;
        border-radius: 10px !important;
        text-align: center;
    }
    .navbar .nav-underline {
        width: 30%;
        bottom: 2px;
    }
    .navbar-collapse {
        background: #fff;
        border-radius: 16px;
        padding: 15px 10px;
        margin-top: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        border: 1px solid #fce4ec;
    }
    .navbar-brand {
        padding: 0 !important;
    }
    .navbar-brand div:first-child {
        width: 38px !important;
        height: 38px !important;
    }
    .navbar-brand span {
        font-size: 1.2rem !important;
    }
}

@media (max-width: 576px) {
    .navbar-brand div:first-child {
        width: 34px !important;
        height: 34px !important;
    }
    .navbar-brand div:first-child i {
        font-size: 1rem !important;
    }
    .navbar-brand span {
        font-size: 1rem !important;
    }
}
</style>