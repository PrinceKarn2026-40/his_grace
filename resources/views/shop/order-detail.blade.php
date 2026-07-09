@extends('layouts.shop')
@section('title', 'Order #' . $order->id . ' - HisGrace')

@section('content')
<div class="container py-5" style="max-width:800px">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
        <h2 class="brand-font mb-0">Order #{{ $order->id }}</h2>
        @php $colors = ['pending'=>'warning','paid'=>'success','shipped'=>'info','delivered'=>'primary','cancelled'=>'danger']; @endphp
        <span class="badge bg-{{ $colors[$order->status] ?? 'secondary' }} fs-6">{{ ucfirst($order->status) }}</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h6 class="fw-bold mb-3">Items Ordered</h6>
                @foreach($order->items as $item)
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:56px;height:56px;background:#f0ede8;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            @if($item->product?->image)
                                <img src="{{ Storage::url($item->product->image) }}" style="width:56px;height:56px;object-fit:cover;border-radius:8px;">
                            @else
                                <i class="bi bi-image text-muted"></i>
                            @endif
                        </div>
                        <div>
                            <p class="mb-0 fw-semibold small">{{ $item->product->name ?? 'Product' }}</p>
                            <p class="mb-0 text-muted" style="font-size:.75rem;">Qty: {{ $item->quantity }} &times; RWF {{ number_format($item->price, 2) }}</p>
                        </div>
                    </div>
                    <span class="fw-semibold">RWF {{ number_format($item->price * $item->quantity, 2) }}</span>
                </div>
                @endforeach
                <div class="d-flex justify-content-between fw-bold mt-2">
                    <span>Total</span>
                    <span style="color:var(--gold)">RWF {{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-0 shadow-sm p-4 mb-3">
                <h6 class="fw-bold mb-3">Shipping Details</h6>
                <p class="mb-1 fw-semibold">{{ $order->shipping_name }}</p>
                <p class="mb-1 text-muted small">{{ $order->shipping_email }}</p>
                @if($order->shipping_phone)
                    <p class="mb-1 text-muted small">{{ $order->shipping_phone }}</p>
                @endif
                <p class="mb-1 text-muted small">{{ $order->shipping_address }}</p>
                <p class="mb-0 text-muted small">{{ $order->shipping_city }}, {{ $order->shipping_country }}</p>
            </div>

            <div class="card border-0 shadow-sm p-4">
                <h6 class="fw-bold mb-3">Payment</h6>
                <p class="mb-1"><span class="badge bg-secondary">{{ ucfirst(str_replace('_',' ',$order->payment_method)) }}</span></p>
                @if($order->payment_reference)
                    <p class="mb-0 text-muted small">Ref: {{ $order->payment_reference }}</p>
                @endif
                <p class="mb-0 text-muted small mt-1">{{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
