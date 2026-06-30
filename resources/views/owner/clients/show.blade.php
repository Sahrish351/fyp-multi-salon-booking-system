
@extends('layouts.owner')
 
@section('title', 'Client Details')
 
@section('content')
 
    @php
        $statusBadge = [
            'VIP'      => 'badge-vip',
            'Regular'  => 'badge-regular',
            'New'      => 'badge-confirmed',
            'Inactive' => 'badge-cancelled',
        ];
    @endphp
 

    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>{{ $client['name'] }}</h2>
            <p>Client since {{ $client['join_date'] }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('owner.clients.edit', ['client' => $client['id']]) }}" class="btn btn-edit-action">
                <i class="bi bi-pencil-square me-2"></i> Edit
            </a>
            <a href="{{ route('owner.clients.index') }}" class="btn btn-back">
                <i class="bi bi-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>
 
    <div class="row g-4">
 
       
        <div class="col-lg-4">
            <div class="panel-card text-center">
                <div class="client-avatar mx-auto">
                    <i class="bi bi-person-fill"></i>
                </div>
 
                <h4 class="mt-3 mb-1" style="color:var(--plum-800); font-weight:700;">{{ $client['name'] }}</h4>
 
                <span class="badge-status {{ $statusBadge[$client['status']] ?? 'badge-regular' }} mb-3">
                    {{ $client['status'] }}
                </span>
 
                <hr class="my-4">
 
                <div class="text-start contact-info-list">
                    <div class="contact-item">
                        <i class="bi bi-envelope-fill"></i>
                        <span>{{ $client['email'] }}</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-telephone-fill"></i>
                        <span>{{ $client['phone'] }}</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-calendar-event-fill"></i>
                        <span>Joined {{ $client['join_date'] }}</span>
                    </div>
                </div>
 
                @if (!empty($client['notes']))
                    <hr class="my-4">
                    <div class="text-start">
                        <p class="info-label mb-2">Notes</p>
                        <p class="client-notes-text">{{ $client['notes'] }}</p>
                    </div>
                @endif
            </div>
        </div>
 
       
        <div class="col-lg-8">
 
       
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-card-sm">
                        <div class="stat-icon icon-blue"><i class="bi bi-calendar-check-fill"></i></div>
                        <div>
                            <div class="stat-label-sm">Total Visits</div>
                            <div class="stat-value-sm">{{ $client['total_visits'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-sm">
                        <div class="stat-icon icon-green"><i class="bi bi-currency-dollar"></i></div>
                        <div>
                            <div class="stat-label-sm">Total Spent</div>
                            <div class="stat-value-sm">${{ number_format($client['total_spent']) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-sm">
                        <div class="stat-icon icon-gold"><i class="bi bi-clock-history"></i></div>
                        <div>
                            <div class="stat-label-sm">Last Visit</div>
                            <div class="stat-value-sm" style="font-size:16px;">{{ $client['last_visit'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
 
           
            <div class="panel-card">
                <div class="panel-title">Visit History</div>
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Stylist</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($visitHistory ?? [] as $visit)
                                <tr>
                                    <td class="cell-name">{{ $visit['service'] }}</td>
                                    <td>{{ $visit['stylist'] }}</td>
                                    <td>{{ $visit['date'] }}</td>
                                    <td class="amount-gold">${{ $visit['amount'] }}</td>
                                    <td>
                                        <span class="badge-status badge-completed">{{ $visit['status'] }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4" style="color:var(--ink-500);">
                                        No visit history found yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
 
        </div>
 
    </div>
 
@endsection
 
@section('extra-css')
<style>
    .btn-back {
        background: var(--white); border: 1px solid var(--blush-200); color: var(--plum-800);
        font-weight: 600; font-size: 14.5px; padding: 10px 20px; border-radius: 10px;
        display: inline-flex; align-items: center; transition: all 0.18s ease;
    }
    .btn-back:hover { background: var(--blush-50); color: var(--plum-900); }
 
    .btn-edit-action {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        color: var(--plum-900); font-weight: 700; font-size: 14.5px;
        padding: 10px 22px; border-radius: 10px; border: none;
        box-shadow: 0 4px 14px rgba(217, 164, 65, 0.35); transition: all 0.18s ease;
        display: inline-flex; align-items: center;
    }
    .btn-edit-action:hover { transform: translateY(-1px); color: var(--plum-900); box-shadow: 0 6px 18px rgba(217, 164, 65, 0.5); }
 
    .badge-vip { background: #FCEFDE; color: var(--gold-600); }
    .badge-regular { background: var(--blue-50); color: var(--blue-500); }
 
    .client-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--blue-500), #6398F2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        color: #fff;
    }
 
    .contact-info-list { display: flex; flex-direction: column; gap: 12px; }
    .contact-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13.5px;
        color: var(--ink-700);
    }
    .contact-item i { color: var(--rose-500); width: 18px; text-align: center; }
 
    .info-label { font-size: 12.5px; color: var(--ink-500); margin: 0; }
    .client-notes-text { font-size: 13.5px; color: var(--ink-700); line-height: 1.6; margin: 0; }
 
    .stat-card-sm {
        background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--blush-200);
        box-shadow: var(--shadow-card); padding: 18px 20px; display: flex; align-items: center; gap: 16px; height: 100%;
    }
    .stat-card-sm .stat-icon { width: 50px; height: 50px; border-radius: 14px; font-size: 20px; flex-shrink: 0; }
    .stat-label-sm { font-size: 13.5px; color: var(--ink-700); margin-bottom: 2px; }
    .stat-value-sm { font-size: 22px; font-weight: 700; color: var(--plum-900); }
</style>
@endsection
 