@extends('layouts.owner')
 
@section('title', 'Settings')
 
@section('content')
 
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
 
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
 
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
 
    {{-- ============================================================ --}}
    {{-- PAGE HEADER --}}
    {{-- ============================================================ --}}
    <div class="page-header">
        <h2>Settings</h2>
        <p>Manage your salon settings and preferences</p>
    </div>
 
    {{-- ============================================================ --}}
    {{-- SETTINGS TABS --}}
    {{-- ============================================================ --}}
    <ul class="settings-tabs" id="settingsTabs">
        <li class="settings-tab active" data-target="profile">Profile Settings</li>
        <li class="settings-tab" data-target="security">Security</li>
        <li class="settings-tab" data-target="notifications">Notifications</li>
    </ul>
 
    {{-- ============================================================ --}}
    {{-- TAB 1: PROFILE SETTINGS --}}
    {{-- ============================================================ --}}
    <div class="settings-panel active" id="panel-profile">
        <form action="{{ route('owner.settings.profile') }}" method="POST">
            @csrf
            @method('PUT')
 
            <div class="panel-card">
                <div class="panel-title">
                    <i class="bi bi-person-fill me-2" style="color:#E85588;"></i> Profile Settings
                </div>
 
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label-custom">First Name</label>
                        <input type="text" name="name" class="form-control input-custom" 
                               value="{{ $user->name }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Last Name</label>
                        <input type="text" class="form-control input-custom" 
                               value="{{ explode(' ', $user->name)[1] ?? '' }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Email</label>
                        <input type="email" name="email" class="form-control input-custom" 
                               value="{{ $user->email }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Phone</label>
                        <input type="text" name="phone" class="form-control input-custom" 
                               value="{{ $user->phone ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Role</label>
                        <input type="text" class="form-control input-custom" 
                               value="Salon Owner" disabled style="font-weight:600; color:#E85588;">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Salon</label>
                        <input type="text" class="form-control input-custom" 
                               value="{{ $salon->name ?? 'No salon created' }}" disabled>
                    </div>
                </div>
 
                <div class="mt-4">
                    <button type="submit" class="btn btn-save-changes">
                        <i class="bi bi-check-circle-fill me-2"></i> Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
 
    {{-- ============================================================ --}}
    {{-- TAB 2: SECURITY --}}
    {{-- ============================================================ --}}
    <div class="settings-panel" id="panel-security">
        <form action="{{ route('owner.settings.password') }}" method="POST">
            @csrf
            @method('PUT')
 
            <div class="panel-card">
                <div class="panel-title">
                    <i class="bi bi-shield-lock-fill me-2" style="color:#E85588;"></i> Change Password
                </div>
 
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label-custom">Current Password</label>
                        <input type="password" name="current_password" class="form-control input-custom" 
                               placeholder="Enter current password" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">New Password</label>
                        <input type="password" name="new_password" class="form-control input-custom" 
                               placeholder="Enter new password" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="form-control input-custom" 
                               placeholder="Confirm new password" required>
                    </div>
                </div>
 
                <div class="mt-4">
                    <button type="submit" class="btn btn-save-changes">
                        <i class="bi bi-check-circle-fill me-2"></i> Update Password
                    </button>
                </div>
            </div>
        </form>
    </div>
 
    {{-- ============================================================ --}}
    {{-- TAB 3: NOTIFICATIONS --}}
    {{-- ============================================================ --}}
    <div class="settings-panel" id="panel-notifications">
        <form action="{{ route('owner.settings.notifications') }}" method="POST">
            @csrf
            @method('PUT')
 
            <div class="panel-card">
                <div class="panel-title">
                    <i class="bi bi-bell-fill me-2" style="color:#E85588;"></i> Notification Preferences
                </div>
 
                <div class="notification-preferences">
                    <div class="preference-group">
                        <h6 class="preference-group-title">Email Notifications</h6>
                        <div class="preference-item">
                            <div class="preference-info">
                                <span class="preference-label">Email notifications for new appointments</span>
                                <span class="preference-desc">Receive email when a client books an appointment</span>
                            </div>
                            <div class="form-check form-switch">
                                <input type="checkbox" name="email_appointments" class="form-check-input" 
                                       id="emailAppointments" {{ ($preferences['email_appointments'] ?? true) ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div class="preference-item">
                            <div class="preference-info">
                                <span class="preference-label">Email notifications for payments</span>
                                <span class="preference-desc">Receive email when a client makes a payment</span>
                            </div>
                            <div class="form-check form-switch">
                                <input type="checkbox" name="email_payments" class="form-check-input" 
                                       id="emailPayments" {{ ($preferences['email_payments'] ?? true) ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div class="preference-item">
                            <div class="preference-info">
                                <span class="preference-label">Email notifications for reviews</span>
                                <span class="preference-desc">Receive email when a client leaves a review</span>
                            </div>
                            <div class="form-check form-switch">
                                <input type="checkbox" name="email_reviews" class="form-check-input" 
                                       id="emailReviews" {{ ($preferences['email_reviews'] ?? true) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
 
                    <div class="preference-group">
                        <h6 class="preference-group-title">SMS Notifications</h6>
                        <div class="preference-item">
                            <div class="preference-info">
                                <span class="preference-label">SMS notifications for appointments</span>
                                <span class="preference-desc">Receive SMS when a client books an appointment</span>
                            </div>
                            <div class="form-check form-switch">
                                <input type="checkbox" name="sms_appointments" class="form-check-input" 
                                       id="smsAppointments" {{ ($preferences['sms_appointments'] ?? false) ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div class="preference-item">
                            <div class="preference-info">
                                <span class="preference-label">SMS notifications for payments</span>
                                <span class="preference-desc">Receive SMS when a client makes a payment</span>
                            </div>
                            <div class="form-check form-switch">
                                <input type="checkbox" name="sms_payments" class="form-check-input" 
                                       id="smsPayments" {{ ($preferences['sms_payments'] ?? false) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
 
                    <div class="preference-group">
                        <h6 class="preference-group-title">Reports</h6>
                        <div class="preference-item">
                            <div class="preference-info">
                                <span class="preference-label">Weekly performance reports</span>
                                <span class="preference-desc">Receive weekly performance summary via email</span>
                            </div>
                            <div class="form-check form-switch">
                                <input type="checkbox" name="weekly_reports" class="form-check-input" 
                                       id="weeklyReports" {{ ($preferences['weekly_reports'] ?? true) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>
 
                <div class="mt-4">
                    <button type="submit" class="btn btn-save-changes">
                        <i class="bi bi-check-circle-fill me-2"></i> Save Preferences
                    </button>
                </div>
            </div>
        </form>
    </div>
 
@endsection
 
@section('extra-css')
<style>
    /* ============================================================
       PAGE HEADER
    ============================================================ */
    .page-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d1f2c;
        margin-bottom: 0.25rem;
    }
    .page-header p {
        color: #8a7a88;
        margin-bottom: 0;
    }
 
    /* ============================================================
       SETTINGS TABS
    ============================================================ */
    .settings-tabs {
        display: flex;
        gap: 4px;
        list-style: none;
        padding: 0;
        margin: 0 0 24px 0;
        background: #fff;
        border-radius: 14px;
        border: 1px solid #f0e8ed;
        padding: 6px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        flex-wrap: wrap;
    }
    .settings-tab {
        padding: 10px 24px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        color: #8a7a88;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        background: none;
    }
    .settings-tab:hover {
        background: #fcf6f9;
        color: #2d1f2c;
    }
    .settings-tab.active {
        background: linear-gradient(135deg, #FF6B9D, #E85588);
        color: #fff;
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.3);
    }
 
    /* ============================================================
       SETTINGS PANELS
    ============================================================ */
    .settings-panel {
        display: none;
    }
    .settings-panel.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
 
    /* ============================================================
       PANEL CARD
    ============================================================ */
    .panel-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f0e8ed;
        height: auto;
    }
    .panel-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2d1f2c;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #f5eef2;
        display: flex;
        align-items: center;
    }
 
    /* ============================================================
       FORM
    ============================================================ */
    .form-label-custom {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #4a3a48;
        margin-bottom: 6px;
    }
    .input-custom {
        background: #fcf6f9 !important;
        border: 1px solid #f0e8ed !important;
        border-radius: 10px !important;
        color: #2d1f2c !important;
        font-size: 14px;
        padding: 11px 14px !important;
        width: 100%;
        transition: all 0.25s ease;
    }
    .input-custom:focus {
        background: #fff !important;
        border-color: #E85588 !important;
        box-shadow: 0 0 0 3px rgba(232, 85, 136, 0.15) !important;
        outline: none;
    }
    .input-custom:disabled {
        background: #f0e8ed !important;
        cursor: not-allowed;
        opacity: 0.7;
    }
 
    /* ============================================================
       SAVE BUTTON - PINK
    ============================================================ */
    .btn-save-changes {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        padding: 10px 26px;
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.35);
        display: inline-flex;
        align-items: center;
        transition: all 0.18s ease;
        font-size: 14px;
    }
    .btn-save-changes:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(232, 85, 136, 0.45);
        color: #ffffff !important;
    }
 
    /* ============================================================
       NOTIFICATION PREFERENCES
    ============================================================ */
    .notification-preferences {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .preference-group {
        border: 1px solid #f5eef2;
        border-radius: 12px;
        padding: 16px 18px;
        background: #fcf6f9;
    }
    .preference-group-title {
        font-size: 13px;
        font-weight: 700;
        color: #2d1f2c;
        margin: 0 0 12px 0;
        padding-bottom: 8px;
        border-bottom: 1px solid #f0e8ed;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .preference-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f5eef2;
    }
    .preference-item:last-child {
        border-bottom: none;
    }
    .preference-info {
        flex: 1;
    }
    .preference-label {
        font-size: 14px;
        font-weight: 600;
        color: #2d1f2c;
        display: block;
    }
    .preference-desc {
        font-size: 12px;
        color: #8a7a88;
        display: block;
        margin-top: 2px;
    }
 
    /* ============================================================
       TOGGLE SWITCH - PINK
    ============================================================ */
    .form-switch .form-check-input {
        width: 50px;
        height: 26px;
        background-color: #e0d8dd;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: 13px;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
    }
    .form-switch .form-check-input:checked {
        background: linear-gradient(135deg, #FF6B9D, #E85588);
        border-color: #E85588;
        box-shadow: 0 0 0 3px rgba(232, 85, 136, 0.2);
    }
    .form-switch .form-check-input:focus {
        box-shadow: 0 0 0 3px rgba(232, 85, 136, 0.15);
        border-color: #E85588;
    }
    .form-switch .form-check-input:checked::after {
        transform: translateX(24px);
    }
 
    /* ============================================================
       ALERTS
    ============================================================ */
    .alert {
        border-radius: 12px;
        border: none;
        padding: 12px 18px;
        margin-bottom: 20px;
    }
    .alert-success { background: #E8F5ED; color: #1B5E20; }
    .alert-danger { 
        background: #FCE4EC; 
        color: #880E4F; 
    }
    .alert ul {
        padding-left: 1.2rem;
        margin-bottom: 0;
    }
 
    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 768px) {
        .settings-tab {
            flex: 1;
            text-align: center;
            padding: 8px 12px;
            font-size: 12px;
        }
        .preference-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        .form-switch {
            align-self: flex-start;
        }
        .panel-card {
            padding: 1rem;
        }
    }
</style>
@endsection
 
@section('extra-js')
<script>
    // ============================================================
    // TABS
    // ============================================================
    document.querySelectorAll('.settings-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active from all tabs
            document.querySelectorAll('.settings-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
 
            // Hide all panels
            document.querySelectorAll('.settings-panel').forEach(p => p.classList.remove('active'));
 
            // Show target panel
            const target = this.dataset.target;
            document.getElementById('panel-' + target).classList.add('active');
        });
    });
 
    // ============================================================
    // AUTO DISMISS ALERTS
    // ============================================================
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.style.display = 'none', 500);
        }, 5000);
    });
</script>
@endsection