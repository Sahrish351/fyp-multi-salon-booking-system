{{-- FILE: resources/views/client/reviews/show.blade.php --}}
@extends('layouts.client')
@section('title', 'Review Details — Glamora')
@section('content')

<style>
:root { --pink:#E91E8C; --pink-lt:#fce4ec; --pink-bg:#fff5f9; }
.action-btn { display:inline-flex;align-items:center;gap:.35rem;padding:.5rem 1.1rem;border-radius:9px;font-size:.82rem;font-weight:700;text-decoration:none;border:1.5px solid;transition:all .15s;cursor:pointer;background:none;font-family:inherit; }
.btn-edit   { background:#f0f4ff;color:#3b5bdb;border-color:#d0daff; }
.btn-edit:hover { background:#3b5bdb;color:#fff;border-color:#3b5bdb; }
.btn-delete { background:#fff0f0;color:#dc2626;border-color:#fecaca; }
.btn-delete:hover { background:#dc2626;color:#fff;border-color:#dc2626; }
.btn-back   { display:inline-flex;align-items:center;gap:.5rem;padding:.45rem 1rem;border:1.5px solid #e5e5e5;border-radius:9px;font-size:.85rem;font-weight:600;color:#888;text-decoration:none;background:#fff;transition:all .15s; }
.btn-back:hover { border-color:var(--pink);color:var(--pink); }
</style>

<a href="{{ route('client.reviews.index') }}" class="btn-back mb-4 d-inline-flex">
    <i class="fas fa-arrow-left"></i> Back to My Reviews
</a>

<div class="row justify-content-center">
    <div class="col-lg-7">

        {{-- alerts --}}
        @if(session('success'))
        <div class="alert rounded-3 mb-3" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;font-size:.88rem;">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="alert rounded-3 mb-3" style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;font-size:.88rem;">
            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
        </div>
        @endif

        <div class="bg-white rounded-4 p-4" style="border:1px solid var(--pink-lt);">

            {{-- Salon info --}}
            <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-3" style="background:var(--pink-bg);border:1px solid var(--pink-lt);">
                <img src="{{ $review->salon->logo_url ?? '' }}"
                     class="rounded-2 flex-shrink-0" width="56" height="56" style="object-fit:cover;"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($review->salon->name ?? 'S') }}&background=E91E8C&color=fff'">
                <div>
                    <h5 class="fw-bold mb-0" style="color:#1a1a1a;">{{ $review->salon->name }}</h5>
                    @if($review->appointment)
                    <div style="color:#888;font-size:.82rem;">
                        <i class="fas fa-spa me-1" style="color:var(--pink);font-size:.7rem;"></i>{{ $review->appointment->service->name ?? '' }}
                        &nbsp;·&nbsp;
                        <i class="fas fa-calendar me-1" style="color:#6c757d;font-size:.7rem;"></i>{{ $review->appointment->appointment_date->format('d M Y') }}
                    </div>
                    @endif
                </div>
            </div>

            {{-- Stars --}}
            <div class="d-flex align-items-center gap-2 mb-3">
                @for($i=1;$i<=5;$i++)
                <i class="fas fa-star" style="font-size:1.4rem;color:{{ $i<=$review->rating ? '#ffc107' : '#e5e7eb' }};"></i>
                @endfor
                <span class="fw-bold ms-1" style="color:#1a1a1a;font-size:1.05rem;">{{ $review->rating }}/5</span>
            </div>

            {{-- Comment --}}
            <p style="color:#555;font-size:.9rem;line-height:1.8;margin-bottom:1rem;">{{ $review->comment }}</p>
            <div style="color:#aaa;font-size:.75rem;margin-bottom:1.5rem;">
                <i class="fas fa-clock me-1"></i>Posted {{ $review->created_at->format('d M Y, h:i A') }}
            </div>

            {{-- Owner reply --}}
            @if($review->reply)
            <div class="p-3 rounded-3 mb-4" style="background:rgba(233,30,140,.04);border-left:3px solid var(--pink);">
                <div style="color:var(--pink);font-size:.73rem;font-weight:700;margin-bottom:.5rem;">
                    <i class="fas fa-reply me-1"></i>SALON OWNER REPLIED
                </div>
                <p style="color:#555;font-size:.86rem;line-height:1.7;margin:0;">{{ $review->reply->reply }}</p>
                <div style="color:#aaa;font-size:.72rem;margin-top:.4rem;">{{ $review->reply->created_at->diffForHumans() }}</div>
            </div>
            @endif

            {{-- Actions: edit/delete only if no reply --}}
            <div class="d-flex gap-2 flex-wrap">
                @if(!$review->reply)
                <a href="{{ route('client.reviews.edit', $review->id) }}" class="action-btn btn-edit">
                    <i class="fas fa-pen"></i> Edit Review
                </a>
                <form action="{{ route('client.reviews.destroy', $review->id) }}" method="POST"
                      onsubmit="return confirm('Delete this review? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="action-btn btn-delete">
                        <i class="fas fa-trash"></i> Delete Review
                    </button>
                </form>
                @else
                <div class="p-2 rounded-3" style="background:#fff8e1;border:1px solid #ffe082;font-size:.78rem;color:#795548;">
                    <i class="fas fa-info-circle me-1" style="color:#f59e0b;"></i>
                    Edit/Delete is disabled because the salon owner has replied to this review.
                </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection