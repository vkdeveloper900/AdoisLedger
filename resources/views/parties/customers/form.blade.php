@extends('layouts.app')

@section('title', isset($customer) ? 'Edit Customer' : 'Add Customer')

@section('content')
    <div class="d-flex align-items-center mb-6">
        <a href="{{ route('customers.index') }}" class="btn btn-icon btn-text-secondary rounded-pill me-3">
            <i class="icon-base ti tabler-arrow-left"></i>
        </a>
        <h4 class="mb-0">{{ isset($customer) ? 'Edit Customer' : 'Add Customer' }}</h4>
    </div>

    <form method="POST"
          action="{{ isset($customer) ? route('customers.update', $customer) : route('customers.store') }}"
          enctype="multipart/form-data">
        @csrf
        @if (isset($customer)) @method('PUT') @endif

        {{-- Basic Info --}}
        <div class="card mb-6">
            <div class="card-header">
                <h5 class="card-title mb-0">Basic Information</h5>
            </div>
            <div class="card-body">
                <div class="row gy-4 gx-6">

                    {{-- Profile Image --}}
                    <div class="col-12">
                        <label class="form-label">Profile Image</label>
                        <div class="d-flex align-items-center gap-4">
                            <div class="avatar avatar-xl">
                                @if (isset($customer) && $customer->profile_image)
                                    <img src="{{ asset('storage/' . $customer->profile_image) }}"
                                         alt="Profile" class="rounded-circle" id="preview" />
                                @else
                                    <span class="avatar-initial rounded-circle bg-label-primary" id="preview-initials">
                                        <i class="icon-base ti tabler-user icon-md"></i>
                                    </span>
                                @endif
                            </div>
                            <div>
                                <input type="file" name="profile_image" id="profile_image"
                                       class="form-control @error('profile_image') is-invalid @enderror"
                                       accept="image/*" />
                                <small class="text-body-secondary">JPG, PNG. Max 1MB.</small>
                                @error('profile_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $customer->name ?? '') }}"
                               placeholder="Full name" required />
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $customer->email ?? '') }}"
                               placeholder="customer@example.com" />
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="mobile" class="form-label">Mobile 1</label>
                        <input type="text" id="mobile" name="mobile"
                               class="form-control @error('mobile') is-invalid @enderror"
                               value="{{ old('mobile', $customer->mobile ?? '') }}"
                               placeholder="+91 9000000000" />
                        @error('mobile') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="mobile2" class="form-label">Mobile 2</label>
                        <input type="text" id="mobile2" name="mobile2"
                               class="form-control @error('mobile2') is-invalid @enderror"
                               value="{{ old('mobile2', $customer->mobile2 ?? '') }}"
                               placeholder="+91 9000000001" />
                        @error('mobile2') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label for="address" class="form-label">Address</label>
                        <textarea id="address" name="address" rows="2"
                                  class="form-control @error('address') is-invalid @enderror"
                                  placeholder="Street, City, State">{{ old('address', $customer->address ?? '') }}</textarea>
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- Bank / Payment Details --}}
        <div class="card mb-6">
            <div class="card-header">
                <h5 class="card-title mb-0">Bank & Payment Details</h5>
            </div>
            <div class="card-body">
                <div class="row gy-4 gx-6">

                    <div class="col-md-6">
                        <label for="bank_account_number" class="form-label">Account Number</label>
                        <input type="text" id="bank_account_number" name="bank_account_number"
                               class="form-control @error('bank_account_number') is-invalid @enderror"
                               value="{{ old('bank_account_number', $customer->bank_account_number ?? '') }}"
                               placeholder="e.g. 0123456789" />
                        @error('bank_account_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="bank_ifsc_code" class="form-label">IFSC Code</label>
                        <input type="text" id="bank_ifsc_code" name="bank_ifsc_code"
                               class="form-control @error('bank_ifsc_code') is-invalid @enderror"
                               value="{{ old('bank_ifsc_code', $customer->bank_ifsc_code ?? '') }}"
                               placeholder="e.g. SBIN0001234" />
                        @error('bank_ifsc_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="bank_branch" class="form-label">Branch</label>
                        <input type="text" id="bank_branch" name="bank_branch"
                               class="form-control @error('bank_branch') is-invalid @enderror"
                               value="{{ old('bank_branch', $customer->bank_branch ?? '') }}"
                               placeholder="e.g. Main Branch, Mumbai" />
                        @error('bank_branch') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="upi_id" class="form-label">UPI ID</label>
                        <input type="text" id="upi_id" name="upi_id"
                               class="form-control @error('upi_id') is-invalid @enderror"
                               value="{{ old('upi_id', $customer->upi_id ?? '') }}"
                               placeholder="e.g. name@upi" />
                        @error('upi_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                </div>
            </div>
        </div>

        <div>
            <button type="submit" class="btn btn-primary me-3">
                {{ isset($customer) ? 'Update Customer' : 'Create Customer' }}
            </button>
            <a href="{{ route('customers.index') }}" class="btn btn-label-secondary">Cancel</a>
        </div>

    </form>
@endsection
