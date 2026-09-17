@extends('layouts.admin')
@section('title', 'Review Salon Request - ' . $salon->name)

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
        --gl-green: #059669;
        --gl-green-light: #D1FAE5;
        --gl-red: #DC2626;
        --gl-red-light: #FEE2E2;
        --gl-amber-light: #FEF3C7;
    }

    .back-link { margin-bottom: 16px; }

    .card { background: #fff; border-radius: 16px; border: 1px solid var(--gl-border); box-shadow: 0 2px 8px rgba(255, 107, 157, 0.04); margin-bottom: 16px; overflow: hidden; }
    .card-header { display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; border-bottom: 1px solid var(--gl-border); }
    .card-title { font-size: 0.9rem; font-weight: 700; color: var(--gl-text); display: flex; align-items: center; gap: 8px; }
    .card-title i { color: var(--gl-pink); }

    .badge { padding: 4px 12px; border-radius: 12px; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.3px; }
    .badge-warning { background: var(--gl-amber-light); color: #B45309; }
    .badge-success { background: var(--gl-green-light); color: var(--gl-green); }
    .badge-danger { background: var(--gl-red-light); color: var(--gl-red); }

    .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px 24px; padding: 20px 24px; }
    .info-grid .full { grid-column: 1 / -1; }
    .field-label { display: block; font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.8px; color: var(--gl-text-lt); font-weight: 700; margin-bottom: 4px; }
    .field-value { margin: 0; font-size: 0.9rem; color: var(--gl-text); line-height: 1.5; }
    .field-value.strong { font-weight: 700; font-size: 0.98rem; }

    .actions-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; padding: 20px 24px; }
    @media (max-width: 768px) {
        .actions-row { grid-template-columns: 1fr; }
    }
    .actions-row form { display: flex; flex-direction: column; gap: 10px; }

    .btn-primary { border: none; padding: 9px 18px; border-radius: 12px; font-size: 0.85rem; font-weight: 700; color: #fff; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: opacity 0.2s ease; width: 100%; }
    .btn-primary:hover { opacity: 0.9; }
    .btn-approve { background: #10B981; }
    .btn-reject { background: #EF4444; }

    .btn-outline { color: var(--gl-pink); border: 1px solid var(--gl-pink-pale); background: #fff; padding: 7px 14px; border-radius: 12px; font-size: 0.8rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s ease; cursor: pointer; }
    .btn-outline:hover { background: var(--gl-pink-light); }

    .form-control { width: 100%; border: 1px solid var(--gl-border); border-radius: 10px; padding: 9px 12px; font-size: 0.82rem; font-family: inherit; resize: vertical; color: var(--gl-text); box-sizing: border-box; }
    .form-control:focus { outline: none; border-color: var(--gl-pink); box-shadow: 0 0 0 3px rgba(255, 107, 157, 0.1); }

    @media (max-width: 640px) {
        .info-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

<div class="back-link">
    <a href="{{ route('admin.salon-requests.index') }}" class="btn-outline"><i class="fas fa-arrow-left"></i> Back to Requests</a>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-store-alt"></i> Salon Profile Information</span>
        <span class="badge {{ $salon->status == 'pending' ? 'badge-warning' : ($salon->status == 'approved' ? 'badge-success' : 'badge-danger') }}">{{ ucfirst($salon->status) }}</span>
    </div>
    <div class="info-grid">
        <div>
            <span class="field-label">Salon Name</span>
            <p class="field-value strong">{{ $salon->name }}</p>
        </div>
        <div>
            <span class="field-label">Owner Name</span>
            <p class="field-value strong">{{ $salon->owner->name ?? 'N/A' }}</p>
        </div>
        <div>
            <span class="field-label">Email Address</span>
            <p class="field-value">{{ $salon->email }}</p>
        </div>
        <div>
            <span class="field-label">Phone Number</span>
            <p class="field-value">{{ $salon->phone }}</p>
        </div>
        <div>
            <span class="field-label">City</span>
            <p class="field-value">{{ $salon->city }}</p>
        </div>
        <div>
            <span class="field-label">Complete Address</span>
            <p class="field-value">{{ $salon->address }}</p>
        </div>
        <div class="full">
            <span class="field-label">Salon Description</span>
            <p class="field-value">{{ $salon->description ?? 'No description provided by the owner.' }}</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><span class="card-title"><i class="fas fa-tasks"></i> Request Management Actions</span></div>
    <div class="actions-row">
        <div>
            <span class="field-label" style="margin-bottom: 8px;">Direct Approval</span>
            <form action="{{ route('admin.salon-requests.approve', $salon->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-primary btn-approve" onclick="return confirm('Are you sure you want to approve this salon?')">
                    <i class="fas fa-check-circle"></i> Approve Salon
                </button>
            </form>
        </div>
        <div>
            <span class="field-label" style="margin-bottom: 8px;">Rejection with Reason</span>
            <form action="{{ route('admin.salon-requests.reject', $salon->id) }}" method="POST">
                @csrf
                <textarea name="reason" class="form-control" rows="2" placeholder="Provide rejection reason..." required></textarea>
                <button type="submit" class="btn-primary btn-reject">
                    <i class="fas fa-times-circle"></i> Reject Salon
                </button>
            </form>
        </div>
    </div>
</div>

@endsection