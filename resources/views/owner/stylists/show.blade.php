{{--
    ===========================================================
    TEAM MEMBER SHOW / DETAIL PAGE
    (resources/views/owner/stylists/show.blade.php)
    Route: GET /owner/stylists/{stylist} --> owner.stylists.show
    ===========================================================
--}}
@extends('layouts.owner')

@section('title', 'Team Member Details')

@section('content')

    {{-- Page Header --}}
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>{{ $stylist['name'] }}</h2>
            <p>{{ $stylist['role'] }} &middot; Team Member Details</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('owner.stylists.edit', ['stylist' => $stylist['id']]) }}" class="btn btn-edit-action">
                <i class="bi bi-pencil-square me-2"></i> Edit
            </a>
            <a href="{{ route('owner.stylists.index') }}" class="btn btn-back">
                <i class="bi bi-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- ===================== LEFT: PHOTO + INFO ===================== --}}
        <div class="col-lg-4">
            <div class="panel-card text-center">
                <div class="stylist-avatar-lg mx-auto">
                    @if (!empty($stylist['photo_url']))
                        <img src="{{ $stylist['photo_url'] }}" alt="{{ $stylist['name'] }}">
                    @else
                        <i class="bi bi-person-fill"></i>
                    @endif
                </div>

                <h4 class="mt-3 mb-1" style="color:var(--plum-800); font-weight:700;">{{ $stylist['name'] }}</h4>
                <p class="mb-2" style="color:var(--ink-700); font-size:14px;">{{ $stylist['role'] }}</p>

                <span class="badge-status {{ $stylist['status'] === 'Active' ? 'badge-confirmed' : 'badge-cancelled' }} mb-3">
                    {{ $stylist['status'] }}
                </span>

                <div class="stylist-rating-lg">
                    <i class="bi bi-star-fill"></i> {{ $stylist['rating'] }} Rating
                </div>

                <hr class="my-4">

                <div class="text-start contact-info-list">
                    <div class="contact-item">
                        <i class="bi bi-envelope-fill"></i>
                        <span>{{ $stylist['email'] }}</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-telephone-fill"></i>
                        <span>{{ $stylist['phone'] }}</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-award-fill"></i>
                        <span>{{ $stylist['specialization'] }}</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-clock-history"></i>
                        <span>{{ $stylist['experience_years'] }} years experience</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===================== RIGHT: STATS + BIO + APPOINTMENTS ===================== --}}
        <div class="col-lg-8">

            {{-- Stat cards --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-card-sm">
                        <div class="stat-icon icon-blue"><i class="bi bi-people-fill"></i></div>
                        <div>
                            <div class="stat-label-sm">Total Clients</div>
                            <div class="stat-value-sm">{{ $stylist['clients'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-sm">
                        <div class="stat-icon icon-green"><i class="bi bi-currency-dollar"></i></div>
                        <div>
                            <div class="stat-label-sm">Total Revenue</div>
                            <div class="stat-value-sm">${{ number_format($stylist['revenue']) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-sm">
                        <div class="stat-icon icon-gold"><i class="bi bi-calendar-check-fill"></i></div>
                        <div>
                            <div class="stat-label-sm">Appointments</div>
                            <div class="stat-value-sm">{{ $stylist['total_appointments'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bio --}}
            @if (!empty($stylist['bio']))
                <div class="panel-card mb-4">
                    <div class="panel-title">About</div>
                    <p class="stylist-bio-text">{{ $stylist['bio'] }}</p>
                </div>
            @endif

            {{-- Recent Appointments --}}
            <div class="panel-card">
                <div class="panel-title">Recent Appointments</div>
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Service</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentAppointments ?? [] as $appt)
                                <tr>
                                    <td class="cell-name">{{ $appt['client'] }}</td>
                                    <td>{{ $appt['service'] }}</td>
                                    <td>{{ $appt['date'] }}</td>
                                    <td>
                                        <span class="badge-status {{ $appt['status'] === 'Completed' ? 'badge-completed' : 'badge-confirmed' }}">
                                            {{ $appt['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4" style="color:var(--ink-500);">
                                        No appointments found yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

@endsection

@section('extra-css')
<style>
    .btn-back {
        background: var(--white); border: 1px solid var(--blush-200); color: var(--plum-800);
        font-weight: 600; font-size: 14.5px; padding: 10px 20px; border-radius: 10px;
        display: inline-flex; align-items: center; transition: all 0.18s ease;
    }
    .btn-back:hover { background: var(--blush-50); color: var(--plum-900); }

    .btn-edit-action {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        color: var(--plum-900); font-weight: 700; font-size: 14.5px;
        padding: 10px 22px; border-radius: 10px; border: none;
        box-shadow: 0 4px 14px rgba(217, 164, 65, 0.35); transition: all 0.18s ease;
        display: inline-flex; align-items: center;
    }
    .btn-edit-action:hover { transform: translateY(-1px); color: var(--plum-900); box-shadow: 0 6px 18px rgba(217, 164, 65, 0.5); }

    .stylist-avatar-lg {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 52px;
        color: #fff;
        overflow: hidden;
    }
    .stylist-avatar-lg img { width: 100%; height: 100%; object-fit: cover; }

    .stylist-rating-lg {
        font-size: 15px;
        font-weight: 700;
        color: var(--gold-600);
        margin-bottom: 4px;
    }
    .stylist-rating-lg i { color: var(--gold-500); margin-right: 4px; }

    .contact-info-list { display: flex; flex-direction: column; gap: 12px; }
    .contact-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13.5px;
        color: var(--ink-700);
    }
    .contact-item i { color: var(--rose-500); width: 18px; text-align: center; }

    .stat-card-sm {
        background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--blush-200);
        box-shadow: var(--shadow-card); padding: 18px 20px; display: flex; align-items: center; gap: 16px; height: 100%;
    }
    .stat-card-sm .stat-icon { width: 50px; height: 50px; border-radius: 14px; font-size: 20px; flex-shrink: 0; }
    .stat-label-sm { font-size: 13.5px; color: var(--ink-700); margin-bottom: 2px; }
    .stat-value-sm { font-size: 22px; font-weight: 700; color: var(--plum-900); }

    .stylist-bio-text {
        color: var(--ink-700);
        font-size: 14.5px;
        line-height: 1.7;
        margin-bottom: 0;
    }
</style>
@endsection
