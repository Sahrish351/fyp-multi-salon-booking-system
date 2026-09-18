@extends('layouts.admin')
@section('title', 'Complaints - Admin')

@push('styles')
<style>
:root {
    --dpink: #FF6B9D;
    --dpink-hover: #E85588;
    --dpink-lt: #fce4ec;
    --border: #ebebeb;
}

/* ── Page Header ── */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.6rem;
    flex-wrap: wrap;
    gap: 1rem;
}
.page-header h1 {
    font-size: 1.55rem;
    font-weight: 700;
    margin: 0 0 .2rem;
    color: #1a1a1a;
}
.page-header h1 i { color: var(--dpink); margin-right: 0.5rem; }
.page-header p { margin: 0; color: #9a9a9a; font-size: .86rem; }

/* ── Dashboard-style White Summary Tiles ── */
.stats-row {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1.6rem;
}
.stat-card {
    flex: 1 1 0;
    min-width: 150px;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 1.2rem 1.4rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0,0,0,.04);
    transition: all .18s ease;
    display: flex;
    flex-direction: column;
    justify-content: center;
    text-decoration: none;
}
.stat-card:hover {
    border-color: var(--dpink);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255,107,157,.1);
}
.stat-top {
    display: flex;
    align-items: center;
    gap: .7rem;
    margin-bottom: .5rem;
}
.stat-icon {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    background: var(--dpink-lt);
    color: var(--dpink);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .85rem;
}
.stat-label {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #9a9a9a;
}
.stat-value {
    font-size: 1.6rem;
    font-weight: 800;
    color: #1a1a1a;
    line-height: 1;
}

@media (max-width: 700px) {
    .stat-card { flex: 1 1 calc(50% - .5rem); min-width: calc(50% - .5rem); }
}

