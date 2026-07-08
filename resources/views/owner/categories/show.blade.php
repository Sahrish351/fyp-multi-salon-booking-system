
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
                <div class="category-icon-preview {{ $category['icon_bg'] }} mx-auto">
                    <i class="bi bi-diagram-3-fill"></i>
                </div>

                <h4 class="mt-3 mb-1" style="color:var(--plum-800); font-weight:700;">{{ $category['name'] }}</h4>
                <span class="badge-status {{ ($category['status'] ?? 'Active') === 'Active' ? 'badge-confirmed' : 'badge-cancelled' }}">
                    {{ $category['status'] ?? 'Active' }}
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
                                    <td class="amount-gold">${{ $service['price'] }}</td>
                                    <td>
                                        <span class="badge-status {{ $service['status'] === 'Active' ? 'badge-confirmed' : 'badge-cancelled' }}">
                                            {{ $service['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4" style="color:var(--ink-500);">
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
    .cat-gold   { background: linear-gradient(135deg, var(--gold-500), var(--gold-600)); }
    .cat-purple { background: linear-gradient(135deg, var(--purple-500), #7E4FB5); }
    .cat-green  { background: linear-gradient(135deg, #38C495, var(--green-500)); }
    .cat-blue   { background: linear-gradient(135deg, #6398F2, var(--blue-500)); }
    .cat-orange { background: linear-gradient(135deg, #F2A23D, var(--orange-500)); }
    .cat-pink   { background: linear-gradient(135deg, var(--rose-400), var(--rose-600)); }
    .cat-teal   { background: linear-gradient(135deg, #3DC9B0, #21A085); }

    .category-description-text {
        font-size: 14px;
        color: var(--ink-700);
        line-height: 1.6;
    }

    .stat-card-sm {
        background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--blush-200);
        box-shadow: var(--shadow-card); padding: 18px 20px; display: flex; align-items: center; gap: 16px; height: 100%;
    }
    .stat-card-sm .stat-icon { width: 50px; height: 50px; border-radius: 14px; font-size: 20px; flex-shrink: 0; }
    .stat-label-sm { font-size: 13.5px; color: var(--ink-700); margin-bottom: 2px; }
    .stat-value-sm { font-size: 22px; font-weight: 700; color: var(--plum-900); }
</style>
@endsection
