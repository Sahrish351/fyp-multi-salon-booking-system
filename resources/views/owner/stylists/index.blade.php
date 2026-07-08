{{--
    ===========================================================
    TEAM MEMBERS / STYLISTS INDEX PAGE
    ===========================================================
--}}
@extends('layouts.owner')

@section('title', 'Team Members')

@section('content')

    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>Team Members</h2>
            <p>Manage your salon staff</p>
        </div>
        <a href="{{ route('owner.stylists.create') }}" class="btn btn-add-stylist">
            <i class="bi bi-plus-lg me-2"></i> Add Team Member
        </a>
    </div>

    <div class="row g-4">
        @forelse ($stylists as $stylist)
            <div class="col-md-6 col-lg-4">
                <div class="stylist-card">
                    <a href="{{ route('owner.stylists.show', ['stylist' => $stylist['id']]) }}" class="stylist-top-link">
                        <div class="stylist-avatar mx-auto">
                            @if (!empty($stylist['photo_url']))
                                <img src="{{ $stylist['photo_url'] }}" alt="{{ $stylist['name'] }}">
                            @else
                                <i class="bi bi-person-fill"></i>
                            @endif
                        </div>
                        <h5 class="stylist-name">{{ $stylist['name'] }}</h5>
                        <p class="stylist-role">{{ $stylist['role'] }}</p>
                        <div class="stylist-rating">
                            <i class="bi bi-star-fill"></i> {{ $stylist['rating'] }}
                        </div>
                    </a>

                    <div class="stylist-meta-row">
                        <div class="meta-col">
                            <span class="meta-label">Clients</span>
                            <span class="meta-value">{{ $stylist['clients'] }}</span>
                        </div>
                        <div class="meta-col text-end">
                            <span class="meta-label">Revenue</span>
                            <span class="meta-value amount-gold">PKR {{ number_format($stylist['revenue']) }}</span>
                        </div>
                    </div>

                    <div class="stylist-actions">
                        <a href="{{ route('owner.stylists.edit', ['stylist' => $stylist['id']]) }}"
                           class="btn btn-edit-stylist">
                            <i class="bi bi-pencil-square me-2"></i> Edit
                        </a>
                        <button type="button" class="btn btn-delete-stylist"
                                data-bs-toggle="modal" data-bs-target="#deleteStylistModal"
                                data-id="{{ $stylist['id'] }}"
                                data-name="{{ $stylist['name'] }}">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-people" style="font-size:48px; color:#d4c4d0;"></i>
                    <p class="mt-3" style="color:#8a7a88;">No team members yet.</p>
                    <a href="{{ route('owner.stylists.create') }}" class="btn btn-add-stylist">
                        <i class="bi bi-plus-lg me-2"></i> Add Your First Team Member
                    </a>
                </div>
            </div>
        @endforelse
    </div>

@endsection

@push('modals')
    <div class="modal fade" id="deleteStylistModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <form action="{{ route('owner.stylists.destroy', ['stylist' => 0]) }}" method="POST" id="deleteStylistForm">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body text-center py-4">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size:42px; color:#E14D6A;"></i>
                        <h5 class="mt-3" style="color:#5C2142; font-weight:700;">Remove Team Member?</h5>
                        <p class="mb-0" style="color:#6B4F62;">
                            Are you sure you want to remove "<span id="deleteStylistName" class="fw-semibold"></span>" from your team? This action cannot be undone.
                        </p>
                    </div>
                    <div class="modal-footer modal-footer-custom justify-content-center">
                        <button type="button" class="btn btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-delete-confirm">Remove</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@section('extra-css')
<style>
    .page-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d1f2c;
        margin-bottom: 0.25rem;
    }
    .page-header p {
        color: #8a7a88;
        margin-bottom: 0;
    }

    .btn-add-stylist {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 14.5px;
        padding: 11px 22px;
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.35);
        transition: all 0.18s ease;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
        text-decoration: none;
    }
    .btn-add-stylist:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(232, 85, 136, 0.45);
        color: #ffffff !important;
    }

    .stylist-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #f0e8ed;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        padding: 26px 22px 22px;
        height: 100%;
        text-align: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stylist-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 24px rgba(0,0,0,0.08);
    }

    .stylist-top-link { text-decoration: none; display: block; color: inherit; }

    .stylist-avatar {
        width: 84px;
        height: 84px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FF6B9D, #E85588);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        color: #fff;
        margin-bottom: 14px;
        overflow: hidden;
    }
    .stylist-avatar img { width: 100%; height: 100%; object-fit: cover; }

    .stylist-name {
        font-size: 17px;
        font-weight: 700;
        color: #2d1f2c;
        margin: 0 0 2px;
    }
    .stylist-role {
        font-size: 13.5px;
        color: #8a7a88;
        margin: 0 0 8px;
    }
    .stylist-rating {
        font-size: 14px;
        font-weight: 700;
        color: #D9A441;
        margin-bottom: 18px;
    }
    .stylist-rating i { color: #D9A441; margin-right: 3px; }

    .stylist-meta-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 4px;
        border-top: 1px solid #f5eef2;
        border-bottom: 1px solid #f5eef2;
        margin-bottom: 16px;
    }
    .meta-col { display: flex; flex-direction: column; gap: 2px; }
    .meta-label { font-size: 12.5px; color: #8a7a88; }
    .meta-value { font-size: 16px; font-weight: 700; color: #2d1f2c; }
    .meta-value.amount-gold { color: #D9A441; }

    .stylist-actions {
        display: flex;
        gap: 10px;
    }

    .btn-edit-stylist {
        flex: 1;
        background: #fcf6f9;
        border: 1px solid #f0e8ed;
        color: #2d1f2c;
        font-weight: 600;
        font-size: 14px;
        padding: 9px 14px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
        text-decoration: none;
    }
    .btn-edit-stylist:hover {
        background: #f0e8ed;
        color: #2d1f2c;
    }

    .btn-delete-stylist {
        background: #fff;
        border: 1.5px solid #FF6B9D;
        color: #E85588;
        font-size: 16px;
        width: 44px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
    }
    .btn-delete-stylist:hover {
        background: #E85588;
        color: #fff;
        border-color: #E85588;
    }

    .modal-content-custom { border-radius: 16px; border: none; overflow: hidden; }
    .modal-body { padding: 22px 24px; }
    .modal-footer-custom { border-top: 1px solid #f5eef2; padding: 16px 24px; }

    .btn-cancel-modal {
        background: #fff;
        border: 1.5px solid #FF6B9D;
        color: #E85588;
        font-weight: 600;
        padding: 9px 20px;
        border-radius: 10px;
        transition: all 0.15s ease;
    }
    .btn-cancel-modal:hover {
        background: #E85588;
        color: #ffffff !important;
        border-color: #E85588;
    }

    .btn-delete-confirm {
        background: linear-gradient(135deg, #F0708C, #E85588);
        color: #fff;
        font-weight: 700;
        padding: 9px 24px;
        border-radius: 10px;
        border: none;
        transition: all 0.15s ease;
    }
    .btn-delete-confirm:hover {
        color: #fff;
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.4);
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch !important;
        }
        .btn-add-stylist {
            justify-content: center;
            width: 100%;
        }
    }
</style>
@endsection

@section('extra-js')
<script>
    document.querySelectorAll('.btn-delete-stylist').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('deleteStylistName').textContent = this.dataset.name;
            const form = document.getElementById('deleteStylistForm');
            form.action = form.action.replace(/stylists\/\d+$/, 'stylists/' + this.dataset.id);
        });
    });
</script>
@endsection