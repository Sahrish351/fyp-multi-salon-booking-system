@extends('layouts.admin')
@section('title', 'System Settings — Beauty Blush Salons')
 
@section('content')
 
{{-- Guard: if the controller ever fails to pass $settings, the page still won't
     crash — every field will fall back to its default value. --}}
@php
    $settings = $settings ?? [];
    $__acctUser = auth()->user();
@endphp
 
<style>
    :root {
        --pk: #FF6B9D;
        --pk-dark: #E85588;
        --pk-lt: #fce4ec;
        --pk-bg: #fff0f7;
        --ink: #1a1a2e;
        --ink-mid: #6b6b7b;
        --ink-lt: #a5a5b3;
        --line: #ececef;
    }
 
    /* ================= HEADER ================= */
    .settings-header { display: flex; align-items: center; gap: 14px; margin-bottom: 1.6rem; }
    .settings-header .h-icon {
        width: 48px; height: 48px; border-radius: 14px; flex-shrink: 0;
        background: linear-gradient(135deg, var(--pk), var(--pk-dark)); color: #fff;
        display: flex; align-items: center; justify-content: center; font-size: 1.1rem;
        box-shadow: 0 6px 16px rgba(255,107,157,.3);
    }
    .settings-header h4 {
        font-family: 'Playfair Display', serif; font-size: 1.6rem; font-weight: 700;
        color: var(--ink); margin: 0;
    }
    .settings-header p { color: var(--ink-lt); font-size: .85rem; margin: 3px 0 0; }
 
    /* ================= ALERTS ================= */
    .alert { border-radius: 14px; border: none; padding: .9rem 1.2rem; font-size: .87rem; }
    .alert-success { background: #f0fdf4; color: #16a34a; }
    .alert-danger  { background: #fef2f2; color: #dc2626; }
 
    /* ================= GRID: 2 cards per row, natural height ================= */
    .settings-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 26px;
        align-items: start;
    }
    .settings-grid .span-2 { grid-column: 1 / -1; }
 
    /* ================= CARD ================= */
    .s-card {
        background: #fff;
        border: 1.5px solid var(--line);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 3px 16px rgba(0,0,0,.04);
    }
 
    .s-card-head {
        padding: 1.15rem 1.5rem;
        display: flex; align-items: center; gap: 12px;
        border-bottom: 1.5px solid var(--line);
        background: #fffafc;
    }
    .s-card-head .sc-icon {
        width: 40px; height: 40px; border-radius: 12px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: .95rem; color: #fff;
    }
    .s-card-head .sc-text .sc-title { font-weight: 700; color: var(--ink); font-size: .96rem; }
    .s-card-head .sc-text .sc-desc  { color: var(--ink-lt); font-size: .73rem; margin-top: 2px; }
 
    .icon-globe { background: linear-gradient(135deg, var(--pk), var(--pk-dark)); }
    .icon-user  { background: linear-gradient(135deg, #fbbf24, #d97706); }
    .icon-lock  { background: linear-gradient(135deg, #f87171, #dc2626); }
 
    .s-card-body { padding: 1.6rem; }
    @media (max-width:576px){ .s-card-body { padding: 1.2rem; } }
 
    /* ================= FORM FIELDS ================= */
    /* Label is now clearly separated from field — extra breathing room */
    .settings-label {
        display: block; color: var(--ink-mid); font-weight: 700; font-size: .74rem;
        text-transform: uppercase; letter-spacing: .5px;
        margin-bottom: .7rem;
        line-height: 1.2;
    }
    .settings-hint { font-size: .71rem; color: var(--ink-lt); margin-top: 6px; }
    .settings-input {
        width: 100%; background: #fdf7fa; border: 1.5px solid var(--pk-lt); color: var(--ink);
        border-radius: 11px; padding: 11px 15px; font-size: .87rem;
        transition: border-color .2s, box-shadow .2s, background .2s; box-sizing: border-box;
    }
    .settings-input:focus {
        outline: none; border-color: var(--pk); background: #fff;
        box-shadow: 0 0 0 3px rgba(255,107,157,.1);
    }
    .settings-input::placeholder { color: #c2c2ca; }
    .settings-input.is-invalid { border-color: #ef4444; background: #fef2f2; }
    .text-danger.small { font-size: .73rem; margin-top: 6px; }
 
    /* Keep browser autofill from turning fields yellow/blue */
    .settings-input:-webkit-autofill,
    .settings-input:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0 1000px #fdf7fa inset;
        -webkit-text-fill-color: var(--ink);
        transition: background-color 9999s ease-in-out 0s;
    }
 
    .input-icon-wrap { position: relative; }
    .input-icon-wrap i {
        position: absolute; left: 15px; top: 50%; transform: translateY(-50%);
        color: var(--pk); font-size: .8rem; pointer-events: none;
    }
    .input-icon-wrap .settings-input { padding-left: 40px; }
 
    /* Fields stacked one after another get generous, even spacing */
    .field-block { margin-bottom: 1.5rem; }
    .field-block:last-of-type { margin-bottom: 0; }
 
    /* ================= BUTTONS ================= */
    .btn-save {
        background: linear-gradient(135deg, var(--pk), var(--pk-dark)); color: #fff;
        border: none; border-radius: 11px; padding: 11px 26px; font-weight: 700;
        font-size: .84rem; box-shadow: 0 5px 14px rgba(255,107,157,.28);
        transition: all .2s ease; display: inline-flex; align-items: center; gap: 8px;
        margin-top: 1.6rem;
    }
    .btn-save:hover { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(255,107,157,.38); color: #fff; }
 
    .btn-outline-pk {
        background: #fff; border: 1.5px solid var(--pk-lt); color: var(--pk);
        border-radius: 11px; padding: 9px 20px; font-weight: 700; font-size: .84rem;
        transition: all .2s ease; display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-outline-pk:hover { background: var(--pk-bg); border-color: var(--pk); color: var(--pk-dark); }
 
    /* ================= FOOTER STRIP inside a card ================= */
    .s-card-footer {
        margin-top: 1.8rem; padding-top: 1.3rem; border-top: 1.5px dashed var(--line);
        display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;
    }
    .s-card-footer .ft-text .ft-title { font-weight: 700; color: var(--ink); font-size: .82rem; }
    .s-card-footer .ft-text .ft-desc  { color: var(--ink-lt); font-size: .72rem; margin-top: 2px; }
 
    /* ================= ACCOUNT AVATAR STRIP ================= */
    .account-hero {
        display: flex; align-items: center; gap: 12px; padding-bottom: 1.2rem;
        margin-bottom: 1.4rem; border-bottom: 1.5px solid var(--line);
    }
    .account-hero .a-avatar {
        width: 46px; height: 46px; border-radius: 50%;
        background: linear-gradient(135deg, #fbbf24, #d97706); color: #fff;
        display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.05rem;
        box-shadow: 0 6px 14px rgba(217,119,6,.28); flex-shrink: 0;
    }
    .account-hero .a-name { font-weight: 700; color: var(--ink); font-size: .92rem; }
    .account-hero .a-email { color: var(--ink-lt); font-size: .75rem; margin-top: 2px; }
 
    /* ================= GENERAL SETTINGS — own grid, not Bootstrap row/col ================= */
    /* This keeps the label/field/button gap from conflicting with the admin layout's Bootstrap */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem 1.6rem;
    }
    .form-grid .full { grid-column: 1 / -1; }
    @media (max-width: 640px) {
        .form-grid { grid-template-columns: 1fr; }
    }
 
    @media (max-width: 900px) {
        .settings-grid { grid-template-columns: 1fr; }
    }
</style>
 
<div class="settings-header">
    <span class="h-icon"><i class="fas fa-cog"></i></span>
    <div>
        <h4>System Settings</h4>
        <p>Manage platform configuration and preferences here</p>
    </div>
</div>
 
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
 
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
 
@if(!$__acctUser)
<div class="alert alert-danger mb-4">
    <i class="fas fa-exclamation-triangle me-2"></i>
    We couldn't find your login session. Please
    <a href="{{ route('login') }}">log in again</a> before saving account details.
</div>
@endif
 
<div class="settings-grid">
 
    {{-- ================= GENERAL SETTINGS — full width, top ================= --}}
    <div class="s-card span-2">
        <div class="s-card-head">
            <span class="sc-icon icon-globe"><i class="fas fa-globe"></i></span>
            <div class="sc-text">
                <div class="sc-title">General Settings</div>
                <div class="sc-desc">Basic site details that will be publicly visible</div>
            </div>
        </div>
        <div class="s-card-body">
            <form action="{{ route('admin.system-settings.general') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div>
                        <label class="settings-label">Site Name</label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-store"></i>
                            <input type="text" name="site_name" class="settings-input"
                                   value="{{ old('site_name', $settings['site_name'] ?? 'Glamora') }}">
                        </div>
                    </div>
                    <div>
                        <label class="settings-label">Site Email</label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="site_email" class="settings-input"
                                   value="{{ old('site_email', $settings['site_email'] ?? 'support@glamora.com') }}">
                        </div>
                    </div>
                    <div>
                        <label class="settings-label">Site Phone</label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-phone"></i>
                            <input type="text" name="site_phone" class="settings-input"
                                   value="{{ old('site_phone', $settings['site_phone'] ?? '') }}">
                        </div>
                    </div>
                    <div>
                        <label class="settings-label">Site Description</label>
                        <input type="text" name="site_description" class="settings-input"
                               placeholder="Short tagline..."
                               value="{{ old('site_description', $settings['site_description'] ?? '') }}">
                    </div>
                    <div class="full">
                        <button type="submit" class="btn-save" style="margin-top:0;"><i class="fas fa-save"></i>Save Changes</button>
                    </div>
                </div>
            </form>
 
            <div class="s-card-footer">
                <div class="ft-text">
                    <div class="ft-title"><i class="fas fa-broom me-1" style="color:var(--pk);"></i>Application Cache</div>
                    <div class="ft-desc">Clear old cached data so the latest changes take effect.</div>
                </div>
                <form action="{{ route('admin.system-settings.clear-cache') }}" method="POST" onsubmit="return confirm('Clear application cache?')">
                    @csrf
                    <button type="submit" class="btn-outline-pk"><i class="fas fa-broom"></i>Clear Cache</button>
                </form>
            </div>
        </div>
    </div>
 
    {{-- ================= ROW: ACCOUNT DETAILS | CHANGE PASSWORD ================= --}}
    <div class="s-card">
        <div class="s-card-head">
            <span class="sc-icon icon-user"><i class="fas fa-user-shield"></i></span>
            <div class="sc-text">
                <div class="sc-title">Account Details</div>
                <div class="sc-desc">Update your name and email</div>
            </div>
        </div>
        <div class="s-card-body">
            @if($__acctUser)
            <div class="account-hero">
                <div class="a-avatar">{{ strtoupper(substr($__acctUser->name ?? 'A', 0, 1)) }}</div>
                <div>
                    <div class="a-name">{{ $__acctUser->name }}</div>
                    <div class="a-email">{{ $__acctUser->email }}</div>
                </div>
            </div>
            @endif
 
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <div class="field-block">
                    <label class="settings-label">Name</label>
                    <input type="text" name="name" class="settings-input @error('name') is-invalid @enderror" value="{{ old('name', optional($__acctUser)->name) }}">
                    @error('name')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field-block">
                    <label class="settings-label">Email</label>
                    <input type="email" name="email" class="settings-input @error('email') is-invalid @enderror" value="{{ old('email', optional($__acctUser)->email) }}">
                    @error('email')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn-save"><i class="fas fa-save"></i>Update Account</button>
            </form>
        </div>
    </div>
 
    <div class="s-card">
        <div class="s-card-head">
            <span class="sc-icon icon-lock"><i class="fas fa-lock"></i></span>
            <div class="sc-text">
                <div class="sc-title">Change Password</div>
                <div class="sc-desc">Update your account security</div>
            </div>
        </div>
        <div class="s-card-body">
            <form action="{{ route('admin.settings.password') }}" method="POST">
                @csrf
                <div class="field-block">
                    <label class="settings-label">Current Password</label>
                    <input type="password" name="current_password" class="settings-input @error('current_password') is-invalid @enderror" autocomplete="current-password">
                    @error('current_password')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field-block">
                    <label class="settings-label">New Password</label>
                    <input type="password" name="password" class="settings-input @error('password') is-invalid @enderror" autocomplete="new-password">
                    @error('password')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field-block">
                    <label class="settings-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="settings-input" autocomplete="new-password">
                </div>
                <button type="submit" class="btn-save"><i class="fas fa-key"></i>Update Password</button>
            </form>
        </div>
    </div>
 
</div>
 
@endsection
 