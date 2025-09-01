<?php

namespace App\Http\Controllers\Api\V1\User;

use Exception;
use Carbon\Carbon;
use App\Models\Recipient;
use App\Models\UserCoupon;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\Admin\Coupon;
use Illuminate\Http\Request;
use App\Models\AppliedCoupon;
use App\Models\TemporaryData;
use App\Constants\GlobalConst;
use App\Http\Helpers\Response;
use App\Models\Admin\Currency;
use App\Models\Admin\CashPickup;
use App\Models\CouponTransaction;
use App\Models\Admin\MobileMethod;

use App\Models\Admin\SourceOfFund;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\BasicSettings;

use App\Traits\PaymentGateway\Gpay;
use App\Http\Controllers\Controller;
use App\Models\Admin\RemittanceBank;
use App\Models\Admin\SendingPurpose;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Constants\PaymentGatewayConst;
use App\Models\Admin\AdminNotification;
use App\Models\Admin\CryptoTransaction;
use App\Models\Admin\TransactionSetting;
use App\Traits\ControlDynamicInputFields;
use Illuminate\Support\Facades\Validator;
use App\Http\Helpers\PushNotificationHelper;
use App\Models\Admin\PaymentGatewayCurrency;
use Illuminate\Support\Facades\Notification;
use App\Notifications\manualEmailNotification;
use App\Traits\PaymentGateway\PaystackGateway;
use Illuminate\Validation\ValidationException;
use App\Http\Helpers\PaymentGateway as PaymentGatewayHelper;

