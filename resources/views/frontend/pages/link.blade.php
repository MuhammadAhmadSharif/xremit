@extends('frontend.layouts.master')
@php
    $app_local  = get_default_language_code();
@endphp

@section('content')
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        Start Privacy
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <section class="pt-150 pb-80">
        <div class="container mx-auto privacy">
            <div class="content">
                <p>{!! $link->content->language?->$app_local?->content ?? "" !!}</p>
            </div>
        </div>
    </section>
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        End Privacy
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
@endsection