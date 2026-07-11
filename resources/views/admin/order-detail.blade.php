@extends('layouts.admin')
@section('title', 'Order #' . $order->id)
@section('breadcrumb', 'Order #' . $order->id)

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0">Order #{{ $order->id }}</h4>
    @php $colors = ['pending'=>'warning','paid'=>'success','shipped'=>'info','delivered'=>'primary','cancelled'=>'danger']; @endphp
    <span class="badge bg-{{ $colors[$order->status] ?? 'secondary' }} fs-6">{{ ucfirst($order->status) }}</span>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        {{-- Items --}}
        <div class="card shadow-sm border-0 p-4 mb-4">
            <h6 class="fw-bold mb-3">Order Items</h6>
            @foreach($order->items as $item)
            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:52px;height:52px;background:#f0ede8;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        @if($item->product?->image)
                            <img src="{{ $item->product->image_url }}" style="width:52px;height:52px;object-fit:cover;border-radius:8px;">
                        @else
                            <i class="bi bi-image text-muted"></i>
                        @endif
                    </div>
                    <div>
                        <p class="mb-0 fw-semibold small">{{ $item->product->name ?? 'Deleted Product' }}</p>
                        <p class="mb-0 text-muted" style="font-size:.72rem;">Qty: {{ $item->quantity }} × RWF {{ number_format($item->price, 2) }}</p>
                    </div>
                </div>
                <span class="fw-semibold">RWF {{ number_format($item->price * $item->quantity, 2) }}</span>
            </div>
            @endforeach
            <div class="d-flex justify-content-between fw-bold mt-1">
                <span>Order Total</span>
                <span style="color:var(--gold)">RWF {{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        {{-- Update Status --}}
        <div class="card shadow-sm border-0 p-4">
            <h6 class="fw-bold mb-3">Update Status</h6>
            <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="d-flex gap-2">
                @csrf @method('PATCH')
                <select name="status" class="form-select">
                    @foreach(['pending','paid','shipped','delivered','cancelled'] as $s)
                        <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <button class="btn px-4" style="background:var(--gold);color:#fff;">Save</button>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Customer --}}
        <div class="card shadow-sm border-0 p-4 mb-3">
            <h6 class="fw-bold mb-3">Customer</h6>
            <p class="mb-1 fw-semibold">{{ $order->user->name }}</p>
            <p class="mb-1 text-muted small">{{ $order->user->email }}</p>
            <a href="{{ route('admin.customers.show', $order->user) }}" class="btn btn-sm btn-outline-secondary mt-2">View Profile</a>
        </div>

        {{-- Shipping --}}
        <div class="card shadow-sm border-0 p-4 mb-3">
            <h6 class="fw-bold mb-3">Shipping Address</h6>
            <p class="mb-1 small">{{ $order->shipping_name }}</p>
            <p class="mb-1 text-muted small">{{ $order->shipping_address }}</p>
            <p class="mb-1 text-muted small">{{ $order->shipping_city }}, {{ $order->shipping_country }}</p>
            @if($order->shipping_phone)<p class="mb-0 text-muted small">{{ $order->shipping_phone }}</p>@endif
        </div>

        {{-- Payment --}}
        <div class="card shadow-sm border-0 p-4">
            <h6 class="fw-bold mb-3">Payment</h6>
            <p class="mb-1"><span class="badge bg-secondary">{{ ucfirst(str_replace('_',' ',$order->payment_method)) }}</span></p>
            @if($order->payment_reference)
                <p class="mb-1 text-muted small">Ref: {{ $order->payment_reference }}</p>
            @endif
            <p class="mb-0 text-muted small">{{ $order->created_at->format('M d, Y h:i A') }}</p>
        </div>
    </div>
</div>
@endsection
