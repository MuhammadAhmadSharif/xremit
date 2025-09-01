@php
    $default = get_default_language_code();
@endphp
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
    <div class="row mb-30-none justify-content-center">
        <div class="col-lg-12 mb-30">
            <div class="dash-payment-item-wrapper">
                <div class="dash-payment-item active">
                    <div class="dash-payment-title-area">
                        <P class="title">
                            @php
                                echo @$gateway->desc;
                            @endphp
                        </P>
                    </div>
                    <div class="dash-payment-body">
                        <form class="card-form" action="{{ setRoute("user.send.remittance.manual.submit",$token) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                @foreach ($gateway->input_fields as $item)
                                @if ($item->type == "select")
                                    <div class="col-lg-12 form-group">
                                        <label for="{{ $item->name }}">{{ $item->label }}
                                            @if($item->required == true)
                                            <span class="text-danger">*</span>
                                            @else
                                            <span class="">( {{ __("Optional") }} )</span>
                                            @endif
                                        </label>
                                        <select name="{{ $item->name }}" id="{{ $item->name }}" class="form--control nice-select">
                                            <option selected disabled>{{ __("Choose One") }}</option>
                                            @foreach ($item->validation->options as $innerItem)
                                                <option value="{{ $innerItem }}">{{ $innerItem }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @elseif ($item->type == "file")
                                    <div class="col-lg-12 form-group">
                                        <label for="{{ $item->name }}">{{ $item->label }}
                                            @if($item->required == true)
                                            <span class="text-danger">*</span>
                                            @else
                                            <span class="">( {{ __("Optional") }} )</span>
                                            @endif
                                        </label>
                                        <input type="{{ $item->type }}" class="form--control" name="{{ $item->name }}" value="{{ old($item->name) }}">
                                    </div>
                                @elseif ($item->type == "text")
                                    <div class="col-lg-12 form-group">
                                        <label for="{{ $item->name }}">{{ $item->label }}
                                            @if($item->required == true)
                                            <span class="text-danger">*</span>
                                            @else
                                            <span class="">( {{ __("Optional") }} )</span>
                                            @endif
                                        </label>
                                        <input type="{{ $item->type }}" class="form--control" placeholder="{{ ucwords(str_replace('_',' ', $item->name)) }}" name="{{ $item->name }}" value="{{ old($item->name) }}">
                                    </div>
                                @elseif ($item->type == "textarea")
                                    <div class="col-lg-12 form-group">
                                        @include('admin.components.form.textarea',[
                                            'label'     => $item->label,
                                            'name'      => $item->name,
                                            'value'     => old($item->name),
                                        ])
                                    </div>
                                @endif
                            @endforeach
                                <div class="col-xl-12 col-lg-12">
                                    <button type="submit" class="btn--base w-100 btn-loading"> {{ __("Confirm Payment") }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')

@endpush
