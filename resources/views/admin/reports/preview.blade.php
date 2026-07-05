@extends('layouts.admin')
@section('title', $typeLabel . ' — Preview')

@section('content')
<style>
:root { --pk:#E91E8C; --pk-lt:#fce4ec; --pk-bg:#fff0f7; }

.prev-toolbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.2rem; flex-wrap:wrap; gap:.6rem; }
.prev-toolbar h1 { font-size:1.3rem; font-weight:700; color:#1a1a1a; margin:0; }
.prev-toolbar .sub { color:#9a9a9a; font-size:.82rem; }
.btn-print {
    display:inline-flex; align-items:center; gap:.4rem; padding:.55rem 1.2rem; border-radius:9px;
    background:var(--pk); color:#fff; border:none; font-weight:700; font-size:.84rem; cursor:pointer;
}

.summary-grid { display:flex; gap:.8rem; margin-bottom:1.4rem; flex-wrap:wrap; }
.summary-card { background:var(--pk-bg); border:1px solid var(--pk-lt); border-radius:12px; padding:.8rem 1.4rem; flex:1; min-width:160px; text-align:center; }
.summary-card .label { font-size:.68rem; text-transform:uppercase; color:#c2185b; font-weight:700; }
.summary-card .value { font-size:1.25rem; font-weight:800; color:#1a1a1a; margin-top:.2rem; }

.prev-card { background:#fff; border:1px solid #ebebeb; border-radius:13px; overflow:hidden; }
.prev-head { padding:.9rem 1.3rem; border-bottom:1px solid #f3f3f3; }
.prev-head .range { font-size:.78rem; color:#c2185b; font-weight:700; background:var(--pk-bg); display:inline-block; padding:.25rem .9rem; border-radius:20px; }

.dt { width:100%; border-collapse:collapse; }
.dt thead th { background:#fafafa; padding:.7rem .9rem; font-size:.68rem; text-transform:uppercase; color:#aaa; text-align:left; border-bottom:1px solid #ebebeb; }
.dt tbody td { padding:.7rem .9rem; font-size:.84rem; color:#333; border-bottom:1px solid #f5f5f5; }
.dt tbody tr:hover { background:#fdf5fa; }
.empty-st { text-align:center; padding:3rem; color:#ccc; }

@media print {
    .prev-toolbar .btn-print, nav, .sidebar { display:none !important; }
    body { background:#fff !important; }
}
</style>

<div class="prev-toolbar">
    <div>
        <h1><i class="fas fa-file-lines me-2" style="color:var(--pk);"></i>{{ $typeLabel }}</h1>
        <div class="sub">{{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }} – {{ \Carbon\Carbon::parse($toDate)->format('d M Y') }}</div>
    </div>
    <button class="btn-print" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
</div>

@if(count($report['summary']) > 0)
<div class="summary-grid">
    @foreach($report['summary'] as $label => $value)
    <div class="summary-card">
        <div class="label">{{ $label }}</div>
        <div class="value">{{ $value }}</div>
    </div>
    @endforeach
</div>
@endif

<div class="prev-card">
    <div class="prev-head">
        <span class="range"><i class="fas fa-calendar me-1"></i>{{ count($report['rows']) }} record(s)</span>
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
            <i class="fas fa-inbox fa-2x mb-2" style="opacity:.3;"></i>
            <p>No records found for this date range.</p>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
    // If opened via the "Print" button on the index page (?print=1),
    // trigger the browser's print dialog automatically.
    @if(request('print'))
    window.addEventListener('load', () => setTimeout(() => window.print(), 300));
    @endif
</script>
@endpush