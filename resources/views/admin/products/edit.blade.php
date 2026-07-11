@extends('layouts.admin')
@section('title', 'Edit Product')
@section('breadcrumb', 'Edit Product')

@push('styles')
<style>
    .form-label { font-size: .82rem; font-weight: 600; color: #444; margin-bottom: .35rem; }
    .form-control, .form-select { border: 1px solid #e5e5e5; border-radius: 10px; padding: .65rem .9rem; font-size: .88rem; transition: .2s; }
    .form-control:focus, .form-select:focus { border-color: var(--red); box-shadow: 0 0 0 3px rgba(192,57,43,.1); }
    .form-check-input:checked { background-color: var(--red); border-color: var(--red); }
    .section-divider { font-size: .7rem; text-transform: uppercase; letter-spacing: .12em; color: #999; font-weight: 700; padding-bottom: .5rem; border-bottom: 1px solid #f0f0f0; margin-bottom: 1rem; }
    .img-preview { width: 100%; height: 160px; border-radius: 10px; object-fit: cover; border: 1px solid #eee; }
    .img-placeholder { width: 100%; height: 160px; border-radius: 10px; background: #f8f8f8; border: 2px dashed #e0e0e0; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #bbb; cursor: pointer; transition: .2s; }
    .img-placeholder:hover { border-color: var(--red); color: var(--red); }
    .toggle-card { background: #fafafa; border: 1px solid #efefef; border-radius: 12px; padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; }
    .toggle-card label { font-size: .85rem; font-weight: 600; color: #333; cursor: pointer; }
    .toggle-card small { color: #999; font-size: .75rem; }
    .btn-save { background: linear-gradient(135deg, var(--red) 0%, var(--red-light) 100%); color: #fff; border: none; border-radius: 10px; padding: .8rem 2.5rem; font-weight: 700; font-size: .95rem; letter-spacing: .03em; transition: .2s; display: inline-flex; align-items: center; gap: .5rem; }
    .btn-save:hover { background: linear-gradient(135deg, #96281b 0%, var(--red) 100%); color: #fff; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(192,57,43,.35); }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.products') }}" class="btn btn-sm btn-outline-secondary rounded-circle" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-0">Edit Product</h4>
        <p class="text-muted small mb-0">{{ $product->name }}</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row g-4" style="max-width:900px;">

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius:16px;">
                <p class="section-divider">Basic Information</p>
                <div class="mb-3">
                    <label class="form-label">Product Name *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $product->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Category *</label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Select category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">Unisex</option>
                            <option value="men" {{ old('gender', $product->gender) == 'men' ? 'selected' : '' }}>Men</option>
                            <option value="women" {{ old('gender', $product->gender) == 'women' ? 'selected' : '' }}>Women</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius:16px;">
                <p class="section-divider">Pricing & Stock</p>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Price (RWF) *</label>
                        <input type="number" name="price" step="0.01" min="0"
                            class="form-control @error('price') is-invalid @enderror"
                            value="{{ old('price', $product->price) }}" required>
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sale Price (RWF)</label>
                        <input type="number" name="sale_price" step="0.01" min="0"
                            class="form-control" value="{{ old('sale_price', $product->sale_price) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Stock *</label>
                        <input type="number" name="stock" min="0"
                            class="form-control @error('stock') is-invalid @enderror"
                            value="{{ old('stock', $product->stock) }}" required>
                        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm p-4" style="border-radius:16px;">
                <p class="section-divider">Visibility</p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="toggle-card">
                            <div>
                                <label for="featured" class="mb-0">Featured Product</label><br>
                                <small>Show in featured section on homepage</small>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="featured" id="featured" value="1"
                                    {{ old('featured', $product->featured) ? 'checked' : '' }} style="width:2.5rem;height:1.3rem;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="toggle-card">
                            <div>
                                <label for="is_new" class="mb-0">New Arrival</label><br>
                                <small>Show in new arrivals section</small>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="is_new" id="is_new" value="1"
                                    {{ old('is_new', $product->is_new) ? 'checked' : '' }} style="width:2.5rem;height:1.3rem;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius:16px;">
                <p class="section-divider">Product Image</p>
                @if($product->image)
                    <img id="imgPreview" src="{{ $product->image_url }}" class="img-preview mb-2">
                    <div id="imgPlaceholder" class="img-placeholder d-none" onclick="document.getElementById('imageInput').click()">
                @else
                    <img id="imgPreview" class="img-preview d-none mb-2">
                    <div id="imgPlaceholder" class="img-placeholder" onclick="document.getElementById('imageInput').click()">
                @endif
                        <i class="bi bi-cloud-upload" style="font-size:2rem;"></i>
                        <span class="mt-2 small fw-semibold">Click to upload new image</span>
                        <span style="font-size:.72rem;">JPG, PNG, WEBP — max 2MB</span>
                    </div>
                <input type="file" name="image" id="imageInput" accept="image/*" class="d-none" onchange="previewImage(this)">
                <button type="button" class="btn btn-sm btn-outline-secondary mt-2 w-100" id="changeImg" onclick="document.getElementById('imageInput').click()">
                    <i class="bi bi-pencil me-1"></i>Change Image
                </button>
            </div>

            <div class="card border-0 shadow-sm p-4" style="border-radius:16px;">
                <p class="section-divider">Actions</p>
                <button type="submit" class="btn-save w-100 mb-3">
                    <i class="bi bi-check-circle-fill"></i> Save Changes
                </button>
                <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary w-100 rounded-3">
                    <i class="bi bi-x me-1"></i>Cancel
                </a>
            </div>
        </div>

    </div>
</form>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('imgPreview').src = e.target.result;
            document.getElementById('imgPreview').classList.remove('d-none');
            document.getElementById('imgPlaceholder').classList.add('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
