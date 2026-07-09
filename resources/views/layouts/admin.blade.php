<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — HisGrace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --red: #c0392b; --red-light: #e74c3c; --dark: #0f0f0f; --sidebar-w: 248px; }
        body { font-family: 'Inter', sans-serif; background: #f5f5f5; }

        #sidebar {
            width: var(--sidebar-w); min-height: 100vh; background: var(--dark);
            position: fixed; top: 0; left: 0; z-index: 1000;
            display: flex; flex-direction: column; transition: transform .3s;
            border-right: 1px solid rgba(255,255,255,.04);
        }
        #sidebar .brand {
            font-family: 'Playfair Display', serif; font-size: 1.45rem; color: #fff;
            padding: 1.4rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,.06);
            display: flex; align-items: center; gap: .75rem;
        }
        #sidebar .brand span { color: var(--red-light); }
        #sidebar .brand-badge {
            font-size: .6rem; font-family: Inter; font-weight: 600; letter-spacing: .1em;
            text-transform: uppercase; background: rgba(192,57,43,.2); color: var(--red-light);
            border: 1px solid rgba(192,57,43,.3); border-radius: 4px; padding: .15rem .4rem;
        }
        #sidebar .nav-link {
            color: rgba(255,255,255,.45); font-size: .82rem; font-weight: 500;
            padding: .6rem 1.5rem; display: flex; align-items: center; gap: .75rem;
            border-left: 3px solid transparent; transition: .15s; text-decoration: none;
        }
        #sidebar .nav-link:hover, #sidebar .nav-link.active {
            color: #fff; background: rgba(255,255,255,.05); border-left-color: var(--red);
        }
        #sidebar .nav-link i { font-size: .95rem; width: 18px; text-align: center; }
        #sidebar .nav-section { font-size: .67rem; text-transform: uppercase; letter-spacing: .12em; color: rgba(255,255,255,.18); padding: .85rem 1.5rem .3rem; }
        #sidebar .sidebar-footer { margin-top: auto; padding: 1rem 1.5rem; border-top: 1px solid rgba(255,255,255,.06); }

        #main { margin-left: var(--sidebar-w); min-height: 100vh; }
        .topbar { background: #fff; border-bottom: 1px solid #ebebeb; padding: .7rem 1.5rem; position: sticky; top: 0; z-index: 999; }
        .page-content { padding: 1.75rem; }

        .stat-card { border: none; border-radius: 14px; background: #fff; box-shadow: 0 1px 4px rgba(0,0,0,.06); }
        .stat-icon { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
        .table th { font-size: .75rem; text-transform: uppercase; letter-spacing: .06em; color: #999; font-weight: 600; }
        .table td { font-size: .875rem; vertical-align: middle; }

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
        <span>His<span>Grace</span></span>
        <span class="brand-badge">Admin</span>
    </div>

    <nav class="mt-2 flex-grow-1">
        <div class="nav-section">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="nav-section">Catalogue</div>
        <a href="{{ route('admin.products') }}" class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Products
        </a>
        <a href="{{ route('admin.categories') }}" class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i> Categories
        </a>

        <div class="nav-section">Sales</div>
        <a href="{{ route('admin.orders') }}" class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
            <i class="bi bi-bag-check"></i> Orders
            @php $pending = \App\Models\Order::where('status','pending')->count(); @endphp
            @if($pending > 0)<span class="badge bg-warning text-dark ms-auto">{{ $pending }}</span>@endif
        </a>
        <a href="{{ route('admin.payments') }}" class="nav-link {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
            <i class="bi bi-phone"></i> MoMo Payments
            @php $pendingPay = \App\Models\Payment::where('status','pending')->count(); @endphp
            @if($pendingPay > 0)<span class="badge bg-danger ms-auto">{{ $pendingPay }}</span>@endif
        </a>
        <a href="{{ route('admin.customers') }}" class="nav-link {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Customers
        </a>

        <div class="nav-section">Insights</div>
        <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> Reports
        </a>

        <div class="nav-section">System</div>
        <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i> Settings
        </a>
        <a href="{{ route('home') }}" class="nav-link" target="_blank">
            <i class="bi bi-shop"></i> View Store
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                style="width:34px;height:34px;background:var(--red);font-size:.82rem;flex-shrink:0;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <p class="mb-0 text-white fw-semibold" style="font-size:.8rem;">{{ auth()->user()->name }}</p>
                <p class="mb-0" style="font-size:.7rem;color:rgba(255,255,255,.3);">Administrator</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-sm w-100" style="background:rgba(192,57,43,.12);color:var(--red-light);border:1px solid rgba(192,57,43,.2);font-size:.78rem;">
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
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none" style="color:var(--red)">Admin</a></li>
                    <li class="breadcrumb-item active">@yield('breadcrumb', 'Dashboard')</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-3">
            @php $unread = auth()->user()->unreadNotifications->count(); @endphp
            <a href="{{ route('notifications') }}" class="text-dark position-relative">
                <i class="bi bi-bell fs-5"></i>
                @if($unread > 0)<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:.6rem;">{{ $unread }}</span>@endif
            </a>
            <span class="text-muted small d-none d-md-inline">{{ now()->format('M d, Y') }}</span>
            <div class="dropdown">
                <button class="btn btn-sm d-flex align-items-center gap-2 border rounded-pill px-3" data-bs-toggle="dropdown">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                        style="width:28px;height:28px;background:var(--red);font-size:.75rem;flex-shrink:0;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="small fw-semibold d-none d-md-inline">{{ auth()->user()->name }}</span>
                    <i class="bi bi-chevron-down" style="font-size:.7rem;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="min-width:180px;">
                    <li><h6 class="dropdown-header">{{ auth()->user()->email }}</h6></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('admin.settings') }}"><i class="bi bi-gear me-2"></i>Settings</a></li>
                    <li><a class="dropdown-item" href="{{ route('home') }}" target="_blank"><i class="bi bi-shop me-2"></i>View Store</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger fw-semibold">
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
