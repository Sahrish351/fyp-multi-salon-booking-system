@extends('layouts.owner')
 
@section('title', 'Waitlist')
 
@section('content')
 
    <div class="page-header">
        <h2>Waitlist</h2>
        <p>Manage clients waiting for appointments</p>
    </div>
 
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card-sm">
                <div class="stat-icon icon-purple"><i class="bi bi-list-task"></i></div>
                <div>
                    <div class="stat-label-sm">Total Waiting</div>
                    <div class="stat-value-sm">{{ $stats['total'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-sm">
                <div class="stat-icon icon-red"><i class="bi bi-exclamation-circle-fill"></i></div>
                <div>
                    <div class="stat-label-sm">High Priority</div>
                    <div class="stat-value-sm">{{ $stats['high_priority'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-sm">
                <div class="stat-icon icon-blue"><i class="bi bi-calendar-week-fill"></i></div>
                <div>
                    <div class="stat-label-sm">This Week</div>
                    <div class="stat-value-sm">{{ $stats['this_week'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
 
    <div class="panel-card panel-card-auto mb-4">
        <div class="search-input-wrap">
    <i class="bi bi-search"></i>
    <input type="text" id="waitlistSearchInput" class="search-input"
           placeholder="Search waitlist by client name...">
</div>
    </div>
 
    <div class="panel-card panel-card-auto">
        <div class="table-responsive">
            <table class="table-custom" id="waitlistTable">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Service</th>
                        <th>Preferred Date</th>
                        <th>Priority</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $priorityBadge = [
                            'High'   => 'badge-priority-high',
                            'Medium' => 'badge-priority-medium',
                            'Low'    => 'badge-priority-low',
                        ];
                    @endphp
 
                    @foreach ($waitlistEntries as $entry)
                        <tr class="waitlist-row" data-client="{{ strtolower($entry['client_name']) }}">
                            <td>
                                <div class="cell-name">{{ $entry['client_name'] }}</div>
                                <div class="cell-sub">{{ $entry['client_email'] }}</div>
                                <div class="cell-sub">{{ $entry['client_phone'] }}</div>
                            </td>
                            <td>{{ $entry['service'] }}</td>
                            <td>{{ $entry['preferred_date'] }}</td>
                            <td>
                                <span class="badge-priority {{ $priorityBadge[$entry['priority']] ?? 'badge-priority-low' }}">
                                    {{ $entry['priority'] }}
                                </span>
                            </td>
                            <td>
                                <div class="action-icons">
                                    <a href="{{ route('owner.appointments.create') }}?client={{ urlencode($entry['client_name']) }}&service={{ urlencode($entry['service']) }}"
                                       class="btn btn-book-now">
                                        Book Now
                                    </a>
                                    <a href="{{ route('owner.waitlist.show', ['waitlist' => $entry['id']]) }}" class="action-btn view-btn">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button type="button" class="action-btn notify-btn"
                                            data-id="{{ $entry['id'] }}" data-name="{{ $entry['client_name'] }}"
                                            title="Notify client">
                                        <i class="bi bi-bell-fill"></i>
                                    </button>
                                    <button type="button" class="action-btn delete-btn"
                                            data-bs-toggle="modal" data-bs-target="#removeWaitlistModal"
                                            data-id="{{ $entry['id'] }}" data-name="{{ $entry['client_name'] }}">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
 
            <div id="noWaitlistFound" class="text-center py-5" style="display:none;">
                <i class="bi bi-emoji-frown" style="font-size:36px; color:#F08FB4;"></i>
                <p class="mt-2 mb-0" style="color:#6B4F62;">No waitlist entries found matching your search.</p>
            </div>
        </div>
    </div>
 
@endsection
 
@push('modals')
    <div class="modal fade" id="removeWaitlistModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <form action="{{ route('owner.waitlist.destroy', ['waitlist' => 0]) }}" method="POST" id="removeWaitlistForm">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body text-center py-4">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size:42px; color:#E14D6A;"></i>
                        <h5 class="mt-3" style="color:#5C2142; font-weight:700;">Remove from Waitlist?</h5>
                        <p class="mb-0" style="color:#6B4F62;">
                            Remove "<span id="removeWaitlistName" class="fw-semibold"></span>" from the waitlist? This action cannot be undone.
                        </p>
                    </div>
                    <div class="modal-footer modal-footer-custom justify-content-center">
                        <button type="button" class="btn btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-delete-confirm">Remove</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
 
    <form id="notifyWaitlistForm" action="{{ route('owner.waitlist.notify', ['id' => 0]) }}" method="POST" class="d-none">
        @csrf
    </form>
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
    .icon-purple { background: linear-gradient(135deg, #9B6FD1, #7E56B0); }
    .icon-red { background: linear-gradient(135deg, #E96A98, #D45482); }
    .icon-blue { background: linear-gradient(135deg, #4A7FE0, #3568C4); }
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
 
    .cell-sub { font-size: 12.5px; color: #8a7a88; }
 
    .badge-priority {
        display: inline-block;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 12.5px;
        font-weight: 600;
    }
    .badge-priority-high   { background: #FCE4EC; color: #D45482; }
    .badge-priority-medium { background: #FDF6E8; color: #C4903A; }
    .badge-priority-low    { background: #E8F0FE; color: #3568C4; }
 
    .action-icons { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
 
    /* ===== BOOK NOW BUTTON - PINK ===== */
    .btn-book-now {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 12px;
        padding: 6px 14px;
        border-radius: 8px;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.15s ease;
        border: none;
    }
    .btn-book-now:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.35);
        color: #ffffff !important;
    }
 
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
    .notify-btn { background: #FCE8F0; color: #E85588; }
    .notify-btn:hover { background: #E85588; color: #fff; }
    .delete-btn { background: #FCE4EC; color: #D45482; }
    .delete-btn:hover { background: #D45482; color: #fff; }
 
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
 
    .btn-delete-confirm {
        background: linear-gradient(135deg, #F0708C, #E85588);
        color: #fff;
        font-weight: 700;
        padding: 9px 24px;
        border-radius: 10px;
        border: none;
        transition: all 0.15s ease;
    }
    .btn-delete-confirm:hover {
        color: #fff;
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.4);
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
 
    @media (max-width: 768px) {
        .action-icons { flex-wrap: wrap; }
        .btn-book-now { font-size: 11px; padding: 4px 10px; }
    }
</style>
@endsection
 
@section('extra-js')
<script>
    const searchInput = document.getElementById('waitlistSearchInput');
    const rows = document.querySelectorAll('.waitlist-row');
    const noResults = document.getElementById('noWaitlistFound');
 
    searchInput.addEventListener('input', function () {
        const term = this.value.toLowerCase().trim();
        let visibleCount = 0;
 
        rows.forEach(row => {
            const show = row.dataset.client.includes(term);
            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });
 
        noResults.style.display = visibleCount === 0 ? 'block' : 'none';
    });
 
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('removeWaitlistName').textContent = this.dataset.name;
            const form = document.getElementById('removeWaitlistForm');
            form.action = form.action.replace(/waitlist\/\d+$/, 'waitlist/' + this.dataset.id);
        });
    });
 
    document.querySelectorAll('.notify-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const form = document.getElementById('notifyWaitlistForm');
            form.action = form.action.replace(/\/\d+$/, '/' + this.dataset.id);
            form.submit();
        });
    });
</script>
@endsection