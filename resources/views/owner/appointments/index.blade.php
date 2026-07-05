@extends('layouts.owner')

@section('title', 'Appointments')

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
            <h2>Appointments</h2>
            <p>Manage all salon appointments</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('owner.appointments.export') }}" class="btn btn-export">
                <i class="bi bi-download me-2"></i> Export
            </a>
            <a href="{{ route('owner.appointments.create') }}" class="btn btn-add-appt">
                <i class="bi bi-plus-lg me-2"></i> New Appointment
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-blue"><i class="bi bi-calendar-event-fill"></i></div>
                <div>
                    <div class="stat-label-sm">Total Today</div>
                    <div class="stat-value-sm">{{ $stats['total_today'] ?? 0 }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-green"><i class="bi bi-calendar-check-fill"></i></div>
                <div>
                    <div class="stat-label-sm">Confirmed</div>
                    <div class="stat-value-sm">{{ $stats['confirmed'] ?? 0 }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-amber"><i class="bi bi-clock-fill"></i></div>
                <div>
                    <div class="stat-label-sm">Pending</div>
                    <div class="stat-value-sm">{{ $stats['pending'] ?? 0 }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-gold"><i class="bi bi-calendar-week-fill"></i></div>
                <div>
                    <div class="stat-label-sm">Revenue Today</div>
                    <div class="stat-value-sm">PKR {{ number_format($stats['revenue_today'] ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="panel-card panel-card-auto mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-9">
                <div class="search-input-wrap">
                    <i class="bi bi-search"></i>
                    <input type="text" id="apptSearchInput" class="form-control input-custom search-input"
                           placeholder="Search appointments by client name...">
                </div>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-filters w-100" data-bs-toggle="modal" data-bs-target="#filtersModal">
                    <i class="bi bi-funnel-fill me-2"></i> Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Appointments Table -->
    <div class="panel-card panel-card-auto">
        <div class="table-responsive">
            <table class="table-custom" id="appointmentsTable">
                <thead>
                    <tr>
                        <th>Booking Ref</th>
                        <th>Client</th>
                        <th>Service</th>
                        <th>Date &amp; Time</th>
                        <th>Stylist</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $statusBadge = [
                            'pending_payment' => 'badge-pending',
                            'confirmed' => 'badge-confirmed',
                            'in_progress' => 'badge-progress',
                            'completed' => 'badge-completed',
                            'cancelled' => 'badge-cancelled',
                        ];
                        $statusDisplay = [
                            'pending_payment' => 'Pending',
                            'confirmed' => 'Confirmed',
                            'in_progress' => 'In Progress',
                            'completed' => 'Completed',
                            'cancelled' => 'Cancelled',
                        ];
                    @endphp

                    @forelse($appointments as $appt)
                        <tr class="appt-row"
                            data-client="{{ strtolower($appt->client->name ?? '') }}"
                            data-status="{{ strtolower($appt->status) }}"
                            data-stylist="{{ strtolower($appt->stylist->name ?? '') }}">
                            <td>
                                <span class="fw-semibold" style="font-size:12px; color:#6B4F62;">{{ $appt->booking_ref ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="cell-name">{{ $appt->client->name ?? 'N/A' }}</div>
                                <div class="cell-sub">{{ $appt->client->email ?? 'N/A' }}</div>
                                <div class="cell-sub">{{ $appt->client->phone ?? 'N/A' }}</div>
                            </td>
                            <td>{{ $appt->service->name ?? 'N/A' }}</td>
                            <td>
                                <div class="cell-name">{{ $appt->appointment_date->format('M d, Y') }}</div>
                                <div class="cell-sub">{{ Carbon\Carbon::parse($appt->start_time)->format('g:i A') }} - {{ Carbon\Carbon::parse($appt->end_time)->format('g:i A') }}</div>
                            </td>
                            <td>{{ $appt->stylist->name ?? 'N/A' }}</td>
                            <td class="amount-gold">PKR {{ number_format($appt->total_amount) }}</td>
                            <td>
                                <span class="badge-status {{ $statusBadge[$appt->status] ?? 'badge-pending' }}">
                                    {{ $statusDisplay[$appt->status] ?? ucfirst($appt->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="action-icons">
                                    <a href="{{ route('owner.appointments.show', ['appointment' => $appt->id]) }}"
                                       class="action-btn view-btn" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('owner.appointments.edit', ['appointment' => $appt->id]) }}"
                                       class="action-btn edit-btn" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" class="action-btn delete-btn"
                                            data-bs-toggle="modal" data-bs-target="#deleteApptModal"
                                            data-id="{{ $appt->id }}"
                                            data-name="{{ $appt->client->name ?? 'N/A' }}"
                                            title="Delete">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-emoji-frown" style="font-size:36px; color:#F08FB4;"></i>
                                <p class="mt-2 mb-0" style="color:#6B4F62;">No appointments found. Create your first appointment!</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div id="noApptsFound" class="text-center py-5" style="display:none;">
                <i class="bi bi-emoji-frown" style="font-size:36px; color:#F08FB4;"></i>
                <p class="mt-2 mb-0" style="color:#6B4F62;">No appointments found matching your search.</p>
            </div>
        </div>
    </div>

@endsection

@push('modals')
    <!-- Delete Modal -->
    <div class="modal fade" id="deleteApptModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <form action="" method="POST" id="deleteApptForm">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body text-center py-4">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size:42px; color:#E14D6A;"></i>
                        <h5 class="mt-3" style="color:#5C2142; font-weight:700;">Delete Appointment?</h5>
                        <p class="mb-0" style="color:#6B4F62;">
                            Are you sure you want to delete the appointment for "<span id="deleteApptName" class="fw-semibold"></span>"? This action cannot be undone.
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

    <!-- Filters Modal -->
    <div class="modal fade" id="filtersModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title">Filter Appointments</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-custom">Status</label>
                        <select id="filterStatus" class="form-select input-custom">
                            <option value="">All Status</option>
                            <option value="pending_payment">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Stylist</label>
                        <select id="filterStylist" class="form-select input-custom">
                            <option value="">All Stylists</option>
                            @foreach ($stylists ?? [] as $stylist)
                                <option value="{{ strtolower($stylist) }}">{{ $stylist }}</option>
                            @endforeach
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
    .btn-add-appt {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 14.5px;
        padding: 11px 22px;
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.35);
        transition: all 0.18s ease;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
    }
    .btn-add-appt:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(232, 85, 136, 0.45);
        color: #ffffff !important;
    }

    .btn-export {
        background: var(--white);
        border: 1.5px solid var(--rose-300) !important;
        color: var(--rose-600) !important;
        font-weight: 600;
        font-size: 14.5px;
        padding: 10px 20px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        transition: all 0.18s ease;
    }
    .btn-export:hover {
        background: var(--blush-50);
        border-color: var(--rose-400) !important;
        color: var(--rose-700) !important;
    }

    .panel-card-auto { height: auto; }

    .stat-card-sm {
        background: var(--white);
        border-radius: var(--radius-lg);
        border: 1px solid var(--blush-200);
        box-shadow: var(--shadow-card);
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
    .stat-label-sm { font-size: 13.5px; color: var(--ink-700); margin-bottom: 2px; }
    .stat-value-sm { font-size: 22px; font-weight: 700; color: var(--plum-900); }

    .icon-blue { background: linear-gradient(135deg, #4A7FE0, #3568C4); }
    .icon-green { background: linear-gradient(135deg, #2EAE7D, #1E8E64); }
    .icon-amber { background: linear-gradient(135deg, #E08A2C, #C47620); }
    .icon-gold { background: linear-gradient(135deg, #D9A441, #C4903A); }

    .search-input-wrap { position: relative; }
    .search-input-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ink-500);
        font-size: 16px;
    }
    .search-input { padding-left: 44px !important; }

    .btn-filters {
        background: var(--white);
        border: 1.5px solid var(--rose-300) !important;
        color: var(--rose-600) !important;
        font-weight: 600;
        font-size: 14.5px;
        padding: 11px 20px;
        border-radius: var(--radius-sm);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.18s ease;
    }
    .btn-filters:hover { background: var(--blush-50); }

    .cell-sub {
        font-size: 12.5px;
        color: var(--ink-500);
    }

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
    .view-btn { background: var(--purple-50); color: var(--purple-500); }
    .view-btn:hover { background: var(--purple-500); color: #fff; }
    .edit-btn { background: var(--blue-50); color: var(--blue-500); }
    .edit-btn:hover { background: var(--blue-500); color: #fff; }
    .delete-btn { background: var(--red-50); color: var(--red-500); }
    .delete-btn:hover { background: var(--red-500); color: #fff; }

    .form-label-custom {
        display: block;
        font-size: 13.5px;
        font-weight: 600;
        color: var(--ink-700);
        margin-bottom: 6px;
    }
    .input-custom {
        background: var(--blush-50) !important;
        border: 1px solid var(--blush-200) !important;
        border-radius: var(--radius-sm) !important;
        color: var(--ink-900) !important;
        font-size: 14.5px;
        padding: 11px 14px !important;
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
        padding: 18px 24px;
    }
    .modal-header-custom .modal-title {
        font-weight: 700;
        color: var(--plum-800);
    }
    .modal-body { padding: 22px 24px; }
    .modal-footer-custom {
        border-top: 1px solid var(--blush-100);
        padding: 16px 24px;
        gap: 10px;
    }

    .btn-cancel-modal {
        background: var(--white);
        border: 1px solid var(--blush-200);
        color: var(--ink-700);
        font-weight: 600;
        padding: 9px 20px;
        border-radius: 10px;
        transition: all 0.18s ease;
    }
    .btn-cancel-modal:hover { background: var(--blush-50); }

    .btn-save-changes {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        padding: 9px 22px;
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
        padding: 9px 24px;
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

    .badge-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-confirmed { background: #E8F5E9; color: #2EAE7D; }
    .badge-pending { background: #FFF3CD; color: #856404; }
    .badge-progress { background: #D1ECF1; color: #0C5460; }
    .badge-completed { background: #D4EDDA; color: #155724; }
    .badge-cancelled { background: #F8D7DA; color: #721C24; }

    .amount-gold {
        color: #E85588;
        font-weight: 700;
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
</style>
@endsection

@section('extra-js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // SEARCH
        const searchInput = document.getElementById('apptSearchInput');
        const rows = document.querySelectorAll('.appt-row');
        const noResults = document.getElementById('noApptsFound');

        function applySearchAndFilters() {
            const term = (searchInput.value || '').toLowerCase().trim();
            const statusFilter = document.getElementById('filterStatus')?.value || '';
            const stylistFilter = document.getElementById('filterStylist')?.value || '';
            let visibleCount = 0;

            rows.forEach(row => {
                const matchesSearch = row.dataset.client.includes(term);
                const matchesStatus = !statusFilter || row.dataset.status === statusFilter;
                const matchesStylist = !stylistFilter || row.dataset.stylist === stylistFilter;
                const show = matchesSearch && matchesStatus && matchesStylist;
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

        if (applyFiltersBtn) {
            applyFiltersBtn.addEventListener('click', applySearchAndFilters);
        }

        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', function() {
                document.getElementById('filterStatus').value = '';
                document.getElementById('filterStylist').value = '';
                applySearchAndFilters();
            });
        }

        // DELETE MODAL
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('deleteApptName').textContent = this.dataset.name;

                const form = document.getElementById('deleteApptForm');
                if (form) {
                    const baseUrl = "{{ route('owner.appointments.destroy', ['appointment' => 0]) }}";
                    form.action = baseUrl.replace('/0', '/' + this.dataset.id);
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