
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment — {{ $salon->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script src="https://js.stripe.com/v3/"></script>
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; background: #f5f5f5; min-height: 100vh; -webkit-font-smoothing: antialiased; }
 
   
    .top-nav {
        position: fixed; top: 0; left: 0; right: 0;
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 20px; z-index: 200; background: #f5f5f5;
    }
    .nav-btn {
        width: 44px; height: 44px; border-radius: 50%;
        border: 1.5px solid #e0e0e0; background: #fff;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; font-size: 1rem; color: #1a1a1a;
        transition: all .15s; text-decoration: none;
    }
    .nav-btn:hover { border-color: #1a1a1a; }
    .breadcrumb-bar { display: flex; align-items: center; gap: 8px; font-size: 0.82rem; color: #aaa; }
    .breadcrumb-bar .active { color: #1a1a1a; font-weight: 700; }
 
   
    .pay-layout {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 0;
        max-width: 1100px;
        margin: 0 auto;
        padding: 90px 24px 60px;
        align-items: start;
    }
    @media(max-width:900px) {
        .pay-layout { grid-template-columns: 1fr; }
        .pay-sidebar { display: none; }
    }
 
    .pay-left { padding-right: 52px; }
    .pay-left h1 { font-size: 2rem; font-weight: 900; color: #1a1a1a; letter-spacing: -1px; margin-bottom: 6px; }
    .pay-sub { font-size: .85rem; color: #888; margin-bottom: 24px; }
 
    
    .secure-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: #f0fdf4; border: 1px solid #bbf7d0;
        border-radius: 50px; padding: 6px 14px;
        font-size: .72rem; font-weight: 600; color: #16a34a;
        margin-bottom: 24px;
    }
 
    /* METHOD TABS */
    .method-tabs { display: grid; grid-template-columns: repeat(4,1fr); gap: 10px; margin-bottom: 28px; }
    .method-tab {
        padding: 14px 10px; border: 2px solid #e8e8e8;
        border-radius: 14px; text-align: center; cursor: pointer;
        transition: all .2s; background: #fff;
        display: flex; flex-direction: column; align-items: center; gap: 6px;
    }
    .method-tab:hover { border-color: #E91E8C; background: #fff5f9; }
    .method-tab.active { border-color: #E91E8C; background: #fff5f9; }
    .method-tab i { font-size: 1.3rem; color: #888; }
    .method-tab.active i { color: #E91E8C; }
    .method-tab span { font-size: .72rem; font-weight: 600; color: #555; }
    .method-tab.active span { color: #E91E8C; }
 
    /* SECTIONS */
    .pay-section { display: none; }
    .pay-section.show { display: block; }
 
    /* FIELD */
    .field-group { margin-bottom: 18px; }
    .field-label { display: block; margin-bottom: 7px; font-size: .72rem; font-weight: 700; color: #555; letter-spacing: .5px; text-transform: uppercase; }
    .field-input {
        width: 100%; padding: 13px 16px;
        background: #fff; border: 1.5px solid #e8e8e8;
        border-radius: 12px; color: #1a1a1a; font-size: .88rem;
        font-family: 'Inter', sans-serif; outline: none; transition: border-color .15s;
    }
    .field-input:focus { border-color: #E91E8C; box-shadow: 0 0 0 3px rgba(233,30,140,0.08); }
    .field-input::placeholder { color: #bbb; }
    .fields-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
 
    /* STRIPE ELEMENT */
    #card-element {
        padding: 13px 16px; background: #fff;
        border: 1.5px solid #e8e8e8; border-radius: 12px;
        transition: border-color .15s;
    }
    #card-errors { color: #dc2626; font-size: .78rem; margin-top: 8px; min-height: 20px; }
 
    /* MANUAL PAYMENT BOX */
    .pay-account-box {
        border-radius: 18px; padding: 20px 22px; margin-bottom: 20px;
        color: #fff; position: relative; overflow: hidden;
    }
    .pab-ep { background: linear-gradient(135deg, #00a651, #007a3d); }
    .pab-jc { background: linear-gradient(135deg, #eb1c24, #b71c1c); }
    .pab-bk { background: linear-gradient(135deg, #1a1a2e, #16213e); }
    .pay-account-box::before {
        content: ''; position: absolute;
        right: -20px; top: -20px;
        width: 120px; height: 120px;
        border-radius: 50%; background: rgba(255,255,255,.08);
    }
    .pab-label { font-size: .65rem; letter-spacing: 1.5px; text-transform: uppercase; opacity: .7; margin-bottom: 4px; }
    .pab-value { font-size: 1.15rem; font-weight: 800; letter-spacing: .5px; }
    .pab-name  { font-size: .8rem; opacity: .8; margin-top: 3px; }
    .copy-btn {
        position: absolute; right: 18px; top: 50%; transform: translateY(-50%);
        background: rgba(255,255,255,.2); border: 1px solid rgba(255,255,255,.35);
        border-radius: 8px; padding: 6px 12px;
        font-size: .72rem; font-weight: 600; color: #fff;
        cursor: pointer; transition: background .15s;
    }
    .copy-btn:hover { background: rgba(255,255,255,.35); }
 
    /* UPLOAD BOX */
    .upload-box {
        border: 2px dashed #e8e8e8; border-radius: 14px;
        padding: 28px 20px; text-align: center;
        cursor: pointer; transition: all .2s; background: #fafafa;
        position: relative;
    }
    .upload-box:hover { border-color: #E91E8C; background: #fff5f9; }
    .upload-box input { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
    .upload-box i { font-size: 2rem; color: #ccc; margin-bottom: 8px; display: block; }
    .upload-box p { font-size: .8rem; color: #888; line-height: 1.5; }
    .upload-box strong { color: #E91E8C; }
    .upload-preview { display: none; margin-top: 12px; text-align: center; }
    .upload-preview img { max-width: 100%; border-radius: 10px; max-height: 200px; object-fit: contain; border: 1.5px solid #e8e8e8; }
    .upload-preview .change-btn { font-size: .75rem; color: #E91E8C; cursor: pointer; margin-top: 6px; display: block; }
 
    /* STEP INFO */
    .step-info {
        background: #fff8ec; border: 1px solid #fde68a;
        border-radius: 12px; padding: 12px 16px;
        font-size: .78rem; color: #92400e; margin-bottom: 20px;
        display: flex; align-items: flex-start; gap: 8px;
    }
    .step-info i { color: #f59e0b; flex-shrink: 0; margin-top: 1px; }
 
    /* PAY BUTTON */
    .pay-btn {
        width: 100%; padding: 15px;
        background: linear-gradient(135deg, #E91E8C, #c2185b);
        color: #fff; border: none; border-radius: 14px;
        font-size: .95rem; font-weight: 800;
        cursor: pointer; transition: all .2s;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        font-family: 'Inter', sans-serif;
        box-shadow: 0 8px 24px rgba(233,30,140,0.28);
        margin-top: 24px;
    }
    .pay-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(233,30,140,0.38); }
    .pay-btn:disabled { opacity: .6; cursor: not-allowed; transform: none; }
    .pay-btn-ep { background: linear-gradient(135deg,#00a651,#007a3d); box-shadow: 0 8px 24px rgba(0,166,81,0.28); }
    .pay-btn-ep:hover { box-shadow: 0 12px 32px rgba(0,166,81,0.38); }
    .pay-btn-jc { background: linear-gradient(135deg,#eb1c24,#b71c1c); box-shadow: 0 8px 24px rgba(235,28,36,0.28); }
    .pay-btn-jc:hover { box-shadow: 0 12px 32px rgba(235,28,36,0.38); }
    .pay-btn-bk { background: linear-gradient(135deg,#1a1a2e,#16213e); box-shadow: 0 8px 24px rgba(26,26,46,0.28); }
 
    .pay-note { text-align: center; font-size: .73rem; color: #aaa; margin-top: 14px; display: flex; align-items: center; justify-content: center; gap: 5px; }
 
    /* SPINNER */
    .spinner { display: none; width: 16px; height: 16px; border: 2.5px solid rgba(255,255,255,.4); border-top-color: #fff; border-radius: 50%; animation: spin .7s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
 
    /* SIDEBAR */
    .pay-sidebar { padding-left: 40px; position: sticky; top: 90px; }
 
    .salon-card {
        background: #fff; border: 1.5px solid #e8e8e8;
        border-radius: 18px; padding: 16px; margin-bottom: 14px;
        display: flex; align-items: center; gap: 14px;
    }
    .salon-img {
        width: 58px; height: 58px; border-radius: 12px; flex-shrink: 0;
        overflow: hidden; background: #fce4ec;
        display: flex; align-items: center; justify-content: center; font-size: 1.4rem;
    }
    .salon-img img { width: 100%; height: 100%; object-fit: cover; }
    .salon-name { font-size: .92rem; font-weight: 800; color: #1a1a1a; }
    .salon-loc  { font-size: .72rem; color: #888; margin-top: 2px; }
 
    .order-box { background: #fff; border: 1.5px solid #e8e8e8; border-radius: 18px; overflow: hidden; margin-bottom: 14px; }
    .order-hdr { padding: 12px 16px; background: #fafafa; border-bottom: 1px solid #f0f0f0; font-size: .7rem; font-weight: 700; color: #888; letter-spacing: .8px; text-transform: uppercase; }
    .order-row { padding: 13px 16px; display: flex; align-items: flex-start; justify-content: space-between; border-bottom: 1px solid #f9f9f9; }
    .order-row:last-child { border-bottom: none; }
    .or-name   { font-size: .85rem; font-weight: 600; color: #1a1a1a; }
    .or-detail { font-size: .72rem; color: #888; margin-top: 2px; }
    .or-price  { font-size: .88rem; font-weight: 800; color: #1a1a1a; white-space: nowrap; }
    .order-total { padding: 14px 16px; background: #fff5f9; border-top: 1px solid #ffe0f0; display: flex; justify-content: space-between; align-items: center; }
    .ot-lbl { font-size: .82rem; font-weight: 600; color: #555; }
    .ot-amt { font-size: 1.15rem; font-weight: 900; color: #E91E8C; }
 
    .advance-box {
        background: #fff8ec; border: 1px solid #fde68a;
        border-radius: 12px; padding: 12px 14px;
        font-size: .75rem; color: #92400e; margin-bottom: 14px;
        display: flex; align-items: flex-start; gap: 8px; line-height: 1.5;
    }
    .advance-box i { color: #f59e0b; flex-shrink: 0; margin-top: 2px; }
 
    .info-box { background: #fff; border: 1.5px solid #e8e8e8; border-radius: 18px; padding: 16px; }
    .info-row { display: flex; align-items: center; gap: 10px; padding: 7px 0; font-size: .8rem; color: #555; }
    .info-row i { width: 16px; color: #E91E8C; font-size: .78rem; }
    .info-row:not(:last-child) { border-bottom: 1px solid #f9f9f9; }
    </style>
</head>
<body>
 
<!-- TOP NAV -->
<div class="top-nav">
    <a href="{{ route('booking.step3', $salon->id) }}" class="nav-btn">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div class="breadcrumb-bar">
        <span>Services</span><span>›</span>
        <span>Professional</span><span>›</span>
        <span>Time</span><span>›</span>
        <span class="active">Payment</span>
    </div>
    <a href="{{ route('salons.show', $salon->slug) }}" class="nav-btn">
        <i class="fas fa-times"></i>
    </a>
</div>
 
<div class="pay-layout">
 
   
    <div class="pay-left">
        <h1>Complete payment</h1>
        <p class="pay-sub">Pay Rs.100 advance to secure your appointment</p>
 
        <div class="secure-badge">
            <i class="fas fa-shield-alt"></i> 256-bit SSL Encrypted & Secure
        </div>
 
    
        <div class="method-tabs">
            <div class="method-tab active" onclick="switchMethod('stripe',this)">
                <i class="fas fa-credit-card"></i>
                <span>Card / Stripe</span>
            </div>
            <div class="method-tab" onclick="switchMethod('easypaisa',this)">
                <i class="fas fa-mobile-alt" style="color:#00a651;"></i>
                <span>EasyPaisa</span>
            </div>
            <div class="method-tab" onclick="switchMethod('jazzcash',this)">
                <i class="fas fa-mobile-alt" style="color:#eb1c24;"></i>
                <span>JazzCash</span>
            </div>
            <div class="method-tab" onclick="switchMethod('bank',this)">
                <i class="fas fa-university"></i>
                <span>Bank Transfer</span>
            </div>
        </div>
 
      
        <div class="pay-section show" id="sec-stripe">
            <div class="step-info">
                <i class="fas fa-info-circle"></i>
                Enter your card details below. Payment is processed securely via Stripe.
            </div>
            <form id="stripeForm" action="{{ route('booking.payment.post', $salon->id) }}" method="POST">
                @csrf
                <input type="hidden" name="payment_method" value="stripe">
                <input type="hidden" name="stripe_payment_method_id" id="stripePayMethodId">
 
                <div class="field-group">
                    <label class="field-label">Cardholder Name</label>
                    <input type="text" name="cardholder_name" class="field-input" placeholder="As it appears on card" required>
                </div>
 
                <div class="field-group">
                    <label class="field-label">Card Details</label>
                    <div id="card-element"></div>
                    <div id="card-errors"></div>
                </div>
 
                <button type="submit" class="pay-btn" id="stripePayBtn">
                    <i class="fas fa-lock" id="stripeIcon"></i>
                    Pay Rs.100 Securely
                    <span class="spinner" id="stripeSpinner"></span>
                </button>
                <p class="pay-note">
                    <i class="fab fa-stripe" style="font-size:1.1rem;color:#635bff;"></i>
                    Payments powered by Stripe
                </p>
            </form>
        </div>
 
       
        <div class="pay-section" id="sec-easypaisa">
            <div class="pay-account-box pab-ep">
                <div class="pab-label">Send Rs.100 to EasyPaisa</div>
                <div class="pab-value">0300-1234567</div>
                <div class="pab-name">Glamora Payments (Pvt) Ltd</div>
                <button type="button" class="copy-btn" onclick="copyNum('03001234567',this)">
                    <i class="fas fa-copy"></i> Copy
                </button>
            </div>
            <form action="{{ route('booking.step4.post', $salon->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="payment_method" value="easypaisa">
                <div class="field-group">
                    <label class="field-label">Your EasyPaisa Number *</label>
                    <input type="text" name="sender_number" class="field-input" placeholder="e.g. 03XX-XXXXXXX" required>
                </div>
                <div class="field-group">
                    <label class="field-label">Transaction ID *</label>
                    <input type="text" name="transaction_id" class="field-input" placeholder="Enter 11-digit transaction ID" required>
                </div>
                <div class="field-group">
                    <label class="field-label">Upload Screenshot *</label>
                    <div class="upload-box" id="epBox" onclick="document.getElementById('epFile').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Click to upload payment screenshot<br><strong>JPG, PNG</strong> — max 2MB</p>
                        <input type="file" id="epFile" name="screenshot" accept="image/*" style="display:none;" onchange="previewImg(this,'epPrev','epBox')" required>
                    </div>
                    <div class="upload-preview" id="epPrev">
                        <img src="" alt="Preview">
                        <span class="change-btn" onclick="changeFile('epBox','epPrev')">Change photo</span>
                    </div>
                </div>
                <button type="submit" class="pay-btn pay-btn-ep">
                    <i class="fas fa-paper-plane"></i> Submit EasyPaisa Payment
                </button>
            </form>
        </div>
 
      
        <div class="pay-section" id="sec-jazzcash">
            <div class="pay-account-box pab-jc">
                <div class="pab-label">Send Rs.100 to JazzCash</div>
                <div class="pab-value">0300-7654321</div>
                <div class="pab-name">Glamora Payments (Pvt) Ltd</div>
                <button type="button" class="copy-btn" onclick="copyNum('03007654321',this)">
                    <i class="fas fa-copy"></i> Copy
                </button>
            </div>
            <form action="{{ route('booking.step4.post', $salon->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="payment_method" value="jazzcash">
                <div class="field-group">
                    <label class="field-label">Your JazzCash Number *</label>
                    <input type="text" name="sender_number" class="field-input" placeholder="e.g. 03XX-XXXXXXX" required>
                </div>
                <div class="field-group">
                    <label class="field-label">Transaction ID *</label>
                    <input type="text" name="transaction_id" class="field-input" placeholder="Enter transaction ID" required>
                </div>
                <div class="field-group">
                    <label class="field-label">Upload Screenshot *</label>
                    <div class="upload-box" id="jcBox" onclick="document.getElementById('jcFile').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Click to upload payment screenshot<br><strong>JPG, PNG</strong> — max 2MB</p>
                        <input type="file" id="jcFile" name="screenshot" accept="image/*" style="display:none;" onchange="previewImg(this,'jcPrev','jcBox')" required>
                    </div>
                    <div class="upload-preview" id="jcPrev">
                        <img src="" alt="Preview">
                        <span class="change-btn" onclick="changeFile('jcBox','jcPrev')">Change photo</span>
                    </div>
                </div>
                <button type="submit" class="pay-btn pay-btn-jc">
                    <i class="fas fa-paper-plane"></i> Submit JazzCash Payment
                </button>
            </form>
        </div>
 
      
        <div class="pay-section" id="sec-bank">
            <div class="pay-account-box pab-bk">
                <div class="pab-label">Bank Transfer Details</div>
                <div class="pab-value">MCB — 1234-5678-9012</div>
                <div class="pab-name">Glamora (Pvt) Ltd · IBAN: PK00MCB01234567890123</div>
                <button type="button" class="copy-btn" onclick="copyNum('12345678901234',this)">
                    <i class="fas fa-copy"></i> Copy
                </button>
            </div>
            <form action="{{ route('booking.step4.post', $salon->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="payment_method" value="bank_transfer">
                <div class="field-group">
                    <label class="field-label">Your Bank & Account Name *</label>
                    <input type="text" name="sender_number" class="field-input" placeholder="e.g. Sahrish Khan — HBL" required>
                </div>
                <div class="field-group">
                    <label class="field-label">Transaction / Reference ID *</label>
                    <input type="text" name="transaction_id" class="field-input" placeholder="Enter bank reference number" required>
                </div>
                <div class="field-group">
                    <label class="field-label">Upload Bank Receipt *</label>
                    <div class="upload-box" id="bkBox" onclick="document.getElementById('bkFile').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Upload bank transfer receipt<br><strong>JPG, PNG, PDF</strong> — max 5MB</p>
                        <input type="file" id="bkFile" name="screenshot" accept="image/*,.pdf" style="display:none;" onchange="previewImg(this,'bkPrev','bkBox')" required>
                    </div>
                    <div class="upload-preview" id="bkPrev">
                        <img src="" alt="Preview">
                        <span class="change-btn" onclick="changeFile('bkBox','bkPrev')">Change file</span>
                    </div>
                </div>
                <button type="submit" class="pay-btn pay-btn-bk">
                    <i class="fas fa-upload"></i> Submit Bank Payment
                </button>
            </form>
        </div>
 
    </div>
 
   
    <div class="pay-sidebar">
 
   
        <div class="salon-card">
            <div class="salon-img">
                @if($salon->cover_photo)
                <img src="{{ asset('storage/'.$salon->cover_photo) }}" alt="{{ $salon->name }}" onerror="this.parentElement.textContent='💆'">
                @else 💆 @endif
            </div>
            <div>
                <div class="salon-name">{{ $salon->name }}</div>
                <div class="salon-loc"><i class="fas fa-map-marker-alt" style="color:#E91E8C;font-size:.65rem;"></i> {{ $salon->city }}</div>
            </div>
        </div>
 
       
        <div class="order-box">
            <div class="order-hdr">Booking Summary</div>
 
            <div class="order-row">
                <div>
                    <div class="or-name">{{ $service->name }}</div>
                    <div class="or-detail">{{ $service->duration_text ?? $service->duration_minutes.' min' }}</div>
                </div>
                <div class="or-price">Rs.{{ number_format($service->price) }}</div>
            </div>
 
            <div class="order-row">
                <div>
                    <div class="or-name">Stylist</div>
                    <div class="or-detail">{{ $stylist->name }}</div>
                </div>
            </div>
 
            @if(isset($slot) && $slot)
            <div class="order-row">
                <div>
                    <div class="or-name">Appointment</div>
                    <div class="or-detail">
                        {{ \Carbon\Carbon::parse($slot->slot_date)->format('d M Y') }}
                        · {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}
                    </div>
                </div>
            </div>
            @endif
 
            <div class="order-total">
                <span class="ot-lbl">Total Amount</span>
                <span class="ot-amt">Rs.{{ number_format($service->price) }}</span>
            </div>
        </div>
 
        
        <div class="advance-box">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong>Rs.100 advance required now</strong> to confirm your appointment. The remaining amount (Rs.{{ number_format($service->price - 100) }}) is paid at the salon.
            </div>
        </div>
 
      
        <div class="info-box">
            <div style="font-size:.7rem;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.8px;margin-bottom:8px;">Appointment Details</div>
            @if(isset($slot) && $slot)
            <div class="info-row">
                <i class="fas fa-calendar"></i>
                {{ \Carbon\Carbon::parse($slot->slot_date)->format('l, d F Y') }}
            </div>
            <div class="info-row">
                <i class="fas fa-clock"></i>
                {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} — {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
            </div>
            @endif
            <div class="info-row">
                <i class="fas fa-user-tie"></i>
                {{ $stylist->name }}
            </div>
            <div class="info-row">
                <i class="fas fa-spa"></i>
                {{ $service->name }}
            </div>
            <div class="info-row" style="margin-top:8px;padding-top:10px;border-top:1px solid #f0f0f0 !important;">
                <i class="fas fa-money-bill-wave" style="color:#10b981 !important;"></i>
                <strong style="color:#10b981;">Pay Now: Rs.100</strong>
            </div>
        </div>
 
    </div>
 
</div>
 
<script>

@if(config('services.stripe.key'))
const stripe = Stripe('{{ config("services.stripe.key") }}');
const elements = stripe.elements();
const cardEl = elements.create('card', {
    style: {
        base: {
            fontFamily: 'Inter, sans-serif',
            fontSize: '15px',
            color: '#1a1a1a',
            '::placeholder': { color: '#bbb' },
            iconColor: '#E91E8C',
        },
        invalid: { color: '#dc2626', iconColor: '#dc2626' }
    }
});
cardEl.mount('#card-element');
 
cardEl.on('change', e => {
    document.getElementById('card-errors').textContent = e.error ? e.error.message : '';
});
 
document.getElementById('stripeForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('stripePayBtn');
    const icon = document.getElementById('stripeIcon');
    const spin = document.getElementById('stripeSpinner');
    btn.disabled = true;
    icon.style.display = 'none';
    spin.style.display = 'inline-block';
 
    const { paymentMethod, error } = await stripe.createPaymentMethod({
        type: 'card',
        card: cardEl,
        billing_details: {
            name: document.querySelector('[name="cardholder_name"]').value
        }
    });
 
    if (error) {
        document.getElementById('card-errors').textContent = error.message;
        btn.disabled = false;
        icon.style.display = 'inline';
        spin.style.display = 'none';
    } else {
        document.getElementById('stripePayMethodId').value = paymentMethod.id;
        this.submit();
    }
});
@else
document.getElementById('card-element').innerHTML = '<p style="color:#888;font-size:.82rem;padding:8px 0;"><i class="fas fa-exclamation-triangle" style="color:#f59e0b;"></i> Stripe key not configured. Add STRIPE_KEY to .env</p>';
@endif
 

function switchMethod(method, tab) {
    document.querySelectorAll('.method-tab').forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    document.querySelectorAll('.pay-section').forEach(s => s.classList.remove('show'));
    document.getElementById('sec-' + method).classList.add('show');
}
 

function copyNum(num, btn) {
    navigator.clipboard.writeText(num).then(() => {
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
        setTimeout(() => btn.innerHTML = orig, 2000);
    });
}
 

function previewImg(input, prevId, boxId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const prev = document.getElementById(prevId);
            const box  = document.getElementById(boxId);
            prev.querySelector('img').src = e.target.result;
            prev.style.display = 'block';
            box.style.display  = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
 
function changeFile(boxId, prevId) {
    document.getElementById(boxId).style.display  = 'block';
    document.getElementById(prevId).style.display = 'none';
}
</script>
</body>
</html>