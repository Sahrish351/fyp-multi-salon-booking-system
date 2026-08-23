<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $salon->name }} - Gallery | Beauty Blush Salons</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8f7fa; }
 
        .g-nav {
            background: #fff;
            border-bottom: 1px solid #f0e8ed;
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
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            font-weight: 900;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #E91E8C, #C9A96E);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-style: italic;
            text-decoration: none;
        }
        .back-btn {
            background: #f5f5f5;
            border: none;
            border-radius: 40px;
            padding: 8px 20px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            color: #1a1a1a;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .back-btn:hover {
            background: #E91E8C;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(233,30,140,0.25);
        }
 
        .gallery-hero {
            background: linear-gradient(135deg, #fce4ec 0%, #f8f0f5 50%, #fff 100%);
            padding: 60px 0 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .gallery-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(233,30,140,0.04), transparent 70%);
            border-radius: 50%;
        }
        .gallery-hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.4rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 4px;
            position: relative;
            z-index: 1;
        }
        .gallery-hero h1 span {
            background: linear-gradient(135deg, #E91E8C, #C9A96E);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .gallery-hero .subtitle {
            color: #888;
            font-size: 0.95rem;
            position: relative;
            z-index: 1;
        }
        .gallery-hero .subtitle i {
            color: #E91E8C;
        }
 
        .category-filter {
            padding: 24px 0 16px;
            background: #fff;
            border-bottom: 1px solid #f0e8ed;
            position: sticky;
            top: 64px;
            z-index: 100;
        }
        .category-filter .filter-scroll {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding: 4px 0 12px;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
            justify-content: center;
            flex-wrap: wrap;
        }
        .category-filter .filter-scroll::-webkit-scrollbar { display: none; }
        .cat-btn {
            border: 2px solid #e0e0e0;
            border-radius: 50px;
            padding: 8px 24px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #555;
            background: #fff;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            white-space: nowrap;
            font-family: 'Inter', sans-serif;
        }
        .cat-btn i { margin-right: 6px; }
        .cat-btn:hover {
            border-color: #E91E8C;
            color: #E91E8C;
            transform: translateY(-2px);
        }
        .cat-btn.active {
            background: linear-gradient(135deg, #E91E8C, #c2185b);
            color: #fff;
            border-color: #E91E8C;
            box-shadow: 0 4px 20px rgba(233,30,140,0.25);
            transform: translateY(-2px);
        }
 
        .gallery-section {
            padding: 30px 0 50px;
        }
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 18px;
        }
        .gallery-item {
            border-radius: 18px;
            overflow: hidden;
            cursor: pointer;
            position: relative;
            aspect-ratio: 1;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 2px 15px rgba(0,0,0,0.04);
        }
        .gallery-item:hover {
            transform: translateY(-6px) scale(1.01);
            box-shadow: 0 15px 45px rgba(233,30,140,0.10);
        }
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
            display: block;
        }
        .gallery-item:hover img {
            transform: scale(1.06);
        }
        .gallery-item .overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px 16px 16px;
            background: linear-gradient(transparent, rgba(0,0,0,0.5));
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        .gallery-item:hover .overlay {
            opacity: 1;
        }
        .gallery-item .overlay span {
            color: #fff;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .gallery-item .overlay i {
            color: #fff;
            margin-right: 6px;
            font-size: 0.75rem;
        }
        .gallery-item.featured {
            grid-column: span 2;
            grid-row: span 2;
        }
        @media(max-width:768px) {
            .gallery-item.featured {
                grid-column: span 1;
                grid-row: span 1;
            }
        }
 
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            grid-column: 1 / -1;
        }
        .empty-state i {
            font-size: 4rem;
            color: #f0e8ed;
            margin-bottom: 16px;
            display: block;
        }
        .empty-state h4 {
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 8px;
        }
        .empty-state p {
            color: #888;
            font-size: 0.9rem;
        }
 
        #lightbox {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.94);
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }
        #lightbox.show { display: flex; }
        .close-modal {
            position: absolute;
            top: 24px;
            right: 32px;
            background: rgba(255,255,255,0.1);
            border: none;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            color: #fff;
            font-size: 1.4rem;
            cursor: pointer;
            z-index: 1001;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .close-modal:hover {
            background: rgba(255,255,255,0.2);
            transform: rotate(90deg);
        }
        .nav-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.08);
            border: none;
            border-radius: 50%;
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #fff;
            font-size: 1.4rem;
            backdrop-filter: blur(10px);
        }
        .nav-arrow:hover {
            background: rgba(255,255,255,0.18);
            transform: translateY(-50%) scale(1.05);
        }
        .nav-arrow.prev { left: 24px; }
        .nav-arrow.next { right: 24px; }
        #lightbox-img {
            max-width: 85%;
            max-height: 82vh;
            object-fit: contain;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .counter {
            position: absolute;
            bottom: 28px;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(255,255,255,0.6);
            background: rgba(0,0,0,0.4);
            padding: 6px 18px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 500;
            backdrop-filter: blur(10px);
        }
        .lightbox-info {
            position: absolute;
            bottom: 80px;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(255,255,255,0.5);
            font-size: 0.78rem;
            text-align: center;
        }
 
        @media (max-width: 768px) {
            .g-nav { padding: 0 16px; height: 56px; }
            .g-nav .brand { font-size: 1.0rem; }
            .back-btn { font-size: 12px; padding: 6px 14px; }
            .gallery-hero { padding: 40px 0 24px; }
            .gallery-hero h1 { font-size: 1.6rem; }
            .gallery-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px; }
            .category-filter { top: 56px; }
            .cat-btn { font-size: 0.72rem; padding: 6px 16px; }
            .nav-arrow { width: 40px; height: 40px; font-size: 1rem; }
            .nav-arrow.prev { left: 12px; }
            .nav-arrow.next { right: 12px; }
            #lightbox-img { max-width: 92%; max-height: 70vh; }
            .close-modal { top: 16px; right: 16px; width: 40px; height: 40px; font-size: 1rem; }
        }
        @media (max-width: 480px) {
            .gallery-grid { grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 8px; }
            .cat-btn { font-size: 0.65rem; padding: 4px 12px; }
            .gallery-hero h1 { font-size: 1.3rem; }
            .counter { font-size: 0.7rem; padding: 4px 14px; }
        }
    </style>
