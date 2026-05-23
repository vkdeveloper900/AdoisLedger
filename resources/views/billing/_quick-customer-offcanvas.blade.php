{{-- resources/views/billing/_quick-customer-offcanvas.blade.php --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="quickCustomerOffcanvas" aria-labelledby="quickCustomerOffcanvasLabel">
    <div class="offcanvas-header border-bottom">
        <h5 id="quickCustomerOffcanvasLabel" class="offcanvas-title">Add Quick Customer</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="quickCustomerForm">
            @csrf
            
            <div id="quickCustomerAlert" class="alert alert-danger d-none" role="alert"></div>

            <div class="row g-3">
                <div class="col-12">
                    <label for="qc_name" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" id="qc_name" name="name" class="form-control" placeholder="Full name" required />
                </div>

                <div class="col-12">
                    <label for="qc_party_type" class="form-label">Party Type <span class="text-danger">*</span></label>
                    <select id="qc_party_type" name="party_type" class="form-select" required>
                        <option value="customer" selected>Customer</option>
                        <option value="vendor">Vendor</option>
                        <option value="both">Both (Customer & Vendor)</option>
                    </select>
                </div>

                <div class="col-12">
                    <label for="qc_email" class="form-label">Email</label>
                    <input type="email" id="qc_email" name="email" class="form-control" placeholder="customer@example.com" />
                </div>

                <div class="col-md-6">
                    <label for="qc_mobile" class="form-label">Mobile 1</label>
                    <input type="text" id="qc_mobile" name="mobile" class="form-control" placeholder="+91 9000000000" />
                </div>

                <div class="col-md-6">
                    <label for="qc_mobile2" class="form-label">Mobile 2</label>
                    <input type="text" id="qc_mobile2" name="mobile2" class="form-control" placeholder="+91 9000000001" />
                </div>

                <div class="col-12">
                    <label for="qc_address" class="form-label">Address</label>
                    <textarea id="qc_address" name="address" rows="2" class="form-control" placeholder="Street, City, State"></textarea>
                </div>

                <hr class="my-2">
                <h6 class="mb-0">Bank & Payment Details</h6>

                <div class="col-12">
                    <label for="qc_bank_account_number" class="form-label">Account Number</label>
                    <input type="text" id="qc_bank_account_number" name="bank_account_number" class="form-control" placeholder="e.g. 0123456789" />
                    <div class="invalid-feedback" id="qc_bank_account_number_error"></div>
                </div>

                <div class="col-md-6">
                    <label for="qc_bank_ifsc_code" class="form-label">IFSC Code</label>
                    <input type="text" id="qc_bank_ifsc_code" name="bank_ifsc_code" class="form-control" placeholder="e.g. SBIN0001234" />
                    <div class="invalid-feedback" id="qc_bank_ifsc_code_error"></div>
                </div>

                <div class="col-md-6">
                    <label for="qc_bank_branch" class="form-label">Branch</label>
                    <input type="text" id="qc_bank_branch" name="bank_branch" class="form-control" placeholder="e.g. Main Branch" />
                    <div class="invalid-feedback" id="qc_bank_branch_error"></div>
                </div>

                <div class="col-12">
                    <label for="qc_upi_id" class="form-label">UPI ID</label>
                    <input type="text" id="qc_upi_id" name="upi_id" class="form-control" placeholder="e.g. name@upi" />
                    <div class="invalid-feedback" id="qc_upi_id_error"></div>
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" id="quickCustomerSubmit" class="btn btn-primary w-100">
                        <span class="spinner-border spinner-border-sm d-none me-1" id="quickCustomerSpinner" role="status" aria-hidden="true"></span>
                        Save Customer
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const qcForm = document.getElementById('quickCustomerForm');
        if (!qcForm) return;

        qcForm.addEventListener('submit', function (e) {
            e.preventDefault();
            
            const form = this;
            const submitBtn = document.getElementById('quickCustomerSubmit');
            const spinner = document.getElementById('quickCustomerSpinner');
            const alertBox = document.getElementById('quickCustomerAlert');
            
            // Disable button & show spinner
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');
            alertBox.classList.add('d-none');
            alertBox.textContent = '';
            // Clear previous field errors
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

            const formData = new FormData(form);

            fetch("{{ route('customers.store') }}", {
                method: "POST",
                body: formData,
                headers: {
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Add new customer option to dropdown
                    const customerSelect = document.getElementById('customer_id');
                    const label = data.customer.name + (data.customer.mobile ? ' · ' + data.customer.mobile : '');
                    
                    const newOption = new Option(label, data.customer.id, true, true);
                    
                    // Prepend new option
                    if (customerSelect.options.length > 0) {
                        customerSelect.insertBefore(newOption, customerSelect.options[0]);
                    } else {
                        customerSelect.add(newOption);
                    }
                    
                    // Select the value
                    customerSelect.value = data.customer.id;

                    // If Select2 is used on customer select, trigger change event
                    if (window.jQuery && jQuery(customerSelect).data('select2')) {
                        jQuery(customerSelect).trigger('change');
                    }

                    // Reset form
                    form.reset();

                    // Close offcanvas
                    const offcanvasEl = document.getElementById('quickCustomerOffcanvas');
                    const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl) || new bootstrap.Offcanvas(offcanvasEl);
                    offcanvas.hide();
                } else {
                    throw new Error(data.message || 'Something went wrong');
                }
            })
            .catch(err => {
                console.error(err);
                alertBox.classList.remove('d-none');
                // Clear any lingering field errors before displaying new ones
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
                if (err.errors) {
                    // Show field‑specific errors
                    for (const field in err.errors) {
                        const messages = err.errors[field].join(', ');
                        const input = document.querySelector(`[name="${field}"]`);
                        if (input) {
                            input.classList.add('is-invalid');
                            const feedback = document.getElementById(`${input.id}_error`);
                            if (feedback) {
                                feedback.textContent = messages;
                            }
                        } else {
                            // fallback to alert box if we cannot map the field
                            const p = document.createElement('div');
                            p.textContent = `${field}: ${messages}`;
                            alertBox.appendChild(p);
                        }
                    }
                } else {
                    alertBox.textContent = err.message || 'An error occurred while creating the customer.';
                }
            })
            .finally(() => {
                submitBtn.disabled = false;
                spinner.classList.add('d-none');
            });
        });
    });
</script>
@endpush
