<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Select Date & Time — {{ $salon->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f8f7fa; min-height: 100vh; color: #1a1a1a; }
        
        .top-nav {
            position: fixed; top: 0; left: 0; right: 0;
            display: flex; align-items: center; justify-content: space-between;
            padding: 12px 24px; z-index: 200; background: #fff;
            border-bottom: 1px solid #f0e8ed;
        }
        .nav-btn {
            width: 40px; height: 40px; border-radius: 50%;
            border: 1.5px solid #e0e0e0; background: #fff;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: #1a1a1a; text-decoration: none;
        }
        .nav-btn:hover { border-color: #E91E8C; color: #E91E8C; }
        
        .breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 0.8rem; color: #aaa; }
        .breadcrumb .active { color: #E91E8C; font-weight: 700; }
 
        .booking-wrapper {
            padding-top: 76px; max-width: 1120px; margin: 0 auto;
            padding-left: 20px; padding-right: 20px; padding-bottom: 90px;
        }
        .booking-layout { display: grid; grid-template-columns: 1fr 340px; gap: 32px; align-items: start; }
        @media(max-width:992px) { .booking-layout { grid-template-columns: 1fr; gap: 20px; } }
 
        .cal-box, .time-box {
            background: #fff; border: 1.5px solid #f0e8ed; border-radius: 16px; padding: 20px; margin-bottom: 20px;
        }
 
        .stylist-pill {
            display: inline-flex; align-items: center; gap: 10px;
            background: #fff; border: 1.5px solid #f0e8ed; border-radius: 50px; padding: 6px 16px 6px 6px; margin-bottom: 18px;
        }
        .stylist-pill .av { width: 32px; height: 32px; border-radius: 50%; background: #E91E8C; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; }
 
        .month-nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
        .month-nav button { background: #fff; border: 1.5px solid #e0e0e0; border-radius: 50px; padding: 6px 14px; font-size: 0.78rem; font-weight: 600; cursor: pointer; }
        .month-nav button:hover { border-color: #E91E8C; color: #E91E8C; }
 
        .weekday-row { display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; margin-bottom: 6px; text-align: center; font-size: 0.65rem; color: #888; font-weight: 700; text-transform: uppercase; }
        .date-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; }
        .date-card {
            background: #fff; border: 1.5px solid #f0e8ed; border-radius: 10px; padding: 8px 4px; text-align: center; cursor: pointer; transition: all .2s;
        }
        .date-card:hover:not(.disabled) { border-color: #E91E8C; transform: translateY(-2px); }
        .date-card.selected { background: #E91E8C; border-color: #E91E8C; color: #fff; }
        .date-card.selected .day, .date-card.selected .num, .date-card.selected .month { color: #fff; }
        .date-card.disabled { opacity: 0.35; cursor: not-allowed; background: #f8f8f8; }
        .date-card .day { font-size: 0.58rem; color: #888; text-transform: uppercase; }
        .date-card .num { font-size: 0.95rem; font-weight: 800; }
        .date-card .month { font-size: 0.52rem; color: #888; }
 
        .time-slot-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
        @media(max-width:768px) { .time-slot-grid { grid-template-columns: repeat(3, 1fr); } }
        @media(max-width:480px) { .time-slot-grid { grid-template-columns: repeat(2, 1fr); } }
        .time-slot {
            background: #fff; border: 1.5px solid #f0e8ed; border-radius: 10px; padding: 10px 8px; text-align: center; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all .2s;
        }
        .time-slot:hover:not(.booked) { border-color: #E91E8C; background: #fff5f9; }
        .time-slot.selected { background: #E91E8C; border-color: #E91E8C; color: #fff; }
        .time-slot.booked { opacity: 0.45; cursor: not-allowed; background: #f8f8f8; }
 
        /* Waitlist Toggle Box */
        .waitlist-card {
            background: #fffbeb; border: 1.5px solid #fcd34d; border-radius: 16px; padding: 18px 20px; margin-top: 15px; cursor: pointer; transition: all .25s ease;
        }
        .waitlist-card:hover { border-color: #d97706; box-shadow: 0 4px 15px rgba(217,119,6,0.15); }
        .waitlist-card.selected {
            background: #fef3c7; border-color: #b45309; box-shadow: 0 0 0 2px #d97706;
        }
 
        .sidebar { position: sticky; top: 85px; }
        @media(max-width:992px) { .sidebar { display: none; } }
        .summary-card { background: #fff; border: 1.5px solid #f0e8ed; border-radius: 16px; padding: 18px; margin-bottom: 16px; }
 
        .continue-btn {
            background: #e0e0e0; color: #fff; border: none; border-radius: 50px; padding: 14px 24px; font-size: 0.92rem; font-weight: 700; width: 100%; cursor: not-allowed; transition: all .25s; display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .continue-btn.active {
            background: linear-gradient(135deg, #E91E8C, #c2185b); cursor: pointer; box-shadow: 0 4px 16px rgba(233,30,140,0.2);
        }
        .continue-btn.active:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(233,30,140,0.28); }
 
        .mobile-bar {
            display: none; position: fixed; bottom: 0; left: 0; right: 0; background: #fff; border-top: 1px solid #f0e8ed; padding: 12px 18px; z-index: 100;
        }
        @media(max-width:992px) { .mobile-bar { display: block; } }
    </style>
</head>
<body>
 
<div class="top-nav">
    <a href="{{ route('booking.step2', $salon->id) }}" class="nav-btn"><i class="fas fa-arrow-left"></i></a>
    <div class="breadcrumb">
        <span>Services</span> <span class="bc-sep">›</span>
        <span>Stylist</span> <span class="bc-sep">›</span>
        <span class="active">Date & Time</span> <span class="bc-sep">›</span>
        <span>Payment</span>
    </div>
    <a href="{{ route('salons.show', $salon->slug) }}" class="nav-btn"><i class="fas fa-times"></i></a>
</div>
 
<div class="booking-wrapper">
    <div class="booking-layout">
 
        <div class="left-panel">
            <h1 style="font-size:1.6rem; font-weight:800; margin-bottom:4px;">Select date and time</h1>
            <p style="font-size:0.85rem; color:#777; margin-bottom:18px;">Pick an available slot for booking, or join the waitlist if preferred time is full.</p>
 
            <div class="stylist-pill">
                <div class="av">{{ substr($stylist->name, 0, 1) }}</div>
                <span style="font-size:0.84rem; font-weight:600;">{{ $stylist->name }}</span>
            </div>
 
            {{-- 1. Date Calendar --}}
            <div class="cal-box">
                <div class="month-nav">
                    <button type="button" onclick="changeMonth(-1)"><i class="fas fa-chevron-left"></i> Previous</button>
                    <span class="current-month" id="currentMonth" style="font-weight:700;"></span>
                    <button type="button" onclick="changeMonth(1)">Next <i class="fas fa-chevron-right"></i></button>
                </div>
                <div class="weekday-row">
                    <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
                </div>
                <div class="date-grid" id="dateGrid"></div>
            </div>
 
            {{-- 2. Time Slots --}}
            <div class="time-box">
                <div style="font-size:0.95rem; font-weight:700; margin-bottom:12px; display:flex; justify-content:space-between;">
                    <span>Available Time Slots</span>
                    <span id="selectedDateBadge" style="font-size:0.75rem; color:#E91E8C;"></span>
                </div>
                <div class="time-slot-grid" id="timeSlotGrid">
                    <div style="grid-column:1/-1; text-align:center; color:#888; padding:20px 0; font-size:0.85rem;">
                        <i class="fas fa-calendar-day me-1" style="color:#E91E8C;"></i> Please select a date first
                    </div>
                </div>
            </div>
 
            {{-- 3. Waitlist Option Box --}}
            <div class="waitlist-card" id="waitlistCard" onclick="toggleWaitlistSelection()">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:6px;">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <i class="fas fa-clock" style="color:#d97706; font-size:1.1rem;"></i>
                        <strong style="color:#92400e; font-size:0.92rem;">Can't find a suitable time?</strong>
                    </div>
                    <span id="wlCheckBadge" style="font-size:0.75rem; font-weight:700; color:#d97706; background:#fff; padding:3px 10px; border-radius:50px; border:1px solid #fcd34d;">
                        Click to Select Waitlist
                    </span>
                </div>
                <p style="font-size:0.8rem; color:#78350f; margin:0; line-height:1.5;">
                    Select this option to join priority waitlist for <strong>{{ $stylist->name }}</strong> on your chosen date. If any client reschedules, you'll receive an instant 20-minute reservation invite!
                </p>
            </div>
 
        </div>
 
        {{-- Right Sidebar --}}
        <div class="sidebar">
            <div class="summary-card">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:14px;">
                    <div style="width:40px; height:40px; border-radius:10px; background:#fdf2f8; display:flex; align-items:center; justify-content:center; color:#E91E8C; font-weight:800;">
                        <i class="fas fa-store"></i>
                    </div>
                    <div>
                        <div style="font-weight:700; font-size:0.9rem;">{{ $salon->name }}</div>
                        <div style="font-size:0.72rem; color:#888;">{{ $salon->city }}</div>
                    </div>
                </div>
 
                <div id="bookingSummaryRows" style="font-size:0.82rem; color:#555; space-y-2;">
                    <div style="display:flex; justify-content:space-between; padding:6px 0; border-top:1px solid #f5f5f5;">
                        <span>Service:</span>
                        <strong style="color:#1a1a1a;">{{ $service->name }}</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; padding:6px 0; border-top:1px solid #f5f5f5;">
                        <span>Date:</span>
                        <strong id="sideDateText" style="color:#1a1a1a;">Not selected</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; padding:6px 0; border-top:1px solid #f5f5f5;">
                        <span>Time / Status:</span>
                        <strong id="sideTimeText" style="color:#1a1a1a;">Not selected</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; padding:8px 0; border-top:1px solid #f0f0f0; margin-top:4px; font-weight:700;">
                        <span>Total Service:</span>
                        <span style="color:#E91E8C;">Rs. {{ number_format($service->price) }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; padding:4px 0; color:#16a34a; font-size:0.78rem;">
                        <span>Advance Deposit (Step 4):</span>
                        <span>Rs. 100</span>
                    </div>
                </div>
            </div>
 
            <form action="{{ route('booking.step3.post', $salon->id) }}" method="POST" id="step3Form">
                @csrf
                <input type="hidden" name="time_slot_id" id="slotInput" value="">
                <input type="hidden" name="appointment_date" id="dateInput" value="">
                <input type="hidden" name="join_waitlist" id="waitlistInput" value="0">
                <button type="button" class="continue-btn" id="continueBtn" onclick="submitBookingStep3()">
                    Continue to Payment <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>
 
    </div>
</div>
 
<div class="mobile-bar">
    <button type="button" class="continue-btn" id="mobileContinueBtn" onclick="submitBookingStep3()">
        Continue to Payment <i class="fas fa-arrow-right"></i>
    </button>
</div>
 
<script>
const salonId = {{ $salon->id }};
const stylistId = {{ $stylist->id }};
const serviceId = {{ $service->id }};
 
let currentDate = new Date();
let selectedDate = null;
let selectedTime = null;
let isWaitlistSelected = false;
 
window.addEventListener('load', renderCalendar);
 
function toLocalDateStr(d) {
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${y}-${m}-${day}`;
}
 
function formatDateDisplay(dateStr) {
    const d = new Date(dateStr + 'T00:00:00');
    return d.toLocaleDateString('en-PK', { weekday: 'short', day: 'numeric', month: 'short' });
}
 
function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const first = new Date(year, month, 1);
    const last = new Date(year, month + 1, 0);
    const startDay = first.getDay();
    const totalDays = last.getDate();
 
    document.getElementById('currentMonth').textContent = first.toLocaleDateString('en-PK', { month: 'long', year: 'numeric' });
 
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    let html = '';
 
    for (let i = 0; i < startDay; i++) {
        html += `<div class="date-card disabled"><div class="day"></div><div class="num"></div><div class="month"></div></div>`;
    }
 
    for (let d = 1; d <= totalDays; d++) {
        const date = new Date(year, month, d);
        const isPast = date < today;
        const dateStr = toLocalDateStr(date);
        const dayName = date.toLocaleDateString('en-PK', { weekday: 'short' });
        const monName = date.toLocaleDateString('en-PK', { month: 'short' });
 
        html += `<div class="date-card${isPast ? ' disabled' : ''}" data-date="${dateStr}"
                     onclick="${isPast ? '' : `selectDate(this, '${dateStr}')`}">
                    <div class="day">${dayName}</div>
                    <div class="num">${d}</div>
                    <div class="month">${monName}</div>
                </div>`;
    }
 
    document.getElementById('dateGrid').innerHTML = html;
}
 
function changeMonth(dir) {
    currentDate.setMonth(currentDate.getMonth() + dir);
    renderCalendar();
}
 
function selectDate(card, date) {
    document.querySelectorAll('.date-card').forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');
    selectedDate = date;
    selectedTime = null;
    isWaitlistSelected = false;
 
    document.getElementById('waitlistCard').classList.remove('selected');
    document.getElementById('wlCheckBadge').textContent = 'Click to Select Waitlist';
    document.getElementById('wlCheckBadge').style.background = '#fff';
 
    document.getElementById('dateInput').value = date;
    document.getElementById('slotInput').value = '';
    document.getElementById('waitlistInput').value = '0';
 
    document.getElementById('sideDateText').textContent = formatDateDisplay(date);
    document.getElementById('sideTimeText').textContent = 'Select time slot or waitlist';
    document.getElementById('selectedDateBadge').textContent = formatDateDisplay(date);
 
    checkContinueButton();
    loadSlots(date);
}
 
function loadSlots(date) {
    const grid = document.getElementById('timeSlotGrid');
    grid.innerHTML = '<div style="grid-column:1/-1; text-align:center; color:#888; padding:20px 0;"><i class="fas fa-spinner fa-spin"></i> Checking slots...</div>';
 
    fetch(`{{ route('booking.slots', $salon->id) }}?stylist_id=${stylistId}&service_id=${serviceId}&date=${date}`, {
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(r => r.json())
    .then(data => {
        if (data.slots && data.slots.length > 0) {
            let html = '';
            data.slots.forEach(s => {
                const statusClass = s.available ? '' : 'booked';
                html += `<div class="time-slot ${statusClass}" data-time="${s.time}"
                             ${s.available ? `onclick="selectTime(this, '${s.time}')"` : ''}>
                            ${s.label}
                        </div>`;
            });
            grid.innerHTML = html;
        } else {
            grid.innerHTML = '<div style="grid-column:1/-1; text-align:center; color:#e11d48; padding:16px 0; font-size:0.82rem;"><i class="fas fa-info-circle"></i> All slots booked for this day. You can join the waitlist below!</div>';
        }
    })
    .catch(() => {
        // Sample slots fallback
        const sampleSlots = ['11:00 AM', '12:30 PM', '02:00 PM', '03:30 PM', '05:00 PM', '06:30 PM'];
        let html = '';
        sampleSlots.forEach((t, i) => {
            html += `<div class="time-slot ${i === 2 ? 'booked' : ''}" onclick="${i === 2 ? '' : `selectTime(this, '${t}')`}">
                        ${t}
                    </div>`;
        });
        grid.innerHTML = html;
    });
}
 
function selectTime(el, time) {
    document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
    el.classList.add('selected');
    selectedTime = time;
    isWaitlistSelected = false;
 
    document.getElementById('waitlistCard').classList.remove('selected');
    document.getElementById('wlCheckBadge').textContent = 'Click to Select Waitlist';
    document.getElementById('wlCheckBadge').style.background = '#fff';
 
    document.getElementById('slotInput').value = time;
    document.getElementById('waitlistInput').value = '0';
 
    document.getElementById('sideTimeText').textContent = time;
    checkContinueButton();
}
 
function toggleWaitlistSelection() {
    if (!selectedDate) {
        alert('Please select a date from the calendar first.');
        return;
    }
 
    isWaitlistSelected = !isWaitlistSelected;
    const card = document.getElementById('waitlistCard');
    const badge = document.getElementById('wlCheckBadge');
 
    if (isWaitlistSelected) {
        card.classList.add('selected');
        badge.textContent = '✓ Waitlist Selected';
        badge.style.background = '#d97706';
        badge.style.color = '#fff';
 
        document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
        selectedTime = null;
 
        document.getElementById('slotInput').value = '';
        document.getElementById('waitlistInput').value = '1';
        document.getElementById('sideTimeText').textContent = 'Priority Waitlist (20-min window)';
    } else {
        card.classList.remove('selected');
        badge.textContent = 'Click to Select Waitlist';
        badge.style.background = '#fff';
        badge.style.color = '#d97706';
 
        document.getElementById('waitlistInput').value = '0';
        document.getElementById('sideTimeText').textContent = 'Select time slot or waitlist';
    }
 
    checkContinueButton();
}
 
/**
 * UPDATED: previously showed "Join Waitlist" text and only activated the
 * button for one of the two paths. Now both paths (time slot selected OR
 * waitlist selected) activate the button with the SAME "Continue to
 * Payment" label, since both now lead to the payment step.
 */
function checkContinueButton() {
    const btn = document.getElementById('continueBtn');
    const mobBtn = document.getElementById('mobileContinueBtn');
 
    if (selectedDate && (selectedTime || isWaitlistSelected)) {
        btn.classList.add('active');
        btn.disabled = false;
        mobBtn.classList.add('active');
        mobBtn.disabled = false;
 
        btn.innerHTML = 'Continue to Payment <i class="fas fa-arrow-right"></i>';
        mobBtn.innerHTML = 'Continue to Payment <i class="fas fa-arrow-right"></i>';
    } else {
        btn.classList.remove('active');
        btn.disabled = true;
        mobBtn.classList.remove('active');
        mobBtn.disabled = true;
    }
}
 
function submitBookingStep3() {
    if (!selectedDate) {
        alert('Please select a date first.');
        return;
    }
    if (!selectedTime && !isWaitlistSelected) {
        alert('Please select an available time slot OR select Join Waitlist.');
        return;
    }
    document.getElementById('step3Form').submit();
}
</script>
 
</body>
</html>