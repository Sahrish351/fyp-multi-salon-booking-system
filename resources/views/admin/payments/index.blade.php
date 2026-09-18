@extends('layouts.admin')
@section('title', 'Payments - Beauty Blush Salons')

@section('content')
<style>
    :root {
        --pm-pink: #FF6B9D;
        --pm-sage: #6b8f71;
        --pm-sage-lt: #f0f5f1;
        --pm-amber: #b07d3a;
        --pm-amber-lt: #fdf6ec;
        --pm-red: #b84444;
        --pm-red-lt: #fdf0f0;
        --pm-slate: #5c7a8a;
        --pm-slate-lt: #eef3f6;
        --pm-text: #2d2d2d;
        --pm-text-mid: #8a8a8a;
        --pm-text-lt: #aaa;
        --pm-border: #ebebeb;
    }

    .pm-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 24px;
    }
    .pm-header-row h1 { font-size: 1.5rem; font-weight: 700; margin: 0 0 4px; color: var(--pm-text); }
    .pm-header-row p { margin: 0; color: var(--pm-text-mid); font-size: 0.86rem; }

    /* Filter card */
    .pm-filter-card {
        background: #fff;
        border: 1px solid var(--pm-border);
        border-radius: 14px;
        margin-bottom: 20px;
        overflow: hidden;
    }
    .pm-filter-head {
        padding: 14px 20px;
        border-bottom: 1px solid var(--pm-border);
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--pm-text);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .pm-filter-head i { color: var(--pm-pink); font-size: 0.82rem; }
    .pm-filter-body { padding: 16px 20px; }
    .pm-filter-row { display: flex; flex-wrap: wrap; gap: 14px; align-items: flex-end; }
    .pm-filter-field { flex: 1 1 170px; min-width: 0; }
    .pm-filter-label { display: block; margin-bottom: 6px; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: .03em; color: var(--pm-text-mid); }
    .pm-filter-input, .pm-filter-select {
        width: 100%;
        box-sizing: border-box;
        padding: 9px 12px;
        border: 1.5px solid var(--pm-border);
        border-radius: 9px;
        font-size: 0.85rem;
        font-family: inherit;
        color: var(--pm-text);
        background: #faf8f6;
        outline: none;
        transition: all .15s;
    }
    .pm-filter-input:focus, .pm-filter-select:focus {
        border-color: var(--pm-pink);
        box-shadow: 0 0 0 3px rgba(255, 107, 157,.1);
        background: #fff;
    }
    .pm-filter-actions { display: flex; gap: 8px; flex: 0 0 auto; }
    .btn-filter-go {
        padding: 9px 18px;
        border-radius: 9px;
        font-size: 0.84rem;
        font-weight: 700;
        background: var(--pm-pink);
        color: #fff;
        border: none;
        cursor: pointer;
        white-space: nowrap;
    }
    .btn-filter-go:hover { background: #E85588; }
    .btn-filter-clear {
        padding: 9px 16px;
        border-radius: 9px;
        font-size: 0.84rem;
        font-weight: 600;
        background: transparent;
        border: 1.5px solid var(--pm-border);
        color: var(--pm-text-mid);
        text-decoration: none;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
    }
    .btn-filter-clear:hover { border-color: var(--pm-pink); color: var(--pm-pink); }

    /* Table styles */
    .pm-table-wrap { overflow-x: auto; }
    .data-table { width: 100%; border-collapse: collapse; text-align: left; }
    .data-table th { padding: 12px 16px; font-size: 0.75rem; text-transform: uppercase; color: var(--pm-text-mid); background: #faf8f6; border-bottom: 1px solid var(--pm-border); }
    .data-table td { padding: 14px 16px; font-size: 0.88rem; color: var(--pm-text); border-bottom: 1px solid var(--pm-border); }
    .data-table tr:last-child td { border-bottom: none; }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        white-space: nowrap;
    }
    .badge-success { background: var(--pm-sage-lt); color: var(--pm-sage); }
    .badge-warning { background: var(--pm-amber-lt); color: var(--pm-amber); }
    .badge-danger { background: var(--pm-red-lt); color: var(--pm-red); }
    .badge-method { background: var(--pm-slate-lt); color: var(--pm-slate); }

    /* Custom Clean Pagination Footer */
    .pm-pagination-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        border-top: 1px solid var(--pm-border);
        flex-wrap: wrap;
        gap: 15px;
        background: #fff;
    }

    .btn-view-frozy {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 9px;
        font-size: 0.78rem;
        font-weight: 700;
        background: #00838f;
        color: #fff;
        border: none;
        text-decoration: none;
        white-space: nowrap;
    }
</style>

<div class="pm-header-row">
    <div>
        <h1>Payment Monitor</h1>
        <p>All advance payments submitted by clients</p>
    </div>
</div>

