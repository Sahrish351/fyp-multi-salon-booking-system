@extends('layouts.admin')
@section('title', 'Review Details — Glamora Admin')

@push('styles')
<style>
    :root { 
        --pk: #e91e8c; 
        --pk-lt: #fde9f4; 
        --pk-h: #c2177a; 
    }
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .48rem 1.05rem;
        border: 1.5px solid #e5e5e5;
        border-radius: 9px;
        font-size: .85rem;
        font-weight: 600;
        color: #888;
        text-decoration: none;
        background: #fff;
        transition: all .15s;
        margin-bottom: 1.6rem;
    }
    .btn-back:hover {
        border-color: var(--pk);
        color: var(--pk);
    }
    .dcard {
        background: #fff;
        border: 1px solid #ede9e4;
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 1.2rem;
    }
    .dcard-head {
        padding: .85rem 1.35rem;
        border-bottom: 1px solid #f5f2ee;
        display: flex;
        align-items: center;
        gap: .5rem;
    }
    .dcard-head i {
        color: var(--pk);
        font-size: .88rem;
    }
    .dcard-title {
        font-weight: 700;
        font-size: .9rem;
        color: #2d2d2d;
    }
    .dcard-body {
        padding: 1.3rem 1.35rem;
    }
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    @media(max-width:600px){
        .info-grid {
            grid-template-columns: 1fr;
        }
    }
    .info-lbl {
        font-size: .67rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #9a9a9a;
        margin-bottom: .32rem;
        display: block;
    }
    .info-val {
        font-size: .9rem;
        color: #2d2d2d;
        font-weight: 500;
    }
    .ab {
        display: inline-flex;
        align-items: center;
        gap: .28rem;
        padding: .45rem .95rem;
        border-radius: 8px;
        font-size: .8rem;
        font-weight: 700;
        text-decoration: none;
        border: 1.5px solid;
        transition: all .15s;
        cursor: pointer;
        background: none;
        font-family: inherit;
    }
    .ab-hide {
        background: #fff8e1;
        color: #a06800;
        border-color: rgba(196,127,0,.25);
    }
    .ab-hide:hover {
        background: #c47f00;
        color: #fff;
        border-color: #c47f00;
    }
    .ab-show {
        background: #eaf3eb;
        color: #3d7045;
        border-color: rgba(90,138,98,.25);
    }
    .ab-show:hover {
        background: #5a8a62;
        color: #fff;
        border-color: #5a8a62;
    }
    .ab-del {
        background: #fdecea;
        color: #a02820;
        border-color: rgba(192,57,43,.25);
    }
    .ab-del:hover {
        background: #c0392b;
        color: #fff;
        border-color: #c0392b;
    }
    .review-layout {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 1.3rem;
        align-items: start;
    }
    @media(max-width:900px){
        .review-layout {
            grid-template-columns: 1fr;
        }
    }
    .flag-badge {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .3rem .8rem;
        border-radius: 20px;
        font-size: .7rem;
        font-weight: 700;
    }
    .flag-yes {
        background: #fff8e1;
        color: #a06800;
    }
    .flag-no {
        background: #eaf3eb;
        color: #3d7045;
    }
</style>
@endpush

@section('content')

<a href="{{ route('admin.reviews.index') }}" class="btn-back">
    <i class="fas fa-arrow-left"></i> Back to Reviews
</a>

