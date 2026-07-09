@extends('layouts.admin')
@section('title', $user->name)
@section('breadcrumb', 'Customer Detail')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.customers') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0">{{ $user->name }}</h4>
    @if($user->email_verified_at)
        <span class="badge bg-success">Active</span>
    @else
        <span class="badge bg-danger">Suspended</span>
    @endif
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 p-4 mb-3 text-center">
            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold mx-auto mb-3"
                style="width:72px;height:72px;background:var(--gold);font-size:1.8rem;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
            <p class="text-muted small mb-3">{{ $user->email }}</p>
            <div class="row g-2 text-center">
                <div class="col-6">
                    <p class="fw-bold mb-0" style="color:var(--gold)">{{ $user->orders->count() }}</p>
                    <p class="text-muted mb-0" style="font-size:.75rem;">Orders</p>
                </div>
                <div class="col-6">
                    <p class="fw-bold mb-0" style="color:var(--gold)">RWF {{ number_format($user->orders->where('status','paid')->sum('total'), 0) }}</p>
                    <p class="text-muted mb-0" style="font-size:.75rem;">Spent</p>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 p-4">
            <h6 class="fw-bold mb-3">Account Info</h6>
            <p class="mb-1 small"><span class="text-muted">Joined:</span> {{ $user->created_at->format('M d, Y') }}</p>
            <p class="mb-1 small"><span class="text-muted">Verified:</span> {{ $user->email_verified_at ? $user->email_verified_at->format('M d, Y') : 'Not verified' }}</p>
            <p class="mb-3 small"><span class="text-muted">2FA:</span> {{ $user->two_factor_secret ? 'Enabled' : 'Disabled' }}</p>
            <form method="POST" action="{{ route('admin.customers.toggle', $user) }}">
                @csrf @method('PATCH')
                <button class="btn btn-sm w-100 {{ $user->email_verified_at ? 'btn-outline-danger' : 'btn-outline-success' }}">
                    {{ $user->email_verified_at ? 'Suspend Account' : 'Activate Account' }}
                </button>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm border-0 p-4">
            <h6 class="fw-bold mb-3">Order History</h6>
            @forelse($user->orders as $order)
            @php $colors = ['pending'=>'warning','paid'=>'success','shipped'=>'info','delivered'=>'primary','cancelled'=>'danger']; @endphp
            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                <div>
                    <p class="mb-0 fw-semibold small">Order #{{ $order->id }}</p>
                    <p class="mb-0 text-muted" style="font-size:.72rem;">
                        {{ $order->items->count() }} item(s) &bull; {{ $order->created_at->format('M d, Y') }}
                    </p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-{{ $colors[$order->status] ?? 'secondary' }}">{{ ucfirst($order->status) }}</span>
                    <span class="fw-semibold small" style="color:var(--gold)">RWF {{ number_format($order->total, 2) }}</span>
                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary py-0">View</a>
                </div>
            </div>
            @empty
            <p class="text-muted small">No orders yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
