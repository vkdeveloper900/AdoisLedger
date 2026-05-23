@extends('layouts.app')
@section('title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}">
@endpush

@section('content')

{{-- ── Header ── --}}
<div class="d-flex align-items-center justify-content-between mb-6">
    <div>
        <h4 class="mb-1">{{ $profile ? $profile->name : 'Overall Dashboard' }}</h4>
        <p class="mb-0 text-body-secondary">
            {{ $profile ? $profile->business_type_label . ' · ' . now()->format('F Y') : 'All businesses combined · ' . now()->format('F Y') }}
        </p>
    </div>
    @if (!$profile)
        <a href="{{ route('settings.business-profiles.index') }}" class="btn btn-sm btn-label-primary">
            <i class="icon-base ti tabler-building-store me-1"></i> Manage Businesses
        </a>
    @endif
</div>

{{-- ── Business Selector (when no active business) ── --}}
@if (!$profile && $profiles->isNotEmpty())
<div class="row g-4 mb-6">
    @foreach ($profiles as $p)
    <div class="col-md-4">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="avatar">
                    <span class="avatar-initial rounded bg-label-primary">
                        <i class="icon-base ti tabler-building-store icon-md"></i>
                    </span>
                </div>
                <div class="flex-grow-1 min-w-0">
                    <h6 class="mb-0 text-truncate">{{ $p->name }}</h6>
                    <small class="text-body-secondary">{{ $p->business_type_label }}</small>
                </div>
                <form method="POST" action="{{ route('shop.enter', $p) }}">
                    @csrf
                    <button class="btn btn-sm btn-primary">Enter</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- ── Stat Cards ── --}}
<div class="row g-6 mb-6">

    {{-- Sales This Month --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="mb-1 text-body-secondary">Sales This Month</p>
                        <h4 class="mb-1">Rs.{{ number_format($totalSalesMonth) }}</h4>
                        <small class="text-body-secondary">All time: Rs.{{ number_format($totalSalesAllTime) }}</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="icon-base ti tabler-file-invoice icon-md"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Outstanding Receivable --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="mb-1 text-body-secondary">Outstanding (To Receive)</p>
                        <h4 class="mb-1 {{ $totalReceivable > 0 ? 'text-danger' : 'text-success' }}">
                            Rs.{{ number_format($totalReceivable) }}
                        </h4>
                        <small class="text-body-secondary">Customer balance due</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-danger">
                            <i class="icon-base ti tabler-clock-dollar icon-md"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Payments Received This Month --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="mb-1 text-body-secondary">Received This Month</p>
                        <h4 class="mb-1 text-success">Rs.{{ number_format($paymentsReceivedMonth) }}</h4>
                        <small class="text-body-secondary">Payments collected</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="icon-base ti tabler-cash icon-md"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Customers / Dairy: Purchases --}}
    @if ($isDairy)
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="mb-1 text-body-secondary">Purchases This Month</p>
                        <h4 class="mb-1">Rs.{{ number_format($totalPurchasesMonth) }}</h4>
                        <small class="{{ $totalPayable > 0 ? 'text-danger' : 'text-success' }}">
                            Pending to pay: Rs.{{ number_format($totalPayable) }}
                        </small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="icon-base ti tabler-basket icon-md"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="mb-1 text-body-secondary">Customers</p>
                        <h4 class="mb-1">{{ number_format($customersCount) }}</h4>
                        <small class="text-body-secondary">Active in this business</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="icon-base ti tabler-users icon-md"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

