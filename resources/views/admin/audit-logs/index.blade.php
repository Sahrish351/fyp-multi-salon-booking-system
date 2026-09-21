@extends('layouts.admin')

@section('title', 'Audit Logs — Beauty Blush Salons')

@push('styles')
<style>
    :root { --pk:#FF6B9D; --pk-dark:#E85588; --pk-lt:#fce4ec; --pk-bg:#fff0f7; }

    /* width:0 + min-width:100% => wide table can never push the page outside the screen */
    .al-page { width:0; min-width:100%; max-width:100%; box-sizing:border-box; }

    /* ── Header ── */
    .al-head { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem; }
    .al-title { display:flex; align-items:center; gap:14px; }
    .al-title-icon {
        width:48px; height:48px; border-radius:15px; flex-shrink:0;
        background:linear-gradient(135deg,var(--pk),var(--pk-dark)); color:#fff;
        display:flex; align-items:center; justify-content:center; font-size:1.15rem;
        box-shadow:0 6px 16px rgba(255,107,157,.3);
    }
    .al-title h1 { font-size:1.6rem; font-weight:800; color:#111; letter-spacing:-.02em; margin:0 0 .15rem; }
    .al-title p  { margin:0; color:#777; font-size:.88rem; font-weight:500; }

    .al-export { display:flex; gap:10px; flex-wrap:wrap; }
    .al-btn-export {
        display:inline-flex; align-items:center; gap:9px; padding:.7rem 1.4rem; border-radius:50px;
        font-weight:700; font-size:.84rem; text-decoration:none; color:#fff; white-space:nowrap;
        box-shadow:0 4px 12px rgba(0,0,0,.12); transition:transform .18s ease, box-shadow .18s ease;
    }
    .al-btn-export:hover { color:#fff; transform:translateY(-2px); }
    .al-btn-csv { background:linear-gradient(135deg,#1d8a4e,#1d6f42); }
    .al-btn-csv:hover { box-shadow:0 8px 20px rgba(29,111,66,.35); }
    .al-btn-pdf { background:linear-gradient(135deg,#e5352b,#c0392b); }
    .al-btn-pdf:hover { box-shadow:0 8px 20px rgba(192,57,43,.35); }

    /* ── Filter card ── */
    .al-filter {
        background:#fff; border:1px solid #eaeaea; border-radius:18px; padding:1.3rem 1.5rem;
        margin-bottom:1.5rem; box-shadow:0 4px 15px rgba(0,0,0,.03);
    }
    .al-filter-title {
        display:flex; align-items:center; gap:8px; font-size:.75rem; font-weight:800; color:var(--pk-dark);
        text-transform:uppercase; letter-spacing:.06em; margin-bottom:1rem;
    }
    .al-filter-title i {
        width:26px; height:26px; border-radius:8px; background:var(--pk-bg); color:var(--pk);
        display:inline-flex; align-items:center; justify-content:center; font-size:.72rem;
    }
    .al-filter-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(150px, 1fr)); gap:12px; }
    .al-search { grid-column:1 / -1; position:relative; }
    .al-search i { position:absolute; left:16px; top:50%; transform:translateY(-50%); color:var(--pk); opacity:.6; font-size:.85rem; pointer-events:none; }
    .al-search input {
        width:100%; box-sizing:border-box; padding:.75rem 1rem .75rem 2.6rem; border-radius:50px;
        border:1.5px solid #f0dde6; background:#fdf8fb; font-size:.86rem; color:#222; outline:none;
        font-family:inherit; transition:all .2s;
    }
    .al-field label { display:block; font-size:.66rem; font-weight:700; color:#9a8a93; text-transform:uppercase; letter-spacing:.05em; margin-bottom:6px; }
    .al-field select, .al-field input {
        width:100%; box-sizing:border-box; padding:.62rem .8rem; border-radius:11px; border:1.5px solid #f0dde6;
        background:#fdf8fb; font-size:.83rem; color:#222; outline:none; font-family:inherit; transition:all .2s;
    }
    .al-search input:focus, .al-field select:focus, .al-field input:focus {
        border-color:var(--pk); background:#fff; box-shadow:0 0 0 4px rgba(255,107,157,.1);
    }
    .al-filter-foot { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:.8rem; margin-top:1.1rem; }
    .al-filter-actions { display:flex; gap:8px; }
    .al-btn-apply {
        display:inline-flex; align-items:center; gap:8px; padding:.65rem 1.7rem; border-radius:50px; border:none;
        background:linear-gradient(135deg,var(--pk),var(--pk-dark)); color:#fff; font-weight:700; font-size:.84rem;
        cursor:pointer; box-shadow:0 4px 14px rgba(255,107,157,.3); outline:none; transition:box-shadow .18s ease;
    }
    .al-btn-apply:hover { box-shadow:0 6px 18px rgba(255,107,157,.42); }
    .al-btn-clear {
        display:inline-flex; align-items:center; gap:6px; padding:.65rem 1.3rem; border-radius:50px;
        background:#fff; border:1.5px solid #f0dde6; color:#999; font-weight:600; font-size:.84rem;
        text-decoration:none; transition:all .18s;
    }
    .al-btn-clear:hover { border-color:var(--pk); color:var(--pk); background:var(--pk-bg); }
    .al-total {
        display:inline-flex; align-items:center; gap:6px; padding:.4rem 1.1rem; border-radius:50px;
        background:var(--pk-bg); border:1px solid var(--pk-lt); color:var(--pk-dark); font-size:.78rem; font-weight:700;
    }

    /* ── Table card ── */
    .al-card {
        background:#fff; border:1px solid #eaeaea; border-radius:18px; overflow:hidden;
        box-shadow:0 4px 15px rgba(0,0,0,.03); width:100%; max-width:100%; min-width:0; box-sizing:border-box;
    }
    .al-card-head {
        padding:1.05rem 1.4rem; border-bottom:1px solid #f2f2f2; background:#fafbfc;
        display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:.5rem;
    }
    .al-card-head .t { font-weight:800; font-size:.95rem; color:#1a1a1a; display:inline-flex; align-items:center; gap:8px; }
    .al-card-head .t i { color:var(--pk); }
    .al-card-head .hint { font-size:.75rem; color:#9ca3af; font-weight:600; }

    .al-table-wrap { width:100%; max-height:560px; overflow:auto; }
    .al-table { width:100%; border-collapse:collapse; text-align:left; }
    .al-table thead th {
        position:sticky; top:0; z-index:1; background:var(--pk-bg); color:var(--pk-dark);
        padding:.85rem 1rem; font-size:.68rem; font-weight:800; text-transform:uppercase; letter-spacing:.05em;
        border-bottom:1px solid var(--pk-lt); white-space:nowrap;
    }
    .al-table tbody td { padding:.85rem 1rem; border-bottom:1px solid #f4f4f4; vertical-align:middle; font-size:.84rem; color:#1f2937; }
    .al-row { cursor:pointer; transition:background .15s; }
    .al-row:hover { background:#fff8fb; }
    .al-row:last-child td { border-bottom:none; }
    .al-id { font-weight:700; color:#b0b0b0; font-size:.75rem; white-space:nowrap; }
    .al-arrow { color:#d6c3cc; font-size:.75rem; text-align:right; }
    .al-row:hover .al-arrow { color:var(--pk); }

    .al-user { display:flex; align-items:center; gap:10px; }
    .al-avatar { width:34px; height:34px; border-radius:50%; object-fit:cover; border:2px solid var(--pk-lt); flex-shrink:0; }
    .al-user-name { display:block; font-weight:700; color:#222; font-size:.82rem; margin-bottom:3px; white-space:nowrap; }

    .al-role { display:inline-block; padding:2px 10px; border-radius:50px; font-size:.6rem; font-weight:700; background:#f1f5f9; color:#475569; }
    .al-role-admin { background:#fce4ec; color:#E85588; }
    .al-role-owner, .al-role-salon_owner { background:#e3f2fd; color:#0d47a1; }
    .al-role-client { background:#e8f5e9; color:#1b5e20; }

    .al-action { display:inline-block; padding:4px 13px; border-radius:50px; font-size:.68rem; font-weight:700; white-space:nowrap; }
    .al-st { display:inline-block; padding:4px 13px; border-radius:50px; font-size:.68rem; font-weight:700; background:#f1f5f9; color:#475569; }
    .al-st-success { background:#dcfce7; color:#16a34a; }
    .al-st-failed  { background:#fee2e2; color:#dc2626; }
    .al-st-pending { background:#fef3c7; color:#d97706; }

    .al-module { color:#555; font-size:.82rem; white-space:nowrap; }
    .al-ip { font-family:monospace; font-size:.76rem; color:#777; white-space:nowrap; }
    .al-date { font-size:.82rem; color:#222; white-space:nowrap; }
    .al-ago  { font-size:.7rem; color:#b5b5b5; margin-top:2px; }

    .al-empty { text-align:center; padding:3.5rem 1rem; }
    .al-empty i {
        width:88px; height:88px; border-radius:50%; background:var(--pk-bg); color:var(--pk); opacity:.7;
        font-size:2.2rem; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;
    }
    .al-empty h6 { margin:0 0 .3rem; color:#333; font-weight:700; font-size:1rem; }
    .al-empty p  { margin:0; color:#aaa; font-size:.85rem; }

    /* ── Pagination (works with Bootstrap-style links) ── */
    .al-pager { padding:1rem 1.4rem; border-top:1px solid #f2f2f2; display:flex; justify-content:center; }
    .al-pager nav { display:flex; flex-direction:column; align-items:center; gap:.6rem; }
    .al-pager svg { width:16px; height:16px; }
    .al-pager p { margin:0; font-size:.78rem; color:#9ca3af; }
    .al-pager .pagination { display:flex; flex-wrap:wrap; justify-content:center; gap:6px; list-style:none; margin:0; padding:0; }
    .al-pager .page-link {
        display:inline-block; min-width:36px; padding:.45rem .8rem; text-align:center; border-radius:10px;
        border:1.5px solid #f0dde6; background:#fff; color:#555; font-size:.8rem; font-weight:600; text-decoration:none;
    }
    .al-pager a.page-link:hover { border-color:var(--pk); color:var(--pk); background:var(--pk-bg); }
    .al-pager .page-item.active .page-link { background:var(--pk); border-color:var(--pk); color:#fff; }
    .al-pager .page-item.disabled .page-link { color:#ccc; background:#fafafa; }
</style>
@endpush

@section('content')
<div class="al-page">

    {{-- HEADER --}}
    <div class="al-head">
        <div class="al-title">
            <div class="al-title-icon"><i class="fas fa-history"></i></div>
            <div>
                <h1>Audit Logs</h1>
                <p>System activity log — all user actions recorded</p>
            </div>
        </div>
        <div class="al-export">
            <a href="{{ route('admin.audit-logs.export-csv', request()->query()) }}" class="al-btn-export al-btn-csv" target="_blank">
                <i class="fas fa-file-csv"></i> Export CSV
            </a>
            <a href="{{ route('admin.audit-logs.export-pdf', request()->query()) }}" class="al-btn-export al-btn-pdf" target="_blank">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="al-filter">
        <div class="al-filter-title"><i class="fas fa-filter"></i> Search &amp; Filter</div>
        <form method="GET">
            <div class="al-filter-grid">
                <div class="al-search">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" placeholder="Search by user, action, module, IP..." value="{{ request('search') }}">
                </div>

                <div class="al-field">
                    <label>Action</label>
                    <select name="action">
                        <option value="">All</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="al-field">
                    <label>Role</label>
                    <select name="role">
                        <option value="">All</option>
                        <option value="admin"  {{ request('role') == 'admin'  ? 'selected' : '' }}>Admin</option>
                        <option value="owner"  {{ request('role') == 'owner'  ? 'selected' : '' }}>Owner</option>
                        <option value="client" {{ request('role') == 'client' ? 'selected' : '' }}>Client</option>
                    </select>
                </div>

                <div class="al-field">
                    <label>From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}">
                </div>

                <div class="al-field">
                    <label>To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}">
                </div>

                <div class="al-field">
                    <label>Status</label>
                    <select name="status">
                        <option value="">All</option>
                        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                        <option value="failed"  {{ request('status') == 'failed'  ? 'selected' : '' }}>Failed</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
            </div>

            <div class="al-filter-foot">
                <div class="al-filter-actions">
                    <button type="submit" class="al-btn-apply"><i class="fas fa-filter"></i> Filter</button>
                    <a href="{{ route('admin.audit-logs.index') }}" class="al-btn-clear"><i class="fas fa-rotate-left"></i> Clear</a>
                </div>
                <span class="al-total"><i class="fas fa-database"></i> {{ $logs->total() }} record(s) found</span>
            </div>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="al-card">
        <div class="al-card-head">
            <span class="t"><i class="fas fa-list"></i> Activity Log</span>
            <span class="hint">Click any row to see full details</span>
        </div>

        <div class="al-table-wrap">
            <table class="al-table">
                <thead>
                    <tr>
                        <th style="width:60px;">#</th>
                        <th style="min-width:170px;">User</th>
                        <th style="min-width:120px;">Action</th>
                        <th style="min-width:100px;">Module</th>
                        <th style="min-width:110px;">IP</th>
                        <th style="min-width:90px;">Status</th>
                        <th style="min-width:160px;">Date &amp; Time</th>
                        <th style="width:30px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        @php
                            $roleKey     = $log->user->role ?? 'client';
                            $status      = $log->status ?? 'success';
                            $actionColor = $log->action_color ?? '#6b7280';
                            $userName    = $log->user->name ?? 'System';
                        @endphp
                        <tr class="al-row" onclick="window.location='{{ route('admin.audit-logs.show', $log->id) }}'">
                            <td class="al-id">#{{ $log->id }}</td>
                            <td>
                                <div class="al-user">
                                    <img class="al-avatar"
                                         src="{{ $log->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($userName).'&background=FF6B9D&color=fff' }}"
                                         onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($userName) }}&background=FF6B9D&color=fff';"
                                         alt="">
                                    <div>
                                        <span class="al-user-name">{{ $userName }}</span>
                                        <span class="al-role al-role-{{ $roleKey }}">{{ $log->role_label ?? ucfirst($log->user->role ?? 'Client') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="al-action" style="background:{{ $actionColor }}20;color:{{ $actionColor }};">{{ ucfirst($log->action) }}</span>
                            </td>
                            <td class="al-module">{{ $log->module ?? '—' }}</td>
                            <td class="al-ip">{{ $log->ip_address ?? '—' }}</td>
                            <td><span class="al-st al-st-{{ $status }}">{{ ucfirst($status) }}</span></td>
                            <td>
                                <div class="al-date">{{ $log->created_at->format('d M Y, h:i A') }}</div>
                                <div class="al-ago">{{ $log->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="al-arrow"><i class="fas fa-chevron-right"></i></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="al-empty">
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
            <div class="al-pager">
                {{ $logs->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</div>
@endsection