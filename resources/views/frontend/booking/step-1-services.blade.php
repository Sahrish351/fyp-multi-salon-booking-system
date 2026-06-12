
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
    body { font-family: 'Inter', sans-serif; background: #f5f5f5; min-height: 100vh; -webkit-font-smoothing: antialiased; }
 
    /* TOP NAV */
    .top-nav { position: fixed; top: 0; left: 0; right: 0; display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; z-index: 200; background: #f5f5f5; }
    .nav-btn { width: 44px; height: 44px; border-radius: 50%; border: 1.5px solid #e0e0e0; background: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 1rem; color: #1a1a1a; transition: all .15s; text-decoration: none; }
    .nav-btn:hover { border-color: #1a1a1a; }
 
    /* BREADCRUMB */
    .breadcrumb { text-align: center; padding: 72px 20px 0; font-size: 0.82rem; color: #aaa; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .breadcrumb .bc-step { color: #aaa; }
    .breadcrumb .bc-step.active { color: #1a1a1a; font-weight: 700; }
    .breadcrumb .bc-sep { color: #ccc; font-size: 0.72rem; }
 
    /* LAYOUT */
    .booking-layout { display: grid; grid-template-columns: 1fr 360px; gap: 0; max-width: 1200px; margin: 0 auto; padding: 0 24px 100px; min-height: calc(100vh - 100px); }
    @media(max-width:900px) { .booking-layout { grid-template-columns: 1fr; } .sidebar { display: none; } }
 
    /* LEFT */
    .left-panel { padding: 24px 40px 24px 0; }
    h1 { font-size: 2.2rem; font-weight: 900; color: #1a1a1a; letter-spacing: -1px; margin-bottom: 20px; }
 
    /* Category tabs */
    .cat-scroll { display: flex; gap: 8px; flex-wrap: nowrap; overflow-x: auto; scrollbar-width: none; padding-bottom: 4px; margin-bottom: 20px; }
    .cat-scroll::-webkit-scrollbar { display: none; }
    .cat-chip { border: 1.5px solid #e0e0e0; border-radius: 50px; padding: 7px 16px; font-size: 0.82rem; font-weight: 600; color: #555; background: #fff; cursor: pointer; white-space: nowrap; transition: all .15s; }
    .cat-chip.active, .cat-chip:hover { background: #1a1a1a; color: #fff; border-color: #1a1a1a; }
 
    /* Section title */
    .svc-section-title { font-size: 0.92rem; font-weight: 700; color: #1a1a1a; margin: 20px 0 12px; }
 
    /* Service card - FIXED: Pink outline instead of blue */
    .svc-card { background: #fff; border: 1.5px solid #e8e8e8; border-radius: 14px; padding: 18px 20px; margin-bottom: 10px; display: flex; align-items: center; justify-content: space-between; cursor: pointer; transition: all .15s; position: relative; }
    .svc-card:hover { border-color: #E91E8C; }
    .svc-card.selected { border-color: #E91E8C; border-width: 2px; background: #fff5f9; }
    .svc-card .sc-info .sc-name { font-size: 0.95rem; font-weight: 600; color: #1a1a1a; margin-bottom: 4px; }
    .svc-card .sc-info .sc-duration { font-size: 0.8rem; color: #888; margin-bottom: 8px; }
    .svc-card .sc-info .sc-price { font-size: 1rem; font-weight: 700; color: #1a1a1a; }
    .svc-card .sc-info .sc-desc { font-size: 0.78rem; color: #aaa; margin-top: 4px; }
    .add-btn { width: 36px; height: 36px; border-radius: 50%; border: 1.5px solid #e0e0e0; background: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 1.1rem; color: #555; transition: all .15s; flex-shrink: 0; }
    .add-btn:hover { border-color: #E91E8C; color: #E91E8C; }
    .check-btn { width: 36px; height: 36px; border-radius: 50%; background: #E91E8C; border: none; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 0.85rem; flex-shrink: 0; }
 
    /* SIDEBAR */
    .sidebar { padding: 24px 0 24px 32px; border-left: 1px solid #e8e8e8; }
    .salon-summary { background: #fff; border: 1.5px solid #e8e8e8; border-radius: 16px; padding: 16px; margin-bottom: 16px; display: flex; align-items: center; gap: 12px; }
    .salon-summary img { width: 56px; height: 56px; border-radius: 10px; object-fit: cover; }
    .salon-summary .ss-name { font-size: 0.95rem; font-weight: 700; color: #1a1a1a; }
    .salon-summary .ss-rating { font-size: 0.78rem; color: #555; display: flex; align-items: center; gap: 4px; }
    .salon-summary .ss-rating .stars { color: #ffc107; }
    .salon-summary .ss-addr { font-size: 0.72rem; color: #888; }
    .selected-services-box { background: #fff; border: 1.5px solid #e8e8e8; border-radius: 16px; padding: 16px; margin-bottom: 16px; min-height: 80px; }
    .no-services { color: #aaa; font-size: 0.85rem; }
    .selected-svc-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 8px 0; border-bottom: 1px solid #f5f5f5; }
    .selected-svc-row:last-child { border-bottom: none; }
    .ssv-name { font-size: 0.88rem; font-weight: 600; color: #1a1a1a; }
    .ssv-detail { font-size: 0.75rem; color: #888; }
    .ssv-price { font-size: 0.88rem; font-weight: 700; color: #1a1a1a; }
    .total-row { display: flex; justify-content: space-between; align-items: center; padding-top: 10px; }
    .total-row .total-lbl { font-size: 0.95rem; font-weight: 700; color: #1a1a1a; }
    .total-row .total-val { font-size: 0.95rem; font-weight: 700; color: #1a1a1a; }
 
    /* Continue button */
    .continue-btn { background: #aaa; color: #fff; border: none; border-radius: 50px; padding: 14px 28px; font-size: 0.95rem; font-weight: 700; width: 100%; cursor: not-allowed; transition: all .2s; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .continue-btn.active { background: #E91E8C; cursor: pointer; }
    .continue-btn.active:hover { background: #c2185b; }
 
    /* Mobile bottom bar */
    .mobile-bar { display: none; position: fixed; bottom: 0; left: 0; right: 0; background: #fff; border-top: 1px solid #f0f0f0; padding: 12px 16px; z-index: 100; }
    @media(max-width:900px) { .mobile-bar { display: block; } .left-panel { padding: 24px 0; } }
    </style>
</head>
<body>
    <div class="top-nav">
        <a href="{{ route('salons.show', $salon->slug) }}" class="nav-btn"><i class="fas fa-arrow-left"></i></a>
        <div class="breadcrumb" style="position:static;padding:0;background:none;">
            <span class="bc-step active">Services</span>
            <span class="bc-sep">›</span>
            <span class="bc-step">Professional</span>
            <span class="bc-sep">›</span>
            <span class="bc-step">Time</span>
            <span class="bc-sep">›</span>
            <span class="bc-step">Confirm</span>
        </div>
        <a href="{{ route('salons.show', $salon->slug) }}" class="nav-btn"><i class="fas fa-times"></i></a>
    </div>
 
    <div style="padding-top:72px;">
        <div class="booking-layout">
            <!-- LEFT -->
            <div class="left-panel">
                <h1>Select services</h1>
 
                <!-- Category chips -->
                <div class="cat-scroll">
                    <div class="cat-chip active" onclick="filterCat('all',this)">Featured</div>
                    @foreach($services->groupBy('category.name') as $catName => $catServices)
                    <div class="cat-chip" onclick="filterCat('{{ Str::slug($catName) }}',this)">{{ $catName }}</div>
                    @endforeach
                </div>
 
                <!-- Services by category -->
                @foreach($services->groupBy('category.name') as $catName => $catServices)
                <div class="svc-section-title cat-section" data-cat="{{ Str::slug($catName) }}">{{ $catName }}</div>
                @foreach($catServices as $service)
                <div class="svc-card cat-section" data-cat="{{ Str::slug($catName) }}" data-id="{{ $service->id }}" data-name="{{ $service->name }}" data-price="{{ $service->price }}" data-duration="{{ $service->duration_text }}" onclick="toggleService(this)">
                    <div class="sc-info">
                        <div class="sc-name">{{ $service->name }}</div>
                        <div class="sc-duration">{{ $service->duration_text }}</div>
                        <div class="sc-price">Rs. {{ number_format($service->price) }}</div>
                        @if($service->description)
                        <div class="sc-desc">{{ Str::limit($service->description, 80) }}</div>
                        @endif
                    </div>
                    <button class="add-btn" id="btn-{{ $service->id }}">+</button>
                </div>
                @endforeach
                @endforeach
            </div>
 
            <!-- SIDEBAR -->
            <div class="sidebar">
                <div class="salon-summary">
                    <img src="{{ $salon->cover_url }}" alt="{{ $salon->name }}" onerror="this.src='https://images.unsplash.com/photo-1560066984-138dadb4c035?w=200&q=70'">
                    <div>
                        <div class="ss-name">{{ $salon->name }}</div>
                        <div class="ss-rating">
                            <span class="stars">★★★★★</span>
                            <span>{{ number_format($salon->rating,1) }}</span>
                            <span style="color:#aaa;">({{ $salon->total_reviews * 10 + 250 }})</span>
                        </div>
                        <div class="ss-addr">{{ Str::limit($salon->address,35) }}</div>
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
 
                <!-- Desktop Form -->
<form action="{{ route('client.booking.step1.post', $salon->id) }}" method="POST" id="step1Form">
    @csrf
    <div id="selectedServicesInputs"></div>
    <input type="hidden" name="service_id" id="serviceIdInput">
    <button type="submit" class="continue-btn" id="continueBtn">
        Continue <i class="fas fa-arrow-right"></i>
    </button>
</form>
            </div>
        </div>
    </div>
 
    <!-- Mobile Form -->
<div class="mobile-bar">
    <form action="{{ route('client.booking.step1.post', $salon->id) }}" method="POST" id="mobileForm">
        @csrf
        <div id="mobileSelectedServicesInputs"></div>
        <button type="submit" class="continue-btn" id="mobileContinueBtn">
            Continue <i class="fas fa-arrow-right"></i>
        </button>
    </form>
</div>
 
    <script>
    let selectedServices = {};
    let totalAmount = 0;
 
    function toggleService(card) {
        const id = card.dataset.id;
        const name = card.dataset.name;
        const price = parseFloat(card.dataset.price);
        const duration = card.dataset.duration;
        const btn = document.getElementById('btn-' + id);
 
        if (selectedServices[id]) {
            // Deselect
            delete selectedServices[id];
            card.classList.remove('selected');
            btn.outerHTML = '<button class="add-btn" id="btn-' + id + '">+</button>';
        } else {
            // Select
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
    
    // Get containers for hidden inputs
    const desktopInputs = document.getElementById('selectedServicesInputs');
    const mobileInputs = document.getElementById('mobileSelectedServicesInputs');
    
    // Service ID input (for backward compatibility)
    const serviceIdInput = document.getElementById('serviceIdInput');

    totalAmount = 0;
    list.innerHTML = '';
    
    // Clear hidden inputs
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
        
        // ✅ Set service_id for form submission
        if (serviceIdInput) {
            serviceIdInput.value = keys[0];
        }
        
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
                        <div class="ssv-detail">${svc.duration} with any professional</div>
                    </div>
                    <div class="ssv-price">Rs. ${svc.price.toLocaleString()}</div>
                </div>`;
            
            // Add hidden input for each selected service
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
    function filterCat(cat, btn) {
        document.querySelectorAll('.cat-chip').forEach(c => c.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.cat-section').forEach(el => {
            el.style.display = (cat === 'all' || el.dataset.cat === cat) ? '' : 'none';
        });
    }
    </script>
</body>
</html>