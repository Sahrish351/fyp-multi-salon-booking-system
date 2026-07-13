@extends('layouts.admin')

@section('title', 'Contact Messages — Glamora')

@push('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 15px;
    }
    .page-header .title-section h4 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0;
    }
    .page-header .title-section h4 i {
        color: #E91E8C;
        margin-right: 10px;
    }
    .page-header .title-section p {
        color: #aaa;
        font-size: 0.85rem;
        margin: 4px 0 0 0;
    }
    .header-stats {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }
    .stat-badge {
        padding: 8px 18px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        background: #fff;
        border: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .stat-badge .count {
        font-size: 1.1rem;
        font-weight: 700;
    }
    .stat-badge.unread {
        background: #fef2f2;
        border-color: #fecaca;
        color: #dc2626;
    }
    .stat-badge.unread .count {
        color: #dc2626;
    }
    .stat-badge.read {
        background: #f0fdf4;
        border-color: #bbf7d0;
        color: #16a34a;
    }
    .stat-badge.read .count {
        color: #16a34a;
    }
    .stat-badge.replied {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #2563eb;
    }
    .stat-badge.replied .count {
        color: #2563eb;
    }
    .stat-badge.total {
        background: #fdf2f8;
        border-color: #fce4ec;
        color: #E91E8C;
    }
    .stat-badge.total .count {
        color: #E91E8C;
    }
    .filter-row {
        background: #fff;
        border-radius: 16px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .filter-row .form-group {
        display: flex;
        flex-direction: column;
    }
    .filter-row .form-group label {
        font-size: 0.6rem;
        font-weight: 700;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 3px;
    }
    .filter-row .form-control,
    .filter-row .form-select {
        border-radius: 10px;
        border: 1.5px solid #f0f0f0;
        font-size: 0.8rem;
        padding: 7px 14px;
        min-width: 140px;
        transition: all 0.3s;
        background: #fafafa;
    }
    .filter-row .form-control:focus,
    .filter-row .form-select:focus {
        border-color: #E91E8C;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(233, 30, 140, 0.08);
    }
    .filter-actions {
        display: flex;
        gap: 8px;
        align-items: center;
        margin-left: auto;
    }
    .btn-pink {
        background: linear-gradient(135deg, #E91E8C, #c2185b);
        color: #fff;
        border: none;
        border-radius: 50px;
        padding: 8px 22px;
        font-weight: 600;
        font-size: 0.8rem;
        transition: all 0.3s;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-pink:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(233, 30, 140, 0.35);
        color: #fff;
    }
    .btn-outline {
        background: transparent;
        color: #666;
        border: 1.5px solid #e0e0e0;
        border-radius: 50px;
        padding: 8px 18px;
        font-weight: 600;
        font-size: 0.8rem;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }
    .btn-outline:hover {
        background: #f5f5f5;
        border-color: #ccc;
        color: #333;
    }
    .total-count {
        color: #aaa;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 6px;
        padding-left: 10px;
        border-left: 1px solid #f0f0f0;
    }
    .total-count i {
        color: #E91E8C;
    }
    .card-table {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #f0f0f0;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .card-table .card-header {
        padding: 1rem 1.5rem;
        background: #fafafa;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .card-table .card-header .title {
        font-weight: 600;
        color: #1a1a2e;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .card-table .card-header .title i {
        color: #E91E8C;
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }
    .data-table thead th {
        background: #f8f9fa;
        padding: 12px 16px;
        text-align: left;
        font-weight: 700;
        color: #555;
        border-bottom: 2px solid #f0f0f0;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .data-table tbody td {
        padding: 12px 16px;
        border-bottom: 1px solid #f5f5f5;
        vertical-align: middle;
    }
    .data-table tbody tr {
        transition: all 0.2s;
    }
    .data-table tbody tr:hover {
        background: #fdf2f8;
    }
    .data-table tbody tr.unread {
        background: #fff8fa;
        border-left: 3px solid #E91E8C;
    }
    .data-table tbody tr.unread:hover {
        background: #fdf2f8;
    }
    .data-table tbody tr:last-child td {
        border-bottom: none;
    }
    .badge-status {
        padding: 4px 14px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .badge-unread {
        background: #fef2f2;
        color: #dc2626;
    }
    .badge-unread i {
        font-size: 0.4rem;
        color: #dc2626;
    }
    .badge-read {
        background: #f0fdf4;
        color: #16a34a;
    }
    .badge-read i {
        color: #16a34a;
    }
    .badge-replied {
        background: #eff6ff;
        color: #2563eb;
    }
    .badge-replied i {
        color: #2563eb;
    }
    .badge-spam {
        background: #fef3c7;
        color: #d97706;
    }
    .badge-spam i {
        color: #d97706;
    }
    .badge-archived {
        background: #f3f4f6;
        color: #6b7280;
    }
    .badge-archived i {
        color: #6b7280;
    }
    .user-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #E91E8C, #c2185b);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
        flex-shrink: 0;
    }
    .user-avatar.green {
        background: linear-gradient(135deg, #22c55e, #16a34a);
    }
    .user-info .name {
        font-weight: 600;
        color: #1a1a2e;
        font-size: 0.85rem;
    }
    .user-info .email {
        color: #aaa;
        font-size: 0.75rem;
    }
    .action-btns {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
    }
    .btn-action {
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 600;
        border: 1.5px solid transparent;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        cursor: pointer;
        background: transparent;
    }
    .btn-action.view {
        color: #E91E8C;
        border-color: #fce4ec;
        background: #fff5f9;
    }
    .btn-action.view:hover {
        background: #E91E8C;
        color: #fff;
    }
    .btn-action.delete {
        color: #ef4444;
        border-color: #fecaca;
        background: #fef2f2;
    }
    .btn-action.delete:hover {
        background: #ef4444;
        color: #fff;
    }
    .btn-action.reply {
        color: #2563eb;
        border-color: #bfdbfe;
        background: #eff6ff;
    }
    .btn-action.reply:hover {
        background: #2563eb;
        color: #fff;
    }
    .date-cell {
        font-size: 0.75rem;
        color: #888;
    }
    .date-cell .time-ago {
        color: #bbb;
        font-size: 0.65rem;
        display: block;
    }
    .pagination-wrapper {
        padding: 1rem 1.5rem;
        border-top: 1px solid #f0f0f0;
        display: flex;
        justify-content: center;
    }
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }
    .empty-state .icon {
        font-size: 4rem;
        color: rgba(233, 30, 140, 0.12);
        display: block;
        margin-bottom: 1rem;
    }
    .empty-state h5 {
        color: #1a1a2e;
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 6px;
    }
    .empty-state p {
        color: #aaa;
        font-size: 0.9rem;
        max-width: 350px;
        margin: 0 auto;
    }
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .header-stats {
            width: 100%;
            overflow-x: auto;
            flex-wrap: nowrap;
        }
        .stat-badge {
            white-space: nowrap;
            font-size: 0.7rem;
            padding: 6px 14px;
        }
        .filter-row {
            flex-direction: column;
            align-items: stretch;
        }
        .filter-actions {
            margin-left: 0;
            flex-wrap: wrap;
        }
        .data-table {
            font-size: 0.75rem;
        }
        .data-table thead th,
        .data-table tbody td {
            padding: 8px 10px;
        }
        .action-btns {
            flex-direction: column;
        }
        .total-count {
            border-left: none;
            padding-left: 0;
        }
    }
</style>
@endpush

@section('content')

{{-- ============================================================ --}}
{{-- PAGE HEADER --}}
{{-- ============================================================ --}}
<div class="page-header">
    <div class="title-section">
        <h4>
            <i class="fas fa-envelope"></i>Contact Messages
        </h4>
        <p>Manage customer inquiries and messages</p>
    </div>
    <div class="header-stats">
        <div class="stat-badge total">
            <i class="fas fa-inbox"></i>
            Total <span class="count">{{ $totalCount ?? $messages->total() }}</span>
        </div>
        <div class="stat-badge unread">
            <i class="fas fa-circle" style="font-size:0.5rem;"></i>
            Unread <span class="count">{{ $unreadCount }}</span>
        </div>
        <div class="stat-badge read">
            <i class="fas fa-check-circle"></i>
            Read <span class="count">{{ $readCount }}</span>
        </div>
        <div class="stat-badge replied">
            <i class="fas fa-reply-all"></i>
            Replied <span class="count">{{ $repliedCount }}</span>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- FILTER ROW --}}
{{-- ============================================================ --}}
<div class="filter-row">
    <form method="GET" style="display:flex;align-items:flex-end;gap:12px;flex-wrap:wrap;width:100%;">
        
        <div class="form-group">
            <label><i class="fas fa-search me-1"></i>Search</label>
            <input type="text" name="search" class="form-control" placeholder="Name, email, subject..." value="{{ request('search') }}">
        </div>
        
        <div class="form-group">
            <label><i class="fas fa-filter me-1"></i>Status</label>
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>🔴 Unread</option>
                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>🟢 Read</option>
                <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>🔵 Replied</option>
                <option value="spam" {{ request('status') == 'spam' ? 'selected' : '' }}>🟡 Spam</option>
                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>⚪ Archived</option>
            </select>
        </div>
        
        <div class="form-group">
            <label><i class="fas fa-calendar me-1"></i>From</label>
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>
        
        <div class="form-group">
            <label><i class="fas fa-calendar me-1"></i>To</label>
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>
        
        <div class="filter-actions">
            <button type="submit" class="btn-pink">
                <i class="fas fa-filter me-1"></i> Apply
            </button>
            <a href="{{ route('admin.contact-messages.index') }}" class="btn-outline">
                <i class="fas fa-times me-1"></i> Clear
            </a>
            <span class="total-count">
                <i class="fas fa-database"></i> {{ $messages->total() }} entries
            </span>
        </div>
    </form>
