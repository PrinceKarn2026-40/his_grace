@extends('layouts.customer')
@section('title', 'Checkout')
@section('breadcrumb', 'Checkout')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-0">Checkout</h4>
    <p class="text-muted small mb-0">Complete your order</p>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card stat-card shadow-sm p-4">
            <h6 class="fw-bold mb-4"><i class="bi bi-truck me-2" style="color:var(--gold)"></i>Shipping Information</h6>
            <form method="POST" action="{{ route('checkout.store') }}" id="checkoutForm">
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
                        <input type="text" name="shipping_phone" class="form-control" value="{{ old('shipping_phone') }}" placeholder="+233 XX XXX XXXX">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">City *</label>
                        <input type="text" name="shipping_city" class="form-control @error('shipping_city') is-invalid @enderror"
                            value="{{ old('shipping_city') }}" required>
                        @error('shipping_city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Delivery Address *</label>
                        <textarea name="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror"
                            rows="2" required placeholder="Street address, area...">{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Country</label>
                        <input type="text" name="shipping_country" class="form-control" value="Rwanda">
                    </div>
                </div>

                <hr class="my-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-credit-card me-2" style="color:var(--gold)"></i>Payment Method</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="d-block border rounded-3 p-3 cursor-pointer" style="cursor:pointer;" for="pay_card">
                            <div class="d-flex align-items-center gap-2">
                                <input class="form-check-input" type="radio" name="payment_method" value="card" id="pay_card" checked>
                                <div>
                                    <i class="bi bi-credit-card fs-5" style="color:var(--gold)"></i>
                                    <p class="mb-0 fw-semibold small mt-1">Card</p>
                                </div>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label class="d-block border rounded-3 p-3" style="cursor:pointer;" for="pay_momo">
                            <div class="d-flex align-items-center gap-2">
                                <input class="form-check-input" type="radio" name="payment_method" value="mobile_money" id="pay_momo">
                                <div>
                                    <i class="bi bi-phone fs-5" style="color:var(--gold)"></i>
                                    <p class="mb-0 fw-semibold small mt-1">Mobile Money</p>
                                </div>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label class="d-block border rounded-3 p-3" style="cursor:pointer;" for="pay_paypal">
                            <div class="d-flex align-items-center gap-2">
                                <input class="form-check-input" type="radio" name="payment_method" value="paypal" id="pay_paypal">
                                <div>
                                    <i class="bi bi-paypal fs-5" style="color:var(--gold)"></i>
                                    <p class="mb-0 fw-semibold small mt-1">PayPal</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="mt-3 p-3 rounded-3" style="background:#f8f9fa;">
                    <p class="small text-muted mb-0"><i class="bi bi-shield-check me-2 text-success"></i>
                    <strong>Simulated payment</strong> — no real charges will be made.</p>
                </div>

                {{-- Mobile Money Instructions Panel --}}
                <div id="momoPanel" class="mt-3 p-3 rounded-3 d-none" style="background:#fff8e1;border:1px solid #ffc107;">
                    <p class="fw-semibold mb-1 small"><i class="bi bi-phone-fill me-2" style="color:var(--gold)"></i>Mobile Money Payment</p>
                    <p class="small text-muted mb-1">After placing your order, you'll be shown step-by-step instructions to send payment to:</p>
                    <p class="fw-bold mb-1" style="color:var(--gold);font-size:1.1rem;">0795919537 — HisGrace Fashion</p>
                    <p class="small text-muted mb-0">Your order will be confirmed once we verify your payment (within 1–2 hours).</p>
                </div>

                <button type="submit" class="btn w-100 btn-lg mt-4" style="background:var(--gold);color:#fff;">
                    <i class="bi bi-lock me-2"></i>Place Order — RWF {{ number_format($total + ($total >= 500 ? 0 : 20), 2) }}
                </button>
            </form>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card stat-card shadow-sm p-4 sticky-top" style="top:80px;">
            <h6 class="fw-bold mb-4">Order Summary</h6>
            @foreach($items as $item)
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:44px;height:44px;background:#f0ede8;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        @if($item->product->image)
                            <img src="{{ Storage::url($item->product->image) }}" style="width:44px;height:44px;object-fit:cover;border-radius:8px;">
                        @else
                            <i class="bi bi-image text-muted small"></i>
                        @endif
                    </div>
                    <div>
                        <p class="mb-0 fw-semibold small">{{ Str::limit($item->product->name, 22) }}</p>
                        <p class="mb-0 text-muted" style="font-size:.72rem;">Qty: {{ $item->quantity }}</p>
                    </div>
                </div>
                <span class="fw-semibold small">RWF {{ number_format($item->product->effective_price * $item->quantity, 2) }}</span>
            </div>
            @endforeach
            <hr>
            <div class="d-flex justify-content-between mb-1 small">
                <span class="text-muted">Subtotal</span><span>RWF {{ number_format($total, 2) }}</span>
            </div>
            <div class="d-flex justify-content-between mb-1 small">
                <span class="text-muted">Shipping</span>
                <span class="{{ $total >= 500 ? 'text-success' : '' }}">{{ $total >= 500 ? 'Free' : 'RWF 20.00' }}</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between fw-bold">
                <span>Total</span>
                <span style="color:var(--gold)">RWF {{ number_format($total + ($total >= 500 ? 0 : 20), 2) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function () {
        document.getElementById('momoPanel').classList.toggle('d-none', this.value !== 'mobile_money');
    });
});
</script>
@endpush
