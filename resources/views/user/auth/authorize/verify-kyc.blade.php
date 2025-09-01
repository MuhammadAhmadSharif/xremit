@php
    $default = get_default_language_code();
@endphp
@extends('user.layouts.master')

@section('content')
<div class="dashboard-area mt-10">
    <div class="dashboard-header-wrapper">
        <h3 class="title">{{ $page_title }}</h3>
    </div>
</div>
<div class="row mb-30-none justify-content-center">
    <div class="col-lg-6 mb-30">
        <div class="dash-payment-item-wrapper">
            <div class="dash-payment-item active">
                <div class="dash-payment-title-area">
                    <span class="dash-payment-badge">!</span>
                    <h5 class="title">{{ __("Proof Of Indentity") }}</h5>
                </div>
                <div class="dash-payment-body">
                    @include('user.components.profile.kyc', compact('user_kyc'))
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')

@endpush
