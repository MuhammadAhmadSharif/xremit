<div class="transaction-results">
    @forelse ($transactions ?? [] as $item)
    <div class="dashboard-list-wrapper">
        <div class="dashboard-list-item-wrapper">
            <div class="dashboard-list-item sent">
                <div class="dashboard-list-left">
                    <div class="dashboard-list-user-wrapper">
                        <div class="dashboard-list-user-icon">
                            <i class="las la-arrow-up"></i>
                        </div>
                        @php
                            $coupon_data = get_coupon_information($item->id) ?? [];
                        @endphp
                        <div class="dashboard-list-user-content">
                            <h4 class="title">{{ $item->remittance_data->first_name ?? '' }} {{ $item->remittance_data->middle_name ?? '' }} {{ $item->remittance_data->last_name ?? ''}}</h4>
                            <span class="sub-title text--danger">{{ __($item->remittance_data->type) ?? '' }}
                                <span class="badge badge--warning ms-2">
                                    @if ($item->status == global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT)
                                        <span>{{ __("Review Payment") }}</span> 
                                    @elseif ($item->status == global_const()::REMITTANCE_STATUS_PENDING)
                                        <span>{{ __("Pending") }}</span>
                                    @elseif ($item->status == global_const()::REMITTANCE_STATUS_CONFIRM_PAYMENT)
                                        <span>{{ __("Confirm Payment") }}</span>
                                    @elseif ($item->status == global_const()::REMITTANCE_STATUS_HOLD)
                                        <span>{{ __("On Hold") }}</span>
                                    @elseif ($item->status == global_const()::REMITTANCE_STATUS_SETTLED)
                                        <span>{{ __("Settled") }}</span>
                                    @elseif ($item->status == global_const()::REMITTANCE_STATUS_COMPLETE)
                                        <span>{{ __("Completed") }}</span>
                                    @elseif ($item->status == global_const()::REMITTANCE_STATUS_CANCEL)
                                        <span>{{ __("Canceled") }}</span>
                                    @elseif ($item->status == global_const()::REMITTANCE_STATUS_FAILED)
                                        <span>{{ __("Failed") }}</span>
                                    @elseif ($item->status == global_const()::REMITTANCE_STATUS_REFUND)
                                        <span>{{ __("Refunded") }}</span>
                                    @else
                                        <span>{{ __("Delayed") }}</span>
                                    @endif
                                </span>
                            </span> 
                        </div>
                    </div>
                </div>
                <div class="dashboard-list-right">
                    <h4 class="main-money text--base">{{ get_amount($item->will_get_amount) ?? '' }} {{ $item->remittance_data->receiver_currency ?? '' }}</h4>
                    <h6 class="exchange-money">{{ get_amount($item->request_amount) ?? '' }} {{ $item->remittance_data->sender_currency ?? '' }}</h6>
                </div>
            </div>
            <div class="preview-list-wrapper">
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="las la-receipt"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>{{ __("MTCN Number") }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span>{{ $item->trx_id ?? 'N/A' }}</span>
                    </div>
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
                        <span>{{ $item->remittance_data->type ?? 'N/A' }}</span>
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
                        <span>{{ $item->remittance_data->method_name ?? 'N/A'}}</span>
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
                        <span>{{ $item->remittance_data->account_number ?? 'N/A'}}</span>
                    </div>
                </div>
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="las la-coins"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>{{ __("Sender Amount") }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span>{{ get_amount($item->request_amount) ?? '' }} {{ $item->remittance_data->sender_currency ?? '' }}</span>
                    </div>
                </div>
                @if (!empty($coupon_data['coupon']) && !empty($coupon_data['price']))
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="las la-coins"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>{{ __("Coupon Name") }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span>{{ $coupon_data['coupon'] }}</span>
                    </div>
                </div>
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="las la-coins"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>{{ __("Bonus") }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span>{{ get_amount($coupon_data['price'],$item->remittance_data->sender_currency)  }}</span>
                    </div>
                </div>
                @endif
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
                        <span>{{ $item->remittance_data->sender_ex_rate ?? '' }} {{ $item->remittance_data->sender_currency ?? ''}} = {{ $item->remittance_data->receiver_ex_rate ?? '' }} {{ $item->remittance_data->receiver_currency ?? '' }}</span>
                    </div>
                </div>
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="las la-battery-half"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>{{ __("Fees & Charges") }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span>{{ get_amount($item->fees ?? '') }} {{ $item->remittance_data->sender_currency ?? '' }}</span>
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
                        <span>{{ $item->remittance_data->sending_purpose ?? '' }}</span>
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
                        <span>{{ $item->remittance_data->source ?? '' }}</span>
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
                        <span>{{ $item->currency->name ?? '' }}</span>
                    </div>
                </div>
                @if ($item->remittance_data->currency->rate ?? false)
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-exchange-alt"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("Payment Method Exchange Rate") }}</span>
                                </div>
                            </div>
                        </div>
                        @php
                            $rate   = $item->remittance_data->currency->rate / ($item->remittance_data->sender_base_rate ?? 1);
                        @endphp
                        <div class="preview-list-right">
                            <span>{{ $item->remittance_data->sender_ex_rate ?? ''}} {{ $item->remittance_data->sender_currency ?? ''}} = {{ get_amount($rate) }} {{ $item->remittance_data->currency->code ?? '' }}</span>
                        </div>
                    </div>
                @endif
                
                <div class="preview-list-item">
                    <div class="preview-list-left">
                        <div class="preview-list-user-wrapper">
                            <div class="preview-list-user-icon">
                                <i class="las la-money-check-alt"></i>
                            </div>
                            <div class="preview-list-user-content">
                                <span>{{ __("Payable Amount") }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="preview-list-right">
                        <span>{{ get_amount($item->payable,$item->remittance_data->currency->code)}}</span>
                    </div>
                </div>
                @if ($item->currency->gateway->isTatum($item->currency->gateway) && $item->status == global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT)
                    <div class="col-12">
                        <form action="{{ setRoute('user.send.remittance.payment.crypto.confirm', $item->trx_id) }}" method="POST">
                            @csrf
                            @php
                                $input_fields = $item->details->payment_info->requirements ?? [];
                            @endphp

                            @foreach ($input_fields as $input)
                                <div class="">
                                    <h4 class="mb-0">{{ $input->label }}</h4>
                                    <input type="text" class="form-control" name="{{ $input->name }}" placeholder="{{ $input->placeholder ?? "" }}">
                                </div>
                            @endforeach

                            <div class="text-end">
                                <button type="submit" class="btn--base my-2">{{ __("Process") }}</button>
                            </div>

                        </form>
                    </div>
                @endif
                <div class="receipt-download" style="text-align: center; padding-top: 20px;">
                    <a href="{{ setRoute('download.pdf',$item->trx_id) }}" class="btn btn--base">{{ __("Download Receipt") }}</a>
                    <input type="hidden" name="" class="box" value="{{ setRoute('share.link',$item->trx_id) }}">
                    <div class="btn btn--base copy">{{ __("Copy Link") }}</div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="alert alert-primary text-center">
        {{ __("No Transaction Found!") }}
    </div>
    @endforelse
</div>