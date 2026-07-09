@php $p = $product ?? null; @endphp

<div class="row g-3">
    <div class="col-12">
        <label class="form-label small fw-semibold">Product Name *</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $p?->name) }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label small fw-semibold">Category *</label>
        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
            <option value="">Select category</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id', $p?->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label small fw-semibold">Gender</label>
        <select name="gender" class="form-select">
            <option value="">Unisex</option>
            <option value="men" {{ old('gender', $p?->gender) == 'men' ? 'selected' : '' }}>Men</option>
            <option value="women" {{ old('gender', $p?->gender) == 'women' ? 'selected' : '' }}>Women</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label small fw-semibold">Price (RWF) *</label>
        <input type="number" name="price" step="0.01" class="form-control @error('price') is-invalid @enderror"
            value="{{ old('price', $p?->price) }}" required>
        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label small fw-semibold">Sale Price (RWF)</label>
        <input type="number" name="sale_price" step="0.01" class="form-control"
            value="{{ old('sale_price', $p?->sale_price) }}">
    </div>

    <div class="col-md-6">
        <label class="form-label small fw-semibold">Stock *</label>
        <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
            value="{{ old('stock', $p?->stock ?? 0) }}" required>
        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label small fw-semibold">Product Image</label>
        <input type="file" name="image" class="form-control" accept="image/*">
        @if($p?->image)
            <img src="{{ Storage::url($p->image) }}" height="60" class="mt-2 rounded">
        @endif
    </div>

    <div class="col-12">
        <label class="form-label small fw-semibold">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $p?->description) }}</textarea>
    </div>

    <div class="col-md-6">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="featured" id="featured" value="1"
                {{ old('featured', $p?->featured) ? 'checked' : '' }}>
            <label class="form-check-label" for="featured">Featured Product</label>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_new" id="is_new" value="1"
                {{ old('is_new', $p?->is_new) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_new">New Arrival</label>
        </div>
    </div>

    <div class="col-12">
        <hr class="my-1">
        <label class="form-label small fw-semibold">Release Date <span class="text-muted fw-normal">(leave blank for immediate availability)</span></label>
        <input type="date" name="release_date" class="form-control"
            value="{{ old('release_date', $p?->release_date?->format('Y-m-d')) }}"
            min="{{ today()->addDay()->format('Y-m-d') }}">
        <div class="form-text"><i class="bi bi-calendar-event me-1" style="color:var(--gold)"></i>Set a future date to list this as a <strong>Coming Soon</strong> product. It will become purchasable on the release date.</div>
    </div>
</div>
