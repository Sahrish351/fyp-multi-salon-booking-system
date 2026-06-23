@extends('layouts.admin')
@section('title', 'Dashboard - Glamora Admin')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 32px; }
    .stat-card { background: var(--white); border-radius: 20px; padding: 20px; border: 1px solid var(--border); transition: all 0.2s; cursor: pointer; }
    .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
    .stat-label { font-size: 0.7rem; letter-spacing: 1.5px; text-transform: uppercase; color: var(--text-lt); font-weight: 600; }
    .stat-value { font-size: 1.8rem; font-weight: 800; color: var(--text); margin-top: 8px; }
    .charts-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 32px; }
    .chart-card { background: var(--white); border-radius: 20px; padding: 20px; border: 1px solid var(--border); }
    .two-columns { display: grid; grid-template-columns: 1fr 320px; gap: 24px; }
    .pending-table { width: 100%; border-collapse: collapse; }
    .pending-table th, .pending-table td { padding: 12px 16px; text-align: left; border-bottom: 1px solid var(--border); }
    .pending-table th { color: var(--text-lt); font-size: 0.65rem; font-weight: 600; text-transform: uppercase; }
    .verify-btn { background: var(--brown); color: white; border: none; padding: 5px 14px; border-radius: 20px; font-size: 0.7rem; cursor: pointer; }
    .verify-btn:hover { background: var(--brown-dk); }
    .action-item { display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px solid var(--border); cursor: pointer; }
    .action-item:last-child { border-bottom: none; }
    .action-item i { width: 36px; height: 36px; background: var(--brown-lt); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--brown); }
    .notif-item { padding: 14px 16px; border-bottom: 1px solid var(--border); cursor: pointer; }
    .notif-item small { color: var(--text-lt); font-size: 0.65rem; }
    .plus-btn { position: absolute; bottom: 16px; right: 16px; width: 40px; height: 40px; background: var(--brown); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer; }
    .notification-card { position: relative; }
</style>
@endpush

