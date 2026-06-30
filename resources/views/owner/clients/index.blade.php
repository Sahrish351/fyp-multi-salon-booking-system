
@extends('layouts.owner')
 
@section('title', 'Clients')
 
@section('content')
 

    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>Clients</h2>
            <p>Manage your client database</p>
        </div>
        <a href="{{ route('owner.clients.create') }}" class="btn btn-add-client">
            <i class="bi bi-plus-lg me-2"></i> Add Client
        </a>
    </div>
 
    
    <div class="row g-4 mb-4">
 
        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-blue"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="stat-label-sm">Total Clients</div>
                    <div class="stat-value-sm">{{ number_format($stats['total'] ?? 1245) }}</div>
                </div>
            </div>
        </div>
 
        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-gold"><i class="bi bi-graph-up-arrow"></i></div>
                <div>
                    <div class="stat-label-sm">VIP Clients</div>
                    <div class="stat-value-sm">{{ $stats['vip'] ?? 186 }}</div>
                </div>
            </div>
        </div>
 
        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-green"><i class="bi bi-person-plus-fill"></i></div>
                <div>
                    <div class="stat-label-sm">New This Month</div>
                    <div class="stat-value-sm">{{ $stats['new_this_month'] ?? 48 }}</div>
                </div>
            </div>
        </div>
 
        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-purple"><i class="bi bi-calendar-check-fill"></i></div>
                <div>
                    <div class="stat-label-sm">Active Today</div>
                    <div class="stat-value-sm">{{ $stats['active_today'] ?? 24 }}</div>
                </div>
            </div>
        </div>
 
    </div>
 
  
    <div class="panel-card panel-card-auto mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-9">
                <div class="search-input-wrap">
                    <i class="bi bi-search"></i>
                    <input type="text" id="clientSearchInput" class="form-control input-custom search-input"
                           placeholder="Search clients...">
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
            <table class="table-custom" id="clientsTable">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Join Date</th>
                        <th>Total Visits</th>
                        <th>Total Spent</th>
                        <th>Last Visit</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $statusBadge = [
                            'VIP'     => 'badge-vip',
                            'Regular' => 'badge-regular',
                            'New'     => 'badge-confirmed',
                            'Inactive'=> 'badge-cancelled',
                        ];
                    @endphp
 
                    @foreach ($clients as $client)
                        <tr class="client-row"
                            data-name="{{ strtolower($client['name']) }}"
                            data-status="{{ strtolower($client['status']) }}">
                            <td>
                                <div class="cell-name">{{ $client['name'] }}</div>
                                <div class="cell-sub">{{ $client['email'] }}</div>
                                <div class="cell-sub">{{ $client['phone'] }}</div>
                            </td>
                            <td>{{ $client['join_date'] }}</td>
                            <td class="cell-name">{{ $client['total_visits'] }}</td>
                            <td class="amount-gold">${{ number_format($client['total_spent']) }}</td>
                            <td>{{ $client['last_visit'] }}</td>
                            <td>
                                <span class="badge-status {{ $statusBadge[$client['status']] ?? 'badge-regular' }}">
                                    {{ $client['status'] }}
                                </span>
                            </td>
                            <td>
                                <div class="action-icons">
                                    <a href="{{ route('owner.clients.show', ['client' => $client['id']]) }}"
                                       class="action-btn view-btn">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('owner.clients.edit', ['client' => $client['id']]) }}"
                                       class="action-btn edit-btn">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" class="action-btn delete-btn"
                                            data-bs-toggle="modal" data-bs-target="#deleteClientModal"
                                            data-id="{{ $client['id'] }}"
                                            data-name="{{ $client['name'] }}">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
 
            <div id="noClientsFound" class="text-center py-5" style="display:none;">
                <i class="bi bi-emoji-frown" style="font-size:36px; color:#F08FB4;"></i>
                <p class="mt-2 mb-0" style="color:#6B4F62;">No clients found matching your search.</p>
            </div>
        </div>
    </div>
 
@endsection
 

