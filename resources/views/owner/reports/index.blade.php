@extends('layouts.owner')
 
@section('title', 'Reports')
 
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
 
    {{-- PAGE HEADER --}}
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>Reports</h2>
            <p>Generate and download business reports</p>
        </div>
        <button type="button" class="btn btn-generate-report" data-bs-toggle="modal" data-bs-target="#generateReportModal">
            <i class="bi bi-plus-lg me-2"></i> Generate Report
        </button>
    </div>
 
    {{-- REPORT CARDS --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="report-card">
                <div class="report-icon financial">
                    <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                </div>
                <h5>Financial Report</h5>
                <p>Revenue, expenses, and profit analysis</p>
                <form action="{{ route('owner.reports.export') }}" method="POST" class="report-form">
                    @csrf
                    <input type="hidden" name="type" value="monthly_sales">
                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                        <button type="submit" name="format" value="excel" class="btn btn-download-pink btn-sm">
                            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Excel
                        </button>
                        <button type="submit" name="format" value="pdf" class="btn btn-download-pink btn-sm">
                            <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
 
        <div class="col-md-4">
            <div class="report-card">
                <div class="report-icon client">
                    <i class="bi bi-people-fill"></i>
                </div>
                <h5>Client Analytics</h5>
                <p>Client demographics and behavior</p>
                <form action="{{ route('owner.reports.export') }}" method="POST" class="report-form">
                    @csrf
                    <input type="hidden" name="type" value="clients">
                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                        <button type="submit" name="format" value="excel" class="btn btn-download-pink btn-sm">
                            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Excel
                        </button>
                        <button type="submit" name="format" value="pdf" class="btn btn-download-pink btn-sm">
                            <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
 
        <div class="col-md-4">
            <div class="report-card">
                <div class="report-icon performance">
                    <i class="bi bi-bar-chart-fill"></i>
                </div>
                <h5>Performance Report</h5>
                <p>Staff and service performance</p>
                <form action="{{ route('owner.reports.export') }}" method="POST" class="report-form">
                    @csrf
                    <input type="hidden" name="type" value="appointments">
                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                        <button type="submit" name="format" value="excel" class="btn btn-download-pink btn-sm">
                            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Excel
                        </button>
                        <button type="submit" name="format" value="pdf" class="btn btn-download-pink btn-sm">
                            <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
 
    {{-- RECENT REPORTS --}}
    <div class="panel-card">
        <div class="panel-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="panel-title">
                <i class="bi bi-clock-history me-2" style="color:#E85588;"></i> Recent Reports
            </div>
            <span class="badge-count">{{ count($recentReports) }} total</span>
        </div>
 
        <div class="table-responsive">
            @if(count($recentReports) > 0)
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Report Name</th>
                            <th>Type</th>
                            <th>Format</th>
                            <th>Size</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentReports as $report)
                            <tr>
                                <td class="cell-name">
                                    <i class="bi bi-file-earmark-text-fill me-2" style="color:#E85588;"></i>
                                    {{ $report['name'] }}
                                </td>
                                <td>
                                    <span class="badge-status badge-{{ $report['type_key'] }}">
                                        {{ $report['type'] }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-format">{{ $report['format'] }}</span>
                                </td>
                                <td>{{ $report['size'] }}</td>
                                <td>{{ $report['date'] }}</td>
                                <td>
                                    <a href="{{ route('owner.reports.download', ['file' => basename($report['file'])]) }}" 
                                       class="btn btn-download-sm" title="Download">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <i class="bi bi-file-earmark"></i>
                    <p>No reports generated yet. Generate your first report!</p>
                </div>
            @endif
        </div>
    </div>
 
@endsection
 
{{-- GENERATE REPORT MODAL --}}
@push('modals')
    <div class="modal fade" id="generateReportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <form action="{{ route('owner.reports.export') }}" method="POST">
                    @csrf
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title">
                            <i class="bi bi-file-earmark-plus me-2" style="color:#E85588;"></i> Generate Report
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label-custom">Report Type</label>
                            <select name="type" class="form-select input-custom" required>
                                <option value="monthly_sales">Monthly Sales Report</option>
                                <option value="daily_sales">Daily Sales Report</option>
                                <option value="clients">Client Analytics</option>
                                <option value="appointments">Appointments Report</option>
                                <option value="payments">Payments Report</option>
                            </select>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label-custom">From Date</label>
                                <input type="date" name="from_date" class="form-control input-custom" value="{{ date('Y-m-01') }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label-custom">To Date</label>
                                <input type="date" name="to_date" class="form-control input-custom" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="form-label-custom">Format</label>
                            <select name="format" class="form-select input-custom">
                                <option value="excel">Excel (.csv)</option>
                                <option value="pdf">PDF (.pdf)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer modal-footer-custom">
                        <button type="button" class="btn btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-save-changes">
                            <i class="bi bi-download me-2"></i> Generate & Download
                        </button>
                    </div>
                </form>
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

    .btn-generate-report {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 14px;
        padding: 10px 22px;
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.35);
        transition: all 0.18s ease;
        display: inline-flex;
        align-items: center;
    }
    .btn-generate-report:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(232, 85, 136, 0.45);
        color: #ffffff !important;
    }

    .report-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #f0e8ed;
        padding: 28px 24px;
        text-align: center;
        height: 100%;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .report-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    }

    .report-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #fff;
        margin: 0 auto 16px;
    }
    .report-icon.financial { background: linear-gradient(135deg, #FF6B9D, #E85588); }
    .report-icon.client { background: linear-gradient(135deg, #9B6FD1, #7E56B0); }
    .report-icon.performance { background: linear-gradient(135deg, #2EAE7D, #1E8E64); }

    .report-card h5 {
        font-size: 1.05rem;
        font-weight: 700;
        color: #2d1f2c;
        margin-bottom: 6px;
    }
    .report-card p {
        font-size: 13px;
        color: #8a7a88;
        margin-bottom: 16px;
        line-height: 1.4;
    }

    .btn-download-pink {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 14px;
        padding: 8px 20px;
        border-radius: 8px;
        border: none;
        box-shadow: 0 3px 10px rgba(232, 85, 136, 0.2);
        transition: all 0.18s ease;
        display: inline-flex;
        align-items: center;
    }
    .btn-download-pink:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 16px rgba(232, 85, 136, 0.35);
        color: #ffffff !important;
    }
    .btn-download-pink.btn-sm {
        font-size: 12px;
        padding: 6px 14px;
    }

    .panel-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f0e8ed;
        overflow: hidden;
    }
    .panel-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f5eef2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    .panel-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2d1f2c;
        margin: 0;
    }
    .badge-count {
        background: #fcf6f9;
        color: #8a7a88;
        font-size: 12px;
        font-weight: 600;
        padding: 3px 12px;
        border-radius: 20px;
    }
    .badge-format {
        background: #f0e8ed;
        color: #6b4f62;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 10px;
        border-radius: 12px;
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
        padding: 12px 16px;
        border-bottom: 1.5px solid #f0e8ed;
    }
    .table-custom tbody td {
        padding: 12px 16px;
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
    .cell-name {
        font-weight: 600;
        color: #2d1f2c;
        display: flex;
        align-items: center;
    }

    .btn-download-sm {
        background: #fcf6f9;
        border: 1px solid #f0e8ed;
        color: #E85588;
        font-size: 14px;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
        text-decoration: none;
    }
    .btn-download-sm:hover {
        background: #E85588;
        color: #fff;
        border-color: #E85588;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }
    .empty-state i {
        font-size: 48px;
        color: #d4c4d0;
        display: block;
        margin-bottom: 12px;
    }
    .empty-state p {
        color: #8a7a88;
        margin-bottom: 0;
        font-size: 14px;
    }

    .badge-status {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .badge-monthly_sales { background: #FDF6E8; color: #C4903A; }
    .badge-daily_sales { background: #FCE8F0; color: #E85588; }
    .badge-appointments { background: #E8F0FE; color: #4A7FE0; }
    .badge-payments { background: #E8F5ED; color: #2EAE7D; }
    .badge-clients { background: #F0E8FD; color: #7E56B0; }

    .modal-content-custom {
        border-radius: 16px;
        border: none;
        overflow: hidden;
    }
    .modal-header-custom {
        background: #fcf6f9;
        border-bottom: 1px solid #f5eef2;
        padding: 18px 24px;
    }
    .modal-header-custom .modal-title {
        font-weight: 700;
        color: #2d1f2c;
    }
    .modal-body {
        padding: 22px 24px;
    }
    .modal-footer-custom {
        border-top: 1px solid #f5eef2;
        padding: 16px 24px;
    }

    .form-label-custom {
        display: block;
        font-size: 13.5px;
        font-weight: 600;
        color: #4a3a48;
        margin-bottom: 6px;
    }
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

    .alert {
        border-radius: 12px;
        border: none;
        padding: 12px 18px;
        margin-bottom: 20px;
    }
    .alert-success { background: #E8F5ED; color: #1B5E20; }
    .alert-danger { background: #FCE4EC; color: #880E4F; }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch !important;
        }
        .btn-generate-report {
            justify-content: center;
            width: 100%;
        }
        .report-card {
            padding: 20px 16px;
        }
        .report-form .d-flex {
            flex-direction: column;
            align-items: center;
        }
        .table-custom thead th,
        .table-custom tbody td {
            padding: 8px 12px;
            font-size: 12px;
        }
        .btn-download-sm {
            width: 32px;
            height: 32px;
            font-size: 12px;
        }
        .panel-header {
            flex-direction: column;
            align-items: stretch !important;
            text-align: center;
        }
    }
</style>
@endsection