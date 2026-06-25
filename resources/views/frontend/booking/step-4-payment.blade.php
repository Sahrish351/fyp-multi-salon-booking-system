{{-- FILE: resources/views/frontend/booking/step-4-payment.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment — {{ $salon->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: 'Inter', sans-serif;
        background: #f0ebf8;
        min-height: 100vh;
        -webkit-font-smoothing: antialiased;
        color: #1a1a1a;
    }

    /* TOP NAV */
    .top-nav {
        position: sticky; top: 0; z-index: 200;
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 24px; background: rgba(255,255,255,0.95);
        backdrop-filter: blur(12px); border-bottom: 1px solid #ede5f5;
    }
    .nav-btn {
        width: 40px; height: 40px; border-radius: 50%;
        border: 1.5px solid #e0d5ec; background: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: .9rem; color: #555; text-decoration: none; transition: all .15s;
    }
    .nav-btn:hover { border-color: #E91E8C; color: #E91E8C; }
    .brand { font-size: 1.15rem; font-weight: 900; font-style: italic;
        background: linear-gradient(135deg, #E91E8C, #9333ea);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }

    /* PROGRESS BAR */
    .progress-wrap { max-width: 680px; margin: 26px auto 0; padding: 0 20px; }
    .progress-steps { display: flex; align-items: center; justify-content: center; }
    .p-step { display: flex; align-items: center; gap: 7px; }
    .p-circle { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: .72rem; }
    .p-circle.done { background: linear-gradient(135deg,#E91E8C,#9333ea); color:#fff; }
    .p-circle.active { background:#fff; color:#E91E8C; border: 2px solid #E91E8C; }
    .p-label { font-size: .72rem; font-weight: 700; color: #bbb; }
    .p-label.done { color: #9333ea; }
    .p-label.active { color: #E91E8C; }
    .p-line { width: 28px; height: 2px; background: #e0d5ec; margin: 0 5px; border-radius: 2px; }
    .p-line.done { background: linear-gradient(90deg,#E91E8C,#9333ea); }
    @media(max-width:500px){ .p-label{display:none;} .p-line{width:16px;} }

    /* MAIN LAYOUT */
    .pay-wrap {
        max-width: 1000px; margin: 28px auto 60px; padding: 0 20px;
        display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start;
    }
    @media(max-width:860px){ .pay-wrap{ grid-template-columns:1fr; } .sidebar{ order:-1; } }

    /* ===== LEFT PANEL ===== */
    .card { background: #fff; border-radius: 20px; padding: 26px; margin-bottom: 18px; box-shadow: 0 2px 20px rgba(147,51,234,0.06); }

    .section-heading {
        font-size: .72rem; font-weight: 800; color: #9333ea;
        text-transform: uppercase; letter-spacing: .8px; margin-bottom: 16px;
        display: flex; align-items: center; gap: 8px;
    }
    .section-heading::after { content:''; flex:1; height:1px; background:#f0e8f8; }

    /* AMOUNT HERO */
    .amount-hero {
        background: linear-gradient(135deg, #E91E8C 0%, #9333ea 100%);
        border-radius: 20px; padding: 28px 26px; color: #fff; margin-bottom: 18px;
        position: relative; overflow: hidden;
    }
    .amount-hero::before { content:''; position:absolute; right:-40px; top:-40px; width:180px; height:180px; border-radius:50%; background:rgba(255,255,255,0.08); }
    .amount-hero::after  { content:''; position:absolute; left:-20px; bottom:-30px; width:120px; height:120px; border-radius:50%; background:rgba(255,255,255,0.05); }
    .ah-label { font-size:.7rem; color:rgba(255,255,255,.75); text-transform:uppercase; letter-spacing:.6px; font-weight:700; margin-bottom:6px; position:relative; z-index:1; }
    .ah-amount { font-size:2.4rem; font-weight:900; line-height:1; margin-bottom:4px; position:relative; z-index:1; }
    .ah-sub { font-size:.78rem; color:rgba(255,255,255,.75); position:relative; z-index:1; }
    .ah-right { position:absolute; right:26px; top:50%; transform:translateY(-50%); z-index:1; text-align:center; }
    .ah-right i { font-size:2.5rem; opacity:.3; }

    /* METHOD SELECTOR */
    .methods-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:4px; }
    .method-btn {
        border: 2px solid #ede5f5; border-radius: 16px; padding: 16px 14px;
        cursor: pointer; transition: all .18s; background: #fff; position: relative;
        display: flex; flex-direction: column; gap: 6px;
    }
    .method-btn:hover { border-color: #d8b4fe; box-shadow: 0 4px 16px rgba(147,51,234,0.1); }
    .method-btn.active { border-color: #E91E8C; background: linear-gradient(135deg, #fff5fb, #faf5ff); box-shadow: 0 4px 20px rgba(233,30,140,0.12); }
    .method-btn .mb-radio { position:absolute; top:12px; right:12px; width:20px; height:20px; border-radius:50%; border:2px solid #d8b4fe; background:#fff; transition:all .18s; display:flex; align-items:center; justify-content:center; }
    .method-btn.active .mb-radio { background:#E91E8C; border-color:#E91E8C; }
    .method-btn.active .mb-radio::after { content:''; width:7px; height:7px; border-radius:50%; background:#fff; }
    .method-btn .mb-icon { width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.15rem; margin-bottom:4px; }
    .method-btn.ep .mb-icon { background:#e8f5e9; color:#2e7d32; }
    .method-btn.jc .mb-icon { background:#fff3e0; color:#e65100; }
    .method-btn .mb-name { font-size:.88rem; font-weight:800; color:#1a1a1a; }
    .method-btn .mb-type { font-size:.68rem; color:#999; }

    /* PAYMENT DETAIL CARD (shown after method selected) */
    .payment-detail {
        border: 1.5px solid #f0e8f8; border-radius: 16px; padding: 18px; margin-top: 14px;
        background: linear-gradient(135deg,#fdf2f8,#faf5ff); transition: all .2s;
    }
    .pd-row { display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; }
    .pd-row:last-child { margin-bottom:0; }
    .pd-label { font-size:.76rem; color:#888; font-weight:600; }
    .pd-value { font-size:.88rem; font-weight:800; color:#1a1a1a; font-family:monospace; letter-spacing:.5px; display:flex; align-items:center; gap:8px; }
    .copy-btn {
        background:#E91E8C; color:#fff; border:none; border-radius:50px; padding:4px 12px;
        font-size:.68rem; font-weight:700; cursor:pointer; transition:all .15s; font-family:'Inter',sans-serif;
    }
    .copy-btn:hover { background:#c2185b; }
    .copy-btn.copied { background:#16a34a; }

    /* FORM FIELDS */
    .field-label { font-size:.76rem; font-weight:700; color:#555; margin-bottom:6px; display:block; }
    .field-input {
        width:100%; border:1.5px solid #e5daf0; border-radius:12px; padding:12px 14px;
        font-size:.88rem; font-family:'Inter',sans-serif; color:#1a1a1a; margin-bottom:14px;
        transition:border-color .15s; background:#fdfbfe;
    }
    .field-input:focus { outline:none; border-color:#E91E8C; background:#fff; }

    /* SCREENSHOT UPLOAD */
    .upload-wrap {
        border: 2px dashed #d8b4fe; border-radius: 16px; padding: 26px 20px;
        text-align: center; cursor: pointer; transition: all .2s; position: relative;
        background: #fdfbfe; margin-bottom: 16px;
    }
    .upload-wrap:hover, .upload-wrap.over { border-color:#E91E8C; background:#fff5fb; }
    .upload-wrap input { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
    .upload-wrap .uw-icon { font-size:2rem; color:#d8b4fe; margin-bottom:10px; }
    .upload-wrap .uw-title { font-size:.85rem; font-weight:700; color:#555; margin-bottom:3px; }
    .upload-wrap .uw-sub { font-size:.7rem; color:#bbb; }

    .preview-wrap { display:none; border-radius:14px; overflow:hidden; border:1.5px solid #f0e8f8; margin-bottom:14px; position:relative; }
    .preview-wrap img { width:100%; max-height:220px; object-fit:contain; display:block; background:#fdfbfe; }
    .preview-remove {
        position:absolute; top:8px; right:8px; background:rgba(0,0,0,0.5); color:#fff;
        border:none; border-radius:50%; width:28px; height:28px; cursor:pointer;
        display:flex; align-items:center; justify-content:center; font-size:.75rem;
    }

    .submit-btn {
        width:100%; padding:15px; border:none; border-radius:14px;
        background:linear-gradient(135deg,#E91E8C,#9333ea); color:#fff;
        font-size:.98rem; font-weight:800; cursor:pointer; transition:all .2s;
        display:flex; align-items:center; justify-content:center; gap:9px;
        font-family:'Inter',sans-serif; box-shadow:0 8px 24px rgba(147,51,234,0.25);
    }
    .submit-btn:hover { transform:translateY(-2px); box-shadow:0 12px 32px rgba(147,51,234,0.35); }
    .submit-btn:disabled { opacity:.65; cursor:not-allowed; transform:none; }

    /* VALIDATION ERROR */
    .field-error { color:#dc2626; font-size:.72rem; margin:-10px 0 10px; }

    /* ===== SIDEBAR ===== */
    .sidebar { position:sticky; top:90px; }

    .salon-row { display:flex; align-items:center; gap:12px; padding:16px; background:#fff; border-radius:18px; margin-bottom:14px; box-shadow:0 2px 16px rgba(147,51,234,0.06); }
    .salon-avatar { width:50px; height:50px; border-radius:12px; background:#fce4ec; display:flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0; overflow:hidden; }
    .salon-avatar img { width:100%; height:100%; object-fit:cover; }
    .salon-nm { font-size:.9rem; font-weight:800; color:#1a1a1a; }
    .salon-ct { font-size:.7rem; color:#999; margin-top:2px; }

    .summary-box { background:#fff; border-radius:18px; overflow:hidden; margin-bottom:14px; box-shadow:0 2px 16px rgba(147,51,234,0.06); }
    .sb-hdr { padding:11px 16px; background:linear-gradient(135deg,#fdf2f8,#faf5ff); border-bottom:1px solid #f0e8f8; font-size:.65rem; font-weight:800; color:#9333ea; text-transform:uppercase; letter-spacing:.8px; }
    .sb-row { padding:11px 16px; display:flex; justify-content:space-between; align-items:flex-start; border-bottom:1px solid #faf5f8; font-size:.83rem; gap:10px; }
    .sb-row:last-child { border-bottom:none; }
    .sb-key { color:#888; }
    .sb-val { font-weight:700; color:#1a1a1a; text-align:right; max-width:60%; }
    .sb-total { padding:13px 16px; background:linear-gradient(135deg,#fdf2f8,#faf5ff); border-top:1px solid #f0e8f8; display:flex; justify-content:space-between; align-items:center; }
    .sb-total-lbl { font-size:.8rem; font-weight:700; color:#666; }
    .sb-total-amt { font-size:1.2rem; font-weight:900; color:#E91E8C; }

    .info-box { background:#fff; border-radius:18px; padding:16px; box-shadow:0 2px 16px rgba(147,51,234,0.06); }
    .ib-title { font-size:.74rem; font-weight:800; color:#1a1a1a; margin-bottom:12px; text-transform:uppercase; letter-spacing:.5px; }
    .ib-item { display:flex; gap:10px; margin-bottom:10px; font-size:.78rem; color:#666; align-items:flex-start; }
    .ib-item:last-child { margin-bottom:0; }
    .ib-dot { width:20px; height:20px; border-radius:50%; background:linear-gradient(135deg,#E91E8C,#9333ea); color:#fff; display:flex; align-items:center; justify-content:center; font-size:.62rem; font-weight:800; flex-shrink:0; margin-top:1px; }
    </style>
</head>
<body>

<div class="top-nav">
    <a href="{{ route('booking.step3', $salon->id) }}" class="nav-btn"><i class="fas fa-arrow-left"></i></a>
    <span class="brand">glamora</span>
    <a href="{{ route('salons.show', $salon->slug) }}" class="nav-btn"><i class="fas fa-times"></i></a>
</div>

<div class="progress-wrap">
    <div class="progress-steps">
        <div class="p-step"><div class="p-circle done"><i class="fas fa-check"></i></div><span class="p-label done">Service</span></div>
        <div class="p-line done"></div>
        <div class="p-step"><div class="p-circle done"><i class="fas fa-check"></i></div><span class="p-label done">Stylist</span></div>
        <div class="p-line done"></div>
        <div class="p-step"><div class="p-circle done"><i class="fas fa-check"></i></div><span class="p-label done">Time</span></div>
        <div class="p-line done"></div>
        <div class="p-step"><div class="p-circle active">4</div><span class="p-label active">Payment</span></div>
    </div>
</div>

<div class="pay-wrap">
    {{-- LEFT --}}
    <div>
        {{-- AMOUNT HERO --}}
        <div class="amount-hero">
            <div class="ah-label">Advance Payment Required</div>
            <div class="ah-amount">Rs. 100</div>
            <div class="ah-sub">Send to confirm your appointment at {{ $salon->name }}</div>
            <div class="ah-right"><i class="fas fa-shield-alt"></i><div style="font-size:.65rem;margin-top:4px;opacity:.6;">Secure</div></div>
        </div>

        {{-- METHOD SELECTION --}}
        <div class="card">
            <div class="section-heading"><i class="fas fa-mobile-alt"></i> Choose payment method</div>

            <div class="methods-grid">
                <div class="method-btn ep active" id="btn-ep" onclick="selectMethod('easypaisa')">
                    <div class="mb-radio"></div>
                    <div class="mb-icon"><i class="fas fa-mobile-alt"></i></div>
                    <div class="mb-name">EasyPaisa</div>
                    <div class="mb-type">Mobile Wallet</div>
                </div>
                <div class="method-btn jc" id="btn-jc" onclick="selectMethod('jazzcash')">
                    <div class="mb-radio"></div>
                    <div class="mb-icon"><i class="fas fa-mobile-alt"></i></div>
                    <div class="mb-name">JazzCash</div>
                    <div class="mb-type">Mobile Wallet</div>
                </div>
                {{-- Bank account add karein jab ready ho:
                <div class="method-btn" id="btn-bank" onclick="selectMethod('bank')">
                    <div class="mb-radio"></div>
                    <div class="mb-icon" style="background:#e8eaf6;color:#3949ab;"><i class="fas fa-university"></i></div>
                    <div class="mb-name">Bank Transfer</div>
                    <div class="mb-type">Account details below</div>
                </div>
                --}}
            </div>

            <div class="payment-detail" id="payDetailEasyPaisa">
                <div class="pd-row">
                    <span class="pd-label">Account type</span>
                    <span class="pd-value">EasyPaisa Mobile Account</span>
                </div>
                <div class="pd-row">
                    <span class="pd-label">Account number</span>
                    <span class="pd-value">
                        0306-9734142
                        <button type="button" class="copy-btn" id="copyBtnEp" onclick="copyNum('03069734142', 'copyBtnEp')">Copy</button>
                    </span>
                </div>
                <div class="pd-row">
                    <span class="pd-label">Account title</span>
                    <span class="pd-value" style="font-family:'Inter',sans-serif;letter-spacing:0;">Glamora Salon</span>
                </div>
            </div>

            <div class="payment-detail" id="payDetailJazzCash" style="display:none;">
                <div class="pd-row">
                    <span class="pd-label">Account type</span>
                    <span class="pd-value">JazzCash Mobile Account</span>
                </div>
                <div class="pd-row">
                    <span class="pd-label">Account number</span>
                    <span class="pd-value">
                        0306-9734142
                        <button type="button" class="copy-btn" id="copyBtnJc" onclick="copyNum('03069734142', 'copyBtnJc')">Copy</button>
                    </span>
                </div>
                <div class="pd-row">
                    <span class="pd-label">Account title</span>
                    <span class="pd-value" style="font-family:'Inter',sans-serif;letter-spacing:0;">Glamora Salon</span>
                </div>
            </div>
        </div>

        {{-- UPLOAD PROOF --}}
        <div class="card">
            <div class="section-heading"><i class="fas fa-upload"></i> Upload payment proof</div>

            <form action="{{ route('booking.payment.post', $salon->id) }}"
                  method="POST" enctype="multipart/form-data" id="payForm">
                @csrf
                <input type="hidden" name="payment_method" id="methodInput" value="easypaisa">

                <label class="field-label">Transaction / Reference Number <span style="color:#E91E8C;">*</span></label>
                <input type="text" name="transaction_ref" class="field-input" required
                    placeholder="e.g. TXN0012345678 (from your app receipt)"
                    value="{{ old('transaction_ref') }}">
                @error('transaction_ref')<div class="field-error">{{ $message }}</div>@enderror

                <label class="field-label">Your mobile number (used to pay) <span style="color:#E91E8C;">*</span></label>
                <input type="tel" name="sender_number" class="field-input" required
                    placeholder="03XX-XXXXXXX"
                    value="{{ old('sender_number') }}">
                @error('sender_number')<div class="field-error">{{ $message }}</div>@enderror

                <label class="field-label">Payment screenshot <span style="color:#E91E8C;">*</span></label>

                <div class="upload-wrap" id="uploadWrap"
                     ondragover="event.preventDefault();this.classList.add('over')"
                     ondragleave="this.classList.remove('over')"
                     ondrop="handleDrop(event)">
                    <input type="file" name="screenshot" id="ssInput"
                           accept="image/*" onchange="previewSS(this)">
                    <div class="uw-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <div class="uw-title">Tap to upload screenshot</div>
                    <div class="uw-sub">JPG, PNG, WEBP · max 5 MB</div>
                </div>

                <div class="preview-wrap" id="previewWrap">
                    <img id="previewImg" src="" alt="Screenshot preview">
                    <button type="button" class="preview-remove" onclick="removeSS()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                @error('screenshot')<div class="field-error" style="margin-bottom:12px;">{{ $message }}</div>@enderror
                @if($errors->any() && !$errors->has('transaction_ref') && !$errors->has('sender_number') && !$errors->has('screenshot'))
                <div style="background:#fff5f5;border:1px solid #fecaca;border-radius:10px;padding:10px 14px;margin-bottom:14px;font-size:.78rem;color:#dc2626;">
                    @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
                </div>
                @endif

                <button type="submit" class="submit-btn" id="submitBtn">
                    <i class="fas fa-paper-plane"></i> Submit &amp; Confirm Booking
                </button>
            </form>
        </div>
    </div>

    {{-- SIDEBAR --}}
    <div class="sidebar">
        <div class="salon-row">
            <div class="salon-avatar">
                @if($salon->cover_image ?? null)
                <img src="{{ asset('storage/'.$salon->cover_image) }}" alt="" onerror="this.parentElement.textContent='💆'">
                @else 💆 @endif
            </div>
            <div>
                <div class="salon-nm">{{ $salon->name }}</div>
                <div class="salon-ct"><i class="fas fa-map-marker-alt" style="color:#E91E8C;font-size:.6rem;"></i> {{ $salon->city }}</div>
            </div>
        </div>

        <div class="summary-box">
            <div class="sb-hdr">Booking Summary</div>
            <div class="sb-row">
                <span class="sb-key">Service</span>
                <span class="sb-val">{{ $service->name }}</span>
            </div>
            <div class="sb-row">
                <span class="sb-key">Duration</span>
                <span class="sb-val">{{ $service->duration ?? 60 }} min</span>
            </div>
            <div class="sb-row">
                <span class="sb-key">Stylist</span>
                <span class="sb-val">{{ $stylist->name }}</span>
            </div>
            @if(isset($slot) && $slot)
            <div class="sb-row">
                <span class="sb-key">Date</span>
                <span class="sb-val">{{ \Carbon\Carbon::parse($slot->slot_date)->format('d M Y') }}</span>
            </div>
            <div class="sb-row">
                <span class="sb-key">Time</span>
                <span class="sb-val">{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}</span>
            </div>
            @endif
            <div class="sb-row">
                <span class="sb-key">Service price</span>
                <span class="sb-val">Rs. {{ number_format($service->price) }}</span>
            </div>
            <div class="sb-total">
                <span class="sb-total-lbl">Pay now (advance)</span>
                <span class="sb-total-amt">Rs. 100</span>
            </div>
        </div>

        <div class="info-box">
            <div class="ib-title">What happens next</div>
            <div class="ib-item"><div class="ib-dot">1</div>Admin verifies your screenshot</div>
            <div class="ib-item"><div class="ib-dot">2</div>Booking gets approved &amp; confirmed</div>
            <div class="ib-item"><div class="ib-dot">3</div>You receive a confirmation email</div>
            <div class="ib-item"><div class="ib-dot">4</div>Pay Rs. {{ number_format(($service->price ?? 100) - 100) }} at the salon</div>
        </div>
    </div>
</div>

<script>
// Method selection
function selectMethod(method) {
    document.querySelectorAll('.method-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('btn-' + (method === 'easypaisa' ? 'ep' : method === 'jazzcash' ? 'jc' : 'bank')).classList.add('active');
    document.getElementById('methodInput').value = method;
    document.getElementById('payDetailEasyPaisa').style.display = method === 'easypaisa' ? 'block' : 'none';
    document.getElementById('payDetailJazzCash').style.display  = method === 'jazzcash'  ? 'block' : 'none';
}

// Copy number
function copyNum(num, btnId) {
    navigator.clipboard.writeText(num).then(() => {
        const btn = document.getElementById(btnId);
        btn.textContent = 'Copied!';
        btn.classList.add('copied');
        setTimeout(() => { btn.textContent = 'Copy'; btn.classList.remove('copied'); }, 2000);
    });
}

// Screenshot preview
function previewSS(input) {
    if (!input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('uploadWrap').style.display = 'none';
        document.getElementById('previewWrap').style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
}

function removeSS() {
    document.getElementById('ssInput').value = '';
    document.getElementById('uploadWrap').style.display = 'block';
    document.getElementById('previewWrap').style.display = 'none';
}

function handleDrop(e) {
    e.preventDefault();
    document.getElementById('uploadWrap').classList.remove('over');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer();
        dt.items.add(file);
        const input = document.getElementById('ssInput');
        input.files = dt.files;
        previewSS(input);
    }
}

document.getElementById('payForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
});
</script>
</body>
</html>
