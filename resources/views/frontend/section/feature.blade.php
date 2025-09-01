@php
    $app_local   = get_default_language_code();
@endphp

<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        Start Features
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="features ptb-60 bg_img" data-background="{{ asset('public/frontend/images/element/fbg.png') }}">
    <div class="container mx-auto">
        <div class="text-content">
            <h4>{{ $feature->value->language->$app_local->title ?? "" }}</h4>
            <h3>{{ $feature->value->language->$app_local->heading ?? "" }}</h3>
        </div>
        <div class="row g-3 pt-40">
            @foreach ($feature->value->items ?? [] as $item)
                <div class="col-lg-4 col-md-6 col-12" data-aos="zoom-in">
                    <div class="card">
                        <div class="d-flex">
                            <div class="thumb">
                                <img src="{{ get_image($item->image ?? null, 'site-section') }}" alt="icon">
                            </div>
                            <div>
                                <h3>{{ $item->language->$app_local->item_title ?? "" }}</h3>
                                <p>{{ $item->language->$app_local->description ?? "" }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Features
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->