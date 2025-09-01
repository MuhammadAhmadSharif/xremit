@extends('admin.layouts.master')

@push('css')

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
    ], 'active' => __("Dashboard")])
@endsection

@section('content')
    <div class="dashboard-area">
        <div class="dashboard-item-area">
            <div class="row">
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Review Payment Remittance") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ formatNumberInKNotation($data['under_review_log']) }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--info">{{ __("Hold") }} {{ formatNumberInKNotation($data['hold_log']) }}</span>
                                    <span class="badge badge--warning">{{ __("Failed") }} {{ formatNumberInkNotation($data['failed_log']) }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart11" data-percent="{{ $data['percent_under_review'] }}"><span>{{ round($data['percent_under_review']) }}%</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Pending Remittance") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ formatNumberInkNotation($data['pending']) }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--info">{{ __("In Progress") }} {{ formatNumberInkNotation($data['progress']) }}</span>
                                    <span class="badge badge--warning">{{ __("Settled") }} {{ formatNumberInkNotation($data['settled']) }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart8" data-percent="{{ $data['percent_pending'] }}"><span>{{ round($data['percent_pending']) }}%</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Complete Remittance") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ formatNumberInkNotation($data['complete']) }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--info">{{ __("Cancel") }} {{ formatNumberInkNotation($data['cancel']) }}</span>
                                    <span class="badge badge--warning">{{ __("Refund") }} {{ formatNumberInkNotation($data['refund']) }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart9" data-percent="{{ $data['percent_complete'] }}"><span>{{ round($data['percent_complete']) }}%</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Total Remittance") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ formatNumberInkNotation($data['total_remittance_count']) }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">{{ __("Complete") }} {{ formatNumberInkNotation($data['complete']) }}</span>
                                    <span class="badge badge--warning">{{ __("Cancel") }} {{ formatNumberInkNotation($data['cancel']) }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart10" data-percent="{{ $data['percent_total_logs'] }}"><span>{{ round($data['percent_total_logs']) }}%</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Total Users") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ formatNumberInKNotation($data['total_user_count']) }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">{{ __("Active") }} {{ $data['active_user'] }}</span>
                                    <span class="badge badge--warning">{{ __("Unverified") }} {{ $data['unverified_user'] }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart6" data-percent="{{ $data['user_percent'] }}"><span>{{ round($data['user_percent']) }}%</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Total Remittance Bank") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ formatNumberInKNotation($data['total_remittance_bank_count']) }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--info">{{ __("Active") }} {{ $data['active_bank'] }}</span>
                                    <span class="badge badge--warning">{{ __("Pending") }} {{ $data['pending_bank'] }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart7" data-percent="{{ $data['percent_bank'] }}"><span>{{ round($data['percent_bank']) }}%</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Total Mobile Method") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ formatNumberInkNotation($data['total_mobile_method_count']) }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--info">{{ __("Active") }} {{ formatNumberInkNotation($data['active_mobile']) }}</span>
                                    <span class="badge badge--warning">{{ __("Pending") }} {{ formatNumberInkNotation($data['pending_mobile']) }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart12" data-percent="{{ $data['percent_mobile'] }}"><span>{{ round($data['percent_mobile']) }}%</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Total Support Ticket") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ formatNumberInkNotation($data['total_support_ticket_count']) }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--info">{{ __("Active") }} {{ formatNumberInkNotation($data['active_ticket']) }}</span>
                                    <span class="badge badge--warning">{{ __("Pending") }} {{ formatNumberInkNotation($data['pending_ticket']) }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart13" data-percent="{{ $data['percent_ticket'] }}"><span>{{ round($data['percent_ticket']) }}%</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="chart-area mt-15">
        <div class="row mb-15-none">
            <div class="col-xxl-6 col-xl-6 col-lg-6 mb-15">
                <div class="chart-wrapper">
                    <div class="chart-area-header">
                        <h5 class="title">{{ __("Remittance Analytics") }}</h5>
                    </div>
                    <div class="chart-container">
                        <div id="chart1" data-chart_one_data="{{ json_encode($data['chart_one_data']) }}" data-month_day="{{ json_encode($data['month_day']) }}"  class="order-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-xxxl-6 col-xxl-3 col-xl-6 col-lg-6 mb-15">
                <div class="chart-wrapper">
                    <div class="chart-area-header">
                        <h5 class="title">{{ __("User Analytics Chart") }}</h5>
                    </div>
                    <div class="chart-container">
                        <div id="chart4" class="balance-chart" data-user_chart_data="{{ json_encode($data['user_chart_data']) }}"></div>
                    </div>
                    <div class="chart-area-footer">
                        <div class="chart-btn">
                            <a href="{{ setRoute('admin.users.index') }}" class="btn--base w-100">{{ __("View User") }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="table-area mt-15">
        <div class="table-wrapper">
            <div class="table-header">
                <h5 class="title">{{ __("Recent Remittance") }}</h5>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>{{ __("MTCN ID") }}</th>
                            <th>{{ __("Sender Name") }}</th>
                            <th>{{ __("Receiver Name") }}</th>
                            <th>{{ __("Transaction Type") }}</th>
                            <th>{{ __("AMOUNT") }}</th>
                            <th>{{ __("Payment Method") }}</th>
                            <th>{{ __("Status") }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions ?? [] as $key => $item)
                        <tr>
                            <td>{{ $item->trx_id ?? '' }}</td>
                            <td>{{ $item->remittance_data->sender_name ?? '' }}</td>
                            <td>{{ $item->remittance_data->first_name ?? '' }} {{ $item->remittance_data->middle_name ?? '' }} {{ $item->remittance_data->last_name ?? '' }}</td>
                            <td>{{ $item->remittance_data->type ?? '' }}</td>
                            <td>{{ get_amount($item->payable) ?? '' }} {{ $item->remittance_data->sender_currency ?? '' }} <span>{{ get_amount($item->will_get_amount) ?? '' }} {{ $item->remittance_data->receiver_currency ?? '' }}</span></td>

                            <td>{{ $item->remark ?? '' }}</td>
                            <td>
                                @if ($item->status == global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT)
                                    <span>{{ __("Under Review") }}</span>
                                @elseif ($item->status == global_const()::REMITTANCE_STATUS_PENDING)
                                    <span>{{ __("Pending") }}</span>
                                @elseif ($item->status == global_const()::REMITTANCE_STATUS_CONFIRM_PAYMENT)
                                    <span>{{ __("In Progress") }}</span>
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
                            <td>
                                <a href="{{ setRoute('admin.send.remittance.details',$item->trx_id) }}" class="btn btn--base btn--primary"><i class="las la-info-circle"></i></a>

                            </td>
                        </tr>
                    @empty
                        @include('admin.components.alerts.empty',['colspan' => 8])
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('script')
<!-- apexcharts js -->
<script src="{{ asset('public/backend/js/apexcharts.js') }}"></script>
<!-- chart js -->
<script src="{{ asset('public/backend/js/chart.js') }}"></script>
    <script>
        let stringMonths = '@json($months)';
        let chartMonths = JSON.parse(stringMonths);

        var options = {
            series: [{
            name: 'Remittance',
            color: "#5A5278",
            data: [44, 55, 57, 56, 61, 58, 63, 60, 66,10,10,12]
            },],
            chart: {
            type: 'bar',
            toolbar: {
                show: false
            },
            height: 325
            },
            plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                borderRadius: 5,
                endingShape: 'rounded'
            },
            },
            dataLabels: {
            enabled: false
            },
            stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
            },
            xaxis: {
            categories: chartMonths,
            },
            yaxis: {
            title: {
                text: '$ (thousands)'
            }
            },
            fill: {
            opacity: 1
            },
            tooltip: {
            y: {
                formatter: function (val) {
                return "$ " + val + " thousands"
                }
            }
            }
            };

            var chart = new ApexCharts(document.querySelector("#chart3"), options);
            chart.render();
    </script>
    <script>
        // apex-chart
