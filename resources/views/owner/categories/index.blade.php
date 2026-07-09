{{--
    CATEGORIES INDEX PAGE (FIXED)
    resources/views/owner/categories/index.blade.php
--}}
@extends('layouts.owner')
@section('title', 'Categories')

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>Categories</h2>
            <p>Organize your services into categories</p>
        </div>
        <a href="{{ route('owner.categories.create') }}" class="btn btn-add-category">
            <i class="bi bi-plus-lg me-2"></i> Add Category
        </a>
    </div>

    @if(count($categories) > 0)
        <div class="row g-4">
            @foreach ($categories as $category)
                <div class="col-md-6 col-lg-4">
                    <div class="category-card">

                        <a href="{{ route('owner.categories.show', ['category' => $category['id']]) }}"
                           class="category-top-link">
                            <div class="category-top">

                                {{-- ✅ ALAG ICON + COLOR HAR CATEGORY KO --}}
                                <div class="category-icon {{ $category['icon_bg'] }}">
                                    <i class="bi bi-{{ $category['icon'] }}"></i>
                                </div>

                                <div class="flex-grow-1 min-w-0">
                                    <h5 class="category-name">{{ $category['name'] }}</h5>
                                    <p class="category-count">
                                        <i class="bi bi-grid-3x3-gap-fill me-1"></i>
                                        {{ $category['count'] }} {{ Str::plural('Service', $category['count']) }}
                                    </p>
                                </div>

                                {{-- Status badge --}}
                                <span class="cat-status-badge {{ $category['status'] === 'Active' ? 'cat-active' : 'cat-inactive' }}">
                                    {{ $category['status'] }}
                                </span>

                            </div>

                            {{-- Description agar ho --}}
                            @if(!empty($category['description']))
                                <p class="category-desc">{{ Str::limit($category['description'], 70) }}</p>
                            @endif
                        </a>

                        <div class="category-actions">
                            <a href="{{ route('owner.categories.edit', ['category' => $category['id']]) }}"
                               class="btn btn-edit-category">
                                <i class="bi bi-pencil-square me-2"></i> Edit
                            </a>
                            <a href="{{ route('owner.categories.show', ['category' => $category['id']]) }}"
                               class="btn btn-view-category">
                                <i class="bi bi-eye"></i>
                            </a>
                            <button type="button" class="btn btn-delete-category"
                                    data-bs-toggle="modal" data-bs-target="#deleteCategoryModal"
                                    data-id="{{ $category['id'] }}"
                                    data-name="{{ $category['name'] }}">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <div class="empty-categories">
            <div class="empty-icon">
                <i class="bi bi-grid-fill"></i>
            </div>
            <h5>No categories yet</h5>
            <p>Create your first category to organize your services.</p>
            <a href="{{ route('owner.categories.create') }}" class="btn btn-add-category">
                <i class="bi bi-plus-lg me-2"></i> Add First Category
            </a>
        </div>
    @endif

@endsection

@push('modals')
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px; border:none; overflow:hidden;">
                <form action="{{ route('owner.categories.destroy', ['category' => 0]) }}"
                      method="POST" id="deleteCategoryForm">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body text-center py-4">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size:42px; color:#E14D6A;"></i>
                        <h5 class="mt-3" style="color:#5C2142; font-weight:700;">Delete Category?</h5>
                        <p class="mb-0" style="color:#6B4F62;">
                            Are you sure you want to delete
                            "<span id="deleteCategoryName" class="fw-semibold"></span>"?
                            Services under this category will not be deleted.
                        </p>
                    </div>
                    <div class="modal-footer justify-content-center"
                         style="border-top:1px solid #f5eef2; padding:16px 24px;">
                        <button type="button" class="btn btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-delete-confirm">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@section('extra-css')
