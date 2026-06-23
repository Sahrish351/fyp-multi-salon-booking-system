
@extends('layouts.owner')
 
@section('title', 'Services')
 
@section('content')
 
   
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>Services</h2>
            <p>Manage your salon services</p>
        </div>
        <a href="{{ route('owner.services.create') }}" class="btn btn-add-service">
            <i class="bi bi-plus-lg me-2"></i> Add Service
        </a>
    </div>
 
    <div class="row g-4 mb-4">
 
        <div class="col-md-4">
            <div class="stat-card-sm">
                <div class="stat-icon icon-gold"><i class="bi bi-scissors"></i></div>
                <div>
                    <div class="stat-label-sm">Total Services</div>
                    <div class="stat-value-sm">{{ $stats['total_services'] ?? 48 }}</div>
                </div>
            </div>
        </div>
 
        <div class="col-md-4">
            <div class="stat-card-sm">
                <div class="stat-icon icon-green"><i class="bi bi-currency-dollar"></i></div>
                <div>
                    <div class="stat-label-sm">Avg. Price</div>
                    <div class="stat-value-sm">${{ $stats['avg_price'] ?? 124 }}</div>
                </div>
            </div>
        </div>
 
        <div class="col-md-4">
            <div class="stat-card-sm">
                <div class="stat-icon icon-blue"><i class="bi bi-clock-fill"></i></div>
                <div>
                    <div class="stat-label-sm">Avg. Duration</div>
                    <div class="stat-value-sm">{{ $stats['avg_duration'] ?? 78 }} min</div>
                </div>
            </div>
        </div>
 
    </div>
 
   
    <div class="panel-card panel-card-auto mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-9">
                <div class="search-input-wrap">
                    <i class="bi bi-search"></i>
                    <input type="text" id="serviceSearchInput" class="form-control input-custom search-input"
                           placeholder="Search services...">
                </div>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-filters w-100" data-bs-toggle="modal" data-bs-target="#filtersModal">
                    <i class="bi bi-funnel-fill me-2"></i> Filters
                </button>
            </div>
        </div>
    </div>
 
   
    <div class="panel-card panel-card-auto">
        <div class="table-responsive">
            <table class="table-custom" id="servicesTable">
                <thead>
                    <tr>
                        <th>Service Name</th>
                        <th>Category</th>
                        <th>Duration</th>
                        <th>Price</th>
                        <th>Bookings</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $services = $services ?? [
                            ['id' => 1, 'name' => 'Premium Haircut',   'category' => 'Hair Styling', 'duration' => 45,  'price' => 85,  'bookings' => 145, 'status' => 'Active'],
                            ['id' => 2, 'name' => 'Hair Coloring',     'category' => 'Hair Styling', 'duration' => 90,  'price' => 120, 'bookings' => 98,  'status' => 'Active'],
                            ['id' => 3, 'name' => 'Luxury Manicure',   'category' => 'Nail Care',    'duration' => 60,  'price' => 65,  'bookings' => 132, 'status' => 'Active'],
                            ['id' => 4, 'name' => 'Luxury Pedicure',   'category' => 'Nail Care',    'duration' => 75,  'price' => 80,  'bookings' => 118, 'status' => 'Active'],
                            ['id' => 5, 'name' => 'Gold Facial',       'category' => 'Facial',       'duration' => 90,  'price' => 150, 'bookings' => 76,  'status' => 'Active'],
                            ['id' => 6, 'name' => 'Full Body Massage', 'category' => 'Spa',          'duration' => 90,  'price' => 180, 'bookings' => 54,  'status' => 'Active'],
                            ['id' => 7, 'name' => 'Bridal Makeup',     'category' => 'Makeup',       'duration' => 180, 'price' => 350, 'bookings' => 24,  'status' => 'Active'],
                            ['id' => 8, 'name' => 'Beard Trim',        'category' => 'Hair Styling', 'duration' => 30,  'price' => 45,  'bookings' => 89,  'status' => 'Active'],
                        ];
                    @endphp
 
                    @foreach ($services as $service)
                        <tr class="service-row"
                            data-name="{{ strtolower($service['name']) }}"
                            data-category="{{ strtolower($service['category']) }}">
                            <td class="cell-name">{{ $service['name'] }}</td>
                            <td>{{ $service['category'] }}</td>
                            <td>{{ $service['duration'] }} min</td>
                            <td class="amount-gold">${{ $service['price'] }}</td>
                            <td>{{ $service['bookings'] }}</td>
                            <td>
                                <span class="badge-status {{ $service['status'] === 'Active' ? 'badge-confirmed' : 'badge-cancelled' }}">
                                    {{ $service['status'] }}
                                </span>
                            </td>
                            <td>
                                <div class="action-icons">
                                    <a href="{{ route('owner.services.show', ['service' => $service['id']]) }}"
                                       class="action-btn view-btn">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button type="button" class="action-btn edit-btn"
                                            data-bs-toggle="modal" data-bs-target="#editServiceModal"
                                            data-id="{{ $service['id'] }}"
                                            data-name="{{ $service['name'] }}"
                                            data-category="{{ $service['category'] }}"
                                            data-duration="{{ $service['duration'] }}"
                                            data-price="{{ $service['price'] }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" class="action-btn delete-btn"
                                            data-bs-toggle="modal" data-bs-target="#deleteServiceModal"
                                            data-id="{{ $service['id'] }}"
                                            data-name="{{ $service['name'] }}">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
 
            <div id="noServicesFound" class="text-center py-5" style="display:none;">
                <i class="bi bi-emoji-frown" style="font-size:36px; color:#F08FB4;"></i>
                <p class="mt-2 mb-0" style="color:#6B4F62;">No services found matching your search.</p>
            </div>
        </div>
    </div>
 
