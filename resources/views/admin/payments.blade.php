@extends('layouts.admin')
@section('title', 'Payments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Mobile Money Payments</h4>
        <p class="text-muted small mb-0">Review and confirm customer MoMo payments</p>
    </div>
</div>

{{-- Status Filter Tabs --}}
<div class="d-flex gap-2 mb-4 flex-wrap">
    <a href="{{ route('admin.payments') }}" class="btn btn-sm {{ !request('status') ? 'btn-dark' : 'btn-outline-secondary' }}">
        All <span class="badge bg-secondary ms-1">{{ array_sum($counts) }}</span>
    </a>
    <a href="{{ route('admin.payments', ['status' => 'pending']) }}"
       class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning text-dark' : 'btn-outline-warning' }}">
        Pending <span class="badge bg-warning text-dark ms-1">{{ $counts['pending'] }}</span>
    </a>
    <a href="{{ route('admin.payments', ['status' => 'confirmed']) }}"
       class="btn btn-sm {{ request('status') === 'confirmed' ? 'btn-success' : 'btn-outline-success' }}">
        Confirmed <span class="badge bg-success ms-1">{{ $counts['confirmed'] }}</span>
    </a>
    <a href="{{ route('admin.payments', ['status' => 'rejected']) }}"
       class="btn btn-sm {{ request('status') === 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">
        Rejected <span class="badge bg-danger ms-1">{{ $counts['rejected'] }}</span>
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead style="background:#f8f9fa;">
                <tr>
                    <th class="px-4 py-3 small fw-semibold text-muted">Payment</th>
                    <th class="py-3 small fw-semibold text-muted">Customer</th>
                    <th class="py-3 small fw-semibold text-muted">Order</th>
                    <th class="py-3 small fw-semibold text-muted">Amount</th>
                    <th class="py-3 small fw-semibold text-muted">Sender Number</th>
                    <th class="py-3 small fw-semibold text-muted">Transaction ID</th>
                    <th class="py-3 small fw-semibold text-muted">Status</th>
                    <th class="py-3 small fw-semibold text-muted">Date</th>
                    <th class="py-3 small fw-semibold text-muted">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td class="px-4 py-3">
                        <span class="fw-semibold small">#{{ $payment->id }}</span>
                    </td>
                    <td class="py-3">
                        <div class="fw-semibold small">{{ $payment->user->name }}</div>
                        <div class="text-muted" style="font-size:.72rem;">{{ $payment->user->email }}</div>
                    </td>
                    <td class="py-3">
                        <a href="{{ route('admin.orders.show', $payment->order_id) }}" class="text-decoration-none fw-semibold small">
                            #{{ $payment->order_id }}
                        </a>
                        <div class="text-muted" style="font-size:.72rem;">{{ $payment->order->payment_reference }}</div>
                    </td>
                    <td class="py-3">
                        <span class="fw-bold" style="color:var(--gold)">RWF {{ number_format($payment->amount, 2) }}</span>
                    </td>
                    <td class="py-3">
                        @if($payment->sender_number)
                            <span class="badge bg-light text-dark border">{{ $payment->sender_number }}</span>
                        @else
                            <span class="text-muted small fst-italic">Not submitted</span>
                        @endif
                    </td>
                    <td class="py-3">
                        @if($payment->transaction_id)
                            <code class="small">{{ $payment->transaction_id }}</code>
                        @else
                            <span class="text-muted small fst-italic">Not submitted</span>
                        @endif
                    </td>
                    <td class="py-3">
                        @php
                            $badge = match($payment->status) {
                                'confirmed' => 'success',
                                'rejected'  => 'danger',
                                default     => 'warning text-dark',
                            };
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ ucfirst($payment->status) }}</span>
                    </td>
                    <td class="py-3">
                        <span class="small text-muted">{{ $payment->created_at->format('d M Y') }}</span>
                        <div style="font-size:.7rem;" class="text-muted">{{ $payment->created_at->format('h:i A') }}</div>
                    </td>
                    <td class="py-3">
                        @if($payment->status === 'pending')
                        <div class="d-flex gap-1">
                            <form method="POST" action="{{ route('admin.payments.status', $payment->id) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="confirmed">
                                <button class="btn btn-sm btn-success" title="Confirm Payment"
                                        onclick="return confirm('Confirm this payment and mark order as paid?')">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.payments.status', $payment->id) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button class="btn btn-sm btn-danger" title="Reject Payment"
                                        onclick="return confirm('Reject this payment?')">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </form>
                        </div>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5 text-muted">
                        <i class="bi bi-phone fs-1 d-block mb-2 opacity-25"></i>
                        No payments found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
    <div class="px-4 py-3 border-top">
        {{ $payments->links() }}
    </div>
    @endif
</div>
@endsection