<style>
    .page-header h2 { font-size:1.5rem; font-weight:700; color:#2d1f2c; margin-bottom:0.25rem; }
    .page-header p  { color:#8a7a88; margin-bottom:0; }

    .btn-add-category {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #fff !important; font-weight:600; font-size:14.5px;
        padding:11px 22px; border-radius:10px; border:none;
        box-shadow:0 4px 14px rgba(232,85,136,0.35); transition:all 0.18s;
        display:inline-flex; align-items:center; white-space:nowrap; text-decoration:none;
    }
    .btn-add-category:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(232,85,136,0.45); color:#fff !important; }

    /* ── Category Card ── */
    .category-card {
        background:#fff; border-radius:16px; border:1px solid #f0e8ed;
        box-shadow:0 2px 12px rgba(0,0,0,0.05); padding:22px; height:100%;
        transition:transform 0.2s, box-shadow 0.2s;
        display:flex; flex-direction:column; gap:16px;
    }
    .category-card:hover { transform:translateY(-3px); box-shadow:0 6px 24px rgba(232,85,136,0.10); }

    .category-top-link { text-decoration:none; display:block; }

    .category-top {
        display:flex; align-items:center; gap:14px; margin-bottom:8px;
    }
    .min-w-0 { min-width:0; }

    /* ✅ Icon — har category alag color + icon */
    .category-icon {
        width:52px; height:52px; border-radius:14px; flex-shrink:0;
        display:flex; align-items:center; justify-content:center;
        font-size:22px; color:#fff;
        box-shadow:0 4px 12px rgba(0,0,0,0.12);
    }

    .cat-gold   { background:linear-gradient(135deg, #D9A441, #C4903A); }
    .cat-purple { background:linear-gradient(135deg, #9B6FD1, #7E4FB5); }
    .cat-green  { background:linear-gradient(135deg, #38C495, #2EAE7D); }
    .cat-blue   { background:linear-gradient(135deg, #6398F2, #4A7FE0); }
    .cat-orange { background:linear-gradient(135deg, #F2A23D, #E08A2C); }
    .cat-pink   { background:linear-gradient(135deg, #FF6B9D, #E85588); }
    .cat-teal   { background:linear-gradient(135deg, #3DC9B0, #21A085); }

    .category-name  { font-size:16px; font-weight:700; color:#2d1f2c; margin:0 0 3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .category-count { font-size:13px; color:#9E7B95; margin:0; }
    .category-desc  { font-size:13px; color:#9E7B95; margin:4px 0 0; line-height:1.5; }

    .cat-status-badge {
        font-size:11px; font-weight:700; padding:3px 10px;
        border-radius:20px; flex-shrink:0; white-space:nowrap;
    }
    .cat-active   { background:#E8F5E9; color:#2EAE7D; }
    .cat-inactive { background:#FCE4EC; color:#E14D6A; }

    /* ── Actions ── */
    .category-actions { display:flex; gap:8px; }

    .btn-edit-category {
        flex:1; background:#fcf6f9; border:1px solid #f0e8ed; color:#2d1f2c;
        font-weight:600; font-size:13.5px; padding:9px 14px; border-radius:8px;
        display:inline-flex; align-items:center; justify-content:center;
        transition:all 0.15s; text-decoration:none;
    }
    .btn-edit-category:hover { background:#FFE0EE; color:#E85588; border-color:#F0C0D8; }

    .btn-view-category {
        background:#E8F0FD; border:none; color:#4A7FE0; font-size:16px;
        width:40px; border-radius:8px; display:inline-flex; align-items:center;
        justify-content:center; transition:all 0.15s; text-decoration:none;
    }
    .btn-view-category:hover { background:#4A7FE0; color:#fff; }

    .btn-delete-category {
        background:#fff; border:1.5px solid #FF6B9D; color:#E85588; font-size:16px;
        width:40px; border-radius:8px; display:inline-flex; align-items:center;
        justify-content:center; transition:all 0.15s;
    }
    .btn-delete-category:hover { background:#E85588; color:#fff; border-color:#E85588; }

    /* ── Empty State ── */
    .empty-categories {
        text-align:center; padding:80px 20px; background:#fff;
        border-radius:16px; border:2px dashed #F0C0D8;
    }
    .empty-icon {
        width:90px; height:90px; border-radius:50%;
        background:#FDE0EC; color:#E85588; font-size:38px;
        display:flex; align-items:center; justify-content:center;
        margin:0 auto 20px;
    }
    .empty-categories h5 { color:#2d1f2c; font-weight:700; font-size:18px; margin-bottom:8px; }
    .empty-categories p  { color:#9E7B95; margin-bottom:20px; }

    /* ── Alerts ── */
    .alert { border-radius:12px; border:none; padding:12px 18px; margin-bottom:20px; }
    .alert-success { background:#E8F5E9; color:#1B5E20; }
    .alert-danger  { background:#FCE4EC; color:#880E4F; }

    .btn-cancel-modal {
        background:#fff; border:1px solid #f0e8ed; color:#6b4f62;
        font-weight:600; padding:9px 20px; border-radius:10px; transition:all 0.15s;
    }
    .btn-cancel-modal:hover { background:#fcf6f9; }
    .btn-delete-confirm {
        background:linear-gradient(135deg, #F0708C, #E85588);
        color:#fff; font-weight:700; padding:9px 24px; border-radius:10px; border:none;
    }
    .btn-delete-confirm:hover { color:#fff; box-shadow:0 4px 14px rgba(232,85,136,0.4); }
</style>
@endsection

@section('extra-js')
<script>
    document.querySelectorAll('.btn-delete-category').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('deleteCategoryName').textContent = this.dataset.name;
            const form = document.getElementById('deleteCategoryForm');
            form.action = form.action.replace(/categories\/\d+$/, 'categories/' + this.dataset.id);
        });
    });

    // Auto dismiss alerts
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.classList.remove('show');
            setTimeout(() => alert.style.display = 'none', 300);
        }, 5000);
    });
</script>
@endsection