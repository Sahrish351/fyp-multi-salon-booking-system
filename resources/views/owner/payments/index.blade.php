
@extends('layouts.owner')
 
@section('title', 'Payments')
 
@section('content')
 
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>Payments</h2>
            <p>Track and manage all transactions</p>
        </div>
        <a href="{{ route('owner.payments.create') }}" class="btn btn-add-payment">
            <i class="bi bi-plus-lg me-2"></i> Record Payment
        </a>
    </div>
 

    <div class="row g-4 mb-4">
 
        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-green"><i class="bi bi-currency-dollar"></i></div>
                <div>
                    <div class="stat-label-sm">Total Revenue</div>
                    <div class="stat-value-sm">${{ number_format($stats['total_revenue'] ?? 45280) }}</div>
                </div>
            </div>
        </div>
 
        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-blue"><i class="bi bi-credit-card-fill"></i></div>
                <div>
                    <div class="stat-label-sm">Completed</div>
                    <div class="stat-value-sm">${{ number_format($stats['completed'] ?? 42860) }}</div>
                </div>
            </div>
        </div>
 
        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-amber"><i class="bi bi-exclamation-circle-fill"></i></div>
                <div>
                    <div class="stat-label-sm">Pending</div>
                    <div class="stat-value-sm">${{ number_format($stats['pending'] ?? 2420) }}</div>
                </div>
            </div>
        </div>
 
        <div class="col-md-6 col-lg-3">
            <div class="stat-card-sm">
                <div class="stat-icon icon-gold"><i class="bi bi-graph-up-arrow"></i></div>
                <div>
                    <div class="stat-label-sm">Today's Total</div>
                    <div class="stat-value-sm">${{ number_format($stats['today_total'] ?? 2840) }}</div>
                </div>
            </div>
        </div>
 
    </div>
 

    <div class="panel-card panel-card-auto mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-6">
                <div class="search-input-wrap">
                    <i class="bi bi-search"></i>
                    <input type="text" id="paymentSearchInput" class="form-control input-custom search-input"
                           placeholder="Search payments...">
                </div>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-filters w-100" data-bs-toggle="modal" data-bs-target="#filtersModal">
                    <i class="bi bi-funnel-fill me-2"></i> Filters
                </button>
            </div>
            <div class="col-md-3">
                <a href="{{ route('owner.payments.export') }}" class="btn btn-export w-100">
                    <i class="bi bi-download me-2"></i> Export
                </a>
            </div>
        </div>
    </div>
 
   
    <div class="panel-card panel-card-auto">
        <div class="table-responsive">
            <table class="table-custom" id="paymentsTable">
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Client</th>
                        <th>Service</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Date &amp; Time</th>
                        <th>Invoice</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $statusBadge = [
                            'Completed' => 'badge-completed',
                            'Pending'   => 'badge-pending',
                            'Failed'    => 'badge-cancelled',
                            'Refunded'  => 'badge-progress',
                        ];
                    @endphp
 
                    @foreach ($payments as $payment)
                        <tr class="payment-row"
                            data-client="{{ strtolower($payment['client_name']) }}"
                            data-status="{{ strtolower($payment['status']) }}"
                            data-method="{{ strtolower($payment['method']) }}">
                            <td class="cell-name">{{ $payment['payment_id'] }}</td>
                            <td>
                                <div class="cell-name">{{ $payment['client_name'] }}</div>
                                <div class="cell-sub">{{ $payment['client_email'] }}</div>
                            </td>
                            <td>{{ $payment['service'] }}</td>
                            <td class="amount-gold">${{ $payment['amount'] }}</td>
                            <td>{{ $payment['method'] }}</td>
                            <td>
                                <div class="cell-name">{{ $payment['date'] }}</div>
                                <div class="cell-sub">{{ $payment['time'] }}</div>
                            </td>
                            <td>{{ $payment['invoice_no'] }}</td>
                            <td>
                                <span class="badge-status {{ $statusBadge[$payment['status']] ?? 'badge-pending' }}">
                                    {{ $payment['status'] }}
                                </span>
                            </td>
                            <td>
                                <div class="action-icons">
                                    <a href="{{ route('owner.payments.show', ['payment' => $payment['id']]) }}"
                                       class="action-btn view-btn">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('owner.appointments.invoice', ['id' => $payment['id']]) }}"
                                       class="action-btn download-btn" title="Download invoice">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
 
            <div id="noPaymentsFound" class="text-center py-5" style="display:none;">
                <i class="bi bi-emoji-frown" style="font-size:36px; color:#F08FB4;"></i>
                <p class="mt-2 mb-0" style="color:#6B4F62;">No payments found matching your search.</p>
            </div>
        </div>
    </div>
 
