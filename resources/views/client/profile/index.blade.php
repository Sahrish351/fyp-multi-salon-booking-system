{{-- ============================================================ --}}
{{-- FILE: resources/views/client/profile/index.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.client')
@section('title', 'My Profile — Glamora')
@section('content')
 
<div class="mb-4">
    <h4 class="fw-bold mb-1" style="color:#333;font-family:'Playfair Display',serif;"><i class="fas fa-user-edit me-2" style="color:#E91E8C;"></i>My Profile</h4>
    <p style="color:#aaa;font-size:0.85rem;margin:0;">Update your personal information and preferences</p>
</div>
 
<div class="row g-4">
    {{-- Profile Card --}}
    <div class="col-lg-4">
        <div class="bg-white rounded-4 p-4 text-center" style="border:1px solid #fce4ec;">
            <div class="position-relative d-inline-block mb-3">
                <img src="{{ Auth::user()->avatar_url }}" class="rounded-circle" width="100" height="100" style="object-fit:cover;border:4px solid #E91E8C;" id="avatarPreview" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=E91E8C&color=fff&size=100'">
                <button type="button" onclick="document.getElementById('avatarInput').click()" style="position:absolute;bottom:2px;right:2px;width:32px;height:32px;background:#E91E8C;color:#fff;border:none;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;">
                    <i class="fas fa-camera" style="font-size:0.75rem;"></i>
                </button>
            </div>
            <h5 class="fw-bold" style="color:#333;">{{ Auth::user()->name }}</h5>
            <p style="color:#aaa;font-size:0.85rem;margin-bottom:1rem;">{{ Auth::user()->email }}</p>
            <div class="d-flex justify-content-center gap-3 mb-3">
                @foreach([
                    [Auth::user()->appointments->count(),'Bookings'],
                    [Auth::user()->favorites->count(),'Favorites'],
                    [Auth::user()->reviews->count(),'Reviews'],
                ] as [$count,$label])
                <div class="text-center">
                    <div style="font-size:1.4rem;font-weight:700;color:#E91E8C;">{{ $count }}</div>
                    <div style="color:#aaa;font-size:0.72rem;">{{ $label }}</div>
                </div>
                @endforeach
            </div>
            <div class="p-3 rounded-3" style="background:#fff5f9;border:1px solid #fce4ec;">
                <div style="color:#aaa;font-size:0.75rem;margin-bottom:2px;">Member Since</div>
                <div style="color:#333;font-weight:600;font-size:0.88rem;">{{ Auth::user()->created_at->format('F Y') }}</div>
            </div>
        </div>
    </div>
 
    {{-- Update Form --}}
    <div class="col-lg-8">
        <div class="bg-white rounded-4 p-4" style="border:1px solid #fce4ec;">
            <h6 class="fw-bold mb-4" style="color:#333;font-family:'Playfair Display',serif;"><i class="fas fa-edit me-2" style="color:#E91E8C;"></i>Edit Personal Information</h6>
            <form action="{{ route('client.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" id="avatarInput" name="avatar" accept="image/*" class="d-none" onchange="previewAvatar(this)">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label style="color:#555;font-size:0.85rem;font-weight:600;" class="mb-1">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required style="border:2px solid #fce4ec;border-radius:10px;padding:0.75rem 1rem;" onfocus="this.style.borderColor='#E91E8C'" onblur="this.style.borderColor='#fce4ec'">
                        @error('name')<div class="text-danger mt-1" style="font-size:0.78rem;">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label style="color:#555;font-size:0.85rem;font-weight:600;" class="mb-1">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ Auth::user()->phone }}" placeholder="03xx-xxxxxxx" style="border:2px solid #fce4ec;border-radius:10px;padding:0.75rem 1rem;" onfocus="this.style.borderColor='#E91E8C'" onblur="this.style.borderColor='#fce4ec'">
                    </div>
                    <div class="col-12">
                        <label style="color:#555;font-size:0.85rem;font-weight:600;" class="mb-1">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" required style="border:2px solid #fce4ec;border-radius:10px;padding:0.75rem 1rem;" onfocus="this.style.borderColor='#E91E8C'" onblur="this.style.borderColor='#fce4ec'">
                    </div>
                    <div class="col-md-6">
                        <label style="color:#555;font-size:0.85rem;font-weight:600;" class="mb-1">City</label>
                        <select name="city" class="form-select" style="border:2px solid #fce4ec;border-radius:10px;padding:0.75rem 1rem;" onfocus="this.style.borderColor='#E91E8C'" onblur="this.style.borderColor='#fce4ec'">
                            @foreach(['Lahore','Karachi','Islamabad','Rawalpindi','Faisalabad','Multan','Peshawar','Quetta','Gujranwala','Sialkot'] as $city)
                                <option {{ Auth::user()->city===$city?'selected':'' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label style="color:#555;font-size:0.85rem;font-weight:600;" class="mb-1">Theme</label>
                        <select name="theme" class="form-select" style="border:2px solid #fce4ec;border-radius:10px;padding:0.75rem 1rem;" onfocus="this.style.borderColor='#E91E8C'" onblur="this.style.borderColor='#fce4ec'">
                            <option value="light" {{ Auth::user()->theme==='light'?'selected':'' }}>Light Mode</option>
                            <option value="dark" {{ Auth::user()->theme==='dark'?'selected':'' }}>Dark Mode</option>
                        </select>
                    </div>
                    <div class="col-12 mt-2">
                        <button type="submit" class="btn rounded-pill px-5 py-2 fw-semibold" style="background:linear-gradient(135deg,#E91E8C,#c2185b);color:#fff;border:none;">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
 
        {{-- Change Password --}}
        <div class="bg-white rounded-4 p-4 mt-4" style="border:1px solid #fce4ec;">
            <h6 class="fw-bold mb-4" style="color:#333;font-family:'Playfair Display',serif;"><i class="fas fa-lock me-2" style="color:#E91E8C;"></i>Change Password</h6>
            <form action="{{ route('client.profile.update') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label style="color:#555;font-size:0.85rem;font-weight:600;" class="mb-1">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required style="border:2px solid #fce4ec;border-radius:10px;padding:0.75rem 1rem;" onfocus="this.style.borderColor='#E91E8C'" onblur="this.style.borderColor='#fce4ec'">
                    </div>
                    <div class="col-md-4">
                        <label style="color:#555;font-size:0.85rem;font-weight:600;" class="mb-1">New Password</label>
                        <input type="password" name="password" class="form-control" required style="border:2px solid #fce4ec;border-radius:10px;padding:0.75rem 1rem;" onfocus="this.style.borderColor='#E91E8C'" onblur="this.style.borderColor='#fce4ec'">
                    </div>
                    <div class="col-md-4">
                        <label style="color:#555;font-size:0.85rem;font-weight:600;" class="mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required style="border:2px solid #fce4ec;border-radius:10px;padding:0.75rem 1rem;" onfocus="this.style.borderColor='#E91E8C'" onblur="this.style.borderColor='#fce4ec'">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn rounded-pill px-4 py-2" style="background:linear-gradient(135deg,#333,#555);color:#fff;border:none;font-weight:600;">
                            <i class="fas fa-key me-2"></i>Update Password
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
 
 