@endsection
 

@push('modals')
 
    
    <div class="modal fade" id="editServiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <form action="{{ route('owner.services.update', ['service' => 0]) }}" method="POST" id="editServiceForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title">Edit Service</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label-custom">Service Name</label>
                            <input type="text" name="name" id="editServiceName" class="form-control input-custom" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-custom">Category</label>
                            <select name="category" id="editServiceCategory" class="form-select input-custom" required>
                                <option>Hair Styling</option>
                                <option>Nail Care</option>
                                <option>Facial</option>
                                <option>Spa</option>
                                <option>Makeup</option>
                            </select>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label-custom">Duration (min)</label>
                                <input type="number" name="duration" id="editServiceDuration" class="form-control input-custom" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label-custom">Price ($)</label>
                                <input type="number" name="price" id="editServicePrice" class="form-control input-custom" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer modal-footer-custom">
                        <button type="button" class="btn btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-save-changes">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
 
  
    <div class="modal fade" id="deleteServiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <form action="{{ route('owner.services.destroy', ['service' => 0]) }}" method="POST" id="deleteServiceForm">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body text-center py-4">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size:42px; color:#E14D6A;"></i>
                        <h5 class="mt-3" style="color:#5C2142; font-weight:700;">Delete Service?</h5>
                        <p class="mb-0" style="color:#6B4F62;">
                            Are you sure you want to delete "<span id="deleteServiceName" class="fw-semibold"></span>"? This action cannot be undone.
                        </p>
                    </div>
                    <div class="modal-footer modal-footer-custom justify-content-center">
                        <button type="button" class="btn btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-delete-confirm">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
 
  
    <div class="modal fade" id="filtersModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title">Filter Services</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-custom">Category</label>
                        <select id="filterCategory" class="form-select input-custom">
                            <option value="">All Categories</option>
                            <option value="hair styling">Hair Styling</option>
                            <option value="nail care">Nail Care</option>
                            <option value="facial">Facial</option>
                            <option value="spa">Spa</option>
                            <option value="makeup">Makeup</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Status</label>
                        <select id="filterStatus" class="form-select input-custom">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer modal-footer-custom">
                    <button type="button" class="btn btn-cancel-modal" id="clearFiltersBtn">Clear</button>
                    <button type="button" class="btn btn-save-changes" id="applyFiltersBtn" data-bs-dismiss="modal">Apply Filters</button>
                </div>
            </div>
        </div>
    </div>
 
@endpush
 
