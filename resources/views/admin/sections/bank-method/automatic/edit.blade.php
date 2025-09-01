@extends('admin.layouts.master')

@push('css')
<style>
    .highlight {
        background-color: #f1f1f1;
        padding: 2px 10px;
        font-weight: bold;         
    }
</style>
@endpush

@section('page-title')
    @include('admin.components.page-title',['title' => __($page_title)])
@endsection

@section('breadcrumb')
    @include('admin.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("admin.dashboard"),
        ]
    ], 'active' => __($page_title)])
@endsection

@section('content')
    <div class="custom-card mb-2">
        <div class="card-header">
            <h6 class="title">{{ __($page_title) }}</h6>
        </div>
        <div class="card-body">
            <form class="card-form" action="{{ setRoute('admin.bank.method.automatic.update',$bank_method_automatic->slug) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method("PUT")
                <div class="row mb-10-none">
                    <div class="col-xl-12   col-lg-12 form-group">
                        <label>{{ __("Name") }}*</label>
                        <input type="text" readonly class="form--control text-capitalize" name="api_method" value="{{ $bank_method_automatic->config->name }}">

                    </div>
                    <div class="col-xl-12 col-lg-12 form-group configForm" id="flutterwave">
                        <div class="row" >
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 form-group">
                                <label>{{ __("Secret Key") }}*</label>
                                <div class="input-group append">
                                    <span class="input-group-text"><i class="las la-key"></i></span>
                                    <input type="text" class="form--control" name="flutterwave_secret_key" value="{{ @$bank_method_automatic->config->flutterwave_secret_key }}">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 form-group">
                                <label>{{ __("Secret Hash") }}*</label>
                                <div class="input-group append">
                                    <span class="input-group-text"><i class="las la-hashtag"></i></span>
                                    <input type="text" class="form--control" name="flutterwave_secret_hash" value="{{ @$bank_method_automatic->config->flutterwave_secret_hash }}">
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 form-group">
                                <label>{{ __("Base Url") }}*</label>
                                <div class="input-group append">
                                    <span class="input-group-text"><i class="las la-link"></i></span>
                                    <input type="text" class="form--control" name="flutterwave_url" value="{{ @$bank_method_automatic->config->flutterwave_url }}">
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-xl-12 col-lg-12 form-group">
                        @include('admin.components.button.form-btn',[
                            'class'         => "w-100 btn-loading",
                            'text'          => __("Update"),
                            'permission'    => "admin.bank.method.automatic.update"
                        ])
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="custom-card">
        <div class="card-header">
            <h6 class="title">{{ __("Flutterwave supported Countries and Bank Lists") }}</h6>
        </div>
        <div class="card-body">
            <div class="row mb-30-none">
                <!-- First Column -->
                <div class="col-lg-4 mb-30">
                    <div class="bank-list-wrapper">
                        @php
                            $banks = getFlutterwaveBanksForAdmin('NG');
                        @endphp
                        <div class="bank-list-header">
                            <h4 class="title">Nigeria: Total Banks {{ count($banks) }}</h4>
                            <div class="search-area">
                                <input type="hidden" value="{{ json_encode($banks) }}" class="nigeria-banks">
                                <input type="text" class="form--control" placeholder="Search here..." id="search-nigeria">
                                <i class="las la-search"></i>
                            </div>
                        </div>
                        <ul class="bank-list nigeria">
                            
                            @foreach ($banks as $item)
                                <li class="nigeria-bank-list">{{ $item['name'] }}</li>    
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 mb-30">
                    <div class="bank-list-wrapper">
                        @php
                            $banks = getFlutterwaveBanksForAdmin('GH');
                        @endphp
                        <div class="bank-list-header">
                            <h4 class="title">Ghana: Total Banks {{ count($banks) }}</h4>
                            <div class="search-area">
                                <input type="hidden" value="{{ json_encode($banks) }}" class="ghana-banks">
                                <input type="text" class="form--control" placeholder="Search here..." id="search-ghana">
                                <i class="las la-search"></i>
                            </div>
                        </div>
                        <ul class="bank-list ghana">
                            
                            @foreach ($banks as $item)
                                <li class="ghana-bank-list">{{ $item['name'] }}</li>    
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 mb-30">
                    <div class="bank-list-wrapper">
                        @php
                            $banks = getFlutterwaveBanksForAdmin('KE');
                        @endphp
                        <div class="bank-list-header">
                            <h4 class="title">Kenya: Total Banks {{ count($banks) }}</h4>
                            <div class="search-area">
                                <input type="hidden" value="{{ json_encode($banks) }}" class="kenya-banks">
                                <input type="text" class="form--control" placeholder="Search here..." id="search-kenya">
                                <i class="las la-search"></i>
                            </div>
                        </div>
                        <ul class="bank-list kenya">
                            
                            @foreach ($banks as $item)
                                <li class="kenya-bank-list">{{ $item['name'] }}</li>    
                            @endforeach
                        </ul>
                    </div>
                </div>
        
                
            </div>
            <div class="row mb-30-none mt-2">
                <div class="col-lg-4 mb-30">
                    <div class="bank-list-wrapper">
                        @php
                            $banks = getFlutterwaveBanksForAdmin('CM');
                        @endphp
                        <div class="bank-list-header">
                            <h4 class="title">Cameroon: Total Banks {{ count($banks) }}</h4>
                            <div class="search-area">
                                <input type="hidden" value="{{ json_encode($banks) }}" class="cameroon-banks">
                                <input type="text" class="form--control" placeholder="Search here..." id="search-cameroon">
                                <i class="las la-search"></i>
                            </div>
                        </div>
                        <ul class="bank-list cameroon">
                            
                            @foreach ($banks as $item)
                                <li class="cameroon-bank-list">{{ $item['name'] }}</li>    
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 mb-30">
                    <div class="bank-list-wrapper">
                        @php
                            $banks = getFlutterwaveBanksForAdmin('TZ');
                        @endphp
                        <div class="bank-list-header">
                            <h4 class="title">Tanzania: Total Banks {{ count($banks) }}</h4>
                            <div class="search-area">
                                <input type="hidden" value="{{ json_encode($banks) }}" class="tanzania-banks">
                                <input type="text" class="form--control" placeholder="Search here..." id="search-tanzania">
                                <i class="las la-search"></i>
                            </div>
                        </div>
                        <ul class="bank-list tanzania">
                            
                            @foreach ($banks as $item)
                                <li class="tanzania-bank-list">{{ $item['name'] }}</li>    
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 mb-30">
                    <div class="bank-list-wrapper">
                        @php
                            $banks = getFlutterwaveBanksForAdmin('IN');
                        @endphp
                        <div class="bank-list-header">
                            <h4 class="title">India: Total Banks {{ count($banks) }}</h4>
                            <div class="search-area">
                                <input type="hidden" value="{{ json_encode($banks) }}" class="india-banks">
                                <input type="text" class="form--control" placeholder="Search here..." id="search-india">
                                <i class="las la-search"></i>
                            </div>
                        </div>
                        <ul class="bank-list india">
                            
                            @foreach ($banks as $item)
                                <li class="india-bank-list">{{ $item['name'] }}</li>    
                            @endforeach
                        </ul>
                    </div>
                </div>
        
                
            </div>
            <div class="row mb-30-none mt-2">
                <div class="col-lg-4 mb-30">
                    <div class="bank-list-wrapper">
                        @php
                            $banks = getFlutterwaveBanksForAdmin('SL');
                        @endphp
                        <div class="bank-list-header">
                            <h4 class="title">Sierra Leone: Total Banks {{ count($banks) }}</h4>
                            <div class="search-area">
                                <input type="hidden" value="{{ json_encode($banks) }}" class="sierra-leone-banks">
                                <input type="text" class="form--control" placeholder="Search here..." id="search-sierra-leone">
                                <i class="las la-search"></i>
                            </div>
                        </div>
                        <ul class="bank-list sierra-leone">
                            
                            @foreach ($banks as $item)
                                <li class="sierra-leone-bank-list">{{ $item['name'] }}</li>    
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 mb-30">
                    <div class="bank-list-wrapper">
                        @php
                            $banks = getFlutterwaveBanksForAdmin('UG');
                        @endphp
                        <div class="bank-list-header">
                            <h4 class="title">Uganda: Total Banks {{ count($banks) }}</h4>
                            <div class="search-area">
                                <input type="hidden" value="{{ json_encode($banks) }}" class="uganda-banks">
                                <input type="text" class="form--control" placeholder="Search here..." id="search-uganda">
                                <i class="las la-search"></i>
                            </div>
                        </div>
                        <ul class="bank-list uganda">
                            
                            @foreach ($banks as $item)
                                <li class="uganda-bank-list">{{ $item['name'] }}</li>    
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 mb-30">
                    <div class="bank-list-wrapper">
                        @php
                            $banks = getFlutterwaveBanksForAdmin('SN');
                        @endphp
                        <div class="bank-list-header">
                            <h4 class="title">Senegal: Total Banks {{ count($banks) }}</h4>
                            <div class="search-area">
                                <input type="hidden" value="{{ json_encode($banks) }}" class="senegal-banks">
                                <input type="text" class="form--control" placeholder="Search here..." id="search-senegal">
                                <i class="las la-search"></i>
                            </div>
                        </div>
                        <ul class="bank-list senegal">
                            
                            @foreach ($banks as $item)
                                <li class="senegal-bank-list">{{ $item['name'] }}</li>    
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row mb-30-none mt-2">
                <div class="col-lg-4 mb-30">
                    <div class="bank-list-wrapper">
                        @php
                            $banks = getFlutterwaveBanksForAdmin('TD');
                        @endphp
                        <div class="bank-list-header">
                            <h4 class="title">Chad: Total Banks {{ count($banks) }}</h4>
                            <div class="search-area">
                                <input type="hidden" value="{{ json_encode($banks) }}" class="chad-banks">
                                <input type="text" class="form--control" placeholder="Search here..." id="search-chad">
                                <i class="las la-search"></i>
                            </div>
                        </div>
                        <ul class="bank-list chad">
                            
                            @foreach ($banks as $item)
                                <li class="chad-bank-list">{{ $item['name'] }}</li>    
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 mb-30">
                    <div class="bank-list-wrapper">
                        @php
                            $banks = getFlutterwaveBanksForAdmin('GA');
                        @endphp
                        <div class="bank-list-header">
                            <h4 class="title">Gabon: Total Banks {{ count($banks) }}</h4>
                            <div class="search-area">
                                <input type="hidden" value="{{ json_encode($banks) }}" class="gabon-banks">
                                <input type="text" class="form--control" placeholder="Search here..." id="search-gabon">
                                <i class="las la-search"></i>
                            </div>
                        </div>
                        <ul class="bank-list gabon">
                            
                            @foreach ($banks as $item)
                                <li class="gabon-bank-list">{{ $item['name'] }}</li>    
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 mb-30">
                    <div class="bank-list-wrapper">
                        @php
                            $banks = getFlutterwaveBanksForAdmin('BJ');
                        @endphp
                        <div class="bank-list-header">
                            <h4 class="title">Benin: Total Banks {{ count($banks) }}</h4>
                            <div class="search-area">
                                <input type="hidden" value="{{ json_encode($banks) }}" class="benin-banks">
                                <input type="text" class="form--control" placeholder="Search here..." id="search-benin">
                                <i class="las la-search"></i>
                            </div>
                        </div>
                        <ul class="bank-list benin">
                            
                            @foreach ($banks as $item)
                                <li class="benin-bank-list">{{ $item['name'] }}</li>    
                            @endforeach
                        </ul>
                    </div>
                </div>
        
                
            </div>
            <div class="row mb-30-none mt-2">
                <div class="col-lg-4 mb-30">
                    <div class="bank-list-wrapper">
                        @php
                            $banks = getFlutterwaveBanksForAdmin('CI');
                        @endphp
                        <div class="bank-list-header">
                            <h4 class="title">Cote D'Ivoire (Ivory Coast): Total Banks {{ count($banks) }}</h4>
                            <div class="search-area">
                                <input type="hidden" value="{{ json_encode($banks) }}" class="ivory-banks">
                                <input type="text" class="form--control" placeholder="Search here..." id="search-ivory">
                                <i class="las la-search"></i>
                            </div>
                        </div>
                        <ul class="bank-list ivory">
                            
                            @foreach ($banks as $item)
                                <li class="ivory-bank-list">{{ $item['name'] }}</li>    
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 mb-30">
                    <div class="bank-list-wrapper">
                        @php
                            $banks = getFlutterwaveBanksForAdmin('ZA');
                        @endphp
                        <div class="bank-list-header">
                            <h4 class="title">South Africa: Total Banks {{ count($banks) }}</h4>
                            <div class="search-area">
                                <input type="hidden" value="{{ json_encode($banks) }}" class="south-africa-banks">
                                <input type="text" class="form--control" placeholder="Search here..." id="search-south-africa">
                                <i class="las la-search"></i>
                            </div>
                        </div>
                        <ul class="bank-list south-africa">
                            
                            @foreach ($banks as $item)
                                <li class="south-africa-bank-list">{{ $item['name'] }}</li>    
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 mb-30">
                    <div class="bank-list-wrapper">
                        @php
                            $banks = getFlutterwaveBanksForAdmin('ZM');
                        @endphp
                        <div class="bank-list-header">
                            <h4 class="title">Zambia: Total Banks {{ count($banks) }}</h4>
                            <div class="search-area">
                                <input type="hidden" value="{{ json_encode($banks) }}" class="zambia-banks">
                                <input type="text" class="form--control" placeholder="Search here..." id="search-zambia">
                                <i class="las la-search"></i>
                            </div>
                        </div>
                        <ul class="bank-list zambia">
                            
                            @foreach ($banks as $item)
                                <li class="zambia-bank-list">{{ $item['name'] }}</li>    
                            @endforeach
                        </ul>
                    </div>
                </div>
        
                
            </div>
        </div>
        
    </div>
