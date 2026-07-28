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
        body { font-family: 'Inter', sans-serif; background: #f8f7fa; min-height: 100vh; -webkit-font-smoothing: antialiased; }

        /* ============================================================ */
        /* TOP NAV - BIGGER */
        /* ============================================================ */
        .top-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 28px;
            z-index: 200;
            background: #f8f7fa;
            border-bottom: 1px solid #f0e8ed;
        }
        .nav-btn {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            border: 1.5px solid #e0e0e0;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.1rem;
            color: #1a1a1a;
            transition: all .3s ease;
            text-decoration: none;
        }
        .nav-btn:hover { border-color: #E91E8C; color: #E91E8C; transform: scale(1.05); }
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.88rem;
            color: #aaa;
            flex-wrap: wrap;
            justify-content: center;
        }
        .breadcrumb .bc-step { color: #aaa; }
        .breadcrumb .bc-step.active { color: #E91E8C; font-weight: 700; }
        .breadcrumb .bc-sep { color: #ccc; font-size: 0.75rem; }

        /* ============================================================ */
        /* MAIN LAYOUT */
        /* ============================================================ */
        .booking-wrapper {
            padding-top: 82px;
            max-width: 1200px;
            margin: 0 auto;
            padding-left: 28px;
            padding-right: 28px;
            padding-bottom: 100px;
            min-height: calc(100vh - 100px);
            overflow-x: hidden;
        }
        .booking-layout {
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 40px;
            align-items: start;
        }
        @media(max-width:992px) {
            .booking-layout { grid-template-columns: 1fr; gap: 24px; }
        }

        /* ============================================================ */
        /* LEFT PANEL - BIGGER */
        /* ============================================================ */
        .left-panel {
            padding: 20px 0;
            overflow-x: hidden;
            width: 100%;
        }
        .left-panel h1 {
            font-size: 2.2rem;
            font-weight: 900;
            color: #1a1a1a;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }
        .left-panel .sub-heading {
            font-size: 0.95rem;
            color: #888;
            margin-bottom: 20px;
        }

        /* ============================================================ */
        /* PROFESSIONAL CARDS - BIGGER */
        /* ============================================================ */
        .pro-card {
            background: #fff;
            border: 1.5px solid #e8e8e8;
            border-radius: 14px;
            padding: 16px 20px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 16px;
            cursor: pointer;
            transition: all .3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            width: 100%;
        }
        .pro-card:hover {
            border-color: #E91E8C;
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(233,30,140,0.05);
        }
        .pro-card.selected {
            border-color: #E91E8C;
            border-width: 2px;
            background: #fff5f9;
        }
        .pro-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            overflow: hidden;
            background: #fce4ec;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            font-weight: 700;
            color: #E91E8C;
            flex-shrink: 0;
        }
        .pro-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .pro-avatar .fa-random { color: #E91E8C; font-size: 1.1rem; }
        .pro-info { flex: 1; min-width: 0; }
        .pro-info .pro-name {
            font-size: 1rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 2px;
        }
        .pro-info .pro-role {
            font-size: 0.85rem;
            color: #888;
            margin-bottom: 3px;
        }
        .pro-info .pro-rating {
            font-size: 0.82rem;
            font-weight: 600;
            color: #1a1a1a;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .pro-info .pro-rating i { color: #ffc107; }
        .pro-info .pro-profile {
            font-size: 0.78rem;
            color: #E91E8C;
            cursor: pointer;
            margin-top: 3px;
            display: inline-block;
            transition: all 0.2s ease;
        }
        .pro-info .pro-profile:hover {
            text-decoration: underline;
            color: #c2185b;
        }
        .select-btn {
            border: 1.5px solid #e0e0e0;
            border-radius: 50px;
            padding: 7px 18px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #1a1a1a;
            background: #fff;
            cursor: pointer;
            transition: all .3s ease;
            flex-shrink: 0;
        }
        .select-btn:hover { border-color: #E91E8C; color: #E91E8C; }
        .check-circle {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #E91E8C;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.85rem;
            flex-shrink: 0;
            box-shadow: 0 4px 15px rgba(233,30,140,0.2);
        }

        /* ============================================================ */
        /* SIDEBAR - BIGGER */
        /* ============================================================ */
        .sidebar {
            padding: 0;
            position: sticky;
            top: 85px;
            align-self: start;
        }
        @media(max-width:992px) {
            .sidebar {
                position: relative;
                top: 0;
                display: none;
            }
        }

        .salon-summary {
            background: #fff;
            border: 1.5px solid #f0e8ed;
            border-radius: 14px;
            padding: 16px 18px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .salon-summary img {
            width: 52px;
            height: 52px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
        }
        .salon-summary .ss-name {
            font-size: 0.92rem;
            font-weight: 700;
            color: #1a1a1a;
            line-height: 1.2;
        }
        .salon-summary .ss-rating {
            font-size: 0.78rem;
            color: #555;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .salon-summary .ss-rating .stars { color: #ffc107; }
        .salon-summary .ss-addr {
            font-size: 0.72rem;
            color: #888;
        }

        .svc-summary-box {
            background: #fff;
            border: 1.5px solid #f0e8ed;
            border-radius: 14px;
            padding: 16px 18px;
            margin-bottom: 16px;
        }
        .svc-sum-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 0.88rem;
        }
        .svc-sum-row .label {
            color: #1a1a1a;
            font-weight: 600;
        }
        .svc-sum-row .sub {
            color: #888;
            font-size: 0.78rem;
        }
        .svc-sum-row .price {
            font-weight: 700;
            color: #E91E8C;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding-top: 10px;
            border-top: 1.5px solid #f0e8ed;
            margin-top: 6px;
        }
        .total-row span {
            font-weight: 700;
            color: #1a1a1a;
            font-size: 0.95rem;
        }
        .total-row .total-val {
            color: #E91E8C;
            font-size: 1rem;
        }

        .continue-btn {
            background: #e0e0e0;
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 16px 32px;
            font-size: 1rem;
            font-weight: 700;
            width: 100%;
            cursor: not-allowed;
            transition: all .3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .continue-btn.active {
            background: linear-gradient(135deg, #E91E8C, #c2185b);
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(233,30,140,0.15);
        }
        .continue-btn.active:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(233,30,140,0.25);
        }

        /* ============================================================ */
        /* MOBILE BAR - BIGGER */
        /* ============================================================ */
        .mobile-bar {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            border-top: 1px solid #f0e8ed;
            padding: 14px 20px;
            z-index: 100;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.04);
        }
        @media(max-width:992px) {
            .mobile-bar { display: block; }
            .left-panel { padding: 16px 0; }
            .booking-wrapper { padding-left: 20px; padding-right: 20px; }
            .left-panel h1 { font-size: 1.8rem; }
        }
        @media(max-width:576px) {
            .breadcrumb { font-size: 0.7rem; gap: 4px; }
            .top-nav { padding: 10px 16px; }
            .nav-btn { width: 38px; height: 38px; font-size: 0.9rem; }
            .left-panel h1 { font-size: 1.5rem; }
            .left-panel .sub-heading { font-size: 0.82rem; }
            .pro-card { padding: 12px 14px; gap: 12px; }
            .pro-avatar { width: 44px; height: 44px; font-size: 1.1rem; }
            .pro-info .pro-name { font-size: 0.88rem; }
            .pro-info .pro-role { font-size: 0.75rem; }
            .pro-info .pro-rating { font-size: 0.75rem; }
            .pro-info .pro-profile { font-size: 0.72rem; }
            .select-btn { padding: 5px 14px; font-size: 0.75rem; }
            .check-circle { width: 30px; height: 30px; font-size: 0.75rem; }
            .booking-wrapper { padding-left: 14px; padding-right: 14px; padding-bottom: 85px; }
            .salon-summary { padding: 12px 14px; }
            .salon-summary img { width: 42px; height: 42px; }
            .salon-summary .ss-name { font-size: 0.82rem; }
            .svc-summary-box { padding: 12px 14px; }
            .svc-sum-row { font-size: 0.8rem; }
            .continue-btn { padding: 14px 24px; font-size: 0.92rem; }
        }
    </style>
</head>
<body>

{{-- ============================================================ --}}
{{-- TOP NAV --}}
{{-- ============================================================ --}}
<div class="top-nav">
    <a href="{{ route('booking.step1', $salon->id) }}" class="nav-btn"><i class="fas fa-arrow-left"></i></a>
    <div class="breadcrumb">
        <span class="bc-step">Services</span>
        <span class="bc-sep">›</span>
        <span class="bc-step active">Professional</span>
        <span class="bc-sep">›</span>
        <span class="bc-step">Time</span>
        <span class="bc-sep">›</span>
        <span class="bc-step">Confirm</span>
    </div>
    <a href="{{ route('salons.show', $salon->slug) }}" class="nav-btn"><i class="fas fa-times"></i></a>
</div>

{{-- ============================================================ --}}
{{-- MAIN CONTENT --}}
{{-- ============================================================ --}}
<div class="booking-wrapper">
    <div class="booking-layout">

        {{-- LEFT PANEL --}}
        <div class="left-panel">
            <h1>Select professional</h1>
            <p class="sub-heading">Choose a stylist for your appointment</p>

            {{-- No Preference --}}
            <div class="pro-card" data-id="any" onclick="selectPro(this, 'any', 'any professional')">
                <div class="pro-avatar">
                    <i class="fas fa-random"></i>
                </div>
                <div class="pro-info">
                    <div class="pro-name">No preference</div>
                    <div class="pro-role">Maximum availability</div>
                </div>
                <button class="select-btn" id="btn-any">Select</button>
            </div>

            {{-- Stylists --}}
            @foreach($stylists as $stylist)
            <div class="pro-card" data-id="{{ $stylist->id }}" onclick="selectPro(this, '{{ $stylist->id }}', '{{ $stylist->name }}')">
                <div class="pro-avatar">
                    @if($stylist->avatar)
                        <img src="{{ $stylist->avatar_url }}" alt="{{ $stylist->name }}" onerror="this.parentElement.textContent='{{ substr($stylist->name, 0, 1) }}'">
                    @else
                        {{ substr($stylist->name, 0, 1) }}
                    @endif
                </div>
                <div class="pro-info">
                    <div class="pro-name">{{ $stylist->name }}</div>
                    <div class="pro-role">{{ $stylist->specializations ? Str::limit($stylist->specializations, 30) : 'Stylist' }}</div>
                    <div class="pro-rating">
                        <i class="fas fa-star"></i>
                        {{ number_format($stylist->rating ?: 5.0, 1) }}
                    </div>
                    <a href="{{ route('stylist.profile', ['salonSlug' => $salon->slug, 'stylistId' => $stylist->id]) }}" class="pro-profile" onclick="event.stopPropagation();">View profile →</a>
                </div>
                <button class="select-btn" id="btn-{{ $stylist->id }}">Select</button>
            </div>
            @endforeach
        </div>

        {{-- RIGHT SIDEBAR --}}
        <div class="sidebar">
            <div class="salon-summary">
                <img src="{{ $salon->cover_image }}" alt="{{ $salon->name }}" onerror="this.src='https://images.unsplash.com/photo-1560066984-138dadb4c035?w=200&q=70'">
                <div>
                    <div class="ss-name">{{ $salon->name }}</div>
                    <div class="ss-rating">
                        <span class="stars">★★★★★</span>
                        {{ number_format($salon->rating, 1) }}
                        <span style="color:#aaa;">({{ $salon->reviews->count() + 100 }})</span>
                    </div>
                    <div class="ss-addr">{{ Str::limit($salon->address, 35) }}</div>
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
                    <span class="total-val">Rs. {{ number_format($service->price) }}</span>
                </div>
            </div>

            <form action="{{ route('booking.step2.post', $salon->id) }}" method="POST" id="step2Form">
                @csrf
                <input type="hidden" name="stylist_id" id="stylistInput" value="">
                <button type="submit" class="continue-btn" id="continueBtn" disabled>
                    Continue <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>

    </div>
</div>

{{-- ============================================================ --}}
{{-- MOBILE BAR --}}
{{-- ============================================================ --}}
<div class="mobile-bar">
    <form action="{{ route('booking.step2.post', $salon->id) }}" method="POST">
        @csrf
        <input type="hidden" name="stylist_id" id="mobileStylistInput" value="">
        <button type="submit" class="continue-btn" id="mobileContinueBtn" disabled>
            Continue <i class="fas fa-arrow-right"></i>
        </button>
    </form>
</div>

<script>
    let selectedStylistId = '';

    function selectPro(card, id, name) {
        document.querySelectorAll('.pro-card').forEach(c => {
            c.classList.remove('selected');
            const cid = c.dataset.id;
            const btn = document.getElementById('btn-' + cid);
            if (btn) {
                btn.outerHTML = '<button class="select-btn" id="btn-' + cid + '" onclick="event.stopPropagation(); selectPro(this.closest(\'.pro-card\'), \'' + cid + '\', \'' + (c.closest('.pro-card')?.querySelector('.pro-name')?.textContent || 'professional') + '\')">Select</button>';
            }
        });

        card.classList.add('selected');
        const btn = document.getElementById('btn-' + id);
        if (btn) {
            btn.outerHTML = '<div class="check-circle" id="btn-' + id + '"><i class="fas fa-check"></i></div>';
        }

        selectedStylistId = id;
        document.getElementById('stylistInput').value = id;
        document.getElementById('mobileStylistInput').value = id;

        const withLabel = document.getElementById('withLabel');
        if (withLabel) {
            withLabel.textContent = '{{ $service->duration_text }} with ' + (name === 'any professional' ? 'any professional' : name);
        }

        const btn2 = document.getElementById('continueBtn');
        btn2.classList.add('active');
        btn2.disabled = false;

        const mobileBtn = document.getElementById('mobileContinueBtn');
        mobileBtn.classList.add('active');
        mobileBtn.disabled = false;
    }
</script>

</body>
</html>