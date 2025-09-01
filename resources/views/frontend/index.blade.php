@extends('frontend.layouts.master')

@push("css")

@endpush

@section('content')



    @include('frontend.section.banner')
    @include('frontend.section.country')
    @include('frontend.section.feature')
    @include('frontend.section.how-it-work')
    @include('frontend.section.testimonial')
    @include('frontend.section.choose-us')
    @include('frontend.section.download-app')
    @include('frontend.section.brand')

@endsection

@push("script")

@endpush