@endsection
@push('script')
<script>
    // search nigeria text
    $('#search-nigeria').keyup(function () { 
        var text    = $(this).val();
        var banks   = $('.nigeria-banks').val();
        var bank_array = JSON.parse(banks);
        if(text == '' || text == null){
            const bankListItems = $('.bank-list li.nigeria-bank-list'); 
            bankListItems.removeClass('highlight'); 
        }else{
            highlightMatchingBanks(bank_array, text);
        }

    });
    //search ghana text
    $('#search-ghana').keyup(function () { 
        var text    = $(this).val();
        var banks   = $('.ghana-banks').val();
        var bank_array = JSON.parse(banks);
        if(text == '' || text == null){
            const bankListItems = $('.bank-list li.ghana-bank-list'); 
            bankListItems.removeClass('highlight'); 
        }else{
            ghanaBank(bank_array, text);
        }

    });
    //search kenya text
    $('#search-kenya').keyup(function () { 
        var text    = $(this).val();
        var banks   = $('.kenya-banks').val();
        var bank_array = JSON.parse(banks);
        if(text == '' || text == null){
            const bankListItems = $('.bank-list li.kenya-bank-list'); 
            bankListItems.removeClass('highlight'); 
        }else{
            kenyaBank(bank_array, text);
        }

    });
    // search cameroon text
    $('#search-cameroon').keyup(function () { 
        var text    = $(this).val();
        var banks   = $('.cameroon-banks').val();
        var bank_array = JSON.parse(banks);
        if(text == '' || text == null){
            const bankListItems = $('.bank-list li.cameroon-bank-list'); 
            bankListItems.removeClass('highlight'); 
        }else{
            cameroonBanks(bank_array, text);
        }

    });
    //search tanzania text
    $('#search-tanzania').keyup(function () { 
        var text    = $(this).val();
        var banks   = $('.tanzania-banks').val();
        var bank_array = JSON.parse(banks);
        if(text == '' || text == null){
            const bankListItems = $('.bank-list li.tanzania-bank-list'); 
            bankListItems.removeClass('highlight'); 
        }else{
            tanzaniaBank(bank_array, text);
        }

    });
    //search india text
    $('#search-india').keyup(function () { 
        var text    = $(this).val();
        var banks   = $('.india-banks').val();
        var bank_array = JSON.parse(banks);
        if(text == '' || text == null){
            const bankListItems = $('li.india-bank-list'); 
            bankListItems.removeClass('highlight'); 
        }else{
            indiaBank(bank_array, text);
        }

    });
    //search sierra-leone text
    $('#search-sierra-leone').keyup(function () { 
        var text    = $(this).val();
        var banks   = $('.sierra-leone-banks').val();
        var bank_array = JSON.parse(banks);
        if(text == '' || text == null){
            const bankListItems = $('li.sierra-leone-bank-list'); 
            bankListItems.removeClass('highlight'); 
        }else{
            sierraLeoneBank(bank_array, text);
        }

    });
    //search uganda text
    $('#search-uganda').keyup(function () { 
        var text    = $(this).val();
        var banks   = $('.uganda-banks').val();
        var bank_array = JSON.parse(banks);
        if(text == '' || text == null){
            const bankListItems = $('li.uganda-bank-list'); 
            bankListItems.removeClass('highlight'); 
        }else{
            ugandaBank(bank_array, text);
        }

    });
    //search senegal text
    $('#search-senegal').keyup(function () { 
        var text    = $(this).val();
        var banks   = $('.senegal-banks').val();
        var bank_array = JSON.parse(banks);
        if(text == '' || text == null){
            const bankListItems = $('li.senegal-bank-list'); 
            bankListItems.removeClass('highlight'); 
        }else{
            senegalBank(bank_array, text);
        }

    });
    //search chad text
    $('#search-chad').keyup(function () { 
        var text    = $(this).val();
        var banks   = $('.chad-banks').val();
        var bank_array = JSON.parse(banks);
        if(text == '' || text == null){
            const bankListItems = $('li.chad-bank-list'); 
            bankListItems.removeClass('highlight'); 
        }else{
            chadBank(bank_array, text);
        }

    });
    //search gabon text
    $('#search-gabon').keyup(function () { 
        var text    = $(this).val();
        var banks   = $('.gabon-banks').val();
        var bank_array = JSON.parse(banks);
        if(text == '' || text == null){
            const bankListItems = $('li.gabon-bank-list'); 
            bankListItems.removeClass('highlight'); 
        }else{
            gabonBank(bank_array, text);
        }

    });
    //search benin text
    $('#search-benin').keyup(function () { 
        var text    = $(this).val();
        var banks   = $('.benin-banks').val();
        var bank_array = JSON.parse(banks);
        if(text == '' || text == null){
            const bankListItems = $('li.benin-bank-list'); 
            bankListItems.removeClass('highlight'); 
        }else{
            beninBank(bank_array, text);
        }

    });
    //search ivory text
    $('#search-ivory').keyup(function () { 
        var text    = $(this).val();
        var banks   = $('.ivory-banks').val();
        var bank_array = JSON.parse(banks);
        if(text == '' || text == null){
            const bankListItems = $('li.ivory-bank-list'); 
            bankListItems.removeClass('highlight'); 
        }else{
            ivoryBank(bank_array, text);
        }

    });
    //search south-africa text
    $('#search-south-africa').keyup(function () { 
        var text    = $(this).val();
        var banks   = $('.south-africa-banks').val();
        var bank_array = JSON.parse(banks);
        if(text == '' || text == null){
            const bankListItems = $('li.south-africa-bank-list'); 
            bankListItems.removeClass('highlight'); 
        }else{
            southAfricaBank(bank_array, text);
        }

    });
    //search zambia text
    $('#search-zambia').keyup(function () { 
        var text    = $(this).val();
        var banks   = $('.zambia-banks').val();
        var bank_array = JSON.parse(banks);
        if(text == '' || text == null){
            const bankListItems = $('li.zambia-bank-list'); 
            bankListItems.removeClass('highlight'); 
        }else{
            zambiaBank(bank_array, text);
        }

    });

    
    //search nigeria bank
    function highlightMatchingBanks(array, text) {
        const lowerTerm = text.toLowerCase();
        const bankListItems = $('.bank-list li.nigeria-bank-list'); 
        bankListItems.removeClass('highlight'); 
        
        bankListItems.each(function() {
            const itemText = $(this).text().toLowerCase();
            if (itemText.includes(lowerTerm)) {
                $(this).addClass('highlight');
                
            }
        });
        setTimeout(() => {
            if ($('.bank-list li.nigeria-bank-list').hasClass('highlight')) {
                $('.bank-list.nigeria').animate({
                    scrollTop: $('.bank-list li.nigeria-bank-list.highlight').offset().top - 600
                }, 600);

            }
        
        }, 200);
    }

    // search ghana banks
    function ghanaBank(array, text) {
        const lowerTerm = text.toLowerCase();
        const bankListItems = $('.bank-list li.ghana-bank-list'); 
        bankListItems.removeClass('highlight'); 

        bankListItems.each(function() {
            const itemText = $(this).text().toLowerCase();
            if (itemText.includes(lowerTerm)) {
                $(this).addClass('highlight');
                
            }
        });
        setTimeout(() => {
            if ($('.bank-list li.ghana-bank-list').hasClass('highlight')) {
                $('.bank-list.ghana').animate({
                    scrollTop: $('.bank-list li.ghana-bank-list.highlight').offset().top - 600
                }, 600);

            }
        
        }, 200);
    }
    // search kenya banks
    function kenyaBank(array, text) {
        const lowerTerm = text.toLowerCase();
        const bankListItems = $('.bank-list li.kenya-bank-list'); 
        bankListItems.removeClass('highlight'); 

        bankListItems.each(function() {
            const itemText = $(this).text().toLowerCase();
            if (itemText.includes(lowerTerm)) {
                $(this).addClass('highlight');
                
            }
        });
        setTimeout(() => {
            if ($('.bank-list li.kenya-bank-list').hasClass('highlight')) {
                $('.bank-list.kenya').animate({
                    scrollTop: $('.bank-list li.kenya-bank-list.highlight').offset().top - 600
                }, 600);

            }
        
        }, 200);
    }
    // search cameroon banks
    function cameroonBanks(array, text) {
        const lowerTerm = text.toLowerCase();
        const bankListItems = $('.bank-list li.cameroon-bank-list'); 
        bankListItems.removeClass('highlight'); 

        bankListItems.each(function() {
            const itemText = $(this).text().toLowerCase();
            if (itemText.includes(lowerTerm)) {
                $(this).addClass('highlight');
                
            }
        });
        setTimeout(() => {
            if ($('.bank-list li.cameroon-bank-list').hasClass('highlight')) {
                $('.bank-list.cameroon').animate({
                    scrollTop: $('.bank-list li.cameroon-bank-list.highlight').offset().top - 600
                }, 600);

            }
        
        }, 200);
    }
    // search tanzania banks
    function tanzaniaBank(array, text) {
        const lowerTerm = text.toLowerCase();
        const bankListItems = $('.bank-list li.tanzania-bank-list'); 
        bankListItems.removeClass('highlight'); 

        bankListItems.each(function() {
            const itemText = $(this).text().toLowerCase();
            if (itemText.includes(lowerTerm)) {
                $(this).addClass('highlight');
                
            }
        });
        setTimeout(() => {
            if ($('.bank-list li.tanzania-bank-list').hasClass('highlight')) {
                $('.bank-list.tanzania').animate({
                    scrollTop: $('.bank-list li.tanzania-bank-list.highlight').offset().top - 600
                }, 600);

            }
        
        }, 200);
    }
    // search india banks
    function indiaBank(array, text) {
        const lowerTerm = text.toLowerCase();
        const bankListItems = $('li.india-bank-list'); 
        bankListItems.removeClass('highlight'); 

        bankListItems.each(function() {
            const itemText = $(this).text().toLowerCase();
            if (itemText.includes(lowerTerm)) {
                $(this).addClass('highlight');
                
            }
        });
        setTimeout(() => {
            if ($('li.india-bank-list').hasClass('highlight')) {
                $('.india').animate({
                    scrollTop: $('li.india-bank-list.highlight').offset().top - 600
                }, 600);

            }
        
        }, 200);
    }
    // search sierra-leone banks
    function sierraLeoneBank(array, text) {
        const lowerTerm = text.toLowerCase();
        const bankListItems = $('li.sierra-leone-bank-list'); 
        bankListItems.removeClass('highlight'); 

        bankListItems.each(function() {
            const itemText = $(this).text().toLowerCase();
            if (itemText.includes(lowerTerm)) {
                $(this).addClass('highlight');
                
            }
        });
        setTimeout(() => {
            if ($('li.sierra-leone-bank-list').hasClass('highlight')) {
                $('.sierra-leone').animate({
                    scrollTop: $('li.sierra-leone-bank-list.highlight').offset().top - 600
                }, 600);

            }
        
        }, 200);
    }
    // search uganda banks
    function ugandaBank(array, text) {
        const lowerTerm = text.toLowerCase();
        const bankListItems = $('li.uganda-bank-list'); 
        bankListItems.removeClass('highlight'); 

        bankListItems.each(function() {
            const itemText = $(this).text().toLowerCase();
            if (itemText.includes(lowerTerm)) {
                $(this).addClass('highlight');
                
            }
        });
        setTimeout(() => {
            if ($('li.uganda-bank-list').hasClass('highlight')) {
                $('.uganda').animate({
                    scrollTop: $('li.uganda-bank-list.highlight').offset().top - 600
                }, 600);

            }
        
        }, 200);
    }
    // search senegal banks
    function senegalBank(array, text) {
        const lowerTerm = text.toLowerCase();
        const bankListItems = $('li.senegal-bank-list'); 
        bankListItems.removeClass('highlight'); 

        bankListItems.each(function() {
            const itemText = $(this).text().toLowerCase();
            if (itemText.includes(lowerTerm)) {
                $(this).addClass('highlight');
                
            }
        });
        setTimeout(() => {
            if ($('li.senegal-bank-list').hasClass('highlight')) {
                $('.senegal').animate({
                    scrollTop: $('li.senegal-bank-list.highlight').offset().top - 600
                }, 600);

            }
        
        }, 200);
    }
    // search chad banks
    function chadBank(array, text) {
        const lowerTerm = text.toLowerCase();
        const bankListItems = $('li.chad-bank-list'); 
        bankListItems.removeClass('highlight'); 

        bankListItems.each(function() {
            const itemText = $(this).text().toLowerCase();
            if (itemText.includes(lowerTerm)) {
                $(this).addClass('highlight');
                
            }
        });
        setTimeout(() => {
            if ($('li.chad-bank-list').hasClass('highlight')) {
                $('.chad').animate({
                    scrollTop: $('li.chad-bank-list.highlight').offset().top - 600
                }, 600);

            }
        
        }, 200);
    }
    // search gabon banks
    function gabonBank(array, text) {
        const lowerTerm = text.toLowerCase();
        const bankListItems = $('li.gabon-bank-list'); 
        bankListItems.removeClass('highlight'); 

        bankListItems.each(function() {
            const itemText = $(this).text().toLowerCase();
            if (itemText.includes(lowerTerm)) {
                $(this).addClass('highlight');
                
            }
        });
        setTimeout(() => {
            if ($('li.gabon-bank-list').hasClass('highlight')) {
                $('.gabon').animate({
                    scrollTop: $('li.gabon-bank-list.highlight').offset().top - 600
                }, 600);

            }
        
        }, 200);
    }
    // search benin banks
    function beninBank(array, text) {
        const lowerTerm = text.toLowerCase();
        const bankListItems = $('li.benin-bank-list'); 
        bankListItems.removeClass('highlight'); 

        bankListItems.each(function() {
            const itemText = $(this).text().toLowerCase();
            if (itemText.includes(lowerTerm)) {
                $(this).addClass('highlight');
                
            }
        });
        setTimeout(() => {
            if ($('li.benin-bank-list').hasClass('highlight')) {
                $('.benin').animate({
                    scrollTop: $('li.benin-bank-list.highlight').offset().top - 600
                }, 600);

            }
        
        }, 200);
    }
    // search ivory banks
    function ivoryBank(array, text) {
        const lowerTerm = text.toLowerCase();
        const bankListItems = $('li.ivory-bank-list'); 
        bankListItems.removeClass('highlight'); 

        bankListItems.each(function() {
            const itemText = $(this).text().toLowerCase();
            if (itemText.includes(lowerTerm)) {
                $(this).addClass('highlight');
                
            }
        });
        setTimeout(() => {
            if ($('li.ivory-bank-list').hasClass('highlight')) {
                $('.ivory').animate({
                    scrollTop: $('li.ivory-bank-list.highlight').offset().top - 600
                }, 600);

            }
        
        }, 200);
    }
    // search south africa banks
    function southAfricaBank(array, text) {
        const lowerTerm = text.toLowerCase();
        const bankListItems = $('li.south-africa-bank-list'); 
        bankListItems.removeClass('highlight'); 

        bankListItems.each(function() {
            const itemText = $(this).text().toLowerCase();
            if (itemText.includes(lowerTerm)) {
                $(this).addClass('highlight');
                
            }
        });
        setTimeout(() => {
            if ($('li.south-africa-bank-list').hasClass('highlight')) {
                $('.south-africa').animate({
                    scrollTop: $('li.south-africa-bank-list.highlight').offset().top - 600
                }, 600);

            }
        
        }, 200);
    }
    // search zambia banks
    function zambiaBank(array, text) {
        const lowerTerm = text.toLowerCase();
        const bankListItems = $('li.zambia-bank-list'); 
        bankListItems.removeClass('highlight'); 

        bankListItems.each(function() {
            const itemText = $(this).text().toLowerCase();
            if (itemText.includes(lowerTerm)) {
                $(this).addClass('highlight');
                
            }
        });
        setTimeout(() => {
            if ($('li.zambia-bank-list').hasClass('highlight')) {
                $('.zambia').animate({
                    scrollTop: $('li.zambia-bank-list.highlight').offset().top - 600
                }, 600);

            }
        
        }, 200);
    }
    
    
</script>
    <script>
        (function ($) {
            "use strict";
            var method = '{{ @$bank_method_automatic->config->name}}';
            if (!method) {
                method = 'flutterwave';
            }

            apiMethod(method);
            $('select[name=api_method]').on('change', function() {
                var method = $(this).val();
                apiMethod(method);
            });

            function apiMethod(method){
                $('.configForm').addClass('d-none');
                if(method != 'other') {
                    $(`#${method}`).removeClass('d-none');
                }
            }

        })(jQuery);

    </script>
    
@endpush
