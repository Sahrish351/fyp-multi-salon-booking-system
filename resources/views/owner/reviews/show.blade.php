
@extends('layouts.owner')
 
@section('title', 'Review Details')
 
@section('content')
 
    
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>Review by {{ $review['client_name'] }}</h2>
            <p>{{ $review['service'] }} &middot; {{ $review['date'] }}</p>
        </div>
        <a href="{{ route('owner.reviews.index') }}" class="btn btn-back">
            <i class="bi bi-arrow-left me-2"></i> Back to Reviews
        </a>
    </div>
 
    <div class="row g-4">
 
        <div class="col-lg-4">
            <div class="panel-card text-center">
                <div class="review-stars-lg">
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star-fill {{ $i <= $review['rating'] ? 'star-filled' : 'star-empty' }}"></i>
                    @endfor
                </div>
                <p class="rating-number">{{ $review['rating'] }}.0 out of 5</p>
 
                <span class="badge-status {{ $review['status'] === 'Approved' ? 'badge-confirmed' : ($review['status'] === 'Flagged' ? 'badge-cancelled' : 'badge-pending') }}">
                    {{ $review['status'] }}
                </span>
 
                <hr class="my-4">
 
                <div class="d-flex flex-column gap-2">
                    @if ($review['status'] !== 'Approved')
                        <form action="{{ route('owner.reviews.approve', ['review' => $review['id']]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-review-approve w-100">
                                <i class="bi bi-check-circle-fill me-2"></i> Approve
                            </button>
                        </form>
                    @endif
 
                    @if ($review['status'] !== 'Flagged')
                        <form action="{{ route('owner.reviews.flag', ['review' => $review['id']]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-review-flag w-100">
                                <i class="bi bi-flag-fill me-2"></i> Flag / Reject
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
 
        <div class="col-lg-8">
 
            <div class="panel-card mb-4">
                <div class="panel-title">Client Feedback</div>
                <p class="review-comment-lg">{{ $review['comment'] }}</p>
            </div>
 
            <div class="panel-card mb-4">
                <div class="panel-title">Appointment Information</div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Service</span>
                        <span class="info-value">{{ $review['service'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Stylist</span>
                        <span class="info-value">{{ $review['stylist'] ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date</span>
                        <span class="info-value">{{ $review['date'] }}</span>
                    </div>
                </div>
            </div>
 
            <div class="panel-card">
                <div class="panel-title">Your Reply</div>
 
                @if (!empty($review['owner_reply']))
                    <div class="owner-reply-box mb-3">
                        <i class="bi bi-reply-fill me-2"></i>
                        {{ $review['owner_reply'] }}
                    </div>
                @endif
 
                <form action="{{ route('owner.reviews.reply', ['review' => $review['id']]) }}" method="POST">
                    @csrf
                    <textarea name="reply" class="form-control input-custom" rows="3"
                              placeholder="Write a reply to this review...">{{ $review['owner_reply'] ?? '' }}</textarea>
                    <button type="submit" class="btn btn-save-changes mt-3">
                        <i class="bi bi-send-fill me-2"></i> {{ !empty($review['owner_reply']) ? 'Update Reply' : 'Post Reply' }}
                    </button>
                </form>
            </div>
 
        </div>
 
    </div>
 
@endsection
 
@section('extra-css')
<style>
    .btn-back {
        background: var(--white); border: 1px solid var(--blush-200); color: var(--plum-800);
        font-weight: 600; font-size: 14.5px; padding: 10px 20px; border-radius: 10px;
        display: inline-flex; align-items: center; transition: all 0.18s ease;
    }
    .btn-back:hover { background: var(--blush-50); color: var(--plum-900); }
 
    .review-stars-lg { font-size: 26px; }
    .star-filled { color: var(--gold-500); }
    .star-empty { color: var(--blush-200); }
    .rating-number { font-size: 14px; color: var(--ink-700); font-weight: 600; margin: 8px 0 14px; }
 
    .btn-review-approve {
        background: linear-gradient(135deg, #38C495, var(--green-500)); color: #fff;
        font-weight: 700; padding: 11px; border-radius: 10px; border: none;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .btn-review-approve:hover { color: #fff; box-shadow: 0 4px 14px rgba(46, 174, 125, 0.35); }
 
    .btn-review-flag {
        background: var(--red-50); color: var(--red-500);
        font-weight: 700; padding: 11px; border-radius: 10px; border: 1px solid #FBD0D9;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .btn-review-flag:hover { background: var(--red-500); color: #fff; }
 
    .review-comment-lg {
        color: var(--ink-700);
        font-size: 15px;
        line-height: 1.7;
        margin-bottom: 0;
    }
 
    .owner-reply-box {
        background: var(--blush-50);
        border-left: 3px solid var(--rose-400);
        border-radius: var(--radius-sm);
        padding: 12px 16px;
        font-size: 13.5px;
        color: var(--plum-800);
    }
 
    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px 24px;
    }
    .info-item { display: flex; flex-direction: column; gap: 4px; }
    .info-label { font-size: 12.5px; color: var(--ink-500); }
    .info-value { font-size: 14.5px; font-weight: 600; color: var(--plum-900); }
 
    .form-label-custom { display: block; font-size: 13.5px; font-weight: 600; color: var(--ink-700); margin-bottom: 6px; }
    .input-custom {
        background: var(--blush-50) !important; border: 1px solid var(--blush-200) !important;
        border-radius: var(--radius-sm) !important; color: var(--ink-900) !important;
        font-size: 14.5px; padding: 11px 14px !important;
    }
    .input-custom:focus { background: #fff !important; border-color: var(--rose-400) !important; box-shadow: 0 0 0 3px rgba(240, 143, 180, 0.2) !important; outline: none; }
 
    .btn-save-changes {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        color: var(--plum-900); font-weight: 700; padding: 10px 24px; border-radius: 10px; border: none;
        display: inline-flex; align-items: center;
    }
    .btn-save-changes:hover { color: var(--plum-900); }
</style>
@endsection