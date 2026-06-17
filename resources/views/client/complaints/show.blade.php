@extends('layouts.client')

@section('title', 'Complaint Details — Glamora')

@section('content')

<div class="mb-4">
    <a href="{{ route('client.complaints.index') }}" style="color:#aaa;text-decoration:none;font-size:0.85rem;">
        <i class="fas fa-arrow-left me-2"></i>Back to Complaints
    </a>
    <h4 class="fw-bold mt-2 mb-0" style="color:#333;font-family:'Playfair Display',serif;">
        <i class="fas fa-exclamation-circle me-2" style="color:#f97316;"></i>Complaint Details
    </h4>
</div>

<div class="row g-4">
    <div class="col-lg-8">

        <div class="bg-white rounded-4 p-4 mb-4" style="border:1px solid #fce4ec;">
            <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-3">
                <div>
                    <h5 class="fw-bold mb-1" style="color:#333;">{{ $complaint->subject }}</h5>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        @php $cs = ['open'=>['#ef4444','Open'],'in_review'=>['#ffc107','In Review'],'resolved'=>['#22c55e','Resolved'],'closed'=>['#8b5cf6','Closed']][$complaint->status] ?? ['#aaa',ucfirst($complaint->status)]; @endphp
                        <span style="background:{{ $cs[0] }}20;color:{{ $cs[0] }};padding:3px 12px;border-radius:20px;font-size:0.78rem;font-weight:600;">{{ $cs[1] }}</span>
                        {!! $complaint->priority_badge !!}
                        <span style="color:#aaa;font-size:0.78rem;">{{ $complaint->created_at->format('d M Y, h:i A') }}</span>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                @foreach([
                    ['Salon', $complaint->salon->name, 'fa-store'],
                    ['Type', str_replace('_',' ',ucfirst($complaint->type)), 'fa-tag'],
                    ['Priority', ucfirst($complaint->priority), 'fa-exclamation'],
                    ['Submitted', $complaint->created_at->format('d M Y'), 'fa-calendar'],
                ] as [$l,$v,$icon])
                <div class="col-md-6">
                    <div class="p-3 rounded-3" style="background:#fff5f9;border:1px solid #fce4ec;">
                        <div style="color:#aaa;font-size:0.72rem;"><i class="fas {{ $icon }} me-1" style="color:#E91E8C;font-size:0.7rem;"></i>{{ $l }}</div>
                        <div style="color:#333;font-weight:600;font-size:0.88rem;text-transform:capitalize;">{{ $v }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="p-3 rounded-3" style="background:#fff5f0;border:1px solid #fed7aa;">
                <div style="color:#f97316;font-size:0.75rem;font-weight:700;margin-bottom:6px;"><i class="fas fa-align-left me-1"></i>DESCRIPTION</div>
                <p style="color:#555;font-size:0.88rem;line-height:1.8;margin:0;">{{ $complaint->description }}</p>
            </div>
        </div>

        <div class="bg-white rounded-4 p-4" style="border:1px solid #fce4ec;">
            <h6 class="fw-bold mb-4" style="color:#333;"><i class="fas fa-comments me-2" style="color:#3b82f6;"></i>Conversation</h6>

            <div class="d-flex gap-3 mb-4">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 fw-bold text-white" style="width:40px;height:40px;background:linear-gradient(135deg,#E91E8C,#c2185b);font-size:0.8rem;">
                    {{ substr(Auth::user()->name,0,1) }}
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="fw-semibold" style="color:#333;font-size:0.85rem;">{{ Auth::user()->name }}</span>
                        <span style="background:#fff0f7;color:#E91E8C;padding:2px 8px;border-radius:10px;font-size:0.7rem;font-weight:600;">You</span>
                        <span style="color:#aaa;font-size:0.75rem;">{{ $complaint->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="p-3 rounded-3" style="background:#fff0f7;border:1px solid #fce4ec;">
                        <p style="color:#555;font-size:0.85rem;line-height:1.7;margin:0;">{{ $complaint->description }}</p>
                    </div>
                </div>
            </div>

            @foreach($complaint->replies as $reply)
            <div class="d-flex gap-3 mb-4 {{ $reply->sender_type === 'admin' ? 'flex-row-reverse' : '' }}">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 fw-bold text-white" style="width:40px;height:40px;background:{{ $reply->sender_type === 'admin' ? 'linear-gradient(135deg,#1a1a2e,#0f3460)' : 'linear-gradient(135deg,#E91E8C,#c2185b)' }};font-size:0.8rem;">
                    {{ substr($reply->user->name,0,1) }}
                </div>
                <div class="flex-grow-1 {{ $reply->sender_type === 'admin' ? 'text-end' : '' }}">
                    <div class="d-flex align-items-center gap-2 mb-1 {{ $reply->sender_type === 'admin' ? 'justify-content-end' : '' }}">
                        <span class="fw-semibold" style="color:#333;font-size:0.85rem;">{{ $reply->user->name }}</span>
                        @if($reply->sender_type === 'admin')
                        <span style="background:rgba(26,26,46,0.1);color:#1a1a2e;padding:2px 8px;border-radius:10px;font-size:0.7rem;font-weight:600;">Glamora Support</span>
                        @endif
                        <span style="color:#aaa;font-size:0.75rem;">{{ $reply->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="p-3 rounded-3 {{ $reply->sender_type === 'admin' ? 'd-inline-block' : '' }}" style="background:{{ $reply->sender_type === 'admin' ? 'rgba(26,26,46,0.06)' : '#fff0f7' }};border:1px solid {{ $reply->sender_type === 'admin' ? 'rgba(26,26,46,0.1)' : '#fce4ec' }};">
                        <p style="color:#555;font-size:0.85rem;line-height:1.7;margin:0;">{{ $reply->message }}</p>
                    </div>
                </div>
            </div>
            @endforeach

            @if($complaint->status !== 'resolved' && $complaint->status !== 'closed')
            <div class="p-3 rounded-3" style="background:#f9f9f9;border:1px solid #f0f0f0;">
                <p style="color:#aaa;font-size:0.82rem;text-align:center;margin:0;">
                    <i class="fas fa-lock me-1"></i>Waiting for admin response...
                </p>
            </div>
            @endif
        </div>
    </div>

    <div class="col-lg-4">
        <div class="bg-white rounded-4 p-4" style="border:1px solid #fce4ec;">
            <h6 class="fw-bold mb-3" style="color:#333;font-size:0.95rem;"><i class="fas fa-info-circle me-2" style="color:#3b82f6;"></i>Complaint Info</h6>
            @foreach([
                ['ID', '#'.$complaint->id],
                ['Status', ucwords(str_replace('_',' ',$complaint->status))],
                ['Priority', ucfirst($complaint->priority)],
                ['Type', ucwords(str_replace('_',' ',$complaint->type))],
                ['Salon', $complaint->salon->name],
                ['Filed On', $complaint->created_at->format('d M Y')],
                ['Replies', $complaint->replies->count().' messages'],
            ] as [$l,$v])
            <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #fce4ec;">
                <span style="color:#aaa;font-size:0.82rem;">{{ $l }}</span>
                <span style="color:#333;font-size:0.82rem;font-weight:500;">{{ $v }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection