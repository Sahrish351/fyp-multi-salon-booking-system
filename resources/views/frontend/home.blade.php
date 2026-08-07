<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Beauty Blush Salons — Book Premium Beauty Services in Pakistan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,700;0,800;1,700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
  <style>
    /* ── reset & base ── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; color: #1a1a1a; background: #fff; -webkit-font-smoothing: antialiased; overflow-x: hidden; }
    a { text-decoration: none; color: inherit; }

    /* ── navbar ── */
    .g-nav {
      background: rgba(255,255,255,0.97);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid #fce4ec;
      padding: 0 16px;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 1000;
      box-shadow: 0 2px 20px rgba(233,30,140,0.06);
    }
    .g-nav .brand { display: flex; align-items: center; gap: 8px; }
    .g-nav .brand .brand-icon {
      width: 36px; height: 36px;
      background: linear-gradient(135deg, #E91E8C, #C9A96E);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      box-shadow: 0 4px 14px rgba(233,30,140,0.25);
    }
    .g-nav .brand .brand-icon i { color: #fff; font-size: 1rem; }
    .g-nav .brand .brand-text {
      font-family: 'Playfair Display', serif;
      font-size: 1.2rem; font-weight: 800; letter-spacing: -0.3px;
    }
    .g-nav .brand .brand-text .pink { color: #E91E8C; }
    .g-nav .brand .brand-text .gold { color: #C9A96E; }
    .g-nav .nav-right { display: flex; align-items: center; gap: 6px; }
    .btn-nav-ghost, .btn-nav-outline {
      font-size: 0.7rem; padding: 5px 12px; border-radius: 50px;
      border: 1.5px solid #1a1a1a; background: transparent; font-weight: 600;
      cursor: pointer; transition: all 0.2s ease;
      display: none;
    }
    .btn-nav-ghost { color: #1a1a1a; }
    .btn-nav-outline { background: #1a1a1a; color: #fff; }
    .btn-nav-ghost:hover, .btn-nav-outline:hover { opacity: 0.8; }
    .btn-nav-menu {
      background: #fff; border: 1.5px solid #e0e0e0; color: #1a1a1a;
      font-size: 0.75rem; font-weight: 600; padding: 5px 12px;
      border-radius: 50px; display: flex; align-items: center; gap: 6px;
      cursor: pointer; transition: all 0.2s ease;
    }
    .btn-nav-menu:hover { background: #1a1a1a; color: #fff; }
    .menu-dropdown {
      position: absolute; top: 100%; right: 0;
      background: #fff; border-radius: 16px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.12);
      border: 1px solid #eee; padding: 10px 0;
      z-index: 9999; min-width: 200px; display: none;
    }
    .menu-dropdown.show { display: block; }
    .menu-dropdown a {
      display: block; padding: 10px 20px; font-size: 0.85rem;
      font-weight: 500; color: #333; transition: all 0.2s;
    }
    .menu-dropdown a:hover { background: #f5f5f5; color: #E91E8C; }
    .menu-dropdown .mobile-auth { display: block; }

    @media(min-width:769px) {
      .g-nav { padding: 0 32px; height: 64px; }
      .g-nav .brand .brand-text { font-size: 1.5rem; }
      .g-nav .brand .brand-icon { width: 44px; height: 44px; }
      .g-nav .brand .brand-icon i { font-size: 1.2rem; }
      .btn-nav-ghost, .btn-nav-outline { display: inline-block; font-size: 0.85rem; padding: 7px 18px; }
      .btn-nav-menu { font-size: 0.82rem; padding: 7px 16px; }
      .menu-dropdown .mobile-auth { display: none; }
    }
    @media(max-width:768px) {
      .g-nav .brand .brand-text { font-size: 1rem; }
      .g-nav .brand .brand-icon { width: 32px; height: 32px; }
      .g-nav .brand .brand-icon i { font-size: 0.85rem; }
      .btn-nav-menu { font-size: 0.7rem; padding: 4px 10px; }
    }

    /* ── HERO ── */
    .hero {
      background: linear-gradient(145deg, #ede8f5 0%, #f5e6f5 20%, #fce8f3 50%, #fdf5fb 80%, #fff 100%);
      padding: 40px 16px 30px;
      text-align: center;
      position: relative;
      overflow: visible;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      min-height: auto;
    }
    .hero h1 {
      font-size: 1.8rem;
      font-weight: 900;
      letter-spacing: -1px;
      color: #1a1a1a;
      line-height: 1.15;
      margin-bottom: 30px;
      margin-top: 0px;
    }
    .hero p {
      font-size: 0.95rem;
      color: #666;
      margin-bottom: 10px;
      line-height: 1.4;
      max-width: 700px;
    }
    .hero-count {
      font-size: 0.85rem;
      color: #666;
      margin-top: 16px;
      margin-bottom: 0;
    }
    @media(min-width:768px) {
      .hero {
        padding: 80px 32px 100px;
        min-height: 92vh;
      }
      .hero h1 {
        font-size: clamp(3rem, 5.5vw, 4.5rem);
        white-space: nowrap;
        margin-top: 10px;
        margin-bottom: 12px;
      }
      .hero p { font-size: 1.15rem; margin-bottom: 28px; }
      .hero-count { margin-top: 28px; }
    }
    @media(max-width:480px) {
      .hero { padding: 140px 20px 80px; }
      .hero h1 { font-size: 1.5rem; }
      .hero p { font-size: 0.85rem; margin-bottom: 20px; }
      .hero-count { font-size: 0.75rem; margin-top: 40px; }
    }

    /* ── search pill ── */
    .search-pill {
      display: flex; flex-direction: column;
      background: #fff; border-radius: 20px;
      box-shadow: 0 2px 24px rgba(0,0,0,0.13), 0 0 0 1px rgba(0,0,0,0.06);
      max-width: 900px; margin: 0 auto; padding: 10px 14px;
      gap: 6px;
      width: 100%;
      position: relative;
    }
    .search-pill .sp-segment {
      display: flex; align-items: center;
      padding: 8px 0; border-bottom: 1px solid #f0f0f0;
      cursor: pointer;
      flex: 1 1 100%;
      position: relative;
    }
    .search-pill .sp-segment:last-of-type { border-bottom: none; }
    .search-pill .sp-icon { color: #999; font-size: 0.8rem; margin-right: 8px; flex-shrink: 0; }
    .search-pill .sp-field {
      border: none; outline: none; font-size: 0.82rem;
      color: #1a1a1a; background: transparent; width: 100%;
      font-family: 'Inter', sans-serif; cursor: pointer;
    }
    .search-pill .sp-field::placeholder { color: #999; }
    .search-pill .sp-divider { display: none; }
    .btn-search-pill {
      background: #1a1a1a; color: #fff; border: none; border-radius: 50px;
      width: 100%; height: 44px; display: flex; align-items: center;
      justify-content: center; font-size: 0.95rem; cursor: pointer;
      transition: all 0.2s ease; margin-top: 2px;
    }
    .btn-search-pill:hover { background: #E91E8C; transform: scale(1.02); }
    @media(min-width:768px) {
      .search-pill { flex-direction: row; padding: 5px 5px 5px 20px; border-radius: 60px; gap: 0; }
      .search-pill .sp-segment { border-bottom: none; padding: 0 4px; flex: 1; }
      .search-pill .sp-divider { display: block; width: 1px; height: 28px; background: #e8e8e8; margin: 0 4px; flex-shrink: 0; }
      .btn-search-pill { width: 48px; height: 48px; margin-top: 0; }
      .search-pill .sp-field { font-size: 0.92rem; }
    }

    /* ── DROPDOWNS (FIXED) ── */
    .sp-dropdown {
      position: absolute;
      top: 100%;
      left: 0;
      right: auto;
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 8px 40px rgba(0,0,0,0.15);
      border: 1px solid #f0f0f0;
      padding: 1rem;
      z-index: 9999;
      display: none;
      max-height: 400px;
      overflow-y: auto;
      min-width: 260px;
      margin-top: 4px;
    }
    .sp-dropdown.show { display: block; }

    /* Mobile view: fixed position */
    @media(max-width:768px) {
      .sp-dropdown {
        position: fixed;
        left: 16px;
        right: 16px;
        top: auto;
        min-width: auto;
        max-height: 50vh;
        margin-top: 0;
      }
    }

    /* Laptop view: specific positioning */
    @media(min-width:769px) {
      #treatmentsDD { 
        left: 0; 
        right: auto; 
      }
      #locationDD { 
        left: 50%; 
        transform: translateX(-50%); 
      }
      #timeDD { 
        right: 0; 
        left: auto; 
        min-width: 380px; 
        max-width: 420px; 
        max-height: none; 
      }
    }

    .sp-dropdown-item {
      display: flex; align-items: center; gap: 10px; padding: 10px 12px;
      border-radius: 10px; cursor: pointer; transition: background .15s;
      font-size: 0.88rem; color: #333;
    }
    .sp-dropdown-item:hover { background: #f5f5f5; }
    .sp-dropdown-item i { color: #E91E8C; width: 16px; }

    /* ── flatpickr inline ── */
    .flatpickr-calendar.inline { width: 100% !important; box-shadow: none !important; border: none !important; }
    .flatpickr-calendar.inline .flatpickr-days { width: 100% !important; }
    .flatpickr-calendar.inline .dayContainer { width: 100% !important; justify-content: center; }
    .flatpickr-day { width: 34px !important; max-width: 34px !important; height: 34px !important; line-height: 34px !important; margin: 2px !important; border-radius: 50% !important; }
    .flatpickr-day.selected, .flatpickr-day.selected:hover { background: #E91E8C !important; border-color: #E91E8C !important; }
    .time-option-btn {
      border: 1.5px solid #e0e0e0; border-radius: 50px; padding: 5px 14px;
      font-size: 0.78rem; font-weight: 500; background: #fff; cursor: pointer;
      transition: all 0.2s;
    }
    .time-option-btn:hover { border-color: #E91E8C; color: #E91E8C; }

    /* ── sections ── */
    .g-section { padding: 30px 0; }
    .g-section-head {
      display: flex; align-items: center; justify-content: space-between;
      padding: 0 16px; margin-bottom: 16px;
    }
    .g-section-head h2 { font-size: 1.2rem; font-weight: 800; color: #1a1a1a; }
    .see-all { font-size: 0.8rem; font-weight: 600; color: #555; display: flex; align-items: center; gap: 4px; }
    .see-all:hover { color: #E91E8C; }
    @media(min-width:768px) {
      .g-section { padding: 44px 0; }
      .g-section-head { padding: 0 32px; }
      .g-section-head h2 { font-size: 1.5rem; }
    }

    /* ── slider ── */
    .slider-outer { position: relative; }
    .slider-scroll-area {
      overflow-x: auto; scrollbar-width: none; padding: 4px 16px 16px;
      scroll-behavior: smooth;
    }
    .slider-scroll-area::-webkit-scrollbar { display: none; }
    .slider-track {
      display: grid; grid-auto-flow: column;
      grid-auto-columns: 75vw;
      gap: 16px;
    }
    @media(min-width:576px) { .slider-track { grid-auto-columns: calc((100% - 32px) / 1.5); } }
    @media(min-width:768px) { .slider-track { grid-auto-columns: calc((100% - 48px) / 2); } }
    @media(min-width:992px) { .slider-track { grid-auto-columns: calc((100% - 80px) / 3); } }
    @media(min-width:1200px) { .slider-track { grid-auto-columns: calc((100% - 96px) / 4); } }

    .slider-arrow-btn {
      display: none;
      position: absolute; top: 40%; transform: translateY(-50%);
      width: 44px; height: 44px; border-radius: 50%; background: #fff;
      border: 1.5px solid #d8d8d8; align-items: center; justify-content: center;
      cursor: pointer; z-index: 10; box-shadow: 0 2px 16px rgba(0,0,0,0.14);
      transition: all 0.2s;
    }
    .slider-arrow-btn:hover { background: #1a1a1a; color: #fff; border-color: #1a1a1a; }
    .slider-arrow-btn.left { left: 4px; }
    .slider-arrow-btn.right { right: 4px; }
    @media(min-width:768px) { .slider-arrow-btn { display: flex; } }

    /* ── salon card ── */
    .salon-card { cursor: pointer; display: block; }
    .salon-card .sc-img {
      width: 100%; aspect-ratio: 4/3; border-radius: 16px; overflow: hidden;
      position: relative; background: #f0f0f0;
    }
    .salon-card .sc-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .salon-card .sc-badge {
      position: absolute; top: 10px; left: 10px; background: rgba(255,255,255,0.95);
      font-size: 0.7rem; font-weight: 600; color: #333; padding: 3px 10px;
      border-radius: 20px; backdrop-filter: blur(6px);
    }
    .salon-card .sc-body { padding: 10px 0 0; }
    .salon-card .sc-name-row {
      display: flex; align-items: flex-start; justify-content: space-between; gap: 6px;
    }
    .salon-card .sc-name {
      font-size: 0.9rem; font-weight: 700; color: #1a1a1a;
      display: flex; align-items: center; gap: 4px; flex: 1; min-width: 0;
    }
    .salon-card .sc-name .vc { color: #7c3aed; font-size: 0.7rem; flex-shrink: 0; }
    .salon-card .sc-rating-inline {
      display: flex; align-items: center; gap: 3px; font-size: 0.8rem;
      font-weight: 700; color: #1a1a1a; flex-shrink: 0;
    }
    .salon-card .sc-rating-inline .star { color: #ffc107; font-size: 0.75rem; }
    .salon-card .sc-addr { font-size: 0.75rem; color: #888; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .salon-card .sc-meta { font-size: 0.75rem; color: #888; }

    /* ── review scroll ── */
    .review-wrapper { overflow: hidden; padding: 0 16px; position: relative; }
    .review-scroll {
      display: flex; gap: 20px;
      animation: scrollReviews 30s linear infinite;
      width: max-content;
    }
    .review-scroll:hover { animation-play-state: paused; }
    .rev-card {
      flex: 0 0 260px; background: #f5f5f5; border-radius: 20px;
      padding: 1.2rem; display: flex; flex-direction: column;
      transition: transform .2s;
    }
    .rev-card:hover { transform: translateY(-4px); }
    .rev-card .rc-stars { color: #ffc107; font-size: 1rem; letter-spacing: 1px; margin-bottom: 6px; }
    .rev-card .rc-title { font-size: 0.95rem; font-weight: 700; color: #1a1a1a; margin-bottom: 6px; }
    .rev-card .rc-text { font-size: 0.82rem; color: #666; line-height: 1.6; flex-grow: 1; }
    .rev-card .rc-reviewer {
      display: flex; align-items: center; gap: 10px; margin-top: 14px;
      padding-top: 12px; border-top: 1px solid #e8e8e8;
    }
    .rev-card .rc-av {
      width: 38px; height: 38px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-weight: 700; font-size: 0.8rem; color: #fff; flex-shrink: 0;
    }
    .rev-card .rc-name { font-weight: 700; font-size: 0.82rem; color: #1a1a1a; }
    .rev-card .rc-loc { font-size: 0.7rem; color: #888; }

    @keyframes scrollReviews {
      0% { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }
    @media(min-width:768px) {
      .rev-card { flex: 0 0 300px; padding: 1.75rem; }
      .review-wrapper { padding: 0 32px; }
    }
    @media(max-width:480px) {
      .rev-card { flex: 0 0 220px; padding: 1rem; }
      .rev-card .rc-text { font-size: 0.75rem; }
    }

    /* ── stats ── */
    .stats-sec { padding: 40px 16px; text-align: center; background: #1a1a1a; }
    .stats-sec .st-title { font-size: 1.2rem; font-weight: 800; color: #fff; margin-bottom: 4px; }
    .stats-sec .st-sub { font-size: 0.85rem; color: #aaa; margin-bottom: 24px; }
    .stats-sec .big-num { font-size: 3rem; font-weight: 700; color: #E91E8C; letter-spacing: -3px; margin-bottom: 4px; }
    .stats-sec .big-lbl { font-size: 0.85rem; color: #aaa; margin-bottom: 30px; }
    .stats-sec .mini-stats { display: flex; justify-content: center; gap: 30px; flex-wrap: wrap; }
    .stats-sec .ms-num { font-size: 1.5rem; font-weight: 900; color: #fff; }
    .stats-sec .ms-lbl { font-size: 0.75rem; color: #aaa; }
    @media(min-width:768px) {
      .stats-sec { padding: 80px 32px; }
      .stats-sec .st-title { font-size: 2rem; }
      .stats-sec .big-num { font-size: 5rem; }
      .stats-sec .mini-stats { gap: 80px; }
      .stats-sec .ms-num { font-size: 2rem; }
    }

    /* ── city ── */
    .city-sec { background: #fafafa; padding: 40px 16px; }
    .city-sec h2 { font-size: 1.2rem; font-weight: 800; margin-bottom: 16px; }
    .city-tabs { display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 20px; }
    .city-tab-btn {
      border: 1.5px solid #e0e0e0; border-radius: 50px; padding: 5px 14px;
      font-size: 0.75rem; font-weight: 600; color: #555; background: #fff;
      cursor: pointer; transition: all 0.2s;
    }
    .city-tab-btn.active, .city-tab-btn:hover { background: #1a1a1a; color: #fff; border-color: #1a1a1a; }
    .city-grid {
      display: grid; grid-template-columns: repeat(2,1fr); gap: 1.2rem;
    }
    .city-col h6 { font-size: 0.8rem; font-weight: 700; margin-bottom: 8px; }
    .city-col a { display: block; font-size: 0.75rem; color: #666; padding: 2px 0; }
    .city-col a:hover { color: #E91E8C; }
    @media(min-width:576px) { .city-grid { grid-template-columns: repeat(3,1fr); } }
    @media(min-width:992px) { .city-grid { grid-template-columns: repeat(5,1fr); gap: 2.5rem; } }
    @media(min-width:768px) { .city-sec { padding: 60px 32px; } }

    /* ── biz ── */
    .biz-sec { background: #1a1a1a; padding: 40px 16px; }
    .biz-sec h2 { font-size: 1.4rem; font-weight: 900; color: #fff; margin-bottom: 12px; }
    .biz-sec p { color: #888; font-size: 0.85rem; margin-bottom: 20px; }
    .btn-biz { background: #fff; color: #1a1a1a; border-radius: 50px; padding: 12px 28px; font-weight: 700; display: inline-block; transition: all 0.2s; }
    .btn-biz:hover { background: #E91E8C; color: #fff; }
    @media(min-width:768px) { .biz-sec { padding: 80px 32px; } .biz-sec h2 { font-size: 2.2rem; } }

    /* ── footer ── */
    footer { background: #f8f5f7; color: #555; padding-top: 40px; border-top: 1px solid #f0e8ed; }
    footer h3 { font-family: 'Playfair Display', serif; font-size: 1.3rem; }
    footer h6 { font-size: 0.7rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 12px; }
    footer a { color: #777; text-decoration: none; font-size: 0.8rem; display: block; padding: 3px 0; }
    footer a:hover { color: #E91E8C; }
    footer .list-unstyled li { margin-bottom: 2px; }
    @media(min-width:768px) { footer { padding-top: 60px; } }
  </style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="g-nav">
  <a href="{{ route('home') }}" class="brand">
    <div class="brand-icon"><i class="fas fa-spa"></i></div>
    <span class="brand-text"><span class="pink">Beauty</span><span class="gold"> Blush</span><span class="pink"> Salons</span></span>
  </a>
  <div class="nav-right">
    @auth
      @if(Auth::user()->isAdmin())
        <a href="{{ route('admin.dashboard') }}" class="btn-nav-ghost">Dashboard</a>
      @elseif(Auth::user()->isOwner())
        <a href="{{ route('owner.dashboard') }}" class="btn-nav-ghost">My Salons</a>
      @else
        <a href="{{ route('client.dashboard') }}" class="btn-nav-ghost">My Account</a>
      @endif
      <form action="{{ route('logout') }}" method="POST" style="display:inline;">
        @csrf
        <button class="btn-nav-outline">Logout</button>
      </form>
    @else
      <a href="{{ route('login') }}" class="btn-nav-ghost">Login</a>
      <a href="{{ route('register.owner') }}" class="btn-nav-outline">List your business</a>
    @endauth
    <button class="btn-nav-menu" onclick="toggleMenuDropdown()">Menu <i class="fas fa-bars"></i></button>
    <div class="menu-dropdown" id="menuDropdown">
      <a href="{{ route('salons.index') }}">Find a salon</a>
      <a href="{{ route('services.index') }}">Services</a>
      <a href="{{ route('about') }}">About</a>
      <a href="{{ route('contact') }}">Contact</a>
      <a href="{{ route('register.owner') }}">List your business</a>
      @guest
        <a href="{{ route('login') }}" class="mobile-auth">Login</a>
        <a href="{{ route('register.owner') }}" class="mobile-auth">Register</a>
      @else
        <a href="{{ route('logout') }}" class="mobile-auth" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
      @endguest
    </div>
  </div>
</nav>

<!-- ===== HERO ===== -->
<section class="hero">
  <h1>Book premium beauty services</h1>
  <p>Discover top-rated salons, bridal studios, nail artists and beauty experts trusted by thousands across Pakistan</p>
  <form action="{{ route('salons.index') }}" method="GET" id="heroSearchForm" style="width:100%;max-width:900px;">
    <div class="search-pill" id="searchPill">
      <div class="sp-segment" onclick="toggleDropdown('treatmentsDD')">
        <i class="fas fa-search sp-icon"></i>
        <input type="text" name="search" id="treatmentInput" class="sp-field" placeholder="All treatments" readonly>
      </div>
      <div class="sp-dropdown" id="treatmentsDD">
        <div style="font-size:0.7rem;font-weight:700;color:#aaa;text-transform:uppercase;margin-bottom:6px;padding:0 4px;">Popular Services</div>
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
        @foreach($categories ?? [] as $cat)
        <div class="sp-dropdown-item" onclick="selectTreatment('{{ $cat->name }}')"><i class="fas fa-spa"></i> {{ $cat->name }}</div>
        @endforeach
      </div>

      <div class="sp-divider"></div>
      <div class="sp-segment" onclick="toggleDropdown('locationDD')">
        <i class="fas fa-map-marker-alt sp-icon" style="color:#E91E8C;"></i>
        <input type="text" name="city" id="locationInput" class="sp-field" placeholder="Current location" readonly>
      </div>
      <div class="sp-dropdown" id="locationDD">
        <div style="font-size:0.7rem;font-weight:700;color:#aaa;text-transform:uppercase;margin-bottom:6px;padding:0 4px;">Select City</div>
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
        <div style="font-size:0.65rem;font-weight:600;color:#aaa;margin-bottom:8px;letter-spacing:0.5px;">SELECT DATE & TIME</div>
        <div id="inlineCal"></div>
        <div style="margin-top:10px;">
          <div style="font-size:0.7rem;font-weight:600;margin-bottom:6px;">Preferred Time</div>
          <div style="display:flex;flex-wrap:wrap;gap:5px;">
            <button type="button" class="time-option-btn" onclick="selectTime('Morning')">Morning</button>
            <button type="button" class="time-option-btn" onclick="selectTime('Afternoon')">Afternoon</button>
            <button type="button" class="time-option-btn" onclick="selectTime('Evening')">Evening</button>
            <button type="button" class="time-option-btn" onclick="selectTime('Any time')">Any time</button>
          </div>
        </div>
        <button type="button" onclick="applyDateTime()" class="btn-search-pill" style="width:100%;border-radius:12px;margin-top:10px;height:44px;">Apply</button>
      </div>

      <button type="submit" class="btn-search-pill"><i class="fas fa-search"></i></button>
    </div>
  </form>
  <p class="hero-count"><strong>{{ number_format($totalBookings ?? 0) }}</strong> appointments booked today</p>
</section>

<!-- ===== RECOMMENDED ===== -->
<section class="g-section" style="background:#fff;padding-top:20px;">
  <div class="g-section-head">
    <h2>Recommended</h2>
    <a href="{{ route('salons.index') }}" class="see-all">See all <i class="fas fa-arrow-right"></i></a>
  </div>
  <div class="slider-outer">
    <button class="slider-arrow-btn left" onclick="slide('rec',-1)"><i class="fas fa-chevron-left"></i></button>
    <div class="slider-scroll-area" id="slider-rec">
      <div class="slider-track">
        @php $ratings = [5.0, 4.9, 4.8, 4.7]; @endphp
        @forelse(($featuredSalons ?? collect())->take(4) as $index => $salon)
        <a href="{{ route('salons.show', $salon->slug) }}" class="salon-card">
          <div class="sc-img">
            <img src="{{ asset('storage/images/salon'.($index+1).'.jpg') }}" alt="{{ $salon->name }}" loading="lazy">
            <span class="sc-badge">Featured</span>
          </div>
          <div class="sc-body">
            <div class="sc-name-row">
              <div class="sc-name">{{ $salon->name ?? 'Beauty Blush Elite' }} <i class="fas fa-check-circle vc"></i></div>
              <div class="sc-rating-inline"><i class="fas fa-star star"></i> {{ $ratings[$index % count($ratings)] }}</div>
            </div>
            <div class="sc-addr">{{ $salon->address ?? 'Main Boulevard Gulberg, Lahore' }}</div>
            <div class="sc-meta">{{ $salon->services->first()?->category?->name ?? 'Luxury Salon' }} · {{ rand(50,300) }} reviews</div>
          </div>
        </a>
        @empty
        <a href="#" class="salon-card"><div class="sc-img"><img src="{{ asset('storage/images/salon1.jpg') }}" alt="Salon" loading="lazy"></div><div class="sc-body"><div class="sc-name-row"><div class="sc-name">Beauty Blush Elite <i class="fas fa-check-circle vc"></i></div><div class="sc-rating-inline"><i class="fas fa-star star"></i> 5.0</div></div><div class="sc-addr">Main Boulevard Gulberg, Lahore</div><div class="sc-meta">Luxury Salon · 128 reviews</div></div></a>
        <a href="#" class="salon-card"><div class="sc-img"><img src="{{ asset('storage/images/salon9.jpg') }}" alt="Salon" loading="lazy"></div><div class="sc-body"><div class="sc-name-row"><div class="sc-name">Aura Beauty Studio <i class="fas fa-check-circle vc"></i></div><div class="sc-rating-inline"><i class="fas fa-star star"></i> 4.9</div></div><div class="sc-addr">DHA Phase 5, Lahore</div><div class="sc-meta">Spa · 89 reviews</div></div></a>
        <a href="#" class="salon-card"><div class="sc-img"><img src="{{ asset('storage/images/salon17.jpg') }}" alt="Salon" loading="lazy"></div><div class="sc-body"><div class="sc-name-row"><div class="sc-name">The Royal Glow Salon <i class="fas fa-check-circle vc"></i></div><div class="sc-rating-inline"><i class="fas fa-star star"></i> 4.8</div></div><div class="sc-addr">MM Alam Road, Lahore</div><div class="sc-meta">Bridal · 234 reviews</div></div></a>
        <a href="#" class="salon-card"><div class="sc-img"><img src="{{ asset('storage/images/salon25.jpg') }}" alt="Salon" loading="lazy"></div><div class="sc-body"><div class="sc-name-row"><div class="sc-name">Elegance Hair & Beauty Lounge <i class="fas fa-check-circle vc"></i></div><div class="sc-rating-inline"><i class="fas fa-star star"></i> 4.7</div></div><div class="sc-addr">Clifton Block 5, Karachi</div><div class="sc-meta">Hair Salon · 156 reviews</div></div></a>
        @endforelse
      </div>
    </div>
    <button class="slider-arrow-btn right" onclick="slide('rec',1)"><i class="fas fa-chevron-right"></i></button>
  </div>
</section>

<!-- ===== NEW TO BEAUTY BLUSH ===== -->
<section class="g-section" style="background:linear-gradient(180deg,#fff 0%,#fdf5fb 100%);">
  <div class="g-section-head">
    <h2>New to Beauty Blush</h2>
    <a href="{{ route('salons.index') }}" class="see-all">See all <i class="fas fa-arrow-right"></i></a>
  </div>
  <div class="slider-outer">
    <button class="slider-arrow-btn left" onclick="slide('newto',-1)"><i class="fas fa-chevron-left"></i></button>
    <div class="slider-scroll-area" id="slider-newto">
      <div class="slider-track">
        @forelse(($newSalons ?? collect())->take(4) as $index => $salon)
        <a href="{{ route('salons.show', $salon->slug) }}" class="salon-card">
          <div class="sc-img"><img src="{{ asset('storage/images/salon'.($index+11).'.jpg') }}" alt="{{ $salon->name }}" loading="lazy"></div>
          <div class="sc-body">
            <div class="sc-name-row"><div class="sc-name">{{ $salon->name ?? 'New Style Studio' }} <i class="fas fa-check-circle vc"></i></div></div>
            <div class="sc-addr">{{ $salon->address ?? 'Johar Town, Lahore' }}</div>
            <div class="sc-meta">New · {{ rand(10,100) }} reviews</div>
          </div>
        </a>
        @empty
        <a href="#" class="salon-card"><div class="sc-img"><img src="{{ asset('storage/images/salon11.jpg') }}" alt="Salon" loading="lazy"></div><div class="sc-body"><div class="sc-name-row"><div class="sc-name">New Style Studio <i class="fas fa-check-circle vc"></i></div></div><div class="sc-addr">Johar Town, Lahore</div><div class="sc-meta">New · 45 reviews</div></div></a>
        <a href="#" class="salon-card"><div class="sc-img"><img src="{{ asset('storage/images/salon20.jpg') }}" alt="Salon" loading="lazy"></div><div class="sc-body"><div class="sc-name-row"><div class="sc-name">Urban Nails & Spa <i class="fas fa-check-circle vc"></i></div></div><div class="sc-addr">Gulshan, Karachi</div><div class="sc-meta">New · 32 reviews</div></div></a>
        <a href="#" class="salon-card"><div class="sc-img"><img src="{{ asset('storage/images/salon13.jpg') }}" alt="Salon" loading="lazy"></div><div class="sc-body"><div class="sc-name-row"><div class="sc-name">Bliss Beauty Bar <i class="fas fa-check-circle vc"></i></div></div><div class="sc-addr">F-7, Islamabad</div><div class="sc-meta">New · 28 reviews</div></div></a>
        <a href="#" class="salon-card"><div class="sc-img"><img src="{{ asset('storage/images/salon29.jpg') }}" alt="Salon" loading="lazy"></div><div class="sc-body"><div class="sc-name-row"><div class="sc-name">The Makeup Loft <i class="fas fa-check-circle vc"></i></div></div><div class="sc-addr">Saddar, Rawalpindi</div><div class="sc-meta">New · 67 reviews</div></div></a>
        @endforelse
      </div>
    </div>
    <button class="slider-arrow-btn right" onclick="slide('newto',1)"><i class="fas fa-chevron-right"></i></button>
  </div>
</section>

<!-- ===== TRENDING ===== -->
<section class="g-section" style="background:#fff;">
  <div class="g-section-head">
    <h2>Trending</h2>
    <a href="{{ route('salons.index') }}" class="see-all">See all <i class="fas fa-arrow-right"></i></a>
  </div>
  <div class="slider-outer">
    <button class="slider-arrow-btn left" onclick="slide('trending',-1)"><i class="fas fa-chevron-left"></i></button>
    <div class="slider-scroll-area" id="slider-trending">
      <div class="slider-track">
        @forelse(($topRatedSalons ?? collect())->take(4) as $index => $salon)
        <a href="{{ route('salons.show', $salon->slug) }}" class="salon-card">
          <div class="sc-img"><img src="{{ asset('storage/images/salon'.($index+13).'.jpg') }}" alt="{{ $salon->name }}" loading="lazy"></div>
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
        <a href="#" class="salon-card"><div class="sc-img"><img src="{{ asset('storage/images/salon21.jpg') }}" alt="Salon" loading="lazy"></div><div class="sc-body"><div class="sc-name-row"><div class="sc-name">Trending Now <i class="fas fa-check-circle vc"></i></div><div class="sc-rating-inline"><i class="fas fa-star star"></i> 4.9</div></div><div class="sc-addr">Liberty Market, Lahore</div><div class="sc-meta">234 reviews</div></div></a>
        <a href="#" class="salon-card"><div class="sc-img"><img src="{{ asset('storage/images/salon22.jpg') }}" alt="Salon" loading="lazy"></div><div class="sc-body"><div class="sc-name-row"><div class="sc-name">Vogue Beauty Lounge <i class="fas fa-check-circle vc"></i></div><div class="sc-rating-inline"><i class="fas fa-star star"></i> 4.8</div></div><div class="sc-addr">Clifton, Karachi</div><div class="sc-meta">189 reviews</div></div></a>
        <a href="#" class="salon-card"><div class="sc-img"><img src="{{ asset('storage/images/salon23.jpg') }}" alt="Salon" loading="lazy"></div><div class="sc-body"><div class="sc-name-row"><div class="sc-name">Elegance Salon <i class="fas fa-check-circle vc"></i></div><div class="sc-rating-inline"><i class="fas fa-star star"></i> 4.7</div></div><div class="sc-addr">F-10, Islamabad</div><div class="sc-meta">156 reviews</div></div></a>
        <a href="#" class="salon-card"><div class="sc-img"><img src="{{ asset('storage/images/salon24.jpg') }}" alt="Salon" loading="lazy"></div><div class="sc-body"><div class="sc-name-row"><div class="sc-name">Style Studio <i class="fas fa-check-circle vc"></i></div><div class="sc-rating-inline"><i class="fas fa-star star"></i> 4.6</div></div><div class="sc-addr">Saddar, Rawalpindi</div><div class="sc-meta">102 reviews</div></div></a>
        @endforelse
      </div>
    </div>
    <button class="slider-arrow-btn right" onclick="slide('trending',1)"><i class="fas fa-chevron-right"></i></button>
  </div>
</section>

<!-- ===== REVIEWS ===== -->
<section class="g-section" style="background:#fff;">
  <div class="g-section-head">
    <h2>Reviews</h2>
  </div>
  <div class="review-wrapper">
    <div class="review-scroll" id="reviewScroll">
      @php
        $reviews = [
          ['The best booking system','Great experience, easy to book. Paying for treatments is so convenient — no cash needed! Beauty Blush made everything seamless.','Ayesha K.','Lahore, Pakistan','#E91E8C'],
          ['Easy to use & explore','Beauty Blush\'s reminders make life so much easier. I found amazing salons I didn\'t know existed. Highly recommend to everyone!','Sana M.','Karachi, Pakistan','#9333ea'],
          ['Great for finding salons','I\'ve been using Beauty Blush for months and it\'s by far the best booking platform I\'ve used. The waitlist feature is brilliant!','Fatima R.','Islamabad, Pakistan','#0ea5e9'],
          ['My go-to for beauty','Beauty Blush is my go-to app for salon bookings. I can easily find and book places near me — absolutely love it!','Hina B.','Lahore, Pakistan','#10b981'],
          ['Absolutely fabulous!','The best salon experience I\'ve ever had. Booked through Beauty Blush and everything was perfect from start to finish.','Zara A.','Karachi, Pakistan','#f43f5e'],
          ['Seamless booking','Love how easy it is to book and reschedule. The platform is super intuitive and the salons are top-notch.','Maha N.','Islamabad, Pakistan','#8b5cf6'],
        ];
      @endphp
      @foreach($reviews as [$title,$text,$name,$loc,$color])
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
      @endforeach
      @foreach($reviews as [$title,$text,$name,$loc,$color])
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
      @endforeach
    </div>
  </div>
</section>

<!-- ===== STATS ===== -->
<section class="stats-sec">
  <h2 class="st-title">The top-rated destination for beauty in Pakistan</h2>
  <p class="st-sub">One platform. Trusted by the best in the beauty industry.</p>
  <div class="big-num">{{ number_format($totalBookings ?? 0) }}+</div>
  <div class="big-lbl">appointments booked on Beauty Blush</div>
  <div class="mini-stats">
    <div><div class="ms-num">{{ number_format($totalSalons ?? 0) }}+</div><div class="ms-lbl">partner salons</div></div>
    <div><div class="ms-num">50+</div><div class="ms-lbl">cities covered</div></div>
    <div><div class="ms-num">{{ number_format($totalClients ?? 0) }}+</div><div class="ms-lbl">happy clients</div></div>
  </div>
</section>

<!-- ===== BROWSE BY CITY ===== -->
<section class="city-sec">
  <h2>Browse by City</h2>
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

<!-- ===== GROW YOUR BUSINESS ===== -->
<section class="biz-sec">
  <div class="container"><div class="row"><div class="col-lg-7">
    <h2>Grow your salon business with Beauty Blush</h2>
    <p>Join thousands of salon owners across Pakistan who manage bookings, payments, and clients from one beautiful dashboard. Completely free to register.</p>
    <a href="{{ route('register.owner') }}" class="btn-biz">List your business — it's free</a>
  </div></div></div>
</section>

<!-- ===== FOOTER ===== -->
<footer>
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-3 col-md-6">
        <h3><span style="color:#E91E8C;">Beauty</span><span style="color:#C9A96E;"> Blush</span><span style="color:#E91E8C;"> Salons</span></h3>
        <p style="color:#777; line-height:1.6; font-size:0.85rem;">Pakistan's premium multi-salon booking platform. Discover top salons, book appointments, and experience beauty like never before.</p>
        <p style="color:#888; font-size:0.75rem;"><i class="fas fa-map-marker-alt" style="color:#E91E8C; width:16px;"></i> Gulberg III, Lahore, Pakistan</p>
        <p style="color:#888; font-size:0.75rem;"><i class="fas fa-phone" style="color:#E91E8C; width:16px;"></i> <a href="tel:+923001234567" style="color:#888;">+92 300 1234567</a></p>
        <p style="color:#888; font-size:0.75rem;"><i class="fas fa-envelope" style="color:#E91E8C; width:16px;"></i> <a href="mailto:hello@beautyblush.pk" style="color:#888;">hello@beautyblush.pk</a></p>
      </div>
      <div class="col-lg-2 col-md-6">
        <h6 style="color:#E91E8C;">Quick Links</h6>
        <ul class="list-unstyled"><li><a href="{{ route('home') }}">Home</a></li><li><a href="{{ route('salons.index') }}">Salons</a></li><li><a href="{{ route('about') }}">About</a></li><li><a href="{{ route('contact') }}">Contact</a></li><li><a href="{{ route('register.owner') }}">List Your Salon</a></li></ul>
      </div>
      <div class="col-lg-2 col-md-6">
        <h6 style="color:#C9A96E;">For Business</h6>
        <ul class="list-unstyled"><li><a href="{{ route('register.owner') }}">List Your Salon</a></li><li><a href="{{ route('partner') }}">Partner With Us</a></li><li><a href="/pricing">Pricing</a></li><li><a href="/support">Support</a></li><li><a href="/faq">FAQ</a></li></ul>
      </div>
      <div class="col-lg-2 col-md-6">
        <h6 style="color:#E91E8C;">Follow Us</h6>
        <ul class="list-unstyled"><li><a href="#"><i class="fab fa-facebook-f" style="width:18px;color:#E91E8C;"></i> Facebook</a></li><li><a href="#"><i class="fab fa-instagram" style="width:18px;color:#E91E8C;"></i> Instagram</a></li><li><a href="#"><i class="fab fa-tiktok" style="width:18px;color:#E91E8C;"></i> TikTok</a></li><li><a href="#"><i class="fab fa-youtube" style="width:18px;color:#E91E8C;"></i> YouTube</a></li><li><a href="#"><i class="fab fa-twitter" style="width:18px;color:#E91E8C;"></i> Twitter</a></li></ul>
      </div>
      <div class="col-lg-3 col-md-6">
        <h6 style="color:#C9A96E;">Legal</h6>
        <ul class="list-unstyled"><li><a href="{{ route('privacy') }}">Privacy Policy</a></li><li><a href="{{ route('terms') }}">Terms &amp; Conditions</a></li><li><a href="{{ route('terms.of.use') }}">Terms of Use</a></li><li><a href="{{ route('cookies') }}">Cookie Policy</a></li><li><a href="{{ route('about') }}">About Us</a></li></ul>
      </div>
    </div>
  </div>
  <div style="border-top:1px solid #f0e8ed; margin-top:30px; padding:16px 0;">
    <div class="container"><p style="text-align:center; color:#aaa; font-size:0.75rem; margin:0;">&copy; {{ date('Y') }} <span style="color:#E91E8C; font-weight:600;">Beauty Blush Salons</span> — All rights reserved.</p></div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  function slide(id, dir) {
    const el = document.getElementById('slider-' + id);
    const firstCard = el.querySelector('.salon-card');
    const cardWidth = firstCard ? firstCard.offsetWidth + 16 : 280;
    el.scrollBy({ left: dir * cardWidth * 3, behavior: 'smooth' });
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
    if (!hidden) { hidden = document.createElement('input'); hidden.type = 'hidden'; hidden.name = 'search'; document.getElementById('heroSearchForm').appendChild(hidden); }
    hidden.value = name;
  }

  function selectCity(city) {
    document.getElementById('locationInput').value = city;
    document.getElementById('locationDD').classList.remove('show');
    let hidden = document.querySelector('input[name="city"]');
    if (!hidden) { hidden = document.createElement('input'); hidden.type = 'hidden'; hidden.name = 'city'; document.getElementById('heroSearchForm').appendChild(hidden); }
    hidden.value = city;
  }

  let selectedDate = '', selectedTime = '';

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
      b.style.borderColor = '#e0e0e0'; b.style.color = '#333'; b.style.background = '#fff';
    });
    if (event && event.target) {
      event.target.style.borderColor = '#E91E8C';
      event.target.style.color = '#E91E8C';
      event.target.style.background = 'rgba(233,30,140,0.05)';
    }
  }

  function updateDateTimeDisplay() {
    const parts = [];
    if (selectedDate) { const d = new Date(selectedDate); parts.push(d.toLocaleDateString('en-PK', { day:'numeric', month:'short' })); }
    if (selectedTime && selectedTime !== 'Any time') parts.push(selectedTime);
    document.getElementById('dateTimeInput').value = parts.join(' · ') || '';
  }

  function applyDateTime() { updateDateTimeDisplay(); document.getElementById('timeDD').classList.remove('show'); }

  function switchCity(btn, city) {
    document.querySelectorAll('.city-tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    if (city !== 'All') window.location = '{{ route("salons.index") }}?city=' + encodeURIComponent(city);
  }

  function toggleMenuDropdown() {
    document.getElementById('menuDropdown').classList.toggle('show');
  }

  document.addEventListener('click', function(e) {
    if (!e.target.closest('.search-pill')) document.querySelectorAll('.sp-dropdown').forEach(d => d.classList.remove('show'));
    if (!e.target.closest('.btn-nav-menu') && !e.target.closest('.menu-dropdown')) document.getElementById('menuDropdown')?.classList.remove('show');
  });
</script>
</body>
</html>