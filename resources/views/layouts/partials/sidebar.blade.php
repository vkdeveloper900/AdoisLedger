<aside id="layout-menu" class="layout-menu menu-vertical menu">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img
                    src="{{ asset('assets/img/branding/brand-img-light.png') }}"
                    alt="AdoisLedger"
                    data-app-light-img="branding/brand-img-light.png"
                    data-app-dark-img="branding/brand-img-dark.png"
                    style="height: 28px; width: auto;">
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-3">AdoisLedger</span>
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

        <!-- Accounting Section Header -->
        <li class="menu-header small">
            <span class="menu-header-text">Accounting</span>
        </li>

        <!-- Accounts -->
        <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="menu-icon icon-base ti tabler-building-bank"></i>
                <div>Accounts</div>
            </a>
        </li>

        <!-- Parties -->
        <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="menu-icon icon-base ti tabler-users"></i>
                <div>Parties</div>
            </a>
        </li>

        <!-- Transactions -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ti tabler-arrows-exchange"></i>
                <div>Transactions</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <div>Journal Entries</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <div>Payments</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <div>Receipts</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Documents Section Header -->
        <li class="menu-header small">
            <span class="menu-header-text">Documents</span>
        </li>

        <!-- Invoices -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ti tabler-file-invoice"></i>
                <div>Invoices</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <div>Sales Invoices</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <div>Purchase Invoices</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Reports Section Header -->
        <li class="menu-header small">
            <span class="menu-header-text">Reports</span>
        </li>

        <!-- Reports -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ti tabler-chart-bar"></i>
                <div>Reports</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <div>Trial Balance</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <div>Ledger</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <div>Profit & Loss</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <div>Balance Sheet</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- System Section Header -->
        <li class="menu-header small">
            <span class="menu-header-text">System</span>
        </li>

        <!-- Settings -->
        <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="menu-icon icon-base ti tabler-settings"></i>
                <div>Settings</div>
            </a>
        </li>
    </ul>
</aside>
