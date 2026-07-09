@extends('layouts.customer')
@section('title', 'Mobile Money Payment')
@section('breadcrumb', 'Pay with Mobile Money')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">

        {{-- Success Banner --}}
        <div class="alert border-0 mb-4 d-flex align-items-center gap-3 p-4 rounded-3"
             style="background:linear-gradient(135deg,#1a472a,#2d6a4f);color:#fff;">
            <i class="bi bi-check-circle-fill fs-2"></i>
            <div>
                <h5 class="mb-0 fw-bold">Order #{{ $order->id }} Placed!</h5>
                <p class="mb-0 small opacity-75">Complete your payment below to confirm your order.</p>
            </div>
        </div>

        {{-- Payment Instructions --}}
        <div class="card stat-card shadow-sm p-4 mb-4">
            <h5 class="fw-bold mb-1"><i class="bi bi-phone-fill me-2" style="color:var(--gold)"></i>Mobile Money Payment Instructions</h5>
            <p class="text-muted small mb-4">Follow these steps carefully to complete your payment.</p>

            <div class="d-flex flex-column gap-3">
                <div class="d-flex gap-3 align-items-start p-3 rounded-3" style="background:#f8f9fa;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                         style="width:36px;height:36px;background:var(--gold);color:#fff;font-size:.9rem;">1</div>
                    <div>
                        <p class="mb-0 fw-semibold">Open your Mobile Money app</p>
                        <p class="mb-0 text-muted small">MTN MoMo, Telecel Cash, or AirtelTigo Money</p>
                    </div>
                </div>

                <div class="d-flex gap-3 align-items-start p-3 rounded-3" style="background:#f8f9fa;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                         style="width:36px;height:36px;background:var(--gold);color:#fff;font-size:.9rem;">2</div>
                    <div>
                        <p class="mb-0 fw-semibold">Send money to this number</p>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span class="fs-4 fw-bold" style="color:var(--gold);letter-spacing:2px;" id="momoNumber">0795919537</span>
                            <button class="btn btn-sm btn-outline-secondary" onclick="copyNumber()" title="Copy number">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                        <p class="mb-0 text-muted small mt-1">Account name: <strong>HisGrace Fashion</strong></p>
                    </div>
                </div>

                <div class="d-flex gap-3 align-items-start p-3 rounded-3" style="background:#fff3cd;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                         style="width:36px;height:36px;background:#ffc107;color:#fff;font-size:.9rem;">3</div>
                    <div>
                        <p class="mb-0 fw-semibold">Send exactly this amount</p>
                        <p class="fs-3 fw-bold mb-0" style="color:var(--gold)">RWF {{ number_format($order->total, 2) }}</p>
                        <p class="mb-0 text-muted small">Order reference: <strong>{{ $order->payment_reference }}</strong></p>
                    </div>
                </div>

                <div class="d-flex gap-3 align-items-start p-3 rounded-3" style="background:#f8f9fa;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                         style="width:36px;height:36px;background:var(--gold);color:#fff;font-size:.9rem;">4</div>
                    <div>
                        <p class="mb-0 fw-semibold">Submit your payment details below</p>
                        <p class="mb-0 text-muted small">Enter the number you sent from and the transaction ID from your SMS confirmation.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Confirm Payment Form --}}
        <div class="card stat-card shadow-sm p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-send-check me-2" style="color:var(--gold)"></i>Submit Payment Confirmation</h6>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('checkout.momo.confirm', $order->id) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Your MoMo Number (number you sent from) *</label>
                    <input type="text" name="sender_number" class="form-control @error('sender_number') is-invalid @enderror"
                           placeholder="e.g. 0551234567" value="{{ old('sender_number') }}" required>
                    @error('sender_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-semibold">Transaction ID (from your SMS) *</label>
                    <input type="text" name="transaction_id" class="form-control @error('transaction_id') is-invalid @enderror"
                           placeholder="e.g. A123456789" value="{{ old('transaction_id') }}" required>
                    @error('transaction_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Check your SMS inbox for the MoMo confirmation message.</div>
                </div>
                <button type="submit" class="btn w-100 btn-lg" style="background:var(--gold);color:#fff;">
                    <i class="bi bi-check2-circle me-2"></i>I Have Sent the Payment
                </button>
            </form>

            <div class="mt-3 p-3 rounded-3 d-flex gap-2 align-items-start" style="background:#e8f4fd;">
                <i class="bi bi-info-circle-fill text-primary mt-1"></i>
                <p class="mb-0 small text-muted">Your order will be confirmed within <strong>1–2 hours</strong> after we verify your payment. You'll receive an email notification once confirmed.</p>
            </div>
        </div>

    </div>
</div>

<script>
function copyNumber() {
    navigator.clipboard.writeText(document.getElementById('momoNumber').textContent.trim());
    const btn = event.currentTarget;
    btn.innerHTML = '<i class="bi bi-check2"></i>';
    setTimeout(() => btn.innerHTML = '<i class="bi bi-clipboard"></i>', 2000);
}
</script>
@endsection
