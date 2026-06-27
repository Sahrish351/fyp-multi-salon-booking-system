@extends('layouts.admin')
@section('title', 'All Salons - Glamora')

@push('styles')
<style>
    :root {
        --gl-pink: #E0177D;
        --gl-pink-dark: #B5125F;
        --gl-pink-light: #FDEAF3;
        --gl-pink-pale: #F1DCE9;
        --gl-text: #2B2230;
        --gl-text-lt: #B98BA6;
        --gl-border: #F1DCE9;
    }

    /* ── Page Header ── */
    .page-header-row { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap; margin-bottom: 26px; }
    .page-header-row h1 { font-size: 1.6rem; font-weight: 800; color: var(--gl-text); margin: 0; }
    .page-header-row p { font-size: 0.88rem; color: var(--gl-text-lt); margin: 6px 0 0; }

    .btn-add { background: linear-gradient(135deg, var(--gl-pink), var(--gl-pink-dark)); color: #fff !important; border: none; padding: 12px 22px; border-radius: 14px; font-weight: 700; font-size: 0.88rem; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; box-shadow: 0 8px 18px rgba(224,23,125,0.3); transition: transform 0.15s ease, box-shadow 0.15s ease; white-space: nowrap; }
    .btn-add:hover { transform: translateY(-2px); box-shadow: 0 12px 24px rgba(224,23,125,0.4); color: #fff !important; }

    /* ── Card ── */
    .card { background: #fff; border-radius: 20px; border: 1px solid var(--gl-border); box-shadow: 0 2px 10px rgba(224,23,125,0.05); overflow: hidden; }
    .card-header { display: flex; align-items: center; justify-content: space-between; padding: 18px 24px; border-bottom: 1px solid var(--gl-border); }
    .card-title { font-size: 0.95rem; font-weight: 700; color: var(--gl-text); display: flex; align-items: center; gap: 8px; }
    .card-title i { color: var(--gl-pink); }
    .count-pill { background: var(--gl-pink-light); color: var(--gl-pink-dark); font-size: 0.75rem; font-weight: 700; padding: 5px 14px; border-radius: 20px; }

    /* ── Search & Filter Bar ── */
    .search-filter-bar {
        display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
        padding: 16px 24px; border-bottom: 1px solid var(--gl-border);
        background: var(--gl-pink-light);
    }
    .search-wrap {
        position: relative; flex: 1; min-width: 200px;
    }
    .search-wrap i {
        position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
        color: var(--gl-text-lt); font-size: 0.82rem; pointer-events: none;
    }
    .search-input {
        width: 100%; padding: 9px 14px 9px 36px;
        border: 1.5px solid var(--gl-border); border-radius: 12px;
        font-size: 0.85rem; color: var(--gl-text); background: #fff;
        outline: none; transition: border-color 0.2s ease, box-shadow 0.2s ease;
        box-sizing: border-box;
    }
    .search-input:focus { border-color: var(--gl-pink); box-shadow: 0 0 0 3px rgba(224,23,125,0.1); }
    .search-input::placeholder { color: var(--gl-text-lt); }

    .filter-select {
        padding: 9px 14px; border: 1.5px solid var(--gl-border); border-radius: 12px;
        font-size: 0.85rem; color: var(--gl-text); background: #fff;
        outline: none; cursor: pointer; min-width: 130px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath fill='%23B98BA6' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 10px center;
        padding-right: 30px;
    }
    .filter-select:focus { border-color: var(--gl-pink); box-shadow: 0 0 0 3px rgba(224,23,125,0.1); }

    .btn-search {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 18px; border-radius: 12px; border: none;
        background: linear-gradient(135deg, var(--gl-pink), var(--gl-pink-dark));
        color: #fff; font-size: 0.84rem; font-weight: 700; cursor: pointer;
        box-shadow: 0 4px 12px rgba(224,23,125,0.3);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        white-space: nowrap; text-decoration: none;
    }
    .btn-search:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(224,23,125,0.4); color: #fff; }

    .btn-reset {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 16px; border-radius: 12px;
        border: 1.5px solid var(--gl-border);
        background: #fff; color: var(--gl-text-lt);
        font-size: 0.84rem; font-weight: 600; cursor: pointer;
        transition: all 0.15s ease; white-space: nowrap; text-decoration: none;
    }
    .btn-reset:hover { border-color: var(--gl-pink); color: var(--gl-pink); background: var(--gl-pink-light); }

    /* ── Table ── */
    .table-responsive { overflow-x: auto; }
    .data-table { width: 100%; border-collapse: collapse; min-width: 640px; }
    .data-table thead th { padding: 14px 24px; text-align: left; font-size: 0.66rem; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 800; color: var(--gl-text-lt); background: var(--gl-pink-light); border-bottom: 1px solid var(--gl-border); }
    .data-table tbody td { padding: 14px 24px; border-bottom: 1px solid var(--gl-border); font-size: 0.88rem; color: var(--gl-text); vertical-align: middle; }
    .data-table tbody tr { cursor: pointer; transition: background 0.15s ease; }
    .data-table tbody tr:hover { background: var(--gl-pink-light); }
    .data-table tbody tr:last-child td { border-bottom: none; }

    .salon-cell { display: flex; align-items: center; gap: 12px; }
    .salon-avatar { width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, var(--gl-pink), var(--gl-pink-dark)); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1rem; flex-shrink: 0; }
    .salon-cell strong { font-weight: 700; color: var(--gl-text); }
    .salon-cell small { color: var(--gl-text-lt); font-size: 0.78rem; }

    .owner-name { font-weight: 700; color: var(--gl-text); }

    .badge { padding: 5px 14px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.3px; white-space: nowrap; }
    .badge-city    { background: #EDE7FF; color: #6A3FC0; }
    .badge-warning { background: #FFF4DD; color: #8A5A00; }
    .badge-success { background: #E3F6E9; color: #1E8E3E; }
    .badge-danger  { background: #FCE8E6; color: #D93025; }

    /* ── Action Buttons ── */
    .row-actions-icons { display: flex; gap: 8px; align-items: center; }

    .gl-btn-view,
    .gl-btn-edit {
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        padding: 7px 14px !important;
        border-radius: 10px !important;
        font-size: 0.78rem !important;
        font-weight: 700 !important;
        text-decoration: none !important;
        border: none !important;
        cursor: pointer !important;
        transition: transform 0.15s ease, box-shadow 0.15s ease, opacity 0.15s ease !important;
        white-space: nowrap !important;
        line-height: 1 !important;
    }
    .gl-btn-view {
        background: #3A7BD5 !important;
        color: #fff !important;
        box-shadow: none !important;
    }
    .gl-btn-edit {
        background: #F26522 !important;
        color: #fff !important;
        box-shadow: none !important;
    }
    .gl-btn-view:hover {
        background: #2E6AC0 !important;
        color: #fff !important;
        transform: none !important;
        opacity: 1 !important;
    }
    .gl-btn-edit:hover {
        background: #E05A18 !important;
        color: #fff !important;
        transform: none !important;
        opacity: 1 !important;
    }
    .gl-btn-view i, .gl-btn-edit i { font-size: 0.75rem; }

    .empty-row td { text-align: center; padding: 50px 20px; color: var(--gl-text-lt); }

    /* ── Pagination ── */
    .pagination-wrapper {
        padding: 20px 24px; display: flex; align-items: center;
        justify-content: space-between; flex-wrap: wrap; gap: 14px;
        border-top: 1px solid var(--gl-border); background: #fff;
    }
    .pagination-info { font-size: 0.8rem; color: var(--gl-text-lt); font-weight: 600; }
    .gl-pagination { display: flex; align-items: center; gap: 6px; list-style: none; margin: 0; padding: 0; }
    .gl-pagination li a,
    .gl-pagination li span {
        display: inline-flex !important; align-items: center !important;
        justify-content: center !important; min-width: 38px !important;
        height: 38px !important; padding: 0 12px !important;
        border-radius: 10px !important; font-size: 0.82rem !important;
        font-weight: 700 !important; text-decoration: none !important;
        transition: all 0.15s ease !important; border: 1.5px solid var(--gl-border) !important;
        color: var(--gl-text-lt) !important; background: #fff !important; line-height: 1 !important;
    }
    .gl-pagination li a:hover { background: var(--gl-pink-light) !important; border-color: var(--gl-pink) !important; color: var(--gl-pink) !important; }
    .gl-pagination li.active span { background: linear-gradient(135deg, var(--gl-pink), var(--gl-pink-dark)) !important; color: #fff !important; border-color: transparent !important; box-shadow: 0 4px 12px rgba(224,23,125,0.4) !important; }
    .gl-pagination li.disabled span { opacity: 0.35 !important; cursor: not-allowed !important; background: #f8f8f8 !important; }
    .gl-pagination li.disabled a { opacity: 0.35 !important; cursor: not-allowed !important; pointer-events: none !important; }

    @media (max-width: 640px) {
        .page-header-row { align-items: stretch; }
        .btn-add { justify-content: center; }
        .search-filter-bar { flex-direction: column; }
        .search-wrap { width: 100%; }
        .filter-select { width: 100%; }
        .pagination-wrapper { justify-content: center; }
        .pagination-info { width: 100%; text-align: center; }
    }
</style>
@endpush

@section('content')

<div class="page-header-row">
    <div>
        <h1>All Salons</h1>
        <p>{{ $salons->total() }} salons in system</p>
    </div>
    <a href="{{ route('admin.salons.create') }}" class="btn-add"><i class="fas fa-plus"></i> Add New Salon</a>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-list"></i> Salons List</span>
        <span class="count-pill">Total: {{ $salons->total() }}</span>
    </div>

    {{-- ── Search & Filter Bar ── --}}
    <form method="GET" action="{{ route('admin.salons.index') }}" class="search-filter-bar">
        <div class="search-wrap">
            <i class="fas fa-search"></i>
            <input
                type="text"
                name="search"
                class="search-input"
                placeholder="Search by salon name, owner, phone..."
                value="{{ request('search') }}"
            >
        </div>

        <select name="city" class="filter-select">
            <option value="">All Cities</option>
            @foreach($cities ?? [] as $city)
                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
            @endforeach
        </select>

        <select name="status" class="filter-select">
            <option value="">All Status</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="pending"  {{ request('status') == 'pending'  ? 'selected' : '' }}>Pending</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>

        <button type="submit" class="btn-search"><i class="fas fa-search"></i> Search</button>

        @if(request()->hasAny(['search','city','status']))
            <a href="{{ route('admin.salons.index') }}" class="btn-reset"><i class="fas fa-times"></i> Reset</a>
        @endif
    </form>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Salon</th>
                    <th>Owner</th>
                    <th>City</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salons as $salon)
                    <tr onclick="window.location='{{ route('admin.salons.show', $salon->id) }}'">
                        <td>
                            <div class="salon-cell">
                                <div class="salon-avatar"><i class="fas fa-store"></i></div>
                                <div>
                                    <strong>{{ $salon->name }}</strong><br>
                                    <small>{{ $salon->phone }}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="owner-name">{{ $salon->owner->name ?? 'N/A' }}</span></td>
                        <td><span class="badge badge-city">{{ $salon->city }}</span></td>
                        <td>
                            <span class="badge {{ $salon->status == 'approved' ? 'badge-success' : ($salon->status == 'pending' ? 'badge-warning' : 'badge-danger') }}">
                                {{ ucfirst($salon->status) }}
                            </span>
                        </td>
                        <td onclick="event.stopPropagation()">
                            <div class="row-actions-icons">
                                <a href="{{ route('admin.salons.show', $salon->id) }}" class="gl-btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('admin.salons.edit', $salon->id) }}" class="gl-btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row"><td colspan="5"><i class="fas fa-store-slash" style="font-size:1.5rem;margin-bottom:8px;display:block;"></i> No salons found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── Pagination ── --}}
    @if($salons->hasPages())
    <div class="pagination-wrapper">
        <span class="pagination-info">
            Showing {{ $salons->firstItem() }}–{{ $salons->lastItem() }} of {{ $salons->total() }} salons
        </span>
        <ul class="gl-pagination">
            {{-- Previous --}}
            @if($salons->onFirstPage())
                <li class="disabled"><span><i class="fas fa-chevron-left"></i></span></li>
            @else
                <li><a href="{{ $salons->previousPageUrl() . '&' . http_build_query(request()->except('page')) }}"><i class="fas fa-chevron-left"></i></a></li>
            @endif

            {{-- Page Numbers --}}
            @foreach($salons->getUrlRange(1, $salons->lastPage()) as $page => $url)
                @if($page == $salons->currentPage())
                    <li class="active"><span>{{ $page }}</span></li>
                @else
                    <li><a href="{{ $url . '&' . http_build_query(request()->except('page')) }}">{{ $page }}</a></li>
                @endif
            @endforeach

            {{-- Next --}}
            @if($salons->hasMorePages())
                <li><a href="{{ $salons->nextPageUrl() . '&' . http_build_query(request()->except('page')) }}"><i class="fas fa-chevron-right"></i></a></li>
            @else
                <li class="disabled"><span><i class="fas fa-chevron-right"></i></span></li>
            @endif
        </ul>
    </div>
    @else
    <div class="pagination-wrapper">
        <span class="pagination-info">Showing all {{ $salons->total() }} salons</span>
    </div>
    @endif

</div>

@endsection