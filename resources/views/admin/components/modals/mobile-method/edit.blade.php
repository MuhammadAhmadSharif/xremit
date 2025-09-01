@if (admin_permission_by_name("admin.mobile.method.update"))
    <div id="edit-mobile-method" class="mfp-hide large">
        <div class="modal-data">
            <div class="modal-header px-0">
                <h5 class="modal-title">{{ __("Edit Mobile Method") }}</h5>
            </div>
            <div class="modal-form-data">
                <form class="modal-form" method="POST" action="{{ setRoute('admin.mobile.method.update') }}">
                    @csrf
                    @method("PUT")
                    <input type="hidden" name="target" value="{{ old('target') }}">
                    <div class="row mb-10-none mt-2">
                        <div class="col-xl-12 col-lg-12 form-group">
                            <label>{{__("Country")}}<span>*</span></label>
                            <select class="form--control select2-basic" name="edit_country">
                                @foreach ($receiver_currency as $item)
                                    <option value="{{ $item->country }}" >{{ $item->country }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-12 col-lg-12 form-group">
                            @include('admin.components.form.input',[
                                'label'         => __("Name")."*",
                                'name'          => 'edit_name',
                                'value'         => old('edit_name')
                            ])
                        </div>
                        <div class="col-xl-12 col-lg-12 form-group d-flex align-items-center justify-content-between mt-4">
                            <button type="button" class="btn btn--danger modal-close">{{ __("Cancel") }}</button>
                            <button type="submit" class="btn btn--base">{{ __("Update") }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push("script")
        <script>
            
            openModalWhenError("edit-mobile-method","#edit-mobile-method");
            $(".edit-modal-button").click(function(){
                var oldData = JSON.parse($(this).parents("tr").attr("data-item"));
                var editModal = $("#edit-mobile-method");

                editModal.find("form").first().find("input[name=target]").val(oldData.id);
                editModal.find("input[name=edit_name]").val(oldData.name);
                editModal.find("select[name=edit_country] option").each(function() {
                    if ($(this).val() === oldData.country) {
                        $(this).prop('selected', true);
                    }
                });
                editModal.find("select[name=edit_country]").trigger('change');
                
                openModalBySelector("#edit-mobile-method");
            });
        </script>
    @endpush
@endif