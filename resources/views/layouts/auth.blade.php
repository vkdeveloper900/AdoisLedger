<!doctype html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="@yield('html_class', 'layout-wide customizer-hide')"
    dir="ltr"
    data-skin="default"
    data-bs-theme="light"
    data-assets-path="{{ asset('assets') }}/"
    data-template="vertical-menu-template-starter">
<head>
    @include('layouts.partials.head')
</head>
<body>
    @yield('content')

    @include('layouts.partials.scripts')
</body>
</html>