@section('extra-css')
<style>
    .btn-add-service {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        color: var(--plum-900); font-weight: 700; font-size: 14.5px;
        padding: 11px 22px; border-radius: 10px; border: none;
        box-shadow: 0 4px 14px rgba(217, 164, 65, 0.35); transition: all 0.18s ease;
        display: inline-flex; align-items: center; white-space: nowrap;
    }
    .btn-add-service:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(217, 164, 65, 0.5); color: var(--plum-900); }
 
  
    .panel-card-auto { height: auto; }
 
    .stat-card-sm {
        background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--blush-200);
        box-shadow: var(--shadow-card); padding: 18px 20px; display: flex; align-items: center; gap: 16px; height: 100%;
    }
    .stat-card-sm .stat-icon { width: 50px; height: 50px; border-radius: 14px; font-size: 20px; flex-shrink: 0; }
    .stat-label-sm { font-size: 13.5px; color: var(--ink-700); margin-bottom: 2px; }
    .stat-value-sm { font-size: 22px; font-weight: 700; color: var(--plum-900); }
 
    .search-input-wrap { position: relative; }
    .search-input-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--ink-500); }
    .search-input { padding-left: 40px !important; }
 
    .btn-filters {
        background: var(--white); border: 1px solid var(--rose-300) !important; color: var(--rose-600) !important;
        font-weight: 600; font-size: 14.5px; padding: 11px 20px; border-radius: var(--radius-sm);
        display: inline-flex; align-items: center; justify-content: center; transition: all 0.18s ease;
    }
    .btn-filters:hover { background: var(--blush-50); }
 
    .action-icons { display: flex; gap: 8px; }
    .action-btn {
        width: 34px; height: 34px; border-radius: 8px; border: none; display: inline-flex;
        align-items: center; justify-content: center; font-size: 15px; cursor: pointer; transition: all 0.15s ease;
    }
    .edit-btn { background: var(--blue-50); color: var(--blue-500); }
    .edit-btn:hover { background: var(--blue-500); color: #fff; }
    .view-btn { background: var(--purple-50); color: var(--purple-500); }
    .view-btn:hover { background: var(--purple-500); color: #fff; }
    .delete-btn { background: var(--red-50); color: var(--red-500); }
    .delete-btn:hover { background: var(--red-500); color: #fff; }
 
    .form-label-custom { display: block; font-size: 13.5px; font-weight: 600; color: var(--ink-700); margin-bottom: 6px; }
    .input-custom {
        background: var(--blush-50) !important; border: 1px solid var(--blush-200) !important;
        border-radius: var(--radius-sm) !important; color: var(--ink-900) !important;
        font-size: 14.5px; padding: 11px 14px !important;
    }
    .input-custom:focus { background: #fff !important; border-color: var(--rose-400) !important; box-shadow: 0 0 0 3px rgba(240, 143, 180, 0.2) !important; outline: none; }
 
    .modal-content-custom { border-radius: var(--radius-lg); border: none; overflow: hidden; }
    .modal-header-custom { background: var(--blush-50); border-bottom: 1px solid var(--blush-200); padding: 18px 24px; }
    .modal-header-custom .modal-title { font-weight: 700; color: var(--plum-800); }
    .modal-body { padding: 22px 24px; }
    .modal-footer-custom { border-top: 1px solid var(--blush-100); padding: 16px 24px; }
 
    .btn-cancel-modal {
        background: var(--white); border: 1px solid var(--blush-200); color: var(--ink-700);
        font-weight: 600; padding: 9px 20px; border-radius: 10px;
    }
    .btn-cancel-modal:hover { background: var(--blush-50); }
 
    .btn-save-changes {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        color: var(--plum-900); font-weight: 700; padding: 9px 22px; border-radius: 10px; border: none;
    }
    .btn-save-changes:hover { color: var(--plum-900); }
 
    .btn-delete-confirm {
        background: linear-gradient(135deg, #F0708C, var(--red-500));
        color: #fff; font-weight: 700; padding: 9px 24px; border-radius: 10px; border: none;
    }
    .btn-delete-confirm:hover { color: #fff; box-shadow: 0 4px 14px rgba(225, 77, 106, 0.4); }
</style>
@endsection
 
@section('extra-js')
<script>
   
    const searchInput = document.getElementById('serviceSearchInput');
    const rows = document.querySelectorAll('.service-row');
    const noResults = document.getElementById('noServicesFound');
 
    function applySearchAndFilters() {
        const term = (searchInput.value || '').toLowerCase().trim();
        const categoryFilter = document.getElementById('filterCategory')?.value || '';
        let visibleCount = 0;
 
        rows.forEach(row => {
            const matchesSearch = row.dataset.name.includes(term);
            const matchesCategory = !categoryFilter || row.dataset.category === categoryFilter;
            const show = matchesSearch && matchesCategory;
            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });
 
        noResults.style.display = visibleCount === 0 ? 'block' : 'none';
    }
 
    searchInput.addEventListener('input', applySearchAndFilters);
 
    document.getElementById('applyFiltersBtn').addEventListener('click', applySearchAndFilters);
    document.getElementById('clearFiltersBtn').addEventListener('click', function () {
        document.getElementById('filterCategory').value = '';
        document.getElementById('filterStatus').value = '';
        applySearchAndFilters();
    });
 
   
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('editServiceName').value = this.dataset.name;
            document.getElementById('editServiceCategory').value = this.dataset.category;
            document.getElementById('editServiceDuration').value = this.dataset.duration;
            document.getElementById('editServicePrice').value = this.dataset.price;
 
            const form = document.getElementById('editServiceForm');
            form.action = form.action.replace(/\/\d+$/, '/' + this.dataset.id).replace(/services\/0/, 'services/' + this.dataset.id);
        });
    });
 
    
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('deleteServiceName').textContent = this.dataset.name;
 
            const form = document.getElementById('deleteServiceForm');
            form.action = form.action.replace(/\/\d+$/, '/' + this.dataset.id).replace(/services\/0/, 'services/' + this.dataset.id);
        });
    });
</script>
@endsection