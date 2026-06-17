@extends('layouts.client')

@section('title', 'My Complaints — Glamora')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1" style="color:#333;font-family:'Playfair Display',serif;">
            <i class="fas fa-exclamation-circle me-2" style="color:#E91E8C;"></i>My Complaints
        </h4>
        <p style="color:#aaa;font-size:0.85rem;margin:0;">Track status of your filed complaints</p>
    </div>
    <a href="{{ url('/client/complaints/create') }}" class="btn btn-sm rounded-pill px-4" style="background:linear-gradient(135deg,#E91E8C,#c2185b);color:#fff;border:none;font-weight:600;">
    <i class="fas fa-plus me-1"></i>File New Complaint

    </a>
</div>

<div class="d-flex gap-2 mb-4 flex-wrap">
    @foreach(['all'=>'All','open'=>'Open','in_review'=>'In Review','resolved'=>'Resolved','closed'=>'Closed'] as $val=>$lbl)
    <a href="{{ route('client.complaints.index', ['status' => $val]) }}"
       class="btn btn-sm rounded-pill"
       style="{{ request('status')===$val || (!request('status') && $val==='all') ? 'background:#E91E8C;color:#fff;border:none;font-weight:600;' : 'background:#fff;color:#888;border:1px solid #fce4ec;' }}font-size:0.82rem;padding:6px 16px;">
        {{ $lbl }}
    </a>
    @endforeach
</div>

<div class="row g-4">
    @forelse($complaints as $complaint)
    <div class="col-12">
        <div class="bg-white rounded-4 p-4" style="border:1px solid #fce4ec;transition:all .3s;" onmouseover="this.style.borderColor='#E91E8C';this.style.boxShadow='0 6px 20px rgba(233,30,140,0.1)'" onmouseout="this.style.borderColor='#fce4ec';this.style.boxShadow='none'">
            <div class="row align-items-start g-3">
                <div class="col-md-1 text-center">
                    @php 
                        $priority_colors = ['high'=>'#E91E8C','medium'=>'#C9A96E','low'=>'#22c55e']; 
                        $pc = $priority_colors[$complaint->priority] ?? '#aaa'; 
                    @endphp
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width:48px;height:48px;background:{{ $pc }}18;border:2px solid {{ $pc }}30;">
                        <i class="fas fa-exclamation" style="color:{{ $pc }};font-size:1rem;"></i>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                        <h6 class="fw-bold mb-0" style="color:#333;">{{ $complaint->subject }}</h6>
                        {!! $complaint->priority_badge ?? '<span class="badge bg-secondary">Normal</span>' !!}
                    </div>
                    <div style="color:#888;font-size:0.82rem;margin-bottom:0.5rem;">
                        <i class="fas fa-store me-1" style="color:#E91E8C;font-size:0.75rem;"></i>{{ $complaint->salon->name ?? 'N/A' }}
                        &nbsp;·&nbsp;
                        <span style="text-transform:capitalize;">{{ str_replace('_',' ',$complaint->type ?? 'general') }}</span>
                    </div>
                    <p style="color:#aaa;font-size:0.82rem;line-height:1.6;margin:0;">{{ Str::limit($complaint->description, 120) }}</p>
                </div>
                <div class="col-md-2 text-center">
                    <div style="color:#aaa;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">Status</div>
                    @php
                        $cs = ['open'=>['#E91E8C','Open'],'in_review'=>['#C9A96E','In Review'],'resolved'=>['#22c55e','Resolved'],'closed'=>['#8b5cf6','Closed']][$complaint->status] ?? ['#aaa',ucfirst($complaint->status)];
                    @endphp
                    <span style="background:{{ $cs[0] }}20;color:{{ $cs[0] }};padding:4px 14px;border-radius:20px;font-size:0.75rem;font-weight:600;">{{ $cs[1] }}</span>
                    <div style="color:#aaa;font-size:0.72rem;margin-top:4px;">{{ $complaint->created_at->diffForHumans() }}</div>
                </div>
                <div class="col-md-2 text-center">
                    <div style="color:#aaa;font-size:0.72rem;margin-bottom:4px;">Replies</div>
                    <div style="color:#333;font-size:1.2rem;font-weight:700;">{{ $complaint->replies->count() ?? 0 }}</div>
                </div>
                <div class="col-md-1 text-end">
                    <a href="{{ route('client.complaints.show', $complaint->id) }}" class="btn btn-sm" style="background:#fff0f7;color:#E91E8C;border:1px solid #fce4ec;border-radius:8px;font-size:0.78rem;">
                        <i class="fas fa-eye me-1"></i>View
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5 bg-white rounded-4" style="border:2px dashed #fce4ec;">
            <i class="fas fa-smile fa-4x mb-3" style="color:rgba(233,30,140,0.2);"></i>
            <h5 style="color:#333;">No complaints filed</h5>
            <p style="color:#aaa;">We hope everything has been perfect for you!</p>
        </div>
    </div>
    @endforelse
</div>

@if($complaints->hasPages())
<div class="mt-4">{{ $complaints->links() }}</div>
@endif

@endsection