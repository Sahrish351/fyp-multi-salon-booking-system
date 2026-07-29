<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Select date and time — {{ $salon->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        /* ============================================================ */
        /* GLOBAL */
        /* ============================================================ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f8f7fa; min-height: 100vh; -webkit-font-smoothing: antialiased; }

        /* ============================================================ */
        /* TOP NAV */
        /* ============================================================ */
        .top-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
            z-index: 200;
            background: #fff;
            border-bottom: 1px solid #f0e8ed;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        }
        .nav-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1.5px solid #e0e0e0;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.95rem;
            color: #1a1a1a;
            transition: all .3s ease;
            text-decoration: none;
        }
        .nav-btn:hover { border-color: #E91E8C; color: #E91E8C; transform: scale(1.05); }
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            color: #aaa;
            flex-wrap: wrap;
            justify-content: center;
        }
        .breadcrumb .bc-step { color: #aaa; }
        .breadcrumb .bc-step.active { color: #E91E8C; font-weight: 700; }
        .breadcrumb .bc-sep { color: #ccc; font-size: 0.6rem; }

        /* ============================================================ */
        /* MAIN LAYOUT */
        /* ============================================================ */
        .booking-wrapper {
            padding-top: 72px;
            max-width: 1100px;
            margin: 0 auto;
            padding-left: 24px;
            padding-right: 24px;
            padding-bottom: 100px;
            min-height: calc(100vh - 100px);
            overflow-x: hidden;
        }
        .booking-layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 32px;
            align-items: start;
        }
        @media(max-width:992px) {
            .booking-layout { grid-template-columns: 1fr; gap: 20px; }
        }

        /* ============================================================ */
        /* LEFT PANEL */
        /* ============================================================ */
        .left-panel {
            padding: 16px 0;
            overflow-x: hidden;
            width: 100%;
        }
        .left-panel h1 {
            font-size: 1.8rem;
            font-weight: 900;
            color: #1a1a1a;
            letter-spacing: -0.5px;
            margin-bottom: 14px;
        }

        /* Stylist Pill */
        .stylist-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #fff;
            border: 1.5px solid #f0e8ed;
            border-radius: 50px;
            padding: 6px 18px 6px 6px;
            margin-bottom: 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }
        .stylist-pill .av {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #E91E8C;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.7rem;
            font-weight: 700;
        }
        .stylist-pill .av img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; }
        .stylist-pill span { font-size: 0.82rem; font-weight: 600; color: #1a1a1a; }

        /* Month Navigation */
        .month-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
            flex-wrap: wrap;
            gap: 8px;
        }
        .month-nav button {
            background: #fff;
            border: 1.5px solid #e0e0e0;
            border-radius: 50px;
            padding: 6px 16px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all .3s ease;
            color: #1a1a1a;
        }
        .month-nav button:hover { border-color: #E91E8C; color: #E91E8C; background: #fff5f9; }
        .current-month { font-size: 0.95rem; font-weight: 700; color: #1a1a1a; }

        /* Date Grid */
        .weekday-row {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            margin-bottom: 6px;
        }
        .weekday {
            text-align: center;
            font-size: 0.6rem;
            font-weight: 700;
            color: #888;
            text-transform: uppercase;
            padding: 3px 0;
        }
        .date-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            margin-bottom: 20px;
        }
        .date-card {
            background: #fff;
            border: 1.5px solid #f0e8ed;
            border-radius: 10px;
            padding: 6px 3px;
            text-align: center;
            cursor: pointer;
            transition: all .25s ease;
        }
        .date-card:hover:not(.disabled) {
            border-color: #E91E8C;
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(233,30,140,0.06);
        }
        .date-card.selected {
            background: #E91E8C;
            border-color: #E91E8C;
        }
        .date-card.selected .day,
        .date-card.selected .num,
        .date-card.selected .month { color: #fff; }
        .date-card.disabled {
            opacity: 0.35;
            cursor: not-allowed;
            background: #f8f8f8;
        }
        .date-card .day { font-size: 0.5rem; color: #888; text-transform: uppercase; margin-bottom: 1px; }
        .date-card .num { font-size: 0.9rem; font-weight: 800; color: #1a1a1a; line-height: 1.1; }
        .date-card .month { font-size: 0.45rem; color: #888; margin-top: 1px; }

        /* Time Slots */
        .pick-time-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1a1a1a;
            margin: 14px 0 10px;
        }
        .time-slot-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }
        @media(max-width:768px) { .time-slot-grid { grid-template-columns: repeat(3, 1fr); } }
        @media(max-width:480px) { .time-slot-grid { grid-template-columns: repeat(2, 1fr); } }

        .time-slot {
            background: #fff;
            border: 1.5px solid #f0e8ed;
            border-radius: 10px;
            padding: 10px 8px;
            text-align: center;
            font-size: 0.82rem;
            font-weight: 500;
            color: #1a1a1a;
            cursor: pointer;
            transition: all .25s ease;
        }
        .time-slot:hover:not(.booked):not(.unavailable) {
            border-color: #E91E8C;
            background: #fff5f9;
            transform: translateY(-2px);
        }
        .time-slot.selected {
            background: #E91E8C;
            border-color: #E91E8C;
            color: #fff;
            font-weight: 700;
            box-shadow: 0 4px 16px rgba(233,30,140,0.15);
        }
        .time-slot.booked,
        .time-slot.unavailable {
            opacity: 0.4;
            cursor: not-allowed;
            background: #f8f8f8;
            position: relative;
        }
        .time-slot.booked::after {
            content: 'Booked';
            position: absolute;
            bottom: 2px;
            left: 0;
            right: 0;
            font-size: 0.45rem;
            color: #999;
        }
        .time-slot.unavailable::after {
            content: 'Unavailable';
            position: absolute;
            bottom: 2px;
            left: 0;
            right: 0;
            font-size: 0.45rem;
            color: #999;
        }
        .no-date-msg {
            grid-column: 1 / -1;
            text-align: center;
            color: #aaa;
            padding: 20px 0;
            font-size: 0.85rem;
        }
        .no-date-msg i { color: #E91E8C; margin-right: 6px; }

        /* ============================================================ */
        /* WAITLIST SECTION - ALWAYS VISIBLE */
        /* ============================================================ */
        .waitlist-section {
            margin-top: 20px;
            padding: 18px 22px;
            background: #fffbeb;
            border-radius: 14px;
            border: 1.5px solid #fcd34d;
            transition: all .3s ease;
        }
        .waitlist-section .wl-icon {
            font-size: 1.8rem;
            color: #f59e0b;
            display: block;
            margin-bottom: 6px;
        }
        .waitlist-section h4 {
            font-size: 0.9rem;
            font-weight: 800;
            color: #92400e;
            margin-bottom: 4px;
        }
        .waitlist-section p {
            font-size: 0.8rem;
            color: #78350f;
            margin-bottom: 12px;
            line-height: 1.6;
        }
        .waitlist-section .wl-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
        }
        .btn-waitlist {
            background: #f59e0b;
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 10px 22px;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .3s ease;
            font-family: 'Inter', sans-serif;
        }
        .btn-waitlist:hover:not(:disabled) {
            background: #d97706;
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(245,158,11,0.3);
        }
        .btn-waitlist:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .btn-waitlist i { margin-right: 6px; }
        .wl-hint {
            font-size: 0.7rem;
            color: #92400e;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* ============================================================ */
        /* SIDEBAR */
        /* ============================================================ */
        .sidebar {
            padding: 0;
            position: sticky;
            top: 80px;
            align-self: start;
        }
        @media(max-width:992px) {
            .sidebar {
                position: relative;
                top: 0;
                display: none;
            }
        }

        .salon-summary {
            background: #fff;
            border: 1.5px solid #f0e8ed;
            border-radius: 14px;
            padding: 14px 16px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .salon-summary img {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
        }
        .salon-summary .ss-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: #1a1a1a;
            line-height: 1.2;
        }
        .salon-summary .ss-addr {
            font-size: 0.65rem;
            color: #888;
        }
        .salon-summary .ss-addr i { color: #E91E8C; font-size: 0.55rem; }

        .booking-detail-box {
            background: #fff;
            border: 1.5px solid #f0e8ed;
            border-radius: 14px;
            padding: 14px 16px;
            margin-bottom: 14px;
        }
        .bd-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            color: #555;
            margin-bottom: 6px;
        }
        .bd-row i { width: 16px; color: #E91E8C; font-size: 0.85rem; }
        .bd-row .bd-label { font-weight: 600; color: #1a1a1a; }

        .svc-sum-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0 8px;
            border-top: 1px solid #f0f0f0;
            margin-top: 6px;
        }
        .svc-label { font-size: 0.85rem; font-weight: 700; color: #1a1a1a; }
        .svc-sub { font-size: 0.65rem; color: #888; margin-top: 2px; }
        .svc-price { font-weight: 700; color: #E91E8C; font-size: 0.85rem; }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding-top: 8px;
            border-top: 1px solid #f0f0f0;
            margin-top: 4px;
        }
        .total-row .total-label { font-weight: 700; color: #1a1a1a; font-size: 0.85rem; }
        .total-row .total-price { font-weight: 700; color: #E91E8C; font-size: 0.9rem; }

        .continue-btn {
            background: #e0e0e0;
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 14px 24px;
            font-size: 0.9rem;
            font-weight: 700;
            width: 100%;
            cursor: not-allowed;
            transition: all .3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: 'Inter', sans-serif;
        }
        .continue-btn.active {
            background: linear-gradient(135deg, #E91E8C, #c2185b);
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(233,30,140,0.15);
        }
        .continue-btn.active:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(233,30,140,0.25);
        }

        /* ============================================================ */
        /* MOBILE BAR */
        /* ============================================================ */
        .mobile-bar {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            border-top: 1px solid #f0e8ed;
            padding: 10px 16px;
            z-index: 100;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.04);
        }
        @media(max-width:992px) {
            .mobile-bar { display: block; }
            .booking-wrapper { padding-left: 16px; padding-right: 16px; }
            .left-panel h1 { font-size: 1.5rem; }
        }
        @media(max-width:576px) {
            .breadcrumb { font-size: 0.55rem; gap: 3px; }
            .top-nav { padding: 8px 12px; }
            .nav-btn { width: 34px; height: 34px; font-size: 0.8rem; }
            .left-panel h1 { font-size: 1.2rem; }
            .date-grid { gap: 4px; }
            .date-card { padding: 4px 2px; }
            .date-card .num { font-size: 0.75rem; }
            .time-slot-grid { grid-template-columns: repeat(2, 1fr); gap: 6px; }
            .time-slot { padding: 8px 6px; font-size: 0.7rem; }
            .booking-wrapper { padding-left: 12px; padding-right: 12px; padding-bottom: 75px; }
            .waitlist-section { padding: 14px 16px; }
            .waitlist-section h4 { font-size: 0.8rem; }
            .waitlist-section p { font-size: 0.7rem; }
            .btn-waitlist { padding: 8px 16px; font-size: 0.7rem; }
        }
    </style>
</head>
<body>

{{-- ============================================================ --}}
{{-- TOP NAV --}}
{{-- ============================================================ --}}
<div class="top-nav">
    <a href="{{ route('booking.step2', $salon->id) }}" class="nav-btn"><i class="fas fa-arrow-left"></i></a>
    <div class="breadcrumb">
        <span class="bc-step">Services</span>
        <span class="bc-sep">›</span>
        <span class="bc-step">Professional</span>
        <span class="bc-sep">›</span>
        <span class="bc-step active">Time</span>
        <span class="bc-sep">›</span>
        <span class="bc-step">Payment</span>
    </div>
    <a href="{{ route('salons.show', $salon->slug) }}" class="nav-btn"><i class="fas fa-times"></i></a>
</div>

{{-- ============================================================ --}}
{{-- MAIN CONTENT --}}
{{-- ============================================================ --}}
<div class="booking-wrapper">
    <div class="booking-layout">

        {{-- LEFT PANEL --}}
        <div class="left-panel">
            <h1>Select date and time</h1>

            @if($errors->any())
            <div style="background:#fff5f5; border:1px solid #fecaca; border-radius:10px; padding:10px 14px; font-size:0.78rem; color:#dc2626; margin-bottom:14px;">
                @foreach($errors->all() as $err)
                    <div>• {{ $err }}</div>
                @endforeach
            </div>
            @endif

            @if(session('error'))
            <div style="background:#fff5f5; border:1px solid #fecaca; border-radius:10px; padding:10px 14px; font-size:0.78rem; color:#dc2626; margin-bottom:14px;">
                {{ session('error') }}
            </div>
            @endif

            {{-- Stylist --}}
            <div class="stylist-pill">
                @if($stylist->avatar)
                    <div class="av"><img src="{{ asset('storage/'.$stylist->avatar) }}" alt="{{ $stylist->name }}"></div>
                @else
                    <div class="av">{{ substr($stylist->name, 0, 1) }}</div>
                @endif
                <span>{{ $stylist->name }}</span>
            </div>

            {{-- Month Navigation --}}
            <div class="month-nav">
                <button type="button" onclick="changeMonth(-1)"><i class="fas fa-chevron-left"></i> Previous</button>
                <span class="current-month" id="currentMonth"></span>
                <button type="button" onclick="changeMonth(1)">Next <i class="fas fa-chevron-right"></i></button>
            </div>

            {{-- Weekdays --}}
            <div class="weekday-row">
                @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $d)
                <div class="weekday">{{ $d }}</div>
                @endforeach
            </div>

            {{-- Dates --}}
            <div class="date-grid" id="dateGrid"></div>

            {{-- Time Slots --}}
            <div class="pick-time-title">Pick a time</div>
            <div class="time-slot-grid" id="timeSlotGrid">
                <div class="no-date-msg"><i class="fas fa-hand-point-up"></i> Select a date first</div>
            </div>

            {{-- ✅ WAITLIST SECTION --}}
            <div class="waitlist-section" id="waitlistSection">
                <span class="wl-icon"><i class="fas fa-clock"></i></span>
                <h4>Can't find a suitable time?</h4>
                <p>Join the waitlist and we'll notify you the moment a slot opens up for {{ $stylist->name }} on your selected date.</p>
                <div class="wl-actions">
                    <button type="button" class="btn-waitlist" id="waitlistBtn" onclick="joinWaitlist()" disabled>
                        <i class="fas fa-plus-circle"></i> Join Waitlist
                    </button>
                    <span class="wl-hint" id="waitlistHint">
                        <i class="fas fa-info-circle"></i> Select a date first
                    </span>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDEBAR --}}
        <div class="sidebar">
            <div class="salon-summary">
                @if($salon->cover_image)
                    <img src="{{ asset('storage/'.$salon->cover_image) }}" alt="{{ $salon->name }}" onerror="this.src='https://images.unsplash.com/photo-1560066984-138dadb4c035?w=200&q=70'">
                @else
                    <div style="width:48px;height:48px;border-radius:10px;background:#fce4ec;display:flex;align-items:center;justify-content:center;font-size:1.5rem;">💆</div>
                @endif
                <div>
                    <div class="ss-name">{{ $salon->name }}</div>
                    <div class="ss-addr"><i class="fas fa-map-marker-alt"></i> {{ $salon->city }}</div>
                </div>
            </div>

            <div class="booking-detail-box">
                <div class="bd-row" id="dateDisplay" style="display:none;">
                    <i class="fas fa-calendar"></i>
                    <span><span class="bd-label">Date:</span> <span id="dateText"></span></span>
                </div>
                <div class="bd-row" id="timeDisplay" style="display:none;">
                    <i class="fas fa-clock"></i>
                    <span><span class="bd-label">Time:</span> <span id="timeText"></span></span>
                </div>

                <div class="svc-sum-row">
                    <div>
                        <div class="svc-label">{{ $service->name }}</div>
                        <div class="svc-sub">{{ $service->duration_text ?? ($service->duration ?? 60).' min' }} with {{ $stylist->name }}</div>
                    </div>
                    <div class="svc-price">Rs. {{ number_format($service->price) }}</div>
                </div>
                <div class="total-row">
                    <span class="total-label">Total</span>
                    <span class="total-price">Rs. {{ number_format($service->price) }}</span>
                </div>
            </div>

            {{-- Desktop Form --}}
            <form action="{{ route('booking.step3.post', $salon->id) }}" method="POST" id="step3Form">
                @csrf
                <input type="hidden" name="time_slot_id" id="slotInput" value="">
                <input type="hidden" name="appointment_date" id="dateInput" value="">
                <input type="hidden" name="join_waitlist" id="waitlistInput" value="0">
                <button type="button" class="continue-btn" id="continueBtn" onclick="submitStep3()">
                    Continue <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>

    </div>
</div>

{{-- ============================================================ --}}
{{-- MOBILE BAR --}}
{{-- ============================================================ --}}
<div class="mobile-bar">
    <form action="{{ route('booking.step3.post', $salon->id) }}" method="POST" id="mobileStep3Form">
        @csrf
        <input type="hidden" name="time_slot_id" id="mobileSlotInput" value="">
        <input type="hidden" name="appointment_date" id="mobileDateInput" value="">
        <input type="hidden" name="join_waitlist" id="mobileWaitlistInput" value="0">
        <button type="button" class="continue-btn" id="mobileContinueBtn" onclick="submitMobileStep3()">
            Continue <i class="fas fa-arrow-right"></i>
        </button>
    </form>
</div>

<script>
// ============================================================
// CONFIG
// ============================================================
const salonId = {{ $salon->id }};
const stylistId = {{ $stylist->id }};
const serviceId = {{ $service->id }};

let currentDate = new Date();
let selectedDate = null;
let selectedTime = null;
let timeSlots = [];

window.addEventListener('load', renderCalendar);

// ============================================================
// HELPERS
// ============================================================
function toLocalDateStr(d) {
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${y}-${m}-${day}`;
}

function formatDateDisplay(dateStr) {
    const d = new Date(dateStr + 'T00:00:00');
    return d.toLocaleDateString('en-PK', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
}

// ============================================================
// CALENDAR
// ============================================================
function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const first = new Date(year, month, 1);
    const last = new Date(year, month + 1, 0);
    const startDay = first.getDay();
    const totalDays = last.getDate();

    document.getElementById('currentMonth').textContent =
        first.toLocaleDateString('en-PK', { month: 'long', year: 'numeric' });

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
    clearSelection();
}

function changeMonth(dir) {
    currentDate.setMonth(currentDate.getMonth() + dir);
    renderCalendar();
}

// ============================================================
// DATE SELECTION
// ============================================================
function selectDate(card, date) {
    document.querySelectorAll('.date-card').forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');
    selectedDate = date;
    selectedTime = null;

    document.getElementById('dateInput').value = date;
    document.getElementById('mobileDateInput').value = date;
    document.getElementById('slotInput').value = '';
    document.getElementById('mobileSlotInput').value = '';
    document.getElementById('waitlistInput').value = '0';
    document.getElementById('mobileWaitlistInput').value = '0';

    document.getElementById('continueBtn').classList.remove('active');
    document.getElementById('mobileContinueBtn').classList.remove('active');
    document.getElementById('timeDisplay').style.display = 'none';

    // Enable waitlist button
    document.getElementById('waitlistBtn').disabled = false;
    document.getElementById('waitlistHint').innerHTML = '<i class="fas fa-info-circle"></i> Click to join waitlist';

    document.getElementById('dateText').textContent = formatDateDisplay(date);
    document.getElementById('dateDisplay').style.display = 'flex';

    loadSlots(date);
}

// ============================================================
// TIME SLOTS
// ============================================================
function loadSlots(date) {
    const grid = document.getElementById('timeSlotGrid');
    grid.innerHTML = '<div class="no-date-msg"><i class="fas fa-spinner fa-pulse"></i> Loading slots...</div>';

    fetch(`{{ route('booking.slots', $salon->id) }}?stylist_id=${stylistId}&service_id=${serviceId}&date=${date}`, {
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(r => r.json())
    .then(data => {
        if (data.holiday) {
            grid.innerHTML = '<div class="no-date-msg" style="color:#f97316;"><i class="fas fa-umbrella-beach"></i> Stylist is on holiday this day.</div>';
            return;
        }

        if (!data.slots || data.slots.length === 0) {
            grid.innerHTML = '<div class="no-date-msg"><i class="fas fa-clock"></i> No slots available on this date.</div>';
            return;
        }

        timeSlots = data.slots;
        let html = '';
        let anyAvail = false;

        data.slots.forEach(s => {
            if (s.available) anyAvail = true;
            const statusClass = s.available ? '' : (s.booked ? 'booked' : 'unavailable');
            html += `<div class="time-slot ${statusClass}" data-time="${s.time}" data-available="${s.available}"
                         ${s.available ? `onclick="selectTime(this, '${s.time}')"` : ''}>
                        ${s.label}
                    </div>`;
        });

        grid.innerHTML = html;

        if (!anyAvail) {
            document.getElementById('waitlistHint').innerHTML = '<i class="fas fa-exclamation-circle"></i> All slots booked - join waitlist';
        }
    })
    .catch(() => {
        grid.innerHTML = '<div class="no-date-msg"><i class="fas fa-exclamation-circle"></i> Error loading slots. Please refresh.</div>';
    });
}

// ============================================================
// TIME SELECTION
// ============================================================
function selectTime(el, time) {
    document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
    el.classList.add('selected');
    selectedTime = time;

    document.getElementById('slotInput').value = time;
    document.getElementById('mobileSlotInput').value = time;
    document.getElementById('waitlistInput').value = '0';
    document.getElementById('mobileWaitlistInput').value = '0';

    const timeDisplay = document.getElementById('timeText');
    timeDisplay.textContent = time + ' ({{ $service->duration_text ?? ($service->duration ?? 60).' min' }})';
    document.getElementById('timeDisplay').style.display = 'flex';

    document.getElementById('continueBtn').classList.add('active');
    document.getElementById('mobileContinueBtn').classList.add('active');
}

// ============================================================
// SUBMIT
// ============================================================
function submitStep3() {
    if (!selectedDate) { alert('Please select a date first.'); return; }
    if (!selectedTime) { alert('Please select a time slot.'); return; }
    document.getElementById('step3Form').submit();
}

function submitMobileStep3() {
    if (!selectedDate) { alert('Please select a date first.'); return; }
    if (!selectedTime) { alert('Please select a time slot.'); return; }
    document.getElementById('mobileStep3Form').submit();
}

// ============================================================
// WAITLIST
// ============================================================
function joinWaitlist() {
    if (!selectedDate) {
        alert('Please select a date first.');
        return;
    }

    if (confirm('Are you sure you want to join the waitlist for ' + formatDateDisplay(selectedDate) + '? We will notify you when a slot becomes available.')) {
        document.getElementById('waitlistInput').value = '1';
        document.getElementById('mobileWaitlistInput').value = '1';
        document.getElementById('dateInput').value = selectedDate;
        document.getElementById('mobileDateInput').value = selectedDate;
        document.getElementById('slotInput').value = 'waitlist';
        document.getElementById('mobileSlotInput').value = 'waitlist';

        document.getElementById('continueBtn').classList.add('active');
        document.getElementById('mobileContinueBtn').classList.add('active');

        document.getElementById('step3Form').submit();
    }
}

// ============================================================
// CLEAR
// ============================================================
function clearSelection() {
    selectedDate = null;
    selectedTime = null;
    document.getElementById('slotInput').value = '';
    document.getElementById('mobileSlotInput').value = '';
    document.getElementById('dateInput').value = '';
    document.getElementById('mobileDateInput').value = '';
    document.getElementById('waitlistInput').value = '0';
    document.getElementById('mobileWaitlistInput').value = '0';
    document.getElementById('continueBtn').classList.remove('active');
    document.getElementById('mobileContinueBtn').classList.remove('active');
    document.getElementById('dateDisplay').style.display = 'none';
    document.getElementById('timeDisplay').style.display = 'none';

    const grid = document.getElementById('timeSlotGrid');
    grid.innerHTML = '<div class="no-date-msg"><i class="fas fa-hand-point-up"></i> Select a date first</div>';

    document.getElementById('waitlistBtn').disabled = true;
    document.getElementById('waitlistHint').innerHTML = '<i class="fas fa-info-circle"></i> Select a date first';
}
</script>

</body>
</html>