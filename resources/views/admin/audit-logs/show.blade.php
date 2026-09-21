@extends('layouts.admin')

@section('title', 'Audit Log Details — Beauty Blush Salons')

@push('styles')
<style>
    :root { --pk:#FF6B9D; --pk-dark:#E85588; --pk-lt:#fce4ec; --pk-bg:#fff0f7; }

    .al-page { width:0; min-width:100%; max-width:100%; box-sizing:border-box; }

    .al-back {
        display:inline-flex; align-items:center; gap:8px; padding:.5rem 1.2rem; margin-bottom:1.4rem;
        border-radius:50px; background:#fff; color:var(--pk); border:1.5px solid var(--pk-lt);
        font-weight:700; font-size:.84rem; text-decoration:none; transition:all .18s;
        box-shadow:0 2px 5px rgba(255,107,157,.06);
    }
    .al-back:hover { background:var(--pk); color:#fff; border-color:var(--pk); transform:translateY(-1px); }

    /* two columns without Bootstrap */
    .al-layout { display:grid; grid-template-columns:minmax(0,2fr) minmax(0,1fr); gap:1.4rem; align-items:start; }
    @media (max-width: 1000px) { .al-layout { grid-template-columns:minmax(0,1fr); } }
    .al-side { display:flex; flex-direction:column; gap:1.4rem; }

    .al-card {
        background:#fff; border:1px solid #eaeaea; border-radius:18px; overflow:hidden;
        box-shadow:0 4px 15px rgba(0,0,0,.03); min-width:0;
    }
    .al-card-head {
        padding:1.05rem 1.4rem; border-bottom:1px solid #f2f2f2; background:#fafbfc;
        display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:.5rem;
    }
    .al-card-head .t { font-weight:800; font-size:.95rem; color:#1a1a1a; display:inline-flex; align-items:center; gap:8px; }
    .al-card-head .t i { color:var(--pk); }
    .al-card-body { padding:1.4rem; }

    .al-grid { display:grid; grid-template-columns:repeat(2, minmax(0,1fr)); gap:1.3rem 1.5rem; }
    @media (max-width: 600px) { .al-grid { grid-template-columns:minmax(0,1fr); } }
    .al-full { grid-column:1 / -1; }

    .al-lbl { display:flex; align-items:center; gap:6px; font-size:.68rem; font-weight:700; color:#a3a3a3; text-transform:uppercase; letter-spacing:.06em; margin-bottom:6px; }
    .al-lbl i { color:var(--pk); }
    .al-val { font-size:.92rem; color:#222; font-weight:600; word-break:break-word; }
    .al-mono { font-family:monospace; }

    .al-userbox { display:flex; align-items:center; gap:14px; }
    .al-avatar-lg { width:54px; height:54px; border-radius:50%; object-fit:cover; border:3px solid var(--pk-lt); flex-shrink:0; }
    .al-uname { font-size:1.02rem; font-weight:800; color:#111; margin-bottom:4px; }
    .al-uemail { color:#9ca3af; font-size:.8rem; font-weight:500; margin-top:4px; }

    .al-role { display:inline-block; padding:2px 11px; border-radius:50px; font-size:.62rem; font-weight:700; background:#f1f5f9; color:#475569; }
    .al-role-admin { background:#fce4ec; color:#E85588; }
    .al-role-owner, .al-role-salon_owner { background:#e3f2fd; color:#0d47a1; }
    .al-role-client { background:#e8f5e9; color:#1b5e20; }

    .al-action { display:inline-block; padding:5px 15px; border-radius:50px; font-size:.75rem; font-weight:700; }
    .al-st { display:inline-block; padding:5px 15px; border-radius:50px; font-size:.75rem; font-weight:700; background:#f1f5f9; color:#475569; }
    .al-st-success { background:#dcfce7; color:#16a34a; }
    .al-st-failed  { background:#fee2e2; color:#dc2626; }
    .al-st-pending { background:#fef3c7; color:#d97706; }

    .al-desc { background:var(--pk-bg); border:1px solid var(--pk-lt); border-radius:14px; padding:1rem 1.2rem; line-height:1.7; font-weight:500; color:#333; font-size:.9rem; }

    .al-divider { border:none; border-top:1px solid #f2f2f2; margin:1.6rem 0; }

    .al-code { border-radius:12px; padding:1rem 1.1rem; background:#f8f9fa; }
    .al-code pre { margin:0; font-size:.75rem; white-space:pre-wrap; word-break:break-all; max-height:260px; overflow-y:auto; font-family:monospace; }
    .al-code-old { border-left:4px solid #ef4444; }
    .al-code-old pre { color:#dc2626; }
    .al-code-new { border-left:4px solid #22c55e; }
    .al-code-new pre { color:#16a34a; }

    .al-agent { font-size:.8rem; color:#777; word-break:break-all; background:#f8f9fa; padding:.8rem 1rem; border-radius:10px; line-height:1.6; }

    .al-copy {
        width:100%; display:inline-flex; align-items:center; justify-content:center; gap:8px; padding:.75rem 1rem;
        border-radius:50px; border:none; cursor:pointer; color:#fff; font-weight:700; font-size:.86rem;
        background:linear-gradient(135deg,var(--pk),var(--pk-dark)); box-shadow:0 4px 14px rgba(255,107,157,.32);
        transition:all .18s ease; font-family:inherit;
    }
    .al-copy:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(255,107,157,.42); }

    .al-info-row { display:flex; justify-content:space-between; align-items:center; gap:10px; padding:.75rem 0; border-bottom:1px solid #f4f4f4; }
    .al-info-row:last-child { border-bottom:none; }
    .al-info-row .k { color:#888; font-size:.84rem; }
    .al-info-row .v { color:#222; font-weight:600; font-size:.84rem; text-align:right; }

    /* small message box (replaces SweetAlert, which is not loaded in the layout) */
    .al-toast {
        position:fixed; right:24px; bottom:24px; z-index:9999; display:flex; align-items:center; gap:10px;
        padding:.85rem 1.3rem; border-radius:14px; background:#fff; font-size:.86rem; font-weight:700; color:#222;
        box-shadow:0 10px 30px rgba(0,0,0,.15); border-left:5px solid #16a34a;
        opacity:0; transform:translateY(15px); pointer-events:none; transition:all .25s ease;
    }
    .al-toast.show { opacity:1; transform:translateY(0); }
    .al-toast.err { border-left-color:#dc2626; }
    .al-toast i { color:#16a34a; }
    .al-toast.err i { color:#dc2626; }
</style>
@endpush

@section('content')
@php
    $roleKey     = $log->user->role ?? 'client';
    $status      = $log->status ?? 'success';
    $roleText    = $log->role_label ?? ucfirst($log->user->role ?? 'Client');
    $actionColor = $log->action_color ?? '#6b7280';
    $userName    = $log->user->name ?? 'System';
    $fmt = fn ($v) => is_string($v) ? $v : json_encode($v, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    $copyData = [
        'id'          => $log->id,
        'action'      => $log->action,
        'user'        => $userName,
        'role'        => $log->user->role ?? 'N/A',
        'status'      => $status,
        'module'      => $log->module ?? 'N/A',
        'description' => $log->description ?? 'No description',
        'timestamp'   => $log->created_at->format('d M Y, h:i:s A'),
        'ip_address'  => $log->ip_address ?? 'N/A',
        'old_values'  => $log->old_values ?? [],
        'new_values'  => $log->new_values ?? [],
    ];
@endphp

<div class="al-page">

    <a href="{{ route('admin.audit-logs.index') }}" class="al-back">
        <i class="fas fa-arrow-left"></i> Back to Audit Logs
    </a>

    <div class="al-layout">

        {{-- MAIN DETAILS --}}
        <div class="al-card">
            <div class="al-card-head">
                <span class="t"><i class="fas fa-info-circle"></i> Log Details</span>
                <span class="al-action" style="background:{{ $actionColor }}20;color:{{ $actionColor }};">{{ ucfirst($log->action) }}</span>
            </div>

            <div class="al-card-body">
                <div class="al-grid">

                    <div class="al-full">
                        <div class="al-lbl"><i class="fas fa-user"></i> User</div>
                        <div class="al-userbox">
                            <img class="al-avatar-lg"
                                 src="{{ $log->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($userName).'&background=FF6B9D&color=fff' }}"
                                 onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($userName) }}&background=FF6B9D&color=fff';"
                                 alt="">
                            <div>
                                <div class="al-uname">{{ $userName }}</div>
                                <span class="al-role al-role-{{ $roleKey }}">{{ $roleText }}</span>
                                @if(!empty($log->user->email))
                                    <div class="al-uemail">{{ $log->user->email }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="al-lbl"><i class="fas fa-tag"></i> Module</div>
                        <div class="al-val">{{ $log->module ?? '—' }}</div>
                    </div>

                    <div>
                        <div class="al-lbl"><i class="fas fa-circle"></i> Status</div>
                        <div class="al-val"><span class="al-st al-st-{{ $status }}">{{ ucfirst($status) }}</span></div>
                    </div>

                    <div>
                        <div class="al-lbl"><i class="fas fa-clock"></i> Timestamp</div>
                        <div class="al-val">{{ $log->created_at->format('d M Y, h:i:s A') }}</div>
                    </div>

                    <div>
                        <div class="al-lbl"><i class="fas fa-calendar"></i> Time Ago</div>
                        <div class="al-val">{{ $log->created_at->diffForHumans() }}</div>
                    </div>

                    <div class="al-full">
                        <div class="al-lbl"><i class="fas fa-network-wired"></i> IP Address</div>
                        <div class="al-val al-mono">{{ $log->ip_address ?? 'N/A' }}</div>
                    </div>

                    <div class="al-full">
                        <div class="al-lbl"><i class="fas fa-align-left"></i> Description</div>
                        <div class="al-desc">{{ $log->description ?? 'No description available' }}</div>
                    </div>
                </div>

                {{-- Data changes --}}
                @if(!empty($log->old_values) || !empty($log->new_values))
                    <hr class="al-divider">
                    <div class="al-grid">
                        @if(!empty($log->old_values))
                            <div>
                                <div class="al-lbl" style="color:#ef4444;"><i class="fas fa-arrow-left" style="color:#ef4444;"></i> Old Values</div>
                                <div class="al-code al-code-old"><pre>{{ $fmt($log->old_values) }}</pre></div>
                            </div>
                        @endif
                        @if(!empty($log->new_values))
                            <div>
                                <div class="al-lbl" style="color:#22c55e;"><i class="fas fa-arrow-right" style="color:#22c55e;"></i> New Values</div>
                                <div class="al-code al-code-new"><pre>{{ $fmt($log->new_values) }}</pre></div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- User agent --}}
                @if($log->user_agent)
                    <hr class="al-divider">
                    <div class="al-lbl"><i class="fas fa-desktop"></i> User Agent</div>
                    <div class="al-agent">{{ $log->user_agent }}</div>
                @endif
            </div>
        </div>

        {{-- SIDEBAR --}}
        <div class="al-side">
            <div class="al-card">
                <div class="al-card-head">
                    <span class="t"><i class="fas fa-bolt"></i> Quick Actions</span>
                </div>
                <div class="al-card-body">
                    <button type="button" class="al-copy" onclick="copyLogDetails()">
                        <i class="fas fa-copy"></i> Copy Log Details
                    </button>
                </div>
            </div>

            <div class="al-card">
                <div class="al-card-head">
                    <span class="t"><i class="fas fa-info-circle"></i> Log Info</span>
                </div>
                <div class="al-card-body" style="padding-top:.6rem;padding-bottom:.6rem;">
                    <div class="al-info-row"><span class="k">Log ID</span><span class="v">#{{ $log->id }}</span></div>
                    <div class="al-info-row"><span class="k">Action</span><span class="v">{{ ucfirst($log->action) }}</span></div>
                    <div class="al-info-row"><span class="k">User</span><span class="v">{{ $userName }}</span></div>
                    <div class="al-info-row"><span class="k">Role</span><span class="v">{{ $roleText }}</span></div>
                    <div class="al-info-row"><span class="k">Status</span><span class="v"><span class="al-st al-st-{{ $status }}" style="font-size:.7rem;padding:3px 12px;">{{ ucfirst($status) }}</span></span></div>
                    <div class="al-info-row"><span class="k">Time Ago</span><span class="v">{{ $log->created_at->diffForHumans() }}</span></div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="al-toast" id="alToast"><i class="fas fa-check-circle"></i><span id="alToastText"></span></div>
@endsection

@push('scripts')
<script>
    const logData = @json($copyData);

    function alToast(message, isError) {
        const box  = document.getElementById('alToast');
        const icon = box.querySelector('i');
        document.getElementById('alToastText').textContent = message;
        box.classList.toggle('err', !!isError);
        icon.className = isError ? 'fas fa-times-circle' : 'fas fa-check-circle';
        box.classList.add('show');
        setTimeout(() => box.classList.remove('show'), 2200);
    }

    function copyLogDetails() {
        const text = JSON.stringify(logData, null, 2);
        const ok   = () => alToast('Log details copied to clipboard', false);
        const fail = () => alToast('Could not copy to clipboard', true);

        function fallbackCopy() {
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.opacity = '0';
            document.body.appendChild(ta);
            ta.select();
            try { document.execCommand('copy') ? ok() : fail(); } catch (e) { fail(); }
            document.body.removeChild(ta);
        }

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(ok).catch(fallbackCopy);
        } else {
            fallbackCopy();
        }
    }
</script>
@endpush