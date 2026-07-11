<div class="col-6 col-md-3">
    <div class="card product-card h-100 rounded-3 overflow-hidden">
        <div class="position-relative">
            @if($product->image)
                <img src="{{ $product->image_url }}" class="card-img-top product-img" alt="{{ $product->name }}">
            @else
                <div class="product-img-placeholder">
                    <i class="bi bi-image text-muted" style="font-size:3rem;"></i>
                </div>
            @endif
            <div class="position-absolute top-0 start-0 p-2 d-flex flex-column gap-1">
                @if($product->is_new)
                    <span class="badge badge-new">NEW</span>
                @endif
                @if($product->sale_price)
                    <span class="badge badge-sale">SALE</span>
                @endif
            </div>
            @auth
            <div class="position-absolute top-0 end-0 p-2">
                <form method="POST" action="{{ route('wishlist.toggle', $product) }}">
                    @csrf
                    <button class="wishlist-btn">
                        <i class="bi bi-heart{{ auth()->user()->wishlists()->where('product_id',$product->id)->exists() ? '-fill' : '' }}"
                           style="font-size:.85rem;color:{{ auth()->user()->wishlists()->where('product_id',$product->id)->exists() ? 'var(--red)' : '#aaa' }};"></i>
                    </button>
                </form>
            </div>
            @endauth
        </div>
        <div class="card-body p-3 d-flex flex-column">
            <p class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.06em;">
                {{ $product->category->name }}
            </p>
            <h6 class="card-title mb-2 fw-semibold flex-grow-1">
                <a href="{{ route('shop.show', $product) }}" class="text-dark text-decoration-none">{{ $product->name }}</a>
            </h6>
            <div class="d-flex align-items-center gap-2 mb-3">
                @if($product->sale_price)
                    <span class="price-tag">RWF {{ number_format($product->sale_price, 0) }}</span>
                    <span class="price-original">RWF {{ number_format($product->price, 0) }}</span>
                @else
                    <span class="price-tag">RWF {{ number_format($product->price, 0) }}</span>
                @endif
            </div>
            @auth
            <form method="POST" action="{{ route('cart.add', $product) }}">
                @csrf
                <button class="btn btn-red btn-sm w-100 rounded-pill">
                    <i class="bi bi-bag-plus me-1"></i>Add to Cart
                </button>
            </form>
            @else
            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm w-100 rounded-pill">
                <i class="bi bi-lock me-1"></i>Login to Add
            </a>
            @endauth
        </div>
    </div>
</div>
