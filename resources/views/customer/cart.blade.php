@extends('layouts.customer')
@section('title', 'My Cart')
@section('breadcrumb', 'My Cart')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Shopping Cart</h4>
    <a href="{{ route('shop') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Continue Shopping</a>
</div>

@if($items->count())
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card stat-card shadow-sm">
            @foreach($items as $item)
            <div class="d-flex align-items-center p-4 border-bottom gap-3">
                <div style="width:72px;height:72px;background:#f0ede8;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    @if($item->product->image)
                        <img src="{{ Storage::url($item->product->image) }}" style="width:72px;height:72px;object-fit:cover;border-radius:10px;">
                    @else
                        <i class="bi bi-image text-muted"></i>
                    @endif
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0 fw-semibold">{{ $item->product->name }}</h6>
                    <p class="text-muted small mb-1">{{ $item->product->category->name }}</p>
                    <span class="fw-semibold" style="color:var(--gold)">RWF {{ number_format($item->product->effective_price, 2) }}</span>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                    <form method="POST" action="{{ route('cart.update', $item) }}">
                        @csrf @method('PATCH')
                        <div class="input-group input-group-sm" style="width:110px;">
                            <button type="button" class="btn btn-outline-secondary" onclick="this.nextElementSibling.stepDown();this.closest('form').submit()">−</button>
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control text-center" onchange="this.form.submit()">
                            <button type="button" class="btn btn-outline-secondary" onclick="this.previousElementSibling.stepUp();this.closest('form').submit()">+</button>
                        </div>
                    </form>
                    <span class="fw-bold" style="min-width:80px;text-align:right;">RWF {{ number_format($item->product->effective_price * $item->quantity, 2) }}</span>
                    <form method="POST" action="{{ route('cart.remove', $item) }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card stat-card shadow-sm p-4">
            <h6 class="fw-bold mb-4">Order Summary</h6>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Subtotal ({{ $items->sum('quantity') }} items)</span>
                <span>RWF {{ number_format($total, 2) }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Shipping</span>
                <span class="{{ $total >= 500 ? 'text-success' : '' }}">{{ $total >= 500 ? 'Free' : 'RWF 20.00' }}</span>
            </div>
            @if($total < 500)
            <div class="alert py-2 px-3 mb-2" style="background:#c9a84c15;border:1px solid #c9a84c33;font-size:.78rem;">
                <i class="bi bi-truck me-1" style="color:var(--gold)"></i>Add RWF {{ number_format(500 - $total, 2) }} more for free shipping!
            </div>
            @endif
            <hr>
            <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                <span>Total</span>
                <span style="color:var(--gold)">RWF {{ number_format($total + ($total >= 500 ? 0 : 20), 2) }}</span>
            </div>
            <a href="{{ route('checkout') }}" class="btn w-100 mb-2" style="background:var(--gold);color:#fff;">
                <i class="bi bi-lock me-1"></i>Proceed to Checkout
            </a>
            <a href="{{ route('shop') }}" class="btn btn-outline-secondary w-100">Continue Shopping</a>
        </div>
    </div>
</div>
@else
<div class="card stat-card shadow-sm p-5 text-center">
    <i class="bi bi-bag" style="font-size:3.5rem;color:#ddd;"></i>
    <h5 class="mt-3 fw-bold">Your cart is empty</h5>
    <p class="text-muted">Looks like you haven't added anything yet.</p>
    <a href="{{ route('shop') }}" class="btn px-5 mt-2" style="background:var(--gold);color:#fff;">Start Shopping</a>
</div>
@endif
@endsection
