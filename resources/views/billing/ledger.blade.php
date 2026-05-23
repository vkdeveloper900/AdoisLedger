@extends('layouts.app')
@section('title', $customer->name . ' — Ledger')

@section('content')
<div class="d-flex align-items-center mb-6">
    <a href="{{ route('customers.index') }}" class="btn btn-icon btn-text-secondary rounded-pill me-3">
        <i class="icon-base ti tabler-arrow-left"></i>
    </a>
    <div>
        <h4 class="mb-0">{{ $customer->name }}</h4>
        <small class="text-body-secondary">Party Ledger · {{ $business->name }}</small>
    </div>
    <div class="ms-auto d-flex gap-2">
        @if($customer->isVendor() && !$customer->isCustomer())
            <a href="{{ route('payments.create', ['customer_id' => $customer->id, 'mode' => 'made']) }}"
               class="btn btn-sm btn-warning">
                <i class="icon-base ti tabler-cash me-1"></i> Pay Vendor
            </a>
        @else
            <a href="{{ route('payments.create', ['customer_id' => $customer->id]) }}"
               class="btn btn-sm btn-success">
                <i class="icon-base ti tabler-cash me-1"></i> Receive Payment
            </a>
        @endif
    </div>
</div>

{{-- Summary Cards --}}
<div class="row g-4 mb-6">
    @php $isVendor = $customer->isVendor() && !$customer->isCustomer(); @endphp
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <p class="text-body-secondary small mb-1">{{ $isVendor ? 'Total Purchased' : 'Total Billed' }}</p>
                <h5 class="mb-0 text-primary">Rs.{{ number_format($totalBilled) }}</h5>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <p class="text-body-secondary small mb-1">{{ $isVendor ? 'Total Paid' : 'Total Received' }}</p>
                <h5 class="mb-0 text-success">Rs.{{ number_format($totalReceived) }}</h5>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <p class="text-body-secondary small mb-1">{{ $isVendor ? 'Pending to Pay' : 'Balance Due' }}</p>
                <h5 class="mb-0 {{ abs($balance) > 0 ? ($isVendor ? 'text-warning' : 'text-danger') : 'text-success' }}">
                    Rs.{{ number_format(abs($balance)) }}
                    @if(abs($balance) > 0) {{ $isVendor ? 'Cr' : 'Dr' }} @else (Settled) @endif
                </h5>
            </div>
        </div>
    </div>
</div>

{{-- Ledger Table --}}
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Transaction History</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th class="text-end">Debit (Due)</th>
                    <th class="text-end">Credit (Paid)</th>
                    <th class="text-end">Balance</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($entries as $entry)
                    <tr>
                        <td>{{ $entry->date->format('d M Y') }}</td>
                        <td>
                            {{ $entry->description }}
                            <span class="badge bg-label-secondary ms-1 rounded-pill small">
                                {{ $entry->account_head }}
                            </span>
                        </td>
                        <td class="text-end">
                            {{ $entry->debit > 0 ? '₹'.number_format($entry->debit) : '—' }}
                        </td>
                        <td class="text-end text-success">
                            {{ $entry->credit > 0 ? '₹'.number_format($entry->credit) : '—' }}
                        </td>
                        <td class="text-end fw-medium {{ $entry->running_balance > 0 ? 'text-danger' : 'text-success' }}">
                            ₹{{ number_format(abs($entry->running_balance)) }}
                            {{ $entry->running_balance > 0 ? 'Dr' : ($entry->running_balance < 0 ? 'Cr' : '') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-body-secondary">No transactions yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
