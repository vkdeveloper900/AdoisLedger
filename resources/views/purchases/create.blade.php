@extends('layouts.app')
@section('title', 'New Purchase')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}">
@endpush

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('purchases.index') }}" class="btn btn-sm btn-icon btn-text-secondary rounded-pill me-2">
        <i class="icon-base ti tabler-arrow-left"></i>
    </a>
    <h5 class="mb-0">Add Purchase — {{ $business->name }}</h5>
    <div class="ms-auto d-flex gap-1 align-items-center">
        <a href="{{ route('bills.create', ['type' => 'dairy_sale']) }}"
           class="btn btn-sm btn-label-secondary">Sale</a>
        <a href="{{ route('purchases.create') }}"
           class="btn btn-sm btn-primary">Purchase</a>
        <button type="button" class="btn btn-sm btn-outline-primary ms-2"
                data-bs-toggle="offcanvas" data-bs-target="#quickVendorOffcanvas">
            <i class="icon-base ti tabler-user-plus me-1"></i> Add Vendor
        </button>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible mb-4">
        <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form method="POST" action="{{ route('purchases.store') }}" id="purchaseForm">
@csrf

<div class="row invoice-add">

    {{-- LEFT --}}
    <div class="col-lg-9 col-12 mb-lg-0 mb-6">
        <div class="card invoice-preview-card">

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Vendor / Farmer <span class="text-danger">*</span></label>
                        <select name="vendor_id" id="vendor_id"
                                class="form-select @error('vendor_id') is-invalid @enderror" required>
                            <option value="">Search by name or mobile…</option>
                            @foreach ($vendors['in_business'] as $v)
                                <option value="{{ $v->id }}" {{ old('vendor_id') == $v->id ? 'selected' : '' }}>
                                    {{ $v->name }}{{ $v->mobile ? ' · '.$v->mobile : '' }}
                                </option>
                            @endforeach
                            @if (count($vendors['others']))
                                <optgroup label="── Other Vendors ──">
                                @foreach ($vendors['others'] as $v)
                                    <option value="{{ $v->id }}" {{ old('vendor_id') == $v->id ? 'selected' : '' }}>
                                        {{ $v->name }}{{ $v->mobile ? ' · '.$v->mobile : '' }}
                                    </option>
                                @endforeach
                                </optgroup>
                            @endif
                        </select>
                        @error('vendor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                               value="{{ old('date', date('Y-m-d')) }}" required />
                        @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Notes</label>
                        <input type="text" name="notes" class="form-control"
                               placeholder="Optional" value="{{ old('notes') }}" />
                    </div>
                </div>
            </div>

            <hr class="my-0">

            {{-- Items Table --}}
            <div class="card-body">
                <div class="row g-2 d-none d-md-flex mb-1 px-1">
                    <div class="col-md-3"><small class="text-body-secondary">Description</small></div>
                    <div class="col-md-2"><small class="text-body-secondary">Qty (Liters)</small></div>
                    <div class="col-md-2"><small class="text-body-secondary">Fat %</small></div>
                    <div class="col-md-2"><small class="text-body-secondary">Rate / Liter</small></div>
                    <div class="col-md-2 text-center"><small class="text-body-secondary">Amount</small></div>
                    <div class="col-md-1"></div>
                </div>
                <div id="itemsContainer"></div>
                <div class="mt-3">
                    <button type="button" class="btn btn-sm btn-primary" id="addRow">
                        <i class="icon-base ti tabler-plus icon-xs me-1"></i> Add Row
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- RIGHT --}}
    <div class="col-lg-3 col-12 invoice-actions">

        <div class="card mb-6">
            <div class="card-body">

                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span class="fw-medium" id="displaySubtotal">₹0.00</span>
                </div>
                <div class="mb-3">
                    <label class="form-label mb-1">Discount</label>
                    <input type="number" name="discount" id="discount" class="form-control form-control-sm"
                           step="0.01" min="0" value="{{ old('discount', '0') }}" />
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Discount:</span>
                    <span class="fw-medium" id="displayDiscount">₹0.00</span>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between mb-3">
                    <span class="fw-semibold">Total:</span>
                    <span class="fw-bold text-primary fs-5" id="displayTotal">₹0</span>
                </div>

                <div class="mb-3">
                    <label class="form-label">Amount Paid</label>
                    <input type="number" name="amount_paid" id="amount_paid"
                           class="form-control @error('amount_paid') is-invalid @enderror"
                           min="0" value="{{ old('amount_paid', '0') }}" />
                    @error('amount_paid') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-between mb-4">
                    <span>Pending to Pay:</span>
                    <span class="fw-medium text-danger" id="displayBalance">₹0</span>
                </div>

                <button type="submit" class="btn btn-primary d-grid w-100">
                    <span class="d-flex align-items-center justify-content-center">
                        <i class="icon-base ti tabler-check icon-xs me-2"></i> Save & Post Purchase
                    </span>
                </button>
            </div>
        </div>

        {{-- Vendor outstanding info --}}
        <div class="card d-none" id="vendorInfoCard">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-semibold">Pending to Pay</span>
                    <span id="vendorBalance" class="fw-bold">₹0</span>
                </div>
                <div id="lastPurchasesList"></div>
            </div>
        </div>

    </div>

