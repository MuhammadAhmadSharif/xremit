@php
    $app_local   = get_default_language_code();
@endphp
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        Start How It Work
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="how-it-work ptb-60">
    <div class="container mx-auto">
        <div class="row">
            <div class="col-lg-6 col-12">
                <img src="{{ get_image($how_its_work->value->image ?? null, 'site-section') }}" alt="image">
            </div>
            <div class="col-lg-6 col-12 my-auto">
                <div class="text-content">
                    <h4>{{ $how_its_work->value->language->$app_local->title ?? "" }}</h4>
                    <h3>{{ $how_its_work->value->language->$app_local->heading ?? "" }}</h3>
                </div>
                <div>
                    <p>{{ $how_its_work->value->language->$app_local->sub_heading ?? "" }}</p>
                </div>
                <div class="row g-4 pt-40 bottom-card">
                    @foreach ($how_its_work->value->items ?? [] as $item)
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="bottom-card-img">
                                    <div class="bottom-img">
                                    <img src="{{ get_image($item->image ?? null , 'site-section') }}" class="img1" alt="icon">
                                    </div>
                                    <h3>{{ $item->language->$app_local->item_title ?? "" }}</h3>
                                <p>{{ $item->language->$app_local->description ?? "" }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End How It Work
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->