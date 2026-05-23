@extends('layouts.app')

@section('title', 'Business Profiles')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-6">
        <div>
            <h4 class="mb-1">Business Profiles</h4>
            <p class="mb-0 text-body-secondary">Manage your business shops</p>
        </div>
        <a href="{{ route('settings.business-profiles.create') }}" class="btn btn-primary">
            <i class="icon-base ti tabler-plus me-1"></i> Add Profile
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible mb-6" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($profiles->isEmpty())
        <div class="card">
            <div class="card-body text-center py-10">
                <i class="icon-base ti tabler-building-store icon-48px text-body-secondary mb-4 d-block"></i>
                <h5 class="mb-2">No business profiles yet</h5>
                <p class="text-body-secondary mb-4">Create your first business profile to get started.</p>
                <a href="{{ route('settings.business-profiles.create') }}" class="btn btn-primary">
                    <i class="icon-base ti tabler-plus me-1"></i> Add Profile
                </a>
            </div>
        </div>
    @else
        <div class="row g-6">
            @foreach ($profiles as $profile)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">

                            {{-- Icon + Type Badge --}}
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="avatar avatar-lg">
                                    <span class="avatar-initial rounded bg-label-primary">
                                        <i class="icon-base ti tabler-building-store icon-md"></i>
                                    </span>
                                </div>
                                <span class="badge bg-label-info rounded-pill">{{ $profile->business_type_label }}</span>
                            </div>

                            {{-- Profile Info --}}
                            <h5 class="card-title mb-1">{{ $profile->name }}</h5>
                            @if ($profile->manager_name)
                                <p class="text-body-secondary small mb-1">
                                    <i class="icon-base ti tabler-user icon-xs me-1"></i>{{ $profile->manager_name }}
                                </p>
                            @endif
                            @if ($profile->city)
                                <p class="text-body-secondary small mb-0">
                                    <i class="icon-base ti tabler-map-pin icon-xs me-1"></i>{{ $profile->city }}, {{ $profile->country }}
                                </p>
                            @endif

                            <div class="mt-auto pt-4 d-flex gap-2">
                                <a href="{{ route('settings.business-profiles.show', $profile) }}"
                                   class="btn btn-sm btn-label-primary flex-fill">
                                    <i class="icon-base ti tabler-eye icon-xs me-1"></i> View Profile
                                </a>
                                <form method="POST" action="{{ route('shop.enter', $profile) }}" class="flex-fill">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-sm w-100 {{ $activeProfile?->id === $profile->id ? 'btn-success' : 'btn-primary' }}">
                                        <i class="icon-base ti tabler-door-enter icon-xs me-1"></i>
                                        {{ $activeProfile?->id === $profile->id ? 'Active' : 'Enter Shop' }}
                                    </button>
                                </form>
                            </div>

                        </div>

                        {{-- Card Footer: Edit / Delete --}}
                        <div class="card-footer d-flex justify-content-end gap-2 py-2">
                            <a href="{{ route('settings.business-profiles.edit', $profile) }}"
                               class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                               title="Edit">
                                <i class="icon-base ti tabler-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('settings.business-profiles.destroy', $profile) }}"
                                  onsubmit="return confirm('Delete this profile?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm btn-icon btn-text-danger rounded-pill"
                                        title="Delete">
                                    <i class="icon-base ti tabler-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