{{-- ── Charts Row ── --}}
<div class="row g-6 mb-6">

    {{-- Monthly Revenue — last 6 months --}}
    <div class="col-xl-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title mb-0">Monthly Revenue</h5>
                    <small class="text-body-secondary">Last 6 months</small>
                </div>
            </div>
            <div class="card-body">
                <div id="monthlyRevenueChart"></div>
            </div>
        </div>
    </div>

    {{-- Top Outstanding Customers --}}
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Top Outstanding</h5>
                <small class="text-body-secondary">Customers with highest balance due</small>
            </div>
            <div class="card-body pt-2">
                @forelse ($topOutstanding as $row)
                    @php $cust = $row->customer; @endphp
                    <div class="d-flex align-items-center justify-content-between py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar avatar-sm">
                                <span class="avatar-initial rounded-circle bg-label-danger">
                                    {{ strtoupper(substr($cust?->name ?? '?', 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <p class="mb-0 fw-medium">{{ $cust?->name ?? 'Unknown' }}</p>
                                @if($cust?->mobile)
                                    <small class="text-body-secondary">{{ $cust->mobile }}</small>
                                @endif
                            </div>
                        </div>
                        <span class="badge bg-label-danger rounded-pill">Rs.{{ number_format($row->total_balance) }}</span>
                    </div>
                @empty
                    <div class="text-center py-8 text-body-secondary">
                        <i class="icon-base ti tabler-check icon-lg mb-2 d-block text-success"></i>
                        All settled!
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

{{-- ── Daily Sales Chart ── --}}
<div class="row g-6 mb-6">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title mb-0">Daily Sales</h5>
                    <small class="text-body-secondary">Last 30 days</small>
                </div>
            </div>
            <div class="card-body">
                <div id="dailySalesChart"></div>
            </div>
        </div>
    </div>
</div>

{{-- ── Recent Transactions ── --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0">Recent Transactions</h5>
        @if ($profile)
            <a href="{{ route('bills.index') }}" class="btn btn-sm btn-label-primary">View All</a>
        @endif
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Bill #</th>
                    <th>Date</th>
                    @if (!$profile)<th>Business</th>@endif
                    <th>Party</th>
                    <th>Type</th>
                    <th class="text-end">Total</th>
                    <th class="text-end">Balance</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentTransactions as $tx)
                <tr>
                    <td class="fw-medium">{{ $tx->bill_number }}</td>
                    <td>{{ $tx->date->format('d M Y') }}</td>
                    @if (!$profile)<td>{{ $tx->business?->name ?? '—' }}</td>@endif
                    <td>{{ $tx->customer?->name ?? '—' }}</td>
                    <td>
                        <span class="badge bg-label-{{ $tx->type->value === 'dairy_purchase' ? 'warning' : 'info' }} rounded-pill">
                            {{ $tx->type->label() }}
                        </span>
                    </td>
                    <td class="text-end fw-medium">Rs.{{ number_format($tx->total_amount) }}</td>
                    <td class="text-end {{ $tx->balance > 0 ? 'text-danger fw-medium' : 'text-success' }}">
                        Rs.{{ number_format($tx->balance) }}
                    </td>
                    <td>
                        <span class="badge bg-label-{{ $tx->status === 'posted' ? 'success' : 'warning' }} rounded-pill">
                            {{ ucfirst($tx->status) }}
                        </span>
                    </td>
                    <td>
                        @if ($tx->type->value === 'dairy_purchase')
                            <a href="{{ route('purchases.show', $tx) }}" class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                                <i class="icon-base ti tabler-eye"></i>
                            </a>
                        @else
                            <a href="{{ route('bills.show', $tx) }}" class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                                <i class="icon-base ti tabler-eye"></i>
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ $profile ? 8 : 9 }}" class="text-center py-8 text-body-secondary">
                        No transactions yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>
const isDarkMode = () => document.documentElement.getAttribute('data-bs-theme') === 'dark';
const textColor  = () => isDarkMode() ? '#b0b8c4' : '#697a8d';
const gridColor  = () => isDarkMode() ? '#3d4459' : '#e5e7eb';

// ── Monthly Revenue Chart ──
@php
    $months = collect();
    for ($i = 5; $i >= 0; $i--) {
        $months->push(now()->subMonths($i)->format('Y-m'));
    }
    $monthLabels  = $months->map(fn($m) => \Carbon\Carbon::createFromFormat('Y-m', $m)->format('M Y'))->toArray();
    $monthValues  = $months->map(fn($m) => (int) ($monthlyRevenue[$m] ?? 0))->toArray();
@endphp

new ApexCharts(document.getElementById('monthlyRevenueChart'), {
    chart:  { type: 'bar', height: 260, toolbar: { show: false }, parentHeightOffset: 0 },
    series: [{ name: 'Revenue', data: @json($monthValues) }],
    xaxis:  { categories: @json($monthLabels), labels: { style: { colors: textColor() } } },
    yaxis:  { labels: { formatter: v => 'Rs.' + Number(v).toLocaleString('en-IN'), style: { colors: textColor() } } },
    grid:   { borderColor: gridColor() },
    colors: ['#696cff'],
    plotOptions: { bar: { borderRadius: 6, columnWidth: '45%' } },
    dataLabels: { enabled: false },
    tooltip: { y: { formatter: v => 'Rs.' + Number(v).toLocaleString('en-IN') } },
}).render();

// ── Daily Sales Chart ──
@php
    $days       = collect();
    $dayValues  = [];
    $dayLabels  = [];
    for ($i = 29; $i >= 0; $i--) {
        $d = now()->subDays($i)->toDateString();
        $dayLabels[] = now()->subDays($i)->format('d M');
        $dayValues[] = (int) ($dailySales[$d] ?? 0);
    }
@endphp

new ApexCharts(document.getElementById('dailySalesChart'), {
    chart:  { type: 'area', height: 220, toolbar: { show: false }, parentHeightOffset: 0 },
    series: [{ name: 'Sales', data: @json($dayValues) }],
    xaxis:  {
        categories: @json($dayLabels),
        tickAmount: 8,
        labels: { style: { colors: textColor() } }
    },
    yaxis:  { labels: { formatter: v => 'Rs.' + Number(v).toLocaleString('en-IN'), style: { colors: textColor() } } },
    grid:   { borderColor: gridColor() },
    colors: ['#71dd37'],
    fill:   { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05 } },
    stroke: { width: 2, curve: 'smooth' },
    dataLabels: { enabled: false },
    tooltip: { y: { formatter: v => 'Rs.' + Number(v).toLocaleString('en-IN') } },
}).render();
</script>
@endpush