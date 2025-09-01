@php
    $app_local    = get_default_language_code();
@endphp
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Countries
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="countries ptb-80 overflow-hidden">
<div class="container-c mx-auto">
    <div class="text-content">
        <h3>{{ @$country_section->value->language->$app_local->title ?? '' }} </h3>
    </div>
    <div class="row d-flex justify-content-center mt-30">
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-8 form-group">
            <h3 class="title text-center">{{ __("Select Country") }}</h3>
            <div class="ad-select" id="ad-select">
                <div class="custom-select-area pt-3">
                    <div class="custom-select">
                        <div class="custom-select-inner">
                            <input type="hidden" name="selected-country" class="selected-country" value="{{ old('selected-country') }}">
                            <img src="{{ get_image($default_currency->flag ,'currency-flag') }}" alt="flag" class="custom-flag">
                            <span class="custom-country">{{ $default_currency->country ?? '' }}</span>
                        </div>
                    </div>
                    <div class="custom-select-wrapper">
                        <div class="custom-select-search-box">
                            <div class="custom-select-search-wrapper">
                                <button type="submit" class="search-btn"><i class="las la-search"></i></button>
                                <input type="text" class="form--control custom-select-search" placeholder="{{ __("Search Here") }}...">
                            </div>
                        </div>
                        <div class="custom-select-list-wrapper">
                            <ul class="custom-select-list">
                                @foreach ($sender_currency as $item)
                                    <li class="custom-option @if($item->country == $default_currency->country) active @endif" data-item='{{ json_encode($item) }}'>
                                        <img src="{{ get_image($item->flag, 'currency-flag') }}" alt="flag" class="custom-flag">
                                        <span class="custom-country">{{ $item->country ?? '' }}</span>
                                        <span class="custom-currency">{{ $item->code ?? '' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-5 pt-40">
        <div class="col-lg-6 col-12">
            <div class="c-top">
                <h3>{{ __("Sending Country") }}</h3>
            </div>
            <div class="row g-4 country-list sender-country">
                
            </div>
        </div>

        <div class="col-lg-6 col-12">
            <div class="c-top">
                <h3>{{ __("Receiving Country") }}</h3>
            </div>
            <div class="row g-4 country-list receiver-country">
                
            </div>
        </div>
    </div>
</div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
End Countries
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
@php
$senderCurrency     = json_encode($sender_currency);
$receiverCurrency   = json_encode($receiver_currency);
@endphp
@push('script')
<script>
    $(".ad-select .custom-select-search").keyup(function(){
        var searchText = $(this).val().toLowerCase();
        var itemList =  $(this).parents(".ad-select").find(".custom-option");
        $.each(itemList,function(index,item){
            var text = $(item).find(".custom-currency").text().toLowerCase();
            var country = $(item).find(".custom-country").text().toLowerCase();
            var match = text.match(searchText);
            var countryMatch = country.match(searchText);
            if(match == null && countryMatch == null) {
                $(item).addClass("d-none");
            }else {
                $(item).removeClass("d-none");
            }
        });
    });
</script>
<script>
    function adSelectedActiveItem(input) {
        
        var adSelect        = $(input).parents(".ad-select");
        var selectedItem    = adSelect.find(".custom-option.active");
        
        if(selectedItem.length > 0) {
            return selectedItem.attr("data-item");
        }
        return false;
    }
    $(document).ready(function(){
        var defaultCountry     = $('.selected-country');
        
        country(JSON.parse(adSelectedActiveItem(defaultCountry)));
    });
    $(document).on("click",".custom-option",function() {
        country(JSON.parse(adSelectedActiveItem($(this))));
    });
    function country(input){
        
        var selectedCode        = input.code;
        var selectedRate        = parseFloat(input.rate);
        var selectedCountry     = input.country;
        var exactRate           = parseFloat(selectedRate) / parseFloat(selectedRate);
       
        var senderCurrency      = {!! json_encode($senderCurrency, JSON_HEX_TAG) !!};
        var senderCurrency      = JSON.parse(senderCurrency);

        var receiverCurrency    = {!! json_encode($receiverCurrency, JSON_HEX_TAG) !!};
        var receiverCurrency    = JSON.parse(receiverCurrency);
        var asset               = "{{ files_asset_path('currency-flag') }}";
        var senderHTML          = '';
        if (senderCurrency.length > 0) {
            $('.sender-country').empty();
            $.each(senderCurrency,function(index,item){
                var receiverRate    = parseFloat(item.rate) / parseFloat(selectedRate);
                senderHTML += `
                <div class="col-lg-6 col-xl-4 col-md-6 col-12">
                    <div class="card">
                        <div class="d-flex">
                            <div class="thumb">
                                <img src="${asset}/${item.flag}" alt="icon">
                            </div>
                            <div>
                                <h3>${item.country}</h3>
                                <p>${exactRate} ${selectedCode} = ${parseFloat(receiverRate.toFixed(2))} ${item.code}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                `; 
            });
            $('.sender-country').append(senderHTML);
        } 
        var receiverHTML  = '';
        if (receiverCurrency.length > 0) {
            $('.receiver-country').empty();
            $.each(receiverCurrency,function(index,item){
                var receiverRate    = parseFloat(item.rate) / parseFloat(selectedRate);
                receiverHTML += `
                <div class="col-lg-6 col-xl-4 col-md-6 col-12">
                    <div class="card">
                        <div class="d-flex">
                            <div class="thumb">
                                <img src="${asset}/${item.flag}" alt="icon">
                            </div>
                            <div>
                                <h3>${item.country}</h3>
                                <p>${exactRate} ${selectedCode} = ${parseFloat(receiverRate.toFixed(2))} ${item.code}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                `; 
            });
            $('.receiver-country').append(receiverHTML);
        } 
        
    }
</script>
@endpush
