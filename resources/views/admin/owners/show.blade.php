@extends('layouts.admin')
@section('title', 'Owner Details - ' . $owner->name)

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
        --gl-green: #1E8E3E;
        --gl-green-light: #E3F6E9;
        --gl-red: #D93025;
        --gl-red-light: #FCE8E6;
        --gl-blue: #1967D2;
        --gl-blue-light: #E8F0FE;
    }

    .gl-owner-profile { max-width: 1180px; margin: 0 auto; box-sizing: border-box; }
    .gl-owner-profile * { box-sizing: border-box; }

    /* Back Link */
    .gl-owner-profile .gl-back-link { margin-bottom: 24px; }
    .gl-owner-profile .gl-btn-outline { color: var(--gl-pink); border: 1.5px solid var(--gl-pink-pale); background: #fff; padding: 10px 20px; border-radius: 12px; font-size: 0.85rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease; box-shadow: 0 2px 8px rgba(255,107,157,0.04); }
    .gl-owner-profile .gl-btn-outline:hover { background: var(--gl-pink-light); border-color: var(--gl-pink); }

    /* Main Container Card */
    .gl-owner-profile .gl-main-card { background: #fff; border-radius: 20px; border: 1px solid var(--gl-border); box-shadow: 0 2px 12px rgba(255, 107, 157, 0.05); overflow: hidden; }

    /* Profile Header */
    .gl-owner-profile .gl-profile-head { display: flex; align-items: center; gap: 22px; padding: 30px 32px 24px; flex-wrap: wrap; background: #fff; }
    .gl-owner-profile .gl-avatar { width: 68px; height: 68px; border-radius: 18px; background: linear-gradient(135deg, var(--gl-pink), var(--gl-pink-dark)); border: 1px solid var(--gl-pink-pale); display: flex; align-items: center; justify-content: center; font-size: 1.6rem; font-weight: 800; color: #fff; flex-shrink: 0; box-shadow: 0 6px 14px rgba(255,107,157,0.25); }
    .gl-owner-profile .gl-profile-meta h2 { font-size: 1.4rem; font-weight: 800; color: var(--gl-text); margin: 0 0 4px; }
    .gl-owner-profile .gl-profile-meta p { font-size: 0.85rem; color: var(--gl-text-lt); margin: 0 0 10px; font-weight: 600; }
    
    .gl-owner-profile .gl-badge-pill { display: inline-flex; align-items: center; gap: 6px; font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.4px; padding: 5px 14px; border-radius: 20px; }
    .gl-owner-profile .gl-badge-pill.gl-active { background: var(--gl-green-light); color: var(--gl-green); }
    .gl-owner-profile .gl-badge-pill.gl-suspended { background: var(--gl-red-light); color: var(--gl-red); }

    .gl-owner-profile .gl-divider { height: 1px; background: var(--gl-border); margin: 0 32px; }

    /* Body Layout Row */
    .gl-owner-profile .gl-body-row { display: grid; grid-template-columns: 1fr 1px 260px; align-items: start; }
    .gl-owner-profile .gl-body-main { min-width: 0; }
    .gl-owner-profile .gl-vdivider { background: var(--gl-border); align-self: stretch; }
    .gl-owner-profile .gl-body-side { padding: 26px 28px; background: #FAFAFC; }

    .gl-owner-profile .gl-section { padding: 26px 32px; }
    .gl-owner-profile .gl-section-title { font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: var(--gl-text-lt); margin: 0 0 18px; display: flex; align-items: center; gap: 8px; }
    .gl-owner-profile .gl-section-title i { color: var(--gl-pink); font-size: 0.9rem; }

    /* Info Grid */
    .gl-owner-profile .gl-info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px 28px; }
    .gl-owner-profile .gl-field-label { display: block; font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.8px; color: var(--gl-text-lt); font-weight: 800; margin-bottom: 4px; }
    .gl-owner-profile .gl-field-value { margin: 0; font-size: 0.92rem; color: var(--gl-text); font-weight: 700; }

    /* Salon Listing Rows */
    .gl-owner-profile .gl-salon-list { display: flex; flex-direction: column; gap: 10px; }
    .gl-owner-profile .gl-salon-row { display: flex; align-items: center; justify-content: space-between; gap: 14px; padding: 12px 16px; border-radius: 14px; background: #FFFBFD; border: 1px solid var(--gl-border); transition: all 0.15s ease; }
    .gl-owner-profile .gl-salon-row:hover { background: var(--gl-pink-light); border-color: var(--gl-pink-pale); }
    .gl-owner-profile .gl-salon-left { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .gl-owner-profile .gl-salon-icon { width: 36px; height: 36px; border-radius: 10px; background: var(--gl-pink-light); color: var(--gl-pink); display: flex; align-items: center; justify-content: center; font-size: 0.82rem; flex-shrink: 0; }
    .gl-owner-profile .gl-salon-row strong { font-size: 0.88rem; color: var(--gl-text); display: block; font-weight: 700; }
    .gl-owner-profile .gl-salon-row small { color: var(--gl-text-lt); font-size: 0.76rem; font-weight: 600; }
    .gl-owner-profile .gl-salon-id { color: var(--gl-text-lt); font-size: 0.72rem; font-weight: 700; }
    .gl-owner-profile .gl-mini-link { color: var(--gl-blue); background: var(--gl-blue-light); padding: 6px 14px; border-radius: 10px; font-size: 0.76rem; font-weight: 700; text-decoration: none; flex-shrink: 0; transition: opacity 0.15s ease; }
    .gl-owner-profile .gl-mini-link:hover { opacity: 0.85; }

    .gl-owner-profile .gl-empty-block { text-align: center; padding: 30px 20px; color: var(--gl-text-lt); font-size: 0.85rem; font-weight: 600; background: #FAFAFC; border-radius: 14px; border: 1px dashed var(--gl-border); }

    /* Quick Actions Column */
    .gl-owner-profile .gl-sidebar-title { font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: var(--gl-text-lt); margin: 0 0 16px; display: flex; align-items: center; gap: 8px; }
    .gl-owner-profile .gl-sidebar-title i { color: var(--gl-pink); font-size: 0.9rem; }

    .gl-owner-profile .gl-action-stack { display: flex; flex-direction: column; gap: 10px; }
    .gl-owner-profile .gl-action-stack form { margin: 0; }
    .gl-owner-profile .gl-mini-action { width: 100%; border: none; padding: 11px 16px; border-radius: 12px; font-size: 0.84rem; font-weight: 700; color: #fff; cursor: pointer; display: flex; align-items: center; gap: 10px; text-decoration: none; justify-content: center; transition: transform 0.15s ease, opacity 0.15s ease; box-shadow: 0 3px 10px rgba(0,0,0,0.08); }
    .gl-owner-profile .gl-mini-action:hover { transform: translateY(-1px); opacity: 0.92; }
    .gl-owner-profile .gl-mini-action.gl-suspend { background: linear-gradient(135deg, #EA4335, #C5221F); }
    .gl-owner-profile .gl-mini-action.gl-activate { background: linear-gradient(135deg, #34A853, #188038); }
    .gl-owner-profile .gl-mini-action.gl-view-salons { background: linear-gradient(135deg, #4285F4, #1967D2); }
    .gl-owner-profile .gl-mini-action.gl-delete { background: #fff; color: var(--gl-red); border: 1.5px solid #FCE8E6; box-shadow: none; }
    .gl-owner-profile .gl-mini-action.gl-delete:hover { background: var(--gl-red-light); transform: none; }

    @media (max-width: 900px) {
        .gl-owner-profile .gl-body-row { grid-template-columns: 1fr; }
        .gl-owner-profile .gl-vdivider { display: none; }
        .gl-owner-profile .gl-body-side { border-top: 1px solid var(--gl-border); padding: 24px 32px; background: #fff; }
    }
    @media (max-width: 640px) {
        .gl-owner-profile .gl-info-grid { grid-template-columns: 1fr; }
        .gl-owner-profile .gl-profile-head,
        .gl-owner-profile .gl-section,
        .gl-owner-profile .gl-body-side { padding-left: 20px; padding-right: 20px; }
        .gl-owner-profile .gl-divider { margin-left: 20px; margin-right: 20px; }
    }
</style>
@endpush

@section('content')
<div class="gl-owner-profile">

    {{-- Back Link Navigation --}}
    <div class="gl-back-link">
        <a href="{{ route('admin.owners.index') }}" class="gl-btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Owners Directory
        </a>
    </div>

    {{-- Main Profile Card Component --}}
    <div class="gl-main-card">

        {{-- Top Profile Header Banner --}}
        <div class="gl-profile-head">
            <div class="gl-avatar">
                {{ strtoupper(substr($owner->name, 0, 1)) }}
            </div>
            <div class="gl-profile-meta">
                <h2>{{ $owner->name }}</h2>
                <p><i class="fas fa-envelope" style="color:var(--gl-pink); margin-right:4px;"></i> {{ $owner->email }} &bull; <i class="fas fa-calendar-alt" style="color:var(--gl-pink); margin-left:4px; margin-right:4px;"></i> Joined {{ $owner->created_at->format('d M Y') }}</p>
                <span class="gl-badge-pill {{ $owner->is_active ? 'gl-active' : 'gl-suspended' }}">
                    <i class="fas fa-circle" style="font-size: 6px;"></i>
                    {{ $owner->is_active ? 'Active Account' : 'Suspended Account' }}
                </span>
            </div>
        </div>

        <div class="gl-divider"></div>

        {{-- Main Layout Body --}}
        <div class="gl-body-row">

            {{-- LEFT COLUMN: Details + Salons --}}
            <div class="gl-body-main">

                {{-- Information Details Block --}}
                <div class="gl-section">
                    <p class="gl-section-title"><i class="fas fa-id-card"></i> Owner Profile Information</p>
                    <div class="gl-info-grid">
                        <div>
                            <span class="gl-field-label">Full Name</span>
                            <p class="gl-field-value">{{ $owner->name }}</p>
                        </div>
                        <div>
                            <span class="gl-field-label">Email Address</span>
                            <p class="gl-field-value">{{ $owner->email }}</p>
                        </div>
                        <div>
                            <span class="gl-field-label">Contact Phone</span>
                            <p class="gl-field-value">{{ $owner->phone ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <span class="gl-field-label">Registration Date</span>
                            <p class="gl-field-value">{{ $owner->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>
                </div>

                <div class="gl-divider"></div>

                {{-- Owned Salons Section --}}
                <div class="gl-section" id="owned-salons">
                    <p class="gl-section-title"><i class="fas fa-store"></i> Associated Salons ({{ $owner->salons->count() }})</p>

                    @if($owner->salons->count())
                        <div class="gl-salon-list">
                            @foreach($owner->salons as $salon)
                                <div class="gl-salon-row">
                                    <div class="gl-salon-left">
                                        <div class="gl-salon-icon"><i class="fas fa-store"></i></div>
                                        <div>
                                            <strong>{{ $salon->name }} <span class="gl-salon-id">#{{ $salon->id }}</span></strong>
                                            <small><i class="fas fa-map-marker-alt"></i> {{ $salon->city }}</small>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.salons.show', $salon->id) }}" class="gl-mini-link">View Salon</a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="gl-empty-block">
                            <i class="fas fa-store-slash" style="font-size: 1.5rem; margin-bottom: 6px; display: block; color: var(--gl-text-lt);"></i>
                            No salons registered under this owner profile yet.
                        </div>
                    @endif
                </div>

            </div>

            {{-- VERTICAL DIVIDER --}}
            <div class="gl-vdivider"></div>

            {{-- RIGHT COLUMN: Quick Admin Actions Panel --}}
            <div class="gl-body-side">
                <p class="gl-sidebar-title"><i class="fas fa-bolt"></i> Quick Actions</p>

                <div class="gl-action-stack">
                    <form action="{{ route('admin.owners.toggle-status', $owner->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="gl-mini-action {{ $owner->is_active ? 'gl-suspend' : 'gl-activate' }}" onclick="return confirm('{{ $owner->is_active ? 'Are you sure you want to suspend this owner?' : 'Are you sure you want to activate this owner?' }}')">
                            <i class="fas {{ $owner->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                            {{ $owner->is_active ? 'Suspend Owner' : 'Activate Owner' }}
                        </button>
                    </form>

                    <a href="{{ route('admin.salons.index', ['owner' => $owner->id]) }}" class="gl-mini-action gl-view-salons">
                        <i class="fas fa-store"></i> Filter Owner Salons
                    </a>

                    @if(\Illuminate\Support\Facades\Route::has('admin.owners.destroy'))
                        <form action="{{ route('admin.owners.destroy', $owner->id) }}" method="POST" onsubmit="return confirm('Permanently delete this owner profile? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="gl-mini-action gl-delete">
                                <i class="fas fa-trash"></i> Delete Owner
                            </button>
                        </form>
                    @endif
                </div>
            </div>

        </div>

    </div>
</div>
@endsection