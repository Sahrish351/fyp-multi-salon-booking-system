{{-- ============================================================ --}}
{{-- FILE: resources/views/client/dashboard.blade.php              --}}
{{-- Glamora Client Dashboard – All routes fixed                  --}}
{{-- ============================================================ --}}

@extends('layouts.client')

@section('title', 'Dashboard — Glamora')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js">
    </script>

    <style>
        /* ... (CSS unchanged) ... */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        :root {
            --pink: #E91E8C;
            --pink-dk: #C2185B;
            --pink-md: #F06292;
            --pink-lt: #FCE4EC;
            --pink-bg: #FDF2F7;
            --white: #ffffff;
            --text: #1C1C2E;
            --text-mid: #5A4A6B;
            --text-lt: #9A8AAA;
            --border: #F0E8F5;
            --shadow: 0 2px 16px rgba(233, 30, 140, 0.08);
            --shadow-md: 0 8px 32px rgba(233, 30, 140, 0.12);
            --sidebar-w: 230px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--pink-bg);
            color: var(--text);
            display: flex;
        }

        .sidebar {
            width: var(--sidebar-w);
            background: var(--white);
            border-right: 1px solid var(--border);
            height: 100vh;
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            overflow-y: auto;
            padding: 20px 16px 16px;
        }

        .sidebar-logo {
            font-size: 24px;
            font-weight: 800;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 22px;
            letter-spacing: -0.5px;
        }
        .sidebar-logo i {
            font-size: 26px;
            color: var(--pink);
        }
        .sidebar-logo span {
            color: var(--pink);
        }

        .sidebar-search {
            background: var(--pink-bg);
            border-radius: 40px;
            padding: 8px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            border: 1px solid var(--border);
        }
        .sidebar-search i {
            color: var(--text-lt);
            font-size: 14px;
        }
        .sidebar-search input {
            border: none;
            background: transparent;
            outline: none;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            width: 100%;
            color: var(--text);
        }
        .sidebar-search input::placeholder {
            color: var(--text-lt);
        }

        .sidebar-btn {
            background: var(--pink);
            color: #fff;
            border: none;
            border-radius: 40px;
            padding: 10px 0;
            font-weight: 600;
            font-size: 14px;
            width: 100%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 20px;
            transition: 0.2s;
        }
        .sidebar-btn:hover {
            background: var(--pink-dk);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(233, 30, 140, 0.35);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0 16px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 12px;
        }
        .sidebar-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--pink), var(--pink-dk));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 16px;
            flex-shrink: 0;
        }
        .sidebar-user .name {
            font-weight: 600;
            font-size: 14px;
            line-height: 1.2;
        }
        .sidebar-user .role {
            font-size: 12px;
            color: var(--text-lt);
        }

        .sidebar-nav {
            flex: 1;
        }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 9px 12px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-mid);
            text-decoration: none;
            transition: 0.15s;
            margin-bottom: 2px;
        }
        .sidebar-nav a i {
            width: 18px;
            font-size: 15px;
            color: var(--text-lt);
        }
        .sidebar-nav a:hover {
            background: var(--pink-lt);
            color: var(--pink-dk);
        }
        .sidebar-nav a:hover i {
            color: var(--pink-dk);
        }
        .sidebar-nav a.active {
            background: var(--pink-lt);
            color: var(--pink-dk);
            font-weight: 600;
        }
        .sidebar-nav a.active i {
            color: var(--pink-dk);
        }

        .sidebar-divider {
            height: 1px;
            background: var(--border);
            margin: 8px 0 12px;
        }

        .sidebar-flower {
            text-align: center;
            margin-top: 12px;
            padding: 10px 8px 4px;
            border-top: 1px solid var(--border);
        }
        .sidebar-flower .flower-icons {
            font-size: 22px;
            color: var(--pink);
            opacity: 0.7;
            letter-spacing: 4px;
        }
        .sidebar-flower .flower-icons i {
            margin: 0 2px;
        }
        .sidebar-flower .flower-quote {
            font-size: 12px;
            font-weight: 500;
            color: var(--text-mid);
            margin-top: 6px;
            line-height: 1.3;
            font-style: italic;
        }
        .sidebar-flower .flower-quote span {
            color: var(--pink);
            font-weight: 600;
        }

        .main {
            flex: 1;
            padding: 24px 32px 40px;
            overflow-y: auto;
            max-height: 100vh;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 12px;
            margin-bottom: 28px;
        }
        .stat-card {
            background: var(--white);
            border-radius: 20px;
            padding: 14px 12px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 10px;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }
        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }
        .stat-icon.total     { background: #E8E8E8; color: #555; }
        .stat-icon.completed { background: #FFB7C5; color: #B34A6B; }
        .stat-icon.upcoming  { background: #A9D6E5; color: #2D7A9B; }
        .stat-icon.pending   { background: #FDE2A2; color: #B88A2D; }
        .stat-icon.cancelled { background: #D3B5E5; color: #7A4B9A; }
        .stat-icon.confirmed { background: #B5EAD7; color: #2E7D5A; }

        .stat-content {
            flex: 1;
            min-width: 0;
        }
        .stat-content .label {
            font-size: 11px;
            font-weight: 500;
            color: var(--text-lt);
            white-space: nowrap;
        }
        .stat-content .value {
            font-size: 22px;
            font-weight: 700;
            color: var(--text);
            line-height: 1.2;
        }
        .stat-content .sub {
            font-size: 10px;
            color: var(--text-lt);
            margin-top: 1px;
        }

        .dash-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-bottom: 24px;
        }
        .card {
            background: var(--white);
            border-radius: 20px;
            padding: 20px 22px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
        }
        .card-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }
        .card-title .badge {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-lt);
            background: var(--pink-lt);
            padding: 2px 12px;
            border-radius: 30px;
        }
        .card-title .dropdown {
            position: relative;
            display: inline-block;
        }
        .card-title .dropdown select {
            background: var(--pink-lt);
            border: 1px solid var(--border);
            border-radius: 30px;
            padding: 4px 12px;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-mid);
            outline: none;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }
        .card-title .dropdown select:hover {
            border-color: var(--pink);
        }

        .empty-state {
            text-align: center;
            padding: 18px 0 8px;
        }
        .empty-state i {
            font-size: 40px;
            color: var(--pink);
            display: block;
            margin-bottom: 10px;
        }
        .empty-state h4 {
            font-size: 15px;
            font-weight: 600;
            color: var(--text);
        }
        .empty-state p {
            font-size: 13px;
            color: var(--text-lt);
            margin-top: 4px;
        }
        .empty-state .btn-sm {
            margin-top: 12px;
            background: var(--pink-lt);
            border: none;
            padding: 8px 22px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 13px;
            color: var(--pink-dk);
            cursor: pointer;
            transition: 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .empty-state .btn-sm:hover {
            background: #E8D5F0;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 4px;
        }
        .quick-actions .qa-item {
            background: var(--pink-bg);
            border-radius: 14px;
            padding: 12px 6px;
            text-align: center;
            font-size: 12px;
            font-weight: 500;
            color: var(--text-mid);
            cursor: pointer;
            transition: 0.2s;
            border: 1px solid transparent;
            text-decoration: none;
            display: block;
        }
        .quick-actions .qa-item i {
            display: block;
            font-size: 22px;
            color: var(--pink);
            margin-bottom: 4px;
        }
        .quick-actions .qa-item:hover {
            background: var(--pink-lt);
            border-color: var(--border);
            transform: translateY(-2px);
        }

        .category-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 6px;
        }
        .category-chips .chip {
            background: var(--pink-bg);
            padding: 6px 16px;
            border-radius: 40px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-mid);
            cursor: pointer;
            transition: 0.2s;
            border: 1px solid transparent;
        }
        .category-chips .chip:hover {
            background: var(--pink-lt);
            border-color: var(--border);
        }
        .category-chips .chip.more {
            background: var(--white);
            border-color: var(--border);
            color: var(--text-lt);
        }
        .category-chips .chip.more:hover {
            background: var(--pink-lt);
            border-color: var(--pink);
            color: var(--pink-dk);
        }

        .calendar-mini {
            margin-top: 12px;
        }
        .calendar-mini .cal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
        }
        .calendar-mini .cal-header .month {
            color: var(--text);
            cursor: default;
        }
        .calendar-mini .cal-header .arrows i {
            color: var(--text-lt);
            cursor: pointer;
            padding: 0 4px;
            font-size: 14px;
            transition: 0.2s;
        }
        .calendar-mini .cal-header .arrows i:hover {
            color: var(--pink);
        }
        .calendar-mini .cal-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
            text-align: center;
        }
        .calendar-mini .cal-grid .day-name {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-lt);
            padding: 4px 0;
        }
        .calendar-mini .cal-grid .day {
            font-size: 13px;
            font-weight: 500;
            padding: 4px 0;
            border-radius: 30px;
            color: var(--text);
            cursor: pointer;
            transition: 0.15s;
        }
        .calendar-mini .cal-grid .day:hover {
            background: var(--pink-lt);
        }
        .calendar-mini .cal-grid .day.empty {
            color: #D5CCE0;
            cursor: default;
        }
        .calendar-mini .cal-grid .day.today {
            background: var(--pink);
            color: #fff;
            font-weight: 700;
        }

        .curated-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: 6px;
        }
        .curated-grid .c-item {
            background: var(--pink-bg);
            border-radius: 12px;
            padding: 10px 12px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: 0.2s;
        }
        .curated-grid .c-item i {
            color: var(--pink);
            font-size: 18px;
            width: 24px;
        }
        .curated-grid .c-item:hover {
            background: var(--pink-lt);
        }

        .appointment-img-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--pink-lt), var(--pink-bg));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: var(--pink);
        }
        .na-img {
            width: 90px;
            height: 72px;
            border-radius: 12px;
            flex-shrink: 0;
            overflow: hidden;
            background: var(--pink-lt);
        }
        .na-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .chart-wrapper-lg {
            width: 100%;
            height: 220px;
            margin: 4px 0;
            position: relative;
        }

        /* ─── STYLISH PAYMENT BARS ────────────────────────────────── */
        .payment-container {
            margin-top: 8px;
        }
        .payment-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
            font-weight: 600;
        }
        .payment-header .paid-title {
            color: var(--pink);
        }
        .payment-header .pending-title {
            color: #D14A6B;
        }

        .payment-bar-group {
            margin-bottom: 16px;
            background: var(--white);
            border-radius: 12px;
            padding: 8px 12px 10px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .payment-bar-group .bar-label {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-mid);
            margin-bottom: 4px;
        }
        .payment-bar-group .bar-label .bar-amount {
            font-weight: 700;
        }
        .payment-bar-group .bar-label .bar-amount.paid-amount {
            color: var(--pink);
        }
        .payment-bar-group .bar-label .bar-amount.pending-amount {
            color: #D14A6B;
        }

        .payment-bar-track {
            width: 100%;
            height: 12px;
            background: var(--pink-bg);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.06);
            position: relative;
        }
        .payment-bar-fill {
            height: 100%;
            border-radius: 20px;
            transition: width 1.2s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 6px;
            min-width: 0;
            position: relative;
        }
        .payment-bar-fill .bar-text {
            color: #fff;
            font-size: 9px;
            font-weight: 700;
            opacity: 0;
            transition: opacity 0.4s;
            white-space: nowrap;
        }
        .payment-bar-fill.show-text .bar-text {
            opacity: 1;
        }
        .payment-bar-fill.paid-fill {
            background: linear-gradient(90deg, #E91E8C, #C2185B);
            box-shadow: 0 0 8px rgba(233,30,140,0.3);
        }
        .payment-bar-fill.pending-fill {
            background: linear-gradient(90deg, #FFB6C1, #D14A6B);
            box-shadow: 0 0 8px rgba(209,74,107,0.25);
        }

        .payment-bar-remaining {
            height: 100%;
            background: var(--pink-lt);
            transition: width 1.2s cubic-bezier(0.34, 1.56, 0.64, 1);
            border-radius: 20px;
        }

        .payment-summary {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 12px;
            color: var(--text-lt);
            border-top: 1px solid var(--border);
            padding-top: 10px;
        }
        .payment-summary .summary-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .payment-summary .summary-item .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }
        .payment-summary .summary-item .dot.paid-dot {
            background: var(--pink);
        }
        .payment-summary .summary-item .dot.pending-dot {
            background: #D14A6B;
        }
        .payment-summary .summary-item .amount {
            font-weight: 600;
            color: var(--text);
        }

        @media (max-width: 1200px) {
            .stats-row {
                grid-template-columns: repeat(3, 1fr);
            }
            .dash-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
        @media (max-width: 900px) {
            .sidebar {
                width: 200px;
                padding: 16px 12px;
            }
            .main {
                padding: 18px 16px;
            }
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }
            .dash-grid {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 600px) {
            .sidebar {
                display: none;
            }
            .main {
                max-height: none;
                padding: 14px 12px;
            }
            .stats-row {
                grid-template-columns: 1fr 1fr;
            }
            .quick-actions {
                grid-template-columns: 1fr 1fr;
            }
            .curated-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $user = auth()->user();
        $name = $user ? $user->name : 'Guest';
        $initials = $user ? collect(explode(' ', $name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->join('') : 'G';
        $displayName = $user ? $name : 'Jane Doe';
        $displayInitials = $user ? $initials : 'JD';
    @endphp

    <div class="main">

        {{-- STATS CARDS --}}
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon total"><i class="fas fa-chart-pie"></i></div>
                <div class="stat-content">
                    <div class="label">Total</div>
                    <div class="value" id="statTotal">0</div>
                    <div class="sub">appointments</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon completed"><i class="fas fa-check-circle"></i></div>
                <div class="stat-content">
                    <div class="label">Completed</div>
                    <div class="value" id="statCompleted">0</div>
                    <div class="sub">(0%)</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon upcoming"><i class="fas fa-calendar-alt"></i></div>
                <div class="stat-content">
                    <div class="label">Upcoming</div>
                    <div class="value" id="statUpcoming">0</div>
                    <div class="sub">(0%)</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon pending"><i class="fas fa-clock"></i></div>
                <div class="stat-content">
                    <div class="label">Pending</div>
                    <div class="value" id="statPending">0</div>
                    <div class="sub">(0%)</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon cancelled"><i class="fas fa-times-circle"></i></div>
                <div class="stat-content">
                    <div class="label">Cancelled</div>
                    <div class="value" id="statCancelled">0</div>
                    <div class="sub">(0%)</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon confirmed"><i class="fas fa-check-double"></i></div>
                <div class="stat-content">
                    <div class="label">Confirmed</div>
                    <div class="value" id="statConfirmed">0</div>
                    <div class="sub">(0%)</div>
                </div>
            </div>
        </div>

        <div class="dash-grid">

            {{-- My Appointments --}}
            <div class="card">
                <div class="card-title">
                    My Appointments
                    <i class="fas fa-ellipsis-h"></i>
                </div>
                <div class="empty-state">
                    <i class="fas fa-calendar-alt"></i>
                    <h4>No appointments booked yet</h4>
                    <p>Start your beauty journey today</p>
                    <button class="btn-sm" id="bookApptBtn">Book Appointment</button>
                </div>
            </div>

            {{-- My Waitlist --}}
            <div class="card">
                <div class="card-title">
                    My Waitlist
                    <span class="badge">0</span>
                </div>
                <div class="empty-state">
                    <i class="fas fa-hourglass-half"></i>
                    <h4>No waitlist entries</h4>
                    <p>You're not on any waitlist yet.</p>
                </div>
            </div>

            {{-- Upcoming Appointment --}}
            <div class="card">
                <div class="card-title">
                    Upcoming Appointment
                    <i class="fas fa-chevron-right"></i>
                </div>
                <div style="display:flex; gap:12px; align-items:center; padding:4px 0 12px 0;">
                    <div class="na-img">
                        <div class="appointment-img-placeholder">
                            <i class="fas fa-spa"></i>
                        </div>
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:700; font-size:14px;">Your next session</div>
                        <div style="font-size:12px; color:var(--text-lt);">No upcoming appointments</div>
                    </div>
                </div>
                <div class="empty-state" style="padding-top:0;">
                    <p style="margin-top:0;">Schedule your next session.</p>
                </div>
            </div>

            {{-- Appointments Overview with Donut Chart --}}
            <div class="card" style="grid-column: span 2;">
                <div class="card-title">
                    Appointments Overview
                    <div class="dropdown">
                        <select id="chartPeriod">
                            <option value="month">This Month</option>
                            <option value="week">This Week</option>
                            <option value="year">This Year</option>
                            <option value="today">Today</option>
                        </select>
                    </div>
                </div>
                <div class="chart-wrapper-lg">
                    <canvas id="appointmentsChart"></canvas>
                </div>
            </div>

            {{-- QUICK ACTIONS – All Routes Fixed --}}
            <div class="card">
                <div class="card-title">Quick Actions</div>
                <div class="quick-actions">
                    <a href="{{ route('salons.index') }}" class="qa-item">
                        <i class="fas fa-calendar-plus"></i> Book
                    </a>
                    <a href="{{ route('salons.index') }}" class="qa-item">
                        <i class="fas fa-search"></i> Search
                    </a>
                    <a href="{{ route('client.appointments.index') }}" class="qa-item">
                        <i class="fas fa-calendar-check"></i> Appointment
                    </a>
                    <a href="{{ route('salons.index') }}" class="qa-item">
                        <i class="fas fa-store"></i> Salons
                    </a>
                    <a href="{{ route('client.notifications.index') }}" class="qa-item">
                        <i class="fas fa-bell"></i> Notifications
                    </a>
                    <a href="{{ route('client.waitlist.index') }}" class="qa-item">
                        <i class="fas fa-hourglass-half"></i> Waitlist
                    </a>
                </div>
            </div>

            {{-- Saved Salons --}}
            <div class="card">
                <div class="card-title">
                    Saved Salons
                    <i class="fas fa-heart" style="color:#D14A6B;"></i>
                </div>
                <div class="empty-state">
                    <i class="fas fa-store-alt"></i>
                    <h4>No saved salons</h4>
                    <p>Save your favourite salons for quick access.</p>
                </div>
            </div>

            {{-- Complaints --}}
            <div class="card">
                <div class="card-title">
                    Complaints
                    <span class="badge">0</span>
                </div>
                <div class="empty-state">
                    <i class="fas fa-comment-dots"></i>
                    <h4>No complaints</h4>
                    <p>All clear! ✨</p>
                </div>
            </div>

            {{-- Explore Categories --}}
            <div class="card">
                <div class="card-title">Explore Categories</div>
                <div class="category-chips">
                    <span class="chip" data-category="hair"><i class="fas fa-cut"></i> Hair</span>
                    <span class="chip" data-category="makeup"><i class="fas fa-paint-brush"></i> Makeup</span>
                    <span class="chip" data-category="skin"><i class="fas fa-hand-sparkles"></i> Skin</span>
                    <span class="chip" data-category="nails"><i class="fas fa-hand"></i> Nails</span>
                    <span class="chip" data-category="massage"><i class="fas fa-spa"></i> Massage</span>
                    <span class="chip more" id="moreCategories"><i class="fas fa-ellipsis-h"></i> More</span>
                </div>
            </div>

            {{-- Favourite Salons + Calendar --}}
            <div class="card">
                <div class="card-title">
                    Favourite Salons
                    <i class="fas fa-heart" style="color:#D14A6B;"></i>
                </div>
                <div class="empty-state" style="padding-bottom:2px;">
                    <i class="fas fa-store"></i>
                    <h4>No favourite salons yet</h4>
                    <p>Explore and save your favourite salons.</p>
                </div>
                <div class="calendar-mini" id="calendarMini">
                    <div class="cal-header">
                        <span class="month" id="calMonth"><i class="fas fa-calendar-alt" style="margin-right:6px;color:var(--pink);"></i> May 2025</span>
                        <span class="arrows">
                            <i class="fas fa-chevron-left" id="calPrev"></i>
                            <i class="fas fa-chevron-right" id="calNext"></i>
                        </span>
                    </div>
                    <div class="cal-grid" id="calGrid"></div>
                </div>
            </div>

            {{-- Curated For Your Glow --}}
            <div class="card">
                <div class="card-title">Curated For Your Glow</div>
                <div class="curated-grid">
                    <div class="c-item"><i class="fas fa-sun"></i> Radiant Skin</div>
                    <div class="c-item"><i class="fas fa-wind"></i> Glossy Hair</div>
                    <div class="c-item"><i class="fas fa-hand-sparkles"></i> Nail Perfection</div>
                    <div class="c-item"><i class="fas fa-leaf"></i> Relax &amp; Unwind</div>
                    <div class="c-item"><i class="fas fa-face-smile"></i> Facial Treatment</div>
                    <div class="c-item"><i class="fas fa-water"></i> Hair Spa</div>
                    <div class="c-item"><i class="fas fa-hand"></i> Manicure</div>
                    <div class="c-item"><i class="fas fa-person-walking"></i> Full Body Massage</div>
                </div>
            </div>

            {{-- PAYMENT ACTIVITY – STYLISH DUAL BARS --}}
            <div class="card payment-activity">
                <div class="card-title">
                    Payment Activity
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="empty-state" style="padding-bottom:8px;">
                    <i class="fas fa-receipt"></i>
                    <h4>No payments made yet</h4>
                    <p>Your payment history will appear here.</p>
                </div>

                <div class="payment-container">
                    <div class="payment-header">
                        <span class="paid-title"><i class="fas fa-check-circle"></i> Paid</span>
                        <span class="pending-title"><i class="fas fa-hourglass-half"></i> Pending</span>
                    </div>

                    {{-- Paid Bar --}}
                    <div class="payment-bar-group">
                        <div class="bar-label">
                            <span>Paid</span>
                            <span class="bar-amount paid-amount" id="paidAmountLabel">$0</span>
                        </div>
                        <div class="payment-bar-track">
                            <div class="payment-bar-fill paid-fill" id="paidBar" style="width: 0%;">
                                <span class="bar-text" id="paidPercent">0%</span>
                            </div>
                            <div class="payment-bar-remaining" style="width: 100%;"></div>
                        </div>
                    </div>

                    {{-- Pending Bar --}}
                    <div class="payment-bar-group">
                        <div class="bar-label">
                            <span>Pending</span>
                            <span class="bar-amount pending-amount" id="pendingAmountLabel">$0</span>
                        </div>
                        <div class="payment-bar-track">
                            <div class="payment-bar-fill pending-fill" id="pendingBar" style="width: 0%;">
                                <span class="bar-text" id="pendingPercent">0%</span>
                            </div>
                            <div class="payment-bar-remaining" style="width: 100%;"></div>
                        </div>
                    </div>

                    {{-- Summary --}}
                    <div class="payment-summary">
                        <span class="summary-item">
                            <span class="dot paid-dot"></span>
                            Paid: <span class="amount" id="paidSummary">$0</span>
                        </span>
                        <span class="summary-item">
                            <span class="dot pending-dot"></span>
                            Pending: <span class="amount" id="pendingSummary">$0</span>
                        </span>
                        <span class="summary-item">
                            <i class="fas fa-percent" style="color:var(--text-lt);"></i>
                            <span id="totalPaidPercent">0%</span> paid
                        </span>
                    </div>
                </div>
            </div>

            {{-- Favourite Havens --}}
            <div class="card favourite-havens">
                <div class="card-title">
                    Favourite Havens
                    <i class="fas fa-heart" style="color:#D14A6B;"></i>
                </div>
                <div class="empty-state">
                    <i class="fas fa-home"></i>
                    <h4>No favourite Havens yet</h4>
                    <p>Save your favourite havens for quick access.</p>
                </div>
            </div>

        </div>{{-- /dash-grid --}}

    </div>{{-- /main --}}
@endsection

{{-- ─── SIDEBAR ─────────────────────────────────────────────────────── --}}
@section('sidebar')
    @php
        $user = auth()->user();
        $name = $user ? $user->name : 'Guest';
        $initials = $user ? collect(explode(' ', $name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->join('') : 'G';
        $displayName = $user ? $name : 'Jane Doe';
        $displayInitials = $user ? $initials : 'JD';
    @endphp

    <aside class="sidebar">
        <div class="sidebar-logo">
            <i class="fas fa-spa"></i> Glam<span>ora</span>
        </div>

        <div class="sidebar-search">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search services..." />
        </div>

        <button class="sidebar-btn" id="sidebarBookBtn">
            <i class="fas fa-calendar-plus"></i> Book Now
        </button>

        <div class="sidebar-user">
            <div class="sidebar-avatar">{{ $displayInitials }}</div>
            <div>
                <div class="name">{{ $displayName }}</div>
                <div class="role">Client</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('client.dashboard') }}" class="active"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="{{ route('client.appointments.index') }}"><i class="fas fa-calendar-check"></i> My Appointments</a>
            <a href="{{ route('client.waitlist.index') }}"><i class="fas fa-hourglass-half"></i> My Waitlist</a>
            <a href="{{ route('client.favorites.index') }}"><i class="fas fa-store"></i> Saved Salons</a>
            <a href="{{ route('client.complaints.index') }}"><i class="fas fa-comment-dots"></i> Complaints</a>
            <a href="{{ route('client.notifications.index') }}"><i class="fas fa-bell"></i> Notifications</a>

            <div class="sidebar-divider"></div>

            <a href="{{ route('client.profile.index') }}"><i class="fas fa-user"></i> My Profile</a>

            {{-- Logout form (POST) – using named route --}}
            <form method="POST" action="{{ route('logout') }}" style="display:inline; width:100%;">
                @csrf
                <button type="submit" class="sidebar-logout" style="background:none; border:none; padding:9px 12px; border-radius:12px; font-size:14px; font-weight:500; color:var(--text-mid); cursor:pointer; display:flex; align-items:center; gap:12px; width:100%; font-family:'Inter', sans-serif; transition:0.15s;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </nav>

        <!-- Flower + Quote -->
        <div class="sidebar-flower">
            <div class="flower-icons">
                <i class="fas fa-rose"></i>
                <i class="fas fa-leaf"></i>
                <i class="fas fa-rose"></i>
                <i class="fas fa-leaf"></i>
                <i class="fas fa-rose"></i>
            </div>
            <div class="flower-quote">
                “Beauty is not just <span>what you see</span>,<br />it's <span>how you feel</span>.”
            </div>
        </div>
    </aside>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ─── DONUT CHART ──────────────────────────────────────────
            const ctx = document.getElementById('appointmentsChart').getContext('2d');

            const centerTextPlugin = {
                id: 'centerText',
                beforeDraw: function(chart) {
                    const { width, height, ctx } = chart;
                    ctx.save();
                    const displayTotal = 0;
                    const fontSize = Math.min(height / 4, 36);
                    ctx.font = `bold ${fontSize}px Inter, sans-serif`;
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillStyle = '#1C1C2E';
                    ctx.fillText(displayTotal, width / 2, height / 2);
                    ctx.restore();
                }
            };

            let donutChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Completed', 'Upcoming', 'Pending', 'Cancelled', 'Confirmed'],
                    datasets: [{
                        data: [1, 1, 1, 1, 1],
                        backgroundColor: ['#FFB7C5', '#A9D6E5', '#FDE2A2', '#D3B5E5', '#B5EAD7'],
                        borderWidth: 0,
                        hoverOffset: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                padding: 14,
                                font: { size: 11, family: 'Inter' },
                                color: '#5A4A6B',
                            }
                        }
                    },
                    animation: {
                        animateRotate: true,
                        duration: 800,
                    }
                },
                plugins: [centerTextPlugin]
            });

            function updateAll(period) {
                const data = [1, 1, 1, 1, 1];
                donutChart.data.datasets[0].data = data;
                donutChart.update();

                const ids = ['statTotal', 'statCompleted', 'statUpcoming', 'statPending', 'statCancelled', 'statConfirmed'];
                const vals = [0, 0, 0, 0, 0, 0];
                ids.forEach((id, idx) => {
                    document.getElementById(id).textContent = vals[idx];
                });
            }

            document.getElementById('chartPeriod').addEventListener('change', function() {
                updateAll(this.value);
            });

            // ── BOOK APPOINTMENT button ──
            document.getElementById('bookApptBtn').addEventListener('click', function() {
                window.location.href = "{{ route('salons.index') }}";
            });

            // ── CATEGORY CHIPS ──
            document.querySelectorAll('.category-chips .chip').forEach(chip => {
                chip.addEventListener('click', function() {
                    if (this.id === 'moreCategories') {
                        window.location.href = "{{ route('salons.index') }}";
                        return;
                    }
                    this.style.background = '#FCE4EC';
                    this.style.borderColor = '#E91E8C';
                    setTimeout(() => {
                        this.style.background = '';
                        this.style.borderColor = '';
                    }, 500);
                });
            });

            // ── CURATED ITEMS ──
            document.querySelectorAll('.curated-grid .c-item').forEach(item => {
                item.addEventListener('click', function() {
                    this.style.background = '#FCE4EC';
                    setTimeout(() => { this.style.background = ''; }, 400);
                });
            });

            // ── SIDEBAR NAV: mark active ──
            document.querySelectorAll('.sidebar-nav a').forEach(link => {
                link.addEventListener('click', function(e) {
                    document.querySelectorAll('.sidebar-nav a').forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // ─── CALENDAR ──────────────────────────────────────────────
            let calDate = new Date(2025, 4, 1);

            function renderCalendar() {
                const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'
                ];
                const year = calDate.getFullYear();
                const month = calDate.getMonth();
                const firstDay = new Date(year, month, 1);
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const startOffset = (firstDay.getDay() + 6) % 7;

                document.getElementById('calMonth').innerHTML =
                    `<i class="fas fa-calendar-alt" style="margin-right:6px;color:var(--pink);"></i> ${monthNames[month]} ${year}`;

                let html = '';
                const dayNames = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                dayNames.forEach(d => {
                    html += `<div class="day-name">${d}</div>`;
                });

                for (let i = 0; i < startOffset; i++) {
                    html += `<div class="day empty"></div>`;
                }

                const today = new Date();
                const isTodayMonth = (today.getFullYear() === year && today.getMonth() === month);
                const todayDate = today.getDate();

                for (let d = 1; d <= daysInMonth; d++) {
                    const isToday = (isTodayMonth && d === todayDate);
                    html += `<div class="day ${isToday ? 'today' : ''}" data-day="${d}">${d}</div>`;
                }
                document.getElementById('calGrid').innerHTML = html;

                document.querySelectorAll('#calGrid .day:not(.empty)').forEach(dayEl => {
                    dayEl.addEventListener('click', function() {
                        document.querySelectorAll('#calGrid .day').forEach(d => d.classList.remove('today'));
                        this.classList.add('today');
                    });
                });
            }

            renderCalendar();

            document.getElementById('calPrev').addEventListener('click', function() {
                calDate.setMonth(calDate.getMonth() - 1);
                renderCalendar();
            });
            document.getElementById('calNext').addEventListener('click', function() {
                calDate.setMonth(calDate.getMonth() + 1);
                renderCalendar();
            });

            updateAll('month');

        });
    </script>
@endpush