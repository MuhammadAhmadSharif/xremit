<nav class="navbar-wrapper">
    <div class="dashboard-title-part">
        <div class="left">
            <div class="icon">
                <button class="sidebar-menu-bar">
                    <i class="fas fa-exchange-alt"></i>
                </button>
            </div>
            <div class="dashboard-path">
                @yield('breadcrumb')
            </div>
        </div>
        <div class="right">
            @php
                $current_url  = URL::current();
            @endphp
            @if ($current_url == setRoute('user.transaction.index'))
                <form class="header-search-wrapper">
                    <div class="position-relative">
                        <input class="form--control" type="text" name="search_text" placeholder="{{ __("Ex: Transactions") }}"
                            aria-label="Search">
                        <span class="las la-search"></span>
                    </div>
                </form>
            @endif
            <div class="header-action">
                <div class="language-select">
                    @php
                        $__current_local = session("local") ?? get_default_language_code();
                    @endphp
                    <select class="nice-select" name="lang_switcher" id="">
                        @foreach ($__languages as $__item)
                            <option value="{{ $__item->code }}" @if ($__current_local == $__item->code)
                                @selected(true)
                            @endif>{{ $__item->name }}</option>
                        @endforeach
                    </select>
                </div>   
            </div>
            <div class="header-notification-wrapper">
                <button class="notification-icon">
                    <i class="las la-bell"></i>
                </button>
                <div class="notification-wrapper">
                    <div class="notification-header">
                        <h5 class="title">{{ __("Notifications") }}</h5>
                    </div>
                    <ul class="notification-list">
                        @forelse ($notifications as $item)
                            <li>
                                <div class="thumb">
                                    <img src="{{ auth()->user()->userImage ?? asset('public/frontend/images/client/client-3.jpg') }}" alt="user">
                                </div>
                                <div class="content">
                                    <div class="title-area">
                                        <h5 class="title">{{ $item->message ?? "" }}</h5>
                                        <span class="time">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$item->created_at ?? "")->format('h:i A') }}</span>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <strong class="title text--danger">{{ __("Notification Not Found!") }}</strong>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="header-user-wrapper">
                <div class="header-user-thumb">
                    <a href="{{ setRoute('user.profile.index')}}"><img src="{{ auth()->user()->userImage ?? asset('public/frontend/images/client/client-3.jpg') }}"
                    alt="client"></a>
                </div>
            </div>
        </div>
    </div>
</nav>
@push('script')
<script>
    $("select[name=lang_switcher]").change(function(){
    var selected_value = $(this).val();
    var submitForm = `<form action="{{ setRoute('frontend.languages.switch') }}" id="local_submit" method="POST"> @csrf <input type="hidden" name="target" value="${$(this).val()}" ></form>`;
    $("body").append(submitForm);
    $("#local_submit").submit();
    });
</script>
@endpush