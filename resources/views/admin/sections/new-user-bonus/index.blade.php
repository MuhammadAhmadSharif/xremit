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
    <div class="custom-card mb-2">
        <div class="card-header">
            <h6 class="title">{{ __($page_title) }}</h6>
        </div>
        <div class="card-body">
            <form class="card-form" action="{{ setRoute('admin.new.user.bonus.update') }}" method="POST">
                @csrf
                @method("PUT")
                <input type="hidden" name="slug" value="{{ global_const()::NEW_USER_BONUS }}">
                <div class="row mb-10-none">
                    <div class="d-flex">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 form-group">
                            <label>{{ __("Price") }}*</label>
                            <input type="text" class="form--control" name="price" placeholder="{{ __("Enter Price") }}" value="{{ isset($bonus) ? get_amount($bonus->price) : old('price') }}">
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 form-group ms-1 me-1">
                            <label>{{ __("Maximum Limit") }}*</label>
                            <input type="text" class="form--control" name="max_used" placeholder="{{ __("Enter Maximum Limit") }}" value="{{ old('max_used',@$bonus->max_used) }}">
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 form-group">
                            @include('admin.components.form.switcher',[
                                'label'       => __('Status'),
                                'name'        => 'status',
                                'value'       => @$bonus->status,
                                'options'     => [__('Enable') => 1, __('Disable') => 0],
                                'onload'      => true,
                                'data_target' => @$bonus->id,
                            ])
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 form-group">
                        @include('admin.components.button.form-btn',[
                            'class'         => "w-100 btn-loading",
                            'text'          => __("Update"),
                        ])
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('script')
<script>
    $(document).ready(function(){
        switcherAjax("{{ setRoute('admin.new.user.bonus.status.update') }}");
    })
</script>    
@endpush