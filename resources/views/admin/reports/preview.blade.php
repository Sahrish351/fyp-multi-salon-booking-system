@extends('layouts.admin')
@section('title', $typeLabel . ' — Glamora Admin')

@section('content')
<style>
:root { --pk:#FF6B9D; --pk-lt:#fce4ec; --pk-bg:#fff0f7; }

.prev-toolbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.4rem; flex-wrap:wrap; gap:.6rem; }
.prev-toolbar h1 { font-size:1.35rem; font-weight:700; color:#1a1a1a; margin:0 0 .2rem; }
.prev-toolbar .sub { color:#9a9a9a; font-size:.84rem; }
.prev-toolbar .sub i { color:var(--pk); margin-right:5px; }

.back-link {
    display:inline-flex; align-items:center; gap:8px; padding:8px 18px; border-radius:50px;
    background:#fff; color:var(--pk); border:1.5px solid var(--pk-lt); text-decoration:none;
    font-weight:600; font-size:.84rem; transition:all .18s;
}
.back-link:hover { background:var(--pk); color:#fff; border-color:var(--pk); }
.btn-print {
    display:inline-flex; align-items:center; gap:8px; padding:8px 20px; border-radius:50px;
    background:linear-gradient(135deg,#FF6B9D,#E85588); color:#fff; border:none;
    font-weight:700; font-size:.84rem; cursor:pointer;
}

.summary-grid { display:flex; flex-wrap:wrap; gap:.8rem; margin-bottom:1.4rem; }
.summary-card {
    flex:1 1 0; min-width:150px; height:112px;
    border-radius:15px;
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    text-align:center; padding:.85rem 1rem; box-sizing:border-box;
    box-shadow:0 2px 6px rgba(0,0,0,.1);
    transition:transform .18s ease, box-shadow .18s ease;
}
.summary-card:hover { transform:translateY(-3px); box-shadow:0 6px 16px rgba(0,0,0,.12); }
.summary-card .lbl { font-size:.66rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; }
.summary-card .val { font-size:1.3rem; font-weight:800; margin-top:.35rem; }

@media (max-width:700px) {
    .summary-card { flex:1 1 calc(50% - .4rem); min-width:calc(50% - .4rem); height:104px; }
}

/* Colorful pastel palette — cycles automatically so every summary card looks distinct */
.sc-0 { background:#C8F0DF; } .sc-0 .lbl, .sc-0 .val { color:#1F7A5C; } /* Mint */
.sc-1 { background:#FFDCC2; } .sc-1 .lbl, .sc-1 .val { color:#B5561A; } /* Peach */
.sc-2 { background:#D9DEF7; } .sc-2 .lbl, .sc-2 .val { color:#4A54A8; } /* Periwinkle */
.sc-3 { background:#F9D4EE; } .sc-3 .lbl, .sc-3 .val { color:#B23E8C; } /* Pink */
.sc-4 { background:#E3F5C4; } .sc-4 .lbl, .sc-4 .val { color:#5E8A22; } /* Lime */
.sc-5 { background:#FFF2B8; } .sc-5 .lbl, .sc-5 .val { color:#A67A00; } /* Butter Yellow */

.table-card { background:#fff; border:1px solid #ebebeb; border-radius:14px; overflow:hidden; }
.table-head { padding:.9rem 1.3rem; border-bottom:1px solid #f3f3f3; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:.5rem; }
.table-head .title { font-weight:700; font-size:.9rem; color:#1a1a1a; }
.table-head .count { font-size:.75rem; color:var(--pk); background:var(--pk-bg); padding:.25rem .8rem; border-radius:20px; font-weight:600; }

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
    <div class="summary-card sc-{{ $loop->index % 6 }}">
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