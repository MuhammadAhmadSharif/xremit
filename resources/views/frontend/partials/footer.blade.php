@php
    $app_local     = get_default_language_code();
@endphp
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        Start Footer 
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <footer class="footer-section">
        <div class="container mx-auto">
            <div class="footer-content pt-100 pb-30">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 mb-50">
                        <div class="f-img">
                            <img src="{{ @$footer->value->footer->image ? get_image($footer->value->footer->image,'site-section') : get_logo($basic_settings) }}" alt=" alt">
                        </div>
                        <div class="footer-widget">
                            <div class="footer-text">
                                <p>{{ $footer->value->footer->language->$app_local->description ?? '' }}</p>
                            </div>
                            <div class="footer-social-icon">
                                @php
                                    $items = $footer->value->social_links ?? [];
                                @endphp
                                <span>{{ __("Follow us") }}</span>
                                @foreach ($items as $item)
                                    <a href="{{ $item->link ?? "" }}" target="_blank"><i class="{{ $item->icon ?? ""}}"></i></a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 mb-30">
                        <div class="footer-widget">
                            <div class="footer-widget-heading">
                                <h3>{{ __("Useful Links") }}</h3>
                            </div>
                            <ul>
                                @foreach ($useful_link ?? [] as $item)
                                    <li><a href="{{ setRoute('link',$item->slug)}}">{{ $item?->title?->language?->$app_local?->title ?? ""}}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 mb-50">
                        <div class="footer-widget">
                            <div class="footer-widget-heading">
                                <h3>{{ $subscribe->value->language->$app_local->title ?? "" }}</h3>
                            </div>
                            <div class="footer-text mb-25">
                                <p>{{ $subscribe->value->language->$app_local->description ?? "" }}</p>
                            </div>
                            <div class="subscribe-form">
                                <form id="subscribe-form" action="{{ setRoute('subscribe') }} " method="POST">
                                    @csrf
                                    <input type="email" name="email" placeholder="{{ __("Email Address") }}">
                                    <button><i class="fab fa-telegram-plane"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright-area">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center text-lg-left">
                        <div class="copyright-text">
                            <p>{{ __("Copyright") }} &copy; 2024, {{ __("All Right Reserved") }} <a href="{{ setRoute('index') }}">{{ $basic_settings->site_name }}</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        End Footer 
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->