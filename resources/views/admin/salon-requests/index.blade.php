@extends('layouts.admin')
@section('title', 'Salon Registration Requests - Glamora')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h1 class="page-title" style="font-size:1.6rem;">Salon Registration Requests</h1><p>Review and manage new salon registrations</p></div>
    <a href="{{ route('admin.salon-requests.index') }}" class="btn-outline"><i class="fas fa-sync-alt"></i> Refresh</a>
</div>

@php
    $pendingCount = \App\Models\Salon::where('status', 'pending')->count();
    $approvedCount = \App\Models\Salon::where('status', 'approved')->count();
    $rejectedCount = \App\Models\Salon::where('status', 'rejected')->count();
@endphp

<div class="stats-grid" style="margin-bottom:24px;">
    <div class="stat-card"><div class="stat-label">Pending</div><div class="stat-value">{{ $pendingCount }}</div></div>
    <div class="stat-card"><div class="stat-label">Approved</div><div class="stat-value">{{ $approvedCount }}</div></div>
    <div class="stat-card"><div class="stat-label">Rejected</div><div class="stat-value">{{ $rejectedCount }}</div></div>
</div>

<div class="card">
    <div class="card-header"><span class="card-title"><i class="fas fa-clock"></i> Pending Requests</span><span class="badge badge-warning">{{ $pendingSalons->total() }} pending</span></div>
    <div>
        @forelse($pendingSalons as $salon)
        <div style="display:flex; justify-content:space-between; align-items:center; padding:1rem 1.5rem; border-bottom:1px solid var(--border);">
            <div><strong>{{ $salon->name }}</strong><br><small>{{ $salon->city }} • {{ $salon->owner->name ?? 'N/A' }}</small></div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.salon-requests.show', $salon->id) }}" class="btn-outline"><i class="fas fa-eye"></i> View</a>
                <form action="{{ route('admin.salon-requests.approve', $salon->id) }}" method="POST">@csrf<button type="submit" class="btn-primary" onclick="return confirm('Approve this salon?')">Approve</button></form>
                <button class="btn-outline" style="color:var(--red);" onclick="document.getElementById('rejectModal{{ $salon->id }}').style.display='flex'">Reject</button>
            </div>
        </div>
        <div id="rejectModal{{ $salon->id }}" class="modal-overlay" style="display:none;">
            <div class="modal-container"><div class="modal-header"><h3>Reject Salon: {{ $salon->name }}</h3><span onclick="this.closest('.modal-overlay').style.display='none'" style="cursor:pointer;">&times;</span></div>
            <form action="{{ route('admin.salon-requests.reject', $salon->id) }}" method="POST"><div class="modal-body">@csrf<textarea name="reason" class="form-control" rows="3" placeholder="Reason for rejection..." required></textarea></div>
            <div class="modal-footer" style="padding:16px 20px; border-top:1px solid var(--border); display:flex; gap:12px; justify-content:flex-end;"><button type="button" class="btn-outline" onclick="this.closest('.modal-overlay').style.display='none'">Cancel</button><button type="submit" class="btn-primary" style="background:var(--red);">Reject</button></div></form></div>
        </div>
        @empty
        <div style="text-align:center; padding:60px;"><i class="fas fa-check-circle fa-3x" style="color:var(--green); margin-bottom:16px;"></i><h3>No Pending Requests</h3><p>All salon registration requests have been processed</p></div>
        @endforelse
    </div>
</div>
<div class="pagination-wrapper">{{ $pendingSalons->links() }}</div>

<style>.modal-overlay{position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);display:flex;align-items:center;justify-content:center;z-index:1000;}.modal-container{background:white;border-radius:24px;width:90%;max-width:450px;}</style>
@endsection