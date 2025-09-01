@extends('admin.layouts.master')

@push('css')

    <style>
        .fileholder {
            min-height: 374px !important;
        }

        .fileholder-files-view-wrp.accept-single-file .fileholder-single-file-view,.fileholder-files-view-wrp.fileholder-perview-single .fileholder-single-file-view{
            height: 330px !important;
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
        ],
        
    ], 'active' => __("Statements")])
@endsection
@section('content')

<div class="row mb-20-none">
    <div class="col-xl-12 col-lg-12 mb-20">
        <div class="custom-card statement-page mt-10">
            <div class="dashboard-header-wrapper">
                <h4 class="title">{{ __("Account Statement") }}</h4>
            </div>
            <div class="card-body">
                <form class="card-form" method="GET" action="{{ setRoute('admin.statements.filter') }}">
                    {{-- @csrf --}}
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-6 form-group">
                            <label>{{ __("Filter by Period") }}</label>
                            @php
                                $time_period   = request()->get('time_period');
                            @endphp
                            <select class="form--control select2-basic" name="time_period" >
                                <option selected disabled>{{ __("Select One") }}</option>
                                <option value="{{ global_const()::LAST_ONE_WEEKS }}" @if ($time_period == global_const()::LAST_ONE_WEEKS) selected @endif>{{ __(remove_special_char(global_const()::LAST_ONE_WEEKS , " "))  }}</option>
                                <option value="{{ global_const()::LAST_TWO_WEEKS }}" @if ($time_period == global_const()::LAST_TWO_WEEKS) selected @endif>{{ __(remove_special_char(global_const()::LAST_TWO_WEEKS , " ")) }}</option>
                                <option value="{{ global_const()::LAST_ONE_MONTHS }}" @if ($time_period == global_const()::LAST_ONE_MONTHS) selected @endif>{{ __(remove_special_char(global_const()::LAST_ONE_MONTHS , " ")) }}</option>
                                <option value="{{ global_const()::LAST_TWO_MONTHS }}" @if ($time_period == global_const()::LAST_TWO_MONTHS) selected @endif>{{ __(remove_special_char(global_const()::LAST_TWO_MONTHS , " ")) }}</option>
                                <option value="{{ global_const()::LAST_THREE_MONTHS }}" @if ($time_period == global_const()::LAST_THREE_MONTHS) selected @endif>{{ __(remove_special_char(global_const()::LAST_THREE_MONTHS , " ")) }}</option>
                                <option value="{{ global_const()::LAST_SIX_MONTHS }}" @if ($time_period == global_const()::LAST_SIX_MONTHS) selected @endif>{{ __(remove_special_char(global_const()::LAST_SIX_MONTHS , " ")) }}</option>
                                <option value="{{ global_const()::LAST_ONE_YEARS }}" @if ($time_period == global_const()::LAST_ONE_YEARS) selected @endif>{{ __(remove_special_char(global_const()::LAST_ONE_YEARS , " ")) }}</option>
                                <option value="{{ global_const()::SPECIFIC_DATES }}" @if ($time_period == global_const()::SPECIFIC_DATES) selected @endif>{{ __(remove_special_char(global_const()::SPECIFIC_DATES , " ")) }}</option>
                            </select>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 form-group">
                            <label>{{ __("Start Date") }}</label>
                            <input type="date" class="form--control" name="start_date" value="{{ old('start_date') }}">
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 form-group">
                            <label>{{ __("End Date") }}</label>
                            <input type="date" class="form--control" name="end_date" value="{{ old('end_date') }}">
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 form-group">
                            <label>{{ __("Status") }}</label>
                            @php
                                $status   = request()->get('status');
                            @endphp
                            <select class="form--control select2-basic" name="status">
                                <option selected disabled>{{ __("Select Status") }}</option>
                                <option value="{{ global_const()::REMITTANCE_STATUS_ALL }}" @if ($status == global_const()::REMITTANCE_STATUS_ALL) selected @endif>{{ __(global_const()::REMITTANCE_STATUS_ALL) }}</option>
                                <option value="{{ global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT }}" @if ($status == global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT) selected @endif>{{ __("Review Payment") }}</option>
                                <option value="{{ global_const()::REMITTANCE_STATUS_PENDING }}" @if ($status == global_const()::REMITTANCE_STATUS_PENDING) selected @endif>{{ __("Pending") }}</option>
                                <option value="{{ global_const()::REMITTANCE_STATUS_CONFIRM_PAYMENT }}" @if ($status == global_const()::REMITTANCE_STATUS_CONFIRM_PAYMENT) selected @endif>{{ __("Confirm Payment") }}</option>
                                <option value="{{ global_const()::REMITTANCE_STATUS_HOLD }}" @if ($status == global_const()::REMITTANCE_STATUS_HOLD) selected @endif>{{ __("On Hold") }}</option>
                                <option value="{{ global_const()::REMITTANCE_STATUS_SETTLED }}" @if ($status == global_const()::REMITTANCE_STATUS_SETTLED) selected @endif>{{ __("Settled") }}</option>
                                <option value="{{ global_const()::REMITTANCE_STATUS_COMPLETE }}" @if ($status == global_const()::REMITTANCE_STATUS_COMPLETE) selected @endif>{{ __("Complete") }}</option>
                                <option value="{{ global_const()::REMITTANCE_STATUS_CANCEL }}" @if ($status == global_const()::REMITTANCE_STATUS_CANCEL) selected @endif>{{ __("Cancel") }}</option>
                                <option value="{{ global_const()::REMITTANCE_STATUS_FAILED }}" @if ($status == global_const()::REMITTANCE_STATUS_FAILED) selected @endif>{{ __("Failed") }}</option>
                                <option value="{{ global_const()::REMITTANCE_STATUS_REFUND }}" @if ($status == global_const()::REMITTANCE_STATUS_REFUND) selected @endif>{{ __("Refunded") }}</option>
                                <option value="{{ global_const()::REMITTANCE_STATUS_DELAYED }}" @if ($status == global_const()::REMITTANCE_STATUS_DELAYED) selected @endif>{{ __("Delayed") }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="submit-btn-area d-flex justify-content-between pt-4">
                        <div class="submit-btn-wrapper">
                            <button type="submit" class="btn--base">{{ __("Search") }}</button>
                        </div>
                        @if (isset($transactions) && count($transactions) > 0)
                            <input type="hidden" class="submit_type">
                            <div class="pdf-btn-wrapper">
                                <button type="button" class="btn--base pdf-button"><i class="las la-file-pdf"></i> {{ __("PDF") }}</button>
                            </div>
                        @endif
                        
                    </div>
                </form>
            </div>
        </div>
        @if(isset($transactions))
            <div class="table-area mt-4">
                <div class="table-wrapper">
                    <div class="table-header">
                        <div class="table-responsive">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>{{ __("MTCN Number") }}</th>
                                        <th>{{ __("Sender Name") }}</th>
                                        <th>{{ __("Receiver Name") }}</th>
                                        <th>{{ __("Sending Amount") }}</th>
                                        <th>{{ __("Receiving Amount") }}</th>
                                        <th>{{ __("Sending Purpose") }}</th>
                                        <th>{{ __("Source Of Fund") }}</th>
                                        <th>{{ __("Transaction Type") }}</th>
                                        <th>{{ __("Payment Method") }}</th>
                                        <th>{{ __("Payable Amount") }}</th>
                                        <th>{{ __("Status") }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($transactions as $item )
                                        <tr>
                                            <td>{{ $item->trx_id ?? ''}}</td>
                                            <td>{{ $item->remittance_data->sender_name ?? '' }}</td>
                                            <td>{{ $item->remittance_data->first_name ?? '' }} {{ $item->remittance_data->middle_name ?? 'N/A' }} {{ $item->remittance_data->last_name ?? '' }}</td>
                                            <td>{{ get_amount($item->request_amount) ?? '' }} {{ $item->remittance_data->sender_currency }}</td>
                                            <td>{{ get_amount($item->will_get_amount) ?? '' }} {{ $item->remittance_data->receiver_currency }}</td>
                                            <td>{{ $item->remittance_data->sending_purpose ?? '' }}</td>
                                            <td>{{ $item->remittance_data->source ?? '' }}</td>
                                            <td>{{ __($item->remittance_data->type) ?? '' }}</td>
                                            <td>{{ $item->remark ?? '' }}</td>
                                            <td>{{ get_amount($item->payable) ?? '' }}</td>
                                            <td>
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
                                            </td>
                                        </tr>
                                    @empty
                                        @include('admin.components.alerts.empty',['colspan' => 11])
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@endsection


@push('script')

<script>
    $(document).on("click",".pdf-button", function() {
        $(this).parents("form").find(".submit_type").attr("name","submit_type").val("EXPORT");
        $(this).parents("form").submit();
    });
</script>
    
@endpush