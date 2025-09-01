@extends('user.layouts.master')

@push('css')

@endpush

@section('breadcrumb')
    @include('user.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("user.dashboard"),
        ]
    ], 'active' => __("Support Tickets")])
@endsection

@section('content')
<div class="body-wrapper">
    <div class="row mb-20-none">
        <div class="col-xl-12 col-lg-12 mb-20">
            <div class="custom-card mt-10">
                <div class="dashboard-header-wrapper">
                    <h4 class="title">{{ __("Add New Ticket") }}</h4>
                </div>
                <div class="card-body">
                    <form class="card-form" action="{{ route('user.support.ticket.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 form-group">
                                @include('admin.components.form.input',[
                                    'label'         => __("Name")."<span>*</span>",
                                    'name'          => "name",
                                    'attribute'     => "readonly",
                                    'placeholder'   => __("Enter Name")."...",
                                    'value'         => old('name',auth()->user()->full_name)
                                ])
                            </div>
                            <div class="col-xl-6 col-lg-6 form-group">
                                @include('admin.components.form.input',[
                                    'label'         => __("Email")."<span>*</span>",
                                    'type'          => "email",
                                    'name'          => "email",
                                    'attribute'     => "readonly",
                                    'placeholder'   => __("Enter Email")."...",
                                    'value'         => old('email',auth()->user()->email)
                                ])
                            </div>
                            <div class="col-xl-12 col-lg-12 form-group">
                                @include('admin.components.form.input',[
                                    'label'         => __("Subject")."<span>*</span>",
                                    'name'          => "subject",
                                    'placeholder'   => __("Enter Subject")."...",
                                ])
                            </div>
                            <div class="col-xl-12 col-lg-12 form-group">
                                @include('admin.components.form.textarea',[
                                    'label'         => __('Message').'<span class="text--base">'.'('.__("Optional").')'.'</span>',
                                    'name'          => "desc",
                                    'placeholder'   => __("Write Here").'...',
                                ])
                            </div>
                            <div class="col-xl-12 col-lg-12 form-group">
                                @include('admin.components.form.input-file',[
                                    'label'         => __("Attachments")."<span>*</span>",
                                    'name'          => "attachment[]",
                                    'class'         => "file-holder",
                                    'attribute'     => "multiple"
                                ])
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12">
                            <button type="submit" class="btn--base w-100">{{ __("Add New") }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>

    </script>
@endpush
