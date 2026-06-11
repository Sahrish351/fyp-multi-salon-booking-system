@extends('layouts.owner')

@section('title', 'Owner Dashboard - Glamora')
@section('page-title', 'Overview')

@section('content')

<!-- Stats Row 1 -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card pink">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-number">{{ $totalAppointments ?? 0 }}</div>
                    <div class="stat-label">Total Appointments</div>
                </div>
                <div class="stat-icon" style="background: #fff0f3;">
                    <i class="fas fa-calendar-alt" style="color: #c8506e;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card orange">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-number">{{ $pendingAppointments ?? 0 }}</div>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-icon" style="background: #fffbeb;">
                    <i class="fas fa-clock" style="color: #f59e0b;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card green">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-number">{{ $completedAppointments ?? 0 }}</div>
                    <div class="stat-label">Completed</div>
                </div>
                <div class="stat-icon" style="background: #ecfdf5;">
                    <i class="fas fa-check-circle" style="color: #10b981;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card blue">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-number">Rs. {{ number_format($totalRevenue ?? 0) }}</div>
                    <div class="stat-label">Total Revenue</div>
                </div>
                <div class="stat-icon" style="background: #eff6ff;">
                    <i class="fas fa-money-bill-wave" style="color: #3b82f6;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Row 2 -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card purple">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-number">{{ $totalClients ?? 0 }}</div>
                    <div class="stat-label">Total Clients</div>
                </div>
                <div class="stat-icon" style="background: #f5f3ff;">
                    <i class="fas fa-users" style="color: #8b5cf6;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card pink">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-number">{{ $totalServices ?? 0 }}</div>
                    <div class="stat-label">Total Services</div>
                </div>
                <div class="stat-icon" style="background: #fff0f3;">
                    <i class="fas fa-spa" style="color: #c8506e;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card blue">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-number">{{ $totalStylists ?? 0 }}</div>
                    <div class="stat-label">Total Stylists</div>
                </div>
                <div class="stat-icon" style="background: #eff6ff;">
                    <i class="fas fa-user-tie" style="color: #3b82f6;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card orange">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-number">{{ $approvedAppointments ?? 0 }}</div>
                    <div class="stat-label">Approved</div>
                </div>
                <div class="stat-icon" style="background: #fffbeb;">
                    <i class="fas fa-thumbs-up" style: #f59e0b;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CHARTS ROW -->
<div class="row g-4 mb-4">
    <div class="col-xl-6">
        <div class="content-card">
            <div class="content-card-header">
                <h5><i class="fas fa-chart-line me-2" style="color: #c8506e;"></i>Appointments Trend (Last 7 Days)</h5>
            </div>
            <div class="p-3">
                <canvas id="appointmentsChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="content-card">
            <div class="content-card-header">
                <h5><i class="fas fa-chart-bar me-2" style="color: #c8506e;"></i>Weekly Revenue (Last 7 Days)</h5>
            </div>
            <div class="p-3">
                <canvas id="revenueChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- MONTHLY REVENUE CHART -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="content-card">
            <div class="content-card-header">
                <h5><i class="fas fa-chart-line me-2" style="color: #c8506e;"></i>Monthly Revenue (Last 6 Months)</h5>
            </div>
            <div class="p-3">
                <canvas id="monthlyRevenueChart" height="280"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Appointments + Recent Payments -->
