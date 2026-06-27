@extends('layouts.admin')
@section('title', 'Review Salon Request - ' . $salon->name)

@push('styles')
<style>
    :root {
        --gl-pink: #E0177D;
        --gl-pink-light: #FDEAF3;
        --gl-pink-pale: #F1DCE9;
        --gl-text: #2B2230;
        --gl-text-lt: #B98BA6;
        --gl-border: #F1DCE9;
        --gl-green: #1E8E3E;
        --gl-green-light: #E3F6E9;
        --gl-red: #D93025;
        --gl-red-light: #FCE8E6;
        --gl-amber-light: #FFF4DD;
    }

    .back-link { margin-bottom: 20px; }

    .card { background: #fff; border-radius: 20px; border: 1px solid var(--gl-border); box-shadow: 0 2px 10px rgba(224, 23, 125, 0.05); margin-bottom: 24px; overflow: hidden; }
    .card-header { display: flex; align-items: center; justify-content: space-between; padding: 18px 24px; border-bottom: 1px solid var(--gl-border); }
    .card-title { font-size: 0.95rem; font-weight: 700; color: var(--gl-text); display: flex; align-items: center; gap: 8px; }
    .card-title i { color: var(--gl-pink); }

    .badge { padding: 5px 14px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.3px; }
    .badge-warning { background: var(--gl-amber-light); color: #8A5A00; }
    .badge-success { background: var(--gl-green-light); color: var(--gl-green); }
    .badge-danger { background: var(--gl-red-light); color: var(--gl-red); }

    .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 22px 28px; padding: 24px; }
    .info-grid .full { grid-column: 1 / -1; }
    .field-label { display: block; font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.8px; color: var(--gl-text-lt); font-weight: 700; margin-bottom: 6px; }
    .field-value { margin: 0; font-size: 0.95rem; color: var(--gl-text); line-height: 1.5; }
    .field-value.strong { font-weight: 700; font-size: 1.02rem; }

    .actions-row { display: flex; flex-wrap: wrap; gap: 20px; padding: 22px 24px; }
    .actions-row form { display: flex; flex-direction: column; gap: 10px; }

    .btn-primary { border: none; padding: 11px 22px; border-radius: 12px; font-size: 0.88rem; font-weight: 700; color: #fff; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: opacity 0.2s ease; }
    .btn-primary:hover { opacity: 0.9; }
    .btn-approve { background: linear-gradient(135deg, #34A853, #188038); }
    .btn-reject { background: linear-gradient(135deg, #EA4335, #C5221F); }

    .btn-outline { color: var(--gl-pink); border: 1px solid var(--gl-pink-pale); background: #fff; padding: 9px 18px; border-radius: 12px; font-size: 0.85rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease; cursor: pointer; }
    .btn-outline:hover { background: var(--gl-pink-light); }

    .form-control { width: 100%; border: 1px solid var(--gl-border); border-radius: 12px; padding: 10px 14px; font-size: 0.85rem; font-family: inherit; resize: vertical; color: var(--gl-text); }
    .form-control:focus { outline: none; border-color: var(--gl-pink); box-shadow: 0 0 0 3px rgba(224, 23, 125, 0.1); }

    @media (max-width: 640px) {
        .info-grid { grid-template-columns: 1fr; }
        .actions-row { flex-direction: column; }
    }
</style>
@endpush

@section('content')

<div class="back-link">
    <a href="{{ route('admin.salon-requests.index') }}" class="btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-info-circle"></i> Salon Information</span>
        <span class="badge {{ $salon->status == 'pending' ? 'badge-warning' : ($salon->status == 'approved' ? 'badge-success' : 'badge-danger') }}">{{ ucfirst($salon->status) }}</span>
    </div>
    <div class="info-grid">
        <div>
            <span class="field-label">Salon Name</span>
            <p class="field-value strong">{{ $salon->name }}</p>
        </div>
        <div>
            <span class="field-label">Owner</span>
            <p class="field-value strong">{{ $salon->owner->name ?? 'N/A' }}</p>
        </div>
        <div>
            <span class="field-label">Email</span>
            <p class="field-value">{{ $salon->email }}</p>
        </div>
        <div>
            <span class="field-label">Phone</span>
            <p class="field-value">{{ $salon->phone }}</p>
        </div>
        <div>
            <span class="field-label">City</span>
            <p class="field-value">{{ $salon->city }}</p>
        </div>
        <div>
            <span class="field-label">Address</span>
            <p class="field-value">{{ $salon->address }}</p>
        </div>
        <div class="full">
            <span class="field-label">Description</span>
            <p class="field-value">{{ $salon->description ?? 'No description provided.' }}</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><span class="card-title"><i class="fas fa-bolt"></i> Actions</span></div>
    <div class="actions-row">
        <form action="{{ route('admin.salon-requests.approve', $salon->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn-primary btn-approve" onclick="return confirm('Approve this salon?')">
                <i class="fas fa-check"></i> Approve Salon
            </button>
        </form>
        <form action="{{ route('admin.salon-requests.reject', $salon->id) }}" method="POST" style="min-width:260px;">
            @csrf
            <textarea name="reason" class="form-control" rows="2" placeholder="Rejection reason..."></textarea>
            <button type="submit" class="btn-primary btn-reject">
                <i class="fas fa-times"></i> Reject Salon
            </button>
        </form>
    </div>
</div>

@endsection