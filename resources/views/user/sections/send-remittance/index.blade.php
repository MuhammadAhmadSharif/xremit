@extends('user.layouts.master')

@section('breadcrumb')
    @include('user.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("user.dashboard"),
        ]
    ], 'active' => __("Send Remittance")])
@endsection

@section('content')

<div class="body-wrapper">
    <div class="row mb-20">
        <div class="col-xl-7 col-lg-7 mb-20">
            <div class="custom-card mt-10">
                <div class="dashboard-header-wrapper">
                    <h4 class="title">{{__("Send Remittance")}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <form method="POST" action="{{ setRoute('user.send.remittance.store') }}">
                             @csrf
                            <div class="col-lg-12">
                                <div class="banner-form">
                                    <div class="top mb-20">
                                        <p>{{__("Exchange Rate")}}</p>
                                        <h3 class="title exchange_rate">--</h3>
                                        <input type="hidden" name="sender_ex_rate" class="sender-ex-rate">
                                        <input type="hidden" name="sender_base_rate" class="sender-base-rate">
                                        <input type="hidden" name="receiver_ex_rate" class="receiver-ex-rate">
                                    </div>
                                    <div class="col-12 pb-20">
                                        <div class="row">
                                            <h3 class="fs-6">{{__("You send exactly")}}</h3>
                                            <div class="col-12 from-cruncy">
                                                <div class="input-group">
                                                    <input id="send_money" type="text" class="form--control w-100 number-input" name="send_money">
                                                    <div class="ad-select">
                                                        <div class="custom-select">
                                                            <div class="custom-select-inner">
                                                                <input type="hidden" name="sender_currency" class="sender_currency">
                                                                <img src="{{ get_image(@$sender_currency_first->flag,'currency-flag') }}" alt="">
                                                                <span class="custom-currency">{{ @$sender_currency_first->code }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="custom-select-wrapper">
                                                            <div class="custom-select-search-box">
                                                                <div class="custom-select-search-wrapper">
                                                                    <button type="submit" class="search-btn"><i class="las la-search"></i></button>
                                                                    <input type="text" class="form--control custom-select-search" placeholder="{{ __("Search currency") }}...">
                                                                </div>
                                                            </div>
                                                            <div class="custom-select-list-wrapper">
                                                                <ul class="custom-select-list">
                                                                    @foreach ($sender_currency as $item)
                                                                        <li class="custom-option
                                                                        @if($item->code == $sender_currency_first->code)
                                                                        active
                                                                        @endif"
                                                                        data-item='{{ json_encode($item) }}' >
                                                                            <img src="{{ get_image($item->flag,'currency-flag') }}" alt="flag" class="custom-flag">
                                                                            <span class="custom-country">{{ $item->name }}</span>
                                                                            <span class="custom-currency">{{ $item->code }}</span>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="exchange-charge d-flex justify-content-between">
                                        <div class="left-side">
                                        <p><i class="las la-dot-circle"></i> {{ __("Fees & Charges") }}</p>
                                        </div>
                                        <div class="right-side">
                                            <input type="hidden" name="fees" id="charge">
                                            <p id="fees"></p>
                                        </div>
                                    </div>
                                    <div class="exchange-charge d-flex justify-content-between">
                                        <div class="left-side">
                                            <p><i class="las la-dot-circle"></i> {{ __("Amount will convert") }}</p>
                                        </div>
                                        <div class="right-side">
                                            <input type="hidden" name="convert_amount" id="convert--amount">
                                            <p id="convert-amount"></p>
                                        </div>
                                    </div>
                                    <div class="exchange-charge d-flex justify-content-between pb-10">
                                        <div class="left-side">
                                            <p><i class="las la-dot-circle"></i> {{ __("Total Payable Amount") }}</p>
                                        </div>
                                        <div class="right-side">
                                            <input type="hidden" name="payable" id="payable--amount">
                                            <p id="payable"> </p>
                                        </div>
                                    </div>
                                    <div class="exchange-charge d-flex justify-content-between pb-10">
                                        <div class="left-side">
                                            <p class="d-none coupon-text" ><i class="las la-dot-circle"></i> {{ __("Coupon Bonus") }}</p>
                                        </div>
                                        <div class="right-side">
                                            <input type="hidden" id="coupon-price">
                                            <input type="hidden" name="coupon_id" id="coupon-id">
                                            <input type="hidden" name="coupon_type" id="coupon-type">
                                            <p id="coupon--bonus"></p>
                                        </div>
                                    </div>
                                    <div class="exchange-charge d-flex justify-content-between pb-10">
                                        <div class="left-side">
                                            <h4 class="text--base remove-coupon">{{ __("Have a coupon code?") }}</h4>
                                        </div>
                                        <div class="right-side">
                                            <a href="#0" class="btn--base btn apply-button" data-bs-toggle="modal" data-bs-target="#couponcode"> {{ __("Apply") }}</a>
                                            <span class="d-none applied-button">{{ __("Applied") }}</span>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-4 pb-10">
                                        <div class="row">
                                            <h3 class="fs-6">{{__("Recipient gets")}}</h3>
                                            <div class="col-12 from-cruncy">
                                                <div class="input-group">
                                                    <input id="receive_money" type="text" class="form--control w-100 number-input" name="receive_money">

                                                    <div class="ad-select">
                                                        <div class="custom-select">
                                                            <div class="custom-select-inner">
                                                                <input type="hidden" name="receiver_currency" class="receiver_currency ">
                                                                <img src="{{ get_image(@$receiver_currency_first->flag,'currency-flag') }}" alt="">
                                                                <span class="custom-currency">{{ @$receiver_currency_first->code }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="custom-select-wrapper">
                                                            <div class="custom-select-search-box">
                                                                <div class="custom-select-search-wrapper">
                                                                    <button type="submit" class="search-btn"><i class="las la-search"></i></button>
                                                                    <input type="text" class="form--control custom-select-search" placeholder="{{ __("Search currency") }}...">
                                                                </div>
                                                            </div>
                                                            <div class="custom-select-list-wrapper">
                                                                <ul class="custom-select-list">
                                                                    @foreach ($receiver_currency as $item)
                                                                        <li class="custom-option
                                                                            @if($item->code == $receiver_currency_first->code)
                                                                            active
                                                                            @endif" data-item='{{ json_encode($item) }}'>
                                                                            <img src="{{ get_image($item->flag,'currency-flag') }}" alt="flag" class="custom-flag">
                                                                            <span class="custom-country">{{ $item->name }}</span>
                                                                            <span class="custom-currency">{{ $item->code }}</span>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group transaction-type">
                                        <div class="transaction-title">
                                            <label>{{__("Receive Method")}}</label>
                                        </div>
                                        <div class="transaction-type-select">
                                            <select class="nice-select trx-type-select" name="type">
                                                @forelse ($transaction_settings as $item)
                                                    <option class="custom-option" value="{{ $item->title }}" data-item='{{ json_encode($item) }}'>{{ __($item->title ?? '')}}</option>
                                                @empty
                                                    <option>{{ __("No data found") }}</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    @if ($transaction_settings->isNotEmpty())
                                    <div class="form-footer-content mt-10-none mb-20">
                                        <div class="note send-form-footer-note" id="feature-list">
                                            <div class="left-side">
                                                <p><i class="las la-dot-circle"></i></p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif                                    
                                    <div class="col-12">
                                        <button type="submit" class="btn--base btn--base-e text-center w-100 ">{{ __("Send Now") }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-5 col-lg-5 mb-20">
            <div class="custom-card mt-10">
                <div class="dashboard-header-wrapper">
                    <h4 class="title">{{ __("Summary") }}</h4>
                </div>
                <div class="card-body">
                    <div class="preview-list-wrapper">
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-receipt"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{__("Sending Amount")}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span class="text--success" id="sending_amount"></span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-battery-half"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{__("Total Fees & Charges")}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span class="text--warning" id="fees-and-charges"></span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="lab la-get-pocket"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Amount We Will Convert") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span class="text--danger" id="convert_amount"></span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-money-check-alt"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span><b>{{__("Will Get Amount")}}</b></span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span class="text--info"><b id="get-amount"></b></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="couponcode" tabindex="-1" aria-labelledby="couponcode" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ __("Coupon") }}</h4>
                <button type="button" class="close close-btn"  data-bs-dismiss="modal" aria-label="Close"><i class="las la-times"></i></button>
            </div>
            <div class="modal-body">
                <form class="row g-4" onsubmit="applyCoupon(event)">
                    <div class="col-12">
                        <div class="form-group">
                            <label>{{ __("Enter Your Coupon Code") }}<span></span></label>
                            <input type="text" name="coupon" class="form--control" id="coupon" placeholder="Enter Coupon">
                            <div class="modal-footer">
                                <button type="submit" class="btn btn--base w-100">{{ __("Apply") }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

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
    $(document).ready(function(){
        $("#send_money").val(100);
        var selectedType = JSON.parse($('.trx-type-select').find(':selected').attr('data-item'));
        $("#feature-list").html(selectedType.feature_text);
        run(JSON.parse(adSelectActiveItem("input[name=sender_currency]")),JSON.parse(adSelectActiveItem("input[name=receiver_currency]")));
    });
    $('.trx-type-select').on('change',function(){
        var selectedItem = JSON.parse(adSelectActiveItem("input[name=sender_currency]"));
        var receiver = JSON.parse(adSelectActiveItem("input[name=receiver_currency]"))
        run(selectedItem,receiver);
    });
    
    function applyCoupon(event){

        event.preventDefault();
        var coupon  = $('#coupon').val();
        var url         = '{{ setRoute("coupon.apply") }}';
        $.post(url,{coupon:coupon,_token:"{{ csrf_token() }}"},function(response){
            var couponId    = response.data.coupon_info.coupon.id;
            var couponPrice = parseFloat(response.data.coupon_info.coupon.price);
            var couponType  = response.data.coupon_info.coupon_type;


            $('#coupon-id').val(couponId);
            $('#coupon-type').val(couponType);

            var selectedType = JSON.parse($('.trx-type-select').find(':selected').attr('data-item'));
            sender(JSON.parse(adSelectActiveItem("input[name=sender_currency]")),JSON.parse(adSelectActiveItem("input[name=receiver_currency]")),couponPrice);

            var successText = response.message.success;
            throwMessage("success",successText);

            $(document).on("click",".custom-option",function() {
                run(JSON.parse(adSelectActiveItem("input[name=sender_currency]")),JSON.parse(adSelectActiveItem("input[name=receiver_currency]")));
                sender(JSON.parse(adSelectActiveItem("input[name=sender_currency]")),JSON.parse(adSelectActiveItem("input[name=receiver_currency]")),couponPrice);
            });


        }).fail(function(response){
            var errorText = response.responseJSON;
            $('#coupon').val('');
            $('#couponcode').modal('hide');
            throwMessage("error",errorText.message.error);
        });
    }
    function sender(selectedItem,receiver = false,couponPrice){
        couponPrice = parseFloat(couponPrice.toFixed(2));

        function acceptVar() {
            var senderCurrency          = selectedItem.code;
            var senderCurrencyRate      = selectedItem.rate;
            var receiverCurrencyRate    = receiver.rate;
            return {
                senderCurrency:senderCurrency,
                senderCurrencyRate:senderCurrencyRate,
                receiverCurrencyRate:receiverCurrencyRate
            };
        }
        var senderCurrencyRate      = acceptVar().senderCurrencyRate;
        var receiverCurrencyRate    = acceptVar().receiverCurrencyRate;
        var senderCurrency          = acceptVar().senderCurrency;
        var receiverRate            = parseFloat(receiverCurrencyRate) / parseFloat(senderCurrencyRate);
        var bonus                   = parseFloat(couponPrice) * parseFloat(receiverRate);
        bonus                       = parseFloat(bonus.toFixed(2));
        $('#coupon-price').val(bonus);
        var enterMoney              = $('#send_money').val();
        var receiveMoney            = parseFloat(enterMoney) * parseFloat(receiverRate)
        var totalReceiveMoney       = parseFloat(receiveMoney) + parseFloat(bonus);
        $('#receive_money').val(parseFloat(totalReceiveMoney.toFixed(2)));

        $('.apply-button').addClass("d-none");
        $('.applied-button').removeClass("d-none");
        $('.coupon-text').removeClass("d-none");
        $('#coupon--bonus').removeClass("d-none");
        $('#couponcode').modal('hide');
        $('#coupon').val('');

        $('#coupon--bonus').text(couponPrice + " " + senderCurrency);
        var removeCoupon    = `
        <span class="remove-coupon-code" onclick="remove(event)"><i class="las la-times"></i>Remove Coupon</span>
        `;
        $('.remove-coupon').html(removeCoupon);


        $('#couponcode').modal('hide');
    }
    function remove(event){
        event.preventDefault();

        $('.apply-button').removeClass("d-none");
        $('.applied-button').addClass("d-none");

        var CouponText    = `
        <h4 class="text--base remove-coupon">{{ __("Have a coupon code?") }}</h4>
                `;
        $('.remove-coupon').html(CouponText);
        couponId  = 0;
        couponType  = '';
        $('#coupon-id').val(couponId);
        $('#coupon-type').val(couponType);
        $('#coupon--bonus').addClass("d-none");
        $('.coupon-text').addClass("d-none");
        var couponPrice = $('#coupon--bonus').text();

        var selectedType = JSON.parse($('.trx-type-select').find(':selected').attr('data-item'));
        removeAmount(JSON.parse(adSelectActiveItem("input[name=sender_currency]")),JSON.parse(adSelectActiveItem("input[name=receiver_currency]")));
        function removeAmount(selectedItem,receiver = false){
            function acceptVar() {

                var senderCurrencyRate      = selectedItem.rate;
                var receiverCurrencyRate    = receiver.rate;
                return {

                    senderCurrencyRate:senderCurrencyRate,
                    receiverCurrencyRate:receiverCurrencyRate
                };
            }
            var senderCurrencyRate      = acceptVar().senderCurrencyRate;
            var receiverCurrencyRate    = acceptVar().receiverCurrencyRate;
            var senderCurrency          = acceptVar().senderCurrency;
            var receiverRate            = parseFloat(receiverCurrencyRate) / parseFloat(senderCurrencyRate);
            var bonus                   = parseFloat(couponPrice) * parseFloat(receiverRate);
            $('#coupon-price').val(bonus);
            var recieveMoney            = $('#receive_money').val();
            var totalReceiveMoney       = parseFloat(recieveMoney) - parseFloat(bonus);
            $('#receive_money').val(totalReceiveMoney);
        }

    };


    function run(selectedItem,receiver){
        var selectedType = JSON.parse($('.trx-type-select').find(':selected').attr('data-item'));
        var enterAmount = $('#send_money').val();
        $("#feature-list").html(selectedType.feature_text);

        var selectedCurrencies      = acceptVar(selectedItem,receiver);

        var senderCurrencyRate      = selectedCurrencies.senderCurrencyRate;
        var senderCurrency          = selectedCurrencies.senderCurrency;
        var receiverCurrency        = selectedCurrencies.receiverCurrency;
        var receiverCurrencyRate    = selectedCurrencies.receiverCurrencyRate;
        
        function getCharges(selectedType,enterAmount,senderCurrencyRate,senderCurrency,receiverCurrency,receiverCurrencyRate){
            
            var senderRate              = senderCurrencyRate / senderCurrencyRate;
            var recieverRate            = receiverCurrencyRate / senderCurrencyRate;
            let findPercentCharge       = (enterAmount / senderCurrencyRate) / 100;
            let fixedCharge             = selectedType.fixed_charge;
            let percentCharge           = selectedType.percent_charge;
            let totalPercentCharge      = parseFloat(findPercentCharge) * parseFloat(percentCharge);
            let totalCharge   = parseFloat(fixedCharge) + parseFloat(totalPercentCharge);
            var totalChargeAmount  = totalCharge * senderCurrencyRate;

            let payableAmount = parseFloat(enterAmount) + parseFloat(totalChargeAmount);
            if(enterAmount == "") enterAmount = 0;
            if (enterAmount != 0) {
                let convertAmount = enterAmount;
                let receivedMoney = convertAmount * parseFloat(recieverRate).toFixed(2);
                var intervals = selectedType.intervals;

                $.each(intervals,function(index,item){
                    if(parseFloat(enterAmount) >= item.min_limit  && parseFloat(enterAmount) <= item.max_limit) {
                        fixedCharge = item.fixed;
                        percentCharge = item.percent;
                        totalPercentCharge = parseFloat(findPercentCharge) * parseFloat(percentCharge);
                        totalCharge = parseFloat(fixedCharge) + parseFloat(totalPercentCharge);
                        totalChargeAmount  = totalCharge * senderCurrencyRate;
                        convertAmount = parseFloat(enterAmount);
                        recieverRate  = receiverCurrencyRate / senderCurrencyRate;
                        payableAmount = parseFloat(enterAmount) + totalChargeAmount;
                        receivedMoney = convertAmount * recieverRate;

                    }
                });

                $("#fees").text('+' + parseFloat(totalChargeAmount ).toFixed(2) + " " + senderCurrency);
                $("#convert-amount").text(parseFloat(convertAmount) + " " + senderCurrency);
                $('#payable').text(parseFloat(payableAmount).toFixed(2) + " " + senderCurrency);
                $("#convert_amount").text(parseFloat(convertAmount) + " " + senderCurrency);
                $('#sending_amount').text(enterAmount + " " + senderCurrency);
                $('#fees-and-charges').text(parseFloat(totalChargeAmount ).toFixed(2) + " " + senderCurrency);
                $('#get-amount').text(parseFloat(receivedMoney).toFixed(2) + " " + receiverCurrency);
                $('.exchange_rate').text(parseFloat(senderRate).toFixed(2) + " " + senderCurrency + " = " + parseFloat(recieverRate).toFixed(2) + " " + receiverCurrency);
                var coupon      = $('#coupon-id').val();
                if(coupon  != 0){

                    var couponPrice     = $('#coupon-price').val();
                    receivedMoney   = parseFloat(receivedMoney) + parseFloat(couponPrice);
                }else{
                    receivedMoney = receivedMoney;
                }

                $('#receive_money').val(parseFloat(receivedMoney).toFixed(2));
                $('#charge').val(parseFloat(totalChargeAmount).toFixed(2));
                $('#convert--amount').val(parseFloat(convertAmount).toFixed(2));
                $('#payable--amount').val(parseFloat(payableAmount).toFixed(2));
                $('.sender-ex-rate').val(parseFloat(senderRate).toFixed(2));
                $('.sender-base-rate').val(parseFloat(senderCurrencyRate).toFixed(2));
                $('.receiver-ex-rate').val(parseFloat(recieverRate).toFixed(2));
                $('.sender_currency').val(senderCurrency);
                $('.receiver_currency').val(receiverCurrency);

            }else{
                $("#fees").text('');
                $('#convert-amount').text('');
                $('#payable').text('');
                $('#receive_money').val('');
                $('#sending_amount').text('');
                $('#fees-and-charges').text('');
                $('#convert_amount').text('');
                $('#get-amount').text('');
            }
        }
        getCharges(selectedType,enterAmount,senderCurrencyRate,senderCurrency,receiverCurrency,receiverCurrencyRate);
    }

    function acceptVar(selectedItem,receiver) {
        var senderCurrency          = selectedItem.code;
        var senderCurrencyRate      = selectedItem.rate;
        var receiverCurrency        = receiver.code;
        var receiverCurrencyRate    = receiver.rate;
        return {
            senderCurrency:senderCurrency,
            senderCurrencyRate:senderCurrencyRate,
            receiverCurrency:receiverCurrency,
            receiverCurrencyRate:receiverCurrencyRate
        }
    }
    function runReverse(selectedItem,receiver){
        var selectedType = JSON.parse($('.trx-type-select').find(':selected').attr('data-item'));
        var receiveAmount = $('#receive_money').val();

        $("#feature-list").html(selectedType.feature_text);
        var selectedCurrencies      = acceptVar(selectedItem,receiver);
        var senderCurrencyRate      = selectedCurrencies.senderCurrencyRate;
        var senderCurrency          = selectedCurrencies.senderCurrency;
        var receiverCurrency        = selectedCurrencies.receiverCurrency;
        var receiverCurrencyRate    = selectedCurrencies.receiverCurrencyRate;

        
        function getReverseCharges(selectedType,receiveAmount,senderCurrencyRate,senderCurrency,receiverCurrency,receiverCurrencyRate){
            
            var senderRate              = senderCurrencyRate / senderCurrencyRate;
            var recieverRate            = receiverCurrencyRate / senderCurrencyRate;
            let findPercentCharge       = (receiveAmount / receiverCurrencyRate) / 100;

            let fixedCharge             = selectedType.fixed_charge;
            let percentCharge           = selectedType.percent_charge;
            var enterAmount            = receiveAmount / recieverRate;

            let totalPercentCharge = parseFloat(findPercentCharge) * parseFloat(percentCharge);

            let totalCharge   = parseFloat(fixedCharge) + parseFloat(totalPercentCharge);
            var totalChargeAmount  = totalCharge * senderCurrencyRate;
            let payableAmount = enterAmount + totalChargeAmount;

            if(enterAmount == "") enterAmount = 0;
            if (enterAmount != 0) {
                let convertAmount = enterAmount;
                let receivedMoney = convertAmount ;
                var intervals = selectedType.intervals;
                
                $.each(intervals,function(index,item){
                    if(parseFloat(enterAmount) >= item.min_limit  && parseFloat(enterAmount) <= item.max_limit) {
                        fixedCharge = item.fixed;
                        percentCharge = item.percent;
                        totalPercentCharge  = parseFloat(findPercentCharge) * parseFloat(percentCharge);
                        totalCharge         = parseFloat(fixedCharge) + parseFloat(totalPercentCharge);
                        totalChargeAmount   = totalCharge * senderCurrencyRate;
                        convertAmount       = parseFloat(enterAmount);
                        payableAmount       = parseFloat(enterAmount) + totalChargeAmount;
                        recieverRate        = receiverCurrencyRate / senderCurrencyRate;
                        receivedMoney       = convertAmount * recieverRate;
                    }
                });

                $('#sending_amount').text(parseFloat(enterAmount).toFixed(2) + " " + senderCurrency);
                $("#fees").text('+' + parseFloat(totalChargeAmount).toFixed(2) + " " + senderCurrency);
                $("#convert-amount").text(parseFloat(enterAmount).toFixed(2) + " " + senderCurrency);
                $('#payable').text(parseFloat(payableAmount).toFixed(2) + " " + senderCurrency);
                $('#charge').val(parseFloat(totalChargeAmount).toFixed(2));
                $('#convert--amount').val(parseFloat(enterAmount).toFixed(2));
                $('#payable--amount').val(parseFloat(payableAmount).toFixed(2));

                var coupon      = $('#coupon-id').val();
                if(coupon  != 0){

                    var couponPrice     = $('#coupon-price').val();
                    enterAmount = enterAmount - (couponPrice / recieverRate);
                }else{
                    enterAmount = enterAmount;
                }

                $('#send_money').val(parseFloat(enterAmount).toFixed(2));
                $('#fees-and-charges').text(totalChargeAmount.toFixed(2) + " " + senderCurrency);
                $('#convert_amount').text(parseFloat(enterAmount).toFixed(2) + " " + senderCurrency);
                $('#get-amount').text(receivedMoney.toFixed(2) + " " + receiverCurrency);
                $('.exchange_rate').text(parseFloat(senderRate).toFixed(2) + " " + senderCurrency + " = " + parseFloat(recieverRate).toFixed(2) + " " + receiverCurrency);


                $('.sender-ex-rate').val(parseFloat(senderRate).toFixed(2));
                $('.sender-base-rate').val(parseFloat(senderCurrencyRate).toFixed(2));
                $('.receiver-ex-rate').val(parseFloat(recieverRate).toFixed(2));
            }else{
                $('#send_money').val('');
                $("#fees").text('');
                $('#convert-amount').text('');
                $('#payable').text('');
                $('#receive_money').val('');
                $('#sending_amount').text('');
                $('#fees-and-charges').text('');
                $('#convert_amount').text('');
                $('#get-amount').text('');
            }
        }
        getReverseCharges(selectedType,receiveAmount,senderCurrencyRate,senderCurrency,receiverCurrency,receiverCurrencyRate);
    }
    $("#send_money").keyup(function(){
        run(JSON.parse(adSelectActiveItem("input[name=sender_currency]")),JSON.parse(adSelectActiveItem("input[name=receiver_currency]")));
    });
    $("#receive_money").keyup(function(){
        runReverse(JSON.parse(adSelectActiveItem("input[name=sender_currency]")),JSON.parse(adSelectActiveItem("input[name=receiver_currency]")));
    });

    function adSelectActiveItem(input) {
        var adSelect        = $(input).parents(".ad-select");
        var selectedItem    = adSelect.find(".custom-option.active");

        if(selectedItem.length > 0) {
            return selectedItem.attr("data-item");
        }
        return false;
    }

    $(document).on("click",".custom-option",function() {
        run(JSON.parse(adSelectActiveItem("input[name=sender_currency]")),JSON.parse(adSelectActiveItem("input[name=receiver_currency]")));
    });

</script>
@endpush



