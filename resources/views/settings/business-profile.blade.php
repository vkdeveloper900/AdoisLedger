@extends('layouts.app')

@section('title', 'Settings - Business Profile')

@section('content')
    <div class="row">
        <div class="col-md-12">

            {{-- Settings Tab Nav --}}
            <div class="nav-align-top">
                <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-md-0 gap-2">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('settings.business-profile.edit') }}">
                            <i class="icon-base ti tabler-building icon-sm me-1_5"></i> Business Profile
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Success Alert --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible mb-6" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Business Profile</h5>
                </div>
                <div class="card-body pt-4">
                    <form method="POST" action="{{ route('settings.business-profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row gy-4 gx-6 mb-6">

                            {{-- Business Name --}}
                            <div class="col-md-6">
                                <label for="name" class="form-label">Business Name <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $profile->name) }}"
                                    placeholder="e.g. Adois Traders"
                                    required />
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Business Type --}}
                            <div class="col-md-6">
                                <label for="business_type_id" class="form-label">Business Type <span class="text-danger">*</span></label>
                                <select
                                    id="business_type_id"
                                    name="business_type_id"
                                    class="form-select @error('business_type_id') is-invalid @enderror"
                                    required>
                                    <option value="">Select Type</option>
                                    @foreach ($businessTypes as $type)
                                        <option
                                            value="{{ $type['id'] }}"
                                            {{ old('business_type_id', $profile->business_type_id) == $type['id'] ? 'selected' : '' }}>
                                            {{ $type['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('business_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Manager Name --}}
                            <div class="col-md-6">
                                <label for="manager_name" class="form-label">Manager Name</label>
                                <input
                                    type="text"
                                    id="manager_name"
                                    name="manager_name"
                                    class="form-control @error('manager_name') is-invalid @enderror"
                                    value="{{ old('manager_name', $profile->manager_name) }}"
                                    placeholder="e.g. Ahmed Khan" />
                                @error('manager_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $profile->email) }}"
                                    placeholder="business@example.com" />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input
                                    type="text"
                                    id="phone"
                                    name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $profile->phone) }}"
                                    placeholder="+92 300 0000000" />
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- City --}}
                            <div class="col-md-6">
                                <label for="city" class="form-label">City</label>
                                <input
                                    type="text"
                                    id="city"
                                    name="city"
                                    class="form-control @error('city') is-invalid @enderror"
                                    value="{{ old('city', $profile->city) }}"
                                    placeholder="e.g. Lahore" />
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Address --}}
                            <div class="col-md-6">
                                <label for="address" class="form-label">Address</label>
                                <input
                                    type="text"
                                    id="address"
                                    name="address"
                                    class="form-control @error('address') is-invalid @enderror"
                                    value="{{ old('address', $profile->address) }}"
                                    placeholder="Street / Area" />
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Country --}}
                            <div class="col-md-6">
                                <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    id="country"
                                    name="country"
                                    class="form-control @error('country') is-invalid @enderror"
                                    value="{{ old('country', $profile->country ?? 'India') }}"
                                    placeholder="e.g. India" />
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-3">Save Changes</button>
                            <a href="{{ route('dashboard') }}" class="btn btn-label-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
