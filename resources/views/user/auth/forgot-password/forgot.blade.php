<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $page_title ?? $basic_settings->site_name }}</title>
    @include('partials.header-asset')
    @stack('css')
</head>

<body>

    @include('frontend.partials.preloader')


    <div id="body-overlay" class="body-overlay"></div>

    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start acount
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="account forgot">
    <div class="account-area">
        <div class="account-wrapper change-form">
            <div class="account-logo">
                <a class="site-logo" href="{{ setRoute('index')}}"><img src="{{ get_logo($basic_settings,"dark")}}" alt="logo"></a>
            </div>
            <h5 class="title">{{ __("Forgot Password?") }}</h5>
            <p>{{ __("Enter your email and we'll send you a otp to reset your password.") }}</p>
            <form class="account-form" action="{{ setRoute('user.password.forgot.send.code') }}" method="POST">
                @csrf
                <div class="row ml-b-20">
                    <div class="col-xl-12 col-lg-12 form-group">
                        <input type="email" name="credentials" class="form--control" placeholder="{{ __("Email") }}...">
                    </div>
                    <div class="col-lg-12 form-group text-center">
                        <button type="submit" class="btn--base w-100">{{ __("Send OTP") }}</button>
                    </div>
                    <div class="col-lg-12 text-center">
                        <div class="account-item">
                            <label>{{ __("Already Have An Account?") }} <a href="{{ setRoute('user.login') }}" class="account-control-btn">{{ __("Login Now") }}</a></label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End acount
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->

@include('partials.footer-asset')
@include('admin.partials.notify')

<script>
    $(document).ready(function() {
        $("#show_hide_password a").on('click', function(event) {
            event.preventDefault();
            if($('#show_hide_password input').attr("type") == "text"){
                $('#show_hide_password input').attr('type', 'password');
                $('#show_hide_password i').addClass( "fa-eye-slash" );
                $('#show_hide_password i').removeClass( "fa-eye" );
            }else if($('#show_hide_password input').attr("type") == "password"){
                $('#show_hide_password input').attr('type', 'text');
                $('#show_hide_password i').removeClass( "fa-eye-slash" );
                $('#show_hide_password i').addClass( "fa-eye" );
            }
        });
    });
</script>


</body>
</html>
