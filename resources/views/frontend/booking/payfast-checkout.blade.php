<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PayFast Secure Checkout</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, #f4f0fa 0%, #fdf2f8 100%);
        min-height: 100vh;
        display: flex; align-items: center; justify-content: center;
        padding: 24px;
        -webkit-font-smoothing: antialiased;
    }
 
    .checkout-box {
        background: #fff; border-radius: 24px; width: 100%; max-width: 460px;
        box-shadow: 0 24px 70px rgba(147,51,234,0.16); overflow: hidden;
    }
    .pf-header {
        background: linear-gradient(135deg, #E91E8C, #9333ea);
        padding: 20px 26px; color: #fff;
        display: flex; align-items: center; justify-content: space-between;
    }
    .pf-header .pf-brand { font-size: 1.1rem; font-weight: 800; display: flex; align-items: center; gap: 8px; }
    .pf-header .pf-tag { font-size: .68rem; background: rgba(255,255,255,0.2); padding: 3px 10px; border-radius: 20px; font-weight: 700; }
 
    .pf-amount-strip { background: #fdf2f8; padding: 18px 26px; text-align: center; border-bottom: 1px solid #f3e5ec; }
    .pf-amount-strip .lbl { font-size: .68rem; color: #9333ea; text-transform: uppercase; letter-spacing: .6px; font-weight: 700; }
    .pf-amount-strip .amt { font-size: 1.8rem; font-weight: 900; color: #1a1a1a; }
    .pf-amount-strip .ref { font-size: .7rem; color: #aaa; margin-top: 2px; }
 
    .pf-body { padding: 24px 26px 26px; }
 
    /* method tabs */
    .pf-tabs { display: flex; gap: 8px; margin-bottom: 20px; background: #faf7fb; padding: 5px; border-radius: 14px; }
    .pf-tab {
        flex: 1; text-align: center; padding: 9px 6px; border-radius: 10px;
        font-size: .76rem; font-weight: 700; color: #999; cursor: pointer;
        display: flex; flex-direction: column; align-items: center; gap: 4px;
        transition: all .15s;
    }
    .pf-tab i { font-size: .95rem; }
    .pf-tab.active { background: #fff; color: #9333ea; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
 
    .pf-step { display: none; }
    .pf-step.active { display: block; animation: fadeIn .25s ease; }
    @keyframes fadeIn { from { opacity:0; transform:translateY(6px);} to {opacity:1; transform:translateY(0);} }
 
    /* live card preview for the Card tab */
    .card-preview {
        background: linear-gradient(135deg, #1a1a2e, #2d1b35);
        border-radius: 16px; padding: 20px 22px; margin-bottom: 20px; color: #fff;
        position: relative; overflow: hidden; min-height: 120px;
    }
    .card-preview::before { content:''; position:absolute; top:-30px; right:-30px; width:130px; height:130px; background:rgba(255,255,255,0.06); border-radius:50%; }
    .card-chip { width: 34px; height: 24px; background: linear-gradient(135deg,#ffd700,#ffb700); border-radius: 5px; margin-bottom: 16px; }
    .card-number-display { font-size: 1.05rem; font-weight: 600; letter-spacing: 2.5px; margin-bottom: 14px; font-family: 'Courier New', monospace; }
    .card-bottom { display: flex; justify-content: space-between; align-items: flex-end; font-size: .78rem; }
    .card-bottom .cb-label { font-size: .6rem; opacity: .6; text-transform: uppercase; letter-spacing: .5px; }
    .card-bottom .cb-value { font-weight: 700; }
 
    .field-label { font-size: .78rem; font-weight: 700; color: #555; margin-bottom: 6px; display: block; }
    .field-input {
        width: 100%; border: 1.5px solid #e8e0ee; border-radius: 12px; padding: 12px 14px;
        font-size: .9rem; font-family: 'Inter', sans-serif; color: #1a1a1a; margin-bottom: 14px;
        transition: border-color .15s;
    }
    .field-input:focus { outline: none; border-color: #9333ea; }
    .field-row { display: flex; gap: 12px; }
    .field-row > div { flex: 1; }
 
    .wallet-icon-row { display: flex; align-items: center; gap: 10px; background: #faf7fb; border-radius: 12px; padding: 12px 14px; margin-bottom: 16px; }
    .wallet-icon-row i { font-size: 1.3rem; }
    .wallet-icon-row .wt { font-size: .8rem; font-weight: 700; color: #333; }
    .wallet-icon-row .ws { font-size: .7rem; color: #999; }
 
    .otp-boxes { display: flex; gap: 8px; justify-content: center; margin-bottom: 18px; }
    .otp-boxes input {
        width: 42px; height: 50px; text-align: center; font-size: 1.15rem; font-weight: 700;
        border: 1.5px solid #e8e0ee; border-radius: 10px; color: #1a1a1a;
    }
    .otp-boxes input:focus { outline: none; border-color: #9333ea; }
 
    .pf-btn {
        width: 100%; padding: 14px; border: none; border-radius: 12px;
        background: linear-gradient(135deg, #E91E8C, #9333ea); color: #fff;
        font-size: .92rem; font-weight: 800; cursor: pointer; transition: all .2s;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        font-family: 'Inter', sans-serif;
    }
    .pf-btn:hover { transform: translateY(-1px); box-shadow: 0 8px 22px rgba(147,51,234,0.3); }
    .pf-btn:disabled { opacity: .6; cursor: not-allowed; transform: none; }
 
    .pf-cancel { text-align: center; margin-top: 14px; }
    .pf-cancel a { font-size: .76rem; color: #999; text-decoration: underline; cursor: pointer; }
    .pf-cancel a:hover { color: #E91E8C; }
 
    .pf-hint { font-size: .72rem; color: #aaa; text-align: center; margin-top: 14px; line-height: 1.5; }
    .pf-hint strong { color: #9333ea; }
 
    .pf-spinner-wrap { text-align: center; padding: 36px 0; }
    .pf-spinner { width: 50px; height: 50px; border: 4px solid #f3e5ec; border-top-color: #E91E8C; border-radius: 50%; margin: 0 auto 16px; animation: spin .8s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .pf-spinner-wrap p { font-size: .84rem; color: #666; font-weight: 600; }
 
    .pf-footer-badges { display: flex; justify-content: center; gap: 16px; padding: 14px 26px 20px; }
    .pf-footer-badges span { font-size: .66rem; color: #bbb; display: flex; align-items: center; gap: 5px; }
 
    .field-error { color: #ef4444; font-size: .72rem; margin: -10px 0 12px; min-height: 14px; }
    </style>
</head>
<body>
 
<div class="checkout-box">
    <div class="pf-header">
        <div class="pf-brand"><i class="fas fa-bolt"></i> PayFast</div>
        <span class="pf-tag">SANDBOX</span>
    </div>
 
    <div class="pf-amount-strip">
        <div class="lbl">Amount to pay</div>
        <div class="amt">Rs. 100.00</div>
        <div class="ref">to {{ $salon->name }}</div>
    </div>
 
    <div class="pf-body">
 
        {{-- METHOD TABS --}}
        <div class="pf-tabs" id="pfTabs">
            <div class="pf-tab" data-method="jazzcash" onclick="switchMethod('jazzcash')">
                <i class="fas fa-mobile-alt"></i> JazzCash
            </div>
            <div class="pf-tab" data-method="easypaisa" onclick="switchMethod('easypaisa')">
                <i class="fas fa-mobile-alt"></i> EasyPaisa
            </div>
            <div class="pf-tab" data-method="card" onclick="switchMethod('card')">
                <i class="far fa-credit-card"></i> Card
            </div>
        </div>
 
        {{-- STEP 1: input form --}}
        <div class="pf-step active" id="step-input">
 
            {{-- Wallet (JazzCash/EasyPaisa) fields --}}
            <div id="walletFields">
                <div class="wallet-icon-row">
                    <i class="fas fa-mobile-alt" style="color:#E91E8C;" id="walletIcon"></i>
                    <div>
                        <div class="wt" id="walletTitle">JazzCash Mobile Account</div>
                        <div class="ws">You'll receive a payment prompt on this number</div>
                    </div>
                </div>
                <label class="field-label">Mobile number</label>
                <input type="tel" class="field-input" id="mobileNumber" placeholder="03XX-XXXXXXX" maxlength="11" oninput="validateMobile()">
                <div class="field-error" id="mobileError"></div>
            </div>
 
            {{-- Card fields --}}
            <div id="cardFields" style="display:none;">
                <div class="card-preview">
                    <div class="card-chip"></div>
                    <div class="card-number-display" id="cardPreviewNum">•••• •••• •••• ••••</div>
                    <div class="card-bottom">
                        <div>
                            <div class="cb-label">Card holder</div>
                            <div class="cb-value" id="cardPreviewName">YOUR NAME</div>
                        </div>
                        <div>
                            <div class="cb-label">Expires</div>
                            <div class="cb-value" id="cardPreviewExp">MM/YY</div>
                        </div>
                    </div>
                </div>
 
                <label class="field-label">Card number</label>
                <input type="text" class="field-input" id="cardNumber" placeholder="4111 1111 1111 1111" maxlength="19" oninput="formatCardNumber()">
                <div class="field-error" id="cardNumberError"></div>
 
                <label class="field-label">Card holder name</label>
                <input type="text" class="field-input" id="cardName" placeholder="As shown on card" oninput="updateCardPreview()">
 
                <div class="field-row">
                    <div>
                        <label class="field-label">Expiry</label>
                        <input type="text" class="field-input" id="cardExpiry" placeholder="MM/YY" maxlength="5" oninput="formatExpiry()">
                    </div>
                    <div>
                        <label class="field-label">CVV</label>
                        <input type="password" class="field-input" id="cardCvv" placeholder="123" maxlength="3" inputmode="numeric">
                    </div>
                </div>
                <div class="field-error" id="cardOtherError"></div>
            </div>
 
            <button class="pf-btn" onclick="proceedToOtp()" id="proceedBtn">
                <i class="fas fa-arrow-right"></i> Proceed
            </button>
 
            <div class="pf-hint">
                Sandbox test values — mobile: <strong>0300-1234567</strong>, card: <strong>4111 1111 1111 1111</strong>, any future expiry &amp; CVV
            </div>
        </div>
 
        {{-- STEP 2: OTP --}}
        <div class="pf-step" id="step-otp">
            <p style="font-size:.84rem;color:#555;margin-bottom:18px;text-align:center;">
                Enter the 6-digit code sent to <strong id="otpDestination">your number</strong>
            </p>
            <div class="otp-boxes">
                <input type="text" maxlength="1" class="otp-digit" inputmode="numeric">
                <input type="text" maxlength="1" class="otp-digit" inputmode="numeric">
                <input type="text" maxlength="1" class="otp-digit" inputmode="numeric">
                <input type="text" maxlength="1" class="otp-digit" inputmode="numeric">
                <input type="text" maxlength="1" class="otp-digit" inputmode="numeric">
                <input type="text" maxlength="1" class="otp-digit" inputmode="numeric">
            </div>
            <button class="pf-btn" onclick="confirmPayment()">
                <i class="fas fa-check-circle"></i> Confirm payment
            </button>
            <div class="pf-hint">Sandbox OTP: <strong>123456</strong> (any 6 digits work in test mode)</div>
        </div>
 
        {{-- STEP 3: processing --}}
        <div class="pf-step" id="step-processing">
            <div class="pf-spinner-wrap">
                <div class="pf-spinner"></div>
                <p>Processing your payment securely...</p>
            </div>
        </div>
 
        <div class="pf-cancel" id="cancelLinkWrap">
            <a onclick="cancelPayment()">Cancel and return to Glamora</a>
        </div>
    </div>
 
    <div class="pf-footer-badges">
        <span><i class="fas fa-lock"></i> SSL Secured</span>
        <span><i class="fas fa-shield-alt"></i> PCI Compliant</span>
    </div>
</div>
 
<script>
const initialMethod = "{{ $requestedMethod ?? 'jazzcash' }}";
let currentMethod = ['jazzcash','easypaisa','card'].includes(initialMethod) ? initialMethod : 'jazzcash';
 
const walletMeta = {
    jazzcash:  { title: 'JazzCash Mobile Account', icon: 'fa-mobile-alt' },
    easypaisa: { title: 'EasyPaisa Mobile Account', icon: 'fa-mobile-alt' },
};
 
function switchMethod(method) {
    currentMethod = method;
    document.querySelectorAll('.pf-tab').forEach(t => t.classList.toggle('active', t.dataset.method === method));
 
    if (method === 'card') {
        document.getElementById('walletFields').style.display = 'none';
        document.getElementById('cardFields').style.display = 'block';
    } else {
        document.getElementById('walletFields').style.display = 'block';
        document.getElementById('cardFields').style.display = 'none';
        document.getElementById('walletTitle').textContent = walletMeta[method].title;
    }
}
switchMethod(currentMethod);
 
function validateMobile() {
    const val = document.getElementById('mobileNumber').value;
    const errEl = document.getElementById('mobileError');
    errEl.textContent = (val.length > 0 && val.length < 11) ? 'Enter a valid 11-digit mobile number' : '';
}
 
function formatCardNumber() {
    let input = document.getElementById('cardNumber');
    let digits = input.value.replace(/\D/g, '').slice(0, 16);
    let formatted = digits.match(/.{1,4}/g)?.join(' ') || digits;
    input.value = formatted;
    updateCardPreview();
}
 
function formatExpiry() {
    let input = document.getElementById('cardExpiry');
    let digits = input.value.replace(/\D/g, '').slice(0, 4);
    if (digits.length >= 3) digits = digits.slice(0,2) + '/' + digits.slice(2);
    input.value = digits;
    updateCardPreview();
}
 
function updateCardPreview() {
    const num = document.getElementById('cardNumber').value;
    const name = document.getElementById('cardName').value;
    const exp = document.getElementById('cardExpiry').value;
 
    document.getElementById('cardPreviewNum').textContent = num ? num.padEnd(19, '•') : '•••• •••• •••• ••••';
    document.getElementById('cardPreviewName').textContent = name ? name.toUpperCase() : 'YOUR NAME';
    document.getElementById('cardPreviewExp').textContent = exp || 'MM/YY';
}
 
function proceedToOtp() {
    let valid = true;
    let senderNumber = '';
 
    if (currentMethod === 'card') {
        const num = document.getElementById('cardNumber').value.replace(/\s/g, '');
        const exp = document.getElementById('cardExpiry').value;
        const cvv = document.getElementById('cardCvv').value;
        const errEl = document.getElementById('cardOtherError');
        const numErrEl = document.getElementById('cardNumberError');
 
        numErrEl.textContent = '';
        errEl.textContent = '';
 
        if (num.length < 13) { numErrEl.textContent = 'Enter a valid card number'; valid = false; }
        if (!/^\d{2}\/\d{2}$/.test(exp)) { errEl.textContent = 'Enter expiry as MM/YY'; valid = false; }
        if (cvv.length < 3) { errEl.textContent = 'Enter a valid CVV'; valid = false; }
 
        senderNumber = num.slice(-4); // store last 4 digits as reference
        document.getElementById('otpDestination').textContent = 'your registered mobile number';
    } else {
        const mobile = document.getElementById('mobileNumber').value;
        if (mobile.length < 10) {
            document.getElementById('mobileError').textContent = 'Enter a valid mobile number';
            valid = false;
        }
        senderNumber = mobile;
        document.getElementById('otpDestination').textContent = mobile || 'your number';
    }
 
    if (!valid) return;
 
    // Save the entered sender number / card reference against the pending payment
    fetch("{{ route('booking.payment.confirmMock') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ sender_number: senderNumber }),
    }).catch(() => {});
 
    showStep('step-otp');
}
 
document.querySelectorAll('.otp-digit').forEach((box, i, all) => {
    box.addEventListener('input', () => {
        box.value = box.value.replace(/\D/g, '');
        if (box.value.length === 1 && i < all.length - 1) all[i + 1].focus();
    });
    box.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && box.value === '' && i > 0) all[i - 1].focus();
    });
});
 
function showStep(id) {
    document.querySelectorAll('.pf-step').forEach(s => s.classList.remove('active'));
    document.getElementById(id).classList.add('active');
    document.getElementById('cancelLinkWrap').style.display = (id === 'step-processing') ? 'none' : 'block';
}
 
function confirmPayment() {
    showStep('step-processing');
    setTimeout(() => {
        window.location.href = "{{ route('payfast.return') }}";
    }, 1700);
}
 
function cancelPayment() {
    window.location.href = "{{ route('payfast.cancel') }}";
}
</script>
</body>
</html>