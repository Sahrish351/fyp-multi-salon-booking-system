@extends('layouts.admin')

@section('title', 'Audit Log Details — Glamora')

@push('styles')
<style>
    .detail-label {
        font-size: 0.7rem;
        font-weight: 600;
        color: #aaa;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    .detail-value {
        font-size: 0.95rem;
        color: #333;
        font-weight: 500;
    }
    .detail-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1rem;
        margin-top: 0.5rem;
    }
    .detail-card pre {
        margin: 0;
        font-size: 0.75rem;
        white-space: pre-wrap;
        word-break: break-all;
        max-height: 250px;
        overflow-y: auto;
    }
    .detail-card .old-data {
        border-left: 3px solid #ef4444;
        padding-left: 1rem;
    }
    .detail-card .new-data {
        border-left: 3px solid #22c55e;
        padding-left: 1rem;
    }
    .btn-back {
        background: #f0f0f0;
        color: #555;
        border: none;
        border-radius: 50px;
        padding: 8px 20px;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-back:hover {
        background: #e0e0e0;
        color: #333;
    }
    .badge-action {
        padding: 4px 14px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-status-success {
        background: #dcfce7;
        color: #16a34a;
        padding: 4px 14px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-status-failed {
        background: #fee2e2;
        color: #dc2626;
        padding: 4px 14px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-status-pending {
        background: #fef3c7;
        color: #d97706;
        padding: 4px 14px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .role-badge {
        padding: 2px 10px;
        border-radius: 50px;
        font-size: 0.6rem;
        font-weight: 600;
    }
    .role-admin {
        background: #fce4ec;
        color: #c2185b;
    }
    .role-owner {
        background: #e3f2fd;
        color: #0d47a1;
    }
    .role-client {
        background: #e8f5e9;
        color: #1b5e20;
    }
    .btn-outline-pink {
        background: transparent;
        color: #E91E8C;
        border: 1.5px solid #E91E8C;
        border-radius: 50px;
        padding: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s;
        width: 100%;
        cursor: pointer;
    }
    .btn-outline-pink:hover {
        background: #E91E8C;
        color: #fff;
    }
    .user-avatar-large {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #f0f0f0;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-row .label {
        color: #888;
        font-size: 0.85rem;
    }
    .info-row .value {
        color: #333;
        font-weight: 500;
        font-size: 0.85rem;
    }
    .card {
        border-radius: 16px;
        border: 1px solid #f0f0f0;
        overflow: hidden;
    }
    .card-header {
        background: #fff;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f0f0f0;
    }
    .card-title {
        font-weight: 600;
        color: #333;
        font-size: 0.95rem;
    }
</style>
@endpush

@section('content')

{{-- Back Button --}}
<div class="mb-4">
    <a href="{{ url('/admin/audit-logs') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to Audit Logs
    </a>
</div>

<div class="row g-4">
    {{-- Main Details --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="card-title">
                    <i class="fas fa-info-circle me-2" style="color:#E91E8C;"></i>Log Details
                </span>
                <span class="badge-action" style="background:{{ $log->action_color ?? '#6b7280' }}20;color:{{ $log->action_color ?? '#6b7280' }};">
                    {{ ucfirst($log->action) }}
                </span>
            </div>
            <div style="padding:1.5rem;">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="detail-label"><i class="fas fa-user me-1"></i>User</div>
                        <div class="detail-value d-flex align-items-center gap-3">
                            <img src="{{ $log->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($log->user->name ?? 'System').'&background=E91E8C&color=fff' }}"
                                 class="user-avatar-large">
                            <div>
                                <div style="font-weight:600;font-size:1rem;">{{ $log->user->name ?? 'System' }}</div>
                                <span class="role-badge role-{{ $log->user->role ?? 'client' }}">
                                    {{ $log->role_label ?? ucfirst($log->user->role ?? 'Client') }}
                                </span>
                                <div style="color:#aaa;font-size:0.8rem;">{{ $log->user->email ?? '' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label"><i class="fas fa-tag me-1"></i>Module</div>
                        <div class="detail-value">{{ $log->module ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label"><i class="fas fa-circle me-1"></i>Status</div>
                        <div class="detail-value">
                            <span class="badge-status-{{ $log->status ?? 'success' }}">
                                {{ ucfirst($log->status ?? 'Success') }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label"><i class="fas fa-clock me-1"></i>Timestamp</div>
                        <div class="detail-value">{{ $log->created_at->format('d M Y, h:i:s A') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label"><i class="fas fa-calendar me-1"></i>Time Ago</div>
                        <div class="detail-value">{{ $log->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label"><i class="fas fa-network-wired me-1"></i>IP Address</div>
                        <div class="detail-value" style="font-family:monospace;">{{ $log->ip_address ?? 'N/A' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="detail-label"><i class="fas fa-align-left me-1"></i>Description</div>
                        <div class="detail-value" style="background:#f8f9fa;padding:12px 16px;border-radius:10px;">
                            {{ $log->description ?? 'No description available' }}
                        </div>
                    </div>
                </div>

                {{-- Data Changes --}}
                @if(!empty($log->old_values) || !empty($log->new_values))
                <hr style="margin:1.5rem 0;">
                <div class="row g-4">
                    @if(!empty($log->old_values))
                    <div class="col-md-6">
                        <div class="detail-label" style="color:#ef4444;"><i class="fas fa-arrow-left me-1"></i>Old Values</div>
                        <div class="detail-card old-data">
                            <pre style="color:#dc2626;">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                    @endif
                    @if(!empty($log->new_values))
                    <div class="col-md-6">
                        <div class="detail-label" style="color:#22c55e;"><i class="fas fa-arrow-right me-1"></i>New Values</div>
                        <div class="detail-card new-data">
                            <pre style="color:#16a34a;">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                {{-- User Agent --}}
                @if($log->user_agent)
                <hr style="margin:1.5rem 0;">
                <div class="detail-label"><i class="fas fa-desktop me-1"></i>User Agent</div>
                <div class="detail-value" style="font-size:0.8rem;color:#888;word-break:break-all;background:#f8f9fa;padding:10px 14px;border-radius:8px;">
                    {{ $log->user_agent }}
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <span class="card-title">
                    <i class="fas fa-bolt me-2" style="color:#E91E8C;"></i>Quick Actions
                </span>
            </div>
            <div style="padding:1.5rem;">
                <button class="btn-outline-pink" onclick="copyLogDetails()">
                    <i class="fas fa-copy me-2"></i> Copy Log Details
                </button>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <span class="card-title">
                    <i class="fas fa-info-circle me-2" style="color:#E91E8C;"></i>Log Info
                </span>
            </div>
            <div style="padding:1.25rem;">
                <div class="info-row">
                    <span class="label">Log ID</span>
                    <span class="value">#{{ $log->id }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Action</span>
                    <span class="value">{{ ucfirst($log->action) }}</span>
                </div>
                <div class="info-row">
                    <span class="label">User</span>
                    <span class="value">{{ $log->user->name ?? 'System' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Role</span>
                    <span class="value">{{ $log->role_label ?? ucfirst($log->user->role ?? 'Client') }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Status</span>
                    <span class="value">
                        <span class="badge-status-{{ $log->status ?? 'success' }}" style="font-size:0.7rem;">
                            {{ ucfirst($log->status ?? 'Success') }}
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="label">Time Ago</span>
                    <span class="value">{{ $log->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function copyLogDetails() {
        const data = {
            id: {{ $log->id }},
            action: '{{ $log->action }}',
            user: '{{ $log->user->name ?? 'System' }}',
            role: '{{ $log->user->role ?? 'N/A' }}',
            status: '{{ $log->status ?? 'Success' }}',
            module: '{{ $log->module ?? 'N/A' }}',
            description: '{{ $log->description ?? 'No description' }}',
            timestamp: '{{ $log->created_at->format('d M Y, h:i:s A') }}',
            ip_address: '{{ $log->ip_address ?? 'N/A' }}',
            old_values: {!! json_encode($log->old_values ?? []) !!},
            new_values: {!! json_encode($log->new_values ?? []) !!}
        };

        navigator.clipboard.writeText(JSON.stringify(data, null, 2))
            .then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Copied!',
                    text: 'Log details copied to clipboard',
                    timer: 2000,
                    showConfirmButton: false
                });
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: 'Could not copy to clipboard',
                    timer: 2000,
                    showConfirmButton: false
                });
            });
    }
</script>

@endsection