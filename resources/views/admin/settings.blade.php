@extends('layouts.admin')
@section('title', 'Settings')
@section('breadcrumb', 'Settings')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-0">Settings</h4>
    <p class="text-muted small mb-0">Manage your admin account settings</p>
</div>

<div class="row g-4" style="max-width:800px">
    {{-- Profile Settings --}}
    <div class="col-12">
        <div class="card shadow-sm border-0 p-4">
            <h6 class="fw-bold mb-4">Profile Information</h6>
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Full Name *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', auth()->user()->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Email Address *</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', auth()->user()->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12"><hr class="my-1"><p class="fw-semibold small mb-2">Change Password <span class="text-muted fw-normal">(leave blank to keep current)</span></p></div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Current Password</label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">New Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn px-4" style="background:var(--gold);color:#fff;">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Store Info (display only) --}}
    <div class="col-12">
        <div class="card shadow-sm border-0 p-4">
            <h6 class="fw-bold mb-4">Store Information</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Store Name</label>
                    <input type="text" class="form-control" value="HisGrace Fashion" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Currency</label>
                    <input type="text" class="form-control" value="RWF (RWF)" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Contact Email</label>
                    <input type="text" class="form-control" value="libprince1999@gmail.com" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Location</label>
                    <input type="text" class="form-control" value="Kigali, Rwanda" readonly>
                </div>
            </div>
            <p class="text-muted small mt-3 mb-0"><i class="bi bi-info-circle me-1"></i>Store settings are managed via the <code>.env</code> configuration file.</p>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="col-12">
        <div class="card shadow-sm border-0 p-4">
            <h6 class="fw-bold mb-3">Quick Actions</h6>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-plus me-1"></i>Add Product</a>
                <a href="{{ route('admin.categories') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-tags me-1"></i>Manage Categories</a>
                <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-bag-check me-1"></i>View Orders</a>
                <a href="{{ route('admin.reports') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-bar-chart me-1"></i>View Reports</a>
                <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-shop me-1"></i>View Store</a>
            </div>
        </div>
    </div>
</div>
@endsection
