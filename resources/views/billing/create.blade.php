@extends('layouts.app')
@section('title', 'New Bill')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}">
@endpush

@section('content')

<div class="d-flex align-items-center card">
    <a href="{{ route('bills.index') }}" class="btn btn-sm btn-icon btn-text-secondary rounded-pill me-2">
        <i class="icon-base ti tabler-arrow-left"></i>
    </a>
    <h5 class="mb-0">New Bill — {{ $business->name }}</h5>
    <div class="ms-auto d-flex gap-1">
        @php
            $types = match($business->business_type_id) {
                2 => [\App\Enums\TransactionType::DairySale, \App\Enums\TransactionType::DairyPurchase],
                3 => [\App\Enums\TransactionType::Construction],
                default => [\App\Enums\TransactionType::GeneralSale],
            };
        @endphp
        @foreach ($types as $t)
            <a href="{{ route('bills.create', ['type' => $t->value]) }}"
               class="btn btn-sm {{ $type === $t->value ? 'btn-primary' : 'btn-label-secondary' }}">
                {{ $t->label() }}
            </a>
        @endforeach
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

    {{-- ── LEFT col-lg-9 ── --}}
    <div class="col-lg-9 col-12 mb-lg-0 mb-6 mt-2">
        <div class="card invoice-preview-card">

            {{-- Header: customer + date + notes --}}
            <div class="card-body invoice-preview-header rounded mb-0 pb-4">
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
                    <div class="col-md-3">
                        <label class="form-label">Notes</label>
                        <input type="text" name="notes" class="form-control" placeholder="Optional" value="{{ old('notes') }}" />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Discount</label>
                        <input type="number" name="discount" id="discount"
                               class="form-control @error('discount') is-invalid @enderror"
                               step="0.01" min="0" value="{{ old('discount', '0') }}" />
                    </div>
                </div>
            </div>

            <hr class="mt-0 mb-6">

            {{-- Items --}}
            <div class="card-body pt-0 px-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0" id="itemsTable">
                        <thead class="table-light">
                            <tr>
                                @if ($type === 'dairy_sale' || $type === 'dairy_purchase')
                                    <th>Description</th>
                                    <th style="width:110px">Qty (L)</th>
                                    <th style="width:90px">Fat %</th>
                                    <th style="width:130px">Rate/L</th>
                                @elseif ($type === 'construction_sale')
                                    <th>Material</th>
                                    <th style="width:100px">Unit</th>
                                    <th style="width:100px">Qty</th>
                                    <th style="width:130px">Rate</th>
                                @else
                                    <th>Item</th>
                                    <th style="width:110px">Qty</th>
                                    <th style="width:130px">Rate</th>
                                @endif
                                <th style="width:120px" class="text-end">Amount</th>
                                <th style="width:40px"></th>
                            </tr>
                        </thead>
                        <tbody id="itemsContainer"></tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <button type="button" class="btn btn-sm btn-primary" id="addRow">
                        <i class="icon-base ti tabler-plus icon-xs me-1"></i> Add Item
                    </button>
                </div>
            </div>

            <hr class="my-0">

        </div>
    </div>

    {{-- ── RIGHT col-lg-3 ── --}}
    <div class="col-lg-3 col-12 invoice-actions">

        {{-- Totals + Actions --}}
        <div class="card mb-6">
            <div class="card-body">

                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span class="fw-medium" id="displaySubtotal">₹0.00</span>
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
                    <label class="form-label">Amount Received</label>
                    <input type="number" name="amount_received" id="amount_received"
                           class="form-control @error('amount_received') is-invalid @enderror"
                           min="0" value="{{ old('amount_received', '0') }}" />
                    @error('amount_received') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-between mb-4">
                    <span>Balance Due:</span>
                    <span class="fw-medium text-danger" id="displayBalance">₹0</span>
                </div>

                <button type="submit" class="btn btn-primary d-grid w-100">
                    <span class="d-flex align-items-center justify-content-center">
                        <i class="icon-base ti tabler-check icon-xs me-2"></i> Save & Post Bill
                    </span>
                </button>
            </div>
        </div>

        {{-- Customer info (shown after customer selected) --}}
        <div class="card d-none" id="customerInfoCard">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-semibold">Outstanding Balance</span>
                    <span id="custBalance" class="fw-bold">₹0</span>
                </div>
                <div id="lastTxList"></div>
            </div>
        </div>

    </div>

</div>
</form>
@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script>
const BILL_TYPE = '{{ $type }}';
const MATERIALS = @json($materials);
const UNITS     = @json($units);
const CUSTOMER_INFO_URL = '{{ route("bills.customer-info") }}';

