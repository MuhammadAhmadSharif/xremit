@php
    $app_local    = get_default_language_code();
@endphp
@extends('frontend.layouts.master')

@section('content')
       <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        Start Info Card 
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <section class="info-card pt-150 pb-80">
        <div class="container mx-auto">
            <div class="row g-4">
                <div class="col-xl-4 col-lg-6 col-md-6 mb-20">
                    <div class="card">
                        <div class="d-flex">
                            <div class="icon">
                                <i class="las la-map-marker-alt"></i>
                            </div>
                            <div class="content">
                                <h3>{{ __("Address") }}</h3>
                                <p>{{ $contact->value->address ?? "" }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 mb-20">
                    <div class="card">
                        <div class="d-flex">
                            <div class="icon">
                                <i class="las la-phone"></i>
                            </div>
                            <div class="content">
                                <h3>{{ __("Contact") }}</h3>
                                <p class="m-0">{{ __("Mobile:") }} {{ $contact->value->phone ?? "" }}</p>
                                <p class="m-0">{{ __("E-mail:") }} {{ $contact->value->email ?? "" }} </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 mb-20">
                    <div class="card">
                        <div class="d-flex">
                            <div class="icon">
                                <i class="las la-history"></i>
                            </div>
                            <div class="content">
                                <h3>{{ __("Hours of Operation") }}</h3>
                                @foreach ($contact->value->schedules ?? [] as $item)
                                    <p class="m-0">{{ $item->schedule ?? ""}}</p>
                                @endforeach
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        End Info Card 
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        Start Contact
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <section class="contact ptb-40">
        <div class="container mx-auto">
            <div class="text-content">
                <h4>{{ $contact->value?->language->$app_local->title ?? "" }}</h4>
                <h3>{{ $contact->value?->language->$app_local->description ?? "" }}</h3>
            </div>
            <div class="row g-5 ptb-60">
                <div class="col-lg-6 col-md-12 col-12 my-auto">
                    <div class="thumb-right ">
                        <img src="{{ get_image($contact->value->image ?? null, 'site-section') }}" class="d-flex mx-md-auto" alt="image">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-12 my-auto">
                    <div class="row justify-content-center">
                        <div class="col-xl-12">
                            <form id="contact-request-form" class="contact-form" action="{{ setRoute('contact.request') }}" method="POST">
                                @csrf
                                <div class="row justify-content-center mb-10-none">
                                    <div class="col-xl-6 col-lg-6 col-md-12 form-group">
                                        <input type="text" name="name" class="form--control"
                                            placeholder="{{ __("Name") }}...">
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-12 form-group">
                                        <input type="email" name="email" class="form--control"
                                            placeholder="{{ __("Email") }}...">
                                    </div>
                                    <div class="col-12 form-group">
                                        <input type="text" name="subject" class="form--control" placeholder="{{ __("Subject") }}...">
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <textarea name="message" class="form--control text-area" placeholder="{{ __("Message") }}..."
                                            rows="3"></textarea>
                                    </div>
                                    <div class="col-lg-12 form-group text-center">
                                        <button type="submit" class="btn--base mt-30 w-100">{{ __("Send Message") }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        End Contact
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~--> 
@endsection
    
