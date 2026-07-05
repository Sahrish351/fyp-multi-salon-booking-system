{{-- ============================================================ --}}
{{-- FILE: resources/views/client/reviews/create.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.client')
@section('title', 'Write Review — Glamora')
@section('content')

<div class="mb-4">
    <a href="{{ route('client.appointments.show',$appointment->id) }}" style="color:#aaa;text-decoration:none;font-size:0.85rem;">
        <i class="fas fa-arrow-left me-2"></i>Back to Appointment
    </a>
    <h4 class="fw-bold mt-2 mb-0" style="color:#333;font-family:'Playfair Display',serif;">
        <i class="fas fa-star me-2" style="color:#ffc107;"></i>Write a Review
    </h4>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="bg-white rounded-4 p-4 shadow-sm" style="border:1px solid #fce4ec;">

            {{-- Salon Info --}}
            <div class="d-flex align-items-center gap-3 p-3 rounded-3 mb-4" style="background:#fff5f9;border:1px solid #fce4ec;">
                <img src="{{ $appointment->salon->logo_url }}" class="rounded-2" width="52" height="52" style="object-fit:cover;" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($appointment->salon->name) }}&background=E91E8C&color=fff'">
                <div>
                    <h6 class="fw-bold mb-0" style="color:#333;">{{ $appointment->salon->name }}</h6>
                    <div style="color:#888;font-size:0.82rem;">
                        <i class="fas fa-spa me-1" style="color:#E91E8C;"></i>{{ $appointment->service->name }}
                        &nbsp;·&nbsp;
                        <i class="fas fa-calendar me-1" style="color:#C9A96E;"></i>{{ $appointment->appointment_date->format('d M Y') }}
                    </div>
                </div>
            </div>

            <form action="{{ route('client.reviews.store', $appointment->id) }}" method="POST">
                @csrf

                {{-- Star Rating --}}
                <div class="mb-4 text-center">
                    <label style="color:#555;font-size:0.85rem;font-weight:600;" class="mb-3 d-block">Rate Your Experience *</label>
                    <div class="d-flex justify-content-center gap-2" id="starRating">
                        @for($i=1;$i<=5;$i++)
                        <i class="fas fa-star star-btn" data-rating="{{ $i }}" style="font-size:2.5rem;color:#e5e7eb;cursor:pointer;transition:all .2s;" onmouseover="hoverStar({{ $i }})" onmouseout="resetStars()" onclick="selectStar({{ $i }})"></i>
                        @endfor
                    </div>
                    <div id="ratingLabel" style="color:#aaa;font-size:0.85rem;margin-top:8px;">Click to rate</div>
                    <input type="hidden" name="rating" id="ratingInput" required>
                </div>

                {{-- Comment --}}
                <div class="mb-4">
                    <label style="color:#555;font-size:0.85rem;font-weight:600;" class="mb-2">Your Review *</label>
                    <textarea name="comment" rows="5" class="form-control" required
                              placeholder="Share your experience — what did you love? What could be improved? Your honest feedback helps other clients make better decisions."
                              style="border:2px solid #fce4ec;border-radius:12px;padding:1rem;resize:none;" onfocus="this.style.borderColor='#ffc107'" onblur="this.style.borderColor='#fce4ec'">{{ old('comment') }}</textarea>
                    @error('comment')<div class="text-danger mt-1" style="font-size:0.78rem;">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn flex-grow-1 rounded-3 py-3 fw-semibold" style="background:linear-gradient(135deg,#ffc107,#f59e0b);color:#fff;border:none;font-size:1rem;">
                        <i class="fas fa-paper-plane me-2"></i>Submit Review
                    </button>
                    <a href="{{ route('client.appointments.show',$appointment->id) }}" class="btn btn-outline-secondary rounded-3 px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
const ratingLabels = ['','Terrible 😞','Poor 😐','Okay 🙂','Good 😊','Excellent 🌟'];
let selectedRating = 0;

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

