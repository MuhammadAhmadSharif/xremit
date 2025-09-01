<!-- favicon -->
<link rel="shortcut icon" href="{{ get_fav($basic_settings) ?? "" }}" type="image/x-icon">
{{-- google font link --}}
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
<!-- fontawesome css link -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/fontawesome-all.css')}}">
<!-- bootstrap css link -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/bootstrap.css')}}">
<!-- swipper css link -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/swiper.css')}}">
<!-- lightcase css links -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/lightcase.css')}}">
<!-- line-awesome-icon css -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/line-awesome.css')}}">
<!-- animate.css -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/animate.css')}}">
<!-- Aos CSS -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/aos.css')}}">
<!-- nice-select -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/nice-select.css')}}">
<!-- country-select -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/countrySelect.css')}}">
<!-- Popup  -->
<link rel="stylesheet" href="{{ asset('public/backend/library/popup/magnific-popup.css') }}">
<!-- main style css link -->
<link rel="stylesheet" href="{{ asset('public/frontend/css/style.css')}}">
<!-- select2-select css link -->
<link rel="stylesheet" href="{{ asset('public/backend/css/select2.css') }}">
<!-- Fileholder CSS CDN -->
<link rel="stylesheet" href="https://appdevs.cloud/cdn/fileholder/v1.0/css/fileholder-style.css" type="text/css">

@php
    $color = @$basic_settings->base_color ?? '#723eeb';
@endphp

<style>
    :root {
        --primary-color: {{$color}};
    }
</style>

