@extends('layouts.admin')
@section('title', 'Salon Details - ' . $salon->name)

@section('content')
<div class="mb-4"><a href="{{ route('admin.salons.index') }}" class="btn-outline"><i class="fas fa-arrow-left"></i> Back to Salons</a></div>

<div class="card"><div class="card-header"><span class="card-title"><i class="fas fa-info-circle"></i> {{ $salon->name }}</span><span class="badge {{ $salon->status == 'approved' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($salon->status) }}</span></div>
<div style="padding:1.5rem;">
    <div class="row g-4"><div class="col-md-6"><label>Owner</label><p>{{ $salon->owner->name ?? 'N/A' }}</p></div>
    <div class="col-md-6"><label>Phone</label><p>{{ $salon->phone }}</p></div>
    <div class="col-md-6"><label>Email</label><p>{{ $salon->email }}</p></div>
    <div class="col-md-6"><label>City</label><p>{{ $salon->city }}</p></div>
    <div class="col-12"><label>Address</label><p>{{ $salon->address }}</p></div>
    <div class="col-12"><label>Description</label><p>{{ $salon->description ?? 'No description' }}</p></div></div>
</div></div>

<div class="card mt-4"><div class="card-header"><span class="card-title">Statistics</span></div>
<div style="padding:1.5rem;"><div class="row g-4"><div class="col-md-3"><div class="stat-card" style="text-align:center;"><div class="stat-value">{{ $salon->appointments->count() }}</div><div class="stat-label">Appointments</div></div></div>
<div class="col-md-3"><div class="stat-card" style="text-align:center;"><div class="stat-value">{{ number_format($salon->rating ?? 0, 1) }}</div><div class="stat-label">Rating</div></div></div>
<div class="col-md-3"><div class="stat-card" style="text-align:center;"><div class="stat-value">{{ $salon->services->count() }}</div><div class="stat-label">Services</div></div></div>
<div class="col-md-3"><div class="stat-card" style="text-align:center;"><div class="stat-value">{{ $salon->reviews->count() }}</div><div class="stat-label">Reviews</div></div></div></div></div></div>

@if($salon->status == 'pending')
<div class="card mt-4"><div class="card-header"><span class="card-title">Actions</span></div><div style="padding:1.5rem;" class="d-flex gap-3">
    <form action="{{ route('admin.salon-requests.approve', $salon->id) }}" method="POST">@csrf<button type="submit" class="btn-primary" style="background:var(--green);">Approve Salon</button></form>
    <form action="{{ route('admin.salon-requests.reject', $salon->id) }}" method="POST">@csrf<button type="submit" class="btn-primary" style="background:var(--red);">Reject Salon</button></form>
</div></div>
@endif
@endsection