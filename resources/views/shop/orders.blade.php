@extends('layouts.shop')
@section('title', 'My Orders - HisGrace')

@section('content')
<div class="container py-5" style="max-width:900px">
    <h2 class="brand-font mb-4">My Orders</h2>

    @forelse($orders as $order)
    @php $colors = ['pending'=>'warning','paid'=>'success','shipped'=>'info','delivered'=>'primary','cancelled'=>'danger']; @endphp
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div>
                    <h6 class="fw-bold mb-1">Order #{{ $order->id }}</h6>
                    <p class="text-muted small mb-0">{{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
                    <p class="text-muted small mb-0">{{ $order->items->count() }} item(s) &bull; {{ ucfirst(str_replace('_',' ',$order->payment_method)) }}</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-{{ $colors[$order->status] ?? 'secondary' }} mb-1">{{ ucfirst($order->status) }}</span>
                    <p class="fw-bold mb-0" style="color:var(--gold)">RWF {{ number_format($order->total, 2) }}</p>
                </div>
            </div>
            <hr class="my-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex gap-2 flex-wrap">
                    @foreach($order->items->take(3) as $item)
                        <span class="badge bg-light text-dark border">{{ $item->product->name ?? 'Product' }}</span>
                    @endforeach
                    @if($order->items->count() > 3)
                        <span class="badge bg-light text-dark border">+{{ $order->items->count() - 3 }} more</span>
                    @endif
                </div>
                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-gold">View Details</a>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-5 text-muted">
        <i class="bi bi-bag-x" style="font-size:3rem;"></i>
        <h5 class="mt-3">No orders yet</h5>
        <a href="{{ route('shop') }}" class="btn btn-gold mt-2">Start Shopping</a>
    </div>
    @endforelse

    <div class="mt-3">{{ $orders->links() }}</div>
</div>
@endsection
