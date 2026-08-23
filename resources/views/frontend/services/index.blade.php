<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Services — Beauty Blush Salons</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Plus Jakarta Sans', sans-serif; color: #1e1e24; background: #fdfafc; -webkit-font-smoothing: antialiased; }
    a { text-decoration: none; color: inherit; }

    /* ── NAVBAR ── */
    .g-nav {
      background: rgba(255,255,255,0.98); backdrop-filter: blur(12px);
      border-bottom: 1px solid #f3e5f0; padding: 0 20px; height: 68px;
      display: flex; align-items: center; justify-content: space-between;
      position: sticky; top: 0; z-index: 1000; box-shadow: 0 4px 25px rgba(233,30,140,0.04);
    }
    .g-nav .brand { display: flex; align-items: center; gap: 10px; }
    .g-nav .brand .brand-icon {
      width: 40px; height: 40px; background: linear-gradient(135deg, #E91E8C, #C9A96E);
      border-radius: 12px; display: flex; align-items: center; justify-content: center;
      box-shadow: 0 4px 15px rgba(233,30,140,0.25);
    }
    .g-nav .brand .brand-icon i { color: #fff; font-size: 1.15rem; }
    .g-nav .brand .brand-text { font-family: 'Playfair Display', serif; font-size: 1.45rem; font-weight: 800; letter-spacing: -0.3px; }
    .g-nav .brand .brand-text .pink { color: #E91E8C; }
    .g-nav .brand .brand-text .gold { color: #C9A96E; }
    .g-nav .nav-right { display: flex; align-items: center; gap: 8px; }
    .btn-nav-ghost, .btn-nav-outline {
      font-size: 0.85rem; padding: 8px 18px; border-radius: 50px; border: 1.5px solid #1a1a1a;
      background: transparent; font-weight: 600; cursor: pointer; transition: all 0.2s ease; display: none;
    }
    .btn-nav-ghost { color: #1a1a1a; border-color: #e0e0e0; }
    .btn-nav-outline { background: #1a1a1a; color: #fff; }
    .btn-nav-menu {
      background: #fff; border: 1.5px solid #e0e0e0; color: #1a1a1a; font-size: 0.85rem; font-weight: 600;
      padding: 8px 18px; border-radius: 50px; display: flex; align-items: center; gap: 8px; cursor: pointer;
    }
    .menu-dropdown {
      position: absolute; top: 100%; right: 0; background: #fff; border-radius: 16px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.12); border: 1px solid #eee; padding: 10px 0;
      z-index: 9999; min-width: 200px; display: none;
    }
    .menu-dropdown.show { display: block; }
    .menu-dropdown a { display: block; padding: 10px 20px; font-size: 0.9rem; font-weight: 500; color: #333; }
    .menu-dropdown a.active { color: #E91E8C; background: #fdf5fb; }

    @media(min-width:769px) {
      .g-nav { padding: 0 40px; }
      .btn-nav-ghost, .btn-nav-outline { display: inline-block; }
    }

    /* ── HERO SECTION ── */
    .hero-wrapper {
      background: radial-gradient(circle at 50% 20%, #fde6f4 0%, #fcf7fa 60%, #ffffff 100%);
      padding: 60px 20px 50px; text-align: center; border-bottom: 1px solid #f2e2ee;
    }
    .hero-badge {
      display: inline-flex; align-items: center; gap: 6px;
      background: rgba(233,30,140,0.08); color: #E91E8C; border: 1px solid rgba(233,30,140,0.2);
      font-size: 0.78rem; font-weight: 700; padding: 6px 16px; border-radius: 30px; margin-bottom: 16px;
      letter-spacing: 0.5px; text-transform: uppercase;
    }
    .hero-title {
      font-family: 'Playfair Display', serif; font-size: 2.8rem; font-weight: 800;
      color: #1e1e24; margin-bottom: 12px; line-height: 1.2;
    }
    .hero-subtitle { font-size: 1.05rem; color: #666; max-width: 620px; margin: 0 auto 28px; font-weight: 400; line-height: 1.6; }

    .grand-search-box {
      max-width: 580px; margin: 0 auto; background: #ffffff;
      border: 1px solid #ebcce2; border-radius: 50px; padding: 6px 8px 6px 20px;
      box-shadow: 0 10px 30px rgba(233,30,140,0.08); display: flex; align-items: center; gap: 10px;
    }
    .search-field { flex: 1; display: flex; align-items: center; gap: 10px; }
    .search-field i { color: #E91E8C; font-size: 1.1rem; }
    .search-field input {
      border: none; outline: none; width: 100%; font-size: 0.95rem; color: #222;
      font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 500; background: transparent;
    }
    .btn-search-action {
      background: linear-gradient(135deg, #E91E8C 0%, #d81b7d 100%); color: #ffffff;
      border: none; border-radius: 40px; padding: 12px 28px; font-weight: 700;
      font-size: 0.9rem; display: flex; align-items: center; gap: 8px;
      cursor: pointer; transition: all 0.25s ease; box-shadow: 0 4px 15px rgba(233,30,140,0.25);
    }
    .btn-search-action:hover { transform: scale(1.02); }

    /* ── FILTER CHIPS ── */
    .filter-sec { padding: 30px 20px 10px; max-width: 1240px; margin: 0 auto; }
    .chips-container { display: flex; gap: 10px; overflow-x: auto; scrollbar-width: none; padding-bottom: 6px; justify-content: center; flex-wrap: wrap; }
    .chips-container::-webkit-scrollbar { display: none; }
    .chip-item {
      white-space: nowrap; border: 1px solid #e3d5e0; background: #ffffff; border-radius: 25px;
      padding: 8px 20px; font-size: 0.85rem; font-weight: 600; color: #555; transition: all 0.2s; cursor: pointer;
    }
    .chip-item.active, .chip-item:hover {
      background: #E91E8C; color: #fff; border-color: #E91E8C; box-shadow: 0 4px 14px rgba(233,30,140,0.2);
    }

    /* ── SERVICE GRID ── */
    .services-sec { padding: 25px 20px 60px; max-width: 1240px; margin: 0 auto; }
    .svc-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(290px, 1fr)); gap: 20px; }

    .svc-card-modern {
      background: #ffffff; border: 1px solid #f0e2ed; border-radius: 18px;
      padding: 20px; display: flex; flex-direction: column; justify-content: space-between;
      position: relative; transition: all 0.25s ease; box-shadow: 0 3px 12px rgba(0,0,0,0.02);
    }
    .svc-card-modern:hover {
      transform: translateY(-4px); border-color: #E91E8C;
      box-shadow: 0 12px 24px rgba(233,30,140,0.1);
    }

    .svc-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; margin-bottom: 14px; }
    .svc-icon-box {
      width: 48px; height: 48px; border-radius: 14px; background: #fdf2f9;
      display: flex; align-items: center; justify-content: center; border: 1px solid #f7d5eb; flex-shrink: 0;
    }
    .svc-icon-box i { font-size: 1.3rem; color: #E91E8C; }
    
    .svc-badge {
      font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
      color: #C9A96E; background: #fffdf9; border: 1px solid #f3e6cf; padding: 4px 10px; border-radius: 20px;
    }

    .svc-body { margin-bottom: 14px; }
    .svc-title { font-size: 1.08rem; font-weight: 600; color: #1e1e24; margin-bottom: 6px; line-height: 1.35; }
    .svc-meta-info { display: flex; align-items: center; gap: 14px; font-size: 0.82rem; color: #666; font-weight: 500; }
    .svc-meta-info i { color: #E91E8C; margin-right: 3px; }

    .svc-footer {
      border-top: 1px solid #f6ecf4; padding-top: 14px;
      display: flex; align-items: center; justify-content: space-between;
    }
    .svc-price-label { font-size: 0.7rem; color: #888; font-weight: 500; display: block; }
    .svc-price-val { font-size: 1.15rem; font-weight: 800; color: #E91E8C; }

    .btn-book-outline {
      background: #ffffff; color: #E91E8C; border: 1.5px solid #E91E8C;
      font-size: 0.82rem; font-weight: 700; padding: 7px 16px; border-radius: 50px;
      transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 5px;
    }
    .svc-card-modern:hover .btn-book-outline {
      background: linear-gradient(135deg, #E91E8C, #d81b7d); color: #ffffff; border-color: transparent;
      box-shadow: 0 4px 12px rgba(233,30,140,0.25);
    }
  </style>
</head>
<body>

<!-- NAVBAR -->
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
    </div>
  </div>
</nav>

<!-- HERO SECTION -->
<section class="hero-wrapper">
  <div class="hero-badge"><i class="fas fa-sparkles"></i> Premium Treatments</div>
  <h1 class="hero-title">Discover Exclusive Treatments</h1>
  <p class="hero-subtitle">Find top luxury beauty treatments, relaxing massages, and professional styling across all our salons.</p>

  <div class="grand-search-box">
    <div class="search-field">
      <i class="fas fa-search"></i>
      <input type="text" id="searchInput" onkeyup="filterServices()" placeholder="Search treatment name e.g., Massage, Facial...">
    </div>
    <button class="btn-search-action" type="button" onclick="filterServices()">
      <i class="fas fa-arrow-right"></i>
      <span>Search</span>
    </button>
  </div>
</section>

<!-- CATEGORY FILTER CHIPS -->
<section class="filter-sec">
  <div class="chips-container" id="categoryChips">
    <span class="chip-item active" onclick="filterCategory('all', this)">All Services ({{ $services->count() }})</span>
    
    @foreach($categories as $cat)
      @if(!empty(trim($cat->name)))
        <span class="chip-item" onclick="filterCategory('{{ strtolower(trim($cat->name)) }}', this)">{{ $cat->name }}</span>
      @endif
    @endforeach
  </div>
</section>

<!-- SERVICES GRID -->
<section class="services-sec">
  <div class="svc-grid" id="servicesGrid">
    @forelse($services as $svc)
      @php
        $catName = strtolower(trim($svc->category->name ?? ''));
        $titleName = strtolower(trim($svc->name ?? ''));
        $combined = $catName . ' ' . $titleName;

        $icon = 'fa-sparkles';
        if (str_contains($combined, 'massage')) $icon = 'fa-spa';
        elseif (str_contains($combined, 'spa') || str_contains($combined, 'body')) $icon = 'fa-hot-tub-person';
        elseif (str_contains($combined, 'hair cut') || str_contains($combined, 'trim')) $icon = 'fa-scissors';
        elseif (str_contains($combined, 'hair') || str_contains($combined, 'dye')) $icon = 'fa-spray-can-sparkles';
        elseif (str_contains($combined, 'nail') || str_contains($combined, 'mani') || str_contains($combined, 'pedi')) $icon = 'fa-hand-sparkles';
        elseif (str_contains($combined, 'facial') || str_contains($combined, 'skin') || str_contains($combined, 'clean') || str_contains($combined, 'hydra')) $icon = 'fa-wand-magic-sparkles';
        elseif (str_contains($combined, 'wax') || str_contains($combined, 'thread')) $icon = 'fa-feather-pointed';
        elseif (str_contains($combined, 'makeup') || str_contains($combined, 'bridal')) $icon = 'fa-eye';
      @endphp

      <div class="svc-card-modern service-item" data-cat-name="{{ $catName }}" data-name="{{ $titleName }}">
        <div>
          <div class="svc-header">
            <div class="svc-icon-box">
              <i class="fas {{ !empty($svc->category->icon) ? $svc->category->icon : $icon }}"></i>
            </div>
            <span class="svc-badge">{{ $svc->category->name ?? 'Service' }}</span>
          </div>

          <div class="svc-body">
            <h3 class="svc-title">{{ $svc->name }}</h3>
            <div class="svc-meta-info">
              <span><i class="far fa-clock"></i> {{ $svc->duration ?? '30 mins' }}</span>
              <span><i class="fas fa-store"></i> {{ $svc->salon->name ?? 'Beauty Salon' }}</span>
            </div>
          </div>
        </div>

        <div class="svc-footer">
          <div>
            <span class="svc-price-label">Price</span>
            <span class="svc-price-val">Rs. {{ number_format($svc->price) }}</span>
          </div>
          
          @auth
            @if(Auth::user()->isOwner())
              @if(isset($userSalon) && $userSalon)
                {{-- Logged-in Owner hamesha apne salon page par jaye ga --}}
                <a href="{{ route('salons.show', $userSalon->slug) }}" class="btn-book-outline">
                  <i class="fas fa-calendar-check me-1"></i> Book Now
                </a>
              @else
                <a href="{{ route('owner.dashboard') }}" class="btn-book-outline">
                  <i class="fas fa-store me-1"></i> My Dashboard
                </a>
              @endif
            @else
              {{-- Client ke liye relevant service salon page --}}
              <a href="{{ route('salons.show', $svc->salon->slug ?? 'salon') }}" class="btn-book-outline">
                <i class="fas fa-calendar-check me-1"></i> Book Now
              </a>
            @endif
          @else
            <a href="{{ route('login') }}" class="btn-book-outline">
              <i class="fas fa-sign-in-alt me-1"></i> Book Now
            </a>
          @endauth
        </div>
      </div>
    @empty
      <div class="text-center py-5 w-100" style="grid-column: 1 / -1;">
        <i class="fas fa-concierge-bell fa-3x text-muted mb-3"></i>
        <h4 class="fw-bold">No Services Available</h4>
      </div>
    @endforelse
  </div>

  <div id="noResults" class="text-center py-5 w-100" style="display: none; grid-column: 1 / -1;">
    <i class="fas fa-search fa-3x text-muted mb-3"></i>
    <h4 class="fw-bold">No Matching Service Found</h4>
  </div>
</section>

<!-- FOOTER COMPONENT IMPORT -->
@include('components.footer')

<script>
  let activeCat = 'all';

  function toggleMenuDropdown() {
    document.getElementById('menuDropdown').classList.toggle('show');
  }

  function filterCategory(catName, element) {
    activeCat = catName.toLowerCase().trim();
    document.querySelectorAll('#categoryChips .chip-item').forEach(chip => chip.classList.remove('active'));
    element.classList.add('active');
    filterServices();
  }

  function filterServices() {
    const query = document.getElementById('searchInput').value.toLowerCase().trim();
    const items = document.querySelectorAll('.service-item');
    let visibleCount = 0;

    items.forEach(item => {
      const itemCatName = (item.getAttribute('data-cat-name') || '').toLowerCase().trim();
      const itemName = (item.getAttribute('data-name') || '').toLowerCase().trim();

      const matchesCat = (activeCat === 'all' || itemCatName === activeCat);
      const matchesSearch = query === '' || itemName.includes(query) || itemCatName.includes(query);

      if (matchesCat && matchesSearch) {
        item.style.display = 'flex';
        visibleCount++;
      } else {
        item.style.display = 'none';
      }
    });

    document.getElementById('noResults').style.display = visibleCount === 0 ? 'block' : 'none';
  }
</script>
</body>
</html>