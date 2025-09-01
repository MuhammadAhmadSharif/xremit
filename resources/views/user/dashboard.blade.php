@extends('user.layouts.master')

@section('breadcrumb')
    @include('user.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("user.dashboard"),
        ]
    ], 'active' => __("Dashboard")])
@endsection

@section('content')
<div class="body-wrapper">
    <div class="row mt-20">
        <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-6 pb-20">
            <div class="dashbord-user dCard-1">
                <div class="dashboard-content">
                    <div class="d-flex justify-content-between">
                        <div class="">
                            <div class="top-content">
                                <h3>{{ __("Total Send Remittance") }}</h3>
                            </div>
                            <div class="user-count">
                                <span class="text-uppercase">{{ get_amount($data['total_amount']) ?? '' }}</span>
                            </div>
                        </div>
                        <div class="icon">
                            <i class="las la-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-6 pb-20">
            <div class="dashbord-user dCard-1">
                <div class="dashboard-content">
                    <div class="d-flex justify-content-between">
                        <div class="">
                            <div class="top-content">
                                <h3>{{ __("Total Remittance") }}</h3>
                            </div>
                            <div class="user-count">
                                <span class="text-uppercase">{{ $data['total_remittances'] ?? '' }}</span>
                            </div>
                        </div>
                        <div class="icon">
                            <i class="las la-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-6 pb-20">
            <div class="dashbord-user dCard-1">
                <div class="dashboard-content">
                    <div class="d-flex justify-content-between">
                        <div class="">
                            <div class="top-content">
                                <h3>{{ __("Completed Remittance") }}</h3>
                            </div>
                            <div class="user-count">
                                <span class="text-uppercase"> {{ formatNumberInkNotation($data['complete']) }}</span>
                            </div>
                        </div>
                        <div class="icon">
                            <i class="las la-cloud-upload-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-6 pb-20">
            <div class="dashbord-user dCard-1">
                <div class="dashboard-content">
                    <div class="d-flex justify-content-between">
                        <div class="">
                            <div class="top-content">
                                <h3>{{ __("Ongoing Remittance") }}</h3>
                            </div>
                            <div class="user-count">
                                <span class="text-uppercase">{{ $data['ongoing'] ?? '' }}</span>
                            </div>
                        </div>
                        <div class="icon">
                            <i class="las la-spinner"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-6 pb-20">
            <div class="dashbord-user dCard-1">
                <div class="dashboard-content">
                    <div class="d-flex justify-content-between">
                        <div class="">
                            <div class="top-content">
                                <h3>{{ __("Canceled Remittance") }}</h3>
                            </div>
                            <div class="user-count">
                                <span class="text-uppercase">{{ $data['cancel'] }}</span>
                            </div>
                        </div>
                        <div class="icon">
                            <i class="las la-times-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="chart-area mt-40">
        <div class="row mb-20-none">
            <div class="col-xxl-12 col-xl-12 col-lg-12 mb-20">
                <div class="chart-wrapper">
                    <div class="dashboard-header-wrapper">
                        <h4 class="title">{{ __("Total Transactions Chart") }}</h4>
                    </div>
                    <div class="chart-container">
                        <div id="chart1" data-chart_one_data="{{ json_encode($data['chart_one_data']) }}" data-month_day="{{ json_encode($data['month_day']) }}" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-list-area ptb-40">
        <div class="dashboard-header-wrapper">
            <h4 class="title">{{ __("Send Remittance Log") }}</h4>
            <div class="dashboard-btn-wrapper">
                <div class="dashboard-btn">
                    <a href="{{ setRoute('user.transaction.index') }}" class="btn--base">{{ __("View More") }}</a>
                </div>
            </div>
        </div>
        @foreach ($transactions as $item)
        <div class="dashboard-list-wrapper">
            <div class="dashboard-list-item-wrapper">
                <div class="dashboard-list-item sent">
                    <div class="dashboard-list-left">
                        <div class="dashboard-list-user-wrapper">
                            <div class="dashboard-list-user-icon">
                                <i class="las la-arrow-up"></i>
                            </div>
                            <div class="dashboard-list-user-content">
                                <h4 class="title">{{ $item->remittance_data->first_name ?? '' }} {{ $item->remittance_data->middle_name ?? '' }} {{ $item->remittance_data->last_name ?? ''}}</h4>
                                <span class="sub-title text--danger">{{ __($item->remittance_data->type ?? '') }}

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
                            <span>{{ $item->trx_id ?? '' }}</span>
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
                            <span>{{ __($item->remittance_data->type) ?? '' }}</span>
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
                            <span>{{ $item->remittance_data->method_name}}</span>
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
                            <span>{{ $item->remittance_data->account_number }}</span>
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
                            <span>{{ get_amount($item->fees ?? '') }} {{ $item->remittance_data->sender_currency ?? ''}}</span>
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
                            <div class="preview-list-right">
                                <span>{{ $item->remittance_data->sender_ex_rate ?? ''}} {{ $item->remittance_data->sender_currency ?? ''}} = {{ floatVal($item->remittance_data->currency->rate ?? '' / $item->remittance_data->sender_base_rate ?? '')}} {{ $item->remittance_data->currency->code ?? '' }}</span>
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
                            <span>{{ get_amount($item->payable ?? '' / $item->remittance_data->sender_base_rate ?? '') ?? '' }} {{ $item->remittance_data->currency->code ?? '' }}</span>
                        </div>
                    </div>
                    <div class="receipt-download" style="text-align: center; padding-top: 20px;">
                        <a href="{{ setRoute('download.pdf',$item->trx_id) }}" class="btn btn--base">{{ __("Download Receipt") }}</a>
                        <input type="hidden" name="" class="box" value="{{ setRoute('share.link',$item->trx_id) }}">
                        <div class="btn btn--base copy">{{ __("Copy Link") }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
@push('script')

<script>
    $('.copy').on('click',function(){

        let input = $('.box').val();
        navigator.clipboard.writeText(input)
        .then(function() {

            $('.copy').text("Copied");
        })
        .catch(function(err) {
            console.error('Copy failed:', err);
        });
    });
</script>
<script>
    var chart1 = $('#chart1');
    var chart_one_data = chart1.data('chart_one_data');
    var month_day = chart1.data('month_day');

    var options = {
      series: [{
          name: '{{ __("Pending") }}',
          color: "#8358ff",
          data: chart_one_data.pending_data
      }, {
          name: '{{ __("Complete") }}',
          data: chart_one_data.complete_data
      }],
      chart: {
          height: 350,
          type: 'area',
          toolbar: {
              show: false
          },
      },
      dataLabels: {
          enabled: false
      },
      stroke: {
          curve: 'smooth'
      },
      xaxis: {
          type: 'datetime',
          categories: month_day,
      },
      tooltip: {
          x: {
              format: 'dd/MM/yy HH:mm'
          },
      },
  };

  var chart = new ApexCharts(document.querySelector("#chart1"), options);
  chart.render();


  var options = {
      series: [{
          data: [44, 55, 41, 64, 22, 43, 21],
          color: "#8358ff"
      }, {
          data: [53, 32, 33, 52, 13, 44, 32]
      }],
      chart: {
          type: 'bar',
          toolbar: {
              show: false
          },
          height: 350
      },
      plotOptions: {
          bar: {
              horizontal: true,
              dataLabels: {
                  position: 'top',
              },
          }
      },
      dataLabels: {
          enabled: true,
          offsetX: -6,
          style: {
              fontSize: '12px',
              colors: ['#fff']
          }
      },
      stroke: {
          show: true,
          width: 1,
          colors: ['#fff']
      },
      tooltip: {
          shared: true,
          intersect: false
      },
      xaxis: {
          categories: [2001, 2002, 2003, 2004, 2005, 2006, 2007],
      },
  };

  var chart = new ApexCharts(document.querySelector("#chart2"), options);
  chart.render();
</script>
@endpush
