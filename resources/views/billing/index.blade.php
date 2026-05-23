@extends('layouts.app')
@section('title', 'Bills')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-6">
        <div>
            <h4 class="mb-1">Bills</h4>
            <p class="mb-0 text-body-secondary">{{ $bills->total() }} total bills</p>
        </div>
        <a href="{{ route('bills.create') }}" class="btn btn-primary">
            <i class="icon-base ti tabler-plus me-1"></i> New Bill
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
                <div class="col-md-4">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="icon-base ti tabler-search"></i></span>
                        <input type="text" name="search" class="form-control"
                               placeholder="Bill no, customer…" value="{{ request('search') }}" />
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        @foreach (\App\Enums\TransactionType::cases() as $t)
                            <option value="{{ $t->value }}" {{ request('type') === $t->value ? 'selected' : '' }}>
                                {{ $t->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    @if (request('search') || request('type'))
                        <a href="{{ route('bills.index') }}" class="btn btn-label-secondary">Clear</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Bill #</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Type</th>
                        <th class="text-end">Total</th>
                        <th class="text-end">Received</th>
                        <th class="text-end">Balance</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bills as $bill)
                        <tr>
                            <td class="fw-medium">{{ $bill->bill_number }}</td>
                            <td>{{ $bill->date->format('d M Y') }}</td>
                            <td>{{ $bill->customer?->name ?? '—' }}</td>
                            <td><span class="badge bg-label-info rounded-pill">{{ $bill->type->label() }}</span></td>
                            <td class="text-end fw-medium">₹{{ number_format($bill->total_amount) }}</td>
                            <td class="text-end text-success">₹{{ number_format($bill->amount_received) }}</td>
                            <td class="text-end {{ $bill->balance > 0 ? 'text-danger fw-medium' : 'text-success' }}">
                                ₹{{ number_format($bill->balance) }}
                            </td>
                            <td>
                                <span class="badge bg-label-{{ $bill->status === 'posted' ? 'success' : 'warning' }} rounded-pill">
                                    {{ ucfirst($bill->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('bills.show', $bill) }}"
                                   class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                                    <i class="icon-base ti tabler-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-8 text-body-secondary">
                                No bills found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($bills->hasPages())
            <div class="card-footer d-flex justify-content-end">
                {{ $bills->links() }}
            </div>
        @endif
    </div>
@endsection
