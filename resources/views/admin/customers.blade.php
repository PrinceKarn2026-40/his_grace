@extends('layouts.admin')
@section('title', 'Customers')
@section('breadcrumb', 'Customers')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Customer Management</h4>
    <div class="d-flex gap-2 align-items-center">
        <span class="badge bg-light text-dark border">{{ $customers->total() }} customers</span>
        <button class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#createCustomerModal">
            <i class="bi bi-person-plus me-1"></i>Create Customer
        </button>
    </div>
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
                            <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-outline-secondary" title="View"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('admin.customers.toggle', $customer) }}">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm {{ $customer->email_verified_at ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                    title="{{ $customer->email_verified_at ? 'Suspend' : 'Activate' }}">
                                    <i class="bi bi-{{ $customer->email_verified_at ? 'slash-circle' : 'check-circle' }}"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" onsubmit="return confirm('Delete this customer?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
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

{{-- Create Customer Modal --}}
<div class="modal fade" id="createCustomerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Create Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.customers.store') }}">
                @csrf
                <div class="modal-body">
                    @if($errors->any())
                        <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Full Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email *</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Password *</label>
                        <input type="password" name="password" class="form-control" minlength="8" required>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="email_verified" id="email_verified" value="1" checked>
                        <label class="form-check-label small" for="email_verified">Mark email as verified</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-person-plus me-1"></i>Create Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->any())
<script>document.addEventListener('DOMContentLoaded',()=>new bootstrap.Modal(document.getElementById('createCustomerModal')).show());</script>
@endif

@endsection
