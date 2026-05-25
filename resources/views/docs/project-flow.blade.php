@extends('layouts.app')

@section('title', 'Project Flow')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-faq.css') }}">
<style>
    .flow-step {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px dashed var(--bs-border-color);
    }
    .flow-step:last-child { border-bottom: 0; }
    .flow-step-num {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--bs-primary);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        flex-shrink: 0;
        font-size: .85rem;
    }
    .arch-card {
        border-left: 4px solid var(--bs-primary) !important;
    }
</style>
@endpush

@section('content')

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('docs.index') }}">Documentation</a></li>
        <li class="breadcrumb-item active">Project Flow</li>
    </ol>
</nav>

<div class="row">
    {{-- Left nav --}}
    <div class="col-lg-3 col-md-4 mb-4 mb-md-0">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pf-overview">
                    <i class="ti tabler-layout-dashboard icon-sm faq-nav-icon me-1_5"></i>
                    <span class="align-middle">Overview</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pf-tenancy">
                    <i class="ti tabler-building icon-sm faq-nav-icon me-1_5"></i>
                    <span class="align-middle">Multi-Tenancy</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pf-billing-flow">
                    <i class="ti tabler-file-invoice icon-sm faq-nav-icon me-1_5"></i>
                    <span class="align-middle">Billing Flow</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pf-ledger">
                    <i class="ti tabler-book-2 icon-sm faq-nav-icon me-1_5"></i>
                    <span class="align-middle">Ledger & Accounting</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pf-payment-flow">
                    <i class="ti tabler-cash icon-sm faq-nav-icon me-1_5"></i>
                    <span class="align-middle">Payment Flow</span>
                </button>
            </li>
        </ul>
    </div>

    {{-- Right content --}}
    <div class="col-lg-9 col-md-8">
        <div class="tab-content p-0">

            {{-- Overview --}}
            <div class="tab-pane fade show active" id="pf-overview">
                <div class="d-flex mb-4 gap-4 align-items-center">
                    <span class="badge bg-label-primary rounded h-px-50 py-2">
                        <i class="ti tabler-layout-dashboard icon-30px"></i>
                    </span>
                    <div>
                        <h5 class="mb-0">System Overview</h5>
                        <span class="text-body-secondary">How AdoisLedger is structured</span>
                    </div>
                </div>

                <div class="card border arch-card mb-3">
                    <div class="card-body">
                        <h6 class="mb-3">What is AdoisLedger?</h6>
                        <p>AdoisLedger is a <strong>multi-tenant accounting and ledger management system</strong> built for small Indian businesses. It supports three distinct business types: <span class="badge bg-label-warning">Dairy</span> <span class="badge bg-label-danger">General Store</span> <span class="badge bg-label-info">Construction</span>.</p>
                        <p class="mb-0">Each business runs in full isolation — customers, transactions, ledger entries, and balances are always scoped to the active business profile. You can manage multiple businesses under a single login.</p>
                    </div>
                </div>

                <div class="card border mb-3">
                    <div class="card-body">
                        <h6 class="mb-3">Core Components</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex gap-2 align-items-start">
                                    <span class="badge bg-label-primary p-2 mt-1"><i class="ti tabler-building icon-16px"></i></span>
                                    <div><strong>Business Profiles</strong><br><small class="text-body-secondary">The top-level tenant — all data belongs to one profile.</small></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex gap-2 align-items-start">
                                    <span class="badge bg-label-success p-2 mt-1"><i class="ti tabler-users icon-16px"></i></span>
                                    <div><strong>Customers & Vendors</strong><br><small class="text-body-secondary">Parties can be customer, vendor, or both.</small></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex gap-2 align-items-start">
                                    <span class="badge bg-label-info p-2 mt-1"><i class="ti tabler-file-invoice icon-16px"></i></span>
                                    <div><strong>Transactions</strong><br><small class="text-body-secondary">Bills and purchases with typed line items.</small></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex gap-2 align-items-start">
                                    <span class="badge bg-label-warning p-2 mt-1"><i class="ti tabler-book-2 icon-16px"></i></span>
                                    <div><strong>Ledger Entries</strong><br><small class="text-body-secondary">Double-entry bookkeeping with running balance.</small></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Multi-Tenancy --}}
            <div class="tab-pane fade" id="pf-tenancy">
                <div class="d-flex mb-4 gap-4 align-items-center">
                    <span class="badge bg-label-warning rounded h-px-50 py-2">
                        <i class="ti tabler-building icon-30px"></i>
                    </span>
                    <div>
                        <h5 class="mb-0">Multi-Tenancy</h5>
                        <span class="text-body-secondary">How business isolation works</span>
                    </div>
                </div>

                <div class="accordion" id="accordionTenancy">
                    <div class="card accordion-item active">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#tenancy-1" aria-expanded="true">
                                How is data isolated between businesses?
                            </button>
                        </h2>
                        <div id="tenancy-1" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <p>AdoisLedger uses <strong>session-scoped multi-tenancy</strong>. When you click <em>Enter</em> on a business, its ID is stored in your session as <code>active_business_id</code>.</p>
                                <p>The <strong>ActiveBusinessProfile middleware</strong> runs on every request and shares the active profile with all views. Every database query is automatically scoped to <code>business_profile_id = active_business_id</code> so data from one business can never appear in another.</p>
                                <p class="mb-0">This is not database-level isolation (no separate schemas) — it is application-level isolation enforced through consistent query scoping.</p>
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tenancy-2">
                                Are customers shared between businesses?
                            </button>
                        </h2>
                        <div id="tenancy-2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                Customers are <strong>global records</strong> but assigned to businesses via a join table. A customer can exist in multiple businesses, but their transactions and ledger entries are always per-business. When you create a bill for a customer, they are automatically assigned to the active business if not already.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Billing Flow --}}
            <div class="tab-pane fade" id="pf-billing-flow">
                <div class="d-flex mb-4 gap-4 align-items-center">
                    <span class="badge bg-label-info rounded h-px-50 py-2">
                        <i class="ti tabler-file-invoice icon-30px"></i>
                    </span>
                    <div>
                        <h5 class="mb-0">Billing Flow</h5>
                        <span class="text-body-secondary">From bill creation to ledger posting</span>
                    </div>
                </div>

                <div class="card border mb-3">
                    <div class="card-body">
                        <h6 class="mb-3">Step-by-step: Creating a Sale Bill</h6>
                        <div class="flow-step">
                            <div class="flow-step-num">1</div>
                            <div>
                                <strong>Navigate to Billing → Create Bill</strong><br>
                                <small class="text-body-secondary">The form adapts to your business type (dairy/general/construction).</small>
                            </div>
                        </div>
                        <div class="flow-step">
                            <div class="flow-step-num">2</div>
                            <div>
                                <strong>Select Customer & Date</strong><br>
                                <small class="text-body-secondary">Choosing a customer loads their current balance and last 5 transactions instantly via AJAX.</small>
                            </div>
                        </div>
                        <div class="flow-step">
                            <div class="flow-step-num">3</div>
                            <div>
                                <strong>Add Line Items</strong><br>
                                <small class="text-body-secondary">Enter item name, quantity, rate. Dairy bills also accept Fat % per item. Amount is auto-calculated.</small>
                            </div>
                        </div>
                        <div class="flow-step">
                            <div class="flow-step-num">4</div>
                            <div>
                                <strong>Enter Amount Received (optional)</strong><br>
                                <small class="text-body-secondary">If the customer pays on the spot, enter the amount received. The balance is calculated automatically.</small>
                            </div>
                        </div>
                        <div class="flow-step">
                            <div class="flow-step-num">5</div>
                            <div>
                                <strong>Submit → PostBillAction runs</strong><br>
                                <small class="text-body-secondary">The system creates the Transaction record, all TransactionItem records, and then calls PostBillAction which creates the LedgerEntry records and marks the bill as <code>posted</code>.</small>
                            </div>
                        </div>
                        <div class="flow-step">
                            <div class="flow-step-num">6</div>
                            <div>
                                <strong>Bill Show page</strong><br>
                                <small class="text-body-secondary">You are redirected to the bill detail page. From here you can print a PDF invoice or record a payment.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info border-0">
                    <i class="ti tabler-info-circle me-2"></i>
                    <strong>Bill Numbering:</strong> Bills are auto-numbered as <code>BILL-00001</code>, <code>BILL-00002</code>, etc. — sequentially per business. Purchase numbers follow the same format with the prefix <code>PUR-</code>.
                </div>
            </div>

            {{-- Ledger --}}
            <div class="tab-pane fade" id="pf-ledger">
                <div class="d-flex mb-4 gap-4 align-items-center">
                    <span class="badge bg-label-success rounded h-px-50 py-2">
                        <i class="ti tabler-book-2 icon-30px"></i>
                    </span>
                    <div>
                        <h5 class="mb-0">Ledger & Accounting</h5>
                        <span class="text-body-secondary">How the double-entry ledger works</span>
                    </div>
                </div>

                <div class="accordion" id="accordionLedger">
                    <div class="card accordion-item active">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#ledger-1" aria-expanded="true">
                                What entry types exist in the ledger?
                            </button>
                        </h2>
                        <div id="ledger-1" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <table class="table table-bordered table-sm mb-0">
                                    <thead><tr><th>Entry Type</th><th>When Created</th><th>Effect</th></tr></thead>
                                    <tbody>
                                        <tr><td><code>receivable</code></td><td>Customer bill posted</td><td>Increases customer's outstanding balance</td></tr>
                                        <tr><td><code>payable</code></td><td>Vendor purchase posted</td><td>Increases what we owe the vendor</td></tr>
                                        <tr><td><code>payment_received</code></td><td>Payment from customer</td><td>Reduces customer's outstanding balance</td></tr>
                                        <tr><td><code>payment_made</code></td><td>Payment to vendor</td><td>Reduces what we owe the vendor</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ledger-2">
                                How is the running balance calculated?
                            </button>
                        </h2>
                        <div id="ledger-2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <p>Every <code>LedgerEntry</code> stores a <code>running_balance</code> field. This is the cumulative balance of all entries for that party up to and including that entry.</p>
                                <p>It is always recalculated sequentially — never updated in isolation. All amounts are stored as <strong>integers</strong> (paise, no decimals) to avoid floating-point errors.</p>
                                <p class="mb-0">A positive running balance means the party owes us money (receivable). A negative balance means we owe them (overpaid or payable).</p>
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ledger-3">
                                What is PostBillAction and why does it matter?
                            </button>
                        </h2>
                        <div id="ledger-3" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <p><strong>PostBillAction</strong> is the single, authoritative path for posting financial data to the ledger. It runs inside a database transaction to ensure consistency.</p>
                                <p>It creates the <code>LedgerEntry</code> records, sets <code>running_balance</code>, and marks the <code>Transaction</code> as <code>posted</code> with a timestamp. No ledger entries are ever created directly in controllers.</p>
                                <p class="mb-0">This centralisation means the ledger is always accurate regardless of whether you are creating a sale bill, a dairy purchase, or recording a payment.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment Flow --}}
            <div class="tab-pane fade" id="pf-payment-flow">
                <div class="d-flex mb-4 gap-4 align-items-center">
                    <span class="badge bg-label-danger rounded h-px-50 py-2">
                        <i class="ti tabler-cash icon-30px"></i>
                    </span>
                    <div>
                        <h5 class="mb-0">Payment Flow</h5>
                        <span class="text-body-secondary">How payments are recorded and applied</span>
                    </div>
                </div>

                <div class="card border mb-3">
                    <div class="card-body">
                        <h6 class="mb-3">Step-by-step: Recording a Payment</h6>
                        <div class="flow-step">
                            <div class="flow-step-num">1</div>
                            <div>
                                <strong>Go to Billing → Receive Payment</strong><br>
                                <small class="text-body-secondary">Choose mode: <strong>Received</strong> (customer pays us) or <strong>Made</strong> (we pay vendor).</small>
                            </div>
                        </div>
                        <div class="flow-step">
                            <div class="flow-step-num">2</div>
                            <div>
                                <strong>Select Party & Amount</strong><br>
                                <small class="text-body-secondary">Enter the customer/vendor, amount, payment method, and date. Their current balance is shown automatically.</small>
                            </div>
                        </div>
                        <div class="flow-step">
                            <div class="flow-step-num">3</div>
                            <div>
                                <strong>Submit</strong><br>
                                <small class="text-body-secondary">A <code>Payment</code> record is created, a <code>LedgerEntry</code> of type <code>payment_received</code> or <code>payment_made</code> is posted, and the party's running balance is updated.</small>
                            </div>
                        </div>
                        <div class="flow-step">
                            <div class="flow-step-num">4</div>
                            <div>
                                <strong>Outstanding bills are updated</strong><br>
                                <small class="text-body-secondary">Open transactions for that party have their <code>amount_received</code> and <code>balance</code> fields updated in sequence (oldest first).</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
