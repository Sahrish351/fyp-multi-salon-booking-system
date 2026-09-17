@extends('layouts.admin')
@section('title', 'Client Details - ' . $client->name)

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

    .gl-client-detail-page { max-width: 1180px; margin: 0 auto; box-sizing: border-box; }
    .gl-client-detail-page * { box-sizing: border-box; }

    /* Back Button */
    .gl-btn-back { display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; border: 1.5px solid var(--gl-border); border-radius: 12px; font-size: 0.85rem; font-weight: 700; color: var(--gl-text-lt); text-decoration: none; transition: all 0.15s ease; margin-bottom: 20px; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.03); }
    .gl-btn-back:hover { border-color: var(--gl-pink); color: var(--gl-pink); background: var(--gl-pink-light); transform: translateY(-1px); }

    /* Layout Grid */
    .gl-detail-grid { display: grid; grid-template-columns: 340px 1fr; gap: 24px; align-items: start; }
    @media (max-width: 992px) { .gl-detail-grid { grid-template-columns: 1fr; } }

    /* Card Base */
    .gl-detail-card { background: #fff; border: 1px solid var(--gl-border); border-radius: 20px; overflow: hidden; box-shadow: 0 2px 12px rgba(255, 107, 157, 0.04); }
    .gl-detail-card + .gl-detail-card { margin-top: 20px; }
    .gl-detail-card-header { display: flex; align-items: center; justify-content: space-between; padding: 18px 24px; border-bottom: 1px solid var(--gl-border); background: #fff; }
    .gl-detail-card-title { font-weight: 800; font-size: 0.95rem; color: var(--gl-text); display: flex; align-items: center; gap: 8px; text-transform: uppercase; letter-spacing: 0.6px; }
    .gl-detail-card-title i { color: var(--gl-pink); font-size: 1rem; }
    .gl-detail-card-body { padding: 24px; }

    /* Profile Card */
    .gl-profile-card { text-align: center; }
    .gl-profile-avatar-wrap { width: 88px; height: 88px; border-radius: 26px; background: var(--gl-pink-light); color: var(--gl-pink); font-size: 2.1rem; font-weight: 800; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; border: 2px solid var(--gl-pink-pale); box-shadow: 0 4px 12px rgba(255,107,157,0.1); }
    .gl-profile-name { font-size: 1.25rem; font-weight: 800; color: var(--gl-text); margin-bottom: 4px; }
    .gl-profile-email { font-size: 0.84rem; color: var(--gl-text-lt); margin-bottom: 14px; font-weight: 500; }

    /* Badges */
    .gl-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 20px; font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.4px; }
    .gl-badge-success { background: var(--gl-green-light); color: var(--gl-green); }
    .gl-badge-danger { background: var(--gl-red-light); color: var(--gl-red); }
    .gl-badge-warning { background: var(--gl-pink-light); color: var(--gl-pink-dark); }
    .gl-badge-info { background: var(--gl-blue-light); color: var(--gl-blue); }

    /* Stats Row */
    .gl-stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin: 20px 0; padding: 16px 0; border-top: 1px solid var(--gl-border); border-bottom: 1px solid var(--gl-border); }
    .gl-stat-item { text-align: center; }
    .gl-stat-value { font-size: 1.25rem; font-weight: 800; color: var(--gl-pink); line-height: 1.1; margin-bottom: 4px; }
    .gl-stat-label { font-size: 0.68rem; color: var(--gl-text-lt); text-transform: uppercase; letter-spacing: 0.6px; font-weight: 800; }

    /* Toggle Suspend Button */
    .gl-btn-toggle-suspend { display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 11px 16px; border-radius: 12px; font-size: 0.86rem; font-weight: 700; cursor: pointer; border: none; transition: all 0.18s ease; }
    .gl-btn-toggle-suspend:hover { transform: translateY(-1px); opacity: 0.9; }
    .gl-btn-suspend { background: var(--gl-red); color: #fff; box-shadow: 0 3px 10px rgba(217,48,37,0.2); }
    .gl-btn-activate { background: var(--gl-green); color: #fff; box-shadow: 0 3px 10px rgba(30,142,62,0.2); }

    /* Info Grid */
    .gl-info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
    @media (max-width: 600px) { .gl-info-grid { grid-template-columns: 1fr; } }
    .gl-info-item label { display: block; font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: var(--gl-text-lt); margin-bottom: 4px; }
    .gl-info-item p { margin: 0; font-size: 0.9rem; color: var(--gl-text); font-weight: 500; }

    /* Appointments List */
    .gl-appt-item { display: flex; justify-content: space-between; align-items: center; padding: 16px 24px; border-bottom: 1px solid #F8F5F7; cursor: pointer; transition: background 0.15s ease; gap: 16px; }
    .gl-appt-item:last-child { border-bottom: none; }
    .gl-appt-item:hover { background: #FFFBFD; }
    .gl-appt-salon { font-weight: 700; font-size: 0.9rem; color: var(--gl-text); margin-bottom: 3px; }
    .gl-appt-meta { font-size: 0.78rem; color: var(--gl-text-lt); font-weight: 500; display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
    .gl-appt-right { text-align: right; flex-shrink: 0; display: flex; flex-direction: column; align-items: flex-end; gap: 4px; }
    .gl-appt-amount { font-weight: 800; font-size: 0.9rem; color: var(--gl-pink); }

    /* Empty Mini State */
    .gl-empty-mini { text-align: center; padding: 36px 16px; color: var(--gl-text-lt); font-size: 0.88rem; font-weight: 500; }
    .gl-empty-mini i { font-size: 2rem; display: block; margin-bottom: 8px; opacity: 0.4; color: var(--gl-pink); }

    /* View All Link */
    .gl-view-all-link { display: block; text-align: center; padding: 14px; font-size: 0.85rem; font-weight: 700; color: var(--gl-pink); border-top: 1px solid var(--gl-border); text-decoration: none; background: #FAFAFC; transition: background 0.15s ease; }
    .gl-view-all-link:hover { background: var(--gl-pink-light); }
</style>
@endpush

@section('content')
<div class="gl-client-detail-page">

    {{-- Back Action --}}
    <a href="{{ route('admin.clients.index') }}" class="gl-btn-back">
        <i class="fas fa-arrow-left"></i> Back to Clients Directory
    </a>

    <div class="gl-detail-grid">

        {{-- ══ LEFT COLUMN: Profile Summary Card ══ --}}
        <div>
            <div class="gl-detail-card gl-profile-card">
                <div class="gl-detail-card-body" style="padding: 28px 20px;">

                    <div class="gl-profile-avatar-wrap">
                        {{ strtoupper(substr($client->name, 0, 1)) }}
                    </div>
                    <div class="gl-profile-name">{{ $client->name }}</div>
                    <div class="gl-profile-email">{{ $client->email }}</div>

                    <span class="gl-badge {{ $client->is_active ? 'gl-badge-success' : 'gl-badge-danger' }}">
                        <i class="fas {{ $client->is_active ? 'fa-circle' : 'fa-ban' }}" style="font-size: 5px;"></i>
                        {{ $client->is_active ? 'Active Account' : 'Suspended Account' }}
                    </span>

                    {{-- Mini Statistics Row --}}
                    <div class="gl-stats-row">
                        <div class="gl-stat-item">
                            <div class="gl-stat-value">{{ $client->appointments->count() }}</div>
                            <div class="gl-stat-label">Bookings</div>
                        </div>
                        <div class="gl-stat-item">
                            <div class="gl-stat-value">{{ $client->reviews->count() }}</div>
                            <div class="gl-stat-label">Reviews</div>
                        </div>
                        <div class="gl-stat-item">
                            <div class="gl-stat-value">0</div>
                            <div class="gl-stat-label">Favorites</div>
                        </div>
                    </div>

                    {{-- Toggle Status Action Form --}}
                    <form action="{{ route('admin.clients.toggle', $client->id) }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" 
                            class="gl-btn-toggle-suspend {{ $client->is_active ? 'gl-btn-suspend' : 'gl-btn-activate' }}"
                            onclick="return confirm('{{ $client->is_active ? 'Are you sure you want to suspend this client?' : 'Are you sure you want to activate this client?' }}')">
                            <i class="fas {{ $client->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                            {{ $client->is_active ? 'Suspend Client' : 'Activate Client' }}
                        </button>
                    </form>

                </div>
            </div>
        </div>

        {{-- ══ RIGHT COLUMN: Info Details & Appointments ══ --}}
        <div>

            {{-- Client Information Details Card --}}
            <div class="gl-detail-card">
                <div class="gl-detail-card-header">
                    <span class="gl-detail-card-title">
                        <i class="fas fa-id-card"></i> Client Information
                    </span>
                </div>
                <div class="gl-detail-card-body">
                    <div class="gl-info-grid">
                        <div class="gl-info-item">
                            <label>Full Name</label>
                            <p>{{ $client->name }}</p>
                        </div>
                        <div class="gl-info-item">
                            <label>Email Address</label>
                            <p>{{ $client->email }}</p>
                        </div>
                        <div class="gl-info-item">
                            <label>Phone Number</label>
                            <p>{{ $client->phone ?? '—' }}</p>
                        </div>
                        <div class="gl-info-item">
                            <label>City Location</label>
                            <p>{{ $client->city ?? '—' }}</p>
                        </div>
                        <div class="gl-info-item">
                            <label>Joined Date</label>
                            <p>{{ $client->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                        <div class="gl-info-item">
                            <label>Auth Provider</label>
                            <p>{{ ucfirst($client->auth_provider ?? 'Email / Password') }}</p>
                        </div>
                        <div class="gl-info-item">
                            <label>Email Verification</label>
                            <p>
                                @if($client->email_verified_at)
                                    <span class="gl-badge gl-badge-success">Verified</span>
                                @else
                                    <span class="gl-badge gl-badge-warning">Unverified</span>
                                @endif
                            </p>
                        </div>
                        <div class="gl-info-item">
                            <label>Account Status</label>
                            <p>
                                <span class="gl-badge {{ $client->is_active ? 'gl-badge-success' : 'gl-badge-danger' }}">
                                    {{ $client->is_active ? 'Active' : 'Suspended' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Appointments Card --}}
            <div class="gl-detail-card" style="margin-top: 20px;">
                <div class="gl-detail-card-header">
                    <span class="gl-detail-card-title">
                        <i class="fas fa-calendar-check"></i> Recent Appointments
                    </span>
                    <span class="gl-badge gl-badge-info">{{ $client->appointments->count() }} Total</span>
                </div>

                @forelse($client->appointments->take(5) as $appt)
                <div class="gl-appt-item" onclick="window.location='{{ route('admin.appointments.show', $appt->id) }}'">
                    <div>
                        <div class="gl-appt-salon">{{ $appt->salon->name ?? 'Beauty Salon' }}</div>
                        <div class="gl-appt-meta">
                            <span><i class="fas fa-scissors"></i> {{ $appt->service->name ?? 'Hair & Beauty Service' }}</span>
                            <span>•</span>
                            <span><i class="fas fa-calendar"></i> {{ optional($appt->appointment_date)->format('d M Y') ?? '—' }}</span>
                        </div>
                    </div>
                    <div class="gl-appt-right">
                        <span class="gl-badge {{ $appt->status == 'confirmed' ? 'gl-badge-success' : ($appt->status == 'cancelled' ? 'gl-badge-danger' : 'gl-badge-warning') }}">
                            {{ ucfirst($appt->status) }}
                        </span>
                        <div class="gl-appt-amount">Rs. {{ number_format($appt->total_amount ?? 0) }}</div>
                    </div>
                </div>
                @empty
                <div class="gl-empty-mini">
                    <i class="fas fa-calendar-times"></i>
                    No appointment history recorded for this client yet.
                </div>
                @endforelse

                @if($client->appointments->count() > 5)
                <a href="{{ route('admin.appointments.index', ['client' => $client->id]) }}" class="gl-view-all-link">
                    View all {{ $client->appointments->count() }} appointments <i class="fas fa-arrow-right" style="font-size: 0.75rem; margin-left: 4px;"></i>
                </a>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection