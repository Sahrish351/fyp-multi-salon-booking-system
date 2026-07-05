@extends('layouts.owner')

@section('title', 'Dashboard')

@section('content')

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-header">
        <h2>Dashboard</h2>
        <p>Welcome back! Here's what's happening today.</p>
    </div>

    
    <div class="row g-3 mb-4">

        
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="stat-card stat-card-sm">
                <div class="stat-top">
                    <div class="stat-icon icon-gold"><i class="bi bi-calendar-event-fill"></i></div>
                    <span class="stat-trend {{ $stats['today_trend'] >= 0 ? 'up' : 'down' }}">
                        <i class="bi bi-arrow-{{ $stats['today_trend'] >= 0 ? 'up' : 'down' }}"></i> 
                        {{ abs($stats['today_trend']) }}%
                    </span>
                </div>
                <div class="stat-label">Today Appointments</div>
                <div class="stat-value">{{ number_format($stats['today_appointments']) }}</div>
            </div>
        </div>

       
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="stat-card stat-card-sm">
                <div class="stat-top">
                    <div class="stat-icon icon-purple"><i class="bi bi-clock-history"></i></div>
                    <span class="stat-trend {{ $stats['pending_appointments'] > 5 ? 'up' : 'down' }}">
                        <i class="bi bi-arrow-{{ $stats['pending_appointments'] > 5 ? 'up' : 'down' }}"></i> 
                        {{ $stats['pending_appointments'] }}
                    </span>
                </div>
                <div class="stat-label">Pending Appointments</div>
                <div class="stat-value">{{ number_format($stats['pending_appointments']) }}</div>
            </div>
        </div>

        
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="stat-card stat-card-sm">
                <div class="stat-top">
                    <div class="stat-icon icon-green"><i class="bi bi-currency-dollar"></i></div>
                    <span class="stat-trend {{ $stats['revenue_trend'] >= 0 ? 'up' : 'down' }}">
                        <i class="bi bi-arrow-{{ $stats['revenue_trend'] >= 0 ? 'up' : 'down' }}"></i> 
                        {{ abs($stats['revenue_trend']) }}%
                    </span>
                </div>
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value">PKR {{ number_format($stats['total_revenue']) }}</div>
            </div>
        </div>

      
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="stat-card stat-card-sm">
                <div class="stat-top">
                    <div class="stat-icon icon-blue"><i class="bi bi-people-fill"></i></div>
                    <span class="stat-trend {{ $stats['client_trend'] >= 0 ? 'up' : 'down' }}">
                        <i class="bi bi-arrow-{{ $stats['client_trend'] >= 0 ? 'up' : 'down' }}"></i> 
                        {{ abs($stats['client_trend']) }}%
                    </span>
                </div>
                <div class="stat-label">Total Clients</div>
                <div class="stat-value">{{ number_format($stats['total_clients']) }}</div>
            </div>
        </div>

    </div>

    
    <div class="row g-3 mb-4">

        
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="stat-card stat-card-sm">
                <div class="stat-top">
                    <div class="stat-icon icon-red"><i class="bi bi-exclamation-circle-fill"></i></div>
                    <span class="stat-trend {{ $stats['pending_payments'] > 1000 ? 'up' : 'down' }}">
                        <i class="bi bi-arrow-{{ $stats['pending_payments'] > 1000 ? 'up' : 'down' }}"></i> 
                        {{ $stats['pending_payments'] > 0 ? round(($stats['pending_payments'] / max($stats['total_revenue'], 1)) * 100) : 0 }}%
                    </span>
                </div>
                <div class="stat-label">Pending Payments</div>
                <div class="stat-value">PKR {{ number_format($stats['pending_payments']) }}</div>
            </div>
        </div>

        
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="stat-card stat-card-sm">
                <div class="stat-top">
                    <div class="stat-icon icon-amber"><i class="bi bi-graph-up-arrow"></i></div>
                    <span class="stat-trend {{ $stats['sales_trend'] >= 0 ? 'up' : 'down' }}">
                        <i class="bi bi-arrow-{{ $stats['sales_trend'] >= 0 ? 'up' : 'down' }}"></i> 
                        {{ abs($stats['sales_trend']) }}%
                    </span>
                </div>
                <div class="stat-label">Monthly Sales</div>
                <div class="stat-value">PKR {{ number_format($stats['monthly_sales']) }}</div>
            </div>
        </div>

        
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="stat-card stat-card-sm">
                <div class="stat-top">
                    <div class="stat-icon icon-teal"><i class="bi bi-check-circle-fill"></i></div>
                    <span class="stat-trend up">
                        <i class="bi bi-arrow-up"></i> 
                        {{ $stats['total_appointments'] > 0 ? round(($stats['completed_appointments'] / max($stats['total_appointments'], 1)) * 100) : 0 }}%
                    </span>
                </div>
                <div class="stat-label">Completed Appointments</div>
                <div class="stat-value">{{ number_format($stats['completed_appointments']) }}</div>
            </div>
        </div>

      
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="stat-card stat-card-sm">
                <div class="stat-top">
                    <div class="stat-icon icon-pink"><i class="bi bi-tags-fill"></i></div>
                    <span class="stat-trend up">
                        <i class="bi bi-arrow-up"></i> 
                        {{ $stats['active_services'] }}
                    </span>
                </div>
                <div class="stat-label">Active Services</div>
                <div class="stat-value">{{ number_format($stats['active_services']) }}</div>
            </div>
        </div>

    </div>

    
    <div class="row g-4 mb-4">

        
        <div class="col-lg-6">
            <div class="panel-card">
                <div class="panel-header d-flex justify-content-between align-items-center flex-wrap">
                    <div class="panel-title">Revenue Trend</div>
                    <div class="chart-filter-wrapper">
                        <select class="chart-filter-select" data-chart="revenue">
                            <option value="weekly">Weekly</option>
                            <option value="monthly" selected>Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                </div>
                <div class="chart-box" style="height: 280px;">
                    <canvas id="revenueTrendChart"></canvas>
                </div>
            </div>
        </div>

       
        <div class="col-lg-6">
            <div class="panel-card">
                <div class="panel-header d-flex justify-content-between align-items-center flex-wrap">
                    <div class="panel-title">Monthly Bookings</div>
                    <div class="chart-filter-wrapper">
                        <select class="chart-filter-select" data-chart="bookings">
                            <option value="weekly">Weekly</option>
                            <option value="monthly" selected>Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                </div>
                <div class="chart-box" style="height: 280px;">
                    <canvas id="monthlyBookingsChart"></canvas>
                </div>
            </div>
        </div>

    </div>

    
    <div class="row g-4 mb-4">

      
        <div class="col-lg-6">
            <div class="panel-card" style="height: 100%;">
                <div class="panel-header">
                    <div class="panel-title">Popular Services</div>
                </div>
                <div class="chart-box" style="height: 280px;">
                    <canvas id="popularServicesChart"></canvas>
                </div>
            </div>
        </div>

       
        <div class="col-lg-6">
            <div class="panel-card" style="height: 100%;">
                <div class="panel-header">
                    <div class="panel-title">Client Growth</div>
                </div>
                <div class="chart-box" style="height: 280px;">
                    <canvas id="clientGrowthChart"></canvas>
                </div>
            </div>
        </div>

    </div>

   
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="panel-card">
                <div class="panel-header-row">
                    <div class="panel-title mb-0">Today's Appointments</div>
                    <a href="{{ route('owner.appointments.index') }}" class="btn btn-view-all">View All</a>
                </div>

                <div class="table-responsive">
                    @if(count($todaysAppointments) > 0)
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
                                @foreach($todaysAppointments as $appt)
                                    <tr>
                                        <td class="cell-name">{{ $appt['client'] }}</td>
                                        <td>{{ $appt['service'] }}</td>
                                        <td>{{ $appt['time'] }}</td>
                                        <td>{{ $appt['stylist'] }}</td>
                                        <td>
                                            <span class="badge-status {{ $appt['status_badge'] }}">
                                                {{ $appt['status'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-calendar-x display-4 text-muted d-block mb-3"></i>
                            <p class="text-muted mb-0">No appointments scheduled for today.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

  
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="panel-card">
                <div class="panel-header-row">
                    <div class="panel-title mb-0">Recent Payments</div>
                    <a href="{{ route('owner.payments.index') }}" class="btn btn-view-all">View All</a>
                </div>

                <div class="table-responsive">
                    @if(count($recentPayments) > 0)
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
                                @foreach($recentPayments as $pay)
                                    <tr>
                                        <td class="cell-name">{{ $pay['client'] }}</td>
                                        <td class="amount-gold">PKR {{ $pay['amount'] }}</td>
                                        <td>{{ $pay['method'] }}</td>
                                        <td>{{ $pay['date'] }}</td>
                                        <td>
                                            <span class="badge-status {{ $pay['status_badge'] }}">
                                                {{ $pay['status'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-credit-card display-4 text-muted d-block mb-3"></i>
                            <p class="text-muted mb-0">No payments recorded yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@section('extra-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
       
        const colors = {
            gold: '#D9A441',
            rose: '#E96A98',
            purple: '#9B6FD1',
            green: '#2EAE7D',
            blue: '#4A7FE0',
            orange: '#E08A2C',
            teal: '#20B2AA',
            pink: '#FF6B9D',
            ink: '#6B4F62'
        };

       
        const revenueLabels = @json($revenueLabels);
        const revenueData = @json($revenueData);
        const bookingsLabels = @json($bookingsLabels);
        const bookingsData = @json($bookingsData);
        const servicesLabels = @json($servicesLabels);
        const servicesData = @json($servicesData);
        const clientGrowthLabels = @json($clientGrowthLabels);
        const clientGrowthData = @json($clientGrowthData);

        
        const revenueCtx = document.getElementById('revenueTrendChart').getContext('2d');
        const revenueGradient = revenueCtx.createLinearGradient(0, 0, 0, 280);
        revenueGradient.addColorStop(0, 'rgba(217, 164, 65, 0.35)');
        revenueGradient.addColorStop(1, 'rgba(217, 164, 65, 0.02)');

        let revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueLabels,
                datasets: [{
                    label: 'Revenue',
                    data: revenueData,
                    borderColor: colors.gold,
                    backgroundColor: revenueGradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: colors.gold,
                    pointRadius: 4,
                    borderWidth: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#FBD5E2' },
                        ticks: { color: colors.ink }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: colors.ink }
                    }
                }
            }
        });

      
        let bookingsChart = new Chart(document.getElementById('monthlyBookingsChart'), {
            type: 'bar',
            data: {
                labels: bookingsLabels,
                datasets: [{
                    label: 'Bookings',
                    data: bookingsData,
                    backgroundColor: colors.purple,
                    borderRadius: 8,
                    maxBarThickness: 42,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#FBD5E2' },
                        ticks: { color: colors.ink }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: colors.ink }
                    }
                }
            }
        });

        
        if (servicesData.length > 0 && servicesData.some(v => v > 0)) {
            new Chart(document.getElementById('popularServicesChart'), {
                type: 'doughnut',
                data: {
                    labels: servicesLabels,
                    datasets: [{
                        data: servicesData,
                        backgroundColor: [colors.gold, colors.purple, colors.green, colors.blue, colors.orange, colors.rose],
                        borderColor: '#fff',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { 
                                color: colors.ink, 
                                padding: 10, 
                                font: { size: 11 },
                                boxWidth: 12,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        }
                    }
                }
            });
        } else {
            document.getElementById('popularServicesChart').parentElement.innerHTML = 
                '<div class="text-center py-4 text-muted"><i class="bi bi-bar-chart display-4 d-block mb-2"></i><small>No service data available</small></div>';
        }

        new Chart(document.getElementById('clientGrowthChart'), {
            type: 'line',
            data: {
                labels: clientGrowthLabels,
                datasets: [{
                    label: 'Clients',
                    data: clientGrowthData,
                    borderColor: colors.green,
                    backgroundColor: 'rgba(46, 174, 125, 0.08)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: colors.green,
                    pointRadius: 4,
                    borderWidth: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#FBD5E2' },
                        ticks: { color: colors.ink }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: colors.ink }
                    }
                }
            }
        });

       
        document.querySelectorAll('.chart-filter-select').forEach(select => {
            select.addEventListener('change', function() {
                const chartType = this.dataset.chart;
                const period = this.value;

                if (chartType === 'revenue') {
                    updateRevenueChart(period);
                } else if (chartType === 'bookings') {
                    updateBookingsChart(period);
                }
            });
        });

       
        function updateRevenueChart(period) {
            fetch(`{{ route('owner.dashboard.chart-data') }}?period=${period}&type=revenue`)
                .then(response => response.json())
                .then(data => {
                    if (data.labels && data.values) {
                        revenueChart.data.labels = data.labels;
                        revenueChart.data.datasets[0].data = data.values;
                        revenueChart.update();
                    }
                })
                .catch(() => {
                    let labels = [];
                    let values = [];
                    if (period === 'weekly') {
                        for (let i = 6; i >= 0; i--) {
                            const date = new Date();
                            date.setDate(date.getDate() - i);
                            labels.push(date.toLocaleDateString('en', { weekday: 'short' }));
                            values.push(Math.floor(Math.random() * 1000) + 100);
                        }
                    } else if (period === 'monthly') {
                        for (let i = 11; i >= 0; i--) {
                            const date = new Date();
                            date.setMonth(date.getMonth() - i);
                            labels.push(date.toLocaleDateString('en', { month: 'short' }));
                            values.push(Math.floor(Math.random() * 2000) + 500);
                        }
                    } else {
                        for (let i = 4; i >= 0; i--) {
                            const year = new Date().getFullYear() - i;
                            labels.push(year.toString());
                            values.push(Math.floor(Math.random() * 5000) + 1000);
                        }
                    }
                    revenueChart.data.labels = labels;
                    revenueChart.data.datasets[0].data = values;
                    revenueChart.update();
                });
        }

       
        function updateBookingsChart(period) {
            fetch(`{{ route('owner.dashboard.chart-data') }}?period=${period}&type=bookings`)
                .then(response => response.json())
                .then(data => {
                    if (data.labels && data.values) {
                        bookingsChart.data.labels = data.labels;
                        bookingsChart.data.datasets[0].data = data.values;
                        bookingsChart.update();
                    }
                })
                .catch(() => {
                    let labels = [];
                    let values = [];
                    if (period === 'weekly') {
                        for (let i = 6; i >= 0; i--) {
                            const date = new Date();
                            date.setDate(date.getDate() - i);
                            labels.push(date.toLocaleDateString('en', { weekday: 'short' }));
                            values.push(Math.floor(Math.random() * 30) + 5);
                        }
                    } else if (period === 'monthly') {
                        for (let i = 11; i >= 0; i--) {
                            const date = new Date();
                            date.setMonth(date.getMonth() - i);
                            labels.push(date.toLocaleDateString('en', { month: 'short' }));
                            values.push(Math.floor(Math.random() * 50) + 10);
                        }
                    } else {
                        for (let i = 4; i >= 0; i--) {
                            const year = new Date().getFullYear() - i;
                            labels.push(year.toString());
                            values.push(Math.floor(Math.random() * 100) + 20);
                        }
                    }
                    bookingsChart.data.labels = labels;
                    bookingsChart.data.datasets[0].data = values;
                    bookingsChart.update();
                });
        }
    });
