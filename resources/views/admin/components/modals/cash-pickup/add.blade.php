@if (admin_permission_by_name("admin.cash.pickup.method.store"))
    <div id="add-cash-pickup" class="mfp-hide large">
        <div class="modal-data">
            <div class="modal-header px-0">
                <h5 class="modal-title">{{ __("Add Pickup Points Manual") }}</h5>
            </div>
            <div class="modal-form-data">
                <form class="card-form" action="{{ setRoute('admin.cash.pickup.method.store') }}" method="POST">
                    @csrf
                    <div class="row mb-10-none">
                        <div class="col-xl-12 col-lg-12 form-group">
                            <label>{{__("Country")}}<span>*</span></label>
                            <select class="form--control select2-auto-tokenize" name="country">
                                <option selected disabled>{{ __("Select Country") }}</option>
                                @foreach ($receiver_currency as $item)
                                    <option value="{{ $item->country }}">{{ $item->country }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-12 col-lg-12 form-group">
                            @include('admin.components.form.input',[
                                'label'         => __("Address")."*",
                                'name'          => "address",
                                'data_limit'    => 150,
                                'placeholder'   => __("Write Address")."...",
                                'value'         => old('address'),
                            ])
                        </div>
                        <div class="col-xl-12 col-lg-12 form-group">
                            @include('admin.components.button.form-btn',[
                                'class'         => "w-100 btn-loading",
                                'permission'    => "admin.cash.pickup.method.store",
                                'text'          => __("Add"),
                            ])
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@push('script')
    <script>
        getAllCountries("{{ setRoute('global.countries') }}",$(".country-select"));
            $(document).ready(function(){

                $(".country-select").select2();

                $("select[name=country]").change(function(){
                    var phoneCode = $("select[name=country] :selected").attr("data-mobile-code");
                    placePhoneCode(phoneCode);
                });

                setTimeout(() => {
                    var phoneCodeOnload = $("select[name=country] :selected").attr("data-mobile-code");
                    placePhoneCode(phoneCodeOnload);
                }, 400);
            });
    </script>
@endpush