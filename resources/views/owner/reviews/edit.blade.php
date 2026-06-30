
@extends('layouts.owner')
 
@section('title', 'Review')
 
@section('content')
    <script>window.location.href = "{{ route('owner.reviews.show', ['review' => $review['id'] ?? 0]) }}";</script>
    <noscript>
        <p>Editing reviews isn't supported. <a href="{{ route('owner.reviews.index') }}">Go back to Reviews</a>.</p>
    </noscript>
@endsection
 