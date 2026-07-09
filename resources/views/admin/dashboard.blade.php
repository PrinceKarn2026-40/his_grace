@extends('layouts.admin')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@push('styles')
<style>
    .stat-card { transition: transform .2s; }
    .stat-card:hover { transform: translateY(-2px); }
    .welcome-strip {
        background: linear-gradient(135deg, #0f0f0f 0%, #2d0a0a 100%);
        border-radius: 16px; padding: 1.5rem 2rem; margin-bottom: 1.5rem;
        position: relative; overflow: hidden;
    }
    .welcome-strip::before {
        content: ''; position: absolute; right: -30px; top: -30px;
        width: 180px; height: 180px; border-radius: 50%;
        background: radial-gradient(circle, rgba(192,57,43,.3) 0%, transparent 70%);
    }
    .trend-up { color: #198754; font-size: .75rem; }
    .trend-down { color: #dc3545; font-size: .75rem; }
    .section-header { font-size: .7rem; text-transform: uppercase; letter-spacing: .1em; color: #999; font-weight: 600; margin-bottom: .75rem; }
</style>
@endpush

@section('content')

{{-- Welcome Strip --}}
<div class="welcome-strip">
    <div class="position-relative" style="z-index:1;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <p class="mb-1" style="color:rgba(255,255,255,.4);font-size:.75rem;text-transform:uppercase;letter-spacing:.1em;">Admin Panel</p>
                <h5 class="fw-bold mb-0 text-white" style="font-family:'Playfair Display',serif;">Welcome back, {{ auth()->user()->name }}!</h5>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-sm px-4 py-2 fw-semibold" style="background:var(--red);color:#fff;border-radius:50px;border:none;">
                <i class="bi bi-plus me-1"></i>Add Product
            </a>
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card stat-card shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small mb-1">Total Revenue</p>
                    <h4 class="fw-bold mb-1" style="color:var(--red);font-size:1.2rem;">RWF {{ number_format($stats['total_revenue'], 0) }}</h4>
                    <span class="trend-up"><i class="bi bi-arrow-up-short"></i>Paid orders</span>
                </div>
                <div class="stat-icon" style="background:rgba(192,57,43,.1);"><i class="bi bi-cash-stack" style="color:var(--red)"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small mb-1">Total Orders</p>
                    <h4 class="fw-bold mb-1">{{ $stats['total_orders'] }}</h4>
                    <span class="text-muted" style="font-size:.75rem;">{{ $ordersByStatus['pending'] ?? 0 }} pending</span>
                </div>
                <div class="stat-icon" style="background:rgba(13,110,253,.1);"><i class="bi bi-bag-check" style="color:#0d6efd"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small mb-1">Products</p>
                    <h4 class="fw-bold mb-1">{{ $stats['total_products'] }}</h4>
                    <a href="{{ route('admin.products') }}" class="text-decoration-none" style="font-size:.75rem;color:var(--red)">Manage →</a>
                </div>
                <div class="stat-icon" style="background:rgba(25,135,84,.1);"><i class="bi bi-box-seam" style="color:#198754"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small mb-1">Customers</p>
                    <h4 class="fw-bold mb-1">{{ $stats['total_users'] }}</h4>
                    <a href="{{ route('admin.customers') }}" class="text-decoration-none" style="font-size:.75rem;color:var(--red)">View all →</a>
                </div>
                <div class="stat-icon" style="background:rgba(220,53,69,.1);"><i class="bi bi-people" style="color:#dc3545"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Revenue Chart --}}
    <div class="col-lg-8">
        <div class="card stat-card shadow-sm p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Revenue Overview</h6>
                <span class="badge bg-light text-muted border">Last 6 months</span>
            </div>
            <canvas id="revenueChart" height="90"></canvas>
        </div>
    </div>

    {{-- Orders by Status --}}
    <div class="col-lg-4">
        <div class="card stat-card shadow-sm p-4 h-100">
            <h6 class="fw-bold mb-3">Orders by Status</h6>
            <canvas id="statusChart" height="160"></canvas>
            <div class="mt-3">
                @php $statusColors = ['pending'=>'#ffc107','paid'=>'#198754','shipped'=>'#0dcaf0','delivered'=>'#0d6efd','cancelled'=>'#dc3545']; @endphp
                @foreach($ordersByStatus as $status => $count)
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div class="d-flex align-items-center gap-2">
                        <span style="width:10px;height:10px;border-radius:50%;background:{{ $statusColors[$status] ?? '#aaa' }};display:inline-block;"></span>
                        <span class="small">{{ ucfirst($status) }}</span>
                    </div>
                    <span class="fw-semibold small">{{ $count }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Recent Orders --}}
    <div class="col-lg-8">
        <div class="card stat-card shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Recent Orders</h6>
                <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>#</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th><th></th></tr></thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        @php $colors = ['pending'=>'warning','paid'=>'success','shipped'=>'info','delivered'=>'primary','cancelled'=>'danger']; @endphp
                        <tr>
                            <td class="fw-semibold">#{{ $order->id }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td style="color:var(--red)" class="fw-semibold">RWF {{ number_format($order->total, 0) }}</td>
                            <td><span class="badge bg-{{ $colors[$order->status] ?? 'secondary' }}">{{ ucfirst($order->status) }}</span></td>
                            <td class="text-muted">{{ $order->created_at->format('M d') }}</td>
                            <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary py-0 rounded-pill px-2">View</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Top Products --}}
    <div class="col-lg-4">
        <div class="card stat-card shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Top Products</h6>
                <a href="{{ route('admin.reports') }}" class="text-decoration-none small" style="color:var(--red)">Reports →</a>
            </div>
            @forelse($topProducts as $i => $p)
            <div class="d-flex align-items-center gap-3 mb-3">
                <span class="fw-bold text-muted" style="width:20px;font-size:.85rem;">{{ $i+1 }}</span>
                <div class="flex-grow-1">
                    <p class="mb-0 fw-semibold small">{{ Str::limit($p->name, 24) }}</p>
                    <div class="progress mt-1" style="height:3px;border-radius:2px;">
                        @php $maxSold = $topProducts->max('sold') ?: 1; @endphp
                        <div class="progress-bar" style="width:{{ ($p->sold/$maxSold)*100 }}%;background:var(--red);"></div>
                    </div>
                </div>
                <span class="small text-muted">{{ $p->sold ?? 0 }}</span>
            </div>
            @empty
            <p class="text-muted small">No sales data yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const rd = @json($revenueChart);
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: rd.map(d => months[d.month-1] + ' ' + d.year),
        datasets: [{
            label: 'Revenue (RWF)',
            data: rd.map(d => d.revenue),
            borderColor: '#c0392b', backgroundColor: 'rgba(192,57,43,.08)',
            borderWidth: 2, fill: true, tension: .4, pointRadius: 4,
            pointBackgroundColor: '#c0392b'
        }]
    },
    options: {
        responsive: true, plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { callback: v => 'RWF ' + v.toLocaleString() } } }
    }
});

const sd = @json($ordersByStatus);
const statusColors = { pending:'#ffc107', paid:'#198754', shipped:'#0dcaf0', delivered:'#0d6efd', cancelled:'#dc3545' };
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(sd).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
        datasets: [{ data: Object.values(sd), backgroundColor: Object.keys(sd).map(s => statusColors[s] || '#aaa'), borderWidth: 0 }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, cutout: '65%' }
});
</script>
@endpush
