
@extends('layouts.owner')
 
@section('title', 'Sales Analytics')
 
@section('content')
 
   
    <div class="page-header">
        <h2>Sales Analytics</h2>
        <p>Comprehensive sales and revenue insights</p>
    </div>
 
   
    <div class="row g-4 mb-4">
 
        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-green"><i class="bi bi-currency-dollar"></i></div>
                <div>
                    <div class="stat-label-sm">Total Revenue</div>
                    <div class="stat-value-sm">${{ number_format($stats['total_revenue'] ?? 206880) }}</div>
                </div>
            </div>
        </div>
 
        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-gold"><i class="bi bi-graph-up-arrow"></i></div>
                <div>
                    <div class="stat-label-sm">Net Profit</div>
                    <div class="stat-value-sm">${{ number_format($stats['net_profit'] ?? 118180) }}</div>
                </div>
            </div>
        </div>
 
        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-blue"><i class="bi bi-calendar-week-fill"></i></div>
                <div>
                    <div class="stat-label-sm">Avg. Monthly</div>
                    <div class="stat-value-sm">${{ number_format($stats['avg_monthly'] ?? 34480) }}</div>
                </div>
            </div>
        </div>
 
        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-purple"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="stat-label-sm">Avg. Per Client</div>
                    <div class="stat-value-sm">${{ $stats['avg_per_client'] ?? 166 }}</div>
                </div>
            </div>
        </div>
 
    </div>
 
    
    <div class="row g-4 mb-4">
 
        <div class="col-lg-6">
            <div class="panel-card panel-card-auto">
                <div class="panel-title">Revenue vs Profit</div>
                <div class="chart-box">
                    <canvas id="revenueProfitChart" height="260"></canvas>
                </div>
            </div>
        </div>
 
        <div class="col-lg-6">
            <div class="panel-card panel-card-auto">
                <div class="panel-title">Revenue by Service</div>
                <div class="chart-box">
                    <canvas id="revenueByServiceChart" height="260"></canvas>
                </div>
            </div>
        </div>
 
    </div>
 
    
    <div class="row g-4 mb-4">
 
        <div class="col-lg-6">
            <div class="panel-card panel-card-auto">
                <div class="panel-title">Monthly Expenses</div>
                <div class="chart-box">
                    <canvas id="monthlyExpensesChart" height="260"></canvas>
                </div>
            </div>
        </div>
 
        <div class="col-lg-6">
            <div class="panel-card panel-card-auto">
                <div class="panel-title">Client Growth</div>
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
 
    .stat-card-sm {
        background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--blush-200);
        box-shadow: var(--shadow-card); padding: 18px 20px; display: flex; align-items: center; gap: 16px; height: 100%;
    }
    .stat-card-sm .stat-icon { width: 50px; height: 50px; border-radius: 14px; font-size: 20px; flex-shrink: 0; }
    .stat-label-sm { font-size: 13.5px; color: var(--ink-700); margin-bottom: 2px; }
    .stat-value-sm { font-size: 22px; font-weight: 700; color: var(--plum-900); }
 
    .chart-box { position: relative; width: 100%; }
</style>
@endsection
 
@section('extra-js')
@php
    // Blade ke @json() directive ke sath inline ?? [...] fallback likhne se
    // parser confuse ho jata hai (Unclosed '[' error). Isliye fallback yahan
    // PHP mein resolve kar lete hain, phir @json() ko clean variable milta hai.
    $monthLabelsJs       = $monthLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    $revenueDataJs       = $revenueData ?? [24000, 29000, 32000, 35000, 41000, 45880];
    $profitDataJs        = $profitData ?? [14000, 17000, 19000, 21500, 24500, 27180];
    $serviceLabelsJs     = $serviceLabels ?? ['Hair Styling', 'Manicure/Pedicure', 'Facial Treatment', 'Spa & Massage'];
    $serviceRevenueJs    = $serviceRevenue ?? [18500, 14200, 11800, 9600];
    $expensesLabelsJs    = $expensesLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    $expensesDataJs      = $expensesData ?? [11200, 12400, 14100, 14800, 15900, 16700];
    $clientGrowthLabelsJs = $clientGrowthLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    $clientGrowthDataJs   = $clientGrowthData ?? [140, 162, 180, 198, 246, 268];
@endphp
<script>
    const goldColor   = '#D9A441';
    const purpleColor = '#9B6FD1';
    const greenColor  = '#2EAE7D';
    const redColor    = '#E14D6A';
    const inkColor    = '#6B4F62';
 
   
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
                    pointRadius: 3,
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
                    pointRadius: 3,
                    borderWidth: 3,
                },
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { color: inkColor, padding: 14, font: { size: 12.5 } } }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: '#FBD5E2' }, ticks: { color: inkColor } },
                x: { grid: { display: false }, ticks: { color: inkColor } }
            }
        }
    });
 
    
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
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, grid: { color: '#FBD5E2' }, ticks: { color: inkColor } },
                y: { grid: { display: false }, ticks: { color: inkColor } }
            }
        }
    });
 
    
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
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#FBD5E2' }, ticks: { color: inkColor } },
                x: { grid: { display: false }, ticks: { color: inkColor } }
            }
        }
    });
 
    
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
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#FBD5E2' }, ticks: { color: inkColor } },
                x: { grid: { display: false }, ticks: { color: inkColor } }
            }
        }
    });
</script>
@endsection
 