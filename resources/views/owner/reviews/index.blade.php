
@extends('layouts.owner')
 
@section('title', 'Reviews')
 
@section('content')
 
   
    <div class="page-header">
        <h2>Reviews</h2>
        <p>Client feedback and ratings</p>
    </div>
 
    <div class="row g-4 mb-4">
 
        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-gold"><i class="bi bi-star-fill"></i></div>
                <div>
                    <div class="stat-label-sm">Avg. Rating</div>
                    <div class="stat-value-sm">{{ $stats['avg_rating'] ?? 4.9 }}</div>
                </div>
            </div>
        </div>
 
        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-green"><i class="bi bi-chat-square-text-fill"></i></div>
                <div>
                    <div class="stat-label-sm">Total Reviews</div>
                    <div class="stat-value-sm">{{ number_format($stats['total'] ?? 1248) }}</div>
                </div>
            </div>
        </div>
 
        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-blue"><i class="bi bi-hand-thumbs-up-fill"></i></div>
                <div>
                    <div class="stat-label-sm">5-Star Reviews</div>
                    <div class="stat-value-sm">{{ number_format($stats['five_star'] ?? 1089) }}</div>
                </div>
            </div>
        </div>
 
        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-purple"><i class="bi bi-star-fill"></i></div>
                <div>
                    <div class="stat-label-sm">This Month</div>
                    <div class="stat-value-sm">{{ $stats['this_month'] ?? 87 }}</div>
                </div>
            </div>
        </div>
 
    </div>
 
   
    <div class="filter-tabs-row mb-4">
        <a href="{{ route('owner.reviews.index') }}" class="filter-tab {{ !request('status') ? 'active' : '' }}">All Reviews</a>
        <a href="{{ route('owner.reviews.index', ['status' => 'pending']) }}" class="filter-tab {{ request('status') === 'pending' ? 'active' : '' }}">Pending Approval</a>
        <a href="{{ route('owner.reviews.index', ['status' => 'approved']) }}" class="filter-tab {{ request('status') === 'approved' ? 'active' : '' }}">Approved</a>
        <a href="{{ route('owner.reviews.index', ['status' => 'flagged']) }}" class="filter-tab {{ request('status') === 'flagged' ? 'active' : '' }}">Flagged</a>
    </div>
 
  
    @forelse ($reviews as $review)
        <div class="panel-card review-card mb-4 {{ $review['flagged'] ? 'review-flagged' : '' }}">
 
            <div class="review-top-row">
                <div>
                    <h5 class="review-client-name">{{ $review['client_name'] }}</h5>
                    <p class="review-meta">{{ $review['service'] }} &middot; {{ $review['date'] }}</p>
                </div>
                <div class="review-stars">
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star-fill {{ $i <= $review['rating'] ? 'star-filled' : 'star-empty' }}"></i>
                    @endfor
                </div>
            </div>
 
            <p class="review-text">{{ $review['comment'] }}</p>
 
            <div class="review-status-row">
                @if ($review['flagged'])
                    <span class="badge-status badge-cancelled"><i class="bi bi-flag-fill me-1"></i> Flagged</span>
                @elseif ($review['approved'])
                    <span class="badge-status badge-confirmed"><i class="bi bi-check-circle-fill me-1"></i> Approved</span>
                @else
                    <span class="badge-status badge-pending"><i class="bi bi-hourglass-split me-1"></i> Pending Approval</span>
                @endif
            </div>
 
            @if (!empty($review['owner_reply']))
                <div class="owner-reply-box">
                    <p class="owner-reply-label"><i class="bi bi-reply-fill me-1"></i> Your Reply</p>
                    <p class="owner-reply-text">{{ $review['owner_reply'] }}</p>
                </div>
            @endif
 
            <div class="review-actions-row">
 
                @if (!$review['approved'] && !$review['flagged'])
                    <form action="{{ route('owner.reviews.approve', ['review' => $review['id']]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-approve-review">
                            <i class="bi bi-check-circle-fill me-2"></i> Approve
                        </button>
                    </form>
                @endif
 
                <form action="{{ route('owner.reviews.flag', ['review' => $review['id']]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-flag-review">
                        @if ($review['flagged'])
                            <i class="bi bi-flag me-2"></i> Unflag
                        @else
                            <i class="bi bi-flag-fill me-2"></i> Flag / Cancel
                        @endif
                    </button>
                </form>
 
                @if (empty($review['owner_reply']))
                    <button type="button" class="btn btn-reply-review"
                            data-bs-toggle="modal" data-bs-target="#replyModal"
                            data-id="{{ $review['id'] }}" data-name="{{ $review['client_name'] }}">
                        <i class="bi bi-chat-left-text-fill me-2"></i> Reply
                    </button>
                @endif
 
            </div>
 
        </div>
    @empty
        <div class="panel-card text-center py-5">
            <i class="bi bi-star" style="font-size:36px; color:#F08FB4;"></i>
            <p class="mt-2 mb-0" style="color:#6B4F62;">No reviews found.</p>
        </div>
    @endforelse
 
@endsection
 

