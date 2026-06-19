{{-- FILE: resources/views/frontend/booking/step-4-payment.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Complete Payment — {{ $salon->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; background: #fafafa; min-height: 100vh; -webkit-font-smoothing: antialiased; }
 
    .top-nav {
        position: fixed; top: 0; left: 0; right: 0;
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 24px; z-index: 200;
        background: rgba(250,250,250,0.92);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid #f0f0f0;
    }
    .nav-btn {
        width: 40px; height: 40px; border-radius: 50%;
        border: 1.5px solid #e8e8e8; background: #fff;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; font-size: 0.88rem; color: #555;
        transition: all .15s; text-decoration: none;
    }
    .nav-btn:hover { border-color: #E91E8C; color: #E91E8C; }
    .steps-bar { display: flex; align-items: center; gap: 6px; }
    .step-dot { width: 28px; height: 4px; border-radius: 2px; background: #e8e8e8; }
    .step-dot.done { background: #E91E8C; }
    .step-dot.active { background: linear-gradient(90deg,#E91E8C,#9333ea); }
 
    .page-wrap {
        max-width: 1060px; margin: 0 auto;
        padding: 84px 24px 60px;
        display: grid; grid-template-columns: 1fr 360px; gap: 32px;
        align-items: start;
    }
    @media(max-width:860px) { .page-wrap { grid-template-columns: 1fr; } .sidebar { display: none; } }
 
    .pay-heading { font-size: 1.8rem; font-weight: 900; letter-spacing: -1px; color: #1a1a1a; margin-bottom: 4px; }
    .pay-sub { font-size: 0.85rem; color: #888; margin-bottom: 28px; }
 
    .progress-steps { display: flex; align-items: center; gap: 0; margin-bottom: 32px; }
    .ps-item { display: flex; align-items: center; gap: 8px; font-size: 0.78rem; font-weight: 600; }
    .ps-item .ps-num {
        width: 24px; height: 24px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.7rem; font-weight: 700; flex-shrink: 0;
    }
    .ps-item.done .ps-num { background: #E91E8C; color: #fff; }
    .ps-item.done .ps-label { color: #E91E8C; }
    .ps-item.active .ps-num { background: linear-gradient(135deg,#E91E8C,#9333ea); color: #fff; }
    .ps-item.active .ps-label { color: #1a1a1a; font-weight: 700; }
    .ps-item.pending .ps-num { background: #f0f0f0; color: #aaa; }
    .ps-item.pending .ps-label { color: #aaa; }
    .ps-connector { flex: 1; height: 2px; background: #f0f0f0; margin: 0 8px; min-width: 16px; }
    .ps-connector.done { background: #E91E8C; }
 
    .sec-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border: 1px solid #bbf7d0; border-radius: 50px;
        padding: 7px 16px; font-size: 0.75rem; font-weight: 700;
        color: #15803d; margin-bottom: 28px;
    }
 
    .payment-card {
        background: #fff; border: 1.5px solid #f0f0f0;
        border-radius: 24px; overflow: hidden; margin-bottom: 20px;
        box-shadow: 0 4px 24px rgba(233,30,140,0.06);
    }
    .pc-header {
        background: linear-gradient(135deg, #E91E8C 0%, #9333ea 100%);
        padding: 28px 28px 24px; position: relative; overflow: hidden;
    }
    .pc-header::before {
        content: ''; position: absolute; top: -60px; right: -60px;
        width: 180px; height: 180px; border-radius: 50%;
        background: rgba(255,255,255,0.08);
    }
    .pc-header::after {
        content: ''; position: absolute; bottom: -40px; left: 20px;
        width: 120px; height: 120px; border-radius: 50%;
        background: rgba(255,255,255,0.05);
    }
    .pc-brand { display: flex; align-items: center; gap: 10px; margin-bottom: 22px; position: relative; z-index: 1; }
    .pc-brand-icon {
        width: 44px; height: 44px; border-radius: 12px;
        background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; color: #fff;
    }
    .pc-brand-name { font-size: 1.2rem; font-weight: 800; color: #fff; }
    .pc-brand-sub { font-size: 0.72rem; color: rgba(255,255,255,0.75); margin-top: 1px; }
    .pc-amount-display { position: relative; z-index: 1; }
    .pc-amount-label { font-size: 0.72rem; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px; }
    .pc-amount-row-inner { display: flex; align-items: flex-end; gap: 8px; }
    .pc-amount-value { font-size: 2.8rem; font-weight: 900; color: #fff; letter-spacing: -2px; line-height: 1; }
    .pc-amount-currency { font-size: 1rem; font-weight: 700; color: rgba(255,255,255,0.75); margin-bottom: 5px; }
 
    .pc-methods {
        padding: 18px 28px; display: flex; gap: 10px; flex-wrap: wrap;
        border-bottom: 1px solid #f5f5f5;
    }
    .method-pill {
        display: flex; align-items: center; gap: 7px;
        background: #fdf2f8; border: 1.5px solid #fce4f0;
        border-radius: 50px; padding: 7px 14px;
        font-size: 0.75rem; font-weight: 700; color: #E91E8C;
        cursor: pointer; transition: all .15s;
    }
    .method-pill:hover, .method-pill.selected { background: #E91E8C; color: #fff; border-color: #E91E8C; }
 
    .pc-action { padding: 20px 28px; }
    .btn-pay {
        width: 100%; padding: 17px;
        background: linear-gradient(135deg, #E91E8C 0%, #9333ea 100%);
        color: #fff; border: none; border-radius: 16px;
        font-size: 1.05rem; font-weight: 800; cursor: pointer;
        transition: all .2s; display: flex; align-items: center;
        justify-content: center; gap: 10px;
        font-family: 'Inter', sans-serif;
        box-shadow: 0 8px 28px rgba(233,30,140,0.35);
    }
    .btn-pay:hover { transform: translateY(-2px); box-shadow: 0 14px 36px rgba(233,30,140,0.45); }
    .btn-pay .lock-icon {
        width: 22px; height: 22px; border-radius: 50%;
        background: rgba(255,255,255,0.25);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.72rem;
    }
 
    .sandbox-box {
        background: linear-gradient(135deg, #fff8ec, #fffbf0);
        border: 1px solid #fde68a; border-radius: 16px;
        padding: 18px 20px; margin-bottom: 16px;
        display: flex; gap: 14px; align-items: flex-start;
    }
    .sandbox-box .sb-icon {
        width: 36px; height: 36px; border-radius: 10px;
        background: #fef3c7; display: flex; align-items: center;
        justify-content: center; font-size: 1rem; color: #f59e0b; flex-shrink: 0;
    }
    .sandbox-box .sb-title { font-size: 0.82rem; font-weight: 700; color: #92400e; margin-bottom: 3px; }
    .sandbox-box .sb-text { font-size: 0.77rem; color: #a16207; line-height: 1.5; }
 
    .creds-box { background: #fff; border: 1.5px solid #f0f0f0; border-radius: 16px; overflow: hidden; }
    .creds-header {
        padding: 14px 18px;
        background: linear-gradient(135deg, #fdf2f8, #f5f0ff);
        border-bottom: 1px solid #f0e8f8;
        display: flex; align-items: center; gap: 8px;
    }
    .creds-header span { font-size: 0.72rem; font-weight: 700; color: #7c3aed; text-transform: uppercase; letter-spacing: 0.8px; }
    .cred-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 11px 18px; border-bottom: 1px solid #fafafa; font-size: 0.82rem;
    }
    .cred-row:last-child { border-bottom: none; }
    .cred-row .cr-label { color: #888; }
    .cred-row .cr-val { font-family: 'Courier New', monospace; font-weight: 700; color: #1a1a1a; font-size: 0.85rem; }
    .cred-copy {
        background: none; border: none; color: #E91E8C;
        cursor: pointer; font-size: 0.72rem; font-weight: 600;
        padding: 3px 8px; border-radius: 6px; transition: background .1s;
    }
    .cred-copy:hover { background: #fdf2f8; }
 
    /* ── SIDEBAR ── */
    .sidebar { position: sticky; top: 80px; }
    .order-card {
        background: #fff; border: 1.5px solid #f0f0f0;
        border-radius: 24px; overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04); margin-bottom: 16px;
    }
    .oc-salon {
        padding: 18px 20px; display: flex; align-items: center; gap: 14px;
        border-bottom: 1px solid #f8f8f8;
    }
    .oc-salon-img {
        width: 52px; height: 52px; border-radius: 14px;
        background: linear-gradient(135deg, #fce4ec, #f3e5f5);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; flex-shrink: 0; overflow: hidden;
    }
    .oc-salon-img img { width: 100%; height: 100%; object-fit: cover; }
    .oc-salon-name { font-size: 0.92rem; font-weight: 800; color: #1a1a1a; }
    .oc-salon-city { font-size: 0.72rem; color: #E91E8C; margin-top: 2px; display: flex; align-items: center; gap: 4px; }
    .oc-section-title {
        padding: 12px 20px 8px;
        font-size: 0.65rem; font-weight: 700; color: #bbb;
        text-transform: uppercase; letter-spacing: 1px;
    }
    .oc-row {
        padding: 10px 20px; display: flex;
        justify-content: space-between; align-items: flex-start;
        border-bottom: 1px solid #fafafa;
    }
    .oc-row:last-of-type { border-bottom: none; }
    .oc-row-label { font-size: 0.8rem; color: #888; margin-bottom: 2px; }
    .oc-row-value { font-size: 0.88rem; font-weight: 700; color: #1a1a1a; }
    .oc-row-price { font-size: 0.92rem; font-weight: 800; color: #1a1a1a; }
    .oc-total {
        padding: 16px 20px;
        background: linear-gradient(135deg, #fdf2f8, #f5f0ff);
        border-top: 1.5px solid #f0e8f8;
        display: flex; justify-content: space-between; align-items: center;
    }
    .oc-total-label { font-size: 0.82rem; font-weight: 700; color: #555; }
    .oc-total-amount { font-size: 1.2rem; font-weight: 900; color: #E91E8C; }
 
    .advance-card {
        background: #fff; border: 1.5px solid #f0f0f0;
        border-radius: 20px; padding: 16px 18px;
        margin-bottom: 16px; display: flex; gap: 12px;
    }
    .advance-card .ac-icon {
        width: 36px; height: 36px; border-radius: 10px;
        background: linear-gradient(135deg, #fff8ec, #fef3c7);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem; color: #f59e0b; flex-shrink: 0;
    }
    .advance-card .ac-title { font-size: 0.8rem; font-weight: 700; color: #1a1a1a; margin-bottom: 3px; }
    .advance-card .ac-text { font-size: 0.75rem; color: #888; line-height: 1.5; }
 
    .appt-card { background: #fff; border: 1.5px solid #f0f0f0; border-radius: 20px; overflow: hidden; }
    .appt-hdr {
        padding: 12px 18px;
        background: linear-gradient(135deg, #1a1a1a, #2d2d2d);
        font-size: 0.65rem; font-weight: 700; color: #aaa;
        text-transform: uppercase; letter-spacing: 1px;
        display: flex; align-items: center; gap: 6px;
    }
    .appt-row {
        padding: 10px 18px; display: flex; align-items: center; gap: 10px;
        border-bottom: 1px solid #fafafa; font-size: 0.82rem; color: #555;
    }
    .appt-row:last-child { border-bottom: none; }
    .appt-row i { color: #E91E8C; width: 14px; font-size: 0.78rem; flex-shrink: 0; }
    .appt-row.highlight { background: #f0fdf4; }
    .appt-row.highlight i { color: #16a34a; }
    .appt-row.highlight span { color: #15803d; font-weight: 700; }
    </style>
</head>
<body>
 
<div class="top-nav">
    <a href="{{ route('booking.step3', $salon->id) }}" class="nav-btn"><i class="fas fa-arrow-left"></i></a>
    <div class="steps-bar">
        <div class="step-dot done"></div>
        <div class="step-dot done"></div>
        <div class="step-dot done"></div>
        <div class="step-dot active"></div>
    </div>
    <a href="{{ route('salons.show', $salon->slug) }}" class="nav-btn"><i class="fas fa-times"></i></a>
</div>
 
<div class="page-wrap">
 
    {{-- LEFT --}}
    <div class="left">
        <h1 class="pay-heading">Complete payment</h1>
        <p class="pay-sub">Secure your slot at {{ $salon->name }} with a Rs.100 advance.</p>
 
        <div class="progress-steps">
            <div class="ps-item done">
                <div class="ps-num"><i class="fas fa-check" style="font-size:.6rem;"></i></div>
                <div class="ps-label">Service</div>
            </div>
            <div class="ps-connector done"></div>
            <div class="ps-item done">
                <div class="ps-num"><i class="fas fa-check" style="font-size:.6rem;"></i></div>
                <div class="ps-label">Stylist</div>
            </div>
            <div class="ps-connector done"></div>
            <div class="ps-item done">
                <div class="ps-num"><i class="fas fa-check" style="font-size:.6rem;"></i></div>
                <div class="ps-label">Time</div>
            </div>
            <div class="ps-connector done"></div>
            <div class="ps-item active">
                <div class="ps-num">4</div>
                <div class="ps-label">Payment</div>
            </div>
        </div>
 
        <div class="sec-badge">
            <i class="fas fa-shield-alt"></i>
            256-bit SSL encrypted &amp; secure
        </div>
 
        <div class="payment-card">
            <div class="pc-header">
                <div class="pc-brand">
                    <div class="pc-brand-icon"><i class="fas fa-bolt"></i></div>
                    <div>
                        <div class="pc-brand-name">PayFast</div>
                        <div class="pc-brand-sub">Pakistan's trusted payment gateway</div>
                    </div>
                </div>
                <div class="pc-amount-display">
                    <div class="pc-amount-label">Advance payment due</div>
                    <div class="pc-amount-row-inner">
                        <div class="pc-amount-value">100</div>
                        <div class="pc-amount-currency">PKR</div>
                    </div>
                </div>
            </div>
 
            <div class="pc-methods">
                <div class="method-pill selected"><i class="fas fa-mobile-alt"></i> JazzCash</div>
                <div class="method-pill"><i class="fas fa-mobile-alt"></i> EasyPaisa</div>
                <div class="method-pill"><i class="far fa-credit-card"></i> Card</div>
                <div class="method-pill"><i class="fas fa-university"></i> Bank</div>
            </div>
 
            <div class="pc-action">
                <form action="{{ route('booking.payment.post', $salon->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-pay">
                        <div class="lock-icon"><i class="fas fa-lock"></i></div>
                        Pay Rs.100 &amp; Confirm Booking
                        <i class="fas fa-arrow-right" style="font-size:0.85rem;opacity:0.7;"></i>
                    </button>
                </form>
            </div>
        </div>
 
        <div class="sandbox-box">
            <div class="sb-icon"><i class="fas fa-flask"></i></div>
            <div>
                <div class="sb-title">Sandbox / Test Mode Active</div>
                <div class="sb-text">No real money will be charged. Use the test credentials below to simulate a successful payment for your demo.</div>
            </div>
        </div>
 
        <div class="creds-box">
            <div class="creds-header">
                <i class="fas fa-key" style="color:#7c3aed;font-size:0.8rem;"></i>
                <span>Test Credentials</span>
            </div>
            <div class="cred-row">
                <span class="cr-label">Card Number</span>
                <span class="cr-val">4111 1111 1111 1111</span>
                <button class="cred-copy" onclick="copyText('4111111111111111',this)">Copy</button>
            </div>
            <div class="cred-row">
                <span class="cr-label">Expiry</span>
                <span class="cr-val">12 / 26</span>
            </div>
            <div class="cred-row">
                <span class="cr-label">CVV</span>
                <span class="cr-val">123</span>
            </div>
            <div class="cred-row">
                <span class="cr-label">JazzCash Number</span>
                <span class="cr-val">0300-1234567</span>
                <button class="cred-copy" onclick="copyText('03001234567',this)">Copy</button>
            </div>
            <div class="cred-row">
                <span class="cr-label">OTP / PIN</span>
                <span class="cr-val">123456</span>
                <button class="cred-copy" onclick="copyText('123456',this)">Copy</button>
            </div>
        </div>
    </div>
 
    {{-- SIDEBAR --}}
    <div class="sidebar">
        <div class="order-card">
            <div class="oc-salon">
                <div class="oc-salon-img">
                    @if($salon->cover_photo)
                    <img src="{{ asset('storage/'.$salon->cover_photo) }}" alt="{{ $salon->name }}" onerror="this.parentElement.innerHTML='💆'">
                    @else 💆 @endif
                </div>
                <div>
                    <div class="oc-salon-name">{{ $salon->name }}</div>
                    <div class="oc-salon-city"><i class="fas fa-map-marker-alt" style="font-size:.65rem;"></i> {{ $salon->city }}</div>
                </div>
            </div>
 
            <div class="oc-section-title">Booking Summary</div>
 
            <div class="oc-row">
                <div>
                    <div class="oc-row-label">Service</div>
                    <div class="oc-row-value">{{ $service->name }}</div>
                    <div style="font-size:.72rem;color:#aaa;margin-top:2px;">{{ $service->duration ?? 60 }} min</div>
                </div>
                <div class="oc-row-price">Rs.{{ number_format($service->price) }}</div>
            </div>
 
            <div class="oc-row">
                <div>
                    <div class="oc-row-label">Stylist</div>
                    <div class="oc-row-value">{{ $stylist->name }}</div>
                </div>
            </div>
 
            @if(isset($slot) && $slot)
            <div class="oc-row">
                <div>
                    <div class="oc-row-label">Date &amp; Time</div>
                    <div class="oc-row-value">{{ \Carbon\Carbon::parse($slot->slot_date)->format('d M Y') }}</div>
                    <div style="font-size:.72rem;color:#aaa;margin-top:2px;">{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}</div>
                </div>
            </div>
            @endif
 
            <div class="oc-total">
                <span class="oc-total-label">Total</span>
                <span class="oc-total-amount">Rs.{{ number_format($service->price) }}</span>
            </div>
        </div>
 
        <div class="advance-card">
            <div class="ac-icon"><i class="fas fa-info-circle"></i></div>
            <div>
                <div class="ac-title">Rs.100 advance secures your slot</div>
                <div class="ac-text">Remaining Rs.{{ number_format($service->price - 100) }} is paid at the salon after your appointment.</div>
            </div>
        </div>
 
        <div class="appt-card">
            <div class="appt-hdr">
                <i class="fas fa-calendar-check" style="color:#E91E8C;"></i>
                Your Appointment
            </div>
            @if(isset($slot) && $slot)
            <div class="appt-row">
                <i class="fas fa-calendar"></i>
                <span>{{ \Carbon\Carbon::parse($slot->slot_date)->format('l, d F Y') }}</span>
            </div>
            <div class="appt-row">
                <i class="fas fa-clock"></i>
                <span>{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}</span>
            </div>
            @endif
            <div class="appt-row">
                <i class="fas fa-user-tie"></i>
                <span>{{ $stylist->name }}</span>
            </div>
            <div class="appt-row">
                <i class="fas fa-spa"></i>
                <span>{{ $service->name }}</span>
            </div>
            <div class="appt-row highlight">
                <i class="fas fa-check-circle"></i>
                <span>Pay Rs.100 now to confirm</span>
            </div>
        </div>
    </div>
 
</div>
 
<script>
function copyText(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        const orig = btn.textContent;
        btn.textContent = '✓ Copied';
        btn.style.color = '#16a34a';
        setTimeout(() => { btn.textContent = orig; btn.style.color = ''; }, 1800);
    });
}
document.querySelectorAll('.method-pill').forEach(pill => {
    pill.addEventListener('click', function() {
        document.querySelectorAll('.method-pill').forEach(p => p.classList.remove('selected'));
        this.classList.add('selected');
    });
});
</script>
</body>
</html>