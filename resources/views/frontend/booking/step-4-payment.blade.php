<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment — {{ $salon->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
    :root {
        --pink: #FF6B9D;
        --pink-dark: #E85588;
        --pink-soft: #fce4ec;
        --pink-bg: #fff5f9;
        --text: #2d1f2c;
        --muted: #8a7a88;
        --line: #f3dfe8;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: 'Poppins', sans-serif;
        background: #ffffff;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 86px 16px 50px;
        -webkit-font-smoothing: antialiased;
        color: var(--text);
    }
 
   
    .top-nav {
        position: fixed; top: 0; left: 0; right: 0;
        display: flex; align-items: center; justify-content: space-between;
        padding: 12px 24px; z-index: 200; background: #fff;
        border-bottom: 1px solid var(--line);
    }
    .nav-btn {
        width: 40px; height: 40px; border-radius: 50%;
        border: 1.5px solid var(--line); background: #fff;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; color: #666; text-decoration: none; font-size: .9rem; transition: all .15s;
    }
    .nav-btn:hover { border-color: var(--pink); color: var(--pink); background: var(--pink-bg); }
 
    .breadcrumb { display: flex; align-items: center; gap: 6px; font-size: .8rem; color: #b5a3b0; }
    .breadcrumb .active { color: var(--pink-dark); font-weight: 700; }
 
   
    .page-title { text-align: center; margin-bottom: 20px; }
    .page-title h1 {
        font-family: 'Playfair Display', serif;
        font-size: 1.85rem; font-weight: 700; color: var(--text);
        line-height: 1.2;
    }
    .page-title h1 span { color: var(--pink); }
    .page-title p { color: var(--muted); font-size: .84rem; margin-top: 5px; }
 
   
    .pay-card {
        width: 100%; max-width: 540px;
        background: #fff;
        border-radius: 26px;
        padding: 26px 26px 24px;
        border: 1px solid var(--line);
        box-shadow: 0 10px 44px rgba(255,107,157,.13);
    }
 
    
    .amount-strip {
        position: relative; overflow: hidden;
        background: linear-gradient(135deg, var(--pink), var(--pink-dark));
        border-radius: 20px;
        padding: 20px 24px;
        color: #fff;
        margin-bottom: 22px;
        display: flex; align-items: center; justify-content: space-between;
        box-shadow: 0 10px 26px rgba(232,85,136,.28);
    }
    .amount-strip::before, .amount-strip::after {
        content: ''; position: absolute; border-radius: 50%;
        background: rgba(255,255,255,.12);
    }
    .amount-strip::before { width: 150px; height: 150px; right: -40px; top: -60px; }
    .amount-strip::after  { width: 90px; height: 90px; right: 60px; bottom: -50px; }
    .amount-strip > div:first-child { position: relative; z-index: 1; }
    .amount-strip .lbl { font-size: .68rem; color: rgba(255,255,255,.85); text-transform: uppercase; letter-spacing: .8px; font-weight: 600; margin-bottom: 2px; }
    .amount-strip .amt { font-size: 2.2rem; font-weight: 800; line-height: 1.15; }
    .amount-strip .to { font-size: .76rem; color: rgba(255,255,255,.88); margin-top: 3px; }
    .amount-strip .icon-wrap {
        position: relative; z-index: 1;
        width: 54px; height: 54px; border-radius: 16px;
        background: rgba(255,255,255,.2);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.45rem;
    }
 
  
    .sec-label {
        font-size: .72rem; font-weight: 700; color: var(--pink-dark);
        text-transform: uppercase; letter-spacing: .8px;
        margin-bottom: 12px;
        display: flex; align-items: center; gap: 8px;
    }
    .sec-label::after { content: ''; flex: 1; height: 1px; background: var(--line); }
 
   
    .method-grid {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;
        margin-bottom: 16px;
    }
    .method-card {
        position: relative;
        border: 2px solid var(--line);
        border-radius: 16px;
        background: #fff;
        padding: 12px 6px 10px;
        text-align: center;
        cursor: pointer;
        transition: all .18s ease;
        display: flex; flex-direction: column; align-items: center; gap: 4px;
    }
    .method-card:hover { border-color: var(--pink); transform: translateY(-2px); box-shadow: 0 6px 18px rgba(255,107,157,.14); }
    .method-card.active {
        border-color: var(--pink);
        background: var(--pink-bg);
        box-shadow: 0 6px 20px rgba(255,107,157,.2);
    }
    .method-card .logo { width: 100%; height: 42px; display: flex; align-items: center; justify-content: center; }
    .method-card .logo svg { width: 100%; height: 100%; }
    .method-card .m-name { font-size: .72rem; font-weight: 600; color: #6d5a68; }
    .method-card.active .m-name { color: var(--pink-dark); }
    .method-card .tick {
        position: absolute; top: -8px; right: -8px;
        width: 22px; height: 22px; border-radius: 50%;
        background: var(--pink); color: #fff;
        font-size: .6rem;
        display: none; align-items: center; justify-content: center;
        border: 2px solid #fff;
        box-shadow: 0 2px 8px rgba(232,85,136,.4);
    }
    .method-card.active .tick { display: flex; }
 
   
    .account-box {
        border: 1.5px solid var(--pink-soft);
        border-radius: 18px;
        margin-bottom: 22px;
        display: none;
        overflow: hidden;
        background: #fff;
    }
    .account-box.show { display: block; animation: fadeUp .25s ease; }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: none; } }
 
    .acct-head {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 16px;
        color: #fff;
    }
    .acct-head.ep { background: linear-gradient(135deg, #2fb34a, #1f8f39); }
    .acct-head.jc { background: linear-gradient(135deg, #ee2b33, #c4161d); }
    .acct-head.bk { background: linear-gradient(135deg, #1a6cc4, #0d4a8f); }
    .acct-head .h-ic {
        width: 34px; height: 34px; border-radius: 10px;
        background: rgba(255,255,255,.22);
        display: flex; align-items: center; justify-content: center; font-size: .95rem;
    }
    .acct-head .h-t { font-size: .88rem; font-weight: 700; line-height: 1.2; }
    .acct-head .h-s { font-size: .68rem; opacity: .85; }
 
    .acct-body { padding: 4px 16px 6px; background: #fffafc; }
    .acct-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 10px 0; font-size: .84rem; gap: 10px;
    }
    .acct-row:not(:last-child) { border-bottom: 1px dashed var(--pink-soft); }
    .acct-key { color: var(--muted); font-size: .76rem; }
    .acct-val { font-weight: 700; color: var(--text); letter-spacing: .4px; display: flex; align-items: center; gap: 8px; text-align: right; }
    .acct-val.normal { letter-spacing: 0; font-weight: 600; }
    .copy-btn {
        background: linear-gradient(135deg, var(--pink), var(--pink-dark)); color: #fff; border: none;
        border-radius: 20px; padding: 5px 13px;
        font-size: .68rem; font-weight: 600; cursor: pointer;
        font-family: 'Poppins', sans-serif; transition: all .15s;
    }
    .copy-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(232,85,136,.35); }
    .copy-btn.ok { background: #16a34a; }
 
   
    .field { margin-bottom: 16px; }
    .field label { font-size: .78rem; font-weight: 600; color: #5b4a57; display: block; margin-bottom: 6px; }
    .field label span { color: var(--pink-dark); }
    .input-wrap { position: relative; }
    .input-wrap i {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: #d9b3c5; font-size: .85rem; pointer-events: none;
    }
    .field input[type="text"], .field input[type="tel"] {
        width: 100%; border: 1.5px solid var(--line); border-radius: 12px;
        padding: 12px 14px 12px 40px; font-size: .88rem; font-family: 'Poppins', sans-serif;
        color: var(--text); background: #fffafc; transition: all .15s;
    }
    .field input::placeholder { color: #c9b6c1; }
    .field input:focus {
        outline: none; border-color: var(--pink); background: #fff;
        box-shadow: 0 0 0 4px rgba(255,107,157,.13);
    }
    .field-error { color: #dc2626; font-size: .72rem; margin-top: 5px; }
 
   
    .upload-box {
        border: 2px dashed #f7a9c4; border-radius: 18px;
        padding: 44px 20px; text-align: center; cursor: pointer;
        background: var(--pink-bg); transition: all .2s; position: relative;
        min-height: 190px;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
    }
    .upload-box:hover, .upload-box.over { border-color: var(--pink-dark); background: #ffe9f2; }
    .upload-box input { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
    .upload-box .up-ic {
        width: 64px; height: 64px; border-radius: 50%;
        background: #fff; color: var(--pink);
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 12px; font-size: 1.7rem;
        box-shadow: 0 6px 18px rgba(255,107,157,.22);
    }
    .upload-box .ub-t { font-size: .92rem; font-weight: 600; color: #5b4a57; margin-bottom: 4px; }
    .upload-box .ub-s { font-size: .74rem; color: #b5a3b0; }
 
    .preview-box { display: none; position: relative; border-radius: 18px; overflow: hidden; border: 1.5px solid var(--pink-soft); }
    .preview-box img { width: 100%; max-height: 300px; object-fit: contain; background: #fffafc; display: block; }
    .rm-btn {
        position: absolute; top: 10px; right: 10px;
        width: 30px; height: 30px; border-radius: 50%;
        background: rgba(45,31,44,.55); color: #fff; border: none;
        cursor: pointer; font-size: .75rem;
        display: flex; align-items: center; justify-content: center;
    }
    .rm-btn:hover { background: var(--pink-dark); }
 
   
    .err-box {
        background: #fff5f5; border: 1px solid #fecaca; border-radius: 12px;
        padding: 10px 14px; font-size: .78rem; color: #dc2626;
    }
 
   
    .submit-btn {
        width: 100%; margin-top: 20px; padding: 15px;
        background: linear-gradient(135deg, var(--pink), var(--pink-dark));
        color: #fff; border: none; border-radius: 50px;
        font-size: .96rem; font-weight: 700; cursor: pointer;
        font-family: 'Poppins', sans-serif; transition: all .2s;
        display: flex; align-items: center; justify-content: center; gap: 9px;
        box-shadow: 0 10px 26px rgba(232,85,136,.32);
    }
    .submit-btn:hover { transform: translateY(-2px); box-shadow: 0 14px 34px rgba(232,85,136,.42); }
    .submit-btn:disabled { opacity: .65; cursor: not-allowed; transform: none; }
 
    .note {
        font-size: .72rem; color: #b5a3b0; text-align: center; margin-top: 12px; line-height: 1.55;
    }
    .note i { color: var(--pink); margin-right: 4px; }
 
    @media (max-width: 520px) {
        body { padding-top: 80px; }
        .page-title h1 { font-size: 1.55rem; }
        .pay-card { padding: 20px 16px 20px; border-radius: 22px; }
        .breadcrumb { font-size: .68rem; gap: 4px; }
        .amount-strip .amt { font-size: 2rem; }
        .method-grid { gap: 8px; }
        .upload-box { padding: 34px 16px; min-height: 170px; }
    }
    </style>
    @include('partials.favicon')
</head>
<body>
 
<div class="top-nav">
    <a href="{{ route('booking.step3', $salon->id) }}" class="nav-btn"><i class="fas fa-arrow-left"></i></a>
    <div class="breadcrumb">
        <span>Services</span> <span class="bc-sep">›</span>
        <span>Stylist</span> <span class="bc-sep">›</span>
        <span>Date & Time</span> <span class="bc-sep">›</span>
        <span class="active">Payment</span>
    </div>
    <a href="{{ route('salons.show', $salon->slug) }}" class="nav-btn"><i class="fas fa-times"></i></a>
</div>
 
<div class="page-title">
    <h1>Confirm Your <span>Payment</span></h1>
    <p>Pay a small advance to secure your appointment</p>
</div>
 
<div class="pay-card">
 
    {{-- AMOUNT --}}
    <div class="amount-strip">
        <div>
            <div class="lbl">Advance Payment</div>
            <div class="amt">Rs. 100</div>
            <div class="to"><i class="fas fa-store" style="margin-right:5px;"></i>{{ $salon->name }} · {{ $salon->city }}</div>
        </div>
        <div class="icon-wrap"><i class="fas fa-shield-heart"></i></div>
    </div>
 
    {{-- METHOD CARDS --}}
    <div class="sec-label">Payment Method</div>
    <div class="method-grid">
 
        {{-- EasyPaisa --}}
        <div class="method-card active" id="tab-ep" onclick="selectTab('ep')">
            <span class="tick"><i class="fas fa-check"></i></span>
            <div class="logo">
                <svg viewBox="0 0 130 44" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="EasyPaisa">
                    <rect x="2" y="6" width="32" height="32" rx="9" fill="#2fb34a"/>
                    <circle cx="18" cy="22" r="9.5" fill="none" stroke="#fff" stroke-width="2.6"/>
                    <path d="M11.5 22h13" stroke="#fff" stroke-width="2.6" stroke-linecap="round"/>
                    <path d="M24.2 26.6a7.6 7.6 0 0 1-6.2 3.2" stroke="#2fb34a" stroke-width="3" stroke-linecap="round" fill="none"/>
                    <text x="40" y="26" font-family="Poppins, Arial, sans-serif" font-size="14" font-weight="700" fill="#1f8f39">easy</text>
                    <text x="72" y="26" font-family="Poppins, Arial, sans-serif" font-size="14" font-weight="700" fill="#2fb34a">paisa</text>
                    <text x="40" y="37" font-family="Poppins, Arial, sans-serif" font-size="7" font-weight="600" fill="#7fbf8b">MOBILE ACCOUNT</text>
                </svg>
            </div>
        </div>
 
        {{-- JazzCash --}}
        <div class="method-card" id="tab-jc" onclick="selectTab('jc')">
            <span class="tick"><i class="fas fa-check"></i></span>
            <div class="logo">
                <svg viewBox="0 0 130 44" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="JazzCash">
                    <rect x="2" y="6" width="32" height="32" rx="9" fill="#ee2b33"/>
                    <path d="M21.5 13v11.2a4.7 4.7 0 0 1-9.4 0" stroke="#fff" stroke-width="3.2" stroke-linecap="round" fill="none"/>
                    <circle cx="26" cy="14" r="3.4" fill="#ffd21f"/>
                    <text x="40" y="26" font-family="Poppins, Arial, sans-serif" font-size="14" font-weight="800" fill="#ee2b33">Jazz</text>
                    <text x="68" y="26" font-family="Poppins, Arial, sans-serif" font-size="14" font-weight="800" fill="#f5a400">Cash</text>
                    <text x="40" y="37" font-family="Poppins, Arial, sans-serif" font-size="7" font-weight="600" fill="#e88a8e">MOBILE ACCOUNT</text>
                </svg>
            </div>
        </div>
 
        {{-- Bank (UBL) --}}
        <div class="method-card" id="tab-bk" onclick="selectTab('bk')">
            <span class="tick"><i class="fas fa-check"></i></span>
            <div class="logo">
                <svg viewBox="0 0 130 44" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Bank Transfer">
                    <rect x="2" y="6" width="32" height="32" rx="9" fill="#1a6cc4"/>
                    <path d="M18 12l11 5H7l11-5zM10 19v9M15 19v9M21 19v9M26 19v9M8 30h20" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    <text x="40" y="26" font-family="Poppins, Arial, sans-serif" font-size="15" font-weight="800" fill="#0d4a8f">UBL</text>
                    <text x="40" y="37" font-family="Poppins, Arial, sans-serif" font-size="7" font-weight="600" fill="#6d8fb5">BANK TRANSFER</text>
                </svg>
            </div>
        </div>
    </div>
 
    {{-- EASYPAISA DETAIL --}}
    <div class="account-box show" id="detail-ep">
        <div class="acct-head ep">
            <div class="h-ic"><i class="fas fa-mobile-screen-button"></i></div>
            <div>
                <div class="h-t">Send via EasyPaisa</div>
                <div class="h-s">Mobile wallet transfer</div>
            </div>
        </div>
        <div class="acct-body">
            <div class="acct-row">
                <span class="acct-key">Account Number</span>
                <span class="acct-val">0306-9734142 <button type="button" class="copy-btn" id="cp-ep" onclick="copyNum('03069734142','cp-ep')">Copy</button></span>
            </div>
            <div class="acct-row">
                <span class="acct-key">Account Title</span>
                <span class="acct-val normal">Sahrish Yaseen</span>
            </div>
            <div class="acct-row">
                <span class="acct-key">Type</span>
                <span class="acct-val normal">EasyPaisa Mobile Account</span>
            </div>
        </div>
    </div>
 
    {{-- JAZZCASH DETAIL --}}
    <div class="account-box" id="detail-jc">
        <div class="acct-head jc">
            <div class="h-ic"><i class="fas fa-mobile-screen-button"></i></div>
            <div>
                <div class="h-t">Send via JazzCash</div>
                <div class="h-s">Mobile wallet transfer</div>
            </div>
        </div>
        <div class="acct-body">
            <div class="acct-row">
                <span class="acct-key">Account Number</span>
                <span class="acct-val">0306-9734142 <button type="button" class="copy-btn" id="cp-jc" onclick="copyNum('03069734142','cp-jc')">Copy</button></span>
            </div>
            <div class="acct-row">
                <span class="acct-key">Account Title</span>
                <span class="acct-val normal">Sahrish Yaseen</span>
            </div>
            <div class="acct-row">
                <span class="acct-key">Type</span>
                <span class="acct-val normal">JazzCash Mobile Account</span>
            </div>
        </div>
    </div>
 
    {{-- BANK DETAIL --}}
    <div class="account-box" id="detail-bk">
        <div class="acct-head bk">
            <div class="h-ic"><i class="fas fa-building-columns"></i></div>
            <div>
                <div class="h-t">Bank Transfer</div>
                <div class="h-s">United Bank Limited</div>
            </div>
        </div>
        <div class="acct-body">
            <div class="acct-row">
                <span class="acct-key">Account Number</span>
                <span class="acct-val">390487874 <button type="button" class="copy-btn" id="cp-bk" onclick="copyNum('390487874','cp-bk')">Copy</button></span>
            </div>
            <div class="acct-row">
                <span class="acct-key">Account Title</span>
                <span class="acct-val normal">Sahrish Yaseen</span>
            </div>
            <div class="acct-row">
                <span class="acct-key">Bank Name</span>
                <span class="acct-val normal">UBL (United Bank Limited)</span>
            </div>
        </div>
    </div>
 
    {{-- FORM --}}
    <form action="{{ route('booking.payment.post', $salon->id) }}"
          method="POST" enctype="multipart/form-data" id="payForm">
        @csrf
        <input type="hidden" name="payment_method" id="methodInput" value="easypaisa">
 
        <div class="field">
            <label>Transaction / Reference Number <span>*</span></label>
            <div class="input-wrap">
                <i class="fas fa-hashtag"></i>
                <input type="text" name="transaction_ref" required
                    placeholder="e.g. TXN0012345678"
                    value="{{ old('transaction_ref') }}">
            </div>
            @error('transaction_ref')<div class="field-error">{{ $message }}</div>@enderror
        </div>
 
        <div class="field">
            <label>Your Mobile Number (paid from) <span>*</span></label>
            <div class="input-wrap">
                <i class="fas fa-phone"></i>
                <input type="tel" name="sender_number" required
                    placeholder="03XX-XXXXXXX"
                    value="{{ old('sender_number') }}">
            </div>
            @error('sender_number')<div class="field-error">{{ $message }}</div>@enderror
        </div>
 
        <div class="field">
            <label>Payment Screenshot <span>*</span></label>
            <div class="upload-box" id="uploadBox"
                 ondragover="event.preventDefault();this.classList.add('over')"
                 ondragleave="this.classList.remove('over')"
                 ondrop="handleDrop(event)">
                <input type="file" name="screenshot" id="ssFile"
                       accept="image/*" onchange="previewSS(this)">
                <div class="up-ic"><i class="fas fa-cloud-arrow-up"></i></div>
                <div class="ub-t">Tap to upload screenshot</div>
                <div class="ub-s">JPG · PNG · WEBP · max 5 MB</div>
            </div>
            <div class="preview-box" id="previewBox">
                <img id="previewImg" src="" alt="">
                <button type="button" class="rm-btn" onclick="removeSS()"><i class="fas fa-times"></i></button>
            </div>
            @error('screenshot')<div class="field-error" style="margin-top:6px;">{{ $message }}</div>@enderror
        </div>
 
        @if($errors->any())
        <div class="err-box">
            @foreach($errors->all() as $err)<div>• {{ $err }}</div>@endforeach
        </div>
        @endif
 
        <button type="submit" class="submit-btn" id="subBtn">
            <i class="fas fa-paper-plane"></i> Submit Payment
        </button>
    </form>
 
    <p class="note"><i class="fas fa-lock"></i>After submission, your booking will stay pending until the salon owner verifies your payment screenshot.</p>
</div>
 
<script>
const methodMap = { ep:'easypaisa', jc:'jazzcash', bk:'bank' };
 
function selectTab(key) {
    ['ep','jc','bk'].forEach(k => {
        document.getElementById('tab-'+k).classList.toggle('active', k===key);
        document.getElementById('detail-'+k).classList.toggle('show', k===key);
    });
    document.getElementById('methodInput').value = methodMap[key];
}
 
function copyNum(num, btnId) {
    navigator.clipboard.writeText(num).then(() => {
        const b = document.getElementById(btnId);
        b.textContent = 'Copied!'; b.classList.add('ok');
        setTimeout(() => { b.textContent = 'Copy'; b.classList.remove('ok'); }, 2000);
    });
}
 
function previewSS(input) {
    if (!input.files[0]) return;
    const r = new FileReader();
    r.onload = e => {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('uploadBox').style.display = 'none';
        document.getElementById('previewBox').style.display = 'block';
    };
    r.readAsDataURL(input.files[0]);
}
 
function removeSS() {
    document.getElementById('ssFile').value = '';
    document.getElementById('uploadBox').style.display = 'flex';
    document.getElementById('previewBox').style.display = 'none';
}
 
function handleDrop(e) {
    e.preventDefault();
    document.getElementById('uploadBox').classList.remove('over');
    const f = e.dataTransfer.files[0];
    if (f && f.type.startsWith('image/')) {
        const dt = new DataTransfer(); dt.items.add(f);
        const inp = document.getElementById('ssFile');
        inp.files = dt.files; previewSS(inp);
    }
}
 
document.getElementById('payForm').addEventListener('submit', () => {
    const b = document.getElementById('subBtn');
    b.disabled = true;
    b.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
});
</script>
</body>
</html>
 