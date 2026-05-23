@extends('layouts.app')
@section('title', 'Balance Sheet')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-6">
    <h4 class="mb-0">Balance Sheet</h4>
    <div class="d-flex gap-2">
        <span class="badge bg-label-danger rounded-pill px-3 py-2">
            Receivable: Rs.{{ number_format($receivables->sum(fn($c) => abs($c->balance))) }}
        </span>
        <span class="badge bg-label-warning rounded-pill px-3 py-2">
            Payable: Rs.{{ number_format($payables->sum(fn($c) => abs($c->balance))) }}
        </span>
    </div>
</div>

{{-- ── Receivables (customers owe us) ── --}}
<div class="card mb-6">
    <div class="card-header d-flex align-items-center gap-2">
        <span class="avatar avatar-sm">
            <span class="avatar-initial rounded bg-label-danger">
                <i class="icon-base ti tabler-arrow-down icon-sm"></i>
            </span>
        </span>
        <div>
            <h5 class="card-title mb-0">Receivable</h5>
            <small class="text-body-secondary">Customers who owe us money</small>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Customer</th>
                    <th>Mobile</th>
                    <th class="text-end">Total Billed</th>
                    <th class="text-end">Total Received</th>
                    <th class="text-end">Balance Due</th>
                </tr>
            </thead>
            <tbody>
                @forelse($receivables as $c)
                <tr class="cursor-pointer" onclick="window.location='{{ route('reports.balance-sheet.customer', $c) }}'">
                    <td class="fw-medium">{{ $c->name }}</td>
                    <td class="text-body-secondary">{{ $c->mobile ?? '—' }}</td>
                    <td class="text-end">Rs.{{ number_format($c->total_debit ?? 0) }}</td>
                    <td class="text-end text-success">Rs.{{ number_format($c->total_credit ?? 0) }}</td>
                    <td class="text-end fw-semibold {{ $c->balance > 0 ? 'text-danger' : 'text-success' }}">
                        Rs.{{ number_format(abs($c->balance)) }}
                        {{ $c->balance > 0 ? 'Dr' : 'Settled' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-6 text-body-secondary">No receivables.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── Payables (we owe vendors) ── --}}
@if($payables->isNotEmpty())
<div class="card">
    <div class="card-header d-flex align-items-center gap-2">
        <span class="avatar avatar-sm">
            <span class="avatar-initial rounded bg-label-warning">
                <i class="icon-base ti tabler-arrow-up icon-sm"></i>
            </span>
        </span>
        <div>
            <h5 class="card-title mb-0">Payable</h5>
            <small class="text-body-secondary">Vendors / Farmers we owe money to</small>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Vendor / Farmer</th>
                    <th>Mobile</th>
                    <th class="text-end">Total Purchased</th>
                    <th class="text-end">Total Paid</th>
                    <th class="text-end">Pending to Pay</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payables as $c)
                <tr class="cursor-pointer" onclick="window.location='{{ route('reports.balance-sheet.customer', $c) }}'">
                    <td class="fw-medium">{{ $c->name }}</td>
                    <td class="text-body-secondary">{{ $c->mobile ?? '—' }}</td>
                    <td class="text-end">Rs.{{ number_format($c->total_debit ?? 0) }}</td>
                    <td class="text-end text-success">Rs.{{ number_format($c->total_credit ?? 0) }}</td>
                    <td class="text-end fw-semibold text-warning">
                        Rs.{{ number_format(abs($c->balance)) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
