@if (admin_permission_by_name("admin.sending.purpose.store"))
    <div id="add-sending-purpose" class="mfp-hide large">
        <div class="modal-data">
            <div class="modal-header px-0">
                <h5 class="modal-title">{{ __("Add Sending Purpose") }}</h5>
            </div>
            <div class="modal-form-data">
                <form class="card-form" action="{{ setRoute('admin.sending.purpose.store') }}" method="POST">
                    @csrf
                    <div class="row mb-10-none">
                        <div class="col-xl-12 col-lg-12 form-group">
                            @include('admin.components.form.input',[
                                'label'         => __("Name")."*",
                                'name'          => "name",
                                'data_limit'    => 150,
                                'placeholder'   => __("Write Name")."...",
                                'value'         => old('name'),
                            ])
                        </div>
                        <div class="col-xl-12 col-lg-12 form-group">
                            @include('admin.components.button.form-btn',[
                                'class'         => "w-100 btn-loading",
                                'permission'    => "admin.sending.purpose.store",
                                'text'          => __("Add"),
                            ])
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif