@extends('layouts.admin')
@section('title', 'Salon Details - ' . $salon->name)

@push('styles')
<style>
    :root {
        --gl-pink: #FF6B9D;
        --gl-pink-light: #FDEAF3;
        --gl-pink-pale: #F1DCE9;
        --gl-text: #2B2230;
        --gl-text-lt: #B98BA6;
        --gl-border: #F1DCE9;
    }

    .back-link { margin-bottom: 20px; }
    .btn-outline { color: var(--gl-pink); border: 1px solid var(--gl-pink-pale); background: #fff; padding: 9px 18px; border-radius: 12px; font-size: 0.85rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease; }
    .btn-outline:hover { background: var(--gl-pink-light); }

    .card { background: #fff; border-radius: 20px; border: 1px solid var(--gl-border); box-shadow: 0 2px 10px rgba(255, 107, 157, 0.05); margin-bottom: 24px; overflow: hidden; }
    .card-header { display: flex; align-items: center; justify-content: space-between; padding: 18px 24px; border-bottom: 1px solid var(--gl-border); }
    .card-title { font-size: 0.95rem; font-weight: 700; color: var(--gl-text); display: flex; align-items: center; gap: 8px; }
    .card-title i { color: var(--gl-pink); }

    .badge { padding: 5px 14px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; }
    .badge-success { background: #E3F6E9; color: #1E8E3E; }
    .badge-warning { background: #FFF4DD; color: #8A5A00; }

    .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 22px 28px; padding: 24px; }
    .info-grid .full { grid-column: 1 / -1; }
    .field-label { display: block; font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.8px; color: var(--gl-text-lt); font-weight: 700; margin-bottom: 6px; }
    .field-value { margin: 0; font-size: 0.95rem; color: var(--gl-text); line-height: 1.5; }

    .mini-stats { display: flex; flex-wrap: wrap; gap: 18px; padding: 24px; }
    .mini-stat { position: relative; overflow: hidden; width: 130px; height: 130px; border-radius: 26px 26px 26px 10px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; box-shadow: 0 6px 14px rgba(0, 0, 0, 0.06); border: 1px solid rgba(0, 0, 0, 0.04); }
    .mini-stat::after { content: ''; position: absolute; width: 70px; height: 70px; background: rgba(255, 255, 255, 0.35); border-radius: 50%; top: -25px; right: -20px; }
    .mini-stat-icon { position: relative; z-index: 1; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.95rem; margin-bottom: 6px; }
    .mini-stat .stat-value { position: relative; z-index: 1; font-size: 1.5rem; font-weight: 800; margin: 0; }
    .mini-stat .stat-label { position: relative; z-index: 1; font-size: 0.62rem; letter-spacing: 0.8px; text-transform: uppercase; font-weight: 700; opacity: 0.85; margin-top: 4px; }

    /* Muted / light mini-stat colors */
    .mini-stat.appointments { background: linear-gradient(135deg, #E8F0FE, #D2E3FC); }
    .mini-stat.appointments .mini-stat-icon { background: rgba(25, 103, 210, 0.12); }
    .mini-stat.appointments .stat-value,
    .mini-stat.appointments .stat-label { color: #1967D2; }

    .mini-stat.rating { background: linear-gradient(135deg, #FFF6E0, #FFEDC2); }
    .mini-stat.rating .mini-stat-icon { background: rgba(138, 90, 0, 0.12); }
    .mini-stat.rating .stat-value,
    .mini-stat.rating .stat-label { color: #8A5A00; }

    .mini-stat.services { background: linear-gradient(135deg, #F3E8FA, #E6D0F0); }
    .mini-stat.services .mini-stat-icon { background: rgba(106, 27, 154, 0.12); }
    .mini-stat.services .stat-value,
    .mini-stat.services .stat-label { color: #6A1B9A; }

    .mini-stat.reviews { background: linear-gradient(135deg, #FDEAF3, #FBD3E6); }
    .mini-stat.reviews .mini-stat-icon { background: rgba(181, 18, 95, 0.12); }
    .mini-stat.reviews .stat-value,
    .mini-stat.reviews .stat-label { color: #E85588; }

    .actions-row { display: flex; flex-wrap: wrap; gap: 16px; padding: 22px 24px; }
    .btn-primary { border: none; padding: 11px 22px; border-radius: 12px; font-size: 0.88rem; font-weight: 700; color: #fff; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: opacity 0.2s ease; }
    .btn-primary:hover { opacity: 0.9; }
    .btn-approve { background: linear-gradient(135deg, #34A853, #188038); }
    .btn-reject { background: linear-gradient(135deg, #EA4335, #C5221F); }

    @media (max-width: 640px) {
        .info-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

<div class="back-link">
    <a href="{{ route('admin.salons.index') }}" class="btn-outline"><i class="fas fa-arrow-left"></i> Back to Salons</a>
</div>

<div class="card">
    <div class="card-header"><span class="card-title"><i class="fas fa-chart-pie"></i> Statistics</span></div>
    <div class="mini-stats">
        <div class="mini-stat appointments">
            <div class="mini-stat-icon"><i class="fas fa-calendar-check"></i></div>
            <p class="stat-value">{{ $salon->appointments->count() }}</p>
            <p class="stat-label">Appointments</p>
        </div>
        <div class="mini-stat rating">
            <div class="mini-stat-icon"><i class="fas fa-star"></i></div>
            <p class="stat-value">{{ number_format($salon->rating ?? 0, 1) }}</p>
            <p class="stat-label">Rating</p>
        </div>
        <div class="mini-stat services">
            <div class="mini-stat-icon"><i class="fas fa-spa"></i></div>
            <p class="stat-value">{{ $salon->services->count() }}</p>
            <p class="stat-label">Services</p>
        </div>
        <div class="mini-stat reviews">
            <div class="mini-stat-icon"><i class="fas fa-comment-dots"></i></div>
            <p class="stat-value">{{ $salon->reviews->count() }}</p>
            <p class="stat-label">Reviews</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-info-circle"></i> {{ $salon->name }}</span>
        <span class="badge {{ $salon->status == 'approved' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($salon->status) }}</span>
    </div>
    <div class="info-grid">
        <div>
            <span class="field-label">Owner</span>
            <p class="field-value">{{ $salon->owner->name ?? 'N/A' }}</p>
        </div>
        <div>
            <span class="field-label">Phone</span>
            <p class="field-value">{{ $salon->phone }}</p>
        </div>
        <div>
            <span class="field-label">Email</span>
            <p class="field-value">{{ $salon->email }}</p>
        </div>
        <div>
            <span class="field-label">City</span>
            <p class="field-value">{{ $salon->city }}</p>
        </div>
        <div class="full">
            <span class="field-label">Address</span>
            <p class="field-value">{{ $salon->address }}</p>
        </div>
        <div class="full">
            <span class="field-label">Description</span>
            <p class="field-value">{{ $salon->description ?? 'No description' }}</p>
        </div>
    </div>
</div>

@if($salon->status == 'pending')
<div class="card">
    <div class="card-header"><span class="card-title"><i class="fas fa-bolt"></i> Actions</span></div>
    <div class="actions-row">
        <form action="{{ route('admin.salon-requests.approve', $salon->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn-primary btn-approve" onclick="return confirm('Approve this salon?')"><i class="fas fa-check"></i> Approve Salon</button>
        </form>
        <form action="{{ route('admin.salon-requests.reject', $salon->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn-primary btn-reject" onclick="return confirm('Reject this salon?')"><i class="fas fa-times"></i> Reject Salon</button>
        </form>
    </div>
</div>
@endif

@endsection