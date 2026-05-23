<nav
    class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="icon-base ti tabler-menu-2 icon-md"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
        <div class="navbar-nav align-items-center me-auto">
            @if ($activeProfile)
                <div class="nav-item d-flex align-items-center gap-2">
                    <i class="icon-base ti tabler-building-store text-primary icon-md"></i>
                    <span class="fw-bold text-primary">{{ $activeProfile->name }}</span>
                    <span class="badge bg-label-primary rounded-pill" style="font-size:10px;">
                        {{ $activeProfile->business_type_label }}
                    </span>
                    <form method="POST" action="{{ route('shop.exit') }}" class="d-inline ms-1">
                        @csrf
                        <button type="submit"
                                class="btn btn-sm btn-icon btn-text-danger rounded-pill p-0"
                                title="Exit Business"
                                onclick="return confirm('Exit {{ addslashes($activeProfile->name) }}?')">
                            <i class="icon-base ti tabler-door-exit icon-md"></i>
                        </button>
                    </form>
                </div>
            @else
                <div class="nav-item d-flex align-items-center gap-2">
                    <i class="icon-base ti tabler-building-store text-body-secondary icon-md"></i>
                    <span class="text-body-secondary">No business selected</span>
                </div>
            @endif
        </div>

        <ul class="navbar-nav flex-row align-items-center ms-md-auto">
            <li class="nav-item dropdown me-2 me-xl-0">
                <a
                    class="nav-link dropdown-toggle hide-arrow"
                    id="nav-theme"
                    href="javascript:void(0);"
                    data-bs-toggle="dropdown">
                    <i class="icon-base ti tabler-sun icon-md theme-icon-active"></i>
                    <span class="d-none ms-2" id="nav-theme-text">Toggle theme</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="nav-theme-text">
                    <li>
                        <button type="button" class="dropdown-item align-items-center active" data-bs-theme-value="light" aria-pressed="false">
                            <span><i class="icon-base ti tabler-sun icon-md me-3" data-icon="sun"></i>Light</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="dark" aria-pressed="true">
                            <span><i class="icon-base ti tabler-moon-stars icon-md me-3" data-icon="moon-stars"></i>Dark</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="system" aria-pressed="false">
                            <span><i class="icon-base ti tabler-device-desktop-analytics icon-md me-3" data-icon="device-desktop-analytics"></i>System</span>
                        </button>
                    </li>
                </ul>
            </li>

            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt="User avatar" class="rounded-circle">
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt="User avatar" class="w-px-40 h-auto rounded-circle">
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                                    <small class="text-body-secondary">Admin</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li><div class="dropdown-divider my-1"></div></li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="icon-base ti tabler-user me-3 icon-md"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="icon-base ti tabler-settings me-3 icon-md"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                    <li><div class="dropdown-divider my-1"></div></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="icon-base ti tabler-logout me-3 icon-md"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
