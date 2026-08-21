@extends('layouts.owner')

@section('title', 'Reviews')

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

    <div class="page-header">
        <h2>Reviews</h2>
        <p>Client feedback and ratings</p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-gold"><i class="bi bi-star-fill"></i></div>
                <div>
                    <div class="stat-label-sm">Avg. Rating</div>
                    <div class="stat-value-sm">{{ number_format($stats['avg_rating'], 1) }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-green"><i class="bi bi-chat-square-text-fill"></i></div>
                <div>
                    <div class="stat-label-sm">Total Reviews</div>
                    <div class="stat-value-sm">{{ number_format($stats['total']) }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-blue"><i class="bi bi-hand-thumbs-up-fill"></i></div>
                <div>
                    <div class="stat-label-sm">5-Star Reviews</div>
                    <div class="stat-value-sm">{{ number_format($stats['five_star']) }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-purple"><i class="bi bi-star-fill"></i></div>
                <div>
                    <div class="stat-label-sm">This Month</div>
                    <div class="stat-value-sm">{{ number_format($stats['this_month']) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="filter-tabs-row mb-3">
        <a href="{{ route('owner.reviews.index') }}" class="filter-tab {{ !request('status') || request('status') === 'all' ? 'active' : '' }}">All Reviews</a>
        <a href="{{ route('owner.reviews.index', ['status' => 'pending']) }}" class="filter-tab {{ request('status') === 'pending' ? 'active' : '' }}">Pending Approval</a>
        <a href="{{ route('owner.reviews.index', ['status' => 'approved']) }}" class="filter-tab {{ request('status') === 'approved' ? 'active' : '' }}">Approved</a>
        <a href="{{ route('owner.reviews.index', ['status' => 'flagged']) }}" class="filter-tab {{ request('status') === 'flagged' ? 'active' : '' }}">Flagged</a>
    </div>

    <div class="reviews-list-container">
        @forelse ($reviews as $review)
            <div class="panel-card review-card {{ $review['flagged'] ? 'review-flagged' : '' }}">

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
                                <i class="bi bi-flag-fill me-2"></i> Flag
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
            <div class="panel-card text-center py-4">
                <i class="bi bi-star" style="font-size:32px; color:#F08FB4;"></i>
                <p class="mt-2 mb-0" style="color:#6B4F62;">No reviews found.</p>
            </div>
        @endforelse
    </div>

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

{{-- ===================== STYLES ===================== --}}
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
        margin-bottom: 1rem;
    }

    .stat-card-sm {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #f0e8ed;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        height: 100%;
    }
    .stat-card-sm .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        font-size: 16px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
    }
    .icon-gold { background: linear-gradient(135deg, #D9A441, #C4903A); }
    .icon-green { background: linear-gradient(135deg, #2EAE7D, #1E8E64); }
    .icon-blue { background: linear-gradient(135deg, #4A7FE0, #3568C4); }
    .icon-purple { background: linear-gradient(135deg, #9B6FD1, #7E56B0); }
    .stat-label-sm { font-size: 12px; color: #8a7a88; margin-bottom: 2px; }
    .stat-value-sm { font-size: 18px; font-weight: 700; color: #2d1f2c; }

    .filter-tabs-row {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        background: #fff;
        border: 1px solid #f0e8ed;
        border-radius: 12px;
        padding: 6px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .filter-tab {
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #4a3a48;
        text-decoration: none;
        transition: all 0.15s ease;
    }
    .filter-tab:hover { background: #fcf6f9; color: #2d1f2c; }
    .filter-tab.active {
        background: linear-gradient(135deg, #FF6B9D, #E85588);
        color: #ffffff;
    }

    /* Fixed Card Height & Extra Bottom Space Removal */
    .reviews-list-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .review-card, .panel-card { 
        background: #fff;
        border: 1px solid #f0e8ed;
        border-radius: 12px;
        padding: 16px 20px !important; 
        margin-bottom: 0 !important;
        height: auto !important;
        min-height: 0 !important;
        max-height: none !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    
    .review-flagged { border-color: #FBD0D9 !important; background: #FCE4EC !important; }

    .review-top-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 6px;
    }
    .review-client-name { font-size: 15px; font-weight: 700; color: #2d1f2c; margin: 0 0 2px; }
    .review-meta { font-size: 12px; color: #8a7a88; margin: 0; }
    .review-stars { font-size: 14px; white-space: nowrap; }
    .star-filled { color: #D9A441; }
    .star-empty { color: #f0e8ed; }
    
    .review-text { 
        font-size: 13.5px; 
        color: #4a3a48; 
        line-height: 1.4; 
        margin-bottom: 8px; 
    }
    .review-status-row { margin-bottom: 8px; }

    .owner-reply-box {
        background: #fcf6f9;
        border-left: 3px solid #D9A441;
        border-radius: 6px;
        padding: 8px 12px;
        margin-bottom: 8px;
    }
    .owner-reply-label { font-size: 11.5px; font-weight: 700; color: #2d1f2c; margin: 0 0 2px; }
    .owner-reply-text { font-size: 12.5px; color: #4a3a48; margin: 0; }

    .review-actions-row { display: flex; gap: 8px; flex-wrap: wrap; }

    .btn-approve-review {
        background: linear-gradient(135deg, #2EAE7D, #1E8E64) !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 12.5px;
        padding: 5px 12px;
        border-radius: 6px;
        border: none;
        display: inline-flex;
        align-items: center;
    }

    .btn-flag-review {
        background: #fff;
        border: 1px solid #FF6B9D;
        color: #E85588;
        font-weight: 600;
        font-size: 12.5px;
        padding: 5px 12px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
    }
    .btn-flag-review:hover {
        background: #E85588;
        color: #ffffff !important;
    }

    .btn-reply-review {
        background: #fcf6f9;
        border: 1px solid #f0e8ed;
        color: #2d1f2c;
        font-weight: 600;
        font-size: 12.5px;
        padding: 5px 12px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
    }

    .badge-status {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .badge-confirmed { background: #E8F5ED; color: #1E8E64; }
    .badge-pending { background: #FDF6E8; color: #C4903A; }
    .badge-cancelled { background: #FCE4EC; color: #D45482; }

    .alert { border-radius: 10px; border: none; padding: 0.6rem 1rem; }
    .alert-success { background: #E8F5ED; color: #1B5E20; }
    .alert-danger { background: #FCE4EC; color: #880E4F; }
</style>
@endsection

{{-- ===================== SCRIPTS ===================== --}}
@section('extra-js')
<script>
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