@extends('layouts.shop')
@section('title', 'Shopping Cart - HisGrace')

@section('content')
<div class="container py-5">
    <h2 class="brand-font mb-4">Shopping Cart</h2>

    @if($items->count())
    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    @foreach($items as $item)
                    <div class="d-flex align-items-center p-4 border-bottom gap-3">
                        <div style="width:80px; height:80px; background:#f0ede8; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            @if($item->product->image)
                                <img src="{{ Storage::url($item->product->image) }}" style="width:80px;height:80px;object-fit:cover;border-radius:8px;" alt="">
                            @else
                                <i class="bi bi-image text-muted"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-semibold">{{ $item->product->name }}</h6>
                            <p class="text-muted small mb-0">{{ $item->product->category->name }}</p>
                            <span class="price-tag fw-semibold">RWF {{ number_format($item->product->effective_price, 2) }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <form method="POST" action="{{ route('cart.update', $item) }}">
                                @csrf @method('PATCH')
                                <div class="input-group input-group-sm" style="width:110px;">
                                    <button type="button" class="btn btn-outline-secondary" onclick="this.nextElementSibling.stepDown(); this.closest('form').submit()">-</button>
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control text-center" onchange="this.form.submit()">
                                    <button type="button" class="btn btn-outline-secondary" onclick="this.previousElementSibling.stepUp(); this.closest('form').submit()">+</button>
                                </div>
                            </form>
                            <span class="fw-bold" style="min-width:80px; text-align:right;">RWF {{ number_format($item->product->effective_price * $item->quantity, 2) }}</span>
                            <form method="POST" action="{{ route('cart.remove', $item) }}">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4">
                <h5 class="fw-bold mb-4">Order Summary</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span>RWF {{ number_format($total, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Shipping</span>
                    <span class="text-success">{{ $total >= 500 ? 'Free' : 'RWF 20.00' }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                    <span>Total</span>
                    <span style="color:var(--gold)">RWF {{ number_format($total + ($total >= 500 ? 0 : 20), 2) }}</span>
                </div>
                @auth
                    <a href="{{ route('checkout') }}" class="btn btn-gold w-100 btn-lg">Proceed to Checkout</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-gold w-100 btn-lg">Login to Checkout</a>
                @endauth
                <a href="{{ route('shop') }}" class="btn btn-outline-secondary w-100 mt-2">Continue Shopping</a>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <i class="bi bi-bag fs-1 text-muted"></i>
        <h4 class="mt-3">Your cart is empty</h4>
        <p class="text-muted">Add some products to get started!</p>
        <a href="{{ route('shop') }}" class="btn btn-gold btn-lg px-5">Shop Now</a>
    </div>
    @endif
</div>
@endsection
