{{--
    ===========================================================
    CATEGORY CREATE PAGE (resources/views/owner/categories/create.blade.php)
    Route: GET /owner/categories/create --> owner.categories.create
    ===========================================================
--}}
@extends('layouts.owner')

@section('title', 'Add New Category')

@section('content')

    {{-- Page Header --}}
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>Add New Category</h2>
            <p>Create a new category to organize your services</p>
        </div>
        <a href="{{ route('owner.categories.index') }}" class="btn btn-back">
            <i class="bi bi-arrow-left me-2"></i> Back to Categories
        </a>
    </div>

    <form action="{{ route('owner.categories.store') }}" method="POST">
        @csrf

        <div class="row g-4">

            {{-- ===================== LEFT: ICON PREVIEW ===================== --}}
            <div class="col-lg-4">
                <div class="panel-card text-center">
                    <div class="category-icon-preview cat-gold mx-auto" id="iconPreview">
                        <i class="bi bi-diagram-3-fill"></i>
                    </div>
                    <p class="preview-hint">Live preview</p>

                    <hr class="my-4">

                    <div class="text-start">
                        <label class="form-label-custom">Icon Color</label>
                        <div class="color-picker-row">
                            <input type="radio" name="icon_color" id="colorGold" value="cat-gold" class="color-radio" checked>
                            <label for="colorGold" class="color-swatch cat-gold"></label>

                            <input type="radio" name="icon_color" id="colorPurple" value="cat-purple" class="color-radio">
                            <label for="colorPurple" class="color-swatch cat-purple"></label>

                            <input type="radio" name="icon_color" id="colorGreen" value="cat-green" class="color-radio">
                            <label for="colorGreen" class="color-swatch cat-green"></label>

                            <input type="radio" name="icon_color" id="colorBlue" value="cat-blue" class="color-radio">
                            <label for="colorBlue" class="color-swatch cat-blue"></label>

                            <input type="radio" name="icon_color" id="colorOrange" value="cat-orange" class="color-radio">
                            <label for="colorOrange" class="color-swatch cat-orange"></label>

                            <input type="radio" name="icon_color" id="colorPink" value="cat-pink" class="color-radio">
                            <label for="colorPink" class="color-swatch cat-pink"></label>

                            <input type="radio" name="icon_color" id="colorTeal" value="cat-teal" class="color-radio">
                            <label for="colorTeal" class="color-swatch cat-teal"></label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===================== RIGHT: CATEGORY DETAILS FORM ===================== --}}
            <div class="col-lg-8">
                <div class="panel-card">
                    <div class="panel-title">Category Details</div>

                    <div class="row g-3">

                        <div class="col-12">
                            <label class="form-label-custom">Category Name</label>
                            <input type="text" name="name" id="categoryNameInput" class="form-control input-custom"
                                   placeholder="e.g. Hair Styling" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label-custom">Description <span class="text-muted">(optional)</span></label>
                            <textarea name="description" class="form-control input-custom" rows="4"
                                      placeholder="Briefly describe what kind of services belong in this category..."></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label-custom">Status</label>
                            <select name="status" class="form-select input-custom">
                                <option value="Active" selected>Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>

                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-save-changes">
                            <i class="bi bi-check-circle-fill me-2"></i> Create Category
                        </button>
                        <a href="{{ route('owner.categories.index') }}" class="btn btn-cancel-modal">Cancel</a>
                    </div>

                </div>
            </div>

        </div>

    </form>

@endsection

@section('extra-css')
<style>
    .btn-back {
        background: var(--white); border: 1px solid var(--blush-200); color: var(--plum-800);
        font-weight: 600; font-size: 14.5px; padding: 10px 20px; border-radius: 10px;
        display: inline-flex; align-items: center; transition: all 0.18s ease;
    }
    .btn-back:hover { background: var(--blush-50); color: var(--plum-900); }

    .category-icon-preview {
        width: 90px;
        height: 90px;
        border-radius: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        color: #fff;
        transition: background 0.2s ease;
    }
    .cat-gold   { background: linear-gradient(135deg, var(--gold-500), var(--gold-600)); }
    .cat-purple { background: linear-gradient(135deg, var(--purple-500), #7E4FB5); }
    .cat-green  { background: linear-gradient(135deg, #38C495, var(--green-500)); }
    .cat-blue   { background: linear-gradient(135deg, #6398F2, var(--blue-500)); }
    .cat-orange { background: linear-gradient(135deg, #F2A23D, var(--orange-500)); }
    .cat-pink   { background: linear-gradient(135deg, var(--rose-400), var(--rose-600)); }
    .cat-teal   { background: linear-gradient(135deg, #3DC9B0, #21A085); }

    .preview-hint { font-size: 12px; color: var(--ink-500); margin-top: 10px; margin-bottom: 0; }

    .color-picker-row { display: flex; gap: 10px; margin-top: 6px; flex-wrap: wrap; }
    .color-radio { display: none; }
    .color-swatch {
        width: 34px; height: 34px; border-radius: 50%; cursor: pointer;
        display: inline-block; border: 3px solid transparent; transition: all 0.15s ease;
    }
    .color-radio:checked + .color-swatch { border-color: var(--plum-800); transform: scale(1.1); }

    .form-label-custom { display: block; font-size: 13.5px; font-weight: 600; color: var(--ink-700); margin-bottom: 6px; }
    .input-custom {
        background: var(--blush-50) !important; border: 1px solid var(--blush-200) !important;
        border-radius: var(--radius-sm) !important; color: var(--ink-900) !important;
        font-size: 14.5px; padding: 11px 14px !important;
    }
    .input-custom:focus { background: #fff !important; border-color: var(--rose-400) !important; box-shadow: 0 0 0 3px rgba(240, 143, 180, 0.2) !important; outline: none; }

    .btn-save-changes {
        background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
        color: var(--plum-900); font-weight: 700; padding: 11px 26px; border-radius: 10px; border: none;
        display: inline-flex; align-items: center;
    }
    .btn-save-changes:hover { color: var(--plum-900); transform: translateY(-1px); box-shadow: 0 6px 16px rgba(217, 164, 65, 0.4); }

    .btn-cancel-modal {
        background: var(--white); border: 1px solid var(--blush-200); color: var(--ink-700);
        font-weight: 600; padding: 11px 26px; border-radius: 10px; display: inline-flex; align-items: center;
    }
    .btn-cancel-modal:hover { background: var(--blush-50); color: var(--ink-900); }
</style>
@endsection

@section('extra-js')
<script>
    // Live icon color preview
    const colorRadios = document.querySelectorAll('.color-radio');
    const iconPreview = document.getElementById('iconPreview');

    colorRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            iconPreview.className = 'category-icon-preview mx-auto ' + this.value;
        });
    });
</script>
@endsection
