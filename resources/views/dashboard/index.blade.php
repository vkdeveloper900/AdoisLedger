@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_heading', 'Dashboard')

@section('content')
    <div class="row g-6">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                        <div>
                            <h4 class="card-title mb-2">Welcome to AdoisLedger</h4>
                            <p class="mb-0 text-body-secondary">
                                Your Laravel admin layout is now powered by the Vuexy Bootstrap theme.
                            </p>
                        </div>
                        <a href="#" class="btn btn-primary">
                            <i class="icon-base ti tabler-plus me-1"></i>
                            New Entry
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="icon-base ti tabler-building-bank icon-md"></i>
                            </span>
                        </div>
                        <div>
                            <p class="mb-0">Accounts</p>
                            <h5 class="mb-0">0</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="icon-base ti tabler-users icon-md"></i>
                            </span>
                        </div>
                        <div>
                            <p class="mb-0">Parties</p>
                            <h5 class="mb-0">0</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="icon-base ti tabler-file-invoice icon-md"></i>
                            </span>
                        </div>
                        <div>
                            <p class="mb-0">Invoices</p>
                            <h5 class="mb-0">0</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="icon-base ti tabler-arrows-exchange icon-md"></i>
                            </span>
                        </div>
                        <div>
                            <p class="mb-0">Transactions</p>
                            <h5 class="mb-0">0</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
