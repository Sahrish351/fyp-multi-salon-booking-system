@extends('layouts.owner')
 
@section('title', 'Sales Analytics')
 
@section('content')
 
    <div class="page-header">
        <h2>Sales Analytics</h2>
        <p>Comprehensive sales and revenue insights</p>
    </div>
 
    {{-- STATS CARDS --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-green"><i class="bi bi-currency-dollar"></i></div>
                <div>
                    <div class="stat-label-sm">Total Revenue</div>
                    <div class="stat-value-sm">PKR {{ number_format($stats['total_revenue']) }}</div>
                </div>
            </div>
        </div>
 
        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-gold"><i class="bi bi-graph-up-arrow"></i></div>
                <div>
                    <div class="stat-label-sm">Net Profit</div>
                    <div class="stat-value-sm">PKR {{ number_format($stats['net_profit']) }}</div>
                </div>
            </div>
        </div>
 
        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-blue"><i class="bi bi-calendar-week-fill"></i></div>
                <div>
                    <div class="stat-label-sm">Avg. Monthly</div>
                    <div class="stat-value-sm">PKR {{ number_format($stats['avg_monthly']) }}</div>
                </div>
            </div>
        </div>
 
        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-purple"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="stat-label-sm">Avg. Per Client</div>
                    <div class="stat-value-sm">PKR {{ $stats['avg_per_client'] }}</div>
                </div>
            </div>
        </div>
    </div>
 
    {{-- CHART 1: Revenue vs Profit --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="panel-card panel-card-auto">
                <div class="panel-title d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span>Revenue vs Profit</span>
                    <div class="dropdown">
                        <button class="btn btn-dropdown-pink dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ ucfirst($selectedPeriod1) }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-pink">
                            <li><a class="dropdown-item {{ $selectedPeriod1 == 'weekly' ? 'active' : '' }}" href="{{ route('owner.analytics.index', array_merge(request()->all(), ['period1' => 'weekly'])) }}">Weekly</a></li>
                            <li><a class="dropdown-item {{ $selectedPeriod1 == 'monthly' ? 'active' : '' }}" href="{{ route('owner.analytics.index', array_merge(request()->all(), ['period1' => 'monthly'])) }}">Monthly</a></li>
                            <li><a class="dropdown-item {{ $selectedPeriod1 == 'yearly' ? 'active' : '' }}" href="{{ route('owner.analytics.index', array_merge(request()->all(), ['period1' => 'yearly'])) }}">Yearly</a></li>
                        </ul>
                    </div>
                </div>
                <div class="chart-box">
                    <canvas id="revenueProfitChart" height="260"></canvas>
                </div>
            </div>
        </div>
 
        {{-- CHART 2: Revenue by Service --}}
        <div class="col-lg-6">
            <div class="panel-card panel-card-auto">
                <div class="panel-title d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span>Revenue by Service</span>
                    <div class="dropdown">
                        <button class="btn btn-dropdown-pink dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ ucfirst($selectedPeriod2) }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-pink">
                            <li><a class="dropdown-item {{ $selectedPeriod2 == 'weekly' ? 'active' : '' }}" href="{{ route('owner.analytics.index', array_merge(request()->all(), ['period2' => 'weekly'])) }}">Weekly</a></li>
                            <li><a class="dropdown-item {{ $selectedPeriod2 == 'monthly' ? 'active' : '' }}" href="{{ route('owner.analytics.index', array_merge(request()->all(), ['period2' => 'monthly'])) }}">Monthly</a></li>
                            <li><a class="dropdown-item {{ $selectedPeriod2 == 'yearly' ? 'active' : '' }}" href="{{ route('owner.analytics.index', array_merge(request()->all(), ['period2' => 'yearly'])) }}">Yearly</a></li>
                        </ul>
                    </div>
                </div>
                <div class="chart-box">
                    <canvas id="revenueByServiceChart" height="260"></canvas>
                </div>
            </div>
        </div>
    </div>
 
    {{-- CHART 3: Monthly Expenses --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="panel-card panel-card-auto">
                <div class="panel-title d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span>Monthly Expenses</span>
                    <div class="dropdown">
                        <button class="btn btn-dropdown-pink dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ ucfirst($selectedPeriod3) }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-pink">
                            <li><a class="dropdown-item {{ $selectedPeriod3 == 'weekly' ? 'active' : '' }}" href="{{ route('owner.analytics.index', array_merge(request()->all(), ['period3' => 'weekly'])) }}">Weekly</a></li>
                            <li><a class="dropdown-item {{ $selectedPeriod3 == 'monthly' ? 'active' : '' }}" href="{{ route('owner.analytics.index', array_merge(request()->all(), ['period3' => 'monthly'])) }}">Monthly</a></li>
                            <li><a class="dropdown-item {{ $selectedPeriod3 == 'yearly' ? 'active' : '' }}" href="{{ route('owner.analytics.index', array_merge(request()->all(), ['period3' => 'yearly'])) }}">Yearly</a></li>
                        </ul>
                    </div>
                </div>
                <div class="chart-box">
                    <canvas id="monthlyExpensesChart" height="260"></canvas>
                </div>
            </div>
        </div>
 
        {{-- CHART 4: Client Growth --}}
        <div class="col-lg-6">
            <div class="panel-card panel-card-auto">
                <div class="panel-title d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span>Client Growth</span>
                    <div class="dropdown">
                        <button class="btn btn-dropdown-pink dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ ucfirst($selectedPeriod4) }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-pink">
                            <li><a class="dropdown-item {{ $selectedPeriod4 == 'weekly' ? 'active' : '' }}" href="{{ route('owner.analytics.index', array_merge(request()->all(), ['period4' => 'weekly'])) }}">Weekly</a></li>
                            <li><a class="dropdown-item {{ $selectedPeriod4 == 'monthly' ? 'active' : '' }}" href="{{ route('owner.analytics.index', array_merge(request()->all(), ['period4' => 'monthly'])) }}">Monthly</a></li>
                            <li><a class="dropdown-item {{ $selectedPeriod4 == 'yearly' ? 'active' : '' }}" href="{{ route('owner.analytics.index', array_merge(request()->all(), ['period4' => 'yearly'])) }}">Yearly</a></li>
                        </ul>
                    </div>
                </div>
                <div class="chart-box">
                    <canvas id="clientGrowthChart" height="260"></canvas>
                </div>
            </div>
        </div>
    </div>
 
@endsection
 
@section('extra-css')
<style>
    .panel-card-auto { height: auto; }
    .panel-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid rgba(0,0,0,0.04);
        transition: all 0.3s ease;
        height: 100%;
    }
    .panel-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    .panel-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2d1f2c;
        margin-bottom: 0.5rem;
    }
 
    .stat-card-sm {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #f0e8ed;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        height: 100%;
    }
    .stat-card-sm .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        font-size: 20px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
    }
    .icon-green { background: linear-gradient(135deg, #2EAE7D, #1E8E64); }
    .icon-gold { background: linear-gradient(135deg, #D9A441, #C4903A); }
    .icon-blue { background: linear-gradient(135deg, #4A7FE0, #3568C4); }
    .icon-purple { background: linear-gradient(135deg, #9B6FD1, #7E56B0); }
    .stat-label-sm { font-size: 13.5px; color: #8a7a88; margin-bottom: 2px; }
    .stat-value-sm { font-size: 22px; font-weight: 700; color: #2d1f2c; }
 
    .chart-box { position: relative; width: 100%; height: 280px; }
 
    /* ===== PINK DROPDOWN BUTTON ===== */
    .btn-dropdown-pink {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 8px !important;
        font-size: 13px;
        font-weight: 600;
        padding: 5px 16px !important;
        height: 34px;
        min-width: 90px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(232, 85, 136, 0.25);
    }
    .btn-dropdown-pink:hover {
        background: linear-gradient(135deg, #E85588, #D43F75) !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.4);
    }
    .btn-dropdown-pink:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(232, 85, 136, 0.3);
    }
    .btn-dropdown-pink::after {
        margin-left: 8px;
        color: #fff;
    }
 
    /* ===== PINK DROPDOWN MENU ===== */
    .dropdown-menu-pink {
        border-radius: 10px;
        border: 1px solid #f0e8ed;
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        padding: 6px 0;
        min-width: 120px;
    }
    .dropdown-menu-pink .dropdown-item {
        font-size: 13px;
        font-weight: 500;
        color: #4a3a48;
        padding: 8px 18px;
        transition: all 0.2s ease;
    }
    .dropdown-menu-pink .dropdown-item:hover {
        background: #FCE8F0;
        color: #E85588;
    }
    .dropdown-menu-pink .dropdown-item.active {
        background: linear-gradient(135deg, #FF6B9D, #E85588);
        color: #ffffff;
    }
 
    @media (max-width: 768px) {
        .panel-title {
            flex-direction: column;
            align-items: stretch !important;
            gap: 8px;
        }
        .btn-dropdown-pink {
            width: 100% !important;
        }
        .stat-value-sm {
            font-size: 18px;
        }
        .stat-card-sm .stat-icon {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }
        .chart-box {
            height: 200px;
        }
    }
</style>
@endsection
 
@section('extra-js')
@php
    // CHART 1: Revenue vs Profit
    $monthLabelsJs       = $monthLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    $revenueDataJs       = $revenueData ?? [0, 0, 0, 0, 0, 0];
    $profitDataJs        = $profitData ?? [0, 0, 0, 0, 0, 0];

    // CHART 2: Revenue by Service
    $serviceLabelsJs     = $serviceLabels ?? ['No Data'];
    $serviceRevenueJs    = $serviceRevenue ?? [0];

    // CHART 3: Monthly Expenses
    $expensesLabelsJs    = $expensesLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    $expensesDataJs      = $expensesData ?? [0, 0, 0, 0, 0, 0];

    // CHART 4: Client Growth
    $clientGrowthLabelsJs = $clientGrowthLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    $clientGrowthDataJs   = $clientGrowthData ?? [0, 0, 0, 0, 0, 0];
@endphp
<script>
    const goldColor   = '#D9A441';
    const purpleColor = '#9B6FD1';
    const greenColor  = '#2EAE7D';
    const redColor    = '#E14D6A';
    const blueColor   = '#4A7FE0';
    const inkColor    = '#6B4F62';
 
    // === CHART 1: REVENUE VS PROFIT ===
    const revProfitCtx = document.getElementById('revenueProfitChart').getContext('2d');
    const revenueGradient = revProfitCtx.createLinearGradient(0, 0, 0, 260);
    revenueGradient.addColorStop(0, 'rgba(217, 164, 65, 0.30)');
    revenueGradient.addColorStop(1, 'rgba(217, 164, 65, 0.02)');
    const profitGradient = revProfitCtx.createLinearGradient(0, 0, 0, 260);
    profitGradient.addColorStop(0, 'rgba(46, 174, 125, 0.28)');
    profitGradient.addColorStop(1, 'rgba(46, 174, 125, 0.02)');
 
    new Chart(revProfitCtx, {
        type: 'line',
        data: {
            labels: @json($monthLabelsJs),
            datasets: [
                {
                    label: 'Revenue',
                    data: @json($revenueDataJs),
                    borderColor: goldColor,
                    backgroundColor: revenueGradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: goldColor,
                    pointRadius: 4,
                    borderWidth: 3,
                },
                {
                    label: 'Profit',
                    data: @json($profitDataJs),
                    borderColor: greenColor,
                    backgroundColor: profitGradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: greenColor,
                    pointRadius: 4,
                    borderWidth: 3,
                },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { color: inkColor, padding: 14, font: { size: 12 } } }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: '#FBD5E2' }, ticks: { color: inkColor } },
                x: { grid: { display: false }, ticks: { color: inkColor } }
            }
        }
    });
 
    // === CHART 2: REVENUE BY SERVICE ===
    new Chart(document.getElementById('revenueByServiceChart'), {
        type: 'bar',
        data: {
            labels: @json($serviceLabelsJs),
            datasets: [{
                label: 'Revenue',
                data: @json($serviceRevenueJs),
                backgroundColor: goldColor,
                borderRadius: 8,
                maxBarThickness: 32,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, grid: { color: '#FBD5E2' }, ticks: { color: inkColor } },
                y: { grid: { display: false }, ticks: { color: inkColor } }
            }
        }
    });
 
    // === CHART 3: MONTHLY EXPENSES ===
    new Chart(document.getElementById('monthlyExpensesChart'), {
        type: 'line',
        data: {
            labels: @json($expensesLabelsJs),
            datasets: [{
                label: 'Expenses',
                data: @json($expensesDataJs),
                borderColor: redColor,
                backgroundColor: 'rgba(225, 77, 106, 0.08)',
                fill: true,
                tension: 0.3,
                pointBackgroundColor: redColor,
                pointRadius: 5,
                borderWidth: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#FBD5E2' }, ticks: { color: inkColor } },
                x: { grid: { display: false }, ticks: { color: inkColor } }
            }
        }
    });
 
    // === CHART 4: CLIENT GROWTH ===
    new Chart(document.getElementById('clientGrowthChart'), {
        type: 'bar',
        data: {
            labels: @json($clientGrowthLabelsJs),
            datasets: [{
                label: 'Clients',
                data: @json($clientGrowthDataJs),
                backgroundColor: purpleColor,
                borderRadius: 8,
                maxBarThickness: 42,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#FBD5E2' }, ticks: { color: inkColor } },
                x: { grid: { display: false }, ticks: { color: inkColor } }
            }
        }
    });
</script>
@endsection