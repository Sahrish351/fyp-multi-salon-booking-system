@extends('layouts.client')

@section('title', 'Complaint Details — Glamora')

@section('content')

<div class="mb-4">
    <a href="{{ route('client.complaints.index') }}" style="color:#aaa;text-decoration:none;font-size:0.85rem;">
        <i class="fas fa-arrow-left me-2"></i>Back to Complaints
    </a>
    <h4 class="fw-bold mt-2 mb-0" style="color:#333;font-family:'Playfair Display',serif;">
        <i class="fas fa-exclamation-circle me-2" style="color:#E91E8C;"></i>Complaint Details
    </h4>
</div>

<div class="row g-4">
    <div class="col-lg-8">

        <div class="bg-white rounded-4 p-4 mb-4" style="border:1px solid #fce4ec;">
            <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-3">
                <div>
                    <h5 class="fw-bold mb-1" style="color:#333;">{{ $complaint->subject }}</h5>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        @php 
                            $statusMap = [
                                'pending' => ['#ef4444', 'Pending'],
                                'in_progress' => ['#f59e0b', 'In Progress'],
                                'resolved' => ['#22c55e', 'Resolved'],
                                'closed' => ['#8b5cf6', 'Closed'],
                                'escalated' => ['#ef4444', 'Escalated'],
                                'rejected' => ['#6b7280', 'Rejected']
                            ];
                            $cs = $statusMap[$complaint->status] ?? ['#aaa', ucfirst($complaint->status)];
                        @endphp
                        <span style="background:{{ $cs[0] }}20;color:{{ $cs[0] }};padding:3px 12px;border-radius:20px;font-size:0.78rem;font-weight:600;">{{ $cs[1] }}</span>
                        <span style="color:#aaa;font-size:0.78rem;">{{ $complaint->created_at->format('d M Y, h:i A') }}</span>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                @foreach([
                    ['Salon', $complaint->salon->name ?? 'N/A', 'fa-store'],
                    ['Type', $complaint->type_label, 'fa-tag'],
                    ['Appointment', $complaint->appointment->appointment_date->format('d M Y') ?? 'N/A', 'fa-calendar-check'],
                    ['Submitted', $complaint->created_at->format('d M Y'), 'fa-calendar'],
                ] as [$l, $v, $icon])
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

            @if($complaint->image)
                <div class="mt-3">
                    <a href="{{ asset('storage/' . $complaint->image) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-image me-1"></i> View Attachment
                    </a>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-4 p-4" style="border:1px solid #fce4ec;">
            <h6 class="fw-bold mb-4" style="color:#333;"><i class="fas fa-comments me-2" style="color:#E91E8C;"></i>Conversation</h6>

            {{-- Client Message --}}
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

            {{-- ============================================ --}}
            {{-- ✅ OWNER REPLY - YEH ADD KIYA HAI --}}
            {{-- ============================================ --}}
            @if($complaint->owner_reply)
                <div class="d-flex gap-3 mb-4">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 fw-bold text-white" style="width:40px;height:40px;background:linear-gradient(135deg,#E85588,#c2185b);font-size:0.8rem;">
                        <i class="fas fa-store"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="fw-semibold" style="color:#333;font-size:0.85rem;">Salon Owner</span>
                            <span style="background:rgba(232,85,136,0.1);color:#E85588;padding:2px 8px;border-radius:10px;font-size:0.7rem;font-weight:600;">Owner</span>
                            <span style="color:#aaa;font-size:0.75rem;">{{ $complaint->owner_replied_at ? \Carbon\Carbon::parse($complaint->owner_replied_at)->diffForHumans() : '' }}</span>
                        </div>
                        <div class="p-3 rounded-3" style="background:#fcf6f9;border:1px solid #f0e8ed;">
                            <p style="color:#555;font-size:0.85rem;line-height:1.7;margin:0;">{{ $complaint->owner_reply }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Admin Replies --}}
            @foreach($complaint->replies as $reply)
            <div class="d-flex gap-3 mb-4 {{ $reply->sender_type === 'admin' ? 'flex-row-reverse' : '' }}">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 fw-bold text-white" style="width:40px;height:40px;background:{{ $reply->sender_type === 'admin' ? 'linear-gradient(135deg,#1a1a2e,#0f3460)' : 'linear-gradient(135deg,#E91E8C,#c2185b)' }};font-size:0.8rem;">
                    {{ substr($reply->user->name ?? 'A',0,1) }}
                </div>
                <div class="flex-grow-1 {{ $reply->sender_type === 'admin' ? 'text-end' : '' }}">
                    <div class="d-flex align-items-center gap-2 mb-1 {{ $reply->sender_type === 'admin' ? 'justify-content-end' : '' }}">
                        <span class="fw-semibold" style="color:#333;font-size:0.85rem;">{{ $reply->user->name ?? 'Admin' }}</span>
                        @if($reply->sender_type === 'admin')
                        <span style="background:rgba(26,26,46,0.1);color:#1a1a2e;padding:2px 8px;border-radius:10px;font-size:0.7rem;font-weight:600;">Admin</span>
                        @endif
                        <span style="color:#aaa;font-size:0.75rem;">{{ $reply->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="p-3 rounded-3 {{ $reply->sender_type === 'admin' ? 'd-inline-block' : '' }}" style="background:{{ $reply->sender_type === 'admin' ? 'rgba(26,26,46,0.06)' : '#fff0f7' }};border:1px solid {{ $reply->sender_type === 'admin' ? 'rgba(26,26,46,0.1)' : '#fce4ec' }};">
                        <p style="color:#555;font-size:0.85rem;line-height:1.7;margin:0;">{{ $reply->message }}</p>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Client Action Buttons --}}
            @if($complaint->canClientAccept())
                <div class="p-3 rounded-3 text-center" style="background:#f0fff4;border:1px solid #bbf7d0;">
                    <p class="fw-bold mb-2" style="color:#16a34a;">Complaint has been resolved by the Owner.</p>
                    <p class="text-muted small">Are you satisfied with the resolution?</p>
                    <div class="d-flex justify-content-center gap-3">
                        <form action="{{ route('client.complaints.accept', $complaint->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn" style="background:linear-gradient(135deg,#22c55e,#16a34a);color:#fff;border:none;border-radius:50px;padding:8px 25px;font-weight:600;">
                                <i class="fas fa-check me-2"></i> Yes, Accept
                            </button>
                        </form>
                        <form action="{{ route('client.complaints.escalate', $complaint->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn" style="background:#fee2e2;color:#dc2626;border:1px solid #fca5a5;border-radius:50px;padding:8px 25px;font-weight:600;">
                                <i class="fas fa-exclamation-triangle me-2"></i> No, Escalate
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            @if($complaint->status == 'closed')
                <div class="p-3 rounded-3" style="background:#f0fff4;border:1px solid #bbf7d0;">
                    <p class="text-success fw-bold mb-0"><i class="fas fa-check-circle me-2"></i>This complaint has been <strong>Closed</strong>.</p>
                </div>
            @endif

            @if($complaint->status == 'escalated')
                <div class="p-3 rounded-3" style="background:#fef3c7;border:1px solid #fcd34d;">
                    <p class="text-warning fw-bold mb-0"><i class="fas fa-exclamation-triangle me-2"></i>This complaint has been <strong>Escalated</strong> to Admin.</p>
                </div>
            @endif

            @if($complaint->status == 'rejected')
                <div class="p-3 rounded-3" style="background:#fee2e2;border:1px solid #fca5a5;">
                    <p class="text-danger fw-bold mb-0"><i class="fas fa-times-circle me-2"></i>This complaint has been <strong>Rejected</strong>.</p>
                    @if($complaint->rejection_reason)
                        <p class="text-muted small mt-1 mb-0">Reason: {{ $complaint->rejection_reason }}</p>
                    @endif
                </div>
            @endif

        </div>
    </div>

    <div class="col-lg-4">
        <div class="bg-white rounded-4 p-4" style="border:1px solid #fce4ec;">
            <h6 class="fw-bold mb-3" style="color:#333;font-size:0.95rem;"><i class="fas fa-info-circle me-2" style="color:#E91E8C;"></i>Complaint Info</h6>
            @foreach([
                ['ID', '#'.$complaint->id],
                ['Status', ucwords(str_replace('_',' ',$complaint->status))],
                ['Type', $complaint->type_label],
                ['Salon', $complaint->salon->name ?? 'N/A'],
                ['Filed On', $complaint->created_at->format('d M Y')],
                ['Appointment', $complaint->appointment->appointment_date->format('d M Y') ?? 'N/A'],
            ] as [$l, $v])
            <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #fce4ec;">
                <span style="color:#aaa;font-size:0.82rem;">{{ $l }}</span>
                <span style="color:#333;font-size:0.82rem;font-weight:500;">{{ $v }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection