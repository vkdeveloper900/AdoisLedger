@extends('layouts.app')

@section('title', $user->name)

@section('content')

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('settings.users.index') }}">Users</a></li>
        <li class="breadcrumb-item active">{{ $user->name }}</li>
    </ol>
</nav>

@if(session('success'))
    <div class="alert alert-success alert-dismissible mb-4">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    {{-- Left: Profile Card --}}
    <div class="col-xl-4 col-lg-5">
        <div class="card mb-4">
            <div class="card-body pt-8 pb-6 text-center">
                {{-- Avatar --}}
                <div class="mb-4">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                         class="rounded-circle border border-3 border-white shadow"
                         style="width:100px;height:100px;object-fit:cover">
                </div>
                <h5 class="mb-1">{{ $user->name }}</h5>
                <p class="text-body-secondary mb-3">{{ $user->email }}</p>
                @if($user->is_active)
                    <span class="badge bg-label-success">Active</span>
                @else
                    <span class="badge bg-label-secondary">Inactive</span>
                @endif
            </div>
            <div class="card-body pt-0 border-top">
                <h6 class="mb-4 mt-3">Details</h6>
                <ul class="list-unstyled mb-4">
                    <li class="d-flex justify-content-between mb-3">
                        <span class="text-body-secondary small">Full Name</span>
                        <span class="fw-medium small">{{ $user->name }}</span>
                    </li>
                    <li class="d-flex justify-content-between mb-3">
                        <span class="text-body-secondary small">Email</span>
                        <span class="fw-medium small">{{ $user->email }}</span>
                    </li>
                    <li class="d-flex justify-content-between mb-3">
                        <span class="text-body-secondary small">Phone</span>
                        <span class="fw-medium small">{{ $user->phone ?: '—' }}</span>
                    </li>
                    <li class="d-flex justify-content-between mb-3">
                        <span class="text-body-secondary small">Status</span>
                        @if($user->is_active)
                            <span class="badge bg-label-success">Active</span>
                        @else
                            <span class="badge bg-label-secondary">Inactive</span>
                        @endif
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-body-secondary small">Joined</span>
                        <span class="fw-medium small">{{ $user->created_at->format('d M Y') }}</span>
                    </li>
                </ul>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('settings.users.edit', $user) }}" class="btn btn-primary btn-sm">
                        <i class="ti tabler-edit me-1"></i> Edit
                    </a>
                    @if($user->id !== auth()->id())
                    <form action="{{ route('settings.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-label-danger btn-sm">
                            <i class="ti tabler-trash me-1"></i> Delete
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Account Info --}}
    <div class="col-xl-8 col-lg-7">
        <div class="card mb-4">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Account Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3">
                            <span class="avatar avatar-sm rounded bg-label-primary">
                                <i class="ti tabler-user icon-18px"></i>
                            </span>
                            <div>
                                <small class="text-body-secondary d-block">Full Name</small>
                                <span class="fw-medium">{{ $user->name }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3">
                            <span class="avatar avatar-sm rounded bg-label-info">
                                <i class="ti tabler-mail icon-18px"></i>
                            </span>
                            <div>
                                <small class="text-body-secondary d-block">Email Address</small>
                                <span class="fw-medium">{{ $user->email }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3">
                            <span class="avatar avatar-sm rounded bg-label-success">
                                <i class="ti tabler-phone icon-18px"></i>
                            </span>
                            <div>
                                <small class="text-body-secondary d-block">Phone</small>
                                <span class="fw-medium">{{ $user->phone ?: 'Not set' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3">
                            <span class="avatar avatar-sm rounded bg-label-warning">
                                <i class="ti tabler-calendar icon-18px"></i>
                            </span>
                            <div>
                                <small class="text-body-secondary d-block">Member Since</small>
                                <span class="fw-medium">{{ $user->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3">
                            <span class="avatar avatar-sm rounded {{ $user->is_active ? 'bg-label-success' : 'bg-label-secondary' }}">
                                <i class="ti {{ $user->is_active ? 'tabler-check' : 'tabler-x' }} icon-18px"></i>
                            </span>
                            <div>
                                <small class="text-body-secondary d-block">Account Status</small>
                                <span class="fw-medium">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3">
                            <span class="avatar avatar-sm rounded bg-label-secondary">
                                <i class="ti tabler-clock-edit icon-18px"></i>
                            </span>
                            <div>
                                <small class="text-body-secondary d-block">Last Updated</small>
                                <span class="fw-medium">{{ $user->updated_at->format('d M Y, h:i A') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Security Card --}}
        <div class="card">
            <div class="card-header border-bottom d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Security</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between py-2">
                    <div class="d-flex align-items-center gap-3">
                        <span class="avatar avatar-sm rounded bg-label-danger">
                            <i class="ti tabler-lock icon-18px"></i>
                        </span>
                        <div>
                            <span class="fw-medium d-block">Password</span>
                            <small class="text-body-secondary">Last changed: {{ $user->updated_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    <a href="{{ route('settings.users.edit', $user) }}" class="btn btn-sm btn-label-primary">
                        Change Password
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
