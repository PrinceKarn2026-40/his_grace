@extends('layouts.customer')
@section('title', 'My Orders')
@section('breadcrumb', 'My Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">My Orders</h4>
    <a href="{{ route('shop') }}" class="btn btn-sm" style="background:var(--gold);color:#fff;"><i class="bi bi-bag me-1"></i>Shop More</a>
</div>

@forelse($orders as $order)
@php $colors = ['pending'=>'warning','paid'=>'success','shipped'=>'info','delivered'=>'primary','cancelled'=>'danger']; @endphp
<div class="card stat-card shadow-sm mb-3">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
            <div>
                <h6 class="fw-bold mb-1">Order #{{ $order->id }}</h6>
                <p class="text-muted small mb-0">
                    <i class="bi bi-calendar me-1"></i>{{ $order->created_at->format('M d, Y \a\t h:i A') }}
                </p>
                <p class="text-muted small mb-0">
                    <i class="bi bi-credit-card me-1"></i>{{ ucfirst(str_replace('_',' ',$order->payment_method)) }}
                    &bull; Ref: {{ $order->payment_reference }}
                </p>
            </div>
            <div class="text-end">
                <span class="badge bg-{{ $colors[$order->status] ?? 'secondary' }} mb-1 d-block">{{ ucfirst($order->status) }}</span>
                <p class="fw-bold mb-0 fs-5" style="color:var(--gold)">RWF {{ number_format($order->total, 2) }}</p>
            </div>
        </div>

        {{-- Items preview --}}
        <div class="d-flex gap-2 flex-wrap mb-3">
            @foreach($order->items->take(4) as $item)
            <div class="d-flex align-items-center gap-2 border rounded-3 px-2 py-1" style="font-size:.78rem;">
                <span class="fw-semibold">{{ $item->product->name ?? 'Product' }}</span>
                <span class="text-muted">×{{ $item->quantity }}</span>
            </div>
            @endforeach
            @if($order->items->count() > 4)
                <span class="badge bg-light text-dark border align-self-center">+{{ $order->items->count() - 4 }} more</span>
            @endif
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-eye me-1"></i>View Details
            </a>
            <a href="{{ route('customer.track') }}?order_id={{ $order->id }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-geo-alt me-1"></i>Track
            </a>
        </div>
    </div>
</div>
@empty
<div class="card stat-card shadow-sm p-5 text-center">
    <i class="bi bi-bag-x" style="font-size:3.5rem;color:#ddd;"></i>
    <h5 class="mt-3 fw-bold">No orders yet</h5>
    <p class="text-muted">You haven't placed any orders. Start shopping!</p>
    <a href="{{ route('shop') }}" class="btn px-5 mt-2" style="background:var(--gold);color:#fff;">Browse Products</a>
</div>
@endforelse

<div class="mt-3">{{ $orders->links() }}</div>
@endsection
