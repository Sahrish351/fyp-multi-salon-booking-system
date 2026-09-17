@extends('layouts.admin')
@section('title', 'Dashboard - Beauty Blush Salons Admin')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
    :root {
        --gl-pink: #FF6B9D;
        --gl-pink-dark: #E85588;
        --gl-pink-light: #FDEAF3;
        --gl-pink-pale: #F6C9DF;
        --gl-text: #2B2230;
        --gl-text-lt: #B98BA6;
        --gl-border: #F1DCE9;
    }

    .page-header { margin-bottom: 16px; padding-top: 0; }
    .page-header h1 { font-size: 1.5rem; font-weight: 800; color: var(--gl-text); letter-spacing: -0.3px; margin: 0; }
    .page-header p { font-size: 0.85rem; color: var(--gl-text-lt); margin: 4px 0 0; }

    /* Compact Owner-like Stat Cards */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-bottom: 16px; }
    .stat-card { background: #fff; position: relative; overflow: hidden; border-radius: 14px; padding: 14px 18px; border: 1px solid var(--gl-border); transition: all 0.25s ease; cursor: pointer; box-shadow: 0 2px 8px rgba(255, 107, 157, 0.04); }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(255, 107, 157, 0.1); border-color: var(--gl-pink-pale); }
    .stat-icon { width: 34px; height: 34px; background: var(--gl-pink-light); border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 0.95rem; color: var(--gl-pink); margin-bottom: 8px; }
    .stat-label { font-size: 0.68rem; letter-spacing: 0.6px; text-transform: uppercase; font-weight: 700; color: var(--gl-text-lt); }
    .stat-value { font-size: 1.4rem; font-weight: 800; margin-top: 2px; color: var(--gl-text); }

    .charts-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; margin-bottom: 16px; }
    .chart-card { background: #fff; border-radius: 16px; padding: 16px 18px 10px; border: 1px solid var(--gl-border); box-shadow: 0 2px 8px rgba(255, 107, 157, 0.04); }
    .chart-header { margin-bottom: 10px; }
    .chart-title { font-size: 0.9rem; font-weight: 700; color: var(--gl-text); }

    .chart-legend { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 8px; }
    .legend-item { display: flex; align-items: center; gap: 5px; font-size: 0.72rem; font-weight: 700; color: var(--gl-text-lt); }
    .legend-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }

    .two-columns { display: grid; grid-template-columns: 1fr 300px; gap: 16px; }

    .card { background: #fff; border-radius: 16px; border: 1px solid var(--gl-border); box-shadow: 0 2px 8px rgba(255, 107, 157, 0.04); margin-bottom: 16px; overflow: hidden; }
    .card-header { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-bottom: 1px solid var(--gl-border); }
    .card-header h3 { font-size: 0.9rem; font-weight: 700; color: var(--gl-text); display: flex; align-items: center; gap: 6px; margin: 0; }
    .card-header h3 i { color: var(--gl-pink); }

    .pending-table { width: 100%; border-collapse: collapse; }
    .pending-table th, .pending-table td { padding: 10px 16px; text-align: left; border-bottom: 1px solid var(--gl-border); font-size: 0.82rem; color: var(--gl-text); }
    .pending-table th { color: var(--gl-text-lt); font-size: 0.62rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .pending-table tbody tr:last-child td { border-bottom: none; }
    .pending-table tbody tr:hover td { background: var(--gl-pink-light); }
    .verify-btn { background: var(--gl-pink); color: #fff; border: none; padding: 4px 12px; border-radius: 16px; font-size: 0.7rem; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; transition: background 0.2s ease; }
    .verify-btn:hover { background: var(--gl-pink-dark); }

    .action-item { display: flex; align-items: center; gap: 10px; padding: 10px 16px; border-bottom: 1px solid var(--gl-border); cursor: pointer; transition: background 0.15s ease; }
    .action-item:last-child { border-bottom: none; }
    .action-item:hover { background: var(--gl-pink-light); }
    .action-item i { width: 30px; height: 30px; background: var(--gl-pink-light); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--gl-pink); flex-shrink: 0; font-size: 0.8rem; }
    .action-item strong { font-size: 0.82rem; color: var(--gl-text); }
    .action-item small { color: var(--gl-text-lt); font-size: 0.68rem; }

    .notif-item { padding: 10px 16px; border-bottom: 1px solid var(--gl-border); font-size: 0.8rem; color: var(--gl-text); line-height: 1.4; }
    .notif-item small { color: var(--gl-text-lt); font-size: 0.65rem; display: block; margin-top: 2px; }

    .notification-card { position: relative; padding-bottom: 44px; }
    .plus-btn { position: absolute; bottom: 10px; right: 12px; width: 34px; height: 34px; background: var(--gl-pink); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; cursor: pointer; box-shadow: 0 4px 10px rgba(255, 107, 157, 0.25); transition: background 0.2s ease; }
    .plus-btn:hover { background: var(--gl-pink-dark); }

    .btn-outline { color: var(--gl-pink); border: 1px solid var(--gl-pink-pale); padding: 4px 10px; border-radius: 16px; font-size: 0.72rem; font-weight: 600; text-decoration: none; transition: all 0.2s ease; }
    .btn-outline:hover { background: var(--gl-pink-light); }
</style>
@endpush

@section('content')
<div>
    <div class="page-header">
        <h1>Enterprise Command Center</h1>
        <p>Real-time Glamora salon network oversight and operational intelligence.</p>
    </div>

    @php
        // ── FULL 12 MONTHS REVENUE CHART DATA ──
        $monthlyData = [];
        $lastYearData = [];
        $targetData = [];
        for ($m = 1; $m <= 12; $m++) {
            $mRev = \App\Models\Payment::where('status','approved')
                        ->whereMonth('created_at', $m)
                        ->whereYear('created_at', now()->year)
                        ->sum('amount');
            $monthlyData[] = $mRev;

            $lyRev = \App\Models\Payment::where('status','approved')
                        ->whereMonth('created_at', $m)
                        ->whereYear('created_at', now()->year - 1)
                        ->sum('amount');
            $lastYearData[] = $lyRev;

            $targetData[] = $mRev * 1.2; 
        }

        // ── WEEKLY APPOINTMENTS DATA ──
        $weeklyLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $weeklyData = [];
        $startOfWeek = \Carbon\Carbon::now()->startOfWeek();
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $weeklyData[] = \App\Models\Appointment::whereDate('appointment_date', $date)->count();
        }
    @endphp

    {{-- STATS CARDS --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-wallet"></i></div>
            <div class="stat-label">Monthly Revenue</div>
            <div class="stat-value">Rs. {{ number_format($monthlyRevenue) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-label">Today's Appointments</div>
            <div class="stat-value">{{ number_format($stats['today_appointments']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-store"></i></div>
            <div class="stat-label">Active Salons</div>
            <div class="stat-value">{{ number_format($stats['total_salons']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-star"></i></div>
            <div class="stat-label">Avg. Rating</div>
            <div class="stat-value">{{ number_format(\App\Models\Review::avg('rating') ?? 0, 1) }} / 5</div>
        </div>
    </div>

    {{-- CHARTS --}}
    <div class="charts-row">
        <div class="chart-card">
            <div class="chart-header">
                <span class="chart-title">Revenue Performance</span>
                <div class="chart-legend" style="margin-top:6px;">
                    <div class="legend-item"><div class="legend-dot" style="background:#E85588;"></div> This Year</div>
                    <div class="legend-item"><div class="legend-dot" style="background:#FF6B9D;"></div> Last Year</div>
                    <div class="legend-item"><div class="legend-dot" style="background:#F2A9CE; border: 1.5px dashed #E85588;"></div> Target</div>
                </div>
            </div>
            <canvas id="revenueChart" height="150"></canvas>
        </div>
        <div class="chart-card">
            <div class="chart-header"><span class="chart-title">Weekly Appointments</span></div>
            <canvas id="weeklyChart" height="150" style="margin-top: 14px;"></canvas>
        </div>
    </div>

    {{-- TWO COLUMN LAYOUT --}}
    <div class="two-columns">
        {{-- LEFT: Pending Salon Approvals --}}
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-store"></i> Pending Salon Approvals</h3>
                <a href="{{ route('admin.salon-requests.index') }}" class="btn-outline">View All →</a>
            </div>
            <table class="pending-table">
                <thead>
                    <tr><th>Salon Name</th><th>Owner</th><th>Location</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($recentSalonRequests as $salon)
                        <tr>
                            <td><strong>{{ $salon->name }}</strong></td>
                            <td>{{ $salon->owner->name ?? 'N/A' }}</td>
                            <td>{{ $salon->city }}</td>
                            <td><a href="{{ route('admin.salon-requests.show', $salon->id) }}" class="verify-btn">Review</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center; padding: 18px;">✨ No pending requests</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- RIGHT: Quick Actions + Real Notifications --}}
        <div>
            <div class="card">
                <div class="card-header"><h3>Quick Admin Actions</h3></div>
                <div>
                    <div class="action-item" onclick="window.location='{{ route('admin.salons.create') }}'">
                        <i class="fas fa-store"></i>
                        <div><strong>Onboard Salon</strong><br><small>Manual registration</small></div>
                    </div>
                    <div class="action-item" onclick="alert('Broadcast feature coming soon')">
                        <i class="fas fa-broadcast-tower"></i>
                        <div><strong>Broadcast Notice</strong><br><small>To all salon owners</small></div>
                    </div>
                    <div class="action-item" onclick="window.location='{{ route('admin.reports.index') }}'">
                        <i class="fas fa-chart-bar"></i>
                        <div><strong>Generate Report</strong><br><small>Export analytics</small></div>
                    </div>
                </div>
            </div>

            <div class="card notification-card">
                <div class="card-header">
                    <h3>Notifications</h3>
                </div>
                <div id="notifList">
                    @forelse($notifications as $notification)
                        <div class="notif-item">
                            🔔 {{ $notification->data['message'] ?? 'New system notification' }}
                            <small>{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                    @empty
                        <div class="notif-item" style="text-align:center; padding: 16px;">✨ No new notifications</div>
                    @endforelse
                </div>
                <div class="plus-btn" onclick="window.location='{{ route('admin.notifications.index') }}'" title="View All Notifications">
                    <i class="fas fa-plus"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // ── Revenue Chart (Full 12 Months) ──
    const revenueCtx = document.getElementById('revenueChart');

    const gradDark = revenueCtx.getContext('2d').createLinearGradient(0, 0, 0, 200);
    gradDark.addColorStop(0, 'rgba(232, 85, 136, 0.18)');
    gradDark.addColorStop(1, 'rgba(232, 85, 136, 0.0)');

    const gradMid = revenueCtx.getContext('2d').createLinearGradient(0, 0, 0, 200);
    gradMid.addColorStop(0, 'rgba(255, 107, 157, 0.12)');
    gradMid.addColorStop(1, 'rgba(255, 107, 157, 0.0)');

    const gradLight = revenueCtx.getContext('2d').createLinearGradient(0, 0, 0, 200);
    gradLight.addColorStop(0, 'rgba(242, 169, 206, 0.10)');
    gradLight.addColorStop(1, 'rgba(242, 169, 206, 0.0)');

    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [
                {
                    label: 'This Year',
                    data: @json($monthlyData),
                    borderColor: '#E85588',
                    backgroundColor: gradDark,
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.38,
                    pointBackgroundColor: '#E85588',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    order: 1
                },
                {
                    label: 'Last Year',
                    data: @json($lastYearData),
                    borderColor: '#FF6B9D',
                    backgroundColor: gradMid,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.38,
                    pointBackgroundColor: '#FF6B9D',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 3,
                    order: 2
                },
                {
                    label: 'Target',
                    data: @json($targetData),
                    borderColor: '#F2A9CE',
                    backgroundColor: gradLight,
                    borderWidth: 1.5,
                    borderDash: [6, 4],
                    fill: true,
                    tension: 0.38,
                    pointBackgroundColor: '#F2A9CE',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 1.5,
                    pointRadius: 2.5,
                    order: 3
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.dataset.label + ': Rs. ' + ctx.parsed.y.toLocaleString()
                    }
                }
            },
            scales: {
                y: {
                    min: 0,
                    grid: { color: '#F8E4EF' },
                    ticks: { callback: v => 'Rs. ' + v.toLocaleString(), font: { size: 10 } }
                },
                x: { grid: { display: false }, ticks: { font: { size: 10 } } }
            }
        }
    });

    // ── Weekly Appointments Bar Chart ──
    const weeklyData = @json($weeklyData);
    const maxVal = Math.max(...weeklyData);
    const minVal = Math.min(...weeklyData);
    const barColors = weeklyData.map(v => {
        if (maxVal === 0) return '#F2A9CE';
        const ratio = (v - minVal) / (maxVal - minVal || 1);
        if (ratio >= 0.66) return '#E85588';
        if (ratio >= 0.33) return '#FF6B9D';
        return '#F2A9CE';
    });

    new Chart(document.getElementById('weeklyChart'), {
        type: 'bar',
        data: {
            labels: @json($weeklyLabels),
            datasets: [{
                data: weeklyData,
                backgroundColor: barColors,
                hoverBackgroundColor: '#E85588',
                borderRadius: 6,
                maxBarThickness: 30
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#F8E4EF' }, ticks: { font: { size: 10 } } },
                x: { grid: { display: false }, ticks: { font: { size: 10 } } }
            }
        }
    });
</script>
@endsection