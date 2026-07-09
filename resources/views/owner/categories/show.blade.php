@extends('layouts.owner')

@section('title', 'Category Details')

@section('content')

    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>{{ $category['name'] }}</h2>
            <p>Category Details</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('owner.categories.edit', ['category' => $category['id']]) }}" class="btn btn-edit-action">
                <i class="bi bi-pencil-square me-2"></i> Edit
            </a>
            <a href="{{ route('owner.categories.index') }}" class="btn btn-back">
                <i class="bi bi-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-lg-4">
            <div class="panel-card text-center">
                {{-- ✅ ALAG ICON + COLOR --}}
                <div class="category-icon-preview {{ $category['icon_bg'] }} mx-auto">
                    <i class="bi bi-{{ $category['icon'] }}"></i>
                </div>

                <h4 class="mt-3 mb-1" style="color:#2d1f2c; font-weight:700;">{{ $category['name'] }}</h4>
                <span class="badge-status {{ $category['status'] === 'Active' ? 'badge-confirmed' : 'badge-cancelled' }}">
                    {{ $category['status'] }}
                </span>

                @if (!empty($category['description']))
                    <p class="category-description-text mt-3">{{ $category['description'] }}</p>
                @endif
            </div>
        </div>

        <div class="col-lg-8">

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="stat-card-sm">
                        <div class="stat-icon icon-gold"><i class="bi bi-scissors"></i></div>
                        <div>
                            <div class="stat-label-sm">Total Services</div>
                            <div class="stat-value-sm">{{ $category['count'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-card-sm">
                        <div class="stat-icon icon-green"><i class="bi bi-currency-dollar"></i></div>
                        <div>
                            <div class="stat-label-sm">Total Bookings</div>
                            <div class="stat-value-sm">{{ $category['total_bookings'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-card">
                <div class="panel-title">Services in this Category</div>
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Service Name</th>
                                <th>Duration</th>
                                <th>Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($servicesInCategory ?? [] as $service)
                                <tr>
                                    <td class="cell-name">{{ $service['name'] }}</td>
                                    <td>{{ $service['duration'] }} min</td>
                                    <td class="amount-gold">PKR {{ number_format($service['price']) }}</td>
                                    <td>
                                        <span class="badge-status {{ $service['status'] === 'Active' ? 'badge-confirmed' : 'badge-cancelled' }}">
                                            {{ $service['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4" style="color:#8a7a88;">
                                        No services found in this category yet.
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

    .btn-back {
        background: #fff;
        border: 1px solid #f0e8ed;
        color: #2d1f2c;
        font-weight: 600;
        font-size: 14.5px;
        padding: 10px 20px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        transition: all 0.18s ease;
        text-decoration: none;
    }
    .btn-back:hover {
        background: #fcf6f9;
        border-color: #E85588;
        color: #E85588;
    }

    .btn-edit-action {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 14.5px;
        padding: 10px 22px;
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.35);
        transition: all 0.18s ease;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }
    .btn-edit-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(232, 85, 136, 0.45);
        color: #ffffff !important;
    }

    .panel-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f0e8ed;
        height: auto !important;
    }
    .panel-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2d1f2c;
        margin-bottom: 1rem;
    }

    .category-icon-preview {
        width: 90px;
        height: 90px;
        border-radius: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        color: #fff;
        margin-bottom: 8px;
    }
    .cat-gold   { background: linear-gradient(135deg, #D9A441, #C4903A); }
    .cat-purple { background: linear-gradient(135deg, #9B6FD1, #7E4FB5); }
    .cat-green  { background: linear-gradient(135deg, #38C495, #2EAE7D); }
    .cat-blue   { background: linear-gradient(135deg, #6398F2, #4A7FE0); }
    .cat-orange { background: linear-gradient(135deg, #F2A23D, #E08A2C); }
    .cat-pink   { background: linear-gradient(135deg, #FF6B9D, #E85588); }
    .cat-teal   { background: linear-gradient(135deg, #3DC9B0, #21A085); }

    .category-description-text {
        font-size: 14px;
        color: #4a3a48;
        line-height: 1.6;
    }

    .stat-card-sm {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #f0e8ed;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        height: 100%;
    }
    .stat-card-sm .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        font-size: 20px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
    }
    .icon-gold { background: linear-gradient(135deg, #D9A441, #C4903A); }
    .icon-green { background: linear-gradient(135deg, #2EAE7D, #1E8E64); }
    .stat-label-sm { font-size: 13.5px; color: #8a7a88; margin-bottom: 2px; }
    .stat-value-sm { font-size: 22px; font-weight: 700; color: #2d1f2c; }

    .badge-status {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .badge-confirmed { background: #E8F5ED; color: #1E8E64; }
    .badge-cancelled { background: #FCE4EC; color: #D45482; }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
    }
    .table-custom thead th {
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        color: #8a7a88;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 0 10px 12px;
        border-bottom: 1.5px solid #f0e8ed;
    }
    .table-custom tbody td {
        padding: 12px 10px;
        font-size: 14px;
        color: #2d1f2c;
        border-bottom: 1px solid #f5eef2;
        vertical-align: middle;
    }
    .table-custom tbody tr:last-child td {
        border-bottom: none;
    }
    .table-custom tbody tr:hover {
        background: #fcf6f9;
    }
    .cell-name { font-weight: 600; color: #2d1f2c; }
    .amount-gold { font-weight: 700; color: #D9A441; }
</style>
@endsection