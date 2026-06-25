
@extends('layouts.owner')

@section('title', 'Time Slots')

@section('content')

    
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>Time Slots</h2>
            <p>Manage available booking times for each team member</p>
        </div>
        <button type="button" class="btn btn-add-slot" data-bs-toggle="modal" data-bs-target="#generateSlotsModal">
            <i class="bi bi-plus-lg me-2"></i> Generate Slots
        </button>
    </div>

   
    <div class="panel-card panel-card-auto mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-8">
                <label class="form-label-custom mb-2">Select Team Member</label>
                <div class="stylist-pill-row">
                    @foreach ($stylists as $stylist)
                        <a href="{{ route('owner.time-slots.index', ['stylist' => $stylist['id']]) }}"
                           class="stylist-pill {{ $selectedStylist['id'] == $stylist['id'] ? 'active' : '' }}">
                            <span class="stylist-pill-avatar">
                                @if (!empty($stylist['photo_url']))
                                    <img src="{{ $stylist['photo_url'] }}" alt="{{ $stylist['name'] }}">
                                @else
                                    <i class="bi bi-person-fill"></i>
                                @endif
                            </span>
                            {{ $stylist['name'] }}
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="slot-interval-badge">
                    <i class="bi bi-clock-history me-1"></i> {{ $selectedStylist['name'] }}'s Schedule
                </div>
            </div>
        </div>
    </div>

  
    <div class="panel-card panel-card-auto">
        <div class="table-responsive">
            <table class="slots-grid-table">
                <thead>
                    <tr>
                        @foreach ($days as $day)
                            <th>{{ $day }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $maxRows = max(array_map('count', $weeklySlots));
                    @endphp
                    @for ($row = 0; $row < $maxRows; $row++)
                        <tr>
                            @foreach ($days as $day)
                                @php $slot = $weeklySlots[$day][$row] ?? null; @endphp
                                <td>
                                    @if ($slot)
                                        <button type="button"
                                                class="slot-btn {{ $slot['active'] ? 'slot-active' : 'slot-inactive' }}"
                                                data-id="{{ $slot['id'] }}"
                                                data-day="{{ $day }}"
                                                data-time="{{ $slot['time'] }}">
                                            {{ $slot['time'] }}
                                        </button>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <div class="slots-legend mt-3">
            <span class="legend-item"><span class="legend-dot legend-active"></span> Available</span>
            <span class="legend-item"><span class="legend-dot legend-inactive"></span> Blocked (click to toggle)</span>
        </div>
    </div>

@endsection


@push('modals')

   
    <div class="modal fade" id="generateSlotsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <form action="{{ route('owner.time-slots.generate') }}" method="POST">
                    @csrf
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title">Generate Time Slots</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label-custom">Team Member</label>
                            <select name="stylist_id" class="form-select input-custom" required>
                                @foreach ($stylists as $stylist)
                                    <option value="{{ $stylist['id'] }}" {{ $selectedStylist['id'] == $stylist['id'] ? 'selected' : '' }}>
                                        {{ $stylist['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom">Days</label>
                            <div class="days-checkbox-row">
                                @foreach ($days as $day)
                                    <div class="day-check">
                                        <input type="checkbox" name="days[]" value="{{ $day }}" id="day-{{ $day }}" checked>
                                        <label for="day-{{ $day }}">{{ substr($day, 0, 3) }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label-custom">Start Time</label>
                                <input type="time" name="start_time" class="form-control input-custom" value="09:00" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label-custom">End Time</label>
                                <input type="time" name="end_time" class="form-control input-custom" value="17:00" required>
                            </div>
                        </div>

                        <div class="mb-1 mt-3">
                            <label class="form-label-custom">Slot Interval</label>
                            <select name="interval" class="form-select input-custom">
                                <option value="30">30 minutes</option>
                                <option value="45">45 minutes</option>
                                <option value="60" selected>1 hour</option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer modal-footer-custom">
                        <button type="button" class="btn btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-save-changes">Generate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endpush

@section('extra-css')
<style>
    .btn-add-slot {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        color: var(--plum-900); font-weight: 700; font-size: 14.5px;
        padding: 11px 22px; border-radius: 10px; border: none;
        box-shadow: 0 4px 14px rgba(217, 164, 65, 0.35); transition: all 0.18s ease;
        display: inline-flex; align-items: center; white-space: nowrap;
    }
    .btn-add-slot:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(217, 164, 65, 0.5); color: var(--plum-900); }

   
    .panel-card-auto { height: auto; }

    .stylist-pill-row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .stylist-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--blush-50);
        border: 1px solid var(--blush-200);
        color: var(--ink-700);
        font-weight: 600;
        font-size: 13.5px;
        padding: 7px 16px 7px 7px;
        border-radius: 30px;
        text-decoration: none;
        transition: all 0.18s ease;
    }
    .stylist-pill:hover {
        background: var(--blush-100);
        color: var(--plum-900);
    }
    .stylist-pill.active {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        color: var(--plum-900);
        border-color: transparent;
        box-shadow: 0 3px 10px rgba(217, 164, 65, 0.35);
    }
    .stylist-pill-avatar {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: var(--plum-800);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        overflow: hidden;
        flex-shrink: 0;
    }
    .stylist-pill-avatar img { width: 100%; height: 100%; object-fit: cover; }

    .slot-interval-badge {
        display: inline-flex;
        align-items: center;
        background: var(--blush-50);
        color: var(--plum-800);
        font-weight: 600;
        font-size: 13.5px;
        padding: 9px 16px;
        border-radius: 20px;
    }

    .slots-grid-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 8px;
    }
    .slots-grid-table thead th {
        background: var(--blush-50);
        color: var(--plum-800);
        font-weight: 700;
        font-size: 14px;
        padding: 12px 10px;
        border-radius: var(--radius-sm);
        text-align: center;
        white-space: nowrap;
    }
    .slots-grid-table tbody td {
        text-align: center;
        padding: 0;
    }

    .slot-btn {
        width: 100%;
        min-width: 100px;
        border: 1px solid var(--blush-200);
        border-radius: var(--radius-sm);
        padding: 10px 8px;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .slot-active {
        background: var(--green-50);
        color: var(--green-500);
        border-color: #BFE9D6;
    }
    .slot-active:hover {
        background: var(--green-500);
        color: #fff;
    }
    .slot-inactive {
        background: var(--blush-50);
        color: var(--ink-500);
        border-color: var(--blush-200);
        text-decoration: line-through;
    }
    .slot-inactive:hover {
        background: var(--red-50);
        color: var(--red-500);
        text-decoration: line-through;
    }

    .slots-legend {
        display: flex;
        gap: 20px;
        font-size: 13px;
        color: var(--ink-700);
    }
    .legend-item { display: inline-flex; align-items: center; gap: 6px; }
    .legend-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
    .legend-active { background: var(--green-500); }
    .legend-inactive { background: var(--ink-500); }

    .form-label-custom { display: block; font-size: 13.5px; font-weight: 600; color: var(--ink-700); margin-bottom: 6px; }
    .input-custom {
        background: var(--blush-50) !important; border: 1px solid var(--blush-200) !important;
        border-radius: var(--radius-sm) !important; color: var(--ink-900) !important;
        font-size: 14.5px; padding: 11px 14px !important;
    }
    .input-custom:focus { background: #fff !important; border-color: var(--rose-400) !important; box-shadow: 0 0 0 3px rgba(240, 143, 180, 0.2) !important; outline: none; }

    .days-checkbox-row { display: flex; gap: 10px; flex-wrap: wrap; }
    .day-check { position: relative; }
    .day-check input[type="checkbox"] {
        position: absolute; opacity: 0; width: 100%; height: 100%; cursor: pointer; margin: 0;
    }
    .day-check label {
        display: inline-flex; align-items: center; justify-content: center;
        width: 46px; height: 36px; border-radius: var(--radius-sm);
        background: var(--blush-50); border: 1px solid var(--blush-200);
        color: var(--ink-700); font-size: 12.5px; font-weight: 600; cursor: pointer;
        transition: all 0.15s ease;
    }
    .day-check input[type="checkbox"]:checked + label {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        color: var(--plum-900); border-color: transparent;
    }

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

    document.querySelectorAll('.slot-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const slotId = this.dataset.id;
            const isCurrentlyActive = this.classList.contains('slot-active');

      
            this.classList.toggle('slot-active');
            this.classList.toggle('slot-inactive');

            fetch(`{{ url('/owner/time-slots') }}/${slotId}/toggle`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            }).catch(() => {
              
                this.classList.toggle('slot-active');
                this.classList.toggle('slot-inactive');
                alert('Could not update slot. Please try again.');
            });
        });
    });
</script>
@endsection
