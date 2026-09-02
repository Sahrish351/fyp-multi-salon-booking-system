@extends('layouts.admin')
@section('title', 'System Settings — Glamora')

@section('content')

{{-- ✅ Guard: agar controller kabhi $settings pass na kare to bhi page crash
     nahi hoga, sab fields apni default value dikha dengi. --}}
@php $settings = $settings ?? []; @endphp

<div class="mb-4">
    <h4 class="fw-bold" style="color:#1a1a1a;">
        <i class="fas fa-cog me-2" style="color:var(--pk);"></i> System Settings
    </h4>
    <p style="color:#9a9a9a;">Manage platform configuration and preferences</p>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4">

    {{-- Sidebar Tabs --}}
    <div class="col-lg-3">
        <div class="settings-side">
            <div class="nav flex-column" role="tablist">
                <button class="settings-tab active" data-bs-toggle="tab" data-bs-target="#general" type="button">
                    <i class="fas fa-globe me-3"></i>General
                </button>
                <button class="settings-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button">
                    <i class="fas fa-credit-card me-3"></i>Payment
                </button>
                <button class="settings-tab" data-bs-toggle="tab" data-bs-target="#email" type="button">
                    <i class="fas fa-envelope me-3"></i>Email
                </button>
                <button class="settings-tab" data-bs-toggle="tab" data-bs-target="#social" type="button">
                    <i class="fas fa-share-alt me-3"></i>Social Links
                </button>
                <button class="settings-tab" data-bs-toggle="tab" data-bs-target="#account" type="button">
                    <i class="fas fa-user-shield me-3"></i>My Account
                </button>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="col-lg-9">
        <div class="tab-content">

            {{-- General --}}
            <div class="tab-pane fade show active" id="general">
                <div class="settings-card">
                    <div class="settings-card-header"><i class="fas fa-globe me-2"></i>General Settings</div>
                    <div class="settings-card-body">
                        <form action="{{ route('admin.system-settings.general') }}" method="POST">
                            @csrf
                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="settings-label">Site Name</label>
                                    <input type="text" name="site_name" class="settings-input"
                                           value="{{ old('site_name', $settings['site_name'] ?? 'Glamora') }}">
                                </div>
                                <div class="col-12">
                                    <label class="settings-label">Site Description</label>
                                    <textarea name="site_description" rows="3" class="settings-input">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="settings-label">Site Email</label>
                                    <input type="email" name="site_email" class="settings-input"
                                           value="{{ old('site_email', $settings['site_email'] ?? 'support@glamora.com') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="settings-label">Site Phone</label>
                                    <input type="text" name="site_phone" class="settings-input"
                                           value="{{ old('site_phone', $settings['site_phone'] ?? '') }}">
                                </div>
                                <div class="col-12 d-flex gap-2 flex-wrap">
                                    <button type="submit" class="btn-save"><i class="fas fa-save me-2"></i>Save Changes</button>
                                </div>
                            </div>
                        </form>

                        <hr style="border-color:#f0f0f0;margin:2rem 0;">

                        <form action="{{ route('admin.system-settings.clear-cache') }}" method="POST" onsubmit="return confirm('Clear application cache?')">
                            @csrf
                            <button type="submit" class="btn-outline-pk">
                                <i class="fas fa-broom me-2"></i>Clear Application Cache
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Payment --}}
            <div class="tab-pane fade" id="payment">
                <div class="settings-card">
                    <div class="settings-card-header"><i class="fas fa-credit-card me-2"></i>Payment Settings</div>
                    <div class="settings-card-body">
                        <form action="{{ route('admin.system-settings.payment') }}" method="POST">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="settings-label">Advance Payment Amount (Rs.)</label>
                                    <input type="number" name="advance_amount" class="settings-input"
                                           value="{{ old('advance_amount', $settings['advance_amount'] ?? 100) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="settings-label">Slot Lock Time (minutes)</label>
                                    <input type="number" name="slot_lock_minutes" class="settings-input"
                                           value="{{ old('slot_lock_minutes', $settings['slot_lock_minutes'] ?? 10) }}">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn-save"><i class="fas fa-save me-2"></i>Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Email --}}
            <div class="tab-pane fade" id="email">
                <div class="settings-card">
                    <div class="settings-card-header"><i class="fas fa-envelope me-2"></i>Email Settings</div>
                    <div class="settings-card-body">
                        <form action="{{ route('admin.system-settings.email') }}" method="POST">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="settings-label">SMTP Host</label>
                                    <input type="text" name="smtp_host" class="settings-input"
                                           value="{{ old('smtp_host', $settings['smtp_host'] ?? 'smtp.gmail.com') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="settings-label">SMTP Port</label>
                                    <input type="text" name="smtp_port" class="settings-input"
                                           value="{{ old('smtp_port', $settings['smtp_port'] ?? 587) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="settings-label">SMTP Username</label>
                                    <input type="text" name="smtp_username" class="settings-input"
                                           value="{{ old('smtp_username', $settings['smtp_username'] ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="settings-label">SMTP Password</label>
                                    <input type="password" name="smtp_password" class="settings-input" placeholder="Leave blank to keep current">
                                </div>
                                <div class="col-12 d-flex gap-2 flex-wrap">
                                    <button type="submit" class="btn-save"><i class="fas fa-save me-2"></i>Save Settings</button>
                                </div>
                            </div>
                        </form>

                        <form action="{{ route('admin.system-settings.test-email') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn-outline-pk">
                                <i class="fas fa-paper-plane me-2"></i>Send Test Email
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Social --}}
            <div class="tab-pane fade" id="social">
                <div class="settings-card">
                    <div class="settings-card-header"><i class="fas fa-share-alt me-2"></i>Social Links</div>
                    <div class="settings-card-body">
                        <form action="{{ route('admin.system-settings.social') }}" method="POST">
                            @csrf
                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="settings-label"><i class="fab fa-facebook me-1"></i> Facebook</label>
                                    <input type="url" name="facebook_url" class="settings-input" placeholder="https://facebook.com/glamora"
                                           value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}">
                                </div>
                                <div class="col-12">
                                    <label class="settings-label"><i class="fab fa-instagram me-1"></i> Instagram</label>
                                    <input type="url" name="instagram_url" class="settings-input" placeholder="https://instagram.com/glamora"
                                           value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}">
                                </div>
                                <div class="col-12">
                                    <label class="settings-label"><i class="fab fa-twitter me-1"></i> Twitter</label>
                                    <input type="url" name="twitter_url" class="settings-input" placeholder="https://twitter.com/glamora"
                                           value="{{ old('twitter_url', $settings['twitter_url'] ?? '') }}">
                                </div>
                                <div class="col-12">
                                    <label class="settings-label"><i class="fab fa-youtube me-1"></i> YouTube</label>
                                    <input type="url" name="youtube_url" class="settings-input" placeholder="https://youtube.com/glamora"
                                           value="{{ old('youtube_url', $settings['youtube_url'] ?? '') }}">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn-save"><i class="fas fa-save me-2"></i>Save Social Links</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- My Account --}}
            <div class="tab-pane fade" id="account">

                {{-- ✅ FIX: pehle "auth()->user()->name" seedha call ho raha tha.
                     Agar kisi wajah se session pe koi logged-in user na ho
                     (guard mismatch, session expire, ya route pe login check
                     missing), to auth()->user() null return karta hai aur
                     ->name access karte hi "Attempt to read property on null"
                     fatal error deta — jaisa abhi hua. optional() isko null-safe
                     bana deta hai: null hone par khaali field dikhega, crash
                     nahi hoga. --}}
                @php $__acctUser = auth()->user(); @endphp

                @if(!$__acctUser)
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Aapki login session nahi mil rahi. Account details save karne se pehle please
                    <a href="{{ route('login') }}">dobara login</a> karein.
                </div>
                @endif

                <div class="settings-card mb-4">
                    <div class="settings-card-header"><i class="fas fa-user me-2"></i>Account Details</div>
                    <div class="settings-card-body">
                        <form action="{{ route('admin.settings.update') }}" method="POST">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="settings-label">Name</label>
                                    <input type="text" name="name" class="settings-input @error('name') is-invalid @enderror" value="{{ old('name', optional($__acctUser)->name) }}">
                                    @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="settings-label">Email</label>
                                    <input type="email" name="email" class="settings-input @error('email') is-invalid @enderror" value="{{ old('email', optional($__acctUser)->email) }}">
                                    @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn-save"><i class="fas fa-save me-2"></i>Update Account</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="settings-card">
                    <div class="settings-card-header"><i class="fas fa-lock me-2"></i>Change Password</div>
                    <div class="settings-card-body">
                        <form action="{{ route('admin.settings.password') }}" method="POST">
                            @csrf
                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="settings-label">Current Password</label>
                                    <input type="password" name="current_password" class="settings-input @error('current_password') is-invalid @enderror">
                                    @error('current_password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="settings-label">New Password</label>
                                    <input type="password" name="password" class="settings-input @error('password') is-invalid @enderror">
                                    @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="settings-label">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="settings-input">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn-save"><i class="fas fa-key me-2"></i>Update Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    :root {
        --pk:    #FF6B9D;
        --pk-h:  #E85588;
        --pk-lt: #fce4ec;
        --pk-bg: #fff0f7;
    }

    /* ── Sidebar tab card — white, light border, matches rest of admin panel ── */
    .settings-side {
        background:#fff;
        border:1px solid #ebebeb;
        border-radius:16px;
        padding:1rem;
        position:sticky;
        top:1rem;
    }

    .settings-tab {
        display: flex;
        align-items: center;
        width: 100%;
        background: transparent;
        color: #6b6b6b;
        border: none;
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 0.9rem;
        text-align: left;
        margin-bottom: 4px;
        transition: all 0.2s ease;
    }
    .settings-tab:hover {
        background: var(--pk-bg);
        color: var(--pk-h);
    }
    .settings-tab.active {
        background: var(--pk-lt);
        color: var(--pk-h);
        font-weight: 700;
        border-left: 3px solid var(--pk);
    }

    /* ── Cards — white, light border like notifications/appointments cards ── */
    .settings-card {
        background: #fff;
        border: 1px solid #ebebeb;
        border-radius: 16px;
        overflow: hidden;
    }
    .settings-card-header {
        padding: 1.1rem 1.5rem;
        font-weight: 700;
        color: #1a1a1a;
        border-bottom: 1px solid #f5f2ee;
        background: var(--pk-bg);
    }
    .settings-card-header i { color: var(--pk); }
    .settings-card-body { padding: 1.8rem; }
    @media (max-width:576px){ .settings-card-body { padding: 1.2rem; } }

    .settings-label {
        display: block;
        color: #555;
        font-weight: 600;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }
    .settings-input {
        width: 100%;
        background: #fafafa;
        border: 1.5px solid #e5e5e5;
        color: #1a1a1a;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.9rem;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        box-sizing: border-box;
    }
    .settings-input:focus {
        outline: none;
        border-color: var(--pk);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(255,107,157,0.12);
    }
    .settings-input::placeholder { color: #bbb; }
    .settings-input.is-invalid { border-color:#dc2626; }

    .btn-save {
        background: linear-gradient(135deg, var(--pk), var(--pk-h));
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 10px 28px;
        font-weight: 700;
        font-size: 0.9rem;
        box-shadow: 0 4px 14px rgba(255,107,157,.3);
        transition: all .18s ease;
    }
    .btn-save:hover { transform:translateY(-1px); box-shadow: 0 6px 18px rgba(255,107,157,.4); color: #fff; }

    .btn-outline-pk {
        background: transparent;
        border: 1.5px solid var(--pk-lt);
        color: var(--pk);
        border-radius: 10px;
        padding: 9px 24px;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all .18s ease;
    }
    .btn-outline-pk:hover { background: var(--pk-bg); border-color: var(--pk); color: var(--pk-h); }

    /* ── Small responsive touches ── */
    @media (max-width: 991px) {
        .settings-side { position: static; margin-bottom: 1rem; }
        .settings-side .nav.flex-column { flex-direction: row !important; flex-wrap: wrap; gap: 4px; }
        .settings-tab { width: auto; flex: 1 1 auto; justify-content: center; text-align: center; }
    }
    @media (max-width: 480px) {
        .settings-tab { font-size: 0.8rem; padding: 10px; }
        .settings-tab i { margin-right: 6px !important; }
    }
</style>

@endsection