@extends('layouts.admin')
@section('title', $typeLabel . ' — Beauty Blush Salons Admin')

@section('content')
<style>
:root { --pk:#FF6B9D; --pk-lt:#fce4ec; --pk-bg:#fff0f7; }

/* Print mode optimization */
@media print {
    body { background: #fff !important; color: #000 !important; }
    .sidebar, .topbar, nav, header, .back-link, .btn-print, .admin-sidebar, .admin-header { display: none !important; }
    .main-content, .content-wrapper, .container-fluid { padding: 0 !important; margin: 0 !important; width: 100% !important; max-width: 100% !important; }
    .report-preview-container { overflow: visible !important; }
    .table-card { border: none !important; box-shadow: none !important; }
    .table-scroll { overflow: visible !important; }
    .summary-card { break-inside: avoid; border: 1px solid #ddd !important; background: #fff !important; box-shadow: none !important; }
    .dt th { background: #f1f5f9 !important; color: #000 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .dt th, .dt td { white-space: normal !important; font-size: .7rem !important; padding: .45rem .5rem !important; }
}

/* width:0 + min-width:100% => container takes the available width but
   never lets a wide table push the whole page outside the screen */
.report-preview-container {
    width: 0;
    min-width: 100%;
    max-width: 100%;
    padding: 0 2px;
    overflow-x: hidden;
    box-sizing: border-box;
}

.prev-toolbar { 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    margin-bottom: 1.5rem; 
    flex-wrap: wrap; 
    gap: 1rem; 
    width: 100%;
    box-sizing: border-box;
}

.prev-toolbar h1 { font-size:1.45rem; font-weight:800; color:#111; margin:0 0 .2rem; letter-spacing:-0.02em; display: flex; align-items: center; gap: 10px; }
.prev-toolbar .sub { color:#666; font-size:.85rem; font-weight:600; display: flex; align-items: center; gap: 6px; }
.prev-toolbar .sub i { color:var(--pk); }

.back-link {
    display:inline-flex; align-items:center; gap:8px; padding:8px 18px; border-radius:50px;
    background:#fff; color:var(--pk); border:1.5px solid var(--pk-lt); text-decoration:none;
    font-weight:700; font-size:.84rem; transition:all .18s; box-shadow: 0 2px 5px rgba(255,107,157,0.06);
}
.back-link:hover { background:var(--pk); color:#fff; border-color:var(--pk); transform: translateY(-1px); }

.btn-print {
    display:inline-flex; align-items:center; gap:8px; padding:9px 22px; border-radius:50px;
    background:linear-gradient(135deg,#FF6B9D,#E85588); color:#fff; border:none;
    font-weight:700; font-size:.85rem; cursor:pointer; box-shadow: 0 4px 12px rgba(255,107,157,0.25);
    transition: all .2s; white-space: nowrap;
}
.btn-print:hover { transform: translateY(-1px); box-shadow: 0 6px 15px rgba(255,107,157,0.35); }

/* Unified Fluid Grid for All Report Summary Cards */
.summary-grid { 
    display: grid; 
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); 
    gap: 1rem; 
    margin-bottom: 1.6rem; 
    width: 100%;
    box-sizing: border-box;
}

.summary-card {
    background: #fff;
    border: 1px solid #eaeaea;
    border-radius: 14px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    padding: 1.1rem 1.25rem;
    box-sizing: border-box;
    box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    position: relative;
    overflow: hidden;
    width: 100%;
    min-width: 0;
    transition: transform .2s ease, box-shadow .2s ease;
}
.summary-card:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,0.05); border-color: #d1d5db; }
.summary-card::before {
    content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%;
}
.summary-card:nth-child(6n+1)::before { background: #f59e0b; }
.summary-card:nth-child(6n+2)::before { background: #3b82f6; }
.summary-card:nth-child(6n+3)::before { background: #ec4899; }
.summary-card:nth-child(6n+4)::before { background: #8b5cf6; }
.summary-card:nth-child(6n+5)::before { background: #06b6d4; }
.summary-card:nth-child(6n+6)::before { background: #10b981; }

.summary-card .lbl { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #6b7280; margin-bottom: .35rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 100%; }
.summary-card .val { font-size: 1.35rem; font-weight: 900; color: #111827; letter-spacing: -0.02em; word-break: break-word; }

/* Table Card Layout */
.table-card { background:#fff; border:1px solid #eaeaea; border-radius:16px; overflow:hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.02); width: 100%; max-width: 100%; min-width: 0; box-sizing: border-box; }
.table-head { padding:1.1rem 1.4rem; border-bottom:1px solid #f2f2f2; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:.5rem; background: #fafbfc; }
.table-head .title { font-weight:800; font-size:.95rem; color:#1a1a1a; display:inline-flex; align-items:center; gap:8px; }
.table-head .count { font-size:.75rem; color:var(--pk); background:var(--pk-bg); padding:.3rem .9rem; border-radius:20px; font-weight:700; border: 1px solid var(--pk-lt); }

/* Wide tables scroll left-right INSIDE the card, not the whole page */
.table-scroll { width: 100%; max-width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
.table-scroll::-webkit-scrollbar { height: 8px; }
.table-scroll::-webkit-scrollbar-thumb { background: #e5c3d1; border-radius: 10px; }
.table-scroll::-webkit-scrollbar-track { background: #f6f6f6; }

.dt { width:100%; border-collapse:collapse; text-align: left; }
.dt thead th { background:#f8f9fa; padding:.85rem 1.1rem; font-size:.7rem; text-transform:uppercase; letter-spacing:.05em; color:#4b5563; border-bottom:2px solid #eaeaea; white-space:nowrap; font-weight: 800; }
.dt tbody td { padding:.85rem 1.1rem; font-size:.84rem; color:#1f2937; border-bottom:1px solid #f2f2f2; white-space:nowrap; font-weight: 500; }
.dt tbody tr:hover { background:#fff8fa; }

/* Tables with many columns (e.g. Client report) get tighter spacing */
.dt.dt-compact thead th { padding:.75rem .8rem; font-size:.66rem; }
.dt.dt-compact tbody td { padding:.75rem .8rem; font-size:.8rem; }

/* Long text columns (e.g. "Salon(s) Booked") wrap onto more lines */
.dt tbody td.wrap { white-space: normal; min-width: 200px; line-height: 1.4; }

.empty-st { text-align:center; padding:3.5rem; color:#9ca3af; }
.empty-st i { font-size:2.5rem; margin-bottom:.8rem; opacity:.4; display:block; }
.empty-st p { color:#4b5563; font-size:.9rem; font-weight: 600; }
</style>

<div class="report-preview-container">
    <div class="prev-toolbar">
        <div>
            <a href="{{ route('admin.reports.index') }}" class="back-link" style="margin-bottom:10px;">
                <i class="fas fa-arrow-left"></i> Back to Reports
            </a>
            <h1>
                <i class="fas fa-file-invoice" style="color:var(--pk);"></i>
                <span>{{ $typeLabel }}</span>
            </h1>
            <div class="sub">
                <i class="fas fa-calendar-alt"></i> 
                <span>Period: {{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }} &mdash; {{ \Carbon\Carbon::parse($toDate)->format('d M Y') }}</span>
            </div>
        </div>
        <button class="btn-print" onclick="window.print()"><i class="fas fa-print"></i> Print Report</button>
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
            <span class="title">
                <i class="fas fa-table" style="color:var(--pk);"></i>
                <span>Detailed Records Listing</span>
            </span>
            <span class="count">{{ count($report['rows']) }} record(s) found</span>
        </div>
        <div class="table-scroll">
            @if(count($report['rows']) > 0)
            <table class="dt {{ count($report['columns']) > 8 ? 'dt-compact' : '' }}">
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
                        <td class="{{ $col['key'] === 'salons' ? 'wrap' : '' }}">{{ $row[$col['key']] ?? '—' }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-st">
                <i class="fas fa-inbox"></i>
                <p>No records found matching this filter and date range.</p>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    @if(request('print'))
    window.addEventListener('load', () => setTimeout(() => window.print(), 350));
    @endif
</script>
@endpush