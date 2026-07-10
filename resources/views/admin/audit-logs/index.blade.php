@extends('layouts.admin')

@section('title', 'Audit Logs — Glamora')

@push('styles')
<style>
    /* ============================================================ */
    /* HEADER */
    /* ============================================================ */
    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 10px;
    }
    .header-left h4 {
        font-size: 1.4rem;
        font-weight: 700;
        color: #333;
        margin: 0;
    }
    .header-left h4 i {
        color: #E91E8C;
        margin-right: 8px;
    }
    .header-left p {
        color: #aaa;
        font-size: 0.85rem;
        margin: 4px 0 0;
    }
    .header-right {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }

    /* ============================================================ */
    /* EXPORT BUTTONS - EXACT COLORS */
    /* ============================================================ */
    .btn-export-csv {
        background: #e8f5e9;
        color: #2e7d32;
        border: 1.5px solid #4caf50;
        border-radius: 50px;
        padding: 10px 22px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        white-space: nowrap;
    }
    .btn-export-csv:hover {
        background: #4caf50;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.4);
    }
    .btn-export-csv i {
        font-size: 1.1rem;
        color: #2e7d32;
    }
    .btn-export-csv:hover i {
        color: #fff;
    }

    .btn-export-pdf {
        background: #fce4ec;
        color: #c62828;
        border: 1.5px solid #ef5350;
        border-radius: 50px;
        padding: 10px 22px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        white-space: nowrap;
    }
    .btn-export-pdf:hover {
        background: #ef5350;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(239, 83, 80, 0.4);
    }
    .btn-export-pdf i {
        font-size: 1.1rem;
        color: #c62828;
    }
    .btn-export-pdf:hover i {
        color: #fff;
    }

    /* ============================================================ */
    /* FILTER ROW */
    /* ============================================================ */
    .filter-row {
        background: #fff;
        border-radius: 16px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .filter-row .form-control,
    .filter-row .form-select {
        border-radius: 10px;
        border: 1.5px solid #f0f0f0;
        font-size: 0.8rem;
        padding: 7px 12px;
        min-width: 120px;
    }
    .filter-row .form-control:focus,
    .filter-row .form-select:focus {
        border-color: #E91E8C;
        box-shadow: 0 0 0 3px rgba(233, 30, 140, 0.1);
    }
    .filter-row .form-label {
        font-size: 0.65rem;
        font-weight: 600;
        color: #888;
        margin-bottom: 2px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .filter-group {
        display: flex;
        flex-direction: column;
    }
    .filter-group-sm {
        min-width: 110px;
    }
    .filter-group-md {
        min-width: 150px;
    }
    .filter-group-lg {
        min-width: 180px;
    }
    .btn-pink {
        background: #E91E8C;
        color: #fff;
        border: none;
        border-radius: 50px;
        padding: 7px 18px;
        font-weight: 600;
        font-size: 0.8rem;
        transition: all 0.3s;
        cursor: pointer;
        white-space: nowrap;
    }
    .btn-pink:hover {
        background: #c2185b;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(233, 30, 140, 0.3);
    }
    .btn-outline-pink {
        background: transparent;
        color: #E91E8C;
        border: 1.5px solid #E91E8C;
        border-radius: 50px;
        padding: 7px 18px;
        font-weight: 600;
        font-size: 0.8rem;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        white-space: nowrap;
    }
    .btn-outline-pink:hover {
        background: #E91E8C;
        color: #fff;
    }
    .filter-actions {
        display: flex;
        gap: 8px;
        align-items: center;
        margin-left: auto;
    }
    .total-badge {
        color: #aaa;
        font-size: 0.8rem;
        white-space: nowrap;
    }
    .total-badge i {
        margin-right: 4px;
    }
    .search-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        flex: 1;
    }

    /* ============================================================ */
    /* TABLE */
    /* ============================================================ */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }
    .data-table thead th {
        background: #f8f9fa;
        padding: 10px 14px;
        text-align: left;
        font-weight: 600;
        color: #555;
        border-bottom: 2px solid #f0f0f0;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    .data-table tbody td {
        padding: 10px 14px;
        border-bottom: 1px solid #f5f5f5;
        vertical-align: middle;
        cursor: pointer;
    }
    .data-table tbody tr:hover {
        background: #fdf2f8;
    }
    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* ============================================================ */
    /* BADGES */
    /* ============================================================ */
    .badge-action {
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.65rem;
        font-weight: 600;
        display: inline-block;
    }
    .badge-status-success {
        background: #dcfce7;
        color: #16a34a;
        padding: 3px 10px;
        border-radius: 50px;
        font-size: 0.65rem;
        font-weight: 600;
    }
    .badge-status-failed {
        background: #fee2e2;
        color: #dc2626;
        padding: 3px 10px;
        border-radius: 50px;
        font-size: 0.65rem;
        font-weight: 600;
    }
    .badge-status-pending {
        background: #fef3c7;
        color: #d97706;
        padding: 3px 10px;
        border-radius: 50px;
        font-size: 0.65rem;
        font-weight: 600;
    }
    .role-badge {
        padding: 2px 8px;
        border-radius: 50px;
        font-size: 0.55rem;
        font-weight: 600;
    }
    .role-admin {
        background: #fce4ec;
        color: #c2185b;
    }
    .role-owner {
        background: #e3f2fd;
        color: #0d47a1;
    }
    .role-client {
        background: #e8f5e9;
        color: #1b5e20;
    }

    /* ============================================================ */
    /* USER INFO */
    /* ============================================================ */
    .user-info {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .user-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #f0f0f0;
    }
    .user-name {
        font-weight: 600;
        color: #333;
        font-size: 0.8rem;
    }
    .user-details {
        display: flex;
        flex-direction: column;
    }

    /* ============================================================ */
    /* DATE TIME */
    /* ============================================================ */
    .date-time .date {
        color: #333;
        font-size: 0.8rem;
    }
    .date-time .time-ago {
        color: #aaa;
        font-size: 0.65rem;
    }

    /* ============================================================ */
    /* EMPTY STATE */
    /* ============================================================ */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
    }
    .empty-state i {
        font-size: 3.5rem;
        color: rgba(233, 30, 140, 0.15);
        display: block;
        margin-bottom: 1rem;
    }
    .empty-state h6 {
        color: #333;
        font-weight: 600;
        font-size: 1rem;
    }
    .empty-state p {
        color: #aaa;
        font-size: 0.85rem;
    }

    /* ============================================================ */
    /* PAGINATION */
    /* ============================================================ */
    .pagination-wrapper {
        padding: 1rem 1.5rem;
        border-top: 1px solid #f0f0f0;
        display: flex;
        justify-content: center;
    }
    .table-responsive {
        max-height: 550px;
        overflow-y: auto;
    }
    .card {
        border-radius: 16px;
        border: 1px solid #f0f0f0;
        overflow: hidden;
    }
    .card-header {
        background: #fff;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f0f0f0;
    }
    .card-title {
        font-weight: 600;
        color: #333;
        font-size: 0.95rem;
    }
