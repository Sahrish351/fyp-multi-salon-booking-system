Home.blede.php

<!D

OCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Glamora — Book Premium Beauty Services in Pakistan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; color: #1a1a1a; background: #fff; -webkit-font-smoothing: antialiased; }
    a { text-decoration: none; color: inherit; }
 
    .g-nav {
        background: #fff;
        border-bottom: 1px solid #ebebeb;
        padding: 0 32px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 1000;
    }
    .g-nav .brand {
        font-size: 1.5rem;
        font-weight: 900;
        letter-spacing: -1px;
        background: linear-gradient(135deg, #E91E8C, #9333ea);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-style: italic;
    }
    .g-nav .nav-right { display: flex; align-items: center; gap: 8px; }
    .btn-nav-ghost {
    background: transparent;
    border: 1.5px solid #1a1a1a;
    color: #1a1a1a;
    font-size: 0.88rem;
    font-weight: 600;
    padding: 8px 18px;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.btn-nav-ghost:hover { 
    background: #1a1a1a !important; 
    color: #fff !important; 
}
    .btn-nav-outline {
        background: #fff;
        border: 1.5px solid #1a1a1a;
        color: #1a1a1a;
        font-size: 0.88rem;
        font-weight: 700;
        padding: 8px 18px;
        border-radius: 50px;
        cursor: pointer;
    }
    .btn-nav-outline:hover { background: #1a1a1a; color: #fff; }
    .btn-nav-menu {
        background: #fff;
        border: 1.5px solid #e0e0e0;
        color: #1a1a1a;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 50px;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        position: relative;
    }
    .btn-nav-menu:hover {  background: #1a1a1a; color: #fff; }
 
    /* Menu Dropdown - Right side small box */
    .menu-dropdown {
        position: absolute;
        top:100%;
        right: 0;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.12);
        border: 1px solid #eee;
        padding: 10px 0;
        z-index: 9999;
        min-width: 200px;
        display: none;
    }
    .menu-dropdown.show {
        display: block;
    }
    .menu-dropdown a {
        display: block;
        padding: 10px 20px;
        font-size: 0.85rem;
        font-weight: 500;
        color: #333;
        text-decoration: none;
        transition: all 0.2s;
    }
    .menu-dropdown a:hover {
        background: #f5f5f5;
        color: #E91E8C;
    }
 
    .hero {
        background: linear-gradient(145deg, #ede8f5 0%, #f5e6f5 20%, #fce8f3 50%, #fdf5fb 80%, #fff 100%);
        padding: 100px 32px 140px;
        text-align: center;
        position: relative;
        overflow: visible;
    }
    .hero::before {
        content: '';
        position: absolute;
        width: 700px;
        height: 700px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(233,30,140,0.07), transparent 70%);
        top: -200px;
        left: 50%;
        transform: translateX(-50%);
        pointer-events: none;
    }
    .hero h1 {
        font-size: clamp(2.8rem, 5.5vw, 4.5rem);
        font-weight: 900;
        letter-spacing: -2.5px;
        color: #1a1a1a;
        line-height: 1.05;
        margin-top: 50px;
        margin-bottom: 16px;
        white-space: nowrap;
    }
    @media(max-width: 768px) { .hero h1 { white-space: normal; font-size: 2.4rem; } }
    .hero p {
        font-size: 1.2rem;
        color: #666;
        margin-bottom: 40px;
        line-height: 1.65;
    }
 
    .search-pill {
        display: flex;
        align-items: center;
        background: #fff;
        border-radius: 60px;
        box-shadow: 0 2px 24px rgba(0,0,0,0.13), 0 0 0 1px rgba(0,0,0,0.06);
        max-width: 900px;
        margin: 0 auto;
        padding: 5px 5px 5px 20px;
        position: relative;
        gap: 0;
    }
    .search-pill .sp-segment {
        display: flex;
        align-items: center;
        flex: 1;
        min-width: 0;
        position: relative;
        cursor: pointer;
        padding: 0 4px;
    }
    .search-pill .sp-divider {
        width: 0;
        height: 24px;
        background: none;
        margin: 0;
    }
    .search-pill .sp-segment:hover::before {
        content: '';
        position: absolute;
        inset: -8px;
        background: rgba(0,0,0,0.04);
        border-radius: 50px;
        pointer-events: none;
    }
    .search-pill .sp-icon { color: #999; font-size: 0.88rem; flex-shrink: 0; margin-right: 8px; }
    .search-pill .sp-field {
        border: none;
        outline: none;
        font-size: 0.92rem;
        color: #1a1a1a;
        background: transparent;
        width: 100%;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
    }
    .search-pill .sp-field::placeholder { color: #999; }
    .btn-search-pill {
        background: #1a1a1a;
        color: #fff;
        border: none;
        border-radius: 50px;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
        cursor: pointer;
    }
    .btn-search-pill:hover { background: #E91E8C; transform: scale(1.05); }
    .hero-count { font-size: 0.92rem; color: #666; margin-top: 22px; }
    .hero-count strong { color: #1a1a1a; font-weight: 700; }
 
    .sp-dropdown {
        position: absolute;
        top: 100%;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.15);
        border: 1px solid #f0f0f0;
        padding: 1rem;
        z-index: 9999;
        display: none;
    }
    .sp-dropdown.show { display: block; }
    
    #treatmentsDD {
        left: 0;
        right: auto;
        min-width: 260px;
        max-height: 400px;
        overflow-y: auto;
    }
    
    #locationDD {
        left: 50%;
        transform: translateX(-50%);
        right: auto;
        min-width: 260px;
        max-height: none;
        overflow-y: visible;
    }
    
    #timeDD {
        right: 0;
        left: auto;
        min-width: 380px;
        max-width: 420px;
        max-height: none;
        overflow-y: visible;
    }
    
    .sp-dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 10px;
        cursor: pointer;
        transition: background .15s;
        font-size: 0.88rem;
        color: #333;
    }
    .sp-dropdown-item:hover { background: #f5f5f5; }
    .sp-dropdown-item i { color: #E91E8C; width: 16px; }
 
    .flatpickr-calendar.inline {
        width: 100% !important;
        box-shadow: none !important;
        border: none !important;
    }
    .flatpickr-calendar.inline .flatpickr-days { width: 100% !important; }
    .flatpickr-calendar.inline .dayContainer { width: 100% !important; justify-content: center; }
    .flatpickr-day {
        width: 34px !important;
        max-width: 34px !important;
        height: 34px !important;
        line-height: 34px !important;
        margin: 2px !important;
        border-radius: 50% !important;
    }
    .flatpickr-day.selected, .flatpickr-day.selected:hover {
        background: #E91E8C !important;
        border-color: #E91E8C !important;
    }
 
    .time-option-btn {
        border: 1.5px solid #e0e0e0;
        border-radius: 50px;
        padding: 5px 14px;
        font-size: 0.78rem;
        font-weight: 500;
        background: #fff;
        cursor: pointer;
    }
    .time-option-btn:hover {
        border-color: #E91E8C;
        color: #E91E8C;
    }
 
    .g-section { padding: 44px 0; }
    .g-section-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 32px;
        margin-bottom: 20px;
    }
    .g-section-head h2 { font-size: 1.5rem; font-weight: 800; color: #1a1a1a; letter-spacing: -0.3px; }
    .see-all { font-size: 0.85rem; font-weight: 600; color: #555; display: flex; align-items: center; gap: 5px; }
    .see-all:hover { color: #E91E8C; }
 
    .slider-outer { position: relative; }
    .slider-scroll-area {
        overflow-x: auto;
        scrollbar-width: none;
        padding: 4px 32px 16px;
        scroll-behavior: smooth;
    }
    .slider-scroll-area::-webkit-scrollbar { display: none; }
    .slider-track {
        display: grid;
        grid-auto-flow: column;
        grid-auto-columns: calc((100% - 96px) / 4);
        gap: 16px;
    }
    @media(max-width: 1200px) { .slider-track { grid-auto-columns: calc((100% - 80px) / 3); } }
    @media(max-width: 768px)  { .slider-track { grid-auto-columns: calc((100% - 48px) / 2); } }
    @media(max-width: 576px)  { .slider-track { grid-auto-columns: 75vw; } }
 
    .slider-arrow-btn {
        position: absolute;
        top: 40%;
        transform: translateY(-50%);
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #fff;
        border: 1.5px solid #d8d8d8;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        box-shadow: 0 2px 16px rgba(0,0,0,0.14);
    }
    .slider-arrow-btn:hover { background: #1a1a1a; color: #fff; border-color: #1a1a1a; }
    .slider-arrow-btn.left { left: 8px; }
    .slider-arrow-btn.right { right: 8px; }
 
    .salon-card { cursor: pointer; display: block; }
    .salon-card .sc-img {
        width: 100%;
        aspect-ratio: 4/3;
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        background: #f0f0f0;
    }
    .salon-card .sc-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .35s;
        display: block;
    }
    .salon-card:hover .sc-img img { transform: scale(1.05); }
    .salon-card .sc-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(255,255,255,0.95);
        font-size: 0.72rem;
        font-weight: 600;
        color: #333;
        padding: 4px 10px;
        border-radius: 20px;
        backdrop-filter: blur(6px);
        border: 1px solid rgba(0,0,0,0.06);
    }
    .salon-card .sc-fav {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: rgba(255,255,255,0.92);
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(6px);
        cursor: pointer;
    }
    .salon-card .sc-fav:hover { background: #fff; transform: scale(1.1); }
    .salon-card .sc-fav i { color: #ccc; font-size: 0.85rem; }
    .salon-card .sc-fav:hover i { color: #E91E8C; }
    .salon-card .sc-body { padding: 12px 0 0; }
    .salon-card .sc-name-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 3px;
    }
    .salon-card .sc-name {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1a1a1a;
        display: flex;
        align-items: center;
        gap: 4px;
        line-height: 1.3;
        flex: 1;
        min-width: 0;
    }
    .salon-card .sc-name .vc { color: #7c3aed; font-size: 0.78rem; flex-shrink: 0; }
    .salon-card .sc-rating-inline {
        display: flex;
        align-items: center;
        gap: 3px;
        font-size: 0.85rem;
        font-weight: 700;
        color: #1a1a1a;
        flex-shrink: 0;
    }
    .salon-card .sc-rating-inline .star { color: #ffc107; font-size: 0.8rem; }
    .salon-card .sc-addr { font-size: 0.8rem; color: #888; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .salon-card .sc-meta { font-size: 0.8rem; color: #888; }
 
    .rev-card {
        background: #f5f5f5;
        border-radius: 20px;
        padding: 1.75rem;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: transform .2s;
    }
    .rev-card:hover { transform: translateY(-4px); }
    .rev-card .rc-stars { color: #ffc107; font-size: 1.1rem; letter-spacing: 1px; margin-bottom: 10px; }
    .rev-card .rc-title { font-size: 1rem; font-weight: 700; color: #1a1a1a; margin-bottom: 8px; }
    .rev-card .rc-text { font-size: 0.87rem; color: #666; line-height: 1.7; flex-grow: 1; }
    .rev-card .rc-reviewer {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 20px;
        padding-top: 16px;
        border-top: 1px solid #e8e8e8;
    }
    .rev-card .rc-av {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.88rem;
        color: #fff;
        flex-shrink: 0;
    }
    .rev-card .rc-name { font-weight: 700; font-size: 0.88rem; color: #1a1a1a; }
    .rev-card .rc-loc { font-size: 0.78rem; color: #888; }
 
    .stats-sec { padding: 80px 32px; text-align: center; background: #1a1a1a; }
    .stats-sec .st-title { font-size: clamp(1.4rem,3vw,2rem); font-weight: 800; color: #fff; margin-bottom: 8px; }
    .stats-sec .st-sub { font-size: 0.92rem; color: #aaa; margin-bottom: 44px; }
    .stats-sec .big-num { font-size: clamp(3.5rem,9vw,6.5rem); font-weight: 900; color: #E91E8C; letter-spacing: -4px; margin-bottom: 8px; }
    .stats-sec .big-lbl { font-size: 0.95rem; color: #aaa; margin-bottom: 50px; }
    .stats-sec .mini-stats { display: flex; justify-content: center; gap: 80px; flex-wrap: wrap; }
    .stats-sec .ms-num { font-size: 2rem; font-weight: 900; color: #fff; }
    .stats-sec .ms-lbl { font-size: 0.82rem; color: #aaa; margin-top: 2px; }
 
    .city-sec { background: #fafafa; padding: 60px 32px; }
    .city-tabs { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 30px; }
    .city-tab-btn {
        border: 1.5px solid #e0e0e0;
        border-radius: 50px;
        padding: 8px 18px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #555;
        background: #fff;
        cursor: pointer;
    }
    .city-tab-btn.active, .city-tab-btn:hover { background: #1a1a1a; color: #fff; border-color: #1a1a1a; }
    .city-grid { display: grid; grid-template-columns: repeat(5,1fr); gap: 2.5rem; }
    @media(max-width:992px) { .city-grid { grid-template-columns: repeat(3,1fr); } }
    .city-col h6 { font-size: 0.9rem; font-weight: 700; margin-bottom: 12px; }
    .city-col a { display: block; font-size: 0.81rem; color: #666; padding: 3px 0; }
    .city-col a:hover { color: #E91E8C; }
 
    .biz-sec { background: #1a1a1a; padding: 80px 32px; }
    .biz-sec h2 { font-size: clamp(1.8rem,4vw,2.8rem); font-weight: 900; color: #fff; margin-bottom: 16px; }
    .biz-sec p { color: #888; font-size: 0.92rem; margin-bottom: 28px; }
    .btn-biz { background: #fff; color: #1a1a1a; border-radius: 50px; padding: 14px 32px; font-weight: 700; display: inline-block; }
    .btn-biz:hover { background: #E91E8C; color: #fff; }
 
    .g-footer { background: #f5f5f5; padding: 56px 32px 30px; }
    .g-footer .foot-brand {
        font-size: 1.4rem;
        font-weight: 900;
        letter-spacing: -0.5px;
        font-style: italic;
        background: linear-gradient(135deg, #E91E8C, #9333ea);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 6px;
    }
    .g-footer .foot-copy { font-size: 0.78rem; color: #bbb; }
    .g-footer h6 { font-size: 0.82rem; font-weight: 700; margin-bottom: 14px; }
    .g-footer a { display: block; font-size: 0.8rem; color: #666; margin-bottom: 9px; }
    .g-footer a:hover { color: #E91E8C; }
    </style>
</head>
<body>
 
<nav class="g-nav">
    <a href="{{ route('home') }}" class="brand">Beauty Blush Salon</a>
    <div class="nav-right">
        @auth
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="btn-nav-ghost">Dashboard</a>
            @elseif(Auth::user()->isOwner())
                <a href="{{ route('owner.dashboard') }}" class="btn-nav-ghost">My Salons</a>
            @else
                <a href="{{ route('client.dashboard') }}" class="btn-nav-ghost">My Account</a>
            @endif
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button class="btn-nav-outline">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn-nav-ghost">Log in</a>
            <a href="{{ route('register.owner') }}" class="btn-nav-outline">List your business</a>
        @endauth
        <button class="btn-nav-menu" onclick="toggleMenuDropdown()">
            Menu <i class="fas fa-bars"></i>
        </button>
        <div class="menu-dropdown" id="menuDropdown">
            <a href="{{ route('salons.index') }}">Find a salon</a>
            <a href="{{ route('services.index') }}">Services</a>
            <a href="{{ route('about') }}">About</a>
            <a href="{{ route('contact') }}">Contact</a>
            <a href="{{ route('register.owner') }}">List your business</a>
        </div>
    </div>
</nav>
 
<section class="hero">
    <h1>Book premium beauty services</h1>
    <p>Discover top-rated salons, bridal studios, nail artists and beauty experts<br class="d-none d-md-block"> trusted by thousands across Pakistan</p>
 
    <form action="{{ route('salons.index') }}" method="GET" id="heroSearchForm">
        <div class="search-pill" id="searchPill">
 
            <div class="sp-segment" onclick="toggleDropdown('treatmentsDD')">
                <i class="fas fa-search sp-icon"></i>
                <input type="text" name="search" id="treatmentInput" class="sp-field" placeholder="All treatments" readonly>
            </div>
            <div class="sp-dropdown" id="treatmentsDD">
                <div style="font-size:0.75rem;font-weight:700;color:#aaa;text-transform:uppercase;margin-bottom:8px;padding:0 4px;">Popular Services</div>
                <div class="sp-dropdown-item" onclick="selectTreatment('Hair Styling')"><i class="fas fa-cut"></i> Hair Styling</div>
                <div class="sp-dropdown-item" onclick="selectTreatment('Hair Color')"><i class="fas fa-palette"></i> Hair Color</div>
                <div class="sp-dropdown-item" onclick="selectTreatment('Hair Treatment')"><i class="fas fa-hand-sparkles"></i> Hair Treatment</div>
                <div class="sp-dropdown-item" onclick="selectTreatment('Nail Art')"><i class="fas fa-hand-peace"></i> Nail Art</div>
                <div class="sp-dropdown-item" onclick="selectTreatment('Bridal Makeup')"><i class="fas fa-brush"></i> Bridal Makeup</div>
                <div class="sp-dropdown-item" onclick="selectTreatment('Party Makeup')"><i class="fas fa-brush"></i> Party Makeup</div>
                <div class="sp-dropdown-item" onclick="selectTreatment('Massage Therapy')"><i class="fas fa-spa"></i> Massage Therapy</div>
                <div class="sp-dropdown-item" onclick="selectTreatment('Facial Treatment')"><i class="fas fa-face-smile"></i> Facial Treatment</div>
                <div class="sp-dropdown-item" onclick="selectTreatment('Waxing')"><i class="fas fa-feather"></i> Waxing</div>
                <div class="sp-dropdown-item" onclick="selectTreatment('Threading')"><i class="fas fa-feather"></i> Threading</div>
                <div class="sp-dropdown-item" onclick="selectTreatment('Mehndi Design')"><i class="fas fa-palette"></i> Mehndi Design</div>
                <div class="sp-dropdown-item" onclick="selectTreatment('Eyelash Extensions')"><i class="fas fa-eye"></i> Eyelash Extensions</div>
                <div class="sp-dropdown-item" onclick="selectTreatment('Men Grooming')"><i class="fas fa-cut"></i> Men Grooming</div>
                @foreach($categories as $cat)
                <div class="sp-dropdown-item" onclick="selectTreatment('{{ $cat->name }}')">
                    <i class="fas fa-spa"></i> {{ $cat->name }}
                </div>
                @endforeach
            </div>
 
            <div class="sp-divider"></div>
 
            <div class="sp-segment" onclick="toggleDropdown('locationDD')">
                <i class="fas fa-map-marker-alt sp-icon" style="color:#E91E8C;"></i>
                <input type="text" name="city" id="locationInput" class="sp-field" placeholder="Current location" readonly>
            </div>
            <div class="sp-dropdown" id="locationDD">
                <div style="font-size:0.75rem;font-weight:700;color:#aaa;text-transform:uppercase;margin-bottom:8px;padding:0 4px;">Select City</div>
                <div class="sp-dropdown-item" onclick="selectCity('Lahore')"><i class="fas fa-map-marker-alt"></i> Lahore</div>
                <div class="sp-dropdown-item" onclick="selectCity('Karachi')"><i class="fas fa-map-marker-alt"></i> Karachi</div>
                <div class="sp-dropdown-item" onclick="selectCity('Islamabad')"><i class="fas fa-map-marker-alt"></i> Islamabad</div>
                <div class="sp-dropdown-item" onclick="selectCity('Rawalpindi')"><i class="fas fa-map-marker-alt"></i> Rawalpindi</div>
                <div class="sp-dropdown-item" onclick="selectCity('Faisalabad')"><i class="fas fa-map-marker-alt"></i> Faisalabad</div>
                <div class="sp-dropdown-item" onclick="selectCity('Multan')"><i class="fas fa-map-marker-alt"></i> Multan</div>
                <div class="sp-dropdown-item" onclick="selectCity('Peshawar')"><i class="fas fa-map-marker-alt"></i> Peshawar</div>
                <div class="sp-dropdown-item" onclick="selectCity('Quetta')"><i class="fas fa-map-marker-alt"></i> Quetta</div>
            </div>
 
            <div class="sp-divider"></div>
 
            <div class="sp-segment" onclick="toggleDropdown('timeDD')">
                <i class="fas fa-calendar sp-icon"></i>
                <input type="text" id="dateTimeInput" class="sp-field" placeholder="Any time" readonly>
                <input type="hidden" name="date" id="dateHidden">
            </div>
            <div class="sp-dropdown" id="timeDD">
                <div style="font-size:0.7rem;font-weight:600;color:#aaa;margin-bottom:10px;letter-spacing:0.5px;">SELECT DATE & TIME</div>
                <div id="inlineCal"></div>
                <div style="margin-top:12px;">
                    <div style="font-size:0.75rem;font-weight:600;margin-bottom:8px;">Preferred Time</div>
                    <div style="display:flex;flex-wrap:wrap;gap:6px;">
                        <button type="button" class="time-option-btn" onclick="selectTime('Morning')">Morning</button>
                        <button type="button" class="time-option-btn" onclick="selectTime('Afternoon')">Afternoon</button>
                        <button type="button" class="time-option-btn" onclick="selectTime('Evening')">Evening</button>
                        <button type="button" class="time-option-btn" onclick="selectTime('Any time')">Any time</button>
                    </div>
                </div>
                <button type="button" onclick="applyDateTime()" class="btn-search-pill" style="width:100%;border-radius:12px;margin-top:12px;height:44px;">Apply</button>
            </div>
 
            <button type="submit" class="btn-search-pill">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>
 
    <p class="hero-count"><strong>{{ number_format($totalBookings) }}</strong> appointments booked today</p>
</section>
 
<!-- RECOMMENDED SECTION - 4 CARDS -->
<section class="g-section" style="background:#fff;padding-top:36px;">
    <div class="g-section-head">
        <h2>Recommended</h2>
        <a href="{{ route('salons.index') }}" class="see-all">See all <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="slider-outer">
        <button class="slider-arrow-btn left" onclick="slide('rec',-1)"><i class="fas fa-chevron-left"></i></button>
        <div class="slider-scroll-area" id="slider-rec">
            <div class="slider-track">
                @php $ratings = [5.0, 4.9, 4.8, 4.7]; @endphp
                @forelse($featuredSalons->take(4) as $index => $salon)
                <a href="{{ route('salons.show', $salon->slug) }}" class="salon-card">
                    <div class="sc-img">
                        @if($index == 0)
                        <img src="{{ asset('storage/images/salon1.jpg') }}" alt="{{ $salon->name }}">
                        @elseif($index == 1)
                        <img src="{{ asset('storage/images/salon9.jpg') }}" alt="{{ $salon->name }}">
                        @elseif($index == 2)
                        <img src="{{ asset('storage/images/salon17.jpg') }}" alt="{{ $salon->name }}">
                        @else
                        <img src="{{ asset('storage/images/salon25.jpg') }}" alt="{{ $salon->name }}">
                        @endif
                        <span class="sc-badge">Featured</span>
                    </div>
                    <div class="sc-body">
                        <div class="sc-name-row">
                            <div class="sc-name">{{ $salon->name ?? 'Glamora Elite' }} <i class="fas fa-check-circle vc"></i></div>
                            <div class="sc-rating-inline"><i class="fas fa-star star"></i> {{ $ratings[$index % count($ratings)] }}</div>
                        </div>
                        <div class="sc-addr">{{ $salon->address ?? 'Main Boulevard Gulberg, Lahore' }}</div>
                        <div class="sc-meta">{{ $salon->services->first()?->category?->name ?? 'Luxury Salon' }} · {{ rand(50,300) }} reviews</div>
                    </div>
                </a>
                @empty

                <a href="#" class="salon-card"><div class="sc-img"><img src="{{ asset('storage/images/salon1.jpg') }}" alt="Salon"></div><div class="sc-body"><div class="sc-name-row"><div class="sc-name">Glamora Elite Salon <i class="fas fa-check-circle vc"></i></div><div class="sc-rating-inline"><i class="fas fa-star star"></i> 5.0</div></div><div class="sc-addr">Main Boulevard Gulberg, Lahore</div><div class="sc-meta">Luxury Salon · 128 reviews</div></div></a>
                <a href="#" class="salon-card"><div class="sc-img"><img src="{{ asset('storage/images/salon2.jpg') }}" alt="Salon"></div><div class="sc-body"><div class="sc-name-row"><div class="sc-name">Aura Beauty Studio <i class="fas fa-check-circle vc"></i></div><div class="sc-rating-inline"><i class="fas fa-star star"></i> 4.9</div></div><div class="sc-addr">DHA Phase 5, Lahore</div><div class="sc-meta">Spa · 89 reviews</div></div></a>
                <a href="#" class="salon-card"><div class="sc-img"><img src="{{ asset('storage/images/salon3.jpg') }}" alt="Salon"></div><div class="sc-body"><div class="sc-name-row"><div class="sc-name">The Royal Glow Salon <i class="fas fa-check-circle vc"></i></div><div class="sc-rating-inline"><i class="fas fa-star star"></i> 4.8</div></div><div class="sc-addr">MM Alam Road, Lahore</div><div class="sc-meta">Bridal · 234 reviews</div></div></a>
                <a href="#" class="salon-card"><div class="sc-img"><img src="{{ asset('storage/images/salon4.jpg') }}" alt="Salon"></div><div class="sc-body"><div class="sc-name-row"><div class="sc-name">Elegance Hair & Beauty Lounge <i class="fas fa-check-circle vc"></i></div><div class="sc-rating-inline"><i class="fas fa-star star"></i> 4.7</div></div><div class="sc-addr">Clifton Block 5, Karachi</div><div class="sc-meta">Hair Salon · 156 reviews</div></div></a>

                <a href="{{ route('salons.index') }}" class="salon-card">
                    <div class="sc-img"><img src="#" alt="Salon"></div>
                    <div class="sc-body">
                        <div class="sc-name-row">
                            <div class="sc-name">Glamora Elite <i class="fas fa-check-circle vc"></i></div>
                            <div class="sc-rating-inline"><i class="fas fa-star star"></i> 5.0</div>
                        </div>
                        <div class="sc-addr">Main Boulevard, Gulberg, Lahore</div>
                        <div class="sc-meta">Luxury Salon · 128 reviews</div>
                    </div>
                </a>
                <a href="{{ route('salons.index') }}" class="salon-card">
                    <div class="sc-img"><img src="#" alt="Salon"></div>
                    <div class="sc-body">
                        <div class="sc-name-row">
                            <div class="sc-name">Serenity Spa & Salon <i class="fas fa-check-circle vc"></i></div>
                            <div class="sc-rating-inline"><i class="fas fa-star star"></i> 4.9</div>
                        </div>
                        <div class="sc-addr">DHA Phase 5, Lahore</div>
                        <div class="sc-meta">Spa · 89 reviews</div>
                    </div>
                </a>
                <a href="{{ route('salons.index') }}" class="salon-card">
                    <div class="sc-img"><img src="#" alt="Salon"></div>
                    <div class="sc-body">
                        <div class="sc-name-row">
                            <div class="sc-name">Royal Bridal Studio <i class="fas fa-check-circle vc"></i></div>
                            <div class="sc-rating-inline"><i class="fas fa-star star"></i> 4.8</div>
                        </div>
                        <div class="sc-addr">MM Alam Road, Lahore</div>
                        <div class="sc-meta">Bridal · 234 reviews</div>
                    </div>
                </a>
                <a href="{{ route('salons.index') }}" class="salon-card">
                    <div class="sc-img"><img src="#" alt="Salon"></div>
                    <div class="sc-body">
                        <div class="sc-name-row">
                            <div class="sc-name">The Hair Lounge <i class="fas fa-check-circle vc"></i></div>
                            <div class="sc-rating-inline"><i class="fas fa-star star"></i> 4.7</div>
                        </div>
                        <div class="sc-addr">Clifton, Karachi</div>
                        <div class="sc-meta">Hair Salon · 156 reviews</div>
                    </div>
                </a>
                @endforelse
            </div>
        </div>
        <button class="slider-arrow-btn right" onclick="slide('rec',1)"><i class="fas fa-chevron-right"></i></button>
    </div>
</section>
 
<!-- NEW TO GLAMORA SECTION - 4 CARDS -->
<section class="g-section" style="background:linear-gradient(180deg,#fff 0%,#fdf5fb 100%);">
    <div class="g-section-head">
        <h2>New to Glamora</h2>
        <a href="{{ route('salons.index') }}" class="see-all">See all <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="slider-outer">
        <button class="slider-arrow-btn left" onclick="slide('newto',-1)"><i class="fas fa-chevron-left"></i></button>
        <div class="slider-scroll-area" id="slider-newto">
            <div class="slider-track">
                @forelse($newSalons->take(4) as $index => $salon)
                <a href="{{ route('salons.show', $salon->slug) }}" class="salon-card">
                    <div class="sc-img">
                        @if($index == 0)
                        <img src="{{ asset('storage/images/salon11.jpg') }}" alt="{{ $salon->name }}">
                        @elseif($index == 1)
                        <img src="{{ asset('storage/images/salon20.jpg') }}" alt="{{ $salon->name }}">
                        @elseif($index == 2)
                        <img src="{{ asset('storage/images/salon13.jpg') }}" alt="{{ $salon->name }}">
                        @else
                        <img src="{{ asset('storage/images/salon29.jpg') }}" alt="{{ $salon->name }}">
                        @endif
                    </div>
                    <div class="sc-body">
                        <div class="sc-name-row"><div class="sc-name">{{ $salon->name ?? 'New Style Studio' }} <i class="fas fa-check-circle vc"></i></div></div>
                        <div class="sc-addr">{{ $salon->address ?? 'Johar Town, Lahore' }}</div>
                        <div class="sc-meta">New · {{ rand(10,100) }} reviews</div>
                    </div>
                </a>
                @empty

                <a href="#" class="salon-card"><div class="sc-img"><img src="{{ asset('storage/images/salon11.jpg') }}" alt="Salon"></div><div class="sc-body"><div class="sc-name-row"><div class="sc-name">New Style Studio <i class="fas fa-check-circle vc"></i></div></div><div class="sc-addr">Johar Town, Lahore</div><div class="sc-meta">New · 45 reviews</div></div></a>
                <a href="#" class="salon-card"><div class="sc-img"><img src="{{ asset('storage/images/salon20.jpg') }}" alt="Salon"></div><div class="sc-body"><div class="sc-name-row"><div class="sc-name">Urban Nails & Spa <i class="fas fa-check-circle vc"></i></div></div><div class="sc-addr">Gulshan, Karachi</div><div class="sc-meta">New · 32 reviews</div></div></a>
                <a href="#" class="salon-card"><div class="sc-img"><img src="{{ asset('storage/images/salon13.jpg') }}" alt="Salon"></div><div class="sc-body"><div class="sc-name-row"><div class="sc-name">Bliss Beauty Bar <i class="fas fa-check-circle vc"></i></div></div><div class="sc-addr">F-7, Islamabad</div><div class="sc-meta">New · 28 reviews</div></div></a>
                <a href="#" class="salon-card"><div class="sc-img"><img src="{{ asset('storage/images/salon29.jpg') }}" alt="Salon"></div><div class="sc-body"><div class="sc-name-row"><div class="sc-name">The Makeup Loft <i class="fas fa-check-circle vc"></i></div></div><div class="sc-addr">Saddar, Rawalpindi</div><div class="sc-meta">New · 67 reviews</div></div></a>

                <a href="{{ route('salons.index') }}" class="salon-card">
                    <div class="sc-img"><img src="#" alt="Salon"></div>
                    <div class="sc-body">
                        <div class="sc-name-row"><div class="sc-name">New Style Studio <i class="fas fa-check-circle vc"></i></div></div>
                        <div class="sc-addr">Johar Town, Lahore</div>
                        <div class="sc-meta">New · 45 reviews</div>
                    </div>
                </a>
                <a href="{{ route('salons.index') }}" class="salon-card">
                    <div class="sc-img"><img src="#" alt="Salon"></div>
                    <div class="sc-body">
                        <div class="sc-name-row"><div class="sc-name">Urban Nails & Spa <i class="fas fa-check-circle vc"></i></div></div>
                        <div class="sc-addr">Gulshan, Karachi</div>
                        <div class="sc-meta">New · 32 reviews</div>
                    </div>
                </a>
                <a href="{{ route('salons.index') }}" class="salon-card">
                    <div class="sc-img"><img src="#" alt="Salon"></div>
                    <div class="sc-body">
                        <div class="sc-name-row"><div class="sc-name">Bliss Beauty Bar <i class="fas fa-check-circle vc"></i></div></div>
                        <div class="sc-addr">F-7, Islamabad</div>
                        <div class="sc-meta">New · 28 reviews</div>
                    </div>
                </a>
                <a href="{{ route('salons.index') }}" class="salon-card">
                    <div class="sc-img"><img src="#" alt="Salon"></div>
                    <div class="sc-body">
                        <div class="sc-name-row"><div class="sc-name">The Makeup Loft <i class="fas fa-check-circle vc"></i></div></div>
                        <div class="sc-addr">Saddar, Rawalpindi</div>
                        <div class="sc-meta">New · 67 reviews</div>
                    </div>
                </a>

                @endforelse
            </div>
        </div>
        <button class="slider-arrow-btn right" onclick="slide('newto',1)"><i class="fas fa-chevron-right"></i></button>
    </div>
</section>
 
<!-- TRENDING SECTION - 4 CARDS -->
<section class="g-section" style="background:#fff;">
    <div class="g-section-head">
        <h2>Trending</h2>
        <a href="{{ route('salons.index') }}" class="see-all">See all <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="slider-outer">
        <button class="slider-arrow-btn left" onclick="slide('trending',-1)"><i class="fas fa-chevron-left"></i></button>
        <div class="slider-scroll-area" id="slider-trending">
            <div class="slider-track">
                @forelse($topRatedSalons->take(4) as $index => $salon)
                <a href="{{ route('salons.show', $salon->slug) }}" class="salon-card">
                    <div class="sc-img">
                        @if($index == 0)
                        <img src="{{ asset('storage/images/salon13.jpg') }}" alt="{{ $salon->name }}">
                        @elseif($index == 1)
                        <img src="{{ asset('storage/images/salon14.jpg') }}" alt="{{ $salon->name }}">
                        @elseif($index == 2)
                        <img src="{{ asset('storage/images/salon32.jpg') }}" alt="{{ $salon->name }}">
                        @else
                        <img src="{{ asset('storage/images/salon31.jpg') }}" alt="{{ $salon->name }}">
                        @endif
                    </div>
                    <div class="sc-body">
                        <div class="sc-name-row">
                            <div class="sc-name">{{ $salon->name ?? 'Trending Now' }} <i class="fas fa-check-circle vc"></i></div>
                            <div class="sc-rating-inline"><i class="fas fa-star star"></i> {{ number_format($salon->rating ?? 4.8, 1) }}</div>
                        </div>
                        <div class="sc-addr">{{ $salon->address ?? 'Liberty Market, Lahore' }}</div>
                        <div class="sc-meta">{{ rand(100,500) }} reviews</div>
                    </div>
                </a>
                @empty

                <a href="#" class="salon-card"><div class="sc-img"><img src="{{ asset('storage/images/salon21.jpg') }}" alt="Salon"></div><div class="sc-body"><div class="sc-name-row"><div class="sc-name">Trending Now <i class="fas fa-check-circle vc"></i></div><div class="sc-rating-inline"><i class="fas fa-star star"></i> 4.9</div></div><div class="sc-addr">Liberty Market, Lahore</div><div class="sc-meta">234 reviews</div></div></a>
                <a href="#" class="salon-card"><div class="sc-img"><img src="{{ asset('storage/images/salon22.jpg') }}" alt="Salon"></div><div class="sc-body"><div class="sc-name-row"><div class="sc-name">Vogue Beauty Lounge <i class="fas fa-check-circle vc"></i></div><div class="sc-rating-inline"><i class="fas fa-star star"></i> 4.8</div></div><div class="sc-addr">Clifton, Karachi</div><div class="sc-meta">189 reviews</div></div></a>
                <a href="#" class="salon-card"><div class="sc-img"><img src="{{ asset('storage/images/salon23.jpg') }}" alt="Salon"></div><div class="sc-body"><div class="sc-name-row"><div class="sc-name">Elegance Salon <i class="fas fa-check-circle vc"></i></div><div class="sc-rating-inline"><i class="fas fa-star star"></i> 4.7</div></div><div class="sc-addr">F-10, Islamabad</div><div class="sc-meta">156 reviews</div></div></a>
                <a href="#" class="salon-card"><div class="sc-img"><img src="{{ asset('storage/images/salon24.jpg') }}" alt="Salon"></div><div class="sc-body"><div class="sc-name-row"><div class="sc-name">Style Studio <i class="fas fa-check-circle vc"></i></div><div class="sc-rating-inline"><i class="fas fa-star star"></i> 4.6</div></div><div class="sc-addr">Saddar, Rawalpindi</div><div class="sc-meta">102 reviews</div></div></a>

                <a href="{{ route('salons.index') }}" class="salon-card">
                    <div class="sc-img"><img src="#" alt="Salon"></div>
                    <div class="sc-body">
                        <div class="sc-name-row">
                            <div class="sc-name">Trending Now <i class="fas fa-check-circle vc"></i></div>
                            <div class="sc-rating-inline"><i class="fas fa-star star"></i> 4.9</div>
                        </div>
                        <div class="sc-addr">Liberty Market, Lahore</div>
                        <div class="sc-meta">234 reviews</div>
                    </div>
                </a>
                <a href="{{ route('salons.index') }}" class="salon-card">
                    <div class="sc-img"><img src="#" alt="Salon"></div>
                    <div class="sc-body">
                        <div class="sc-name-row">
                            <div class="sc-name">Vogue Beauty Lounge <i class="fas fa-check-circle vc"></i></div>
                            <div class="sc-rating-inline"><i class="fas fa-star star"></i> 4.8</div>
                        </div>
                        <div class="sc-addr">Clifton, Karachi</div>
                        <div class="sc-meta">189 reviews</div>
                    </div>
                </a>
                <a href="{{ route('salons.index') }}" class="salon-card">
                    <div class="sc-img"><img src="#" alt="Salon"></div>
                    <div class="sc-body">
                        <div class="sc-name-row">
                            <div class="sc-name">Elegance Salon <i class="fas fa-check-circle vc"></i></div>
                            <div class="sc-rating-inline"><i class="fas fa-star star"></i> 4.7</div>
                        </div>
                        <div class="sc-addr">F-10, Islamabad</div>
                        <div class="sc-meta">156 reviews</div>
                    </div>
                </a>
                <a href="{{ route('salons.index') }}" class="salon-card">
                    <div class="sc-img"><img src="#" alt="Salon"></div>
                    <div class="sc-body">
                        <div class="sc-name-row">
                            <div class="sc-name">Style Studio <i class="fas fa-check-circle vc"></i></div>
                            <div class="sc-rating-inline"><i class="fas fa-star star"></i> 4.6</div>
                        </div>
                        <div class="sc-addr">Saddar, Rawalpindi</div>
                        <div class="sc-meta">102 reviews</div>
                    </div>
                </a>

                @endforelse
            </div>
        </div>
        <button class="slider-arrow-btn right" onclick="slide('trending',1)"><i class="fas fa-chevron-right"></i></button>
    </div>
</section>
 
<section class="g-section" style="background:#fff;">
    <div class="g-section-head">
        <h2>Reviews</h2>
    </div>
    <div style="padding:0 32px;">
        <div class="row g-4">
            @foreach([
                ['The best booking system','Great experience, easy to book. Paying for treatments is so convenient — no cash needed! Glamora made everything seamless.','Ayesha K.','Lahore, Pakistan','#E91E8C'],
                ['Easy to use & explore','Glamora\'s reminders make life so much easier. I found amazing salons I didn\'t know existed. Highly recommend to everyone!','Sana M.','Karachi, Pakistan','#9333ea'],
                ['Great for finding salons','I\'ve been using Glamora for months and it\'s by far the best booking platform I\'ve used. The waitlist feature is brilliant!','Fatima R.','Islamabad, Pakistan','#0ea5e9'],
                ['My go-to for beauty','Glamora is my go-to app for salon bookings. I can easily find and book places near me — absolutely love it!','Hina B.','Lahore, Pakistan','#10b981'],
            ] as [$title,$text,$name,$loc,$color])
            <div class="col-lg-3 col-md-6">
                <div class="rev-card">
                    <div class="rc-stars">★★★★★</div>
                    <div class="rc-title">{{ $title }}</div>
                    <p class="rc-text">{{ $text }}</p>
                    <div class="rc-reviewer">
                        <div class="rc-av" style="background:{{ $color }};">{{ substr($name,0,1) }}</div>
                        <div>
                            <div class="rc-name">{{ $name }}</div>
                            <div class="rc-loc">{{ $loc }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
 
<section class="stats-sec">
    <h2 class="st-title">The top-rated destination for beauty in Pakistan</h2>
    <p class="st-sub">One platform. Trusted by the best in the beauty industry.</p>
    <div class="big-num">{{ number_format($totalBookings) }}+</div>
    <div class="big-lbl">appointments booked on Glamora</div>
    <div class="mini-stats">
        <div><div class="ms-num">{{ number_format($totalSalons) }}+</div><div class="ms-lbl">partner salons</div></div>
        <div><div class="ms-num">50+</div><div class="ms-lbl">cities covered</div></div>
        <div><div class="ms-num">{{ number_format($totalClients) }}+</div><div class="ms-lbl">happy clients</div></div>
    </div>
</section>
 
<section class="city-sec">
    <h2 style="font-size:1.5rem;font-weight:800;margin-bottom:20px;">Browse by City</h2>
    <div class="city-tabs">
        @foreach(['All','Lahore','Karachi','Islamabad','Rawalpindi'] as $i=>$city)
        <button class="city-tab-btn {{ $i===0?'active':'' }}" onclick="switchCity(this,'{{ $city }}')">{{ $city }}</button>
        @endforeach
    </div>
    <div class="city-grid">
        <div class="city-col"><h6>Popular</h6><a href="#">Beauty Salons</a><a href="#">Hair Salons</a><a href="#">Bridal Salons</a></div>
        <div class="city-col"><h6>Lahore</h6><a href="#">Hair Salons Lahore</a><a href="#">Nail Salons Lahore</a><a href="#">Bridal Salons Lahore</a></div>
        <div class="city-col"><h6>Karachi</h6><a href="#">Hair Salons Karachi</a><a href="#">Nail Salons Karachi</a><a href="#">Bridal Salons Karachi</a></div>
        <div class="city-col"><h6>Islamabad</h6><a href="#">Hair Salons Islamabad</a><a href="#">Nail Salons Islamabad</a><a href="#">Bridal Salons Islamabad</a></div>
        <div class="city-col"><h6>Rawalpindi</h6><a href="#">Hair Salons Rawalpindi</a><a href="#">Nail Salons Rawalpindi</a><a href="#">Bridal Salons Rawalpindi</a></div>
    </div>
</section>
 
<section class="biz-sec">
    <div class="container"><div class="row"><div class="col-lg-7">
        <h2>Grow your salon business with Glamora</h2>
        <p>Join thousands of salon owners across Pakistan who manage bookings, payments, and clients from one beautiful dashboard. Completely free to register.</p>
        <a href="{{ route('register.owner') }}" class="btn-biz">List your business — it's free</a>
    </div></div></div>
</section>
 
<footer class="g-footer">
    <div class="row g-4">
        <div class="col-lg-3 col-md-6">
            <div class="foot-brand">glamora</div>
            <div class="foot-copy">© {{ date('Y') }} Glamora. All rights reserved.</div>
            <div style="display:flex;gap:10px;margin-top:16px;">
                @foreach(['fab fa-facebook-f','fab fa-instagram','fab fa-tiktok','fab fa-youtube'] as $icon)
                <a href="#" style="width:34px;height:34px;border-radius:50%;background:#e8e8e8;display:flex;align-items:center;justify-content:center;color:#555;font-size:0.78rem;transition:all .15s;"
                   onmouseover="this.style.background='#E91E8C';this.style.color='#fff'"
                   onmouseout="this.style.background='#e8e8e8';this.style.color='#555'">
                    <i class="{{ $icon }}"></i>
                </a>
                @endforeach
            </div>
        </div>
        <div class="col-lg-2 col-md-6">
            <h6>About Glamora</h6>
            <a href="{{ route('about') }}">About us</a>
            <a href="#">Careers</a>
            <a href="{{ route('contact') }}">Help & Support</a>
            <a href="#">Blog</a>
            <a href="#">Sitemap</a>
        </div>
        <div class="col-lg-2 col-md-6">
            <h6>For business</h6>
            <a href="{{ route('register.owner') }}">List your salon</a>
            <a href="#">For partners</a>
            <a href="#">Pricing</a>
            <a href="#">Payments</a>
            <a href="#">Support</a>
        </div>
        <div class="col-lg-2 col-md-6">
            <h6>Legal</h6>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of service</a>
            <a href="#">Terms of use</a>
            <a href="#">Cookie policy</a>
        </div>
        <div class="col-lg-3 col-md-6">
            <h6>Find us on social</h6>
            @foreach(['Facebook','Instagram','Twitter (X)','LinkedIn'] as $sm)
            <a href="#">↗ {{ $sm }}</a>
            @endforeach
        </div>
    </div>
</footer>
 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
function slide(id, dir) {
    const el = document.getElementById('slider-' + id);
    const firstCard = el.querySelector('.salon-card');
    const cardWidth = firstCard ? firstCard.offsetWidth + 16 : 280;
    el.scrollBy({ left: dir * cardWidth * 4, behavior: 'smooth' });
}
 
function toggleDropdown(id) {
    const all = document.querySelectorAll('.sp-dropdown');
    all.forEach(d => { if (d.id !== id) d.classList.remove('show'); });
    document.getElementById(id).classList.toggle('show');
}
 
function selectTreatment(name) {
    document.getElementById('treatmentInput').value = name;
    document.getElementById('treatmentsDD').classList.remove('show');
    let hidden = document.querySelector('input[name="search"]');
    if (!hidden) {
        hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'search';
        document.getElementById('heroSearchForm').appendChild(hidden);
    }
    hidden.value = name;
}
 
function selectCity(city) {
    document.getElementById('locationInput').value = city;
    document.getElementById('locationDD').classList.remove('show');
    let hidden = document.querySelector('input[name="city"]');
    if (!hidden) {
        hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'city';
        document.getElementById('heroSearchForm').appendChild(hidden);
    }
    hidden.value = city;
}
 
let selectedDate = '';
let selectedTime = '';
 
flatpickr('#inlineCal', {
    inline: true,
    dateFormat: 'Y-m-d',
    onChange: function(dates, dateStr) {
        selectedDate = dateStr;
        document.getElementById('dateHidden').value = dateStr;
        updateDateTimeDisplay();
    },
    yearSelectorType: 'dropdown',
    monthSelectorType: 'dropdown'
});
 
function selectTime(t) {
    selectedTime = t;
    updateDateTimeDisplay();
    document.querySelectorAll('.time-option-btn').forEach(b => {
        b.style.borderColor = '#e0e0e0';
        b.style.color = '#333';
        b.style.background = '#fff';
    });
    event.target.style.borderColor = '#E91E8C';
    event.target.style.color = '#E91E8C';
    event.target.style.background = 'rgba(233,30,140,0.05)';
}
 
function updateDateTimeDisplay() {
    const parts = [];
    if (selectedDate) {
        const d = new Date(selectedDate);
        parts.push(d.toLocaleDateString('en-PK', { day:'numeric', month:'short' }));
    }
    if (selectedTime && selectedTime !== 'Any time') parts.push(selectedTime);
    document.getElementById('dateTimeInput').value = parts.join(' · ') || '';
}
 
function applyDateTime() {
    updateDateTimeDisplay();
    document.getElementById('timeDD').classList.remove('show');
}
 
function switchCity(btn, city) {
    document.querySelectorAll('.city-tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    if (city !== 'All') window.location = '{{ route("salons.index") }}?city=' + encodeURIComponent(city);
}
 
function toggleMenuDropdown() {
    const menu = document.getElementById('menuDropdown');
    menu.classList.toggle('show');
}
 
document.addEventListener('click', function(e) {
    if (!e.target.closest('.search-pill')) {
        document.querySelectorAll('.sp-dropdown').forEach(d => d.classList.remove('show'));
    }
    if (!e.target.closest('.btn-nav-menu') && !e.target.closest('.menu-dropdown')) {
        const menu = document.getElementById('menuDropdown');
        if (menu) menu.classList.remove('show');
    }
});
</script>
</body>
</html>

