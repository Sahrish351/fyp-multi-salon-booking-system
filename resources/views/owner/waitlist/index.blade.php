
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
                    <div class="stat-value-sm">{{ $stats['total'] ?? 4 }}</div>
                </div>
            </div>
        </div>
 
        <div class="col-md-4">
            <div class="stat-card-sm">
                <div class="stat-icon icon-red"><i class="bi bi-exclamation-circle-fill"></i></div>
                <div>
                    <div class="stat-label-sm">High Priority</div>
                    <div class="stat-value-sm">{{ $stats['high_priority'] ?? 2 }}</div>
                </div>
            </div>
        </div>
 
        <div class="col-md-4">
            <div class="stat-card-sm">
                <div class="stat-icon icon-blue"><i class="bi bi-calendar-week-fill"></i></div>
                <div>
                    <div class="stat-label-sm">This Week</div>
                    <div class="stat-value-sm">{{ $stats['this_week'] ?? 4 }}</div>
                </div>
            </div>
        </div>
 
    </div>
 
    
    <div class="panel-card panel-card-auto mb-4">
        <div class="search-input-wrap">
            <i class="bi bi-search"></i>
            <input type="text" id="waitlistSearchInput" class="form-control input-custom search-input"
                   placeholder="Search waitlist...">
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
 
    .cell-sub { font-size: 12.5px; color: var(--ink-500); }
 
    .badge-priority {
        display: inline-block;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 12.5px;
        font-weight: 600;
    }
    .badge-priority-high   { background: var(--red-50); color: var(--red-500); }
    .badge-priority-medium { background: #FCEFDE; color: var(--orange-500); }
    .badge-priority-low    { background: var(--blue-50); color: var(--blue-500); }
 
    .action-icons { display: flex; gap: 8px; align-items: center; }
 
    .btn-book-now {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        color: var(--plum-900); font-weight: 700; font-size: 13px;
        padding: 8px 16px; border-radius: 8px; text-decoration: none;
        white-space: nowrap; transition: all 0.15s ease;
    }
    .btn-book-now:hover { transform: translateY(-1px); box-shadow: 0 4px 10px rgba(217, 164, 65, 0.35); color: var(--plum-900); }
 
    .action-btn {
        width: 34px; height: 34px; border-radius: 8px; border: none; display: inline-flex;
        align-items: center; justify-content: center; font-size: 15px; cursor: pointer; transition: all 0.15s ease;
        text-decoration: none;
    }
    .view-btn { background: var(--purple-50); color: var(--purple-500); }
    .view-btn:hover { background: var(--purple-500); color: #fff; }
    .notify-btn { background: var(--blush-100); color: var(--rose-600); }
    .notify-btn:hover { background: var(--rose-500); color: #fff; }
    .delete-btn { background: var(--red-50); color: var(--red-500); }
    .delete-btn:hover { background: var(--red-500); color: #fff; }
 
    .input-custom {
        background: var(--blush-50) !important; border: 1px solid var(--blush-200) !important;
        border-radius: var(--radius-sm) !important; color: var(--ink-900) !important;
        font-size: 14.5px; padding: 11px 14px !important;
    }
    .input-custom:focus { background: #fff !important; border-color: var(--rose-400) !important; box-shadow: 0 0 0 3px rgba(240, 143, 180, 0.2) !important; outline: none; }
 
    .modal-content-custom { border-radius: var(--radius-lg); border: none; overflow: hidden; }
    .modal-body { padding: 22px 24px; }
    .modal-footer-custom { border-top: 1px solid var(--blush-100); padding: 16px 24px; }
 
    .btn-cancel-modal {
        background: var(--white); border: 1px solid var(--blush-200); color: var(--ink-700);
        font-weight: 600; padding: 9px 20px; border-radius: 10px;
    }
    .btn-cancel-modal:hover { background: var(--blush-50); }
 
    .btn-delete-confirm {
        background: linear-gradient(135deg, #F0708C, var(--red-500));
        color: #fff; font-weight: 700; padding: 9px 24px; border-radius: 10px; border: none;
    }
    .btn-delete-confirm:hover { color: #fff; box-shadow: 0 4px 14px rgba(225, 77, 106, 0.4); }
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