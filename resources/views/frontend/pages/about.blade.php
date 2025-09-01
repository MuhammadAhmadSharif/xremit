@extends('frontend.layouts.master')
@php
    $app_local = get_default_language_code();
@endphp
@section('content')
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        Start about-whyChoose
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <section class="overflow-hidden pt-150 pb-40 about-whyChoose">
        <div class="container-xl mx-auto">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-12 left-content my-auto">
                    <h3>{{ $about->value->language->$app_local->heading ?? "" }}</h3>
                    <p>{{ $about->value->language->$app_local->sub_heading ?? "" }}</p>
                </div>
                <div class="col-lg-6 col-md-12 col-12 right-img">
                    <div>
                        <img src="{{ get_image($about->value->image ?? null,'site-section')}}" alt="image">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        End about-whyChoose
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    @include('frontend.section.choose-us')
    @include('frontend.section.brand')

@endsection