</head>
<body>
 
@php
    // Local storage path ho ya poora URL (Unsplash waghera), dono ke liye sahi <img> src banata hai
    function resolveGalleryImage($path) {
        if (!$path) return '';
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        return asset('storage/' . ltrim($path, '/'));
    }
 
    // Category name ke hisab se icon choose karta hai, warna default icon
    function categoryIcon($name) {
        $name = strtolower($name);
        return match(true) {
            str_contains($name, 'bridal')  => 'fa-rings-wedding',
            str_contains($name, 'hair')    => 'fa-cut',
            str_contains($name, 'makeup')  => 'fa-brush',
            str_contains($name, 'nail')    => 'fa-hand-peace',
            str_contains($name, 'spa')     => 'fa-spa',
            str_contains($name, 'massage') => 'fa-hands',
            str_contains($name, 'facial')  => 'fa-face-smile',
            str_contains($name, 'wax')     => 'fa-feather',
            default => 'fa-tag',
        };
    }
 
    // Salon ki asal 8 categories (dashboard mein jo owner ne banai hain), sirf wahi buttons banenge
    $galleryCategories = DB::table('categories')->where('salon_id', $salon->id)->orderBy('name')->get(['id', 'name']);
@endphp
 
<!-- ============================================================ -->
<!-- NAVBAR -->
<!-- ============================================================ -->
<nav class="g-nav">
    <a href="{{ route('home') }}" class="brand">Beauty Blush Salons</a>
    <a href="{{ route('salons.show', $salon->slug) }}" class="back-btn">
        <i class="fas fa-arrow-left me-2"></i> Back to {{ $salon->name }}
    </a>
</nav>
 
<!-- ============================================================ -->
<!-- HERO -->
<!-- ============================================================ -->
<section class="gallery-hero">
    <div class="container">
        <h1><span>{{ $salon->name }}</span></h1>
        <p class="subtitle">
            <i class="fas fa-images me-2"></i> {{ $salon->gallery->count() }} photos
        </p>
    </div>
</section>
 
<!-- ============================================================ -->
<!-- CATEGORY FILTER -->
<!-- ============================================================ -->
<section class="category-filter">
    <div class="container">
        <div class="filter-scroll">
            <button class="cat-btn active" data-category="all" onclick="filterGallery('all', this)">
                <i class="fas fa-th-large"></i> All
            </button>
            @foreach($galleryCategories as $cat)
            <button class="cat-btn" data-category="{{ $cat->id }}" onclick="filterGallery('{{ $cat->id }}', this)">
                <i class="fas {{ categoryIcon($cat->name) }}"></i> {{ $cat->name }}
            </button>
            @endforeach
        </div>
    </div>
</section>
 
