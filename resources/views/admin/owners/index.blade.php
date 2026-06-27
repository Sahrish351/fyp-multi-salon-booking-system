{{-- ============================================================ --}}
{{-- THIS FILE GOES IN: resources/views/admin/owners/index.blade.php --}}
{{-- This is the OWNERS LIST page (uses $owners collection) --}}
{{-- ============================================================ --}}
@extends('layouts.admin')
@section('title', 'Salon Owners - Glamora')

@push('styles')
<style>
    .gl-owners, .gl-owners * { box-sizing: border-box; }
    .gl-owners {
        --gl-pink: #E0177D;
        --gl-pink-dark: #B5125F;
        --gl-pink-light: #FDEAF3;
        --gl-pink-pale: #F1DCE9;
        --gl-text: #2B2230;
        --gl-text-lt: #B98BA6;
        --gl-border: #F1DCE9;
    }

    .gl-owners .gl-page-header { margin-bottom: 24px; }
    .gl-owners .gl-page-header h1 { font-size: 1.6rem; font-weight: 800; color: var(--gl-text); margin: 0; }
    .gl-owners .gl-page-header p { font-size: 0.88rem; color: var(--gl-text-lt); margin: 6px 0 0; }

    .gl-owners .gl-card { background: #fff; border-radius: 20px; border: 1px solid var(--gl-border); box-shadow: 0 2px 10px rgba(224, 23, 125, 0.05); overflow: hidden; }
    .gl-owners .gl-card-header { display: flex; align-items: center; justify-content: space-between; padding: 18px 24px; border-bottom: 1px solid var(--gl-border); }
    .gl-owners .gl-card-title { font-size: 0.95rem; font-weight: 700; color: var(--gl-text); display: flex; align-items: center; gap: 8px; margin: 0; }
    .gl-owners .gl-card-title i { color: var(--gl-pink); }

    /* Toolbar: its own full-width row, separate from the title row, so it can never get squashed */
    .gl-owners .gl-toolbar { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; padding: 18px 24px; border-bottom: 1px solid var(--gl-border); background: #FFFBFD; margin: 0; }
    .gl-owners .gl-search-box { display: flex; align-items: center; gap: 10px; background: #fff; border: 1.5px solid var(--gl-border); border-radius: 16px; padding: 13px 18px; flex: 2 1 280px; min-width: 240px; }
    .gl-owners .gl-search-box i { color: var(--gl-pink); font-size: 0.95rem; flex-shrink: 0; }
    .gl-owners .gl-search-box input { border: none; outline: none; background: transparent; font-size: 0.92rem; color: var(--gl-text); width: 100%; font-family: inherit; }
    .gl-owners .gl-filter-select { border: 1.5px solid var(--gl-border); border-radius: 16px; padding: 13px 18px; font-size: 0.88rem; color: var(--gl-text); background: #fff; cursor: pointer; font-family: inherit; flex: 1 1 160px; min-width: 150px; }
    .gl-owners .gl-btn-filter { background: linear-gradient(135deg, var(--gl-pink), var(--gl-pink-dark)); border: none; color: #fff; padding: 13px 24px; border-radius: 16px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-size: 0.88rem; font-weight: 700; font-family: inherit; transition: opacity 0.2s ease; flex-shrink: 0; box-shadow: 0 6px 16px rgba(224, 23, 125, 0.25); }
    .gl-owners .gl-btn-filter:hover { opacity: 0.9; }

    .gl-owners .gl-table-responsive { overflow-x: auto; }
    .gl-owners .gl-table { width: 100%; border-collapse: collapse; min-width: 700px; }
    .gl-owners .gl-table thead th { padding: 14px 24px; text-align: left; font-size: 0.66rem; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 800; color: var(--gl-text-lt); background: var(--gl-pink-light); border-bottom: 1px solid var(--gl-border); white-space: nowrap; }
    .gl-owners .gl-table tbody td { padding: 14px 24px; border-bottom: 1px solid var(--gl-border); font-size: 0.88rem; color: var(--gl-text); vertical-align: middle; }
    .gl-owners .gl-table tbody tr { cursor: pointer; transition: background 0.15s ease; }
    .gl-owners .gl-table tbody tr:hover { background: var(--gl-pink-light); }
    .gl-owners .gl-table tbody tr:last-child td { border-bottom: none; }
    .gl-owners .gl-owner-id { color: var(--gl-text-lt); font-weight: 600; }
    .gl-owners .gl-owner-cell strong { font-weight: 700; color: var(--gl-text); display: block; }
    .gl-owners .gl-owner-cell small { color: var(--gl-text-lt); font-size: 0.78rem; }

    .gl-owners .gl-badge { display: inline-block; padding: 5px 14px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.3px; white-space: nowrap; line-height: 1.4; }
    .gl-owners .gl-badge-info { background: #E3F2FD; color: #1565C0; }
    .gl-owners .gl-badge-success { background: #E3F6E9; color: #1E8E3E; }
    .gl-owners .gl-badge-danger { background: #FCE8E6; color: #D93025; }

    .gl-owners .gl-row-actions { display: flex; gap: 10px; align-items: center; }
    .gl-owners .gl-icon-btn { width: 36px; height: 36px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: #fff; text-decoration: none; font-size: 0.85rem; border: none; cursor: pointer; padding: 0; transition: transform 0.15s ease, opacity 0.15s ease; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15); }
    .gl-owners .gl-icon-btn:hover { transform: scale(1.12); opacity: 0.92; }
    .gl-owners .gl-icon-btn.gl-view { background: linear-gradient(135deg, #4285F4, #1967D2); }
    .gl-owners .gl-icon-btn.gl-suspend { background: linear-gradient(135deg, #EA4335, #C5221F); }
    .gl-owners .gl-icon-btn.gl-activate { background: linear-gradient(135deg, #34A853, #188038); }
    .gl-owners .gl-toggle-form { display: inline-flex; margin: 0; }

    .gl-owners .gl-empty-row td { text-align: center; padding: 50px 20px; color: var(--gl-text-lt); }

    /* ---- Pagination (Laravel default links()) fix ---- */
    .gl-owners .gl-pagination { padding: 18px 24px; }
    .gl-owners .gl-pagination nav > div:first-of-type { display: none; }
    .gl-owners .gl-pagination nav > div:last-of-type { display: flex !important; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; }
    .gl-owners .gl-pagination p { font-size: 0.82rem; color: var(--gl-text-lt); margin: 0; }
    .gl-owners .gl-pagination p span { color: var(--gl-text); font-weight: 700; }
    .gl-owners .gl-pagination svg { width: 14px; height: 14px; display: inline-block; vertical-align: middle; }
    .gl-owners .gl-pagination a,
    .gl-owners .gl-pagination span[aria-current] span,
    .gl-owners .gl-pagination span[aria-disabled] span {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 34px; height: 34px; padding: 0 10px; margin: 0 3px;
        border-radius: 10px; font-size: 0.82rem; font-weight: 600;
        border: 1px solid var(--gl-border); background: #fff; color: var(--gl-text);
        text-decoration: none; transition: all 0.15s ease;
    }
    .gl-owners .gl-pagination a:hover { background: var(--gl-pink-light); border-color: var(--gl-pink-pale); color: var(--gl-pink); }
    .gl-owners .gl-pagination span[aria-current] span { background: linear-gradient(135deg, var(--gl-pink), var(--gl-pink-dark)); color: #fff; border-color: transparent; }
    .gl-owners .gl-pagination span[aria-disabled] span { color: var(--gl-text-lt); opacity: 0.45; cursor: not-allowed; }

    @media (max-width: 640px) {
        .gl-owners .gl-toolbar { flex-direction: column; align-items: stretch; }
        .gl-owners .gl-search-box { flex: 1 1 auto; }
    }
</style>
@endpush

@section('content')
<div class="gl-owners">

    <div class="gl-page-header">
        <h1>Salon Owners</h1>
        <p>{{ $owners->total() }} total owners</p>
    </div>

    <div class="gl-card">
        <div class="gl-card-header">
            <span class="gl-card-title"><i class="fas fa-user-tie"></i> Owners List</span>
        </div>

        <form method="GET" action="{{ route('admin.owners.index') }}" class="gl-toolbar">
            <div class="gl-search-box">
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email...">
            </div>
            <select name="status" class="gl-filter-select">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
            </select>
            <button type="submit" class="gl-btn-filter"><i class="fas fa-filter"></i> Apply</button>
        </form>

        <div class="gl-table-responsive">
            <table class="gl-table">
                <thead>
                    <tr><th>#</th><th>Owner</th><th>Phone</th><th>Salons</th><th>Status</th><th>Actions</th></tr>
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
                            <td><span class="gl-badge {{ $owner->is_active ? 'gl-badge-success' : 'gl-badge-danger' }}">{{ $owner->is_active ? 'Active' : 'Suspended' }}</span></td>
                            <td>
                                <div class="gl-row-actions">
                                    <a href="{{ route('admin.owners.show', $owner->id) }}" class="gl-icon-btn gl-view" title="View" onclick="event.stopPropagation()"><i class="fas fa-eye"></i></a>
                                    <form action="{{ route('admin.owners.toggle-status', $owner->id) }}" method="POST" class="gl-toggle-form" onclick="event.stopPropagation()">
                                        @csrf
                                        <button type="submit" class="gl-icon-btn {{ $owner->is_active ? 'gl-suspend' : 'gl-activate' }}" title="{{ $owner->is_active ? 'Suspend' : 'Activate' }}" onclick="return confirm('{{ $owner->is_active ? 'Suspend this owner?' : 'Activate this owner?' }}')">
                                            <i class="fas {{ $owner->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="gl-empty-row"><td colspan="6">No owners found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="gl-pagination">{{ $owners->links() }}</div>
    </div>

</div>
@endsection