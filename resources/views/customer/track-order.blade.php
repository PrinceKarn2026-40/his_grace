@extends('layouts.customer')
@section('title', 'Track Order')
@section('breadcrumb', 'Track Order')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-0">Track Your Order</h4>
    <p class="text-muted small mb-0">Enter your order ID to see the current status</p>
</div>

{{-- Search --}}
<div class="card stat-card shadow-sm p-4 mb-4" style="max-width:500px">
    <form method="GET" action="{{ route('customer.track') }}" class="d-flex gap-2">
        <input type="number" name="order_id" class="form-control" placeholder="Enter Order ID e.g. 12"
            value="{{ request('order_id') }}" required>
        <button class="btn px-4" style="background:var(--gold);color:#fff;white-space:nowrap;">
            <i class="bi bi-search me-1"></i>Track
        </button>
    </form>
</div>

@if($order)
@php
    $colors = ['pending'=>'warning','paid'=>'success','shipped'=>'info','delivered'=>'primary','cancelled'=>'danger'];
    $steps = [
        'pending'   => ['Order Placed',       'We have received your order.',          'bi-bag-check-fill'],
        'paid'      => ['Payment Confirmed',   'Your payment has been confirmed.',      'bi-credit-card-fill'],
        'shipped'   => ['Order Shipped',       'Your order is on its way to you.',      'bi-truck'],
        'delivered' => ['Order Delivered',     'Your order has been delivered.',        'bi-house-check-fill'],
    ];
    $statusOrder = ['pending', 'paid', 'shipped', 'delivered'];
    $currentIndex = array_search($order->status, $statusOrder);
    if ($order->status === 'cancelled') $currentIndex = -1;
@endphp

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card stat-card shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h5 class="fw-bold mb-1">Order #{{ $order->id }}</h5>
                    <p class="text-muted small mb-0">Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
                </div>
                <span class="badge bg-{{ $colors[$order->status] ?? 'secondary' }} fs-6">{{ ucfirst($order->status) }}</span>
            </div>

            @if($order->status === 'cancelled')
                <div class="alert alert-danger"><i class="bi bi-x-circle me-2"></i>This order has been cancelled.</div>
            @else
            {{-- Progress Bar --}}
            @php $progress = $currentIndex >= 0 ? (($currentIndex + 1) / count($steps)) * 100 : 0; @endphp
            <div class="progress mb-4" style="height:8px;border-radius:4px;">
                <div class="progress-bar" style="width:{{ $progress }}%;background:var(--gold);transition:width .5s;"></div>
            </div>

            {{-- Steps --}}
            <div class="row g-3 mb-4">
                @foreach($steps as $key => $step)
                @php
                    $stepIndex = array_search($key, $statusOrder);
                    $done   = $stepIndex <= $currentIndex;
                    $active = $stepIndex === $currentIndex;
                @endphp
                <div class="col-6 col-md-3 text-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2"
                        style="width:52px;height:52px;
                        background:{{ $done ? 'var(--gold)' : '#e9ecef' }};
                        color:{{ $done ? '#fff' : '#aaa' }};font-size:1.3rem;">
                        <i class="bi {{ $step[2] }}"></i>
                    </div>
                    <p class="mb-0 fw-semibold small {{ $done ? '' : 'text-muted' }}">{{ $step[0] }}</p>
                    @if($active)<span class="badge mt-1" style="background:var(--gold);font-size:.65rem;">Now</span>@endif
                </div>
                @endforeach
            </div>
            @endif

            {{-- Items --}}
            <h6 class="fw-bold mb-3">Items in this Order</h6>
            @foreach($order->items as $item)
            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:40px;height:40px;background:#f0ede8;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        @if($item->product?->image)
                            <img src="{{ Storage::url($item->product->image) }}" style="width:40px;height:40px;object-fit:cover;border-radius:8px;">
                        @else
                            <i class="bi bi-image text-muted small"></i>
                        @endif
                    </div>
                    <span class="small fw-semibold">{{ $item->product->name ?? 'Product' }}</span>
                </div>
                <span class="small text-muted">×{{ $item->quantity }} — RWF {{ number_format($item->price * $item->quantity, 2) }}</span>
            </div>
            @endforeach
            <div class="d-flex justify-content-between fw-bold mt-2">
                <span>Total</span>
                <span style="color:var(--gold)">RWF {{ number_format($order->total, 2) }}</span>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card stat-card shadow-sm p-4 mb-3">
            <h6 class="fw-bold mb-3">Shipping To</h6>
            <p class="mb-1 fw-semibold">{{ $order->shipping_name }}</p>
            <p class="mb-1 text-muted small">{{ $order->shipping_address }}</p>
            <p class="mb-0 text-muted small">{{ $order->shipping_city }}, {{ $order->shipping_country }}</p>
        </div>
        <div class="card stat-card shadow-sm p-4">
            <h6 class="fw-bold mb-3">Payment</h6>
            <span class="badge bg-secondary">{{ ucfirst(str_replace('_',' ',$order->payment_method)) }}</span>
            @if($order->payment_reference)
                <p class="mb-0 text-muted small mt-2">Ref: {{ $order->payment_reference }}</p>
            @endif
        </div>
        <a href="{{ route('orders.show', $order) }}" class="btn w-100 mt-3 btn-outline-secondary">
            <i class="bi bi-eye me-1"></i>Full Order Details
        </a>
    </div>
</div>
@endif
@endsection
