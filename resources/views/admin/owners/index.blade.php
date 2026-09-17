@extends('layouts.admin')
@section('title', 'Salon Owners - Beauty Blush Salons')

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
    }

    /* ── Page Header ── */
    .page-header-row { display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; margin-bottom: 26px; }
    .page-header-row h1 { font-size: 1.6rem; font-weight: 800; color: var(--gl-text); margin: 0; }
    .page-header-row p  { font-size: 0.88rem; color: var(--gl-text-lt); margin: 6px 0 0; }

    /* ── Card ── */
    .gl-card { background: #fff; border-radius: 20px; border: 1px solid var(--gl-border); box-shadow: 0 2px 12px rgba(255,107,157,0.06); overflow: hidden; margin-bottom: 24px; }
    .gl-card-header { display: flex; align-items: center; gap: 10px; padding: 18px 26px; border-bottom: 1px solid var(--gl-border); background: #FAFAFC; }
    .gl-card-header i { color: var(--gl-pink); font-size: 0.95rem; }
    .gl-card-header span { font-size: 0.92rem; font-weight: 800; color: var(--gl-text); text-transform: uppercase; letter-spacing: 0.5px; }

    /* ── Toolbar ── */
    .gl-toolbar { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; padding: 20px 26px; border-bottom: 1px solid var(--gl-border); background: #FFFBFD; }
    .gl-search-box { display: flex; align-items: center; gap: 10px; background: #fff; border: 1.5px solid var(--gl-border); border-radius: 14px; padding: 10px 16px; flex: 2 1 260px; transition: border-color 0.2s ease, box-shadow 0.2s ease; }
    .gl-search-box:focus-within { border-color: var(--gl-pink); box-shadow: 0 0 0 3px rgba(255,107,157,0.1); }
    .gl-search-box i { color: var(--gl-pink); font-size: 0.9rem; flex-shrink: 0; }
    .gl-search-box input { border: none; outline: none; background: transparent; font-size: 0.88rem; color: var(--gl-text); width: 100%; font-family: inherit; }
    .gl-search-box input::placeholder { color: var(--gl-text-lt); }

    .gl-filter-select { border: 1.5px solid var(--gl-border); border-radius: 14px; padding: 11px 16px; font-size: 0.88rem; color: var(--gl-text); background: #fff; cursor: pointer; font-family: inherit; flex: 1 1 150px; outline: none; transition: border-color 0.2s ease, box-shadow 0.2s ease; appearance: none; -webkit-appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24'%3E%3Cpath fill='%23B98BA6' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 36px; }
    .gl-filter-select:focus { border-color: var(--gl-pink); box-shadow: 0 0 0 3px rgba(255,107,157,0.1); }

    .gl-btn-filter { background: linear-gradient(135deg, var(--gl-pink), var(--gl-pink-dark)); border: none; color: #fff; padding: 11px 22px; border-radius: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-size: 0.88rem; font-weight: 700; font-family: inherit; transition: transform 0.15s ease, box-shadow 0.15s ease; flex-shrink: 0; box-shadow: 0 4px 12px rgba(255,107,157,0.3); }
    .gl-btn-filter:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(255,107,157,0.4); }

    /* ── Table ── */
    .gl-table-responsive { overflow-x: auto; }
    .gl-table { width: 100%; border-collapse: collapse; min-width: 700px; }
    .gl-table thead th { padding: 14px 26px; text-align: left; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 800; color: var(--gl-text-lt); background: var(--gl-pink-light); border-bottom: 1px solid var(--gl-border); white-space: nowrap; }
    .gl-table tbody td { padding: 16px 26px; border-bottom: 1px solid var(--gl-border); font-size: 0.88rem; color: var(--gl-text); vertical-align: middle; }
    .gl-table tbody tr { cursor: pointer; transition: background 0.15s ease; }
    .gl-table tbody tr:hover { background: var(--gl-pink-light); }
    .gl-table tbody tr:last-child td { border-bottom: none; }
    .gl-owner-id { color: var(--gl-text-lt); font-weight: 700; font-size: 0.82rem; }
    .gl-owner-cell strong { font-weight: 700; color: var(--gl-text); display: block; margin-bottom: 2px; }
    .gl-owner-cell small { color: var(--gl-text-lt); font-size: 0.78rem; }

    /* ── Badges ── */
    .gl-badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 20px; font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.4px; white-space: nowrap; }
    .gl-badge-info { background: #E3F2FD; color: #1565C0; }
    .gl-badge-success { background: #E3F6E9; color: #1E8E3E; }
    .gl-badge-danger { background: #FCE8E6; color: #D93025; }

    /* ── Action Buttons ── */
    .gl-row-actions { display: flex; gap: 10px; align-items: center; }
    .gl-icon-btn { width: 34px; height: 34px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; color: #fff; text-decoration: none; font-size: 0.8rem; border: none; cursor: pointer; padding: 0; transition: transform 0.15s ease, opacity 0.15s ease; box-shadow: 0 3px 8px rgba(0,0,0,0.1); }
    .gl-icon-btn:hover { transform: translateY(-2px); opacity: 0.9; }
    .gl-icon-btn.gl-view { background: linear-gradient(135deg, #4285F4, #1967D2); }
    .gl-icon-btn.gl-suspend { background: linear-gradient(135deg, #EA4335, #C5221F); }
    .gl-icon-btn.gl-activate { background: linear-gradient(135deg, #34A853, #188038); }
    .gl-toggle-form { display: inline-flex; margin: 0; }

    .gl-empty-row td { text-align: center; padding: 50px 20px; color: var(--gl-text-lt); font-weight: 600; }

    /* ── Pagination Styling ── */
    .gl-pagination { padding: 18px 26px; }
    .gl-pagination nav > div:first-of-type { display: none; }
    .gl-pagination nav > div:last-of-type { display: flex !important; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; }
    .gl-pagination p { font-size: 0.82rem; color: var(--gl-text-lt); margin: 0; }
    .gl-pagination p span { color: var(--gl-text); font-weight: 700; }
    .gl-pagination svg { width: 14px; height: 14px; display: inline-block; vertical-align: middle; }
    .gl-pagination a,
    .gl-pagination span[aria-current] span,
    .gl-pagination span[aria-disabled] span {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 34px; height: 34px; padding: 0 10px; margin: 0 3px;
        border-radius: 10px; font-size: 0.82rem; font-weight: 600;
        border: 1.5px solid var(--gl-border); background: #fff; color: var(--gl-text);
        text-decoration: none; transition: all 0.15s ease;
    }
    .gl-pagination a:hover { background: var(--gl-pink-light); border-color: var(--gl-pink-pale); color: var(--gl-pink); }
    .gl-pagination span[aria-current] span { background: linear-gradient(135deg, var(--gl-pink), var(--gl-pink-dark)); color: #fff; border-color: transparent; }
    .gl-pagination span[aria-disabled] span { color: var(--gl-text-lt); opacity: 0.4; cursor: not-allowed; }

    @media (max-width: 640px) {
        .gl-toolbar { flex-direction: column; align-items: stretch; }
        .gl-search-box { flex: 1 1 auto; }
        .page-header-row { flex-direction: column; align-items: stretch; }
    }
</style>
@endpush

@section('content')
<div class="gl-owners">

    {{-- Page Header --}}
    <div class="page-header-row">
        <div>
            <h1>Salon Owners</h1>
            <p>Manage and monitor registered salon proprietors</p>
        </div>
    </div>

    <div class="gl-card">
        <div class="gl-card-header">
            <i class="fas fa-user-tie"></i>
            <span>Owners Directory ({{ $owners->total() }})</span>
        </div>

        {{-- Filter and Search Toolbar --}}
        <form method="GET" action="{{ route('admin.owners.index') }}" class="gl-toolbar">
            <div class="gl-search-box">
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email...">
            </div>
            <select name="status" class="gl-filter-select">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
            </select>
            <button type="submit" class="gl-btn-filter"><i class="fas fa-filter"></i> Filter</button>
        </form>

        {{-- Table Container --}}
        <div class="gl-table-responsive">
            <table class="gl-table">
                <thead>
                    <tr>
                        <th># ID</th>
                        <th>Owner Info</th>
                        <th>Phone</th>
                        <th>Salons Count</th>
                        <th>Account Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($owners as $owner)
                        <tr onclick="window.location='{{ route('admin.owners.show', $owner->id) }}'">
                            <td><span class="gl-owner-id">#{{ $owner->id }}</span></td>
                            <td>
                                <div class="gl-owner-cell">
                                    <strong>{{ $owner->name }}</strong>
                                    <small>{{ $owner->email }}</small>
                                </div>
                            </td>
                            <td>{{ $owner->phone ?? '—' }}</td>
                            <td><span class="gl-badge gl-badge-info">{{ $owner->salons_count ?? 0 }} salons</span></td>
                            <td>
                                <span class="gl-badge {{ $owner->is_active ? 'gl-badge-success' : 'gl-badge-danger' }}">
                                    <i class="fas fa-circle" style="font-size: 6px;"></i> {{ $owner->is_active ? 'Active' : 'Suspended' }}
                                </span>
                            </td>
                            <td>
                                <div class="gl-row-actions">
                                    <a href="{{ route('admin.owners.show', $owner->id) }}" class="gl-icon-btn gl-view" title="View Details" onclick="event.stopPropagation()">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.owners.toggle-status', $owner->id) }}" method="POST" class="gl-toggle-form" onclick="event.stopPropagation()">
                                        @csrf
                                        <button type="submit" class="gl-icon-btn {{ $owner->is_active ? 'gl-suspend' : 'gl-activate' }}" title="{{ $owner->is_active ? 'Suspend Owner' : 'Activate Owner' }}" onclick="return confirm('{{ $owner->is_active ? 'Are you sure you want to suspend this owner?' : 'Are you sure you want to activate this owner?' }}')">
                                            <i class="fas {{ $owner->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="gl-empty-row">
                            <td colspan="6"><i class="fas fa-folder-open" style="font-size: 2rem; margin-bottom: 8px; display: block; color: var(--gl-text-lt);"></i> No owners found matching your criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Block --}}
        <div class="gl-pagination">
            {{ $owners->withQueryString()->links() }}
        </div>
    </div>

</div>
@endsection