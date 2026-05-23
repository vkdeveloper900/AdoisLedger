@extends('layouts.app')

@section('title', isset($businessProfile) ? 'Edit Business Profile' : 'Add Business Profile')

@section('content')
    <div class="d-flex align-items-center mb-6">
        <a href="{{ route('settings.business-profiles.index') }}" class="btn btn-icon btn-text-secondary rounded-pill me-3">
            <i class="icon-base ti tabler-arrow-left"></i>
        </a>
        <h4 class="mb-0">{{ isset($businessProfile) ? 'Edit Business Profile' : 'Add Business Profile' }}</h4>
    </div>

    <form method="POST" action="{{ isset($businessProfile)
        ? route('settings.business-profiles.update', $businessProfile)
        : route('settings.business-profiles.store') }}">
        @csrf
        @if (isset($businessProfile)) @method('PUT') @endif

        {{-- Basic Info --}}
        <div class="card mb-6">
            <div class="card-header">
                <h5 class="card-title mb-0">Basic Information</h5>
            </div>
            <div class="card-body">
                <div class="row gy-4 gx-6">

                    <div class="col-md-6">
                        <label for="name" class="form-label">Business Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $businessProfile->name ?? '') }}"
                               placeholder="e.g. Adois Traders" required />
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="business_type_id" class="form-label">Business Type <span class="text-danger">*</span></label>
                        <select id="business_type_id" name="business_type_id"
                                class="form-select @error('business_type_id') is-invalid @enderror" required>
                            <option value="">Select Type</option>
                            @foreach ($businessTypes as $type)
                                <option value="{{ $type['id'] }}"
                                    {{ old('business_type_id', $businessProfile->business_type_id ?? '') == $type['id'] ? 'selected' : '' }}>
                                    {{ $type['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('business_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="manager_name" class="form-label">Manager Name</label>
                        <input type="text" id="manager_name" name="manager_name"
                               class="form-control @error('manager_name') is-invalid @enderror"
                               value="{{ old('manager_name', $businessProfile->manager_name ?? '') }}"
                               placeholder="e.g. Ahmed Khan" />
                        @error('manager_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $businessProfile->email ?? '') }}"
                               placeholder="business@example.com" />
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" id="phone" name="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $businessProfile->phone ?? '') }}"
                               placeholder="+92 300 0000000" />
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="city" class="form-label">City</label>
                        <input type="text" id="city" name="city"
                               class="form-control @error('city') is-invalid @enderror"
                               value="{{ old('city', $businessProfile->city ?? '') }}"
                               placeholder="e.g. Lahore" />
                        @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" id="address" name="address"
                               class="form-control @error('address') is-invalid @enderror"
                               value="{{ old('address', $businessProfile->address ?? '') }}"
                               placeholder="Street / Area" />
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                        <input type="text" id="country" name="country"
                               class="form-control @error('country') is-invalid @enderror"
                               value="{{ old('country', $businessProfile->country ?? 'India') }}" />
                        @error('country') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- Bank Details --}}
        <div class="card mb-6">
            <div class="card-header">
                <h5 class="card-title mb-0">Bank Details</h5>
            </div>
            <div class="card-body">
                <div class="row gy-4 gx-6">

                    <div class="col-md-6">
                        <label for="bank_name" class="form-label">Bank Name</label>
                        <input type="text" id="bank_name" name="bank_name"
                               class="form-control @error('bank_name') is-invalid @enderror"
                               value="{{ old('bank_name', $businessProfile->bank_name ?? '') }}"
                               placeholder="e.g. State Bank of India" />
                        @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="bank_holder_name" class="form-label">Account Holder Name</label>
                        <input type="text" id="bank_holder_name" name="bank_holder_name"
                               class="form-control @error('bank_holder_name') is-invalid @enderror"
                               value="{{ old('bank_holder_name', $businessProfile->bank_holder_name ?? '') }}"
                               placeholder="e.g. Adois Traders" />
                        @error('bank_holder_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="bank_account_number" class="form-label">Account Number</label>
                        <input type="text" id="bank_account_number" name="bank_account_number"
                               class="form-control @error('bank_account_number') is-invalid @enderror"
                               value="{{ old('bank_account_number', $businessProfile->bank_account_number ?? '') }}"
                               placeholder="e.g. 0123456789" />
                        @error('bank_account_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="bank_ifsc_code" class="form-label">IFSC Code</label>
                        <input type="text" id="bank_ifsc_code" name="bank_ifsc_code"
                               class="form-control @error('bank_ifsc_code') is-invalid @enderror"
                               value="{{ old('bank_ifsc_code', $businessProfile->bank_ifsc_code ?? '') }}"
                               placeholder="e.g. SBIN0001234" />
                        @error('bank_ifsc_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="bank_branch" class="form-label">Branch</label>
                        <input type="text" id="bank_branch" name="bank_branch"
                               class="form-control @error('bank_branch') is-invalid @enderror"
                               value="{{ old('bank_branch', $businessProfile->bank_branch ?? '') }}"
                               placeholder="e.g. Main Branch, Mumbai" />
                        @error('bank_branch') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                </div>
            </div>
        </div>

        <div>
            <button type="submit" class="btn btn-primary me-3">
                {{ isset($businessProfile) ? 'Update Profile' : 'Create Profile' }}
            </button>
            <a href="{{ route('settings.business-profiles.index') }}" class="btn btn-label-secondary">Cancel</a>
        </div>

    </form>
@endsection
