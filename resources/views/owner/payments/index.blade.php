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

    /* ===== RECORD PAYMENT BUTTON - PINK ===== */
    .btn-add-payment {
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
        text-decoration: none;
    }
    .btn-add-payment:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(232, 85, 136, 0.45);
        color: #ffffff !important;
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
    .icon-green { background: linear-gradient(135deg, #2EAE7D, #1E8E64); }
    .icon-blue { background: linear-gradient(135deg, #4A7FE0, #3568C4); }
    .icon-amber { background: linear-gradient(135deg, #E08A2C, #C47620); }
    .icon-gold { background: linear-gradient(135deg, #D9A441, #C4903A); }
    .stat-label-sm { font-size: 13.5px; color: #8a7a88; margin-bottom: 2px; }
    .stat-value-sm { font-size: 22px; font-weight: 700; color: #2d1f2c; }
 
    .search-input-wrap { position: relative; }
    .search-input-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #8a7a88; }
    .search-input { padding-left: 40px !important; }
 
    /* ===== FILTERS BUTTON - PINK OUTLINE ===== */
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
 
    /* ===== EXPORT BUTTON - PINK ===== */
    .btn-export {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        border: none;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 14.5px;
        padding: 11px 20px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.18s ease;
        text-decoration: none;
    }
    .btn-export:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(232, 85, 136, 0.4);
        color: #ffffff !important;
    }
 
    .cell-sub { font-size: 12.5px; color: #8a7a88; }
 
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
    .view-btn { background: #f0e8fd; color: #7E56B0; }
    .view-btn:hover { background: #7E56B0; color: #fff; }
    .download-btn { background: #e8f0fe; color: #3568C4; }
    .download-btn:hover { background: #3568C4; color: #fff; }
 
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
 
    /* ===== CANCEL BUTTON - PINK OUTLINE ===== */
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
 
    /* ===== APPLY FILTERS BUTTON - PINK ===== */
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
        box-shadow: 0 6px 20px rgba(232, 85, 136, 0.4);
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
    .amount-gold { font-weight: 700; color: #D9A441; }

    .badge-status {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .badge-completed { background: #E8F5ED; color: #1E8E64; }
    .badge-pending { background: #FDF6E8; color: #C4903A; }
    .badge-cancelled { background: #FCE4EC; color: #D45482; }
    .badge-progress { background: #F0E8FD; color: #7E56B0; }
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