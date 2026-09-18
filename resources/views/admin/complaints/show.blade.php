@extends('layouts.admin')
@section('title', 'Complaint Details - Admin')

@push('styles')
<style>
    :root { 
        --dpink: #FF6B9D; 
        --dpink-lt: #fce4ec; 
        --dpink-hover: #E85588; 
    }
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .48rem 1.05rem;
        border: 1.5px solid #e5e5e5;
        border-radius: 9px;
        font-size: .85rem;
        font-weight: 600;
        color: #888;
        text-decoration: none;
        background: #fff;
        transition: all .15s;
        margin-bottom: 1.6rem;
    }
    .btn-back:hover {
        border-color: var(--dpink);
        color: var(--dpink);
    }
    .dcard {
        background: #fff;
        border: 1px solid #ebebeb;
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 1.2rem;
        box-shadow: 0 2px 6px rgba(0,0,0,.04);
    }
    .dcard-head {
        padding: 1rem 1.35rem;
        border-bottom: 1px solid #f3f3f3;
        display: flex;
        align-items: center;
        gap: .6rem;
    }
    .dcard-head i {
        color: var(--dpink);
        font-size: .95rem;
    }
    .dcard-title {
        font-weight: 700;
        font-size: .95rem;
        color: #1a1a1a;
    }
    .dcard-body {
        padding: 1.35rem;
    }
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.2rem;
    }
    @media(max-width:600px){
        .info-grid { grid-template-columns: 1fr; }
    }
    .info-lbl {
        font-size: .68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #9a9a9a;
        margin-bottom: .3rem;
        display: block;
    }
    .info-val {
        font-size: .9rem;
        color: #1a1a1a;
        font-weight: 600;
    }
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .3rem .8rem;
        border-radius: 20px;
        font-size: .75rem;
        font-weight: 700;
    }
    .form-group {
        display: flex;
        flex-direction: column;
        gap: .4rem;
        margin-bottom: 1rem;
    }
    .form-group label {
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #777;
    }
    .form-control {
        width: 100%;
        padding: .65rem .95rem;
        border: 1.5px solid #e5e5e5;
        border-radius: 9px;
        font-size: .87rem;
        background: #fafafa;
        color: #1a1a1a;
        outline: none;
        transition: all .2s;
        font-family: inherit;
        box-sizing: border-box;
    }
    .form-control:focus {
        border-color: var(--dpink);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(255,107,157,.1);
    }
    .btn-submit {
        background: var(--dpink);
        color: #fff;
        border: none;
        padding: .65rem 1.2rem;
        border-radius: 9px;
        font-size: .85rem;
        font-weight: 700;
        cursor: pointer;
        transition: background .18s;
        width: 100%;
    }
    .btn-submit:hover { background: var(--dpink-hover); }
    .complaint-layout {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 1.4rem;
        align-items: start;
    }
    @media(max-width:900px){
        .complaint-layout { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

<a href="{{ route('admin.complaints.index') }}" class="btn-back">
    <i class="fas fa-arrow-left"></i> Back to Complaints
</a>

{{-- Alerts --}}
@if(session('success'))
<div style="background:#eaf3eb;border:1px solid #a8d5b0;color:#2d6a35;border-radius:10px;padding:.8rem 1.1rem;margin-bottom:1.2rem;font-size:.87rem;display:flex;align-items:center;gap:.5rem;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<div class="complaint-layout">

    {{-- LEFT COLUMN --}}
    <div>
        {{-- Complaint Overview Card --}}
        <div class="dcard">
            <div class="dcard-head">
                <i class="fas fa-exclamation-circle"></i>
                <span class="dcard-title">Complaint Ticket #{{ $complaint->id }}</span>
            </div>
            <div class="dcard-body">
                <div class="info-grid">
                    <div>
                        <span class="info-lbl">Client Details</span>
                        <div class="info-val">{{ $complaint->client->name ?? 'N/A' }}</div>
                        <div style="font-size:.78rem;color:#9a9a9a;">{{ $complaint->client->email ?? '' }}</div>
                    </div>
                    <div>
                        <span class="info-lbl">Salon Name</span>
                        <div class="info-val">{{ $complaint->salon->name ?? 'N/A' }}</div>
                        <div style="font-size:.78rem;color:#9a9a9a;">{{ $complaint->salon->city ?? '' }}</div>
                    </div>
                    <div>
                        <span class="info-lbl">Complaint Type</span>
                        <div class="info-val"><span style="background:#f3f3f3;padding:.2rem .6rem;border-radius:12px;font-size:.75rem;color:#555;">{{ $complaint->type_label }}</span></div>
                    </div>
                    <div>
                        <span class="info-lbl">Date Filed</span>
                        <div class="info-val">{{ $complaint->created_at->format('d M Y, h:i A') }}</div>
                    </div>
                </div>

                <div style="margin-top:1.4rem;padding-top:1.2rem;border-top:1px solid #f3f3f3;">
                    <span class="info-lbl">Subject</span>
                    <div style="font-weight:700;font-size:1.05rem;color:#1a1a1a;margin-top:.2rem;">{{ $complaint->subject }}</div>
                </div>

                <div style="margin-top:1.2rem;padding:1.1rem;background:#faf8f6;border-radius:10px;border-left:3px solid var(--dpink);">
                    <span class="info-lbl" style="margin-bottom:.35rem;">Detailed Description</span>
                    <p style="margin:0;font-size:.9rem;color:#333;line-height:1.7;">{{ $complaint->description }}</p>
                </div>
            </div>
        </div>

        {{-- Previous Admin Response Section (if exists) --}}
        @if($complaint->admin_response)
        <div class="dcard">
            <div class="dcard-head">
                <i class="fas fa-comment-dots"></i>
                <span class="dcard-title">Admin Response History</span>
            </div>
            <div class="dcard-body">
                <div style="padding:1.1rem;background:#f0f7f4;border-radius:10px;border-left:3px solid #2e7d32;">
                    <p style="margin:0;font-size:.9rem;color:#1b5e20;line-height:1.7;">{{ $complaint->admin_response }}</p>
                    <div style="font-size:.72rem;color:#558b2f;margin-top:.5rem;font-weight:600;">Sent at: {{ optional($complaint->admin_actioned_at)->format('d M Y, h:i A') }}</div>
                </div>
            </div>
        </div>
        @endif

        {{-- Resolution Notes Section (if any) --}}
        @if($complaint->resolution_notes)
        <div class="dcard">
            <div class="dcard-head">
                <i class="fas fa-clipboard-check"></i>
                <span class="dcard-title">Resolution Notes</span>
            </div>
            <div class="dcard-body">
                <div style="padding:1.1rem;background:#eaf3eb;border-radius:10px;border-left:3px solid #5a8a62;">
                    <p style="margin:0;font-size:.9rem;color:#2d5a35;line-height:1.7;">{{ $complaint->resolution_notes }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- RIGHT COLUMN (Status Update, Admin Reply & Actions) --}}
    <div>
        {{-- Status Update Card --}}
        <div class="dcard">
            <div class="dcard-head">
                <i class="fas fa-sliders-h"></i>
                <span class="dcard-title">Update Status</span>
            </div>
            <div class="dcard-body">
                <div style="margin-bottom:1.2rem;text-align:center;padding:1.1rem;background:#faf8f6;border-radius:10px;border:1px solid #f0f0f0;">
                    <span class="info-lbl" style="margin-bottom:.3rem;">Current Status</span>
                    <span class="badge-status badge-{{ $complaint->status }}" style="font-size:.85rem;margin-top:.2rem;">
                        {{ $complaint->status_label }}
                    </span>
                </div>

                {{-- Status Update Form --}}
                <form action="{{ route('admin.complaints.update', $complaint->id) }}" method="POST">
                    @csrf @method('PUT')
                    
                    <div class="form-group">
                        <label>Change Status</label>
                        <select name="status" class="form-control">
                            <option value="pending" {{ $complaint->status=='pending' ? 'selected':'' }}>Pending</option>
                            <option value="in_progress" {{ $complaint->status=='in_progress' ? 'selected':'' }}>In Progress</option>
                            <option value="resolved" {{ $complaint->status=='resolved' ? 'selected':'' }}>Resolved</option>
                            <option value="closed" {{ $complaint->status=='closed' ? 'selected':'' }}>Closed</option>
                            <option value="escalated" {{ $complaint->status=='escalated' ? 'selected':'' }}>Escalated</option>
                            <option value="rejected" {{ $complaint->status=='rejected' ? 'selected':'' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Resolution Notes</label>
                        <textarea name="resolution_notes" class="form-control" rows="3" placeholder="Add resolution notes...">{{ old('resolution_notes', $complaint->resolution_notes) }}</textarea>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save" style="margin-right:.4rem;"></i> Update Status
                    </button>
                </form>
            </div>
        </div>

        {{-- Admin Reply & Direct Close Card --}}
        <div class="dcard">
            <div class="dcard-head">
                <i class="fas fa-reply"></i>
                <span class="dcard-title">Respond to Client</span>
            </div>
            <div class="dcard-body">
                <form action="{{ route('admin.complaints.respond', $complaint->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Admin Reply Message</label>
                        <textarea name="admin_response" class="form-control" rows="4" placeholder="Write response to client..." required>{{ old('admin_response') }}</textarea>
                    </div>
                    <button type="submit" class="btn-submit" style="background:#2e7d32;margin-bottom:.8rem;">
                        <i class="fas fa-paper-plane" style="margin-right:.4rem;"></i> Send Reply & Close
                    </button>
                </form>

                <hr style="border:0;border-top:1px solid #f3f3f3;margin:1rem 0;">

                <form action="{{ route('admin.complaints.close', $complaint->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to close this complaint?');">
                    @csrf
                    <button type="submit" class="btn-submit" style="background:#d32f2f;">
                        <i class="fas fa-check-circle" style="margin-right:.4rem;"></i> Close Without Reply
                    </button>
                </form>
            </div>
        </div>

        {{-- Meta Information Card --}}
        <div class="dcard">
            <div class="dcard-head">
                <i class="fas fa-info-circle"></i>
                <span class="dcard-title">Ticket Meta</span>
            </div>
            <div class="dcard-body" style="padding:1rem 1.35rem;">
                @foreach([
                    ['Ticket ID', '#'.$complaint->id],
                    ['Client Name', $complaint->client->name ?? 'N/A'],
                    ['Salon City', $complaint->salon->city ?? 'N/A'],
                    ['Last Updated', $complaint->updated_at->diffForHumans()],
                ] as [$lbl,$val])
                <div style="display:flex;justify-content:space-between;align-items:center;padding:.6rem 0;border-bottom:1px solid #f3f3f3;">
                    <span style="font-size:.8rem;color:#9a9a9a;">{{ $lbl }}</span>
                    <span style="font-size:.82rem;font-weight:600;color:#1a1a1a;">{{ $val }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

@endsection