@extends('layouts.admin')
@section('title', 'Clients - Beauty Blush Salons')

@push('styles')
<style>
    :root {
        --gl-pink: #FF6B9D;
        --gl-pink-dark: #E85588;
        --gl-pink-light: #FDEAF3;
        --gl-pink-pale: #F1DCE9;
        --gl-text: #2B2230;
        --gl-text-lt: #B98BA6;
        --gl-border: #F1DCE9;
        --gl-green: #1E8E3E;
        --gl-green-light: #E3F6E9;
        --gl-red: #D93025;
        --gl-red-light: #FCE8E6;
        --gl-blue: #1967D2;
        --gl-blue-light: #E8F0FE;
    }

    .gl-clients-page { max-width: 1180px; margin: 0 auto; box-sizing: border-box; }
    .gl-clients-page * { box-sizing: border-box; }

    /* Page Header */
    .gl-page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .gl-page-header h1 { font-size: 1.5rem; font-weight: 800; color: var(--gl-text); margin: 0 0 4px; display: flex; align-items: center; gap: 10px; }
    .gl-page-header h1 i { color: var(--gl-pink); }
    .gl-page-header p { margin: 0; color: var(--gl-text-lt); font-size: 0.88rem; font-weight: 600; }

    /* Export Buttons Group */
    .gl-export-group { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
    .gl-btn-export { display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; border-radius: 12px; font-size: 0.84rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.18s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
    .gl-btn-export:hover { transform: translateY(-1px); opacity: 0.92; }
    
    .gl-btn-excel { background: #1d6f42; color: #fff; }
    .gl-btn-pdf { background: #C62828; color: #fff; }
    .gl-export-icon { width: 20px; height: 20px; background: #fff; border-radius: 4px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

    /* Filter Bar */
    .gl-filter-bar { background: #fff; border: 1px solid var(--gl-border); border-radius: 20px; padding: 20px 24px; margin-bottom: 24px; display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-end; box-shadow: 0 2px 10px rgba(255,107,157,0.03); }
    .gl-filter-group { display: flex; flex-direction: column; gap: 6px; flex: 1; min-width: 140px; }
    .gl-filter-group label { font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: var(--gl-text-lt); }
    
    .gl-search-wrapper { position: relative; flex: 2; min-width: 240px; }
    .gl-search-wrapper i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--gl-pink); font-size: 0.88rem; pointer-events: none; }
    .gl-search-input { width: 100%; padding: 10px 14px 10px 38px; border: 1.5px solid var(--gl-border); border-radius: 12px; font-size: 0.9rem; background: #FAFAFC; color: var(--gl-text); outline: none; transition: all 0.2s ease; font-weight: 500; }
    .gl-search-input:focus { border-color: var(--gl-pink); box-shadow: 0 0 0 3px var(--gl-pink-light); background: #fff; }

    /* Dropdown Filter Selects — Cleaned up font weights */
    .gl-filter-select { width: 100%; padding: 10px 14px; border: 1.5px solid var(--gl-border); border-radius: 12px; font-size: 0.86rem; background: #FAFAFC; color: var(--gl-text); cursor: pointer; outline: none; transition: border-color 0.2s ease; font-weight: 500; }
    .gl-filter-select option { font-weight: normal; color: var(--gl-text); padding: 6px; }
    .gl-filter-select:focus { border-color: var(--gl-pink); background: #fff; }

    .gl-filter-actions { display: flex; gap: 8px; align-items: flex-end; }
    .gl-btn-search { padding: 10px 18px; border-radius: 12px; font-size: 0.85rem; font-weight: 700; cursor: pointer; border: none; background: var(--gl-pink); color: #fff; transition: background 0.18s ease; display: inline-flex; align-items: center; gap: 6px; }
    .gl-btn-search:hover { background: var(--gl-pink-dark); }
    .gl-btn-clear { padding: 10px 14px; border-radius: 12px; font-size: 0.85rem; font-weight: 700; cursor: pointer; background: #fff; border: 1.5px solid var(--gl-border); color: var(--gl-text-lt); text-decoration: none; transition: all 0.15s ease; display: inline-flex; align-items: center; gap: 6px; }
    .gl-btn-clear:hover { border-color: var(--gl-pink); color: var(--gl-pink); background: var(--gl-pink-light); }

    /* Table Card */
    .gl-clients-card { background: #fff; border: 1px solid var(--gl-border); border-radius: 20px; overflow: hidden; box-shadow: 0 2px 12px rgba(255, 107, 157, 0.05); }
    .gl-clients-card-header { display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; border-bottom: 1px solid var(--gl-border); flex-wrap: wrap; gap: 12px; background: #fff; }
    .gl-card-title { font-weight: 800; font-size: 0.95rem; color: var(--gl-text); text-transform: uppercase; letter-spacing: 0.6px; }
    .gl-result-count { font-size: 0.76rem; font-weight: 700; color: var(--gl-text-lt); background: var(--gl-pink-light); padding: 4px 12px; border-radius: 20px; }

    /* Clients Table */
    .gl-clients-table { width: 100%; border-collapse: collapse; }
    .gl-clients-table thead tr { background: #FAFAFC; }
    .gl-clients-table thead th { padding: 12px 20px; font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: var(--gl-text-lt); text-align: left; white-space: nowrap; border-bottom: 1px solid var(--gl-border); }
    .gl-clients-table tbody tr { border-bottom: 1px solid #F8F5F7; cursor: pointer; transition: background 0.15s ease; }
    .gl-clients-table tbody tr:last-child { border-bottom: none; }
    .gl-clients-table tbody tr:hover { background: #FFFBFD; }
    .gl-clients-table td { padding: 14px 20px; font-size: 0.88rem; color: var(--gl-text); vertical-align: middle; font-weight: 500; }

    /* Client Cell Components */
    .gl-client-cell { display: flex; align-items: center; gap: 12px; }
    .gl-client-avatar { width: 40px; height: 40px; border-radius: 14px; background: var(--gl-pink-light); color: var(--gl-pink); font-weight: 800; font-size: 0.95rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid var(--gl-pink-pale); }
    .gl-client-name { font-weight: 700; color: var(--gl-text); margin-bottom: 2px; }
    .gl-client-email { font-size: 0.76rem; color: var(--gl-text-lt); font-weight: 500; }

    /* Badges */
    .gl-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 20px; font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.4px; }
    .gl-badge-success { background: var(--gl-green-light); color: var(--gl-green); }
    .gl-badge-danger { background: var(--gl-red-light); color: var(--gl-red); }
    .gl-badge-warning { background: var(--gl-pink-light); color: var(--gl-pink-dark); }

    /* Action Buttons Group */
    .gl-action-group { display: flex; gap: 6px; align-items: center; }
    
    .gl-btn-view { width: 34px; height: 34px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem; background: var(--gl-blue-light); color: var(--gl-blue); border: 1px solid rgba(25,103,210,0.2); text-decoration: none; transition: all 0.15s ease; flex-shrink: 0; }
    .gl-btn-view:hover { opacity: 0.85; }

    .gl-btn-suspend { width: 34px; height: 34px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem; background: var(--gl-red); color: #fff; border: none; cursor: pointer; transition: all 0.15s ease; flex-shrink: 0; box-shadow: 0 2px 6px rgba(217,48,37,0.25); }
    .gl-btn-suspend:hover { background: #b5231a; transform: translateY(-1px); }

    .gl-btn-activate { width: 34px; height: 34px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem; background: var(--gl-green); color: #fff; border: none; cursor: pointer; transition: all 0.15s ease; flex-shrink: 0; box-shadow: 0 2px 6px rgba(30,142,62,0.25); }
    .gl-btn-activate:hover { background: #167031; transform: translateY(-1px); }

    /* Empty States & Pagination */
    .gl-empty-state { text-align: center; padding: 40px 20px; color: var(--gl-text-lt); font-weight: 500; }
    .gl-empty-state i { font-size: 2rem; margin-bottom: 10px; opacity: 0.4; display: block; color: var(--gl-pink); }
    .gl-pagination-wrapper { padding: 16px 24px; border-top: 1px solid var(--gl-border); background: #FAFAFC; }

    @media (max-width: 768px) {
        .gl-filter-bar { flex-direction: column; }
        .gl-search-wrapper, .gl-filter-group { min-width: 100%; }
        .gl-filter-actions, .gl-export-group { width: 100%; }
        .gl-btn-search, .gl-btn-clear, .gl-btn-export { flex: 1; justify-content: center; }
        .gl-clients-table th:nth-child(4), .gl-clients-table td:nth-child(4) { display: none; }
    }
</style>
@endpush

@section('content')
<div class="gl-clients-page">

    {{-- Page Header --}}
    <div class="gl-page-header">
        <div>
            <h1><i class="fas fa-users"></i> Clients Directory</h1>
            <p>{{ $clients->total() }} total registered clients</p>
        </div>

        {{-- Export Action Buttons --}}
        <div class="gl-export-group">
            <a href="{{ route('admin.clients.export', request()->query()) }}" class="gl-btn-export gl-btn-excel">
                <span class="gl-export-icon">
                    <svg width="13" height="13" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="14" height="14" rx="2" fill="#1d6f42"/>
                        <path d="M3 3.5L5.5 7L3 10.5H4.8L6.5 7.9L8.2 10.5H10L7.5 7L10 3.5H8.2L6.5 6.1L4.8 3.5H3Z" fill="white"/>
                    </svg>
                </span>
                Export Excel
            </a>

            <a href="{{ route('admin.clients.export.pdf', request()->query()) }}" class="gl-btn-export gl-btn-pdf">
                <span class="gl-export-icon">
                    <svg width="13" height="13" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="14" height="14" rx="2" fill="#C62828"/>
                        <path d="M3 2H8L11 5V12H3V2Z" stroke="white" stroke-width="1" fill="none"/>
                        <text x="3.5" y="10" font-size="4.5" fill="white" font-family="Arial" font-weight="bold">PDF</text>
                    </svg>
                </span>
                Export PDF
            </a>
        </div>
    </div>

    {{-- Filter Bar Form --}}
    <form method="GET" action="{{ route('admin.clients.index') }}" id="filterForm">
        <div class="gl-filter-bar">
            <div class="gl-search-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" name="search" class="gl-search-input" placeholder="Search by name, email or phone..." value="{{ request('search') }}" autocomplete="off">
            </div>

            <div class="gl-filter-group">
                <label>Status</label>
                <select name="status" class="gl-filter-select" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status')=='active' ? 'selected':'' }}>Active</option>
                    <option value="suspended" {{ request('status')=='suspended' ? 'selected':'' }}>Suspended</option>
                </select>
            </div>

            <div class="gl-filter-group">
                <label>City</label>
                <select name="city" class="gl-filter-select" onchange="this.form.submit()">
                    <option value="">All Cities</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city')==$city ? 'selected':'' }}>{{ $city }}</option>
                    @endforeach
                </select>
            </div>

            <div class="gl-filter-group">
                <label>Sort By</label>
                <select name="sort" class="gl-filter-select" onchange="this.form.submit()">
                    <option value="newest" {{ request('sort','newest')=='newest' ? 'selected':'' }}>Newest First</option>
                    <option value="oldest" {{ request('sort')=='oldest' ? 'selected':'' }}>Oldest First</option>
                    <option value="name_asc" {{ request('sort')=='name_asc' ? 'selected':'' }}>Name A–Z</option>
                    <option value="bookings" {{ request('sort')=='bookings' ? 'selected':'' }}>Most Bookings</option>
                </select>
            </div>

            <div class="gl-filter-actions">
                <button type="submit" class="gl-btn-search"><i class="fas fa-search"></i> Search</button>
                @if(request()->hasAny(['search','status','city','sort']))
                    <a href="{{ route('admin.clients.index') }}" class="gl-btn-clear"><i class="fas fa-times"></i> Clear</a>
                @endif
            </div>
        </div>
    </form>

    {{-- Clients Table Card --}}
    <div class="gl-clients-card">
        <div class="gl-clients-card-header">
            <span class="gl-card-title">Registered Clients List</span>
            <span class="gl-result-count">
                Showing {{ $clients->firstItem() ?? 0 }}–{{ $clients->lastItem() ?? 0 }} of {{ $clients->total() }} records
            </span>
        </div>

        <div style="overflow-x:auto;">
            <table class="gl-clients-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client Details</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Bookings</th>
                        <th>Joined Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                    <tr onclick="window.location='{{ route('admin.clients.show', $client->id) }}'">
                        <td style="color:var(--gl-text-lt); font-size:0.78rem;">#{{ $client->id }}</td>
                        <td>
                            <div class="gl-client-cell">
                                <div class="gl-client-avatar">{{ strtoupper(substr($client->name,0,1)) }}</div>
                                <div>
                                    <div class="gl-client-name">{{ $client->name }}</div>
                                    <div class="gl-client-email">{{ $client->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $client->phone ?? '—' }}</td>
                        <td>{{ $client->city ?? '—' }}</td>
                        <td><span class="gl-badge gl-badge-warning"><i class="fas fa-calendar-check"></i> {{ $client->appointments_count ?? 0 }}</span></td>
                        <td style="font-size:0.82rem; color:var(--gl-text-lt);">{{ $client->created_at->format('d M Y') }}</td>
                        <td>
                            <span class="gl-badge {{ $client->is_active ? 'gl-badge-success' : 'gl-badge-danger' }}">
                                <i class="fas fa-circle" style="font-size: 5px;"></i>
                                {{ $client->is_active ? 'Active' : 'Suspended' }}
                            </span>
                        </td>
                        <td onclick="event.stopPropagation()">
                            <div class="gl-action-group">
                                <a href="{{ route('admin.clients.show', $client->id) }}" class="gl-btn-view" title="View Profile">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.clients.toggle', $client->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="{{ $client->is_active ? 'gl-btn-suspend' : 'gl-btn-activate' }}" title="{{ $client->is_active ? 'Suspend Client' : 'Activate Client' }}" onclick="return confirm('{{ $client->is_active ? 'Are you sure you want to suspend this client?' : 'Are you sure you want to activate this client?' }}')">
                                        <i class="fas {{ $client->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="gl-empty-state">
                                <i class="fas fa-user-slash"></i>
                                No client records found matching your active filters.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($clients->hasPages())
        <div class="gl-pagination-wrapper">
            {{ $clients->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>
@endsection