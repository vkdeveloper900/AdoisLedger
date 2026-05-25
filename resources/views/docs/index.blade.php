@extends('layouts.app')

@section('title', 'Documentation')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-faq.css') }}">
<style>
    .doc-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        cursor: pointer;
        text-decoration: none;
        display: block;
    }
    .doc-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,.12) !important;
    }
    .doc-card .card-body { padding: 1.75rem; }
    .doc-icon-wrap {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }
    .doc-header {
        background: linear-gradient(135deg, #7367f0 0%, #9e95f5 100%);
        border-radius: 1rem;
        padding: 3rem 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .doc-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,.08);
        border-radius: 50%;
    }
    .doc-header::after {
        content: '';
        position: absolute;
        bottom: -40%;
        right: 5%;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,.05);
        border-radius: 50%;
    }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="doc-header text-white">
    <div class="position-relative z-1">
        <h3 class="text-white mb-2 fw-bold">
            <i class="ti tabler-book me-2"></i> AdoisLedger Documentation
        </h3>
        <p class="mb-0 opacity-75">Everything you need to know — from first login to advanced reports.</p>
    </div>
</div>

{{-- Quick-nav cards --}}
<div class="row g-4 mb-5">

    <div class="col-xl-4 col-md-6">
        <a href="{{ route('docs.getting-started') }}" class="doc-card card shadow-none border">
            <div class="card-body">
                <div class="doc-icon-wrap bg-label-success">
                    <i class="ti tabler-rocket icon-28px text-success"></i>
                </div>
                <h5 class="mb-1">Getting Started</h5>
                <p class="text-body-secondary mb-0">First login, business setup, and navigating the dashboard.</p>
                <div class="mt-3">
                    <span class="badge bg-label-success">Beginner</span>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-md-6">
        <a href="{{ route('docs.project-flow') }}" class="doc-card card shadow-none border">
            <div class="card-body">
                <div class="doc-icon-wrap bg-label-primary">
                    <i class="ti tabler-network icon-28px text-primary"></i>
                </div>
                <h5 class="mb-1">Project Flow</h5>
                <p class="text-body-secondary mb-0">System architecture, multi-tenancy, ledger double-entry, and transaction lifecycle.</p>
                <div class="mt-3">
                    <span class="badge bg-label-primary">Architecture</span>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-md-6">
        <a href="{{ route('docs.business-guide') }}" class="doc-card card shadow-none border">
            <div class="card-body">
                <div class="doc-icon-wrap bg-label-warning">
                    <i class="ti tabler-building-store icon-28px text-warning"></i>
                </div>
                <h5 class="mb-1">Business Type Guide</h5>
                <p class="text-body-secondary mb-0">Dairy, General Store, and Construction — what each type supports and how to use it.</p>
                <div class="mt-3">
                    <span class="badge bg-label-warning me-1">Dairy</span>
                    <span class="badge bg-label-danger me-1">General</span>
                    <span class="badge bg-label-info">Construction</span>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-md-6">
        <a href="{{ route('docs.modules') }}?tab=billing" class="doc-card card shadow-none border">
            <div class="card-body">
                <div class="doc-icon-wrap bg-label-info">
                    <i class="ti tabler-file-invoice icon-28px text-info"></i>
                </div>
                <h5 class="mb-1">Billing Module</h5>
                <p class="text-body-secondary mb-0">Create bills, manage bill numbers, print invoices, and track outstanding balances.</p>
                <div class="mt-3">
                    <span class="badge bg-label-info">Module</span>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-md-6">
        <a href="{{ route('docs.modules') }}?tab=payments" class="doc-card card shadow-none border">
            <div class="card-body">
                <div class="doc-icon-wrap bg-label-secondary">
                    <i class="ti tabler-cash icon-28px text-secondary"></i>
                </div>
                <h5 class="mb-1">Payments & Ledger</h5>
                <p class="text-body-secondary mb-0">Record payments, view running balances, and understand the ledger entries.</p>
                <div class="mt-3">
                    <span class="badge bg-label-secondary">Module</span>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-md-6">
        <a href="{{ route('docs.modules') }}?tab=reports" class="doc-card card shadow-none border">
            <div class="card-body">
                <div class="doc-icon-wrap bg-label-danger">
                    <i class="ti tabler-chart-bar icon-28px text-danger"></i>
                </div>
                <h5 class="mb-1">Reports & Accounts</h5>
                <p class="text-body-secondary mb-0">Balance sheet, receivables vs payables, and per-customer ledger reports.</p>
                <div class="mt-3">
                    <span class="badge bg-label-danger">Module</span>
                </div>
            </div>
        </a>
    </div>

</div>

{{-- All modules quick links --}}
<div class="card border shadow-none">
    <div class="card-header border-bottom">
        <h6 class="mb-0"><i class="ti tabler-layout-grid me-2 text-primary"></i>All Module Guides</h6>
    </div>
    <div class="card-body py-3">
        <div class="row g-2">
            @php
            $modules = [
                ['tab' => 'billing',    'icon' => 'tabler-file-invoice',  'label' => 'Billing',            'color' => 'info'],
                ['tab' => 'purchases',  'icon' => 'tabler-basket',         'label' => 'Purchases',          'color' => 'warning'],
                ['tab' => 'payments',   'icon' => 'tabler-cash',           'label' => 'Payments & Ledger',  'color' => 'success'],
                ['tab' => 'customers',  'icon' => 'tabler-users',          'label' => 'Customers & Vendors','color' => 'primary'],
                ['tab' => 'reports',    'icon' => 'tabler-chart-bar',      'label' => 'Reports',            'color' => 'danger'],
                ['tab' => 'settings',   'icon' => 'tabler-settings',       'label' => 'Settings',           'color' => 'secondary'],
            ];
            @endphp
            @foreach($modules as $m)
            <div class="col-md-4 col-6">
                <a href="{{ route('docs.modules') }}?tab={{ $m['tab'] }}" class="d-flex align-items-center gap-2 p-2 rounded text-body text-decoration-none hover-bg">
                    <span class="badge bg-label-{{ $m['color'] }} p-2">
                        <i class="ti {{ $m['icon'] }} icon-16px"></i>
                    </span>
                    <span class="fw-medium small">{{ $m['label'] }}</span>
                    <i class="ti tabler-chevron-right ms-auto text-body-secondary" style="font-size:12px"></i>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
