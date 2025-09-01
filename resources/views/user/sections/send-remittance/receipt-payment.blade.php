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
    <div class="row mb-50">
        <div class="col-xl-12 col-lg-12 mb-20">
            <div class="custom-card mt-10">
                <div class="dashboard-header-wrapper">
                    <h4 class="title">{{ __("Recipient Payment") }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ setRoute('user.send.remittance.receipant.payment.store',$temporary_data->identifier) }}" class="card-form" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 form-group">
                                <label>{{ __("Sending Purpose") }}<span>*</span></label>
                                <select class="form--control select2-basic" name="sending_purpose">
                                    <option selected disabled>{{ __("Select Purpose") }}</option>
                                    @foreach ($sending_purposes as $item)
                                        
                                        <option value="{{ $item->id }}">{{ $item->name ?? '' }}</option>
                                        
                                    @endforeach
                                    
                                </select>
                            </div>
                            <div class="col-xl-6 col-lg-6 form-group">
                                <label>{{ __("Source Of Fund") }} <span>*</span></label>
                                <select class="form--control select2-basic" name="source">
                                    <option selected disabled>{{ __("Select Source") }}</option>
                                    @foreach ($source_of_funds as $item)
                                        <option value="{{ $item->id }}">{{ $item->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-12 col-lg-12 form-group">
                                <label>{{ __("Remarks") }}<span>( {{ __("Optional") }} )</span></label>
                                <textarea class="form--control" name="remark" placeholder="{{ __("Write Here") }}..."></textarea>
                            </div>
                            <div class="col-xl-12 col-lg-12 form-group">
                                <label>{{ __("Payment Gateway") }} <span>*</span></label>
                                <select class="form--control select2-basic" name="payment_gateway">
                                    <option selected disabled>{{ __("Select Gateway") }}</option>
                                    @foreach ($payment_gateway as $item)
                                        
                                    <option 
                                            value="{{ $item->id  }}"
                                            data-currency="{{ $item->currency_code }}"
                                            data-min_amount="{{ $item->min_limit }}"
                                            data-max_amount="{{ $item->max_limit }}"
                                            data-percent_charge="{{ $item->percent_charge }}"
                                            data-fixed_charge="{{ $item->fixed_charge }}"
                                            data-rate="{{ $item->rate }}"
                                    >{{ $item->name ?? '' }} @if ($item->gateway->isManual())
                                        (Manual)
                                    @endif</option>
                                    @endforeach
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12">
                            <button type="submit" class="btn--base w-100">{{ __("Confirm") }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection