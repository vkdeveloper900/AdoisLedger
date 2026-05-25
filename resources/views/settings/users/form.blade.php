@extends('layouts.app')

@section('title', $user ? 'Edit User' : 'Add User')

@section('content')

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('settings.users.index') }}">Users</a></li>
        @if($user)
            <li class="breadcrumb-item"><a href="{{ route('settings.users.show', $user) }}">{{ $user->name }}</a></li>
            <li class="breadcrumb-item active">Edit</li>
        @else
            <li class="breadcrumb-item active">Add User</li>
        @endif
    </ol>
</nav>

<form action="{{ $user ? route('settings.users.update', $user) : route('settings.users.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if($user) @method('PUT') @endif

    <div class="row g-4">

        {{-- Left: Avatar upload --}}
        <div class="col-xl-4 col-lg-5">
            <div class="card">
                <div class="card-body text-center py-6">
                    {{-- Preview --}}
                    <div class="mb-4">
                        <img id="avatarPreview"
                             src="{{ $user ? $user->avatar_url : 'https://ui-avatars.com/api/?name=New+User&background=7367f0&color=fff&size=128' }}"
                             alt="Avatar"
                             class="rounded-circle border border-3 border-white shadow"
                             style="width:100px;height:100px;object-fit:cover">
                    </div>
                    <h6 class="mb-1">{{ $user ? $user->name : 'New User' }}</h6>
                    <p class="text-body-secondary small mb-4">JPG, PNG up to 2 MB</p>
                    <label for="avatarInput" class="btn btn-label-primary btn-sm mb-2">
                        <i class="ti tabler-upload me-1"></i> Upload Photo
                    </label>
                    <input type="file" id="avatarInput" name="avatar" class="d-none" accept="image/*">
                    @error('avatar') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- Status toggle --}}
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="mb-3">Account Status</h6>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="isActive" name="is_active"
                               value="1" {{ old('is_active', $user?->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">
                            Active Account
                        </label>
                    </div>
                    <small class="text-body-secondary d-block mt-2">Inactive users cannot log in.</small>
                </div>
            </div>
        </div>

        {{-- Right: Fields --}}
        <div class="col-xl-8 col-lg-7">
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="mb-0">{{ $user ? 'Edit User' : 'Add New User' }}</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">

                        {{-- Name --}}
                        <div class="col-12">
                            <label class="form-label" for="name">Full Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user?->name) }}" placeholder="Enter full name" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label class="form-label" for="email">Email Address <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user?->email) }}" placeholder="user@example.com" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-6">
                            <label class="form-label" for="phone">Phone</label>
                            <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $user?->phone) }}" placeholder="+91 99999 99999">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Password --}}
                        <div class="col-md-6">
                            <label class="form-label" for="password">
                                Password {{ $user ? '(leave blank to keep current)' : '' }}
                                @if(!$user) <span class="text-danger">*</span> @endif
                            </label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Min 8 characters"
                                       {{ !$user ? 'required' : '' }}>
                                <span class="input-group-text cursor-pointer" onclick="togglePass('password')">
                                    <i class="ti tabler-eye icon-xs" id="password-eye"></i>
                                </span>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="col-md-6">
                            <label class="form-label" for="password_confirmation">
                                Confirm Password
                                @if(!$user) <span class="text-danger">*</span> @endif
                            </label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="form-control"
                                       placeholder="Repeat password"
                                       {{ !$user ? 'required' : '' }}>
                                <span class="input-group-text cursor-pointer" onclick="togglePass('password_confirmation')">
                                    <i class="ti tabler-eye icon-xs" id="password_confirmation-eye"></i>
                                </span>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-footer border-top d-flex gap-2 justify-content-end">
                    <a href="{{ $user ? route('settings.users.show', $user) : route('settings.users.index') }}"
                       class="btn btn-label-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti tabler-check me-1"></i>
                        {{ $user ? 'Update User' : 'Create User' }}
                    </button>
                </div>
            </div>
        </div>

    </div>
</form>

@push('scripts')
<script>
    // Avatar preview
    document.getElementById('avatarInput').addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
            reader.readAsDataURL(file);
        }
    });

    // Toggle password visibility
    function togglePass(id) {
        const input = document.getElementById(id);
        const icon  = document.getElementById(id + '-eye');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('tabler-eye', 'tabler-eye-off');
        } else {
            input.type = 'password';
            icon.classList.replace('tabler-eye-off', 'tabler-eye');
        }
    }
</script>
@endpush

@endsection
