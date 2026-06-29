@extends('layouts.admin')
@section('title', 'Edit Category - ' . $category->name)

@section('content')
<style>
:root {
    --rose:    #b3466b; --rose-lt: #fbeef2; --rose-h: #9c3759;
    --sage:    #5e9272; --sage-lt: #eef6f1;
    --slate:   #4f7c93; --slate-lt:#ecf4f7;
    --dust:    #8b6b4a; --dust-lt: #f8f1e8;
    --red:     #c1495a; --red-lt:  #fdf0f1;
    --mist:    #7c6a96; --mist-lt: #f3f0f8;
}
.btn-back {
    display:inline-flex; align-items:center; gap:.5rem;
    padding:.5rem 1.1rem; border:1.5px solid #e5e0d8; border-radius:9px;
    font-size:.86rem; font-weight:600; color:#8a8a8a; text-decoration:none;
    background:#fff; transition:all .15s; margin-bottom:1.75rem;
}
.btn-back:hover { border-color:var(--rose); color:var(--rose); }

.pg-title-row { display:flex; align-items:center; gap:.85rem; margin-bottom:1.75rem; }
.pg-title-icon {
    width:48px; height:48px; border-radius:12px;
    background:var(--rose-lt); color:var(--rose);
    display:flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0;
}
.pg-title-row h1 { font-size:1.45rem; font-weight:700; margin:0 0 .15rem; color:#2d2d2d; }
.pg-title-row p  { margin:0; font-size:.84rem; color:#9a9a9a; }

.form-layout { display:grid; grid-template-columns:1fr 330px; gap:1.4rem; align-items:start; }
@media(max-width:900px){ .form-layout { grid-template-columns:1fr; } }

.fcard { background:#fff; border:1px solid #ede9e4; border-radius:14px; overflow:hidden; margin-bottom:1.2rem; }
.fcard:last-child { margin-bottom:0; }
.fcard-head {
    padding:.9rem 1.4rem; border-bottom:1px solid #f5f2ee;
    display:flex; align-items:center; gap:.55rem;
}
.fcard-head i { color:var(--rose); font-size:.88rem; }
.fcard-title { font-weight:700; font-size:.9rem; color:#2d2d2d; }
.fcard-body { padding:1.4rem; }

.fg { margin-bottom:1.2rem; }
.fg:last-child { margin-bottom:0; }
.fl {
    display:block; font-size:.7rem; font-weight:700;
    text-transform:uppercase; letter-spacing:.055em; color:#9a9a9a; margin-bottom:.42rem;
}
.fl span { color:var(--rose); }
.fi {
    width:100%; padding:.68rem .95rem; border:1.5px solid #e8e3dc;
    border-radius:9px; font-size:.92rem; color:#2d2d2d;
    background:#faf8f6; outline:none; transition:all .2s; box-sizing:border-box;
}
.fi:focus { border-color:var(--rose); box-shadow:0 0 0 3px rgba(179,70,107,.12); background:#fff; }
.fi-ta { resize:vertical; min-height:85px; line-height:1.6; }
.fi-sel { cursor:pointer; }
.fhint { font-size:.73rem; color:#b0a898; margin-top:.32rem; }
.frow { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
@media(max-width:580px){ .frow { grid-template-columns:1fr; } }

/* Image Upload */
.img-upload-area {
    border:2px dashed #e0dbd3; border-radius:12px;
    padding:1.5rem 1rem; text-align:center; cursor:pointer;
    transition:all .2s; background:#faf8f6; position:relative;
}
.img-upload-area:hover, .img-upload-area.drag-over { border-color:var(--rose); background:var(--rose-lt); }
.img-upload-area input[type="file"] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
.img-upload-icon { font-size:1.8rem; color:#c8c0b8; margin-bottom:.5rem; }
.img-upload-text { font-size:.82rem; color:#9a9a9a; }
.img-upload-text strong { color:var(--rose); }
.cur-img-wrap { margin-bottom:.85rem; }
.cur-img-wrap img {
    width:100%; max-height:170px; object-fit:cover;
    border-radius:10px; border:2px solid #e8e3dc; display:block;
}
.cur-img-label { font-size:.72rem; color:#9a9a9a; margin-bottom:.45rem; font-weight:600; text-transform:uppercase; letter-spacing:.04em; }
.img-preview-wrap { position:relative; display:inline-block; margin-top:.65rem; width:100%; }
.img-preview-wrap img { width:100%; max-height:160px; object-fit:cover; border-radius:10px; border:2px solid #e8e3dc; }
.img-remove-btn {
    position:absolute; top:-.4rem; right:-.4rem;
    width:24px; height:24px; border-radius:50%;
    background:var(--red); color:#fff; border:none; font-size:.7rem;
    cursor:pointer; display:flex; align-items:center; justify-content:center;
    box-shadow:0 2px 6px rgba(0,0,0,.2);
}
#newImgWrap { display:none; }

/* Icon picker */
.icon-prev-row { display:flex; align-items:center; gap:.8rem; margin-top:.5rem; }
.icon-box {
    width:50px; height:50px; border-radius:11px;
    background:var(--rose-lt); color:var(--rose);
    display:flex; align-items:center; justify-content:center;
    font-size:1.35rem; flex-shrink:0; border:2px solid rgba(179,70,107,.2);
}
.icon-chips { display:flex; flex-wrap:wrap; gap:.38rem; margin-top:.6rem; }
.ichip {
    width:32px; height:32px; border-radius:8px;
    border:1.5px solid #e8e3dc; background:#faf8f6; color:#9a9a9a;
    display:flex; align-items:center; justify-content:center;
    font-size:.85rem; cursor:pointer; transition:all .15s;
}
.ichip:hover, .ichip.on { background:var(--rose-lt); color:var(--rose); border-color:rgba(179,70,107,.35); }

/* Status */
.sts-row { display:flex; gap:.6rem; }
.sts-opt { flex:1; position:relative; }
.sts-opt input { position:absolute; opacity:0; width:0; height:0; }
.sts-opt label {
    display:flex; align-items:center; justify-content:center; gap:.4rem;
    padding:.62rem .8rem; border-radius:9px; border:1.5px solid #e8e3dc;
    font-size:.85rem; font-weight:600; cursor:pointer; transition:all .15s;
    color:#9a9a9a; background:#faf8f6;
}
.sts-opt input:checked + label.l-act { background:var(--sage-lt); color:var(--sage); border-color:rgba(94,146,114,.35); }
.sts-opt input:checked + label.l-ina { background:var(--red-lt);  color:var(--red);  border-color:rgba(193,73,90,.25); }

/* Stats */
.edit-stats { display:grid; grid-template-columns:1fr 1fr; gap:.7rem; }
.estat { background:#faf8f6; border:1px solid #ede9e4; border-radius:10px; padding:.85rem; text-align:center; }
.estat-val { font-size:1.4rem; font-weight:800; color:var(--rose); }
.estat-lbl { font-size:.7rem; color:#9a9a9a; margin-top:.15rem; }

/* Services list */
.svc-item {
    display:flex; align-items:center; justify-content:space-between;
    padding:.75rem 1rem; border-bottom:1px solid #f5f2ee;
    transition:background .15s;
}
.svc-item:last-child { border-bottom:none; }
.svc-item:hover { background:#faf8f6; }
.svc-name { font-weight:600; font-size:.87rem; color:#2d2d2d; }
.svc-meta { font-size:.75rem; color:#9a9a9a; margin-top:.1rem; }
.svc-price { font-weight:700; font-size:.87rem; color:var(--sage); }

/* Add service */
.svc-card-head-extra { margin-left:auto; display:flex; align-items:center; gap:.5rem; }
.svc-count-badge {
    background:var(--slate-lt); color:var(--slate); font-size:.7rem; font-weight:700;
    padding:.18rem .6rem; border-radius:20px;
}
.btn-add-svc {
    display:inline-flex; align-items:center; gap:.35rem;
    background:var(--rose-lt); color:var(--rose); border:1.5px solid rgba(179,70,107,.25);
    padding:.32rem .7rem; border-radius:8px; font-size:.74rem; font-weight:700;
    cursor:pointer; transition:all .15s;
}
.btn-add-svc:hover { background:var(--rose); color:#fff; }
.add-svc-form { padding:1.1rem 1.4rem 1.3rem; border-top:1px dashed #ede9e4; background:#fffaf9; }
.btn-save-svc {
    display:inline-flex; align-items:center; gap:.4rem;
    background:var(--sage); color:#fff; border:none; border-radius:8px;
    padding:.55rem 1.1rem; font-size:.82rem; font-weight:700; cursor:pointer; transition:all .15s;
}
.btn-save-svc:hover { background:#4d7c60; }
.btn-cncl-svc {
    background:transparent; border:1.5px solid #e8e3dc; color:#9a9a9a;
    border-radius:8px; padding:.55rem 1rem; font-size:.82rem; font-weight:600; cursor:pointer;
}
.btn-cncl-svc:hover { border-color:var(--red); color:var(--red); }

/* Live Preview */
.lp-card { border-radius:12px; overflow:hidden; border:1px solid #e8e3dc; }
.lp-img { height:80px; display:flex; align-items:center; justify-content:center; }
.lp-icon-wrap {
    width:46px; height:46px; border-radius:50%;
    background:rgba(255,255,255,.22); border:2px solid rgba(255,255,255,.3);
    display:flex; align-items:center; justify-content:center;
    font-size:1.25rem; color:#fff;
}
.lp-body { padding:.85rem 1rem; background:#fff; }
.lp-name { font-weight:700; font-size:.93rem; color:#2d2d2d; margin-bottom:.2rem; }
.lp-sub  { font-size:.73rem; color:#9a9a9a; }

/* Actions */
.form-acts { display:flex; gap:.7rem; align-items:center; flex-wrap:wrap; margin-top:1.2rem; }
.btn-save {
    display:inline-flex; align-items:center; gap:.45rem;
    padding:.7rem 1.5rem; background:var(--rose); color:#fff;
    border:none; border-radius:9px; font-size:.9rem; font-weight:700;
    cursor:pointer; transition:all .18s;
}
.btn-save:hover { background:var(--rose-h); transform:translateY(-1px); box-shadow:0 4px 14px rgba(179,70,107,.3); }
.btn-cncl {
    display:inline-flex; align-items:center; gap:.4rem;
    padding:.7rem 1.1rem; background:transparent; color:#9a9a9a;
    border:1.5px solid #e8e3dc; border-radius:9px; font-size:.88rem;
    font-weight:600; text-decoration:none; transition:all .15s;
}
.btn-cncl:hover { border-color:var(--rose); color:var(--rose); }

/* Danger zone */
.danger-zone {
    margin-top:1.1rem; padding:1rem; border:1.5px solid rgba(193,73,90,.2);
    border-radius:11px; background:var(--red-lt);
}
.danger-title { font-size:.7rem; font-weight:700; color:var(--red); text-transform:uppercase; letter-spacing:.04em; margin:0 0 .45rem; }
.danger-desc  { font-size:.79rem; color:#9a9a9a; margin:0 0 .75rem; }
.btn-del {
    display:inline-flex; align-items:center; gap:.4rem;
    padding:.62rem 1.1rem; background:var(--red-lt); color:var(--red);
    border:1.5px solid rgba(193,73,90,.25); border-radius:8px;
    font-size:.85rem; font-weight:700; cursor:pointer; transition:all .15s;
}
.btn-del:hover { background:var(--red); color:#fff; }
</style>

<a href="{{ route('admin.categories.index') }}" class="btn-back">
    <i class="fas fa-arrow-left"></i> Back to Categories
</a>

<div class="pg-title-row">
    <div class="pg-title-icon"><i class="fas fa-pen"></i></div>
    <div>
        <h1>Edit Category</h1>
        <p>Updating: <strong style="color:#2d2d2d;">{{ $category->name }}</strong></p>
    </div>
</div>

<form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" id="catForm">
@csrf @method('PUT')
<div class="form-layout">

    {{-- LEFT --}}
    <div>
        {{-- Basic Info --}}
        <div class="fcard">
            <div class="fcard-head"><i class="fas fa-info-circle"></i><span class="fcard-title">Basic Information</span></div>
            <div class="fcard-body">
                <div class="fg">
                    <label class="fl">Category Name <span>*</span></label>
                    <input type="text" name="name" class="fi" id="nameInput"
                        value="{{ old('name', $category->name) }}" required>
                </div>
                <div class="fg">
                    <label class="fl">Description</label>
                    <textarea name="description" class="fi fi-ta">{{ old('description', $category->description) }}</textarea>
                </div>
                <div class="frow">
                    <div class="fg">
                        <label class="fl">Avg Duration (minutes)</label>
                        <input type="number" name="duration" class="fi"
                            value="{{ old('duration', $category->duration ?? '') }}"
                            placeholder="e.g. 60" min="5" step="5">
                        <p class="fhint">Typical service length</p>
                    </div>
                    <div class="fg">
                        <label class="fl">Sort Order</label>
                        <input type="number" name="sort_order" class="fi"
                            value="{{ old('sort_order', $category->sort_order) }}" min="0">
                        <p class="fhint">Lower = shown first</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Category Image --}}
        <div class="fcard">
            <div class="fcard-head"><i class="fas fa-image"></i><span class="fcard-title">Category Image</span></div>
            <div class="fcard-body">
                @if($category->image)
                <div class="cur-img-wrap">
                    <p class="cur-img-label">Current Image</p>
                    <img src="{{ asset('storage/'.$category->image) }}" alt="{{ $category->name }}" id="curImg">
                </div>
                @endif
                <div class="img-upload-area" id="dropZone">
                    <input type="file" name="image" id="imgInput" accept="image/*" onchange="previewImg(this)">
                    <div id="imgPlaceholder">
                        <div class="img-upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                        <div class="img-upload-text">
                            <strong>{{ $category->image ? 'Replace image' : 'Click to upload' }}</strong> or drag & drop<br>
                            <span style="font-size:.75rem;">PNG, JPG, WEBP — max 2MB</span>
                        </div>
                    </div>
                    <div id="newImgWrap" class="img-preview-wrap">
                        <img id="imgPreview" src="" alt="New Preview">
                        <button type="button" class="img-remove-btn" onclick="removeImg()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                @if($category->image)
                <div style="margin-top:.7rem;display:flex;align-items:center;gap:.5rem;">
                    <input type="checkbox" name="remove_image" id="removeImg" value="1" style="width:15px;height:15px;">
                    <label for="removeImg" style="font-size:.8rem;color:var(--red);cursor:pointer;margin:0;">Remove current image</label>
                </div>
                @endif
            </div>
        </div>

        {{-- Icon --}}
        <div class="fcard">
            <div class="fcard-head"><i class="fas fa-palette"></i><span class="fcard-title">Icon</span></div>
            <div class="fcard-body">
                <div class="fg">
                    <label class="fl">Font Awesome Class</label>
                    <input type="text" name="icon" id="iconInput" class="fi"
                        value="{{ old('icon', $category->icon ?? 'fa-tag') }}"
                        oninput="updateIcon(this.value)">
                    <p class="fhint">e.g. <code>fa-scissors</code>, <code>fa-spa</code>, <code>fa-heart</code></p>
                    <div class="icon-prev-row">
                        <div class="icon-box"><i class="fas {{ $category->icon ?? 'fa-tag' }}" id="iconPrev"></i></div>
                        <span style="font-size:.78rem;color:#b0a898;">Live preview</span>
                    </div>
                    <p style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#c0b8b0;margin:.85rem 0 .38rem;">Quick picks</p>
                    <div class="icon-chips">
                        @foreach(['fa-scissors','fa-spa','fa-hand-sparkles','fa-paint-brush','fa-star','fa-heart','fa-gem','fa-crown','fa-leaf','fa-fire','fa-smile','fa-eye','fa-tint','fa-magic','fa-tag','fa-concierge-bell','fa-sun','fa-feather'] as $ic)
                        <div class="ichip {{ ($category->icon??'fa-tag')===$ic?'on':'' }}" title="{{ $ic }}" onclick="pickIcon('{{ $ic }}')">
                            <i class="fas {{ $ic }}"></i>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Services under this category --}}
        <div class="fcard">
            <div class="fcard-head">
                <i class="fas fa-concierge-bell"></i>
                <span class="fcard-title">Services in this Category</span>
                <div class="svc-card-head-extra">
                    <span class="svc-count-badge">{{ $category->services_count ?? $category->services->count() }}</span>
                    @if(Route::has('admin.services.store'))
                    <button type="button" class="btn-add-svc" onclick="toggleAddService()">
                        <i class="fas fa-plus"></i> Add Service
                    </button>
                    @endif
                </div>
            </div>
            @if(isset($category->services) && $category->services->count())
            <div>
                @foreach($category->services as $svc)
                <div class="svc-item">
                    <div>
                        <div class="svc-name">{{ $svc->name }}</div>
                        <div class="svc-meta">
                            @if($svc->duration) <i class="fas fa-clock" style="font-size:.65rem;"></i> {{ $svc->duration }} min @endif
                            @if($svc->is_active) &nbsp;<span style="color:var(--sage);font-weight:600;font-size:.72rem;">Active</span>
                            @else &nbsp;<span style="color:var(--red);font-weight:600;font-size:.72rem;">Inactive</span> @endif
                        </div>
                    </div>
                    <div class="svc-price">
                        @if($svc->price) Rs. {{ number_format($svc->price) }} @else — @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="fcard-body">
                <div style="background:#faf8f6;border:1px dashed #e0dbd3;border-radius:10px;padding:1rem;text-align:center;">
                    <i class="fas fa-concierge-bell" style="color:#c8c0b8;font-size:1.3rem;margin-bottom:.4rem;display:block;"></i>
                    <p style="font-size:.82rem;color:#9a9a9a;margin:0 0 .7rem;">No services in this category yet.</p>
                    @if(Route::has('admin.services.store'))
                    <button type="button" onclick="toggleAddService()"
                        style="font-size:.8rem;color:var(--rose);font-weight:700;background:none;border:none;cursor:pointer;">
                        <i class="fas fa-plus"></i> Add your first service
                    </button>
                    @endif
                </div>
            </div>
            @endif

            {{-- Quick Add Service form (hidden until "Add Service" is clicked) --}}
            @if(Route::has('admin.services.store'))
            <div class="add-svc-form" id="addServiceForm" style="display:none;">
                <form action="{{ route('admin.services.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="category_id" value="{{ $category->id }}">
                    <div class="frow">
                        <div class="fg">
                            <label class="fl">Service Name <span>*</span></label>
                            <input type="text" name="name" class="fi" placeholder="e.g. Hair Spa" required>
                        </div>
                        <div class="fg">
                            <label class="fl">Price (Rs.)</label>
                            <input type="number" name="price" class="fi" placeholder="e.g. 1500" min="0">
                        </div>
                    </div>
                    <div class="frow">
                        <div class="fg">
                            <label class="fl">Duration (minutes)</label>
                            <input type="number" name="duration" class="fi" placeholder="e.g. 45" min="5" step="5">
                        </div>
                        <div class="fg">
                            <label class="fl">Status</label>
                            <select name="is_active" class="fi fi-sel">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div style="display:flex; gap:.6rem;">
                        <button type="submit" class="btn-save-svc"><i class="fas fa-check"></i> Save Service</button>
                        <button type="button" class="btn-cncl-svc" onclick="toggleAddService()">Cancel</button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>

    {{-- RIGHT --}}
    <div>
        {{-- Stats --}}
        <div class="fcard">
            <div class="fcard-head"><i class="fas fa-chart-bar"></i><span class="fcard-title">Category Stats</span></div>
            <div class="fcard-body" style="padding:1rem;">
                <div class="edit-stats">
                    <div class="estat">
                        <div class="estat-val">{{ $category->services_count ?? $category->services->count() }}</div>
                        <div class="estat-lbl">Services</div>
                    </div>
                    <div class="estat">
                        <div class="estat-val">{{ $category->created_at->format('M Y') }}</div>
                        <div class="estat-lbl">Created</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="fcard">
            <div class="fcard-head"><i class="fas fa-toggle-on"></i><span class="fcard-title">Status</span></div>
            <div class="fcard-body">
                <div class="sts-row">
                    <div class="sts-opt">
                        <input type="radio" name="is_active" id="sa" value="1"
                            {{ old('is_active', $category->is_active ? '1':'0')==='1' ? 'checked':'' }}>
                        <label for="sa" class="l-act"><i class="fas fa-check-circle"></i> Active</label>
                    </div>
                    <div class="sts-opt">
                        <input type="radio" name="is_active" id="si" value="0"
                            {{ old('is_active', $category->is_active ? '1':'0')==='0' ? 'checked':'' }}>
                        <label for="si" class="l-ina"><i class="fas fa-ban"></i> Inactive</label>
                    </div>
                </div>
                <p class="fhint" style="margin-top:.55rem;">Active categories appear during booking.</p>
            </div>
        </div>

        {{-- Live Preview --}}
        <div class="fcard">
            <div class="fcard-head"><i class="fas fa-eye"></i><span class="fcard-title">Card Preview</span></div>
            <div class="fcard-body" style="padding:1rem;">
                <div class="lp-card">
                    <div class="lp-img" id="lpGrad" style="background:linear-gradient(135deg,#b3466b,#dd92ac);">
                        <div class="lp-icon-wrap">
                            <i class="fas {{ $category->icon ?? 'fa-tag' }}" id="lpIcon"></i>
                        </div>
                    </div>
                    <div class="lp-body">
                        <div class="lp-name" id="lpName">{{ $category->name }}</div>
                        <div class="lp-sub">{{ $category->services_count ?? $category->services->count() }} services</div>
                    </div>
                </div>
                <p class="fhint" style="text-align:center;margin-top:.5rem;">Updates as you type</p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="form-acts">
            <button type="submit" class="btn-save"><i class="fas fa-save"></i> Update Category</button>
            <a href="{{ route('admin.categories.index') }}" class="btn-cncl"><i class="fas fa-times"></i> Cancel</a>
        </div>

        {{-- Danger Zone --}}
        <div class="danger-zone">
            <p class="danger-title"><i class="fas fa-exclamation-triangle"></i> Danger Zone</p>
            <p class="danger-desc">Permanently deletes this category. Services linked to it may be affected.</p>
            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="margin:0;">
                @csrf @method('DELETE')
                <button type="submit" class="btn-del"
                    onclick="return confirm('Delete \'{{ addslashes($category->name) }}\'? This cannot be undone.')">
                    <i class="fas fa-trash"></i> Delete Category
                </button>
            </form>
        </div>
    </div>

</div>
</form>

<script>
function previewImg(input){
    if(input.files && input.files[0]){
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('imgPreview').src = e.target.result;
            document.getElementById('newImgWrap').style.display = 'inline-block';
            document.getElementById('imgPlaceholder').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function removeImg(){
    document.getElementById('imgInput').value = '';
    document.getElementById('newImgWrap').style.display = 'none';
    document.getElementById('imgPlaceholder').style.display = 'block';
}
function updateIcon(val){
    val = val.trim();
    if(!val.startsWith('fa-')) val = 'fa-'+val;
    document.getElementById('iconPrev').className = 'fas '+val;
    document.getElementById('lpIcon').className   = 'fas '+val;
    document.querySelectorAll('.ichip').forEach(c => c.classList.toggle('on', c.title===val));
}
function pickIcon(ic){
    document.getElementById('iconInput').value = ic;
    updateIcon(ic);
}
function toggleAddService(){
    const f = document.getElementById('addServiceForm');
    f.style.display = (f.style.display === 'none' || !f.style.display) ? 'block' : 'none';
}
document.addEventListener('DOMContentLoaded', function(){
    document.getElementById('nameInput').addEventListener('input', function(){
        document.getElementById('lpName').textContent = this.value.trim() || 'Category Name';
    });
    const dz = document.getElementById('dropZone');
    dz.addEventListener('dragover', e=>{ e.preventDefault(); dz.classList.add('drag-over'); });
    dz.addEventListener('dragleave', ()=> dz.classList.remove('drag-over'));
    dz.addEventListener('drop', e=>{ e.preventDefault(); dz.classList.remove('drag-over');
        if(e.dataTransfer.files[0]){ document.getElementById('imgInput').files = e.dataTransfer.files; previewImg(document.getElementById('imgInput')); }
    });
});
</script>
@endsection