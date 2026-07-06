@extends('layouts.admin')
@section('title', $typeLabel . ' — Glamora Admin')

@section('content')
<style>
:root { --pk:#E91E8C; --pk-lt:#fce4ec; --pk-bg:#fff0f7; }

.prev-toolbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.4rem; flex-wrap:wrap; gap:.6rem; }
.prev-toolbar h1 { font-size:1.35rem; font-weight:700; color:#1a1a1a; margin:0 0 .2rem; }
.prev-toolbar .sub { color:#9a9a9a; font-size:.84rem; }
.prev-toolbar .sub i { color:var(--pk); margin-right:5px; }

.back-link {
    display:inline-flex; align-items:center; gap:8px; padding:8px 18px; border-radius:50px;
    background:#fff; color:#c2185b; border:1.5px solid var(--pk-lt); text-decoration:none;
    font-weight:600; font-size:.84rem; transition:all .18s;
}
.back-link:hover { background:var(--pk); color:#fff; border-color:var(--pk); }
.btn-print {
    display:inline-flex; align-items:center; gap:8px; padding:8px 20px; border-radius:50px;
    background:linear-gradient(135deg,#E91E8C,#c2185b); color:#fff; border:none;
    font-weight:700; font-size:.84rem; cursor:pointer;
}

.summary-grid { display:flex; flex-wrap:wrap; gap:.8rem; margin-bottom:1.4rem; }
.summary-card {
    flex:1; min-width:160px; background:var(--pk-bg); border:1px solid var(--pk-lt); border-radius:14px;
    padding:.85rem 1.2rem;
}
.summary-card .lbl { font-size:.66rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#c2185b; }
.summary-card .val { font-size:1.25rem; font-weight:800; color:#1a1a1a; margin-top:.2rem; }

.table-card { background:#fff; border:1px solid #ebebeb; border-radius:14px; overflow:hidden; }
.table-head { padding:.9rem 1.3rem; border-bottom:1px solid #f3f3f3; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:.5rem; }
.table-head .title { font-weight:700; font-size:.9rem; color:#1a1a1a; }
.table-head .count { font-size:.75rem; color:#c2185b; background:var(--pk-bg); padding:.25rem .8rem; border-radius:20px; font-weight:600; }

.dt { width:100%; border-collapse:collapse; }
.dt thead th { background:#fafafa; padding:.7rem .9rem; font-size:.68rem; text-transform:uppercase; letter-spacing:.04em; color:#aaa; text-align:left; border-bottom:1px solid #ebebeb; white-space:nowrap; }
.dt tbody td { padding:.7rem .9rem; font-size:.84rem; color:#333; border-bottom:1px solid #f5f5f5; white-space:nowrap; }
.dt tbody tr:hover { background:#fdf5fa; }
.empty-st { text-align:center; padding:3rem; color:#ccc; }
.empty-st i { font-size:2.2rem; margin-bottom:.6rem; opacity:.3; display:block; }
.empty-st p { color:#999; font-size:.88rem; }

@media print {
    .back-link, .btn-print, nav, .sidebar, #sidebarToggle { display:none !important; }
    body { background:#fff !important; }
}
</style>

<a href="{{ route('admin.reports.index') }}" class="back-link" style="margin-bottom:14px;">
    <i class="fas fa-arrow-left"></i> Back to Reports
</a>

<div class="prev-toolbar">
    <div>
        <h1 style="display:flex;align-items:center;gap:8px;">
            <i class="fas fa-file-lines" style="color:var(--pk);"></i>
            <span>{{ $typeLabel }}</span>
        </h1>
        <div class="sub"><i class="fas fa-calendar"></i>{{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }} – {{ \Carbon\Carbon::parse($toDate)->format('d M Y') }}</div>
    </div>
    <button class="btn-print" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
</div>

@if(count($report['summary']) > 0)
<div class="summary-grid">
    @foreach($report['summary'] as $label => $value)
    <div class="summary-card">
        <div class="lbl">{{ $label }}</div>
        <div class="val">{{ $value }}</div>
    </div>
    @endforeach
</div>
@endif

<div class="table-card">
    <div class="table-head">
        <span class="title" style="display:inline-flex;align-items:center;gap:6px;">
            <i class="fas fa-table" style="color:var(--pk);"></i>
            <span>Detailed Records</span>
        </span>
        <span class="count">{{ count($report['rows']) }} record(s)</span>
    </div>
    <div style="overflow-x:auto;">
        @if(count($report['rows']) > 0)
        <table class="dt">
            <thead>
                <tr>
                    @foreach($report['columns'] as $col)
                    <th>{{ $col['label'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($report['rows'] as $row)
                <tr>
                    @foreach($report['columns'] as $col)
                    <td>{{ $row[$col['key']] ?? '—' }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-st">
            <i class="fas fa-inbox"></i>
            <p>No records found for this date range.</p>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
    @if(request('print'))
    window.addEventListener('load', () => setTimeout(() => window.print(), 300));
    @endif
</script>
@endpush