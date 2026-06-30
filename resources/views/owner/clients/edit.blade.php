
@extends('layouts.owner')
 
@section('title', 'Edit Client')
 
@section('content')
 
   
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
                            <label class="form-label-custom">Full Name</label>
                            <input type="text" name="name" class="form-control input-custom"
                                   value="{{ $client['name'] }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Email</label>
                            <input type="email" name="email" class="form-control input-custom"
                                   value="{{ $client['email'] }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Phone</label>
                            <input type="text" name="phone" class="form-control input-custom"
                                   value="{{ $client['phone'] }}" required>
                        </div>
                    </div>
                </div>
            </div>
 
            <div class="col-lg-6">
                <div class="panel-card">
                    <div class="panel-title">Client Status</div>
 
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label-custom">Status</label>
                            <select name="status" class="form-select input-custom" required>
                                @foreach (['New', 'Regular', 'VIP', 'Inactive'] as $statusOption)
                                    <option {{ $client['status'] === $statusOption ? 'selected' : '' }}>{{ $statusOption }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Join Date</label>
                            <input type="date" name="join_date" class="form-control input-custom"
                                   value="{{ $client['join_date_raw'] }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Notes <span class="text-muted">(optional)</span></label>
                            <textarea name="notes" class="form-control input-custom" rows="3">{{ $client['notes'] ?? '' }}</textarea>
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
    .btn-back {
        background: var(--white); border: 1px solid var(--blush-200); color: var(--plum-800);
        font-weight: 600; font-size: 14.5px; padding: 10px 20px; border-radius: 10px;
        display: inline-flex; align-items: center; transition: all 0.18s ease;
    }
    .btn-back:hover { background: var(--blush-50); color: var(--plum-900); }
 
    .form-label-custom { display: block; font-size: 13.5px; font-weight: 600; color: var(--ink-700); margin-bottom: 6px; }
    .input-custom {
        background: var(--blush-50) !important; border: 1px solid var(--blush-200) !important;
        border-radius: var(--radius-sm) !important; color: var(--ink-900) !important;
        font-size: 14.5px; padding: 11px 14px !important;
    }
    .input-custom:focus { background: #fff !important; border-color: var(--rose-400) !important; box-shadow: 0 0 0 3px rgba(240, 143, 180, 0.2) !important; outline: none; }
 
    .btn-save-changes {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        color: var(--plum-900); font-weight: 700; padding: 11px 26px; border-radius: 10px; border: none;
        display: inline-flex; align-items: center;
    }
    .btn-save-changes:hover { color: var(--plum-900); transform: translateY(-1px); box-shadow: 0 6px 16px rgba(217, 164, 65, 0.4); }
 
    .btn-cancel-modal {
        background: var(--white); border: 1px solid var(--blush-200); color: var(--ink-700);
        font-weight: 600; padding: 11px 26px; border-radius: 10px; display: inline-flex; align-items: center;
    }
    .btn-cancel-modal:hover { background: var(--blush-50); color: var(--ink-900); }
</style>
@endsection
 