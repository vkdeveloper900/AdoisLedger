@extends('layouts.app')

@section('title', 'Business Type Guide')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-faq.css') }}">
@endpush

@section('content')

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('docs.index') }}">Documentation</a></li>
        <li class="breadcrumb-item active">Business Type Guide</li>
    </ol>
</nav>

<div class="row">
    {{-- Left nav --}}
    <div class="col-lg-3 col-md-4 mb-4 mb-md-0">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#biz-dairy">
                    <i class="ti tabler-droplet icon-sm faq-nav-icon me-1_5"></i>
                    <span class="align-middle">Dairy Business</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#biz-general">
                    <i class="ti tabler-shopping-bag icon-sm faq-nav-icon me-1_5"></i>
                    <span class="align-middle">General Store</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#biz-construction">
                    <i class="ti tabler-building icon-sm faq-nav-icon me-1_5"></i>
                    <span class="align-middle">Construction</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#biz-comparison">
                    <i class="ti tabler-columns icon-sm faq-nav-icon me-1_5"></i>
                    <span class="align-middle">Comparison</span>
                </button>
            </li>
        </ul>
    </div>

    {{-- Right content --}}
    <div class="col-lg-9 col-md-8">
        <div class="tab-content p-0">

            {{-- Dairy --}}
            <div class="tab-pane fade show active" id="biz-dairy">
                <div class="d-flex mb-4 gap-4 align-items-center">
                    <span class="badge bg-label-warning rounded h-px-50 py-2">
                        <i class="ti tabler-droplet icon-30px"></i>
                    </span>
                    <div>
                        <h5 class="mb-0">Dairy Business <span class="badge bg-label-warning ms-1">Type 2</span></h5>
                        <span class="text-body-secondary">Milk farms, dairy shops, and milk collection centres</span>
                    </div>
                </div>

                <div class="alert alert-warning border-0 mb-4">
                    <i class="ti tabler-star me-2"></i>
                    <strong>Unique Feature:</strong> Dairy is the only business type with a dedicated <strong>Purchases (Milk Purchase)</strong> module — for buying milk from farmers/vendors with Fat % tracking per item.
                </div>

                <div class="accordion" id="accordionDairy">
                    <div class="card accordion-item active">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#dairy-1" aria-expanded="true">
                                What can I do with a Dairy business?
                            </button>
                        </h2>
                        <div id="dairy-1" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                    <li>Create <strong>dairy sale bills</strong> for customers — including Fat % per line item</li>
                                    <li>Record <strong>milk purchases</strong> from farmers/vendors with Fat % and rate per kg</li>
                                    <li>Track <strong>receivables</strong> from customers and <strong>payables</strong> to vendors separately</li>
                                    <li>Record and track payments in both directions</li>
                                    <li>View the full ledger per customer/vendor</li>
                                    <li>Generate PDF invoices for both sales and purchases</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#dairy-2">
                                How does Fat % work in dairy billing?
                            </button>
                        </h2>
                        <div id="dairy-2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <p>Each line item in a dairy bill or purchase has a <strong>Fat %</strong> field. This is stored on the <code>TransactionItem</code> record as a decimal value (e.g. 4.5%).</p>
                                <p class="mb-0">Fat % is used for reference/reporting only — the amount is still calculated as <strong>Qty × Rate</strong>. The fat value appears on the printed PDF invoice.</p>
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#dairy-3">
                                How do I add a Milk Purchase?
                            </button>
                        </h2>
                        <div id="dairy-3" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Enter your Dairy business from the Dashboard.</li>
                                    <li>Go to <strong>Purchases → Add Purchase</strong> in the sidebar.</li>
                                    <li>Select a <strong>Vendor</strong> (party type must be <em>vendor</em> or <em>both</em>).</li>
                                    <li>Add line items: Item name, Quantity (litres/kg), Fat %, Rate per unit.</li>
                                    <li>Enter amount paid on the spot (if any) and click <strong>Save Purchase</strong>.</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#dairy-4">
                                How do I record payment to a milk vendor?
                            </button>
                        </h2>
                        <div id="dairy-4" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                Go to <strong>Billing → Receive Payment</strong>. On the payment form, select mode = <strong>Made</strong> (we are paying the vendor). Choose the vendor and enter the amount. This creates a <code>payment_made</code> ledger entry and reduces the payable balance for that vendor.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- General --}}
            <div class="tab-pane fade" id="biz-general">
                <div class="d-flex mb-4 gap-4 align-items-center">
                    <span class="badge bg-label-danger rounded h-px-50 py-2">
                        <i class="ti tabler-shopping-bag icon-30px"></i>
                    </span>
                    <div>
                        <h5 class="mb-0">General Store <span class="badge bg-label-danger ms-1">Type 1</span></h5>
                        <span class="text-body-secondary">Retail shops, kirana stores, and general merchants</span>
                    </div>
                </div>

                <div class="alert alert-danger border-0 mb-4">
                    <i class="ti tabler-info-circle me-2"></i>
                    The General Store type is the simplest — it focuses on <strong>sales billing</strong> and <strong>customer ledger management</strong>. No purchase module, no materials.
                </div>

                <div class="accordion" id="accordionGeneral">
                    <div class="card accordion-item active">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#gen-1" aria-expanded="true">
                                What can I do with a General Store business?
                            </button>
                        </h2>
                        <div id="gen-1" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                    <li>Create <strong>general sale bills</strong> with any items, quantities, and rates</li>
                                    <li>Offer <strong>discounts</strong> at the bill level</li>
                                    <li>Track customer balances and outstanding receivables</li>
                                    <li>Record payments from customers</li>
                                    <li>View ledger and print PDF invoices</li>
                                    <li>Generate balance sheet reports</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#gen-2">
                                How does discount work on a General bill?
                            </button>
                        </h2>
                        <div id="gen-2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                The discount field appears in the <strong>right summary card</strong> of the bill creation form. Enter a flat discount amount (in Rs.). It is subtracted from the gross total to arrive at the net total. The discount is stored on the <code>Transaction</code> record and shown on the printed invoice.
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#gen-3">
                                How do I create a General Store bill step by step?
                            </button>
                        </h2>
                        <div id="gen-3" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Enter your General Store business from the Dashboard.</li>
                                    <li>Go to <strong>Billing → Create Bill</strong>.</li>
                                    <li>Select a customer and bill date.</li>
                                    <li>Add items: Description, Qty, Rate — Amount fills automatically.</li>
                                    <li>Add a discount in the right card (optional).</li>
                                    <li>Enter amount received (if paid on the spot).</li>
                                    <li>Click <strong>Save Bill</strong>.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Construction --}}
            <div class="tab-pane fade" id="biz-construction">
                <div class="d-flex mb-4 gap-4 align-items-center">
                    <span class="badge bg-label-info rounded h-px-50 py-2">
                        <i class="ti tabler-building icon-30px"></i>
                    </span>
                    <div>
                        <h5 class="mb-0">Construction Materials <span class="badge bg-label-info ms-1">Type 3</span></h5>
                        <span class="text-body-secondary">Construction suppliers and building materials businesses</span>
                    </div>
                </div>

                <div class="alert alert-info border-0 mb-4">
                    <i class="ti tabler-star me-2"></i>
                    <strong>Unique Feature:</strong> Construction businesses have a <strong>Materials & Units</strong> settings panel for pre-defining your product catalogue (e.g. Sand, Bricks, Cement) and units (e.g. CFT, Bags, MT).
                </div>

                <div class="accordion" id="accordionConstruction">
                    <div class="card accordion-item active">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#con-1" aria-expanded="true">
                                What can I do with a Construction business?
                            </button>
                        </h2>
                        <div id="con-1" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                    <li>Maintain a <strong>Materials catalogue</strong> (predefined items for the bill form)</li>
                                    <li>Maintain a <strong>Units list</strong> (CFT, MT, Bags, etc.)</li>
                                    <li>Create <strong>construction sale bills</strong> with materials dropdown and unit selection</li>
                                    <li>Track customer receivables and record payments</li>
                                    <li>View ledger and generate PDF invoices</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#con-2">
                                How do I add Materials and Units?
                            </button>
                        </h2>
                        <div id="con-2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Enter your Construction business.</li>
                                    <li>Go to <strong>Settings → Materials & Units</strong> (this menu item is only visible for Construction businesses).</li>
                                    <li>Use the <strong>Add Material</strong> panel to add items like "River Sand", "Bricks", "Cement".</li>
                                    <li>Use the <strong>Add Unit</strong> panel to add units like "CFT", "Bags", "MT".</li>
                                    <li>These now appear in the bill creation form dropdowns.</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#con-3">
                                How does the Construction bill form differ from other types?
                            </button>
                        </h2>
                        <div id="con-3" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                The Construction bill form includes:
                                <ul class="mb-0">
                                    <li>A <strong>Material dropdown</strong> (populated from your Materials list)</li>
                                    <li>A <strong>Unit dropdown</strong> (populated from your Units list)</li>
                                    <li>Quantity and Rate fields (amount auto-calculated)</li>
                                </ul>
                                Unlike the General form, item names are selected from the catalogue rather than typed free-form.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Comparison --}}
            <div class="tab-pane fade" id="biz-comparison">
                <div class="d-flex mb-4 gap-4 align-items-center">
                    <span class="badge bg-label-secondary rounded h-px-50 py-2">
                        <i class="ti tabler-columns icon-30px"></i>
                    </span>
                    <div>
                        <h5 class="mb-0">Feature Comparison</h5>
                        <span class="text-body-secondary">Side-by-side overview of all three business types</span>
                    </div>
                </div>

                <div class="card border">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Feature</th>
                                    <th class="text-center"><span class="badge bg-label-warning">Dairy</span></th>
                                    <th class="text-center"><span class="badge bg-label-danger">General</span></th>
                                    <th class="text-center"><span class="badge bg-label-info">Construction</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Sale Billing</td>
                                    <td class="text-center text-success"><i class="ti tabler-check"></i></td>
                                    <td class="text-center text-success"><i class="ti tabler-check"></i></td>
                                    <td class="text-center text-success"><i class="ti tabler-check"></i></td>
                                </tr>
                                <tr>
                                    <td>Discount on Bill</td>
                                    <td class="text-center text-body-secondary"><i class="ti tabler-minus"></i></td>
                                    <td class="text-center text-success"><i class="ti tabler-check"></i></td>
                                    <td class="text-center text-success"><i class="ti tabler-check"></i></td>
                                </tr>
                                <tr>
                                    <td>Fat % on Items</td>
                                    <td class="text-center text-success"><i class="ti tabler-check"></i></td>
                                    <td class="text-center text-body-secondary"><i class="ti tabler-minus"></i></td>
                                    <td class="text-center text-body-secondary"><i class="ti tabler-minus"></i></td>
                                </tr>
                                <tr>
                                    <td>Milk Purchases Module</td>
                                    <td class="text-center text-success"><i class="ti tabler-check"></i></td>
                                    <td class="text-center text-body-secondary"><i class="ti tabler-minus"></i></td>
                                    <td class="text-center text-body-secondary"><i class="ti tabler-minus"></i></td>
                                </tr>
                                <tr>
                                    <td>Materials & Units Catalogue</td>
                                    <td class="text-center text-body-secondary"><i class="ti tabler-minus"></i></td>
                                    <td class="text-center text-body-secondary"><i class="ti tabler-minus"></i></td>
                                    <td class="text-center text-success"><i class="ti tabler-check"></i></td>
                                </tr>
                                <tr>
                                    <td>Material/Unit Dropdown on Bill</td>
                                    <td class="text-center text-body-secondary"><i class="ti tabler-minus"></i></td>
                                    <td class="text-center text-body-secondary"><i class="ti tabler-minus"></i></td>
                                    <td class="text-center text-success"><i class="ti tabler-check"></i></td>
                                </tr>
                                <tr>
                                    <td>Customer Receivables</td>
                                    <td class="text-center text-success"><i class="ti tabler-check"></i></td>
                                    <td class="text-center text-success"><i class="ti tabler-check"></i></td>
                                    <td class="text-center text-success"><i class="ti tabler-check"></i></td>
                                </tr>
                                <tr>
                                    <td>Vendor Payables</td>
                                    <td class="text-center text-success"><i class="ti tabler-check"></i></td>
                                    <td class="text-center text-body-secondary"><i class="ti tabler-minus"></i></td>
                                    <td class="text-center text-body-secondary"><i class="ti tabler-minus"></i></td>
                                </tr>
                                <tr>
                                    <td>PDF Invoices</td>
                                    <td class="text-center text-success"><i class="ti tabler-check"></i></td>
                                    <td class="text-center text-success"><i class="ti tabler-check"></i></td>
                                    <td class="text-center text-success"><i class="ti tabler-check"></i></td>
                                </tr>
                                <tr>
                                    <td>Balance Sheet Reports</td>
                                    <td class="text-center text-success"><i class="ti tabler-check"></i></td>
                                    <td class="text-center text-success"><i class="ti tabler-check"></i></td>
                                    <td class="text-center text-success"><i class="ti tabler-check"></i></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
