{{-- Shared right card: totals + amount received + save + customer info --}}
{{-- Required vars: $errors --}}

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
