<?php

namespace App\Http\Controllers\Api\V1\User;

use Exception;

use App\Models\Recipient;
use Illuminate\Http\Request;
use App\Constants\GlobalConst;
use App\Http\Helpers\Response;
use App\Models\Admin\Currency;
use App\Models\Admin\CashPickup;
use App\Models\Admin\MobileMethod;
use App\Http\Controllers\Controller;
use App\Models\Admin\RemittanceBank;
use App\Models\Admin\BankMethodAutomatic;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BeneficiaryController extends Controller
{
    /**
     * Method for show beneficiary data
     */
    public function index(Request $request){
        $validator           = Validator::make($request->all(),[
            'id'           => 'nullable',
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }
        $validated            = $validator->validate();
        if($request->id){

            $beneficiaries  = Recipient::where('id',$request->id)->get();
        }else{
            $beneficiaries  = Recipient::where('user_id',auth()->user()->id)->orderByDESC('id')->get()->map(function($data){
                return [
                    'id'                 => $data->id,
                    'user_id'            => $data->user_id ,
                    'first_name'         => $data->first_name,
                    'middle_name'        => $data->middle_name,
                    'last_name'          => $data->last_name,
                    'email'              => $data->email,
                    'country'            => $data->country,
                    'city'               => $data->city,
                    'state'              => $data->state,
                    'zip_code'           => $data->zip_code,
                    'phone'              => $data->phone,
                    'method'             => $data->method,
                    'mobile_name'        => $data->mobile_name ?? '',
                    'account_number'     => $data->account_number ?? '',
                    'bank_name'          => $data->bank_name ?? '',
                    'iban_number'        => $data->iban_number ?? '',
                    'address'            => $data->address ?? '',
                    'document_type'      => $data->document_type ?? '',
                    'front_image'        => $data->front_image ?? '',
                    'back_image'         => $data->back_image ?? '',
                    'created_at'         => $data->created_at ?? '',
                    'updated_at'         => $data->updated_at ?? '',
                ];
            });
        }
         
        return Response::success(['Beneficiary Data.'],[
            'beneficiaries'    => $beneficiaries,
        ],200);
    }
    /**
     * Method for receiver currency list
     */
    public function receiverCountry(){
        $receiver_currency    = Currency::where('status',true)->where('receiver',true)->get()->map(function($data){
            return [
                'id'           => $data->id,
                'country'      => $data->country,
                'name'         => $data->name,
                'code'         => $data->code,
            ];
        });

        return Response::success(['Receiver country data fetch successfully.'],[
            'receiver_country'  => $receiver_currency,
        ],200);
    }
    /**
     * Method for create beneficiary
     */
    public function create(Request $request){
        $validator      = Validator::make($request->all(),[
            'country'   => 'required'
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }
        $user_country       = Currency::where('country',$request->country)->where('receiver',true)->first();
        if(!$user_country) return Response::error([$request->country .' '.'is not receiver country']);
        $country            = get_specific_country($user_country->country);
        $bank_method_automatic = BankMethodAutomatic::where('status',true)->first();
        if(!$bank_method_automatic){
            $automatic_bank_list  = [];
        }else{
            $automatic_bank_list  = getFlutterwaveBanks($country['country_code']) ?? [];
        }
        $manual_bank_list   = RemittanceBank::where('country',$request->country)->get();
        if ($manual_bank_list->isNotEmpty()) {
            $manual_bank_list->each(function ($bank) {
                $bank->name = $bank->name . " (Manual)";
            });
    
            $manual_bank_list_array = $manual_bank_list->toArray();
        } else {
            $manual_bank_list_array = [];
        }
        $bank_list = array_merge($automatic_bank_list, $manual_bank_list_array);

        $mobile_methods       = MobileMethod::where('status',true)->where('country',$request->country)->get()->map(function($data){
            return [
                'id'                 => $data->id,
                'name'               => $data->name,
                'slug'               => $data->slug,
                'country'            => $data->country,
                'status'             => $data->status,
                'created_at'         => $data->created_at ?? '',
                'updated_at'         => $data->updated_at ?? '',
            ];
        });

        $cash_pickups       = CashPickup::where('country',$request->country)->where('status',true)->get()->map(function($data){
            return [
                'id'                 => $data->id,
                'address'               => $data->address,
                'slug'               => $data->slug,
                'country'            => $data->country,
                'status'             => $data->status,
                'created_at'         => $data->created_at ?? '',
                'updated_at'         => $data->updated_at ?? '',
            ];
        });
        
        return Response::success(['Bank & Mobile Data.'],[
            'banks'             => $bank_list,
            'mobile_methods'    => $mobile_methods,
            'cash_pickups'      => $cash_pickups,
        ],200);
    }
    /**
     * Method for store beneficiary information
     */
    public function store(Request $request){
        
        if($request->method == global_const()::BENEFICIARY_METHOD_BANK_TRANSAFER ){
            
            $validator      = Validator::make($request->all(),[
                'first_name'      => 'required|string',
                'middle_name'     => 'nullable|string',
                'last_name'       => 'required|string',
                'email'           => 'nullable|email',
                'country'         => 'required|string',
                'city'            => 'nullable|string',
                'state'           => 'nullable|string',
                'zip_code'        => 'nullable|string',
                'phone'           => 'nullable|string',
                'method'          => 'required|string',
                'bank_name'       => 'required|string',
                'iban_number'     => 'required',
                'address'         => 'nullable|string',
                'document_type'   => 'nullable|string',
                'front_image'     => 'nullable|mimes:png,jpg,webp,jpeg,svg',
                'back_image'      => 'nullable|mimes:png,jpg,webp,jpeg,svg',
            ]);
            if($validator->fails()){
                return Response::error($validator->errors()->all(),[]);
            }
            $validated   = $validator->validate();
            if($request->hasFile('front_image')){
                $image = upload_file($validated['front_image'],'junk-files');
                
                $upload_image = upload_files_from_path_dynamic([$image['dev_path']],'site-section');
                chmod(get_files_path('site-section') . '/' . $upload_image, 0644);
                $validated['front_image']     = $upload_image;
                
            }
            if($request->hasFile('back_image')){
                $back_image = upload_file($validated['back_image'],'junk-files');
                
                $back_upload_image = upload_files_from_path_dynamic([$back_image['dev_path']],'site-section');
                chmod(get_files_path('site-section') . '/' . $back_upload_image, 0644);
                $validated['back_image']     = $back_upload_image;
            }
            $validated['user_id'] = auth()->user()->id;
            if(Recipient::where('user_id',auth()->user()->id)->where('email',$validated['email'])->where('method',$validated['method'])->where('bank_name',$validated['bank_name'])->exists()){
                throw ValidationException::withMessages([
                    'name'  => "Recipient already exists!",
                ]);
            }
            $validated['user_id'] = auth()->user()->id;
            $validated['method'] = "Bank Transfer";
            try{
                $beneficiary  =  Recipient::create($validated);
            }catch(Exception $e){
                
                return Response::error(['Something went wrong! Please try again.'],[],404);
            }
            return Response::success(['Recipient Stored'],[
                'beneficiary'       => $beneficiary,
            ],200);
        }if($request->method == global_const()::TRANSACTION_TYPE_CASHPICKUP){
            $validator      = Validator::make($request->all(),[
                'first_name'      => 'required|string',
                'middle_name'     => 'nullable|string',
                'last_name'       => 'required|string',
                'email'           => 'nullable|email',
                'country'         => 'nullable|string',
                'city'            => 'nullable|string',
                'state'           => 'nullable|string',
                'zip_code'        => 'nullable|string',
                'phone'           => 'nullable|string',
                'method'          => 'required|string',
                'pickup_point'    => 'required|string',
                'address'         => 'nullable|string',
                'document_type'   => 'nullable|string',
                'front_image'     => 'nullable|mimes:png,jpg,webp,jpeg,svg',
                'back_image'      => 'nullable|mimes:png,jpg,webp,jpeg,svg',
            ]);
            if($validator->fails()){
                return Response::error($validator->errors()->all(),[]);
            }
            $validated   = $validator->validate();
            if($request->hasFile('front_image')){
                $image = upload_file($validated['front_image'],'junk-files');
                
                $upload_image = upload_files_from_path_dynamic([$image['dev_path']],'site-section');
                chmod(get_files_path('site-section') . '/' . $upload_image, 0644);
                $validated['front_image']     = $upload_image;

                
            }
            if($request->hasFile('back_image')){
                $back_image = upload_file($validated['back_image'],'junk-files');
                
                $back_upload_image = upload_files_from_path_dynamic([$back_image['dev_path']],'site-section');
                chmod(get_files_path('site-section') . '/' . $back_upload_image, 0644);
                $validated['back_image']     = $back_upload_image;
            }
            $validated['user_id'] = auth()->user()->id;
            if(Recipient::where('user_id',auth()->user()->id)->where('email',$validated['email'])->where('method',$validated['method'])->where('pickup_point',$validated['pickup_point'])->exists()){
                throw ValidationException::withMessages([
                    'name'  => "Recipient already exists!",
                ]);
            }
            $validated['user_id'] = auth()->user()->id;
            $validated['method'] = "Cash Pickup";
            try{
                $beneficiary  =  Recipient::create($validated);
            }catch(Exception $e){
                return Response::error(['Something went wrong! Please try again.'],[],404);
            }
            return Response::success(['Recipient Stored'],[
                'beneficiary'       => $beneficiary,
            ],200);
        }
        if($request->method == global_const()::TRANSACTION_TYPE_MOBILE){
            $validator      = Validator::make($request->all(),[
                'first_name'      => 'required|string',
                'middle_name'     => 'nullable|string',
                'last_name'       => 'required|string',
                'email'           => 'nullable|email',
                'country'         => 'required|string',
                'city'            => 'nullable|string',
                'state'           => 'nullable|string',
                'zip_code'        => 'nullable|string',
                'phone'           => 'nullable|string',
                'method'          => 'required|string',
                'mobile_name'     => 'required|string',
                'account_number'  => 'required|string',
                'address'         => 'nullable|string',
                'document_type'   => 'nullable|string',
                'front_image'     => 'nullable|mimes:png,jpg,webp,jpeg,svg',
                'back_image'      => 'nullable|mimes:png,jpg,webp,jpeg,svg',
            ]);
            if($validator->fails()){
                return Response::error($validator->errors()->all(),[]);
            }
            $validated   = $validator->validate();
            if($request->hasFile('front_image')){
                $image = upload_file($validated['front_image'],'junk-files');
                
                $upload_image = upload_files_from_path_dynamic([$image['dev_path']],'site-section');
                chmod(get_files_path('site-section') . '/' . $upload_image, 0644);
                $validated['front_image']     = $upload_image;

            }
            if($request->hasFile('back_image')){
                $back_image = upload_file($validated['back_image'],'junk-files');
                
                $back_upload_image = upload_files_from_path_dynamic([$back_image['dev_path']],'site-section');
                chmod(get_files_path('site-section') . '/' . $back_upload_image, 0644);
                $validated['back_image']     = $back_upload_image;
            }
            $validated['user_id'] = auth()->user()->id;
            if(Recipient::where('user_id',auth()->user()->id)->where('email',$validated['email'])->where('method',$validated['method'])->where('mobile_name',$validated['mobile_name'])->exists()){
                throw ValidationException::withMessages([
                    'name'  => "Recipient already exists!",
                ]);
            }
            $validated['user_id'] = auth()->user()->id;
            $validated['method'] = "Mobile Money";
            try{
                $beneficiary  =  Recipient::create($validated);
            }catch(Exception $e){
                return Response::error(['Something went wrong! Please try again.'],[],404);
            }
            return Response::success(['Recipient Stored'],[
                'beneficiary'       => $beneficiary,
            ],200);
        }
        return Response::error(['Something went wrong! Please try again.'],[],404);
    }
    /**
     * Method edit
     * 
     */
    
    /**
     * Method for update Beneficiary Data
     */
    public function update(Request $request){
        
        if($request->method == global_const()::BENEFICIARY_METHOD_BANK_TRANSAFER ){
            
            $validator      = Validator::make($request->all(),[
                'id'              => 'required|integer',
                'first_name'      => 'required|string',
                'middle_name'     => 'nullable|string',
                'last_name'       => 'required|string',
                'email'           => 'nullable|email',
                'country'         => 'required|string',
                'city'            => 'nullable|string',
                'state'           => 'nullable|string',
                'zip_code'        => 'nullable|string',
                'phone'           => 'nullable|string',
                'method'          => 'required|string',
                'bank_name'       => 'required|string',
                'iban_number'     => 'required',
                'address'         => 'nullable|string',
                'document_type'   => 'nullable|string',
                'front_image'     => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
                'back_image'      => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
            ]);
            if($validator->fails()){
                return Response::error($validator->errors()->all(),[]);
            }
            $validated   = $validator->validate();
            
            $validated['user_id'] = auth()->user()->id;
            $validated['method'] = "Bank Transfer";
            $beneficiary    = Recipient::where('id',$request->id)->first();
            if($request->hasFile('front_image')){
                $image = upload_file($validated['front_image'],'junk-files',$beneficiary->front_image);
                $upload_image = upload_files_from_path_dynamic([$image['dev_path']],'site-section');
                delete_file($image['dev_path']);
                chmod(get_files_path('site-section') . '/' . $upload_image, 0644);
                $validated['front_image']     = $upload_image;
            }
            if($request->hasFile('back_image')){
                $back_image = upload_file($validated['back_image'],'junk-files',$beneficiary->back_image);
                $back_upload_image = upload_files_from_path_dynamic([$back_image['dev_path']],'site-section');
                delete_file($back_image['dev_path']);
                chmod(get_files_path('site-section') . '/' . $back_upload_image, 0644);
                $validated['back_image']     = $back_upload_image;
            }
            if(Recipient::where('user_id',auth()->user()->id)->where('email',$validated['email'])->where('method',$validated['method'])->where('bank_name',$validated['bank_name'])->exists()){
                throw ValidationException::withMessages([
                    'name'  => "Recipient already exists!",
                ]);
            }
            try{
                $beneficiary->update($validated);
            }catch(Exception $e){
                return Response::error(['Something went wrong! Please try again.'],[],404);
            }
            return Response::success(['Recipient Data Updated Successfully.'],[
                'beneficiary'       => $beneficiary,
            ],200);;
        }if($request->method == global_const()::TRANSACTION_TYPE_CASHPICKUP){
            $validator      = Validator::make($request->all(),[
                'id'              => 'required|integer',
                'first_name'      => 'required|string',
                'middle_name'     => 'nullable|string',
                'last_name'       => 'required|string',
                'email'           => 'nullable|email',
                'country'         => 'required|string',
                'city'            => 'nullable|string',
                'state'           => 'nullable|string',
                'zip_code'        => 'nullable|string',
                'phone'           => 'nullable|string',
                'method'          => 'required|string',
                'pickup_point'    => 'required|string',
                'address'         => 'nullable|string',
                'document_type'   => 'nullable|string',
                'front_image'     => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
                'back_image'      => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
            ]);
            if($validator->fails()){
                return Response::error($validator->errors()->all(),[]);
            }
            $validated   = $validator->validate();
            
            $validated['user_id'] = auth()->user()->id;
            $beneficiary    = Recipient::where('id',$request->id)->first();
            if($request->hasFile('front_image')){

                $image = upload_file($validated['front_image'],'junk-files',$beneficiary->front_image);
                $upload_image = upload_files_from_path_dynamic([$image['dev_path']],'site-section');
                delete_file($image['dev_path']);
                chmod(get_files_path('site-section') . '/' . $upload_image, 0644);
                $validated['front_image']     = $upload_image;
            }
            if($request->hasFile('back_image')){
                $back_image = upload_file($validated['back_image'],'junk-files',$beneficiary->back_image);
                $back_upload_image = upload_files_from_path_dynamic([$back_image['dev_path']],'site-section');
                delete_file($back_image['dev_path']);
                chmod(get_files_path('site-section') . '/' . $back_upload_image, 0644);
                $validated['back_image']     = $back_upload_image;
            }
            if(Recipient::where('user_id',auth()->user()->id)->where('email',$validated['email'])->where('method',$validated['method'])->where('pickup_point',$validated['pickup_point'])->exists()){
                throw ValidationException::withMessages([
                    'name'  => "Recipient already exists!",
                ]);
            }
            $validated['user_id'] = auth()->user()->id;
            $validated['method'] = GlobalConst::TRANSACTION_TYPE_CASHPICKUP;
            try{
                $beneficiary->update($validated);
            }catch(Exception $e){
                return Response::error(['Something went wrong! Please try again.'],[],404);
            }
            return Response::success(['Recipient Data Updated Successfully.'],[
                'beneficiary'       => $beneficiary,
            ],200);;
        }
        if($request->method == global_const()::TRANSACTION_TYPE_MOBILE){
            $validator      = Validator::make($request->all(),[
                'id'              => 'required|integer',
                'first_name'      => 'required|string',
                'middle_name'     => 'nullable|string',
                'last_name'       => 'required|string',
                'email'           => 'nullable|email',
                'country'         => 'nullable|string',
                'city'            => 'nullable|string',
                'state'           => 'nullable|string',
                'zip_code'        => 'nullable|string',
                'phone'           => 'nullable|string',
                'method'          => 'required|string',
                'mobile_name'     => 'required|string',
                'account_number'  => 'required|string',
                'address'         => 'nullable|string',
                'document_type'   => 'nullable|string',
                'front_image'     => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
                'back_image'      => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
            ]);
            if($validator->fails()){
                return Response::error($validator->errors()->all(),[]);
            }
            $validated            = $validator->validate();
            $validated['user_id'] = auth()->user()->id;
            $beneficiary          = Recipient::where('id',$request->id)->first();
            if($request->hasFile('front_image')){

                $image = upload_file($validated['front_image'],'junk-files',$beneficiary->front_image);
                $upload_image = upload_files_from_path_dynamic([$image['dev_path']],'site-section');
                delete_file($image['dev_path']);
                chmod(get_files_path('site-section') . '/' . $upload_image, 0644);
                $validated['front_image']     = $upload_image;
            }
            if($request->hasFile('back_image')){
                $back_image = upload_file($validated['back_image'],'junk-files',$beneficiary->back_image);
                $back_upload_image = upload_files_from_path_dynamic([$back_image['dev_path']],'site-section');
                delete_file($back_image['dev_path']);
                chmod(get_files_path('site-section') . '/' . $back_upload_image, 0644);
                $validated['back_image']     = $back_upload_image;
            }
            if(Recipient::where('user_id',auth()->user()->id)->where('email',$validated['email'])->where('method',$validated['method'])->where('mobile_name',$validated['mobile_name'])->exists()){
                throw ValidationException::withMessages([
                    'name'  => "Recipient already exists!",
                ]);
            }
            try{
                $beneficiary->update($validated);
            }catch(Exception $e){
                return Response::error(['Something went wrong! Please try again.'],[],404);
            }
            return Response::success(['Recipient Data Updated Successfully.'],[
                'beneficiary'       => $beneficiary,
            ],200);;
        }
        return Response::error(['Something went wrong! Please try again.'],[],404);
    }
    /**
     * Method for delete beneficiary data
     */
    public function delete(Request $request){
        $validator      = Validator::make($request->all(),[
            'id'              => 'required|integer',
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }
        $beneficiary  = Recipient::where('id',$request->id)->first();
        try{
            $beneficiary->delete();
        }catch(Exception $e){
            return Response::error(['Something went wrong! Please try again.'],[],404);
        }
        return Response::success(['Recipient data deleted successfully.'],[],200);
    }
}
