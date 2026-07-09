@extends('layouts.client')

@section('title', 'My Complaints — Glamora')

@push('styles')
<style>
    :root {
        --pk: #E91E8C;
        --pk-dark: #c2185b;
        --pk-light: #fce4ec;
        --pk-bg: #fdf2f8;
        --pk-edit: #3b82f6;
        --pk-delete: #ef4444;
        --pk-success: #22c55e;
        --pk-muted: #9ca3af;
    }

    /* ============================================ */
    /* Page Header Styles */
    /* ============================================ */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
    }
    .page-header h4 {
        font-weight: 700;
        margin: 0;
        color: #333;
        font-family: 'Playfair Display', serif;
    }
    .page-header h4 i {
        color: var(--pk);
        margin-right: 8px;
    }
    .page-header p {
        color: #aaa;
        font-size: 0.85rem;
        margin: 0;
    }

    /* ============================================ */
    /* New Complaint Button - Dark Pink */
    /* ============================================ */
    .btn-new {
        background: linear-gradient(135deg, var(--pk), var(--pk-dark));
        color: #fff;
        border: none;
        border-radius: 50px;
        padding: 8px 20px;
        font-weight: 600;
        font-size: 0.82rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s;
    }
    .btn-new:hover {
        transform: scale(1.02);
        box-shadow: 0 6px 20px rgba(233,30,140,0.3);
        color: #fff;
    }

    /* ============================================ */
    /* Status Tabs Styles */
    /* ============================================ */
    .status-tabs {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    .status-tab {
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        border: 1.5px solid var(--pk-light);
        background: #fff;
        color: #888;
    }
    .status-tab.active {
        background: var(--pk);
        color: #fff;
        border-color: var(--pk);
    }
    .status-tab:hover:not(.active) {
        border-color: var(--pk);
        color: var(--pk);
    }

    /* ============================================ */
    /* Complaint Card Styles */
    /* ============================================ */
    .complaint-card {
        background: #fff;
        border: 1px solid var(--pk-light);
        border-radius: 20px;
        padding: 1.5rem;
        transition: all 0.3s;
        margin-bottom: 1rem;
        position: relative;
        overflow: hidden;
    }
    .complaint-card:hover {
        border-color: var(--pk);
        box-shadow: 0 8px 25px rgba(233,30,140,0.08);
        transform: translateY(-3px);
    }
    .complaint-card .card-left {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        flex: 1;
    }
    
    /* ============================================ */
    /* Priority Icon Box - Dark Pink Circle */
    /* Exactly like Show Page */
    /* ============================================ */
    .complaint-card .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.1rem;
        background: #fff5f9;
        color: #E91E8C;
        border: 2px solid #fce4ec;
    }
    .complaint-card .icon-box.high {
        background: #fee2e2;
        color: #dc2626;
        border: 2px solid #fecaca;
    }
    .complaint-card .icon-box.medium {
        background: #fef3c7;
        color: #d97706;
        border: 2px solid #fde68a;
    }
    .complaint-card .icon-box.low {
        background: #d1fae5;
        color: #059669;
        border: 2px solid #a7f3d0;
    }

    .complaint-card .info h5 {
        font-weight: 700;
        font-size: 0.95rem;
        color: #333;
        margin: 0 0 4px 0;
    }
    .complaint-card .info .meta {
        font-size: 0.8rem;
        color: #888;
        display: flex;
        flex-wrap: wrap;
        gap: 4px 14px;
    }
    .complaint-card .info .meta i {
        color: var(--pk);
        width: 16px;
    }
    .complaint-card .info .description {
        font-size: 0.82rem;
        color: #666;
        margin-top: 6px;
        line-height: 1.5;
    }

    .complaint-card .right-section {
        display: flex;
        align-items: flex-end;
        flex-direction: column;
        gap: 8px;
        min-width: 100px;
    }
    .complaint-card .status-badge {
        padding: 4px 14px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: capitalize;
        display: inline-block;
    }
    .status-open { background: #fee2e2; color: #dc2626; }
    .status-in_review { background: #fef3c7; color: #d97706; }
    .status-resolved { background: #d1fae5; color: #059669; }
    .status-closed { background: #f3f4f6; color: #6b7280; }

    .complaint-card .actions {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }
    .btn-action {
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s;
        border: 1.5px solid transparent;
    }
    .btn-action.view {
        background: #fff0f7;
        color: var(--pk);
        border-color: var(--pk-light);
    }
    .btn-action.view:hover {
        background: var(--pk);
        color: #fff;
    }
    .btn-action.edit {
        background: #eff6ff;
        color: #2563eb;
        border-color: #bfdbfe;
    }
    .btn-action.edit:hover {
        background: #2563eb;
        color: #fff;
    }
    .btn-action.delete {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }
    .btn-action.delete:hover {
        background: #dc2626;
        color: #fff;
    }
    .btn-action.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: #f3f4f6;
        color: #9ca3af;
        border-color: #e5e7eb;
    }
    .btn-action.disabled:hover {
        background: #f3f4f6;
        color: #9ca3af;
        transform: none;
    }

    .complaint-card .replies-count {
        font-size: 0.7rem;
        color: #aaa;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .complaint-card .replies-count i {
        color: var(--pk);
    }

    /* ============================================ */
    /* Empty State Styles */
    /* ============================================ */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: #fff;
        border-radius: 20px;
        border: 2px dashed var(--pk-light);
    }
    .empty-state .empty-icon {
        font-size: 4rem;
        color: #E91E8C;
        display: block;
        margin-bottom: 16px;
        opacity: 0.6;
    }
    .empty-state h5 {
        font-weight: 700;
        color: #333;
        margin-bottom: 8px;
        font-size: 1.3rem;
    }
    .empty-state p {
        color: #888;
        max-width: 350px;
        margin: 0 auto 1rem;
        font-size: 0.95rem;
    }

    /* ============================================ */
    /* Complaint Icon - Dark Pink */
    /* ============================================ */
    .complaint-icon {
        color: #E91E8C;
    }

    /* ============================================ */
    /* Responsive Styles */
    /* ============================================ */
    @media (max-width: 768px) {
        .complaint-card {
            padding: 1rem;
        }
        .complaint-card .card-left {
            flex-direction: column;
            align-items: flex-start;
        }
        .complaint-card .right-section {
            align-items: flex-start;
            margin-top: 12px;
            width: 100%;
        }
        .complaint-card .actions {
            justify-content: flex-start;
        }
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')

{{-- ============================================ --}}
{{-- Page Header --}}
{{-- ============================================ --}}
<div class="page-header">
    <div>
        <h4><i class="fas fa-exclamation-circle"></i>My Complaints</h4>
        <p>Track status of your filed complaints</p>
    </div>
    <a href="{{ route('client.complaints.create') }}" class="btn-new">
        <i class="fas fa-plus me-1"></i>File New Complaint
    </a>
</div>

{{-- ============================================ --}}
{{-- Status Tabs --}}
{{-- ============================================ --}}
<div class="status-tabs">
    @php
        $tabs = ['all' => 'All', 'open' => 'Open', 'in_review' => 'In Review', 'resolved' => 'Resolved', 'closed' => 'Closed'];
        $current = request('status', 'all');
    @endphp
    @foreach($tabs as $val => $lbl)
        <a href="{{ route('client.complaints.index', ['status' => $val]) }}"
           class="status-tab {{ $current === $val ? 'active' : '' }}">
            {{ $lbl }}
        </a>
    @endforeach
</div>

{{-- ============================================ --}}
{{-- Complaints List --}}
{{-- ============================================ --}}
<div class="row g-4">
    @forelse($complaints as $complaint)
    <div class="col-12">
        <div class="complaint-card">

            <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">

                {{-- ============================================ --}}
                {{-- Left Side - Complaint Info --}}
                {{-- ============================================ --}}
                <div class="card-left">
                    {{-- Priority Icon - Dark Pink Circle --}}
                    @php
                        $priorityClass = $complaint->priority ?? 'medium';
                        $priorityIcon = $priorityClass === 'high' ? 'fa-exclamation' : ($priorityClass === 'medium' ? 'fa-circle' : 'fa-circle');
                    @endphp
                    <div class="icon-box {{ $priorityClass }}">
                        <i class="fas {{ $priorityIcon }}"></i>
                    </div>

                    {{-- Complaint Details --}}
                    <div class="info">
                        <h5>{{ $complaint->subject }}</h5>
                        <div class="meta">
                            <span><i class="fas fa-store"></i>{{ $complaint->salon->name ?? 'N/A' }}</span>
                            <span><i class="fas fa-tag"></i>{{ str_replace('_', ' ', ucfirst($complaint->type ?? 'general')) }}</span>
                            <span><i class="fas fa-calendar"></i>{{ $complaint->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="description">{{ Str::limit($complaint->description, 120) }}</div>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- Right Side - Status & Actions --}}
                {{-- ============================================ --}}
                <div class="right-section">
                    {{-- Status Badge --}}
                    @php
                        $statusLabels = [
                            'open' => ['label' => 'Open', 'class' => 'status-open'],
                            'in_review' => ['label' => 'In Review', 'class' => 'status-in_review'],
                            'resolved' => ['label' => 'Resolved', 'class' => 'status-resolved'],
                            'closed' => ['label' => 'Closed', 'class' => 'status-closed'],
                        ];
                        $st = $statusLabels[$complaint->status] ?? ['label' => ucfirst($complaint->status), 'class' => 'status-open'];
                    @endphp
                    <span class="status-badge {{ $st['class'] }}">{{ $st['label'] }}</span>

                    {{-- Replies Count --}}
                    <div class="replies-count">
                        <i class="fas fa-comment"></i>
                        {{ $complaint->replies->count() }} replies
                    </div>

                    {{-- Action Buttons --}}
                    <div class="actions">
                        {{-- View Button --}}
                        <a href="{{ route('client.complaints.show', $complaint->id) }}" class="btn-action view">
                            <i class="fas fa-eye"></i> View
                        </a>

                        {{-- Edit Button - Only if status is 'open' --}}
                        @if($complaint->status === 'open')
                            <a href="{{ route('client.complaints.edit', $complaint->id) }}" class="btn-action edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        @else
                            <span class="btn-action disabled" title="Cannot edit while in review or resolved">
                                <i class="fas fa-edit"></i> Edit
                            </span>
                        @endif

                        {{-- Delete Button - Only if status is 'open' --}}
                        @if($complaint->status === 'open')
                            <form action="{{ route('client.complaints.destroy', $complaint->id) }}" method="POST" style="display:inline;" 
                                  onsubmit="return confirm('Are you sure you want to withdraw this complaint? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action delete" style="border:none;cursor:pointer;padding:4px 12px;border-radius:8px;font-size:0.7rem;font-weight:600;display:inline-flex;align-items:center;gap:4px;background:#fef2f2;color:#dc2626;border:1.5px solid #fecaca;">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        @else
                            <span class="btn-action disabled" title="Cannot delete while in review or resolved">
                                <i class="fas fa-trash"></i> Delete
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    {{-- ============================================ --}}
    {{-- Empty State - No Complaints --}}
    {{-- ============================================ --}}
    <div class="col-12">
        <div class="empty-state">
            {{-- Complaint Icon - Dark Pink --}}
            <i class="fas fa-exclamation-circle empty-icon"></i>
            <h5>No complaints filed</h5>
            <p>We hope everything has been perfect for you!</p>
            {{-- Dark Pink Button --}}
            <a href="{{ route('client.complaints.create') }}" class="btn-new" style="display:inline-flex;">
                <i class="fas fa-plus me-1"></i>File a Complaint
            </a>
        </div>
    </div>
    @endforelse
</div>

{{-- ============================================ --}}
{{-- Pagination --}}
{{-- ============================================ --}}
@if($complaints->hasPages())
<div class="mt-4 d-flex justify-content-center">
    {{ $complaints->links() }}
</div>
@endif

@endsection