@extends('layouts.admin')
@section('title', 'System Settings — Admin')

@section('content')

{{-- ✅ Guard: agar controller $generalSettings pass na kare to bhi page
     crash nahi hogi, field apni default value dikha degi. --}}
@php $generalSettings = $generalSettings ?? []; @endphp

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

    {{-- Tabs Sidebar --}}
    <div class="col-lg-3">
        <div class="settings-side">
            <div class="list-group list-group-flush">
                <a href="#general" class="settings-tab active" data-bs-toggle="tab">
                    <i class="fas fa-globe me-3"></i>General
                </a>
                <a href="#payment" class="settings-tab" data-bs-toggle="tab">
                    <i class="fas fa-credit-card me-3"></i>Payment
                </a>
                <a href="#email" class="settings-tab" data-bs-toggle="tab">
                    <i class="fas fa-envelope me-3"></i>Email
                </a>
                <a href="#social" class="settings-tab" data-bs-toggle="tab">
                    <i class="fas fa-share-alt me-3"></i>Social
                </a>
            </div>
        </div>
    </div>

    {{-- Content Area --}}
    <div class="col-lg-9">
        <div class="tab-content">

            {{-- General --}}
            <div class="tab-pane fade show active" id="general">
                <div class="settings-card">
                    <div class="settings-card-body">
                        <form action="{{ route('admin.system-settings.general') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="settings-label">Site Name</label>
                                    <input type="text" name="site_name" class="settings-input"
                                           value="{{ old('site_name', $generalSettings['site_name'] ?? 'Glamora') }}">
                                </div>
                                {{-- Add more fields as needed --}}
                                <div class="col-12">
                                    <button type="submit" class="btn-save">
                                        <i class="fas fa-save me-2"></i>Save Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Payment, Email, Social tabs can be added similarly --}}
            <div class="tab-pane fade" id="payment">
                <div class="settings-card">
                    <div class="settings-card-body">
                        <p style="color:#9a9a9a;margin:0;">Payment settings fields go here.</p>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="email">
                <div class="settings-card">
                    <div class="settings-card-body">
                        <p style="color:#9a9a9a;margin:0;">Email settings fields go here.</p>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="social">
                <div class="settings-card">
                    <div class="settings-card-body">
                        <p style="color:#9a9a9a;margin:0;">Social link fields go here.</p>
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
    .settings-side {
        background:#fff; border:1px solid #ebebeb; border-radius:16px;
        padding:1.2rem; position:sticky; top:1rem;
    }
    .settings-tab {
        display:flex; align-items:center; width:100%;
        background:transparent; color:#6b6b6b; border:none; text-decoration:none;
        border-radius:10px; padding:14px 16px; font-size:0.9rem; margin-bottom:4px;
        transition:all .2s ease;
    }
    .settings-tab:hover { background:var(--pk-bg); color:var(--pk-h); }
    .settings-tab.active { background:var(--pk-lt); color:var(--pk-h); font-weight:700; border-left:3px solid var(--pk); }

    .settings-card { background:#fff; border:1px solid #ebebeb; border-radius:16px; overflow:hidden; }
    .settings-card-body { padding:2rem; }
    @media (max-width:576px){ .settings-card-body { padding:1.2rem; } }

    .settings-label { display:block; color:#555; font-weight:600; font-size:0.85rem; margin-bottom:.5rem; }
    .settings-input {
        width:100%; background:#fafafa; border:1.5px solid #e5e5e5; color:#1a1a1a;
        border-radius:10px; padding:10px 14px; font-size:.9rem; box-sizing:border-box;
        transition:border-color .2s, box-shadow .2s, background .2s;
    }
    .settings-input:focus {
        outline:none; border-color:var(--pk); background:#fff;
        box-shadow:0 0 0 3px rgba(255,107,157,.12);
    }

    .btn-save {
        background:linear-gradient(135deg,var(--pk),var(--pk-h)); color:#fff; border:none;
        border-radius:10px; padding:10px 28px; font-weight:700; font-size:.9rem;
        box-shadow:0 4px 14px rgba(255,107,157,.3); transition:all .18s ease;
    }
    .btn-save:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(255,107,157,.4); color:#fff; }

    @media (max-width:991px) {
        .settings-side { position:static; margin-bottom:1rem; }
        .settings-side .list-group { flex-direction:row !important; flex-wrap:wrap; gap:4px; }
        .settings-tab { width:auto; flex:1 1 auto; justify-content:center; text-align:center; }
    }
</style>
@endsection