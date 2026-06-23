
@extends('layouts.owner')

@section('title', 'Service Details')

@section('content')

    @php
        // Demo/dummy data — BAAD ME ye $service controller se aayega (Service::findOrFail($id))
        $service = $service ?? [
            'id'             => 1,
            'name'           => 'Premium Haircut',
            'category'       => 'Hair Styling',
            'duration'       => 45,
            'price'          => 85,
            'discount_price' => null,
            'bookings'       => 145,
            'revenue'        => 12325,
            'rating'         => 4.8,
            'description'    => 'A precision haircut tailored to your face shape and style preference, finished with a professional blow-dry.',
            'client_notes'   => 'Please arrive 10 minutes early for a consultation.',
            'status'         => 'Active',
            'image_url'      => null,
        ];

        $recentBookings = $recentBookings ?? [
            ['client' => 'Sarah Johnson', 'date' => 'Jun 8, 2026', 'stylist' => 'Emma Wilson', 'status' => 'Confirmed'],
            ['client' => 'Michael Chen',  'date' => 'Jun 7, 2026', 'stylist' => 'James Brown', 'status' => 'Completed'],
            ['client' => 'Amanda Lee',    'date' => 'Jun 5, 2026', 'stylist' => 'Emma Wilson', 'status' => 'Completed'],
        ];
    @endphp

   
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>{{ $service['name'] }}</h2>
            <p>{{ $service['category'] }} &middot; Service Details</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('owner.services.edit', ['service' => $service['id']]) }}" class="btn btn-edit-action">
                <i class="bi bi-pencil-square me-2"></i> Edit
            </a>
            <a href="{{ route('owner.services.index') }}" class="btn btn-back">
                <i class="bi bi-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-lg-4">
            <div class="panel-card text-center">
                <div class="service-image-box mx-auto">
                    @if (!empty($service['image_url']))
                        <img src="{{ $service['image_url'] }}" alt="{{ $service['name'] }}">
                    @else
                        <i class="bi bi-scissors"></i>
                    @endif
                </div>

                <h4 class="mt-3 mb-1" style="color:var(--plum-800); font-weight:700;">{{ $service['name'] }}</h4>
                <span class="badge-status {{ $service['status'] === 'Active' ? 'badge-confirmed' : 'badge-cancelled' }} mb-3">
                    {{ $service['status'] }}
                </span>

                <div class="price-row">
                    @if (!empty($service['discount_price']))
                        <span class="price-old">${{ $service['price'] }}</span>
                        <span class="price-current">${{ $service['discount_price'] }}</span>
                    @else
                        <span class="price-current">${{ $service['price'] }}</span>
                    @endif
                </div>

                <div class="detail-meta-row">
                    <div class="meta-item">
                        <i class="bi bi-clock-fill"></i>
                        <span>{{ $service['duration'] }} min</span>
                    </div>
                    <div class="meta-item">
                        <i class="bi bi-star-fill" style="color:var(--gold-500);"></i>
                        <span>{{ $service['rating'] }} Rating</span>
                    </div>
                </div>
            </div>
        </div>

       
        <div class="col-lg-8">

            
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-card-sm">
                        <div class="stat-icon icon-gold"><i class="bi bi-calendar-check-fill"></i></div>
                        <div>
                            <div class="stat-label-sm">Total Bookings</div>
                            <div class="stat-value-sm">{{ $service['bookings'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-sm">
                        <div class="stat-icon icon-green"><i class="bi bi-currency-dollar"></i></div>
                        <div>
                            <div class="stat-label-sm">Total Revenue</div>
                            <div class="stat-value-sm">${{ number_format($service['revenue']) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-sm">
                        <div class="stat-icon icon-blue"><i class="bi bi-clock-fill"></i></div>
                        <div>
                            <div class="stat-label-sm">Duration</div>
                            <div class="stat-value-sm">{{ $service['duration'] }} min</div>
                        </div>
                    </div>
                </div>
            </div>

         
            <div class="panel-card mb-4">
                <div class="panel-title">Description</div>
                <p class="service-description">{{ $service['description'] }}</p>

                @if (!empty($service['client_notes']))
                    <div class="client-notes-box">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        {{ $service['client_notes'] }}
                    </div>
                @endif
            </div>

            
            <div class="panel-card">
                <div class="panel-title">Recent Bookings</div>
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Stylist</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentBookings as $booking)
                                <tr>
                                    <td class="cell-name">{{ $booking['client'] }}</td>
                                    <td>{{ $booking['date'] }}</td>
                                    <td>{{ $booking['stylist'] }}</td>
                                    <td>
                                        <span class="badge-status {{ $booking['status'] === 'Completed' ? 'badge-completed' : 'badge-confirmed' }}">
                                            {{ $booking['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
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

    .service-image-box {
        width: 100%;
        height: 180px;
        border-radius: var(--radius-md);
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 56px;
        color: #fff;
        overflow: hidden;
        margin-bottom: 6px;
    }
    .service-image-box img { width: 100%; height: 100%; object-fit: cover; }

    .price-row { margin: 12px 0; }
    .price-old { text-decoration: line-through; color: var(--ink-500); font-size: 15px; margin-right: 10px; }
    .price-current { font-size: 26px; font-weight: 700; color: var(--gold-600); }

    .detail-meta-row {
        display: flex;
        justify-content: center;
        gap: 24px;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid var(--blush-100);
    }
    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        color: var(--ink-700);
        font-weight: 600;
    }
    .meta-item i { color: var(--rose-500); }

    .stat-card-sm {
        background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--blush-200);
        box-shadow: var(--shadow-card); padding: 18px 20px; display: flex; align-items: center; gap: 16px; height: 100%;
    }
    .stat-card-sm .stat-icon { width: 50px; height: 50px; border-radius: 14px; font-size: 20px; flex-shrink: 0; }
    .stat-label-sm { font-size: 13.5px; color: var(--ink-700); margin-bottom: 2px; }
    .stat-value-sm { font-size: 22px; font-weight: 700; color: var(--plum-900); }

    .service-description {
        color: var(--ink-700);
        font-size: 14.5px;
        line-height: 1.7;
        margin-bottom: 0;
    }

    .client-notes-box {
        background: var(--blush-50);
        border-left: 3px solid var(--rose-400);
        border-radius: var(--radius-sm);
        padding: 12px 16px;
        margin-top: 16px;
        font-size: 13.5px;
        color: var(--plum-800);
    }
</style>
@endsection
