@extends('layouts.customer')
@section('title', 'My Dashboard')
@section('breadcrumb', 'Dashboard')

@push('styles')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #0f0f0f 0%, #2d0a0a 100%);
        border-radius: 16px; padding: 2rem; position: relative; overflow: hidden;
    }
    .welcome-banner::before {
        content: ''; position: absolute; right: -40px; top: -40px;
        width: 200px; height: 200px; border-radius: 50%;
        background: radial-gradient(circle, rgba(192,57,43,.3) 0%, transparent 70%);
    }
    .welcome-banner::after {
        content: ''; position: absolute; right: 60px; bottom: -60px;
        width: 150px; height: 150px; border-radius: 50%;
        background: radial-gradient(circle, rgba(192,57,43,.15) 0%, transparent 70%);
    }
    .stat-card { transition: transform .2s; }
    .stat-card:hover { transform: translateY(-2px); }
    .order-row { transition: background .15s; }
    .order-row:hover { background: #fafafa; }
    .quick-btn {
        display: flex; align-items: center; gap: .75rem;
        padding: .75rem 1rem; border-radius: 10px; border: 1px solid #ebebeb;
        text-decoration: none; color: var(--dark, #0f0f0f); font-size: .85rem; font-weight: 500;
        transition: .2s; background: #fff;
    }
    .quick-btn:hover { border-color: var(--red, #c0392b); color: var(--red, #c0392b); background: rgba(192,57,43,.03); }
    .quick-btn i { font-size: 1.1rem; color: var(--red, #c0392b); }
    .quick-btn-primary { background: var(--red, #c0392b); color: #fff; border-color: var(--red, #c0392b); }
    .quick-btn-primary i { color: #fff; }
    .quick-btn-primary:hover { background: #96281b; color: #fff; border-color: #96281b; }
</style>
@endpush

@section('content')

{{-- Welcome Banner --}}
<div class="welcome-banner mb-4">
    <div class="position-relative" style="z-index:1;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <p class="mb-1" style="color:rgba(255,255,255,.5);font-size:.8rem;text-transform:uppercase;letter-spacing:.1em;">Welcome back</p>
                <h4 class="fw-bold mb-1 text-white" style="font-family:'Playfair Display',serif;font-size:1.6rem;">{{ auth()->user()->name }} 👋</h4>
                <p class="mb-0" style="color:rgba(255,255,255,.45);font-size:.85rem;">Here's what's happening with your account</p>
            </div>
            <a href="{{ route('shop') }}" class="btn btn-sm px-4 py-2 fw-semibold" style="background:var(--red);color:#fff;border-radius:50px;border:none;">
                <i class="bi bi-bag me-1"></i>Shop Now
            </a>
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small mb-1">Total Orders</p>
                    <h4 class="fw-bold mb-0">{{ $stats['total_orders'] }}</h4>
                </div>
                <div class="stat-icon" style="background:rgba(13,110,253,.1);"><i class="bi bi-bag-check" style="color:#0d6efd"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small mb-1">Total Spent</p>
                    <h4 class="fw-bold mb-0" style="color:var(--red);font-size:1.1rem;">RWF {{ number_format($stats['total_spent'], 0) }}</h4>
                </div>
                <div class="stat-icon" style="background:rgba(192,57,43,.1);"><i class="bi bi-cash-stack" style="color:var(--red)"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small mb-1">Pending</p>
                    <h4 class="fw-bold mb-0">{{ $stats['pending_orders'] }}</h4>
                </div>
                <div class="stat-icon" style="background:rgba(255,193,7,.1);"><i class="bi bi-clock" style="color:#ffc107"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small mb-1">Wishlist</p>
                    <h4 class="fw-bold mb-0">{{ $stats['wishlist_count'] }}</h4>
                </div>
                <div class="stat-icon" style="background:rgba(220,53,69,.1);"><i class="bi bi-heart" style="color:#dc3545"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Recent Orders --}}
    <div class="col-lg-8">
        <div class="card stat-card shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Recent Orders</h6>
                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">View All</a>
            </div>
            @forelse($recentOrders as $order)
            @php $colors = ['pending'=>'warning','paid'=>'success','shipped'=>'info','delivered'=>'primary','cancelled'=>'danger']; @endphp
            <div class="order-row d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom rounded-2 px-2 py-1">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:40px;height:40px;border-radius:10px;background:rgba(192,57,43,.08);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-bag" style="color:var(--red);"></i>
                    </div>
                    <div>
                        <p class="mb-0 fw-semibold small">Order #{{ $order->id }}</p>
                        <p class="mb-0 text-muted" style="font-size:.72rem;">
                            {{ $order->items->count() }} item(s) &bull; {{ $order->created_at->format('M d, Y') }}
                        </p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-{{ $colors[$order->status] ?? 'secondary' }}">{{ ucfirst($order->status) }}</span>
                    <span class="fw-bold small" style="color:var(--red)">RWF {{ number_format($order->total, 0) }}</span>
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-secondary py-0 px-2 rounded-pill">View</a>
                </div>
            </div>
            @empty
            <div class="text-center py-5 text-muted">
                <div style="width:64px;height:64px;border-radius:16px;background:rgba(192,57,43,.08);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                    <i class="bi bi-bag-x fs-3" style="color:var(--red);opacity:.5;"></i>
                </div>
                <p class="mb-1 fw-semibold">No orders yet</p>
                <p class="small mb-3">Start shopping to see your orders here</p>
                <a href="{{ route('shop') }}" class="btn btn-sm px-4" style="background:var(--red);color:#fff;border-radius:50px;">Browse Products</a>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">
        {{-- Quick Actions --}}
        <div class="card stat-card shadow-sm p-4 mb-3">
            <h6 class="fw-bold mb-3">Quick Actions</h6>
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('shop') }}" class="quick-btn quick-btn-primary"><i class="bi bi-bag"></i>Browse Products</a>
                <a href="{{ route('cart') }}" class="quick-btn"><i class="bi bi-cart"></i>View Cart</a>
                <a href="{{ route('customer.track') }}" class="quick-btn"><i class="bi bi-geo-alt"></i>Track Order</a>
                <a href="{{ route('wishlist') }}" class="quick-btn"><i class="bi bi-heart"></i>My Wishlist</a>
            </div>
        </div>

        {{-- Account Info --}}
        <div class="card stat-card shadow-sm p-4">
            <h6 class="fw-bold mb-3">Account Info</h6>
            <div class="d-flex align-items-center gap-3 mb-3">
                @if(auth()->user()->profile_photo_path)
                    <img src="{{ auth()->user()->profile_photo_url }}"
                        style="width:52px;height:52px;border-radius:50%;object-fit:cover;border:2px solid var(--red);flex-shrink:0;">
                @else
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                        style="width:52px;height:52px;background:var(--red);font-size:1.3rem;flex-shrink:0;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <p class="mb-0 fw-semibold">{{ auth()->user()->name }}</p>
                    <p class="mb-0 text-muted small">{{ auth()->user()->email }}</p>
                    <span class="badge mt-1" style="background:rgba(192,57,43,.1);color:var(--red);font-size:.7rem;">Customer</span>
                </div>
            </div>
            <a href="{{ route('customer.profile') }}" class="btn btn-sm btn-outline-secondary w-100 rounded-pill">
                <i class="bi bi-pencil me-1"></i>Edit Profile
            </a>
        </div>
    </div>
</div>
@endsection