// ── Select2 ──────────────────────────────────────────────────────────────────
$(function () {
    $('#customer_id').select2({ placeholder: 'Search by name or mobile…', allowClear: true, width: '100%' })
    .on('change', function () {
        const id = $(this).val();
        if (!id) { $('#customerInfoCard').addClass('d-none'); return; }
        $.getJSON(CUSTOMER_INFO_URL, { customer_id: id }, function (data) {
            const bal = data.balance;
            const balEl = $('#custBalance');
            balEl.text('₹' + Math.abs(bal).toLocaleString('en-IN'));
            balEl.removeClass('text-danger text-success text-body-secondary');
            if (bal > 0) balEl.addClass('text-danger');
            else if (bal < 0) balEl.addClass('text-success');
            else balEl.addClass('text-body-secondary');

            let html = '';
            if (data.last_transactions.length) {
                html += '<p class="small text-body-secondary mb-1 fw-semibold">Last Transactions</p><ul class="list-unstyled mb-0 small">';
                data.last_transactions.forEach(tx => {
                    const due = tx.balance > 0
                        ? `<span class="text-danger">Due ₹${Number(tx.balance).toLocaleString('en-IN')}</span>`
                        : `<span class="text-success">Paid</span>`;
                    html += `<li class="d-flex justify-content-between border-bottom py-1">
                        <span>${tx.bill_number} <small class="text-body-secondary">${tx.date}</small></span>
                        <span>₹${Number(tx.total_amount).toLocaleString('en-IN')} ${due}</span>
                    </li>`;
                });
                html += '</ul>';
            } else {
                html = '<p class="small text-body-secondary mb-0">No previous transactions.</p>';
            }
            $('#lastTxList').html(html);
            $('#customerInfoCard').removeClass('d-none');
        });
    });
    if ($('#customer_id').val()) $('#customer_id').trigger('change');
});

// ── Row builder ───────────────────────────────────────────────────────────────
function makeRow(idx) {
    const del = `<button type="button" class="btn btn-icon btn-text-danger rounded-pill del-row p-0">
        <i class="icon-base ti tabler-trash icon-xs"></i>
    </button>`;

    if (BILL_TYPE === 'dairy_sale' || BILL_TYPE === 'dairy_purchase') {
        return `<tr>
            <td><input type="text" name="items[${idx}][description]" class="form-control" value="Milk" required>
                <input type="hidden" name="items[${idx}][unit]" value="liter"></td>
            <td><input type="number" name="items[${idx}][qty]" class="form-control item-qty" step="0.001" min="0" required></td>
            <td><input type="number" name="items[${idx}][fat]" class="form-control" step="0.01" min="0" max="100" placeholder="0.00"></td>
            <td><input type="number" name="items[${idx}][rate]" class="form-control item-rate" step="0.01" min="0" required></td>
            <td class="text-end fw-medium item-amount">₹0.00</td>
            <td class="text-center">${del}</td>
        </tr>`;
    }

    if (BILL_TYPE === 'construction_sale') {
        const matOpts  = MATERIALS.map(m => `<option value="${m}">${m}</option>`).join('');
        const unitOpts = UNITS.map(u => `<option value="${u}">${u}</option>`).join('');
        return `<tr>
            <td><select name="items[${idx}][description]" class="form-select" required>${matOpts}</select></td>
            <td><select name="items[${idx}][unit]" class="form-select">${unitOpts}</select></td>
            <td><input type="number" name="items[${idx}][qty]" class="form-control item-qty" step="0.001" min="0" required></td>
            <td><input type="number" name="items[${idx}][rate]" class="form-control item-rate" step="0.01" min="0" required></td>
            <td class="text-end fw-medium item-amount">₹0.00</td>
            <td class="text-center">${del}</td>
        </tr>`;
    }

    return `<tr>
        <td><input type="text" name="items[${idx}][description]" class="form-control" placeholder="Item name" required>
            <input type="hidden" name="items[${idx}][unit]" value="piece"></td>
        <td><input type="number" name="items[${idx}][qty]" class="form-control item-qty" step="0.001" min="0" required></td>
        <td><input type="number" name="items[${idx}][rate]" class="form-control item-rate" step="0.01" min="0" required></td>
        <td class="text-end fw-medium item-amount">₹0.00</td>
        <td class="text-center">${del}</td>
    </tr>`;
}

let rowCount = 0;

function addRow() {
    const tbody = document.getElementById('itemsContainer');
    const tmp = document.createElement('tbody');
    tmp.innerHTML = makeRow(rowCount++);
    tbody.appendChild(tmp.firstChild);
    recalc();
}

function recalc() {
    let subtotal = 0;
    document.querySelectorAll('#itemsContainer tr').forEach(row => {
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
    const recvEl   = document.getElementById('amount_received');
    if (recvEl.dataset.manual !== '1') recvEl.value = total;

    const received = parseInt(recvEl.value) || 0;
    const balance  = Math.max(0, total - received);

    document.getElementById('displaySubtotal').textContent = '₹' + subtotal.toFixed(2);
    document.getElementById('displayDiscount').textContent = '₹' + discount.toFixed(2);
    document.getElementById('displayTotal').textContent    = '₹' + total.toLocaleString('en-IN');
    document.getElementById('displayBalance').textContent  = '₹' + balance.toLocaleString('en-IN');
}

document.getElementById('addRow').addEventListener('click', addRow);

document.getElementById('itemsContainer').addEventListener('input', e => {
    if (e.target.classList.contains('item-qty') || e.target.classList.contains('item-rate')) recalc();
});

document.getElementById('itemsContainer').addEventListener('click', e => {
    const btn = e.target.closest('.del-row');
    if (btn && document.querySelectorAll('#itemsContainer tr').length > 1) {
        btn.closest('tr').remove(); recalc();
    }
});

document.getElementById('discount').addEventListener('input', () => {
    document.getElementById('discount').dataset.manual = '1'; recalc();
});
document.getElementById('amount_received').addEventListener('input', () => {
    document.getElementById('amount_received').dataset.manual = '1'; recalc();
});

addRow();
</script>
@endpush
