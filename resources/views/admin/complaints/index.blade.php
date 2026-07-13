@extends('layouts.admin')
@section('title', 'Complaints - Admin')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Complaints Management</h2>
            <p class="text-muted">Review escalated and closed complaints</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-center p-3 border-danger">
                <h4 class="text-danger">{{ $stats['escalated'] ?? 0 }}</h4>
                <small class="text-muted">Escalated</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3 border-warning">
                <h4 class="text-warning">{{ $stats['pending'] ?? 0 }}</h4>
                <small class="text-muted">Pending</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3 border-success">
                <h4 class="text-success">{{ $stats['closed'] ?? 0 }}</h4>
                <small class="text-muted">Closed</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3 border-secondary">
                <h4 class="text-secondary">{{ $stats['rejected'] ?? 0 }}</h4>
                <small class="text-muted">Rejected</small>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="escalated" {{ request('status') == 'escalated' ? 'selected' : '' }}>Escalated</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="">All</option>
                        <option value="service" {{ request('type') == 'service' ? 'selected' : '' }}>Service Issue</option>
                        <option value="staff" {{ request('type') == 'staff' ? 'selected' : '' }}>Staff Behavior</option>
                        <option value="payment" {{ request('type') == 'payment' ? 'selected' : '' }}>Payment Issue</option>
                        <option value="product" {{ request('type') == 'product' ? 'selected' : '' }}>Product Issue</option>
                        <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Salon</th>
                            <th>Subject</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($complaints as $complaint)
                            <tr>
                                <td>#{{ $complaint->id }}</td>
                                <td>{{ $complaint->client->name ?? 'N/A' }}</td>
                                <td>{{ $complaint->salon->name ?? 'N/A' }}</td>
                                <td>{{ Str::limit($complaint->subject, 30) }}</td>
                                <td><span class="badge bg-light">{{ $complaint->type_label }}</span></td>
                                <td>{{ $complaint->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge {{ $complaint->status_badge }}">{{ $complaint->status_label }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    <p>No complaints found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $complaints->links() }}
        </div>
    </div>
</div>

@endsection