</div>

{{-- ============================================================ --}}
{{-- TABLE --}}
{{-- ============================================================ --}}
<div class="card-table">
    <div class="card-header">
        <span class="title">
            <i class="fas fa-list"></i> All Messages
        </span>
        @if($messages->total() > 0)
        <span style="font-size:0.75rem;color:#aaa;">
            Showing {{ $messages->firstItem() }}–{{ $messages->lastItem() }} of {{ $messages->total() }}
        </span>
        @endif
    </div>
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th style="min-width:180px;">Sender</th>
                    <th style="min-width:160px;">Subject</th>
                    <th style="min-width:100px;">Status</th>
                    <th style="min-width:130px;">Date</th>
                    <th style="min-width:140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $message)
                <tr class="{{ $message->status === 'unread' ? 'unread' : '' }}">
                    <td style="font-weight:600;color:#aaa;font-size:0.75rem;">#{{ $message->id }}</td>
                    
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar {{ $message->status === 'unread' ? '' : 'green' }}">
                                {{ strtoupper(substr($message->name, 0, 1)) }}
                            </div>
                            <div class="user-info">
                                <div class="name">{{ $message->name }}</div>
                                <div class="email">{{ $message->email }}</div>
                            </div>
                        </div>
                    </td>
                    
                    <td>
                        <div style="font-weight:{{ $message->status === 'unread' ? '600' : '400' }};color:#1a1a2e;">
                            {{ Str::limit($message->subject, 40) }}
                        </div>
                        @if($message->status === 'unread')
                        <span style="font-size:0.6rem;color:#E91E8C;font-weight:600;">
                            <i class="fas fa-circle" style="font-size:0.3rem;"></i> New
                        </span>
                        @endif
                    </td>
                    
                    <td>
                        {!! $message->status_badge !!}
                    </td>
                    
                    <td>
                        <div class="date-cell">
                            {{ $message->created_at->format('d M Y') }}
                            <span class="time-ago">{{ $message->created_at->diffForHumans() }}</span>
                        </div>
                    </td>
                    
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('admin.contact-messages.show', $message->id) }}" class="btn-action view" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            @if($message->status === 'unread')
                            <form action="{{ route('admin.contact-messages.mark-read', $message->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-action view" title="Mark as Read">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                            
                            @if($message->status === 'read')
                            <form action="{{ route('admin.contact-messages.mark-unread', $message->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-action" title="Mark as Unread" style="color:#f59e0b;border-color:#fde68a;background:#fffbeb;">
                                    <i class="fas fa-undo"></i>
                                </button>
                            </form>
                            @endif
                            
                            <a href="{{ route('admin.contact-messages.show', $message->id) }}#reply" class="btn-action reply" title="Reply">
                                <i class="fas fa-reply"></i>
                            </a>
                            
                            <form action="{{ route('admin.contact-messages.destroy', $message->id) }}" method="POST" style="display:inline;" 
                                  onsubmit="return confirm('Delete this message?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action delete" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <span class="icon"><i class="fas fa-inbox"></i></span>
                            <h5>No messages found</h5>
                            <p>Customer messages will appear here when they contact you through the website.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($messages->hasPages())
    <div class="pagination-wrapper">
        {{ $messages->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@endsection