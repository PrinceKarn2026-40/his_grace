@extends('layouts.admin')
@section('title', 'Products')
@section('breadcrumb', 'Products')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Product Management</h4>
    <a href="{{ route('admin.products.create') }}" class="btn btn-sm" style="background:var(--gold);color:#fff;">
        <i class="bi bi-plus me-1"></i>Add Product
    </a>
</div>

{{-- Filters --}}
<div class="card shadow-sm border-0 p-3 mb-4">
    <form method="GET" action="{{ route('admin.products') }}" class="row g-2 align-items-end">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name..." value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <select name="category_id" class="form-select form-select-sm">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <select name="upcoming" class="form-select form-select-sm">
                <option value="">All Products</option>
                <option value="1" {{ request('upcoming') == '1' ? 'selected' : '' }}>Upcoming Only</option>
                <option value="0" {{ request('upcoming') == '0' ? 'selected' : '' }}>Available Only</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-sm btn-dark w-100">Filter</button>
            <a href="{{ route('admin.products') }}" class="btn btn-sm btn-outline-secondary w-100">Clear</a>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:44px;height:44px;background:#f0ede8;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                @if($product->image)
                                    <img src="{{ $product->image_url }}" style="width:44px;height:44px;object-fit:cover;border-radius:8px;">
                                @else
                                    <i class="bi bi-image text-muted small"></i>
                                @endif
                            </div>
                            <div>
                                <p class="mb-0 fw-semibold small">{{ $product->name }}</p>
                                <p class="mb-0 text-muted" style="font-size:.72rem;">{{ ucfirst($product->gender ?? 'unisex') }}</p>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge bg-light text-dark border">{{ $product->category->name }}</span></td>
                    <td>
                        <span style="color:var(--gold)" class="fw-semibold">RWF {{ number_format($product->effective_price, 2) }}</span>
                        @if($product->sale_price)
                            <br><small class="text-muted text-decoration-line-through">RWF {{ number_format($product->price, 2) }}</small>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $product->stock > 10 ? 'bg-success' : ($product->stock > 0 ? 'bg-warning text-dark' : 'bg-danger') }}">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td>
                        @if($product->is_upcoming)
                            <span class="badge bg-purple text-white me-1" style="background:#6f42c1!important;">Upcoming</span>
                            <span class="small text-muted">{{ $product->release_date->format('d M Y') }}</span>
                        @else
                            @if($product->featured)<span class="badge badge-gold me-1">Featured</span>@endif
                            @if($product->is_new)<span class="badge bg-info text-dark">New</span>@endif
                            @if(!$product->featured && !$product->is_new)<span class="text-muted small">—</span>@endif
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('shop.show', $product) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Preview"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-5">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3 border-top">{{ $products->links() }}</div>
</div>
@endsection
