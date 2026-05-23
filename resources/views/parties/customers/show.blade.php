@extends('layouts.app')

@section('title', $customer->name)

@section('content')
    <div class="d-flex align-items-center mb-6">
        <a href="{{ route('customers.index') }}" class="btn btn-icon btn-text-secondary rounded-pill me-3">
            <i class="icon-base ti tabler-arrow-left"></i>
        </a>
        <h4 class="mb-0">{{ $customer->name }}</h4>
        <div class="ms-auto d-flex gap-2">
            <a href="{{ route('ledger.show', $customer) }}" class="btn btn-sm btn-label-info">
                <i class="icon-base ti tabler-book me-1"></i> Ledger
            </a>
            <a href="{{ route('reports.balance-sheet.customer', $customer) }}" class="btn btn-sm btn-label-secondary">
                <i class="icon-base ti tabler-report-analytics me-1"></i> Balance Sheet
            </a>
            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-primary">
                <i class="icon-base ti tabler-edit me-1"></i> Edit
            </a>
        </div>
    </div>

    <div class="row g-6">

        {{-- Basic Info --}}
        <div class="col-md-5">
            <div class="card h-100">
                <div class="card-body text-center pt-6">
                    <div class="avatar avatar-xl mx-auto mb-4">
                        @if ($customer->profile_image)
                            <img src="{{ asset('storage/' . $customer->profile_image) }}"
                                 alt="{{ $customer->name }}" class="rounded-circle" />
                        @else
                            <span class="avatar-initial rounded-circle bg-label-primary" style="font-size:1.5rem">
                                {{ strtoupper(substr($customer->name, 0, 2)) }}
                            </span>
                        @endif
                    </div>
                    <h5 class="mb-1">{{ $customer->name }}</h5>
                    @if ($customer->email)
                        <p class="text-body-secondary small mb-0">{{ $customer->email }}</p>
                    @endif
                </div>
                <div class="card-body border-top">
                    <dl class="row mb-0 gy-2">
                        @if ($customer->mobile)
                            <dt class="col-5 text-body-secondary small">Mobile 1</dt>
                            <dd class="col-7 mb-0 fw-medium">{{ $customer->mobile }}</dd>
                        @endif
                        @if ($customer->mobile2)
                            <dt class="col-5 text-body-secondary small">Mobile 2</dt>
                            <dd class="col-7 mb-0 fw-medium">{{ $customer->mobile2 }}</dd>
                        @endif
                        @if ($customer->address)
                            <dt class="col-5 text-body-secondary small">Address</dt>
                            <dd class="col-7 mb-0 fw-medium">{{ $customer->address }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        {{-- Bank / Payment Details --}}
        <div class="col-md-7">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Bank & Payment Details</h5>
                </div>
                <div class="card-body">
                    <dl class="row gy-3 mb-0">
                        <dt class="col-sm-5 text-body-secondary small">Account Number</dt>
                        <dd class="col-sm-7 mb-0 fw-medium">{{ $customer->bank_account_number ?: '—' }}</dd>

                        <dt class="col-sm-5 text-body-secondary small">IFSC Code</dt>
                        <dd class="col-sm-7 mb-0 fw-medium">{{ $customer->bank_ifsc_code ?: '—' }}</dd>

                        <dt class="col-sm-5 text-body-secondary small">Branch</dt>
                        <dd class="col-sm-7 mb-0 fw-medium">{{ $customer->bank_branch ?: '—' }}</dd>

                        <dt class="col-sm-5 text-body-secondary small">UPI ID</dt>
                        <dd class="col-sm-7 mb-0 fw-medium">{{ $customer->upi_id ?: '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>

    </div>
@endsection
