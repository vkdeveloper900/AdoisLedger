@extends('layouts.app')

@section('title', 'Customers')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-6">
        <div>
            <h4 class="mb-1">Customers</h4>
            <p class="mb-0 text-body-secondary">{{ $customers->total() }} total customers</p>
        </div>
        <div class="d-flex gap-2">
            @if ($activeProfile && ! $viewAll)
                <a href="{{ route('customers.index', ['view_all' => 1]) }}"
                   class="btn btn-label-secondary">
                    <i class="icon-base ti tabler-eye me-1"></i> View All
                </a>
            @elseif ($activeProfile && $viewAll)
                <a href="{{ route('customers.index') }}"
                   class="btn btn-label-primary">
                    <i class="icon-base ti tabler-building-store me-1"></i> {{ $activeProfile->name }} Only
                </a>
            @endif
            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                <i class="icon-base ti tabler-plus me-1"></i> Add Customer
            </a>
        </div>
    </div>

    {{-- Active scope notice --}}
    @if ($activeProfile && ! $viewAll)
        <div class="alert alert-primary d-flex align-items-center gap-2 mb-6 py-2">
            <i class="icon-base ti tabler-building-store"></i>
            Showing customers of <strong class="ms-1">{{ $activeProfile->name }}</strong>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible mb-6" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">

        {{-- Filters --}}
        <div class="card-header border-bottom">
            <form method="GET" action="{{ route('customers.index') }}" class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label class="form-label small text-body-secondary mb-1">Search</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="icon-base ti tabler-search"></i></span>
                        <input type="text" name="search" class="form-control"
                               placeholder="Name, mobile, email…"
                               value="{{ request('search') }}" />
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label small text-body-secondary mb-1">Business Profile</label>
                    <select name="business_profile_id" class="form-select">
                        <option value="">All Businesses</option>
                        @foreach ($businessProfiles as $profile)
                            <option value="{{ $profile->id }}"
                                {{ request('business_profile_id') == $profile->id ? 'selected' : '' }}>
                                {{ $profile->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="icon-base ti tabler-filter me-1"></i> Filter
                    </button>
                    @if (request('search') || request('business_profile_id'))
                        <a href="{{ route('customers.index') }}" class="btn btn-label-secondary">
                            <i class="icon-base ti tabler-x me-1"></i> Clear
                        </a>
                    @endif
                </div>

            </form>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Businesses</th>
                        <th>UPI / Account</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                        <tr>
                            <td class="text-body-secondary small">{{ $customer->id }}</td>

                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm">
                                        @if ($customer->profile_image)
                                            <img src="{{ asset('storage/' . $customer->profile_image) }}"
                                                 class="rounded-circle" alt="{{ $customer->name }}" />
                                        @else
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                {{ strtoupper(substr($customer->name, 0, 2)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="fw-medium">{{ $customer->name }}</span>
                                        @if ($customer->mobile2)
                                            <br><small class="text-body-secondary">{{ $customer->mobile2 }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td>{{ $customer->mobile ?: '—' }}</td>

                            <td>{{ $customer->email ?: '—' }}</td>

                            <td>
                                @forelse ($customer->businessProfiles as $profile)
                                    <span class="badge bg-label-info rounded-pill me-1">{{ $profile->name }}</span>
                                @empty
                                    <span class="text-body-secondary small">—</span>
                                @endforelse
                            </td>

                            <td>
                                @if ($customer->upi_id)
                                    <span class="badge bg-label-success rounded-pill">{{ $customer->upi_id }}</span>
                                @elseif ($customer->bank_account_number)
                                    <small class="text-body-secondary">{{ $customer->bank_account_number }}</small>
                                @else
                                    <span class="text-body-secondary small">—</span>
                                @endif
                            </td>

                            <td class="text-end text-nowrap">
                                {{-- Assign Business --}}
                                <button type="button"
                                        class="btn btn-sm btn-icon btn-text-warning rounded-pill"
                                        title="Assign Business"
                                        data-bs-toggle="modal"
                                        data-bs-target="#assignModal{{ $customer->id }}">
                                    <i class="icon-base ti tabler-building-store"></i>
                                </button>

                                <a href="{{ route('customers.show', $customer) }}"
                                   class="btn btn-sm btn-icon btn-text-secondary rounded-pill" title="View">
                                    <i class="icon-base ti tabler-eye"></i>
                                </a>
                                <a href="{{ route('customers.edit', $customer) }}"
                                   class="btn btn-sm btn-icon btn-text-secondary rounded-pill" title="Edit">
                                    <i class="icon-base ti tabler-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('customers.destroy', $customer) }}"
                                      class="d-inline" onsubmit="return confirm('Delete this customer?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-icon btn-text-danger rounded-pill" title="Delete">
                                        <i class="icon-base ti tabler-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- Assign Business Modal --}}
                        <div class="modal fade" id="assignModal{{ $customer->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <i class="icon-base ti tabler-building-store me-2"></i>Assign Businesses
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('customers.assign-business', $customer) }}">
                                        @csrf
                                        <div class="modal-body">
                                            <p class="text-body-secondary small mb-3">
                                                Select all businesses for <strong>{{ $customer->name }}</strong>:
                                            </p>
                                            @foreach ($businessProfiles as $profile)
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="business_profile_ids[]"
                                                           value="{{ $profile->id }}"
                                                           id="bp_{{ $customer->id }}_{{ $profile->id }}"
                                                           {{ $customer->businessProfiles->contains($profile->id) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="bp_{{ $customer->id }}_{{ $profile->id }}">
                                                        {{ $profile->name }}
                                                        <span class="badge bg-label-secondary ms-1">{{ $profile->business_type_label }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-body-secondary">
                                <i class="icon-base ti tabler-users icon-32px d-block mx-auto mb-2"></i>
                                No customers found.
                                @if (request('search') || request('business_profile_id'))
                                    <a href="{{ route('customers.index') }}">Clear filters</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($customers->hasPages())
            <div class="card-footer d-flex justify-content-end">
                {{ $customers->links() }}
            </div>
        @endif

    </div>
@endsection
