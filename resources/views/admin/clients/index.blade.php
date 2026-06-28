@extends('layouts.admin')
@section('title', 'Clients - Glamora')

@section('content')

<style>
:root {
    --dpink:       #c2185b;
    --dpink-lt:    #fce4ec;
    --dpink-hover: #ad1457;
    --frozy:       #00838f;
    --frozy-lt:    #e0f7fa;
    --red-shock:   #b71c1c;
    --red-shock-lt:#ffcdd2;
}

/* ── Page Header ── */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}
.page-header h1 {
    font-size: 1.7rem;
    font-weight: 700;
    margin: 0 0 .25rem;
    color: var(--heading, #1a1a1a);
}
.page-header p { margin: 0; color: var(--muted, #6b7280); font-size: .9rem; }

/* ── Export Buttons ── */
.export-group { display: flex; gap: .7rem; flex-wrap: wrap; align-items: center; }

.btn-export {
    display: inline-flex;
    align-items: center;
    gap: .55rem;
    padding: .62rem 1.15rem;
    border-radius: 9px;
    font-size: .88rem;
    font-weight: 700;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: opacity .18s, transform .12s, box-shadow .18s;
    white-space: nowrap;
    line-height: 1;
}
.btn-export:hover {
    opacity: .9;
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(0,0,0,.18);
}

/* Excel button — green with Excel grid icon */
.btn-export-excel {
    background: #1d6f42;
    color: #fff;
}
.btn-export-excel .export-icon {
    width: 22px;
    height: 22px;
    background: #fff;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.btn-export-excel .export-icon svg { display: block; }

/* PDF button — red with PDF icon */
.btn-export-pdf {
    background: #c62828;
    color: #fff;
}
.btn-export-pdf .export-icon {
    width: 22px;
    height: 22px;
    background: #fff;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.btn-export-pdf .export-icon svg { display: block; }

/* ── Filter Bar ── */
.filter-bar {
    background: #fff;
    border: 1px solid var(--border, #e5e7eb);
    border-radius: 12px;
    padding: 1.2rem 1.5rem;
    margin-bottom: 1.5rem;
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    align-items: flex-end;
}
.filter-group {
    display: flex;
    flex-direction: column;
    gap: .35rem;
    flex: 1;
    min-width: 150px;
}
.filter-group label {
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--muted, #6b7280);
}
.search-wrapper { position: relative; flex: 2; min-width: 240px; }
.search-wrapper i {
    position: absolute;
    left: .95rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--dpink);
    font-size: .88rem;
    pointer-events: none;
}
.search-input {
    width: 100%;
    padding: .7rem 1rem .7rem 2.5rem;
    border: 1.5px solid var(--border, #e5e7eb);
    border-radius: 9px;
    font-size: .93rem;
    background: #f9fafb;
    color: #111;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
    box-sizing: border-box;
}
.search-input:focus {
    border-color: var(--dpink);
    box-shadow: 0 0 0 3px rgba(194,24,91,.1);
    background: #fff;
}
.filter-select {
    width: 100%;
    padding: .7rem 1rem;
    border: 1.5px solid var(--border, #e5e7eb);
    border-radius: 9px;
    font-size: .88rem;
    background: #f9fafb;
    color: #111;
    cursor: pointer;
    outline: none;
    transition: border-color .2s;
}
.filter-select:focus { border-color: var(--dpink); }
.filter-actions { display: flex; gap: .5rem; align-items: flex-end; }
.btn-search {
    padding: .7rem 1.2rem;
    border-radius: 9px;
    font-size: .88rem;
    font-weight: 700;
    cursor: pointer;
    border: none;
    background: var(--dpink);
    color: #fff;
    transition: background .18s;
    white-space: nowrap;
}
.btn-search:hover { background: var(--dpink-hover); }
.btn-clear {
    padding: .7rem .9rem;
    border-radius: 9px;
    font-size: .88rem;
    font-weight: 600;
    cursor: pointer;
    background: transparent;
    border: 1.5px solid var(--border, #e5e7eb);
    color: var(--muted, #6b7280);
    text-decoration: none;
    transition: all .15s;
    white-space: nowrap;
}
.btn-clear:hover { border-color: var(--dpink); color: var(--dpink); }

/* ── Table Card ── */
.clients-card {
    background: #fff;
    border: 1px solid var(--border, #e5e7eb);
    border-radius: 12px;
    overflow: hidden;
}
.clients-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border, #e5e7eb);
    flex-wrap: wrap;
    gap: .75rem;
}
.card-title { font-weight: 700; font-size: 1rem; color: var(--heading, #1a1a1a); }
.result-count {
    font-size: .8rem;
    color: var(--muted, #6b7280);
    background: #f3f4f6;
    padding: .26rem .7rem;
    border-radius: 20px;
}

/* ── Table ── */
.clients-table { width: 100%; border-collapse: collapse; }
.clients-table thead tr { background: #f9fafb; }
.clients-table thead th {
    padding: .82rem 1.2rem;
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--muted, #6b7280);
    text-align: left;
    white-space: nowrap;
    border-bottom: 1px solid var(--border, #e5e7eb);
}
.clients-table tbody tr {
    border-bottom: 1px solid #f3f4f6;
    cursor: pointer;
    transition: background .15s;
}
.clients-table tbody tr:last-child { border-bottom: none; }
.clients-table tbody tr:hover { background: #fdf0f5; }
.clients-table td {
    padding: .9rem 1.2rem;
    font-size: .9rem;
    color: #374151;
    vertical-align: middle;
}

/* ── Avatar ── */
.client-cell { display: flex; align-items: center; gap: .8rem; }
.client-avatar {
    width: 40px; height: 40px;
    border-radius: 50%;
    background: var(--dpink-lt);
    color: var(--dpink);
    font-weight: 800;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border: 2px solid rgba(194,24,91,.15);
}
.client-name  { font-weight: 600; color: #111827; margin-bottom: .1rem; }
.client-email { font-size: .77rem; color: #9ca3af; }

/* ── Badges ── */
.badge {
    display: inline-block;
    padding: .26rem .72rem;
    border-radius: 20px;
    font-size: .73rem;
    font-weight: 700;
}
.badge-success { background: #d1fae5; color: #065f46; }
.badge-danger  { background: #fee2e2; color: #991b1b; }
.badge-warning { background: #fef3c7; color: #92400e; }

/* ── Action Buttons ── */
.action-group { display: flex; gap: .4rem; align-items: center; }

/* View — frozy teal */
.btn-view {
    width: 34px; height: 34px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: .82rem;
    background: var(--frozy-lt);
    color: var(--frozy);
    border: 1.5px solid rgba(0,131,143,.3);
    text-decoration: none;
    transition: all .15s;
    flex-shrink: 0;
}
.btn-view:hover { background: var(--frozy); color: #fff; border-color: var(--frozy); }

/* Suspend — dark shocking red FILLED */
.btn-suspend {
    width: 34px; height: 34px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: .82rem;
    background: var(--red-shock);
    color: #fff;
    border: none;
    cursor: pointer;
    transition: all .15s;
    flex-shrink: 0;
    box-shadow: 0 2px 6px rgba(183,28,28,.35);
}
.btn-suspend:hover {
    background: #7f0000;
    box-shadow: 0 3px 10px rgba(127,0,0,.4);
    transform: translateY(-1px);
}

/* Activate — green filled */
.btn-activate {
    width: 34px; height: 34px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: .82rem;
    background: #2e7d32;
    color: #fff;
    border: none;
    cursor: pointer;
    transition: all .15s;
    flex-shrink: 0;
    box-shadow: 0 2px 6px rgba(46,125,50,.3);
}
.btn-activate:hover {
    background: #1b5e20;
    box-shadow: 0 3px 10px rgba(27,94,32,.4);
    transform: translateY(-1px);
}

/* ── Empty / Pagination ── */
.empty-state { text-align: center; padding: 3.5rem 1rem; color: #9ca3af; }
.empty-state i { font-size: 2.2rem; margin-bottom: .8rem; opacity: .35; display: block; }
.pagination-wrapper { padding: 1rem 1.5rem; border-top: 1px solid var(--border, #e5e7eb); }

@media (max-width: 768px) {
    .filter-bar { flex-direction: column; }
    .search-wrapper, .filter-group { min-width: 100%; }
    .filter-actions, .export-group { width: 100%; }
    .btn-search, .btn-clear { flex: 1; text-align: center; }
    .btn-export { flex: 1; justify-content: center; }
    .clients-table th:nth-child(4), .clients-table td:nth-child(4) { display: none; }
}
</style>

{{-- ── Page Header ── --}}
<div class="page-header">
    <div>
        <h1>
            <i class="fas fa-users" style="color:var(--dpink);margin-right:.5rem;"></i>
            Clients
        </h1>
        <p>{{ $clients->total() }} total registered clients</p>
    </div>

    {{-- Export Buttons ── --}}
    <div class="export-group">

        {{-- Excel Button — green with X grid icon --}}
        <a href="{{ route('admin.clients.export', request()->query()) }}" class="btn-export btn-export-excel">
            <span class="export-icon">
                {{-- Excel "X" SVG icon --}}
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="14" height="14" rx="2" fill="#1d6f42"/>
                    <path d="M3 3.5L5.5 7L3 10.5H4.8L6.5 7.9L8.2 10.5H10L7.5 7L10 3.5H8.2L6.5 6.1L4.8 3.5H3Z" fill="white"/>
                    <rect x="10" y="3.5" width="1" height="7" fill="white" opacity="0.5"/>
                </svg>
            </span>
            Download Excel
        </a>

        {{-- PDF Button — red with PDF icon --}}
        <a href="{{ route('admin.clients.export.pdf', request()->query()) }}" class="btn-export btn-export-pdf">
            <span class="export-icon">
                {{-- PDF icon SVG --}}
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="14" height="14" rx="2" fill="#c62828"/>
                    <path d="M3 2H8.5L11 4.5V12H3V2Z" fill="white" opacity="0.15"/>
                    <path d="M8 2V5H11" stroke="white" stroke-width="1" fill="none" opacity="0.8"/>
                    <path d="M3 2H8L11 5V12H3V2Z" stroke="white" stroke-width="1" fill="none"/>
                    <text x="4" y="10" font-size="4" fill="white" font-family="Arial" font-weight="bold">PDF</text>
                </svg>
            </span>
            Download PDF
        </a>

    </div>
</div>

{{-- ── Filter Bar ── --}}
<form method="GET" action="{{ route('admin.clients.index') }}" id="filterForm">
<div class="filter-bar">

    <div class="search-wrapper">
        <i class="fas fa-search"></i>
        <input
            type="text"
            name="search"
            class="search-input"
            placeholder="Search by name, email or phone…"
            value="{{ request('search') }}"
            autocomplete="off"
        >
    </div>

    <div class="filter-group">
        <label>Status</label>
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">All Statuses</option>
            <option value="active"    {{ request('status')=='active'    ? 'selected':'' }}>Active</option>
            <option value="suspended" {{ request('status')=='suspended' ? 'selected':'' }}>Suspended</option>
        </select>
    </div>

    <div class="filter-group">
        <label>City</label>
        <select name="city" class="filter-select" onchange="this.form.submit()">
            <option value="">All Cities</option>
            @foreach($cities as $city)
                <option value="{{ $city }}" {{ request('city')==$city ? 'selected':'' }}>{{ $city }}</option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <label>Sort By</label>
        <select name="sort" class="filter-select" onchange="this.form.submit()">
            <option value="newest"   {{ request('sort','newest')=='newest'   ? 'selected':'' }}>Newest First</option>
            <option value="oldest"   {{ request('sort')=='oldest'   ? 'selected':'' }}>Oldest First</option>
            <option value="name_asc" {{ request('sort')=='name_asc' ? 'selected':'' }}>Name A–Z</option>
            <option value="bookings" {{ request('sort')=='bookings' ? 'selected':'' }}>Most Bookings</option>
        </select>
    </div>

    <div class="filter-actions">
        <button type="submit" class="btn-search">
            <i class="fas fa-search"></i> Search
        </button>
        @if(request()->hasAny(['search','status','city','sort']))
            <a href="{{ route('admin.clients.index') }}" class="btn-clear">
                <i class="fas fa-times"></i> Clear
            </a>
        @endif
    </div>

</div>
</form>

{{-- ── Table Card ── --}}
<div class="clients-card">
    <div class="clients-card-header">
        <span class="card-title">Client List</span>
        <span class="result-count">
            Showing {{ $clients->firstItem() ?? 0 }}–{{ $clients->lastItem() ?? 0 }}
            of {{ $clients->total() }}
        </span>
    </div>

    <div style="overflow-x:auto;">
        <table class="clients-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>Bookings</th>
                    <th>Joined</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                <tr onclick="window.location='{{ route('admin.clients.show', $client->id) }}'">
                    <td style="color:#9ca3af;font-size:.8rem;">#{{ $client->id }}</td>
                    <td>
                        <div class="client-cell">
                            <div class="client-avatar">{{ strtoupper(substr($client->name,0,1)) }}</div>
                            <div>
                                <div class="client-name">{{ $client->name }}</div>
                                <div class="client-email">{{ $client->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $client->phone ?? '—' }}</td>
                    <td>{{ $client->city ?? '—' }}</td>
                    <td><span class="badge badge-warning">{{ $client->appointments_count ?? 0 }}</span></td>
                    <td style="font-size:.82rem;color:#6b7280;">{{ $client->created_at->format('d M Y') }}</td>
                    <td>
                        <span class="badge {{ $client->is_active ? 'badge-success' : 'badge-danger' }}">
                            {{ $client->is_active ? 'Active' : 'Suspended' }}
                        </span>
                    </td>
                    <td onclick="event.stopPropagation()">
                        <div class="action-group">
                            {{-- View — frozy teal --}}
                            <a href="{{ route('admin.clients.show', $client->id) }}"
                               class="btn-view" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            {{-- Suspend / Activate --}}
                            <form action="{{ route('admin.clients.toggle', $client->id) }}" method="POST" style="margin:0;">
                                @csrf
                                <button type="submit"
                                    class="{{ $client->is_active ? 'btn-suspend' : 'btn-activate' }}"
                                    title="{{ $client->is_active ? 'Suspend Client' : 'Activate Client' }}"
                                    onclick="return confirm('{{ $client->is_active ? 'Suspend this client?' : 'Activate this client?' }}')">
                                    <i class="fas {{ $client->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            No clients found matching your filters.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($clients->hasPages())
    <div class="pagination-wrapper">
        {{ $clients->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@endsection