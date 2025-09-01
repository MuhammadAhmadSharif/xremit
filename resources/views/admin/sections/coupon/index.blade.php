@extends('admin.layouts.master')

@push('css')

    <style>
        .fileholder {
            min-height: 374px !important;
        }

        .fileholder-files-view-wrp.accept-single-file .fileholder-single-file-view,.fileholder-files-view-wrp.fileholder-perview-single .fileholder-single-file-view{
            height: 330px !important;
        }
    </style>
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
                <div class="d-flex">
                    <div class="table-btn-area">
                        @include('admin.components.link.add-default',[
                            'text'          => __("Add New Coupon"),
                            'href'          => "#add-coupon",
                            'class'         => "modal-btn",
                            'permission'    => "admin.coupon.store",
                        ])
                    </div>
                </div>
                
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>{{ __("Coupon Name") }}</th>
                            <th>{{ __("Price") }}</th>
                            <th>{{ __("Maximum Limit") }}</th>
                            <th>{{ __("Remaining") }}</th>
                            <th>{{ __("Status") }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data ?? [] as $key => $item)
                            <tr data-item="{{ $item }}">
                                <td>{{ $item->name ?? ''}}</td>
                                <td>{{ get_amount($item->price) ?? ''}}</td>                                
                                <td>{{ @$item->max_used }}</td>
                                @php
                                    $coupon_transactions    = App\Models\CouponTransaction::where('coupon_id',$item->id)->count();
                                    $remaining = @$item->max_used - @$coupon_transactions;
                                @endphp                                
                                <td>{{ @$remaining }}</td>                                
                                <td>
                                    @if (@$remaining == 0)
                                        <span class="badge badge--danger">{{ __("Used") }}</span>
                                    @else
                                        <span class="badge badge--primary">{{ __("Unused") }}</span>
                                    @endif
                                </td>                                
                                <td>
                                    @include('admin.components.link.edit-default',[
                                        'class'         => "edit-modal-button",
                                        'permission'    => "admin.coupon.update",
                                    ])
                                    <button class="btn btn--base btn--danger delete-modal-button" ><i class="las la-trash-alt"></i></button>
                                </td>
                            </tr>
                        @empty
                            @include('admin.components.alerts.empty',['colspan' => 6])
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{ get_paginate($data) }}
    </div>
    @include('admin.components.modals.coupon.add')

    @include('admin.components.modals.coupon.edit')

@endsection

@push('script')
    <script>
        openModalWhenError("add-coupon","#add-coupon")

        $(".delete-modal-button").click(function(){
            var oldData     = JSON.parse($(this).parents("tr").attr("data-item"));
            var actionRoute = "{{ setRoute('admin.coupon.delete') }}";
            var target      = oldData.id;
            var message     = `Are you sure to <strong>delete</strong> this coupon?`;

            openDeleteModal(actionRoute,target,message);

        });

    </script>
@endpush