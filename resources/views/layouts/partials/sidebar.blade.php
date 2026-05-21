@php
    $menuItems = [
        ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'tabler-smart-home'],
        ['label' => 'Accounts', 'url' => '#', 'icon' => 'tabler-building-bank'],
        ['label' => 'Parties', 'url' => '#', 'icon' => 'tabler-users'],
        ['label' => 'Transactions', 'url' => '#', 'icon' => 'tabler-arrows-exchange'],
        ['label' => 'Invoices', 'url' => '#', 'icon' => 'tabler-file-invoice'],
        ['label' => 'Reports', 'url' => '#', 'icon' => 'tabler-chart-bar'],
        ['label' => 'Settings', 'url' => '#', 'icon' => 'tabler-settings'],
    ];
@endphp

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
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
            <i class="icon-base ti tabler-x d-block d-xl-none"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @foreach ($menuItems as $item)
            @php
                $isActive = isset($item['route']) && request()->routeIs($item['route']);
                $href = isset($item['route']) ? route($item['route']) : $item['url'];
            @endphp
            <li class="menu-item {{ $isActive ? 'active' : '' }}">
                <a href="{{ $href }}" class="menu-link">
                    <i class="menu-icon icon-base ti {{ $item['icon'] }}"></i>
                    <div>{{ $item['label'] }}</div>
                </a>
            </li>
        @endforeach
    </ul>
</aside>
