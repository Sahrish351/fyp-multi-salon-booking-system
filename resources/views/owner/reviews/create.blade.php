
@extends('layouts.owner')
 
@section('title', 'Add Review')
 
@section('content')
 
    <div class="page-header">
        <h2>Add Review</h2>
        <p>Manually log feedback received outside the app (e.g. phone call, in-person)</p>
    </div>
 
    <div class="panel-card text-center py-5">
        <i class="bi bi-info-circle-fill" style="font-size:40px; color:#F08FB4;"></i>
        <h4 class="mt-3" style="color:#5C2142; font-weight:700;">Reviews are submitted by clients</h4>
        <p class="text-muted mb-4">Clients leave reviews after their appointment is completed. You can approve, flag, or reply to reviews from the Reviews page.</p>
        <a href="{{ route('owner.reviews.index') }}" class="btn btn-back">
            <i class="bi bi-arrow-left me-2"></i> Back to Reviews
        </a>
    </div>
 
@endsection
 
@section('extra-css')
<style>
    .btn-back {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        color: var(--plum-900); font-weight: 700; font-size: 14.5px;
        padding: 11px 24px; border-radius: 10px; border: none;
        display: inline-flex; align-items: center;
    }
    .btn-back:hover { color: var(--plum-900); }
</style>
@endsection