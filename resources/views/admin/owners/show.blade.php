{{-- ============================================================ --}}
{{-- THIS FILE GOES IN: resources/views/admin/owners/show.blade.php --}}
{{-- This is the OWNER DETAILS page (uses single $owner object) --}}
{{-- ============================================================ --}}
@extends('layouts.admin')
@section('title', 'Owner Details - ' . $owner->name)

@push('styles')
<style>
    .gl-owner-profile, .gl-owner-profile * { box-sizing: border-box; }
    .gl-owner-profile {
        --gl-pink: #E0177D;
        --gl-pink-dark: #B5125F;
        --gl-pink-light: #FDEAF3;
        --gl-pink-pale: #F1DCE9;
        --gl-text: #2B2230;
        --gl-text-lt: #B98BA6;
        --gl-border: #F1DCE9;
    }

    .gl-owner-profile .gl-back-link { margin-bottom: 20px; }
    .gl-owner-profile .gl-btn-outline { color: var(--gl-pink); border: 1px solid var(--gl-pink-pale); background: #fff; padding: 9px 18px; border-radius: 12px; font-size: 0.85rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease; }
    .gl-owner-profile .gl-btn-outline:hover { background: var(--gl-pink-light); }

    .gl-owner-profile .gl-hero { position: relative; overflow: hidden; border-radius: 28px; background: linear-gradient(135deg, var(--gl-pink) 0%, var(--gl-pink-dark) 100%); padding: 32px 28px; margin-bottom: 24px; display: flex; align-items: center; gap: 20px; flex-wrap: wrap; }
    .gl-owner-profile .gl-hero::after { content: ''; position: absolute; width: 220px; height: 220px; background: rgba(255, 255, 255, 0.08); border-radius: 50%; top: -90px; right: -60px; pointer-events: none; }
    .gl-owner-profile .gl-hero-avatar { position: relative; z-index: 1; width: 76px; height: 76px; border-radius: 22px; background: rgba(255, 255, 255, 0.22); display: flex; align-items: center; justify-content: center; font-size: 1.8rem; font-weight: 800; color: #fff; flex-shrink: 0; }
    .gl-owner-profile .gl-hero-meta { position: relative; z-index: 1; min-width: 0; }
    .gl-owner-profile .gl-hero-meta h2 { font-size: 1.35rem; font-weight: 800; color: #fff; margin: 0 0 4px; word-break: break-word; }
    .gl-owner-profile .gl-hero-meta p { font-size: 0.85rem; color: rgba(255, 255, 255, 0.85); margin: 0 0 10px; word-break: break-word; }
    .gl-owner-profile .gl-badge-pill { display: inline-flex; align-items: center; gap: 6px; font-size: 0.72rem; font-weight: 700; padding: 5px 14px; border-radius: 20px; }
    .gl-owner-profile .gl-badge-pill.gl-active { background: rgba(255, 255, 255, 0.25); color: #fff; }
    .gl-owner-profile .gl-badge-pill.gl-suspended { background: rgba(0, 0, 0, 0.28); color: #fff; }

    .gl-owner-profile .gl-card { background: #fff; border-radius: 20px; border: 1px solid var(--gl-border); box-shadow: 0 2px 10px rgba(224, 23, 125, 0.05); margin-bottom: 24px; overflow: hidden; }
    .gl-owner-profile .gl-card-header { display: flex; align-items: center; justify-content: space-between; padding: 18px 24px; border-bottom: 1px solid var(--gl-border); }
    .gl-owner-profile .gl-card-title { font-size: 0.95rem; font-weight: 700; color: var(--gl-text); display: flex; align-items: center; gap: 8px; margin: 0; }
    .gl-owner-profile .gl-card-title i { color: var(--gl-pink); }

    .gl-owner-profile .gl-info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 22px 28px; padding: 24px; }
    .gl-owner-profile .gl-field-label { display: block; font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.8px; color: var(--gl-text-lt); font-weight: 700; margin-bottom: 6px; }
    .gl-owner-profile .gl-field-value { margin: 0; font-size: 0.95rem; color: var(--gl-text); font-weight: 700; word-break: break-word; }

    .gl-owner-profile .gl-salon-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 16px 24px; border-bottom: 1px solid var(--gl-border); transition: background 0.15s ease; }
    .gl-owner-profile .gl-salon-row:last-child { border-bottom: none; }
    .gl-owner-profile .gl-salon-row:hover { background: var(--gl-pink-light); }
    .gl-owner-profile .gl-salon-row strong { font-size: 0.92rem; color: var(--gl-text); display: block; }
    .gl-owner-profile .gl-salon-row small { color: var(--gl-text-lt); font-size: 0.78rem; }

    .gl-owner-profile .gl-empty-block { text-align: center; padding: 50px 24px; color: var(--gl-text-lt); }

    .gl-owner-profile .gl-actions-grid { display: flex; flex-wrap: wrap; gap: 14px; padding: 22px 24px; margin: 0; }
    .gl-owner-profile .gl-actions-grid form { margin: 0; }
    .gl-owner-profile .gl-action-btn { border: none; padding: 13px 22px; border-radius: 14px; font-size: 0.88rem; font-weight: 700; color: #fff; cursor: pointer; display: inline-flex; align-items: center; gap: 9px; text-decoration: none; transition: transform 0.15s ease, opacity 0.15s ease; box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12); }
    .gl-owner-profile .gl-action-btn:hover { transform: translateY(-2px); opacity: 0.95; }
    .gl-owner-profile .gl-action-btn.gl-suspend { background: linear-gradient(135deg, #EA4335, #C5221F); }
    .gl-owner-profile .gl-action-btn.gl-activate { background: linear-gradient(135deg, #34A853, #188038); }
    .gl-owner-profile .gl-action-btn.gl-view-salons { background: linear-gradient(135deg, #4285F4, #1967D2); }
    .gl-owner-profile .gl-action-btn.gl-delete { background: linear-gradient(135deg, #B71C1C, #6B0000); }

    @media (max-width: 640px) {
        .gl-owner-profile .gl-info-grid { grid-template-columns: 1fr; }
        .gl-owner-profile .gl-hero { flex-direction: column; align-items: flex-start; text-align: left; }
        .gl-owner-profile .gl-action-btn { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="gl-owner-profile">

    <div class="gl-back-link">
        <a href="{{ route('admin.owners.index') }}" class="gl-btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
    </div>

    <div class="gl-hero">
        <div class="gl-hero-avatar">{{ strtoupper(substr($owner->name, 0, 1)) }}</div>
        <div class="gl-hero-meta">
            <h2>{{ $owner->name }}</h2>
            <p>{{ $owner->email }} &middot; Joined {{ $owner->created_at->format('d M Y') }}</p>
            <span class="gl-badge-pill {{ $owner->is_active ? 'gl-active' : 'gl-suspended' }}">
                <i class="fas {{ $owner->is_active ? 'fa-circle-check' : 'fa-ban' }}"></i>
                {{ $owner->is_active ? 'Active' : 'Suspended' }}
            </span>
        </div>
    </div>

    <div class="gl-card">
        <div class="gl-card-header"><span class="gl-card-title"><i class="fas fa-id-card"></i> Owner Information</span></div>
        <div class="gl-info-grid">
            <div><span class="gl-field-label">Full Name</span><p class="gl-field-value">{{ $owner->name }}</p></div>
            <div><span class="gl-field-label">Email</span><p class="gl-field-value">{{ $owner->email }}</p></div>
            <div><span class="gl-field-label">Phone</span><p class="gl-field-value">{{ $owner->phone ?? 'Not provided' }}</p></div>
            <div><span class="gl-field-label">Joined</span><p class="gl-field-value">{{ $owner->created_at->format('d M Y') }}</p></div>
        </div>
    </div>

    <div class="gl-card" id="owned-salons">
        <div class="gl-card-header"><span class="gl-card-title"><i class="fas fa-store"></i> Owned Salons ({{ $owner->salons->count() }})</span></div>
        <div>
            @forelse($owner->salons as $salon)
                <div class="gl-salon-row">
                    <div><strong>{{ $salon->name }}</strong><small>{{ $salon->city }}</small></div>
                    <a href="{{ route('admin.salons.show', $salon->id) }}" class="gl-btn-outline">View</a>
                </div>
            @empty
                <div class="gl-empty-block">No salons registered yet</div>
            @endforelse
        </div>
    </div>

    <div class="gl-card">
        <div class="gl-card-header"><span class="gl-card-title"><i class="fas fa-bolt"></i> Quick Actions</span></div>
        <div class="gl-actions-grid">
            <form action="{{ route('admin.owners.toggle-status', $owner->id) }}" method="POST">
                @csrf
                <button type="submit" class="gl-action-btn {{ $owner->is_active ? 'gl-suspend' : 'gl-activate' }}" onclick="return confirm('{{ $owner->is_active ? 'Suspend this owner?' : 'Activate this owner?' }}')">
                    <i class="fas {{ $owner->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                    {{ $owner->is_active ? 'Suspend Owner' : 'Activate Owner' }}
                </button>
            </form>

            <a href="{{ route('admin.salons.index', ['owner' => $owner->id]) }}" class="gl-action-btn gl-view-salons"><i class="fas fa-store"></i> View Salons</a>

            @if(\Illuminate\Support\Facades\Route::has('admin.owners.destroy'))
                <form action="{{ route('admin.owners.destroy', $owner->id) }}" method="POST" onsubmit="return confirm('Permanently delete this owner? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="gl-action-btn gl-delete"><i class="fas fa-trash"></i> Delete Owner</button>
                </form>
            @endif
        </div>
    </div>

</div>
@endsection