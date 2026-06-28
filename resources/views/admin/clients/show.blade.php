@extends('layouts.admin')
@section('title', 'Client Details - ' . $client->name)

@section('content')

<style>
/* ── Back Button ── */
.btn-back {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .55rem 1.1rem;
    border: 1.5px solid var(--border, #e5e7eb);
    border-radius: 8px;
    font-size: .88rem;
    font-weight: 600;
    color: var(--muted, #6b7280);
    text-decoration: none;
    transition: all .15s;
    margin-bottom: 1.75rem;
    background: #fff;
}
.btn-back:hover {
    border-color: var(--brown, #8b5e3c);
    color: var(--brown, #8b5e3c);
}

/* ── Layout Grid ── */
.detail-grid {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 1.5rem;
    align-items: start;
}
@media (max-width: 900px) {
    .detail-grid { grid-template-columns: 1fr; }
}

/* ── Card Base ── */
.detail-card {
    background: #fff;
    border: 1px solid var(--border, #e5e7eb);
    border-radius: 14px;
    overflow: hidden;
}
.detail-card + .detail-card { margin-top: 1.25rem; }
.detail-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border, #f3f4f6);
}
.detail-card-title {
    font-weight: 700;
    font-size: .95rem;
    color: var(--heading, #111827);
}
.detail-card-body { padding: 1.5rem; }

/* ── Profile Card (left column) ── */
.profile-card { text-align: center; }
.profile-avatar-wrap {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    background: var(--brown-lt, #f5ede6);
    color: var(--brown, #8b5e3c);
    font-size: 2.2rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.1rem;
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px var(--brown-lt, #f0e4da);
}
.profile-name {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--heading, #111827);
    margin-bottom: .25rem;
}
.profile-email {
    font-size: .87rem;
    color: var(--muted, #6b7280);
    margin-bottom: .85rem;
}

/* ── Badge ── */
.badge {
    display: inline-block;
    padding: .3rem .8rem;
    border-radius: 20px;
    font-size: .75rem;
    font-weight: 700;
    letter-spacing: .03em;
}
.badge-success { background: #d1fae5; color: #065f46; }
.badge-danger  { background: #fee2e2; color: #991b1b; }
.badge-warning { background: #fef3c7; color: #92400e; }
.badge-info    { background: #dbeafe; color: #1e40af; }

/* ── Stats Row ── */
.stats-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: .5rem;
    margin: 1.25rem 0;
    padding: 1rem 0;
    border-top: 1px solid var(--border, #f3f4f6);
    border-bottom: 1px solid var(--border, #f3f4f6);
}
.stat-item { text-align: center; }
.stat-value {
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--brown, #8b5e3c);
    line-height: 1;
    margin-bottom: .2rem;
}
.stat-label {
    font-size: .72rem;
    color: var(--muted, #9ca3af);
    text-transform: uppercase;
    letter-spacing: .05em;
    font-weight: 600;
}

/* ── Toggle Button ── */
.btn-toggle-suspend {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: .45rem;
    width: 100%;
    padding: .7rem 1rem;
    border-radius: 9px;
    font-size: .9rem;
    font-weight: 700;
    cursor: pointer;
    border: none;
    transition: opacity .18s, transform .1s;
    margin-top: .25rem;
}
.btn-toggle-suspend:hover { opacity: .88; transform: translateY(-1px); }
.btn-suspend  { background: #fee2e2; color: #b91c1c; }
.btn-activate { background: #d1fae5; color: #065f46; }

/* ── Info Grid ── */
.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
}
@media (max-width: 600px) { .info-grid { grid-template-columns: 1fr; } }
.info-item label {
    display: block;
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--muted, #9ca3af);
    margin-bottom: .3rem;
}
.info-item p {
    margin: 0;
    font-size: .93rem;
    color: var(--text, #374151);
    font-weight: 500;
}

/* ── Appointments List ── */
.appt-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border, #f3f4f6);
    cursor: pointer;
    transition: background .15s;
    gap: 1rem;
}
.appt-item:last-child { border-bottom: none; }
.appt-item:hover { background: var(--bg-hover, #faf7f5); }
.appt-salon {
    font-weight: 600;
    font-size: .93rem;
    color: var(--heading, #111827);
    margin-bottom: .2rem;
}
.appt-meta {
    font-size: .8rem;
    color: var(--muted, #9ca3af);
}
.appt-right {
    text-align: right;
    flex-shrink: 0;
}
.appt-amount {
    font-weight: 700;
    font-size: .9rem;
    color: var(--brown, #8b5e3c);
    margin-top: .3rem;
}

/* ── Empty State ── */
.empty-mini {
    text-align: center;
    padding: 2.5rem 1rem;
    color: var(--muted, #9ca3af);
    font-size: .9rem;
}
.empty-mini i { font-size: 1.8rem; display: block; margin-bottom: .6rem; opacity: .35; }

/* ── View All Link ── */
.view-all-link {
    display: block;
    text-align: center;
    padding: .85rem;
    font-size: .85rem;
    font-weight: 600;
    color: var(--brown, #8b5e3c);
    border-top: 1px solid var(--border, #f3f4f6);
    text-decoration: none;
    transition: background .15s;
}
.view-all-link:hover { background: var(--brown-lt, #fdf6f2); }
</style>

{{-- ── Back ── --}}
<a href="{{ route('admin.clients.index') }}" class="btn-back">
    <i class="fas fa-arrow-left"></i> Back to Clients
</a>

<div class="detail-grid">

    {{-- ══ LEFT COLUMN ══ --}}
    <div>

        {{-- Profile Card --}}
        <div class="detail-card profile-card">
            <div class="detail-card-body" style="padding:2rem 1.5rem;">

                <div class="profile-avatar-wrap">
                    {{ strtoupper(substr($client->name, 0, 1)) }}
                </div>
                <div class="profile-name">{{ $client->name }}</div>
                <div class="profile-email">{{ $client->email }}</div>

                <span class="badge {{ $client->is_active ? 'badge-success' : 'badge-danger' }}">
                    <i class="fas {{ $client->is_active ? 'fa-circle-check' : 'fa-ban' }}" style="font-size:.65rem;"></i>
                    {{ $client->is_active ? 'Active' : 'Suspended' }}
                </span>

                {{-- Stats --}}
                <div class="stats-row">
                    <div class="stat-item">
                        <div class="stat-value">{{ $client->appointments->count() }}</div>
                        <div class="stat-label">Bookings</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $client->reviews->count() }}</div>
                        <div class="stat-label">Reviews</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">0</div>
                        <div class="stat-label">Favourites</div>
                    </div>
                </div>

                {{-- Toggle Suspend / Activate --}}
                <form action="{{ route('admin.clients.toggle', $client->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="btn-toggle-suspend {{ $client->is_active ? 'btn-suspend' : 'btn-activate' }}"
                        onclick="return confirm('{{ $client->is_active ? 'Suspend this client?' : 'Activate this client?' }}')">
                        <i class="fas {{ $client->is_active ? 'fa-ban' : 'fa-circle-check' }}"></i>
                        {{ $client->is_active ? 'Suspend Client' : 'Activate Client' }}
                    </button>
                </form>

            </div>
        </div>

    </div>
    {{-- ══ END LEFT ══ --}}

    {{-- ══ RIGHT COLUMN ══ --}}
    <div>

        {{-- Client Information --}}
        <div class="detail-card">
            <div class="detail-card-header">
                <span class="detail-card-title"><i class="fas fa-id-card" style="color:var(--brown,#8b5e3c);margin-right:.4rem;"></i>Client Information</span>
            </div>
            <div class="detail-card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label>Full Name</label>
                        <p>{{ $client->name }}</p>
                    </div>
                    <div class="info-item">
                        <label>Email Address</label>
                        <p>{{ $client->email }}</p>
                    </div>
                    <div class="info-item">
                        <label>Phone Number</label>
                        <p>{{ $client->phone ?? '—' }}</p>
                    </div>
                    <div class="info-item">
                        <label>City</label>
                        <p>{{ $client->city ?? '—' }}</p>
                    </div>
                    <div class="info-item">
                        <label>Joined Date</label>
                        <p>{{ $client->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="info-item">
                        <label>Auth Provider</label>
                        <p>{{ ucfirst($client->auth_provider ?? 'email') }}</p>
                    </div>
                    <div class="info-item">
                        <label>Email Verified</label>
                        <p>
                            @if($client->email_verified_at)
                                <span class="badge badge-success" style="font-size:.72rem;">Verified</span>
                            @else
                                <span class="badge badge-warning" style="font-size:.72rem;">Not Verified</span>
                            @endif
                        </p>
                    </div>
                    <div class="info-item">
                        <label>Account Status</label>
                        <p>
                            <span class="badge {{ $client->is_active ? 'badge-success' : 'badge-danger' }}" style="font-size:.72rem;">
                                {{ $client->is_active ? 'Active' : 'Suspended' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Appointments --}}
        <div class="detail-card" style="margin-top:1.25rem;">
            <div class="detail-card-header">
                <span class="detail-card-title"><i class="fas fa-calendar-check" style="color:var(--brown,#8b5e3c);margin-right:.4rem;"></i>Recent Appointments</span>
                <span class="badge badge-info">{{ $client->appointments->count() }} total</span>
            </div>

            @forelse($client->appointments->take(5) as $appt)
            <div class="appt-item" onclick="window.location='{{ route('admin.appointments.show', $appt->id) }}'">
                <div>
                    <div class="appt-salon">{{ $appt->salon->name ?? 'N/A' }}</div>
                    <div class="appt-meta">
                        <i class="fas fa-scissors" style="font-size:.7rem;"></i>
                        {{ $appt->service->name ?? 'Service' }}
                        &nbsp;·&nbsp;
                        <i class="fas fa-calendar" style="font-size:.7rem;"></i>
                        {{ $appt->appointment_date->format('d M Y') }}
                    </div>
                </div>
                <div class="appt-right">
                    <span class="badge {{ $appt->status == 'confirmed' ? 'badge-success' : ($appt->status == 'cancelled' ? 'badge-danger' : 'badge-warning') }}">
                        {{ ucfirst($appt->status) }}
                    </span>
                    <div class="appt-amount">Rs. {{ number_format($appt->total_amount ?? 0) }}</div>
                </div>
            </div>
            @empty
            <div class="empty-mini">
                <i class="fas fa-calendar-xmark"></i>
                No appointments yet
            </div>
            @endforelse

            @if($client->appointments->count() > 5)
            <a href="{{ route('admin.appointments.index', ['client' => $client->id]) }}" class="view-all-link">
                View all {{ $client->appointments->count() }} appointments <i class="fas fa-arrow-right" style="font-size:.75rem;"></i>
            </a>
            @endif
        </div>

    </div>
    {{-- ══ END RIGHT ══ --}}

</div>

@endsection