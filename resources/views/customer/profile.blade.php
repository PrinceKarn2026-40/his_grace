@extends('layouts.customer')
@section('title', 'My Profile')
@section('breadcrumb', 'My Profile')

@push('styles')
<style>
    .avatar-wrap { position: relative; width: 100px; height: 100px; margin: 0 auto; }
    .avatar-wrap img, .avatar-wrap .avatar-initials {
        width: 100px; height: 100px; border-radius: 50%; object-fit: cover;
        border: 3px solid var(--gold);
    }
    .avatar-initials {
        background: var(--gold); color: #fff; font-size: 2.2rem; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
    }
    .avatar-edit {
        position: absolute; bottom: 2px; right: 2px;
        width: 30px; height: 30px; border-radius: 50%;
        background: var(--gold); color: #fff; border: 2px solid #fff;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; font-size: .8rem; transition: .2s;
    }
    .avatar-edit:hover { background: #b8943e; }
    #photoInput { display: none; }
</style>
@endpush

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-0">My Profile</h4>
    <p class="text-muted small mb-0">Update your personal information, photo and password</p>
</div>

<form method="POST" action="{{ route('customer.profile.update') }}" enctype="multipart/form-data">
@csrf
<div class="row g-4" style="max-width:820px">

    {{-- Left: Avatar + Info --}}
    <div class="col-md-4">
        <div class="card stat-card shadow-sm p-4 text-center">

            {{-- Avatar with edit button --}}
            <div class="avatar-wrap mb-3">
                @if(auth()->user()->profile_photo_path)
                    <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}"
                         alt="Profile Photo" id="avatarPreview">
                @else
                    <div class="avatar-initials" id="avatarInitials">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <img src="" alt="" id="avatarPreview" style="display:none; width:100px; height:100px; border-radius:50%; object-fit:cover; border:3px solid var(--gold);">
                @endif
                <label class="avatar-edit" for="photoInput" title="Change photo">
                    <i class="bi bi-camera-fill"></i>
                </label>
            </div>

            <input type="file" name="photo" id="photoInput" accept="image/*">

            <h5 class="fw-bold mb-1">{{ auth()->user()->name }}</h5>
            <p class="text-muted small mb-2">{{ auth()->user()->email }}</p>
            <span class="badge bg-success mb-3">Active Customer</span>

            <p class="text-muted small mb-1 text-start"><i class="bi bi-calendar me-2" style="color:var(--gold)"></i>Joined {{ auth()->user()->created_at->format('M d, Y') }}</p>
            <p class="text-muted small mb-1 text-start"><i class="bi bi-bag-check me-2" style="color:var(--gold)"></i>{{ auth()->user()->orders()->count() }} orders placed</p>
            <p class="text-muted small mb-0 text-start"><i class="bi bi-heart me-2" style="color:var(--gold)"></i>{{ auth()->user()->wishlists()->count() }} wishlist items</p>

            <div class="mt-3 p-2 rounded-3" style="background:#f8f9fa; font-size:.75rem; color:#888;">
                <i class="bi bi-info-circle me-1"></i>Click the camera icon to change your photo. JPG, PNG or WEBP, max 2MB.
            </div>
        </div>
    </div>

    {{-- Right: Update Form --}}
    <div class="col-md-8">
        <div class="card stat-card shadow-sm p-4">
            <h6 class="fw-bold mb-4">Personal Information</h6>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-semibold">Full Name *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', auth()->user()->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold">Email Address *</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', auth()->user()->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12"><hr class="my-1">
                    <p class="fw-semibold small mb-0">Change Password
                        <span class="text-muted fw-normal">(leave blank to keep current)</span>
                    </p>
                </div>

                <div class="col-12">
                    <label class="form-label small fw-semibold">Current Password</label>
                    <div class="input-group">
                        <input type="password" name="current_password" id="curPwd"
                            class="form-control @error('current_password') is-invalid @enderror">
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePwd('curPwd',this)">
                            <i class="bi bi-eye"></i>
                        </button>
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">New Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="newPwd"
                            class="form-control @error('password') is-invalid @enderror">
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePwd('newPwd',this)">
                            <i class="bi bi-eye"></i>
                        </button>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Confirm New Password</label>
                    <div class="input-group">
                        <input type="password" name="password_confirmation" id="confPwd" class="form-control">
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePwd('confPwd',this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="col-12 d-flex gap-2 mt-2">
                    <button type="submit" class="btn px-4" style="background:var(--gold);color:#fff;">
                        <i class="bi bi-check-circle me-1"></i>Save Changes
                    </button>
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </div>

</div>
</form>
@endsection

@push('scripts')
<script>
// Live photo preview
document.getElementById('photoInput').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('avatarPreview');
        const initials = document.getElementById('avatarInitials');
        preview.src = e.target.result;
        preview.style.display = 'block';
        if (initials) initials.style.display = 'none';
    };
    reader.readAsDataURL(file);
});

// Toggle password visibility
function togglePwd(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
@endpush
