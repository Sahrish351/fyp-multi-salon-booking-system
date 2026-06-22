
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
        background: linear-gradient(135deg, #fdf2f8 0%, #faf5ff 50%, #fdf2f8 100%);
        min-height: 100vh;
        -webkit-font-smoothing: antialiased;
        color: #1a1a1a;
    }

    .top-nav {
        position: sticky; top: 0; left: 0; right: 0;
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 24px; z-index: 200; background: rgba(255,255,255,0.92);
        backdrop-filter: blur(10px); border-bottom: 1px solid #f3e5ec;
    }
    .nav-btn {
        width: 42px; height: 42px; border-radius: 50%;
        border: 1.5px solid #e8d8e4; background: #fff;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; font-size: .95rem; color: #1a1a1a;
        transition: all .15s; text-decoration: none;
    }
    .nav-btn:hover { border-color: #E91E8C; color: #E91E8C; }
    .brand { font-size: 1.2rem; font-weight: 900; letter-spacing: -.5px; font-style: italic;
        background: linear-gradient(135deg, #E91E8C, #9333ea);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }

    .progress-wrap { max-width: 720px; margin: 28px auto 0; padding: 0 24px; }
    .progress-steps { display: flex; align-items: center; justify-content: center; gap: 0; flex-wrap: wrap; }
    .p-step { display: flex; align-items: center; gap: 8px; }
    .p-circle {
        width: 30px; height: 30px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: .76rem; flex-shrink: 0;
    }
    .p-circle.done { background: linear-gradient(135deg,#E91E8C,#9333ea); color: #fff; }
    .p-circle.active { background: #fff; color: #E91E8C; border: 2px solid #E91E8C; }
    .p-label { font-size: .76rem; font-weight: 700; color: #c4c4c4; white-space: nowrap; }
    .p-label.done { color: #9333ea; }
    .p-label.active { color: #E91E8C; }
    .p-line { width: 30px; height: 2px; background: #f0d9e8; margin: 0 6px; border-radius: 2px; }
    .p-line.done { background: linear-gradient(90deg,#E91E8C,#9333ea); }
    @media(max-width:560px){ .p-label{ display:none; } .p-line{ width:18px; } }

    .pay-layout {
        display: grid; grid-template-columns: 1fr 380px; gap: 40px;
        max-width: 1080px; margin: 0 auto; padding: 36px 24px 70px; align-items: start;
    }
    @media(max-width:900px) { .pay-layout { grid-template-columns: 1fr; } .pay-sidebar { order: -1; } }

    .pay-left h1 { font-family: 'Playfair Display', serif; font-size: 1.9rem; font-weight: 800; color: #1a1a1a; letter-spacing: -.5px; margin-bottom: 6px; }
    .pay-sub { font-size: .86rem; color: #8a8a95; margin-bottom: 22px; }

    .secure-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 50px;
        padding: 6px 14px; font-size: .72rem; font-weight: 700; color: #16a34a; margin-bottom: 22px;
    }

    .payfast-card {
        background: linear-gradient(135deg, #E91E8C 0%, #9333ea 100%);
        border-radius: 22px; padding: 30px 26px; color: #fff;
        position: relative; overflow: hidden; margin-bottom: 22px;
        box-shadow: 0 16px 40px rgba(147,51,234,0.22);
    }
    .payfast-card::before { content: ''; position: absolute; right: -50px; top: -60px; width: 200px; height: 200px; border-radius: 50%; background: rgba(255,255,255,0.08); }
    .payfast-card::after { content: ''; position: absolute; left: -30px; bottom: -50px; width: 140px; height: 140px; border-radius: 50%; background: rgba(255,255,255,0.06); }
    .pf-logo { font-size: 1.3rem; font-weight: 800; margin-bottom: 4px; display: flex; align-items: center; gap: 9px; position: relative; z-index: 1; }
    .pf-logo .pf-badge { background: rgba(255,255,255,0.2); border-radius: 8px; padding: 2px 9px; font-size: .62rem; font-weight: 800; letter-spacing: .5px; }
    .pf-sub { font-size: .79rem; color: rgba(255,255,255,0.85); margin-bottom: 20px; position: relative; z-index: 1; }
    .pf-methods { display: flex; gap: 8px; margin-bottom: 22px; flex-wrap: wrap; position: relative; z-index: 1; }
    .pf-method-chip {
        background: rgba(255,255,255,0.14); border: 1px solid rgba(255,255,255,0.25);
        border-radius: 50px; padding: 7px 14px; font-size: .72rem; font-weight: 600;
        display: flex; align-items: center; gap: 6px; cursor: pointer; transition: all .15s;
    }
    .pf-method-chip:hover, .pf-method-chip.selected { background: rgba(255,255,255,0.28); }
    .pf-amount-row {
        display: flex; align-items: center; justify-content: space-between;
        background: rgba(255,255,255,0.14); border-radius: 14px; padding: 16px 18px;
        margin-bottom: 20px; position: relative; z-index: 1;
    }
    .pf-amount-label { font-size: .7rem; color: rgba(255,255,255,0.75); text-transform: uppercase; letter-spacing: .6px; font-weight: 700; }
    .pf-amount-value { font-size: 1.55rem; font-weight: 900; color: #fff; }

    .pay-btn {
        width: 100%; padding: 15px;
        background: #fff; color: #9333ea;
        border: none; border-radius: 14px;
        font-size: .98rem; font-weight: 800; cursor: pointer; transition: all .2s;
        display: flex; align-items: center; justify-content: center; gap: 9px;
        font-family: 'Inter', sans-serif; position: relative; z-index: 1;
    }
    .pay-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(0,0,0,0.18); }

    .sandbox-note {
        background: #fff8ec; border: 1px solid #fde68a; border-radius: 14px;
        padding: 13px 16px; font-size: .78rem; color: #92400e; margin-bottom: 20px;
        display: flex; align-items: flex-start; gap: 10px; line-height: 1.5;
    }
    .sandbox-note i { color: #f59e0b; margin-top: 1px; flex-shrink: 0; }

    .what-next { background: #fff; border: 1.5px solid #f3e5ec; border-radius: 18px; padding: 20px; }
    .what-next-title { font-size: .82rem; font-weight: 800; color: #1a1a1a; margin-bottom: 14px; }
    .wn-item { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 12px; font-size: .82rem; color: #666; }
    .wn-item:last-child { margin-bottom: 0; }
    .wn-icon {
        width: 24px; height: 24px; border-radius: 50%; flex-shrink: 0;
        background: #fdf0f7; color: #E91E8C; display: flex; align-items: center; justify-content: center;
        font-size: .68rem; margin-top: 1px;
    }

    .pay-sidebar { position: sticky; top: 90px; }
    .salon-card { background: #fff; border: 1.5px solid #f3e5ec; border-radius: 18px; padding: 16px; margin-bottom: 14px; display: flex; align-items: center; gap: 14px; }
    .salon-img { width: 56px; height: 56px; border-radius: 12px; flex-shrink: 0; overflow: hidden; background: #fce4ec; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
    .salon-img img { width: 100%; height: 100%; object-fit: cover; }
    .salon-name { font-size: .92rem; font-weight: 800; color: #1a1a1a; }
    .salon-loc { font-size: .72rem; color: #999; margin-top: 2px; }

    .order-box { background: #fff; border: 1.5px solid #f3e5ec; border-radius: 18px; overflow: hidden; margin-bottom: 14px; }
    .order-hdr { padding: 12px 16px; background: #fdf2f8; border-bottom: 1px solid #f3e5ec; font-size: .68rem; font-weight: 800; color: #9333ea; letter-spacing: .8px; text-transform: uppercase; }
    .order-row { padding: 13px 16px; display: flex; align-items: flex-start; justify-content: space-between; border-bottom: 1px solid #faf5f8; gap: 10px; }
    .order-row:last-child { border-bottom: none; }
    .or-name { font-size: .85rem; font-weight: 700; color: #1a1a1a; }
    .or-detail { font-size: .72rem; color: #999; margin-top: 2px; }
    .or-price { font-size: .88rem; font-weight: 800; color: #1a1a1a; white-space: nowrap; }
    .order-total { padding: 14px 16px; background: #fdf2f8; border-top: 1px solid #f3e5ec; display: flex; justify-content: space-between; align-items: center; }
    .ot-lbl { font-size: .82rem; font-weight: 700; color: #666; }
    .ot-amt { font-size: 1.2rem; font-weight: 900; color: #E91E8C; }

    .advance-box { background: #fff8ec; border: 1px solid #fde68a; border-radius: 14px; padding: 13px 15px; font-size: .76rem; color: #92400e; line-height: 1.55; display: flex; gap: 8px; align-items: flex-start; }
    .advance-box i { color: #f59e0b; flex-shrink: 0; margin-top: 2px; }
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

<div class="pay-layout">

    <div class="pay-left">
        <h1>Complete your payment</h1>
        <p class="pay-sub">Pay a small advance via PayFast to lock in your appointment</p>

        <div class="secure-badge">
            <i class="fas fa-shield-alt"></i> 256-bit SSL encrypted &amp; secure
        </div>

        <div class="payfast-card">
            <div class="pf-logo">
                <i class="fas fa-bolt"></i> PayFast <span class="pf-badge">PK</span>
            </div>
            <p class="pf-sub">Pakistan's trusted payment gateway — pay with JazzCash, EasyPaisa, or your debit/credit card.</p>

            <div class="pf-methods">
                <div class="pf-method-chip selected" data-method="jazzcash" onclick="selectMethod(this)"><i class="fas fa-mobile-alt"></i> JazzCash</div>
                <div class="pf-method-chip" data-method="easypaisa" onclick="selectMethod(this)"><i class="fas fa-mobile-alt"></i> EasyPaisa</div>
                <div class="pf-method-chip" data-method="card" onclick="selectMethod(this)"><i class="far fa-credit-card"></i> Card</div>
            </div>

            <div class="pf-amount-row">
                <span class="pf-amount-label">Advance payment</span>
                <span class="pf-amount-value">Rs. 100</span>
            </div>

            <form action="{{ route('booking.payment.post', $salon->id) }}" method="POST" id="payfastForm">
                @csrf
                <input type="hidden" name="payment_method" id="selectedMethod" value="jazzcash">
                <button type="submit" class="pay-btn">
                    <i class="fas fa-lock"></i> Continue to PayFast
                </button>
            </form>
        </div>

        <div class="sandbox-note">
            <i class="fas fa-flask"></i>
            <div>
                <strong>Sandbox mode is active.</strong> This is a demo checkout for testing — no real money will be charged. Your PayFast merchant account is pending verification; this will switch to live payments automatically once approved.
            </div>
        </div>

        <div class="what-next">
            <div class="what-next-title">What happens after you pay</div>
            <div class="wn-item">
                <div class="wn-icon"><i class="fas fa-check"></i></div>
                Your appointment is reserved and marked pending salon approval
            </div>
            <div class="wn-item">
                <div class="wn-icon"><i class="fas fa-envelope"></i></div>
                You'll get a confirmation email with your booking details
            </div>
            <div class="wn-item">
                <div class="wn-icon"><i class="fas fa-money-bill-wave"></i></div>
                Pay the remaining amount in cash at the salon
            </div>
        </div>
    </div>

    <div class="pay-sidebar">
        <div class="salon-card">
            <div class="salon-img">
                @if($salon->cover_photo ?? null)
                <img src="{{ asset('storage/'.$salon->cover_photo) }}" alt="{{ $salon->name }}" onerror="this.parentElement.textContent='💆'">
                @else 💆 @endif
            </div>
            <div>
                <div class="salon-name">{{ $salon->name }}</div>
                <div class="salon-loc"><i class="fas fa-map-marker-alt" style="color:#E91E8C;font-size:.65rem;"></i> {{ $salon->city }}</div>
            </div>
        </div>

        <div class="order-box">
            <div class="order-hdr">Booking summary</div>
            <div class="order-row">
                <div>
                    <div class="or-name">{{ $service->name }}</div>
                    <div class="or-detail">{{ $service->duration ?? 60 }} min</div>
                </div>
                <div class="or-price">Rs. {{ number_format($service->price) }}</div>
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
                <span class="ot-lbl">Total amount</span>
                <span class="ot-amt">Rs. {{ number_format($service->price) }}</span>
            </div>
        </div>

        <div class="advance-box">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong>Rs. 100 advance required now</strong> to confirm your booking. The remaining Rs. {{ number_format($service->price - 100) }} is paid directly at the salon.
            </div>
        </div>
    </div>

</div>

<script>
function selectMethod(el) {
    document.querySelectorAll('.pf-method-chip').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('selectedMethod').value = el.dataset.method;
}
</script>
</body>
</html>