var chart1 = $('#chart1');
var chart_one_data = chart1.data('chart_one_data');
var month_day = chart1.data('month_day');
var options = {
  series: [{
  name: '{{ __("Pending") }}',
  color: "#5A5278",
  data: chart_one_data.pending_data
}, {
  name: '{{ __("Completed") }}',
  color: "#6F6593",
  data: chart_one_data.complete_data
},],
  chart: {
  type: 'bar',
  height: 350,
  stacked: true,
  toolbar: {
    show: false
  },
  zoom: {
    enabled: true
  }
},
responsive: [{
  breakpoint: 480,
  options: {
    legend: {
      position: 'bottom',
      offsetX: -10,
      offsetY: 0
    }
  }
}],
plotOptions: {
  bar: {
    horizontal: false,
    borderRadius: 10
  },
},
xaxis: {
  type: 'datetime',
  categories: month_day,

},
legend: {
  position: 'bottom',
  offsetX: 40
},
fill: {
  opacity: 1
}
};

var chart = new ApexCharts(document.querySelector("#chart1"), options);
chart.render();
var chart4 = $('#chart4');
var user_chart_data = chart4.data('user_chart_data');
var options = {
  series: user_chart_data,
  chart: {
  width: 350,
  type: 'pie'
},
colors: ['#5A5278', '#6F6593', '#8075AA', '#A192D9'],
labels: ['{{ __("Active") }}', '{{ __("Unverified") }}', '{{ __("Banned") }}', '{{ __("ALL") }}'],
responsive: [{
  breakpoint: 1480,
  options: {
    chart: {
      width: 280
    },
    legend: {
      position: 'bottom'
    }
  },
  breakpoint: 1199,
  options: {
    chart: {
      width: 380
    },
    legend: {
      position: 'bottom'
    }
  },
  breakpoint: 575,
  options: {
    chart: {
      width: 280
    },
    legend: {
      position: 'bottom'
    }
  }
}],
legend: {
  position: 'bottom'
},
};

var chart = new ApexCharts(document.querySelector("#chart4"), options);
chart.render();
    </script>
@endpush
