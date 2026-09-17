@extends('layouts.admin')
@section('title', 'Salon Details - ' . $salon->name)

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

    /* ── Page Header / Back ── */
    .page-top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
    .btn-back {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 20px; border-radius: 12px;
        border: 1.5px solid var(--gl-pink-pale);
        background: #fff; color: var(--gl-pink);
        font-size: 0.85rem; font-weight: 700;
        text-decoration: none; transition: all 0.2s ease;
    }
    .btn-back:hover { background: var(--gl-pink-light); border-color: var(--gl-pink); }

    /* ── Hero Salon Banner ── */
    .salon-hero-card {
        background: linear-gradient(135deg, #FFF0F5 0%, #FFFFFF 100%);
        border-radius: 20px; border: 1px solid var(--gl-border);
        padding: 28px; margin-bottom: 24px;
        box-shadow: 0 4px 20px rgba(255,107,157,0.06);
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 20px;
    }
    .salon-hero-left { display: flex; align-items: center; gap: 20px; }
    .salon-avatar-lg {
        width: 72px; height: 72px; border-radius: 18px;
        background: linear-gradient(135deg, var(--gl-pink), var(--gl-pink-dark));
        color: #fff; font-size: 1.8rem; font-weight: 800;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 8px 16px rgba(255,107,157,0.25);
        flex-shrink: 0; overflow: hidden;
    }
    .salon-avatar-lg img { width: 100%; height: 100%; object-fit: cover; }
    .salon-hero-info h2 { font-size: 1.5rem; font-weight: 800; color: var(--gl-text); margin: 0 0 6px 0; }
    .salon-hero-meta { display: flex; gap: 16px; flex-wrap: wrap; font-size: 0.85rem; color: var(--gl-text-lt); font-weight: 600; }
    .salon-hero-meta span { display: inline-flex; align-items: center; gap: 6px; }
    .salon-hero-meta i { color: var(--gl-pink); }

    /* ── Badges ── */
    .gl-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 14px; border-radius: 20px;
        font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .gl-badge.success { background: #E3F6E9; color: #1E8E3E; }
    .gl-badge.warning { background: #FFF4DD; color: #B06000; }

    /* ── Standard Card Layout ── */
    .gl-card { background: #fff; border-radius: 20px; border: 1px solid var(--gl-border); box-shadow: 0 2px 12px rgba(255,107,157,0.05); overflow: hidden; margin-bottom: 24px; }
    .gl-card-header { display: flex; align-items: center; gap: 10px; padding: 18px 26px; border-bottom: 1px solid var(--gl-border); background: #FAFAFC; }
    .gl-card-header i { color: var(--gl-pink); font-size: 0.95rem; }
    .gl-card-header span { font-size: 0.92rem; font-weight: 800; color: var(--gl-text); text-transform: uppercase; letter-spacing: 0.5px; }

    /* ── Mini Stats Grid ── */
    .stats-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 18px; padding: 24px; }
    .stat-box {
        position: relative; border-radius: 16px; padding: 20px;
        display: flex; flex-direction: column; gap: 8px;
        border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        transition: transform 0.2s ease;
    }
    .stat-box:hover { transform: translateY(-3px); }
    .stat-box.appointments { background: linear-gradient(135deg, #F0F4FF, #E1ECFC); color: #1967D2; }
    .stat-box.rating { background: linear-gradient(135deg, #FFF8E7, #FEEDC7); color: #B06000; }
    .stat-box.services { background: linear-gradient(135deg, #F6EEFB, #EADAF5); color: #6A1B9A; }
    .stat-box.reviews { background: linear-gradient(135deg, #FFF0F5, #FCE0EC); color: #E85588; }

    .stat-box-top { display: flex; justify-content: space-between; align-items: center; }
    .stat-icon-wrap { width: 38px; height: 38px; border-radius: 10px; background: rgba(255,255,255,0.6); display: flex; align-items: center; justify-content: center; font-size: 1rem; }
    .stat-num { font-size: 1.8rem; font-weight: 800; line-height: 1.1; margin: 0; }
    .stat-lbl { font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; opacity: 0.8; }

    /* ── Info Grid ── */
    .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 22px 28px; padding: 26px; }
    .info-grid .full { grid-column: 1 / -1; }
    .info-group { display: flex; flex-direction: column; gap: 4px; }
    .info-label { font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: var(--gl-text-lt); }
    .info-val { font-size: 0.95rem; font-weight: 600; color: var(--gl-text); line-height: 1.6; margin: 0; }

    /* ── Action Box ── */
    .action-box-card { background: linear-gradient(135deg, #FFF5F7, #FFF); border: 1.5px dashed var(--gl-pink-pale); }
    .actions-row { display: flex; gap: 14px; padding: 24px; flex-wrap: wrap; }
    .btn-action {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 12px 24px; border-radius: 12px; border: none;
        font-size: 0.88rem; font-weight: 700; color: #fff;
        cursor: pointer; box-shadow: 0 4px 14px rgba(0,0,0,0.1);
        transition: transform 0.15s ease, opacity 0.15s ease;
    }
    .btn-action:hover { transform: translateY(-2px); opacity: 0.95; color: #fff; }
    .btn-approve { background: linear-gradient(135deg, #34A853, #188038); box-shadow: 0 6px 16px rgba(52,168,83,0.3); }
    .btn-reject  { background: linear-gradient(135deg, #EA4335, #C5221F); box-shadow: 0 6px 16px rgba(234,67,53,0.3); }

    @media (max-width: 640px) {
        .info-grid { grid-template-columns: 1fr; }
        .salon-hero-card { flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush

@section('content')

{{-- Back Button Bar --}}
<div class="page-top-bar">
    <a href="{{ route('admin.salons.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to Salons List
    </a>
</div>

{{-- Hero Header Card --}}
<div class="salon-hero-card">
    <div class="salon-hero-left">
        <div class="salon-avatar-lg">
            @if(!empty($salon->logo))
                <img src="{{ asset('storage/' . $salon->logo) }}" alt="{{ $salon->name }}">
            @else
                {{ strtoupper(substr($salon->name, 0, 1)) }}
            @endif
        </div>
        <div class="salon-hero-info">
            <h2>{{ $salon->name }}</h2>
            <div class="salon-hero-meta">
                <span><i class="fas fa-map-marker-alt"></i> {{ $salon->area }}, {{ $salon->city }}</span>
                <span><i class="fas fa-phone-alt"></i> {{ $salon->phone }}</span>
            </div>
        </div>
    </div>
    <div>
        <span class="gl-badge {{ $salon->status == 'approved' ? 'success' : 'warning' }}">
            <i class="fas fa-circle" style="font-size: 6px;"></i> {{ ucfirst($salon->status) }}
        </span>
    </div>
</div>

{{-- Quick Statistics Grid --}}
<div class="gl-card">
    <div class="gl-card-header">
        <i class="fas fa-chart-line"></i>
        <span>Performance & Metrics</span>
    </div>
    <div class="stats-container">
        <div class="stat-box appointments">
            <div class="stat-box-top">
                <span class="stat-lbl">Appointments</span>
                <div class="stat-icon-wrap"><i class="fas fa-calendar-check"></i></div>
            </div>
            <p class="stat-num">{{ $salon->appointments->count() }}</p>
        </div>
        <div class="stat-box rating">
            <div class="stat-box-top">
                <span class="stat-lbl">Rating</span>
                <div class="stat-icon-wrap"><i class="fas fa-star"></i></div>
            </div>
            <p class="stat-num">{{ number_format($salon->rating ?? 0, 1) }}</p>
        </div>
        <div class="stat-box services">
            <div class="stat-box-top">
                <span class="stat-lbl">Services</span>
                <div class="stat-icon-wrap"><i class="fas fa-spa"></i></div>
            </div>
            <p class="stat-num">{{ $salon->services->count() }}</p>
        </div>
        <div class="stat-box reviews">
            <div class="stat-box-top">
                <span class="stat-lbl">Reviews</span>
                <div class="stat-icon-wrap"><i class="fas fa-comment-dots"></i></div>
            </div>
            <p class="stat-num">{{ $salon->reviews->count() }}</p>
        </div>
    </div>
</div>

{{-- Main Details Information Card --}}
<div class="gl-card">
    <div class="gl-card-header">
        <i class="fas fa-info-circle"></i>
        <span>Salon Profile Details</span>
    </div>
    <div class="info-grid">
        <div class="info-group">
            <span class="info-label">Assigned Owner</span>
            <p class="info-val">{{ $salon->owner->name ?? 'N/A' }} <span style="color:var(--gl-text-lt); font-size:0.82rem;">({{ $salon->owner->email ?? '' }})</span></p>
        </div>
        <div class="info-group">
            <span class="info-label">Contact Phone</span>
            <p class="info-val">{{ $salon->phone }}</p>
        </div>
        <div class="info-group">
            <span class="info-label">Email Address</span>
            <p class="info-val">{{ $salon->email }}</p>
        </div>
        <div class="info-group">
            <span class="info-label">City & Area</span>
            <p class="info-val">{{ $salon->city }} / {{ $salon->area }}</p>
        </div>
        <div class="info-group full">
            <span class="info-label">Complete Street Address</span>
            <p class="info-val">{{ $salon->address }}</p>
        </div>
        <div class="info-group full">
            <span class="info-label">About / Description</span>
            <p class="info-val" style="color: {{ $salon->description ? 'var(--gl-text)' : 'var(--gl-text-lt)' }};">
                {{ $salon->description ?? 'No description provided for this salon yet.' }}
            </p>
        </div>
    </div>
</div>

{{-- Pending Approval Actions Box --}}
@if($salon->status == 'pending')
<div class="gl-card action-box-card">
    <div class="gl-card-header" style="background: rgba(255,107,157,0.05);">
        <i class="fas fa-shield-alt" style="color: var(--gl-pink);"></i>
        <span>Moderation Action Panel</span>
    </div>
    <div class="actions-row">
        <form action="{{ route('admin.salon-requests.approve', $salon->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn-action btn-approve" onclick="return confirm('Are you sure you want to approve this salon?')">
                <i class="fas fa-check-circle"></i> Approve Salon
            </button>
        </form>
        <form action="{{ route('admin.salon-requests.reject', $salon->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn-action btn-reject" onclick="return confirm('Are you sure you want to reject this salon request?')">
                <i class="fas fa-times-circle"></i> Reject Salon
            </button>
        </form>
    </div>
</div>
@endif

@endsection