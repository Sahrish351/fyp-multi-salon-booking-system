@extends('layouts.owner')

@section('title', 'Services')

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

    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>Services</h2>
            <p>Manage your salon services</p>
        </div>
        <a href="{{ route('owner.services.create') }}" class="btn btn-add-service">
            <i class="bi bi-plus-lg me-2"></i> Add Service
        </a>
    </div>

    <!-- 4 STATS CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-gold"><i class="bi bi-scissors"></i></div>
                <div>
                    <div class="stat-label-sm">Total Services</div>
                    <div class="stat-value-sm">{{ $stats['total_services'] ?? 0 }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-green"><i class="bi bi-check-circle-fill"></i></div>
                <div>
                    <div class="stat-label-sm">Active Services</div>
                    <div class="stat-value-sm">{{ $stats['active_services'] ?? 0 }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-red"><i class="bi bi-x-circle-fill"></i></div>
                <div>
                    <div class="stat-label-sm">Inactive Services</div>
                    <div class="stat-value-sm">{{ $stats['inactive_services'] ?? 0 }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-blue"><i class="bi bi-clock-fill"></i></div>
                <div>
                    <div class="stat-label-sm">Avg. Duration</div>
                    <div class="stat-value-sm">{{ $stats['avg_duration'] ?? 0 }} min</div>
                </div>
            </div>
        </div>
    </div>

    <!-- SEARCH & FILTERS -->
    <div class="panel-card panel-card-auto mb-4">
        <div class="row g-2 align-items-center">
            <div class="col-md-8">
                <div class="search-input-wrap">
    <i class="bi bi-search"></i>
    <input type="text" id="serviceSearchInput" class="search-input"
           placeholder="Search services by name...">
</div>
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-filters w-100" data-bs-toggle="modal" data-bs-target="#filtersModal">
                    <i class="bi bi-funnel-fill me-2"></i> Filters
                </button>
            </div>
        </div>
    </div>

    <!-- SERVICES TABLE -->
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
                    @forelse($services as $service)
                        <tr class="service-row"
                            data-name="{{ strtolower($service->name) }}"
                            data-category="{{ strtolower($service->category->name ?? '') }}"
                            data-status="{{ strtolower($service->is_active ? 'active' : 'inactive') }}">
                            <td class="cell-name">{{ $service->name }}</td>
                            <td>{{ $service->category->name ?? 'Uncategorized' }}</td>
                            <td>{{ $service->duration }} min</td>
                            <td class="amount-gold">PKR {{ number_format($service->price) }}</td>
                            <td>{{ $service->bookings ?? 0 }}</td>
                            <td>
                                <span class="badge-status {{ $service->is_active ? 'badge-confirmed' : 'badge-cancelled' }}">
                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-icons">
                                    <a href="{{ route('owner.services.show', $service->id) }}"
                                       class="action-btn view-btn">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('owner.services.edit', $service->id) }}"
                                       class="action-btn edit-btn">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" class="action-btn delete-btn"
                                            data-bs-toggle="modal" data-bs-target="#deleteServiceModal"
                                            data-id="{{ $service->id }}"
                                            data-name="{{ $service->name }}">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-emoji-frown" style="font-size:36px; color:#F08FB4;"></i>
                                <p class="mt-2 mb-0" style="color:#6B4F62;">No services found. Create your first service!</p>
                            </td>
                        </tr>
                    @endforelse
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
    <!-- DELETE MODAL -->
    <div class="modal fade" id="deleteServiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <form action="" method="POST" id="deleteServiceForm">
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

    <!-- FILTERS MODAL -->
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
                            @php
                                $uniqueCategories = $services->pluck('category.name')->unique()->filter();
                            @endphp
                            @foreach($uniqueCategories as $cat)
                                @if($cat)
                                    <option value="{{ strtolower($cat) }}">{{ $cat }}</option>
                                @endif
                            @endforeach
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
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 14.5px;
        padding: 10px 20px;
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.35);
        transition: all 0.18s ease;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
    }
    .btn-add-service:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(232, 85, 136, 0.45);
        color: #ffffff !important;
    }

    .stat-card-sm {
        background: var(--white);
        border-radius: var(--radius-lg);
        border: 1px solid var(--blush-200);
        box-shadow: var(--shadow-card);
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        height: 80px;
        transition: all 0.3s ease;
    }
    .stat-card-sm:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-card-hover);
    }
    .stat-card-sm .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        font-size: 18px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
    }
    .stat-label-sm {
        font-size: 12px;
        color: var(--ink-700);
        margin-bottom: 1px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .stat-value-sm {
        font-size: 18px;
        font-weight: 700;
        color: var(--plum-900);
    }

    .icon-gold { background: linear-gradient(135deg, #D9A441, #C4903A); }
    .icon-green { background: linear-gradient(135deg, #2EAE7D, #1E8E64); }
    .icon-red { background: linear-gradient(135deg, #E14D6A, #C0392B); }
    .icon-blue { background: linear-gradient(135deg, #4A7FE0, #3568C4); }

    .search-input-wrap {
    display: flex;
    align-items: center;
    background: #fcf6f9;
    border: 1px solid #f0e8ed;
    border-radius: 10px;
    padding: 0 16px;
    transition: all 0.2s ease;
    width: 100%;
}

.search-input-wrap i {
    color: #b09aa8;
    font-size: 18px;
    margin-right: 12px;
    flex-shrink: 0;
}

.search-input-wrap .search-input {
    border: none !important;
    background: transparent !important;
    outline: none !important;
    padding: 11px 0 !important;
    font-size: 14.5px;
    color: #2d1f2c;
    width: 100%;
    height: auto !important;
    box-shadow: none !important;
}

.search-input-wrap .search-input::placeholder {
    color: #b09aa8;
}

.search-input-wrap:focus-within {
    border-color: #E85588;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(232, 85, 136, 0.15);
}

    .btn-filters {
        background: var(--white);
        border: 1.5px solid var(--rose-300) !important;
        color: var(--rose-600) !important;
        font-weight: 600;
        font-size: 14px;
        padding: 10px 16px;
        border-radius: var(--radius-sm);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.18s ease;
        height: 46px;
    }
    .btn-filters:hover {
        background: var(--blush-50);
        border-color: var(--rose-400) !important;
    }

    .panel-card-auto {
        height: auto !important;
        padding: 1.2rem 1.5rem !important;
    }
    .panel-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.2rem 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid rgba(0,0,0,0.04);
        transition: all 0.3s ease;
        height: 100%;
    }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
    }
    .table-custom thead th {
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: var(--ink-500);
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 0 10px 12px;
        border-bottom: 1px solid var(--blush-200);
    }
    .table-custom tbody td {
        padding: 12px 10px;
        font-size: 14px;
        color: var(--ink-900);
        border-bottom: 1px solid var(--blush-100);
    }
    .table-custom tbody tr:last-child td {
        border-bottom: none;
    }
    .table-custom tbody tr:hover {
        background: var(--blush-50);
    }
    .cell-name {
        font-weight: 600;
        color: var(--plum-800);
    }
    .amount-gold {
        color: #E85588;
        font-weight: 700;
    }

    .badge-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-confirmed { background: #E8F5E9; color: #2EAE7D; }
    .badge-cancelled { background: #FCE4EC; color: #E14D6A; }

    .action-icons { display: flex; gap: 6px; }
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .edit-btn { background: #E9F0FD; color: #4A7FE0; }
    .edit-btn:hover { background: #4A7FE0; color: #fff; }
    .view-btn { background: #F1E9FB; color: #9B6FD1; }
    .view-btn:hover { background: #9B6FD1; color: #fff; }
    .delete-btn { background: #FCE9ED; color: #E14D6A; }
    .delete-btn:hover { background: #E14D6A; color: #fff; }

    .form-label-custom {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--ink-700);
        margin-bottom: 5px;
    }
    .input-custom {
        background: var(--blush-50) !important;
        border: 1.5px solid var(--blush-200) !important;
        border-radius: var(--radius-sm) !important;
        color: var(--ink-900) !important;
        font-size: 14px;
        padding: 10px 14px !important;
        transition: all 0.25s ease;
        width: 100%;
    }
    .input-custom:focus {
        background: #fff !important;
        border-color: #FF6B9D !important;
        box-shadow: 0 0 0 3px rgba(255, 107, 157, 0.15) !important;
        outline: none;
    }

    .modal-content-custom {
        border-radius: var(--radius-lg);
        border: none;
        overflow: hidden;
    }
    .modal-header-custom {
        background: var(--blush-50);
        border-bottom: 1px solid var(--blush-200);
        padding: 16px 24px;
    }
    .modal-header-custom .modal-title {
        font-weight: 700;
        color: var(--plum-800);
    }
    .modal-body { padding: 20px 24px; }
    .modal-footer-custom {
        border-top: 1px solid var(--blush-100);
        padding: 14px 24px;
        gap: 10px;
    }

    .btn-cancel-modal {
        background: var(--white);
        border: 1px solid var(--blush-200);
        color: var(--ink-700);
        font-weight: 600;
        padding: 8px 18px;
        border-radius: 10px;
        transition: all 0.18s ease;
    }
    .btn-cancel-modal:hover {
        background: var(--blush-50);
        color: var(--ink-900);
    }

    .btn-save-changes {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        padding: 8px 20px;
        border-radius: 10px;
        border: none;
        transition: all 0.18s ease;
    }
    .btn-save-changes:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(232, 85, 136, 0.4);
        color: #ffffff !important;
    }

    .btn-delete-confirm {
        background: linear-gradient(135deg, #F0708C, #E14D6A);
        color: #fff;
        font-weight: 700;
        padding: 8px 22px;
        border-radius: 10px;
        border: none;
        transition: all 0.18s ease;
    }
    .btn-delete-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(225, 77, 106, 0.4);
        color: #fff;
    }

    .alert {
        border-radius: 12px;
        border: none;
        padding: 0.8rem 1.2rem;
    }
    .alert-success {
        background: #E8F5E9;
        color: #1B5E20;
    }
    .alert-danger {
        background: #FCE4EC;
        color: #880E4F;
    }

    .page-header {
        margin-bottom: 1.5rem;
    }
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
</style>
@endsection

@section('extra-js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // SEARCH
        const searchInput = document.getElementById('serviceSearchInput');
        const rows = document.querySelectorAll('.service-row');
        const noResults = document.getElementById('noServicesFound');

        function applySearchAndFilters() {
            const term = (searchInput.value || '').toLowerCase().trim();
            const categoryFilter = document.getElementById('filterCategory')?.value || '';
            const statusFilter = document.getElementById('filterStatus')?.value || '';
            let visibleCount = 0;

            rows.forEach(row => {
                const matchesSearch = row.dataset.name.includes(term);
                const matchesCategory = !categoryFilter || row.dataset.category === categoryFilter;
                const matchesStatus = !statusFilter || row.dataset.status === statusFilter;
                const show = matchesSearch && matchesCategory && matchesStatus;
                row.style.display = show ? '' : 'none';
                if (show) visibleCount++;
            });

            if (noResults) {
                noResults.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        }

        if (searchInput) {
            searchInput.addEventListener('input', applySearchAndFilters);
        }

        // FILTERS
        const applyFiltersBtn = document.getElementById('applyFiltersBtn');
        const clearFiltersBtn = document.getElementById('clearFiltersBtn');
        const filterCategory = document.getElementById('filterCategory');
        const filterStatus = document.getElementById('filterStatus');

        if (applyFiltersBtn) {
            applyFiltersBtn.addEventListener('click', applySearchAndFilters);
        }

        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', function() {
                if (filterCategory) filterCategory.value = '';
                if (filterStatus) filterStatus.value = '';
                applySearchAndFilters();
            });
        }

        // DELETE MODAL
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const serviceId = this.dataset.id;
                const serviceName = this.dataset.name;
                
                const nameSpan = document.getElementById('deleteServiceName');
                if (nameSpan) {
                    nameSpan.textContent = serviceName;
                }
                
                const form = document.getElementById('deleteServiceForm');
                if (form) {
                    const baseUrl = "{{ route('owner.services.destroy', ['service' => 0]) }}";
                    form.action = baseUrl.replace('/0', '/' + serviceId);
                }
            });
        });

        // AUTO-DISMISS ALERTS
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                alert.classList.remove('show');
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 300);
            }, 5000);
        });
    });
</script>
@endsection