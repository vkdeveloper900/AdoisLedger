@extends('layouts.app')

@section('title', 'Getting Started')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-faq.css') }}">
@endpush

@section('content')

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('docs.index') }}">Documentation</a></li>
        <li class="breadcrumb-item active">Getting Started</li>
    </ol>
</nav>

<div class="row">
    {{-- Left nav --}}
    <div class="col-lg-3 col-md-4 mb-4 mb-md-0">
        <div class="d-flex flex-column nav-align-left">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-login">
                        <i class="ti tabler-lock icon-sm faq-nav-icon me-1_5"></i>
                        <span class="align-middle">Login</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-business">
                        <i class="ti tabler-building-store icon-sm faq-nav-icon me-1_5"></i>
                        <span class="align-middle">Business Setup</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-dashboard">
                        <i class="ti tabler-smart-home icon-sm faq-nav-icon me-1_5"></i>
                        <span class="align-middle">Dashboard</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-navigation">
                        <i class="ti tabler-layout-sidebar icon-sm faq-nav-icon me-1_5"></i>
                        <span class="align-middle">Navigation</span>
                    </button>
                </li>
            </ul>
            <div class="mt-4 d-none d-md-block">
                <div class="alert alert-primary border-0 p-3">
                    <i class="ti tabler-info-circle me-2"></i>
                    <small>Need more help? Browse the <a href="{{ route('docs.index') }}">full documentation</a>.</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Right content --}}
    <div class="col-lg-9 col-md-8">
        <div class="tab-content p-0">

            {{-- Login --}}
            <div class="tab-pane fade show active" id="tab-login" role="tabpanel">
                <div class="d-flex mb-4 gap-4 align-items-center">
                    <span class="badge bg-label-primary rounded h-px-50 py-2">
                        <i class="ti tabler-lock icon-30px"></i>
                    </span>
                    <div>
                        <h5 class="mb-0">Logging In</h5>
                        <span class="text-body-secondary">Access your AdoisLedger account</span>
                    </div>
                </div>

                <div class="accordion" id="accordionLogin">
                    <div class="card accordion-item active">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#login-1" aria-expanded="true">
                                How do I log in?
                            </button>
                        </h2>
                        <div id="login-1" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <p>Open the app in your browser and navigate to <code>/login</code>. Enter your credentials:</p>
                                <ul>
                                    <li><strong>Email:</strong> Your registered email address</li>
                                    <li><strong>Password:</strong> Your account password</li>
                                </ul>
                                <p class="mb-0">Click <strong>Sign In</strong>. On success you are redirected to the Dashboard.</p>
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#login-2">
                                What are the default test credentials?
                            </button>
                        </h2>
                        <div id="login-2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <p>After running the database seeder, you can log in with:</p>
                                <table class="table table-bordered table-sm mb-0">
                                    <tr><td class="fw-medium">Email</td><td><code>admin@gmail.com</code></td></tr>
                                    <tr><td class="fw-medium">Password</td><td><code>Admin@123</code></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#login-3">
                                How do I log out?
                            </button>
                        </h2>
                        <div id="login-3" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                Click your <strong>user avatar</strong> in the top-right navbar. A dropdown appears — click <strong>Logout</strong>. Your session and active business context are cleared immediately.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Business Setup --}}
            <div class="tab-pane fade" id="tab-business" role="tabpanel">
                <div class="d-flex mb-4 gap-4 align-items-center">
                    <span class="badge bg-label-warning rounded h-px-50 py-2">
                        <i class="ti tabler-building-store icon-30px"></i>
                    </span>
                    <div>
                        <h5 class="mb-0">Business Setup</h5>
                        <span class="text-body-secondary">Create and switch between your businesses</span>
                    </div>
                </div>

                <div class="accordion" id="accordionBusiness">
                    <div class="card accordion-item active">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#biz-1" aria-expanded="true">
                                How do I create a new business profile?
                            </button>
                        </h2>
                        <div id="biz-1" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Go to <strong>Settings → Business Profiles</strong> in the sidebar.</li>
                                    <li>Click <strong>Add Business</strong>.</li>
                                    <li>Fill in: Business Name, Owner Name, Phone, Address, GSTIN, and <strong>Business Type</strong>.</li>
                                    <li>Click <strong>Save</strong>. The profile appears on your Dashboard.</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#biz-2">
                                How do I switch to a business (Enter Shop)?
                            </button>
                        </h2>
                        <div id="biz-2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <p>On the Dashboard, each business card shows an <strong>Enter</strong> button. Click it to activate that business. The sidebar will immediately update to show only the modules relevant to that business type.</p>
                                <p class="mb-0">To exit a business and return to the overview Dashboard, click <strong>Exit Shop</strong> from the top navbar or Dashboard.</p>
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#biz-3">
                                What business types are available?
                            </button>
                        </h2>
                        <div id="biz-3" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <table class="table table-bordered table-sm mb-0">
                                    <thead><tr><th>Type</th><th>Use Case</th><th>Extra Modules</th></tr></thead>
                                    <tbody>
                                        <tr><td><span class="badge bg-label-warning">Dairy</span></td><td>Milk & dairy farms</td><td>Milk Purchases (fat % tracking)</td></tr>
                                        <tr><td><span class="badge bg-label-danger">General</span></td><td>Retail / general stores</td><td>Basic billing only</td></tr>
                                        <tr><td><span class="badge bg-label-info">Construction</span></td><td>Construction suppliers</td><td>Materials & Units management</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dashboard --}}
            <div class="tab-pane fade" id="tab-dashboard" role="tabpanel">
                <div class="d-flex mb-4 gap-4 align-items-center">
                    <span class="badge bg-label-success rounded h-px-50 py-2">
                        <i class="ti tabler-smart-home icon-30px"></i>
                    </span>
                    <div>
                        <h5 class="mb-0">Dashboard</h5>
                        <span class="text-body-secondary">Understanding your overview and stats</span>
                    </div>
                </div>

                <div class="accordion" id="accordionDash">
                    <div class="card accordion-item active">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#dash-1" aria-expanded="true">
                                What does the main Dashboard show?
                            </button>
                        </h2>
                        <div id="dash-1" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <p>When <strong>no business is active</strong>, the Dashboard shows a summary across all your businesses: total receivables, total payables, and a list of all business profiles with an Enter button.</p>
                                <p class="mb-0">When a <strong>business is active</strong>, it shows that business's stats: today's revenue, monthly revenue, outstanding receivables, payables, customer count, and recent transactions.</p>
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#dash-2">
                                What are Receivables and Payables?
                            </button>
                        </h2>
                        <div id="dash-2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                    <li><strong>Receivables</strong> — Money that customers owe <em>you</em> for unpaid bills.</li>
                                    <li><strong>Payables</strong> — Money that <em>you</em> owe vendors for purchases not yet paid.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <div class="tab-pane fade" id="tab-navigation" role="tabpanel">
                <div class="d-flex mb-4 gap-4 align-items-center">
                    <span class="badge bg-label-info rounded h-px-50 py-2">
                        <i class="ti tabler-layout-sidebar icon-30px"></i>
                    </span>
                    <div>
                        <h5 class="mb-0">Navigation</h5>
                        <span class="text-body-secondary">How the sidebar and menus work</span>
                    </div>
                </div>

                <div class="accordion" id="accordionNav">
                    <div class="card accordion-item active">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#nav-1" aria-expanded="true">
                                Why does the sidebar change when I enter a business?
                            </button>
                        </h2>
                        <div id="nav-1" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                The sidebar is context-aware. When no business is active, only <strong>Dashboard</strong>, <strong>Settings</strong>, and <strong>Documentation</strong> are shown. When you enter a business, the relevant modules (Billing, Purchases, Parties, Accounts) appear based on that business's type.
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#nav-2">
                                What sections are always visible?
                            </button>
                        </h2>
                        <div id="nav-2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                    <li><strong>Dashboard</strong> — always visible</li>
                                    <li><strong>Settings</strong> — always visible (Business Profiles, Materials & Units for Construction)</li>
                                    <li><strong>Documentation</strong> — always visible</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#nav-3">
                                How do I collapse/expand the sidebar?
                            </button>
                        </h2>
                        <div id="nav-3" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                Click the <strong>toggle icon</strong> (three horizontal lines) in the top-left of the sidebar header. On desktop it collapses to icon-only mode. On mobile, use the floating menu button at the bottom-left.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
