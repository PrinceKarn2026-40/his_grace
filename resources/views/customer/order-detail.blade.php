@extends('layouts.customer')
@section('title', 'Order #' . $order->id)
@section('breadcrumb', 'Order #' . $order->id)

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0">Order #{{ $order->id }}</h4>
    @php $colors = ['pending'=>'warning','paid'=>'success','shipped'=>'info','delivered'=>'primary','cancelled'=>'danger']; @endphp
    <span class="badge bg-{{ $colors[$order->status] ?? 'secondary' }}">{{ ucfirst($order->status) }}</span>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        {{-- Items --}}
        <div class="card stat-card shadow-sm p-4 mb-4">
            <h6 class="fw-bold mb-3">Items Ordered</h6>
            @foreach($order->items as $item)
            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:56px;height:56px;background:#f0ede8;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        @if($item->product?->image)
                            <img src="{{ Storage::url($item->product->image) }}" style="width:56px;height:56px;object-fit:cover;border-radius:10px;">
                        @else
                            <i class="bi bi-image text-muted"></i>
                        @endif
                    </div>
                    <div>
                        <p class="mb-0 fw-semibold">{{ $item->product->name ?? 'Product' }}</p>
                        <p class="mb-0 text-muted small">Qty: {{ $item->quantity }} × RWF {{ number_format($item->price, 2) }}</p>
                        @if($item->product)
                            <a href="{{ route('shop.show', $item->product) }}" class="text-decoration-none small" style="color:var(--gold)">View product →</a>
                        @endif
                    </div>
                </div>
                <span class="fw-bold">RWF {{ number_format($item->price * $item->quantity, 2) }}</span>
            </div>
            @endforeach
            <div class="d-flex justify-content-between fw-bold mt-1 pt-1">
                <span>Order Total</span>
                <span style="color:var(--gold)">RWF {{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        {{-- Track Order --}}
        <div class="card stat-card shadow-sm p-4">
            <h6 class="fw-bold mb-4"><i class="bi bi-geo-alt me-2" style="color:var(--gold)"></i>Order Tracking</h6>
            @php
                $steps = [
                    'pending'   => ['Order Placed',      'Your order has been received.',              'bi-bag-check'],
                    'paid'      => ['Payment Confirmed',  'Payment has been confirmed.',                'bi-credit-card'],
                    'shipped'   => ['Order Shipped',      'Your order is on its way.',                  'bi-truck'],
                    'delivered' => ['Order Delivered',    'Your order has been delivered.',             'bi-house-check'],
                ];
                $statusOrder = ['pending', 'paid', 'shipped', 'delivered'];
                $currentIndex = array_search($order->status, $statusOrder);
                if ($order->status === 'cancelled') $currentIndex = -1;
            @endphp

            @if($order->status === 'cancelled')
                <div class="alert alert-danger"><i class="bi bi-x-circle me-2"></i>This order has been cancelled.</div>
            @else
            <div class="position-relative">
                @foreach($steps as $key => $step)
                @php
                    $stepIndex = array_search($key, $statusOrder);
                    $done = $stepIndex <= $currentIndex;
                    $active = $stepIndex === $currentIndex;
                @endphp
                <div class="d-flex gap-3 mb-4">
                    <div class="d-flex flex-column align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                            style="width:40px;height:40px;flex-shrink:0;
                            background:{{ $done ? 'var(--gold)' : '#e9ecef' }};
                            color:{{ $done ? '#fff' : '#aaa' }};">
                            <i class="bi {{ $step[2] }}"></i>
                        </div>
                        @if(!$loop->last)
                            <div style="width:2px;height:30px;background:{{ $done ? 'var(--gold)' : '#e9ecef' }};margin-top:4px;"></div>
                        @endif
                    </div>
                    <div class="pt-1">
                        <p class="mb-0 fw-semibold {{ $active ? '' : ($done ? '' : 'text-muted') }}">{{ $step[0] }}</p>
                        <p class="mb-0 small text-muted">{{ $step[1] }}</p>
                        @if($active)
                            <span class="badge mt-1" style="background:var(--gold)">Current Status</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card stat-card shadow-sm p-4 mb-3">
            <h6 class="fw-bold mb-3">Shipping Address</h6>
            <p class="mb-1 fw-semibold">{{ $order->shipping_name }}</p>
            <p class="mb-1 text-muted small">{{ $order->shipping_email }}</p>
            @if($order->shipping_phone)<p class="mb-1 text-muted small">{{ $order->shipping_phone }}</p>@endif
            <p class="mb-1 text-muted small">{{ $order->shipping_address }}</p>
            <p class="mb-0 text-muted small">{{ $order->shipping_city }}, {{ $order->shipping_country }}</p>
        </div>

        <div class="card stat-card shadow-sm p-4">
            <h6 class="fw-bold mb-3">Payment Info</h6>
            <p class="mb-1"><span class="badge bg-secondary">{{ ucfirst(str_replace('_',' ',$order->payment_method)) }}</span></p>
            @if($order->payment_reference)
                <p class="mb-1 text-muted small">Ref: {{ $order->payment_reference }}</p>
            @endif
            <p class="mb-0 text-muted small">{{ $order->created_at->format('M d, Y h:i A') }}</p>
        </div>
    </div>
</div>
@endsection
