<div class="offcanvas offcanvas-end" tabindex="-1" id="quickVendorOffcanvas" aria-labelledby="quickVendorOffcanvasLabel">
    <div class="offcanvas-header border-bottom">
        <h5 id="quickVendorOffcanvasLabel" class="offcanvas-title">Add Quick Vendor</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="quickVendorForm">
            @csrf
            <input type="hidden" name="party_type" value="vendor">

            <div id="quickVendorAlert" class="alert alert-danger d-none" role="alert"></div>

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" id="qv_name" name="name" class="form-control" placeholder="Vendor / Farmer name" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mobile 1</label>
                    <input type="text" id="qv_mobile" name="mobile" class="form-control" placeholder="9876543210" />
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mobile 2</label>
                    <input type="text" name="mobile2" class="form-control" placeholder="Optional" />
                </div>
                <div class="col-12">
                    <label class="form-label">Address</label>
                    <textarea name="address" rows="2" class="form-control" placeholder="Village, City…"></textarea>
                </div>

                <hr class="my-1">
                <h6 class="mb-0">Bank Details</h6>

                <div class="col-12">
                    <label class="form-label">Account Number</label>
                    <input type="text" id="qv_bank_account_number" name="bank_account_number" class="form-control" />
                    <div class="invalid-feedback" id="qv_bank_account_number_error"></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">IFSC Code</label>
                    <input type="text" name="bank_ifsc_code" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label class="form-label">UPI ID</label>
                    <input type="text" name="upi_id" class="form-control" placeholder="name@upi" />
                </div>

                <div class="col-12 mt-2">
                    <button type="submit" id="quickVendorSubmit" class="btn btn-primary w-100">
                        <span class="spinner-border spinner-border-sm d-none me-1" id="quickVendorSpinner"></span>
                        Save Vendor
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form      = document.getElementById('quickVendorForm');
    const submitBtn = document.getElementById('quickVendorSubmit');
    const spinner   = document.getElementById('quickVendorSpinner');
    const alertBox  = document.getElementById('quickVendorAlert');

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        submitBtn.disabled = true;
        spinner.classList.remove('d-none');
        alertBox.classList.add('d-none');

        fetch("{{ route('customers.store') }}", {
            method: 'POST',
            body: new FormData(form),
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.ok ? r.json() : r.json().then(e => { throw e; }))
        .then(data => {
            if (!data.success) throw new Error(data.message || 'Error');

            const sel   = document.getElementById('vendor_id');
            const label = data.customer.name + (data.customer.mobile ? ' · ' + data.customer.mobile : '');
            const opt   = new Option(label, data.customer.id, true, true);
            sel.insertBefore(opt, sel.options[0]);
            sel.value = data.customer.id;
            if (window.jQuery && jQuery(sel).data('select2')) jQuery(sel).trigger('change');

            form.reset();
            bootstrap.Offcanvas.getInstance(document.getElementById('quickVendorOffcanvas'))?.hide();
        })
        .catch(err => {
            alertBox.classList.remove('d-none');
            alertBox.textContent = err.message || 'Something went wrong.';
        })
        .finally(() => { submitBtn.disabled = false; spinner.classList.add('d-none'); });
    });
});
</script>
@endpush