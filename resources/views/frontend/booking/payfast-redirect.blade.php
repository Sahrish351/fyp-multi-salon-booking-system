
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to PayFast...</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: 'Inter', sans-serif;
        background: #f5f5f5;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .box {
        background: #fff;
        border-radius: 20px;
        padding: 48px 40px;
        text-align: center;
        max-width: 380px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    }
    .spinner {
        width: 56px; height: 56px;
        border: 5px solid #fce4ec;
        border-top-color: #E91E8C;
        border-radius: 50%;
        margin: 0 auto 24px;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    h2 { font-size: 1.2rem; color: #1a1a1a; font-weight: 800; margin-bottom: 8px; }
    p { font-size: 0.85rem; color: #888; line-height: 1.6; }
    .payfast-logo { margin-top: 20px; font-size: 0.75rem; color: #aaa; display: flex; align-items: center; justify-content: center; gap: 6px; }
    </style>
</head>
<body>
    <div class="box">
        <div class="spinner"></div>
        <h2>Redirecting to PayFast...</h2>
        <p>Please wait while we securely connect you to PayFast Sandbox to complete your Rs.100 advance payment.</p>
        <div class="payfast-logo"><i class="fas fa-shield-alt" style="color:#16a34a;"></i> Secure Sandbox Payment</div>
    </div>

    {{-- Auto-submitting PayFast form — exactly matches the official PayFast
         integration pattern (merchant_id, merchant_key, return/cancel/notify urls) --}}
    <form id="payfastForm" method="POST" action="{{ $sandboxUrl }}">
        <input type="hidden" name="merchant_id"  value="{{ $payfastData['merchant_id'] }}">
        <input type="hidden" name="merchant_key" value="{{ $payfastData['merchant_key'] }}">
        <input type="hidden" name="return_url"   value="{{ $payfastData['return_url'] }}">
        <input type="hidden" name="cancel_url"   value="{{ $payfastData['cancel_url'] }}">
        <input type="hidden" name="notify_url"   value="{{ $payfastData['notify_url'] }}">
        <input type="hidden" name="amount"       value="{{ $payfastData['amount'] }}">
        <input type="hidden" name="item_name"    value="{{ $payfastData['item_name'] }}">
        <input type="hidden" name="order_id"     value="{{ $payfastData['order_id'] }}">
    </form>

    <script>
        
        setTimeout(function () {
            document.getElementById('payfastForm').submit();
        }, 900);
    </script>
</body>
</html>
