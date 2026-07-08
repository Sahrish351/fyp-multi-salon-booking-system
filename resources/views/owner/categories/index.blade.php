@extends('layouts.owner')

@section('title', 'Categories')

@section('content')

    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>Categories</h2>
            <p>Organize your services into categories</p>
        </div>
        <a href="{{ route('owner.categories.create') }}" class="btn btn-add-category">
            <i class="bi bi-plus-lg me-2"></i> Add Category
        </a>
    </div>

    <div class="row g-4">
        @foreach ($categories as $category)
            <div class="col-md-6 col-lg-4">
                <div class="category-card">
                    <a href="{{ route('owner.categories.show', ['category' => $category['id']]) }}" class="category-top-link">
                        <div class="category-top">
                            {{-- ✅ FIXED ICON CODE --}}
                            <div class="category-icon {{ $category['icon_bg'] }}">
                                <i class="bi bi-{{ $category['icon'] ?? 'folder' }}"></i>
                            </div>
                            <div>
                                <h5 class="category-name">{{ $category['name'] }}</h5>
                                <p class="category-count">{{ $category['count'] }} Services</p>
                            </div>
                        </div>
                    </a>

                    <div class="category-actions">
                        <a href="{{ route('owner.categories.edit', ['category' => $category['id']]) }}"
                           class="btn btn-edit-category">
                            <i class="bi bi-pencil-square me-2"></i> Edit
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

@endsection

@push('modals')
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <form action="{{ route('owner.categories.destroy', ['category' => 0]) }}" method="POST" id="deleteCategoryForm">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body text-center py-4">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size:42px; color:#E14D6A;"></i>
                        <h5 class="mt-3" style="color:#5C2142; font-weight:700;">Delete Category?</h5>
                        <p class="mb-0" style="color:#6B4F62;">
                            Are you sure you want to delete "<span id="deleteCategoryName" class="fw-semibold"></span>"? Services under this category will not be deleted.
                        </p>
                    </div>
                    <div class="modal-footer modal-footer-custom justify-content-center">
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

    .btn-add-category {
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
    .btn-add-category:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(232, 85, 136, 0.45);
        color: #ffffff !important;
    }

    .category-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #f0e8ed;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        padding: 22px;
        height: 100%;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .category-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 24px rgba(0,0,0,0.08);
    }

    .category-top-link {
        text-decoration: none;
        display: block;
    }

    .category-top {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 20px;
    }

    .category-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: #fff;
        flex-shrink: 0;
    }

    .cat-gold   { background: linear-gradient(135deg, #D9A441, #C4903A); }
    .cat-purple { background: linear-gradient(135deg, #9B6FD1, #7E4FB5); }
    .cat-green  { background: linear-gradient(135deg, #38C495, #2EAE7D); }
    .cat-blue   { background: linear-gradient(135deg, #6398F2, #4A7FE0); }
    .cat-orange { background: linear-gradient(135deg, #F2A23D, #E08A2C); }
    .cat-pink   { background: linear-gradient(135deg, #FF6B9D, #E85588); }
    .cat-teal   { background: linear-gradient(135deg, #3DC9B0, #21A085); }

    .category-name {
        font-size: 17px;
        font-weight: 700;
        color: #2d1f2c;
        margin: 0 0 2px;
    }

    .category-count {
        font-size: 13.5px;
        color: #8a7a88;
        margin: 0;
    }

    .category-actions {
        display: flex;
        gap: 10px;
    }

    .btn-edit-category {
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
    .btn-edit-category:hover {
        background: #f0e8ed;
        color: #2d1f2c;
    }

    .btn-delete-category {
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
    .btn-delete-category:hover {
        background: #E85588;
        color: #fff;
        border-color: #E85588;
    }

    .modal-content-custom {
        border-radius: 16px;
        border: none;
        overflow: hidden;
    }
    .modal-body {
        padding: 22px 24px;
    }
    .modal-footer-custom {
        border-top: 1px solid #f5eef2;
        padding: 16px 24px;
    }

    .btn-cancel-modal {
        background: #fff;
        border: 1px solid #f0e8ed;
        color: #6b4f62;
        font-weight: 600;
        padding: 9px 20px;
        border-radius: 10px;
        transition: all 0.15s ease;
    }
    .btn-cancel-modal:hover {
        background: #fcf6f9;
        color: #2d1f2c;
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
        .btn-add-category {
            justify-content: center;
            width: 100%;
        }
        .category-actions {
            flex-wrap: wrap;
        }
        .btn-edit-category {
            flex: 1;
            min-width: 100px;
        }
    }
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
</script>
@endsection