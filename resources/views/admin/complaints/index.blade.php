@extends('layouts.admin')
@section('title', 'Complaints - Glamora Admin')

@push('styles')
<style>
    :root {
        --pk: #E91E8C;
        --pk-dark: #c2185b;
        --pk-light: #fce4ec;
        --pk-bg: #fff0f7;
    }

    /* ── Page Header ── */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }
    .page-header h1 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1a1a1a;
        margin: 0;
    }
    .page-header h1 i {
        color: var(--pk);
        margin-right: 10px;
    }
    .page-header p {
        color: #999;
        font-size: 0.85rem;
        margin: 2px 0 0 0;
    }

    /* ── Stats ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 12px;
        margin-bottom: 24px;
    }
    .stat-box {
        background: #fff;
        border: 1px solid #f0edf0;
        border-radius: 14px;
        padding: 16px 20px;
        text-align: center;
        transition: all 0.2s;
    }
    .stat-box:hover {
        border-color: var(--pk);
    }
    .stat-box .number {
        font-size: 1.8rem;
        font-weight: 800;
        line-height: 1.2;
    }
    .stat-box .number.pink { color: var(--pk); }
    .stat-box .number.red { color: #ef4444; }
    .stat-box .number.orange { color: #f59e0b; }
    .stat-box .number.green { color: #22c55e; }
    .stat-box .label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #aaa;
        font-weight: 600;
        margin-top: 4px;
    }

    /* ── Filter Bar ── */
    .filter-bar {
        background: #fff;
        border: 1px solid #f0edf0;
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
    }
    .filter-bar .filter-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .filter-bar label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #999;
        white-space: nowrap;
    }
    .filter-bar select,
    .filter-bar input {
        padding: 8px 14px;
        border: 1.5px solid #e5e0e5;
        border-radius: 10px;
        font-size: 0.82rem;
        background: #fff;
        color: #333;
        outline: none;
        transition: border 0.2s;
        min-width: 130px;
    }
    .filter-bar select:focus,
    .filter-bar input:focus {
        border-color: var(--pk);
        box-shadow: 0 0 0 3px rgba(233,30,140,0.08);
    }
    .filter-bar .btn-filter {
        background: var(--pk);
        color: #fff;
        border: none;
        padding: 8px 22px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.82rem;
        cursor: pointer;
        transition: background 0.2s;
    }
    .filter-bar .btn-filter:hover {
        background: var(--pk-dark);
    }
    .filter-bar .btn-clear {
        background: transparent;
        color: #999;
        border: 1.5px solid #e5e0e5;
        padding: 8px 22px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.82rem;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }
    .filter-bar .btn-clear:hover {
        border-color: var(--pk);
        color: var(--pk);
    }

    /* ── Table Card ── */
    .table-card {
        background: #fff;
        border: 1px solid #f0edf0;
        border-radius: 18px;
        overflow: hidden;
    }
    .table-card .card-head {
        padding: 16px 22px;
        border-bottom: 1px solid #f5f0f5;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .table-card .card-title {
        font-weight: 700;
        font-size: 0.95rem;
        color: #1a1a1a;
    }
    .table-card .card-title i {
        color: var(--pk);
        margin-right: 8px;
    }
    .table-card .record-count {
        font-size: 0.75rem;
        color: #aaa;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    .data-table th {
        text-align: left;
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #aaa;
        padding: 14px 20px;
        border-bottom: 1.5px solid #f5f0f5;
        white-space: nowrap;
    }
    .data-table td {
        padding: 14px 20px;
        font-size: 0.85rem;
        color: #333;
        border-bottom: 1px solid #f8f4f8;
        vertical-align: middle;
    }
    .data-table tbody tr {
        cursor: pointer;
        transition: background 0.15s;
    }
    .data-table tbody tr:hover {
        background: var(--pk-bg);
    }
    .data-table tbody tr:last-child td {
        border-bottom: none;
    }
    .data-table .empty-cell {
        text-align: center;
        padding: 50px 20px;
        color: #ccc;
    }
    .data-table .empty-cell i {
        font-size: 2.5rem;
        display: block;
        margin-bottom: 10px;
        color: #eee;
    }

    .client-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .client-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--pk), var(--pk-dark));
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.75rem;
        flex-shrink: 0;
    }
    .client-name {
        font-weight: 600;
        color: #1a1a1a;
    }

    .badge-status {
        display: inline-flex;
        padding: 4px 14px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: capitalize;
    }
    .badge-open { background: #fee2e2; color: #dc2626; }
    .badge-in_review { background: #fef3c7; color: #d97706; }
    .badge-resolved { background: #d1fae5; color: #059669; }
    .badge-closed { background: #f3f4f6; color: #6b7280; }

    .badge-priority {
        display: inline-flex;
        padding: 3px 12px;
        border-radius: 50px;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    .priority-high { background: #fee2e2; color: #dc2626; }
    .priority-medium { background: #fef3c7; color: #d97706; }
    .priority-low { background: #d1fae5; color: #059669; }

    .btn-view {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 16px;
        border-radius: 50px;
        border: 1.5px solid #e5e0e5;
        color: #666;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-view:hover {
        border-color: var(--pk);
        color: var(--pk);
        background: var(--pk-bg);
    }

    .pagination-wrap {
        padding: 16px 22px;
        border-top: 1px solid #f5f0f5;
    }
    .pagination-wrap nav {
        display: flex;
        justify-content: center;
    }
    .pagination-wrap .pagination {
        margin: 0;
    }
    .pagination-wrap .page-item.active .page-link {
        background: var(--pk);
        border-color: var(--pk);
        color: #fff;
    }
    .pagination-wrap .page-link {
        color: #666;
        border-color: #e5e0e5;
    }
    .pagination-wrap .page-link:hover {
        color: var(--pk);
        border-color: var(--pk-light);
    }

    .subject-text {
        max-width: 200px;
        display: inline-block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    @media (max-width: 768px) {
        .filter-bar {
            flex-direction: column;
            align-items: stretch;
        }
        .filter-bar .filter-group {
            flex-wrap: wrap;
        }
        .filter-bar select,
        .filter-bar input {
            min-width: 100%;
        }
        .data-table {
            font-size: 0.8rem;
        }
        .data-table th,
        .data-table td {
            padding: 10px 14px;
        }
        .subject-text {
            max-width: 100px;
        }
    }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="page-header">
    <div>
        <h1><i class="fas fa-exclamation-circle"></i>Complaints</h1>
        <p>Review and resolve client complaints</p>
    </div>
</div>

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-box">
        <div class="number pink">{{ $stats['total'] ?? 0 }}</div>
        <div class="label">Total</div>
    </div>
    <div class="stat-box">
        <div class="number red">{{ $stats['open'] ?? 0 }}</div>
        <div class="label">Open</div>
    </div>
    <div class="stat-box">
        <div class="number orange">{{ $stats['in_review'] ?? 0 }}</div>
        <div class="label">In Review</div>
    </div>
    <div class="stat-box">
        <div class="number green">{{ $stats['resolved'] ?? 0 }}</div>
        <div class="label">Resolved</div>
    </div>
</div>

{{-- Filter Bar --}}
<div class="filter-bar">
    <form method="GET" action="{{ route('admin.complaints.index') }}" style="display:flex;flex-wrap:wrap;align-items:center;gap:12px;width:100%;">
        <div class="filter-group">
            <label>Status</label>
            <select name="status">
                <option value="">All</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                <option value="in_review" {{ request('status') == 'in_review' ? 'selected' : '' }}>In Review</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>
        <div class="filter-group">
            <label>Priority</label>
            <select name="priority">
                <option value="">All</option>
                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
            </select>
        </div>
        <div class="filter-group" style="flex:1;min-width:180px;">
            <label>Search</label>
            <input type="text" name="search" placeholder="Client or subject..." value="{{ request('search') }}" style="min-width:180px;width:100%;">
        </div>
        <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Filter</button>
        <a href="{{ route('admin.complaints.index') }}" class="btn-clear">Clear</a>
    </form>
</div>

{{-- Table --}}
<div class="table-card">
    <div class="card-head">
        <span class="card-title"><i class="fas fa-list"></i>Complaints List</span>
        <span class="record-count">{{ $complaints->total() }} records</span>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Salon</th>
                    <th>Subject</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($complaints as $c)
                <tr onclick="window.location='{{ route('admin.complaints.show', $c->id) }}'">
                    <td style="font-weight:600;color:#aaa;">#{{ $c->id }}</td>
                    <td>
                        <div class="client-cell">
                            <div class="client-avatar">{{ strtoupper(substr($c->client->name ?? 'N', 0, 1)) }}</div>
                            <span class="client-name">{{ $c->client->name ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td>{{ $c->salon->name ?? 'N/A' }}</td>
                    <td>
                        <span class="subject-text" title="{{ $c->subject }}">{{ Str::limit($c->subject, 35) }}</span>
                    </td>
                    <td>
                        <span class="badge-priority priority-{{ $c->priority }}">
                            {{ ucfirst($c->priority) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-status badge-{{ $c->status }}">
                            {{ ucfirst(str_replace('_', ' ', $c->status)) }}
                        </span>
                    </td>
                    <td style="color:#999;font-size:0.8rem;white-space:nowrap;">{{ $c->created_at->format('d M Y') }}</td>
                    <td onclick="event.stopPropagation();">
                        <a href="{{ route('admin.complaints.show', $c->id) }}" class="btn-view">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="empty-cell">
                        <i class="fas fa-check-circle"></i>
                        No complaints found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($complaints->hasPages())
    <div class="pagination-wrap">
        {{ $complaints->links() }}
    </div>
    @endif
</div>

@endsection