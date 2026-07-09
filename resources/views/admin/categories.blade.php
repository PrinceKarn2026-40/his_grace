@extends('layouts.admin')
@section('title', 'Categories')
@section('breadcrumb', 'Categories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Category Management</h4>
    <span class="badge bg-light text-dark border">{{ $categories->count() }} categories</span>
</div>

<div class="row g-4">
    {{-- Add Category --}}
    <div class="col-md-4">
        <div class="card shadow-sm border-0 p-4">
            <h6 class="fw-bold mb-3">Add New Category</h6>
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Category Name *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        placeholder="e.g. Dresses" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button class="btn w-100" style="background:var(--gold);color:#fff;">Add Category</button>
            </form>
        </div>
    </div>

    {{-- Categories List --}}
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Name</th><th>Slug</th><th>Products</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $cat)
                        <tr>
                            <td class="fw-semibold">{{ $cat->name }}</td>
                            <td><code class="text-muted small">{{ $cat->slug }}</code></td>
                            <td><span class="badge bg-light text-dark border">{{ $cat->products_count }}</span></td>
                            <td>
                                <div class="d-flex gap-1">
                                    {{-- Inline Edit --}}
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editCat{{ $cat->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Delete this category?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Edit Modal --}}
                        <div class="modal fade" id="editCat{{ $cat->id }}" tabindex="-1">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header py-2">
                                        <h6 class="modal-title fw-bold">Edit Category</h6>
                                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.categories.update', $cat) }}">
                                        @csrf @method('PUT')
                                        <div class="modal-body">
                                            <input type="text" name="name" class="form-control" value="{{ $cat->name }}" required>
                                        </div>
                                        <div class="modal-footer py-2">
                                            <button class="btn btn-sm" style="background:var(--gold);color:#fff;">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-5">No categories yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
