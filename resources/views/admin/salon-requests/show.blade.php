@extends('layouts.admin')
@section('title', 'Review Salon Request - ' . $salon->name)

@section('content')
<div class="mb-4"><a href="{{ route('admin.salon-requests.index') }}" class="btn-outline"><i class="fas fa-arrow-left"></i> Back</a></div>

<div class="card"><div class="card-header"><span class="card-title"><i class="fas fa-info-circle"></i> Salon Information</span><span class="badge {{ $salon->status == 'pending' ? 'badge-warning' : ($salon->status == 'approved' ? 'badge-success' : 'badge-danger') }}">{{ ucfirst($salon->status) }}</span></div>
<div style="padding:1.5rem;">
    <div class="row g-4"><div class="col-md-6"><label class="text-muted">Salon Name</label><p class="fw-bold">{{ $salon->name }}</p></div>
    <div class="col-md-6"><label class="text-muted">Owner</label><p class="fw-bold">{{ $salon->owner->name ?? 'N/A' }}</p></div>
    <div class="col-md-6"><label class="text-muted">Email</label><p>{{ $salon->email }}</p></div>
    <div class="col-md-6"><label class="text-muted">Phone</label><p>{{ $salon->phone }}</p></div>
    <div class="col-md-6"><label class="text-muted">City</label><p>{{ $salon->city }}</p></div>
    <div class="col-md-6"><label class="text-muted">Address</label><p>{{ $salon->address }}</p></div>
    <div class="col-12"><label class="text-muted">Description</label><p>{{ $salon->description ?? 'No description provided.' }}</p></div></div>
</div></div>

<div class="card mt-4"><div class="card-header"><span class="card-title">Actions</span></div>
<div style="padding:1.5rem;" class="d-flex gap-3">
    <form action="{{ route('admin.salon-requests.approve', $salon->id) }}" method="POST">@csrf<button type="submit" class="btn-primary" style="background:var(--green);" onclick="return confirm('Approve this salon?')"><i class="fas fa-check"></i> Approve Salon</button></form>
    <form action="{{ route('admin.salon-requests.reject', $salon->id) }}" method="POST">@csrf<textarea name="reason" class="form-control" rows="2" style="width:250px;" placeholder="Rejection reason..."></textarea><button type="submit" class="btn-primary" style="background:var(--red); margin-top:8px;"><i class="fas fa-times"></i> Reject Salon</button></form>
</div></div>
@endsection