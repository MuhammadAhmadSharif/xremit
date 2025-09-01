@extends('user.layouts.master')

@section('breadcrumb')
    @include('user.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("user.dashboard"),
        ]
    ], 'active' => __("Transactions Log")])
@endsection

@section('content')


<div class="body-wrapper">
    <div class="dashboard-list-area mt-20">
        <div class="dashboard-header-wrapper">
            <h4 class="title">{{ __("Transactions Log") }}</h4>
        </div>
        @include('user.components.transaction-log.index',[
            'data'  => $transactions
        ])
    </div>
</div>

@endsection

@push('script')
<script>
 itemSearch($("input[name=search_text]"),$(".transaction-results"),"{{ setRoute('user.transaction.search') }}",1);
</script>
@endpush