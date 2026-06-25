
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Submitted — Glamora</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, #fdf2f8 0%, #faf5ff 100%);
        min-height: 100vh;
        display: flex; align-items: center; justify-content: center;
        padding: 40px 20px;
        -webkit-font-smoothing: antialiased;
    }
    .confetti { position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 999; }
 
    .success-card {
        background: #fff; border-radius: 26px; padding: 46px 40px;
        max-width: 520px; width: 100%; text-align: center;
        box-shadow: 0 24px 70px rgba(147,51,234,0.14); position: relative; z-index: 1;
    }
    @media(max-width:560px){ .success-card { padding: 32px 22px; } }
 
    .success-icon {
        width: 86px; height: 86px;
        background: linear-gradient(135deg, #E91E8C, #9333ea);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 22px;
        animation: pop .5s ease;
        font-size: 2.1rem; color: #fff;
        box-shadow: 0 12px 30px rgba(147,51,234,0.3);
    }
    @keyframes pop { 0% { transform: scale(0); opacity: 0; } 80% { transform: scale(1.08); } 100% { transform: scale(1); opacity: 1; } }
 
    .success-title { font-family: 'Playfair Display', serif; font-size: 1.7rem; font-weight: 800; color: #1a1a1a; margin-bottom: 8px; }
    .success-sub { color: #888; font-size: .88rem; margin-bottom: 18px; line-height: 1.6; }
 
    .booking-id {
        background: #fdf2f8; border: 1.5px dashed #E91E8C; border-radius: 12px;
        padding: 11px 22px; display: inline-block; margin-bottom: 24px;
        font-size: .85rem; color: #888;
    }
    .booking-id strong { color: #E91E8C; font-size: 1rem; }
 
    .detail-grid { background: #faf8fb; border-radius: 16px; padding: 20px; text-align: left; margin-bottom: 20px; }
    .detail-item { display: flex; justify-content: space-between; align-items: flex-start; padding: 9px 0; border-bottom: 1px solid #f0eaf0; font-size: .87rem; gap: 12px; }
    .detail-item:last-child { border-bottom: none; }
    .detail-item span:first-child { color: #999; flex-shrink: 0; }
    .detail-item span:last-child { font-weight: 700; color: #1a1a1a; text-align: right; }
 
    .status-pill-pending { background: #fff8ec; color: #92400e; padding: 4px 14px; border-radius: 20px; font-size: .78rem; font-weight: 700; border: 1px solid #fde68a; }
    .status-pill-amount  { background: #f0fdf4; color: #16a34a; padding: 4px 14px; border-radius: 20px; font-size: .78rem; font-weight: 700; }
 
    .pending-notice {
        background: linear-gradient(135deg, #fffbeb, #fef3c7);
        border: 1px solid #fcd34d; border-radius: 14px; padding: 18px;
        margin-bottom: 22px; display: flex; gap: 12px; align-items: flex-start; text-align: left;
    }
    .pending-notice i { color: #f59e0b; font-size: 1.1rem; margin-top: 2px; flex-shrink: 0; }
    .pending-notice strong { color: #92400e; display: block; margin-bottom: 4px; font-size: .85rem; }
    .pending-notice p { color: #78350f; font-size: .8rem; margin: 0; line-height: 1.6; }
 
    .action-row { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
    .btn-primary {
        background: linear-gradient(135deg, #E91E8C, #9333ea); color: #fff;
        border-radius: 50px; padding: 13px 24px; font-weight: 700; font-size: .88rem;
        text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all .2s;
    }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(147,51,234,0.3); }
    .btn-ghost {
        background: #f5f5f5; color: #555; border-radius: 50px; padding: 13px 24px;
        font-weight: 700; font-size: .88rem; text-decoration: none; transition: all .15s;
    }
    .btn-ghost:hover { background: #ebebeb; }
    </style>
</head>
<body>
 
<canvas class="confetti" id="confetti"></canvas>
 
<div class="success-card">
    <div class="success-icon"><i class="fas fa-paper-plane"></i></div>
 
    <h2 class="success-title">Booking Submitted!</h2>
    <p class="success-sub">
        Your payment screenshot has been sent for verification. Your appointment will be confirmed once admin approves it.
    </p>
 
    <div class="booking-id">Booking ID: <strong>#{{ $appointment->booking_ref ?? $appointment->id }}</strong></div>
 
    <div class="detail-grid">
        <div class="detail-item">
            <span>Salon</span>
            <span>{{ $appointment->salon->name ?? 'N/A' }}</span>
        </div>
        <div class="detail-item">
            <span>Service</span>
            <span>{{ $appointment->service->name ?? 'N/A' }}</span>
        </div>
        <div class="detail-item">
            <span>Stylist</span>
            <span>{{ $appointment->stylist->name ?? 'N/A' }}</span>
        </div>
        <div class="detail-item">
            <span>Date</span>
            <span>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M, Y') }}</span>
        </div>
        <div class="detail-item">
            <span>Time</span>
            <span>{{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}</span>
        </div>
        <div class="detail-item">
            <span>Advance Paid</span>
            <span class="status-pill-amount">Rs. {{ number_format($appointment->advance_amount ?? 100) }}</span>
        </div>
        <div class="detail-item">
            <span>Status</span>
            <span class="status-pill-pending">⏳ Pending Verification</span>
        </div>
    </div>
 
    <div class="pending-notice">
        <i class="fas fa-bell"></i>
        <div>
            <strong>What happens next?</strong>
            <p>Admin will review your payment screenshot and approve your booking. You'll receive a confirmation email as soon as it's approved — usually within a few hours.</p>
        </div>
    </div>
 
    <div class="action-row">
        <a href="{{ route('client.appointments.index') }}" class="btn-primary">
            <i class="fas fa-calendar-check"></i> My Appointments
        </a>
        <a href="{{ route('home') }}" class="btn-ghost">
            <i class="fas fa-home"></i> Back to Home
        </a>
    </div>
</div>
 
<script>
const canvas = document.getElementById('confetti');
const ctx = canvas.getContext('2d');
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;
 
const pieces = [];
const colors = ['#E91E8C', '#9333ea', '#f4a0b5', '#c084fc', '#fbbf24', '#34d399'];
for (let i = 0; i < 120; i++) {
    pieces.push({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height - canvas.height,
        size: Math.random() * 7 + 3,
        color: colors[Math.floor(Math.random() * colors.length)],
        speed: Math.random() * 3 + 1.5,
        angle: Math.random() * 360,
        spin: Math.random() * 8 - 4,
    });
}
 
let frame;
function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    pieces.forEach(p => {
        ctx.save();
        ctx.translate(p.x, p.y);
        ctx.rotate(p.angle * Math.PI / 180);
        ctx.fillStyle = p.color;
        ctx.fillRect(-p.size / 2, -p.size / 2, p.size, p.size);
        ctx.restore();
        p.y += p.speed;
        p.angle += p.spin;
        if (p.y > canvas.height) { p.y = -10; p.x = Math.random() * canvas.width; }
    });
    frame = requestAnimationFrame(animate);
}
animate();
setTimeout(() => { cancelAnimationFrame(frame); ctx.clearRect(0, 0, canvas.width, canvas.height); }, 3500);
</script>
</body>
</html>