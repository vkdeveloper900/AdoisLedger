@extends('layouts.auth')

@section('title', '503 - Under Maintenance')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-misc.css') }}" />
@endpush

@section('content')
<div class="container-xxl container-p-y">
    <div class="misc-wrapper">
        <h4 class="mb-2 mx-2">Under Maintenance! 🚧</h4>
        <p class="mb-6 mx-2 text-body-secondary">
            Sorry for the inconvenience — we're performing some maintenance at the moment.<br>
            We'll be back up shortly.
        </p>
        <a href="{{ url('/') }}" class="btn btn-primary">Back to Home</a>
        <div class="mt-12">
            <img src="{{ asset('assets/img/illustrations/page-misc-under-maintenance.png') }}"
                 alt="under-maintenance"
                 width="550"
                 class="img-fluid" />
        </div>
    </div>
</div>

<div class="container-fluid misc-bg-wrapper misc-under-maintenance-bg-wrapper">
    <img src="{{ asset('assets/img/illustrations/bg-shape-image-light.png') }}"
         height="355"
         alt="bg-shape"
         data-app-light-img="illustrations/bg-shape-image-light.png"
         data-app-dark-img="illustrations/bg-shape-image-dark.png" />
</div>
@endsection
