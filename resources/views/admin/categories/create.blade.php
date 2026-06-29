@extends('layouts.admin')
@section('title', 'Add Category - Glamora')

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
.img-upload-area:hover, .img-upload-area.drag-over {
    border-color:var(--rose); background:var(--rose-lt);
}
.img-upload-area input[type="file"] {
    position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%;
}
.img-upload-icon { font-size:1.8rem; color:#c8c0b8; margin-bottom:.5rem; }
.img-upload-text { font-size:.82rem; color:#9a9a9a; }
.img-upload-text strong { color:var(--rose); }
.img-preview-wrap { position:relative; display:inline-block; margin-top:.75rem; }
.img-preview-wrap img {
    width:100%; max-height:160px; object-fit:cover;
    border-radius:10px; display:block; border:2px solid #e8e3dc;
}
.img-remove-btn {
    position:absolute; top:-.4rem; right:-.4rem;
    width:24px; height:24px; border-radius:50%;
    background:var(--red); color:#fff; border:none;
    font-size:.7rem; cursor:pointer; display:flex;
    align-items:center; justify-content:center;
    box-shadow:0 2px 6px rgba(0,0,0,.2);
}
#imgPreviewWrap { display:none; }

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

/* Live Preview */
.lp-card { border-radius:12px; overflow:hidden; border:1px solid #e8e3dc; }
.lp-img {
    height:80px; display:flex; align-items:center; justify-content:center;
    transition:background .3s; position:relative;
}
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
</style>

<a href="{{ route('admin.categories.index') }}" class="btn-back">
    <i class="fas fa-arrow-left"></i> Back to Categories
</a>

<div class="pg-title-row">
    <div class="pg-title-icon"><i class="fas fa-plus"></i></div>
    <div>
        <h1>Add New Category</h1>
        <p>Create a service category for your salon listings</p>
    </div>
</div>

<form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" id="catForm">
@csrf
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
                        placeholder="e.g. Hair Care, Nail Art, Skincare…" value="{{ old('name') }}" required>
                </div>
                <div class="fg">
                    <label class="fl">Description</label>
                    <textarea name="description" class="fi fi-ta"
                        placeholder="Briefly describe what services belong in this category…">{{ old('description') }}</textarea>
                </div>
                <div class="frow">
                    <div class="fg">
                        <label class="fl">Avg Duration (minutes)</label>
                        <input type="number" name="duration" class="fi"
                            placeholder="e.g. 30, 60, 90" value="{{ old('duration') }}" min="5" step="5">
                        <p class="fhint">Typical service length</p>
                    </div>
                    <div class="fg">
                        <label class="fl">Sort Order</label>
                        <input type="number" name="sort_order" class="fi" value="{{ old('sort_order',0) }}" min="0">
                        <p class="fhint">Lower = shown first</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Category Image --}}
        <div class="fcard">
            <div class="fcard-head"><i class="fas fa-image"></i><span class="fcard-title">Category Image</span></div>
            <div class="fcard-body">
                <div class="img-upload-area" id="dropZone">
                    <input type="file" name="image" id="imgInput" accept="image/*" onchange="previewImg(this)">
                    <div id="imgPlaceholder">
                        <div class="img-upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                        <div class="img-upload-text">
                            <strong>Click to upload</strong> or drag & drop<br>
                            <span style="font-size:.75rem;">PNG, JPG, WEBP — max 2MB</span>
                        </div>
                    </div>
                    <div id="imgPreviewWrap" class="img-preview-wrap">
                        <img id="imgPreview" src="" alt="Preview">
                        <button type="button" class="img-remove-btn" onclick="removeImg()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <p class="fhint" style="margin-top:.6rem;">This image appears on the category card shown to clients.</p>
            </div>
        </div>

        {{-- Icon --}}
        <div class="fcard">
            <div class="fcard-head"><i class="fas fa-palette"></i><span class="fcard-title">Icon</span></div>
            <div class="fcard-body">
                <div class="fg">
                    <label class="fl">Font Awesome Class</label>
                    <input type="text" name="icon" id="iconInput" class="fi"
                        value="{{ old('icon','fa-tag') }}" placeholder="fa-tag"
                        oninput="updateIcon(this.value)">
                    <p class="fhint">Type any FA class e.g. <code>fa-scissors</code>, <code>fa-spa</code></p>
                    <div class="icon-prev-row">
                        <div class="icon-box"><i class="fas fa-tag" id="iconPrev"></i></div>
                        <span style="font-size:.78rem;color:#b0a898;">Live icon preview</span>
                    </div>
                    <p style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#c0b8b0;margin:.85rem 0 .38rem;">Quick picks</p>
                    <div class="icon-chips">
                        @foreach(['fa-scissors','fa-spa','fa-hand-sparkles','fa-paint-brush','fa-star','fa-heart','fa-gem','fa-crown','fa-leaf','fa-fire','fa-smile','fa-eye','fa-tint','fa-magic','fa-tag','fa-concierge-bell','fa-sun','fa-feather'] as $ic)
                        <div class="ichip" title="{{ $ic }}" onclick="pickIcon('{{ $ic }}')"><i class="fas {{ $ic }}"></i></div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Services under this category --}}
        <div class="fcard">
            <div class="fcard-head"><i class="fas fa-concierge-bell"></i><span class="fcard-title">Services in this Category</span></div>
            <div class="fcard-body">
                <div style="background:var(--rose-lt);border:1px dashed rgba(179,70,107,.3);border-radius:10px;padding:1.1rem;text-align:center;">
                    <i class="fas fa-info-circle" style="color:var(--rose);font-size:1.1rem;margin-bottom:.4rem;display:block;"></i>
                    <p style="font-size:.82rem;color:#8a7a80;margin:0;">Services can be added to this category <strong style="color:var(--rose);">after saving</strong>. You'll be able to add and manage them right from the Edit page.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT --}}
    <div>
        {{-- Status --}}
        <div class="fcard">
            <div class="fcard-head"><i class="fas fa-toggle-on"></i><span class="fcard-title">Status</span></div>
            <div class="fcard-body">
                <div class="sts-row">
                    <div class="sts-opt">
                        <input type="radio" name="is_active" id="sa" value="1" {{ old('is_active','1')=='1'?'checked':'' }}>
                        <label for="sa" class="l-act"><i class="fas fa-check-circle"></i> Active</label>
                    </div>
                    <div class="sts-opt">
                        <input type="radio" name="is_active" id="si" value="0" {{ old('is_active')=='0'?'checked':'' }}>
                        <label for="si" class="l-ina"><i class="fas fa-ban"></i> Inactive</label>
                    </div>
                </div>
                <p class="fhint" style="margin-top:.55rem;">Active categories are visible to clients during booking.</p>
            </div>
        </div>

        {{-- Live Preview --}}
        <div class="fcard">
            <div class="fcard-head"><i class="fas fa-eye"></i><span class="fcard-title">Card Preview</span></div>
            <div class="fcard-body" style="padding:1rem;">
                <div class="lp-card">
                    <div class="lp-img" id="lpGrad" style="background:linear-gradient(135deg,#b3466b,#dd92ac);">
                        <div class="lp-icon-wrap"><i class="fas fa-tag" id="lpIcon"></i></div>
                    </div>
                    <div class="lp-body">
                        <div class="lp-name" id="lpName">Category Name</div>
                        <div class="lp-sub">0 services</div>
                    </div>
                </div>
                <p class="fhint" style="text-align:center;margin-top:.5rem;">Updates as you type</p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="form-acts">
            <button type="submit" class="btn-save"><i class="fas fa-save"></i> Create Category</button>
            <a href="{{ route('admin.categories.index') }}" class="btn-cncl"><i class="fas fa-times"></i> Cancel</a>
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
            document.getElementById('imgPreviewWrap').style.display = 'inline-block';
            document.getElementById('imgPlaceholder').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function removeImg(){
    document.getElementById('imgInput').value = '';
    document.getElementById('imgPreviewWrap').style.display = 'none';
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
document.addEventListener('DOMContentLoaded', function(){
    document.getElementById('nameInput').addEventListener('input', function(){
        document.getElementById('lpName').textContent = this.value.trim() || 'Category Name';
    });
    // drag & drop
    const dz = document.getElementById('dropZone');
    dz.addEventListener('dragover', e=>{ e.preventDefault(); dz.classList.add('drag-over'); });
    dz.addEventListener('dragleave', ()=> dz.classList.remove('drag-over'));
    dz.addEventListener('drop', e=>{ e.preventDefault(); dz.classList.remove('drag-over');
        if(e.dataTransfer.files[0]){ document.getElementById('imgInput').files = e.dataTransfer.files; previewImg(document.getElementById('imgInput')); }
    });
});
</script>
@endsection