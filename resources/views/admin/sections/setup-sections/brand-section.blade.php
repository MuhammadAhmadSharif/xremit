@php
    $default_lang_code   = language_const()::NOT_REMOVABLE;
    $system_default_lang = get_default_language_code();
    $languages_for_js_use = $languages->toJson();
@endphp

@extends('admin.layouts.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('public/backend/css/fontawesome-iconpicker.css') }}">
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
    ], 'active' => __("Brand Section")])
@endsection

@section('content')

<div class="table-area mt-15">
    <div class="table-wrapper">
        <div class="table-header justify-content-end">
            <div class="table-btn-area">
                <a href="#add-brand" class="btn--base modal-btn"><i class="fas fa-plus me-1"></i> {{ __("Add Brand") }}</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data->value->items ?? [] as $key => $item)
                        <tr data-item="{{ json_encode($item) }}">
                            <td>
                                <ul class="user-list">
                                    <li><img src="{{ get_image($item->image ?? '','site-section') ?? '' }}" alt="image"></li>
                                </ul>
                            </td>
                            <td>
                                <button class="btn btn--base btn--danger delete-modal-button" ><i class="las la-trash-alt"></i></button>
                            </td>
                        </tr>
                    @empty
                        @include('admin.components.alerts.empty',['colspan' => 2])
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div id="add-brand" class="mfp-hide large">
    <div class="modal-data">
        <div class="modal-header px-0">
            <h5 class="modal-title">{{ __("Add Brand") }}</h5>
        </div>
        <div class="modal-form-data">
            <form class="modal-form" method="POST" action="{{ setRoute('admin.setup.sections.section.item.store',$slug) }}" enctype="multipart/form-data">
                @csrf
                <div class="row mb-10-none mt-3">
                    <div class="col-xl-12 col-lg-12 form-group">
                        @include('admin.components.form.input-file',[
                            'label'             => __("Image").":",
                            'name'              => "image",
                            'class'             => "file-holder",
                            'old_files_path'    => files_asset_path("site-section"),
                            'old_files'         => $data->value->items->image ?? "",
                        ])
                    </div>
                    
                    
                    <div class="col-xl-12 col-lg-12 form-group d-flex align-items-center justify-content-between mt-4">
                        <button type="button" class="btn btn--danger modal-close">{{ __("Cancel") }}</button>
                        <button type="submit" class="btn btn--base">{{ __("Add") }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
@push('script')
    <script>
        $(".delete-modal-button").click(function(){
            var oldData     = JSON.parse($(this).parents("tr").attr("data-item"));
            var actionRoute = "{{ setRoute('admin.setup.sections.section.item.delete',$slug) }}";
            var target      = oldData.id;
            var message     = `{{ __("Are you sure to") }} <strong>{{ __("delete") }}</strong> {{ __("this brand?") }}`;
            
            openDeleteModal(actionRoute,target,message);
        })
    </script>
@endpush