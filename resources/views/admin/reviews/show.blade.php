@extends('layouts.admin')
@section('title', 'Review Details — Beauty Blush Salons Admin')

@push('styles')
<style>
    :root { 
        --pk: #FF6B9D; 
        --pk-lt: #fce4ec; 
        --pk-h: #E85588; 
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
        border: 1px solid #ebebeb;
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 1.2rem;
        box-shadow: 0 2px 6px rgba(0,0,0,.04);
    }
    .dcard-head {
        padding: 1rem 1.35rem;
        border-bottom: 1px solid #f3f3f3;
        display: flex;
        align-items: center;
        gap: .6rem;
    }
    .dcard-head i {
        color: var(--pk);
        font-size: .95rem;
    }
    .dcard-title {
        font-weight: 700;
        font-size: .95rem;
        color: #1a1a1a;
    }
    .dcard-body {
        padding: 1.35rem;
    }
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.2rem;
    }
    @media(max-width:600px){
        .info-grid {
            grid-template-columns: 1fr;
        }
    }
    .info-lbl {
        font-size: .68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #9a9a9a;
        margin-bottom: .3rem;
        display: block;
    }
    .info-val {
        font-size: .9rem;
        color: #1a1a1a;
        font-weight: 600;
    }
    .ab {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .35rem;
        padding: .55rem 1rem;
        border-radius: 9px;
        font-size: .82rem;
        font-weight: 700;
        text-decoration: none;
        border: 1.5px solid;
        transition: all .15s;
        cursor: pointer;
        background: none;
        font-family: inherit;
        width: 100%;
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
        grid-template-columns: 1fr 320px;
        gap: 1.4rem;
        align-items: start;
    }
    @media(max-width:900px){
        .review-layout {
            grid-template-columns: 1fr;
        }
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

    {{-- LEFT COLUMN --}}
    <div>
        {{-- Review Main Card --}}
        <div class="dcard">
            <div class="dcard-head">
                <i class="fas fa-star" style="color:var(--pk);"></i>
                <span class="dcard-title">Review & Client Information</span>
            </div>
            <div class="dcard-body">
                <div class="info-grid">
                    <div>
                        <span class="info-lbl">Client Details</span>
                        <div class="info-val">{{ $review->client->name ?? 'N/A' }}</div>
                        <div style="font-size:.78rem;color:#9a9a9a;">{{ $review->client->email ?? '' }}</div>
                    </div>
                    <div>
                        <span class="info-lbl">Salon Name</span>
                        <div class="info-val">{{ $review->salon->name ?? 'N/A' }}</div>
                        <div style="font-size:.78rem;color:#9a9a9a;">{{ $review->salon->city ?? '' }}</div>
                    </div>
                    <div>
                        <span class="info-lbl">Booked Service</span>
                        <div class="info-val">{{ $review->appointment->service->name ?? '—' }}</div>
                    </div>
                    <div>
                        <span class="info-lbl">Submission Date</span>
                        <div class="info-val">{{ $review->created_at->format('d M Y, h:i A') }}</div>
                    </div>
                </div>

                {{-- Rating Stars Display --}}
                <div style="margin-top:1.4rem;padding-top:1.2rem;border-top:1px solid #f3f3f3;">
                    <span class="info-lbl">Rating Score</span>
                    <div style="display:flex;align-items:center;gap:.4rem;margin-top:.35rem;">
                        @for($i=1;$i<=5;$i++)
                        <i class="fas fa-star" style="font-size:1.35rem;color:{{ $i<=$review->rating ? '#ffc107':'#e5e7eb' }};"></i>
                        @endfor
                        <span style="font-weight:800;font-size:1.1rem;color:#1a1a1a;margin-left:.3rem;">{{ $review->rating }} / 5</span>
                    </div>
                </div>

                {{-- Comment Box --}}
                <div style="margin-top:1.2rem;padding:1.1rem;background:#faf8f6;border-radius:10px;border-left:3px solid var(--pk);">
                    <span class="info-lbl" style="margin-bottom:.35rem;">Client Comment</span>
                    <p style="margin:0;font-size:.9rem;color:#333;line-height:1.7;">{{ $review->comment }}</p>
                </div>
            </div>
        </div>

        {{-- Owner Reply Card (if available) --}}
        @if(isset($review->reply) && $review->reply)
        <div class="dcard">
            <div class="dcard-head">
                <i class="fas fa-reply"></i>
                <span class="dcard-title">Salon Owner's Response</span>
            </div>
            <div class="dcard-body">
                <div style="padding:1.1rem;background:#eaf3eb;border-radius:10px;border-left:3px solid #5a8a62;">
                    <p style="margin:0;font-size:.9rem;color:#2d5a35;line-height:1.7;">{{ $review->reply->reply ?? 'No reply' }}</p>
                    <div style="font-size:.75rem;color:#5a8a62;margin-top:.5rem;opacity:.8;">{{ $review->reply->created_at->diffForHumans() ?? '' }}</div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- RIGHT COLUMN --}}
    <div>
        {{-- Status & Quick Action Card --}}
        <div class="dcard">
            <div class="dcard-head">
                <i class="fas fa-sliders-h"></i>
                <span class="dcard-title">Moderation Actions</span>
            </div>
            <div class="dcard-body">

                {{-- Status Banner inside Show Page --}}
                <div style="margin-bottom:1.2rem;text-align:center;padding:1.1rem;background:#faf8f6;border-radius:10px;border:1px solid #f0f0f0;">
                    @if(isset($review->is_flagged) && $review->is_flagged)
                    <i class="fas fa-flag" style="font-size:2rem;color:#c47f00;display:block;margin-bottom:.4rem;"></i>
                    <div style="font-weight:700;color:#c47f00;font-size:.95rem;">Reported by Owner</div>
                    <div style="font-size:.75rem;color:#9a9a9a;margin-top:.2rem;">Requires moderation</div>
                    @elseif(isset($review->is_approved) && $review->is_approved)
                    <i class="fas fa-check-circle" style="font-size:2rem;color:#5a8a62;display:block;margin-bottom:.4rem;"></i>
                    <div style="font-weight:700;color:#5a8a62;font-size:.95rem;">Published Status</div>
                    <div style="font-size:.75rem;color:#9a9a9a;margin-top:.2rem;">Visible to public users</div>
                    @else
                    <i class="fas fa-eye-slash" style="font-size:2rem;color:#c0392b;display:block;margin-bottom:.4rem;"></i>
                    <div style="font-weight:700;color:#c0392b;font-size:.95rem;">Hidden Status</div>
                    <div style="font-size:.75rem;color:#9a9a9a;margin-top:.2rem;">Hidden from public</div>
                    @endif
                </div>

                {{-- Stacked Actions --}}
                <div style="display:flex;flex-direction:column;gap:.65rem;">
                    @if(isset($review->is_approved) && $review->is_approved && !($review->is_flagged ?? false))
                    <form action="{{ route('admin.reviews.hide', $review->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="ab ab-hide" onclick="return confirm('Hide this review?')">
                            <i class="fas fa-eye-slash"></i> Hide Review
                        </button>
                    </form>
                    @else
                    <form action="{{ route('admin.reviews.publish', $review->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="ab ab-show">
                            <i class="fas fa-check"></i> Publish Review
                        </button>
                    </form>
                    @endif

                    <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="ab ab-del" onclick="return confirm('Permanently delete? This action cannot be undone.')">
                            <i class="fas fa-trash"></i> Delete Review
                        </button>
                    </form>
                </div>

                @if(isset($review->is_flagged) && $review->is_flagged)
                <div style="margin-top:1rem;padding:.8rem;background:#fff8e1;border:1px solid #ffe082;border-radius:9px;font-size:.78rem;color:#856100;line-height:1.5;">
                    <i class="fas fa-exclamation-triangle" style="color:#f59e0b;"></i>
                    This review has been flagged by the salon owner for inspection.
                </div>
                @endif
            </div>
        </div>

        {{-- Meta Info Card --}}
        <div class="dcard">
            <div class="dcard-head">
                <i class="fas fa-info-circle"></i>
                <span class="dcard-title">Quick Meta</span>
            </div>
            <div class="dcard-body" style="padding:1rem 1.35rem;">
                @foreach([
                    ['Review ID', '#'.$review->id],
                    ['Flagged Status', (isset($review->is_flagged) && $review->is_flagged) ? 'Yes' : 'No'],
                    ['Owner Response', isset($review->reply) && $review->reply ? 'Available' : 'None'],
                    ['Time Elapsed', $review->created_at->diffForHumans()],
                ] as [$lbl,$val])
                <div style="display:flex;justify-content:space-between;align-items:center;padding:.6rem 0;border-bottom:1px solid #f3f3f3;">
                    <span style="font-size:.8rem;color:#9a9a9a;">{{ $lbl }}</span>
                    <span style="font-size:.82rem;font-weight:600;color:#1a1a1a;">{{ $val }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection