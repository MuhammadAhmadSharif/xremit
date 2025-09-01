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
                                <span class="number">MTCN Number : {{ $transaction->trx_id }}</span>
                            </div>
                        </div>
                        <div class="custom-card">
                            <div class="card-body">
                                <div class="preview-list-area" style="width: 100%; margin-top: 30px">
                                    <div class="" style="width:30%; margin-right: 20px; display: inline-block">
                                        <div class="preview-list-wrapper" style="border-radius: 10px">
                                            <div class="preview-list-item" style="width: 100%">
                                                <div class="preview-list-left" style="width: 40%; display: inline-block">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-content">
                                                            <span>Send Amount</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right" style="width: 40%; display: inline-block">
                                                    <span><span class="text--base">{{ get_amount($transaction->request_amount) }} {{ $transaction->remittance_data->sender_currency }}</span>
                                                </div>
                                            </div>
                                            <div class="preview-list-item" style="width: 100%">
                                                <div class="preview-list-left" style="width: 40%; display: inline-block">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-content">
                                                            <span>Received Amount</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right" style="width: 40%; display: inline-block">
                                                    <span><span class="text--base">{{ get_amount($transaction->will_get_amount) }} {{ $transaction->remittance_data->receiver_currency }}</span>
                                                </div>
                                            </div>
                                            <div class="preview-list-item" style="width: 100%">
                                                <div class="preview-list-left" style="width: 40%; display: inline-block">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-content">
                                                            <span>Exchange rate</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right" style="width: 40%; display: inline-block">
                                                    <span>{{ $transaction->remittance_data->sender_ex_rate }} {{ $transaction->remittance_data->sender_currency }} = {{ $transaction->remittance_data->receiver_ex_rate }} {{ $transaction->remittance_data->receiver_currency }}</span>
                                                </div>
                                            </div>
                                            <div class="preview-list-item" style="width: 100%">
                                                <div class="preview-list-left" style="width: 40%; display: inline-block">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-content">
                                                            <span>Fees & Charges</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right" style="width: 40%; display: inline-block">
                                                    <span>{{ get_amount($transaction->fees) }} {{ $transaction->remittance_data->sender_currency }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="" style="width:31%; margin-right: 20px; display: inline-block">
                                        <div class="preview-list-wrapper" style="border-radius: 10px">
                                            <div class="preview-list-item" style="width: 100%">
                                                <div class="preview-list-left" style="width: 40%; display: inline-block">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-content">
                                                            <span>Transaction Type</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right" style="width: 40%; display: inline-block">
                                                    <span><span class="text--base">{{ $transaction->remittance_data->type }}</span>
                                                </div>
                                            </div>
                                            <div class="preview-list-item" style="width: 100%">
                                                <div class="preview-list-left" style="width: 40%; display: inline-block">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-content">
                                                            <span>Country</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right" style="width: 40%; display: inline-block">
                                                    <span><span class="text--base">{{ $transaction->remittance_data->country }}</span>
                                                </div>
                                            </div>
                                            <div class="preview-list-item" style="width: 100%">
                                                <div class="preview-list-left" style="width: 40%; display: inline-block">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-content">
                                                            <span>City & State</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right" style="width: 40%; display: inline-block">
                                                    <span>{{ $transaction->remittance_data->city }} ({{ $transaction->remittance_data->state }})</span>
                                                </div>
                                            </div>
                                            <div class="preview-list-item" style="width: 100%">
                                                <div class="preview-list-left" style="width: 40%; display: inline-block">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-content">
                                                            <span>Request Date</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right" style="width: 40%; display: inline-block">
                                                    <span>{{ $transaction->created_at->format("d-m-Y") }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="preview-list-item" style="width: 100%">
                                                <div class="preview-list-left" style="width: 40%; display: inline-block">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-content">
                                                            <span>Status</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right" style="width: 40%; display: inline-block">
                                                    <span>
                                                        @if ($transaction->status == global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT)
                                                            <span>{{ __("Review Payment") }}</span> 
                                                        @elseif ($transaction->status == global_const()::REMITTANCE_STATUS_PENDING)
                                                            <span>{{ __("Pending") }}</span>
                                                        @elseif ($transaction->status == global_const()::REMITTANCE_STATUS_CONFIRM_PAYMENT)
                                                            <span>{{ __("Confirm Payment") }}</span>
                                                        @elseif ($transaction->status == global_const()::REMITTANCE_STATUS_HOLD)
                                                            <span>{{ __("On Hold") }}</span>
                                                        @elseif ($transaction->status == global_const()::REMITTANCE_STATUS_SETTLED)
                                                            <span>{{ __("Settled") }}</span>
                                                        @elseif ($transaction->status == global_const()::REMITTANCE_STATUS_COMPLETE)
                                                            <span>{{ __("Completed") }}</span>
                                                        @elseif ($transaction->status == global_const()::REMITTANCE_STATUS_CANCEL)
                                                            <span>{{ __("Canceled") }}</span>
                                                        @elseif ($transaction->status == global_const()::REMITTANCE_STATUS_FAILED)
                                                            <span>{{ __("Failed") }}</span>
                                                        @elseif ($transaction->status == global_const()::REMITTANCE_STATUS_REFUND)
                                                            <span>{{ __("Refunded") }}</span>
                                                        @else
                                                            <span>{{ __("Delayed") }}</span>
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="" style="width:31%; display: inline-block">
                                        <div class="preview-list-wrapper" style="border-radius: 10px">
                                            <div class="preview-list-item" style="width: 100%">
                                                <div class="preview-list-left" style="width: 40%; display: inline-block">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-content">
                                                            <span>Sending Purpose</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right" style="width: 40%; display: inline-block">
                                                    <span><span class="text--base">{{ $transaction->remittance_data->sending_purpose ?? "" }}</span>
                                                </div>
                                            </div>
                                            <div class="preview-list-item" style="width: 100%">
                                                <div class="preview-list-left" style="width: 40%; display: inline-block">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-content">
                                                            <span>Source of Fund</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right" style="width: 40%; display: inline-block">
                                                    <span><span class="text--base">{{ $transaction->remittance_data->source ?? "" }}</span>
                                                </div>
                                            </div>
                                            <div class="preview-list-item" style="width: 100%">
                                                <div class="preview-list-left" style="width: 40%; display: inline-block">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-content">
                                                            <span>Payment Type</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right" style="width: 40%; display: inline-block">
                                                    <span>{{ $transaction->remittance_data->currency->name ?? "" }}</span>
                                                </div>
                                            </div>
                                            <div class="preview-list-item" style="width: 100%">
                                                <div class="preview-list-left" style="width: 40%; display: inline-block">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-content">
                                                            <span>Payment Date</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right" style="width: 40%; display: inline-block">
                                                    <span>{{ $transaction->created_at->format("d-m-Y") }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if (empty($coupon_transaction))
                                <div class="table-area" style="margin-bottom: 15px;">
                                    <div class="table-wrapper">
                                        <div class="dashboard-header-wrapper">
                                            <h4 class="title">Sender</h4>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="custom-table">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>{{ $transaction->remittance_data->sender_name ?? "N/A"}}</td>
                                                        <td>{{ $transaction->remittance_data->sender_email ?? "" }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="preview-list-area" style="width: 100%;">
                                    <div class="" style="width:48%; margin-right: 20px; display: inline-block">
                                        <div class="preview-list-wrapper" style="border-radius: 10px">
                                            <div class="table-wrapper">
                                                <div class="dashboard-header-wrapper">
                                                    <h4 class="title">Sender</h4>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="custom-table">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>Email</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>{{ $transaction->remittance_data->sender_name ?? ""}}</td>
                                                                <td>{{ $transaction->remittance_data->sender_email ?? "" }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="" style="width:48%; display: inline-block">
                                        <div class="preview-list-wrapper" style="border-radius: 10px">
                                            <div class="table-wrapper">
                                                <div class="dashboard-header-wrapper">
                                                    <h4 class="title">Coupon</h4>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="custom-table">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>Price</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>{{ $coupon_transaction->coupon->name ?? $coupon_transaction->user_coupon->coupon_name}}</td>
                                                                <td>{{ get_amount($coupon_transaction->coupon->price) ?? get_amount($coupon_transaction->user_coupon->price) }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="table-area ">
                                    <div class="table-wrapper">
                                        <div class="dashboard-header-wrapper">
                                            <h4 class="title">Receiver</h4>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="custom-table">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Country</th>
                                                        <th>City</th>
                                                        <th>Address</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td weight="100px">{{ $transaction->remittance_data->first_name . " " . $transaction->remittance_data->middle_name . " " . $transaction->remittance_data->last_name }}</td>
                                                        <td weight="100px">{{ $transaction->remittance_data->country ?? "" }}</td>
                                                        <td weight="100px">{{ $transaction->remittance_data->city ?? "" }}</td>
                                                        <td weight="100px">{{ $transaction->remittance_data->address ?? "" }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
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