@push('modals')
 
 
    <div class="modal fade" id="deleteClientModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <form action="{{ route('owner.clients.destroy', ['client' => 0]) }}" method="POST" id="deleteClientForm">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body text-center py-4">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size:42px; color:#E14D6A;"></i>
                        <h5 class="mt-3" style="color:#5C2142; font-weight:700;">Delete Client?</h5>
                        <p class="mb-0" style="color:#6B4F62;">
                            Are you sure you want to delete "<span id="deleteClientName" class="fw-semibold"></span>"? This action cannot be undone.
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
                    <h5 class="modal-title">Filter Clients</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-custom">Status</label>
                        <select id="filterStatus" class="form-select input-custom">
                            <option value="">All Status</option>
                            <option value="vip">VIP</option>
                            <option value="regular">Regular</option>
                            <option value="new">New</option>
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
    .btn-add-client {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        color: var(--plum-900); font-weight: 700; font-size: 14.5px;
        padding: 11px 22px; border-radius: 10px; border: none;
        box-shadow: 0 4px 14px rgba(217, 164, 65, 0.35); transition: all 0.18s ease;
        display: inline-flex; align-items: center; white-space: nowrap;
    }
    .btn-add-client:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(217, 164, 65, 0.5); color: var(--plum-900); }
 
    .panel-card-auto { height: auto; }
 
    .stat-card-sm {
        background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--blush-200);
        box-shadow: var(--shadow-card); padding: 18px 20px; display: flex; align-items: center; gap: 16px; height: 100%;
    }
    .stat-card-sm .stat-icon { width: 50px; height: 50px; border-radius: 14px; font-size: 20px; flex-shrink: 0; }
    .stat-label-sm { font-size: 13.5px; color: var(--ink-700); margin-bottom: 2px; }
    .stat-value-sm { font-size: 22px; font-weight: 700; color: var(--plum-900); }
 
    .search-input-wrap { position: relative; }
    .search-input-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--ink-500); }
    .search-input { padding-left: 40px !important; }
 
    .btn-filters {
        background: var(--white); border: 1px solid var(--rose-300) !important; color: var(--rose-600) !important;
        font-weight: 600; font-size: 14.5px; padding: 11px 20px; border-radius: var(--radius-sm);
        display: inline-flex; align-items: center; justify-content: center; transition: all 0.18s ease;
    }
    .btn-filters:hover { background: var(--blush-50); }
 
    .cell-sub { font-size: 12.5px; color: var(--ink-500); }
 
    .badge-vip { background: #FCEFDE; color: var(--gold-600); }
    .badge-regular { background: var(--blue-50); color: var(--blue-500); }
 
    .action-icons { display: flex; gap: 8px; }
    .action-btn {
        width: 34px; height: 34px; border-radius: 8px; border: none; display: inline-flex;
        align-items: center; justify-content: center; font-size: 15px; cursor: pointer; transition: all 0.15s ease;
        text-decoration: none;
    }
    .view-btn { background: var(--purple-50); color: var(--purple-500); }
    .view-btn:hover { background: var(--purple-500); color: #fff; }
    .edit-btn { background: var(--blue-50); color: var(--blue-500); }
    .edit-btn:hover { background: var(--blue-500); color: #fff; }
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
   
    const searchInput = document.getElementById('clientSearchInput');
    const rows = document.querySelectorAll('.client-row');
    const noResults = document.getElementById('noClientsFound');
 
    function applySearchAndFilters() {
        const term = (searchInput.value || '').toLowerCase().trim();
        const statusFilter = document.getElementById('filterStatus')?.value || '';
        let visibleCount = 0;
 
        rows.forEach(row => {
            const matchesSearch = row.dataset.name.includes(term);
            const matchesStatus = !statusFilter || row.dataset.status === statusFilter;
            const show = matchesSearch && matchesStatus;
            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });
 
        noResults.style.display = visibleCount === 0 ? 'block' : 'none';
    }
 
    searchInput.addEventListener('input', applySearchAndFilters);
 
    document.getElementById('applyFiltersBtn').addEventListener('click', applySearchAndFilters);
    document.getElementById('clearFiltersBtn').addEventListener('click', function () {
        document.getElementById('filterStatus').value = '';
        applySearchAndFilters();
    });
 
   
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('deleteClientName').textContent = this.dataset.name;
 
            const form = document.getElementById('deleteClientForm');
            form.action = form.action.replace(/clients\/\d+$/, 'clients/' + this.dataset.id);
        });
    });
</script>
@endsection