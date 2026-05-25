@php
$activeTab = request('tab', 'billing');
$validTabs = ['billing','purchases','payments','customers','reports','settings'];
if (!in_array($activeTab, $validTabs)) $activeTab = 'billing';
@endphp

@extends('layouts.app')

@section('title', 'Module Reference')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-faq.css') }}">
@endpush

@section('content')

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('docs.index') }}">Documentation</a></li>
        <li class="breadcrumb-item active">Module Reference</li>
    </ol>
</nav>

<div class="row">
    {{-- Left nav --}}
    <div class="col-lg-3 col-md-4 mb-4 mb-md-0">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <button class="nav-link {{ $activeTab === 'billing' ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#mod-billing">
                    <i class="ti tabler-file-invoice icon-sm faq-nav-icon me-1_5"></i>
                    <span class="align-middle">Billing</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link {{ $activeTab === 'purchases' ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#mod-purchases">
                    <i class="ti tabler-basket icon-sm faq-nav-icon me-1_5"></i>
                    <span class="align-middle">Purchases</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link {{ $activeTab === 'payments' ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#mod-payments">
                    <i class="ti tabler-cash icon-sm faq-nav-icon me-1_5"></i>
                    <span class="align-middle">Payments & Ledger</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link {{ $activeTab === 'customers' ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#mod-customers">
                    <i class="ti tabler-users icon-sm faq-nav-icon me-1_5"></i>
                    <span class="align-middle">Customers & Vendors</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link {{ $activeTab === 'reports' ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#mod-reports">
                    <i class="ti tabler-chart-bar icon-sm faq-nav-icon me-1_5"></i>
                    <span class="align-middle">Reports</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link {{ $activeTab === 'settings' ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#mod-settings">
                    <i class="ti tabler-settings icon-sm faq-nav-icon me-1_5"></i>
                    <span class="align-middle">Settings</span>
                </button>
            </li>
        </ul>
    </div>

    {{-- Right content --}}
    <div class="col-lg-9 col-md-8">
        <div class="tab-content p-0">

            {{-- BILLING --}}
            <div class="tab-pane fade {{ $activeTab === 'billing' ? 'show active' : '' }}" id="mod-billing">
                <div class="d-flex mb-4 gap-4 align-items-center">
                    <span class="badge bg-label-info rounded h-px-50 py-2">
                        <i class="ti tabler-file-invoice icon-30px"></i>
                    </span>
                    <div>
                        <h5 class="mb-0">Billing Module</h5>
                        <span class="text-body-secondary">Create and manage sale bills</span>
                    </div>
                </div>

                <div class="accordion" id="accordionBilling">
                    <div class="card accordion-item active">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#bill-1" aria-expanded="true">
                                How do I create a new bill?
                            </button>
                        </h2>
                        <div id="bill-1" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <p>Navigate to <strong>Billing → Create Bill</strong> in the sidebar. The form shown depends on your active business type:</p>
                                <ul class="mb-0">
                                    <li><strong>Dairy:</strong> Items with Qty, Fat %, Rate</li>
                                    <li><strong>General:</strong> Items with Qty, Rate, plus a bill-level Discount</li>
                                    <li><strong>Construction:</strong> Material dropdown + Unit dropdown + Qty + Rate</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#bill-2">
                                How are bill numbers assigned?
                            </button>
                        </h2>
                        <div id="bill-2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                Bill numbers are auto-generated per business in the format <code>BILL-00001</code>, <code>BILL-00002</code>, etc. Each business has its own independent sequence — two different businesses can both have <code>BILL-00001</code> without conflict.
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#bill-3">
                                Can I add multiple items to one bill?
                            </button>
                        </h2>
                        <div id="bill-3" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                Yes. Click <strong>+ Add Item</strong> on the bill form to add more line items. Each item has its own Qty, Rate (and Fat % for Dairy). You can remove items with the trash icon. The totals update instantly.
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#bill-4">
                                How do I print or download a bill as PDF?
                            </button>
                        </h2>
                        <div id="bill-4" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                Open any bill from the <strong>Bills</strong> list or click a bill number. On the Bill Detail page, click the <strong>Download PDF</strong> button. A formatted invoice PDF is generated and downloaded immediately.
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#bill-5">
                                What is the "Amount Received" field on the bill form?
                            </button>
                        </h2>
                        <div id="bill-5" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <p>If the customer pays part or all of the bill at the time of purchase, enter it here. The system will:</p>
                                <ul class="mb-0">
                                    <li>Create the bill with <code>amount_received</code> set</li>
                                    <li>Calculate <code>balance = total_amount - amount_received</code></li>
                                    <li>Create a <code>payment_received</code> ledger entry for the amount received</li>
                                </ul>
                                You do not need to record a separate payment for this amount.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PURCHASES --}}
            <div class="tab-pane fade {{ $activeTab === 'purchases' ? 'show active' : '' }}" id="mod-purchases">
                <div class="d-flex mb-4 gap-4 align-items-center">
                    <span class="badge bg-label-warning rounded h-px-50 py-2">
                        <i class="ti tabler-basket icon-30px"></i>
                    </span>
                    <div>
                        <h5 class="mb-0">Purchases Module</h5>
                        <span class="text-body-secondary">Dairy milk purchases from vendors/farmers</span>
                    </div>
                </div>

                <div class="alert alert-warning border-0 mb-3">
                    <i class="ti tabler-info-circle me-2"></i>
                    The Purchases module is only available for <strong>Dairy</strong> business type.
                </div>

                <div class="accordion" id="accordionPurchases">
                    <div class="card accordion-item active">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#pur-1" aria-expanded="true">
                                How do I add a milk purchase?
                            </button>
                        </h2>
                        <div id="pur-1" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Go to <strong>Purchases → Add Purchase</strong>.</li>
                                    <li>Select the vendor/farmer (party type must be <em>vendor</em> or <em>both</em>).</li>
                                    <li>Set the purchase date.</li>
                                    <li>Add line items: Item name, Quantity, Fat %, Rate per unit.</li>
                                    <li>Enter amount paid (if any) and click <strong>Save Purchase</strong>.</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pur-2">
                                How are purchase numbers assigned?
                            </button>
                        </h2>
                        <div id="pur-2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                Purchase numbers are auto-generated per business in the format <code>PUR-00001</code>, <code>PUR-00002</code>, etc. They are completely separate from bill numbers — both sequences exist independently per business.
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pur-3">
                                Who can be selected as a vendor in purchases?
                            </button>
                        </h2>
                        <div id="pur-3" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                Only parties with <code>party_type = vendor</code> or <code>party_type = both</code> can be selected as a vendor on the purchase form. Parties with <code>party_type = customer</code> only will not appear in the vendor dropdown.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PAYMENTS --}}
            <div class="tab-pane fade {{ $activeTab === 'payments' ? 'show active' : '' }}" id="mod-payments">
                <div class="d-flex mb-4 gap-4 align-items-center">
                    <span class="badge bg-label-success rounded h-px-50 py-2">
                        <i class="ti tabler-cash icon-30px"></i>
                    </span>
                    <div>
                        <h5 class="mb-0">Payments & Ledger</h5>
                        <span class="text-body-secondary">Record payments and view ledger history</span>
                    </div>
                </div>

                <div class="accordion" id="accordionPayments">
                    <div class="card accordion-item active">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#pay-1" aria-expanded="true">
                                How do I record a payment received from a customer?
                            </button>
                        </h2>
                        <div id="pay-1" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Go to <strong>Billing → Receive Payment</strong>.</li>
                                    <li>Select mode = <strong>Received</strong> (customer is paying us).</li>
                                    <li>Choose the customer, enter the amount, payment method, and date.</li>
                                    <li>Click <strong>Record Payment</strong>.</li>
                                </ol>
                                The ledger entry is posted instantly and the customer's outstanding balance is reduced.
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pay-2">
                                How do I record a payment made to a vendor?
                            </button>
                        </h2>
                        <div id="pay-2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                On the same payment form, select mode = <strong>Made</strong> (we are paying a vendor). Choose the vendor, enter amount and date. A <code>payment_made</code> ledger entry is created, reducing the vendor's payable balance.
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pay-3">
                                How do I view the full ledger for a customer or vendor?
                            </button>
                        </h2>
                        <div id="pay-3" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <p>From the <strong>Customers & Vendors</strong> list, click any party name to open their detail page. There is a <strong>View Ledger</strong> button that opens the full chronological ledger with all debits, credits, and running balances.</p>
                                <p class="mb-0">You can also reach the ledger from any bill's detail page via the customer name link.</p>
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pay-4">
                                What does the running balance mean?
                            </button>
                        </h2>
                        <div id="pay-4" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                    <li><strong>Positive balance</strong> — The party owes us money (they are a debtor / receivable).</li>
                                    <li><strong>Zero balance</strong> — All settled.</li>
                                    <li><strong>Negative balance</strong> — We have overpaid them or they have credit with us.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CUSTOMERS --}}
            <div class="tab-pane fade {{ $activeTab === 'customers' ? 'show active' : '' }}" id="mod-customers">
                <div class="d-flex mb-4 gap-4 align-items-center">
                    <span class="badge bg-label-primary rounded h-px-50 py-2">
                        <i class="ti tabler-users icon-30px"></i>
                    </span>
                    <div>
                        <h5 class="mb-0">Customers & Vendors</h5>
                        <span class="text-body-secondary">Manage your parties (customers and vendors)</span>
                    </div>
                </div>

                <div class="accordion" id="accordionCustomers">
                    <div class="card accordion-item active">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#cust-1" aria-expanded="true">
                                What is a "Party Type" and why does it matter?
                            </button>
                        </h2>
                        <div id="cust-1" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <p>Every person in AdoisLedger is called a <strong>Party</strong>. They have a <code>party_type</code> that controls where they appear:</p>
                                <table class="table table-bordered table-sm mb-0">
                                    <thead><tr><th>Type</th><th>Appears in</th></tr></thead>
                                    <tbody>
                                        <tr><td><code>customer</code></td><td>Bill customer dropdown only</td></tr>
                                        <tr><td><code>vendor</code></td><td>Purchase vendor dropdown only</td></tr>
                                        <tr><td><code>both</code></td><td>Both bill and purchase dropdowns</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cust-2">
                                How do I add a new customer or vendor?
                            </button>
                        </h2>
                        <div id="cust-2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Go to <strong>Parties → Customers & Vendors</strong>.</li>
                                    <li>Click <strong>Add New</strong>.</li>
                                    <li>Fill in Name, Phone, Address, and select the Party Type.</li>
                                    <li>Optionally upload a profile photo.</li>
                                    <li>Click <strong>Save</strong>.</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cust-3">
                                What does "Assign to Business" do?
                            </button>
                        </h2>
                        <div id="cust-3" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                Customers are global but must be <strong>assigned to a business</strong> before appearing in that business's billing dropdowns. When you create a bill for a customer who is not yet assigned to the active business, they are assigned automatically. You can also manually assign them from the Customer detail page → <strong>Assign to Businesses</strong>.
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cust-4">
                                Can I search or filter customers?
                            </button>
                        </h2>
                        <div id="cust-4" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                Yes. The Customers & Vendors list has a search bar at the top — it searches across name and phone number. You can also filter by party type (All / Customers / Vendors) using the filter chips.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- REPORTS --}}
            <div class="tab-pane fade {{ $activeTab === 'reports' ? 'show active' : '' }}" id="mod-reports">
                <div class="d-flex mb-4 gap-4 align-items-center">
                    <span class="badge bg-label-danger rounded h-px-50 py-2">
                        <i class="ti tabler-chart-bar icon-30px"></i>
                    </span>
                    <div>
                        <h5 class="mb-0">Reports</h5>
                        <span class="text-body-secondary">Balance sheet and financial reports</span>
                    </div>
                </div>

                <div class="accordion" id="accordionReports">
                    <div class="card accordion-item active">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#rep-1" aria-expanded="true">
                                What does the Balance Sheet show?
                            </button>
                        </h2>
                        <div id="rep-1" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <p>The Balance Sheet (<strong>Accounts → Balance Sheet</strong>) shows every party associated with the active business, their outstanding balance, and a summary row:</p>
                                <ul class="mb-0">
                                    <li><strong>Total Receivable</strong> — Sum of all positive balances (customers who owe us)</li>
                                    <li><strong>Total Payable</strong> — Sum of all negative balances (vendors we owe)</li>
                                    <li><strong>Net Position</strong> — Receivable minus Payable</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#rep-2">
                                How do I view a per-customer ledger from the Balance Sheet?
                            </button>
                        </h2>
                        <div id="rep-2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                On the Balance Sheet page, click any customer/vendor name. You are taken to the per-customer ledger view (<strong>Balance Sheet → Customer Detail</strong>) which shows every transaction, payment, and the running balance line by line.
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#rep-3">
                                Can I see bill details from the Balance Sheet?
                            </button>
                        </h2>
                        <div id="rep-3" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                Yes. On the customer detail page in Balance Sheet, each transaction row has a <strong>View Details</strong> icon. Clicking it opens a side panel (offcanvas) showing the full item breakdown of that bill — without leaving the report page.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SETTINGS --}}
            <div class="tab-pane fade {{ $activeTab === 'settings' ? 'show active' : '' }}" id="mod-settings">
                <div class="d-flex mb-4 gap-4 align-items-center">
                    <span class="badge bg-label-secondary rounded h-px-50 py-2">
                        <i class="ti tabler-settings icon-30px"></i>
                    </span>
                    <div>
                        <h5 class="mb-0">Settings</h5>
                        <span class="text-body-secondary">Business profiles, materials, and units</span>
                    </div>
                </div>

                <div class="accordion" id="accordionSettings">
                    <div class="card accordion-item active">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#set-1" aria-expanded="true">
                                How do I manage Business Profiles?
                            </button>
                        </h2>
                        <div id="set-1" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <p>Go to <strong>Settings → Business Profiles</strong>. From here you can:</p>
                                <ul class="mb-0">
                                    <li>View all your businesses</li>
                                    <li>Create a new business (name, type, GSTIN, bank details)</li>
                                    <li>Edit an existing business's details</li>
                                    <li>Delete a business (warning: this is permanent)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#set-2">
                                What information is stored on a Business Profile?
                            </button>
                        </h2>
                        <div id="set-2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                    <li>Business name and owner name</li>
                                    <li>Phone number and address</li>
                                    <li>GSTIN (optional)</li>
                                    <li>Business type (Dairy / General / Construction)</li>
                                    <li>Bank account details (name, account number, IFSC, branch) — shown on PDF invoices</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#set-3">
                                Where do I manage Materials & Units?
                            </button>
                        </h2>
                        <div id="set-3" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <p><strong>Materials & Units</strong> is only available when a <span class="badge bg-label-info">Construction</span> business is active. Go to <strong>Settings → Materials & Units</strong>.</p>
                                <ul class="mb-0">
                                    <li>Add/remove <strong>Materials</strong> (products you sell — e.g. River Sand, Bricks)</li>
                                    <li>Add/remove <strong>Units</strong> (measurement units — e.g. CFT, MT, Bags)</li>
                                </ul>
                                These appear as dropdown options when creating a construction bill.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    // Activate the tab from URL ?tab= query parameter
    document.addEventListener('DOMContentLoaded', function () {
        const activeTab = '{{ $activeTab }}';
        const btn = document.querySelector(`[data-bs-target="#mod-${activeTab}"]`);
        if (btn) btn.click();
    });
</script>
@endpush

@endsection
