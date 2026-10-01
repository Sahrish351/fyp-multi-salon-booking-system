<nav class="navbar navbar-expand-lg sticky-top" style="background: rgba(255,255,255,0.97); backdrop-filter: blur(10px); border-bottom: 1px solid #fce4ec; z-index: 999; box-shadow: 0 2px 20px rgba(233,30,140,0.06);">
    <div class="container-fluid nav-wrap">

        <a class="navbar-brand" href="{{ route('home') }}" style="display:flex; align-items:center; text-decoration:none; transition: all 0.3s ease; padding:4px 0;">
            <img src="{{ asset('images/full-logo.png') }}" alt="Beauty Blush Salons" class="brand-logo-img">
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" style="padding:8px 12px; border-radius:10px; background:linear-gradient(135deg, #fce4ec, #f5e6f5);">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto gap-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}"
                       style="font-weight:500; font-size:0.92rem; color:#444; transition: color 0.3s ease; position:relative; padding:10px 18px; border-radius:8px;">
                        Home
                        <span class="nav-underline"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('salons.*') ? 'active' : '' }}" href="{{ route('salons.index') }}"
                       style="font-weight:500; font-size:0.92rem; color:#444; transition: color 0.3s ease; position:relative; padding:10px 18px; border-radius:8px;">
                        Salons
                        <span class="nav-underline"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}" href="{{ route('services.index') }}"
                       style="font-weight:500; font-size:0.92rem; color:#444; transition: color 0.3s ease; position:relative; padding:10px 18px; border-radius:8px;">
                        Services
                        <span class="nav-underline"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}"
                       style="font-weight:500; font-size:0.92rem; color:#444; transition: color 0.3s ease; position:relative; padding:10px 18px; border-radius:8px;">
                        About
                        <span class="nav-underline"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}"
                       style="font-weight:500; font-size:0.92rem; color:#444; transition: color 0.3s ease; position:relative; padding:10px 18px; border-radius:8px;">
                        Contact
                        <span class="nav-underline"></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
/* ===== FIX 1: scrollbar ki jagah hamesha reserve (navbar na hile) ===== */
html {
    overflow-y: scroll;
    scrollbar-gutter: stable;
}

/* ===== LOGO LEFT POSITION (change --nav-pad to move logo more left/right) ===== */
.navbar .nav-wrap {
    --nav-pad: 40px;
    padding-left: var(--nav-pad);
    padding-right: var(--nav-pad);
}

/* ===== FIX 2: navbar ki apni fixed font (page ki body font ka asar nahi) ===== */
.navbar,
.navbar .nav-link {
    font-family: 'Poppins', 'Segoe UI', system-ui, -apple-system, Arial, sans-serif !important;
}

.navbar .nav-link {
    position: relative;
    text-decoration: none;
    background: transparent !important;
    outline: none;
    box-shadow: none;
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
    transition: transform 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.navbar .nav-link:hover,
.navbar .nav-link:focus,
.navbar .nav-link:active {
    color: #E91E8C !important;
    background: transparent !important;
    outline: none;
    box-shadow: none;
}

.navbar .nav-link:hover .nav-underline,
.navbar .nav-link:focus .nav-underline {
    transform: translateX(-50%) scaleX(1);
}

/* ===== FIX 3: active link bold nahi hota (width same rehti hai) ===== */
.navbar .nav-link.active {
    color: #E91E8C !important;
    font-weight: 500 !important;
    background: transparent !important;
}

.navbar .nav-link.active .nav-underline {
    transform: translateX(-50%) scaleX(1);
}

/* ===== BRAND LOGO ===== */
.navbar-brand .brand-logo-img {
    height: 58px;
    width: auto;
    object-fit: contain;
    display: block;
}

.navbar-brand:hover {
    transform: scale(1.02);
}

@media (max-width: 991.98px) {
    .navbar .nav-wrap {
        --nav-pad: 20px;
    }
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
    .navbar-brand .brand-logo-img {
        height: 46px;
    }
}

@media (max-width: 576px) {
    .navbar .nav-wrap {
        --nav-pad: 16px;
    }
    .navbar-brand .brand-logo-img {
        height: 40px;
    }
}
</style>