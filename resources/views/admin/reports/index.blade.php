@extends('layouts.admin')
@section('title', 'Reports & Analytics — Glamora Admin')

@section('content')
<style>
:root {
    --pk:      #E91E8C; --pk-lt:  #fce4ec; --pk-bg:  #fff0f7;
    --teal:    #0891b2;
    --green:   #16a34a;
    --amber:   #d97706;
    --purple:  #7c3aed;
    --slate:   #475569;
    --crimson: #dc2626;
}

.pg-hdr { margin-bottom:1.6rem; }
.pg-hdr h1 { font-size:1.55rem; font-weight:700; margin:0 0 .2rem; color:#1a1a1a; }
.pg-hdr p  { margin:0; color:#9a9a9a; font-size:.86rem; }

/* ── Report Cards ── */
.reports-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(320px,1fr)); gap:1rem; }
.report-card { background:#fff; border:1px solid #ebebeb; border-radius:14px; overflow:hidden; }
.rc-head { padding:.9rem 1.2rem; display:flex; align-items:center; gap:.6rem; border-bottom:1px solid #f3f3f3; }
.rc-head .rc-icon { width:34px;height:34px;border-radius:9px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.85rem;flex-shrink:0; }
.rc-head .rc-title { font-weight:700; font-size:.9rem; color:#1a1a1a; }
.rc-body { padding:1rem 1.2rem 1.2rem; }
.rc-body label { font-size:.66rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#aaa; display:block; margin-bottom:.3rem; }
.rc-fi {
    width:100%; padding:.5rem .7rem; border:1.5px solid #e5e5e5; border-radius:8px;
    font-size:.82rem; color:#333; background:#fafafa; outline:none; box-sizing:border-box; margin-bottom:.7rem;
    font-family:inherit;
}
.rc-fi:focus { border-color:var(--pk); background:#fff; }
.rc-row2 { display:grid; grid-template-columns:1fr 1fr; gap:.5rem; }
.rc-actions { display:grid; grid-template-columns:repeat(4,1fr); gap:.4rem; margin-top:.4rem; }
.rc-btn {
    display:flex; align-items:center; justify-content:center; gap:.3rem;
    padding:.5rem .3rem; border-radius:8px; font-size:.72rem; font-weight:700;
    border:none; cursor:pointer; transition:opacity .15s; color:#fff;
}
.rc-btn:hover { opacity:.88; }
.rc-btn.view  { background:#475569; }
.rc-btn.pdf   { background:#dc2626; }
.rc-btn.excel { background:#16a34a; }
.rc-btn.print { background:#0891b2; }

.pk-icon      { background:linear-gradient(135deg,var(--pk),#c2185b); }
.teal-icon    { background:linear-gradient(135deg,var(--teal),#0e7490); }
.green-icon   { background:linear-gradient(135deg,var(--green),#0d8a3e); }
.amber-icon   { background:linear-gradient(135deg,var(--amber),#b45309); }
.purple-icon  { background:linear-gradient(135deg,var(--purple),#5b21b6); }
.slate-icon   { background:linear-gradient(135deg,var(--slate),#334155); }
.crimson-icon { background:linear-gradient(135deg,var(--crimson),#991b1b); }
</style>

<div class="pg-hdr">
    <h1><i class="fas fa-chart-line" style="color:var(--pk);margin-right:.5rem;"></i>Reports &amp; Analytics</h1>
    <p>Generate, view, and download detailed reports — all based on your real data.</p>
</div>

{{-- ── Report Cards — View / Filter / Search / Export PDF / Export Excel / Print ── --}}
<div class="reports-grid">
    @foreach($reportTypes as $key => $info)
    <div class="report-card" data-type="{{ $key }}">
        <div class="rc-head">
            <div class="rc-icon {{ $info['color'] }}-icon"><i class="fas {{ $info['icon'] }}"></i></div>
            <div class="rc-title">{{ $info['label'] }}</div>
        </div>
        <div class="rc-body">
            <label>Quick Range (Filter)</label>
            <select class="rc-fi quick-range" onchange="applyQuickRange(this)">
                <option value="">Custom</option>
                <option value="today">Today</option>
                <option value="yesterday">Yesterday</option>
                <option value="week">This Week</option>
                <option value="month" selected>This Month</option>
                <option value="year">This Year</option>
            </select>

            <div class="rc-row2">
                <div>
                    <label>From</label>
                    <input type="date" class="rc-fi from-date" value="{{ now()->startOfMonth()->format('Y-m-d') }}">
                </div>
                <div>
                    <label>To</label>
                    <input type="date" class="rc-fi to-date" value="{{ now()->format('Y-m-d') }}">
                </div>
            </div>

            @if($key !== 'revenue')
            <label>Search</label>
            <input type="text" class="rc-fi search-input" placeholder="Name, ID, city...">
            @endif

            <div class="rc-actions">
                <button type="button" class="rc-btn view"  onclick="viewReport(this)"><i class="fas fa-eye"></i> View</button>
                <button type="button" class="rc-btn pdf"   onclick="exportReport(this,'pdf')"><i class="fas fa-file-pdf"></i> PDF</button>
                <button type="button" class="rc-btn excel" onclick="exportReport(this,'excel')"><i class="fas fa-file-excel"></i> Excel</button>
                <button type="button" class="rc-btn print" onclick="printReport(this)"><i class="fas fa-print"></i> Print</button>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection

@push('scripts')
<script>
const csrfToken = "{{ csrf_token() }}";
const previewUrl = "{{ route('admin.reports.preview') }}";
const exportUrl  = "{{ route('admin.reports.export') }}";

function getCardValues(btn) {
    const card = btn.closest('.report-card');
    const searchEl = card.querySelector('.search-input');
    return {
        type: card.dataset.type,
        from_date: card.querySelector('.from-date').value,
        to_date: card.querySelector('.to-date').value,
        search: searchEl ? searchEl.value : '',
    };
}

function viewReport(btn) {
    const v = getCardValues(btn);
    const params = new URLSearchParams(v);
    window.open(previewUrl + '?' + params.toString(), '_blank');
}

function printReport(btn) {
    const v = getCardValues(btn);
    v.print = 1;
    const params = new URLSearchParams(v);
    window.open(previewUrl + '?' + params.toString(), '_blank');
}

function exportReport(btn, format) {
    const v = getCardValues(btn);
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = exportUrl;
    form.style.display = 'none';

    const fields = { _token: csrfToken, type: v.type, from_date: v.from_date, to_date: v.to_date, search: v.search, format: format };
    Object.keys(fields).forEach(name => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = fields[name] ?? '';
        form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
}

function applyQuickRange(select) {
    if (!select.value) return; // "Custom" — leave dates as the user set them
    const card = select.closest('.report-card');
    const fromInput = card.querySelector('.from-date');
    const toInput = card.querySelector('.to-date');
    const today = new Date();
    let from = new Date(today), to = new Date(today);

    switch (select.value) {
        case 'today': break;
        case 'yesterday':
            from.setDate(today.getDate() - 1);
            to.setDate(today.getDate() - 1);
            break;
        case 'week':
            from.setDate(today.getDate() - today.getDay() + 1);
            break;
        case 'month':
            from = new Date(today.getFullYear(), today.getMonth(), 1);
            break;
        case 'year':
            from = new Date(today.getFullYear(), 0, 1);
            break;
    }

    fromInput.value = from.toISOString().split('T')[0];
    toInput.value = to.toISOString().split('T')[0];
}
</script>
@endpush