@extends('layouts.admin')
@section('title', 'Add New Salon - Glamora')

@push('styles')
<style>
    :root {
        --gl-pink: #E0177D;
        --gl-pink-dark: #B5125F;
        --gl-pink-light: #FDEAF3;
        --gl-pink-pale: #F1DCE9;
        --gl-text: #2B2230;
        --gl-text-lt: #B98BA6;
        --gl-border: #F1DCE9;
    }

    /* ── Page Header ── */
    .page-header-row { display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; margin-bottom: 26px; }
    .page-header-row h1 { font-size: 1.6rem; font-weight: 800; color: var(--gl-text); margin: 0; }
    .page-header-row p  { font-size: 0.88rem; color: var(--gl-text-lt); margin: 6px 0 0; }

    /* ── Buttons ── */
    .btn-back {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 20px; border-radius: 12px;
        border: 1.5px solid var(--gl-pink-pale);
        background: #fff; color: var(--gl-pink);
        font-size: 0.85rem; font-weight: 700;
        text-decoration: none; transition: all 0.2s ease;
        white-space: nowrap;
    }
    .btn-back:hover { background: var(--gl-pink-light); border-color: var(--gl-pink); color: var(--gl-pink); }

    .btn-save {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 11px 24px; border-radius: 12px; border: none;
        background: linear-gradient(135deg, var(--gl-pink), var(--gl-pink-dark));
        color: #fff; font-size: 0.88rem; font-weight: 700;
        cursor: pointer; text-decoration: none;
        box-shadow: 0 6px 16px rgba(224,23,125,0.3);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        white-space: nowrap;
    }
    .btn-save:hover { transform: translateY(-2px); box-shadow: 0 10px 22px rgba(224,23,125,0.4); color: #fff; }

    .btn-cancel {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 11px 22px; border-radius: 12px;
        border: none;
        background: #C0392B; color: #fff;
        font-size: 0.88rem; font-weight: 700;
        text-decoration: none; cursor: pointer;
        transition: all 0.2s ease; white-space: nowrap;
    }
    .btn-cancel:hover { background: #A93226; color: #fff; }

    /* ── Card ── */
    .gl-card { background: #fff; border-radius: 20px; border: 1px solid var(--gl-border); box-shadow: 0 2px 12px rgba(224,23,125,0.06); overflow: hidden; margin-bottom: 24px; }
    .gl-card-header { display: flex; align-items: center; gap: 10px; padding: 18px 26px; border-bottom: 1px solid var(--gl-border); background: var(--gl-pink-light); }
    .gl-card-header i { color: var(--gl-pink); font-size: 1rem; }
    .gl-card-header span { font-size: 0.95rem; font-weight: 800; color: var(--gl-text); }
    .gl-card-body { padding: 26px; }

    /* ── Form Fields ── */
    .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px 24px; }
    .form-grid .full { grid-column: 1 / -1; }

    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-label {
        font-size: 0.72rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: 0.7px; color: var(--gl-text-lt);
    }
    .form-label span { color: var(--gl-pink); margin-left: 2px; }

    .form-control, .form-select {
        width: 100%; padding: 10px 14px;
        border: 1.5px solid var(--gl-border);
        border-radius: 12px; font-size: 0.88rem;
        color: var(--gl-text); background: #fff;
        outline: none; transition: border-color 0.2s ease, box-shadow 0.2s ease;
        box-sizing: border-box; appearance: none; -webkit-appearance: none;
        font-family: inherit;
    }
    .form-control::placeholder { color: var(--gl-text-lt); }
    .form-control:focus, .form-select:focus {
        border-color: var(--gl-pink);
        box-shadow: 0 0 0 3px rgba(224,23,125,0.1);
    }
    .form-control.is-invalid, .form-select.is-invalid { border-color: #D93025; }
    .invalid-feedback { font-size: 0.78rem; color: #D93025; font-weight: 600; margin-top: 2px; }

    textarea.form-control { resize: vertical; min-height: 110px; line-height: 1.6; }

    .form-select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24'%3E%3Cpath fill='%23B98BA6' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 12px center;
        padding-right: 36px; cursor: pointer;
    }

    /* ── File Upload ── */
    .file-upload-wrap { display: flex; flex-direction: column; gap: 8px; }
    .file-input {
        width: 100%; padding: 10px 14px;
        border: 1.5px dashed var(--gl-pink-pale);
        border-radius: 12px; font-size: 0.85rem;
        color: var(--gl-text); background: var(--gl-pink-light);
        cursor: pointer; box-sizing: border-box;
        transition: border-color 0.2s ease;
    }
    .file-input:hover { border-color: var(--gl-pink); }
    .file-hint { font-size: 0.75rem; color: var(--gl-text-lt); }

    .logo-preview-box { display: none; align-items: center; gap: 14px; margin-top: 6px; }
    .logo-preview-box img { width: 80px; height: 80px; object-fit: cover; border-radius: 12px; border: 2px solid var(--gl-border); }
    .btn-remove-logo {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 14px; border-radius: 10px;
        border: none; background: #FFF5F5;
        color: #C0392B; font-size: 0.78rem; font-weight: 700;
        cursor: pointer; transition: all 0.2s ease;
    }
    .btn-remove-logo:hover { background: #FCE8E6; }

    /* ── Alert ── */
    .gl-alert {
        display: flex; gap: 12px; padding: 14px 18px;
        border-radius: 14px; background: #FCE8E6;
        border: none; margin-bottom: 22px;
    }
    .gl-alert i { color: #D93025; margin-top: 2px; flex-shrink: 0; }
    .gl-alert-title { font-size: 0.85rem; font-weight: 800; color: #C0392B; margin-bottom: 6px; }
    .gl-alert ul { margin: 0; padding-left: 18px; }
    .gl-alert ul li { font-size: 0.82rem; color: #C0392B; }

    /* ── Divider ── */
    .gl-divider { border: none; border-top: 1px solid var(--gl-border); margin: 24px 0; }

    /* ── Footer Actions ── */
    .form-actions { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; }

    @media (max-width: 640px) {
        .form-grid { grid-template-columns: 1fr; }
        .form-grid .full { grid-column: 1; }
        .page-header-row { flex-direction: column; align-items: stretch; }
        .btn-back { justify-content: center; }
    }
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="page-header-row">
    <div>
        <h1>Add New Salon</h1>
        <p>Fill in the details below to register a new salon</p>
    </div>
    <a href="{{ route('admin.salons.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to Salons
    </a>
</div>

<div class="gl-card">
    <div class="gl-card-header">
        <i class="fas fa-plus-circle"></i>
        <span>Salon Information</span>
    </div>
    <div class="gl-card-body">

        {{-- Errors --}}
        @if($errors->any())
        <div class="gl-alert">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <div class="gl-alert-title">Please fix the following errors:</div>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <form action="{{ route('admin.salons.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-grid">

                {{-- Salon Name --}}
                <div class="form-group">
                    <label class="form-label">Salon Name <span>*</span></label>
                    <input type="text" name="name" id="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" placeholder="e.g., Glamora Gulberg" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Owner --}}
                <div class="form-group">
                    <label class="form-label">Owner <span>*</span></label>
                    <select name="owner_id" id="owner_id"
                        class="form-select @error('owner_id') is-invalid @enderror" required>
                        <option value="">— Select Owner —</option>
                        @foreach($owners as $owner)
                            <option value="{{ $owner->id }}" {{ old('owner_id') == $owner->id ? 'selected' : '' }}>
                                {{ $owner->name }} ({{ $owner->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('owner_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- City --}}
                <div class="form-group">
                    <label class="form-label">City <span>*</span></label>
                    <input type="text" name="city" id="city"
                        class="form-control @error('city') is-invalid @enderror"
                        value="{{ old('city') }}" placeholder="e.g., Lahore" required>
                    @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Area --}}
                <div class="form-group">
                    <label class="form-label">Area <span>*</span></label>
                    <input type="text" name="area" id="area"
                        class="form-control @error('area') is-invalid @enderror"
                        value="{{ old('area') }}" placeholder="e.g., Gulberg III" required>
                    @error('area')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Address --}}
                <div class="form-group full">
                    <label class="form-label">Full Address <span>*</span></label>
                    <input type="text" name="address" id="address"
                        class="form-control @error('address') is-invalid @enderror"
                        value="{{ old('address') }}" placeholder="Complete street address" required>
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Phone --}}
                <div class="form-group">
                    <label class="form-label">Phone <span>*</span></label>
                    <input type="text" name="phone" id="phone"
                        class="form-control @error('phone') is-invalid @enderror"
                        value="{{ old('phone') }}" placeholder="e.g., 0300-1234567" required>
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Email --}}
                <div class="form-group">
                    <label class="form-label">Email <span>*</span></label>
                    <input type="email" name="email" id="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" placeholder="salon@example.com" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Description --}}
                <div class="form-group full">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="description"
                        class="form-control @error('description') is-invalid @enderror"
                        placeholder="Describe the salon, services, ambiance...">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Logo --}}
                <div class="form-group full">
                    <label class="form-label">Logo</label>
                    <div class="file-upload-wrap">
                        <input type="file" name="logo" id="logo"
                            class="file-input @error('logo') is-invalid @enderror"
                            accept="image/*">
                        <span class="file-hint"><i class="fas fa-info-circle"></i> Supported: JPG, PNG, GIF — Max 2MB</span>
                        @error('logo')<div class="invalid-feedback" style="display:block;">{{ $message }}</div>@enderror
                        <div class="logo-preview-box" id="logoPreview">
                            <img id="logoPreviewImg" src="#" alt="Preview">
                            <button type="button" class="btn-remove-logo" onclick="clearLogoPreview()">
                                <i class="fas fa-times"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <hr class="gl-divider">

            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Create Salon
                </button>
                <a href="{{ route('admin.salons.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('logo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                document.getElementById('logoPreviewImg').src = ev.target.result;
                document.getElementById('logoPreview').style.display = 'flex';
            }
            reader.readAsDataURL(file);
        }
    });
    function clearLogoPreview() {
        document.getElementById('logoPreview').style.display = 'none';
        document.getElementById('logo').value = '';
    }
</script>
@endpush

@endsection