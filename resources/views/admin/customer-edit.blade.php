@extends('layouts.admin')
@section('title', 'Edit Customer')
@section('breadcrumb', 'Edit Customer')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Edit Customer</h4>
    <a href="{{ route('admin.customers') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

<div class="card shadow-sm border-0 p-4" style="max-width:560px;">
    <form method="POST" action="{{ route('admin.customers.update', $user) }}">
        @csrf @method('PUT')
        @if($errors->any())
            <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
        @endif
        <div class="mb-3">
            <label class="form-label small fw-semibold">Full Name *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label small fw-semibold">Email *</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label small fw-semibold">New Password <span class="text-muted fw-normal">(leave blank to keep current)</span></label>
            <input type="password" name="password" class="form-control" minlength="8">
        </div>
        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="email_verified" id="email_verified" value="1"
                {{ $user->email_verified_at ? 'checked' : '' }}>
            <label class="form-check-label small" for="email_verified">Email verified / Account active</label>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-dark btn-sm px-4">Save Changes</button>
            <a href="{{ route('admin.customers') }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
