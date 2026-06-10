{{-- ============================================================ --}}
{{-- FILE: resources/views/partials/alerts.blade.php --}}
{{-- ============================================================ --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mx-3 mt-3" role="alert" style="background:linear-gradient(135deg,#d4edda,#c3e6cb);border-left:4px solid #28a745 !important;">
    <i class="fas fa-check-circle me-2 text-success"></i>
    <strong>{{ session('success') }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
 
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm mx-3 mt-3" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>
    <strong>{{ session('error') }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
 
@if(session('warning'))
<div class="alert alert-warning alert-dismissible fade show border-0 rounded-3 shadow-sm mx-3 mt-3" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    {{ session('warning') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
 
@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm mx-3 mt-3">
    <i class="fas fa-times-circle me-2"></i>
    <strong>Please fix the following errors:</strong>
    <ul class="mb-0 mt-2">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
 









