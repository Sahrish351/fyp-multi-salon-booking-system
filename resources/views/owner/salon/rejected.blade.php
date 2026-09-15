@extends('layouts.auth')

@section('title', 'Registration Not Approved')

@section('content')
<div class="status-page">
    <div class="status-card">
        <div class="status-icon">✕</div>
        <h2 class="status-title">Your Request Was Not Approved</h2>
        <p class="status-text">
            We're sorry, but your salon registration could not be approved at this time.
        </p>
        <div class="reason-box">
            <strong>Reason</strong>
            {{ $salon->rejection_reason ?? 'No reason provided.' }}
        </div>
        <p class="status-text" style="margin-top:-8px;">
            If you believe this was a mistake, please reach out to our support team.
        </p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="status-btn">Logout</button>
        </form>
    </div>
</div>
@endsection