</script>

<style>
   
    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.04);
        height: 100%;
        min-height: 100px;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }

    .stat-card-sm {
        padding: 1rem 1.25rem !important;
        min-height: auto !important;
    }

    .stat-card-sm .stat-icon {
        width: 38px !important;
        height: 38px !important;
        font-size: 1.1rem !important;
    }

    .stat-card-sm .stat-value {
        font-size: 1.3rem !important;
        font-weight: 700 !important;
    }

    .stat-card-sm .stat-label {
        font-size: 0.7rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        color: #8a7a88 !important;
    }

    .stat-card-sm .stat-trend {
        font-size: 0.65rem !important;
        padding: 0.15rem 0.5rem !important;
    }

    .stat-card-sm .stat-top {
        margin-bottom: 0.4rem !important;
    }

    .stat-card .stat-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .stat-card .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: #fff;
    }

    .stat-card .stat-label {
        color: #8a7a88;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-card .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d1f2c;
        line-height: 1.2;
    }

    .stat-card .stat-trend {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        background: rgba(0,0,0,0.04);
    }

    .stat-card .stat-trend.up {
        color: #2EAE7D;
    }

    .stat-card .stat-trend.down {
        color: #E96A98;
    }

    .icon-gold { background: linear-gradient(135deg, #D9A441, #C4903A); }
    .icon-purple { background: linear-gradient(135deg, #9B6FD1, #7E56B0); }
    .icon-green { background: linear-gradient(135deg, #2EAE7D, #1E8E64); }
    .icon-blue { background: linear-gradient(135deg, #4A7FE0, #3568C4); }
    .icon-red { background: linear-gradient(135deg, #E96A98, #D45482); }
    .icon-amber { background: linear-gradient(135deg, #E08A2C, #C47620); }
    .icon-teal { background: linear-gradient(135deg, #20B2AA, #16908A); }
    .icon-pink { background: linear-gradient(135deg, #FF6B9D, #E85588); }

  
    .panel-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        border: 1px solid rgba(0,0,0,0.04);
        overflow: hidden;
        height: 100%;
    }

    .panel-card .panel-header {
        padding: 0.75rem 1.25rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .panel-card .panel-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #2d1f2c;
        display: flex;
        align-items: center;
    }

    .panel-card .panel-header-row {
        padding: 0.75rem 1.25rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    .panel-card .chart-box {
        padding: 1rem;
        position: relative;
    }

  
    .chart-filter-wrapper {
        position: relative;
    }

    .chart-filter-select {
        appearance: none;
        -webkit-appearance: none;
        background: linear-gradient(135deg, #FF6B9D, #E85588);
        color: #ffffff !important;
        border: none;
        padding: 0.35rem 2rem 0.35rem 1rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        outline: none;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(232, 85, 136, 0.3);
        min-width: 100px;
        letter-spacing: 0.3px;
    }

    .chart-filter-select:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(232, 85, 136, 0.4);
    }

    .chart-filter-select:focus {
        box-shadow: 0 0 0 3px rgba(232, 85, 136, 0.3);
    }

    .chart-filter-select option {
        background: #ffffff;
        color: #2d1f2c;
        padding: 8px 12px;
        font-weight: 500;
    }

    .chart-filter-wrapper::after {
        content: '\F282';
        font-family: 'bootstrap-icons';
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.7rem;
        pointer-events: none;
    }

   
    .table-custom {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }

    .table-custom thead th {
        background: #f8f6f8;
        color: #4a3a48;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.6rem 1rem;
        border-bottom: 2px solid #e9e5e9;
        text-align: left;
    }

    .table-custom tbody td {
        padding: 0.6rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f0eef0;
    }

    .table-custom tbody tr:hover {
        background: #fcf9fc;
    }

    .table-custom .cell-name {
        font-weight: 600;
        color: #2d1f2c;
    }

    .table-custom .amount-gold {
        color: #D9A441;
        font-weight: 700;
    }

   
    .badge-status {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
    }

    .badge-pending { background: #FFF3CD; color: #856404; }
    .badge-confirmed { background: #D1ECF1; color: #0C5460; }
    .badge-progress { background: #CCE5FF; color: #004085; }
    .badge-completed { background: #D4EDDA; color: #155724; }
    .badge-cancelled { background: #F8D7DA; color: #721C24; }

   
    .btn-view-all {
        background: linear-gradient(135deg, #FF6B9D, #E85588);
        color: #ffffff !important;
        border: none;
        padding: 0.35rem 1.2rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(232, 85, 136, 0.3);
        letter-spacing: 0.3px;
    }

    .btn-view-all:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(232, 85, 136, 0.4);
        color: #ffffff !important;
    }

  
    @media (max-width: 768px) {
        .stat-card-sm .stat-value {
            font-size: 1.1rem !important;
        }
        
        .panel-card .chart-box {
            height: 200px !important;
            padding: 0.5rem !important;
        }
        
        .panel-card .panel-header {
            padding: 0.5rem 0.75rem !important;
        }
        
        .panel-card .panel-title {
            font-size: 0.85rem !important;
        }
        
        .chart-filter-select {
            font-size: 0.65rem !important;
            padding: 0.25rem 1.8rem 0.25rem 0.8rem !important;
            min-width: 80px !important;
        }
    }

    @media (max-width: 576px) {
        .stat-card {
            padding: 0.75rem 1rem !important;
            min-height: 80px !important;
        }
        
        .stat-card-sm .stat-icon {
            width: 32px !important;
            height: 32px !important;
            font-size: 0.9rem !important;
        }
        
        .stat-card-sm .stat-value {
            font-size: 0.95rem !important;
        }
        
        .table-custom {
            font-size: 0.7rem !important;
        }
        
        .table-custom thead th,
        .table-custom tbody td {
            padding: 0.4rem 0.5rem !important;
        }
        
        .chart-filter-select {
            font-size: 0.6rem !important;
            padding: 0.2rem 1.5rem 0.2rem 0.6rem !important;
            min-width: 70px !important;
        }
    }
</style>
@endsection