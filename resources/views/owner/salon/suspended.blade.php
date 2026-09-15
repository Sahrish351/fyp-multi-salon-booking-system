@extends('layouts.auth')

@section('title', 'Salon Suspended')

@section('content')
<div class="status-page">
    <div class="status-card">
        <div class="status-icon">⚠️</div>
        <h2 class="status-title">Your Salon Has Been Suspended</h2>
        <p class="status-text">
            Your salon account has been temporarily suspended. Please contact our support team
            for more details or to request reactivation.
        </p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="status-btn">Logout</button>
        </form>
    </div>
</div>
@endsection