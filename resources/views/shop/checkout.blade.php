@extends('layouts.shop')
@section('title', 'Checkout - HisGrace')

@section('content')
<div class="container py-5">
    <h2 class="brand-font mb-4">Checkout</h2>
    <div class="row g-4">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm p-4">
                <h5 class="fw-bold mb-4">Shipping Information</h5>
                <form method="POST" action="{{ route('checkout.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Full Name *</label>
                            <input type="text" name="shipping_name" class="form-control @error('shipping_name') is-invalid @enderror"
                                value="{{ old('shipping_name', auth()->user()->name) }}" required>
                            @error('shipping_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Email *</label>
                            <input type="email" name="shipping_email" class="form-control @error('shipping_email') is-invalid @enderror"
                                value="{{ old('shipping_email', auth()->user()->email) }}" required>
                            @error('shipping_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Phone</label>
                            <input type="text" name="shipping_phone" class="form-control" value="{{ old('shipping_phone') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">City *</label>
                            <input type="text" name="shipping_city" class="form-control @error('shipping_city') is-invalid @enderror"
                                value="{{ old('shipping_city') }}" required>
                            @error('shipping_city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Address *</label>
                            <textarea name="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror" rows="2" required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Country</label>
                            <input type="text" name="shipping_country" class="form-control" value="Rwanda">
                        </div>
                    </div>

                    <hr class="my-4">
                    <h5 class="fw-bold mb-3">Payment Method</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-check border rounded-3 p-3">
                                <input class="form-check-input" type="radio" name="payment_method" value="card" id="card" checked>
                                <label class="form-check-label w-100" for="card">
                                    <i class="bi bi-credit-card me-2" style="color:var(--gold)"></i>
                                    <span class="fw-semibold">Card</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check border rounded-3 p-3">
                                <input class="form-check-input" type="radio" name="payment_method" value="mobile_money" id="momo">
                                <label class="form-check-label w-100" for="momo">
                                    <i class="bi bi-phone me-2" style="color:var(--gold)"></i>
                                    <span class="fw-semibold">Mobile Money</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check border rounded-3 p-3">
                                <input class="form-check-input" type="radio" name="payment_method" value="paypal" id="paypal">
                                <label class="form-check-label w-100" for="paypal">
                                    <i class="bi bi-paypal me-2" style="color:var(--gold)"></i>
                                    <span class="fw-semibold">PayPal</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 rounded-3" style="background:#f8f9fa;">
                        <p class="small text-muted mb-0"><i class="bi bi-shield-check me-2 text-success"></i>
                        This is a <strong>simulated payment</strong> for demonstration. No real charges will be made.</p>
                    </div>

                    <button type="submit" class="btn btn-gold btn-lg w-100 mt-4">
                        <i class="bi bi-lock me-2"></i>Place Order — RWF {{ number_format($total + ($total >= 500 ? 0 : 20), 2) }}
                    </button>
                </form>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-0 shadow-sm p-4">
                <h5 class="fw-bold mb-4">Order Summary</h5>
                @foreach($items as $item)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <p class="mb-0 fw-semibold small">{{ $item->product->name }}</p>
                        <p class="mb-0 text-muted" style="font-size:.75rem;">Qty: {{ $item->quantity }}</p>
                    </div>
                    <span class="fw-semibold">RWF {{ number_format($item->product->effective_price * $item->quantity, 2) }}</span>
                </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Subtotal</span>
                    <span>RWF {{ number_format($total, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Shipping</span>
                    <span class="text-success">{{ $total >= 500 ? 'Free' : 'RWF 20.00' }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold fs-5">
                    <span>Total</span>
                    <span style="color:var(--gold)">RWF {{ number_format($total + ($total >= 500 ? 0 : 20), 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
