@extends('layouts.auth')

@section('title', '404 - Page Not Found')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-misc.css') }}" />
@endpush

@section('content')
<div class="container-xxl container-p-y">
    <div class="misc-wrapper">
        <h1 class="mb-2 mx-2" style="line-height: 6rem; font-size: 6rem;">404</h1>
        <h4 class="mb-2 mx-2">Page Not Found ⚠️</h4>
        <p class="mb-6 mx-2 text-body-secondary">We couldn't find the page you are looking for.</p>
        <a href="{{ url('/') }}" class="btn btn-primary mb-10">Back to Home</a>
        <div class="mt-4">
            <img src="{{ asset('assets/img/illustrations/page-misc-error.png') }}"
                 alt="page-not-found"
                 width="225"
                 class="img-fluid" />
        </div>
    </div>
</div>

<div class="container-fluid misc-bg-wrapper">
    <img src="{{ asset('assets/img/illustrations/bg-shape-image-light.png') }}"
         height="355"
         alt="bg-shape"
         data-app-light-img="illustrations/bg-shape-image-light.png"
         data-app-dark-img="illustrations/bg-shape-image-dark.png" />
</div>
@endsection
