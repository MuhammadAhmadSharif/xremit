@php
    $app_local     = get_default_language_code();
@endphp
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        Start App Download
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="app ptb-60">
    <div class="container mx-auto">
        <div class="row">
            <div class="col-lg-6 col-12 my-auto">
                <div class="text-content">
                    <h4>{{ $app_download->value->language->$app_local->title ?? "" }}</h4>
                    <h3>{{ $app_download->value->language->$app_local->heading ?? "" }}</h3>
                </div>
                <div>
                    <p>{{ $app_download->value->language->$app_local->sub_heading ?? "" }} 
                    </p>
                </div>
                <div class="d-flex mt-5">
                    <div class="me-4 app-btn">
                        <a href="{{ $app_download->value->google_play_link ?? "" }}" class="btn--base mb-4 mb-lg-0 mb-md-0" target="_blank"><i class="lab la-google-play"></i>
                            {{ __("Google Play") }}</a>
                    </div>
                    <div class="">
                        <a href="{{ $app_download->value->app_store_link ?? "" }}" class="btn--base mb-4 mb-lg-0 mb-md-0" target="_blank"><i class="lab la-app-store"></i>{{ __("App Store") }}</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <img src="{{ get_image($app_download->value->image ?? null, 'site-section') }}" alt="image">
            </div>
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End App Download
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->