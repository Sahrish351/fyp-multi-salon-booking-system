@extends('layouts.admin')
@section('title', 'Dashboard - Glamora Admin')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
    :root {
        --gl-pink: #E0177D;
        --gl-pink-dark: #B5125F;
        --gl-pink-light: #FDEAF3;
        --gl-pink-pale: #F6C9DF;
        --gl-text: #2B2230;
        --gl-text-lt: #B98BA6;
        --gl-border: #F1DCE9;
    }

    .page-header { margin-bottom: 28px; padding-top: 2px; }
    .page-header h1 { font-size: 1.65rem; font-weight: 800; color: var(--gl-text); letter-spacing: -0.3px; margin: 0; }
    .page-header p { font-size: 0.9rem; color: var(--gl-text-lt); margin: 6px 0 0; }

    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin: 8px 0 32px; }
    .stat-card { position: relative; overflow: hidden; border-radius: 32px 32px 32px 8px; padding: 24px 26px; border: none; transition: all 0.25s ease; cursor: pointer; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12); }
    .stat-card:hover { transform: translateY(-5px) rotate(-1deg) scale(1.02); }
    .stat-card::after { content: ''; position: absolute; width: 110px; height: 110px; background: rgba(255, 255, 255, 0.14); border-radius: 50%; top: -45px; right: -35px; }
    .stat-icon { position: relative; z-index: 1; width: 38px; height: 38px; background: rgba(255, 255, 255, 0.25); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; margin-bottom: 10px; }
    .stat-label { position: relative; z-index: 1; font-size: 0.68rem; letter-spacing: 1.4px; text-transform: uppercase; font-weight: 700; color: rgba(255, 255, 255, 0.85); }
    .stat-value { position: relative; z-index: 1; font-size: 1.95rem; font-weight: 800; margin-top: 10px; color: #fff; text-shadow: 0 1px 4px rgba(0, 0, 0, 0.15); }

    .stat-card:nth-child(1) { background: linear-gradient(135deg, #4285F4 0%, #1967D2 100%); }
    .stat-card:nth-child(1):hover { box-shadow: 0 16px 32px rgba(66, 133, 244, 0.45); }
    .stat-card:nth-child(2) { background: linear-gradient(135deg, #EA4335 0%, #C5221F 100%); }
    .stat-card:nth-child(2):hover { box-shadow: 0 16px 32px rgba(234, 67, 53, 0.45); }
    .stat-card:nth-child(3) { background: linear-gradient(135deg, #FBBC05 0%, #F29900 100%); }
    .stat-card:nth-child(3) .stat-label { color: rgba(60, 40, 0, 0.75); }
    .stat-card:nth-child(3) .stat-value { color: #3C2800; text-shadow: none; }
    .stat-card:nth-child(3) .stat-icon { background: rgba(60, 40, 0, 0.15); }
    .stat-card:nth-child(3):hover { box-shadow: 0 16px 32px rgba(251, 188, 5, 0.45); }
    .stat-card:nth-child(4) { background: linear-gradient(135deg, #34A853 0%, #188038 100%); }
    .stat-card:nth-child(4):hover { box-shadow: 0 16px 32px rgba(52, 168, 83, 0.45); }

    .charts-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 32px; }
    .chart-card { background: #fff; border-radius: 20px; padding: 22px 24px 14px; border: 1px solid var(--gl-border); box-shadow: 0 2px 10px rgba(224, 23, 125, 0.05); }
    .chart-header { margin-bottom: 18px; }
    .chart-title { font-size: 0.95rem; font-weight: 700; color: var(--gl-text); }

    /* ── Chart Legend ── */
    .chart-legend { display: flex; gap: 18px; flex-wrap: wrap; margin-bottom: 14px; }
    .legend-item { display: flex; align-items: center; gap: 7px; font-size: 0.75rem; font-weight: 700; color: var(--gl-text-lt); }
    .legend-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }

    .two-columns { display: grid; grid-template-columns: 1fr 320px; gap: 24px; }

    .card { background: #fff; border-radius: 20px; border: 1px solid var(--gl-border); box-shadow: 0 2px 10px rgba(224, 23, 125, 0.05); margin-bottom: 24px; overflow: hidden; }
    .card-header { display: flex; align-items: center; justify-content: space-between; padding: 18px 22px; border-bottom: 1px solid var(--gl-border); }
    .card-header h3 { font-size: 0.95rem; font-weight: 700; color: var(--gl-text); display: flex; align-items: center; gap: 8px; margin: 0; }
    .card-header h3 i { color: var(--gl-pink); }

    .pending-table { width: 100%; border-collapse: collapse; }
    .pending-table th, .pending-table td { padding: 14px 22px; text-align: left; border-bottom: 1px solid var(--gl-border); font-size: 0.85rem; color: var(--gl-text); }
    .pending-table th { color: var(--gl-text-lt); font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; }
    .pending-table tbody tr:last-child td { border-bottom: none; }
    .pending-table tbody tr:hover td { background: var(--gl-pink-light); }
    .verify-btn { background: var(--gl-pink); color: #fff; border: none; padding: 6px 16px; border-radius: 20px; font-size: 0.72rem; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; transition: background 0.2s ease; }
    .verify-btn:hover { background: var(--gl-pink-dark); }

    .action-item { display: flex; align-items: center; gap: 14px; padding: 14px 22px; border-bottom: 1px solid var(--gl-border); cursor: pointer; transition: background 0.15s ease; }
    .action-item:last-child { border-bottom: none; }
    .action-item:hover { background: var(--gl-pink-light); }
    .action-item i { width: 38px; height: 38px; background: var(--gl-pink-light); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--gl-pink); flex-shrink: 0; font-size: 0.9rem; }
    .action-item strong { font-size: 0.85rem; color: var(--gl-text); }
    .action-item small { color: var(--gl-text-lt); font-size: 0.72rem; }

    .notif-item { padding: 14px 22px; border-bottom: 1px solid var(--gl-border); cursor: pointer; font-size: 0.82rem; color: var(--gl-text); line-height: 1.55; transition: background 0.15s ease; }
    .notif-item:hover { background: var(--gl-pink-light); }
    .notif-item small { color: var(--gl-text-lt); font-size: 0.68rem; display: block; margin-top: 4px; }

    .notification-card { position: relative; padding-bottom: 58px; }
    .plus-btn { position: absolute; bottom: 16px; right: 16px; width: 42px; height: 42px; background: var(--gl-pink); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; cursor: pointer; box-shadow: 0 6px 14px rgba(224, 23, 125, 0.3); transition: background 0.2s ease; }
    .plus-btn:hover { background: var(--gl-pink-dark); }

    .btn-outline { color: var(--gl-pink); border: 1px solid var(--gl-pink-pale); padding: 6px 14px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; text-decoration: none; transition: all 0.2s ease; }
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
        // ── STATS ──
        $totalRevenue      = \App\Models\Payment::where('status', 'approved')->sum('amount');
        $totalAppointments = \App\Models\Appointment::count();
        $activeSalons      = \App\Models\Salon::where('status', 'approved')->count();
        $avgRating         = \App\Models\Review::avg('rating');
        $pendingRequests   = \App\Models\Salon::where('status', 'pending')->count();
        $todayAppointments = \App\Models\Appointment::whereDate('appointment_date', today())->count();
        $monthlyRevenue    = \App\Models\Payment::where('status', 'approved')->whereMonth('created_at', now()->month)->sum('amount');

        // ── MONTHLY REVENUE DATA (for chart) ──
        $monthlyData = [];
        $lastYearData = [];
        $targetData = [];
        for ($m = 1; $m <= 6; $m++) {
            $monthlyData[]  = \App\Models\Payment::where('status','approved')
                                ->whereMonth('created_at', $m)
                                ->whereYear('created_at', now()->year)
                                ->sum('amount');
            $lastYearData[] = \App\Models\Payment::where('status','approved')
                                ->whereMonth('created_at', $m)
                                ->whereYear('created_at', now()->year - 1)
                                ->sum('amount');
            $targetData[]   = $monthlyData[$m-1] * 1.2; // 20% above current as target line
        }

        // ── WEEKLY APPOINTMENTS DATA (real DB) ──
        $weeklyLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $weeklyData = [];
        $startOfWeek = \Carbon\Carbon::now()->startOfWeek(); // Monday
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $weeklyData[] = \App\Models\Appointment::whereDate('appointment_date', $date)->count();
        }

        // ── PENDING SALONS ──
        $pendingSalons = \App\Models\Salon::where('status', 'pending')->latest()->take(5)->get();
    @endphp

    {{-- STATS CARDS --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-label">Monthly Revenue</div>
            <div class="stat-value">Rs. {{ number_format($monthlyRevenue) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-label">Today's Appointments</div>
            <div class="stat-value">{{ number_format($todayAppointments) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🏠</div>
            <div class="stat-label">Active Salons</div>
            <div class="stat-value">{{ number_format($activeSalons) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">⭐</div>
            <div class="stat-label">Avg. Rating</div>
            <div class="stat-value">{{ number_format($avgRating ?? 0, 1) }} / 5</div>
        </div>
    </div>

    {{-- CHARTS --}}
    <div class="charts-row">
        <div class="chart-card">
            <div class="chart-header">
                <span class="chart-title">Revenue Performance</span>
                <div class="chart-legend" style="margin-top:10px;">
                    <div class="legend-item"><div class="legend-dot" style="background:#B5125F;"></div> This Year</div>
                    <div class="legend-item"><div class="legend-dot" style="background:#E0177D;"></div> Last Year</div>
                    <div class="legend-item"><div class="legend-dot" style="background:#F2A9CE; border: 1.5px dashed #B5125F; border-radius:50%;"></div> Target</div>
                </div>
            </div>
            <canvas id="revenueChart" height="180"></canvas>
        </div>
        <div class="chart-card">
            <div class="chart-header"><span class="chart-title">Weekly Appointments</span></div>
            <canvas id="weeklyChart" height="180"></canvas>
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
                    @forelse($pendingSalons as $salon)
                        <tr>
                            <td><strong>{{ $salon->name }}</strong></td>
                            <td>{{ $salon->owner->name ?? 'N/A' }}</td>
                            <td>{{ $salon->city }}</td>
                            <td><a href="{{ route('admin.salon-requests.show', $salon->id) }}" class="verify-btn">Review</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center; padding: 28px;">✨ No pending requests</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- RIGHT: Quick Actions + Notifications --}}
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
                    <span class="btn-outline" style="cursor:pointer;"
                        onclick="document.getElementById('notifList').innerHTML='<div class=\'notif-item\' style=\'text-align:center;\'>✨ No notifications</div>'">
                        Clear All
                    </span>
                </div>
                <div id="notifList">
                    <div class="notif-item">🔔 Server maintenance scheduled for 02:00 UTC.<small>2 hours ago</small></div>
                    <div class="notif-item">⚠️ Payout failure for 'Luxe Locks' Salon.<small>5 hours ago</small></div>
                    <div class="notif-item">📢 New salon request from Karachi.<small>1 day ago</small></div>
                </div>
                <div class="plus-btn" onclick="window.location='{{ route('admin.notifications.index') }}'">
                    <i class="fas fa-plus"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // ── Revenue Chart ──
    const revenueCtx = document.getElementById('revenueChart');

    // Gradients
    const gradDark = revenueCtx.getContext('2d').createLinearGradient(0, 0, 0, 260);
    gradDark.addColorStop(0, 'rgba(181, 18, 95, 0.18)');
    gradDark.addColorStop(1, 'rgba(181, 18, 95, 0.0)');

    const gradMid = revenueCtx.getContext('2d').createLinearGradient(0, 0, 0, 260);
    gradMid.addColorStop(0, 'rgba(224, 23, 125, 0.12)');
    gradMid.addColorStop(1, 'rgba(224, 23, 125, 0.0)');

    const gradLight = revenueCtx.getContext('2d').createLinearGradient(0, 0, 0, 260);
    gradLight.addColorStop(0, 'rgba(242, 169, 206, 0.10)');
    gradLight.addColorStop(1, 'rgba(242, 169, 206, 0.0)');

    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [
                {
                    label: 'This Year',
                    data: @json($monthlyData),
                    borderColor: '#B5125F',
                    backgroundColor: gradDark,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.38,
                    pointBackgroundColor: '#B5125F',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    order: 1
                },
                {
                    label: 'Last Year',
                    data: @json($lastYearData),
                    borderColor: '#E0177D',
                    backgroundColor: gradMid,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.38,
                    pointBackgroundColor: '#E0177D',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
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
                    pointRadius: 3,
                    pointHoverRadius: 5,
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
                    ticks: { callback: v => 'Rs. ' + v.toLocaleString(), font: { size: 11 } }
                },
                x: { grid: { display: false }, ticks: { font: { size: 11 } } }
            }
        }
    });

    // ── Weekly Appointments Bar Chart ──
    const weeklyData = @json($weeklyData);
    const maxVal = Math.max(...weeklyData);
    const minVal = Math.min(...weeklyData);
    const barColors = weeklyData.map(v => {
        if (maxVal === 0) return '#F2A9CE'; // sab zero hain toh lightest pink
        const ratio = (v - minVal) / (maxVal - minVal);
        if (ratio >= 0.66) return '#B5125F';
        if (ratio >= 0.33) return '#E0177D';
        return '#F2A9CE';
    });

    new Chart(document.getElementById('weeklyChart'), {
        type: 'bar',
        data: {
            labels: @json($weeklyLabels),
            datasets: [{
                data: weeklyData,
                backgroundColor: barColors,
                hoverBackgroundColor: '#8C0D42',
                borderRadius: 8,
                maxBarThickness: 36
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#F8E4EF' } },
                x: { grid: { display: false } }
            }
        }
    });

    // ── Click on stat cards ──
    document.querySelectorAll('.stat-card').forEach(card =>
        card.addEventListener('click', () => alert('📊 Detailed analytics would appear here'))
    );
</script>
@endsection