{{-- Filter Form --}}
<div class="pm-filter-card">
    <div class="pm-filter-head"><i class="fas fa-filter"></i> Filter Payments</div>
    <div class="pm-filter-body">
        <form method="GET" action="{{ route('admin.payments.index') }}" class="pm-filter-row">
            <div class="pm-filter-field" style="flex:2 1 220px;">
                <label class="pm-filter-label">Search</label>
                <input type="text" name="search" class="pm-filter-input" placeholder="Client name, email, or transaction ID…" value="{{ request('search') }}">
            </div>
            <div class="pm-filter-field">
                <label class="pm-filter-label">Status</label>
                <select name="status" class="pm-filter-select">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status')=='approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status')=='rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="pm-filter-field">
                <label class="pm-filter-label">Method</label>
                <select name="method" class="pm-filter-select">
                    <option value="">All Methods</option>
                    @foreach(\App\Models\Payment::whereNotNull('method')->distinct()->pluck('method') as $m)
                        <option value="{{ $m }}" {{ request('method')==$m ? 'selected' : '' }}>{{ ucfirst($m) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="pm-filter-actions">
                <button type="submit" class="btn-filter-go"><i class="fas fa-search"></i> Filter</button>
                <a href="{{ route('admin.payments.index') }}" class="btn-filter-clear">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card" style="background: #fff; border: 1px solid var(--pm-border); border-radius: 14px; overflow: hidden;">
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid var(--pm-border);">
        <span style="font-weight: 700; color: var(--pm-text);">Payments List</span>
        <span style="font-size:0.75rem; color: var(--pm-text-lt);">Total {{ $payments->total() }} records</span>
    </div>

    <div class="pm-table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Salon</th>
                    <th>Method</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $pay)
                <tr>
                    <td style="font-family: monospace; font-weight:600;">#{{ $pay->id }}</td>
                    <td>{{ $pay->client->name ?? 'N/A' }}</td>
                    <td>{{ $pay->salon->name ?? ($pay->appointment->salon->name ?? 'N/A') }}</td>
                    <td><span class="badge badge-method">{{ ucfirst($pay->method) }}</span></td>
                    <td style="font-weight:700; color: var(--pm-pink);">Rs. {{ number_format($pay->amount ?? 0) }}</td>
                    <td>
                        <span class="badge {{ $pay->status == 'approved' ? 'badge-success' : ($pay->status == 'rejected' ? 'badge-danger' : 'badge-warning') }}">
                            {{ ucfirst($pay->status) }}
                        </span>
                    </td>
                    <td>{{ $pay->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.payments.show', $pay->id) }}" class="btn-view-frozy">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding: 48px 20px;">
                        <i class="fas fa-receipt" style="font-size: 2.2rem; color: #ddd; display:block; margin-bottom: 12px;"></i>
                        <span style="color: var(--pm-text-mid);">No payments found</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Custom Pagination Footer -->
    <div class="pm-pagination-footer">
        <div style="font-size: 0.85rem; color: var(--pm-text-mid);">
            Showing <b>{{ $payments->firstItem() ?? 0 }}</b> to <b>{{ $payments->lastItem() ?? 0 }}</b> of <b>{{ $payments->total() }}</b> results
        </div>
        
        <div style="display: flex; gap: 6px; align-items: center; flex-wrap: wrap;">
            {{-- Previous Page Link --}}
            @if ($payments->onFirstPage())
                <span style="background: #f5f5f5; color: #ccc; border: 1px solid var(--pm-border); padding: 6px 12px; border-radius: 8px; font-size: 0.85rem; cursor: not-allowed;">Previous</span>
            @else
                <a href="{{ $payments->previousPageUrl() }}" style="background: #fff; color: var(--pm-text); border: 1px solid var(--pm-border); padding: 6px 12px; border-radius: 8px; font-size: 0.85rem; text-decoration: none; font-weight: 600;">Previous</a>
            @endif

            {{-- Page Numbers --}}
            @foreach ($payments->getUrlRange(1, $payments->lastPage()) as $page => $url)
                @if ($page == $payments->currentPage())
                    <span style="background: var(--pm-pink); color: #fff; border: 1px solid var(--pm-pink); padding: 6px 12px; border-radius: 8px; font-weight: 700; font-size: 0.85rem;">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" style="background: #fff; color: var(--pm-text); border: 1px solid var(--pm-border); padding: 6px 12px; border-radius: 8px; font-size: 0.85rem; text-decoration: none; font-weight: 600;">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($payments->hasMorePages())
                <a href="{{ $payments->nextPageUrl() }}" style="background: #fff; color: var(--pm-text); border: 1px solid var(--pm-border); padding: 6px 12px; border-radius: 8px; font-size: 0.85rem; text-decoration: none; font-weight: 600;">Next</a>
            @else
                <span style="background: #f5f5f5; color: #ccc; border: 1px solid var(--pm-border); padding: 6px 12px; border-radius: 8px; font-size: 0.85rem; cursor: not-allowed;">Next</span>
            @endif
        </div>
    </div>
</div>
@endsection