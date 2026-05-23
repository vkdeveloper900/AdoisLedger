@extends('layouts.app')
@section('title', 'Milk Purchases')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-6">
        <div>
            <h4 class="mb-1">Milk Purchases</h4>
            <p class="mb-0 text-body-secondary">{{ $purchases->total() }} total purchases</p>
        </div>
        <a href="{{ route('purchases.create') }}" class="btn btn-primary">
            <i class="icon-base ti tabler-plus me-1"></i> New Purchase
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible mb-6" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header border-bottom">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="icon-base ti tabler-search"></i></span>
                        <input type="text" name="search" class="form-control"
                               placeholder="Purchase no, vendor name…" value="{{ request('search') }}" />
                    </div>
                </div>
                <div class="col-auto d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Search</button>
                    @if (request('search'))
                        <a href="{{ route('purchases.index') }}" class="btn btn-label-secondary">Clear</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Purchase #</th>
                        <th>Date</th>
                        <th>Vendor / Farmer</th>
                        <th class="text-end">Total</th>
                        <th class="text-end">Paid</th>
                        <th class="text-end">Pending</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($purchases as $purchase)
                        <tr>
                            <td class="fw-medium">{{ $purchase->bill_number }}</td>
                            <td>{{ $purchase->date->format('d M Y') }}</td>
                            <td>{{ $purchase->customer?->name ?? '—' }}</td>
                            <td class="text-end fw-medium">₹{{ number_format($purchase->total_amount) }}</td>
                            <td class="text-end text-success">₹{{ number_format($purchase->amount_received) }}</td>
                            <td class="text-end {{ $purchase->balance > 0 ? 'text-danger fw-medium' : 'text-success' }}">
                                ₹{{ number_format($purchase->balance) }}
                            </td>
                            <td>
                                <span class="badge bg-label-{{ $purchase->status === 'posted' ? 'success' : 'warning' }} rounded-pill">
                                    {{ ucfirst($purchase->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('purchases.show', $purchase) }}"
                                   class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                                    <i class="icon-base ti tabler-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-body-secondary">
                                No purchases found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($purchases->hasPages())
            <div class="card-footer d-flex justify-content-end">
                {{ $purchases->links() }}
            </div>
        @endif
    </div>
@endsection