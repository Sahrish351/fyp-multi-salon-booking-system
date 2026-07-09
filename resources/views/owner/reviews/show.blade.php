@extends('layouts.owner')

@section('title', 'Review Details')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2>Review Details</h2>
        <p class="text-muted">View client feedback</p>
    </div>
    <a href="{{ route('owner.reviews.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Back to Reviews
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        {{-- Review Card --}}
        <div class="panel-card">
            
            {{-- Client Info --}}
            <div class="d-flex align-items-start gap-3 mb-3">
                <div class="review-avatar">
                    {{ substr($review->client->name ?? 'U', 0, 1) }}
                </div>
                <div>
                    <h5 class="mb-0">{{ $review->client->name ?? 'Unknown' }}</h5>
                    <p class="text-muted small">{{ $review->appointment->service->name ?? 'Service' }}</p>
                </div>
            </div>

            {{-- Rating --}}
            <div class="review-stars mb-2">
                @for ($i = 1; $i <= 5; $i++)
                    <i class="bi bi-star-fill {{ $i <= $review->rating ? 'star-filled' : 'star-empty' }}"></i>
                @endfor
                <span class="ms-2 text-muted small">({{ $review->created_at->format('d M Y') }})</span>
            </div>

            {{-- Comment --}}
            <p class="review-text">{{ $review->comment }}</p>

            {{-- Status --}}
            <div class="review-status-row">
                @if ($review->reply)
                    <span class="badge-status badge-confirmed"><i class="bi bi-reply-fill me-1"></i> 💬 Replied</span>
                @else
                    <span class="badge-status badge-pending"><i class="bi bi-star-fill me-1"></i> 🟢 New</span>
                @endif
            </div>

            <hr>

            {{-- Owner Reply (if exists) --}}
            @if ($review->reply)
                <div class="owner-reply-box">
                    <p class="owner-reply-label"><i class="bi bi-reply-fill me-1"></i> Your Reply</p>
                    <p class="owner-reply-text">{{ $review->reply->reply }}</p>
                    <small class="text-muted">{{ $review->reply->created_at->format('d M Y, h:i A') }}</small>
                </div>
            @endif

        </div>
    </div>

    <div class="col-lg-4">
        {{-- Actions Card --}}
        <div class="panel-card">
            <h6 class="fw-bold mb-3">Actions</h6>

            {{-- Reply Button (only if no reply exists) --}}
            @if (!$review->reply)
                <button type="button" class="btn btn-reply-review w-100 mb-2"
                        data-bs-toggle="modal" data-bs-target="#replyModal"
                        data-id="{{ $review->id }}" data-name="{{ $review->client->name ?? 'Client' }}">
                    <i class="bi bi-chat-left-text-fill me-2"></i> Reply to Review
                </button>
            @endif

            <a href="{{ route('owner.reviews.index') }}" class="btn btn-secondary w-100">
                <i class="bi bi-arrow-left me-2"></i> Back to Reviews
            </a>
        </div>

        {{-- Info Card --}}
        <div class="panel-card mt-3">
            <h6 class="fw-bold mb-3">Review Info</h6>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Review ID</span>
                <span>#{{ $review->id }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Client</span>
                <span>{{ $review->client->name ?? 'Unknown' }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Service</span>
                <span>{{ $review->appointment->service->name ?? 'N/A' }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Submitted</span>
                <span>{{ $review->created_at->format('d M Y, h:i A') }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">Status</span>
                @if ($review->reply)
                    <span class="badge-status badge-confirmed">💬 Replied</span>
                @else
                    <span class="badge-status badge-pending">🟢 New</span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ===================== MODALS ===================== --}}
<div class="modal fade" id="replyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom">
            <form action="{{ route('owner.reviews.reply', 0) }}" method="POST" id="replyForm">
                @csrf
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title">Reply to <span id="replyClientName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label-custom">Your Reply</label>
                    <textarea name="reply" class="form-control input-custom" rows="4"
                              placeholder="Thank you for your feedback. We hope to see you again." required></textarea>
                </div>
                <div class="modal-footer modal-footer-custom">
                    <button type="button" class="btn btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-save-changes">Post Reply</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

{{-- ===================== STYLES ===================== --}}
@section('extra-css')
<style>
    .panel-card {
        background: var(--white);
        border-radius: var(--radius-lg);
        border: 1px solid var(--blush-200);
        box-shadow: var(--shadow-card);
        padding: 1.5rem;
        transition: all 0.15s ease;
    }
    .panel-card:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    }

    .review-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 20px;
        flex-shrink: 0;
    }

    .review-stars {
        font-size: 18px;
    }
    .star-filled { color: var(--gold-500); }
    .star-empty { color: var(--blush-200); }

    .review-text {
        font-size: 15px;
        color: var(--ink-700);
        line-height: 1.7;
        margin-bottom: 14px;
    }

    .review-status-row {
        margin-bottom: 14px;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 14px;
        border-radius: 50px;
        font-size: 12.5px;
        font-weight: 600;
    }
    .badge-confirmed { background: #E8F5E9; color: #1B5E20; }
    .badge-pending { background: #FFF8E1; color: #E65100; }

    .owner-reply-box {
        background: var(--blush-50);
        border-left: 3px solid var(--gold-500);
        border-radius: var(--radius-sm);
        padding: 12px 16px;
    }
    .owner-reply-label { font-size: 12.5px; font-weight: 700; color: var(--plum-800); margin: 0 0 4px; }
    .owner-reply-text { font-size: 14px; color: var(--ink-700); margin: 0; }

    .btn-reply-review {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        color: var(--plum-900);
        font-weight: 700;
        padding: 10px 20px;
        border-radius: 10px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-reply-review:hover { color: var(--plum-900); opacity: 0.9; }

    .btn-secondary {
        background: var(--blush-100);
        color: var(--ink-700);
        border: 1px solid var(--blush-200);
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
    }
    .btn-secondary:hover { background: var(--blush-200); color: var(--ink-900); }

    .form-label-custom { display: block; font-size: 13.5px; font-weight: 600; color: var(--ink-700); margin-bottom: 6px; }
    .input-custom {
        background: var(--blush-50) !important;
        border: 1px solid var(--blush-200) !important;
        border-radius: var(--radius-sm) !important;
        color: var(--ink-900) !important;
        font-size: 14.5px;
        padding: 11px 14px !important;
        width: 100%;
    }
    .input-custom:focus {
        background: #fff !important;
        border-color: var(--rose-400) !important;
        box-shadow: 0 0 0 3px rgba(240, 143, 180, 0.2) !important;
        outline: none;
    }

    .modal-content-custom { border-radius: var(--radius-lg); border: none; overflow: hidden; }
    .modal-header-custom { background: var(--blush-50); border-bottom: 1px solid var(--blush-200); padding: 18px 24px; }
    .modal-header-custom .modal-title { font-weight: 700; color: var(--plum-800); }
    .modal-body { padding: 22px 24px; }
    .modal-footer-custom { border-top: 1px solid var(--blush-100); padding: 16px 24px; }

    .btn-cancel-modal {
        background: var(--white); border: 1px solid var(--blush-200); color: var(--ink-700);
        font-weight: 600; padding: 9px 20px; border-radius: 10px;
    }
    .btn-cancel-modal:hover { background: var(--blush-50); }

    .btn-save-changes {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        color: var(--plum-900); font-weight: 700; padding: 9px 22px; border-radius: 10px; border: none;
    }
    .btn-save-changes:hover { color: var(--plum-900); opacity: 0.9; }
</style>
@endsection

{{-- ===================== SCRIPTS ===================== --}}
@section('extra-js')
<script>
    // Reply Modal - Dynamic Form Action
    document.querySelectorAll('[data-bs-target="#replyModal"]').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('replyClientName').textContent = this.dataset.name;

            const form = document.getElementById('replyForm');
            const reviewId = this.dataset.id;
            form.action = form.action.replace(/\/reviews\/\d+/, '/reviews/' + reviewId);
        });
    });
</script>
@endsection