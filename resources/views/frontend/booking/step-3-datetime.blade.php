
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
    
    /* Top Nav */
    .top-nav { position: fixed; top: 0; left: 0; right: 0; display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; z-index: 200; background: #f5f5f5; }
    .nav-btn { width: 44px; height: 44px; border-radius: 50%; border: 1.5px solid #e0e0e0; background: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 1rem; color: #1a1a1a; transition: all .15s; text-decoration: none; }
    .nav-btn:hover { border-color: #1a1a1a; }
    
    /* Layout */
    .booking-layout { display: grid; grid-template-columns: 1fr 360px; gap: 0; max-width: 1200px; margin: 0 auto; padding: 0 24px 100px; }
    @media(max-width:900px) { .booking-layout { grid-template-columns: 1fr; } .sidebar { display: none; } }
    .left-panel { padding: 24px 40px 24px 0; }
    h1 { font-size: 2rem; font-weight: 900; color: #1a1a1a; letter-spacing: -1px; margin-bottom: 20px; }
    
    /* Stylist row */
    .stylist-row { margin-bottom: 24px; }
    .stylist-pill { display: inline-flex; align-items: center; gap: 10px; background: #fff; border: 1.5px solid #e8e8e8; border-radius: 50px; padding: 8px 20px 8px 12px; }
    .stylist-pill img { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; }
    .stylist-pill span { font-size: 0.9rem; font-weight: 600; color: #1a1a1a; }
    
    /* Month Navigation */
    .month-nav { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
    .month-nav button { background: #fff; border: 1.5px solid #e0e0e0; border-radius: 50px; padding: 8px 16px; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all .15s; }
    .month-nav button:hover { border-color: #E91E8C; color: #E91E8C; }
    .current-month { font-size: 1.1rem; font-weight: 700; color: #1a1a1a; }
    
    /* Date Grid - Full Month */
    .date-section-title { font-size: 1rem; font-weight: 700; color: #1a1a1a; margin-bottom: 16px; }
    .date-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; margin-bottom: 30px; }
    @media(max-width: 768px) { .date-grid { grid-template-columns: repeat(7, 1fr); gap: 5px; } }
    
    .date-card {
        background: #fff;
        border: 1.5px solid #e8e8e8;
        border-radius: 12px;
        padding: 10px 4px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .date-card:hover { border-color: #E91E8C; transform: translateY(-2px); }
    .date-card.selected { background: #E91E8C; border-color: #E91E8C; }
    .date-card.selected .day, 
    .date-card.selected .num, 
    .date-card.selected .month { color: #fff; }
    .date-card.disabled { opacity: 0.4; cursor: not-allowed; background: #f5f5f5; }
    .date-card.disabled:hover { transform: none; border-color: #e8e8e8; }
    
    .date-card .day { font-size: 0.65rem; color: #888; text-transform: uppercase; margin-bottom: 4px; }
    .date-card .num { font-size: 1rem; font-weight: 800; color: #1a1a1a; line-height: 1; margin-bottom: 2px; }
    .date-card .month { font-size: 0.6rem; color: #888; }
    
    /* Weekday Headers */
    .weekday-row { display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; margin-bottom: 10px; }
    .weekday { text-align: center; font-size: 0.7rem; font-weight: 600; color: #888; text-transform: uppercase; padding: 5px 0; }
    
    /* Time Slots - More Options */
    .pick-time-title { font-size: 1rem; font-weight: 700; color: #1a1a1a; margin: 20px 0 16px; display: flex; align-items: center; justify-content: space-between; }
    .time-slot-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
    @media(max-width: 768px) { .time-slot-grid { grid-template-columns: repeat(3, 1fr); } }
    @media(max-width: 480px) { .time-slot-grid { grid-template-columns: repeat(2, 1fr); } }
    
    .time-slot {
        background: #fff;
        border: 1.5px solid #e8e8e8;
        border-radius: 12px;
        padding: 12px 8px;
        text-align: center;
        font-size: 0.85rem;
        font-weight: 500;
        color: #1a1a1a;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .time-slot:hover { border-color: #E91E8C; background: #fff5f9; }
    .time-slot.selected { background: #E91E8C; border-color: #E91E8C; color: #fff; }
    .time-slot.booked { opacity: 0.4; cursor: not-allowed; background: #f5f5f5; }
    .time-slot.booked:hover { border-color: #e8e8e8; background: #f5f5f5; }
    
    /* Sidebar */
    .sidebar { padding: 24px 0 24px 32px; border-left: 1px solid #e8e8e8; }
    .salon-summary { background: #fff; border: 1.5px solid #e8e8e8; border-radius: 16px; padding: 16px; margin-bottom: 16px; display: flex; align-items: center; gap: 12px; }
    .salon-summary img { width: 56px; height: 56px; border-radius: 10px; object-fit: cover; }
    .salon-summary .ss-name { font-size: 0.95rem; font-weight: 700; color: #1a1a1a; }
    .salon-summary .ss-rating { font-size: 0.78rem; color: #555; }
    .salon-summary .stars { color: #ffc107; }
    .salon-summary .ss-addr { font-size: 0.72rem; color: #888; }
    
    .booking-detail-box { background: #fff; border: 1.5px solid #e8e8e8; border-radius: 16px; padding: 16px; margin-bottom: 16px; }
    .bd-row { display: flex; align-items: center; gap: 10px; font-size: 0.85rem; color: #555; margin-bottom: 12px; }
    .bd-row i { width: 20px; color: #E91E8C; }
    .svc-sum-row { display: flex; justify-content: space-between; padding: 12px 0 8px; border-top: 1px solid #f0f0f0; margin-top: 8px; }
    .svc-sum-row .label { font-size: 0.9rem; font-weight: 700; color: #1a1a1a; }
    .svc-sum-row .sub { font-size: 0.75rem; color: #888; margin-top: 2px; }
    .svc-sum-row .price { font-weight: 700; color: #E91E8C; font-size: 0.9rem; }
    .total-row { display: flex; justify-content: space-between; padding-top: 10px; border-top: 1px solid #f0f0f0; margin-top: 6px; }
    .total-row span { font-weight: 700; color: #1a1a1a; font-size: 1rem; }
    
    .continue-btn { background: #aaa; color: #fff; border: none; border-radius: 50px; padding: 14px 28px; font-size: 0.95rem; font-weight: 700; width: 100%; cursor: not-allowed; transition: all .2s; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .continue-btn.active { background: #E91E8C; cursor: pointer; }
    .continue-btn.active:hover { background: #c2185b; transform: translateY(-1px); }
    
    /* Waitlist Section */
    .waitlist-section { margin-top: 30px; padding: 20px; background: #fef3c7; border-radius: 16px; text-align: center; }
    .waitlist-section h4 { font-size: 1rem; font-weight: 700; color: #f97316; margin-bottom: 8px; }
    .waitlist-section p { font-size: 0.8rem; color: #888; margin-bottom: 12px; }
    .btn-waitlist { background: #f97316; color: #fff; border: none; border-radius: 50px; padding: 10px 20px; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all .15s; }
    .btn-waitlist:hover { background: #ea580c; transform: translateY(-1px); }
    
    /* Loading */
    .loading-state { text-align: center; padding: 40px; color: #888; }
    .fully-booked-state { text-align: center; padding: 40px; }
    .fb-icon { font-size: 3rem; margin-bottom: 16px; }
    .fb-actions { display: flex; gap: 12px; justify-content: center; margin-top: 20px; flex-wrap: wrap; }
    </style>
</head>
<body>
    <div class="top-nav">
        <a href="{{ route('booking.step2', $salon->id) }}" class="nav-btn"><i class="fas fa-arrow-left"></i></a>
        <div style="display:flex;align-items:center;gap:8px;font-size:0.82rem;color:#aaa;">
            <span>Services</span><span style="color:#ccc;">›</span>
            <span>Professional</span><span style="color:#ccc;">›</span>
            <span style="color:#1a1a1a;font-weight:700;">Time</span><span style="color:#ccc;">›</span>
            <span>Confirm</span>
        </div>
        <a href="{{ route('salons.show', $salon->slug) }}" class="nav-btn"><i class="fas fa-times"></i></a>
    </div>
 
    <div style="padding-top:72px;">
        <div class="booking-layout">
            <div class="left-panel">
                <h1>Select date and time</h1>
 
                <!-- Stylist pill -->
                <div class="stylist-row">
                    <div class="stylist-pill">
                        @if($stylist->avatar)
                        <img src="{{ $stylist->avatar_url }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($stylist->name) }}&background=E91E8C&color=fff'">
                        @else
                        <div style="width:32px;height:32px;border-radius:50%;background:#E91E8C;display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.8rem;font-weight:700;">{{ substr($stylist->name,0,1) }}</div>
                        @endif
                        <span>{{ $stylist->name }}</span>
                    </div>
                </div>
 
                <!-- Month Navigation -->
                <div class="month-nav">
                    <button onclick="changeMonth(-1)"><i class="fas fa-chevron-left"></i> Previous</button>
                    <span class="current-month" id="currentMonth"></span>
                    <button onclick="changeMonth(1)">Next <i class="fas fa-chevron-right"></i></button>
                </div>
 
                <!-- Weekday Headers -->
                <div class="weekday-row">
                    <div class="weekday">Sun</div>
                    <div class="weekday">Mon</div>
                    <div class="weekday">Tue</div>
                    <div class="weekday">Wed</div>
                    <div class="weekday">Thu</div>
                    <div class="weekday">Fri</div>
                    <div class="weekday">Sat</div>
                </div>
 
                <!-- Date Grid -->
                <div class="date-grid" id="dateGrid"></div>
 
                <!-- Time Slots -->
                <div class="pick-time-title">
                    <span>Pick a time</span>
                </div>
                <div class="time-slot-grid" id="timeSlotGrid">
                    <div style="grid-column: 1/-1; text-align: center; color: #aaa; padding: 20px;">
                        <i class="fas fa-spinner fa-pulse"></i> Loading available slots...
                    </div>
                </div>
 
                <!-- Waitlist Section -->
                <div class="waitlist-section" id="waitlistSection" style="display: none;">
                    <i class="fas fa-list-ol fa-2x" style="color: #f97316;"></i>
                    <h4>No available slots?</h4>
                    <p>Join the waitlist and get notified when a slot opens up</p>
                    <button class="btn-waitlist" onclick="joinWaitlist()">
                        <i class="fas fa-plus-circle me-2"></i>Join Waitlist
                    </button>
                </div>
            </div>
 
            <!-- SIDEBAR -->
            <div class="sidebar">
                <div class="salon-summary">
                    <img src="{{ $salon->cover_url }}" alt="{{ $salon->name }}" onerror="this.src='https://images.unsplash.com/photo-1560066984-138dadb4c035?w=200&q=70'">
                    <div>
                        <div class="ss-name">{{ $salon->name }}</div>
                        <div class="ss-rating"><span class="stars">★★★★★</span> {{ number_format($salon->rating,1) }} <span style="color:#aaa;">({{ $salon->total_reviews * 10 + 250 }})</span></div>
                        <div class="ss-addr">{{ Str::limit($salon->address,35) }}</div>
                    </div>
                </div>
 
                <div class="booking-detail-box" id="bookingDetail">
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
                            <div class="label">{{ $service->name }}</div>
                            <div class="sub">{{ $service->duration_text }} with {{ $stylist->name }}</div>
                        </div>
                        <div class="price">Rs. {{ number_format($service->price) }}</div>
                    </div>
                    <div class="total-row">
                        <span>Total</span>
                        <span>Rs. {{ number_format($service->price) }}</span>
                    </div>
                </div>
 
                <form action="{{ route('booking.step3.post', $salon->id) }}" method="POST" id="step3Form">
                    @csrf
                    <input type="hidden" name="time_slot_id" id="slotInput">
                    <button type="button" class="continue-btn" id="continueBtn" onclick="submitBooking()">
    Continue <i class="fas fa-arrow-right"></i>
</button>
                </form>
            </div>
        </div>
    </div>
 
    <script>
    const salonId = {{ $salon->id }};
    const stylistId = {{ $stylist->id }};
    
    let currentDate = new Date();
    let selectedDate = null;
    let selectedSlotId = null;
    let availableSlots = {};
 
    // All time slots (9 AM to 9 PM)
    const allTimeSlots = [
        '09:00 AM', '09:30 AM', '10:00 AM', '10:30 AM',
        '11:00 AM', '11:30 AM', '12:00 PM', '12:30 PM',
        '01:00 PM', '01:30 PM', '02:00 PM', '02:30 PM',
        '03:00 PM', '03:30 PM', '04:00 PM', '04:30 PM',
        '05:00 PM', '05:30 PM', '06:00 PM', '06:30 PM',
        '07:00 PM', '07:30 PM', '08:00 PM', '08:30 PM', '09:00 PM'
    ];
 
    // Initialize
    window.addEventListener('load', () => {
        renderCalendar();
        loadSlotsForSelectedDate();
    });
 
    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const startingDay = firstDay.getDay(); // 0 = Sunday
        const totalDays = lastDay.getDate();
        
        document.getElementById('currentMonth').innerText = firstDay.toLocaleDateString('en-PK', { month: 'long', year: 'numeric' });
        
        let html = '';
        // Empty cells for days before month starts
        for (let i = 0; i < startingDay; i++) {
            html += `<div class="date-card disabled"><div class="day"></div><div class="num"></div><div class="month"></div></div>`;
        }
        
        // Days of the month
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        for (let d = 1; d <= totalDays; d++) {
            const date = new Date(year, month, d);
            const isPast = date < today;
            const dateStr = date.toISOString().split('T')[0];
            const dayName = date.toLocaleDateString('en-PK', { weekday: 'short' });
            const monthName = date.toLocaleDateString('en-PK', { month: 'short' });
            
            html += `
                <div class="date-card ${isPast ? 'disabled' : ''}" 
                     data-date="${dateStr}" 
                     onclick="${isPast ? '' : `selectDate(this, '${dateStr}')`}">
                    <div class="day">${dayName}</div>
                    <div class="num">${d}</div>
                    <div class="month">${monthName}</div>
                </div>
            `;
        }
        
        document.getElementById('dateGrid').innerHTML = html;
    }
 
    function changeMonth(direction) {
        currentDate.setMonth(currentDate.getMonth() + direction);
        renderCalendar();
        // Clear selected date
        selectedDate = null;
        selectedSlotId = null;
        document.getElementById('slotInput').value = '';
        document.getElementById('continueBtn').classList.remove('active');
        document.getElementById('continueBtn').disabled = true;
        document.getElementById('dateDisplay').style.display = 'none';
        document.getElementById('timeDisplay').style.display = 'none';
        document.getElementById('timeSlotGrid').innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 20px; color: #aaa;">Select a date first</div>';
        document.getElementById('waitlistSection').style.display = 'none';
    }
 
    function selectDate(card, date) {
        document.querySelectorAll('.date-card').forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');
        selectedDate = date;
        selectedSlotId = null;
        document.getElementById('slotInput').value = '';
        document.getElementById('continueBtn').classList.remove('active');
        document.getElementById('continueBtn').disabled = true;
        document.getElementById('timeDisplay').style.display = 'none';
        loadSlotsForSelectedDate();
    }
 
    function loadSlotsForSelectedDate() {
        if (!selectedDate) return;
        
        const container = document.getElementById('timeSlotGrid');
        container.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 20px;"><i class="fas fa-spinner fa-pulse"></i> Loading available slots...</div>';
        
        // Update sidebar date
        const d = new Date(selectedDate);
        document.getElementById('dateText').innerHTML = d.toLocaleDateString('en-PK', { weekday: 'long', day: 'numeric', month: 'long' });
        document.getElementById('dateDisplay').style.display = 'flex';
        
        // Simulate API call (replace with actual API)
        setTimeout(() => {
            // Randomly mark some slots as booked for demo
            const bookedSlots = ['09:00 AM', '12:00 PM', '03:00 PM', '06:00 PM'];
            
            let html = '';
            let hasAvailable = false;
            
            allTimeSlots.forEach(slot => {
                const isBooked = bookedSlots.includes(slot) && Math.random() > 0.7;
                if (!isBooked) hasAvailable = true;
                html += `
                    <div class="time-slot ${isBooked ? 'booked' : ''}" 
                         data-time="${slot}"
                         ${!isBooked ? `onclick="selectTimeSlot(this, '${slot}')"` : ''}>
                        ${slot}
                    </div>
                `;
            });
            
            container.innerHTML = html;
            
            // Show/hide waitlist section
            const waitlistSection = document.getElementById('waitlistSection');
            if (!hasAvailable) {
                waitlistSection.style.display = 'block';
            } else {
                waitlistSection.style.display = 'none';
            }
        }, 500);
    }
 
   function selectTimeSlot(el, time) {
    document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
    el.classList.add('selected');
    selectedSlotId = time;
    document.getElementById('slotInput').value = time;
    document.getElementById('continueBtn').classList.add('active');
    document.getElementById('continueBtn').disabled = false;
    document.getElementById('timeText').innerHTML = time + ' (' + '{{ $service->duration_text }}' + ')';
    document.getElementById('timeDisplay').style.display = 'flex';
}

function submitBooking() {
    console.log("submitBooking called");
    console.log("selectedSlotId:", selectedSlotId);
    
    if (!selectedSlotId) {
        alert('Please select a time slot first');
        return;
    }
    
    console.log("Submitting form to:", document.getElementById('step3Form').action);
    document.getElementById('slotInput').value = selectedSlotId;
    document.getElementById('step3Form').submit();
}

function joinWaitlist() {
    if (!selectedDate) {
        alert('Please select a date first');
        return;
    }
    window.location.href = "{{ route('client.waitlist.index') }}?salon_id={{ $salon->id }}&date=" + selectedDate + "&service_id={{ $service->id }}&stylist_id={{ $stylist->id }}";
}
    </script>
</body>
</html>