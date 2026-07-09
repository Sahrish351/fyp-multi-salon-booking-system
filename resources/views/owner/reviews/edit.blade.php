@extends('layouts.owner')
 
@section('title', 'Edit Review')
 
@section('content')
 
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
 
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>Edit Review</h2>
            <p>Update review from {{ $review['client_name'] }}</p>
        </div>
        <a href="{{ route('owner.reviews.index') }}" class="btn btn-back">
            <i class="bi bi-arrow-left me-2"></i> Back to Reviews
        </a>
    </div>
 
    <form action="{{ route('owner.reviews.update', ['review' => $review['id']]) }}" method="POST">
        @csrf
        @method('PUT')
 
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="panel-card">
                    <div class="panel-title">Review Details</div>
 
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label-custom">Client</label>
                            <input type="text" class="form-control input-custom" value="{{ $review['client_name'] }}" disabled>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Service</label>
                            <input type="text" class="form-control input-custom" value="{{ $review['service'] }}" disabled>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Rating <span class="text-danger">*</span></label>
                            <div class="rating-input">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="rating" id="rating-{{ $i }}" value="{{ $i }}" {{ old('rating', $review['rating']) == $i ? 'checked' : '' }} required>
                                    <label for="rating-{{ $i }}" class="star-label"><i class="bi bi-star-fill"></i></label>
                                @endfor
                            </div>
                            @error('rating')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Comment <span class="text-danger">*</span></label>
                            <textarea name="comment" class="form-control input-custom @error('comment') is-invalid @enderror" rows="4">{{ old('comment', $review['comment']) }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Status</label>
                            <select name="status" class="form-select input-custom @error('status') is-invalid @enderror">
                                <option value="pending" {{ old('status', $review['is_pending'] ? 'pending' : '') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ old('status', $review['is_approved'] ? 'approved' : '') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="flagged" {{ old('status', $review['is_flagged'] ? 'flagged' : '') == 'flagged' ? 'selected' : '' }}>Flagged</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
 
            <div class="col-lg-6">
                <div class="panel-card">
                    <div class="panel-title">Owner Reply</div>
 
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label-custom">Reply <span class="text-muted">(optional)</span></label>
                            <textarea name="reply" class="form-control input-custom @error('reply') is-invalid @enderror" rows="6"
                                      placeholder="Reply to this review...">{{ old('reply', $review['owner_reply'] ?? '') }}</textarea>
                            @error('reply')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
 
            <div class="col-12">
                <div class="panel-card">
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-save-changes">
                            <i class="bi bi-check-circle-fill me-2"></i> Save Changes
                        </button>
                        <a href="{{ route('owner.reviews.index') }}" class="btn btn-cancel-modal">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
 
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
 
    .panel-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f0e8ed;
        height: 100% !important;
        display: flex;
        flex-direction: column;
    }
    .panel-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2d1f2c;
        margin-bottom: 1rem;
        flex-shrink: 0;
    }
 
    .form-label-custom {
        display: block;
        font-size: 13.5px;
        font-weight: 600;
        color: #4a3a48;
        margin-bottom: 6px;
    }
    .form-label-custom .text-danger { color: #E85588; }
    .form-label-custom .text-muted { font-weight: 400; font-size: 12.5px; color: #8a7a88; }
 
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
    .is-invalid { border-color: #E85588 !important; }
    .invalid-feedback {
        color: #E85588;
        font-size: 12px;
        margin-top: 4px;
    }
 
    .rating-input {
        display: flex;
        flex-direction: row-reverse;
        gap: 8px;
        justify-content: flex-end;
    }
    .rating-input input[type="radio"] {
        display: none;
    }
    .rating-input .star-label {
        font-size: 28px;
        cursor: pointer;
        color: #f0e8ed;
        transition: color 0.2s;
    }
    .rating-input .star-label:hover,
    .rating-input .star-label:hover ~ .star-label,
    .rating-input input[type="radio"]:checked ~ .star-label {
        color: #D9A441;
    }
 
    .btn-save-changes {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        padding: 11px 26px;
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.35);
        display: inline-flex;
        align-items: center;
        transition: all 0.18s ease;
        text-decoration: none;
    }
    .btn-save-changes:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(232, 85, 136, 0.45);
        color: #ffffff !important;
    }
 
    .btn-cancel-modal {
        background: #fff;
        border: 1.5px solid #FF6B9D;
        color: #E85588;
        font-weight: 600;
        padding: 11px 26px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        transition: all 0.18s ease;
        text-decoration: none;
    }
    .btn-cancel-modal:hover {
        background: #E85588;
        color: #ffffff !important;
        border-color: #E85588;
    }
 
    .alert {
        border-radius: 12px;
        border: none;
        padding: 0.8rem 1.2rem;
    }
    .alert-danger {
        background: #FCE4EC;
        color: #880E4F;
    }
    .alert ul {
        padding-left: 1.2rem;
        margin-bottom: 0;
    }
 
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch !important;
        }
        .btn-back {
            justify-content: center;
            width: 100%;
        }
        .d-flex.gap-3 {
            flex-wrap: wrap;
        }
        .btn-save-changes,
        .btn-cancel-modal {
            flex: 1;
            justify-content: center;
        }
        .panel-card {
            height: auto !important;
        }
        .rating-input .star-label {
            font-size: 22px;
        }
    }
</style>
@endsection