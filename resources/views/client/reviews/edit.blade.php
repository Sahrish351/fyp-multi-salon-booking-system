{{-- FILE: resources/views/client/reviews/edit.blade.php --}}
@extends('layouts.client')
@section('title', 'Edit Review — Glamora')
@section('content')

<style>
:root { --pink:#E91E8C; --pink-lt:#fce4ec; --pink-bg:#fff5f9; }
.btn-back { display:inline-flex;align-items:center;gap:.5rem;padding:.45rem 1rem;border:1.5px solid #e5e5e5;border-radius:9px;font-size:.85rem;font-weight:600;color:#888;text-decoration:none;background:#fff;transition:all .15s; }
.btn-back:hover { border-color:var(--pink);color:var(--pink); }
</style>

<a href="{{ route('client.reviews.show', $review->id) }}" class="btn-back mb-4 d-inline-flex">
    <i class="fas fa-arrow-left"></i> Back to Review
</a>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="bg-white rounded-4 p-4 shadow-sm" style="border:1px solid var(--pink-lt);">

            <h5 class="fw-bold mb-4" style="color:#333;font-family:'Playfair Display',serif;">
                <i class="fas fa-pen me-2" style="color:var(--pink);"></i>Edit Your Review
            </h5>

            {{-- Salon Info --}}
            <div class="d-flex align-items-center gap-3 p-3 rounded-3 mb-4" style="background:var(--pink-bg);border:1px solid var(--pink-lt);">
                <img src="{{ $review->salon->logo_url ?? '' }}"
                     class="rounded-2" width="48" height="48" style="object-fit:cover;flex-shrink:0;"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($review->salon->name ?? 'S') }}&background=E91E8C&color=fff'">
                <div>
                    <h6 class="fw-bold mb-0" style="color:#1a1a1a;">{{ $review->salon->name }}</h6>
                    <div style="color:#888;font-size:.8rem;">
                        <i class="fas fa-spa me-1" style="color:var(--pink);font-size:.7rem;"></i>{{ $review->appointment->service->name ?? '' }}
                        &nbsp;·&nbsp;
                        <i class="fas fa-calendar me-1" style="font-size:.7rem;"></i>{{ $review->appointment->appointment_date->format('d M Y') }}
                    </div>
                </div>
            </div>

            @if($errors->any())
            <div class="alert rounded-3 mb-3" style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;font-size:.83rem;">
                <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <form action="{{ route('client.reviews.update', $review->id) }}" method="POST">
                @csrf @method('PUT')

                {{-- Star Rating --}}
                <div class="mb-4 text-center">
                    <label style="color:#555;font-size:.85rem;font-weight:600;" class="mb-3 d-block">Your Rating *</label>
                    <div class="d-flex justify-content-center gap-2" id="starRating">
                        @for($i=1;$i<=5;$i++)
                        <i class="fas fa-star star-btn" data-rating="{{ $i }}"
                           style="font-size:2.5rem;color:{{ $i<=$review->rating ? '#ffc107' : '#e5e7eb' }};cursor:pointer;transition:all .2s;"
                           onmouseover="hoverStar({{ $i }})" onmouseout="resetStars()" onclick="selectStar({{ $i }})"></i>
                        @endfor
                    </div>
                    <div id="ratingLabel" style="color:#ffc107;font-size:.88rem;font-weight:600;margin-top:8px;">{{ ['','Terrible 😞','Poor 😐','Okay 🙂','Good 😊','Excellent 🌟'][$review->rating] }}</div>
                    <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating', $review->rating) }}" required>
                </div>

                {{-- Comment --}}
                <div class="mb-4">
                    <label style="color:#555;font-size:.85rem;font-weight:600;" class="mb-2 d-block">Your Review *</label>
                    <textarea name="comment" rows="5" class="form-control" required
                              placeholder="Share your experience..."
                              style="border:2px solid var(--pink-lt);border-radius:12px;padding:1rem;resize:none;"
                              onfocus="this.style.borderColor='#ffc107'" onblur="this.style.borderColor='var(--pink-lt)'">{{ old('comment', $review->comment) }}</textarea>
                    @error('comment')<div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn flex-grow-1 rounded-3 py-2 fw-semibold"
                            style="background:linear-gradient(135deg,#ffc107,#f59e0b);color:#fff;border:none;font-size:.95rem;">
                        <i class="fas fa-save me-2"></i>Update Review
                    </button>
                    <a href="{{ route('client.reviews.show', $review->id) }}"
                       class="btn btn-outline-secondary rounded-3 px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
const ratingLabels = ['','Terrible 😞','Poor 😐','Okay 🙂','Good 😊','Excellent 🌟'];
let selectedRating = {{ $review->rating }};

function hoverStar(n) {
    document.querySelectorAll('.star-btn').forEach((s,i) => {
        s.style.color = i < n ? '#ffc107' : '#e5e7eb';
        s.style.transform = i < n ? 'scale(1.1)' : 'scale(1)';
    });
    document.getElementById('ratingLabel').textContent = ratingLabels[n];
}
function resetStars() {
    document.querySelectorAll('.star-btn').forEach((s,i) => {
        s.style.color = i < selectedRating ? '#ffc107' : '#e5e7eb';
        s.style.transform = 'scale(1)';
    });
    document.getElementById('ratingLabel').textContent = selectedRating ? ratingLabels[selectedRating] : 'Click to rate';
}
function selectStar(n) {
    selectedRating = n;
    document.getElementById('ratingInput').value = n;
    resetStars();
    document.getElementById('ratingLabel').style.color = '#ffc107';
    document.getElementById('ratingLabel').style.fontWeight = '600';
}
</script>
@endpush
@endsection