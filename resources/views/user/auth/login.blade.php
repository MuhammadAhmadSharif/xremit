@php
    $app_local  = get_default_language_code();
    $slug = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::LOGIN_SECTION);
    $login = App\Models\Admin\SiteSections::getData($slug)->first();
@endphp
@extends('frontend.layouts.master')

@push("css")
    
@endpush

@section('content') 
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Sign In 
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="login pt-150 pb-80">
    <div class="container mx-auto">
        <div class="row">
            <div class="col-md-6 d-grid my-auto py-5 login-img">
                <img src="{{ get_image(@$login->value->image , 'site-section') }}" alt="Image" class="img-fluid">
            </div>

            <div class="col-md-6 my-auto">
                <div class="content">
                    <div class="my-3">
                        <h3 class="pb-2 text-capitalize fw-bold">{{ @$login->value->language->$app_local->heading ?? '' }}</h3>
                    </div>
                    <form action="{{ setRoute('user.login.submit') }}" method="POST">
                        @csrf
                        <div class="form-group ">
                            <label for="email">{{ __("Email") }}</label>
                            <input type="email" class="form--control" id="email" name="credentials" placeholder="{{ __("Enter Your Email") }}...">

                        </div>
                        <div class="form-group show_hide_password">
                            <label>{{ __("Password") }}</label>
                            <input type="password" class="form--control" name="password" placeholder="{{ __("Enter Your Password") }}...">
                            <a href="javascript:void(0)" class="show-pass icon field-icon"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                        </div>
                        <div class="forgot-item text-end">
                            <label><a href="{{ setRoute('user.password.forgot') }}" class="text--base">{{ __("Forgot Password?") }}</a></label>
                        </div>
                        <button type="submit" class="btn--base w-100 text-center mt-2">{{ __("Login") }}</button>

                        <p class="d-block text-center mt-3 create-acc">
                            &mdash; {{ __("Don't have an account?") }}
                            @if ($basic_settings->user_registration == true)   
                                <a href="{{ setRoute('user.register')}}">{{ __("Register") }}</a>
                            @endif
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