</style>
@endpush

@section('content')

{{-- ============================================================ --}}
{{-- HEADER --}}
{{-- ============================================================ --}}
<div class="header-section">
    <div class="header-left">
        <h4><i class="fas fa-history"></i>Audit Logs</h4>
        <p>System activity log — all user actions recorded</p>
    </div>
    <div class="header-right">
        {{-- CSV Export --}}
        <a href="{{ url('/admin/audit-logs/export-csv?' . http_build_query(request()->query())) }}" class="btn-export-csv" target="_blank">
            <i class="fas fa-file-csv"></i> Export CSV
        </a>
        {{-- PDF Export --}}
        <a href="{{ url('/admin/audit-logs/export-pdf?' . http_build_query(request()->query())) }}" class="btn-export-pdf" target="_blank">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div>
</div>

{{-- ============================================================ --}}
{{-- FILTERS --}}
{{-- ============================================================ --}}
<div class="filter-row">
    <form method="GET" class="search-wrapper" style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;width:100%;">
        <div class="filter-group filter-group-lg">
            <label class="form-label"><i class="fas fa-search me-1"></i>Search</label>
            <input type="text" name="search" class="form-control" placeholder="User, action..." value="{{ request('search') }}">
        </div>

        <div class="filter-group filter-group-sm">
            <label class="form-label"><i class="fas fa-tag me-1"></i>Action</label>
            <select name="action" class="form-select">
                <option value="">All</option>
                @foreach($actions as $action)
                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                    {{ ucfirst($action) }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="filter-group filter-group-sm">
            <label class="form-label"><i class="fas fa-user me-1"></i>Role</label>
            <select name="role" class="form-select">
                <option value="">All</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="owner" {{ request('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                <option value="client" {{ request('role') == 'client' ? 'selected' : '' }}>Client</option>
            </select>
        </div>

        <div class="filter-group filter-group-sm">
            <label class="form-label"><i class="fas fa-calendar me-1"></i>From</label>
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>

        <div class="filter-group filter-group-sm">
            <label class="form-label"><i class="fas fa-calendar me-1"></i>To</label>
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>

        <div class="filter-group filter-group-sm">
            <label class="form-label"><i class="fas fa-circle me-1"></i>Status</label>
            <select name="status" class="form-select">
                <option value="">All</option>
                <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            </select>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn-pink">
                <i class="fas fa-filter me-1"></i> Apply
            </button>
            <a href="{{ url('/admin/audit-logs') }}" class="btn-outline-pink" style="border-color:#ddd;color:#666;">
                <i class="fas fa-times me-1"></i> Clear
            </a>
            <span class="total-badge">
                <i class="fas fa-database"></i> {{ $logs->total() }}
            </span>
        </div>
    </form>
</div>

{{-- ============================================================ --}}
{{-- TABLE --}}
{{-- ============================================================ --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="card-title">
            <i class="fas fa-list me-2" style="color:#E91E8C;"></i>Activity Log
            <span class="badge" style="background:#fce4ec;color:#E91E8C;margin-left:8px;font-weight:600;">{{ $logs->total() }}</span>
        </span>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:45px;">#</th>
                    <th style="min-width:150px;">User</th>
                    <th style="min-width:130px;">Action</th>
                    <th style="min-width:100px;">Module</th>
                    <th style="min-width:100px;">IP</th>
                    <th style="min-width:80px;">Status</th>
                    <th style="min-width:150px;">Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr onclick="window.location='{{ url('/admin/audit-logs/' . $log->id) }}'">
                    <td style="font-weight:600;color:#aaa;font-size:0.75rem;">#{{ $log->id }}</td>
                    <td>
                        <div class="user-info">
                            <img src="{{ $log->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($log->user->name ?? 'System').'&background=E91E8C&color=fff' }}"
                                 class="user-avatar">
                            <div class="user-details">
                                <span class="user-name">{{ $log->user->name ?? 'System' }}</span>
                                <span class="role-badge role-{{ $log->user->role ?? 'client' }}">
                                    {{ $log->role_label ?? ucfirst($log->user->role ?? 'Client') }}
                                </span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge-action" style="background:{{ $log->action_color ?? '#6b7280' }}20;color:{{ $log->action_color ?? '#6b7280' }};">
                            {{ ucfirst($log->action) }}
                        </span>
                    </td>
                    <td style="color:#555;font-size:0.8rem;">{{ $log->module ?? '—' }}</td>
                    <td style="font-size:0.75rem;color:#888;font-family:monospace;">{{ $log->ip_address ?? '—' }}</td>
                    <td>
                        <span class="badge-status-{{ $log->status ?? 'success' }}">
                            {{ ucfirst($log->status ?? 'Success') }}
                        </span>
                    </td>
                    <td>
                        <div class="date-time">
                            <div class="date">{{ $log->created_at->format('d M Y, h:i A') }}</div>
                            <div class="time-ago">{{ $log->created_at->diffForHumans() }}</div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h6>No audit logs found</h6>
                            <p>All system activities will be recorded here automatically</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="pagination-wrapper">
        {{ $logs->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@endsection