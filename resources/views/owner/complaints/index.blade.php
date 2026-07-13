@extends('layouts.owner')

@section('title', 'Complaints')

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
            <h2>Complaints</h2>
            <p>Manage customer complaints and feedback</p>
        </div>
    </div>

    <!-- 4 STATS CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-total"><i class="bi bi-exclamation-triangle-fill"></i></div>
                <div>
                    <div class="stat-label-sm">Total Complaints</div>
                    <div class="stat-value-sm">{{ $counts['total'] ?? 0 }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-pending"><i class="bi bi-clock-fill"></i></div>
                <div>
                    <div class="stat-label-sm">Pending</div>
                    <div class="stat-value-sm">{{ $counts['pending'] ?? 0 }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-progress"><i class="bi bi-arrow-repeat"></i></div>
                <div>
                    <div class="stat-label-sm">In Progress</div>
                    <div class="stat-value-sm">{{ $counts['in_progress'] ?? 0 }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-resolved"><i class="bi bi-check-circle-fill"></i></div>
                <div>
                    <div class="stat-label-sm">Resolved</div>
                    <div class="stat-value-sm">{{ $counts['resolved'] ?? 0 }}</div>
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
                    <input type="text" id="complaintSearchInput" class="search-input"
                           placeholder="Search complaints by client or subject...">
                </div>
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-filters w-100" data-bs-toggle="modal" data-bs-target="#filtersModal">
                    <i class="bi bi-funnel-fill me-2"></i> Filters
                </button>
            </div>
        </div>
    </div>

    <!-- COMPLAINTS TABLE -->
    <div class="panel-card panel-card-auto">
        <div class="table-responsive">
            <table class="table-custom" id="complaintsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Client</th>
                        <th>Subject</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $statusBadge = [
                            'pending' => 'badge-pending',
                            'in_progress' => 'badge-progress',
                            'resolved' => 'badge-resolved',
                            'closed' => 'badge-closed',
                            'escalated' => 'badge-escalated',
                            'rejected' => 'badge-rejected',
                        ];
                        $statusDisplay = [
                            'pending' => 'Pending',
                            'in_progress' => 'In Progress',
                            'resolved' => 'Resolved',
                            'closed' => 'Closed',
                            'escalated' => 'Escalated',
                            'rejected' => 'Rejected',
                        ];
                    @endphp

                    @forelse($complaints as $complaint)
                        <tr class="complaint-row"
                            data-client="{{ strtolower($complaint->client->name ?? '') }}"
                            data-subject="{{ strtolower($complaint->subject) }}"
                            data-status="{{ strtolower($complaint->status) }}"
                            data-type="{{ strtolower($complaint->type) }}">
                            <td class="cell-name">#{{ $complaint->id }}</td>
                            <td>
                                <div class="cell-name">{{ $complaint->client->name ?? 'N/A' }}</div>
                                <div class="cell-sub">{{ $complaint->client->email ?? 'N/A' }}</div>
                            </td>
                            <td>{{ Str::limit($complaint->subject, 35) }}</td>
                            <td>
                                <span class="badge-type">
                                    <i class="bi {{ $complaint->type_icon }} me-1"></i>
                                    {{ $complaint->type_label }}
                                </span>
                            </td>
                            <td>
                                <div class="cell-name">{{ $complaint->created_at->format('M d, Y') }}</div>
                                <div class="cell-sub">{{ $complaint->created_at->format('h:i A') }}</div>
                            </td>
                            <td>
                                <span class="badge-status {{ $statusBadge[$complaint->status] ?? 'badge-pending' }}">
                                    {{ $statusDisplay[$complaint->status] ?? ucfirst($complaint->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="action-icons">
                                    <a href="{{ route('owner.complaints.show', $complaint->id) }}"
                                       class="action-btn view-btn">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-check-circle" style="font-size:36px; color:#2EAE7D;"></i>
                                <p class="mt-2 mb-0" style="color:#6B4F62;">No complaints found. All clear!</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div id="noComplaintsFound" class="text-center py-5" style="display:none;">
                <i class="bi bi-emoji-frown" style="font-size:36px; color:#F08FB4;"></i>
                <p class="mt-2 mb-0" style="color:#6B4F62;">No complaints found matching your search.</p>
            </div>
        </div>
    </div>

@endsection

@push('modals')
    <!-- FILTERS MODAL -->
    <div class="modal fade" id="filtersModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title">Filter Complaints</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-custom">Status</label>
                        <select id="filterStatus" class="form-select input-custom">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                            <option value="escalated">Escalated</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Type</label>
                        <select id="filterType" class="form-select input-custom">
                            <option value="">All Types</option>
                            <option value="service">Service Issue</option>
                            <option value="staff">Staff Behavior</option>
                            <option value="payment">Payment Issue</option>
                            <option value="product">Product Issue</option>
                            <option value="other">Other</option>
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

    .panel-card-auto { height: auto; }

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
        transition: all 0.3s ease;
    }
    .stat-card-sm:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
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

    .icon-total { background: linear-gradient(135deg, #9B6FD1, #7E56B0); }
    .icon-pending { background: linear-gradient(135deg, #E08A2C, #C47620); }
    .icon-progress { background: linear-gradient(135deg, #4A7FE0, #3568C4); }
    .icon-resolved { background: linear-gradient(135deg, #2EAE7D, #1E8E64); }

    .stat-label-sm { font-size: 13.5px; color: #8a7a88; margin-bottom: 2px; }
    .stat-value-sm { font-size: 22px; font-weight: 700; color: #2d1f2c; }

    .search-input-wrap {
        display: flex;
        align-items: center;
        background: #fcf6f9;
        border: 1px solid #f0e8ed;
        border-radius: 10px;
        padding: 0 16px;
        transition: all 0.2s ease;
    }

    .search-input-wrap i {
        color: #b09aa8;
        font-size: 18px;
        margin-right: 12px;
    }

    .search-input-wrap .search-input {
        border: none !important;
        background: transparent !important;
        outline: none !important;
        padding: 11px 0 !important;
        font-size: 14.5px;
        color: #2d1f2c;
        width: 100%;
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
        background: #fff;
        border: 1.5px solid #FF6B9D !important;
        color: #E85588 !important;
        font-weight: 600;
        font-size: 14.5px;
        padding: 11px 20px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.18s ease;
    }
    .btn-filters:hover {
        background: #E85588 !important;
        color: #ffffff !important;
        border-color: #E85588 !important;
    }

    .cell-sub { font-size: 12.5px; color: #8a7a88; }

    .badge-type {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        background: #fcf6f9;
        color: #4a3a48;
        border: 1px solid #f0e8ed;
    }
    .badge-type i { color: #E85588; }

    .badge-status {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .badge-pending { background: #FDF6E8; color: #C4903A; }
    .badge-progress { background: #E8F0FE; color: #3568C4; }
    .badge-resolved { background: #E8F5ED; color: #1E8E64; }
    .badge-closed { background: #F3F4F6; color: #6B7280; }
    .badge-escalated { background: #FCE4EC; color: #D45482; }
    .badge-rejected { background: #FEE2E2; color: #DC2626; }

    .action-icons { display: flex; gap: 8px; }
    .action-btn {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none;
    }
    .view-btn { background: #F0E8FD; color: #7E56B0; }
    .view-btn:hover { background: #7E56B0; color: #fff; }

    .form-label-custom { display: block; font-size: 13.5px; font-weight: 600; color: #4a3a48; margin-bottom: 6px; }
    .input-custom {
        background: #fcf6f9 !important;
        border: 1px solid #f0e8ed !important;
        border-radius: 10px !important;
        color: #2d1f2c !important;
        font-size: 14.5px;
        padding: 11px 14px !important;
        width: 100%;
    }
    .input-custom:focus {
        background: #fff !important;
        border-color: #E85588 !important;
        box-shadow: 0 0 0 3px rgba(232, 85, 136, 0.15) !important;
        outline: none;
    }

    .modal-content-custom { border-radius: 16px; border: none; overflow: hidden; }
    .modal-header-custom {
        background: #fcf6f9;
        border-bottom: 1px solid #f5eef2;
        padding: 18px 24px;
    }
    .modal-header-custom .modal-title { font-weight: 700; color: #2d1f2c; }
    .modal-body { padding: 22px 24px; }
    .modal-footer-custom { border-top: 1px solid #f5eef2; padding: 16px 24px; }

    .btn-cancel-modal {
        background: #fff;
        border: 1.5px solid #FF6B9D;
        color: #E85588;
        font-weight: 600;
        padding: 9px 20px;
        border-radius: 10px;
        transition: all 0.15s ease;
    }
    .btn-cancel-modal:hover {
        background: #E85588;
        color: #ffffff !important;
        border-color: #E85588;
    }

    .btn-save-changes {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        padding: 9px 22px;
        border-radius: 10px;
        border: none;
        transition: all 0.15s ease;
    }
    .btn-save-changes:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.35);
        color: #ffffff !important;
    }

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

    .alert {
        border-radius: 12px;
        border: none;
        padding: 0.8rem 1.2rem;
    }
    .alert-success { background: #E8F5E9; color: #1B5E20; }
    .alert-danger { background: #FCE4EC; color: #880E4F; }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch !important;
        }
        .action-icons {
            flex-wrap: wrap;
        }
        .stat-card-sm {
            padding: 12px 14px;
        }
        .stat-value-sm {
            font-size: 18px;
        }
    }
</style>
@endsection

@section('extra-js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('complaintSearchInput');
        const rows = document.querySelectorAll('.complaint-row');
        const noResults = document.getElementById('noComplaintsFound');

        function applySearchAndFilters() {
            const term = (searchInput.value || '').toLowerCase().trim();
            const statusFilter = document.getElementById('filterStatus')?.value || '';
            const typeFilter = document.getElementById('filterType')?.value || '';
            let visibleCount = 0;

            rows.forEach(row => {
                const matchesSearch = row.dataset.client.includes(term) || row.dataset.subject.includes(term);
                const matchesStatus = !statusFilter || row.dataset.status === statusFilter;
                const matchesType = !typeFilter || row.dataset.type === typeFilter;
                const show = matchesSearch && matchesStatus && matchesType;
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

        const applyFiltersBtn = document.getElementById('applyFiltersBtn');
        const clearFiltersBtn = document.getElementById('clearFiltersBtn');

        if (applyFiltersBtn) {
            applyFiltersBtn.addEventListener('click', applySearchAndFilters);
        }

        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', function() {
                document.getElementById('filterStatus').value = '';
                document.getElementById('filterType').value = '';
                applySearchAndFilters();
            });
        }

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