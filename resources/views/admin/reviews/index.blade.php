@extends('layouts.admin')
@section('title', 'Reviews Management — Glamora Admin')

@section('content')
<style>
:root {
    --pk:#e91e8c; --pk-lt:#fde9f4; --pk-h:#c2177a;
    --sage:#5a8a62; --sage-lt:#eaf3eb;
    --amber:#c47f00; --amber-lt:#fff8e1;
    --crimson:#c0392b; --crimson-lt:#fdecea;
    --slate:#3d6b8a; --slate-lt:#e8f2f8;
}

.pg-hdr { display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem; margin-bottom:1.6rem; }
.pg-hdr h1 { font-size:1.55rem; font-weight:700; margin:0 0 .2rem; color:#1a1a1a; }
.pg-hdr p  { margin:0; color:#9a9a9a; font-size:.86rem; }

/* Summary tiles */
.sum-strip { display:flex; flex-wrap:wrap; gap:.85rem; margin-bottom:1.6rem; }
.sum-tile {
    flex:1; min-width:130px; border-radius:13px; padding:.9rem 1.1rem;
    display:flex; align-items:center; gap:.75rem;
    border:1.5px solid transparent; text-decoration:none; cursor:pointer;
    transition:all .2s;
}
.sum-tile:hover { transform:translateY(-2px); }
.sum-tile-icon { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.95rem; flex-shrink:0; }
.sum-tile-val  { font-size:1.4rem; font-weight:800; line-height:1; }
.sum-tile-lbl  { font-size:.67rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; margin-top:.15rem; opacity:.8; }

.st-total   { background:var(--pk-lt);      border-color:rgba(233,30,140,.2); color:var(--pk); }
.st-total   .sum-tile-icon { background:rgba(233,30,140,.15); }
.st-published { background:var(--sage-lt);  border-color:rgba(90,138,98,.2);  color:var(--sage); }
.st-published .sum-tile-icon { background:rgba(90,138,98,.15); }
.st-reported { background:var(--amber-lt);  border-color:rgba(196,127,0,.2);  color:var(--amber); }
.st-reported .sum-tile-icon { background:rgba(196,127,0,.15); }
.st-hidden  { background:var(--crimson-lt); border-color:rgba(192,57,43,.2);  color:var(--crimson); }
.st-hidden  .sum-tile-icon { background:rgba(192,57,43,.15); }

/* Filter pills */
.pills-wrap { display:flex; flex-wrap:wrap; gap:.45rem; margin-bottom:1.4rem; }
.sp {
    display:inline-flex; align-items:center; gap:.35rem;
    padding:.42rem 1rem; border-radius:999px; font-size:.76rem;
    font-weight:700; text-decoration:none; border:2px solid transparent; transition:all .15s;
}
.sp-off { background:#f3f3f3; color:#999; border-color:#e5e5e5; }
.sp-off:hover { border-color:var(--pk); color:var(--pk); }
.sp.on { background:var(--pk); color:#fff; border-color:var(--pk); }

/* Filter card */
.filter-card { background:#fff; border:1px solid #ebebeb; border-radius:13px; overflow:hidden; margin-bottom:1.4rem; }
.fc-head { padding:.8rem 1.3rem; border-bottom:1px solid #f3f3f3; display:flex; align-items:center; gap:.5rem; }
.fc-head i { color:var(--pk); font-size:.88rem; }
.fc-title { font-weight:700; font-size:.88rem; color:#1a1a1a; }
.fc-body { padding:1rem 1.3rem; }
.f-row { display:flex; flex-wrap:wrap; gap:.85rem; align-items:flex-end; }
.fg { display:flex; flex-direction:column; gap:.32rem; flex:1; min-width:140px; }
.fg label { font-size:.67rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#aaa; }
.fi { width:100%; padding:.6rem .9rem; border:1.5px solid #e5e5e5; border-radius:9px; font-size:.87rem; color:#1a1a1a; background:#fafafa; outline:none; transition:all .2s; font-family:inherit; box-sizing:border-box; }
.fi:focus { border-color:var(--pk); box-shadow:0 0 0 3px rgba(233,30,140,.1); background:#fff; }
.fi-sw { position:relative; }
.fi-sw i.si { position:absolute; left:.85rem; top:50%; transform:translateY(-50%); color:#ccc; font-size:.8rem; pointer-events:none; }
.fi-sw .fi { padding-left:2.2rem; }
.btn-go { padding:.62rem 1.1rem; border-radius:9px; font-size:.85rem; font-weight:700; cursor:pointer; border:none; background:var(--pk); color:#fff; transition:all .18s; white-space:nowrap; }
.btn-go:hover { background:var(--pk-h); }
.btn-clr { padding:.62rem .9rem; border-radius:9px; font-size:.85rem; font-weight:600; cursor:pointer; background:transparent; border:1.5px solid #e5e5e5; color:#aaa; text-decoration:none; transition:all .15s; }
.btn-clr:hover { border-color:var(--pk); color:var(--pk); }

/* Table */
.tcard { background:#fff; border:1px solid #ebebeb; border-radius:13px; overflow:hidden; }
.tc-head { display:flex; justify-content:space-between; align-items:center; padding:.9rem 1.3rem; border-bottom:1px solid #f3f3f3; flex-wrap:wrap; gap:.5rem; }
.tc-title { font-weight:700; font-size:.9rem; color:#1a1a1a; }
.tc-count { font-size:.75rem; color:#aaa; background:#f3f3f3; padding:.2rem .62rem; border-radius:20px; }
.dt { width:100%; border-collapse:collapse; }
.dt thead tr { background:#fafafa; }
.dt thead th { padding:.72rem .9rem; font-size:.66rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#bbb; text-align:left; white-space:nowrap; border-bottom:1px solid #ebebeb; }
.dt tbody tr { border-bottom:1px solid #f5f5f5; transition:background .15s; }
.dt tbody tr:last-child { border-bottom:none; }
.dt tbody tr:hover { background:#fdf5fa; }
.dt td { padding:.8rem .9rem; font-size:.85rem; color:#444; vertical-align:middle; }
.cn { font-weight:600; color:#1a1a1a; }
.csub { font-size:.7rem; color:#aaa; margin-top:.05rem; }
.stars-sm i { font-size:.72rem; }

/* Status badges */
.sbadge { display:inline-flex; align-items:center; gap:.28rem; padding:.25rem .7rem; border-radius:20px; font-size:.71rem; font-weight:700; white-space:nowrap; }
.sdot { width:6px; height:6px; border-radius:50%; display:inline-block; flex-shrink:0; }
.s-published { background:#eaf3eb; color:#3d7045; }
.s-reported  { background:#fff8e1; color:#a06800; }
.s-hidden    { background:#fdecea; color:#a02820; }

/* Action buttons */
.ab { display:inline-flex; align-items:center; gap:.25rem; padding:.32rem .72rem; border-radius:7px; font-size:.73rem; font-weight:700; text-decoration:none; border:1.5px solid; transition:all .15s; cursor:pointer; background:none; font-family:inherit; white-space:nowrap; }
.ab-view   { background:var(--pk-lt); color:var(--pk); border-color:rgba(233,30,140,.2); }
.ab-view:hover { background:var(--pk); color:#fff; }
.ab-hide   { background:#fff8e1; color:#a06800; border-color:rgba(196,127,0,.25); }
.ab-hide:hover { background:#c47f00; color:#fff; border-color:#c47f00; }
.ab-show   { background:#eaf3eb; color:#3d7045; border-color:rgba(90,138,98,.25); }
.ab-show:hover { background:#5a8a62; color:#fff; border-color:#5a8a62; }
.ab-del    { background:#fdecea; color:#a02820; border-color:rgba(192,57,43,.25); }
.ab-del:hover { background:#c0392b; color:#fff; border-color:#c0392b; }

.empty-st { text-align:center; padding:3rem 1rem; }
.empty-st i { font-size:2.2rem; margin-bottom:.7rem; opacity:.3; display:block; }
.pgn-wrap { padding:.9rem 1.3rem; border-top:1px solid #f3f3f3; }
</style>

{{-- Header --}}
<div class="pg-hdr">
    <div>
        <h1><i class="fas fa-star" style="color:var(--pk);margin-right:.5rem;font-size:1.2rem;"></i>Reviews Management</h1>
        <p>Moderate client reviews across all salons</p>
    </div>
</div>

{{-- Alerts --}}
@if(session('success'))
<div style="background:#eaf3eb;border:1px solid #a8d5b0;color:#2d6a35;border-radius:10px;padding:.8rem 1.1rem;margin-bottom:1.2rem;font-size:.87rem;display:flex;align-items:center;gap:.5rem;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="background:#fdecea;border:1px solid #f5a5a5;color:#a02820;border-radius:10px;padding:.8rem 1.1rem;margin-bottom:1.2rem;font-size:.87rem;display:flex;align-items:center;gap:.5rem;">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

{{-- Summary Tiles --}}
<div class="sum-strip">
    <a href="{{ route('admin.reviews.index') }}" class="sum-tile st-total">
        <div class="sum-tile-icon"><i class="fas fa-star"></i></div>
        <div><div class="sum-tile-val">{{ number_format($counts['total']) }}</div><div class="sum-tile-lbl">Total</div></div>
    </a>
    <a href="{{ route('admin.reviews.index', ['status'=>'published']) }}" class="sum-tile st-published">
        <div class="sum-tile-icon"><i class="fas fa-check-circle"></i></div>
        <div><div class="sum-tile-val">{{ number_format($counts['published']) }}</div><div class="sum-tile-lbl">Published</div></div>
    </a>
    <a href="{{ route('admin.reviews.index', ['status'=>'reported']) }}" class="sum-tile st-reported">
        <div class="sum-tile-icon"><i class="fas fa-flag"></i></div>
        <div><div class="sum-tile-val">{{ number_format($counts['reported']) }}</div><div class="sum-tile-lbl">Reported</div></div>
    </a>
    <a href="{{ route('admin.reviews.index', ['status'=>'hidden']) }}" class="sum-tile st-hidden">
        <div class="sum-tile-icon"><i class="fas fa-eye-slash"></i></div>
        <div><div class="sum-tile-val">{{ number_format($counts['hidden']) }}</div><div class="sum-tile-lbl">Hidden</div></div>
    </a>
</div>

{{-- Status Pills --}}
@php $cur = request('status','all'); @endphp
<div class="pills-wrap">
    <a href="{{ route('admin.reviews.index', array_merge(request()->except('status'),['status'=>'all'])) }}"       class="sp {{ $cur==='all'       ? 'on' : 'sp-off' }}"><i class="fas fa-th-list"      style="font-size:.65rem;"></i> All</a>
    <a href="{{ route('admin.reviews.index', array_merge(request()->except('status'),['status'=>'published'])) }}" class="sp {{ $cur==='published' ? 'on' : 'sp-off' }}"><i class="fas fa-check-circle" style="font-size:.65rem;"></i> Published</a>
    <a href="{{ route('admin.reviews.index', array_merge(request()->except('status'),['status'=>'reported'])) }}"  class="sp {{ $cur==='reported'  ? 'on' : 'sp-off' }}"><i class="fas fa-flag"         style="font-size:.65rem;"></i> Reported</a>
    <a href="{{ route('admin.reviews.index', array_merge(request()->except('status'),['status'=>'hidden'])) }}"    class="sp {{ $cur==='hidden'    ? 'on' : 'sp-off' }}"><i class="fas fa-eye-slash"    style="font-size:.65rem;"></i> Hidden</a>
</div>

{{-- Filters --}}
<div class="filter-card">
    <div class="fc-head"><i class="fas fa-filter"></i><span class="fc-title">Search & Filter</span></div>
    <div class="fc-body">
        <form method="GET">
            <div class="f-row">
                <div class="fg" style="flex:2;min-width:180px;">
                    <label>Search</label>
                    <div class="fi-sw">
                        <i class="fas fa-search si"></i>
                        <input type="text" name="search" class="fi" placeholder="Client, salon, or comment…" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="fg" style="min-width:130px;">
                    <label>Rating</label>
                    <select name="rating" class="fi">
                        <option value="">All Ratings</option>
                        @for($i=5;$i>=1;$i--)
                        <option value="{{ $i }}" {{ request('rating')==$i ? 'selected':'' }}>{{ $i }} Star{{ $i>1?'s':'' }}</option>
                        @endfor
                    </select>
                </div>
                <input type="hidden" name="status" value="{{ request('status') }}">
                <div style="display:flex;gap:.5rem;align-items:flex-end;">
                    <button type="submit" class="btn-go"><i class="fas fa-search"></i> Filter</button>
                    <a href="{{ route('admin.reviews.index') }}" class="btn-clr">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="tcard">
    <div class="tc-head">
        <span class="tc-title"><i class="fas fa-star" style="color:var(--pk);margin-right:.4rem;"></i>Review List</span>
        <span class="tc-count">{{ $reviews->total() }} records</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="dt">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Salon</th>
                    <th>Service</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($reviews as $i => $review)
            <tr>
                <td style="color:#bbb;font-size:.78rem;">{{ $reviews->firstItem() + $i }}</td>
                <td>
                    <div class="cn">{{ $review->client->name ?? 'N/A' }}</div>
                    <div class="csub">{{ $review->client->email ?? '' }}</div>
                </td>
                <td><span class="cn" style="font-weight:500;">{{ Str::limit($review->salon->name ?? 'N/A', 18) }}</span></td>
                <td style="font-size:.8rem;color:#666;">{{ Str::limit($review->appointment->service->name ?? '—', 16) }}</td>
                <td>
                    <div class="stars-sm d-flex align-items-center gap-1">
                        @for($s=1;$s<=5;$s++)
                        <i class="fas fa-star" style="color:{{ $s<=$review->rating ? '#ffc107':'#e5e7eb' }};"></i>
                        @endfor
                        <span style="font-size:.78rem;font-weight:700;color:#333;margin-left:2px;">{{ $review->rating }}</span>
                    </div>
                </td>
                <td style="max-width:200px;">
                    <div style="font-size:.8rem;color:#555;line-height:1.5;">{{ Str::limit($review->comment, 60) }}</div>
                    @if($review->reply)
                    <div style="font-size:.7rem;color:#5a8a62;margin-top:.2rem;"><i class="fas fa-reply"></i> Owner replied</div>
                    @endif
                </td>
                <td>
                    @if($review->is_flagged)
                    <span class="sbadge s-reported"><span class="sdot" style="background:#c47f00;"></span>Reported</span>
                    @elseif($review->is_approved)
                    <span class="sbadge s-published"><span class="sdot" style="background:#5a8a62;"></span>Published</span>
                    @else
                    <span class="sbadge s-hidden"><span class="sdot" style="background:#c0392b;"></span>Hidden</span>
                    @endif
                </td>
                <td style="font-size:.78rem;white-space:nowrap;">{{ $review->created_at->format('d M Y') }}</td>
                <td>
                    <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                        <a href="{{ route('admin.reviews.show', $review->id) }}" class="ab ab-view">
                            <i class="fas fa-eye"></i> View
                        </a>
                        @if($review->is_approved && !$review->is_flagged)
                        <form action="{{ route('admin.reviews.hide', $review->id) }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" class="ab ab-hide" onclick="return confirm('Hide this review?')">
                                <i class="fas fa-eye-slash"></i> Hide
                            </button>
                        </form>
                        @else
                        <form action="{{ route('admin.reviews.publish', $review->id) }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" class="ab ab-show">
                                <i class="fas fa-check"></i> Publish
                            </button>
                        </form>
                        @endif
                        <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" style="margin:0;">
                            @csrf @method('DELETE')
                            <button type="submit" class="ab ab-del" onclick="return confirm('Permanently delete this review?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9">
                <div class="empty-st">
                    <i class="fas fa-star"></i>
                    <p style="color:#999;font-size:.88rem;">No reviews found</p>
                </div>
            </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($reviews->hasPages())
    <div class="pgn-wrap">{{ $reviews->links() }}</div>
    @endif
</div>
@endsection