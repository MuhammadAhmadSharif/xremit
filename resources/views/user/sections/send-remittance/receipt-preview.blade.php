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
    <div class="row mb-20-none">
        <div class="col-xl-12 col-lg-12 mb-20">
            <div class="custom-card mt-10">
                <div class="dashboard-header-wrapper">
                    <h4 class="title">{{ __("Payment Preview") }}</h4>
                </div>
                <form action="{{ setRoute('user.money.submit') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 pb-20">
                                <div class="preview-list-wrapper">
                                    <div class="summary-item">
                                        <h4 class="title">{{ __("Remittance Summary") }}</h4>
                                    </div>
                                    
                                    <input type="hidden" name="identifier" value="{{ $temporary_data->identifier }}">
                                    <div class="preview-list-item">
                                        <div class="preview-list-left">
                                            <div class="preview-list-user-wrapper">
                                                <div class="preview-list-user-icon">
                                                    <i class="las la-receipt"></i>
                                                </div>
                                                <div class="preview-list-user-content">
                                                    <span>{{ __("Sending Amount") }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="preview-list-right">
                                            <span class="text--success">{{ $temporary_data->data->send_money ?? "" }} {{ $temporary_data->data->sender_currency }}</span>
                                        </div>
                                    </div>
                                    <div class="preview-list-item">
                                        <div class="preview-list-left">
                                            <div class="preview-list-user-wrapper">
                                                <div class="preview-list-user-icon">
                                                    <i class="las la-exchange-alt"></i>
                                                </div>
                                                <div class="preview-list-user-content">
                                                    <span>{{ __("Exchange Rate") }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="preview-list-right">
                                            <span>{{ $temporary_data->data->sender_ex_rate }} {{ $temporary_data->data->sender_currency }} = {{ $temporary_data->data->receiver_ex_rate }} {{ $temporary_data->data->receiver_currency }}</span>
                                        </div>
                                    </div>
                                    <div class="preview-list-item">
                                        <div class="preview-list-left">
                                            <div class="preview-list-user-wrapper">
                                                <div class="preview-list-user-icon">
                                                    <i class="las la-battery-half"></i>
                                                </div>
                                                <div class="preview-list-user-content">
                                                    <span>{{ __("Total Fees & Charges") }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="preview-list-right">
                                            <span class="text--warning">{{ $temporary_data->data->fees ?? "" }} {{ $temporary_data->data->sender_currency }}</span>
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
                                            <span class="text--danger">{{ $temporary_data->data->convert_amount ?? "" }} {{ $temporary_data->data->sender_currency }}</span>
                                        </div>
                                    </div>
                                    <div class="preview-list-item">
                                        <div class="preview-list-left">
                                            <div class="preview-list-user-wrapper">
                                                <div class="preview-list-user-icon">
                                                    <i class="las la-money-check-alt"></i>
                                                </div>
                                                <div class="preview-list-user-content">
                                                    <span>{{ __("Will Get Amount") }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="preview-list-right">
                                            <span class="text--info">{{ $temporary_data->data->receive_money ?? "" }} {{ $temporary_data->data->receiver_currency }}</span>
                                        </div>
                                    </div>
                                    <div class="preview-list-item">
                                        <div class="preview-list-left">
                                            <div class="preview-list-user-wrapper">
                                                <div class="preview-list-user-icon">
                                                    <i class="las la-gifts"></i>
                                                </div>
                                                <div class="preview-list-user-content">
                                                    <span>{{ __("Sending Purpose") }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="preview-list-right">
                                            <span class="text--danger">{{ $temporary_data->data->sending_purpose->name ?? "N/A" }}</span>
                                        </div>
                                    </div>
                                    <div class="preview-list-item">
                                        <div class="preview-list-left">
                                            <div class="preview-list-user-wrapper">
                                                <div class="preview-list-user-icon">
                                                    <i class="las la-universal-access"></i>
                                                </div>
                                                <div class="preview-list-user-content">
                                                    <span>{{ __("Source Of Fund") }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="preview-list-right">
                                            <span class="text--danger">{{ $temporary_data->data->source->name ?? "N/A" }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 pb-20">
                                <div class="preview-list-wrapper">
                                    <div class="summary-item">
                                        <h4 class="title">{{ __("Receipt Summary") }}</h4>
                                    </div>
                                    <div class="preview-list-item">
                                        <div class="preview-list-left">
                                            <div class="preview-list-user-wrapper">
                                                <div class="preview-list-user-icon">
                                                    <i class="las la-user"></i>
                                                </div>
                                                <div class="preview-list-user-content">
                                                    <span>{{ __("Recipient Name") }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="preview-list-right">
                                            <span class="text--success">{{ $temporary_data->data->first_name ?? "N/A" }} {{ $temporary_data->data->middle_name ?? "N/A" }} {{ $temporary_data->data->last_name ?? "N/A" }}</span>
                                        </div>
                                    </div>
                                    <div class="preview-list-item">
                                        <div class="preview-list-left">
                                            <div class="preview-list-user-wrapper">
                                                <div class="preview-list-user-icon">
                                                    <i class="las la-envelope"></i>
                                                </div>
                                                <div class="preview-list-user-content">
                                                    <span>{{ __("Recipient Email") }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="preview-list-right">
                                            <span class="text--success">{{ $temporary_data->data->email ?? "N/A" }} </span>
                                        </div>
                                    </div>
                                    <div class="preview-list-item">
                                        <div class="preview-list-left">
                                            <div class="preview-list-user-wrapper">
                                                <div class="preview-list-user-icon">
                                                    <i class="las la-phone-alt"></i>
                                                </div>
                                                <div class="preview-list-user-content">
                                                    <span>{{ __("Phone") }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="preview-list-right">
                                            <span class="text--info">{{ $temporary_data->data->phone ?? "N/A"  }}</span>
                                        </div>
                                    </div>
                                    <div class="preview-list-item">
                                        <div class="preview-list-left">
                                            <div class="preview-list-user-wrapper">
                                                <div class="preview-list-user-icon">
                                                    <i class="las la-flag"></i>
                                                </div>
                                                <div class="preview-list-user-content">
                                                    <span>{{ __("Country") }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="preview-list-right">
                                            <span>{{ $temporary_data->data->country ?? "N/A" }}</span>
                                        </div>
                                    </div>
                                    <div class="preview-list-item">
                                        <div class="preview-list-left">
                                            <div class="preview-list-user-wrapper">
                                                <div class="preview-list-user-icon">
                                                    <i class="las la-city"></i>
                                                </div>
                                                <div class="preview-list-user-content">
                                                    <span>{{ __("City & State") }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="preview-list-right">
                                            <span class="text--warning">{{ $temporary_data->data->city ?? "N/A" }} </span><span class="ms-1">({{ $temporary_data->data->state ?? "N/A" }})</span>
                                        </div>
                                    </div>
                                    <div class="preview-list-item">
                                        <div class="preview-list-left">
                                            <div class="preview-list-user-wrapper">
                                                <div class="preview-list-user-icon">
                                                    <i class="las la-location-arrow"></i>
                                                </div>
                                                <div class="preview-list-user-content">
                                                    <span>{{ __("Zip Code") }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="preview-list-right">
                                            <span class="text--danger">{{ $temporary_data->data->zip_code ?? "N/A" }}</span>
                                        </div>
                                    </div>
                                    <div class="preview-list-item">
                                        <div class="preview-list-left">
                                            <div class="preview-list-user-wrapper">
                                                <div class="preview-list-user-icon">
                                                    <i class="las la-street-view"></i>
                                                </div>
                                                <div class="preview-list-user-content">
                                                    <span>{{ __("Address") }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="preview-list-right">
                                            <span class="text--warning">{{ $temporary_data->data->address ?? "N/A" }}</span>
                                        </div>
                                    </div>                        
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 pb-20">
                            <div class="preview-list-wrapper">
                                <div class="summary-item">
                                    <h4 class="title">{{ __("Payment Summary") }}</h4>
                                </div>
                                <div class="preview-list-item">
                                    <div class="preview-list-left">
                                        <div class="preview-list-user-wrapper">
                                            <div class="preview-list-user-icon">
                                                <i class="las la-receipt"></i>
                                            </div>
                                            <div class="preview-list-user-content">
                                                <span>{{ __("Transaction Type") }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-list-right">
                                        <span class="text--success">{{ $temporary_data->type ?? "N/A" }}</span>
                                    </div>
                                </div>
                                <div class="preview-list-item">
                                    <div class="preview-list-left">
                                        <div class="preview-list-user-wrapper">
                                            <div class="preview-list-user-icon">
                                                <i class="las la-university"></i>
                                            </div>
                                            <div class="preview-list-user-content">
                                                <span>{{ __("Method Name") }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-list-right">
                                        <span>{{ $temporary_data->data->method_name ?? "" }}</span>
                                    </div>
                                </div>
                                <div class="preview-list-item">
                                    <div class="preview-list-left">
                                        <div class="preview-list-user-wrapper">
                                            <div class="preview-list-user-icon">
                                                <i class="las la-user-alt"></i>
                                            </div>
                                            <div class="preview-list-user-content">
                                                <span>{{ __("Account Number") }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-list-right">
                                        <span class="text--warning">{{ $temporary_data->data->account_number ?? "" }}</span>
                                    </div>
                                </div>
                                <div class="preview-list-item">
                                    <div class="preview-list-left">
                                        <div class="preview-list-user-wrapper">
                                            <div class="preview-list-user-icon">
                                                <i class="las la-comment-dollar"></i>
                                            </div>
                                            <div class="preview-list-user-content">
                                                <span>{{ __("Payment Method") }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-list-right">
                                        <span class="text--info">{{ $temporary_data->data->currency->name ?? "" }}</span>
                                    </div>
                                </div>
                                <div class="preview-list-item">
                                    <div class="preview-list-left">
                                        <div class="preview-list-user-wrapper">
                                            <div class="preview-list-user-icon">
                                                <i class="las la-comment-dollar"></i>
                                            </div>
                                            <div class="preview-list-user-content">
                                                <span>{{ __("Exchange Rate") }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-list-right">
                                        <span class="text--info">{{ $temporary_data->data->sender_ex_rate ?? "1" }} {{ $temporary_data->data->sender_currency }} = {{ $temporary_data->data->rate ?? "" }} {{ $temporary_data->data->currency->code }}</span>
                                    </div>
                                </div>
                                <div class="preview-list-item">
                                    <div class="preview-list-left">
                                        <div class="preview-list-user-wrapper">
                                            <div class="preview-list-user-icon">
                                                <i class="las la-comment-dollar"></i>
                                            </div>
                                            <div class="preview-list-user-content">
                                                <span>{{ __("Payable Amount") }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-list-right">
                                        
                                        <span class="text--info">{{ get_amount($temporary_data->data->payable_amount) }} {{ $temporary_data->data->currency->code }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn--base mt-10 w-100">{{ __("Confirm Payment") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection