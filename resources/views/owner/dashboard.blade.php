{{--
    ===========================================================
    OWNER DASHBOARD - MAIN PAGE (resources/views/owner/dashboard.blade.php)
    ===========================================================
--}}
@extends('layouts.owner')

@section('title', 'Dashboard')

@section('content')

    {{-- Page Header --}}
    <div class="page-header">
        <h2>Dashboard</h2>
        <p>Welcome back! Here's what's happening today.</p>
    </div>

    {{-- ===================== STAT CARDS ROW 1 ===================== --}}
    <div class="row g-4 mb-4">

        {{-- Today Appointments --}}
        <div class="col-md-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-icon icon-gold"><i class="bi bi-calendar-event-fill"></i></div>
                    <span class="stat-trend up"><i class="bi bi-arrow-up"></i> +12%</span>
                </div>
                <div class="stat-label">Today Appointments</div>
                <div class="stat-value">{{ $stats['today_appointments'] ?? 24 }}</div>
            </div>
        </div>

        {{-- Pending Appointments --}}
        <div class="col-md-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-icon icon-purple"><i class="bi bi-clock-history"></i></div>
                    <span class="stat-trend down"><i class="bi bi-arrow-down"></i> -3%</span>
                </div>
                <div class="stat-label">Pending Appointments</div>
                <div class="stat-value">{{ $stats['pending_appointments'] ?? 8 }}</div>
            </div>
        </div>

        {{-- Total Revenue --}}
        <div class="col-md-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-icon icon-green"><i class="bi bi-currency-dollar"></i></div>
                    <span class="stat-trend up"><i class="bi bi-arrow-up"></i> +18%</span>
                </div>
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value">${{ number_format($stats['total_revenue'] ?? 45280) }}</div>
            </div>
        </div>

    </div>

    {{-- ===================== STAT CARDS ROW 2 ===================== --}}
    <div class="row g-4 mb-4">

        {{-- Total Clients --}}
        <div class="col-md-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-icon icon-blue"><i class="bi bi-people-fill"></i></div>
                    <span class="stat-trend up"><i class="bi bi-arrow-up"></i> +8%</span>
                </div>
                <div class="stat-label">Total Clients</div>
                <div class="stat-value">{{ number_format($stats['total_clients'] ?? 1245) }}</div>
            </div>
        </div>

        {{-- Pending Payments --}}
        <div class="col-md-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-icon icon-red"><i class="bi bi-exclamation-circle-fill"></i></div>
                    <span class="stat-trend down"><i class="bi bi-arrow-down"></i> +5%</span>
                </div>
                <div class="stat-label">Pending Payments</div>
                <div class="stat-value">${{ number_format($stats['pending_payments'] ?? 3420) }}</div>
            </div>
        </div>

        {{-- Monthly Sales --}}
        <div class="col-md-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-icon icon-amber"><i class="bi bi-graph-up-arrow"></i></div>
                    <span class="stat-trend up"><i class="bi bi-arrow-up"></i> +24%</span>
                </div>
                <div class="stat-label">Monthly Sales</div>
                <div class="stat-value">${{ number_format($stats['monthly_sales'] ?? 128450) }}</div>
            </div>
        </div>

    </div>

    {{-- ===================== CHARTS ROW 1: Revenue Trend + Monthly Bookings ===================== --}}
    <div class="row g-4 mb-4">

        <div class="col-lg-6">
            <div class="panel-card">
                <div class="panel-title">Revenue Trend</div>
                <div class="chart-box">
                    <canvas id="revenueTrendChart" height="230"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="panel-card">
                <div class="panel-title">Monthly Bookings</div>
                <div class="chart-box">
                    <canvas id="monthlyBookingsChart" height="230"></canvas>
                </div>
            </div>
        </div>

    </div>

    {{-- ===================== CHARTS ROW 2: Popular Services + Client Growth ===================== --}}
    <div class="row g-4 mb-4">

        <div class="col-lg-6">
            <div class="panel-card">
                <div class="panel-title">Popular Services</div>
                <div class="chart-box">
                    <canvas id="popularServicesChart" height="230"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="panel-card">
                <div class="panel-title">Client Growth</div>
                <div class="chart-box">
                    <canvas id="clientGrowthChart" height="230"></canvas>
                </div>
            </div>
        </div>

    </div>

    {{-- ===================== TODAY'S APPOINTMENTS TABLE ===================== --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="panel-card">
                <div class="panel-header-row">
                    <div class="panel-title mb-0">Today's Appointments</div>
                    <a href="{{ route('owner.appointments.index') }}" class="btn btn-view-all">View All</a>
                </div>

                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Service</th>
                                <th>Time</th>
                                <th>Stylist</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $appointments = $todaysAppointments ?? [
                                    ['client' => 'Sarah Johnson', 'service' => 'Hair Styling', 'time' => '10:00 AM', 'stylist' => 'Emma Wilson', 'status' => 'Confirmed'],
                                    ['client' => 'Michael Chen', 'service' => 'Haircut', 'time' => '11:30 AM', 'stylist' => 'James Brown', 'status' => 'Confirmed'],
                                    ['client' => 'Emily Davis', 'service' => 'Manicure', 'time' => '02:00 PM', 'stylist' => 'Sophia Lee', 'status' => 'Pending'],
                                    ['client' => 'David Miller', 'service' => 'Facial Treatment', 'time' => '03:30 PM', 'stylist' => 'Olivia Martinez', 'status' => 'Confirmed'],
                                    ['client' => 'Lisa Anderson', 'service' => 'Full Body Massage', 'time' => '04:00 PM', 'stylist' => 'Isabella Garcia', 'status' => 'In Progress'],
                                ];

                                $statusBadge = [
                                    'Confirmed'   => 'badge-confirmed',
                                    'Pending'     => 'badge-pending',
                                    'In Progress' => 'badge-progress',
                                    'Cancelled'   => 'badge-cancelled',
                                ];
                            @endphp

                            @foreach ($appointments as $appt)
                                <tr>
                                    <td class="cell-name">{{ $appt['client'] }}</td>
                                    <td>{{ $appt['service'] }}</td>
                                    <td>{{ $appt['time'] }}</td>
                                    <td>{{ $appt['stylist'] }}</td>
                                    <td>
                                        <span class="badge-status {{ $statusBadge[$appt['status']] ?? 'badge-pending' }}">
                                            {{ $appt['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== RECENT PAYMENTS TABLE ===================== --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="panel-card">
                <div class="panel-header-row">
                    <div class="panel-title mb-0">Recent Payments</div>
                    <a href="{{ route('owner.payments.index') }}" class="btn btn-view-all">View All</a>
                </div>

                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $payments = $recentPayments ?? [
                                    ['client' => 'Sarah Johnson', 'amount' => 120, 'method' => 'Credit Card', 'date' => 'Jun 8, 2026', 'status' => 'Completed'],
                                    ['client' => 'Michael Chen', 'amount' => 85, 'method' => 'Cash', 'date' => 'Jun 8, 2026', 'status' => 'Completed'],
                                    ['client' => 'Emily Davis', 'amount' => 95, 'method' => 'Credit Card', 'date' => 'Jun 8, 2026', 'status' => 'Pending'],
                                    ['client' => 'David Miller', 'amount' => 150, 'method' => 'Debit Card', 'date' => 'Jun 7, 2026', 'status' => 'Completed'],
                                ];

                                $paymentBadge = [
                                    'Completed' => 'badge-completed',
                                    'Pending'   => 'badge-pending',
                                    'Cancelled' => 'badge-cancelled',
                                ];
                            @endphp

                            @foreach ($payments as $pay)
                                <tr>
                                    <td class="cell-name">{{ $pay['client'] }}</td>
                                    <td class="amount-gold">${{ $pay['amount'] }}</td>
                                    <td>{{ $pay['method'] }}</td>
                                    <td>{{ $pay['date'] }}</td>
                                    <td>
                                        <span class="badge-status {{ $paymentBadge[$pay['status']] ?? 'badge-pending' }}">
                                            {{ $pay['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('extra-js')
@php
    // Blade ke @json() directive ke sath inline ?? [...] fallback likhne se
    // parser confuse ho jata hai (Unclosed '[' error). Isliye fallback
    // yahan PHP mein resolve kar lete hain, phir @json() ko clean variable milta hai.
    $revenueLabelsJs     = $revenueLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    $revenueDataJs       = $revenueData ?? [24000, 29000, 32000, 35000, 41000, 45280];
    $bookingsLabelsJs    = $bookingsLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    $bookingsDataJs      = $bookingsData ?? [140, 160, 185, 205, 245, 265];
    $servicesLabelsJs    = $servicesLabels ?? ['Hair Styling', 'Manicure', 'Facial', 'Massage', 'Makeup'];
    $servicesDataJs      = $servicesData ?? [35, 25, 20, 15, 5];
    $clientGrowthLabelsJs = $clientGrowthLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    $clientGrowthDataJs   = $clientGrowthData ?? [780, 850, 920, 1040, 1150, 1245];
@endphp
<script>
    // ===================== Chart Color Palette (Pinkish Theme) =====================
    const goldColor   = '#D9A441';
    const roseColor    = '#E96A98';
    const purpleColor = '#9B6FD1';
    const greenColor  = '#2EAE7D';
    const blueColor   = '#4A7FE0';
    const orangeColor = '#E08A2C';
    const inkColor    = '#6B4F62';

    // ===================== 1. Revenue Trend (Area Chart) =====================
    const revenueCtx = document.getElementById('revenueTrendChart').getContext('2d');
    const revenueGradient = revenueCtx.createLinearGradient(0, 0, 0, 230);
    revenueGradient.addColorStop(0, 'rgba(217, 164, 65, 0.35)');
    revenueGradient.addColorStop(1, 'rgba(217, 164, 65, 0.02)');

    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: @json($revenueLabelsJs),
            datasets: [{
                label: 'Revenue',
                data: @json($revenueDataJs),
                borderColor: goldColor,
                backgroundColor: revenueGradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: goldColor,
                pointRadius: 4,
                borderWidth: 3,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#FBD5E2' },
                    ticks: { color: inkColor }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: inkColor }
                }
            }
        }
    });

    // ===================== 2. Monthly Bookings (Bar Chart) =====================
    new Chart(document.getElementById('monthlyBookingsChart'), {
        type: 'bar',
        data: {
            labels: @json($bookingsLabelsJs),
            datasets: [{
                label: 'Bookings',
                data: @json($bookingsDataJs),
                backgroundColor: purpleColor,
                borderRadius: 8,
                maxBarThickness: 42,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#FBD5E2' },
                    ticks: { color: inkColor }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: inkColor }
                }
            }
        }
    });

    // ===================== 3. Popular Services (Pie Chart) =====================
    new Chart(document.getElementById('popularServicesChart'), {
        type: 'pie',
        data: {
            labels: @json($servicesLabelsJs),
            datasets: [{
                data: @json($servicesDataJs),
                backgroundColor: [goldColor, purpleColor, greenColor, blueColor, orangeColor],
                borderColor: '#fff',
                borderWidth: 3,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: inkColor, padding: 14, font: { size: 12.5 } }
                }
            }
        }
    });

    // ===================== 4. Client Growth (Line Chart) =====================
    new Chart(document.getElementById('clientGrowthChart'), {
        type: 'line',
        data: {
            labels: @json($clientGrowthLabelsJs),
            datasets: [{
                label: 'Clients',
                data: @json($clientGrowthDataJs),
                borderColor: greenColor,
                backgroundColor: 'rgba(46, 174, 125, 0.08)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: greenColor,
                pointRadius: 5,
                borderWidth: 3,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#FBD5E2' },
                    ticks: { color: inkColor }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: inkColor }
                }
            }
        }
    });
</script>
@endsection
