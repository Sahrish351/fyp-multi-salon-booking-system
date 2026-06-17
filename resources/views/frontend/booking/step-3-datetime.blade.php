
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
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; background: #f5f5f5; min-height: 100vh; -webkit-font-smoothing: antialiased; }
    .top-nav { position: fixed; top: 0; left: 0; right: 0; display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; z-index: 200; background: #f5f5f5; }
    .nav-btn { width: 44px; height: 44px; border-radius: 50%; border: 1.5px solid #e0e0e0; background: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 1rem; color: #1a1a1a; transition: all .15s; text-decoration: none; }
    .nav-btn:hover { border-color: #1a1a1a; }
    .booking-layout { display: grid; grid-template-columns: 1fr 360px; gap: 0; max-width: 1200px; margin: 0 auto; padding: 0 24px 100px; }
    @media(max-width:900px) { .booking-layout { grid-template-columns: 1fr; } .sidebar { display: none; } }
    .left-panel { padding: 24px 40px 24px 0; }
    h1 { font-size: 2rem; font-weight: 900; color: #1a1a1a; letter-spacing: -1px; margin-bottom: 20px; }
    .stylist-row { margin-bottom: 24px; }
    .stylist-pill { display: inline-flex; align-items: center; gap: 10px; background: #fff; border: 1.5px solid #e8e8e8; border-radius: 50px; padding: 8px 20px 8px 12px; }
    .stylist-pill .av { width: 32px; height: 32px; border-radius: 50%; background: #E91E8C; display: flex; align-items: center; justify-content: center; color: #fff; font-size: .8rem; font-weight: 700; }
    .stylist-pill img { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; }
    .stylist-pill span { font-size: .9rem; font-weight: 600; color: #1a1a1a; }
    .month-nav { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
    .month-nav button { background: #fff; border: 1.5px solid #e0e0e0; border-radius: 50px; padding: 8px 16px; font-size: .85rem; font-weight: 600; cursor: pointer; transition: all .15s; }
    .month-nav button:hover { border-color: #E91E8C; color: #E91E8C; }
    .current-month { font-size: 1.1rem; font-weight: 700; color: #1a1a1a; }
    .weekday-row { display: grid; grid-template-columns: repeat(7,1fr); gap: 8px; margin-bottom: 10px; }
    .weekday { text-align: center; font-size: .7rem; font-weight: 600; color: #888; text-transform: uppercase; padding: 5px 0; }
    .date-grid { display: grid; grid-template-columns: repeat(7,1fr); gap: 8px; margin-bottom: 30px; }
    .date-card { background: #fff; border: 1.5px solid #e8e8e8; border-radius: 12px; padding: 10px 4px; text-align: center; cursor: pointer; transition: all .2s; }
    .date-card:hover:not(.disabled) { border-color: #E91E8C; transform: translateY(-2px); }
    .date-card.selected { background: #E91E8C; border-color: #E91E8C; }
    .date-card.selected .day, .date-card.selected .num, .date-card.selected .month { color: #fff; }
    .date-card.disabled { opacity: .4; cursor: not-allowed; background: #f5f5f5; }
    .date-card .day { font-size: .62rem; color: #888; text-transform: uppercase; margin-bottom: 4px; }
    .date-card .num { font-size: 1rem; font-weight: 800; color: #1a1a1a; line-height: 1; margin-bottom: 2px; }
    .date-card .month { font-size: .58rem; color: #888; }
    .pick-time-title { font-size: 1rem; font-weight: 700; color: #1a1a1a; margin: 20px 0 16px; }
    .time-slot-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 12px; }
    @media(max-width:768px){ .time-slot-grid{ grid-template-columns:repeat(3,1fr); } }
    @media(max-width:480px){ .time-slot-grid{ grid-template-columns:repeat(2,1fr); } }
    .time-slot { background: #fff; border: 1.5px solid #e8e8e8; border-radius: 12px; padding: 12px 8px; text-align: center; font-size: .85rem; font-weight: 500; color: #1a1a1a; cursor: pointer; transition: all .2s; }
    .time-slot:hover:not(.booked) { border-color: #E91E8C; background: #fff5f9; }
    .time-slot.selected { background: #E91E8C; border-color: #E91E8C; color: #fff; font-weight: 700; }
    .time-slot.booked { opacity: .4; cursor: not-allowed; background: #f5f5f5; position: relative; }
    .time-slot.booked::after { content: 'Booked'; position: absolute; bottom: 2px; left: 0; right: 0; font-size: .55rem; color: #999; }
    .sidebar { padding: 24px 0 24px 32px; border-left: 1px solid #e8e8e8; }
    .salon-summary { background: #fff; border: 1.5px solid #e8e8e8; border-radius: 16px; padding: 16px; margin-bottom: 16px; display: flex; align-items: center; gap: 12px; }
    .salon-summary img { width: 56px; height: 56px; border-radius: 10px; object-fit: cover; }
    .ss-name { font-size: .95rem; font-weight: 700; color: #1a1a1a; }
    .ss-addr { font-size: .72rem; color: #888; }
    .booking-detail-box { background: #fff; border: 1.5px solid #e8e8e8; border-radius: 16px; padding: 16px; margin-bottom: 16px; }
    .bd-row { display: flex; align-items: center; gap: 10px; font-size: .85rem; color: #555; margin-bottom: 10px; }
    .bd-row i { width: 20px; color: #E91E8C; }
    .svc-sum-row { display: flex; justify-content: space-between; padding: 12px 0 8px; border-top: 1px solid #f0f0f0; margin-top: 8px; }
    .svc-label { font-size: .9rem; font-weight: 700; color: #1a1a1a; }
    .svc-sub   { font-size: .75rem; color: #888; margin-top: 2px; }
    .svc-price { font-weight: 700; color: #E91E8C; font-size: .9rem; }
    .total-row { display: flex; justify-content: space-between; padding-top: 10px; border-top: 1px solid #f0f0f0; margin-top: 6px; }
    .total-row span { font-weight: 700; color: #1a1a1a; font-size: 1rem; }
    .continue-btn { background: #aaa; color: #fff; border: none; border-radius: 50px; padding: 14px 28px; font-size: .95rem; font-weight: 700; width: 100%; cursor: not-allowed; transition: all .2s; display: flex; align-items: center; justify-content: center; gap: 8px; font-family: 'Inter',sans-serif; }
    .continue-btn.active { background: #E91E8C; cursor: pointer; }
    .continue-btn.active:hover { background: #c2185b; transform: translateY(-1px); }
    .waitlist-section { margin-top: 24px; padding: 20px; background: #fef3c7; border-radius: 16px; text-align: center; display: none; }
    .waitlist-section.show { display: block; }
    .waitlist-section h4 { font-size: 1rem; font-weight: 700; color: #f97316; margin-bottom: 6px; }
    .waitlist-section p { font-size: .8rem; color: #92400e; margin-bottom: 14px; line-height: 1.5; }
    .btn-waitlist { background: #f97316; color: #fff; border: none; border-radius: 50px; padding: 11px 22px; font-size: .85rem; font-weight: 700; cursor: pointer; transition: all .15s; font-family: 'Inter',sans-serif; }
    .btn-waitlist:hover { background: #ea580c; transform: translateY(-1px); }
    .no-date-msg { grid-column: 1/-1; text-align: center; color: #aaa; padding: 20px; }
    </style>
</head>
<body>

<div class="top-nav">
    <a href="{{ route('booking.step2', $salon->id) }}" class="nav-btn"><i class="fas fa-arrow-left"></i></a>
    <div style="display:flex;align-items:center;gap:8px;font-size:.82rem;color:#aaa;">
        <span>Services</span><span style="color:#ccc;">›</span>
        <span>Professional</span><span style="color:#ccc;">›</span>
        <span style="color:#1a1a1a;font-weight:700;">Time</span><span style="color:#ccc;">›</span>
        <span>Payment</span>
    </div>
    <a href="{{ route('salons.show', $salon->slug) }}" class="nav-btn"><i class="fas fa-times"></i></a>
</div>

<div style="padding-top:72px;">
    <div class="booking-layout">

        <!-- LEFT PANEL -->
        <div class="left-panel">
            <h1>Select date and time</h1>

            <div class="stylist-row">
                <div class="stylist-pill">
                    @if($stylist->photo)
                        <img src="{{ asset('storage/'.$stylist->photo) }}" onerror="this.style.display='none'">
                    @else
                        <div class="av">{{ substr($stylist->name,0,1) }}</div>
                    @endif
                    <span>{{ $stylist->name }}</span>
                </div>
            </div>

            <div class="month-nav">
                <button type="button" onclick="changeMonth(-1)"><i class="fas fa-chevron-left"></i> Previous</button>
                <span class="current-month" id="currentMonth"></span>
                <button type="button" onclick="changeMonth(1)">Next <i class="fas fa-chevron-right"></i></button>
            </div>

            <div class="weekday-row">
                @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $d)
                <div class="weekday">{{ $d }}</div>
                @endforeach
            </div>

            <div class="date-grid" id="dateGrid"></div>

            <div class="pick-time-title">Pick a time</div>
            <div class="time-slot-grid" id="timeSlotGrid">
                <div class="no-date-msg"><i class="fas fa-hand-point-up"></i> Select a date first</div>
            </div>

            {{-- ✅ WAITLIST: shown when no slots are available for chosen date --}}
            <div class="waitlist-section" id="waitlistSection">
                <i class="fas fa-list-ol fa-2x" style="color:#f97316;margin-bottom:10px;display:block;"></i>
                <h4>No available slots on this date</h4>
                <p>All time slots for {{ $stylist->name }} are booked. Join the waitlist and we'll notify you the moment a slot opens up!</p>
                <button type="button" class="btn-waitlist" onclick="joinWaitlist()">
                    <i class="fas fa-plus-circle"></i> Join Waitlist for This Date
                </button>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="salon-summary">
                @if($salon->cover_photo)
                <img src="{{ asset('storage/'.$salon->cover_photo) }}" alt="{{ $salon->name }}" onerror="this.src='https://images.unsplash.com/photo-1560066984-138dadb4c035?w=200&q=70'">
                @else
                <div style="width:56px;height:56px;border-radius:10px;background:#fce4ec;display:flex;align-items:center;justify-content:center;font-size:1.5rem;">💆</div>
                @endif
                <div>
                    <div class="ss-name">{{ $salon->name }}</div>
                    <div class="ss-addr"><i class="fas fa-map-marker-alt" style="color:#E91E8C;font-size:.65rem;"></i> {{ $salon->city }}</div>
                </div>
            </div>

            <div class="booking-detail-box">
                <div class="bd-row" id="dateDisplay" style="display:none;">
                    <i class="fas fa-calendar"></i>
                    <span id="dateText"></span>
                </div>
                <div class="bd-row" id="timeDisplay" style="display:none;">
                    <i class="fas fa-clock"></i>
                    <span id="timeText"></span>
                </div>
                <div class="svc-sum-row">
                    <div>
                        <div class="svc-label">{{ $service->name }}</div>
                        <div class="svc-sub">{{ $service->duration_minutes ?? 60 }} min with {{ $stylist->name }}</div>
                    </div>
                    <div class="svc-price">Rs.{{ number_format($service->price) }}</div>
                </div>
                <div class="total-row">
                    <span>Total</span>
                    <span>Rs.{{ number_format($service->price) }}</span>
                </div>
            </div>

            {{-- ✅ FIXED: route() helper used (not hardcoded url()) so it always
                 matches the named route regardless of prefix changes --}}
            <form action="{{ route('booking.step3.post', $salon->id) }}" method="POST" id="step3Form">
                @csrf
                <input type="hidden" name="time_slot_id"     id="slotInput" value="">
                <input type="hidden" name="appointment_date" id="dateInput" value="">
                <input type="hidden" name="join_waitlist"    id="waitlistInput" value="0">
                <button type="button" class="continue-btn" id="continueBtn" onclick="submitStep3()">
                    Continue <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
const salonId   = {{ $salon->id }};
const stylistId = {{ $stylist->id }};
const serviceId = {{ $service->id }};

let currentDate  = new Date();
let selectedDate = null;
let selectedTime = null;

window.addEventListener('load', renderCalendar);

function renderCalendar() {
    const year  = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const first = new Date(year, month, 1);
    const last  = new Date(year, month+1, 0);
    const startDay  = first.getDay();
    const totalDays = last.getDate();

    document.getElementById('currentMonth').textContent =
        first.toLocaleDateString('en-PK', { month:'long', year:'numeric' });

    const today = new Date(); today.setHours(0,0,0,0);
    let html = '';

    for (let i = 0; i < startDay; i++) {
        html += `<div class="date-card disabled"><div class="day"></div><div class="num"></div><div class="month"></div></div>`;
    }
    for (let d = 1; d <= totalDays; d++) {
        const date    = new Date(year, month, d);
        const isPast  = date < today;
        const dateStr = date.toISOString().split('T')[0];
        const dayName = date.toLocaleDateString('en-PK', { weekday:'short' });
        const monName = date.toLocaleDateString('en-PK', { month:'short' });
        html += `<div class="date-card${isPast?' disabled':''}" data-date="${dateStr}"
                     onclick="${isPast?'':` selectDate(this,'${dateStr}')`}">
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
    clearSelection();
}

function selectDate(card, date) {
    document.querySelectorAll('.date-card').forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');
    selectedDate = date;
    selectedTime = null;
    document.getElementById('slotInput').value = '';
    document.getElementById('dateInput').value = date;
    document.getElementById('continueBtn').classList.remove('active');
    document.getElementById('timeDisplay').style.display = 'none';
    document.getElementById('waitlistSection').classList.remove('show');
    document.getElementById('dateText').textContent =
        new Date(date).toLocaleDateString('en-PK', { weekday:'long', day:'numeric', month:'long' });
    document.getElementById('dateDisplay').style.display = 'flex';
    loadSlots(date);
}

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
            document.getElementById('waitlistSection').classList.add('show');
            return;
        }
        if (!data.slots || data.slots.length === 0) {
            grid.innerHTML = '<div class="no-date-msg">No slots configured for this date.</div>';
            document.getElementById('waitlistSection').classList.add('show');
            return;
        }
        let html = '';
        let anyAvail = false;
        data.slots.forEach(s => {
            if (s.available) anyAvail = true;
            html += `<div class="time-slot${s.available?'':' booked'}"
                         data-time="${s.time}"
                         ${s.available?`onclick="selectTime(this,'${s.time}')"`:''}>${s.label}</div>`;
        });
        grid.innerHTML = html;
        
        document.getElementById('waitlistSection').classList.toggle('show', !anyAvail);
    })
    .catch(() => {
        showStaticSlots();
    });
}

function showStaticSlots() {
    const slots = ['09:00 AM','09:30 AM','10:00 AM','10:30 AM','11:00 AM','11:30 AM',
                   '12:00 PM','12:30 PM','01:00 PM','01:30 PM','02:00 PM','02:30 PM',
                   '03:00 PM','03:30 PM','04:00 PM','04:30 PM','05:00 PM','05:30 PM',
                   '06:00 PM','06:30 PM','07:00 PM','07:30 PM','08:00 PM'];
    let html = '';
    slots.forEach(s => {
        html += `<div class="time-slot" data-time="${s}" onclick="selectTime(this,'${s}')">${s}</div>`;
    });
    document.getElementById('timeSlotGrid').innerHTML = html;
}

function selectTime(el, time) {
    document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
    el.classList.add('selected');
    selectedTime = time;
    document.getElementById('slotInput').value = time;
    document.getElementById('waitlistInput').value = '0';
    document.getElementById('timeText').textContent = time + ' ({{ $service->duration_minutes ?? 60 }} min)';
    document.getElementById('timeDisplay').style.display = 'flex';
    document.getElementById('continueBtn').classList.add('active');
}

function submitStep3() {
    if (!selectedDate) { alert('Please select a date first.'); return; }
    if (!selectedTime) { alert('Please select a time slot.'); return; }
    document.getElementById('slotInput').value = selectedTime;
    document.getElementById('dateInput').value = selectedDate;
    document.getElementById('step3Form').submit();
}


function joinWaitlist() {
    if (!selectedDate) { alert('Please select a date first'); return; }
    document.getElementById('waitlistInput').value = '1';
    document.getElementById('dateInput').value = selectedDate;
    document.getElementById('slotInput').value = 'waitlist';
    document.getElementById('step3Form').submit();
}

function clearSelection() {
    selectedDate = null;
    selectedTime = null;
    document.getElementById('slotInput').value = '';
    document.getElementById('dateInput').value = '';
    document.getElementById('continueBtn').classList.remove('active');
    document.getElementById('dateDisplay').style.display = 'none';
    document.getElementById('timeDisplay').style.display = 'none';
    document.getElementById('timeSlotGrid').innerHTML =
        '<div class="no-date-msg"><i class="fas fa-hand-point-up"></i> Select a date first</div>';
    document.getElementById('waitlistSection').classList.remove('show');
}
</script>
</body>
</html>