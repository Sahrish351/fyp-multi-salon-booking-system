<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Select services — {{ $salon->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: 'Inter', sans-serif; 
            background: #f8f7fa; 
            min-height: 100vh; 
            -webkit-font-smoothing: antialiased; 
            overflow-x: hidden;
        }

        .top-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 28px; z-index: 200;
            background: #f8f7fa; border-bottom: 1px solid #f0e8ed;
        }
        .nav-btn {
            width: 46px; height: 46px; border-radius: 50%;
            border: 1.5px solid #e0e0e0; background: #fff;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 1.1rem; color: #1a1a1a;
            transition: all .3s ease; text-decoration: none;
        }
        .nav-btn:hover { border-color: #E91E8C; color: #E91E8C; transform: scale(1.05); }

        .breadcrumb {
            display: flex; align-items: center; gap: 8px;
            font-size: 0.88rem; color: #aaa; flex-wrap: wrap; justify-content: center;
        }
        .breadcrumb .bc-step { color: #aaa; }
        .breadcrumb .bc-step.active { color: #E91E8C; font-weight: 700; }
        .breadcrumb .bc-sep { color: #ccc; font-size: 0.75rem; }

        .booking-wrapper {
            padding-top: 82px; max-width: 1200px; margin: 0 auto;
            padding-left: 28px; padding-right: 28px; padding-bottom: 100px;
            min-height: calc(100vh - 100px); overflow-x: hidden;
        }

        .booking-layout {
            display: grid; grid-template-columns: 1fr 360px;
            gap: 40px; align-items: start;
        }
        @media(max-width:992px) {
            .booking-layout { grid-template-columns: 1fr; gap: 24px; }
        }

        .left-panel { padding: 20px 0; overflow-x: hidden; width: 100%; }
        .left-panel h1 {
            font-size: 2.2rem; font-weight: 900; color: #1a1a1a;
            letter-spacing: -0.5px; margin-bottom: 8px;
        }
        .left-panel .sub-heading { font-size: 0.95rem; color: #888; margin-bottom: 20px; }

        .cat-scroll {
            display: flex; gap: 10px; overflow-x: auto;
            padding-bottom: 10px; margin-bottom: 20px;
            scrollbar-width: none; -webkit-overflow-scrolling: touch;
        }
        .cat-scroll::-webkit-scrollbar { display: none; }
        .cat-chip {
            border: 1.5px solid #e0e0e0; border-radius: 50px;
            padding: 8px 22px; font-size: 0.88rem; font-weight: 600;
            color: #555; background: #fff; cursor: pointer;
            white-space: nowrap; transition: all .3s ease; flex-shrink: 0;
        }
        .cat-chip.active, .cat-chip:hover {
            background: #E91E8C; color: #fff; border-color: #E91E8C;
            transform: translateY(-2px); box-shadow: 0 4px 15px rgba(233,30,140,0.15);
        }

        .svc-card {
            background: #fff; border: 1.5px solid #e8e8e8; border-radius: 14px;
            padding: 16px 20px; margin-bottom: 10px; display: flex;
            align-items: center; justify-content: space-between; cursor: pointer;
            transition: all .3s cubic-bezier(0.175, 0.885, 0.32, 1.275); position: relative; width: 100%;
        }
        .svc-card:hover {
            border-color: #E91E8C; transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(233,30,140,0.06);
        }
        .svc-card.selected { border-color: #E91E8C; border-width: 2px; background: #fff5f9; }
        .svc-card .sc-info { flex: 1; min-width: 0; }
        .svc-card .sc-info .sc-name { font-size: 1rem; font-weight: 600; color: #1a1a1a; margin-bottom: 3px; }
        .svc-card .sc-info .sc-duration { font-size: 0.82rem; color: #888; margin-bottom: 3px; }
        .svc-card .sc-info .sc-price { font-size: 1rem; font-weight: 700; color: #E91E8C; }
        
        .add-btn {
            width: 36px; height: 36px; border-radius: 50%; border: 1.5px solid #e0e0e0;
            background: #fff; display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 1rem; color: #555; transition: all .3s ease; flex-shrink: 0;
        }
        .add-btn:hover { border-color: #E91E8C; color: #E91E8C; transform: scale(1.05); }
        .check-btn {
            width: 36px; height: 36px; border-radius: 50%; background: #E91E8C;
            border: none; display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 0.85rem; flex-shrink: 0; box-shadow: 0 4px 15px rgba(233,30,140,0.2);
        }

        .sidebar { padding: 0; position: sticky; top: 85px; align-self: start; }
        @media(max-width:992px) { .sidebar { position: relative; top: 0; display: none; } }

        .salon-summary {
            background: #fff; border: 1.5px solid #f0e8ed; border-radius: 16px;
            padding: 16px 18px; margin-bottom: 16px; display: flex; align-items: center; gap: 14px;
        }
        .salon-summary img { width: 56px; height: 56px; border-radius: 10px; object-fit: cover; flex-shrink: 0; }
        .salon-summary .ss-name { font-size: 0.95rem; font-weight: 700; color: #1a1a1a; line-height: 1.2; }
        .salon-summary .ss-rating { font-size: 0.78rem; color: #555; display: flex; align-items: center; gap: 4px; }
        .salon-summary .ss-rating .stars { color: #ffc107; }
        .salon-summary .ss-addr { font-size: 0.72rem; color: #888; }

        .selected-services-box {
            background: #fff; border: 1.5px solid #f0e8ed; border-radius: 16px;
            padding: 18px; margin-bottom: 16px; min-height: 100px; max-height: 320px; overflow-y: auto;
        }
        .selected-services-box::-webkit-scrollbar { width: 4px; }
        .selected-services-box::-webkit-scrollbar-thumb { background: #E91E8C; border-radius: 10px; }

        .no-services { color: #aaa; font-size: 0.88rem; text-align: center; padding: 12px 0; }
        .selected-svc-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 8px 0; border-bottom: 1px solid #f5f5f5; }
        .selected-svc-row:last-child { border-bottom: none; }
        .ssv-name { font-size: 0.88rem; font-weight: 600; color: #1a1a1a; }
        .ssv-detail { font-size: 0.75rem; color: #888; }
        .ssv-price { font-size: 0.88rem; font-weight: 700; color: #E91E8C; }

        .total-row { display: flex; justify-content: space-between; align-items: center; padding-top: 12px; border-top: 2px solid #f0e8ed; margin-top: 6px; }
        .total-row .total-lbl { font-size: 0.95rem; font-weight: 700; color: #1a1a1a; }
        .total-row .total-val { font-size: 1rem; font-weight: 800; color: #E91E8C; }

        .continue-btn {
            background: #e0e0e0; color: #fff; border: none; border-radius: 50px;
            padding: 16px 32px; font-size: 1rem; font-weight: 700; width: 100%;
            cursor: not-allowed; transition: all .3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        .continue-btn.active {
            background: linear-gradient(135deg, #E91E8C, #c2185b); cursor: pointer;
            box-shadow: 0 4px 20px rgba(233,30,140,0.2);
        }
        .continue-btn.active:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(233,30,140,0.3); }

        .mobile-bar {
            display: none; position: fixed; bottom: 0; left: 0; right: 0;
            background: #fff; border-top: 1px solid #f0e8ed; padding: 14px 20px;
            z-index: 100; box-shadow: 0 -4px 20px rgba(0,0,0,0.04);
        }
        @media(max-width:992px) {
            .mobile-bar { display: block; }
            .left-panel { padding: 16px 0; }
            .booking-wrapper { padding-left: 20px; padding-right: 20px; }
            .left-panel h1 { font-size: 1.8rem; }
        }
    </style>
</head>
<body>

<div class="top-nav">
    <a href="{{ route('salons.show', $salon->slug ?? $salon->id) }}" class="nav-btn"><i class="fas fa-arrow-left"></i></a>
    <div class="breadcrumb">
        <span class="bc-step active">Services</span>
        <span class="bc-sep">›</span>
        <span class="bc-step">Professional</span>
        <span class="bc-sep">›</span>
        <span class="bc-step">Time</span>
        <span class="bc-sep">›</span>
        <span class="bc-step">Confirm</span>
    </div>
    <a href="{{ route('salons.show', $salon->slug ?? $salon->id) }}" class="nav-btn"><i class="fas fa-times"></i></a>
</div>

<div class="booking-wrapper">
    <div class="booking-layout">

        <div class="left-panel">
            <h1>Select services</h1>
            <p class="sub-heading">Choose the services you'd like to book</p>

            {{-- Category Filter Chips --}}
<div class="cat-scroll">
    <div class="cat-chip active" onclick="filterCat('all', 'all', this)">All Services</div>
    @if(isset($services) && count($services) > 0)
        @php
            // Extract only unique categories attached to this salon's services
            $salonCategories = $services->pluck('category')->filter()->unique('id');
        @endphp

        @foreach($salonCategories as $category)
            <div class="cat-chip" onclick="filterCat('{{ $category->id }}', '{{ strtolower($category->name) }}', this)">
                {{ $category->name }}
            </div>
        @endforeach
    @endif
</div>

            {{-- Services Display Loop --}}
            <div id="servicesContainer">
                @forelse($services as $service)
                    <div class="svc-card cat-section" 
                         data-category-id="{{ $service->category_id ?? $service->salon_category_id ?? '' }}"
                         data-category-name="{{ strtolower($service->category->name ?? $service->category_name ?? '') }}"
                         data-id="{{ $service->id }}" 
                         data-name="{{ $service->name }}" 
                         data-price="{{ $service->price }}" 
                         data-duration="{{ $service->duration_minutes ?? $service->duration ?? '30' }} mins" 
                         onclick="toggleService(this)">
                        <div class="sc-info">
                            <div class="sc-name">{{ $service->name }}</div>
                            <div class="sc-duration"><i class="far fa-clock"></i> {{ $service->duration_minutes ?? $service->duration ?? '30' }} mins</div>
                            <div class="sc-price">Rs. {{ number_format($service->price) }}</div>
                        </div>
                        <button class="add-btn" id="btn-{{ $service->id }}">+</button>
                    </div>
                @empty
                    <div class="text-center py-5" style="background: #fff; padding: 30px; border-radius: 12px; border: 1px dashed #ccc;">
                        <i class="fas fa-concierge-bell fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No services available for this salon yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="sidebar">
            <div class="salon-summary">
                <img src="{{ $salon->cover_image }}" alt="{{ $salon->name }}" onerror="this.src='https://images.unsplash.com/photo-1560066984-138dadb4c035?w=200&q=70'">
                <div>
                    <div class="ss-name">{{ $salon->name }}</div>
                    <div class="ss-rating">
                        <span class="stars">★★★★★</span>
                        <span>{{ number_format($salon->rating ?? 5.0, 1) }}</span>
                    </div>
                    <div class="ss-addr">{{ Str::limit($salon->address, 35) }}</div>
                </div>
            </div>

            <div class="selected-services-box" id="selectedBox">
                <div class="no-services" id="noServicesMsg">No services selected</div>
                <div id="selectedList"></div>
                <div class="total-row" id="totalRow" style="display:none;">
                    <span class="total-lbl">Total</span>
                    <span class="total-val" id="totalVal">Rs. 0</span>
                </div>
            </div>

            <form action="{{ route('booking.step1.post', $salon->id) }}" method="POST" id="step1Form">
                @csrf
                <div id="selectedServicesInputs"></div>
                <input type="hidden" name="service_id" id="serviceIdInput">
                <button type="submit" class="continue-btn" id="continueBtn" disabled>
                    Continue <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>

    </div>
</div>

<div class="mobile-bar">
    <form action="{{ route('booking.step1.post', $salon->id) }}" method="POST" id="mobileForm">
        @csrf
        <div id="mobileSelectedServicesInputs"></div>
        <button type="submit" class="continue-btn" id="mobileContinueBtn" disabled>
            Continue <i class="fas fa-arrow-right"></i>
        </button>
    </form>
</div>

<script>
    let selectedServices = {};
    let totalAmount = 0;

    function filterCat(catId, catName, btn) {
        document.querySelectorAll('.cat-chip').forEach(c => c.classList.remove('active'));
        if (btn) btn.classList.add('active');

        const targetId = String(catId || '').trim();
        const targetName = String(catName || '').trim().toLowerCase();

        const cards = document.querySelectorAll('.svc-card');
        cards.forEach(card => {
            const cardCatId = String(card.dataset.categoryId || '').trim();
            const cardCatName = String(card.dataset.categoryName || '').trim().toLowerCase();

            if (targetId === 'all') {
                card.style.display = 'flex';
            } else if ((targetId !== '' && cardCatId === targetId) || (targetName !== '' && cardCatName.includes(targetName))) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function toggleService(card) {
        const id = card.dataset.id;
        const name = card.dataset.name;
        const price = parseFloat(card.dataset.price);
        const duration = card.dataset.duration;
        const btn = document.getElementById('btn-' + id);

        if (selectedServices[id]) {
            delete selectedServices[id];
            card.classList.remove('selected');
            btn.outerHTML = '<button class="add-btn" id="btn-' + id + '">+</button>';
        } else {
            selectedServices[id] = { name, price, duration };
            card.classList.add('selected');
            btn.outerHTML = '<div class="check-btn" id="btn-' + id + '"><i class="fas fa-check"></i></div>';
        }
        updateSidebar();
    }

    function updateSidebar() {
        const keys = Object.keys(selectedServices);
        const noMsg = document.getElementById('noServicesMsg');
        const list = document.getElementById('selectedList');
        const totalRow = document.getElementById('totalRow');
        const continueBtn = document.getElementById('continueBtn');
        const mobileContinueBtn = document.getElementById('mobileContinueBtn');
        const desktopInputs = document.getElementById('selectedServicesInputs');
        const mobileInputs = document.getElementById('mobileSelectedServicesInputs');
        const serviceIdInput = document.getElementById('serviceIdInput');

        totalAmount = 0;
        list.innerHTML = '';
        if (desktopInputs) desktopInputs.innerHTML = '';
        if (mobileInputs) mobileInputs.innerHTML = '';

        if (keys.length === 0) {
            noMsg.style.display = 'block';
            totalRow.style.display = 'none';
            continueBtn.classList.remove('active');
            continueBtn.disabled = true;
            if (serviceIdInput) serviceIdInput.value = '';
            if (mobileContinueBtn) {
                mobileContinueBtn.classList.remove('active');
                mobileContinueBtn.disabled = true;
            }
        } else {
            noMsg.style.display = 'none';
            totalRow.style.display = 'flex';
            continueBtn.classList.add('active');
            continueBtn.disabled = false;
            if (serviceIdInput) serviceIdInput.value = keys[0];
            if (mobileContinueBtn) {
                mobileContinueBtn.classList.add('active');
                mobileContinueBtn.disabled = false;
            }

            keys.forEach(id => {
                const svc = selectedServices[id];
                totalAmount += svc.price;
                list.innerHTML += `
                    <div class="selected-svc-row">
                        <div>
                            <div class="ssv-name">${svc.name}</div>
                            <div class="ssv-detail">${svc.duration}</div>
                        </div>
                        <div class="ssv-price">Rs. ${svc.price.toLocaleString()}</div>
                    </div>`;
                if (desktopInputs) {
                    desktopInputs.innerHTML += `<input type="hidden" name="service_ids[]" value="${id}">`;
                }
                if (mobileInputs) {
                    mobileInputs.innerHTML += `<input type="hidden" name="service_ids[]" value="${id}">`;
                }
            });

            document.getElementById('totalVal').textContent = 'Rs. ' + totalAmount.toLocaleString();
        }
    }
</script>

</body>
</html>