<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $type === 'owner' ? 'Salon Owner Demo' : 'How to Book' }} — Beauty Blush Salons</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
 
        html, body { min-height: 100%; }
        body {
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 16px;
            font-family: system-ui, -apple-system, 'Segoe UI', sans-serif;
          
            background: linear-gradient(145deg, #ede8f5 0%, #f5e6f5 20%, #fce8f3 50%, #fdf5fb 80%, #ffffff 100%);
            overflow-x: hidden;
        }
 
        
        .video-wrap {
            width: min(100%, 1000px, calc((100vh - 32px) * 16 / 9));
            aspect-ratio: 16 / 9;
            border-radius: 18px;
            overflow: hidden;
            background: #000;
            border: 4px solid #fff;
            box-shadow:
                0 20px 50px rgba(233,30,140,.22),
                0 6px 18px rgba(0,0,0,.12);
            animation: rise .6s cubic-bezier(.22,1,.36,1) both;
        }
        .video-wrap iframe { width: 100%; height: 100%; border: 0; display: block; }
 
        @keyframes rise {
            from { opacity: 0; transform: translateY(20px) scale(.98); }
            to   { opacity: 1; transform: none; }
        }
 
        .back {
            position: fixed; top: 14px; left: 14px; z-index: 10;
            width: 42px; height: 42px; border-radius: 50%;
            background: rgba(255,255,255,.9);
            border: 1.5px solid #f2d9e8;
            color: #E91E8C; text-decoration: none; font-size: 18px;
            box-shadow: 0 4px 14px rgba(233,30,140,.15);
            display: flex; align-items: center; justify-content: center;
            transition: all .2s ease;
        }
        .back:hover { background: #E91E8C; border-color: #E91E8C; color: #fff; transform: translateX(-2px); }
 
        @media (max-width: 576px) {
            body { padding: 70px 12px 16px; align-items: flex-start; }
            .video-wrap { width: 100%; border-radius: 14px; border-width: 3px; }
        }
        @media (max-height: 500px) and (orientation: landscape) {
            body { padding: 8px; align-items: center; }
            .back { top: 8px; left: 8px; width: 34px; height: 34px; font-size: 15px; }
        }
        @media (prefers-reduced-motion: reduce) { .video-wrap { animation: none; } }
    </style>
</head>
<body>
 
    <a href="{{ url()->previous() }}" class="back" aria-label="Back">&larr;</a>
 
    <div class="video-wrap">
        <iframe
            src="https://www.youtube-nocookie.com/embed/{{ $videoId }}?rel=0&modestbranding=1"
            title="Beauty Blush Salons demo video"
            allow="accelerometer; autoplay; encrypted-media; picture-in-picture; fullscreen"
            allowfullscreen></iframe>
    </div>
 
</body>
</html>
 