@section('content')
<div>
    <div class="page-header">
        <h1>Enterprise Command Center</h1>
        <p>Real-time Glamora salon network oversight and operational intelligence.</p>
    </div>

    @php
        $totalRevenue = \App\Models\Payment::where('status', 'approved')->sum('amount');
        $totalAppointments = \App\Models\Appointment::count();
        $activeSalons = \App\Models\Salon::where('status', 'approved')->count();
        $avgRating = \App\Models\Review::avg('rating');
        $pendingRequests = \App\Models\Salon::where('status', 'pending')->count();
        $todayAppointments = \App\Models\Appointment::whereDate('appointment_date', today())->count();
        $monthlyRevenue = \App\Models\Payment::where('status', 'approved')->whereMonth('created_at', now()->month)->sum('amount');
        $monthlyData = [
            \App\Models\Payment::where('status', 'approved')->whereMonth('created_at', 1)->sum('amount'),
            \App\Models\Payment::where('status', 'approved')->whereMonth('created_at', 2)->sum('amount'),
            \App\Models\Payment::where('status', 'approved')->whereMonth('created_at', 3)->sum('amount'),
            \App\Models\Payment::where('status', 'approved')->whereMonth('created_at', 4)->sum('amount'),
            \App\Models\Payment::where('status', 'approved')->whereMonth('created_at', 5)->sum('amount'),
            \App\Models\Payment::where('status', 'approved')->whereMonth('created_at', 6)->sum('amount'),
        ];
        $pendingSalons = \App\Models\Salon::where('status', 'pending')->latest()->take(5)->get();
    @endphp

    <div class="stats-grid">
        <div class="stat-card"><div class="stat-label">MONTHLY REVENUE</div><div class="stat-value" id="revenueStat">Rs. {{ number_format($monthlyRevenue) }}</div></div>
        <div class="stat-card"><div class="stat-label">TODAY'S APPOINTMENTS</div><div class="stat-value" id="apptStat">{{ number_format($todayAppointments) }}</div></div>
        <div class="stat-card"><div class="stat-label">ACTIVE SALONS</div><div class="stat-value" id="salonStat">{{ number_format($activeSalons) }}</div></div>
        <div class="stat-card"><div class="stat-label">AVG. RATING</div><div class="stat-value">{{ number_format($avgRating, 1) }} / 5</div></div>
    </div>

    <div class="charts-row">
        <div class="chart-card">
            <div class="chart-header"><span class="chart-title">Revenue Performance</span></div>
            <canvas id="revenueChart" height="180"></canvas>
        </div>
        <div class="chart-card">
            <div class="chart-header"><span class="chart-title">Weekly Appointments</span></div>
            <canvas id="weeklyChart" height="180"></canvas>
        </div>
    </div>

    <div class="two-columns">
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-store"></i> Pending Salon Approvals</h3><a href="{{ route('admin.salon-requests.index') }}" class="btn-outline">View All →</a></div>
            <table class="pending-table">
                <thead><tr><th>SALON NAME</th><th>OWNER</th><th>LOCATION</th><th></th></tr></thead>
                <tbody>
                    @forelse($pendingSalons as $salon)
                    <tr><td><strong>{{ $salon->name }}</strong></td><td>{{ $salon->owner->name ?? 'N/A' }}</td><td>{{ $salon->city }}</td><td><a href="{{ route('admin.salon-requests.show', $salon->id) }}" class="verify-btn">Review</a></td></tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;">✨ No pending requests</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            <div class="card">
                <div class="card-header"><h3>Quick Admin Actions</h3></div>
                <div style="padding:0 16px;">
                    <div class="action-item" onclick="window.location='{{ route('admin.salons.create') }}'"><i class="fas fa-store"></i><div><strong>Onboard Salon</strong><br><small>Manual registration</small></div></div>
                    <div class="action-item" onclick="alert('Broadcast feature coming soon')"><i class="fas fa-broadcast-tower"></i><div><strong>Broadcast Notice</strong><br><small>To all salon owners</small></div></div>
                    <div class="action-item" onclick="window.location='{{ route('admin.reports.index') }}'"><i class="fas fa-chart-bar"></i><div><strong>Generate Report</strong><br><small>Export analytics</small></div></div>
                </div>
            </div>

            <div class="card notification-card">
                <div class="card-header"><h3>Notifications</h3><span class="btn-outline" style="cursor:pointer;" onclick="document.getElementById('notifList').innerHTML='<div class=\'notif-item\' style=\'text-align:center;\'>✨ No notifications</div>'">Clear All</span></div>
                <div id="notifList">
                    <div class="notif-item">🔔 Server maintenance scheduled for 02:00 UTC.<br><small>2 hours ago</small></div>
                    <div class="notif-item">⚠️ Payout failure for 'Luxe Locks' Salon.<br><small>5 hours ago</small></div>
                    <div class="notif-item">📢 New salon request from Karachi.<br><small>1 day ago</small></div>
                </div>
                <div class="plus-btn" onclick="window.location='{{ route('admin.notifications.index') }}'"><i class="fas fa-plus"></i></div>
            </div>
        </div>
    </div>
</div>

<script>
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: { labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'], datasets: [{ data: {{ json_encode($monthlyData) }}, borderColor: '#5C3D2E', backgroundColor: 'rgba(92,61,46,0.05)', borderWidth: 2.5, fill: true, tension: 0.3, pointBackgroundColor: '#5C3D2E', pointRadius: 4 }] },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { ticks: { callback: v => 'Rs.' + (v/1000) + 'k' } } } }
    });
    new Chart(document.getElementById('weeklyChart'), {
        type: 'bar',
        data: { labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], datasets: [{ data: [42, 58, 51, 68, 75, 89, 64], backgroundColor: '#C17D52', borderRadius: 6 }] },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { grid: { color: '#F0E8E0' } } } }
    });
    document.querySelectorAll('.stat-card').forEach(card => card.addEventListener('click', () => alert('📊 Detailed analytics would appear here')));
</script>
@endsection