</div>
</form>

@include('purchases._quick-vendor-offcanvas')
@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script>
const VENDOR_INFO_URL = '{{ route("purchases.vendor-info") }}';

$(function () {
    $('#vendor_id').select2({ placeholder: 'Search by name or mobile…', allowClear: true, width: '100%' })
    .on('change', function () {
        const id = $(this).val();
        if (!id) { $('#vendorInfoCard').addClass('d-none'); return; }
        $.getJSON(VENDOR_INFO_URL, { vendor_id: id }, function (data) {
            const bal = Math.abs(data.balance);
            const balEl = $('#vendorBalance');
            balEl.text('₹' + bal.toLocaleString('en-IN'));
            balEl.removeClass('text-danger text-success text-body-secondary');
            if (data.balance < 0) balEl.addClass('text-danger'); // we owe them
            else balEl.addClass('text-success');

            let html = '';
            if (data.last_purchases.length) {
                html += '<p class="small text-body-secondary mb-1 fw-semibold">Last Purchases</p><ul class="list-unstyled mb-0 small">';
                data.last_purchases.forEach(p => {
                    const pending = p.balance > 0
                        ? `<span class="text-danger">Due ₹${Number(p.balance).toLocaleString('en-IN')}</span>`
                        : `<span class="text-success">Paid</span>`;
                    html += `<li class="d-flex justify-content-between border-bottom py-1">
                        <span>${p.bill_number} <small class="text-body-secondary">${p.date}</small></span>
                        <span>₹${Number(p.total_amount).toLocaleString('en-IN')} ${pending}</span>
                    </li>`;
                });
                html += '</ul>';
            } else {
                html = '<p class="small text-body-secondary mb-0">No previous purchases.</p>';
            }
            $('#lastPurchasesList').html(html);
            $('#vendorInfoCard').removeClass('d-none');
        });
    });
    if ($('#vendor_id').val()) $('#vendor_id').trigger('change');
});

function recalc() {
    let subtotal = 0;
    document.querySelectorAll('#itemsContainer .item-row').forEach(row => {
        const qty  = parseFloat(row.querySelector('.item-qty')?.value)  || 0;
        const rate = parseFloat(row.querySelector('.item-rate')?.value) || 0;
        const amt  = Math.round(qty * rate * 100) / 100;
        const el   = row.querySelector('.item-amount');
        if (el) el.textContent = '₹' + amt.toFixed(2);
        subtotal += amt;
    });
    subtotal = Math.round(subtotal * 100) / 100;

    const discEl = document.getElementById('discount');
    if (discEl.dataset.manual !== '1') {
        discEl.value = (Math.round((subtotal % 1) * 100) / 100).toFixed(2);
    }

    const discount = parseFloat(discEl.value) || 0;
    const total    = Math.floor(subtotal - discount);
    const paidEl   = document.getElementById('amount_paid');
    if (paidEl.dataset.manual !== '1') paidEl.value = 0;

    const paid    = parseInt(paidEl.value) || 0;
    const balance = Math.max(0, total - paid);

    document.getElementById('displaySubtotal').textContent = '₹' + subtotal.toFixed(2);
    document.getElementById('displayDiscount').textContent = '₹' + discount.toFixed(2);
    document.getElementById('displayTotal').textContent    = '₹' + total.toLocaleString('en-IN');
    document.getElementById('displayBalance').textContent  = '₹' + balance.toLocaleString('en-IN');
}

document.getElementById('itemsContainer').addEventListener('input', e => {
    if (e.target.classList.contains('item-qty') || e.target.classList.contains('item-rate')) recalc();
});
document.getElementById('itemsContainer').addEventListener('click', e => {
    const btn = e.target.closest('.del-row');
    if (btn && document.querySelectorAll('#itemsContainer .item-row').length > 1) {
        btn.closest('.item-row').remove(); recalc();
    }
});
document.getElementById('discount').addEventListener('input', () => {
    document.getElementById('discount').dataset.manual = '1'; recalc();
});
document.getElementById('amount_paid').addEventListener('input', () => {
    document.getElementById('amount_paid').dataset.manual = '1'; recalc();
});

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
            <input type="number" name="items[${rowCount}][qty]" class="form-control item-qty" step="0.001" min="0" placeholder="0.000" required>
        </div>
        <div class="col-5 col-md-2">
            <label class="d-md-none form-label mb-1">Fat %</label>
            <input type="number" name="items[${rowCount}][fat]" class="form-control" step="0.01" min="0" max="100" placeholder="0.00">
        </div>
        <div class="col-5 col-md-2">
            <label class="d-md-none form-label mb-1">Rate / Liter</label>
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