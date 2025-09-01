@php
    $app_local   = get_default_language_code();
@endphp
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        Start Why Choose Us
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="why-choose-us ptb-60">
    <div class="container mx-auto">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-12">
                <img src="{{ get_image($choose->value->image ?? null,'site-section')}}" alt="image">
            </div>
            <div class="col-lg-6 col-md-12 col-12 my-auto">
                <div class="text-content">
                    <h4>{{ $choose->value->language->$app_local->title ?? "" }}</h4>
                    <h3>{{ $choose->value->language->$app_local->heading ?? "" }}</h3>
                </div>
                <div>
                    <p>{!! @$choose->value->language->$app_local->sub_heading !!}</p>
                </div>
                <div class="d-flex mt-5">
                    <div class="me-5 banner-join-btn">
                        <a href="javascript:void(0)" class="btn--base mb-4 mb-lg-0 mb-md-0">{{ __($choose->value->button_name ?? "") }}</a>
                    </div>
                    <div class="video-wrapper mt-2">
                        <a class="video-icon" data-rel="lightcase:myCollection"
                            href="{{ $choose->value->video_link ?? ""}}">
                            <i class="las la-play"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Why Choose Us
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
