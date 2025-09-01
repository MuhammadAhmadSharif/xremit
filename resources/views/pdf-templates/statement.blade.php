<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ public_path('public/backend/css/line-awesome.css') }}">

    <style>
        body{
            background: #ffffff;
            font-family: "Outfit", sans-serif;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.5em;
            color: #52526C;
            overflow-x: hidden;
            padding: 0;
            margin: 0;
        }
        *, ::after, ::before {
            box-sizing: border-box;
        }
        .page-wrapper {
            position: relative;
            min-height: 100vh;
        }
        .bg-overlay-base {
            position: relative;
            z-index: 2;
        }
        .bg_img {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat !important;
        }
        .bg-overlay-base:after {
            content: "";
            position: absolute;
            background-color: #f0eff5;
            opacity: 0.6;
            width: 100%;
            height: 100%;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            z-index: -1;
        }
        .main-wrapper{
            max-width: 1300px;
            margin: 0 auto;
            justify-content: center;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            min-height: 100vh;
        }
        .body-wrapper {
            -webkit-transition: all 0.5s;
            transition: all 0.5s;
        }
        .mb-20-none {
            margin-bottom: -20px;
        }
        .row {
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 0;
            display: flex;
            flex-wrap: wrap;
            margin-top: calc(var(--bs-gutter-y) * -1);
            margin-right: calc(var(--bs-gutter-x)/ -2);
            margin-left: calc(var(--bs-gutter-x)/ -2);
        }
        .row > * {
            position: relative;
        }
        .row>* {
            flex-shrink: 0;
            width: 100%;
            max-width: 100%;
            padding-right: calc(1.5rem / 2);
            padding-left: calc(1.5rem / 2);
            margin-top: 0;
        }
        @media (min-width: 768px){
            .col-md-6 {
                flex: 0 0 auto;
                width: 50%;
            }
        }
        @media (min-width: 992px){
            .col-lg-4 {
                flex: 0 0 auto;
                width: 33.3333333333%;
            }
        }
        @media (min-width: 1200px){
            .col-xl-4 {
                flex: 0 0 auto;
                width: 33.3333333333%;
            }
        }
        .mb-20 {
            margin-bottom: 20px;
        }
        .pdf-logo{
            width: 270px;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f7f7f7;
            border-radius: 20px 20px 0 0;
            text-align: center;
            margin: 0 auto;
        }
        .logo-wrapper{
            text-align: center;
        }
        .logo-wrapper img{
            width: 110px;
        }
        img {
            max-width: 100%;
            height: auto;
        }
        .logo-wrapper .number{
            display: block;
            padding-top: 10px;
            font-size: 14px;
            font-weight: 600;
            color: #000248;
        }
        .custom-card .card-body {
            background: #f7f7f7;
            padding: 30px;
            border-radius: 20px;
        }
        .preview-list-wrapper {
            background: #f0eff5;
            border-radius: 0;
            overflow: hidden;
        }
        .preview-list-wrapper .preview-list-item {
            position: relative;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            border-bottom: 1px solid rgb(231, 232, 236);
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            padding: 15px 10px;
        }
        .preview-list-user-wrapper {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }
        .preview-list-user-wrapper .preview-list-user-icon {
            width: 30px;
            height: 30px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            background-color: transparent;
            border: 1px solid #263159;
            color: #263159;
            border-radius: 50%;
            font-size: 18px;
            -webkit-transition: all 0.5s;
            transition: all 0.5s;
        }
        .preview-list-user-wrapper .preview-list-user-content span {
            color: #000248;
            font-weight: 400;
        }
        .preview-list-right {
            text-align: right;
            color: #000248;
            font-weight: 600;
            font-size: 12px;
        }
        span {
            display: inline-block;
        }
        .text--base {
            color: #263159 !important;
        }
        .table-wrapper {
            background-color: #f7f7f7;
            padding: 30px;
            border-radius: 15px;
        }
        .custom-card .table-wrapper {
            background-color: #f0eff5;
        }
        .dashboard-header-wrapper {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        h1, h2, h3, h4, h5, h6 {
            clear: both;
            line-height: 1.3em;
            color: #000248;
            -webkit-font-smoothing: antialiased;
            font-family: "Outfit", sans-serif;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 0;
        }
        .dashboard-header-wrapper .title {
            margin-bottom: 0;
        }
        h4 {
            font-size: 18px;
        }
        table {
            caption-side: bottom;
            border-collapse: collapse;
        }
        .custom-table {
            width: 100%;
        }
        tbody, td, tfoot, th, thead, tr {
            border-color: inherit;
            border-style: solid;
            border-width: 0;
        }
        .custom-table thead tr {
            border-bottom: 1px solid rgb(231, 232, 236);
        }
        .custom-table thead tr th {
            border: none;
            font-weight: 600;
            color: #000248;
            font-size: 14px;
            padding: 12px 15px;
            text-align: left;
        }
        .custom-table tbody tr {
            border-bottom: 1px solid rgb(231, 232, 236);
        }
        .custom-table tbody tr td {
            border: none;
            font-weight: 500;
            color: #52526C;
            font-size: 13px;
            padding: 12px 15px;
        }
        .mt-20 {
            margin-top: 20px;
        }
        @media print{
            header{
                position: fixed;
                top: 0;
                border: none;
            }
            main{
                margin-top: 2cm;
            }
            footer{
                position: fixed;
                bottom: 0;
            }
            @page {
                size: auto;
                margin: 6.35mm;
            }
        }
        @page {
            size: A4;
            margin: 0;
        }
    </style>
</head>
<body>

<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Dashboard
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div class="page-wrapper" style="margin: 50px">
    <div class="main-wrapper">
        <div class="main-body-wrapper">
            <div class="body-wrapper p-0">
                <div class="row mb-20-none">
                    <div class="col-xl-12 col-lg-12 mb-20">
                        <div class="pdf-logo" style="padding-top: 30px">
                            <div class="logo-wrapper">
                                <img src="{{ get_logo($basic_settings,"dark") }}" alt="logo">
                            </div>
                        </div>
                        <div class="custom-card">
                            <div class="card-body">
                                <div class="table-area mt-20">
                                    <div class="table-wrapper">
                                        <div class="table-responsive">
                                            <table class="custom-table">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Remittance</th>
                                                        <th>Amount</th>
                                                        <th>Source & Purpose</th>
                                                        <th>Payment By</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($transaction as $item)
                                                        <tr>
                                                            <td>{{ $item->created_at->format("d-m-Y") }}</td>
                                                            <td>
                                                                {{ $item->trx_id ?? ''}} <br>
                                                                {{ $item->remittance_data->sender_name ?? '' }} <br>
                                                                {{ $item->remittance_data->first_name ?? '' }} {{ $item->remittance_data->middle_name ?? '' }} {{ $item->remittance_data->last_name ?? '' }}
                                                            </td>
                                                            <td>
                                                                {{ get_amount($item->request_amount) ?? '' }} {{ $item->remittance_data->sender_currency }} <br>
                                                                {{ get_amount($item->will_get_amount) ?? '' }} {{ $item->remittance_data->receiver_currency }}
                                                            </td>
                                                            <td>
                                                                {{ $item->remittance_data->sending_purpose ?? '' }} <br>
                                                                {{ $item->remittance_data->source ?? '' }}
                                                            </td>
                                                            <td>
                                                                {{ $item->remark ?? '' }} <br>
                                                                {{ get_amount($item->payable) ?? '' }} {{ $item->remittance_data->currency->code }}
                                                            </td>
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
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <p>Total Search Result : {{ $total_transactions }}</p>
                                            <p>Total Send Amount : {{ get_amount($total_send_amount) }} {{ get_default_currency_code() }}</p>
                                            <p>Total Receive Amount : {{ get_amount($total_receive_amount) }} {{ get_default_currency_code() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Dashboard
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
</body>
</html>