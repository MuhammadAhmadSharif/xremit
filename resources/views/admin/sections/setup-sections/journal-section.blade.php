@php
    $app_local = get_default_language_code();
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
    ], 'active' => __("Journal Section")])
@endsection

@section('content')
    <div class="custom-card">
        <div class="card-header">
            <h6 class="title">{{ __($page_title) }}</h6>
        </div>
        <div class="card-body">
            <form class="card-form" action="{{ setRoute('admin.setup.sections.section.update',$slug) }}" method="POST">
                @csrf
                <div class="row justify-content-center mb-10-none">
                    <div class="col-xl-12 col-lg-12">
                        <div class="product-tab">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    @foreach ($languages as $item)
                                        <button class="nav-link @if (get_default_language_code() == $item->code) active @endif" id="{{$item->name}}-tab" data-bs-toggle="tab" data-bs-target="#{{$item->name}}" type="button" role="tab" aria-controls="{{ $item->name }}" aria-selected="true">{{ $item->name }}</button>
                                    @endforeach
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">

                                @foreach ($languages as $item)
                                    @php
                                        $lang_code = $item->code;
                                    @endphp

                                    <div class="tab-pane @if (get_default_language_code() == $item->code) fade show active @endif" id="{{ $item->name }}" role="tabpanel" aria-labelledby="english-tab"> 
                                                                                                            
                                        <div class="form-group">
                                            @include('admin.components.form.input',[
                                                'label'     => __("Section Title")."*",
                                                'name'      => $lang_code . "_title",
                                                'value'     => old($lang_code . "_title",$data->value->language->$lang_code->title ?? "")
                                            ])
                                        </div>

                                        <div class="form-group">
                                            @include('admin.components.form.input',[
                                                'label'     => __("Heading")."*",
                                                'name'      => $lang_code . "_heading",
                                                'value'     => old($lang_code . "_heading",$data->value->language->$lang_code->heading ?? "")
                                            ])
                                        </div>  
                                    </div>
                                @endforeach                                
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 form-group">
                        @include('admin.components.button.form-btn',[
                            'class'         => "w-100 btn-loading",
                            'text'          => __("Submit"),
                            'permission'    => "admin.setup.sections.section.update"
                        ])
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="custom-card mt-5">
        <div class="card-header">
            <h6 class="title">{{ __("Web Journal Dashboard") }}</h6>
            <div class="d-flex ">
                <div class="button-link me-2">
                    @include('admin.components.link.custom',[
                        'text'          => __('Add Category'),
                        'class'         => 'btn btn--base',
                        'href'          => setRoute('admin.setup.sections.category.index'),
                        'permission'    => 'admin.setup.sections.journal.create',
                    ])
                </div>
                <div class="button-link">
                    @include('admin.components.link.custom',[
                        'text'          => __('Add New Journal'),
                        'class'         => 'btn btn--base',
                        'href'          => setRoute('admin.setup.sections.journal.create'),
                        'permission'    => 'admin.setup.sections.journal.create',
                    ])
                </div>
            </div>
            
        </div>

        <div class="card-body">
            <div class="dashboard-area">
                <div class="dashboard-item-area">
                    <div class="row">
                        <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                            <div class="dashbord-item border">
                                <div class="dashboard-content">
                                    <div class="left">
                                        <h6 class="title">{{ __("Total Category") }}</h6>
                                        <div class="user-info">
                                            <h2 class="user-count">{{ count($category) }}</h2>
                                        </div>
                                    </div>
                                    <div class="right">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                            <div class="dashbord-item border">
                                <div class="dashboard-content">
                                    <div class="left">
                                        <h6 class="title">{{ __("Active Category") }}</h6>
                                        <div class="user-info">
                                            <h2 class="user-count">{{ count($active_category) }}</h2>
                                        </div>
                                    </div>
                                    <div class="right">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                            <div class="dashbord-item border">
                                <div class="dashboard-content">
                                    <div class="left">
                                        <h6 class="title">{{ __("Total Journal") }}</h6>
                                        <div class="user-info">
                                            <h2 class="user-count">{{ count($journal) }}</h2>
                                        </div>
                                    </div>
                                    <div class="right">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                            <div class="dashbord-item border">
                                <div class="dashboard-content">
                                    <div class="left">
                                        <h6 class="title">{{ __("Active Journal") }}</h6>
                                        <div class="user-info">
                                            <h2 class="user-count">{{ count($journal_active) }}</h2>
                                        </div>
                                    </div>
                                    <div class="right">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                            <div class="dashbord-item border">
                                <div class="dashboard-content">
                                    <div class="left">
                                        <h6 class="title">{{ __("Deactive Journal") }}</h6>
                                        <div class="user-info">
                                            <h2 class="user-count">{{ count($journal_deactive) }}</h2>
                                        </div>
                                    </div>
                                    <div class="right">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="table-area mt-15">
        <div class="table-wrapper">
            
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>{{ __("Title") }}</th>
                            <th>{{ __("Description") }}</th>
                            <th>{{ __("Status") }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($journal ?? [] as $key => $item)
                            <tr data-item="{{ json_encode($item) }}">
                                <td>
                                    <ul class="user-list">
                                        <li><img src="{{ get_image($item->data->image ?? '','site-section') ?? '' }}" alt="image"></li>
                                    </ul>
                                </td>
                                <td> {{ Str::words($item->data->language->$app_local->title ?? '' , 6, '...') }} </td>
                                <td> {{ Str::words(strip_tags($item->data->language->$app_local->description) ?? '' , 10, '...') }} </td>
                                <td>
                                    @include('admin.components.form.switcher',[
                                        'name'          => 'status',
                                        'value'         => $item->status,
                                        'options'       => [__('Active') => 1,__('Deactive') => 0],
                                        'onload'        => true,
                                        'data_target'   => $item->id,
                                        'permission'    => "admin.setup.sections.journal.status.update",
                                    ])
                                </td>
                                <td>
                                    @include('admin.components.link.edit-default',[
                                        'href'          => setRoute('admin.setup.sections.journal.edit',$item->id),
                                        'class'         => "edit-modal-button",
                                        'permission'    => "admin.setup.sections.journal.edit",
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
    </div>
@endsection
@push('script')
    <script src="{{ asset('public/backend/js/fontawesome-iconpicker.js') }}"></script>
    <script>
        // icon picker
        $('.icp-auto').iconpicker();
    </script>
    <script>
        $(document).ready(function(){
            // Switcher
            switcherAjax("{{ setRoute('admin.setup.sections.journal.status.update',$slug) }}");
        })

        $(".delete-modal-button").click(function(){
            var oldData = JSON.parse($(this).parents("tr").attr("data-item"));

            var actionRoute =  "{{ setRoute('admin.setup.sections.journal.delete') }}";
            var target      = oldData.id;
            var message     = `{{ __("Are you sure to") }} <span>{{ __("delete") }}</span> {{ __("this journal?") }}`;

            openDeleteModal(actionRoute,target,message);
        });
        
    </script>
@endpush