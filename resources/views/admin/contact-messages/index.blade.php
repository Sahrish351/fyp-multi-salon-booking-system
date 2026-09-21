@extends('layouts.admin')
 
@section('title', 'Contact Messages — Beauty Blush Salons')
 
@push('styles')
<style>
    :root {
        --pk: #FF6B9D;
        --pk-dark: #E85588;
        --pk-lt: #fce4ec;
        --pk-bg: #fff0f7;
        --ink: #1a1a2e;
        --ink-mid: #6b6b7b;
        --ink-lt: #a5a5b3;
        --line: #f0f0f3;
    }
 
    /* ================= HEADER ================= */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 1.4rem;
        flex-wrap: wrap;
        gap: 16px;
    }
    .page-header h4 {
        font-family: 'Playfair Display', serif;
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .page-header h4 .title-icon {
        width: 42px; height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--pk), var(--pk-dark));
        color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 1rem;
        box-shadow: 0 6px 16px rgba(255,107,157,.3);
    }
    .page-header p { color: var(--ink-lt); font-size: .85rem; margin: 6px 0 0 54px; }
 
    /* ================= STAT CARDS (clickable filters) ================= */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 1.4rem;
    }
    .stat-card {
        background: #fff;
        border: 1.5px solid var(--line);
        border-radius: 16px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        text-decoration: none;
        transition: all .2s;
        box-shadow: 0 2px 8px rgba(0,0,0,.02);
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,.05); }
    .stat-card .s-icon {
        width: 42px; height: 42px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: .95rem; flex-shrink: 0;
    }
    .stat-card .s-num { font-size: 1.4rem; font-weight: 700; line-height: 1; color: var(--ink); }
    .stat-card .s-label { font-size: .72rem; font-weight: 600; color: var(--ink-lt); text-transform: uppercase; letter-spacing: .5px; margin-top: 4px; }
 
    .stat-card.total  .s-icon { background: var(--pk-bg); color: var(--pk); }
    .stat-card.unread .s-icon { background: #fef2f2; color: #dc2626; }
    .stat-card.read   .s-icon { background: #f0fdf4; color: #16a34a; }
    .stat-card.replied .s-icon { background: #eff6ff; color: #2563eb; }
 
    .stat-card.active.total   { border-color: var(--pk);  background: var(--pk-bg); }
    .stat-card.active.unread  { border-color: #f87171; background: #fff7f7; }
    .stat-card.active.read    { border-color: #4ade80; background: #f7fdf9; }
    .stat-card.active.replied { border-color: #60a5fa; background: #f7faff; }
 
    /* ================= FILTER ================= */
    .filter-row {
        background: #fff;
        border-radius: 16px;
        padding: 1rem 1.3rem;
        margin-bottom: 1.4rem;
        border: 1px solid var(--line);
        box-shadow: 0 2px 8px rgba(0,0,0,.02);
    }
    .filter-row form {
        display: flex; align-items: flex-end; gap: 12px; flex-wrap: wrap;
    }
    .filter-row .form-group { display: flex; flex-direction: column; }
    .filter-row .form-group.grow { flex: 1; min-width: 220px; }
    .filter-row label {
        font-size: .62rem; font-weight: 700; color: #888;
        text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px;
    }
    .filter-row .form-control,
    .filter-row .form-select {
        border-radius: 10px;
        border: 1.5px solid var(--pk-lt);
        font-size: .82rem;
        padding: 8px 14px;
        min-width: 140px;
        transition: all .25s;
        background-color: #fdf7fa;
    }
    .filter-row .form-control:focus,
    .filter-row .form-select:focus {
        border-color: var(--pk);
        background-color: #fff;
        box-shadow: 0 0 0 3px rgba(255,107,157,.1);
        outline: none;
    }
    .search-wrap { position: relative; }
    .search-wrap i {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: var(--pk); font-size: .8rem; pointer-events: none;
    }
    .search-wrap .form-control { padding-left: 38px; width: 100%; }
 
    .filter-actions { display: flex; gap: 8px; align-items: center; margin-left: auto; }
    .btn-pink {
        background: linear-gradient(135deg, var(--pk), var(--pk-dark));
        color: #fff; border: none; border-radius: 50px;
        padding: 9px 22px; font-weight: 600; font-size: .8rem;
        transition: all .25s; cursor: pointer;
        display: inline-flex; align-items: center; gap: 6px;
        text-decoration: none;
    }
    .btn-pink:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,107,157,.35); color: #fff; }
    .btn-outline {
        background: #fff; color: #666; border: 1.5px solid #e6e6ea;
        border-radius: 50px; padding: 9px 18px; font-weight: 600; font-size: .8rem;
        transition: all .25s; text-decoration: none; cursor: pointer;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-outline:hover { background: var(--pk-bg); border-color: var(--pk-lt); color: var(--pk); }
 
    /* ================= TABLE CARD ================= */
    .card-table {
        background: #fff; border-radius: 18px; border: 1px solid var(--line);
        overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.03);
    }
    .card-table .card-header {
        padding: 1rem 1.5rem; background: #fff; border-bottom: 1px solid var(--line);
        display: flex; justify-content: space-between; align-items: center;
    }
    .card-table .card-header .title {
        font-weight: 700; color: var(--ink); font-size: .95rem;
        display: flex; align-items: center; gap: 8px;
    }
    .card-table .card-header .title i { color: var(--pk); }
    .card-table .card-header .showing {
        font-size: .75rem; color: var(--ink-lt);
        background: #f8f8fa; padding: 5px 14px; border-radius: 50px;
    }
 
    .data-table { width: 100%; border-collapse: collapse; font-size: .85rem; }
    .data-table thead th {
        background: #fafafb; padding: 12px 16px; text-align: left;
        font-weight: 700; color: #777; border-bottom: 1px solid var(--line);
        font-size: .68rem; text-transform: uppercase; letter-spacing: .6px;
        white-space: nowrap;
    }
    .data-table tbody td {
        padding: 14px 16px; border-bottom: 1px solid #f6f6f8; vertical-align: middle;
    }
    .data-table tbody tr { transition: background .2s; }
    .data-table tbody tr:hover { background: var(--pk-bg); }
    .data-table tbody tr:last-child td { border-bottom: none; }
    .data-table tbody tr.unread { background: #fffafc; }
    .data-table tbody tr.unread td:first-child { box-shadow: inset 3px 0 0 var(--pk); }
    .data-table tbody tr.unread:hover { background: var(--pk-bg); }
 
    .id-cell { font-weight: 600; color: var(--ink-lt); font-size: .75rem; }
 
    /* Sender */
    .user-cell { display: flex; align-items: center; gap: 12px; }
    .user-avatar {
        width: 40px; height: 40px; border-radius: 50%;
        background: linear-gradient(135deg, var(--pk), var(--pk-dark));
        color: #fff; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: .85rem; flex-shrink: 0;
        box-shadow: 0 3px 8px rgba(255,107,157,.25);
    }
    .user-avatar.green { background: linear-gradient(135deg, #34d399, #16a34a); box-shadow: 0 3px 8px rgba(22,163,74,.2); }
    .user-avatar.blue  { background: linear-gradient(135deg, #60a5fa, #2563eb); box-shadow: 0 3px 8px rgba(37,99,235,.2); }
    .user-avatar.gray  { background: linear-gradient(135deg, #cbd5e1, #94a3b8); box-shadow: none; }
    .user-info .name { font-weight: 600; color: var(--ink); font-size: .86rem; }
    .user-info .email { color: var(--ink-lt); font-size: .74rem; margin-top: 1px; }
    .user-info a { text-decoration: none; }
    .user-info a:hover .name { color: var(--pk); }
 
    /* Subject */
    .subject-cell .subject {
        color: var(--ink); font-size: .86rem; font-weight: 400;
        display: flex; align-items: center; gap: 8px;
    }
    .subject-cell.is-unread .subject { font-weight: 700; }
    .subject-cell .preview {
        color: var(--ink-lt); font-size: .75rem; margin-top: 3px;
        max-width: 320px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .new-tag {
        font-size: .58rem; font-weight: 700; color: #fff; background: var(--pk);
        padding: 2px 8px; border-radius: 50px; letter-spacing: .4px; text-transform: uppercase;
    }
 
    /* Status badge (blade mein hi bantay hain, model accessor pe depend nahi) */
    .badge-status {
        padding: 5px 14px; border-radius: 50px; font-size: .7rem; font-weight: 600;
        display: inline-flex; align-items: center; gap: 6px; white-space: nowrap;
    }
    .badge-status i { font-size: .65rem; }
    .badge-unread   { background: #fef2f2; color: #dc2626; }
    .badge-unread i { font-size: .4rem; }
    .badge-read     { background: #f0fdf4; color: #16a34a; }
    .badge-replied  { background: #eff6ff; color: #2563eb; }
    .badge-spam     { background: #fef3c7; color: #d97706; }
    .badge-archived { background: #f3f4f6; color: #6b7280; }
 
    /* Date */
    .date-cell { font-size: .78rem; color: #555; white-space: nowrap; }
    .date-cell .time-ago { color: var(--ink-lt); font-size: .68rem; display: block; margin-top: 2px; }
 
    /* Actions — chhotay round icon buttons */
    .action-btns { display: flex; gap: 6px; align-items: center; }
    .action-btns form { margin: 0; display: inline-flex; }
    .btn-action {
        width: 32px; height: 32px; border-radius: 9px;
        font-size: .75rem; border: 1.5px solid transparent;
        transition: all .2s; text-decoration: none; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .btn-action.view   { color: var(--pk);  border-color: var(--pk-lt); background: #fff5f9; }
    .btn-action.check  { color: #16a34a;    border-color: #bbf7d0;     background: #f0fdf4; }
    .btn-action.reply  { color: #2563eb;    border-color: #bfdbfe;     background: #eff6ff; }
    .btn-action.toggle { color: #f59e0b;    border-color: #fde68a;     background: #fffbeb; }
    .btn-action.delete { color: #ef4444;    border-color: #fecaca;     background: #fef2f2; }
    /* Sab ka hover sidebar jaisa light pink */
    .btn-action:hover {
        background: var(--pk-lt); color: var(--pk-dark); border-color: var(--pk);
        transform: translateY(-1px);
    }
 
    /* Pagination */
    .pagination-wrapper {
        padding: 1rem 1.5rem; border-top: 1px solid var(--line);
        display: flex; justify-content: center;
    }
 
    /* Empty */
    .empty-state { text-align: center; padding: 4rem 2rem; }
    .empty-state .icon {
        width: 90px; height: 90px; border-radius: 50%; background: var(--pk-bg);
        color: var(--pk); font-size: 2.2rem; margin: 0 auto 1.2rem;
        display: flex; align-items: center; justify-content: center;
    }
    .empty-state h5 { color: var(--ink); font-weight: 700; font-size: 1.1rem; margin-bottom: 6px; }
    .empty-state p { color: var(--ink-lt); font-size: .88rem; max-width: 360px; margin: 0 auto 1rem; }
 
    /* ================= RESPONSIVE ================= */
    @media (max-width: 992px) {
        .stats-row { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .page-header p { margin-left: 0; }
        .filter-row form { flex-direction: column; align-items: stretch; }
        .filter-row .form-group.grow { min-width: 0; }
        .filter-actions { margin-left: 0; }
        .data-table { font-size: .78rem; }
        .data-table thead th, .data-table tbody td { padding: 10px; }
        .subject-cell .preview { max-width: 180px; }
        .card-table .card-header .showing { display: none; }
    }
    @media (max-width: 480px) {
        .stats-row { grid-template-columns: 1fr 1fr; gap: 10px; }
        .stat-card { padding: 12px; gap: 10px; }
        .stat-card .s-icon { width: 36px; height: 36px; }
        .stat-card .s-num { font-size: 1.15rem; }
    }
</style>
@endpush
 
@section('content')
 
@php
    $currentStatus = request('status');
    $hasFilters = request()->filled('search') || request()->filled('status') || request()->filled('date_from') || request()->filled('date_to');
@endphp
 
{{-- ============ PAGE HEADER ============ --}}
<div class="page-header">
    <div>
        <h4>
            <span class="title-icon"><i class="fas fa-envelope"></i></span>
            Contact Messages
        </h4>
        <p>Customers ke inquiries aur messages yahan manage karein</p>
    </div>
</div>
 
{{-- ============ STAT CARDS ============ --}}
<div class="stats-row">
    <a href="{{ route('admin.contact-messages.index') }}" class="stat-card total {{ !$currentStatus ? 'active' : '' }}">
        <span class="s-icon"><i class="fas fa-inbox"></i></span>
        <div>
            <div class="s-num">{{ $totalCount ?? $messages->total() }}</div>
            <div class="s-label">Total</div>
        </div>
    </a>
    <a href="{{ route('admin.contact-messages.index', ['status' => 'unread']) }}" class="stat-card unread {{ $currentStatus === 'unread' ? 'active' : '' }}">
        <span class="s-icon"><i class="fas fa-envelope"></i></span>
        <div>
            <div class="s-num">{{ $unreadCount }}</div>
            <div class="s-label">Unread</div>
        </div>
    </a>
    <a href="{{ route('admin.contact-messages.index', ['status' => 'read']) }}" class="stat-card read {{ $currentStatus === 'read' ? 'active' : '' }}">
        <span class="s-icon"><i class="fas fa-envelope-open"></i></span>
        <div>
            <div class="s-num">{{ $readCount }}</div>
            <div class="s-label">Read</div>
        </div>
    </a>
    <a href="{{ route('admin.contact-messages.index', ['status' => 'replied']) }}" class="stat-card replied {{ $currentStatus === 'replied' ? 'active' : '' }}">
        <span class="s-icon"><i class="fas fa-reply-all"></i></span>
        <div>
            <div class="s-num">{{ $repliedCount }}</div>
            <div class="s-label">Replied</div>
        </div>
    </a>
</div>
 
{{-- ============ FILTER ROW ============ --}}
<div class="filter-row">
    <form method="GET">
        <div class="form-group grow">
            <label>Search</label>
            <div class="search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" name="search" class="form-control" placeholder="Name, email, subject..." value="{{ request('search') }}">
            </div>
        </div>
 
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="unread"   {{ request('status') == 'unread'   ? 'selected' : '' }}>Unread</option>
                <option value="read"     {{ request('status') == 'read'     ? 'selected' : '' }}>Read</option>
                <option value="replied"  {{ request('status') == 'replied'  ? 'selected' : '' }}>Replied</option>
                <option value="spam"     {{ request('status') == 'spam'     ? 'selected' : '' }}>Spam</option>
                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
            </select>
        </div>
 
        <div class="form-group">
            <label>From</label>
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>
 
        <div class="form-group">
            <label>To</label>
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>
 
        <div class="filter-actions">
            <button type="submit" class="btn-pink"><i class="fas fa-filter"></i> Apply</button>
            @if($hasFilters)
            <a href="{{ route('admin.contact-messages.index') }}" class="btn-outline"><i class="fas fa-times"></i> Clear</a>
            @endif
        </div>
    </form>
</div>
 
{{-- ============ TABLE ============ --}}
<div class="card-table">
    <div class="card-header">
        <span class="title"><i class="fas fa-list"></i> All Messages</span>
        @if($messages->total() > 0)
        <span class="showing">
            Showing {{ $messages->firstItem() }}–{{ $messages->lastItem() }} of {{ $messages->total() }}
        </span>
        @endif
    </div>
 
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:60px;">#</th>
                    <th style="min-width:210px;">Sender</th>
                    <th style="min-width:230px;">Subject</th>
                    <th style="min-width:110px;">Status</th>
                    <th style="min-width:130px;">Date</th>
                    <th style="min-width:150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $message)
                @php
                    $statusMap = [
                        'unread'   => ['label' => 'Unread',   'class' => 'badge-unread',   'icon' => 'fa-circle',               'avatar' => ''],
                        'read'     => ['label' => 'Read',     'class' => 'badge-read',     'icon' => 'fa-check-circle',         'avatar' => 'green'],
                        'replied'  => ['label' => 'Replied',  'class' => 'badge-replied',  'icon' => 'fa-reply-all',            'avatar' => 'blue'],
                        'spam'     => ['label' => 'Spam',     'class' => 'badge-spam',     'icon' => 'fa-exclamation-triangle', 'avatar' => 'gray'],
                        'archived' => ['label' => 'Archived', 'class' => 'badge-archived', 'icon' => 'fa-box-archive',          'avatar' => 'gray'],
                    ];
                    $statusInfo = $statusMap[$message->status] ?? $statusMap['read'];
                    $isUnread = $message->status === 'unread';
                @endphp
                <tr class="{{ $isUnread ? 'unread' : '' }}">
                    <td class="id-cell">#{{ $message->id }}</td>
 
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar {{ $statusInfo['avatar'] }}">
                                {{ strtoupper(substr($message->name, 0, 1)) }}
                            </div>
                            <div class="user-info">
                                <a href="{{ route('admin.contact-messages.show', $message->id) }}">
                                    <div class="name">{{ $message->name }}</div>
                                </a>
                                <div class="email">{{ $message->email }}</div>
                            </div>
                        </div>
                    </td>
 
                    <td class="subject-cell {{ $isUnread ? 'is-unread' : '' }}">
                        <div class="subject">
                            {{ Str::limit($message->subject, 45) }}
                            @if($isUnread)<span class="new-tag">New</span>@endif
                        </div>
                        <div class="preview">{{ Str::limit($message->message, 80) }}</div>
                    </td>
 
                    <td>
                        <span class="badge-status {{ $statusInfo['class'] }}">
                            <i class="fas {{ $statusInfo['icon'] }}"></i> {{ $statusInfo['label'] }}
                        </span>
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
 
                            @if($isUnread)
                            <form action="{{ route('admin.contact-messages.mark-read', $message->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-action check" title="Mark as Read">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
 
                            @if($message->status === 'read')
                            <form action="{{ route('admin.contact-messages.mark-unread', $message->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-action toggle" title="Mark as Unread">
                                    <i class="fas fa-undo"></i>
                                </button>
                            </form>
                            @endif
 
                            <a href="{{ route('admin.contact-messages.show', $message->id) }}#reply" class="btn-action reply" title="Reply">
                                <i class="fas fa-reply"></i>
                            </a>
 
                            <form action="{{ route('admin.contact-messages.destroy', $message->id) }}" method="POST"
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
                            <div class="icon"><i class="fas {{ $hasFilters ? 'fa-search' : 'fa-inbox' }}"></i></div>
                            <h5>{{ $hasFilters ? 'Koi match nahi mila' : 'No messages yet' }}</h5>
                            <p>
                                {{ $hasFilters
                                    ? 'Filters change karke dobara try karein.'
                                    : 'Customer messages yahan nazar aayenge jab woh website ke through contact karenge.' }}
                            </p>
                            @if($hasFilters)
                            <a href="{{ route('admin.contact-messages.index') }}" class="btn-outline">
                                <i class="fas fa-times"></i> Clear Filters
                            </a>
                            @endif
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