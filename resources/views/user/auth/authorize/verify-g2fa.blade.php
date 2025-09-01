
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
    <div id="body-overlay" class="body-overlay"></div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Account
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="account forgot">
    <div class="account-area">
        <div class="account-wrapper change-form">
            <h3 class="title">{{ __("Two Factor Authorization") }}</h3>
            <p>{{ __("Please enter your authorization code to access dashboard") }}</p>
            <form method="POST" class="account-form" action="{{ setRoute('user.authorize.google.2fa.submit') }}">
                @csrf
                <div class="row ml-b-20">
                    <div class="col-lg-12 form-group">
                        <input class="otp" type="text" name="code[]" oninput="digitValidate(this)" onkeyup="tabChange(1)" maxlength="1" required="">
                        <input class="otp" type="text" name="code[]" oninput="digitValidate(this)" onkeyup="tabChange(2)" maxlength="2" required="">
                        <input class="otp" type="text" name="code[]" oninput="digitValidate(this)" onkeyup="tabChange(3)" maxlength="1" required="">
                        <input class="otp" type="text" name="code[]" oninput="digitValidate(this)" onkeyup="tabChange(4)" maxlength="1" required="">
                        <input class="otp" type="text" name="code[]" oninput="digitValidate(this)" onkeyup="tabChange(5)" maxlength="1" required="">
                        <input class="otp" type="text" name="code[]" oninput="digitValidate(this)" onkeyup="tabChange(6)" maxlength="1" required="">
                    </div>
                    <div class="col-lg-12 form-group text-end">
                        <div class="time-area">{{ __("Didn't get the code?") }} <span id="time"> </span></div>
                    </div>
                    <div class="col-lg-12 form-group text-center">
                        <button type="submit" class="btn--base w-100">{{ __("Verify Mail") }}</button>
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
    End Account
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
@include('partials.footer-asset')
@include('admin.partials.notify')
</body>
</html>

@push('script')
    <script>
          let digitValidate = function (ele) {
            ele.value = ele.value.replace(/[^0-9]/g, '');
        }

        let tabChange = function (val) {
            let ele = document.querySelectorAll('.otp');
            if (ele[val - 1].value != '') {
                ele[val].focus()
            } else if (ele[val - 1].value == '') {
                ele[val - 2].focus()
            }
        }
    </script>
@endpush
