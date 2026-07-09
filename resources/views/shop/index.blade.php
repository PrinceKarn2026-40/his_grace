@extends('layouts.shop')
@section('title', 'Shop - HisGrace Fashion')

@section('content')
<div class="container py-5">
    <div class="row g-4">
        {{-- Sidebar Filters --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-4">
                <h6 class="fw-bold mb-3 text-uppercase" style="letter-spacing:.1em;">Filters</h6>
                <form method="GET" action="{{ route('shop') }}">
                    {{-- Search --}}
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Search</label>
                        <input type="text" name="search" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="Product name...">
                    </div>
                    {{-- Category --}}
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Category</label>
                        @foreach($categories as $cat)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="category" value="{{ $cat->slug }}" id="cat{{ $cat->id }}"
                                {{ request('category') === $cat->slug ? 'checked' : '' }}>
                            <label class="form-check-label small" for="cat{{ $cat->id }}">{{ $cat->name }}</label>
                        </div>
                        @endforeach
                    </div>
                    {{-- Gender --}}
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Gender</label>
                        @foreach(['men' => 'Men', 'women' => 'Women', 'unisex' => 'Unisex'] as $val => $label)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" value="{{ $val }}" id="g{{ $val }}"
                                {{ request('gender') === $val ? 'checked' : '' }}>
                            <label class="form-check-label small" for="g{{ $val }}">{{ $label }}</label>
                        </div>
                        @endforeach
                    </div>
                    {{-- Price --}}
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Price Range</label>
                        <div class="d-flex gap-2">
                            <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min" value="{{ request('min_price') }}">
                            <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max" value="{{ request('max_price') }}">
                        </div>
                    </div>
                    {{-- Sort --}}
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Sort By</label>
                        <select name="sort" class="form-select form-select-sm">
                            <option value="">Featured</option>
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-gold btn-sm w-100 mb-2">Apply Filters</button>
                    <a href="{{ route('shop') }}" class="btn btn-outline-secondary btn-sm w-100">Clear</a>
                </form>
            </div>
        </div>

        {{-- Products Grid --}}
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">{{ $products->total() }} Products Found</h5>
            </div>
            @if($products->count())
                <div class="row g-4">
                    @foreach($products as $product)
                        @include('shop.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
                <div class="mt-4 d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-search fs-1 text-muted"></i>
                    <p class="mt-3 text-muted">No products found. Try adjusting your filters.</p>
                    <a href="{{ route('shop') }}" class="btn btn-gold">View All Products</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
