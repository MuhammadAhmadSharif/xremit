@extends('user.layouts.master')

@section('breadcrumb')
    @include('user.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("user.dashboard"),
        ]
    ], 'active' => __("2FA Security")])
@endsection

@section('content')

<div class="body-wrapper">
    <div class="row mb-20-none">
        <div class="col-xl-6 col-lg-6 mb-20">
            <div class="custom-card mt-10">
                <div class="dashboard-header-wrapper">
                    <h4 class="title">{{ __("Two Factor Authenticator") }}</h4>
                </div>
                <div class="card-body">
                    <form class="card-form">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 form-group">
                                <label>{{ __("QRcode Share") }}</label>
                                <div class="input-group">
                                    <input type="text" class="form--control" id="referralURL" value="{{ auth()->user()->two_factor_secret }}" readonly>
                                    <div class="input-group-text copytext"><i class="las la-copy"></i></div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 form-group">
                                <div class="qr-code-thumb text-center">
                                    <img class="mx-auto" src="{{ $qr_code }}" alt="QR Code">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12">
                            @if (auth()->user()->two_factor_status)
                                <button type="button" class="btn--base bg--warning w-100 active-deactive-btn">{{ __("Disable") }}</button>
                                <br>
                                <div class="text--danger mt-3">{{ __("Don't forget to add this application in your google authentication app. Otherwise you can't login in your account.") }}</div>
                            @else
                                <button type="button" class="btn--base w-100 active-deactive-btn">{{ __("Enable") }}</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 mb-20">
            <div class="custom-card mt-10">
                <div class="dashboard-header-wrapper">
                    <h4 class="title">{{ __("Google Authenticator") }}</h4>
                </div>
                <div class="card-body">
                    <h4 class="mb-3 fatitle">{{ __("Download Google Authenticator App") }}</h4>
                    <p>{{ __("Google Authenticator is a product based authenticator by Google that executes two-venture confirmation administrations for verifying clients of any programming applications.") }}</p>
                    <div class="play-store-thumb text-center mb-20">
                        <img class="mx-auto" src="{{ asset('public/frontend/') }}/images/element/play-store.png">
                    </div>
                    <a href="https://play.google.com/store/apps?hl=en&gl=US" class="btn--base mt-10 w-100">{{ __("Download App") }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('script')
    <script>
        $(".active-deactive-btn").click(function(){
            var actionRoute =  "{{ setRoute('user.security.google.2fa.status.update') }}";
            var target      = 1;
            var btnText = $(this).text();
            var message     = `Are you sure to <strong>${btnText}</strong> 2 factor authentication (Powered by google)?`;
            openAlertModal(actionRoute,target,message,btnText,"POST");
        });

        $('.copytext').on('click',function(){
            var copyText = document.getElementById("referralURL");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");

            throwMessage('success',["Copied: " + copyText.value]);
        });
    </script>
@endpush