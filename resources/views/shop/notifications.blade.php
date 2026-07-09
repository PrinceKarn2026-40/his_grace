@extends('layouts.shop')
@section('title', 'Notifications - HisGrace')

@section('content')
<div class="container py-5" style="max-width:700px">
    <h2 class="brand-font mb-4">Notifications</h2>

    @forelse($notifications as $n)
    @php $data = $n->data; @endphp
    <div class="card border-0 shadow-sm mb-3 {{ $n->read_at ? '' : 'border-start border-4' }}" style="{{ $n->read_at ? '' : 'border-color:var(--gold)!important' }}">
        <div class="card-body d-flex justify-content-between align-items-start">
            <div>
                <p class="mb-1 fw-semibold">
                    <i class="bi bi-bag-check me-2" style="color:var(--gold)"></i>
                    {{ $data['message'] ?? 'Order Update' }}
                </p>
                @if(isset($data['order_id']))
                    <a href="{{ route('orders.show', $data['order_id']) }}" class="btn btn-sm btn-outline-gold mt-1">View Order #{{ $data['order_id'] }}</a>
                @endif
            </div>
            <small class="text-muted text-nowrap ms-3">{{ $n->created_at->diffForHumans() }}</small>
        </div>
    </div>
    @empty
    <div class="text-center py-5 text-muted">
        <i class="bi bi-bell-slash" style="font-size:3rem;"></i>
        <p class="mt-3">No notifications yet.</p>
    </div>
    @endforelse

    <div class="mt-3">{{ $notifications->links() }}</div>
</div>
@endsection
