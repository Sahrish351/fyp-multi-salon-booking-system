@extends('layouts.admin')
@section('title', 'Reports & Analytics — Beauty Blush Salons Admin')

@section('content')
<style>
:root {
    --pk: #FF6B9D; 
    --pk-lt: #fce4ec; 
    --pk-bg: #fff0f7;
    --teal: #0891b2;
    --green: #16a34a;
    --amber: #d97706;
    --purple: #7c3aed;
    --slate: #475569;
    --crimson: #dc2626;
}

.pg-hdr { 
    margin-bottom: 1.8rem; 
    display: flex; 
    justify-content: space-between; 
    align-items: flex-end; 
    flex-wrap: wrap; 
    gap: 1rem; 
}
.pg-hdr h1 { font-size: 1.6rem; font-weight: 800; margin: 0 0 .3rem; color: #111; letter-spacing: -0.02em; }
.pg-hdr p  { margin: 0; color: #777; font-size: .88rem; font-weight: 500; }

/* ── Report Cards Container ── */
.reports-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 1.5rem; }
.report-card { 
    background: #fff; 
    border: 1px solid #eaeaea; 
    border-radius: 18px; 
    overflow: hidden; 
    transition: all .25s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
}
.report-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(255,107,157,0.1);
    border-color: rgba(255,107,157,0.3);
}

.rc-head { padding: 1.2rem 1.4rem; display: flex; align-items: center; gap: .8rem; border-bottom: 1px solid #f2f2f2; background: #fafbfc; }
.rc-head .rc-icon { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: .95rem; flex-shrink: 0; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
.rc-head .rc-title { font-weight: 800; font-size: 1rem; color: #1a1a1a; letter-spacing: -0.01em; }

.rc-body { padding: 1.35rem 1.4rem 1.4rem; }
.rc-body label { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #777; display: block; margin-bottom: .4rem; }

.rc-fi {
    width: 100%; padding: .65rem .9rem; border: 1.5px solid #e2e2e2; border-radius: 10px;
    font-size: .86rem; color: #222; background: #fcfcfc; outline: none; box-sizing: border-box; margin-bottom: .9rem;
    font-family: inherit; transition: all .2s;
}
.rc-fi:focus { border-color: var(--pk); background: #fff; box-shadow: 0 0 0 4px rgba(255,107,157,0.08); }

.rc-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: .8rem; }
.rc-actions { display: grid; grid-template-columns: repeat(4, 1fr); gap: .5rem; margin-top: .7rem; }

.rc-btn {
    display: flex; align-items: center; justify-content: center; gap: .3rem;
    padding: .65rem .2rem; border-radius: 10px; font-size: .76rem; font-weight: 700;
    border: none; cursor: pointer; transition: all .2s; color: #fff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}
.rc-btn:hover { transform: translateY(-1px); opacity: .92; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
.rc-btn.view  { background: #475569; }
.rc-btn.pdf   { background: #dc2626; }
.rc-btn.excel { background: #16a34a; }
.rc-btn.print { background: #0891b2; }

/* Gradients for Icons */
.pk-icon      { background: linear-gradient(135deg, var(--pk), #E85588); }
.teal-icon    { background: linear-gradient(135deg, var(--teal), #0e7490); }
.green-icon   { background: linear-gradient(135deg, var(--green), #0d8a3e); }
.amber-icon   { background: linear-gradient(135deg, var(--amber), #b45309); }
.purple-icon  { background: linear-gradient(135deg, var(--purple), #5b21b6); }
.slate-icon   { background: linear-gradient(135deg, var(--slate), #334155); }
.crimson-icon { background: linear-gradient(135deg, var(--crimson), #991b1b); }
</style>

<div class="pg-hdr">
    <div>
        <h1><i class="fas fa-chart-line" style="color:var(--pk);margin-right:.5rem;"></i>Reports</h1>
        <p>Generate, filter, preview and export professional business reports instantly.</p>
    </div>
</div>

<div class="reports-grid">
    @foreach($reportTypes as $key => $info)
    <div class="report-card" data-type="{{ $key }}">
        <div class="rc-head">
            <div class="rc-icon {{ $info['color'] }}-icon"><i class="fas {{ $info['icon'] }}"></i></div>
            <div class="rc-title">{{ $info['label'] }}</div>
        </div>
        <div class="rc-body">
            <label>Quick Date Range Filter</label>
            <select class="rc-fi quick-range" onchange="applyQuickRange(this)">
                <option value="">Custom Date Range</option>
                <option value="today">Today</option>
                <option value="yesterday">Yesterday</option>
                <option value="week">This Week</option>
                <option value="month" selected>This Month</option>
                <option value="year">This Year</option>
            </select>

            <div class="rc-row2">
                <div>
                    <label>From Date</label>
                    <input type="date" class="rc-fi from-date" value="{{ now()->startOfMonth()->format('Y-m-d') }}">
                </div>
                <div>
                    <label>To Date</label>
                    <input type="date" class="rc-fi to-date" value="{{ now()->format('Y-m-d') }}">
                </div>
            </div>

            {{-- Sab reports mein ab search filter barabar aa gaya hai --}}
            <label>Search Filter</label>
            <input type="text" class="rc-fi search-input" placeholder="Filter by name, ID, keyword...">

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
    if (!select.value) return; 
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