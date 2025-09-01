@extends('user.layouts.master')

@section('breadcrumb')
    @include('user.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("user.dashboard"),
        ]
    ], 'active' => __("Recipients")])
@endsection

@section('content')

<div class="body-wrapper">
    <div class="add-recipient-btn text-end pb-3">
        <a href="{{ setRoute('user.recipient.create') }}" class="btn--base">+{{ __("Add New Recipient") }} </a>
    </div>
    <div class="dashboard-list-wrapper">
        <input type="hidden" class="hidden-value" name="id">
        @csrf
        @forelse ($recipients as $item)
            <div class="dashboard-list-item-wrapper">
                <div class="dashboard-list-item receive d-flex justify-content-between">
                    <div class="dashboard-list-left">
                        <div class="dashboard-list-user-wrapper">
                            <div class="dashboard-list-user-icon">
                                <i class="las la-arrow-up"></i>
                            </div>
                            <div class="dashboard-list-user-content">
                                <h4 class="title">{{ $item->first_name }} {{ $item->middle_name ?? '' }} {{ $item->last_name }}</h4>
                                <span class="badge badge--warning">{{ __($item->method) ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard-list-button">
                        @include('admin.components.link.edit-default',[
                            'href'          => setRoute('user.recipient.edit',$item->id),
                            'class'         => "edit-modal-button",
                        ])
                         <button type="button" class="btn btn--danger delete-recipient" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $item->id }}"><i class="las la-trash-alt"></i></button>
                    </div>
                </div>
                <div class="preview-list-wrapper">
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-user"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("Name") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>{{ $item->first_name ?? '' }} {{ $item->middle_name ?? '' }} {{ $item->last_name ?? '' }}</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-envelope"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("Email") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>{{ $item->email ?? 'N/A'}} </span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-globe"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("Country") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>{{ $item->country }}</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-map-marked-alt"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("City & State") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>{{ $item->city ?? 'N/A'}} ({{ $item->state ?? 'N/A' }})</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-phone"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("Phone") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>{{ $item->phone ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-university"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("Method Name") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>{{ $item->bank_name ?? ($item->mobile_name ?? $item->pickup_point) }}</span>
                        </div>
                    </div>
                    @if ($item->method == global_const()::TRANSACTION_TYPE_BANK || $item->method == global_const()::TRANSACTION_TYPE_MOBILE)
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-user-circle"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __("Account Number") }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span>{{ $item->iban_number ?? $item->account_number }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="deleteModal-{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                    <h4 class="title">{{ __("Are you sure to delete this Recipient?") }}</h4>  
                    </div>
                    <div class="modal-footer justify-content-between border-0">
                        <button type="button" class="btn--base bg-danger" data-bs-dismiss="modal">{{ __("Close") }}</button>
                        <form action="{{ setRoute('user.recipient.delete',$item->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn--base">{{ __("Confirm") }}</button>
                        </form>
                    </div>
                </div>
                </div>
            </div>
        @empty
        <div class="alert alert-primary text-center">
            {{ __("No Recipient Found!") }}
        </div>
        @endforelse
    </div>

    {{ get_paginate($recipients) }}
</div>
@endsection
@push('script')

@endpush