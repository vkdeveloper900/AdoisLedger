<aside id="layout-menu" class="layout-menu menu-vertical menu">
    <div class="app-brand demo ">
        <a href="{{route('dashboard')}}" class="app-brand-link">
              <span class="app-brand-logo demo">
                <span class="text-primary">
                  <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                        fill="currentColor" />
                    <path
                        opacity="0.06"
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z"
                        fill="#161616" />
                    <path
                        opacity="0.06"
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z"
                        fill="#161616" />
                    <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                        fill="currentColor" />
                  </svg>
                </span>
              </span>
            <span class="app-brand-text demo menu-text fw-bold ms-3">Vuexy</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
            <i class="icon-base ti tabler-x d-block d-xl-none"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        <!-- Dashboard -->
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-smart-home"></i>
                <div>Dashboard</div>
            </a>
        </li>

        @if ($activeProfile)

        <!-- Billing -->
        <li class="menu-header small">
            <span class="menu-header-text">Billing</span>
        </li>

        <li class="menu-item {{ request()->routeIs('bills.index') || request()->routeIs('bills.show') ? 'active' : '' }}">
            <a href="{{ route('bills.index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-file-invoice"></i>
                <div>Bills</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('bills.create') ? 'active' : '' }}">
            <a href="{{ route('bills.create') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-file-plus"></i>
                <div>Create Bill</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('payments.*') ? 'active' : '' }}">
            <a href="{{ route('payments.create') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-cash"></i>
                <div>Receive Payment</div>
            </a>
        </li>

        @if ($activeProfile->business_type_id === 2)
        <li class="menu-header small">
            <span class="menu-header-text">Purchases</span>
        </li>
        <li class="menu-item {{ request()->routeIs('purchases.index') || request()->routeIs('purchases.show') ? 'active' : '' }}">
            <a href="{{ route('purchases.index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-basket"></i>
                <div>Milk Purchases</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('purchases.create') ? 'active' : '' }}">
            <a href="{{ route('purchases.create') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-basket-plus"></i>
                <div>Add Purchase</div>
            </a>
        </li>
        @endif

        <!-- Parties -->
        <li class="menu-header small">
            <span class="menu-header-text">Parties</span>
        </li>

        <li class="menu-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
            <a href="{{ route('customers.index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-users"></i>
                <div>Customers & Vendors</div>
            </a>
        </li>

        <!-- Accounts -->
        <li class="menu-header small">
            <span class="menu-header-text">Accounts</span>
        </li>

        <li class="menu-item {{ request()->routeIs('reports.balance-sheet') ? 'active' : '' }}">
            <a href="{{ route('reports.balance-sheet') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-book-2"></i>
                <div>Balance Sheet</div>
            </a>
        </li>

        @endif

        <!-- System -->
        <li class="menu-header small">
            <span class="menu-header-text">System</span>
        </li>

        <li class="menu-item {{ request()->routeIs('settings.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ti tabler-settings"></i>
                <div>Settings</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('settings.business-profiles.*') ? 'active' : '' }}">
                    <a href="{{ route('settings.business-profiles.index') }}" class="menu-link">
                        <div>Business Profiles</div>
                    </a>
                </li>
                @if ($activeProfile && $activeProfile->business_type_id === 3)
                    <li class="menu-item {{ request()->routeIs('settings.material-units') ? 'active' : '' }}">
                        <a href="{{ route('settings.material-units') }}" class="menu-link">
                            <div>Materials & Units</div>
                        </a>
                    </li>
                @endif
            </ul>
        </li>

    </ul>
</aside>
