@extends('layouts.admin')

@section('title', 'Audit Logs — Glamora')

@push('styles')
<style>
    :root { --pk:#E91E8C; --pk-dark:#c2185b; --pk-lt:#fce4ec; --pk-bg:#fff0f7; }

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
    .header-left h4 { font-size: 1.45rem; font-weight: 800; color: #2d2d2d; margin: 0; font-family: 'Playfair Display', serif; }
    .header-left h4 i {
        color: #fff;
        background: linear-gradient(135deg, var(--pk), var(--pk-dark));
        width: 38px; height: 38px; border-radius: 12px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.95rem; margin-right: 10px;
        box-shadow: 0 4px 12px rgba(233,30,140,.28);
        vertical-align: middle;
    }
    .header-left p { color: #aaa; font-size: 0.85rem; margin: 6px 0 0 48px; }
    .header-right { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }

    /* ============================================================ */
    /* EXPORT BUTTONS — authentic file-type colors, solid + icon */
    /* ============================================================ */
    .btn-export {
        display: inline-flex; align-items: center; gap: 9px;
        padding: 10px 22px; border-radius: 12px; font-weight: 700; font-size: 0.85rem;
        text-decoration: none; border: none; cursor: pointer; white-space: nowrap;
        box-shadow: 0 4px 12px rgba(0,0,0,.12); transition: box-shadow .18s;
        outline: none;
    }
    .btn-export:hover { color: #fff; }
    .btn-export:focus, .btn-export:active { outline: none; }
    .btn-export i { font-size: 1.05rem; }

    /* Excel green — same authentic shade used elsewhere in the app */
    .btn-export-csv { background: linear-gradient(135deg, #1d8a4e, #1d6f42); color: #fff; }
    .btn-export-csv:hover { box-shadow: 0 8px 22px rgba(29,111,66,.38); }

    /* PDF red — authentic Adobe-style red */
    .btn-export-pdf { background: linear-gradient(135deg, #e5352b, #c0392b); color: #fff; }
    .btn-export-pdf:hover { box-shadow: 0 8px 22px rgba(192,57,43,.38); }

    /* ============================================================ */
    /* FILTER CARD */
    /* ============================================================ */
    .filter-card {
        background: #fff; border-radius: 20px; padding: 1.4rem 1.6rem;
        margin-bottom: 1.5rem; border: 1px solid var(--pk-lt);
        box-shadow: 0 4px 18px rgba(233,30,140,.05);
    }
    .filter-card-title {
        font-size: .78rem; font-weight: 800; color: var(--pk-dark); text-transform: uppercase;
        letter-spacing: .05em; margin-bottom: 1.1rem; display: flex; align-items: center; gap: 8px;
    }
    .filter-card-title i {
        background: var(--pk-bg); color: var(--pk); width: 26px; height: 26px; border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;
    }

    .search-box { position: relative; flex: 2 1 260px; }
    .search-box i {
        position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
        color: var(--pk); opacity: .55; font-size: .85rem; pointer-events: none;
    }
    .search-box input {
        width: 100%; box-sizing: border-box; padding: 11px 16px 11px 40px;
        border-radius: 50px; border: 1.5px solid var(--pk-lt); font-size: .85rem;
        background: #fdf7fa; outline: none; transition: all .18s; color: #333;
    }
    .search-box input::placeholder { color: #c9a8b8; }
    .search-box input:focus { border-color: var(--pk); background: #fff; box-shadow: 0 0 0 4px rgba(233,30,140,.1); }

    .filter-row { display: flex; align-items: flex-end; gap: 12px; flex-wrap: wrap; }
    .filter-group { display: flex; flex-direction: column; flex: 1 1 130px; min-width: 0; }
    .filter-group.sm { flex: 1 1 120px; }
    .filter-group label {
        font-size: .66rem; font-weight: 700; color: #b9a3ae; text-transform: uppercase;
        letter-spacing: .05em; margin-bottom: 6px;
    }
    .filter-group select, .filter-group input[type=date] {
        width: 100%; box-sizing: border-box; padding: 10px 12px; border-radius: 11px;
        border: 1.5px solid var(--pk-lt); font-size: .82rem; background: #fdf7fa; outline: none;
        font-family: inherit; color: #333; transition: all .18s;
    }
    .filter-group select:focus, .filter-group input[type=date]:focus {
        border-color: var(--pk); background: #fff; box-shadow: 0 0 0 4px rgba(233,30,140,.1);
    }

    .filter-actions { display: flex; gap: 8px; flex: 0 0 auto; }

    /* ===== Filter button — pretty pill, matches theme, no movement, no browser focus ring ===== */
    .btn-apply {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 11px 26px; border-radius: 50px; font-size: .84rem; font-weight: 700;
        background: linear-gradient(135deg, var(--pk), var(--pk-dark)); color: #fff; border: none; cursor: pointer;
        white-space: nowrap; box-shadow: 0 4px 14px rgba(233,30,140,.3);
        transition: box-shadow .18s ease, opacity .18s ease;
        outline: none;
    }
    .btn-apply:hover {
        box-shadow: 0 6px 18px rgba(233,30,140,.4);
        opacity: .92;
        color: #fff;
    }
    .btn-apply:focus,
    .btn-apply:active {
        outline: none;
        box-shadow: 0 4px 14px rgba(233,30,140,.3);
    }

    .btn-clear {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 11px 20px; border-radius: 50px; font-size: .84rem; font-weight: 600;
        background: #fff; border: 1.5px solid var(--pk-lt); color: #aaa;
        text-decoration: none; white-space: nowrap; transition: all .18s;
        outline: none;
    }
    .btn-clear:hover { border-color: var(--pk); color: var(--pk); background: var(--pk-bg); }
    .btn-clear:focus, .btn-clear:active { outline: none; }

    .total-pill {
        font-size: .78rem; color: var(--pk-dark); background: var(--pk-bg); padding: 6px 18px;
        border-radius: 50px; font-weight: 700; white-space: nowrap; display: inline-flex; align-items: center; gap: 6px;
        border: 1px solid var(--pk-lt);
    }

    /* ============================================================ */
    /* TABLE */
    /* ============================================================ */
    .data-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
    .data-table thead th {
        background: var(--pk-bg); padding: 12px 14px; text-align: left; font-weight: 800; color: var(--pk-dark);
        border-bottom: 1px solid var(--pk-lt); font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.05em;
    }
    .data-table tbody td { padding: 12px 14px; border-bottom: 1px solid #f5f5f5; vertical-align: middle; cursor: pointer; }
    .data-table tbody tr { transition: background .15s; }
    .data-table tbody tr:hover { background: #fdf2f8; }
    .data-table tbody tr:last-child td { border-bottom: none; }

    .badge-action { padding: 4px 12px; border-radius: 50px; font-size: 0.65rem; font-weight: 700; display: inline-block; }
    .badge-status-success { background: #dcfce7; color: #16a34a; padding: 4px 12px; border-radius: 50px; font-size: 0.65rem; font-weight: 700; }
    .badge-status-failed  { background: #fee2e2; color: #dc2626; padding: 4px 12px; border-radius: 50px; font-size: 0.65rem; font-weight: 700; }
    .badge-status-pending { background: #fef3c7; color: #d97706; padding: 4px 12px; border-radius: 50px; font-size: 0.65rem; font-weight: 700; }
    .role-badge { padding: 3px 10px; border-radius: 50px; font-size: 0.58rem; font-weight: 700; }
    .role-admin  { background: #fce4ec; color: #c2185b; }
    .role-owner  { background: #e3f2fd; color: #0d47a1; }
    .role-client { background: #e8f5e9; color: #1b5e20; }

    .user-info { display: flex; align-items: center; gap: 9px; }
    .user-avatar { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 2px solid var(--pk-lt); }
    .user-name { font-weight: 700; color: #333; font-size: 0.8rem; }
    .user-details { display: flex; flex-direction: column; gap: 3px; }

    .date-time .date { color: #333; font-size: 0.8rem; }
    .date-time .time-ago { color: #bbb; font-size: 0.68rem; }

    .empty-state { text-align: center; padding: 3.5rem 2rem; }
    .empty-state i {
        font-size: 2.4rem; color: var(--pk-dark); opacity: .5;
        width: 90px; height: 90px; border-radius: 50%; background: var(--pk-bg);
        display: flex; align-items: center; justify-content: center; margin: 0 auto 1.1rem;
    }
    .empty-state h6 { color: #333; font-weight: 700; font-size: 1rem; }
    .empty-state p { color: #aaa; font-size: 0.85rem; }

    .pagination-wrapper { padding: 1rem 1.5rem; border-top: 1px solid #f5f5f5; display: flex; justify-content: center; }
    .table-responsive { max-height: 550px; overflow-y: auto; }
    .card { border-radius: 20px; border: 1px solid var(--pk-lt); overflow: hidden; box-shadow: 0 4px 18px rgba(233,30,140,.05); }
    .card-header { background: #fff; padding: 1.1rem 1.5rem; border-bottom: 1px solid var(--pk-lt); }
    .card-title { font-weight: 700; color: #333; font-size: 0.95rem; }
</style>
@endpush

@section('content')

{{-- HEADER --}}
<div class="header-section">
    <div class="header-left">
        <h4><i class="fas fa-history"></i>Audit Logs</h4>
        <p>System activity log — all user actions recorded</p>
    </div>
    <div class="header-right">
        <a href="{{ route('admin.audit-logs.export-csv', request()->query()) }}" class="btn-export btn-export-csv" target="_blank">
            <i class="fas fa-file-csv"></i> Export CSV
        </a>
        <a href="{{ route('admin.audit-logs.export-pdf', request()->query()) }}" class="btn-export btn-export-pdf" target="_blank">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div>
</div>

{{-- FILTERS --}}
<div class="filter-card">
    <div class="filter-card-title"><i class="fas fa-filter"></i> Search &amp; Filter</div>
    <form method="GET">
        <div class="filter-row">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Search by user, action, module, IP..." value="{{ request('search') }}">
            </div>

            <div class="filter-group sm">
                <label>Action</label>
                <select name="action">
                    <option value="">All</option>
                    @foreach($actions as $action)
                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group sm">
                <label>Role</label>
                <select name="role">
                    <option value="">All</option>
                    <option value="admin"  {{ request('role') == 'admin'  ? 'selected' : '' }}>Admin</option>
                    <option value="owner"  {{ request('role') == 'owner'  ? 'selected' : '' }}>Owner</option>
                    <option value="client" {{ request('role') == 'client' ? 'selected' : '' }}>Client</option>
                </select>
            </div>

            <div class="filter-group sm">
                <label>From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}">
            </div>

            <div class="filter-group sm">
                <label>To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}">
            </div>

            <div class="filter-group sm">
                <label>Status</label>
                <select name="status">
                    <option value="">All</option>
                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                    <option value="failed"  {{ request('status') == 'failed'  ? 'selected' : '' }}>Failed</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-apply">Filter</button>
                <a href="{{ route('admin.audit-logs.index') }}" class="btn-clear"><i class="fas fa-rotate-left"></i> Clear</a>
            </div>
        </div>
    </form>
    <div class="mt-3">
        <span class="total-pill"><i class="fas fa-database"></i> {{ $logs->total() }} record(s) found</span>
    </div>
</div>

{{-- TABLE --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="card-title">
            <i class="fas fa-list me-2" style="color:var(--pk);"></i>Activity Log
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
                    <th style="min-width:150px;">Date &amp; Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr onclick="window.location='{{ route('admin.audit-logs.show', $log->id) }}'">
                    <td style="font-weight:700;color:#aaa;font-size:0.75rem;">#{{ $log->id }}</td>
                    <td>
                        <div class="user-info">
                            <img src="{{ $log->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($log->user->name ?? 'System').'&background=E91E8C&color=fff' }}" class="user-avatar">
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
                            <p>Try adjusting your search or filters</p>
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