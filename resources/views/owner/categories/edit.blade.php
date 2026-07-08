
@extends('layouts.owner')

@section('title', 'Edit Category')

@section('content')

 
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>Edit Category</h2>
            <p>Update details for "{{ $category['name'] }}"</p>
        </div>
        <a href="{{ route('owner.categories.index') }}" class="btn btn-back">
            <i class="bi bi-arrow-left me-2"></i> Back to Categories
        </a>
    </div>

    <form action="{{ route('owner.categories.update', ['category' => $category['id']]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">

           
            <div class="col-lg-4">
                <div class="panel-card text-center">
                    <div class="category-icon-preview {{ $category['icon_bg'] }} mx-auto" id="iconPreview">
                        <i class="bi bi-diagram-3-fill"></i>
                    </div>
                    <p class="preview-hint">Live preview</p>

                    <hr class="my-4">

                    <div class="text-start">
                        <label class="form-label-custom">Icon Color</label>
                        <div class="color-picker-row">
                            @foreach (['cat-gold', 'cat-purple', 'cat-green', 'cat-blue', 'cat-orange', 'cat-pink', 'cat-teal'] as $colorOption)
                                <input type="radio" name="icon_color" id="color-{{ $colorOption }}" value="{{ $colorOption }}"
                                       class="color-radio" {{ $category['icon_bg'] === $colorOption ? 'checked' : '' }}>
                                <label for="color-{{ $colorOption }}" class="color-swatch {{ $colorOption }}"></label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-lg-8">
                <div class="panel-card">
                    <div class="panel-title">Category Details</div>

                    <div class="row g-3">

                        <div class="col-12">
                            <label class="form-label-custom">Category Name</label>
                            <input type="text" name="name" id="categoryNameInput" class="form-control input-custom"
                                   value="{{ $category['name'] }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label-custom">Description <span class="text-muted">(optional)</span></label>
                            <textarea name="description" class="form-control input-custom" rows="4">{{ $category['description'] ?? '' }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label-custom">Status</label>
                            <select name="status" class="form-select input-custom">
                                <option value="Active" {{ ($category['status'] ?? 'Active') === 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ ($category['status'] ?? '') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-save-changes">
                            <i class="bi bi-check-circle-fill me-2"></i> Save Changes
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

    
    .btn-back {
        background: #fff;
        border: 1px solid #f0e8ed;
        color: #2d1f2c;
        font-weight: 600;
        font-size: 14.5px;
        padding: 10px 20px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        transition: all 0.18s ease;
        text-decoration: none;
    }
    .btn-back:hover {
        background: #fcf6f9;
        border-color: #E85588;
        color: #E85588;
    }

   
    .panel-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid rgba(0,0,0,0.04);
        transition: all 0.3s ease;
    }
    .panel-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    .panel-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2d1f2c;
        margin-bottom: 1.2rem;
    }

  
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
    .cat-gold   { background: linear-gradient(135deg, #D9A441, #C4903A); }
    .cat-purple { background: linear-gradient(135deg, #9B6FD1, #7E4FB5); }
    .cat-green  { background: linear-gradient(135deg, #38C495, #2EAE7D); }
    .cat-blue   { background: linear-gradient(135deg, #6398F2, #4A7FE0); }
    .cat-orange { background: linear-gradient(135deg, #F2A23D, #E08A2C); }
    .cat-pink   { background: linear-gradient(135deg, #FF6B9D, #E85588); }
    .cat-teal   { background: linear-gradient(135deg, #3DC9B0, #21A085); }

    .preview-hint {
        font-size: 12px;
        color: #8a7a88;
        margin-top: 10px;
        margin-bottom: 0;
    }

    .color-picker-row {
        display: flex;
        gap: 10px;
        margin-top: 6px;
        flex-wrap: wrap;
    }
    .color-radio {
        display: none;
    }
    .color-swatch {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        cursor: pointer;
        display: inline-block;
        border: 3px solid transparent;
        transition: all 0.15s ease;
    }
    .color-radio:checked + .color-swatch {
        border-color: #2d1f2c;
        transform: scale(1.1);
    }

  
    .form-label-custom {
        display: block;
        font-size: 13.5px;
        font-weight: 600;
        color: #4a3a48;
        margin-bottom: 6px;
    }
    .form-label-custom .text-muted {
        font-weight: 400;
        font-size: 12.5px;
        color: #8a7a88;
    }

    .input-custom {
        background: #fcf6f9 !important;
        border: 1px solid #f0e8ed !important;
        border-radius: 10px !important;
        color: #2d1f2c !important;
        font-size: 14.5px;
        padding: 11px 14px !important;
        transition: all 0.25s ease;
        width: 100%;
    }
    .input-custom:focus {
        background: #fff !important;
        border-color: #E85588 !important;
        box-shadow: 0 0 0 3px rgba(232, 85, 136, 0.15) !important;
        outline: none;
    }

   
    .btn-save-changes {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        padding: 11px 26px;
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.35);
        display: inline-flex;
        align-items: center;
        transition: all 0.18s ease;
        text-decoration: none;
    }
    .btn-save-changes:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(232, 85, 136, 0.45);
        color: #ffffff !important;
    }

    
    .btn-cancel-modal {
        background: #fff;
        border: 1.5px solid #FF6B9D;
        color: #E85588;
        font-weight: 600;
        padding: 11px 26px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        transition: all 0.18s ease;
        text-decoration: none;
    }
    .btn-cancel-modal:hover {
        background: #E85588;
        color: #ffffff !important;
        border-color: #E85588;
    }

   
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch !important;
        }
        .btn-back {
            justify-content: center;
            width: 100%;
        }
        .d-flex.gap-3 {
            flex-wrap: wrap;
        }
        .btn-save-changes,
        .btn-cancel-modal {
            flex: 1;
            justify-content: center;
        }
    }
</style>
@endsection

@section('extra-js')
<script>
    const colorRadios = document.querySelectorAll('.color-radio');
    const iconPreview = document.getElementById('iconPreview');

    colorRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            iconPreview.className = 'category-icon-preview mx-auto ' + this.value;
        });
    });
</script>
@endsection