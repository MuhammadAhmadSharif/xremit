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
    ], 'active' => __($page_title)])
@endsection

@section('content')
<div class="table-area">
    <div class="table-wrapper">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>{{ __("Name") }}</th>
                        <th>{{ __("Status") }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bank_method_automatic ?? [] as $key => $item)
                        <tr data-item="{{ $item }}">
                            <td>{{ $item->name ?? ''}}</td>
                            
                            <td>
                                @include('admin.components.form.switcher',[
                                    'name'        => 'status',
                                    'value'       => $item->status,
                                    'options'     => [__('Enable') => 1,__('Disable') => 0],
                                    'onload'      => true,
                                    'data_target' => $item->id,
                                ])
                            </td>
                            
                            <td>
                                @include('admin.components.link.edit-default',[
                                    'href'          => setRoute('admin.bank.method.automatic.edit',$item->slug),
                                    'class'         => "edit-modal-button",
                                    'permission'    => "admin.bank.method.automatic.update",
                                ])
                               
                            </td>
                        </tr>
                    @empty
                        @include('admin.components.alerts.empty',['colspan' => 3])
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ get_paginate($bank_method_automatic) }}
</div>
@endsection
@push('script')
    <script>
        $(document).ready(function(){
            // Switcher
            switcherAjax("{{ setRoute('admin.bank.method.automatic.status.update') }}");
        })
    </script>
@endpush
