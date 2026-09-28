@extends('layouts.auth')

@section('title', 'Pending Review')

@section('content')
<div class="status-page">
    <div class="status-card">
        <div class="status-icon">⏳</div>
        <span class="status-badge">Pending Review</span>
        <h2 class="status-title">Your Salon Is Under Review</h2>
        <p class="status-text">
            Thanks for registering with us! Our team is currently reviewing your salon details
            to make sure everything meets our quality standards. This usually takes
            <strong>24–48 hours</strong>.
        </p>
        <p class="status-subtext">
            Once approved, you'll receive a confirmation email and your dashboard will
            unlock automatically — no further action needed from your side.
        </p>

        <div class="status-steps">
            <div class="step done">
                <span class="step-dot">✓</span>
                <span>Registration Submitted</span>
            </div>
            <div class="step active">
                <span class="step-dot">2</span>
                <span>Under Admin Review</span>
            </div>
            <div class="step">
                <span class="step-dot">3</span>
                <span>Dashboard Unlocked</span>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="status-btn">Logout</button>
        </form>

        <p class="status-footer">
    Need help? <a href="mailto:beautyblushsalons@gmail.com">Contact Support</a>
</p>
    </div>
</div>

<style>
.status-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #fdf2f8 0%, #fae8ff 50%, #fce7f3 100%);
    padding: 24px;
}

.status-card {
    background: #ffffff;
    max-width: 460px;
    width: 100%;
    padding: 48px 36px;
    border-radius: 24px;
    box-shadow: 0 20px 60px rgba(219, 39, 119, 0.12), 0 4px 12px rgba(0,0,0,0.04);
    text-align: center;
    position: relative;
    overflow: hidden;
    animation: fadeUp 0.5s ease;
}

.status-card::before {
    content: "";
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 5px;
    background: linear-gradient(90deg, #f472b6, #db2777, #f472b6);
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to { opacity: 1; transform: translateY(0); }
}

.status-icon {
    width: 84px;
    height: 84px;
    margin: 0 auto 18px;
    border-radius: 50%;
    background: radial-gradient(circle, #fce7f3, #fbcfe8);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 38px;
    animation: pulse 2.2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.06); }
}

.status-badge {
    display: inline-block;
    background: #fef3c7;
    color: #92400e;
    padding: 6px 16px;
    border-radius: 30px;
    font-size: 12.5px;
    font-weight: 700;
    letter-spacing: 0.4px;
    text-transform: uppercase;
    margin-bottom: 18px;
}

.status-title {
    color: #1f2937;
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 14px;
    line-height: 1.3;
}

.status-text {
    color: #4b5563;
    font-size: 14.5px;
    line-height: 1.7;
    margin-bottom: 10px;
}

.status-subtext {
    color: #9ca3af;
    font-size: 13px;
    line-height: 1.6;
    margin-bottom: 28px;
}

.status-steps {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
    position: relative;
}

.status-steps::before {
    content: "";
    position: absolute;
    top: 14px;
    left: 12%;
    right: 12%;
    height: 2px;
    background: #f3d9e8;
    z-index: 0;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    flex: 1;
    position: relative;
    z-index: 1;
}

.step span:last-child {
    font-size: 11px;
    color: #9ca3af;
    max-width: 80px;
}

.step-dot {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #f3f4f6;
    color: #9ca3af;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
    border: 2px solid #f3f4f6;
}

.step.done .step-dot {
    background: #db2777;
    color: #fff;
    border-color: #db2777;
}

.step.done span:last-child {
    color: #db2777;
    font-weight: 600;
}

.step.active .step-dot {
    background: #fff;
    color: #db2777;
    border-color: #db2777;
    animation: ringPulse 1.8s ease-in-out infinite;
}

.step.active span:last-child {
    color: #1f2937;
    font-weight: 600;
}

@keyframes ringPulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(219, 39, 119, 0.3); }
    50% { box-shadow: 0 0 0 6px rgba(219, 39, 119, 0); }
}

.status-btn {
    background: linear-gradient(135deg, #db2777, #be185d);
    color: #fff;
    border: none;
    padding: 12px 34px;
    border-radius: 12px;
    font-size: 14.5px;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
    box-shadow: 0 6px 16px rgba(219, 39, 119, 0.3);
}

.status-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 22px rgba(219, 39, 119, 0.4);
}

.status-btn:active {
    transform: translateY(0);
}

.status-footer {
    margin-top: 20px;
    font-size: 12.5px;
    color: #9ca3af;
}

.status-footer a {
    color: #db2777;
    font-weight: 600;
    text-decoration: none;
}

.status-footer a:hover {
    text-decoration: underline;
}
</style>
@endsection
