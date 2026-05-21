<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title', 'Dashboard') | AdoisLedger</title>
<meta name="description" content="@yield('meta_description', 'AdoisLedger admin panel')">

<link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;display=swap"
    rel="stylesheet">

<link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}">

<script src="{{ asset('assets/vendor/libs/@algolia/autocomplete-js.js') }}"></script>

<link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">

@stack('styles')

<script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
<script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>
<script src="{{ asset('assets/js/config.js') }}"></script>
