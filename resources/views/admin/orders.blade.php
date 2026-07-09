@extends('layouts.admin')
@section('title', 'Orders')
@section('breadcrumb', 'Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Order Management</h4>
    <span class="badge bg-light text-dark border">{{ $orders->total() }} orders</span>
</div>

{{-- Filters --}}
<div class="card shadow-sm border-0 p-3 mb-4">
    <form method="GET" action="{{ route('admin.orders') }}" class="row g-2 align-items-end">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by customer name or order ID..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Statuses</option>
                @foreach(['pending','paid','shipped','delivered','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button class="btn btn-sm btn-dark w-100">Filter</button>
            <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-secondary w-100">Clear</a>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>Customer</th><th>Shipping To</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                @php $colors = ['pending'=>'warning','paid'=>'success','shipped'=>'info','delivered'=>'primary','cancelled'=>'danger']; @endphp
                <tr>
                    <td class="fw-bold">#{{ $order->id }}</td>
                    <td>
                        <p class="mb-0 fw-semibold small">{{ $order->user->name }}</p>
                        <p class="mb-0 text-muted" style="font-size:.72rem;">{{ $order->shipping_email }}</p>
                    </td>
                    <td class="small">{{ $order->shipping_city }}, {{ $order->shipping_country }}</td>
                    <td class="fw-semibold" style="color:var(--gold)">RWF {{ number_format($order->total, 2) }}</td>
                    <td><span class="badge bg-secondary">{{ ucfirst(str_replace('_',' ',$order->payment_method)) }}</span></td>
                    <td><span class="badge bg-{{ $colors[$order->status] ?? 'secondary' }}">{{ ucfirst($order->status) }}</span></td>
                    <td class="text-muted small">{{ $order->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#statusModal{{ $order->id }}">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                {{-- Status Modal --}}
                <div class="modal fade" id="statusModal{{ $order->id }}" tabindex="-1">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header py-2">
                                <h6 class="modal-title fw-bold">Update Order #{{ $order->id }}</h6>
                                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                                @csrf @method('PATCH')
                                <div class="modal-body">
                                    <select name="status" class="form-select">
                                        @foreach(['pending','paid','shipped','delivered','cancelled'] as $s)
                                            <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="modal-footer py-2">
                                    <button class="btn btn-sm" style="background:var(--gold);color:#fff;">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-5">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3 border-top">{{ $orders->links() }}</div>
</div>
@endsection
