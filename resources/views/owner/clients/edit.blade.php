@extends('layouts.owner')
 
@section('title', 'Edit Client')
 
@section('content')
 
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
 
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>Edit Client</h2>
            <p>Update details for "{{ $client['name'] }}"</p>
        </div>
        <a href="{{ route('owner.clients.index') }}" class="btn btn-back">
            <i class="bi bi-arrow-left me-2"></i> Back to Clients
        </a>
    </div>
 
    <form action="{{ route('owner.clients.update', ['client' => $client['id']]) }}" method="POST">
        @csrf
        @method('PUT')
 
        <div class="row g-4">
 
            <div class="col-lg-6">
                <div class="panel-card">
                    <div class="panel-title">Personal Information</div>
 
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label-custom">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control input-custom @error('name') is-invalid @enderror"
                                   value="{{ old('name', $client['name']) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control input-custom @error('email') is-invalid @enderror"
                                   value="{{ old('email', $client['email']) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control input-custom @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $client['phone']) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
 
            <div class="col-lg-6">
                <div class="panel-card">
                    <div class="panel-title">Client Status</div>
 
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label-custom">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select input-custom @error('status') is-invalid @enderror" required>
                                <option value="New" {{ old('status', $client['status']) == 'New' ? 'selected' : '' }}>New</option>
                                <option value="Regular" {{ old('status', $client['status']) == 'Regular' ? 'selected' : '' }}>Regular</option>
                                <option value="VIP" {{ old('status', $client['status']) == 'VIP' ? 'selected' : '' }}>VIP</option>
                                <option value="Inactive" {{ old('status', $client['status']) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Join Date <span class="text-danger">*</span></label>
                            <input type="date" name="join_date" class="form-control input-custom @error('join_date') is-invalid @enderror"
                                   value="{{ old('join_date', $client['join_date_raw']) }}" required>
                            @error('join_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Notes <span class="text-muted">(optional)</span></label>
                            <textarea name="notes" class="form-control input-custom @error('notes') is-invalid @enderror" rows="3"
                                      placeholder="Any preferences, allergies, or important notes about this client...">{{ old('notes', $client['notes'] ?? '') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
 
            <div class="col-12">
                <div class="panel-card">
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-save-changes">
                            <i class="bi bi-check-circle-fill me-2"></i> Save Changes
                        </button>
                        <a href="{{ route('owner.clients.index') }}" class="btn btn-cancel-modal">Cancel</a>
                    </div>
                </div>
            </div>
 
        </div>
 
    </form>
 
@endsection
 
@section('extra-css')
<style>
    .page-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d1f2c;
        margin-bottom: 0.25rem;
    }
    .page-header p {
        color: #8a7a88;
        margin-bottom: 0;
    }

    .btn-back {
        background: #fff;
        border: 1px solid #f0e8ed;
        color: #2d1f2c;
        font-weight: 600;
        font-size: 14.5px;
        padding: 10px 20px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        transition: all 0.18s ease;
        text-decoration: none;
    }
    .btn-back:hover {
        background: #fcf6f9;
        border-color: #E85588;
        color: #E85588;
    }

    .panel-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f0e8ed;
        height: 100% !important;
        display: flex;
        flex-direction: column;
    }
    .panel-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2d1f2c;
        margin-bottom: 1rem;
        flex-shrink: 0;
    }

    .form-label-custom {
        display: block;
        font-size: 13.5px;
        font-weight: 600;
        color: #4a3a48;
        margin-bottom: 6px;
    }
    .form-label-custom .text-danger { color: #E85588; }
    .form-label-custom .text-muted { font-weight: 400; font-size: 12.5px; color: #8a7a88; }

    .input-custom {
        background: #fcf6f9 !important;
        border: 1px solid #f0e8ed !important;
        border-radius: 10px !important;
        color: #2d1f2c !important;
        font-size: 14.5px;
        padding: 11px 14px !important;
        width: 100%;
    }
    .input-custom:focus {
        background: #fff !important;
        border-color: #E85588 !important;
        box-shadow: 0 0 0 3px rgba(232, 85, 136, 0.15) !important;
        outline: none;
    }
    .is-invalid {
        border-color: #E85588 !important;
    }
    .invalid-feedback {
        color: #E85588;
        font-size: 12px;
        margin-top: 4px;
    }

    .btn-save-changes {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        padding: 11px 26px;
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.35);
        display: inline-flex;
        align-items: center;
        transition: all 0.18s ease;
        text-decoration: none;
    }
    .btn-save-changes:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(232, 85, 136, 0.45);
        color: #ffffff !important;
    }

    .btn-cancel-modal {
        background: #fff;
        border: 1.5px solid #FF6B9D;
        color: #E85588;
        font-weight: 600;
        padding: 11px 26px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        transition: all 0.18s ease;
        text-decoration: none;
    }
    .btn-cancel-modal:hover {
        background: #E85588;
        color: #ffffff !important;
        border-color: #E85588;
    }

    .alert {
        border-radius: 12px;
        border: none;
        padding: 0.8rem 1.2rem;
    }
    .alert-danger {
        background: #FCE4EC;
        color: #880E4F;
    }
    .alert ul {
        padding-left: 1.2rem;
        margin-bottom: 0;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch !important;
        }
        .btn-back {
            justify-content: center;
            width: 100%;
        }
        .d-flex.gap-3 {
            flex-wrap: wrap;
        }
        .btn-save-changes,
        .btn-cancel-modal {
            flex: 1;
            justify-content: center;
        }
        .panel-card {
            height: auto !important;
        }
    }
</style>
@endsection