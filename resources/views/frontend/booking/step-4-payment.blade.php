
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment — {{ $salon->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; background: #f5f5f5; min-height: 100vh; -webkit-font-smoothing: antialiased; }

    .top-nav { position: fixed; top: 0; left: 0; right: 0; display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; z-index: 200; background: #f5f5f5; }
    .nav-btn { width: 44px; height: 44px; border-radius: 50%; border: 1.5px solid #e0e0e0; background: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 1rem; color: #1a1a1a; transition: all .15s; text-decoration: none; }
    .nav-btn:hover { border-color: #1a1a1a; }
    .breadcrumb-bar { display: flex; align-items: center; gap: 8px; font-size: 0.82rem; color: #aaa; }
    .breadcrumb-bar .active { color: #1a1a1a; font-weight: 700; }

    .pay-layout {
        display: grid; grid-template-columns: 1fr 380px; gap: 0;
        max-width: 1100px; margin: 0 auto; padding: 90px 24px 60px; align-items: start;
    }
    @media(max-width:900px) { .pay-layout { grid-template-columns: 1fr; } .pay-sidebar { display: none; } }

    .pay-left { padding-right: 52px; }
    .pay-left h1 { font-size: 2rem; font-weight: 900; color: #1a1a1a; letter-spacing: -1px; margin-bottom: 6px; }
    .pay-sub { font-size: .85rem; color: #888; margin-bottom: 24px; }

    .secure-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 50px;
        padding: 6px 14px; font-size: .72rem; font-weight: 600; color: #16a34a; margin-bottom: 24px;
    }

    .payfast-card {
        background: linear-gradient(135deg, #003D3D, #005F5F);
        border-radius: 20px; padding: 32px 28px; color: #fff;
        position: relative; overflow: hidden; margin-bottom: 24px;
    }
    .payfast-card::before {
        content: ''; position: absolute; right: -40px; top: -40px;
        width: 180px; height: 180px; border-radius: 50%; background: rgba(212,175,55,0.12);
    }
    .pf-logo { font-size: 1.4rem; font-weight: 800; color: #D4AF37; margin-bottom: 6px; display: flex; align-items: center; gap: 10px; }
    .pf-sub  { font-size: .8rem; color: rgba(255,255,255,0.75); margin-bottom: 22px; }
    .pf-methods { display: flex; gap: 10px; margin-bottom: 24px; flex-wrap: wrap; }
    .pf-method-chip {
        background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
        border-radius: 50px; padding: 6px 14px; font-size: .72rem; font-weight: 600;
        display: flex; align-items: center; gap: 6px;
    }
    .pf-amount-row {
        display: flex; align-items: center; justify-content: space-between;
        background: rgba(255,255,255,0.08); border-radius: 14px; padding: 16px 18px; margin-bottom: 22px;
    }
    .pf-amount-label { font-size: .72rem; color: rgba(255,255,255,0.65); text-transform: uppercase; letter-spacing: .5px; }
    .pf-amount-value { font-size: 1.6rem; font-weight: 900; color: #D4AF37; }

    .pay-btn {
        width: 100%; padding: 16px;
        background: #D4AF37; color: #003D3D;
        border: none; border-radius: 14px;
        font-size: 1rem; font-weight: 800; cursor: pointer; transition: all .2s;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        font-family: 'Inter', sans-serif; box-shadow: 0 8px 24px rgba(212,175,55,0.3);
    }
    .pay-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(212,175,55,0.4); }

    .sandbox-note {
        background: #fff8ec; border: 1px solid #fde68a; border-radius: 12px;
        padding: 14px 16px; font-size: .78rem; color: #92400e; margin-bottom: 24px;
        display: flex; align-items: flex-start; gap: 10px; line-height: 1.5;
    }
    .sandbox-note i { color: #f59e0b; margin-top: 1px; flex-shrink: 0; }

    .test-card-box {
        background: #fff; border: 1.5px dashed #e8e8e8; border-radius: 14px;
        padding: 16px 18px; margin-bottom: 20px;
    }
    .test-card-box .tc-title { font-size: .75rem; font-weight: 700; color: #555; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 10px; }
    .tc-row { display: flex; justify-content: space-between; font-size: .82rem; padding: 5px 0; color: #444; }
    .tc-row strong { color: #1a1a1a; font-family: monospace; }

    .pay-sidebar { padding-left: 40px; position: sticky; top: 90px; }
    .salon-card { background: #fff; border: 1.5px solid #e8e8e8; border-radius: 18px; padding: 16px; margin-bottom: 14px; display: flex; align-items: center; gap: 14px; }
    .salon-img { width: 58px; height: 58px; border-radius: 12px; flex-shrink: 0; overflow: hidden; background: #fce4ec; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
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

    .advance-box { background: #fff8ec; border: 1px solid #fde68a; border-radius: 12px; padding: 12px 14px; font-size: .75rem; color: #92400e; margin-bottom: 14px; display: flex; align-items: flex-start; gap: 8px; line-height: 1.5; }
    .advance-box i { color: #f59e0b; flex-shrink: 0; margin-top: 2px; }

    .info-box { background: #fff; border: 1.5px solid #e8e8e8; border-radius: 18px; padding: 16px; }
    .info-row { display: flex; align-items: center; gap: 10px; padding: 7px 0; font-size: .8rem; color: #555; }
    .info-row i { width: 16px; color: #E91E8C; font-size: .78rem; }
    .info-row:not(:last-child) { border-bottom: 1px solid #f9f9f9; }
    </style>
</head>
<body>

<div class="top-nav">
    <a href="{{ route('booking.step3', $salon->id) }}" class="nav-btn"><i class="fas fa-arrow-left"></i></a>
    <div class="breadcrumb-bar">
        <span>Services</span><span>›</span>
        <span>Professional</span><span>›</span>
        <span>Time</span><span>›</span>
        <span class="active">Payment</span>
    </div>
    <a href="{{ route('salons.show', $salon->slug) }}" class="nav-btn"><i class="fas fa-times"></i></a>
</div>

<div class="pay-layout">

    <div class="pay-left">
        <h1>Complete payment</h1>
        <p class="pay-sub">Pay Rs.100 advance via PayFast to secure your appointment</p>

        <div class="secure-badge">
            <i class="fas fa-shield-alt"></i> 256-bit SSL Encrypted & Secure
        </div>

      
        <div class="payfast-card">
            <div class="pf-logo"><i class="fas fa-credit-card"></i> PayFast</div>
            <p class="pf-sub">Pakistan's trusted payment gateway — pay with JazzCash, EasyPaisa, or any debit/credit card.</p>

            <div class="pf-methods">
                <div class="pf-method-chip"><i class="fas fa-mobile-alt"></i> JazzCash</div>
                <div class="pf-method-chip"><i class="fas fa-mobile-alt"></i> EasyPaisa</div>
                <div class="pf-method-chip"><i class="far fa-credit-card"></i> Debit/Credit Card</div>
                <div class="pf-method-chip"><i class="fas fa-university"></i> Bank Transfer</div>
            </div>

            <div class="pf-amount-row">
                <span class="pf-amount-label">Advance Payment</span>
                <span class="pf-amount-value">Rs.100</span>
            </div>

            <form action="{{ route('booking.payment.post', $salon->id) }}" method="POST">
                @csrf
                <button type="submit" class="pay-btn">
                    <i class="fas fa-lock"></i> Pay Rs.100 with PayFast
                </button>
            </form>
        </div>

       
        <div class="sandbox-note">
            <i class="fas fa-flask"></i>
            <div>
                <strong>Sandbox / Test Mode is active.</strong> This is a demo environment — no real money will be charged. Use the test card details below to complete a sample payment.
            </div>
        </div>

       
        <div class="test-card-box">
            <div class="tc-title">PayFast Sandbox Test Card</div>
            <div class="tc-row"><span>Card Number</span><strong>4111 1111 1111 1111</strong></div>
            <div class="tc-row"><span>Expiry Date</span><strong>12 / 26</strong></div>
            <div class="tc-row"><span>CVV</span><strong>123</strong></div>
            <div class="tc-row"><span>JazzCash Test Number</span><strong>0300-1234567</strong></div>
            <div class="tc-row"><span>OTP (if asked)</span><strong>123456</strong></div>
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
                    <div class="or-detail">{{ $service->duration_minutes ?? 60 }} min</div>
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
            <div class="info-row" style="margin-top:8px;padding-top:10px;">
                <i class="fas fa-money-bill-wave" style="color:#10b981 !important;"></i>
                <strong style="color:#10b981;">Pay Now: Rs.100</strong>
            </div>
        </div>
    </div>

</div>
</body>
</html>
