@extends('layouts.app')

@section('title', $businessProfile->name)

@section('content')
    <div class="d-flex align-items-center mb-6">
        <a href="{{ route('settings.business-profiles.index') }}" class="btn btn-icon btn-text-secondary rounded-pill me-3">
            <i class="icon-base ti tabler-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-0">{{ $businessProfile->name }}</h4>
            <small class="text-body-secondary">{{ $businessProfile->business_type_label }}</small>
        </div>
        <div class="ms-auto d-flex gap-2">
            <a href="{{ route('settings.business-profiles.edit', $businessProfile) }}" class="btn btn-primary btn-sm">
                <i class="icon-base ti tabler-edit me-1"></i> Edit
            </a>
            <a href="#" class="btn btn-success btn-sm">
                <i class="icon-base ti tabler-door-enter me-1"></i> Enter Shop
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row gy-4">

                <div class="col-md-6">
                    <p class="text-body-secondary small mb-1">Business Name</p>
                    <p class="fw-medium mb-0">{{ $businessProfile->name }}</p>
                </div>

                <div class="col-md-6">
                    <p class="text-body-secondary small mb-1">Business Type</p>
                    <span class="badge bg-label-info rounded-pill">{{ $businessProfile->business_type_label }}</span>
                </div>

                @if ($businessProfile->manager_name)
                    <div class="col-md-6">
                        <p class="text-body-secondary small mb-1">Manager</p>
                        <p class="fw-medium mb-0">{{ $businessProfile->manager_name }}</p>
                    </div>
                @endif

                @if ($businessProfile->email)
                    <div class="col-md-6">
                        <p class="text-body-secondary small mb-1">Email</p>
                        <p class="fw-medium mb-0">{{ $businessProfile->email }}</p>
                    </div>
                @endif

                @if ($businessProfile->phone)
                    <div class="col-md-6">
                        <p class="text-body-secondary small mb-1">Phone</p>
                        <p class="fw-medium mb-0">{{ $businessProfile->phone }}</p>
                    </div>
                @endif

                @if ($businessProfile->address)
                    <div class="col-md-6">
                        <p class="text-body-secondary small mb-1">Address</p>
                        <p class="fw-medium mb-0">{{ $businessProfile->address }}</p>
                    </div>
                @endif

                @if ($businessProfile->city)
                    <div class="col-md-6">
                        <p class="text-body-secondary small mb-1">City</p>
                        <p class="fw-medium mb-0">{{ $businessProfile->city }}</p>
                    </div>
                @endif

                <div class="col-md-6">
                    <p class="text-body-secondary small mb-1">Country</p>
                    <p class="fw-medium mb-0">{{ $businessProfile->country }}</p>
                </div>

            </div>
        </div>
    </div>
@endsection