/* ── Filter Bar ── */
.filter-bar {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 1.1rem 1.3rem;
    margin-bottom: 1.4rem;
    display: flex;
    gap: .85rem;
    flex-wrap: wrap;
    align-items: flex-end;
    box-shadow: 0 2px 6px rgba(0,0,0,.04);
}
.filter-group {
    display: flex;
    flex-direction: column;
    gap: .32rem;
    flex: 1;
    min-width: 140px;
}
.filter-group label {
    font-size: .67rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #aaa;
}
.search-wrapper {
    position: relative;
    flex: 2;
    min-width: 200px;
}
.search-wrapper i {
    position: absolute;
    left: .85rem;
    top: 50%;
    transform: translateY(-50%);
    color: #ccc;
    font-size: .8rem;
    pointer-events: none;
}
.search-input {
    width: 100%;
    padding: .6rem .9rem .6rem 2.2rem;
    border: 1.5px solid #e5e5e5;
    border-radius: 9px;
    font-size: .87rem;
    background: #fafafa;
    color: #1a1a1a;
    outline: none;
    transition: all .2s;
    font-family: inherit;
    box-sizing: border-box;
}
.search-input:focus {
    border-color: var(--dpink);
    box-shadow: 0 0 0 3px rgba(255,107,157,.1);
    background: #fff;
}
.filter-select {
    width: 100%;
    padding: .6rem .9rem;
    border: 1.5px solid #e5e5e5;
    border-radius: 9px;
    font-size: .87rem;
    background: #fafafa;
    color: #1a1a1a;
    cursor: pointer;
    outline: none;
    font-family: inherit;
    box-sizing: border-box;
}
.filter-select:focus { border-color: var(--dpink); background: #fff; }
.filter-actions { display: flex; gap: .5rem; align-items: flex-end; }
.btn-search {
    padding: .62rem 1.1rem;
    border-radius: 9px;
    font-size: .85rem;
    font-weight: 700;
    cursor: pointer;
    border: none;
    background: var(--dpink);
    color: #fff;
    transition: all .18s;
    white-space: nowrap;
}
.btn-search:hover { background: var(--dpink-hover); }
.btn-clear {
    padding: .62rem .9rem;
    border-radius: 9px;
    font-size: .85rem;
    font-weight: 600;
    cursor: pointer;
    background: transparent;
    border: 1.5px solid #e5e5e5;
    color: #aaa;
    text-decoration: none;
    transition: all .15s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
}
.btn-clear:hover { border-color: var(--dpink); color: var(--dpink); }

/* ── Table Card ── */
.complaints-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0,0,0,.04);
}
.complaints-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.3rem;
    border-bottom: 1px solid #f3f3f3;
    flex-wrap: wrap;
    gap: .5rem;
}
.card-title { font-weight: 700; font-size: .9rem; color: #1a1a1a; }
.card-title i { color: var(--dpink); margin-right: .4rem; }
.result-count {
    font-size: .75rem;
    color: #aaa;
    background: #f3f3f3;
    padding: .2rem .62rem;
    border-radius: 20px;
}

/* ── Table ── */
.complaints-table { width: 100%; border-collapse: collapse; }
.complaints-table thead tr { background: #fafafa; }
.complaints-table thead th {
    padding: .75rem .9rem;
    font-size: .66rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #bbb;
    text-align: left;
    white-space: nowrap;
    border-bottom: 1px solid var(--border);
}
.complaints-table tbody tr {
    border-bottom: 1px solid #f5f5f5;
    cursor: pointer;
    transition: background .15s;
}
.complaints-table tbody tr:last-child { border-bottom: none; }
.complaints-table tbody tr:hover { background: #fdf5fa; }
.complaints-table td {
    padding: .85rem .9rem;
    font-size: .85rem;
    color: #444;
    vertical-align: middle;
}

.client-cell { display: flex; align-items: center; gap: .7rem; }
.client-avatar {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: var(--dpink-lt);
    color: var(--dpink);
    font-weight: 800;
    font-size: .8rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.client-name { font-weight: 600; color: #1a1a1a; }
.client-email { font-size: .7rem; color: #aaa; }

.badge {
    display: inline-flex;
    align-items: center;
    gap: .28rem;
    padding: .25rem .7rem;
    border-radius: 20px;
    font-size: .71rem;
    font-weight: 700;
    white-space: nowrap;
}
.badge-pending { background: #fef3c7; color: #92400e; }
.badge-progress { background: #dbeafe; color: #1e40af; }
.badge-resolved { background: #d1fae5; color: #065f46; }
.badge-closed { background: #e5e7eb; color: #4b5563; }
.badge-escalated { background: #fee2e2; color: #991b1b; }
.badge-rejected { background: #fdecea; color: #c0392b; }

.btn-view {
    width: 30px; height: 30px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: .75rem;
    background: #e0f7fa;
    color: #00838f;
    border: none;
    text-decoration: none;
    transition: all .15s;
}
.btn-view:hover { background: #00838f; color: #fff; }

.pagination-wrapper { padding: 1rem 1.3rem; border-top: 1px solid #f3f3f3; }

@media (max-width: 768px) {
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
        <h1><i class="fas fa-exclamation-circle"></i> Complaints Management</h1>
        <p>Monitor and resolve client complaints seamlessly</p>
    </div>
</div>

{{-- ── Stats Row (Dashboard White Cards Style) ── --}}
<div class="stats-row">
    <a href="{{ route('admin.complaints.index', ['status'=>'escalated']) }}" class="stat-card">
        <div class="stat-top">
            <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="stat-label">Escalated</div>
        </div>
        <div class="stat-value">{{ $stats['escalated'] ?? 0 }}</div>
    </a>
    <a href="{{ route('admin.complaints.index', ['status'=>'pending']) }}" class="stat-card">
        <div class="stat-top">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-label">Pending</div>
        </div>
        <div class="stat-value">{{ $stats['pending'] ?? 0 }}</div>
    </a>
    <a href="{{ route('admin.complaints.index', ['status'=>'in_progress']) }}" class="stat-card">
        <div class="stat-top">
            <div class="stat-icon"><i class="fas fa-spinner"></i></div>
            <div class="stat-label">In Progress</div>
        </div>
        <div class="stat-value">{{ $stats['in_progress'] ?? 0 }}</div>
    </a>
    <a href="{{ route('admin.complaints.index', ['status'=>'resolved']) }}" class="stat-card">
        <div class="stat-top">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-label">Resolved</div>
        </div>
        <div class="stat-value">{{ $stats['resolved'] ?? 0 }}</div>
    </a>
    <a href="{{ route('admin.complaints.index', ['status'=>'closed']) }}" class="stat-card">
        <div class="stat-top">
            <div class="stat-icon"><i class="fas fa-check-double"></i></div>
            <div class="stat-label">Closed</div>
        </div>
        <div class="stat-value">{{ $stats['closed'] ?? 0 }}</div>
    </a>
    <a href="{{ route('admin.complaints.index', ['status'=>'rejected']) }}" class="stat-card">
        <div class="stat-top">
            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
            <div class="stat-label">Rejected</div>
        </div>
        <div class="stat-value">{{ $stats['rejected'] ?? 0 }}</div>
    </a>
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
            <option value="">All Status</option>
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
            <option value="">All Types</option>
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

{{-- ── Table Card ── --}}
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
                @forelse($complaints as $i => $complaint)
                <tr onclick="window.location='{{ route('admin.complaints.show', $complaint->id) }}'">
                    <td style="color:#bbb;font-size:.78rem;">#{{ $complaint->id }}</td>
                    <td>
                        <div class="client-cell">
                            <div class="client-avatar">{{ strtoupper(substr($complaint->client->name ?? 'N', 0, 1)) }}</div>
                            <div>
                                <div class="client-name">{{ $complaint->client->name ?? 'N/A' }}</div>
                                <div class="client-email">{{ $complaint->client->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td><span style="font-weight:500;color:#1a1a1a;">{{ Str::limit($complaint->salon->name ?? 'N/A', 18) }}</span></td>
                    <td style="color:#333;font-weight:500;">{{ Str::limit($complaint->subject, 28) }}</td>
                    <td><span style="background:#f3f3f3;padding:.2rem .62rem;border-radius:12px;font-size:.7rem;font-weight:600;color:#666;">{{ $complaint->type_label }}</span></td>
                    <td style="font-size:.78rem;color:#777;white-space:nowrap;">{{ $complaint->created_at->format('d M Y') }}</td>
                    <td><span class="badge badge-{{ $complaint->status }}">{{ $complaint->status_label }}</span></td>
                    <td onclick="event.stopPropagation()">
                        <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="btn-view" title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div style="text-align:center;padding:3rem 1rem;color:#aaa;">
                            <i class="fas fa-exclamation-circle" style="font-size:2.2rem;margin-bottom:.7rem;opacity:.3;display:block;"></i>
                            <p style="font-size:.88rem;margin:0;">No complaints found</p>
                        </div>
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