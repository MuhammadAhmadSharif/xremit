@php
    $app_local  = get_default_language_code();
    $slug = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::REGISTER_SECTION);
    $register = App\Models\Admin\SiteSections::getData($slug)->first();
@endphp
@extends('frontend.layouts.master')

@push("css")
    
@endpush

@section('content') 

<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Sign In 
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="login pt-150 pb-80">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-12 col-12 d-grid my-auto py-5 login-img">
                <img src="{{ get_image(@$register->value->image , 'site-section') }}" alt="Image" class="img-fluid">
            </div>
            <div class="col-lg-6 col-md-12 col-12">
                <div class="content">
                    <div class="my-3">
                        <h3 class="pb-2 text-capitalize fw-bold">{{ @$register->value->language->$app_local->heading ?? '' }}</h3>
                    </div>
                    <form action="{{ setRoute('user.register.submit') }}" method="POST" autocomplete="on">
                        @csrf
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6 col-12">
                                <label for="firstname">{{ __("First Name") }}</label>
                                <input type="text" class="form--control" id="firstname" name="firstname" placeholder="{{ __("Enter First Name") }}...">
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-12">
                                <label for="lastname">{{ __("Last Name") }}</label>
                                <input type="text" class="form--control" id="lastname" name="lastname" placeholder="{{ __("Enter Last Name") }}...">
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="email">{{ __("Email") }}</label>
                            <input type="email" class="form--control" id="email" name="email" placeholder="{{ __("Enter Email") }}">
                        </div>
                        <div class="form-group show_hide_password">
                            <label>{{ __("Password") }}</label>
                            <input type="password" class="form--control" name="password" placeholder="{{ __("Enter Password") }}...">
                            <a href="javascript:void(0)" class="show-pass icon field-icon"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                        </div>
                        <div class="form-group">
                            <div class="custom-check-group">
                                <input type="checkbox" name="agree" id="level-1">
                                @foreach ($useful_link ?? [] as $item)
                                <label for="level-1">{{ __("I have agreed with") }} <a href="{{ setRoute('link',$item->slug)}}">{{ __("Terms Of Use & Privacy Policy") }}</a></label>
                                @endforeach
                            </div>
                        </div>
                        <button type="submit" class="btn--base w-100 text-center mt-2">{{ __("Register") }}</button>
                        <p class="d-block text-center mt-3 create-acc">
                            &mdash; {{ __("Already Have An Account?") }}
                            <a href="{{ setRoute('user.login')}}">{{ __("Login") }}</a>
                            &mdash;
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Sign In 
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->    
@endsection

@push("script")
    
@endpush