<!-- ============================================================ -->
<!-- GALLERY GRID -->
<!-- ============================================================ -->
<section class="gallery-section">
    <div class="container">
        <div class="gallery-grid" id="galleryGrid">
            @forelse($salon->gallery as $key => $image)
            @php
                $isFeatured = $key == 0;
                $imgUrl = resolveGalleryImage($image->image_path);
            @endphp
            <div class="gallery-item {{ $isFeatured ? 'featured' : '' }}" data-category="{{ $image->category_id }}" onclick="openLightbox({{ $key }})">
                <img src="{{ $imgUrl }}" alt="{{ $salon->name }} - Photo {{ $key + 1 }}" loading="lazy">
                <div class="overlay">
                    <span><i class="fas fa-expand"></i> View</span>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-images"></i>
                <h4>No Photos Yet</h4>
                <p>This salon hasn't uploaded any photos yet.</p>
                <a href="{{ route('salons.show', $salon->slug) }}" class="btn btn-outline-dark rounded-pill px-4 mt-3">Back to Salon</a>
            </div>
            @endforelse
        </div>
    </div>
</section>
 
<!-- ============================================================ -->
<!-- LIGHTBOX -->
<!-- ============================================================ -->
<div id="lightbox" onclick="closeLightbox()">
    <button class="close-modal" onclick="event.stopPropagation(); closeLightbox()">
        <i class="fas fa-times"></i>
    </button>
    <button class="nav-arrow prev" onclick="event.stopPropagation(); prevImage()">
        <i class="fas fa-chevron-left"></i>
    </button>
    <img id="lightbox-img" src="" onclick="event.stopPropagation()">
    <button class="nav-arrow next" onclick="event.stopPropagation(); nextImage()">
        <i class="fas fa-chevron-right"></i>
    </button>
    <div class="counter" id="counter"></div>
    <div class="lightbox-info" id="lightboxInfo"></div>
</div>
 
@php
    // GALLERY DATA ko pehle ek simple PHP variable mein resolve kar lein
    // (closure ko seedha @json() ke andar likhna Blade parser ko confuse kar deta hai)
    $galleryImageUrls = $salon->gallery->map(function ($g) {
        return resolveGalleryImage($g->image_path);
    })->values();
@endphp
<script>
    // ============================================================
    // GALLERY DATA (local storage paths ko poora URL bana kar bheja ja raha hai)
    // ============================================================
    let images = @json($galleryImageUrls);
    let currentIndex = 0;
 
    // ============================================================
    // CATEGORY FILTER
    // ============================================================
    function filterGallery(category, btn) {
        document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
 
        document.querySelectorAll('.gallery-item').forEach(item => {
            if (category === 'all' || item.dataset.category === category) {
                item.style.display = 'block';
                item.style.animation = 'none';
                setTimeout(() => {
                    item.style.animation = 'slideUp 0.5s ease forwards';
                }, 10);
            } else {
                item.style.display = 'none';
            }
        });
    }
 
    // ============================================================
    // LIGHTBOX
    // ============================================================
    function openLightbox(index) {
        currentIndex = index;
        updateLightbox();
        document.getElementById('lightbox').classList.add('show');
        document.body.style.overflow = 'hidden';
    }
 
    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('show');
        document.body.style.overflow = '';
    }
 
    function prevImage() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        updateLightbox();
    }
 
    function nextImage() {
        currentIndex = (currentIndex + 1) % images.length;
        updateLightbox();
    }
 
    function updateLightbox() {
        document.getElementById('lightbox-img').src = images[currentIndex];
        document.getElementById('counter').innerText = (currentIndex + 1) + ' / ' + images.length;
        document.getElementById('lightboxInfo').innerText = 'Photo ' + (currentIndex + 1) + ' of ' + images.length;
    }
 
    // ============================================================
    // KEYBOARD SHORTCUTS
    // ============================================================
    document.addEventListener('keydown', function(e) {
        if (document.getElementById('lightbox').classList.contains('show')) {
            if (e.key === 'ArrowLeft') prevImage();
            if (e.key === 'ArrowRight') nextImage();
            if (e.key === 'Escape') closeLightbox();
        }
    });
 
    // ============================================================
    // SWIPE SUPPORT
    // ============================================================
    let touchStartX = 0;
    let touchEndX = 0;
 
    document.getElementById('lightbox').addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    });
 
    document.getElementById('lightbox').addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        const diff = touchStartX - touchEndX;
        if (Math.abs(diff) > 50) {
            if (diff > 0) nextImage();
            else prevImage();
        }
    });
 
    // ============================================================
    // ANIMATION KEYFRAMES
    // ============================================================
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    `;
    document.head.appendChild(style);
</script>
 
</body>
</html>