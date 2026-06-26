@extends('layouts.admin')
@section('title', 'All Salons - Glamora')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h1 style="font-size:1.6rem;">All Salons</h1><p>{{ $salons->total() }} salons in system</p></div>
    <a href="{{ route('admin.salons.create') }}" class="btn-primary"><i class="fas fa-plus"></i> Add New Salon</a>
</div>

<div class="card"><div class="card-header"><span class="card-title"><i class="fas fa-list"></i> Salons List</span><span>Total: {{ $salons->total() }}</span></div>
<div class="table-responsive"><table class="data-table"><thead><tr><th>Salon</th><th>Owner</th><th>City</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>@forelse($salons as $salon)
<tr onclick="window.location='{{ route('admin.salons.show', $salon->id) }}'">
<td><div style="display:flex; align-items:center; gap:12px;"><div style="width:44px;height:44px;background:var(--brown-lt);border-radius:12px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-store"></i></div><div><strong>{{ $salon->name }}</strong><br><small>{{ $salon->phone }}</small></div></div></td>
<td>{{ $salon->owner->name ?? 'N/A' }}</td>
<td><span class="badge badge-warning">{{ $salon->city }}</span></td>
<td><span class="badge {{ $salon->status == 'approved' ? 'badge-success' : ($salon->status == 'pending' ? 'badge-warning' : 'badge-danger') }}">{{ ucfirst($salon->status) }}</span></td>
<td><div class="d-flex gap-2"><a href="{{ route('admin.salons.show', $salon->id) }}" class="btn-outline"><i class="fas fa-eye"></i></a><a href="{{ route('admin.salons.edit', $salon->id) }}" class="btn-outline" onclick="event.stopPropagation()"><i class="fas fa-edit"></i></a></div></td>
</tr>@empty<tr><td colspan="5" style="text-align:center;">No salons found</td></tr>@endforelse</tbody></table></div>
<div class="pagination-wrapper">{{ $salons->links() }}</div></div>
@endsection