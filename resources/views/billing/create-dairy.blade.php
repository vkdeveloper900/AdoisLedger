@extends('layouts.app')
@section('title', 'New Bill — Dairy')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}">
@endpush

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('bills.index') }}" class="btn btn-sm btn-icon btn-text-secondary rounded-pill me-2">
        <i class="icon-base ti tabler-arrow-left"></i>
    </a>
    <h5 class="mb-0">New Bill — {{ $business->name }}</h5>
    <div class="ms-auto d-flex gap-1 align-items-center">
        <a href="{{ route('bills.create', ['type' => 'dairy_sale']) }}"
           class="btn btn-sm btn-primary">Sale</a>
        <a href="{{ route('purchases.create') }}"
           class="btn btn-sm btn-label-secondary">Purchase</a>
        <button type="button" class="btn btn-sm btn-outline-primary ms-2" data-bs-toggle="offcanvas" data-bs-target="#quickCustomerOffcanvas">
            <i class="icon-base ti tabler-user-plus me-1"></i> Add Customer
        </button>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible mb-4">
        <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form method="POST" action="{{ route('bills.store') }}" id="billForm">
@csrf
<input type="hidden" name="type" value="{{ $type }}">

<div class="row invoice-add">

    {{-- LEFT --}}
    <div class="col-lg-9 col-12 mb-lg-0 mb-6">
        <div class="card invoice-preview-card">

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Customer <span class="text-danger">*</span></label>
                        <select name="customer_id" id="customer_id"
                                class="form-select @error('customer_id') is-invalid @enderror" required>
                            <option value="">Search by name or mobile…</option>
                            @foreach ($customers['in_business'] as $c)
                                <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}{{ $c->mobile ? ' · '.$c->mobile : '' }}
                                </option>
                            @endforeach
                            @foreach ($customers['others'] as $c)
                                <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}{{ $c->mobile ? ' · '.$c->mobile : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                               value="{{ old('date', date('Y-m-d')) }}" required />
                        @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Notes</label>
                        <input type="text" name="notes" class="form-control" placeholder="Optional" value="{{ old('notes') }}" />
                    </div>
                </div>
            </div>

            <hr class="my-0">

            <div class="card-body">
                <div class="row g-2 d-none d-md-flex mb-1 px-1">
                    <div class="col-md-3"><small class="text-body-secondary">Description</small></div>
                    <div class="col-md-2"><small class="text-body-secondary">Qty (L)</small></div>
                    <div class="col-md-2"><small class="text-body-secondary">Fat %</small></div>
                    <div class="col-md-3"><small class="text-body-secondary">Rate/L</small></div>
                    <div class="col-md-2 text-center"><small class="text-body-secondary">Amount</small></div>
                </div>
                <div id="itemsContainer"></div>
                <div class="mt-3">
                    <button type="button" class="btn btn-sm btn-primary" id="addRow">
                        <i class="icon-base ti tabler-plus icon-xs me-1"></i> Add Item
                    </button>
                </div>
            </div>

        </div>
    </div>

    @include('billing._right-card')

</div>
</form>

@include('billing._quick-customer-offcanvas')
@endsection

@push('scripts')
@include('billing._bill-scripts')
<script>
let rowCount = 0;
function addRow() {
    const del = `<button type="button" class="btn btn-icon btn-text-danger rounded-pill del-row p-0"><i class="icon-base ti tabler-trash icon-xs"></i></button>`;
    const row = `<div class="row g-2 align-items-end border-bottom py-2 item-row">
        <div class="col-12 col-md-3">
            <label class="d-md-none form-label mb-1">Description</label>
            <input type="text" name="items[${rowCount}][description]" class="form-control" value="Milk" required>
            <input type="hidden" name="items[${rowCount}][unit]" value="liter">
        </div>
        <div class="col-5 col-md-2">
            <label class="d-md-none form-label mb-1">Qty (L)</label>
            <input type="number" name="items[${rowCount}][qty]" class="form-control item-qty" step="0.001" min="0" placeholder="0" required>
        </div>
        <div class="col-5 col-md-2">
            <label class="d-md-none form-label mb-1">Fat %</label>
            <input type="number" name="items[${rowCount}][fat]" class="form-control" step="0.01" min="0" max="100" placeholder="0.00">
        </div>
        <div class="col-5 col-md-2">
            <label class="d-md-none form-label mb-1">Rate/L</label>
            <input type="number" name="items[${rowCount}][rate]" class="form-control item-rate" step="0.01" min="0" placeholder="0.00" required>
        </div>
        <div class="col-10 col-md-2 text-center">
            <label class="d-md-none form-label mb-1">Amount</label>
            <div class="form-control-plaintext fw-medium item-amount text-center">₹0.00</div>
        </div>
        <div class="col-2 col-md-1 d-flex align-items-end justify-content-end pb-1">${del}</div>
    </div>`;
    document.getElementById('itemsContainer').insertAdjacentHTML('beforeend', row);
    rowCount++;
    recalc();
}
document.getElementById('addRow').addEventListener('click', addRow);
addRow();
</script>
@endpush
