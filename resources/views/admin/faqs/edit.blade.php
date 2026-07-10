@extends('layouts.admin')

@section('title', 'Edit FAQ — Glamora')

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.faqs.index') }}" style="color:#aaa;text-decoration:none;font-size:0.85rem;">
        <i class="fas fa-arrow-left me-2"></i>Back to FAQs
    </a>
    <h4 class="fw-bold mt-2 mb-0" style="color:#333;">
        <i class="fas fa-edit me-2" style="color:#E91E8C;"></i>Edit FAQ
    </h4>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.faqs.update', $faq->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-bold">Question <span class="text-danger">*</span></label>
                <input type="text" name="question" class="form-control @error('question') is-invalid @enderror" 
                       placeholder="Enter question..." value="{{ old('question', $faq->question) }}" required>
                @error('question')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Answer <span class="text-danger">*</span></label>
                <textarea name="answer" class="form-control @error('answer') is-invalid @enderror" 
                          rows="5" placeholder="Enter answer..." required>{{ old('answer', $faq->answer) }}</textarea>
                @error('answer')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Order</label>
                        <input type="number" name="order" class="form-control @error('order') is-invalid @enderror" 
                               placeholder="0" value="{{ old('order', $faq->order) }}">
                        @error('order')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <div class="form-check form-switch mt-2">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" {{ $faq->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">Active</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn-pink">
                    <i class="fas fa-save me-1"></i> Update FAQ
                </button>
                <a href="{{ route('admin.faqs.index') }}" class="btn-outline-pink" style="border-color:#ddd;color:#666;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection