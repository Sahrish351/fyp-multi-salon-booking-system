<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $salon->name }} - Gallery | Glamora</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8f9fc; }
        
        .navbar {
            background: #fff;
            border-bottom: 1px solid #eef2f6;
            padding: 16px 32px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .navbar .brand {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #c8506e, #8b2252);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }
        .back-btn {
            background: #f5f5f5;
            border: none;
            border-radius: 40px;
            padding: 8px 20px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            color: #1a1a1a;
            transition: all 0.2s;
        }
        .back-btn:hover {
            background: #1a1a1a;
            color: #fff;
        }
        .gallery-hero {
            background: linear-gradient(135deg, #c8506e10, #8b225210);
            padding: 40px 0;
            text-align: center;
        }
        .gallery-hero h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
            padding: 40px 0;
        }
        .gallery-item {
            aspect-ratio: 1;
            border-radius: 16px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        .gallery-item:hover img {
            transform: scale(1.05);
        }
        #lightbox {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.95);
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
        }
        .close-modal {
            position: absolute;
            top: 20px;
            right: 30px;
            background: none;
            border: none;
            color: #fff;
            font-size: 30px;
            cursor: pointer;
            z-index: 1001;
        }
        .nav-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.2);
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
            color: #fff;
            font-size: 30px;
        }
        .nav-arrow:hover {
            background: rgba(255,255,255,0.4);
        }
        .nav-arrow.prev { left: 20px; }
        .nav-arrow.next { right: 20px; }
        .counter {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            color: #fff;
            background: rgba(0,0,0,0.6);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
        }
        @media (max-width: 768px) {
            .gallery-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 10px; }
            .navbar { padding: 12px 16px; }
            .gallery-hero { padding: 24px 0; }
            .gallery-hero h1 { font-size: 1.5rem; }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="{{ route('home') }}" class="brand">glamora</a>
        <a href="{{ route('salons.show', $salon->slug) }}" class="back-btn">
            <i class="fas fa-arrow-left me-2"></i> Back to {{ $salon->name }}
        </a>
    </div>
</nav>

<!-- Hero Section -->
<section class="gallery-hero">
    <div class="container">
        <h1>{{ $salon->name }}</h1>
        <p class="text-muted">{{ $salon->gallery->count() }} photos</p>
    </div>
</section>

<!-- Gallery Grid -->
<div class="container">
    <div class="gallery-grid">
        @forelse($salon->gallery as $key => $image)
        <div class="gallery-item" onclick="openLightbox({{ $key }})">
            <img src="{{ $image->image_url }}" alt="{{ $salon->name }} - Photo {{ $key + 1 }}">
        </div>
        @empty
        <div class="text-center py-5" style="grid-column: 1/-1;">
            <i class="fas fa-images fa-4x text-muted mb-3"></i>
            <p class="text-muted">No photos available for this salon yet.</p>
            <a href="{{ route('salons.show', $salon->slug) }}" class="btn btn-outline-dark rounded-pill px-4">Back to Salon</a>
        </div>
        @endforelse
    </div>
</div>

<!-- Lightbox -->
<div id="lightbox" onclick="closeLightbox()">
    <button class="close-modal" onclick="closeLightbox()">✕</button>
    <button class="nav-arrow prev" onclick="event.stopPropagation(); prevImage()">‹</button>
    <img id="lightbox-img" src="" style="max-width:85%; max-height:85%; object-fit:contain; border-radius:8px;" onclick="event.stopPropagation()">
    <button class="nav-arrow next" onclick="event.stopPropagation(); nextImage()">›</button>
    <div class="counter" id="counter"></div>
</div>

<script>
    let images = @json($salon->gallery->pluck('image_url'));
    let currentIndex = 0;
    
    function openLightbox(index) {
        currentIndex = index;
        document.getElementById('lightbox-img').src = images[currentIndex];
        document.getElementById('counter').innerText = (currentIndex + 1) + ' / ' + images.length;
        document.getElementById('lightbox').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function closeLightbox() {
        document.getElementById('lightbox').style.display = 'none';
        document.body.style.overflow = '';
    }
    
    function prevImage() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        document.getElementById('lightbox-img').src = images[currentIndex];
        document.getElementById('counter').innerText = (currentIndex + 1) + ' / ' + images.length;
    }
    
    function nextImage() {
        currentIndex = (currentIndex + 1) % images.length;
        document.getElementById('lightbox-img').src = images[currentIndex];
        document.getElementById('counter').innerText = (currentIndex + 1) + ' / ' + images.length;
    }
    
    document.addEventListener('keydown', function(e) {
        if (document.getElementById('lightbox').style.display === 'flex') {
            if (e.key === 'ArrowLeft') prevImage();
            if (e.key === 'ArrowRight') nextImage();
            if (e.key === 'Escape') closeLightbox();
        }
    });
</script>

</body>
</html>