@push('modals')
 
    
    <div class="modal fade" id="replyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <form action="{{ route('owner.reviews.reply', ['review' => 0]) }}" method="POST" id="replyForm">
                    @csrf
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title">Reply to <span id="replyClientName"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label-custom">Your Reply</label>
                        <textarea name="reply" class="form-control input-custom" rows="4"
                                  placeholder="Thank the client and address their feedback..." required></textarea>
                    </div>
                    <div class="modal-footer modal-footer-custom">
                        <button type="button" class="btn btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-save-changes">Post Reply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
 
@endpush
 
@section('extra-css')
<style>
    .stat-card-sm {
        background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--blush-200);
        box-shadow: var(--shadow-card); padding: 18px 20px; display: flex; align-items: center; gap: 16px; height: 100%;
    }
    .stat-card-sm .stat-icon { width: 50px; height: 50px; border-radius: 14px; font-size: 20px; flex-shrink: 0; }
    .stat-label-sm { font-size: 13.5px; color: var(--ink-700); margin-bottom: 2px; }
    .stat-value-sm { font-size: 22px; font-weight: 700; color: var(--plum-900); }
 
    .filter-tabs-row {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        background: var(--white);
        border: 1px solid var(--blush-200);
        border-radius: var(--radius-lg);
        padding: 8px;
        box-shadow: var(--shadow-card);
    }
    .filter-tab {
        padding: 9px 18px;
        border-radius: var(--radius-sm);
        font-size: 13.5px;
        font-weight: 600;
        color: var(--ink-700);
        text-decoration: none;
        transition: all 0.15s ease;
    }
    .filter-tab:hover { background: var(--blush-50); color: var(--plum-900); }
    .filter-tab.active {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        color: var(--plum-900);
    }
 
    .review-card { transition: all 0.15s ease; }
    .review-flagged { border-color: #FBD0D9 !important; background: var(--red-50) !important; }
 
    .review-top-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
    }
    .review-client-name { font-size: 16.5px; font-weight: 700; color: var(--plum-800); margin: 0 0 2px; }
    .review-meta { font-size: 13px; color: var(--ink-500); margin: 0; }
 
    .review-stars { font-size: 16px; white-space: nowrap; }
    .star-filled { color: var(--gold-500); }
    .star-empty { color: var(--blush-200); }
 
    .review-text { font-size: 14.5px; color: var(--ink-700); line-height: 1.6; margin-bottom: 14px; }
 
    .review-status-row { margin-bottom: 14px; }
 
    .owner-reply-box {
        background: var(--blush-50);
        border-left: 3px solid var(--gold-500);
        border-radius: var(--radius-sm);
        padding: 12px 16px;
        margin-bottom: 14px;
    }
    .owner-reply-label { font-size: 12.5px; font-weight: 700; color: var(--plum-800); margin: 0 0 4px; }
    .owner-reply-text { font-size: 13.5px; color: var(--ink-700); margin: 0; }
 
    .review-actions-row { display: flex; gap: 10px; flex-wrap: wrap; }
 
    .btn-approve-review {
        background: linear-gradient(135deg, #38C495, var(--green-500)); color: #fff;
        font-weight: 600; font-size: 13.5px; padding: 9px 18px; border-radius: 8px; border: none;
        display: inline-flex; align-items: center;
    }
    .btn-approve-review:hover { color: #fff; box-shadow: 0 4px 10px rgba(46, 174, 125, 0.3); }
 
    .btn-flag-review {
        background: var(--red-50); color: var(--red-500); border: 1px solid #FBD0D9;
        font-weight: 600; font-size: 13.5px; padding: 9px 18px; border-radius: 8px;
        display: inline-flex; align-items: center;
    }
    .btn-flag-review:hover { background: var(--red-500); color: #fff; }
 
    .btn-reply-review {
        background: var(--blush-50); color: var(--plum-800); border: 1px solid var(--blush-200);
        font-weight: 600; font-size: 13.5px; padding: 9px 18px; border-radius: 8px;
        display: inline-flex; align-items: center;
    }
    .btn-reply-review:hover { background: var(--blush-100); color: var(--plum-900); }
 
    .form-label-custom { display: block; font-size: 13.5px; font-weight: 600; color: var(--ink-700); margin-bottom: 6px; }
    .input-custom {
        background: var(--blush-50) !important; border: 1px solid var(--blush-200) !important;
        border-radius: var(--radius-sm) !important; color: var(--ink-900) !important;
        font-size: 14.5px; padding: 11px 14px !important;
    }
    .input-custom:focus { background: #fff !important; border-color: var(--rose-400) !important; box-shadow: 0 0 0 3px rgba(240, 143, 180, 0.2) !important; outline: none; }
 
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
    .btn-save-changes:hover { color: var(--plum-900); }
</style>
@endsection
 
@section('extra-js')
<script>
   
    document.querySelectorAll('[data-bs-target="#replyModal"]').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('replyClientName').textContent = this.dataset.name;
 
            const form = document.getElementById('replyForm');
            form.action = form.action.replace(/reviews\/\d+/, 'reviews/' + this.dataset.id);
        });
    });
</script>
@endsection
 