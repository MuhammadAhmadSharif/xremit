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
                        <th>{{ __("SL") }}</th>
                        <th>{{ __("Name") }}</th>
                        <th>{{ __("Status") }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $step_key = 0;
                    @endphp
                    @forelse ($categories ?? [] as $key => $item)
                        @php
                            $step_key++;
                        @endphp
                        <tr data-item="{{ $item }}">
                            <td>{{ $step_key ?? ''}}</td>
                            <td>{{ $item->title ?? ''}}</td>
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
                                    'href'          => setRoute('admin.receiving.method.category.edit',$item->slug),
                                    'class'         => "edit-modal-button",
                                    'permission'    => "admin.receiving.method.category.update",
                                ])
                               
                            </td>
                        </tr>
                    @empty
                        @include('admin.components.alerts.empty',['colspan' => 2])
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ get_paginate($categories) }}
</div>
@endsection
@push('script')
    <script>
        $(document).ready(function(){
            // Switcher
            switcherAjax("{{ setRoute('admin.receiving.method.category.status.update') }}");
        })
    </script>
@endpush
