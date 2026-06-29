{{-- FILE: resources/views/frontend/booking/step-4-payment.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment — {{ $salon->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: 'Inter', sans-serif;
        background: #f5f0fc;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 0 16px 60px;
        -webkit-font-smoothing: antialiased;
        color: #1a1a1a;
    }
 
    /* TOP BAR */
    .top-bar {
        width: 100%; max-width: 520px;
        display: flex; align-items: center; justify-content: space-between;
        padding: 18px 0 24px;
    }
    .back-btn {
        width: 40px; height: 40px; border-radius: 50%;
        background: #fff; border: 1.5px solid #e5daf0;
        display: flex; align-items: center; justify-content: center;
        color: #555; text-decoration: none; font-size: .9rem; transition: all .15s;
    }
    .back-btn:hover { border-color: #E91E8C; color: #E91E8C; }
    .brand {
        font-size: 1.1rem; font-weight: 900; font-style: italic;
        background: linear-gradient(135deg, #E91E8C, #9333ea);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    .close-btn {
        width: 40px; height: 40px; border-radius: 50%;
        background: #fff; border: 1.5px solid #e5daf0;
        display: flex; align-items: center; justify-content: center;
        color: #555; text-decoration: none; font-size: .9rem; transition: all .15s;
    }
    .close-btn:hover { border-color: #E91E8C; color: #E91E8C; }
 
    /* MAIN CARD */
    .pay-card {
        width: 100%; max-width: 520px;
        background: #fff;
        border-radius: 24px;
        padding: 32px 28px;
        box-shadow: 0 4px 40px rgba(147,51,234,0.08);
    }
 
    /* AMOUNT STRIP */
    .amount-strip {
        background: linear-gradient(135deg, #E91E8C, #9333ea);
        border-radius: 16px;
        padding: 22px 24px;
        color: #fff;
        margin-bottom: 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .amount-strip .lbl { font-size: .7rem; color: rgba(255,255,255,.8); text-transform: uppercase; letter-spacing: .6px; font-weight: 700; margin-bottom: 4px; }
    .amount-strip .amt { font-size: 2rem; font-weight: 900; }
    .amount-strip .to { font-size: .72rem; color: rgba(255,255,255,.7); margin-top: 2px; }
    .amount-strip .icon { font-size: 2rem; opacity: .25; }
 
    /* SECTION LABEL */
    .sec-label {
        font-size: .72rem; font-weight: 800; color: #9333ea;
        text-transform: uppercase; letter-spacing: .6px;
        margin-bottom: 12px;
    }
 
    /* PAYMENT METHOD TABS */
    .method-tabs {
        display: flex; gap: 8px;
        background: #f5f0fc;
        padding: 5px;
        border-radius: 14px;
        margin-bottom: 18px;
    }
    .method-tab {
        flex: 1; text-align: center; padding: 10px 8px;
        border-radius: 10px; font-size: .78rem; font-weight: 700;
        color: #999; cursor: pointer; transition: all .18s;
        display: flex; flex-direction: column; align-items: center; gap: 4px;
    }
    .method-tab i { font-size: 1rem; }
    .method-tab.active {
        background: #fff; color: #E91E8C;
        box-shadow: 0 2px 12px rgba(233,30,140,0.1);
    }
 
    /* ACCOUNT DETAIL BOX */
    .account-box {
        background: #fdf5fb;
        border: 1.5px solid #f0e0f5;
        border-radius: 14px;
        padding: 16px 18px;
        margin-bottom: 24px;
        display: none;
    }
    .account-box.show { display: block; }
    .acct-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 8px 0; font-size: .84rem;
    }
    .acct-row:not(:last-child) { border-bottom: 1px solid #f0e0f5; }
    .acct-key { color: #888; font-size: .76rem; }
    .acct-val { font-weight: 700; color: #1a1a1a; font-family: monospace; letter-spacing: .5px; display: flex; align-items: center; gap: 8px; }
    .acct-val.normal { font-family: 'Inter', sans-serif; letter-spacing: 0; }
    .copy-btn {
        background: #E91E8C; color: #fff; border: none;
        border-radius: 20px; padding: 4px 12px;
        font-size: .68rem; font-weight: 700; cursor: pointer;
        font-family: 'Inter', sans-serif; transition: all .15s;
    }
    .copy-btn:hover { background: #c2185b; }
    .copy-btn.ok { background: #16a34a; }
 
    /* DIVIDER */
    .divider { height: 1px; background: #f0e8f8; margin: 24px 0; }
 
    /* FORM FIELDS */
    .field { margin-bottom: 16px; }
    .field label { font-size: .76rem; font-weight: 700; color: #555; display: block; margin-bottom: 6px; }
    .field label span { color: #E91E8C; }
    .field input {
        width: 100%; border: 1.5px solid #e5daf0; border-radius: 12px;
        padding: 12px 14px; font-size: .88rem; font-family: 'Inter', sans-serif;
        color: #1a1a1a; background: #fdfbfe; transition: border-color .15s;
    }
    .field input:focus { outline: none; border-color: #E91E8C; background: #fff; }
    .field-error { color: #dc2626; font-size: .72rem; margin-top: 5px; }
 
    /* UPLOAD */
    .upload-box {
        border: 2px dashed #d8b4fe; border-radius: 14px;
        padding: 28px 20px; text-align: center; cursor: pointer;
        background: #fdfbfe; transition: all .2s; position: relative;
    }
    .upload-box:hover, .upload-box.over { border-color: #E91E8C; background: #fff5fb; }
    .upload-box input { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
    .upload-box i { font-size: 1.8rem; color: #d8b4fe; display: block; margin-bottom: 8px; }
    .upload-box .ub-t { font-size: .84rem; font-weight: 700; color: #555; margin-bottom: 3px; }
    .upload-box .ub-s { font-size: .7rem; color: #bbb; }
 
    .preview-box { display: none; position: relative; border-radius: 14px; overflow: hidden; border: 1.5px solid #f0e0f5; }
    .preview-box img { width: 100%; max-height: 200px; object-fit: contain; background: #fdfbfe; display: block; }
    .rm-btn {
        position: absolute; top: 8px; right: 8px;
        width: 26px; height: 26px; border-radius: 50%;
        background: rgba(0,0,0,.45); color: #fff; border: none;
        cursor: pointer; font-size: .72rem;
        display: flex; align-items: center; justify-content: center;
    }
 
    /* SUBMIT */
    .submit-btn {
        width: 100%; margin-top: 22px; padding: 15px;
        background: linear-gradient(135deg, #E91E8C, #9333ea);
        color: #fff; border: none; border-radius: 14px;
        font-size: .96rem; font-weight: 800; cursor: pointer;
        font-family: 'Inter', sans-serif; transition: all .2s;
        display: flex; align-items: center; justify-content: center; gap: 9px;
        box-shadow: 0 8px 24px rgba(147,51,234,0.22);
    }
    .submit-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(147,51,234,0.3); }
    .submit-btn:disabled { opacity: .65; cursor: not-allowed; transform: none; }
 
    .note { font-size: .72rem; color: #aaa; text-align: center; margin-top: 12px; line-height: 1.5; }
    </style>
</head>
<body>
 
<div class="top-bar">
    <a href="{{ route('booking.step3', $salon->id) }}" class="back-btn"><i class="fas fa-arrow-left"></i></a>
    <span class="brand">glamora</span>
    <a href="{{ route('salons.show', $salon->slug) }}" class="close-btn"><i class="fas fa-times"></i></a>
</div>
 
<div class="pay-card">
 
    {{-- AMOUNT --}}
    <div class="amount-strip">
        <div>
            <div class="lbl">Advance Payment</div>
            <div class="amt">Rs. 100</div>
            <div class="to">{{ $salon->name }} · {{ $salon->city }}</div>
        </div>
        <i class="fas fa-shield-alt icon"></i>
    </div>
 
    {{-- METHOD TABS --}}
    <div class="sec-label">Payment Method</div>
    <div class="method-tabs">
        <div class="method-tab active" id="tab-ep" onclick="selectTab('ep')">
            <i class="fas fa-mobile-alt"></i> EasyPaisa
        </div>
        <div class="method-tab" id="tab-jc" onclick="selectTab('jc')">
            <i class="fas fa-mobile-alt"></i> JazzCash
        </div>
        <div class="method-tab" id="tab-bk" onclick="selectTab('bk')">
            <i class="fas fa-university"></i> Bank
        </div>
    </div>
 
    {{-- EASYPAISA DETAIL --}}
    <div class="account-box show" id="detail-ep">
        <div class="acct-row">
            <span class="acct-key">Account Number</span>
            <span class="acct-val">0306-9734142 <button class="copy-btn" id="cp-ep" onclick="copyNum('03069734142','cp-ep')">Copy</button></span>
        </div>
        <div class="acct-row">
            <span class="acct-key">Account Title</span>
            <span class="acct-val normal">Glamora Salon</span>
        </div>
        <div class="acct-row">
            <span class="acct-key">Type</span>
            <span class="acct-val normal">EasyPaisa Mobile Account</span>
        </div>
    </div>
 
    {{-- JAZZCASH DETAIL --}}
    <div class="account-box" id="detail-jc">
        <div class="acct-row">
            <span class="acct-key">Account Number</span>
            <span class="acct-val">0306-9734142 <button class="copy-btn" id="cp-jc" onclick="copyNum('03069734142','cp-jc')">Copy</button></span>
        </div>
        <div class="acct-row">
            <span class="acct-key">Account Title</span>
            <span class="acct-val normal">Glamora Salon</span>
        </div>
        <div class="acct-row">
            <span class="acct-key">Type</span>
            <span class="acct-val normal">JazzCash Mobile Account</span>
        </div>
    </div>
 
    {{-- BANK DETAIL --}}
    <div class="account-box" id="detail-bk">
        <div class="acct-row">
            <span class="acct-key">Bank Name</span>
            <span class="acct-val normal">— Coming soon —</span>
        </div>
        <div class="acct-row" style="font-size:.72rem;color:#aaa;border:none;padding-top:4px;">
            Bank account details will be added shortly.
        </div>
    </div>
 
    <div class="divider"></div>
 
    {{-- FORM --}}
    <form action="{{ route('booking.payment.post', $salon->id) }}"
          method="POST" enctype="multipart/form-data" id="payForm">
        @csrf
        <input type="hidden" name="payment_method" id="methodInput" value="easypaisa">
 
        <div class="field">
            <label>Transaction / Reference Number <span>*</span></label>
            <input type="text" name="transaction_ref" required
                placeholder="e.g. TXN0012345678"
                value="{{ old('transaction_ref') }}">
            @error('transaction_ref')<div class="field-error">{{ $message }}</div>@enderror
        </div>
 
        <div class="field">
            <label>Your Mobile Number (paid from) <span>*</span></label>
            <input type="tel" name="sender_number" required
                placeholder="03XX-XXXXXXX"
                value="{{ old('sender_number') }}">
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
                <i class="fas fa-cloud-upload-alt"></i>
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
        <div style="background:#fff5f5;border:1px solid #fecaca;border-radius:10px;padding:10px 14px;font-size:.78rem;color:#dc2626;">
            @foreach($errors->all() as $err)<div>• {{ $err }}</div>@endforeach
        </div>
        @endif
 
        <button type="submit" class="submit-btn" id="subBtn">
            <i class="fas fa-paper-plane"></i> Submit Payment
        </button>
    </form>
 
    <p class="note">After submission, your booking will be pending until admin verifies the screenshot.</p>
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
    document.getElementById('uploadBox').style.display = 'block';
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