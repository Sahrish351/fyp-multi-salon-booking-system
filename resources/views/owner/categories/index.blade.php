
@extends('layouts.owner')

@section('title', 'Categories')

@section('content')

    {{-- Page Header --}}
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>Categories</h2>
            <p>Organize your services into categories</p>
        </div>
        <a href="{{ route('owner.categories.create') }}" class="btn btn-add-category">
            <i class="bi bi-plus-lg me-2"></i> Add Category
        </a>
    </div>

    {{-- ===================== CATEGORY CARDS GRID ===================== --}}
    <div class="row g-4">

        @foreach ($categories as $category)
            <div class="col-md-6 col-lg-4">
                <div class="category-card">
                    <a href="{{ route('owner.categories.show', ['category' => $category['id']]) }}" class="category-top-link">
                        <div class="category-top">
                            <div class="category-icon {{ $category['icon_bg'] }}">
                                <i class="bi bi-diagram-3-fill"></i>
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
    .btn-add-category {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        color: var(--plum-900); font-weight: 700; font-size: 14.5px;
        padding: 11px 22px; border-radius: 10px; border: none;
        box-shadow: 0 4px 14px rgba(217, 164, 65, 0.35); transition: all 0.18s ease;
        display: inline-flex; align-items: center; white-space: nowrap;
    }
    .btn-add-category:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(217, 164, 65, 0.5); color: var(--plum-900); }

    .category-card {
        background: var(--white);
        border-radius: var(--radius-lg);
        border: 1px solid var(--blush-200);
        box-shadow: var(--shadow-card);
        padding: 22px;
        height: 100%;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .category-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-card-hover);
    }

    .category-top-link { text-decoration: none; display: block; }

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
        font-size: 20px;
        color: #fff;
        flex-shrink: 0;
    }

    .cat-gold   { background: linear-gradient(135deg, var(--gold-500), var(--gold-600)); }
    .cat-purple { background: linear-gradient(135deg, var(--purple-500), #7E4FB5); }
    .cat-green  { background: linear-gradient(135deg, #38C495, var(--green-500)); }
    .cat-blue   { background: linear-gradient(135deg, #6398F2, var(--blue-500)); }
    .cat-orange { background: linear-gradient(135deg, #F2A23D, var(--orange-500)); }
    .cat-pink   { background: linear-gradient(135deg, var(--rose-400), var(--rose-600)); }
    .cat-teal   { background: linear-gradient(135deg, #3DC9B0, #21A085); }

    .category-name {
        font-size: 17px;
        font-weight: 700;
        color: var(--plum-800);
        margin: 0 0 2px;
    }

    .category-count {
        font-size: 13.5px;
        color: var(--ink-700);
        margin: 0;
    }

    .category-actions {
        display: flex;
        gap: 10px;
    }

    .btn-edit-category {
        flex: 1;
        background: var(--blush-50);
        border: 1px solid var(--blush-200);
        color: var(--plum-800);
        font-weight: 600;
        font-size: 14px;
        padding: 9px 14px;
        border-radius: var(--radius-sm);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
    }
    .btn-edit-category:hover {
        background: var(--blush-100);
        color: var(--plum-900);
    }

    .btn-delete-category {
        background: var(--red-50);
        border: 1px solid #FBD0D9;
        color: var(--red-500);
        font-size: 16px;
        width: 44px;
        border-radius: var(--radius-sm);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
    }
    .btn-delete-category:hover {
        background: var(--red-500);
        color: #fff;
        border-color: var(--red-500);
    }

    .modal-content-custom { border-radius: var(--radius-lg); border: none; overflow: hidden; }
    .modal-body { padding: 22px 24px; }
    .modal-footer-custom { border-top: 1px solid var(--blush-100); padding: 16px 24px; }

    .btn-cancel-modal {
        background: var(--white); border: 1px solid var(--blush-200); color: var(--ink-700);
        font-weight: 600; padding: 9px 20px; border-radius: 10px;
    }
    .btn-cancel-modal:hover { background: var(--blush-50); }

    .btn-delete-confirm {
        background: linear-gradient(135deg, #F0708C, var(--red-500));
        color: #fff; font-weight: 700; padding: 9px 24px; border-radius: 10px; border: none;
    }
    .btn-delete-confirm:hover { color: #fff; box-shadow: 0 4px 14px rgba(225, 77, 106, 0.4); }
</style>
@endsection

@section('extra-js')
<script>
    // Delete modal: populate name + form action
    document.querySelectorAll('.btn-delete-category').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('deleteCategoryName').textContent = this.dataset.name;

            const form = document.getElementById('deleteCategoryForm');
            form.action = form.action.replace(/categories\/\d+$/, 'categories/' + this.dataset.id);
        });
    });
</script>
@endsection
