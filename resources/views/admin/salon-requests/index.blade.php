@extends('layouts.admin')
@section('title', 'Salon Registration Requests - Glamora')

@push('styles')
<style>
    :root {
        --gl-pink: #E0177D;
        --gl-pink-light: #FDEAF3;
        --gl-pink-pale: #F1DCE9;
        --gl-text: #2B2230;
        --gl-text-lt: #B98BA6;
        --gl-border: #F1DCE9;
    }

    .page-header-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 28px; gap: 16px; flex-wrap: wrap; }
    .page-header-row h1 { font-size: 1.6rem; font-weight: 800; color: var(--gl-text); margin: 0; }
    .page-header-row p { font-size: 0.88rem; color: var(--gl-text-lt); margin: 6px 0 0; }

    .btn-outline { color: var(--gl-pink); border: 1px solid var(--gl-pink-pale); background: #fff; padding: 9px 18px; border-radius: 12px; font-size: 0.85rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease; cursor: pointer; }
    .btn-outline:hover { background: var(--gl-pink-light); }

    .stats-grid { display: flex; flex-wrap: wrap; gap: 18px; margin-bottom: 28px; }
    .stat-card { position: relative; overflow: hidden; width: 132px; height: 132px; border-radius: 26px 26px 26px 10px; padding: 14px; box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12); display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; }
    .stat-card::after { content: ''; position: absolute; width: 70px; height: 70px; background: rgba(255, 255, 255, 0.15); border-radius: 50%; top: -25px; right: -20px; }
    .stat-icon { position: relative; z-index: 1; width: 32px; height: 32px; background: rgba(255, 255, 255, 0.25); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.95rem; margin-bottom: 6px; }
    .stat-label { position: relative; z-index: 1; font-size: 0.6rem; letter-spacing: 1px; text-transform: uppercase; font-weight: 700; color: rgba(255, 255, 255, 0.85); }
    .stat-value { position: relative; z-index: 1; font-size: 1.5rem; font-weight: 800; margin-top: 4px; color: #fff; }

    .stat-card.pending { background: linear-gradient(135deg, #FBBC05, #F29900); }
    .stat-card.pending .stat-label, .stat-card.pending .stat-value { color: #3C2800; }
    .stat-card.pending .stat-icon { background: rgba(60, 40, 0, 0.15); }

    .stat-card.approved { background: linear-gradient(135deg, #34A853, #188038); }
    .stat-card.rejected { background: linear-gradient(135deg, #EA4335, #C5221F); }

    .card { background: #fff; border-radius: 20px; border: 1px solid var(--gl-border); box-shadow: 0 2px 10px rgba(224, 23, 125, 0.05); margin-bottom: 24px; overflow: hidden; }
    .card-header { display: flex; align-items: center; justify-content: space-between; padding: 18px 24px; border-bottom: 1px solid var(--gl-border); }
    .card-title { font-size: 0.95rem; font-weight: 700; color: var(--gl-text); display: flex; align-items: center; gap: 8px; }
    .card-title i { color: var(--gl-pink); }

    .badge { padding: 5px 14px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; }
    .badge-warning { background: #FFF4DD; color: #8A5A00; }

    .request-row { display: flex; justify-content: space-between; align-items: center; gap: 16px; padding: 16px 24px; border-bottom: 1px solid var(--gl-border); flex-wrap: wrap; transition: background 0.15s ease; }
    .request-row:last-child { border-bottom: none; }
    .request-row:hover { background: var(--gl-pink-light); }
    .request-row strong { font-size: 0.92rem; color: var(--gl-text); }
    .request-row small { color: var(--gl-text-lt); font-size: 0.78rem; }

    .row-actions { display: flex; gap: 10px; flex-wrap: wrap; }
    .btn-primary { border: none; padding: 8px 16px; border-radius: 10px; font-size: 0.8rem; font-weight: 700; color: #fff; cursor: pointer; background: linear-gradient(135deg, #34A853, #188038); transition: opacity 0.2s ease; }
    .btn-primary:hover { opacity: 0.9; }
    .btn-reject-link { color: #C5221F; border: 1px solid #FAD2CF; background: #FCE8E6; padding: 8px 16px; border-radius: 10px; font-size: 0.8rem; font-weight: 700; cursor: pointer; }
    .btn-reject-link:hover { background: #FAD2CF; }

    .empty-state { text-align: center; padding: 64px 24px; }
    .empty-state i { color: #34A853; margin-bottom: 16px; }
    .empty-state h3 { font-size: 1.1rem; color: var(--gl-text); margin: 0 0 6px; }
    .empty-state p { color: var(--gl-text-lt); font-size: 0.88rem; margin: 0; }

    .pagination-wrapper { margin-top: 20px; }

    .form-control { width: 100%; border: 1px solid var(--gl-border); border-radius: 12px; padding: 10px 14px; font-size: 0.85rem; font-family: inherit; resize: vertical; color: var(--gl-text); }
    .form-control:focus { outline: none; border-color: var(--gl-pink); box-shadow: 0 0 0 3px rgba(224, 23, 125, 0.1); }

    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(43, 34, 48, 0.55); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; z-index: 1000; }
    .modal-container { background: #fff; border-radius: 24px; width: 90%; max-width: 450px; overflow: hidden; }
    .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 18px 22px; border-bottom: 1px solid var(--gl-border); }
    .modal-header h3 { font-size: 1rem; margin: 0; color: var(--gl-text); }
    .modal-body { padding: 20px 22px; }
    .modal-footer { padding: 16px 22px; border-top: 1px solid var(--gl-border); display: flex; gap: 12px; justify-content: flex-end; }

    @media (max-width: 640px) {
        .request-row { flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush

@section('content')

<div class="page-header-row">
    <div>
        <h1>Salon Registration Requests</h1>
        <p>Review and manage new salon registrations</p>
    </div>
    <a href="{{ route('admin.salon-requests.index') }}" class="btn-outline"><i class="fas fa-sync-alt"></i> Refresh</a>
</div>

@php
    $pendingCount = \App\Models\Salon::where('status', 'pending')->count();
    $approvedCount = \App\Models\Salon::where('status', 'approved')->count();
    $rejectedCount = \App\Models\Salon::where('status', 'rejected')->count();
@endphp

<div class="stats-grid">
    <div class="stat-card pending">
        <div class="stat-icon">⏳</div>
        <div class="stat-label">Pending</div>
        <div class="stat-value">{{ $pendingCount }}</div>
    </div>
    <div class="stat-card approved">
        <div class="stat-icon">✅</div>
        <div class="stat-label">Approved</div>
        <div class="stat-value">{{ $approvedCount }}</div>
    </div>
    <div class="stat-card rejected">
        <div class="stat-icon">❌</div>
        <div class="stat-label">Rejected</div>
        <div class="stat-value">{{ $rejectedCount }}</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-clock"></i> Pending Requests</span>
        <span class="badge badge-warning">{{ $pendingSalons->total() }} pending</span>
    </div>
    <div>
        @forelse($pendingSalons as $salon)
            <div class="request-row">
                <div>
                    <strong>{{ $salon->name }}</strong><br>
                    <small>{{ $salon->city }} • {{ $salon->owner->name ?? 'N/A' }}</small>
                </div>
                <div class="row-actions">
                    <a href="{{ route('admin.salon-requests.show', $salon->id) }}" class="btn-outline"><i class="fas fa-eye"></i> View</a>
                    <form action="{{ route('admin.salon-requests.approve', $salon->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-primary" onclick="return confirm('Approve this salon?')">Approve</button>
                    </form>
                    <button class="btn-reject-link" onclick="document.getElementById('rejectModal{{ $salon->id }}').style.display='flex'">Reject</button>
                </div>
            </div>
            <div id="rejectModal{{ $salon->id }}" class="modal-overlay" style="display:none;">
                <div class="modal-container">
                    <div class="modal-header">
                        <h3>Reject Salon: {{ $salon->name }}</h3>
                        <span onclick="this.closest('.modal-overlay').style.display='none'" style="cursor:pointer;">&times;</span>
                    </div>
                    <form action="{{ route('admin.salon-requests.reject', $salon->id) }}" method="POST">
                        <div class="modal-body">
                            @csrf
                            <textarea name="reason" class="form-control" rows="3" placeholder="Reason for rejection..." required></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-outline" onclick="this.closest('.modal-overlay').style.display='none'">Cancel</button>
                            <button type="submit" class="btn-primary" style="background:linear-gradient(135deg, #EA4335, #C5221F);">Reject</button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-check-circle fa-3x"></i>
                <h3>No Pending Requests</h3>
                <p>All salon registration requests have been processed</p>
            </div>
        @endforelse
    </div>
</div>

<div class="pagination-wrapper">{{ $pendingSalons->links() }}</div>

@endsection