@extends('layouts.admin')
@section('title', 'Categories - Glamora')

@section('content')
<style>
:root {
    --rose:     #b3466b;
    --rose-h:   #9c3759;
    --rose-lt:  #fbeef2;
    --rose-md:  #dd92ac;
    --sage:     #5e9272;
    --sage-lt:  #eef6f1;
    --dust:     #8b6b4a;
    --dust-lt:  #f8f1e8;
    --slate:    #4f7c93;
    --slate-lt: #ecf4f7;
    --mist:     #7c6a96;
    --mist-lt:  #f3f0f8;
    --clay:     #a0674a;
    --clay-lt:  #fdf2ee;
    --red:      #c1495a;
    --red-lt:   #fdf0f1;
}

.page-header {
    display:flex; justify-content:space-between; align-items:flex-start;
    margin-bottom:2rem; flex-wrap:wrap; gap:1rem;
}
.page-header h1 { font-size:1.65rem; font-weight:700; margin:0 0 .2rem; color:#2d2d2d; }
.page-header p  { margin:0; color:#8a8a8a; font-size:.88rem; }

.btn-add {
    display:inline-flex; align-items:center; gap:.5rem;
    padding:.65rem 1.3rem; background:var(--rose); color:#fff;
    border-radius:10px; font-size:.88rem; font-weight:700;
    text-decoration:none; transition:all .18s; white-space:nowrap; border:none; cursor:pointer;
}
.btn-add:hover { background:var(--rose-h); color:#fff; transform:translateY(-1px); box-shadow:0 4px 14px rgba(179,70,107,.3); }

/* Stats — pretty cards */
.stats-row { display:grid; grid-template-columns:repeat(4,1fr); gap:1.1rem; margin-bottom:2.2rem; }
@media(max-width:768px){ .stats-row { grid-template-columns:repeat(2,1fr); } }
.stat-card {
    position:relative; overflow:hidden; background:#fff;
    border:1px solid #f1e9ed; border-radius:18px;
    padding:1.25rem 1.3rem; display:flex; align-items:center; gap:1rem;
    transition:transform .2s, box-shadow .2s;
}
.stat-card::after {
    content:''; position:absolute; width:100px; height:100px;
    border-radius:50%; right:-35px; top:-35px; opacity:.07;
}
.stat-card:hover { transform:translateY(-3px); box-shadow:0 12px 28px rgba(0,0,0,.08); }
.stat-card.c-rose::after  { background:var(--rose); }
.stat-card.c-sage::after  { background:var(--sage); }
.stat-card.c-slate::after { background:var(--slate); }
.stat-card.c-red::after   { background:var(--red); }

.stat-icon {
    width:50px; height:50px; border-radius:14px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:1.15rem;
    color:#fff; box-shadow:0 5px 12px rgba(0,0,0,.1);
}
.stat-icon.c-rose  { background:linear-gradient(135deg,var(--rose),var(--rose-md)); }
.stat-icon.c-sage  { background:linear-gradient(135deg,var(--sage),#92c5a4); }
.stat-icon.c-slate { background:linear-gradient(135deg,var(--slate),#87b3c5); }
.stat-icon.c-red   { background:linear-gradient(135deg,var(--red),#e0909c); }

.stat-val { font-size:1.65rem; font-weight:800; color:#2d2d2d; line-height:1; letter-spacing:-.01em; }
.stat-lbl { font-size:.74rem; color:#a89d9a; margin-top:.25rem; font-weight:600; }

/* Grid */
.cat-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(250px,1fr)); gap:1.25rem; }

/* Card */
.cat-card {
    background:#fff; border:1px solid #ede9e4; border-radius:16px;
    overflow:hidden; transition:box-shadow .22s, transform .2s; cursor:pointer;
    display:flex; flex-direction:column;
}
.cat-card:hover { box-shadow:0 8px 30px rgba(0,0,0,.1); transform:translateY(-4px); }

/* Image area */
.cat-img-area {
    height:130px; position:relative; overflow:hidden;
    display:flex; align-items:center; justify-content:center;
}
.cat-img-area img {
    width:100%; height:100%; object-fit:cover; display:block;
    transition:transform .3s;
}
.cat-card:hover .cat-img-area img { transform:scale(1.06); }
.cat-img-overlay {
    position:absolute; inset:0;
    background:linear-gradient(to bottom, rgba(0,0,0,.08), rgba(0,0,0,.38));
    display:flex; align-items:center; justify-content:center;
}
/* No image fallback */
.cat-no-img {
    width:100%; height:130px;
    display:flex; align-items:center; justify-content:center;
    position:relative;
}
.cat-no-img-icon {
    width:58px; height:58px; border-radius:50%;
    background:rgba(255,255,255,.22);
    display:flex; align-items:center; justify-content:center;
    font-size:1.6rem; color:#fff;
    border:2px solid rgba(255,255,255,.3);
    backdrop-filter:blur(2px);
}

/* Status badge on image */
.cat-status-badge {
    position:absolute; top:.65rem; right:.65rem;
    padding:.22rem .65rem; border-radius:20px;
    font-size:.68rem; font-weight:700; backdrop-filter:blur(4px);
}
.cat-status-badge.active   { background:rgba(94,146,114,.88); color:#fff; }
.cat-status-badge.inactive { background:rgba(193,73,90,.85);  color:#fff; }

.cat-body { padding:1rem 1.1rem; flex:1; }
.cat-name {
    font-size:.97rem; font-weight:700; color:#2d2d2d;
    margin:0 0 .28rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
}
.cat-desc {
    font-size:.78rem; color:#8a8a8a; margin:0 0 .75rem;
    display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;
    overflow:hidden; line-height:1.5; min-height:2.3em;
}
.cat-pills { display:flex; gap:.4rem; flex-wrap:wrap; }
.cpill {
    display:inline-flex; align-items:center; gap:.25rem;
    padding:.2rem .6rem; border-radius:20px; font-size:.7rem; font-weight:600;
}
.cpill-svc  { background:var(--slate-lt); color:var(--slate); }
.cpill-dur  { background:var(--dust-lt);  color:var(--dust);  }
.cpill-ord  { background:var(--mist-lt);  color:var(--mist);  }

.cat-footer {
    display:flex; gap:.5rem; padding:.8rem 1.1rem;
    border-top:1px solid #f5f2ee; background:#faf8f6;
}
.btn-ce {
    flex:1; display:inline-flex; align-items:center; justify-content:center;
    gap:.35rem; padding:.5rem .7rem; border-radius:8px;
    font-size:.78rem; font-weight:700; text-decoration:none;
    transition:all .15s; cursor:pointer; border:none;
}
.btn-ce-edit   { background:var(--slate-lt); color:var(--slate); border:1px solid rgba(79,124,147,.2); }
.btn-ce-edit:hover { background:var(--slate); color:#fff; }
.btn-ce-del    { width:34px; flex:none; background:var(--rose-lt); color:var(--rose); border:1px solid rgba(179,70,107,.2); }
.btn-ce-del:hover { background:var(--rose); color:#fff; }

.empty-state { text-align:center; padding:4rem 1rem; color:#bbb; grid-column:1/-1; }
.empty-state i { font-size:3rem; margin-bottom:1rem; opacity:.3; display:block; }
.empty-state h3 { color:#555; margin-bottom:.5rem; font-size:1.1rem; }
</style>

<div class="page-header">
    <div>
        <h1><i class="fas fa-th-large" style="color:var(--rose);margin-right:.5rem;font-size:1.3rem;"></i>Service Categories</h1>
        <p>Manage categories displayed to clients during booking</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn-add">
        <i class="fas fa-plus"></i> Add Category
    </a>
</div>

{{-- Stats --}}
<div class="stats-row">
    <div class="stat-card c-rose">
        <div class="stat-icon c-rose"><i class="fas fa-th-large"></i></div>
        <div><div class="stat-val">{{ $categories->total() }}</div><div class="stat-lbl">Total Categories</div></div>
    </div>
    <div class="stat-card c-sage">
        <div class="stat-icon c-sage"><i class="fas fa-check-circle"></i></div>
        <div><div class="stat-val">{{ $categories->getCollection()->where('is_active',true)->count() }}</div><div class="stat-lbl">Active</div></div>
    </div>
    <div class="stat-card c-slate">
        <div class="stat-icon c-slate"><i class="fas fa-concierge-bell"></i></div>
        <div><div class="stat-val">{{ $categories->getCollection()->sum('services_count') }}</div><div class="stat-lbl">Total Services</div></div>
    </div>
    <div class="stat-card c-red">
        <div class="stat-icon c-red"><i class="fas fa-ban"></i></div>
        <div><div class="stat-val">{{ $categories->getCollection()->where('is_active',false)->count() }}</div><div class="stat-lbl">Inactive</div></div>
    </div>
</div>

{{-- Grid --}}
<div class="cat-grid">
@php
$palettes = [
    ['#e07a93','#f0a8b8'],
    ['#9b7fd4','#c4aef0'],
    ['#e0a23d','#f0c873'],
    ['#4fb393','#85d4b5'],
    ['#f08066','#f7ab94'],
    ['#6e87d6','#a3b8ed'],
    ['#3f9fae','#7ecbd4'],
    ['#b0568f','#d99cc0'],
];
@endphp
@forelse($categories as $i => $cat)
@php $p = $palettes[$cat->id % count($palettes)]; @endphp
<div class="cat-card" onclick="window.location='{{ route('admin.categories.edit', $cat->id) }}'">

    {{-- Image / Gradient --}}
    @if($cat->image)
    <div class="cat-img-area">
        <img src="{{ asset('storage/'.$cat->image) }}" alt="{{ $cat->name }}">
        <div class="cat-img-overlay">
            <i class="fas {{ $cat->icon ?? 'fa-tag' }}" style="font-size:1.6rem;color:rgba(255,255,255,.7);"></i>
        </div>
        <span class="cat-status-badge {{ $cat->is_active ? 'active' : 'inactive' }}">
            {{ $cat->is_active ? 'Active' : 'Inactive' }}
        </span>
    </div>
    @else
    <div class="cat-no-img" style="background:linear-gradient(135deg,{{ $p[0] }},{{ $p[1] }});">
        <div class="cat-no-img-icon"><i class="fas {{ $cat->icon ?? 'fa-tag' }}"></i></div>
        <span class="cat-status-badge {{ $cat->is_active ? 'active' : 'inactive' }}">
            {{ $cat->is_active ? 'Active' : 'Inactive' }}
        </span>
    </div>
    @endif

    <div class="cat-body">
        <div class="cat-name">{{ $cat->name }}</div>
        <div class="cat-desc">{{ $cat->description ?? 'No description added.' }}</div>
        <div class="cat-pills">
            <span class="cpill cpill-svc"><i class="fas fa-scissors" style="font-size:.62rem;"></i>{{ $cat->services_count ?? 0 }} services</span>
            @if($cat->duration ?? false)<span class="cpill cpill-dur"><i class="fas fa-clock" style="font-size:.62rem;"></i>{{ $cat->duration }} min</span>@endif
            @if($cat->sort_order)<span class="cpill cpill-ord"><i class="fas fa-sort" style="font-size:.62rem;"></i>#{{ $cat->sort_order }}</span>@endif
        </div>
    </div>

    <div class="cat-footer" onclick="event.stopPropagation()">
        <a href="{{ route('admin.categories.edit', $cat->id) }}" class="btn-ce btn-ce-edit">
            <i class="fas fa-pen"></i> Edit
        </a>
        <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" style="margin:0;">
            @csrf @method('DELETE')
            <button type="submit" class="btn-ce btn-ce-del" title="Delete"
                onclick="return confirm('Delete \'{{ addslashes($cat->name) }}\'?')">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    </div>
</div>
@empty
<div class="empty-state">
    <i class="fas fa-th-large"></i>
    <h3>No categories yet</h3>
    <p style="margin-bottom:1.2rem;font-size:.88rem;">Create your first service category to get started.</p>
    <a href="{{ route('admin.categories.create') }}" class="btn-add"><i class="fas fa-plus"></i> Add First Category</a>
</div>
@endforelse
</div>

@if($categories->hasPages())
<div style="margin-top:1.5rem;">{{ $categories->links() }}</div>
@endif
@endsection