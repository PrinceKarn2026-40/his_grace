@extends('layouts.admin')
@section('title', 'Reports')
@section('breadcrumb', 'Reports')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Reports & Analytics</h4>
    <span class="text-muted small">{{ now()->format('F Y') }}</span>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card shadow-sm border-0 p-3 text-center">
            <p class="text-muted mb-1" style="font-size:.72rem;">Today's Revenue</p>
            <h5 class="fw-bold mb-0" style="color:var(--gold)">RWF {{ number_format($summary['revenue_today'], 2) }}</h5>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card shadow-sm border-0 p-3 text-center">
            <p class="text-muted mb-1" style="font-size:.72rem;">This Month</p>
            <h5 class="fw-bold mb-0" style="color:var(--gold)">RWF {{ number_format($summary['revenue_month'], 2) }}</h5>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card shadow-sm border-0 p-3 text-center">
            <p class="text-muted mb-1" style="font-size:.72rem;">Total Revenue</p>
            <h5 class="fw-bold mb-0" style="color:var(--gold)">RWF {{ number_format($summary['revenue_total'], 2) }}</h5>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card shadow-sm border-0 p-3 text-center">
            <p class="text-muted mb-1" style="font-size:.72rem;">Orders Today</p>
            <h5 class="fw-bold mb-0">{{ $summary['orders_today'] }}</h5>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card shadow-sm border-0 p-3 text-center">
            <p class="text-muted mb-1" style="font-size:.72rem;">Avg Order Value</p>
            <h5 class="fw-bold mb-0">RWF {{ number_format($summary['avg_order_value'], 2) }}</h5>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card shadow-sm border-0 p-3 text-center">
            <p class="text-muted mb-1" style="font-size:.72rem;">New Customers</p>
            <h5 class="fw-bold mb-0">{{ $summary['new_customers'] }}</h5>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    {{-- Monthly Revenue Chart --}}
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 p-4">
            <h6 class="fw-bold mb-3">Monthly Revenue (Last 12 Months)</h6>
            <canvas id="revenueChart" height="100"></canvas>
        </div>
    </div>

    {{-- Orders by Status --}}
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 p-4">
            <h6 class="fw-bold mb-3">Orders by Status</h6>
            <canvas id="statusChart" height="200"></canvas>
            <div class="mt-3">
                @php $statusColors = ['pending'=>'#ffc107','paid'=>'#198754','shipped'=>'#0dcaf0','delivered'=>'#0d6efd','cancelled'=>'#dc3545']; @endphp
                @foreach($ordersByStatus as $status => $count)
                <div class="d-flex justify-content-between mb-1">
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

<div class="row g-4">
    {{-- Top Products --}}
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 p-4">
            <h6 class="fw-bold mb-3">Top 10 Products by Sales</h6>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light"><tr><th>#</th><th>Product</th><th>Sold</th></tr></thead>
                    <tbody>
                        @forelse($topProducts as $i => $p)
                        <tr>
                            <td class="text-muted">{{ $i+1 }}</td>
                            <td>
                                <p class="mb-0 fw-semibold small">{{ Str::limit($p->name, 28) }}</p>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @php $maxSold = $topProducts->max('sold') ?: 1; @endphp
                                    <div class="progress flex-grow-1" style="height:6px;">
                                        <div class="progress-bar" style="width:{{ ($p->sold/$maxSold)*100 }}%;background:var(--gold);"></div>
                                    </div>
                                    <span class="small fw-semibold">{{ $p->sold ?? 0 }}</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-muted text-center py-3">No sales data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Top Categories --}}
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 p-4">
            <h6 class="fw-bold mb-3">Categories Overview</h6>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light"><tr><th>Category</th><th>Products</th><th>Units Sold</th></tr></thead>
                    <tbody>
                        @forelse($topCategories as $cat)
                        <tr>
                            <td class="fw-semibold small">{{ $cat->name }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $cat->products_count }}</span></td>
                            <td class="fw-semibold small" style="color:var(--gold)">{{ $cat->total_sold ?? 0 }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-muted text-center py-3">No data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const rd = @json($monthlyRevenue);
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: rd.map(d => months[d.month-1] + ' ' + d.year),
        datasets: [{
            label: 'Revenue (RWF)',
            data: rd.map(d => d.revenue),
            backgroundColor: 'rgba(201,168,76,.75)',
            borderColor: '#c9a84c', borderWidth: 2, borderRadius: 6
        }]
    },
    options: {
        responsive: true, plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { callback: v => 'RWF ' + v } } }
    }
});

const sd = @json($ordersByStatus);
const statusColors = { pending:'#ffc107', paid:'#198754', shipped:'#0dcaf0', delivered:'#0d6efd', cancelled:'#dc3545' };
new Chart(document.getElementById('statusChart'), {
    type: 'pie',
    data: {
        labels: Object.keys(sd).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
        datasets: [{ data: Object.values(sd), backgroundColor: Object.keys(sd).map(s => statusColors[s] || '#aaa'), borderWidth: 0 }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});
</script>
@endpush
