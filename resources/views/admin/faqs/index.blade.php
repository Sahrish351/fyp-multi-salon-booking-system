@extends('layouts.admin')

@section('title', 'FAQs — Glamora')

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#333;">
            <i class="fas fa-question-circle me-2" style="color:#E91E8C;"></i>FAQs
        </h4>
        <p style="color:#aaa;font-size:0.85rem;">Manage frequently asked questions</p>
    </div>
    <a href="{{ route('admin.faqs.create') }}" class="btn btn-pink">
        <i class="fas fa-plus me-1"></i> Add New FAQ
    </a>
</div>

{{-- Success Message --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- FAQs Table --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">All FAQs</span>
        <span class="badge bg-primary">{{ $faqs->total() }} Total</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Question</th>
                    <th>Answer</th>
                    <th style="width:100px;">Order</th>
                    <th style="width:100px;">Status</th>
                    <th style="width:150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($faqs as $faq)
                <tr>
                    <td>{{ $faq->id }}</td>
                    <td>{{ Str::limit($faq->question, 50) }}</td>
                    <td>{{ Str::limit($faq->answer, 80) }}</td>
                    <td>{{ $faq->order }}</td>
                    <td>
                        <span class="badge {{ $faq->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $faq->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        {{-- Toggle Status --}}
                        <form action="{{ route('admin.faqs.toggle-status', $faq->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $faq->is_active ? 'btn-warning' : 'btn-success' }}" title="Toggle Status">
                                <i class="fas {{ $faq->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                            </button>
                        </form>

                        {{-- Edit --}}
                        <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>

                        {{-- Delete --}}
                        <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" style="display:inline;" 
                              onsubmit="return confirm('Delete this FAQ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <i class="fas fa-inbox fa-3x mb-3" style="color:rgba(233,30,140,0.2);display:block;"></i>
                        <h6>No FAQs found</h6>
                        <p style="color:#aaa;">Click "Add New FAQ" to create one</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $faqs->links() }}
    </div>
</div>

@endsection