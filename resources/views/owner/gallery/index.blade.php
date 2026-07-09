@extends('layouts.owner')
 
@section('title', 'Gallery')
 
@section('content')
 
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
 
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
 
    {{-- Page Header --}}
    <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2>Gallery</h2>
            <p>Showcase your salon's work</p>
        </div>
        <button type="button" class="btn btn-upload-photo" data-bs-toggle="modal" data-bs-target="#uploadPhotoModal">
            <i class="bi bi-plus-lg me-2"></i> Upload Photo
        </button>
    </div>
 
    {{-- STAT CARDS --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card-sm">
                <div class="stat-icon icon-purple"><i class="bi bi-images"></i></div>
                <div>
                    <div class="stat-label-sm">Total Photos</div>
                    <div class="stat-value-sm">{{ $stats['total'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-sm">
                <div class="stat-icon icon-gold"><i class="bi bi-eye-fill"></i></div>
                <div>
                    <div class="stat-label-sm">Total Views</div>
                    <div class="stat-value-sm">{{ number_format($stats['total_views'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-sm">
                <div class="stat-icon icon-green"><i class="bi bi-grid-3x3-gap-fill"></i></div>
                <div>
                    <div class="stat-label-sm">Categories</div>
                    <div class="stat-value-sm">{{ $stats['categories'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
 
    {{-- CATEGORY FILTER TABS --}}
    <div class="filter-tabs-row mb-4">
        @php $cats = ['All', 'Hair', 'Nails', 'Facial', 'Spa', 'Makeup']; @endphp
        @foreach ($cats as $cat)
            <button type="button"
                    class="filter-tab {{ $cat === 'All' ? 'active' : '' }}"
                    data-cat="{{ strtolower($cat) }}">
                {{ $cat }}
            </button>
        @endforeach
    </div>
 
    {{-- GALLERY GRID --}}
    @if (count($photos) > 0)
        <div class="gallery-grid" id="galleryGrid">
            @foreach ($photos as $photo)
                <div class="gallery-item" data-id="{{ $photo['id'] }}" data-cat="{{ $photo['category'] }}">
 
                    <div class="gallery-img-wrapper">
                        @if (!empty($photo['url']))
                            <img src="{{ $photo['url'] }}" alt="{{ $photo['caption'] ?? 'Gallery photo' }}" loading="lazy">
                        @else
                            <div class="gallery-placeholder">
                                <i class="bi bi-card-image"></i>
                            </div>
                        @endif
 
                        {{-- Hover overlay --}}
                        <div class="gallery-overlay">
                            <button type="button" class="gallery-action-btn edit-caption-btn"
                                    data-bs-toggle="modal" data-bs-target="#editCaptionModal"
                                    data-id="{{ $photo['id'] }}"
                                    data-caption="{{ $photo['caption'] ?? '' }}"
                                    data-category="{{ $photo['category'] }}"
                                    title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button type="button" class="gallery-action-btn delete-photo-btn"
                                    data-bs-toggle="modal" data-bs-target="#deletePhotoModal"
                                    data-id="{{ $photo['id'] }}"
                                    data-caption="{{ $photo['caption'] ?? 'this photo' }}"
                                    title="Delete">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>
                    </div>
 
                    @if (!empty($photo['caption']))
                        <p class="gallery-caption">{{ $photo['caption'] }}</p>
                    @endif
 
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty state --}}
        <div class="gallery-empty">
            <i class="bi bi-images"></i>
            <h5>No photos yet</h5>
            <p>Upload your first photo to showcase your salon's work</p>
            <button type="button" class="btn btn-upload-photo" data-bs-toggle="modal" data-bs-target="#uploadPhotoModal">
                <i class="bi bi-cloud-upload-fill me-2"></i> Upload Your First Photo
            </button>
        </div>
    @endif
 
@endsection
 
@push('modals')
 
    {{-- UPLOAD PHOTO MODAL --}}
    <div class="modal fade" id="uploadPhotoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <form action="{{ route('owner.gallery.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title">Upload Photo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
 
                        {{-- Drop zone --}}
                        <label for="photoUploadInput" class="upload-dropzone" id="uploadDropzone">
                            <div class="dropzone-content" id="dropzoneContent">
                                <i class="bi bi-cloud-upload-fill"></i>
                                <p>Click to upload or drag &amp; drop</p>
                                <span>JPG, PNG, WebP — max 5MB</span>
                            </div>
                            <img id="uploadPreviewImg" src="" alt="" style="display:none; width:100%; height:100%; object-fit:cover; border-radius:12px; position:absolute; inset:0;">
                        </label>
                        <input type="file" id="photoUploadInput" name="image" accept="image/*" hidden required>
 
                        <div class="mt-3">
                            <label class="form-label-custom">Caption <span class="text-muted">(optional)</span></label>
                            <input type="text" name="caption" class="form-control input-custom"
                                   placeholder="e.g. Bridal hair transformation by Emma">
                        </div>
 
                        <div class="mt-3">
                            <label class="form-label-custom">Category</label>
                            <select name="category" class="form-select input-custom">
                                <option value="hair">Hair</option>
                                <option value="nails">Nails</option>
                                <option value="facial">Facial</option>
                                <option value="spa">Spa</option>
                                <option value="makeup">Makeup</option>
                            </select>
                        </div>
 
                    </div>
                    <div class="modal-footer modal-footer-custom">
                        <button type="button" class="btn btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-save-changes">
                            <i class="bi bi-cloud-upload-fill me-2"></i> Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
 
    {{-- EDIT CAPTION MODAL --}}
    <div class="modal fade" id="editCaptionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <form action="{{ route('owner.gallery.update', ['gallery' => 0]) }}" method="POST" id="editCaptionForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title">Edit Photo Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label-custom">Caption</label>
                            <input type="text" name="caption" id="editCaptionInput"
                                   class="form-control input-custom"
                                   placeholder="Add a caption for this photo">
                        </div>
                        <div>
                            <label class="form-label-custom">Category</label>
                            <select name="category" id="editCategorySelect" class="form-select input-custom">
                                <option value="hair">Hair</option>
                                <option value="nails">Nails</option>
                                <option value="facial">Facial</option>
                                <option value="spa">Spa</option>
                                <option value="makeup">Makeup</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer modal-footer-custom">
                        <button type="button" class="btn btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-save-changes">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
 
    {{-- DELETE PHOTO MODAL --}}
    <div class="modal fade" id="deletePhotoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <form action="{{ route('owner.gallery.destroy', ['gallery' => 0]) }}" method="POST" id="deletePhotoForm">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body text-center py-4">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size:42px; color:#E14D6A;"></i>
                        <h5 class="mt-3" style="color:#5C2142; font-weight:700;">Delete Photo?</h5>
                        <p class="mb-0" style="color:#6B4F62;">
                            Are you sure you want to delete
                            "<span id="deletePhotoCaption" class="fw-semibold"></span>"?
                            This action cannot be undone.
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

    .btn-upload-photo {
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
    .btn-upload-photo:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(232, 85, 136, 0.45);
        color: #ffffff !important;
    }
 
    .stat-card-sm {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #f0e8ed;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        height: 100%;
    }
    .stat-card-sm .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        font-size: 20px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
    }
    .icon-purple { background: linear-gradient(135deg, #9B6FD1, #7E56B0); }
    .icon-gold { background: linear-gradient(135deg, #D9A441, #C4903A); }
    .icon-green { background: linear-gradient(135deg, #2EAE7D, #1E8E64); }
    .stat-label-sm { font-size: 13.5px; color: #8a7a88; margin-bottom: 2px; }
    .stat-value-sm { font-size: 22px; font-weight: 700; color: #2d1f2c; }
 
    .filter-tabs-row {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        background: #fff;
        border: 1px solid #f0e8ed;
        border-radius: 14px;
        padding: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .filter-tab {
        padding: 8px 18px;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 600;
        color: #4a3a48;
        background: none;
        border: none;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .filter-tab:hover { background: #fcf6f9; color: #2d1f2c; }
    .filter-tab.active {
        background: linear-gradient(135deg, #FF6B9D, #E85588);
        color: #ffffff;
    }
 
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }
    @media (max-width: 1100px) { .gallery-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px)  { .gallery-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px)  { .gallery-grid { grid-template-columns: 1fr; } }
 
    .gallery-item { cursor: grab; }
    .gallery-item.sortable-ghost { opacity: 0.4; }
 
    .gallery-img-wrapper {
        position: relative;
        width: 100%;
        padding-top: 100%;
        border-radius: 14px;
        overflow: hidden;
        background: #fcf6f9;
        border: 1px solid #f0e8ed;
    }
    .gallery-img-wrapper img {
        position: absolute; inset: 0;
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .gallery-img-wrapper:hover img { transform: scale(1.05); }
 
    .gallery-placeholder {
        position: absolute; inset: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: 48px; color: rgba(255,255,255,0.15);
        background: #2A1535;
    }
 
    .gallery-overlay {
        position: absolute; inset: 0;
        background: rgba(64, 21, 48, 0.60);
        display: flex; align-items: center; justify-content: center; gap: 12px;
        opacity: 0; transition: opacity 0.2s ease;
        border-radius: 14px;
    }
    .gallery-img-wrapper:hover .gallery-overlay { opacity: 1; }
 
    .gallery-action-btn {
        width: 42px; height: 42px; border-radius: 50%; border: none;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px; cursor: pointer; transition: all 0.15s ease;
    }
    .edit-caption-btn {
        background: rgba(255,255,255,0.92);
        color: #2d1f2c;
    }
    .edit-caption-btn:hover {
        background: #fff;
        transform: scale(1.12);
    }
    .delete-photo-btn {
        background: rgba(225, 77, 106, 0.92);
        color: #fff;
    }
    .delete-photo-btn:hover {
        background: #D45482;
        transform: scale(1.12);
    }
 
    .gallery-caption {
        font-size: 12.5px;
        color: #4a3a48;
        margin: 6px 2px 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
 
    .gallery-empty {
        text-align: center;
        padding: 80px 20px;
        background: #fff;
        border-radius: 14px;
        border: 2px dashed #f0d8e0;
    }
    .gallery-empty > i {
        font-size: 56px;
        color: #f0d8e0;
        display: block;
        margin-bottom: 16px;
    }
    .gallery-empty h5 {
        color: #2d1f2c;
        font-weight: 700;
        margin-bottom: 8px;
    }
    .gallery-empty p {
        color: #8a7a88;
        margin-bottom: 20px;
    }
 
    .upload-dropzone {
        display: block;
        width: 100%;
        height: 180px;
        border: 2px dashed #f0d8e0;
        border-radius: 14px;
        background: #fcf6f9;
        cursor: pointer;
        transition: all 0.2s ease;
        overflow: hidden;
        position: relative;
    }
    .upload-dropzone:hover {
        border-color: #E85588;
        background: #fce8f0;
    }
    .dropzone-content {
        position: absolute; inset: 0;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center; gap: 6px;
    }
    .dropzone-content i {
        font-size: 32px;
        color: #E85588;
    }
    .dropzone-content p {
        font-size: 14px;
        font-weight: 600;
        color: #2d1f2c;
        margin: 0;
    }
    .dropzone-content span {
        font-size: 12px;
        color: #8a7a88;
    }
 
    .form-label-custom { display: block; font-size: 13.5px; font-weight: 600; color: #4a3a48; margin-bottom: 6px; }
    .input-custom {
        background: #fcf6f9 !important;
        border: 1px solid #f0e8ed !important;
        border-radius: 10px !important;
        color: #2d1f2c !important;
        font-size: 14.5px;
        padding: 11px 14px !important;
        width: 100%;
    }
    .input-custom:focus {
        background: #fff !important;
        border-color: #E85588 !important;
        box-shadow: 0 0 0 3px rgba(232, 85, 136, 0.15) !important;
        outline: none;
    }
 
    .modal-content-custom { border-radius: 16px; border: none; overflow: hidden; }
    .modal-header-custom {
        background: #fcf6f9;
        border-bottom: 1px solid #f5eef2;
        padding: 18px 24px;
    }
    .modal-header-custom .modal-title { font-weight: 700; color: #2d1f2c; }
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
 
    .btn-save-changes {
        background: linear-gradient(135deg, #FF6B9D, #E85588) !important;
        color: #ffffff !important;
        font-weight: 600;
        padding: 9px 22px;
        border-radius: 10px;
        border: none;
        transition: all 0.15s ease;
    }
    .btn-save-changes:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(232, 85, 136, 0.35);
        color: #ffffff !important;
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
 
    .alert {
        border-radius: 12px;
        border: none;
        padding: 0.8rem 1.2rem;
    }
    .alert-success { background: #E8F5ED; color: #1B5E20; }
    .alert-danger { background: #FCE4EC; color: #880E4F; }
 
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch !important;
        }
        .btn-upload-photo {
            justify-content: center;
            width: 100%;
        }
        .filter-tabs-row {
            flex-direction: column;
        }
        .filter-tab {
            text-align: center;
        }
        .gallery-grid {
            gap: 10px;
        }
    }
</style>
@endsection
 
@section('extra-js')
{{-- SortableJS CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js"></script>
<script>
    // ===================== Drag-to-Reorder =====================
    const grid = document.getElementById('galleryGrid');
    if (grid) {
        new Sortable(grid, {
            animation: 200,
            ghostClass: 'sortable-ghost',
            onEnd: function () {
                const items = [...grid.querySelectorAll('.gallery-item')];
                const order = items.map(el => el.dataset.id);
 
                fetch('{{ route("owner.gallery.reorder") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ order }),
                });
            }
        });
    }
 
    // ===================== Image Upload Preview =====================
    const photoInput     = document.getElementById('photoUploadInput');
    const previewImg     = document.getElementById('uploadPreviewImg');
    const dropzoneContent = document.getElementById('dropzoneContent');
 
    if (photoInput) {
        photoInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                    dropzoneContent.style.display = 'none';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
 
    // ===================== Edit Caption Modal =====================
    document.querySelectorAll('.edit-caption-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('editCaptionInput').value   = this.dataset.caption;
            document.getElementById('editCategorySelect').value = this.dataset.category;
 
            const form = document.getElementById('editCaptionForm');
            form.action = form.action.replace(/gallery\/\d+$/, 'gallery/' + this.dataset.id);
        });
    });
 
    // ===================== Delete Modal =====================
    document.querySelectorAll('.delete-photo-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('deletePhotoCaption').textContent = this.dataset.caption;
 
            const form = document.getElementById('deletePhotoForm');
            form.action = form.action.replace(/gallery\/\d+$/, 'gallery/' + this.dataset.id);
        });
    });
 
    // ===================== Category Filter =====================
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
 
            const selectedCat = this.dataset.cat;
            document.querySelectorAll('.gallery-item').forEach(item => {
                item.style.display = (selectedCat === 'all' || item.dataset.cat === selectedCat) ? '' : 'none';
            });
        });
    });
</script>
@endsection