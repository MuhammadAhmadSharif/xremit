<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        Start Preloader
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <div class="preloader">
        <div class="cssload-preloader">
            <div class="cssload-preloader-box">
                @php
                    $animation_move_delay = 0;
                @endphp
                @foreach (str_split($basic_settings->site_name) as $item)
                    @php
                        $animation_move_delay += 86
                    @endphp
                    <div class="style text-uppercase" style="animation-delay: {{ $animation_move_delay }}ms">{{ $item }}</div>
                @endforeach
            </div>
        </div>
    </div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        End Preloader
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
