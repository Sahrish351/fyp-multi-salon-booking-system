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
        --gl-green: #1E8E3E;
        --gl-green-light: #E3F6E9;
        --gl-red: #D93025;
        --gl-red-light: #FCE8E6;
        --gl-blue: #1967D2;
        --gl-blue-light: #E8F0FE;
        max-width: 1180px;
        margin: 0 auto;
    }

    .gl-owner-profile .gl-back-link { margin-bottom: 22px; }
    .gl-owner-profile .gl-btn-outline { color: var(--gl-pink); border: 1px solid var(--gl-pink-pale); background: #fff; padding: 9px 18px; border-radius: 12px; font-size: 0.85rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease; }
    .gl-owner-profile .gl-btn-outline:hover { background: var(--gl-pink-light); }

    /* Two-column layout: main content + sidebar */
    .gl-owner-profile .gl-layout { display: grid; grid-template-columns: 1fr 300px; gap: 28px; align-items: start; }

    /* ONE unified main card: profile header + info + salons, separated by soft dividers, not hard boxes */
    .gl-owner-profile .gl-main-card { background: #fff; border-radius: 26px; box-shadow: 0 4px 24px rgba(224, 23, 125, 0.07); overflow: hidden; }

    .gl-owner-profile .gl-profile-head { display: flex; align-items: center; gap: 22px; padding: 34px 36px 28px; flex-wrap: wrap; }
    .gl-owner-profile .gl-avatar { width: 72px; height: 72px; border-radius: 20px; background: linear-gradient(135deg, var(--gl-pink-light), #ffffff); border: 1px solid var(--gl-pink-pale); display: flex; align-items: center; justify-content: center; font-size: 1.7rem; font-weight: 800; color: var(--gl-pink); flex-shrink: 0; }
    .gl-owner-profile .gl-profile-meta h2 { font-size: 1.4rem; font-weight: 800; color: var(--gl-text); margin: 0 0 5px; }
    .gl-owner-profile .gl-profile-meta p { font-size: 0.88rem; color: var(--gl-text-lt); margin: 0 0 10px; }
    .gl-owner-profile .gl-badge-pill { display: inline-flex; align-items: center; gap: 6px; font-size: 0.72rem; font-weight: 700; padding: 5px 15px; border-radius: 20px; }
    .gl-owner-profile .gl-badge-pill.gl-active { background: var(--gl-green-light); color: var(--gl-green); }
    .gl-owner-profile .gl-badge-pill.gl-suspended { background: var(--gl-red-light); color: var(--gl-red); }

    .gl-owner-profile .gl-divider { height: 1px; background: var(--gl-border); margin: 0 36px; }

    .gl-owner-profile .gl-section { padding: 28px 36px; }
    .gl-owner-profile .gl-section-title { font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.9px; color: var(--gl-text-lt); margin: 0 0 20px; display: flex; align-items: center; gap: 8px; }
    .gl-owner-profile .gl-section-title i { color: var(--gl-pink); }

    .gl-owner-profile .gl-info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 22px 32px; }
    .gl-owner-profile .gl-field-label { display: block; font-size: 0.66rem; text-transform: uppercase; letter-spacing: 0.8px; color: var(--gl-text-lt); font-weight: 700; margin-bottom: 6px; }
    .gl-owner-profile .gl-field-value { margin: 0; font-size: 0.95rem; color: var(--gl-text); font-weight: 700; }

    .gl-owner-profile .gl-salon-list { display: flex; flex-direction: column; gap: 12px; }
    .gl-owner-profile .gl-salon-row { display: flex; align-items: center; justify-content: space-between; gap: 14px; padding: 14px 18px; border-radius: 16px; background: #FFFBFD; border: 1px solid var(--gl-border); transition: all 0.15s ease; }
    .gl-owner-profile .gl-salon-row:hover { background: var(--gl-pink-light); border-color: var(--gl-pink-pale); }
    .gl-owner-profile .gl-salon-left { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .gl-owner-profile .gl-salon-icon { width: 38px; height: 38px; border-radius: 12px; background: var(--gl-pink-light); color: var(--gl-pink); display: flex; align-items: center; justify-content: center; font-size: 0.85rem; flex-shrink: 0; }
    .gl-owner-profile .gl-salon-row strong { font-size: 0.9rem; color: var(--gl-text); display: block; }
    .gl-owner-profile .gl-salon-row small { color: var(--gl-text-lt); font-size: 0.76rem; }
    .gl-owner-profile .gl-salon-id { color: var(--gl-text-lt); font-size: 0.7rem; font-weight: 500; }
    .gl-owner-profile .gl-mini-link { color: var(--gl-blue); background: var(--gl-blue-light); padding: 7px 16px; border-radius: 10px; font-size: 0.78rem; font-weight: 700; text-decoration: none; flex-shrink: 0; }
    .gl-owner-profile .gl-mini-link:hover { opacity: 0.85; }

    .gl-owner-profile .gl-empty-block { text-align: center; padding: 34px 20px; color: var(--gl-text-lt); font-size: 0.88rem; }

    /* Sidebar Quick Actions — spaced with flex gap, not cramped */
    .gl-owner-profile .gl-sidebar-card { background: #fff; border-radius: 24px; box-shadow: 0 4px 24px rgba(224, 23, 125, 0.07); padding: 26px 22px; position: sticky; top: 20px; }
    .gl-owner-profile .gl-sidebar-title { font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.9px; color: var(--gl-text-lt); margin: 0 0 18px; display: flex; align-items: center; gap: 8px; }
    .gl-owner-profile .gl-sidebar-title i { color: var(--gl-pink); }

    .gl-owner-profile .gl-action-stack { display: flex; flex-direction: column; gap: 12px; }
    .gl-owner-profile .gl-action-stack form { margin: 0; }
    .gl-owner-profile .gl-mini-action { width: 100%; border: none; padding: 12px 16px; border-radius: 14px; font-size: 0.84rem; font-weight: 700; color: #fff; cursor: pointer; display: flex; align-items: center; gap: 10px; text-decoration: none; justify-content: center; transition: transform 0.15s ease, opacity 0.15s ease; }
    .gl-owner-profile .gl-mini-action:hover { transform: translateY(-1px); opacity: 0.92; }
    .gl-owner-profile .gl-mini-action.gl-suspend { background: linear-gradient(135deg, #EA4335, #C5221F); }
    .gl-owner-profile .gl-mini-action.gl-activate { background: linear-gradient(135deg, #34A853, #188038); }
    .gl-owner-profile .gl-mini-action.gl-view-salons { background: linear-gradient(135deg, #4285F4, #1967D2); }
    .gl-owner-profile .gl-mini-action.gl-delete { background: #fff; color: var(--gl-red); border: 1.5px solid var(--gl-red-light); }
    .gl-owner-profile .gl-mini-action.gl-delete:hover { background: var(--gl-red-light); transform: none; }

    @media (max-width: 900px) {
        .gl-owner-profile .gl-layout { grid-template-columns: 1fr; }
        .gl-owner-profile .gl-sidebar-card { position: static; }
    }
    @media (max-width: 640px) {
        .gl-owner-profile .gl-info-grid { grid-template-columns: 1fr; }
        .gl-owner-profile .gl-profile-head,
        .gl-owner-profile .gl-section { padding-left: 22px; padding-right: 22px; }
        .gl-owner-profile .gl-divider { margin-left: 22px; margin-right: 22px; }
    }
</style>
@endpush

@section('content')
<div class="gl-owner-profile">

    <div class="gl-back-link">
        <a href="{{ route('admin.owners.index') }}" class="gl-btn-outline"><i class="fas fa-arrow-left"></i> Back to Owners</a>
    </div>

    <div class="gl-layout">

        {{-- MAIN CONTENT: one unified flowing card --}}
        <div class="gl-main-card">

            <div class="gl-profile-head">
                <div class="gl-avatar">{{ strtoupper(substr($owner->name, 0, 1)) }}</div>
                <div class="gl-profile-meta">
                    <h2>{{ $owner->name }}</h2>
                    <p>{{ $owner->email }} &middot; Joined {{ $owner->created_at->format('d M Y') }}</p>
                    <span class="gl-badge-pill {{ $owner->is_active ? 'gl-active' : 'gl-suspended' }}">
                        <i class="fas {{ $owner->is_active ? 'fa-circle-check' : 'fa-ban' }}"></i>
                        {{ $owner->is_active ? 'Active' : 'Suspended' }}
                    </span>
                </div>
            </div>

            <div class="gl-divider"></div>

            <div class="gl-section">
                <p class="gl-section-title"><i class="fas fa-id-card"></i> Owner Information</p>
                <div class="gl-info-grid">
                    <div><span class="gl-field-label">Full Name</span><p class="gl-field-value">{{ $owner->name }}</p></div>
                    <div><span class="gl-field-label">Email</span><p class="gl-field-value">{{ $owner->email }}</p></div>
                    <div><span class="gl-field-label">Phone</span><p class="gl-field-value">{{ $owner->phone ?? 'Not provided' }}</p></div>
                    <div><span class="gl-field-label">Joined</span><p class="gl-field-value">{{ $owner->created_at->format('d M Y') }}</p></div>
                </div>
            </div>

            <div class="gl-divider"></div>

            <div class="gl-section" id="owned-salons">
                <p class="gl-section-title"><i class="fas fa-store"></i> Owned Salons ({{ $owner->salons->count() }})</p>

                @if($owner->salons->count())
                    <div class="gl-salon-list">
                        @foreach($owner->salons as $salon)
                            <div class="gl-salon-row">
                                <div class="gl-salon-left">
                                    <div class="gl-salon-icon"><i class="fas fa-store"></i></div>
                                    <div>
                                        <strong>{{ $salon->name }} <span class="gl-salon-id">#{{ $salon->id }}</span></strong>
                                        <small>{{ $salon->city }}</small>
                                    </div>
                                </div>
                                <a href="{{ route('admin.salons.show', $salon->id) }}" class="gl-mini-link">View</a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="gl-empty-block">No salons registered yet</div>
                @endif
            </div>

        </div>

        {{-- SIDEBAR: Quick Actions, spaced buttons --}}
        <div class="gl-sidebar-card">
            <p class="gl-sidebar-title"><i class="fas fa-bolt"></i> Quick Actions</p>

            <div class="gl-action-stack">
                <form action="{{ route('admin.owners.toggle-status', $owner->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="gl-mini-action {{ $owner->is_active ? 'gl-suspend' : 'gl-activate' }}" onclick="return confirm('{{ $owner->is_active ? 'Suspend this owner?' : 'Activate this owner?' }}')">
                        <i class="fas {{ $owner->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                        {{ $owner->is_active ? 'Suspend Owner' : 'Activate Owner' }}
                    </button>
                </form>

                <a href="{{ route('admin.salons.index', ['owner' => $owner->id]) }}" class="gl-mini-action gl-view-salons">
                    <i class="fas fa-store"></i> View Salons
                </a>

                @if(\Illuminate\Support\Facades\Route::has('admin.owners.destroy'))
                    <form action="{{ route('admin.owners.destroy', $owner->id) }}" method="POST" onsubmit="return confirm('Permanently delete this owner? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="gl-mini-action gl-delete"><i class="fas fa-trash"></i> Delete Owner</button>
                    </form>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection