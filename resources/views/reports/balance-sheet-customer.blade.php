@extends('layouts.app')
@section('title', $customer->name . ' — Balance Sheet')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('reports.balance-sheet') }}" class="btn btn-icon btn-text-secondary rounded-pill me-3">
        <i class="icon-base ti tabler-arrow-left"></i>
    </a>
    <div>
        <h4 class="mb-0">{{ $customer->name }}</h4>
        <small class="text-body-secondary">Customer Balance Details & Transaction History</small>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row g-4 mb-4">
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <p class="text-body-secondary small mb-1">Total Billed</p>
                <h5 class="mb-0 text-primary">₹{{ number_format($entries->sum('debit')) }}</h5>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <p class="text-body-secondary small mb-1">Total Received</p>
                <h5 class="mb-0 text-success">₹{{ number_format($entries->sum('credit')) }}</h5>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <p class="text-body-secondary small mb-1">Balance Outstanding</p>
                <h5 class="mb-0 {{ $balance > 0 ? 'text-danger' : ($balance < 0 ? 'text-success' : '') }}">
                    ₹{{ number_format(abs($balance)) }}
                    {{ $balance > 0 ? '(Due)' : ($balance < 0 ? '(Advance)' : '') }}
                </h5>
            </div>
        </div>
    </div>
</div>

{{-- Ledger Table --}}
<div class="card">
    <div class="card-header border-bottom">
        <h5 class="card-title mb-0">Transaction History</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th class="text-end">Debit (Due)</th>
                    <th class="text-end">Credit (Paid)</th>
                    <th class="text-end">Running Balance</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($entries as $entry)
                    <tr @if($entry->transaction_id) 
                            class="cursor-pointer" 
                            onclick="showBillDetails('{{ route('reports.balance-sheet.bill', $entry->transaction_id) }}')" 
                            data-bs-toggle="offcanvas" 
                            data-bs-target="#billOffcanvas"
                        @endif>
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
                        <td class="text-end fw-medium {{ $entry->running_balance > 0 ? 'text-danger' : ($entry->running_balance < 0 ? 'text-success' : '') }}">
                            ₹{{ number_format(abs($entry->running_balance)) }}
                            {{ $entry->running_balance > 0 ? 'Dr' : ($entry->running_balance < 0 ? 'Cr' : '') }}
                        </td>
                        <td class="text-center">
                            @if($entry->transaction_id)
                                <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                                    <i class="icon-base ti tabler-eye"></i>
                                </button>
                            @else
                                <span class="text-body-secondary small">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-body-secondary">No transactions found for this customer.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Offcanvas for Bill Details --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="billOffcanvas" aria-labelledby="billOffcanvasLabel">
    <div class="offcanvas-header border-bottom">
        <h5 id="billOffcanvasLabel" class="offcanvas-title">Bill Details</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body" id="billOffcanvasBody">
        <!-- Loaded via AJAX -->
    </div>
</div>

@push('scripts')
<script>
    function showBillDetails(url) {
        const body = document.getElementById('billOffcanvasBody');
        body.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;
        
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                body.innerHTML = html;
            })
            .catch(err => {
                body.innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        Failed to load bill details. Please try again.
                    </div>
                `;
            });
    }
</script>
@endpush
@endsection
