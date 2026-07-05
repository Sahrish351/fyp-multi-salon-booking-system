@extends('layouts.admin')
@section('title', 'Complaints - Glamora')

@push('styles')
<style>
    .complaints-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 28px;
    }
    .complaints-header h1 {
        font-size: 1.6rem;
        font-weight: 800;
        margin-bottom: 4px;
        color: #1a1a1a;
    }
    .complaints-header p {
        color: #999;
        font-size: 0.88rem;
        margin: 0;
    }

    .complaints-card {
        background: #fff;
        border-radius: 18px;
        border: 1px solid #eee;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        overflow: hidden;
    }
    .complaints-card .card-header {
        padding: 18px 22px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .complaints-card .card-title {
        font-size: 1rem;
        font-weight: 700;
        color: #333;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    .data-table thead th {
        text-align: left;
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #aaa;
        padding: 14px 22px;
        border-bottom: 1.5px solid #f2f2f2;
        white-space: nowrap;
    }
    .data-table tbody tr {
        cursor: pointer;
        transition: background 0.15s ease;
        border-bottom: 1px solid #f5f5f5;
    }
    .data-table tbody tr:last-child { border-bottom: none; }
    .data-table tbody tr:hover { background: #fafafa; }
    .data-table tbody td {
        padding: 16px 22px;
        font-size: 0.86rem;
        color: #333;
        vertical-align: middle;
    }
    .data-table tbody td.empty-cell {
        text-align: center;
        padding: 50px 20px;
        color: #bbb;
        font-size: 0.9rem;
        cursor: default;
    }

    .client-cell { display: flex; align-items: center; gap: 10px; }
    .client-avatar {
        width: 34px; height: 34px; border-radius: 50%;
        background: linear-gradient(135deg,#8d6e63,#6d4c41);
        color: #fff; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.8rem; flex-shrink: 0;
    }
    .client-name { font-weight: 600; color: #1a1a1a; }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: capitalize;
    }
    .badge-danger  { background: #fdeceb; color: #d9534f; }
    .badge-warning { background: #fff6e0; color: #c9982f; }
    .badge-success { background: #e9f7ef; color: #3fa46a; }

    .subject-cell { color: #555; }
    .date-cell { color: #999; font-size: 0.8rem; white-space: nowrap; }

    .btn-outline {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 50px;
        border: 1.5px solid #e5e0da;
        color: #6d4c41;
        font-size: 0.76rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.15s;
    }
    .btn-outline:hover {
        border-color: #6d4c41;
        background: #6d4c41;
        color: #fff;
    }

    .pagination-wrapper {
        padding: 16px 22px;
        border-top: 1px solid #f2f2f2;
    }
</style>
@endpush

@section('content')

<div class="complaints-header">
    <div>
        <h1>Complaints</h1>
        <p>Review and resolve client complaints</p>
    </div>
</div>

<div class="complaints-card">
    <div class="card-header">
        <span class="card-title">Complaints List</span>
        <span class="text-muted" style="font-size:0.78rem;color:#aaa;">{{ $complaints->total() }} total</span>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Salon</th>
                    <th>Subject</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($complaints as $c)
                <tr onclick="window.location='{{ route('admin.complaints.show', $c->id) }}'">
                    <td>#{{ $c->id }}</td>
                    <td>
                        <div class="client-cell">
                            <div class="client-avatar">{{ strtoupper(substr($c->client->name ?? 'N', 0, 1)) }}</div>
                            <span class="client-name">{{ $c->client->name ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td>{{ $c->salon->name ?? 'N/A' }}</td>
                    <td class="subject-cell">{{ Str::limit($c->subject, 30) }}</td>
                    <td>
                        <span class="badge {{ $c->priority == 'high' ? 'badge-danger' : ($c->priority == 'medium' ? 'badge-warning' : 'badge-success') }}">
                            {{ ucfirst($c->priority) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $c->status == 'open' ? 'badge-danger' : ($c->status == 'resolved' ? 'badge-success' : 'badge-warning') }}">
                            {{ ucfirst($c->status) }}
                        </span>
                    </td>
                    <td class="date-cell">{{ $c->created_at->format('d M Y') }}</td>
                    <td onclick="event.stopPropagation();">
                        <a href="{{ route('admin.complaints.show', $c->id) }}" class="btn-outline">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="empty-cell">
                        <i class="fas fa-check-circle" style="font-size:1.6rem;color:#ddd;display:block;margin-bottom:8px;"></i>
                        No complaints found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($complaints->hasPages())
    <div class="pagination-wrapper">
        {{ $complaints->links() }}
    </div>
    @endif
</div>
@endsection