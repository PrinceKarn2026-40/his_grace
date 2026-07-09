<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#c0392b">
    <link rel="manifest" href="/manifest.json">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="HisGrace">
    <title>@yield('title', 'HisGrace Fashion')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --red: #c0392b;
            --red-dark: #96281b;
            --red-light: #e74c3c;
            --dark: #0f0f0f;
            --dark2: #1a1a1a;
            --light-bg: #fafafa;
        }
        body { font-family: 'Inter', sans-serif; background: var(--light-bg); }
        .brand-font { font-family: 'Playfair Display', serif; }

        /* Navbar */
        .navbar {
            background: var(--dark);
            border-bottom: 1px solid rgba(255,255,255,.06);
            padding: .75rem 0;
        }
        .navbar-brand { font-family: 'Playfair Display', serif; font-size: 1.7rem; color: #fff !important; letter-spacing: -.02em; }
        .navbar-brand span { color: var(--red-light); }
        .nav-link { font-size: .8rem; font-weight: 500; letter-spacing: .08em; text-transform: uppercase; color: rgba(255,255,255,.7) !important; padding: .5rem .75rem !important; transition: .2s; }
        .nav-link:hover, .nav-link.active { color: #fff !important; }
        .navbar-toggler { border-color: rgba(255,255,255,.2); }
        .navbar-toggler-icon { filter: invert(1); }
        .dropdown-menu { background: #1e1e1e; border: 1px solid rgba(255,255,255,.08); border-radius: 10px; padding: .5rem; }
        .dropdown-item { color: rgba(255,255,255,.7); font-size: .85rem; border-radius: 6px; padding: .5rem .75rem; }
        .dropdown-item:hover { background: rgba(255,255,255,.06); color: #fff; }
        .dropdown-divider { border-color: rgba(255,255,255,.08); }

        /* Buttons */
        .btn-red { background: var(--red); color: #fff; border: none; transition: .2s; }
        .btn-red:hover { background: var(--red-dark); color: #fff; }
        .btn-outline-red { border: 1px solid var(--red); color: var(--red); background: transparent; transition: .2s; }
        .btn-outline-red:hover { background: var(--red); color: #fff; }

        /* Search */
        .search-input {
            background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.1);
            color: #fff; border-radius: 8px; font-size: .82rem; padding: .4rem .85rem;
            width: 150px; transition: .2s;
        }
        .search-input:focus { background: rgba(255,255,255,.12); border-color: var(--red); box-shadow: none; color: #fff; width: 180px; }
        .search-input::placeholder { color: rgba(255,255,255,.3); }

        /* Cart icon */
        .cart-icon-wrap { position: relative; color: rgba(255,255,255,.8); transition: .2s; }
        .cart-icon-wrap:hover { color: #fff; }
        .cart-count {
            position: absolute; top: -7px; right: -9px;
            background: var(--red); color: #fff; border-radius: 50%;
            width: 18px; height: 18px; font-size: .62rem;
            display: flex; align-items: center; justify-content: center; font-weight: 700;
        }
        .notification-dot { width: 7px; height: 7px; background: var(--red-light); border-radius: 50%; display: inline-block; }

        /* Products */
        .product-card { border: none; transition: transform .25s, box-shadow .25s; background: #fff; border-radius: 12px; overflow: hidden; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 16px 40px rgba(0,0,0,.12); }
        .product-img { height: 280px; object-fit: cover; background: #f0ede8; width: 100%; }
        .product-img-placeholder { height: 280px; background: linear-gradient(135deg, #f5f0ea, #ede8e0); display: flex; align-items: center; justify-content: center; }
        .price-tag { color: var(--red); font-weight: 700; }
        .price-original { text-decoration: line-through; color: #bbb; font-size: .85rem; }
        .badge-new { background: var(--red); font-size: .68rem; }
        .badge-sale { background: #e67e22; font-size: .68rem; }
        .section-title { font-family: 'Playfair Display', serif; font-size: 2rem; color: var(--dark); }

        /* Flash */
        .alert { border-radius: 0; border: none; }
        .star-filled { color: var(--red); }
        .star-empty { color: #ddd; }

        /* Footer */
        .footer { background: var(--dark); color: rgba(255,255,255,.55); }
        .footer-brand { font-family: 'Playfair Display', serif; font-size: 1.8rem; color: #fff; }
        .footer-brand span { color: var(--red-light); }
        .footer a { color: rgba(255,255,255,.5); text-decoration: none; transition: .2s; }
        .footer a:hover { color: var(--red-light); }
        .footer-heading { color: #fff; font-size: .8rem; letter-spacing: .12em; text-transform: uppercase; font-weight: 600; margin-bottom: 1rem; }
        .footer-divider { border-color: rgba(255,255,255,.08); }
        .social-link { width: 36px; height: 36px; border-radius: 8px; background: rgba(255,255,255,.06); display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,.6); transition: .2s; text-decoration: none; }
        .social-link:hover { background: var(--red); color: #fff; }

        /* Announcement bar */
        .announcement-bar { background: var(--red); color: #fff; text-align: center; font-size: .78rem; padding: .4rem; letter-spacing: .05em; }

        /* WhatsApp FAB */
        .wa-fab {
            position: fixed; bottom: 28px; right: 28px; z-index: 9999;
            width: 58px; height: 58px; border-radius: 50%;
            background: #25D366;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 8px 28px rgba(37,211,102,.45);
            text-decoration: none; transition: transform .25s, box-shadow .25s;
            animation: waPop .4s cubic-bezier(.175,.885,.32,1.275) both;
        }
        .wa-fab:hover { transform: scale(1.12); box-shadow: 0 12px 36px rgba(37,211,102,.55); }
        .wa-fab svg { width: 30px; height: 30px; fill: #fff; }
        .wa-fab-tooltip {
            position: fixed; bottom: 38px; right: 96px; z-index: 9998;
            background: #1a1a1a; color: #fff; font-size: .78rem; font-weight: 600;
            padding: .45rem .9rem; border-radius: 8px; white-space: nowrap;
            opacity: 0; pointer-events: none; transition: opacity .2s;
            box-shadow: 0 4px 16px rgba(0,0,0,.2);
        }
        .wa-fab-tooltip::after {
            content: ''; position: absolute; right: -6px; top: 50%; transform: translateY(-50%);
            border: 6px solid transparent; border-left-color: #1a1a1a; border-right: none;
        }
        .wa-fab:hover + .wa-tooltip { opacity: 1; }
        .wa-wrap:hover .wa-fab-tooltip { opacity: 1; }
        .wa-pulse {
            position: absolute; inset: 0; border-radius: 50%;
            background: #25D366; animation: waPulse 2s ease-out infinite;
        }
        @keyframes waPulse { 0% { transform: scale(1); opacity: .6; } 100% { transform: scale(1.7); opacity: 0; } }
        @keyframes waPop { from { transform: scale(0); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    </style>
    @stack('styles')
</head>
<body>

{{-- Announcement Bar --}}
<div class="announcement-bar">
    🔥 FREE SHIPPING on orders over RWF 50,000 &nbsp;|&nbsp; Use code <strong>HISGRACE10</strong> for 10% off
</div>

{{-- Navbar --}}
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">His<span>Grace</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav mx-auto gap-1">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('shop') }}">Shop</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('shop', ['gender' => 'men']) }}">Men</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('shop', ['gender' => 'women']) }}">Women</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Collections</a>
                    <ul class="dropdown-menu">
                        @foreach(\App\Models\Category::all() as $cat)
                            <li><a class="dropdown-item" href="{{ route('shop', ['category' => $cat->slug]) }}">{{ $cat->name }}</a></li>
                        @endforeach
                    </ul>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <form action="{{ route('shop') }}" method="GET" class="d-flex">
                    <input class="search-input form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                </form>
                <a href="{{ route('cart') }}" class="cart-icon-wrap">
                    <i class="bi bi-bag fs-5"></i>
                    @php $cartCount = \App\Models\Cart::where(auth()->check() ? ['user_id' => auth()->id()] : ['session_id' => session()->getId()])->sum('quantity'); @endphp
                    @if($cartCount > 0)<span class="cart-count">{{ $cartCount }}</span>@endif
                </a>
                @auth
                    <a href="{{ route('notifications') }}" class="position-relative" style="color:rgba(255,255,255,.7);">
                        <i class="bi bi-bell fs-5"></i>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="notification-dot position-absolute top-0 end-0"></span>
                        @endif
                    </a>
                    <div class="dropdown">
                        <a class="dropdown-toggle" href="#" data-bs-toggle="dropdown" style="color:rgba(255,255,255,.8);text-decoration:none;">
                            <i class="bi bi-person-circle fs-5"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header" style="color:rgba(255,255,255,.4);">{{ auth()->user()->name }}</h6></li>
                            <li><a class="dropdown-item" href="{{ route('customer.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>My Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="bi bi-box me-2"></i>My Orders</a></li>
                            <li><a class="dropdown-item" href="{{ route('wishlist') }}"><i class="bi bi-heart me-2"></i>Wishlist</a></li>
                            @if(auth()->user()->is_admin)
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}" style="color:var(--red-light);"><i class="bi bi-speedometer2 me-2"></i>Admin Panel</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item" style="color:#e74c3c;"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-red rounded-pill px-3">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-sm btn-red rounded-pill px-3">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="alert alert-success text-center py-2 mb-0">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger text-center py-2 mb-0">{{ session('error') }}</div>
@endif

@yield('content')

{{-- Footer --}}
<footer class="footer py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="footer-brand mb-3">His<span>Grace</span></div>
                <p class="small mb-3">Premium fashion for the discerning individual. Quality, style, and elegance in every piece crafted for Kigali and beyond.</p>
                <div class="d-flex gap-2">
                    <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-link"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="social-link"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>
            <div class="col-md-2">
                <p class="footer-heading">Shop</p>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="{{ route('shop', ['gender' => 'women']) }}">Women</a></li>
                    <li class="mb-2"><a href="{{ route('shop', ['gender' => 'men']) }}">Men</a></li>
                    <li class="mb-2"><a href="{{ route('shop') }}">All Products</a></li>
                    <li class="mb-2"><a href="{{ route('shop', ['sort' => 'newest']) }}">New Arrivals</a></li>
                </ul>
            </div>
            <div class="col-md-2">
                <p class="footer-heading">Account</p>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="{{ route('login') }}">Login</a></li>
                    <li class="mb-2"><a href="{{ route('register') }}">Register</a></li>
                    @auth
                        <li class="mb-2"><a href="{{ route('orders.index') }}">My Orders</a></li>
                        <li class="mb-2"><a href="{{ route('wishlist') }}">Wishlist</a></li>
                    @endauth
                </ul>
            </div>
            <div class="col-md-4">
                <p class="footer-heading">Contact</p>
                <p class="small mb-2"><i class="bi bi-envelope me-2" style="color:var(--red-light)"></i>libprince1999@gmail.com</p>
                <p class="small mb-2"><i class="bi bi-whatsapp me-2" style="color:var(--red-light)"></i>+250 795 919 537</p>
                <p class="small mb-3"><i class="bi bi-geo-alt me-2" style="color:var(--red-light)"></i>Kigali, Rwanda</p>
                <div class="d-flex align-items-center gap-2 p-3 rounded-3" style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);">
                    <i class="bi bi-shield-check" style="color:var(--red-light);font-size:1.2rem;"></i>
                    <span class="small">Secure payments via MoMo</span>
                </div>
            </div>
        </div>
        <hr class="footer-divider mt-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <p class="small mb-0">&copy; {{ date('Y') }} HisGrace Fashion. All rights reserved.</p>
            <p class="small mb-0" style="color:rgba(255,255,255,.25);">Made with ❤️ in Kigali</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- WhatsApp FAB --}}
<div class="wa-wrap" style="position:fixed;bottom:28px;right:28px;z-index:9999;">
    <a href="https://wa.me/250795919537?text=Hi%20HisGrace%20Fashion%2C%20I%20need%20help%20with%20an%20order!" target="_blank" rel="noopener" class="wa-fab" aria-label="Chat on WhatsApp">
        <span class="wa-pulse"></span>
        <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
            <path d="M16.004 2.667C8.64 2.667 2.667 8.64 2.667 16c0 2.347.64 4.64 1.853 6.64L2.667 29.333l6.907-1.813A13.253 13.253 0 0016.004 29.333C23.36 29.333 29.333 23.36 29.333 16S23.36 2.667 16.004 2.667zm0 24c-2.08 0-4.107-.56-5.867-1.6l-.413-.24-4.107 1.08 1.093-4-.267-.427A10.587 10.587 0 015.333 16c0-5.88 4.787-10.667 10.667-10.667S26.667 10.12 26.667 16 21.88 26.667 16 26.667zm5.84-7.973c-.32-.16-1.893-.933-2.187-1.04-.293-.107-.507-.16-.72.16-.213.32-.827 1.04-.987 1.253-.16.213-.347.24-.667.08-.32-.16-1.347-.493-2.56-1.573-.947-.84-1.587-1.88-1.773-2.2-.187-.32-.02-.493.14-.653.147-.147.32-.373.48-.56.16-.187.213-.32.32-.533.107-.213.053-.4-.027-.56-.08-.16-.72-1.733-.987-2.373-.253-.613-.52-.533-.72-.547h-.613c-.213 0-.56.08-.853.4-.293.32-1.12 1.093-1.12 2.667s1.147 3.093 1.307 3.307c.16.213 2.253 3.44 5.467 4.827.76.333 1.36.533 1.827.68.76.24 1.453.213 2 .133.613-.093 1.893-.773 2.16-1.52.267-.747.267-1.387.187-1.52-.08-.133-.293-.213-.613-.373z"/>
        </svg>
    </a>
    <div class="wa-fab-tooltip">Chat with us on WhatsApp</div>
</div>

@stack('scripts')
<script>
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js').catch(() => {});
}
</script>
</body>
</html>