class SendRemittanceController extends Controller
{
    use ControlDynamicInputFields,PaystackGateway;
    /**
     * Method for get the transaction type data
     */
    public function index(Request $request){

        $transaction_type  = TransactionSetting::where('status',true)->whereIn('slug', [
            GlobalConst::BANK_TRANSFER,
            GlobalConst::MOBILE_MONEY,
            GlobalConst::CASH_PICKUP
        ])->get();
        if($transaction_type->isEmpty()) {
            $transaction_first = TransactionSetting::first();
        }
        $sender_currency      = Currency::where('status',true)->where('sender',true)->get()->map(function($data){
            return [
                'id'           => $data->id,
                'admin_id'     => $data->admin_id,
                'country'      => $data->country,
                'name'         => $data->name,
                'code'         => $data->code,
                'symbol'       => $data->code,
                'type'         => $data->type,
                'flag'         => $data->flag,
                'rate'         => $data->rate,
                'sender'       => $data->sender,
                'receiver'     => $data->receiver,
            ];
        });
        $receiver_currency    = Currency::where('status',true)->where('receiver',true)->get()->map(function($data){
            return [
                'id'           => $data->id,
                'admin_id'     => $data->admin_id,
                'country'      => $data->country,
                'name'         => $data->name,
                'code'         => $data->code,
                'symbol'       => $data->code,
                'type'         => $data->type,
                'flag'         => $data->flag,
                'rate'         => $data->rate,
                'sender'       => $data->sender,
                'receiver'     => $data->receiver,
            ];
        });

        //counpons
        $coupons    = Coupon::get()->map(function($data){
            $coupon_transactions    = CouponTransaction::where('coupon_id',$data->id)->count();
            $remaining = @$data->max_used - @$coupon_transactions;
            return [
                'id'                => $data->id,
                'coupon_type'       => GlobalConst::COUPON,
                'name'              => $data->name,
                'price'             => $data->price,
                'max_limit'         => $data->max_used,
                'remaining'         => $remaining
            ];
        });
        $image_paths = [
            'base_url'         => url("/"),
            'path_location'    => files_asset_path_basename("currency-flag"),
            'default_image'    => files_asset_path_basename("default"),

        ];
        //new user coupon
        
        $user_coupon    = UserCoupon::auth()->with(['new_user_bonus'])->first();
        if(!$user_coupon) return Response::success(['Transaction Type find successfully.'],[
            'sender_currency'    => $sender_currency,
            'receiver_currency'  => $receiver_currency,
            'image_paths'        => $image_paths,
            'transaction_type'   => $transaction_type,
            'coupons'            => $coupons ?? '',
        ],200);
        $coupon_transactions    = CouponTransaction::where('user_coupon_id',$user_coupon->id)->count();
        $remaining = $user_coupon->new_user_bonus->max_used - @$coupon_transactions;
        $new_user_coupon = [
            'id'                => $user_coupon->id,
            'coupon_type'       => GlobalConst::NEW_USER_BONUS,
            'name'              => $user_coupon->coupon_name,
            'price'             => $user_coupon->price,
            'max_limit'         => $user_coupon->new_user_bonus->max_used,
            'remaining'         => $remaining
        ];

        



        return Response::success(['Transaction Type find successfully.'],[
            'sender_currency'    => $sender_currency,
            'receiver_currency'  => $receiver_currency,
            'image_paths'        => $image_paths,
            'transaction_type'   => $transaction_type ?? $transaction_first,
            'coupons'            => $coupons,
            'new_user_coupon'    => $new_user_coupon
        ],200);
    }
    /**
     * Method for store send remittance data in temporary datas
     */
    public function store(Request $request){
        $validator              = Validator::make($request->all(),[
            'type'              => 'required',
            'send_money'        => 'required',
            'sender_currency'   => 'required',
            'receiver_currency' => 'required',
            'coupon'            => 'nullable',
            'coupon_type'       => 'nullable',
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }
        $validated            = $validator->validate();
        $coupons = Coupon::where('status', true)->get();
        if(!$coupons) return Response::error(['Coupon not found!'],[],404);
        $matchingCoupon = $coupons->first(function ($coupon) use ($request) {
            return $coupon->name === $request->coupon;
        });
        if(!$coupons){

            $matching_with_new_user     = UserCoupon::with(['new_user_bonus'])->where('coupon_name',$request->coupon)->first();
            if(!$matching_with_new_user) return Response::error(['Coupon not found!'],[],404);
        }
        $user   = auth()->user();
        $coupon_type = '';
        $couponId = 0;
        $coupon_bonus = 0;
        if($request->coupon){
            if(isset($matchingCoupon)){
                $transaction    = CouponTransaction::auth()->where('coupon_id',$matchingCoupon->id)->count();
                if($transaction >= $matchingCoupon->max_used){
                    return Response::error(['Sorry! Your Coupon limit is over.']);
                }else{
                    $coupon_type    = GlobalConst::COUPON;
                    $couponId       = $matchingCoupon->id;
                    $coupon_bonus   = $matchingCoupon->price;
                }
            }else{
                if(isset($matching_with_new_user)){
                    $transaction    = CouponTransaction::auth()->where('user_coupon_id',$matching_with_new_user->id)->count();
                    if($transaction >= $matching_with_new_user->new_user_bonus->max_used){
                        return Response::error(['Sorry! Your Coupon limit is over.']);
                    }else{
                        $coupon_type    = GlobalConst::NEW_USER_BONUS;
                        $couponId       = $matching_with_new_user->id;
                        $coupon_bonus   = $matching_with_new_user->price;
                    }
                }else{
                    return Response::error(['Coupon not found!'],[],404);
                } 
                
            }
        }
        
        
        $transaction_type     = TransactionSetting::where('status',true)->where('title','like','%'.$request->type.'%')->first();
        $sender_currency      = Currency::where('status',true)->where('sender',true)->where('id',$request->sender_currency)->first();
        if(!$sender_currency) return Response::error(['Sender Currency not found!'],[],404);
        $receiver_currency    = Currency::where('status',true)->where('receiver',true)->where('id',$request->receiver_currency)->first();
        if(!$receiver_currency) return Response::error(['Receiver Currency not found!'],[],404);
        if($request->type == PaymentGatewayConst::TRANSACTION_TYPE_BANK || $request->type == PaymentGatewayConst::TRANSACTION_TYPE_MOBILE){
            $sender_currency_code   = $sender_currency->code;
            $sender_currency_rate   = $sender_currency->rate;
            $sender_rate            = $sender_currency_rate / $sender_currency_rate;
            $receiver_currency_code = $receiver_currency->code;
            $receiver_currency_rate = $receiver_currency->rate;

            $enter_amount           = floatval($request->send_money) / $sender_currency_rate;
            $intervals = get_intervals_data($enter_amount,$transaction_type);
            if($intervals == false){
                return Response::error(['Please follow the transaction limit.'],[],404);  
            }
            $find_percent_charge    = ($enter_amount) / 100;

            $fixed_charge           = $transaction_type->fixed_charge;

            $percent_charge         = $transaction_type->percent_charge;

            $total_percent_charge   = $find_percent_charge * $percent_charge;
            $total_charge           = $fixed_charge + $total_percent_charge;
            $total_charge_amount    = $total_charge * $sender_currency_rate;

            $payable_amount       = $enter_amount + $total_charge_amount;
            if ($request->send_money == 0) {
                return Response::error(['Send Money must be greater than 0.'], [], 400);
            }
            if($enter_amount == ""){
                $enter_amount = 0;
            }
            if($enter_amount != 0){
                $convert_amount = $enter_amount;
                $receive_money  = $convert_amount * $receiver_currency_rate;
                $intervals      = $transaction_type->intervals;
                foreach($intervals as $index => $item){
                    if($enter_amount >= $item->min_limit && $enter_amount <= $item->max_limit){
                        $fixed_charge         = $item->fixed;
                        $percent_charge       = $item->percent;
                        $total_percent_charge = $find_percent_charge * $percent_charge;
                        $total_charge         = $fixed_charge + $total_percent_charge;
                        $total_charge_amount  = $total_charge * $sender_currency_rate;
                        $convert_amount       = floatval($request->send_money);
                        $payable_amount       = $request->send_money + $total_charge_amount;
                        $reciver_rate         = $receiver_currency_rate / $sender_currency_rate;
                        $receive_money        = $convert_amount * $reciver_rate;
                    }
                }
            }
        }
        
        if($couponId != 0){
            $coupon_price    = $coupon_bonus * $reciver_rate;
            $receive_money   = $receive_money + $coupon_price;
        }else{
            $receive_money  = $receive_money;
        }
        
        $validated['identifier']    = Str::uuid();
        $data = [
            'type'                  => $validated['type'],
            'identifier'            => $validated['identifier'],
            'data'                  => [
                'send_money'        => floatval($request->send_money),
                'fees'              => $total_charge_amount,
                'convert_amount'    => $convert_amount,
                'payable_amount'    => $payable_amount,
                'payable'           => $payable_amount,
                'receive_money'     => $receive_money,
                'sender_name'       => auth()->user()->fullname,
                'sender_email'      => auth()->user()->email,
                'sender_currency'   => $sender_currency_code,
                'receiver_currency' => $receiver_currency_code,
                'receiver_country'  => $receiver_currency->country,
                'sender_ex_rate'    => $sender_rate,
                'receiver_ex_rate'  => $reciver_rate,
                'sender_base_rate'  => floatval($sender_currency_rate),
                'coupon_id'         => $couponId,
                'coupon_type'       => $coupon_type,
            ],

        ];
        try {
            $temporary_data = TemporaryData::create($data);
        } catch (Exception $e) {
            return Response::error(['Something went wrong! Please try again.'],[],404);
        }
        if($couponId != 0){
            return Response::success(['Send Money with coupon'],[
                'temporary_data'      => $temporary_data,
                'matchingCoupon'      => $matchingCoupon ?? $matching_with_new_user,
            ],200);
        }
        if($couponId == 0){
            return Response::success(['Send Money without coupon'],[
                'temporary_data'      => $temporary_data,
            ],200);
        }
        
    }
    
    /**
     * Method for show send remittance beneficiary
     * @param $identifier
     * @param Illuminate\Http\Request $request
     */
    public function beneficiary(Request $request){
        $validator  = Validator::make($request->all(),[
            'identifier'  => 'required',
        ]);

        if($validator->fails()) return Response::error($validator->errors()->all(),[]);
        $temporary_data    = TemporaryData::where('identifier',$request->identifier)->first();
        $beneficiaries     = Recipient::auth()->where('method',$temporary_data->type)->where('country',$temporary_data->data->receiver_country)->orderByDESC('id')->get();  

        return Response::success(['Beneficiary'],[
            'beneficiaries'       => $beneficiaries,
            'temporary_data'      => $temporary_data
        ],200);
    }
    /**
     * Method for show the bank and mobile method information
     */
    public function beneficiaryAdd(Request $request){
        $validator  = Validator::make($request->all(),[
            'identifier'      => 'nullable',
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }

        $receiver_country     = Currency::where('receiver',true)->first();
        $user_country       = Currency::where('country',$request->country)->where('receiver',true)->first();
        if(!$user_country) return Response::error([$request->country .' '.'is not receiver country']);
        $country            = get_specific_country($user_country->country);
        $automatic_bank_list  = getFlutterwaveBanks($country['country_code']) ?? [];
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

        $mobile_methods       = MobileMethod::where('country',$request->country)->where('status',true)->get()->map(function($data){
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

        return Response::success(['Bank and Mobile Data fetch successfully.'],[
            'banks'           => $bank_list,
            'mobile_method'   => $mobile_methods,
            'cash_pickups'   => $cash_pickups,
        ],200);
    }

    public function beneficiaryStore(Request $request){
        $validator  = Validator::make($request->all(),[
            'identifier'      => 'required',
        ]);
        $temporary_data       = TemporaryData::where('identifier',$request->identifier)->first();
        if($request->method == global_const()::BENEFICIARY_METHOD_BANK_TRANSAFER ){
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
                $beneficiary  = Recipient::create($validated);
            }catch(Exception $e){
                return Response::error(['Something went wrong! Please try again.'],[],404);
            }
            return Response::success(['Beneficiary'],[
                'beneficiary'       => $beneficiary,
                'temporary_data'      => $temporary_data
            ],200);
        }if($request->method == global_const()::TRANSACTION_TYPE_MOBILE){
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
                $beneficiary  = Recipient::create($validated);
            }catch(Exception $e){
                return Response::error(['Something went wrong! Please try again.'],[],404);
            }
            return Response::success(['Beneficiary'],[
                'beneficiary'       => $beneficiary,
                'temporary_data'      => $temporary_data
            ],200);
        }
        if($request->method == global_const()::TRANSACTION_TYPE_CASHPICKUP){
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
            return Response::success(['Beneficiary'],[
                'beneficiary'       => $beneficiary,
                'temporary_data'      => $temporary_data
            ],200);
        }
        return Response::error(['Something went wrong! Please try again.'],[],404);
    }
    /**
     * Method for send the beneficiary to temporary data
     */
    public function beneficiarySend(Request $request){
        $validator  = Validator::make($request->all(),[
            'beneficiary_id'  => 'required',
            'identifier'      => 'required',
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }
        $beneficiary = Recipient::where('id',$request->beneficiary_id)->first();
        if(!$beneficiary){
            return Response::error(['Recipient Not exists!'],[],404);
        }
        $temporary_data = TemporaryData::where('identifier',$request->identifier)->first();
        $data = [
            'type'                  => $temporary_data->type,
            'identifier'            => $temporary_data->identifier,
            'data'                  => [
                'sender_name'       => $temporary_data->data->sender_name,
                'sender_email'      => $temporary_data->data->sender_email,
                'sender_currency'   => $temporary_data->data->sender_currency,
                'receiver_currency' => $temporary_data->data->receiver_currency,
                'sender_ex_rate'    => $temporary_data->data->sender_ex_rate,
                'sender_base_rate'  => $temporary_data->data->sender_base_rate,
                'receiver_ex_rate'  => $temporary_data->data->receiver_ex_rate,
                'coupon_id'         => $temporary_data->data->coupon_id,
                'coupon_type'       => $temporary_data->data->coupon_type,
                'first_name'        => $beneficiary->first_name,
                'middle_name'       => $beneficiary->middle_name ?? '',
                'last_name'         => $beneficiary->last_name,
                'email'             => $beneficiary->email ?? '',
                'country'           => $beneficiary->country,
                'city'              => $beneficiary->city ?? '',
                'state'             => $beneficiary->state ?? '',
                'zip_code'          => $beneficiary->zip_code ?? '',
                'phone'             => $beneficiary->phone ?? '',
                'method_name'       => $beneficiary->bank_name ?? $beneficiary->mobile_name,
                'account_number'    => $beneficiary->iban_number ?? $beneficiary->account_number,
                'address'           => $beneficiary->address ?? '',
                'document_type'     => $beneficiary->document_type ?? '',
                'front_image'       => $beneficiary->front_image ?? '',
                'back_image'        => $beneficiary->back_image ?? '',
                'send_money'        => $temporary_data->data->send_money,
                'fees'              => $temporary_data->data->fees,
                'convert_amount'    => $temporary_data->data->convert_amount,
                'payable_amount'    => $temporary_data->data->payable_amount,
                'payable'           => $temporary_data->data->payable,
                'receive_money'     => $temporary_data->data->receive_money,
            ],
        ];
        try{
            $temporary_data->update($data);
        }catch(Exception $e){
            return Response::error(['Something went wrong! Please try again.'],[],404);
        }
        return Response::success(['Beneficiary'],[
            'beneficiaries'       => $beneficiary,
            'temporary_data'      => $temporary_data
        ],200);
    }
    /**
     * Method for store receipt payment information
     */
    public function receiptPayment(Request $request){
        $validator = Validator::make($request->all(),[
            'identifier'  => 'nullable',
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }
        $transaction_type  = TransactionSetting::where('status',true)->get();
        if($transaction_type->isEmpty()) {
            return Response::error(['Transaction Type not found!'],[],404);
        }
        $sending_purposes  = SendingPurpose::where('status',true)->get();
        $source_of_funds   = SourceOfFund::where('status',true)->get();
        $payment_gateway   = PaymentGatewayCurrency::whereHas('gateway', function ($gateway) {
            $gateway->where('slug', PaymentGatewayConst::remittance_money_slug());
            $gateway->where('status', 1);
        })->get();

        return Response::success(['Sending Purpose and Source of Fund Data Fetch Successfully.'],[
            'sending_purposes'       => $sending_purposes,
            'source_of_funds'        => $source_of_funds,
            'payment_gateway'        => $payment_gateway,
            'transaction_type'       => $transaction_type,
        ],200);
    }
    /**
     * Method for store receipt payment information
     */
    public function receiptPaymentStore(Request $request){
        $validator = Validator::make($request->all(),[
            'identifier'         => 'required',
            'sending_purpose'    => 'required|integer',
            'source'             => 'required|integer',
            'remark'             => 'nullable|string',
            'payment_gateway'    => 'required|integer',
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }
        $temporary_data     = TemporaryData::where('identifier',$request->identifier)->first();
        
        $validated          = $validator->validate();
        $currency           = PaymentGatewayCurrency::where('id',$validated['payment_gateway'])->first();
        $source_of_fund     = SourceOfFund::where('id',$validated['source'])->first();
        $sending_purpose    = SendingPurpose::where('id',$validated['sending_purpose'])->first();
        $sender_currency    = Currency::where('status',true)->where('sender',true)->first();

        $receiver_currency    = Currency::where('status',true)->where('receiver',true)->first();
        $rate                  = $currency->rate / $temporary_data->data->sender_base_rate;
        
        $data = [
            'type'                    => $temporary_data->type,
            'identifier'              => $temporary_data->identifier,
            'data'                    => [
                'sender_name'         => auth()->user()->fullname,
                'sender_email'        => auth()->user()->email,
                'sender_currency'     => $temporary_data->data->sender_currency,
                'receiver_currency'   => $temporary_data->data->receiver_currency,
                'sender_ex_rate'      => $temporary_data->data->sender_ex_rate,
                'sender_base_rate'    => $temporary_data->data->sender_base_rate,
                'receiver_ex_rate'    => $temporary_data->data->receiver_ex_rate,
                'coupon_id'           => $temporary_data->data->coupon_id,
                'coupon_type'         => $temporary_data->data->coupon_type,
                'first_name'          => $temporary_data->data->first_name,
                'middle_name'         => $temporary_data->data->middle_name,
                'last_name'           => $temporary_data->data->last_name,
                'email'               => $temporary_data->data->email,
                'country'             => $temporary_data->data->country,
                'city'                => $temporary_data->data->city,
                'state'               => $temporary_data->data->state,
                'zip_code'            => $temporary_data->data->zip_code,
                'phone'               => $temporary_data->data->phone,
                'method_name'         => $temporary_data->data->method_name,
                'account_number'      => $temporary_data->data->account_number,
                'address'             => $temporary_data->data->address,
                'document_type'       => $temporary_data->data->document_type,
                'sending_purpose'     => [
                    'id'              => $sending_purpose->id,
                    'name'            => $sending_purpose->name,
                ],
                'source'              => [
                    'id'              => $source_of_fund->id,
                    'name'            => $source_of_fund->name,
                ],
                'remark'              => $validated['remark'],
                'currency'            => [
                    'id'              => $currency->id,
                    'name'            => $currency->name,
                    'code'            => $currency->currency_code,
                    'alias'           => $currency->alias,
                    'rate'            => $currency->rate,
                ],
                'payment_gateway'     => $validated['payment_gateway'],
                'front_image'         => $temporary_data->data->front_image,
                'back_image'          => $temporary_data->data->back_image,
                'send_money'          => $temporary_data->data->send_money,
                'fees'                => $temporary_data->data->fees,
                'convert_amount'      => $temporary_data->data->convert_amount,
                'payable'             => $temporary_data->data->payable,
                'payable_amount'      => $temporary_data->data->payable * $rate,
                'receive_money'       => $temporary_data->data->receive_money,
            ],

        ];
        try{
            $temporary_data->update($data);
        }catch(Exception $e){
            return Response::error(['Something went wrong! Please try again.'],[],404);
        }
        return Response::success(['Receipt Payment Data Stored'],[
            'temporary_data'         => $temporary_data
        ],200);
    }

    /**
     * Add Money Form Submit
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submitData(Request $request) {
        try{
            $instance = PaymentGatewayHelper::init($request->all())->type(PaymentGatewayConst::TYPESENDREMITTANCE)->gateway()->api()->render();
        }catch(Exception $e) {
            return Response::error([$e->getMessage()],[],500);
        }

        if($instance instanceof RedirectResponse === false && isset($instance['gateway_type']) && $instance['gateway_type'] == PaymentGatewayConst::MANUAL) {
            return Response::error([__('Can\'t submit manual gateway in automatic link')],[],400);
        }

        return Response::success([__('Payment gateway response successful')],[
            'redirect_url'          => $instance['redirect_url'],
            'redirect_links'        => $instance['redirect_links'],
            'action_type'           => $instance['type']  ?? false, 
            'address_info'          => $instance['address_info'] ?? [],
        ],200);  
    }
    public function success(Request $request, $gateway){
        try{
            $token = PaymentGatewayHelper::getToken($request->all(),$gateway);
            $temp_data = TemporaryData::where("identifier",$token)->first();

            if(!$temp_data) {
                if(Transaction::where('callback_ref',$token)->exists()) {
                    return Response::success([__('Transaction request sended successfully!')],[],400);
                }else {
                    return Response::error([__('Transaction failed. Record didn\'t saved properly. Please try again')],[],400);
                }
            }

            $update_temp_data = json_decode(json_encode($temp_data->data),true);
            $update_temp_data['callback_data']  = $request->all();
            $temp_data->update([
                'data'  => $update_temp_data,
            ]);
            $temp_data = $temp_data->toArray();
            $instance = PaymentGatewayHelper::init($temp_data)->type(PaymentGatewayConst::TYPESENDREMITTANCE)->responseReceive();

            if($instance instanceof RedirectResponse) return $instance;
        }catch(Exception $e) {
            
            return Response::error([$e->getMessage()],[],500);
        }
        $share_link         = route('share.link',$instance);
        $download_link      = route('download.pdf',$instance);

        return Response::success(["Payment successful, please go back your app"],[
            'share-link'   => $share_link,
            'download_link' => $download_link,
        ],200);

        
    }

    public function cancel(Request $request,$gateway) {
        $token = PaymentGatewayHelper::getToken($request->all(),$gateway);
        $temp_data = TemporaryData::where("identifier",$token)->first();
        try{
            if($temp_data != null) {
                $temp_data->delete();
            }
        }catch(Exception $e) {
            // Handel error
        }
        return Response::success([__('Payment process cancel successfully!')],[],200);
    }

    public function manualInputFields(Request $request) {
       
        $validator = Validator::make($request->all(),[
            'alias'         => "required|string|exists:payment_gateway_currencies",
        ]);

        if($validator->fails()) {
            return Response::error($validator->errors()->all(),[],400);
        }

        $validated = $validator->validate();
        $gateway_currency = PaymentGatewayCurrency::where("alias",$validated['alias'])->first();

        $gateway = $gateway_currency->gateway;

        if(!$gateway->isManual()) return Response::error([__('Can\'t get fields. Requested gateway is automatic')],[],400);

        if(!$gateway->input_fields || !is_array($gateway->input_fields)) return Response::error([__("This payment gateway is under constructions. Please try with another payment gateway")],[],503);

        try{
            $input_fields = json_decode(json_encode($gateway->input_fields),true);
            $input_fields = array_reverse($input_fields);
        }catch(Exception $e) {
            return Response::error([__("Something went wrong! Please try again")],[],500);
        }
        
        return Response::success([__('Payment gateway input fields fetch successfully!')],[
            'gateway'           => [
                'desc'          => $gateway->desc
            ],
            'input_fields'      => $input_fields,
            'currency'          => $gateway_currency->only(['alias']),
        ],200);
    }
    public function manualSubmit(Request $request) {
        $basic_setting = BasicSettings::first();
        $user          = auth()->user();
        try{
            $instance = PaymentGatewayHelper::init($request->all())->gateway()->get();
        }catch(Exception $e) {
            return Response::error([$e->getMessage()],[],401);
        }

        // Check it's manual or automatic
        if(!isset($instance['gateway_type']) || $instance['gateway_type'] != PaymentGatewayConst::MANUAL) return Response::error([__('Can\'t submit automatic gateway in manual link')],[],400);

        $gateway = $instance['gateway'] ?? null;
        $gateway_currency = $instance['currency'] ?? null;
        if(!$gateway || !$gateway_currency || !$gateway->isManual()) return Response::error([__('Selected gateway is invalid')],[],400);

        $amount = $instance['amount'] ?? null;
        if(!$amount) return Response::error([__('Transaction Failed. Failed to save information. Please try again')],[],400);

        // $wallet = $wallet = $instance['wallet'] ?? null;
        // if(!$wallet) return Response::error([__('Your wallet is invalid!')],[],400);
        
        $this->file_store_location = "transaction";
        $dy_validation_rules = $this->generateValidationRules($gateway->input_fields);
        
        $validator = Validator::make($request->all(),$dy_validation_rules);
        
        if($validator->fails()) return Response::error($validator->errors()->all(),[],400);
        $validated = $validator->validate();
        
        $get_values = $this->placeValueWithFields($gateway->input_fields,$validated);
        
        $data   = TemporaryData::where('identifier',$request->identifier)->first();
        
        $trx_id = generateTrxString("transactions","trx_id","SR",8);
        // Make Transaction
        DB::beginTransaction();
        try{
            $id = DB::table("transactions")->insertGetId([
                'user_id'                       => auth()->user()->id,
                'payment_gateway_currency_id'   => $gateway_currency->id,
                'type'                          => PaymentGatewayConst::TYPESENDREMITTANCE,
                'remittance_data'               => json_encode([
                    'type'                      => $data->type,
                    'sender_name'               => $data->data->sender_name,
                    'sender_email'              => $data->data->sender_email,
                    'sender_currency'           => $data->data->sender_currency,
                    'receiver_currency'         => $data->data->receiver_currency,
                    'sender_ex_rate'            => $data->data->sender_ex_rate,
                    'sender_base_rate'          => $data->data->sender_base_rate,
                    'receiver_ex_rate'          => $data->data->receiver_ex_rate,
                    'coupon_id'                 => $data->data->coupon_id,
                    'coupon_type'               => $data->data->coupon_type,
                    'first_name'                => $data->data->first_name,
                    'middle_name'               => $data->data->middle_name,
                    'last_name'                 => $data->data->last_name,
                    'email'                     => $data->data->email,
                    'country'                   => $data->data->country,
                    'city'                      => $data->data->city,
                    'state'                     => $data->data->state,
                    'zip_code'                  => $data->data->zip_code,
                    'phone'                     => $data->data->phone,
                    'method_name'               => $data->data->method_name,
                    'account_number'            => $data->data->account_number,
                    'address'                   => $data->data->address,
                    'document_type'             => $data->data->document_type,
                    'front_image'               => $data->data->front_image,
                    'back_image'                => $data->data->back_image,
                    
                    'sending_purpose'           => $data->data->sending_purpose->name,
                    'source'                    => $data->data->source->name,
                    'currency'                  => [
                        'name'                  => $data->data->currency->name,
                        'code'                  => $data->data->currency->code,
                        'rate'                  => $data->data->currency->rate,
                    ],
                    'send_money'                => $data->data->send_money,
                    'fees'                      => $data->data->fees,
                    'convert_amount'            => $data->data->convert_amount,
                    'payable_amount'            => $data->data->payable_amount,
                    'remark'                    => $data->data->remark,
                ]),
                'trx_id'                        => $trx_id,
                'request_amount'                => $data->data->send_money,
                'exchange_rate'                 => $data->data->currency->rate,
                'payable'                       => $data->data->payable_amount,
                'fees'                          => $data->data->fees,
                'convert_amount'                => $data->data->convert_amount,
                'will_get_amount'               => $data->data->receive_money,
                'remark'                        => 'Manual',
                'details'                       => "COMPLETED",
                'status'                        => global_const()::REMITTANCE_STATUS_PENDING,
                'attribute'                     => PaymentGatewayConst::SEND,
                'created_at'                    => now(),
                'callback_ref'                  => $output['callback_ref'] ?? null,
            ]);
            if($data->data->coupon_id != 0){
                if($data->data->coupon_type == GlobalConst::COUPON){
                    $coupon_id  = $data->data->coupon_id;
                    $user   = auth()->user();
                    CouponTransaction::create([
                        'user_id'   => $user->id,
                        'coupon_id'   => $coupon_id,
                        'transaction_id'   => $id,
                    ]);
                }else{
                    $user_coupon_id = $data->data->coupon_id;
                    $user   = auth()->user();
                    CouponTransaction::create([
                        'user_id'           => $user->id,
                        'user_coupon_id'    => $user_coupon_id,
                        'transaction_id'    => $id,
                    ]);
                }
            }
            if($basic_setting->email_notification == true){
                Notification::route("mail",$user->email)->notify(new manualEmailNotification($user,$data,$trx_id));
            }
            $notification_message = [
                'title'     => "Send Remittance from " . "(" . $user->username . ")" . "Transaction ID :". $trx_id . " created successfully.",
                'time'      => Carbon::now()->diffForHumans(),
                'image'     => get_image($user->image,'user-profile'),
            ];
            AdminNotification::create([
                'type'      => "Send Remittance",
                'admin_id'  => 1,
                'message'   => $notification_message,
            ]);
            (new PushNotificationHelper())->prepare([1],[
                'title' => "Send Remittance from " . "(" . $user->username . ")" . "Transaction ID :". $trx_id . " created successfully.",
                'desc'  => "",
                'user_type' => 'admin',
            ])->send();
            DB::commit();
        }catch(Exception $e) {
            
            DB::rollBack();
            return Response::error([__("Something went wrong! Please try again")],[],500);
        }
        $share_link   = route('share.link',$trx_id);
        $download_link   = route('download.pdf',$trx_id);
        return Response::success(["Payment successful, please go back your app"],[
            'share-link'   => $share_link,
            'download_link' => $download_link,
        ],200);
    }
    public function gatewayAdditionalFields(Request $request) {
        $validator = Validator::make($request->all(),[
            'currency'          => "required|string|exists:payment_gateway_currencies,alias",
        ]);
        if($validator->fails()) return Response::error($validator->errors()->all(),[],400);
        $validated = $validator->validate();

        $gateway_currency = PaymentGatewayCurrency::where("alias",$validated['currency'])->first();

        $gateway = $gateway_currency->gateway;

        $data['available'] = false;
        $data['additional_fields']  = [];
        if(Gpay::isGpay($gateway)) {
            $gpay_bank_list = Gpay::getBankList();
            if($gpay_bank_list == false) return Response::error(['Gpay bank list server response failed! Please try again'],[],500);
            $data['available']  = true;

            $gpay_bank_list_array = json_decode(json_encode($gpay_bank_list),true);

            $gpay_bank_list_array = array_map(function ($array){
                
                $data['name']       = $array['short_name_by_gpay'];
                $data['value']      = $array['gpay_bank_code'];

                return $data;
        
            }, $gpay_bank_list_array);

            $data['additional_fields'][] = [
                'type'      => "select",
                'label'     => "Select Bank",
                'title'     => "Select Bank",
                'name'      => "bank",
                'values'    => $gpay_bank_list_array,
            ];

        }

        return Response::success([__('Request response fetch successfully!')],$data,200);
    }

    public function cryptoPaymentConfirm(Request $request, $trx_id) 
    {
        
        $transaction = Transaction::where('trx_id',$trx_id)->where('status', global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT)->firstOrFail();

        $dy_input_fields = $transaction->details->payment_info->requirements ?? [];
        $validation_rules = $this->generateValidationRules($dy_input_fields);

        $validated = [];
        if(count($validation_rules) > 0) {
            $validated = Validator::make($request->all(), $validation_rules)->validate();
        }

        if(!isset($validated['txn_hash'])) return Response::error(['Transaction hash is required for verify']);

        $receiver_address = $transaction->details->payment_info->receiver_address ?? "";

        // check hash is valid or not
        $crypto_transaction = CryptoTransaction::where('txn_hash', $validated['txn_hash'])
                                                ->where('receiver_address', $receiver_address)
                                                ->where('asset',$transaction->currency->currency_code)
                                                ->where(function($query) {
                                                    return $query->where('transaction_type',"Native")
                                                                ->orWhere('transaction_type', "native");
                                                })
                                                ->where('status',PaymentGatewayConst::NOT_USED)
                                                ->first();
                                                
        if(!$crypto_transaction) return Response::error(['Transaction hash is not valid! Please input a valid hash'],[],404);

        if($crypto_transaction->amount >= $transaction->payable == false) {
            if(!$crypto_transaction) Response::error(['Insufficient amount added. Please contact with system administrator'],[],400);
        }

        DB::beginTransaction();
        try{

            

            // update crypto transaction as used
            DB::table($crypto_transaction->getTable())->where('id', $crypto_transaction->id)->update([
                'status'        => PaymentGatewayConst::USED,
            ]);

            // update transaction status
            $transaction_details = json_decode(json_encode($transaction->details), true);
            $transaction_details['payment_info']['txn_hash'] = $validated['txn_hash'];

            DB::table($transaction->getTable())->where('id', $transaction->id)->update([
                'details'       => json_encode($transaction_details),
                'status'        => global_const()::REMITTANCE_STATUS_CONFIRM_PAYMENT,
            ]);

            DB::commit();

        }catch(Exception $e) {
            DB::rollback();
            return Response::error(['Something went wrong! Please try again'],[],500);
        }

        return Response::success(['Payment Confirmation Success!'],[],200);
    }
    public function postSuccess(Request $request, $gateway)
    {
        try{
            $token = PaymentGatewayHelper::getToken($request->all(),$gateway);
            $temp_data = TemporaryData::where("identifier",$token)->first();
            if($temp_data && $temp_data->data->creator_guard != 'api') {
                Auth::guard($temp_data->data->creator_guard)->loginUsingId($temp_data->data->creator_id);
            }
        }catch(Exception $e) {
            return Response::error([$e->getMessage()]);
        }
        return $this->success($request, $gateway);
    }
    public function postCancel(Request $request, $gateway)
    {
        try{
            $token = PaymentGatewayHelper::getToken($request->all(),$gateway);
            $temp_data = TemporaryData::where("identifier",$token)->first();
            if($temp_data && $temp_data->data->creator_guard != 'api') {
                Auth::guard($temp_data->data->creator_guard)->loginUsingId($temp_data->data->creator_id);
            }
        }catch(Exception $e) {
            return Response::error([$e->getMessage()]);
        }
        return $this->cancel($request, $gateway);
    }
    /**
     * razor pay callback
     */
    public function razorCallback(){
        $request_data = request()->all();
        //if payment is successful
        if ($request_data['razorpay_payment_link_status'] ==  'paid') {
            $token = $request_data['razorpay_payment_link_reference_id'];

            $checkTempData = TemporaryData::where("type",PaymentGatewayConst::RAZORPAY)->where("identifier",$token)->first();
            if(!$checkTempData) {

                return Response::error(['Transaction Failed. Record didn\'t saved properly. Please try again.'],404);
            }
            $checkTempData = $checkTempData->toArray();
            try{
                $data = PaymentGatewayHelper::init($checkTempData)->type(PaymentGatewayConst::TYPESENDREMITTANCE)->responseReceive();
            }catch(Exception $e) {
                $message = ['error' => [$e->getMessage()]];
                return Response::error($message);
            }
            $share_link   = route('share.link',$data);
            $download_link   = route('download.pdf',$data);
            return Response::success(["Payment successful, please go back your app"],[
                'share-link'   => $share_link,
                'download_link' => $download_link,
            ],200);

        }
        else{
            $message = ['error' => ['Payment Failed']];
            return Response::error($message);
        }
    }
    /**
     * Redirect Users for collecting payment via Button Pay (JS Checkout)
     */
    public function redirectBtnPay(Request $request, $gateway)
    {
        try{
            return PaymentGatewayHelper::init([])->type(PaymentGatewayConst::TYPESENDREMITTANCE)->handleBtnPay($gateway, $request->all());
        }catch(Exception $e) {
            return Response::error([$e->getMessage()], [], 500);
        }
    }
    /**
     * Method for paystack pay callback
     */
    public function paystackPayCallBack(Request $request){
        $instance = $this->paystackSuccess($request->all());
        $share_link   = route('share.link',$instance);
        $download_link   = route('download.pdf',$instance);
        return Response::success(["Payment successful, please go back your app"],[
            'share-link'   => $share_link,
            'download_link' => $download_link,
        ],200);
    }

}
