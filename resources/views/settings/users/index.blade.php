@extends('layouts.app')

@section('title', 'Users')

@section('content')

{{-- Header --}}
<div class="d-flex align-items-center justify-content-between mb-6">
    <div>
        <h4 class="mb-1">Users</h4>
        <p class="text-body-secondary mb-0">Manage system users</p>
    </div>
    <a href="{{ route('settings.users.create') }}" class="btn btn-primary">
        <i class="ti tabler-plus me-1"></i> Add User
    </a>
</div>

{{-- Flash --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible mb-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible mb-4" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Stats --}}
<div class="row g-4 mb-6">
    <div class="col-sm-6 col-xl-4">
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-heading d-block">Total Users</span>
                    <h4 class="mb-0 mt-1">{{ $totalUsers }}</h4>
                </div>
                <div class="avatar">
                    <span class="avatar-initial rounded bg-label-primary">
                        <i class="ti tabler-users icon-26px"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-heading d-block">Active</span>
                    <h4 class="mb-0 mt-1">{{ $activeUsers }}</h4>
                </div>
                <div class="avatar">
                    <span class="avatar-initial rounded bg-label-success">
                        <i class="ti tabler-user-check icon-26px"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-heading d-block">Inactive</span>
                    <h4 class="mb-0 mt-1">{{ $inactiveUsers }}</h4>
                </div>
                <div class="avatar">
                    <span class="avatar-initial rounded bg-label-secondary">
                        <i class="ti tabler-user-off icon-26px"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Users Table --}}
<div class="card">
    <div class="card-header border-bottom d-flex align-items-center justify-content-between flex-wrap gap-3">
        <h5 class="mb-0">All Users</h5>
        {{-- Search + Filter --}}
        <form method="GET" action="{{ route('settings.users.index') }}" class="d-flex gap-2 flex-wrap align-items-center">
            <div class="input-group input-group-merge" style="width:220px">
                <span class="input-group-text"><i class="ti tabler-search icon-xs"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Search name / email" value="{{ request('search') }}">
            </div>
            <select name="status" class="form-select" style="width:140px" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button class="btn btn-label-primary btn-sm">Search</button>
            @if(request('search') || request('status'))
                <a href="{{ route('settings.users.index') }}" class="btn btn-label-secondary btn-sm">Clear</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:48px">#</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="text-body-secondary small">{{ $user->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar avatar-sm">
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle">
                            </div>
                            <div>
                                <a href="{{ route('settings.users.show', $user) }}" class="text-heading fw-medium">{{ $user->name }}</a>
                                @if($user->id === auth()->id())
                                    <span class="badge bg-label-primary ms-1" style="font-size:10px">You</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="text-body-secondary">{{ $user->email }}</td>
                    <td class="text-body-secondary">{{ $user->phone ?: '—' }}</td>
                    <td>
                        @if($user->is_active)
                            <span class="badge bg-label-success">Active</span>
                        @else
                            <span class="badge bg-label-secondary">Inactive</span>
                        @endif
                    </td>
                    <td class="text-body-secondary small">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('settings.users.show', $user) }}" class="btn btn-icon btn-sm btn-text-secondary rounded-pill" data-bs-toggle="tooltip" title="View">
                                <i class="ti tabler-eye icon-md"></i>
                            </a>
                            <a href="{{ route('settings.users.edit', $user) }}" class="btn btn-icon btn-sm btn-text-secondary rounded-pill" data-bs-toggle="tooltip" title="Edit">
                                <i class="ti tabler-edit icon-md"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('settings.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-sm btn-text-danger rounded-pill" data-bs-toggle="tooltip" title="Delete">
                                    <i class="ti tabler-trash icon-md"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-body-secondary py-5">
                        <i class="ti tabler-users icon-30px d-block mx-auto mb-2 opacity-50"></i>
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="card-footer border-top d-flex justify-content-end">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection
