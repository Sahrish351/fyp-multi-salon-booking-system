@extends('layouts.auth')

@section('title', 'Pending Review')

@section('content')
<div class="status-page">
    <div class="status-card">
        <div class="status-icon">⏳</div>
        <span class="status-badge">Pending Review</span>
        <h2 class="status-title">Your Request Is Under Review</h2>
        <p class="status-text">
            Your salon registration has been submitted and is currently being reviewed by our team.
            You'll receive an email as soon as it's approved, and your dashboard will be unlocked.
        </p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="status-btn">Logout</button>
        </form>
    </div>
</div>
@endsection