{{-- Alerts --}}
@if(session('success'))
<div style="background:#eaf3eb;border:1px solid #a8d5b0;color:#2d6a35;border-radius:10px;padding:.8rem 1.1rem;margin-bottom:1.2rem;font-size:.87rem;display:flex;align-items:center;gap:.5rem;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<div class="review-layout">

    {{-- LEFT --}}
    <div>
        {{-- Review Info --}}
        <div class="dcard">
            <div class="dcard-head"><i class="fas fa-star"></i><span class="dcard-title">Review Details</span></div>
            <div class="dcard-body">
                <div class="info-grid">
                    <div>
                        <span class="info-lbl">Client</span>
                        <div class="info-val" style="font-weight:700;">{{ $review->client->name ?? 'N/A' }}</div>
                        <div style="font-size:.75rem;color:#aaa;">{{ $review->client->email ?? '' }}</div>
                    </div>
                    <div>
                        <span class="info-lbl">Salon</span>
                        <div class="info-val" style="font-weight:700;">{{ $review->salon->name ?? 'N/A' }}</div>
                        <div style="font-size:.75rem;color:#aaa;">{{ $review->salon->city ?? '' }}</div>
                    </div>
                    <div>
                        <span class="info-lbl">Service</span>
                        <div class="info-val">{{ $review->appointment->service->name ?? '—' }}</div>
                    </div>
                    <div>
                        <span class="info-lbl">Submitted On</span>
                        <div class="info-val">{{ $review->created_at->format('d M Y, h:i A') }}</div>
                    </div>
                </div>

                {{-- Stars --}}
                <div style="margin-top:1.1rem;padding-top:1rem;border-top:1px solid #f5f2ee;">
                    <span class="info-lbl">Rating</span>
                    <div style="display:flex;align-items:center;gap:.35rem;margin-top:.3rem;">
                        @for($i=1;$i<=5;$i++)
                        <i class="fas fa-star" style="font-size:1.3rem;color:{{ $i<=$review->rating ? '#ffc107':'#e5e7eb' }};"></i>
                        @endfor
                        <span style="font-weight:800;font-size:1.05rem;color:#1a1a1a;margin-left:.25rem;">{{ $review->rating }}/5</span>
                    </div>
                </div>

                {{-- Comment --}}
                <div style="margin-top:1rem;padding:1rem;background:#faf8f6;border-radius:10px;border-left:3px solid var(--pk);">
                    <span class="info-lbl" style="margin-bottom:.3rem;display:block;">Client Review</span>
                    <p style="margin:0;font-size:.88rem;color:#4a4a4a;line-height:1.7;">{{ $review->comment }}</p>
                </div>
            </div>
        </div>

        {{-- Owner Reply --}}
        @if(isset($review->reply) && $review->reply)
        <div class="dcard">
            <div class="dcard-head"><i class="fas fa-reply"></i><span class="dcard-title">Salon Owner's Reply</span></div>
            <div class="dcard-body">
                <div style="padding:1rem;background:#f0fdf4;border-radius:10px;border-left:3px solid #5a8a62;">
                    <p style="margin:0;font-size:.88rem;color:#4a4a4a;line-height:1.7;">{{ $review->reply->reply ?? 'No reply' }}</p>
                    <div style="font-size:.73rem;color:#aaa;margin-top:.5rem;">{{ $review->reply->created_at->diffForHumans() ?? '' }}</div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- RIGHT --}}
    <div>
        {{-- Status & Actions --}}
        <div class="dcard">
            <div class="dcard-head"><i class="fas fa-bolt"></i><span class="dcard-title">Status & Actions</span></div>
            <div class="dcard-body">

                {{-- Current Status --}}
                <div style="margin-bottom:1.1rem;text-align:center;padding:.9rem;background:#faf8f6;border-radius:10px;">
                    @if(isset($review->is_flagged) && $review->is_flagged)
                    <i class="fas fa-flag" style="font-size:2rem;color:#c47f00;display:block;margin-bottom:.4rem;"></i>
                    <div style="font-weight:700;color:#c47f00;">Reported by Owner</div>
                    <div style="font-size:.75rem;color:#aaa;margin-top:.2rem;">Awaiting admin action</div>
                    @elseif(isset($review->is_approved) && $review->is_approved)
                    <i class="fas fa-check-circle" style="font-size:2rem;color:#5a8a62;display:block;margin-bottom:.4rem;"></i>
                    <div style="font-weight:700;color:#5a8a62;">Published</div>
                    <div style="font-size:.75rem;color:#aaa;margin-top:.2rem;">Visible to public</div>
                    @else
                    <i class="fas fa-eye-slash" style="font-size:2rem;color:#c0392b;display:block;margin-bottom:.4rem;"></i>
                    <div style="font-weight:700;color:#c0392b;">Hidden</div>
                    <div style="font-size:.75rem;color:#aaa;margin-top:.2rem;">Not visible to public</div>
                    @endif
                </div>

                {{-- Action buttons --}}
                <div style="display:flex;flex-direction:column;gap:.6rem;">
                    @if(isset($review->is_approved) && $review->is_approved && !($review->is_flagged ?? false))
                    <form action="{{ route('admin.reviews.hide', $review->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="ab ab-hide" style="width:100%;justify-content:center;" onclick="return confirm('Hide this review?')">
                            <i class="fas fa-eye-slash"></i> Hide Review
                        </button>
                    </form>
                    @else
                    <form action="{{ route('admin.reviews.publish', $review->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="ab ab-show" style="width:100%;justify-content:center;">
                            <i class="fas fa-check"></i> Publish Review
                        </button>
                    </form>
                    @endif

                    <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" class="ab ab-del" style="width:100%;justify-content:center;" onclick="return confirm('Permanently delete? This cannot be undone.')">
                            <i class="fas fa-trash"></i> Delete Review
                        </button>
                    </form>
                </div>

                @if(isset($review->is_flagged) && $review->is_flagged)
                <div style="margin-top:.9rem;padding:.75rem;background:#fff8e1;border:1px solid #ffe082;border-radius:9px;font-size:.78rem;color:#795548;">
                    <i class="fas fa-exclamation-triangle" style="color:#f59e0b;"></i>
                    This review was flagged by the salon owner as fake or abusive.
                </div>
                @endif
            </div>
        </div>

        {{-- Quick Info --}}
        <div class="dcard">
            <div class="dcard-head"><i class="fas fa-info-circle"></i><span class="dcard-title">Quick Info</span></div>
            <div class="dcard-body">
                @foreach([
                    ['Review ID', '#'.$review->id],
                    ['Flagged', (isset($review->is_flagged) && $review->is_flagged) ? 'Yes' : 'No'],
                    ['Owner Replied', isset($review->reply) && $review->reply ? 'Yes' : 'No'],
                    ['Posted', $review->created_at->diffForHumans()],
                ] as [$lbl,$val])
                <div style="display:flex;justify-content:space-between;align-items:center;padding:.55rem 0;border-bottom:1px solid #f5f2ee;">
                    <span style="font-size:.8rem;color:#9a9a9a;">{{ $lbl }}</span>
                    <span style="font-size:.82rem;font-weight:600;color:#2d2d2d;">{{ $val }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection