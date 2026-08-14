@extends('layouts.admin')
@section('title', 'Complaints - Admin')

@push('styles')
<style>
:root {
    --dpink: #c2185b;
    --dpink-lt: #fce4ec;
    --border: #e5e7eb;
}

/* ── Page Header ── */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}
.page-header h1 {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0;
    color: #1a1a1a;
}
.page-header h1 i { color: var(--dpink); margin-right: 0.5rem; }
.page-header p { margin: 0; color: #6b7280; font-size: 0.85rem; }

/* ── Stats Row - All Cards in One Line ── */
.stats-row {
    display: flex;
    gap: 12px;
    margin-bottom: 1.5rem;
    flex-wrap: nowrap;
    overflow-x: auto;
}
.stat-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    flex: 1;
    min-width: 120px;
    transition: all 0.2s ease;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}
.stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 18px;
    flex-shrink: 0;
}
.stat-icon.bg-danger { background: #ef4444; }
.stat-icon.bg-warning { background: #f59e0b; }
.stat-icon.bg-primary { background: #3b82f6; }
.stat-icon.bg-success { background: #22c55e; }
.stat-icon.bg-secondary { background: #6b7280; }
.stat-icon.bg-dark { background: #374151; }

.stat-info { flex: 1; min-width: 0; }
.stat-value {
    font-size: 1.3rem;
    font-weight: 800;
    color: #111827;
    line-height: 1.2;
}
.stat-label {
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    font-weight: 700;
    color: #9ca3af;
    margin-top: 2px;
}

/* ── Filter Bar ── */
.filter-bar {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1rem 1.2rem;
    margin-bottom: 1.5rem;
    display: flex;
    gap: 0.8rem;
    flex-wrap: wrap;
    align-items: flex-end;
}
.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
    flex: 1;
    min-width: 140px;
}
.filter-group label {
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #6b7280;
}
.search-wrapper {
    position: relative;
    flex: 2;
    min-width: 200px;
}
.search-wrapper i {
    position: absolute;
    left: 0.9rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--dpink);
    font-size: 0.85rem;
}
.search-input {
    width: 100%;
    padding: 0.6rem 1rem 0.6rem 2.4rem;
    border: 1.5px solid var(--border);
    border-radius: 9px;
    font-size: 0.88rem;
    background: #f9fafb;
    color: #111;
    outline: none;
    transition: border-color 0.2s;
}
.search-input:focus {
    border-color: var(--dpink);
    background: #fff;
}
.filter-select {
    width: 100%;
    padding: 0.6rem 1rem;
    border: 1.5px solid var(--border);
    border-radius: 9px;
    font-size: 0.85rem;
    background: #f9fafb;
    color: #111;
    cursor: pointer;
    outline: none;
}
.filter-select:focus { border-color: var(--dpink); }
.filter-actions { display: flex; gap: 0.5rem; align-items: flex-end; }
.btn-search {
    padding: 0.6rem 1.2rem;
    border-radius: 9px;
    font-size: 0.85rem;
    font-weight: 700;
    cursor: pointer;
    border: none;
    background: var(--dpink);
    color: #fff;
    transition: background 0.2s;
    white-space: nowrap;
}
.btn-search:hover { background: #ad1457; }
.btn-clear {
    padding: 0.6rem 0.9rem;
    border-radius: 9px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    background: transparent;
    border: 1.5px solid var(--border);
    color: #6b7280;
    text-decoration: none;
    transition: all 0.15s;
    white-space: nowrap;
}
.btn-clear:hover { border-color: var(--dpink); color: var(--dpink); }

/* ── Table Card ── */
.complaints-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
}
.complaints-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.8rem 1.2rem;
    border-bottom: 1px solid var(--border);
    flex-wrap: wrap;
    gap: 0.5rem;
}
.card-title { font-weight: 700; font-size: 0.95rem; color: #1a1a1a; }
.card-title i { color: var(--dpink); margin-right: 0.5rem; }
.result-count {
    font-size: 0.75rem;
    color: #6b7280;
    background: #f3f4f6;
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
}

/* ── Table ── */
.complaints-table { width: 100%; border-collapse: collapse; }
.complaints-table thead tr { background: #f9fafb; }
.complaints-table thead th {
    padding: 0.7rem 1rem;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #6b7280;
    text-align: left;
    white-space: nowrap;
    border-bottom: 1px solid var(--border);
}
.complaints-table tbody tr {
    border-bottom: 1px solid #f3f4f6;
    cursor: pointer;
    transition: background 0.15s;
}
.complaints-table tbody tr:last-child { border-bottom: none; }
.complaints-table tbody tr:hover { background: #fdf0f5; }
.complaints-table td {
    padding: 0.7rem 1rem;
    font-size: 0.85rem;
    color: #374151;
    vertical-align: middle;
}

.client-cell { display: flex; align-items: center; gap: 0.7rem; }
.client-avatar {
    width: 34px; height: 34px;
    border-radius: 50%;
    background: var(--dpink-lt);
    color: var(--dpink);
    font-weight: 800;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.client-name { font-weight: 600; color: #111827; }
.client-email { font-size: 0.7rem; color: #9ca3af; }

.badge {
    display: inline-block;
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
    font-size: 0.68rem;
    font-weight: 700;
}
.badge-pending { background: #fef3c7; color: #92400e; }
.badge-progress { background: #dbeafe; color: #1e40af; }
.badge-resolved { background: #d1fae5; color: #065f46; }
.badge-closed { background: #e5e7eb; color: #4b5563; }
.badge-escalated { background: #fee2e2; color: #991b1b; }
.badge-rejected { background: #f3f4f6; color: #6b7280; }

.btn-view {
    width: 30px; height: 30px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    background: #e0f7fa;
    color: #00838f;
    border: none;
    text-decoration: none;
    transition: all 0.15s;
}
.btn-view:hover { background: #00838f; color: #fff; }

.empty-state { text-align: center; padding: 3rem 1rem; color: #9ca3af; }
.empty-state i { font-size: 2rem; margin-bottom: 0.5rem; display: block; }
.pagination-wrapper { padding: 0.8rem 1.2rem; border-top: 1px solid var(--border); }

@media (max-width: 768px) {
    .stats-row { flex-wrap: wrap; }
    .stat-card { min-width: 80px; flex: 1 1 calc(33% - 10px); }
    .filter-bar { flex-direction: column; }
    .search-wrapper, .filter-group { min-width: 100%; }
    .filter-actions { width: 100%; }
    .btn-search, .btn-clear { flex: 1; text-align: center; }
}
</style>
@endpush

@section('content')

{{-- ── Page Header ── --}}
<div class="page-header">
    <div>
        <h1><i class="fas fa-exclamation-circle"></i> Complaints</h1>
        <p>{{ $complaints->total() }} total complaints</p>
    </div>
</div>

{{-- ── Stats Row (All Cards in One Horizontal Line) ── --}}
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['escalated'] ?? 0 }}</div>
            <div class="stat-label">Escalated</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-warning"><i class="fas fa-clock"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['pending'] ?? 0 }}</div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-primary"><i class="fas fa-spinner"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['in_progress'] ?? 0 }}</div>
            <div class="stat-label">In Progress</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-success"><i class="fas fa-check-circle"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['resolved'] ?? 0 }}</div>
            <div class="stat-label">Resolved</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-secondary"><i class="fas fa-check-double"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['closed'] ?? 0 }}</div>
            <div class="stat-label">Closed</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-dark"><i class="fas fa-times-circle"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['rejected'] ?? 0 }}</div>
            <div class="stat-label">Rejected</div>
        </div>
    </div>
</div>

{{-- ── Filter Bar ── --}}
<form method="GET" action="{{ route('admin.complaints.index') }}">
<div class="filter-bar">
    <div class="search-wrapper">
        <i class="fas fa-search"></i>
        <input type="text" name="search" class="search-input" placeholder="Search by client, subject…" value="{{ request('search') }}">
    </div>
    <div class="filter-group">
        <label>Status</label>
        <select name="status" class="filter-select">
            <option value="">All</option>
            <option value="pending" {{ request('status')=='pending' ? 'selected':'' }}>Pending</option>
            <option value="in_progress" {{ request('status')=='in_progress' ? 'selected':'' }}>In Progress</option>
            <option value="resolved" {{ request('status')=='resolved' ? 'selected':'' }}>Resolved</option>
            <option value="closed" {{ request('status')=='closed' ? 'selected':'' }}>Closed</option>
            <option value="escalated" {{ request('status')=='escalated' ? 'selected':'' }}>Escalated</option>
            <option value="rejected" {{ request('status')=='rejected' ? 'selected':'' }}>Rejected</option>
        </select>
    </div>
    <div class="filter-group">
        <label>Type</label>
        <select name="type" class="filter-select">
            <option value="">All</option>
            <option value="service" {{ request('type')=='service' ? 'selected':'' }}>Service</option>
            <option value="staff" {{ request('type')=='staff' ? 'selected':'' }}>Staff</option>
            <option value="payment" {{ request('type')=='payment' ? 'selected':'' }}>Payment</option>
            <option value="product" {{ request('type')=='product' ? 'selected':'' }}>Product</option>
            <option value="other" {{ request('type')=='other' ? 'selected':'' }}>Other</option>
        </select>
    </div>
    <div class="filter-actions">
        <button type="submit" class="btn-search"><i class="fas fa-search"></i> Filter</button>
        @if(request()->hasAny(['search','status','type']))
            <a href="{{ route('admin.complaints.index') }}" class="btn-clear">Clear</a>
        @endif
    </div>
</div>
</form>

{{-- ── Table ── --}}
<div class="complaints-card">
    <div class="complaints-card-header">
        <span class="card-title"><i class="fas fa-list"></i> Complaints List</span>
        <span class="result-count">{{ $complaints->total() }} records</span>
    </div>

    <div style="overflow-x:auto;">
        <table class="complaints-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Salon</th>
                    <th>Subject</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($complaints as $complaint)
                <tr onclick="window.location='{{ route('admin.complaints.show', $complaint->id) }}'">
                    <td style="color:#9ca3af;">#{{ $complaint->id }}</td>
                    <td>
                        <div class="client-cell">
                            <div class="client-avatar">{{ strtoupper(substr($complaint->client->name ?? 'N', 0, 1)) }}</div>
                            <div>
                                <div class="client-name">{{ $complaint->client->name ?? 'N/A' }}</div>
                                <div class="client-email">{{ $complaint->client->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $complaint->salon->name ?? 'N/A' }}</td>
                    <td>{{ Str::limit($complaint->subject, 30) }}</td>
                    <td><span style="background:#f3f4f6;padding:2px 10px;border-radius:12px;font-size:0.7rem;">{{ $complaint->type_label }}</span></td>
                    <td style="font-size:0.8rem;color:#6b7280;">{{ $complaint->created_at->format('d M Y') }}</td>
                    <td><span class="badge {{ $complaint->status_badge }}">{{ $complaint->status_label }}</span></td>
                    <td onclick="event.stopPropagation()">
                        <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="btn-view"><i class="fas fa-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding:3rem 1rem;text-align:center;color:#9ca3af;">
                        <i class="fas fa-exclamation-circle" style="font-size:2rem;display:block;margin-bottom:0.5rem;"></i>
                        No complaints found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($complaints->hasPages())
    <div class="pagination-wrapper">
        {{ $complaints->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@endsection