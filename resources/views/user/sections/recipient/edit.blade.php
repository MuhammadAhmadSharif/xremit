@extends('user.layouts.master')

@section('breadcrumb')
    @include('user.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("user.dashboard"),
        ],
        [
            'name'  => __("Recipients"),
            'url'   => setRoute("user.recipient.show"),
        ]
    ], 'active' => __("Edit Recipient")])
@endsection


@section('content')

<div class="body-wrapper">
    <div class="row mb-20-none">
        <div class="col-xl-12 col-lg-12 mb-20">
            <div class="custom-card mt-10">
                <div class="dashboard-header-wrapper">
                    <h4 class="title">{{ __("Edit Recipient") }}</h4>
                </div>
                <div class="card-body">
                    <form class="card-form add-recipient-item" action="{{ setRoute('user.recipient.data.update',$recipient->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="trx-inputs bt-view" style="display: block;">
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-6 form-group">
                                    @include('admin.components.form.input',[
                                        'label'           => __('First Name').'*',
                                        'type'            => 'text',
                                        'name'            => 'first_name',
                                        'value'           => old('first_name',$recipient->first_name),
                                        'placeholder'     => __("Enter First Name")."..."
                                    ])
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 form-group">
                                    @include('admin.components.form.input',[
                                        'label'           => __('Middle Name'),
                                        'type'            => 'text',
                                        'name'            => 'middle_name',
                                        'value'           => old('middle_name',$recipient->middle_name),
                                        'placeholder'     => __("Enter Middle Name")."..."
                                    ])
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 form-group">
                                    @include('admin.components.form.input',[
                                        'label'           => __('Last Name').'*',
                                        'type'            => 'text',
                                        'name'            => 'last_name',
                                        'value'           => old('last_name',$recipient->last_name),
                                        'placeholder'     => __("Enter Last Name")."..."
                                    ])
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 form-group">
                                    @include('admin.components.form.input',[
                                        'label'           => __('Email'),
                                        'type'            => 'email',
                                        'name'            => 'email',
                                        'value'           => old('email',$recipient->email),
                                        'placeholder'     => __("Enter Email")."..."
                                    ])
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 form-group">
                                    <label for="country">{{ __("Country") }}</label>
                                    <select class="form--control select2-basic" name="country">
                                        @foreach ($receiver_currency as $item)
                                            <option value="{{ $item->country }}" @if ($item->country == $recipient->country) selected @endif>{{ $item->country }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 form-group">
                                    @include('admin.components.form.input',[
                                        'label'           => __('City'),
                                        'type'            => 'text',
                                        'name'            => 'city',
                                        'value'           => old('city',$recipient->city),
                                        'placeholder'     => __("Enter City")."..."
                                    ])
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 form-group">
                                    @include('admin.components.form.input',[
                                        'label'           => __('State'),
                                        'type'            => 'text',
                                        'name'            => 'state',
                                        'value'           => old('state',$recipient->state),
                                        'placeholder'     => __("Enter State")."..."
                                    ])
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 form-group">
                                    @include('admin.components.form.input',[
                                        'label'           => __('Zip Code'),
                                        'type'            => 'text',
                                        'name'            => 'zip_code',
                                        'value'           => old('zip_code',$recipient->zip_code),
                                        'placeholder'     => __("Enter Zip Code")."..."
                                    ])
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 form-group">
                                    @include('admin.components.form.input',[
                                        'label'           => __('Phone'),
                                        'type'            => 'number',
                                        'name'            => 'phone',
                                        'value'           => old('phone',$recipient->phone),
                                        'placeholder'     => __("Enter Phone")."..."
                                    ])
                                </div>
                                @if ($recipient->method == global_const()::BENEFICIARY_METHOD_BANK_TRANSAFER)
                                    <div class="form-group transaction-type">
                                        <label>{{ __("Transaction Type") }} <span>*</span></label>
                                        <select class="form--control trx-type-select select2-basic" name="method">
                                            <option value="{{ global_const()::RECIPIENT_METHOD_BANK }}"   @if(global_const()::BENEFICIARY_METHOD_BANK_TRANSAFER == $recipient->method) selected @endif>{{ global_const()::TRANSACTION_TYPE_BANK }}</option>
                                            
                                        </select>
                                    </div>
                                    <div class="trx-inputs {{ global_const()::RECIPIENT_METHOD_BANK }}-view" @if(global_const()::BENEFICIARY_METHOD_BANK_TRANSAFER == $recipient->method) style="display: block;" @else style="display: none;" @endif>
                                        <div class="row">
                                            <div class="col-xl-6 col-lg-6 col-md-6 form-group">
                                                <label>{{ __("Bank Name") }}*</label>
                                                <select class="form--control select2-basic bank-list" name="bank_name">
                                                    
                                                </select>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 col-md-6 form-group">
                                                @include('admin.components.form.input',[
                                                    'label'           => __('IBAN Number').'*',
                                                    'type'            => 'text',
                                                    'name'            => 'iban_number',
                                                    'value'           => old('iban_number',$recipient->iban_number),
                                                    'placeholder'     => __("Enter IBAN Number")."..."
                                                ])
                                            </div>
                                        </div>
                                    </div>
                                @elseif ($recipient->method == global_const()::TRANSACTION_TYPE_MOBILE)
                                    <div class="form-group transaction-type">
                                        <label>{{ __("Transaction Type") }} <span>*</span></label>
                                        <select class="form--control trx-type-select select2-basic" name="method">
                                            
                                            <option value="{{ global_const()::RECIPIENT_METHOD_MOBILE }}" @if(global_const()::TRANSACTION_TYPE_MOBILE == $recipient->method) selected @endif>{{ global_const()::TRANSACTION_TYPE_MOBILE }}</option>
                                        </select>
                                    </div>
                                    <div class="trx-inputs {{ global_const()::RECIPIENT_METHOD_MOBILE }}-view" @if(global_const()::TRANSACTION_TYPE_MOBILE == $recipient->method) style="display: block;" @else style="display: none;" @endif >
                                        <div class="row">
                                            <div class="col-xl-6 col-lg-6 col-md-6 form-group">
                                                <label>{{ __("Mobile Method") }}<span>*</span></label>
                                                <select class="form--control select2-basic mobile-list" name="mobile_name">
                                                    
                                                </select>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 col-md-6 form-group">
                                                <label>{{ __("Account Number") }}<span>*</span></label>
                                                <input type="number" class="form--control" name="account_number" value="{{ $recipient->account_number }}" placeholder="{{ __("Enter Number") }}...">
                                            </div>
                                        </div>
                                    </div>
                                @else
                                <div class="form-group transaction-type">
                                    <label>{{ __("Transaction Type") }} <span>*</span></label>
                                    <select class="form--control trx-type-select select2-basic" name="method">
                                        
                                        <option value="{{ global_const()::RECIPIENT_METHOD_CASH }}" @if(global_const()::TRANSACTION_TYPE_CASHPICKUP == $recipient->method) selected @endif>{{ global_const()::TRANSACTION_TYPE_CASHPICKUP }}</option>
                                    </select>
                                </div>
                                <div class="trx-inputs {{ global_const()::RECIPIENT_METHOD_CASH }}-view" @if(global_const()::TRANSACTION_TYPE_CASHPICKUP == $recipient->method) style="display: block;" @else style="display: none;" @endif >
                                    <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12 form-group">
                                            <label>{{ __("Pickup Point") }}<span>*</span></label>
                                            <select class="form--control select2-basic pickup-list" name="pickup_point">
                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="col-xl-12 col-lg-12 form-group">
                                    @include('admin.components.form.textarea',[
                                        'label'           => __('Address'),
                                        'name'            => 'address',
                                        'value'           => old('address',$recipient->address),
                                        'placeholder'     => __('Write Here').'...'
                                    ])
                                </div>
                                <div class="document-id ptb-30">
                                    <div class="input-document">
                                        <div class="row">
                                            <div class="col-lg-4 pb-20">
                                                <label class="title">{{ __("Document type") }}</label>
                                                <select class="nice-select" name="document_type">
                                                    <option selected disabled value="">{{ __("Select Document Type") }}</option>
                                                    <option value="{{ global_const()::DOCUMENT_TYPE_NID }}" @if($recipient->document_type == global_const()::DOCUMENT_TYPE_NID) selected @endif>{{ global_const()::DOCUMENT_TYPE_NID }}</option>
                                                    <option value="{{ global_const()::DOCUMENT_TYPE_DRIVING_LICENCE }}" @if($recipient->document_type == global_const()::DOCUMENT_TYPE_DRIVING_LICENCE) selected @endif>{{ global_const()::DOCUMENT_TYPE_DRIVING_LICENCE }}</option>
                                                    <option value="{{ global_const()::DOCUMENT_TYPE_PASSPORT }}" @if($recipient->document_type == global_const()::DOCUMENT_TYPE_PASSPORT) selected @endif>{{ global_const()::DOCUMENT_TYPE_PASSPORT }}</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                
                                                <div class="file-holder-wrapper">
                                                    @include('admin.components.form.input-file',[
                                                        'label'             => __("Front Part"),
                                                        'name'              => "front_image",
                                                        'class'             => "file-holder",
                                                        'old_files_path'    => files_asset_path("site-section"),
                                                        'old_files'         => old("front_image",$recipient->front_image),
                                                    ])
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                
                                                <div class="file-holder-wrapper">
                                                    @include('admin.components.form.input-file',[
                                                        'label'             => __("Back Part"),
                                                        'name'              => "back_image",
                                                        'class'             => "file-holder",
                                                        'old_files_path'    => files_asset_path("site-section"),
                                                        'old_files'         => old("back_image",$recipient->back_image),
                                                    ])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12">
                            <button type="submit" class="btn--base w-100">{{ __("Update") }}</button>
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
    $(".trx-type-select").change(function () {
        var recipientsWrapper = $(".add-recipient-item");
        var inputItems = recipientsWrapper.find("input,select,textarea");
        var selectValue = $(this).val();
        $.each(inputItems, function (index, item) {
            if (selectValue == "" || selectValue == null) {
                $(item).prop("readonly", true);
            } else {
                $(item).prop("readonly", false);
            }
        });
        if (selectValue != "") {
            $(this).parents(".transaction-type").siblings(".trx-inputs").slideUp();
            $(this).parents(".transaction-type").siblings("." + $(this).val() + "-view").slideDown();
        }
    });
</script>
<script>
    $(document).ready(function () {
        var country = $("select[name=country] :selected").val();
        var transactionType = $("select[name=method] :selected").val();
        if(transactionType == 'Bank'){
            getBankList(country,transactionType);
        }else if(transactionType == 'Mobile'){
            getMobileList(country,transactionType);
        }else{
            getCashPickupPoint(country,transactionType);
        }   
    });
    $("select[name=country]").change(function(){
        var country = $(this).val();
        var transactionType = $("select[name=method] :selected").val();
        if(transactionType == 'Bank'){
            getBankList(country,transactionType);
        }else if(transactionType == 'Mobile'){
            getMobileList(country,transactionType);
        }else{
            getCashPickupPoint(country,transactionType);
        }       
    });
    $("select[name=method]").change(function(){
        var country = $("select[name=country] :selected").val();
        var transactionType = $(this).val();
        
        $(".bank-list").html('');
        $(".mobile-list").html('');
        $(".pickup-list").html('');
        if(transactionType == 'Bank'){
            getBankList(country,transactionType);
        }else if(transactionType == 'Mobile'){
            getMobileList(country,transactionType);
        }else{
            getCashPickupPoint(country,transactionType);
        }  
    });
    function getBankList(country,transactionType){
        var bankListUrl = "{{ route('user.recipient.bank.list') }}";
        $(".bank-list").html('');
        $.post(bankListUrl,{country:country,_token:"{{ csrf_token() }}"},function(response){

            if(response.data.bank_list == null || response.data.bank_list == ''){
                $('.bank-list').html('<option value="" disabled>No Bank Aviliable</option>');
            }else{
                $('.bank-list').html('<option value="" disabled>Select Bank</option>');
            }
            
            $.each(response.data.bank_list, function (key, value) { 
                var bank_name   = "{{ $recipient->bank_name }}";
                var selectedAttribute = (bank_name === value.name) ? 'selected' : ''; 
                $(".bank-list").append('<option value="' + value.name + '" ' + selectedAttribute + '>' + value.name + '</option>');
            });
        });
    }
    function getMobileList(country,transactionType){
        var getMobileMethod = "{{ setRoute('user.get.mobile.method') }}";

        $(".mobile-list").html('');
        $.post(getMobileMethod,{country:country,_token:"{{ csrf_token() }}"},function(response){
            if(response.data.country == null || response.data.country == ''){
                $('.mobile-list').html('<option value="" disabled>No Mobile Method Aviliable</option>');
            }else{
                $('.mobile-list').html('<option value="" disabled>Select Mobile Method</option>');
            }
            $.each(response.data.country,function(index,item){
                var mobile_name   = "{{ $recipient->mobile_name }}";
                var selectedAttribute = (mobile_name === item.name) ? 'selected' : ''; 
                $(".mobile-list").append('<option value="' + item.name + '" ' + selectedAttribute + '>' + item.name + '</option>');
            });
            
        });
    }
    function getCashPickupPoint(country,transactionType){
        var pickupURL = "{{ setRoute('user.get.pickup.points') }}";
        
        $(".pickup-list").html('');
        $.post(pickupURL,{country:country,_token:"{{ csrf_token() }}"},function(response){
            if(response.data.country == null || response.data.country == ''){
                $('.pickup-list').html('<option value="" disabled>No Pickup Points Aviliable</option>');
            }else{
                $('.pickup-list').html('<option value="" disabled>Select Pickup Points</option>');
            }
            $.each(response.data.country,function(index,item){
                var pickupPoint   = "{{ $recipient->pickup_point }}";
                var selectedAttribute = (pickupPoint === item.address) ? 'selected' : ''; 
                $(".pickup-list").append('<option value="' + item.address + '" ' + selectedAttribute + ' >' + item.address + '</option>');
            });
            
        });
    }
    
</script>

    
@endpush