@extends('layouts.admin')
@section('title', 'System Settings — Admin')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold text-white">
        <i class="fas fa-cog me-2" style="color:#3b82f6;"></i> System Settings
    </h4>
    <p style="color:rgba(255,255,255,0.4);">Manage platform configuration and preferences</p>
</div>

<div class="row g-4">
    <!-- Tabs Sidebar -->
    <div class="col-lg-3">
        <div style="background:#0f172a;border:1px solid rgba(59,130,246,0.15);border-radius:16px;padding:1.2rem;">
            <div class="list-group list-group-flush">
                <a href="#general" class="list-group-item list-group-item-action active" data-bs-toggle="tab" style="background:transparent;color:#fff;border:none;padding:14px 16px;">
                    <i class="fas fa-globe me-3"></i>General
                </a>
                <a href="#payment" class="list-group-item list-group-item-action" data-bs-toggle="tab" style="background:transparent;color:#fff;border:none;padding:14px 16px;">
                    <i class="fas fa-credit-card me-3"></i>Payment
                </a>
                <a href="#email" class="list-group-item list-group-item-action" data-bs-toggle="tab" style="background:transparent;color:#fff;border:none;padding:14px 16px;">
                    <i class="fas fa-envelope me-3"></i>Email
                </a>
                <a href="#social" class="list-group-item list-group-item-action" data-bs-toggle="tab" style="background:transparent;color:#fff;border:none;padding:14px 16px;">
                    <i class="fas fa-share-alt me-3"></i>Social
                </a>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <div class="col-lg-9">
        <div class="tab-content">

            <!-- General -->
            <div class="tab-pane fade show active" id="general">
                <div style="background:#0f172a;border:1px solid rgba(59,130,246,0.15);border-radius:16px;padding:2rem;">
                    <form action="{{ route('admin.system-settings.general') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="text-white fw-semibold mb-2">Site Name</label>
                                <input type="text" name="site_name" class="form-control" value="{{ old('site_name', $generalSettings['site_name'] ?? 'Glamora') }}" 
                                       style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);color:#fff;">
                            </div>
                            <!-- Add more fields as needed -->
                            <div class="col-12">
                                <button type="submit" class="btn px-5" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8);color:#fff;">
                                    <i class="fas fa-save me-2"></i>Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payment, Email, Social tabs can be added similarly -->

        </div>
    </div>
</div>
@endsection