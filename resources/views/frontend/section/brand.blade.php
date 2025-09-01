<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        Start Brand
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div class="brand ptb-60">
    <div class="container mx-auto">
        <div class="swiper mySwiper2 overflow-hidden">
            <div class="swiper-wrapper" data-swiper-autoplay="4000">
                @foreach ($brand->value->items ?? [] as $key=>$item)
                    <div class="swiper-slide">
                        <img src="{{ get_image($item->image ?? null ,'site-section') }}" alt="brand">
                    </div> 
                @endforeach
            </div>
        </div>
    </div>
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Brand
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->