@extends('layouts.owner')
 
@section('title', 'Review Details')
 
@section('content')
 
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
 
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
 
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>Review by {{ $review['client_name'] }}</h2>
            <p>{{ $review['service'] }} &middot; {{ $review['date'] }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('owner.reviews.edit', ['review' => $review['id']]) }}" class="btn btn-edit-action">
                <i class="bi bi-pencil-square me-2"></i> Edit
            </a>
            <a href="{{ route('owner.reviews.index') }}" class="btn btn-back">
                <i class="bi bi-arrow-left me-2"></i> Back to Reviews
            </a>
        </div>
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
 
                <span class="badge-status {{ $review['is_approved'] ? 'badge-confirmed' : ($review['is_flagged'] ? 'badge-cancelled' : 'badge-pending') }}">
                    {{ $review['status'] }}
                </span>
 
                <hr class="my-4">
 
                <div class="d-flex flex-column gap-2">
                    @if (!$review['is_approved'] && !$review['is_flagged'])
                        <form action="{{ route('owner.reviews.approve', ['review' => $review['id']]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-review-approve w-100">
                                <i class="bi bi-check-circle-fill me-2"></i> Approve
                            </button>
                        </form>
                    @endif
 
                    <form action="{{ route('owner.reviews.flag', ['review' => $review['id']]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-review-flag w-100">
                            @if ($review['is_flagged'])
                                <i class="bi bi-flag me-2"></i> Unflag
                            @else
                                <i class="bi bi-flag-fill me-2"></i> Flag
                            @endif
                        </button>
                    </form>
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
    .page-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d1f2c;
        margin-bottom: 0.25rem;
    }
    .page-header p {
        color: #8a7a88;
        margin-bottom: 0;
    }
 
    .btn-back {
        background: #fff;
        border: 1px solid #f0e8ed;
        color: #2d1f2c;
        font-weight: 600;
        font-size: 14.5px;
        padding: 10px 20px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        transition: all 0.18s ease;
        text-decoration: none;
    }
    .btn-back:hover {
        background: #fcf6f9;
        border-color: #E85588;
        color: #E85588;
    }
 
    .btn-edit-action {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 14.5px;
        padding: 10px 22px;
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.35);
        transition: all 0.18s ease;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    .btn-edit-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(232, 85, 136, 0.45);
        color: #ffffff !important;
    }
 
    .panel-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f0e8ed;
        height: auto !important;
    }
    .panel-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2d1f2c;
        margin-bottom: 1rem;
    }
 
    .review-stars-lg { font-size: 26px; }
    .star-filled { color: #D9A441; }
    .star-empty { color: #f0e8ed; }
    .rating-number { font-size: 14px; color: #4a3a48; font-weight: 600; margin: 8px 0 14px; }
 
    .btn-review-approve {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        padding: 11px;
        border-radius: 10px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
    }
    .btn-review-approve:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.35);
        color: #ffffff !important;
    }
 
    .btn-review-flag {
        background: #fff;
        border: 1.5px solid #FF6B9D;
        color: #E85588;
        font-weight: 600;
        padding: 11px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
    }
    .btn-review-flag:hover {
        background: #E85588;
        color: #ffffff !important;
        border-color: #E85588;
    }
 
    .review-comment-lg {
        color: #4a3a48;
        font-size: 15px;
        line-height: 1.7;
        margin-bottom: 0;
    }
 
    .owner-reply-box {
        background: #fcf6f9;
        border-left: 3px solid #E85588;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 13.5px;
        color: #2d1f2c;
    }
 
    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px 24px;
    }
    .info-item { display: flex; flex-direction: column; gap: 4px; }
    .info-label { font-size: 12.5px; color: #8a7a88; }
    .info-value { font-size: 14.5px; font-weight: 600; color: #2d1f2c; }
 
    .input-custom {
        background: #fcf6f9 !important;
        border: 1px solid #f0e8ed !important;
        border-radius: 10px !important;
        color: #2d1f2c !important;
        font-size: 14.5px;
        padding: 11px 14px !important;
        width: 100%;
    }
    .input-custom:focus {
        background: #fff !important;
        border-color: #E85588 !important;
        box-shadow: 0 0 0 3px rgba(232, 85, 136, 0.15) !important;
        outline: none;
    }
 
    .btn-save-changes {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        padding: 10px 24px;
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.35);
        display: inline-flex;
        align-items: center;
        transition: all 0.15s ease;
    }
    .btn-save-changes:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(232, 85, 136, 0.45);
        color: #ffffff !important;
    }
 
    .badge-status {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .badge-confirmed { background: #E8F5ED; color: #1E8E64; }
    .badge-pending { background: #FDF6E8; color: #C4903A; }
    .badge-cancelled { background: #FCE4EC; color: #D45482; }
 
    .alert {
        border-radius: 12px;
        border: none;
        padding: 0.8rem 1.2rem;
    }
    .alert-success { background: #E8F5ED; color: #1B5E20; }
    .alert-danger { background: #FCE4EC; color: #880E4F; }
 
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        .page-header {
            flex-direction: column;
            align-items: stretch !important;
        }
        .btn-back {
            justify-content: center;
            width: 100%;
        }
        .btn-edit-action {
            justify-content: center;
        }
        .d-flex.gap-2 {
            flex-direction: column;
        }
        .d-flex.gap-2 .btn {
            width: 100%;
        }
    }
</style>
@endsection