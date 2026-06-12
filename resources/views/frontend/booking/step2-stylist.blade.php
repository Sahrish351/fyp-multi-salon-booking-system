
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Select professional — {{ $salon->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; background: #f5f5f5; min-height: 100vh; -webkit-font-smoothing: antialiased; }
    .top-nav { position: fixed; top: 0; left: 0; right: 0; display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; z-index: 200; background: #f5f5f5; }
    .nav-btn { width: 44px; height: 44px; border-radius: 50%; border: 1.5px solid #e0e0e0; background: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 1rem; color: #1a1a1a; transition: all .15s; text-decoration: none; }
    .nav-btn:hover { border-color: #1a1a1a; }
    .booking-layout { display: grid; grid-template-columns: 1fr 360px; gap: 0; max-width: 1200px; margin: 0 auto; padding: 0 24px 100px; }
    @media(max-width:900px) { .booking-layout { grid-template-columns: 1fr; } .sidebar { display: none; } }
    .left-panel { padding: 24px 40px 24px 0; }
    h1 { font-size: 2.2rem; font-weight: 900; color: #1a1a1a; letter-spacing: -1px; margin-bottom: 20px; }
    
   
    .pro-card { background: #fff; border: 1.5px solid #e8e8e8; border-radius: 14px; padding: 18px 20px; margin-bottom: 10px; display: flex; align-items: center; gap: 16px; cursor: pointer; transition: all .15s; }
    .pro-card:hover { border-color: #E91E8C; }
    .pro-card.selected { border-color: #E91E8C; border-width: 2px; background: #fff5f9; }
    .pro-avatar { width: 60px; height: 60px; border-radius: 50%; overflow: hidden; background: #fdf2f8; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; font-weight: 700; color: #E91E8C; flex-shrink: 0; }
    .pro-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .pro-info { flex: 1; }
    .pro-info .pro-name { font-size: 1rem; font-weight: 700; color: #1a1a1a; margin-bottom: 2px; }
    .pro-info .pro-role { font-size: 0.82rem; color: #888; margin-bottom: 4px; }
    .pro-info .pro-rating { font-size: 0.8rem; font-weight: 600; color: #1a1a1a; display: flex; align-items: center; gap: 4px; }
    .pro-info .pro-rating i { color: #ffc107; }
    .pro-info .pro-profile { font-size: 0.78rem; color: #E91E8C; text-decoration: underline; cursor: pointer; margin-top: 2px; }
    .select-btn { border: 1.5px solid #e0e0e0; border-radius: 50px; padding: 7px 18px; font-size: 0.82rem; font-weight: 600; color: #1a1a1a; background: #fff; cursor: pointer; transition: all .15s; flex-shrink: 0; }
    .select-btn:hover { border-color: #E91E8C; color: #E91E8C; }
    .check-circle { width: 36px; height: 36px; border-radius: 50%; background: #E91E8C; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 0.85rem; flex-shrink: 0; }
    
   
    .sidebar { padding: 24px 0 24px 32px; border-left: 1px solid #e8e8e8; }
    .salon-summary { background: #fff; border: 1.5px solid #e8e8e8; border-radius: 16px; padding: 16px; margin-bottom: 16px; display: flex; align-items: center; gap: 12px; }
    .salon-summary img { width: 56px; height: 56px; border-radius: 10px; object-fit: cover; }
    .salon-summary .ss-name { font-size: 0.95rem; font-weight: 700; color: #1a1a1a; }
    .salon-summary .ss-rating { font-size: 0.78rem; color: #555; display: flex; align-items: center; gap: 4px; }
    .salon-summary .ss-rating .stars { color: #ffc107; }
    .salon-summary .ss-addr { font-size: 0.72rem; color: #888; }
    .svc-summary-box { background: #fff; border: 1.5px solid #e8e8e8; border-radius: 16px; padding: 16px; margin-bottom: 16px; }
    .svc-sum-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 0.85rem; }
    .svc-sum-row .label { color: #1a1a1a; font-weight: 600; }
    .svc-sum-row .sub { color: #888; font-size: 0.78rem; }
    .svc-sum-row .price { font-weight: 700; color: #1a1a1a; }
    .total-row { display: flex; justify-content: space-between; padding-top: 10px; border-top: 1px solid #f0f0f0; margin-top: 8px; }
    .total-row span { font-weight: 700; color: #1a1a1a; }
    .continue-btn { background: #aaa; color: #fff; border: none; border-radius: 50px; padding: 14px 28px; font-size: 0.95rem; font-weight: 700; width: 100%; cursor: not-allowed; transition: all .2s; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .continue-btn.active { background: #E91E8C; cursor: pointer; }
    .continue-btn.active:hover { background: #c2185b; }
    </style>
</head>
<body>
    <div class="top-nav">
        <a href="{{ route('client.booking.step1', $salon->id) }}" class="nav-btn"><i class="fas fa-arrow-left"></i></a>
        <div style="display:flex;align-items:center;gap:8px;font-size:0.82rem;color:#aaa;">
            <span style="color:#aaa;">Services</span><span style="color:#ccc;">›</span>
            <span style="color:#1a1a1a;font-weight:700;">Professional</span><span style="color:#ccc;">›</span>
            <span>Time</span><span style="color:#ccc;">›</span>
            <span>Confirm</span>
        </div>
        <a href="{{ route('salons.show', $salon->slug) }}" class="nav-btn"><i class="fas fa-times"></i></a>
    </div>
 
    <div style="padding-top:72px;">
        <div class="booking-layout">
            <div class="left-panel">
                <h1>Select professional</h1>
 
                
                <div class="pro-card" data-id="any" onclick="selectPro(this,'any','any professional')">
                    <div class="pro-avatar" style="background:#fdf2f8;">
                        <i class="fas fa-random" style="color:#E91E8C;font-size:1.2rem;"></i>
                    </div>
                    <div class="pro-info">
                        <div class="pro-name">No preference</div>
                        <div class="pro-role">Maximum availability</div>
                    </div>
                    <button class="select-btn" id="btn-any">Select</button>
                </div>
 
               
                @foreach($stylists as $stylist)
                <div class="pro-card" data-id="{{ $stylist->id }}" onclick="selectPro(this,'{{ $stylist->id }}','{{ $stylist->name }}')">
                    <div class="pro-avatar">
                        @if($stylist->avatar)
                        <img src="{{ $stylist->avatar_url }}" onerror="this.parentElement.textContent='{{ substr($stylist->name,0,1) }}'">
                        @else
                        {{ substr($stylist->name, 0, 1) }}
                        @endif
                    </div>
                    <div class="pro-info">
                        <div class="pro-name">{{ $stylist->name }}</div>
                        <div class="pro-role">{{ $stylist->specializations ? Str::limit($stylist->specializations,30) : 'Stylist' }}</div>
                        <div class="pro-rating">
                            <i class="fas fa-star"></i>
                            {{ number_format($stylist->rating ?: 5.0, 1) }}
                        </div>
                        <div class="pro-profile">View profile</div>
                    </div>
                    <button class="select-btn" id="btn-{{ $stylist->id }}">Select</button>
                </div>
                @endforeach
            </div>
 
         
            <div class="sidebar">
                <div class="salon-summary">
                    <img src="{{ $salon->cover_url }}" alt="{{ $salon->name }}" onerror="this.src='https://images.unsplash.com/photo-1560066984-138dadb4c035?w=200&q=70'">
                    <div>
                        <div class="ss-name">{{ $salon->name }}</div>
                        <div class="ss-rating"><span class="stars">★★★★★</span> {{ number_format($salon->rating,1) }} <span style="color:#aaa;">({{ $salon->total_reviews * 10 + 250 }})</span></div>
                        <div class="ss-addr">{{ Str::limit($salon->address,35) }}</div>
                    </div>
                </div>
 
                <div class="svc-summary-box" id="svcBox">
                    <div class="svc-sum-row">
                        <div>
                            <div class="label">{{ $service->name }}</div>
                            <div class="sub" id="withLabel">{{ $service->duration_text }} with any professional</div>
                        </div>
                        <div class="price">Rs. {{ number_format($service->price) }}</div>
                    </div>
                    <div class="total-row">
                        <span>Total</span>
                        <span>Rs. {{ number_format($service->price) }}</span>
                    </div>
                </div>
 
                <form action="{{ route('client.booking.step2.post', $salon->id) }}" method="POST" id="step2Form">
    @csrf
    <input type="hidden" name="stylist_id" id="stylistInput" value="">
    <button type="submit" class="continue-btn" id="continueBtn" disabled>
        Continue <i class="fas fa-arrow-right"></i>
    </button>
</form>
            </div>
        </div>
    </div>
 
    <script>
    let selectedStylistId = '';
 
    function selectPro(card, id, name) {
        document.querySelectorAll('.pro-card').forEach(c => {
            c.classList.remove('selected');
            const cid = c.dataset.id;
            const btn = document.getElementById('btn-' + cid);
            if (btn) btn.outerHTML = '<button class="select-btn" id="btn-' + cid + '">Select</button>';
        });
 
        card.classList.add('selected');
        const btn = document.getElementById('btn-' + id);
        if (btn) btn.outerHTML = '<div class="check-circle" id="btn-' + id + '"><i class="fas fa-check"></i></div>';
 
        selectedStylistId = id;
        document.getElementById('stylistInput').value = id;
 
        
        const withLabel = document.getElementById('withLabel');
        if (withLabel) {
            withLabel.textContent = '{{ $service->duration_text }} with ' + (name === 'any professional' ? 'any professional' : name);
        }
 
        const btn2 = document.getElementById('continueBtn');
        btn2.classList.add('active');
        btn2.disabled = false;
    }
    </script>
</body>
</html>