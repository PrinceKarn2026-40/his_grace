@extends('layouts.shop')
@section('title', $product->name . ' - HisGrace')

@section('content')
<div class="container py-5">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none" style="color:var(--gold)">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop') }}" class="text-decoration-none" style="color:var(--gold)">Shop</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        {{-- Product Image --}}
        <div class="col-md-5">
            <div class="rounded-3 overflow-hidden" style="background:#f0ede8; min-height:450px; display:flex; align-items:center; justify-content:center;">
                @if($product->image)
                    <img src="{{ $product->image_url }}" class="img-fluid" alt="{{ $product->name }}">
                @else
                    <i class="bi bi-image text-muted" style="font-size:6rem;"></i>
                @endif
            </div>
        </div>

        {{-- Product Info --}}
        <div class="col-md-7">
            <p class="text-uppercase small mb-1" style="color:var(--gold); letter-spacing:.1em;">{{ $product->category->name }}</p>
            <h1 class="brand-font h2 mb-2">{{ $product->name }}</h1>

            {{-- Rating --}}
            <div class="d-flex align-items-center gap-2 mb-3">
                @for($i = 1; $i <= 5; $i++)
                    <i class="bi bi-star{{ $i <= $product->average_rating ? '-fill star-filled' : ' star-empty' }}"></i>
                @endfor
                <span class="text-muted small">({{ $product->reviews->count() }} reviews)</span>
            </div>

            {{-- Price --}}
            <div class="mb-4">
                @if($product->sale_price)
                    <span class="fs-3 fw-bold price-tag">RWF {{ number_format($product->sale_price, 2) }}</span>
                    <span class="price-original ms-2 fs-5">RWF {{ number_format($product->price, 2) }}</span>
                    <span class="badge badge-sale ms-2">{{ round((1 - $product->sale_price/$product->price)*100) }}% OFF</span>
                @else
                    <span class="fs-3 fw-bold price-tag">RWF {{ number_format($product->price, 2) }}</span>
                @endif
            </div>

            <p class="text-muted mb-4">{{ $product->description }}</p>

            {{-- Stock --}}
            <p class="mb-3">
                @if($product->stock > 0)
                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>In Stock ({{ $product->stock }} left)</span>
                @else
                    <span class="badge bg-danger">Out of Stock</span>
                @endif
            </p>

            {{-- Add to Cart --}}
            @if($product->stock > 0)
            @auth
            <form method="POST" action="{{ route('cart.add', $product) }}" class="d-flex gap-3 mb-3">
                @csrf
                <button class="btn btn-gold btn-lg px-5">
                    <i class="bi bi-bag-plus me-2"></i>Add to Cart
                </button>
            </form>
            @else
            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg px-5 mb-3">
                <i class="bi bi-lock me-2"></i>Login to Add to Cart
            </a>
            @endauth
            @endif

            {{-- Wishlist --}}
            @auth
            <form method="POST" action="{{ route('wishlist.toggle', $product) }}">
                @csrf
                <button class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-heart{{ $inWishlist ? '-fill text-danger' : '' }} me-1"></i>
                    {{ $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}
                </button>
            </form>
            @endauth

            <hr class="my-4">
            <div class="d-flex gap-4 text-muted small">
                <span><i class="bi bi-truck me-1"></i>Free shipping over RWF 500</span>
                <span><i class="bi bi-arrow-return-left me-1"></i>30-day returns</span>
            </div>
        </div>
    </div>

    {{-- Reviews --}}
    <div class="row mt-5">
        <div class="col-md-8">
            <h4 class="brand-font mb-4">Customer Reviews</h4>

            @auth
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h6 class="fw-semibold mb-3">Write a Review</h6>
                <form method="POST" action="{{ route('reviews.store', $product) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small">Rating</label>
                        <div class="d-flex gap-2">
                            @for($i = 1; $i <= 5; $i++)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="rating" value="{{ $i }}" id="r{{ $i }}" required>
                                <label class="form-check-label" for="r{{ $i }}">{{ $i }}★</label>
                            </div>
                            @endfor
                        </div>
                    </div>
                    <div class="mb-3">
                        <textarea name="comment" class="form-control" rows="3" placeholder="Share your experience..."></textarea>
                    </div>
                    <button class="btn btn-gold btn-sm">Submit Review</button>
                </form>
            </div>
            @endauth

            @forelse($product->reviews as $review)
            <div class="border-bottom pb-3 mb-3">
                <div class="d-flex justify-content-between">
                    <strong>{{ $review->user->name }}</strong>
                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                </div>
                <div class="my-1">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill star-filled' : ' star-empty' }}" style="font-size:.8rem;"></i>
                    @endfor
                </div>
                <p class="mb-0 text-muted small">{{ $review->comment }}</p>
            </div>
            @empty
            <p class="text-muted">No reviews yet. Be the first to review!</p>
            @endforelse
        </div>
    </div>

    {{-- AI Recommendations --}}
    @if($recommended->count())
    <div class="mt-5">
        <h4 class="brand-font mb-4">You May Also Like <span class="badge bg-secondary fs-6 ms-2" style="font-size:.7rem!important; vertical-align:middle;"><i class="bi bi-stars me-1"></i>AI Picks</span></h4>
        <div class="row g-4">
            @foreach($recommended as $rec)
                @include('shop.partials.product-card', ['product' => $rec])
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
