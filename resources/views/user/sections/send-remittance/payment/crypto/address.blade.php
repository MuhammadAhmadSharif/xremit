@extends('user.layouts.master')

@push('css')
    
@endpush
@section('breadcrumb')
    @include('user.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("user.dashboard"),
        ]
    ], 'active' => __("Crypto Address")])
@endsection
@section('content')
<div class="body-wrapper">
    <div class="row mb-20">
        <div class="send-add-form row g-4">
            <div class="col-xxl-8 col-lg-12 col-12 form-area mb-40">
                <div class="add-money-text pb-20">
                    <h4>{{ __("Pay With This Address") }} ({{ $transaction->currency->currency_code }})</h4>
                </div>
                @if ($transaction->status == global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT)
                    <form class="row g-4 submit-form" method="POST" action="{{ setRoute('user.send.remittance.payment.crypto.confirm',$transaction->trx_id) }}">
                        @csrf
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" value="{{ $transaction->details->payment_info->receiver_address ?? "" }}" class="form--control ref-input copiable" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text copytext copy-button">
                                        <i class="la la-copy"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mx-auto mt-4 text-center">
                            <img class="mx-auto" src="{{ $transaction->details->payment_info->receiver_qr_image ?? "" }}" alt="Qr Code">
                        </div>
                        {{-- Print Dynamic Input Filed if Have START --}}
                        @foreach ($transaction->details->payment_info->requirements ?? [] as $input)
                            <div class="form-group col-12">
                                <label for="">{{ $input->label }} </label>
                                <input type="text" name="{{ $input->name }}" placeholder="{{ $input->placeholder ?? "" }}" class="form--control" @if ($input->required)
                                    @required(true)
                                @endif>
                            </div>
                        @endforeach
                        {{-- Print Dynamic Input Filed if Have END --}}
                        <div class="col-12 mt-5">
                            <button type="submit" class="btn--base w-100 text-center">{{ __("Proceed_WEB") }}</button>
                        </div>
                    </form>
                @else
                    <div class="payment-received-alert">
                        <div class="text-center text--success">
                            {{ __("Payment Received Successfully!") }}
                        </div>
                        <div class="txn-hash text-center mt-2 text--info">
                            <strong>{{ __("Txn Hash:") }} </strong>
                            <span>{{ $transaction->details->payment_info->txn_hash ?? "" }}</span>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-xxl-4 col-lg-12 col-12">
                <div class="col-12 preview">
                    <div class="row">
                        <h3>{{ __("Preview_WEB") }}</h3>
                        <div class="py-3">
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h4>{{ __("Request Amount") }}</h4>
                                <h4 class="enter-amount">{{ get_amount($transaction->request_amount, $transaction->remittance_data->sender_currency) }}</h4>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h4>{{ __("Exchange Rate_WEB") }}</h4>
                                <h4 class="exchange-rate">
                                    1 {{ $transaction->remittance_data->sender_currency }} = 
                                    {{ get_amount($transaction->exchange_rate, $transaction->remittance_data->currency->code,8) }}
                                </h4>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h4>{{ __("Fees_WEB") }}</h4>
                                <h4 class="fees">{{ get_amount($transaction->fees, $transaction->remittance_data->sender_currency,4) }}</h4>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h4>{{ __("Total Payable_WEB") }}</h4>
                                <h4 class="payable">{{ get_amount($transaction->payable,  $transaction->remittance_data->currency->code,8) }}</h4>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h4>{{ __("You Will Get_WEB") }}</h4>
                                <h4 class="will-get">{{ get_amount($transaction->will_get_amount, $transaction->remittance_data->receiver_currency,4) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')

@endpush