{{-- ============================================================ --}}
{{-- FILE: resources/views/client/reviews/show.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.client')
@section('title', 'Review Details — Glamora')
@section('content')

<div class="mb-4">
    <a href="{{ route('client.appointments.index') }}" style="color:#aaa;text-decoration:none;font-size:0.85rem;">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
    <h4 class="fw-bold mt-2 mb-0" style="color:#333;font-family:'Playfair Display',serif;">
        <i class="fas fa-star me-2" style="color:#ffc107;"></i>Review Details
    </h4>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="bg-white rounded-4 p-4" style="border:1px solid #fce4ec;">
            <div class="d-flex align-items-center gap-3 mb-4">
                <img src="{{ $review->salon->logo_url }}" class="rounded-2" width="56" height="56" style="object-fit:cover;" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($review->salon->name) }}&background=E91E8C&color=fff'">
                <div>
                    <h5 class="fw-bold mb-0" style="color:#333;">{{ $review->salon->name }}</h5>
                    @if($review->appointment)
                    <div style="color:#888;font-size:0.82rem;">{{ $review->appointment->service->name ?? '' }} · {{ $review->appointment->appointment_date->format('d M Y') }}</div>
                    @endif
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 mb-3">
                @for($i=1;$i<=5;$i++)
                <i class="fas fa-star" style="font-size:1.3rem;color:{{ $i<=$review->rating ? '#ffc107' : '#e5e7eb' }};"></i>
                @endfor
                <span class="fw-bold ms-1" style="color:#333;font-size:1rem;">{{ $review->rating }}/5</span>
            </div>
            <p style="color:#555;font-size:0.9rem;line-height:1.8;margin-bottom:1.5rem;">{{ $review->comment }}</p>
            <div style="color:#aaa;font-size:0.78rem;">Posted {{ $review->created_at->format('d M Y, h:i A') }}</div>

            @if($review->reply)
            <div class="mt-4 p-3 rounded-3" style="background:rgba(233,30,140,0.04);border-left:3px solid #E91E8C;">
                <div style="color:#E91E8C;font-size:0.75rem;font-weight:700;margin-bottom:6px;"><i class="fas fa-reply me-1"></i>SALON OWNER REPLIED</div>
                <p style="color:#555;font-size:0.85rem;line-height:1.7;margin:0;">{{ $review->reply->reply }}</p>
                <div style="color:#aaa;font-size:0.72rem;margin-top:6px;">{{ $review->reply->created_at->diffForHumans() }}</div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
