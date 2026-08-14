<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Services — Beauty Blush Salons</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,700;0,800;1,700&display=swap" rel="stylesheet" />
  <style>
    /* ── reset & base ── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; color: #1a1a1a; background: #fff; -webkit-font-smoothing: antialiased; overflow-x: hidden; }
    a { text-decoration: none; color: inherit; }
 
    /* ── navbar (same as home) ── */
    .g-nav {
      background: rgba(255,255,255,0.97); backdrop-filter: blur(10px);
      border-bottom: 1px solid #fce4ec; padding: 0 16px; height: 60px;
      display: flex; align-items: center; justify-content: space-between;
      position: sticky; top: 0; z-index: 1000; box-shadow: 0 2px 20px rgba(233,30,140,0.06);
    }
    .g-nav .brand { display: flex; align-items: center; gap: 8px; }
    .g-nav .brand .brand-icon {
      width: 36px; height: 36px; background: linear-gradient(135deg, #E91E8C, #C9A96E);
      border-radius: 10px; display: flex; align-items: center; justify-content: center;
      box-shadow: 0 4px 14px rgba(233,30,140,0.25);
    }
    .g-nav .brand .brand-icon i { color: #fff; font-size: 1rem; }
    .g-nav .brand .brand-text { font-family: 'Playfair Display', serif; font-size: 1.2rem; font-weight: 800; letter-spacing: -0.3px; }
    .g-nav .brand .brand-text .pink { color: #E91E8C; }
    .g-nav .brand .brand-text .gold { color: #C9A96E; }
    .g-nav .nav-right { display: flex; align-items: center; gap: 6px; }
    .btn-nav-ghost, .btn-nav-outline {
      font-size: 0.7rem; padding: 5px 12px; border-radius: 50px; border: 1.5px solid #1a1a1a;
      background: transparent; font-weight: 600; cursor: pointer; transition: all 0.2s ease; display: none;
    }
    .btn-nav-ghost { color: #1a1a1a; }
    .btn-nav-outline { background: #1a1a1a; color: #fff; }
    .btn-nav-ghost:hover, .btn-nav-outline:hover { opacity: 0.8; }
    .btn-nav-menu {
      background: #fff; border: 1.5px solid #e0e0e0; color: #1a1a1a; font-size: 0.75rem; font-weight: 600;
      padding: 5px 12px; border-radius: 50px; display: flex; align-items: center; gap: 6px;
      cursor: pointer; transition: all 0.2s ease;
    }
    .btn-nav-menu:hover { background: #1a1a1a; color: #fff; }
    .menu-dropdown {
      position: absolute; top: 100%; right: 0; background: #fff; border-radius: 16px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.12); border: 1px solid #eee; padding: 10px 0;
      z-index: 9999; min-width: 200px; display: none;
    }
    .menu-dropdown.show { display: block; }
    .menu-dropdown a { display: block; padding: 10px 20px; font-size: 0.85rem; font-weight: 500; color: #333; transition: all 0.2s; }
    .menu-dropdown a.active { color: #E91E8C; background: #fdf5fb; }
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
 
    /* ── shared reveal animation ── */
    .reveal-up { opacity: 0; transform: translateY(28px); transition: opacity .7s cubic-bezier(.22,1,.36,1), transform .7s cubic-bezier(.22,1,.36,1); }
    .reveal-up.in-view { opacity: 1; transform: translateY(0); }
    .reveal-stagger > * { opacity: 0; transform: translateY(20px); }
    .reveal-stagger.in-view > * { animation: staggerUp .6s cubic-bezier(.22,1,.36,1) both; }
    .reveal-stagger.in-view > *:nth-child(1){animation-delay:.05s;} .reveal-stagger.in-view > *:nth-child(2){animation-delay:.13s;}
    .reveal-stagger.in-view > *:nth-child(3){animation-delay:.21s;} .reveal-stagger.in-view > *:nth-child(4){animation-delay:.29s;}
    .reveal-stagger.in-view > *:nth-child(5){animation-delay:.37s;} .reveal-stagger.in-view > *:nth-child(6){animation-delay:.45s;}
    .reveal-stagger.in-view > *:nth-child(7){animation-delay:.53s;} .reveal-stagger.in-view > *:nth-child(8){animation-delay:.61s;}
    @keyframes staggerUp { to { opacity: 1; transform: translateY(0); } }
    @media (prefers-reduced-motion: reduce) { .reveal-up, .reveal-stagger > * { opacity:1 !important; transform:none !important; animation:none !important; transition:none !important; } }
 
    .eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: #E91E8C; margin-bottom: 8px; display: block; }
    .section-title-center { text-align: center; padding: 0 16px; margin-bottom: 28px; }
    .section-title-center h2 { font-size: 1.5rem; font-weight: 800; color: #1a1a1a; margin-bottom: 8px; }
    .section-title-center p { font-size: 0.88rem; color: #777; max-width: 560px; margin: 0 auto; }
    @media(min-width:768px) { .section-title-center h2 { font-size: 2.1rem; } .section-title-center p { font-size: 1rem; } }
 
    /* ── page hero ── */
    .svc-hero {
      background: linear-gradient(145deg, #ede8f5 0%, #f5e6f5 20%, #fce8f3 55%, #fdf5fb 85%, #fff 100%);
      padding: 50px 16px 40px; text-align: center;
    }
    .svc-hero .eyebrow { justify-content: center; }
    .svc-hero h1 { font-size: 1.9rem; font-weight: 900; letter-spacing: -1px; margin-bottom: 14px; line-height: 1.15; }
    .svc-hero p { font-size: 0.92rem; color: #666; max-width: 560px; margin: 0 auto 26px; line-height: 1.5; }
    .svc-search {
      display: flex; align-items: center; gap: 8px; background: #fff; max-width: 560px; margin: 0 auto;
      border-radius: 50px; padding: 6px 6px 6px 20px; box-shadow: 0 6px 30px rgba(0,0,0,0.1);
    }
    .svc-search input { flex: 1; border: none; outline: none; font-size: 0.88rem; font-family: 'Inter',sans-serif; padding: 8px 0; }
    .svc-search input::placeholder { color: #999; }
    .svc-search button {
      background: #1a1a1a; color: #fff; border: none; border-radius: 50px; width: 42px; height: 42px;
      flex-shrink: 0; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background .2s;
    }
    .svc-search button:hover { background: #E91E8C; }
    @media(min-width:768px) { .svc-hero { padding: 84px 32px 64px; } .svc-hero h1 { font-size: 2.8rem; } .svc-hero p { font-size: 1.05rem; } }
 
    /* ── category filter chips ── */
    .svc-chips-sec { padding: 26px 16px 6px; }
    .svc-chips { display: flex; gap: 8px; overflow-x: auto; scrollbar-width: none; padding-bottom: 4px; max-width: 1100px; margin: 0 auto; }
    .svc-chips::-webkit-scrollbar { display: none; }
    .svc-chip {
      flex-shrink: 0; border: 1.5px solid #eee; background: #fff; border-radius: 50px; padding: 7px 16px;
      font-size: 0.78rem; font-weight: 600; color: #555; cursor: pointer; transition: all .2s;
    }
    .svc-chip.active, .svc-chip:hover { background: #1a1a1a; color: #fff; border-color: #1a1a1a; }
    @media(min-width:768px) { .svc-chips-sec { padding: 34px 32px 6px; } }
 
    /* ── category grid ── */
    .cat-sec { padding: 40px 16px 50px; background: #fff; }
    .cat-grid { display: grid; grid-template-columns: 1fr; gap: 16px; max-width: 1150px; margin: 0 auto; }
    .cat-card {
      display: flex; align-items: center; gap: 16px; background: #fdf5fb; border: 1px solid #f2d9e8;
      border-radius: 20px; padding: 18px; transition: all .3s cubic-bezier(.22,1,.36,1); cursor: pointer;
    }
    .cat-card:hover { transform: translateY(-5px); box-shadow: 0 16px 32px rgba(233,30,140,0.14); border-color: #E91E8C; }
    .cat-card .cc-icon {
      width: 58px; height: 58px; border-radius: 50%; flex-shrink: 0; background: #fff;
      display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(233,30,140,0.1);
      transition: all .3s ease;
    }
    .cat-card:hover .cc-icon { background: linear-gradient(135deg,#E91E8C,#C9A96E); }
    .cat-card .cc-icon i { color: #E91E8C; font-size: 1.2rem; transition: color .3s ease; }
    .cat-card:hover .cc-icon i { color: #fff; }
    .cat-card .cc-body { flex: 1; min-width: 0; }
    .cat-card h3 { font-size: 0.98rem; font-weight: 700; color: #1a1a1a; margin-bottom: 3px; }
    .cat-card .cc-meta { font-size: 0.75rem; color: #888; }
    .cat-card .cc-meta .dot { margin: 0 6px; }
    .cat-card .cc-from { font-size: 0.72rem; font-weight: 700; color: #C9A96E; }
    .cat-card .cc-arrow { color: #ccc; flex-shrink: 0; transition: transform .3s ease, color .3s ease; }
    .cat-card:hover .cc-arrow { transform: translateX(4px); color: #E91E8C; }
    @media(min-width:576px) { .cat-grid { grid-template-columns: repeat(2,1fr); } }
    @media(min-width:992px) { .cat-grid { grid-template-columns: repeat(3,1fr); gap: 20px; } }
    @media(min-width:768px) { .cat-sec { padding: 60px 32px 70px; } }
 
    /* ── popular services spotlight ── */
    .pop-sec { background: linear-gradient(180deg,#fdf5fb 0%,#fff 100%); padding: 50px 16px; }
    .pop-grid { display: grid; grid-template-columns: 1fr; gap: 18px; max-width: 1150px; margin: 0 auto; }
    .pop-card { display: block; }
    .pop-card .pc-img {
      width: 100%; aspect-ratio: 4/3; border-radius: 18px; overflow: hidden; background: #f0f0f0;
      position: relative; transition: transform .3s cubic-bezier(.22,1,.36,1), box-shadow .3s ease;
    }
    .pop-card:hover .pc-img { transform: translateY(-8px); box-shadow: 0 18px 32px rgba(0,0,0,0.18); }
    .pop-card .pc-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .pop-card .pc-tag {
      position: absolute; top: 10px; left: 10px; background: rgba(255,255,255,0.95); font-size: 0.68rem;
      font-weight: 700; color: #E91E8C; padding: 3px 10px; border-radius: 20px; backdrop-filter: blur(6px);
    }
    .pop-card .pc-body { padding: 12px 2px 0; }
    .pop-card h4 { font-size: 0.92rem; font-weight: 700; color: #1a1a1a; margin-bottom: 4px; }
    .pop-card .pc-row { display: flex; align-items: center; justify-content: space-between; font-size: 0.78rem; color: #888; }
    .pop-card .pc-price { font-weight: 700; color: #1a1a1a; }
    @media(min-width:576px) { .pop-grid { grid-template-columns: repeat(2,1fr); } }
    @media(min-width:992px) { .pop-grid { grid-template-columns: repeat(4,1fr); gap: 24px; } }
    @media(min-width:768px) { .pop-sec { padding: 80px 32px; } }
 
    /* ── CTA ── */
    .svc-cta {
      background: radial-gradient(circle at 15% 20%, rgba(233,30,140,0.35) 0%, transparent 45%),
                  radial-gradient(circle at 85% 80%, rgba(201,169,110,0.28) 0%, transparent 50%), #1a1a1a;
      padding: 46px 16px; text-align: center;
    }
    .svc-cta h2 { color: #fff; font-size: 1.4rem; font-weight: 900; margin-bottom: 10px; }
    .svc-cta p { color: #aaa; font-size: 0.86rem; max-width: 460px; margin: 0 auto 22px; line-height: 1.6; }
    .btn-cta { background: #fff; color: #1a1a1a; border-radius: 50px; padding: 12px 28px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; transition: all .25s ease; }
    .btn-cta:hover { background: #E91E8C; color: #fff; transform: translateY(-2px); }
    @media(min-width:768px) { .svc-cta { padding: 70px 32px; } .svc-cta h2 { font-size: 2rem; } .svc-cta p { font-size: 0.95rem; } }
 
    /* ── footer (same as home) ── */
    footer { background: #f8f5f7; color: #555; padding-top: 60px; border-top: 1px solid #f0e8ed; }
    footer h3 { font-family: 'Playfair Display', serif; font-size: 1.3rem; }
    footer h6 { font-size: 0.7rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 12px; }
    footer a { color: #777; text-decoration: none; font-size: 0.8rem; display: block; padding: 3px 0; }
    footer a:hover { color: #E91E8C; }
    footer .list-unstyled li { margin-bottom: 2px; }
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
      <a href="{{ route('services.index') }}" class="active">Services</a>
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
 
<!-- ===== PAGE HERO ===== -->
<section class="svc-hero">
  <span class="eyebrow">All Services</span>
  <h1>Every treatment, all in one place</h1>
  <p>From a quick trim to full bridal glam — browse services across every category and book with a verified salon near you.</p>
  <form action="{{ route('services.index') }}" method="GET" class="svc-search">
    <i class="fas fa-search" style="color:#999;"></i>
    <input type="text" name="search" placeholder="Search for a service — e.g. Hair Color, Bridal Makeup">
    <button type="submit"><i class="fas fa-arrow-right"></i></button>
  </form>
</section>
 
<!-- ===== FILTER CHIPS ===== -->
<section class="svc-chips-sec">
  <div class="svc-chips">
    <button class="svc-chip active" onclick="filterChip(this,'all')">All</button>
    <button class="svc-chip" onclick="filterChip(this,'hair')">Hair</button>
    <button class="svc-chip" onclick="filterChip(this,'nails')">Nails</button>
    <button class="svc-chip" onclick="filterChip(this,'bridal')">Bridal</button>
    <button class="svc-chip" onclick="filterChip(this,'makeup')">Makeup</button>
    <button class="svc-chip" onclick="filterChip(this,'spa')">Spa &amp; Massage</button>
    <button class="svc-chip" onclick="filterChip(this,'skincare')">Skincare</button>
    <button class="svc-chip" onclick="filterChip(this,'mehndi')">Mehndi</button>
    <button class="svc-chip" onclick="filterChip(this,'men')">Men's Grooming</button>
  </div>
</section>
 
<!-- ===== CATEGORY GRID ===== -->
<section class="cat-sec">
  <div class="section-title-center reveal-up">
    <span class="eyebrow">Browse by Category</span>
    <h2>Find the right treatment</h2>
    <p>Tap a category to see every salon in Pakistan that offers it, sorted by rating.</p>
  </div>
  <div class="cat-grid reveal-stagger">
    @php
      $svcCategories = $categories ?? collect([
        (object)['name'=>'Hair Styling & Color','icon'=>'fa-cut','slug'=>'hair','salon_count'=>142,'from_price'=>800],
        (object)['name'=>'Nail Art & Care','icon'=>'fa-hand-sparkles','slug'=>'nails','salon_count'=>96,'from_price'=>500],
        (object)['name'=>'Bridal Packages','icon'=>'fa-ring','slug'=>'bridal','salon_count'=>78,'from_price'=>15000],
        (object)['name'=>'Makeup & Glam','icon'=>'fa-brush','slug'=>'makeup','salon_count'=>110,'from_price'=>2000],
        (object)['name'=>'Spa & Massage','icon'=>'fa-spa','slug'=>'spa','salon_count'=>64,'from_price'=>1500],
        (object)['name'=>'Facial & Skincare','icon'=>'fa-face-smile','slug'=>'skincare','salon_count'=>88,'from_price'=>1200],
        (object)['name'=>'Mehndi Design','icon'=>'fa-palette','slug'=>'mehndi','salon_count'=>52,'from_price'=>700],
        (object)['name'=>"Men's Grooming",'icon'=>'fa-user-tie','slug'=>'men','salon_count'=>70,'from_price'=>600],
        (object)['name'=>'Eyelash & Brows','icon'=>'fa-eye','slug'=>'lashes','salon_count'=>45,'from_price'=>900],
      ]);
    @endphp
    @foreach($svcCategories as $cat)
    <a href="{{ route('salons.index') }}?category={{ $cat->slug }}" class="cat-card">
      <div class="cc-icon"><i class="fas {{ $cat->icon }}"></i></div>
      <div class="cc-body">
        <h3>{{ $cat->name }}</h3>
        <div class="cc-meta">{{ $cat->salon_count }} salons <span class="dot">·</span> <span class="cc-from">From Rs. {{ number_format($cat->from_price) }}</span></div>
      </div>
      <i class="fas fa-chevron-right cc-arrow"></i>
    </a>
    @endforeach
  </div>
</section>
 
<!-- ===== POPULAR SERVICES SPOTLIGHT ===== -->
<section class="pop-sec">
  <div class="section-title-center reveal-up">
    <span class="eyebrow">Trending Now</span>
    <h2>Popular services this week</h2>
    <p>The treatments clients are booking the most, right now.</p>
  </div>
  <div class="pop-grid reveal-stagger">
    @php
      $popularServices = $popularServices ?? [
        ['name'=>'Balayage Hair Color','duration'=>'2h 30m','price'=>'Rs. 6,500','img'=>1,'tag'=>'Trending'],
        ['name'=>'Bridal Makeup + Hair','duration'=>'3h','price'=>'Rs. 18,000','img'=>13,'tag'=>'Popular'],
        ['name'=>'Classic Gel Manicure','duration'=>'45m','price'=>'Rs. 1,200','img'=>9,'tag'=>'Trending'],
        ['name'=>'Deep Cleansing Facial','duration'=>'1h','price'=>'Rs. 2,800','img'=>17,'tag'=>'Popular'],
      ];
    @endphp
    @foreach($popularServices as $svc)
    <a href="{{ route('salons.index') }}" class="pop-card">
      <div class="pc-img">
        <img src="{{ asset('storage/images/salon'.$svc['img'].'.jpg') }}" alt="{{ $svc['name'] }}" loading="lazy">
        <span class="pc-tag">{{ $svc['tag'] }}</span>
      </div>
      <div class="pc-body">
        <h4>{{ $svc['name'] }}</h4>
        <div class="pc-row"><span>{{ $svc['duration'] }}</span><span class="pc-price">{{ $svc['price'] }}</span></div>
      </div>
    </a>
    @endforeach
  </div>
</section>
 
<!-- ===== CTA ===== -->
<section class="svc-cta">
  <h2>Can't find what you're looking for?</h2>
  <p>Browse the full salon directory or get in touch — we'll help you find the right treatment.</p>
  <a href="{{ route('salons.index') }}" class="btn-cta">Browse all salons <i class="fas fa-arrow-right"></i></a>
</section>
 
<!-- ===== FOOTER (same as home) ===== -->
<footer>
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <h3 class="fw-bold mb-3" style="font-family:'Playfair Display',serif; font-size:1.6rem; margin-top:0; padding-top:0;">
                    <span style="color:#E91E8C;">Beauty</span><span style="color:#C9A96E;"> Blush</span><span style="color:#E91E8C;"> Salons</span>
                </h3>
                <p style="color:#777; line-height:1.8; font-size:0.88rem;">Pakistan's premium multi-salon booking platform. Discover top salons, book appointments, and experience beauty like never before.</p>
                <p style="color:#888; font-size:0.78rem; margin-top:10px; display:flex; align-items:center; gap:8px;"><i class="fas fa-map-marker-alt" style="color:#E91E8C; width:16px; text-align:center;"></i><span>Gulberg III, Lahore, Pakistan</span></p>
                <p style="color:#888; font-size:0.78rem; margin-bottom:4px; display:flex; align-items:center; gap:8px;"><i class="fas fa-phone" style="color:#E91E8C; width:16px; text-align:center;"></i><a href="tel:+923001234567" style="color:#888;">+92 300 1234567</a></p>
                <p style="color:#888; font-size:0.78rem; display:flex; align-items:center; gap:8px;"><i class="fas fa-envelope" style="color:#E91E8C; width:16px; text-align:center;"></i><a href="mailto:hello@beautyblush.pk" style="color:#888;">hello@beautyblush.pk</a></p>
            </div>
            <div class="col-lg-2 col-md-6" style="padding-top:8px;">
                <h6 class="fw-bold mb-4" style="color:#E91E8C; letter-spacing:1px; text-transform:uppercase; font-size:0.78rem;">Quick Links</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('home') }}">Home</a></li>
                    <li class="mb-2"><a href="{{ route('salons.index') }}">Salons</a></li>
                    <li class="mb-2"><a href="{{ route('services.index') }}">Services</a></li>
                    <li class="mb-2"><a href="{{ route('about') }}">About</a></li>
                    <li class="mb-2"><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-6" style="padding-top:8px;">
                <h6 class="fw-bold mb-4" style="color:#C9A96E; letter-spacing:1px; text-transform:uppercase; font-size:0.78rem;">For Business</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('register.owner') }}">List Your Salon</a></li>
                    <li class="mb-2"><a href="/pricing">Pricing</a></li>
                    <li class="mb-2"><a href="/support">Support</a></li>
                    <li class="mb-2"><a href="/faq">FAQ</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-6" style="padding-top:8px;">
                <h6 class="fw-bold mb-4" style="color:#E91E8C; letter-spacing:1px; text-transform:uppercase; font-size:0.78rem;">Follow Us</h6>
                <ul class="list-unstyled" style="display:flex; flex-direction:column; gap:6px;">
                    <li><a href="#" style="display:flex; align-items:center; gap:10px;"><i class="fab fa-facebook-f" style="width:20px; color:#E91E8C;"></i> Facebook</a></li>
                    <li><a href="#" style="display:flex; align-items:center; gap:10px;"><i class="fab fa-instagram" style="width:20px; color:#E91E8C;"></i> Instagram</a></li>
                    <li><a href="#" style="display:flex; align-items:center; gap:10px;"><i class="fab fa-tiktok" style="width:20px; color:#E91E8C;"></i> TikTok</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6" style="padding-top:8px;">
                <h6 class="fw-bold mb-4" style="color:#C9A96E; letter-spacing:1px; text-transform:uppercase; font-size:0.78rem;">Legal</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                    <li class="mb-2"><a href="{{ route('terms') }}">Terms &amp; Conditions</a></li>
                    <li class="mb-2"><a href="{{ route('cookies') }}">Cookie Policy</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div style="border-top: 1px solid #f0e8ed; margin-top: 40px; padding: 20px 0;">
        <div class="container">
            <p style="text-align:center; color:#aaa; font-size:0.82rem; margin:0;">&copy; {{ date('Y') }} <span style="color:#E91E8C; font-weight:600;">Beauty Blush Salons</span> — All rights reserved.</p>
        </div>
    </div>
</footer>
 
<script>
  function toggleMenuDropdown() { document.getElementById('menuDropdown').classList.toggle('show'); }
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.btn-nav-menu') && !e.target.closest('.menu-dropdown')) document.getElementById('menuDropdown')?.classList.remove('show');
  });
 
  function filterChip(el, cat) {
    document.querySelectorAll('.svc-chip').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
    if (cat !== 'all') window.location = '{{ route("salons.index") }}?category=' + cat;
  }
 
  const revealTargets = document.querySelectorAll('.reveal-up, .reveal-stagger');
  if ('IntersectionObserver' in window) {
    const revealObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) { entry.target.classList.add('in-view'); revealObserver.unobserve(entry.target); }
      });
    }, { threshold: 0.15 });
    revealTargets.forEach(el => revealObserver.observe(el));
  } else {
    revealTargets.forEach(el => el.classList.add('in-view'));
  }
</script>
</body>
</html>