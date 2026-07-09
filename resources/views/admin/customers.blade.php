@extends('layouts.admin')
@section('title', 'Customers')
@section('breadcrumb', 'Customers')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Customer Management</h4>
    <span class="badge bg-light text-dark border">{{ $customers->total() }} customers</span>
</div>

{{-- Search --}}
<div class="card shadow-sm border-0 p-3 mb-4">
    <form method="GET" action="{{ route('admin.customers') }}" class="row g-2 align-items-end">
        <div class="col-md-6">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name or email..." value="{{ request('search') }}">
        </div>
        <div class="col-md-6 d-flex gap-2">
            <button class="btn btn-sm btn-dark">Search</button>
            <a href="{{ route('admin.customers') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Customer</th><th>Email</th><th>Orders</th><th>Joined</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                style="width:36px;height:36px;background:var(--gold);font-size:.8rem;flex-shrink:0;">
                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                            </div>
                            <span class="fw-semibold small">{{ $customer->name }}</span>
                        </div>
                    </td>
                    <td class="text-muted small">{{ $customer->email }}</td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $customer->orders_count }}</span>
                    </td>
                    <td class="text-muted small">{{ $customer->created_at->format('M d, Y') }}</td>
                    <td>
                        @if($customer->email_verified_at)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Suspended</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                            <form method="POST" action="{{ route('admin.customers.toggle', $customer) }}" onsubmit="return confirm('Toggle customer status?')">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm {{ $customer->email_verified_at ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                    title="{{ $customer->email_verified_at ? 'Suspend' : 'Activate' }}">
                                    <i class="bi bi-{{ $customer->email_verified_at ? 'slash-circle' : 'check-circle' }}"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-5">No customers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3 border-top">{{ $customers->links() }}</div>
</div>
@endsection
