@php
    $app_local    = get_default_language_code();
@endphp

<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        Start Client
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="client ptb-60 overflow-hidden">
    <div class="container mx-auto client">
        <div class="row">
            <div class="col-12 my-auto">
                <div class="text-content">
                    <h4>{{ $testimonial->value->language->$app_local->title ?? "" }}</h4>
                    <h3>{{ $testimonial->value->language->$app_local->heading ?? "" }}</h3>
                </div>
            </div>
            <div class="col-12">
                <div class="client-slider mt-2">
                    <div class="swiper-wrapper">
                        @foreach ($testimonial->value->items ?? [] as $item)
                            <div class="swiper-slide">
                                <div class="d-flex flex-wrap card" data-aos="zoom-in">
                                    <div class="client-content">
                                        <p>"{{ $item->language->$app_local->comment ?? "" }}"
                                        </p>
                                    </div>
                                    <div class="client-thumb d-flex mt-5">
                                        <div class="me-3">
                                            <img src="{{ get_image($item->image ?? null , 'site-section') }}" alt="client">
                                        </div>
                                        <div>
                                            <h3>{{ $item->name ?? "" }}</h3>
                                            <p class="sub">{{ $item->designation ?? "" }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Client 
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->