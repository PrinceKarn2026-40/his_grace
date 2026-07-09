<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'My Account') — HisGrace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --red: #c0392b; --red-light: #e74c3c; --dark: #0f0f0f; --sidebar-w: 250px; }
        body { font-family: 'Inter', sans-serif; background: #f5f5f5; }

        #sidebar {
            width: var(--sidebar-w); min-height: 100vh; background: var(--dark);
            position: fixed; top: 0; left: 0; z-index: 1000;
            display: flex; flex-direction: column; transition: transform .3s;
            border-right: 1px solid rgba(255,255,255,.05);
        }
        #sidebar .brand {
            font-family: 'Playfair Display', serif; font-size: 1.4rem; color: #fff;
            padding: 1.4rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,.06);
        }
        #sidebar .brand span { color: var(--red-light); }
        #sidebar .nav-link {
            color: rgba(255,255,255,.5); font-size: .83rem; font-weight: 500;
            padding: .6rem 1.5rem; display: flex; align-items: center; gap: .75rem;
            border-left: 3px solid transparent; transition: .15s; text-decoration: none;
        }
        #sidebar .nav-link:hover, #sidebar .nav-link.active {
            color: #fff; background: rgba(255,255,255,.05); border-left-color: var(--red);
        }
        #sidebar .nav-link i { font-size: .95rem; width: 18px; text-align: center; }
        #sidebar .nav-section { font-size: .68rem; text-transform: uppercase; letter-spacing: .12em; color: rgba(255,255,255,.2); padding: .85rem 1.5rem .3rem; }
        #sidebar .sidebar-footer { margin-top: auto; padding: 1rem 1.5rem; border-top: 1px solid rgba(255,255,255,.06); }

        #main { margin-left: var(--sidebar-w); min-height: 100vh; }
        .topbar { background: #fff; border-bottom: 1px solid #ebebeb; padding: .7rem 1.5rem; position: sticky; top: 0; z-index: 999; }
        .page-content { padding: 1.75rem; }

        .stat-card { border: none; border-radius: 14px; background: #fff; box-shadow: 0 1px 4px rgba(0,0,0,.06); }
        .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }

        .badge-red { background: var(--red); }

        @media(max-width:768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #main { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

<div id="sidebar">
    <div class="brand">
        His<span>Grace</span>
        <small class="d-block" style="font-size:.62rem;font-family:Inter;font-weight:400;color:rgba(255,255,255,.3);letter-spacing:.08em;text-transform:uppercase;">My Account</small>
    </div>

    <nav class="mt-2 flex-grow-1">
        <div class="nav-section">Account</div>
        <a href="{{ route('customer.dashboard') }}" class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
        <a href="{{ route('customer.profile') }}" class="nav-link {{ request()->routeIs('customer.profile') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i> My Profile
        </a>

        <div class="nav-section">Shopping</div>
        <a href="{{ route('cart') }}" class="nav-link {{ request()->routeIs('cart') ? 'active' : '' }}">
            <i class="bi bi-bag"></i> My Cart
            @php $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity'); @endphp
            @if($cartCount > 0)<span class="badge ms-auto badge-red">{{ $cartCount }}</span>@endif
        </a>
        <a href="{{ route('checkout') }}" class="nav-link {{ request()->routeIs('checkout') ? 'active' : '' }}">
            <i class="bi bi-credit-card"></i> Checkout
        </a>

        <div class="nav-section">Orders</div>
        <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.index') ? 'active' : '' }}">
            <i class="bi bi-bag-check"></i> My Orders
        </a>
        <a href="{{ route('customer.track') }}" class="nav-link {{ request()->routeIs('customer.track*') ? 'active' : '' }}">
            <i class="bi bi-geo-alt"></i> Track Order
        </a>

        <div class="nav-section">More</div>
        <a href="{{ route('wishlist') }}" class="nav-link {{ request()->routeIs('wishlist') ? 'active' : '' }}">
            <i class="bi bi-heart"></i> Wishlist
        </a>
        <a href="{{ route('notifications') }}" class="nav-link {{ request()->routeIs('notifications') ? 'active' : '' }}">
            <i class="bi bi-bell"></i> Notifications
            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="badge bg-danger ms-auto">{{ auth()->user()->unreadNotifications->count() }}</span>
            @endif
        </a>
        <a href="{{ route('home') }}" class="nav-link">
            <i class="bi bi-shop"></i> Back to Store
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2 mb-3">
            @if(auth()->user()->profile_photo_path)
                <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}"
                    style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid var(--red);flex-shrink:0;">
            @else
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                    style="width:36px;height:36px;background:var(--red);font-size:.85rem;flex-shrink:0;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            @endif
            <div style="overflow:hidden;">
                <p class="mb-0 text-white fw-semibold" style="font-size:.8rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->name }}</p>
                <p class="mb-0" style="font-size:.7rem;color:rgba(255,255,255,.35);">Customer</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-sm w-100" style="background:rgba(192,57,43,.15);color:var(--red-light);border:1px solid rgba(192,57,43,.2);font-size:.78rem;">
                <i class="bi bi-box-arrow-right me-1"></i>Logout
            </button>
        </form>
    </div>
</div>

<div id="main">
    <div class="topbar d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="bi bi-list fs-5"></i>
            </button>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}" class="text-decoration-none" style="color:var(--red)">My Account</a></li>
                    <li class="breadcrumb-item active">@yield('breadcrumb', 'Dashboard')</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('cart') }}" class="text-dark position-relative">
                <i class="bi bi-bag fs-5"></i>
                @if($cartCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background:var(--red);font-size:.6rem;">{{ $cartCount }}</span>
                @endif
            </a>
            <a href="{{ route('notifications') }}" class="text-dark position-relative">
                <i class="bi bi-bell fs-5"></i>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:.6rem;">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
            </a>
            <div class="dropdown">
                <button class="btn btn-sm d-flex align-items-center gap-2 border rounded-pill px-3 py-1" data-bs-toggle="dropdown">
                    @if(auth()->user()->profile_photo_path)
                        <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}" style="width:26px;height:26px;border-radius:50%;object-fit:cover;">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                            style="width:26px;height:26px;background:var(--red);font-size:.72rem;flex-shrink:0;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <span class="small fw-semibold d-none d-md-inline">{{ auth()->user()->name }}</span>
                    <i class="bi bi-chevron-down" style="font-size:.65rem;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="min-width:190px;">
                    <li><h6 class="dropdown-header small">{{ auth()->user()->email }}</h6></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item small" href="{{ route('customer.profile') }}"><i class="bi bi-person me-2"></i>My Profile</a></li>
                    <li><a class="dropdown-item small" href="{{ route('orders.index') }}"><i class="bi bi-bag-check me-2"></i>My Orders</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item small text-danger fw-semibold">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3 mb-0 py-2" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3 mb-0 py-2" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-content">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