<div class="row g-4">
    <div class="col-lg-8">
        <div class="content-card">
            <div class="content-card-header">
                <h5><i class="fas fa-calendar-alt me-2" style="color: #c8506e;"></i>Recent Appointments</h5>
                <a href="{{ route('owner.appointments.index') }}" style="color: #c8506e; font-size: 0.875rem; text-decoration: none; font-weight: 600;">View All <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
            @if($recentAppointments->isEmpty())
                <div class="text-center py-5"><i class="fas fa-calendar-times fa-3x mb-3" style="color: #ddd;"></i><p style="color: #888;">No appointments yet</p></div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Client</th><th>Service</th><th>Stylist</th><th>Date</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach($recentAppointments as $apt)
                            <tr>
                                <td><strong>{{ $apt->client->name ?? 'N/A' }}</strong></td>
                                <td>{{ $apt->service->name ?? 'N/A' }}</td>
                                <td>{{ $apt->stylist->name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($apt->appointment_date)->format('M d, Y') }}</td>
                                <td><span class="status-badge badge-{{ $apt->status }}">{{ ucfirst($apt->status) }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="col-lg-4">
        <div class="content-card mb-4">
            <div class="content-card-header">
                <h5><i class="fas fa-bolt me-2" style="color: #c8506e;"></i>Quick Actions</h5>
            </div>
            <div class="p-3">
                <a href="{{ route('owner.services.create') }}" style="display:flex; align-items:center; gap:12px; padding:12px; background:#fff0f3; border-radius:10px; text-decoration:none; color:#1a1a2e; margin-bottom:10px;">
                    <div style="width:38px;height:38px;background:#c8506e;border-radius:8px;display:flex;align-items:center;justify-content:center;color:white;"><i class="fas fa-plus"></i></div>
                    <span style="font-weight:600;">Add New Service</span>
                </a>
                <a href="{{ route('owner.stylists.create') }}" style="display:flex; align-items:center; gap:12px; padding:12px; background:#eff6ff; border-radius:10px; text-decoration:none; color:#1a1a2e; margin-bottom:10px;">
                    <div style="width:38px;height:38px;background:#3b82f6;border-radius:8px;display:flex;align-items:center;justify-content:center;color:white;"><i class="fas fa-user-plus"></i></div>
                    <span style="font-weight:600;">Add New Stylist</span>
                </a>
                <a href="{{ route('owner.appointments.index') }}" style="display:flex; align-items:center; gap:12px; padding:12px; background:#ecfdf5; border-radius:10px; text-decoration:none; color:#1a1a2e;">
                    <div style="width:38px;height:38px;background:#10b981;border-radius:8px;display:flex;align-items:center;justify-content:center;color:white;"><i class="fas fa-calendar-check"></i></div>
                    <span style="font-weight:600;">Manage Appointments</span>
                </a>
            </div>
        </div>

        <div class="content-card">
            <div class="content-card-header">
                <h5><i class="fas fa-credit-card me-2" style="color: #c8506e;"></i>Recent Payments</h5>
            </div>
            @if($recentPayments->isEmpty())
                <div class="text-center py-4"><i class="fas fa-money-bill fa-2x mb-2" style="color: #ddd;"></i><p style="color: #888;">No payments yet</p></div>
            @else
                <div class="p-3">
                    @foreach($recentPayments as $payment)
                    <div style="display:flex; align-items:center; justify-content:space-between; padding:10px 0; border-bottom:1px solid #f9fafb;">
                        <div>
                            <div style="font-weight:600;">{{ $payment->client->name ?? 'N/A' }}</div>
                            <div style="font-size:0.78rem; color:#9ca3af;">{{ $payment->appointment->service->name ?? 'N/A' }}</div>
                        </div>
                        <div style="font-weight:700; color:#10b981;">Rs. {{ number_format($payment->amount) }}</div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get data from PHP or use defaults
        const appointmentsLabels = {!! json_encode($appointmentsLabels ?? []) !!};
        const appointmentsData = {!! json_encode($appointmentsData ?? []) !!};
        const revenueLabels = {!! json_encode($revenueLabels ?? []) !!};
        const revenueData = {!! json_encode($revenueData ?? []) !!};
        const monthlyLabels = {!! json_encode($monthlyLabels ?? []) !!};
        const monthlyData = {!! json_encode($monthlyData ?? []) !!};
        
        // Use default data if empty
        const finalAppointmentsLabels = appointmentsLabels.length ? appointmentsLabels : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        const finalAppointmentsData = appointmentsData.length ? appointmentsData : [0, 0, 0, 0, 0, 0, 0];
        const finalRevenueLabels = revenueLabels.length ? revenueLabels : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        const finalRevenueData = revenueData.length ? revenueData : [0, 0, 0, 0, 0, 0, 0];
        const finalMonthlyLabels = monthlyLabels.length ? monthlyLabels : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        const finalMonthlyData = monthlyData.length ? monthlyData : [0, 0, 0, 0, 0, 0];
        
        // Appointments Chart
        const appointmentsCtx = document.getElementById('appointmentsChart');
        if(appointmentsCtx) {
            new Chart(appointmentsCtx, {
                type: 'line',
                data: { labels: finalAppointmentsLabels, datasets: [{ label: 'Appointments', data: finalAppointmentsData, borderColor: '#c8506e', backgroundColor: 'rgba(200,80,110,0.05)', borderWidth: 2, fill: true, tension: 0.4, pointBackgroundColor: '#c8506e', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 4 }] },
                options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
            });
        }

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart');
        if(revenueCtx) {
            new Chart(revenueCtx, {
                type: 'bar',
                data: { labels: finalRevenueLabels, datasets: [{ label: 'Revenue (Rs.)', data: finalRevenueData, backgroundColor: '#c8506e', borderRadius: 8, barPercentage: 0.6 }] },
                options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { callback: function(v) { return 'Rs. ' + v.toLocaleString(); } } } } }
            });
        }

        // Monthly Revenue Chart
        const monthlyCtx = document.getElementById('monthlyRevenueChart');
        if(monthlyCtx) {
            new Chart(monthlyCtx, {
                type: 'line',
                data: { labels: finalMonthlyLabels, datasets: [{ label: 'Revenue (Rs.)', data: finalMonthlyData, borderColor: '#c8506e', backgroundColor: 'rgba(200,80,110,0.05)', borderWidth: 2.5, fill: true, tension: 0.3, pointBackgroundColor: '#c8506e', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 5 }] },
                options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { callback: function(v) { return 'Rs. ' + v.toLocaleString(); } } } } }
            });
        }
    });
</script>
@endpush