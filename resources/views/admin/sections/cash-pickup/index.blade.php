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
        <div class="table-header">
            <h5 class="title">{{ __($page_title) }}</h5>
            <div class="table-btn-area">
                @include('admin.components.link.add-default',[
                    'text'          => __("Add Pickup Point"),
                    'href'          => "#add-cash-pickup",
                    'class'         => "modal-btn",
                    'permission'    => "admin.cash.pickup.method.store",
                ])
            </div>
        </div>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>{{ __("Country") }}</th>
                        <th>{{ __("Address") }}</th>
                        <th>{{ __("Status") }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cash_pickups ?? [] as $key => $item)
                        <tr data-item="{{ $item }}">
                            <td>{{ $item->country ?? ''}}</td>
                            <td class="text-wrap">{{ $item->address ?? ''}}</td>
                            
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
                                        'class'         => "edit-modal-button",
                                        'permission'    => "admin.cash.pickip.method.update",
                                    ])
                                <button class="btn btn--base btn--danger delete-modal-button" ><i class="las la-trash-alt"></i></button>
                               
                            </td>
                        </tr>
                    @empty
                        @include('admin.components.alerts.empty',['colspan' => 4])
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ get_paginate($cash_pickups) }}
</div>

@include('admin.components.modals.cash-pickup.add')

@include('admin.components.modals.cash-pickup.edit')
@endsection
@push('script')
    <script>
        openModalWhenError("add-cash-pickup","#add-cash-pickup")

        $(".delete-modal-button").click(function(){
            var oldData     = JSON.parse($(this).parents("tr").attr("data-item"));
            var actionRoute = "{{ setRoute('admin.cash.pickup.method.delete') }}";
            var target      = oldData.id;
            var message     = `{{ __("Are you sure to") }} <strong>{{ __("delete") }}</strong> {{ __("this pickup point?") }}`;

            openDeleteModal(actionRoute,target,message);
        });
    </script>
    <script>
        $(document).ready(function(){
            // Switcher
            switcherAjax("{{ setRoute('admin.cash.pickup.method.status.update') }}");
        })
    </script>
@endpush
