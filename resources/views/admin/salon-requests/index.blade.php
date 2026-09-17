@extends('layouts.admin')
@section('title', 'Salon Registration Requests - Beauty Blush Salons')

@push('styles')
<style>
    :root {
        --gl-pink: #FF6B9D;
        --gl-pink-dark: #E85588;
        --gl-pink-light: #FDEAF3;
        --gl-pink-pale: #F1DCE9;
        --gl-text: #2B2230;
        --gl-text-lt: #B98BA6;
        --gl-border: #F1DCE9;
    }

    .page-header-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; gap: 16px; flex-wrap: wrap; }
    .page-header-row h1 { font-size: 1.5rem; font-weight: 800; color: var(--gl-text); margin: 0; letter-spacing: -0.3px; }
    .page-header-row p { font-size: 0.85rem; color: var(--gl-text-lt); margin: 4px 0 0; }

    .btn-outline { color: var(--gl-pink); border: 1px solid var(--gl-pink-pale); background: #fff; padding: 7px 14px; border-radius: 12px; font-size: 0.8rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s ease; cursor: pointer; }
    .btn-outline:hover { background: var(--gl-pink-light); border-color: var(--gl-pink); }

    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-bottom: 20px; }
    .stat-card { background: #fff; position: relative; overflow: hidden; border-radius: 14px; padding: 14px 18px; border: 1px solid var(--gl-border); transition: all 0.25s ease; box-shadow: 0 2px 8px rgba(255, 107, 157, 0.04); display: flex; flex-direction: column; justify-content: center; }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(255, 107, 157, 0.1); border-color: var(--gl-pink-pale); }
    .stat-icon { width: 34px; height: 34px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 0.95rem; margin-bottom: 8px; }
    .stat-label { font-size: 0.68rem; letter-spacing: 0.6px; text-transform: uppercase; font-weight: 700; color: var(--gl-text-lt); }
    .stat-value { font-size: 1.4rem; font-weight: 800; margin-top: 2px; }

    .stat-card.pending { border-left: 4px solid #F59E0B; }
    .stat-card.pending .stat-icon { background: #FEF3C7; color: #D97706; }
    .stat-card.pending .stat-value { color: #B45309; }

    .stat-card.approved { border-left: 4px solid #10B981; }
    .stat-card.approved .stat-icon { background: #D1FAE5; color: #059669; }
    .stat-card.approved .stat-value { color: #047857; }

    .stat-card.rejected { border-left: 4px solid #EF4444; }
    .stat-card.rejected .stat-icon { background: #FEE2E2; color: #DC2626; }
    .stat-card.rejected .stat-value { color: #B91C1C; }

    .card { background: #fff; border-radius: 16px; border: 1px solid var(--gl-border); box-shadow: 0 2px 8px rgba(255, 107, 157, 0.04); margin-bottom: 16px; overflow: hidden; }
    .card-header { display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; border-bottom: 1px solid var(--gl-border); }
    .card-title { font-size: 0.9rem; font-weight: 700; color: var(--gl-text); display: flex; align-items: center; gap: 8px; }
    .card-title i { color: var(--gl-pink); }

    .badge { padding: 4px 10px; border-radius: 12px; font-size: 0.68rem; font-weight: 700; }
    .badge-warning { background: #FEF3C7; color: #B45309; }

    .request-row { display: flex; justify-content: space-between; align-items: center; gap: 16px; padding: 14px 18px; border-bottom: 1px solid var(--gl-border); flex-wrap: wrap; transition: background 0.15s ease; }
    .request-row:last-child { border-bottom: none; }
    .request-row:hover { background: var(--gl-pink-light); }
    .request-row strong { font-size: 0.88rem; color: var(--gl-text); }
    .request-row small { color: var(--gl-text-lt); font-size: 0.75rem; }

    .row-actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }

    .btn-view-teal { border: none; padding: 6px 14px; border-radius: 10px; font-size: 0.75rem; font-weight: 700; color: #fff; cursor: pointer; background: #00838f; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: background 0.2s ease; line-height: 1.2; }
    .btn-view-teal:hover { background: #006670; color: #fff; }

    .btn-primary-green { border: none; padding: 6px 14px; border-radius: 10px; font-size: 0.75rem; font-weight: 700; color: #fff; cursor: pointer; background: #10B981; transition: background 0.2s ease; line-height: 1.2; }
    .btn-primary-green:hover { background: #059669; }

    .btn-reject-red { color: #fff; border: none; background: #EF4444; padding: 6px 14px; border-radius: 10px; font-size: 0.75rem; font-weight: 700; cursor: pointer; line-height: 1.2; transition: background 0.2s ease; }
    .btn-reject-red:hover { background: #DC2626; }

    .empty-state { text-align: center; padding: 48px 20px; }
    .empty-state i { color: #10B981; margin-bottom: 12px; font-size: 2.5rem; }
    .empty-state h3 { font-size: 1rem; color: var(--gl-text); margin: 0 0 4px; }
    .empty-state p { color: var(--gl-text-lt); font-size: 0.8rem; margin: 0; }

    .pagination-wrapper { margin-top: 16px; display: flex; justify-content: center; }

    .form-control { width: 100%; border: 1px solid var(--gl-border); border-radius: 10px; padding: 9px 12px; font-size: 0.82rem; font-family: inherit; resize: vertical; color: var(--gl-text); }
    .form-control:focus { outline: none; border-color: var(--gl-pink); box-shadow: 0 0 0 3px rgba(255, 107, 157, 0.1); }

    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(43, 34, 48, 0.5); backdrop-filter: blur(3px); display: flex; align-items: center; justify-content: center; z-index: 1000; }
    .modal-container { background: #fff; border-radius: 16px; width: 90%; max-width: 420px; overflow: hidden; border: 1px solid var(--gl-border); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 14px 18px; border-bottom: 1px solid var(--gl-border); background: #faf5f8; }
    .modal-header h3 { font-size: 0.95rem; margin: 0; color: var(--gl-text); }
    .modal-body { padding: 16px 18px; }
    .modal-footer { padding: 12px 18px; border-top: 1px solid var(--gl-border); display: flex; gap: 8px; justify-content: flex-end; background: #faf5f8; }

    @media (max-width: 640px) {
        .request-row { flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush

@section('content')

<div class="page-header-row">
    <div>
        <h1>Salon Registration Requests</h1>
        <p>Review and manage new salon registrations across the network.</p>
    </div>
    <a href="{{ route('admin.salon-requests.index') }}" class="btn-outline"><i class="fas fa-sync-alt"></i> Refresh</a>
</div>

<div class="stats-grid">
    <div class="stat-card pending">
        <div class="stat-icon"><i class="fas fa-clock"></i></div>
        <div class="stat-label">Pending Review</div>
        <div class="stat-value">{{ $stats['pending'] }}</div>
    </div>
    <div class="stat-card approved">
        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        <div class="stat-label">Approved Salons</div>
        <div class="stat-value">{{ $stats['approved'] }}</div>
    </div>
    <div class="stat-card rejected">
        <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
        <div class="stat-label">Rejected Requests</div>
        <div class="stat-value">{{ $stats['rejected'] }}</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-hourglass-half"></i> Pending Requests Queue</span>
        <span class="badge badge-warning">{{ $pendingSalons->total() }} pending</span>
    </div>
    <div>
        @forelse($pendingSalons as $salon)
            <div class="request-row">
                <div>
                    <strong>{{ $salon->name }}</strong><br>
                    <small><i class="fas fa-map-marker-alt text-danger"></i> {{ $salon->city }} &bull; <i class="fas fa-user text-muted"></i> {{ $salon->owner->name ?? 'N/A' }}</small>
                </div>
                <div class="row-actions">
                    <a href="{{ route('admin.salon-requests.show', $salon->id) }}" class="btn-view-teal"><i class="fas fa-eye"></i> View</a>
                    <form action="{{ route('admin.salon-requests.approve', $salon->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-primary-green" onclick="return confirm('Approve this salon?')"><i class="fas fa-check"></i> Approve</button>
                    </form>
                    <button type="button" class="btn-reject-red" onclick="document.getElementById('rejectModal{{ $salon->id }}').style.display='flex'"><i class="fas fa-times"></i> Reject</button>
                </div>
            </div>

            <div id="rejectModal{{ $salon->id }}" class="modal-overlay" style="display:none;">
                <div class="modal-container">
                    <div class="modal-header">
                        <h3>Reject Salon: {{ $salon->name }}</h3>
                        <span onclick="this.closest('.modal-overlay').style.display='none'" style="cursor:pointer; font-size: 1.2rem; color: var(--gl-text-lt);">&times;</span>
                    </div>
                    <form action="{{ route('admin.salon-requests.reject', $salon->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <label style="font-size:0.75rem; font-weight:700; color:var(--gl-text-lt); text-transform:uppercase; margin-bottom:6px; display:block;">Reason for rejection</label>
                            <textarea name="reason" class="form-control" rows="3" placeholder="Provide a clear reason for rejection..." required></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-outline" onclick="this.closest('.modal-overlay').style.display='none'">Cancel</button>
                            <button type="submit" class="btn-reject-red">Confirm Rejection</button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-check-circle"></i>
                <h3>No Pending Requests</h3>
                <p>All salon registration requests have been successfully processed.</p>
            </div>
        @endforelse
    </div>
</div>

<div class="pagination-wrapper">{{ $pendingSalons->links() }}</div>

@endsection