@endsection
 

@push('modals')
 
    
    <div class="modal fade" id="filtersModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title">Filter Payments</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-custom">Status</label>
                        <select id="filterStatus" class="form-select input-custom">
                            <option value="">All Status</option>
                            <option value="completed">Completed</option>
                            <option value="pending">Pending</option>
                            <option value="failed">Failed</option>
                            <option value="refunded">Refunded</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Payment Method</label>
                        <select id="filterMethod" class="form-select input-custom">
                            <option value="">All Methods</option>
                            <option value="credit card">Credit Card</option>
                            <option value="debit card">Debit Card</option>
                            <option value="cash">Cash</option>
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
    .btn-add-payment {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        color: var(--plum-900); font-weight: 700; font-size: 14.5px;
        padding: 11px 22px; border-radius: 10px; border: none;
        box-shadow: 0 4px 14px rgba(217, 164, 65, 0.35); transition: all 0.18s ease;
        display: inline-flex; align-items: center; white-space: nowrap;
    }
    .btn-add-payment:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(217, 164, 65, 0.5); color: var(--plum-900); }
 
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
 
    .btn-export {
        background: var(--plum-800); border: none; color: #fff !important;
        font-weight: 600; font-size: 14.5px; padding: 11px 20px; border-radius: var(--radius-sm);
        display: inline-flex; align-items: center; justify-content: center; transition: all 0.18s ease;
        text-decoration: none;
    }
    .btn-export:hover { background: var(--plum-900); }
 
    .cell-sub { font-size: 12.5px; color: var(--ink-500); }
 
    .action-icons { display: flex; gap: 8px; }
    .action-btn {
        width: 34px; height: 34px; border-radius: 8px; border: none; display: inline-flex;
        align-items: center; justify-content: center; font-size: 15px; cursor: pointer; transition: all 0.15s ease;
        text-decoration: none;
    }
    .view-btn { background: var(--purple-50); color: var(--purple-500); }
    .view-btn:hover { background: var(--purple-500); color: #fff; }
    .download-btn { background: var(--blue-50); color: var(--blue-500); }
    .download-btn:hover { background: var(--blue-500); color: #fff; }
 
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
</style>
@endsection
 
@section('extra-js')
<script>
    
    const searchInput = document.getElementById('paymentSearchInput');
    const rows = document.querySelectorAll('.payment-row');
    const noResults = document.getElementById('noPaymentsFound');
 
    function applySearchAndFilters() {
        const term = (searchInput.value || '').toLowerCase().trim();
        const statusFilter = document.getElementById('filterStatus')?.value || '';
        const methodFilter = document.getElementById('filterMethod')?.value || '';
        let visibleCount = 0;
 
        rows.forEach(row => {
            const matchesSearch = row.dataset.client.includes(term);
            const matchesStatus = !statusFilter || row.dataset.status === statusFilter;
            const matchesMethod = !methodFilter || row.dataset.method === methodFilter;
            const show = matchesSearch && matchesStatus && matchesMethod;
            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });
 
        noResults.style.display = visibleCount === 0 ? 'block' : 'none';
    }
 
    searchInput.addEventListener('input', applySearchAndFilters);
 
    document.getElementById('applyFiltersBtn').addEventListener('click', applySearchAndFilters);
    document.getElementById('clearFiltersBtn').addEventListener('click', function () {
        document.getElementById('filterStatus').value = '';
        document.getElementById('filterMethod').value = '';
        applySearchAndFilters();
    });
</script>
@endsection