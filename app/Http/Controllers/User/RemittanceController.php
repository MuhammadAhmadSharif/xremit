<?php

namespace App\Http\Controllers\User;

use PDF;
use Exception;
use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\AppliedCoupon;
use App\Models\TemporaryData;
use App\Constants\GlobalConst;
use App\Http\Helpers\Response;
use App\Models\Admin\Currency;
use App\Models\Admin\SetupKyc;
use App\Models\UserNotification;
use App\Models\CouponTransaction;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\BasicSettings;
use App\Http\Controllers\Controller;
use App\Models\Admin\PaymentGateway;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Constants\PaymentGatewayConst;
use App\Models\Admin\AdminNotification;
use App\Models\Admin\CryptoTransaction;
use Illuminate\Support\Facades\Session;
use App\Notifications\paypalNotification;
use App\Traits\ControlDynamicInputFields;
use Illuminate\Support\Facades\Validator;
use App\Http\Helpers\PushNotificationHelper;
use App\Models\Admin\PaymentGatewayCurrency;
use Illuminate\Support\Facades\Notification;
use App\Notifications\manualEmailNotification;
use App\Providers\Admin\BasicSettingsProvider;
use App\Http\Helpers\PaymentGateway as PaymentGatewayHelper;

class RemittanceController extends Controller
{
   
    use ControlDynamicInputFields;
    
    /**
     * Method for buy crypto submit
     * @param Illuminate\Http\Request $request
     */
    public function submit(Request $request){
       
        try{
            $instance = PaymentGatewayHelper::init($request->all())->type(PaymentGatewayConst::TYPESENDREMITTANCE)->gateway()->render();
            if($instance instanceof RedirectResponse === false && isset($instance['gateway_type']) && $instance['gateway_type'] == PaymentGatewayConst::MANUAL) {
                $manual_handler = $instance['distribute'];
                return $this->$manual_handler($instance);
            }
        }catch(Exception $e){
            return back()->with(['error' => [$e->getMessage()]]);
        }
        return $instance;
    }
    /**
     * This method for success alert of PayPal
     * @method POST
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\Request
     */
    public function success(Request $request, $gateway){
        try{
            $token = PaymentGatewayHelper::getToken($request->all(),$gateway);
            $temp_data = TemporaryData::where("identifier",$token)->first();
            

            if(Transaction::where('callback_ref', $token)->exists()) {
                if(!$temp_data) return redirect()->route('user.send.remittance.index')->with(['success' => ['Transaction request sended successfully!']]);;
            }else {
                if(!$temp_data) return redirect()->route('user.send.remittance.index')->with(['error' => ['Transaction failed. Record didn\'t saved properly. Please try again.']]);
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
            
            return back()->with(['error' => [$e->getMessage()]]);
        }
        
        return redirect()->route("user.payment.confirmation",$instance)->with(['success' => ['Successfully Send Remittance']]);
    }
    /**
     * Method for pagadito success
     */
    public function successPagadito(Request $request, $gateway){
        $token = PaymentGatewayHelper::getToken($request->all(),$gateway);
        $temp_data = TemporaryData::where("identifier",$token)->first();
        if($temp_data->data->creator_guard == 'web'){
            Auth::guard($temp_data->data->creator_guard)->loginUsingId($temp_data->data->creator_id);
            try{
                if(Transaction::where('callback_ref', $token)->exists()) {
                    if(!$temp_data) return redirect()->route("user.send.remittance.index")->with(['success' => [__('Successfully Send Remittance')]]);
                }else {
                    if(!$temp_data) return redirect()->route('index')->with(['error' => [__("transaction_record")]]);
                }

                $update_temp_data = json_decode(json_encode($temp_data->data),true);
                $update_temp_data['callback_data']  = $request->all();
                $temp_data->update([
                    'data'  => $update_temp_data,
                ]);
                $temp_data = $temp_data->toArray();
                $instance = PaymentGatewayHelper::init($temp_data)->type(PaymentGatewayConst::TYPESENDREMITTANCE)->responseReceive();
            }catch(Exception $e) {
                return back()->with(['error' => [$e->getMessage()]]);
            }
            return redirect()->route("user.payment.confirmation",$instance)->with(['success' => ['Successfully Send Remittance']]);
        }elseif($temp_data->data->creator_guard =='api'){
            $creator_table = $temp_data->data->creator_table ?? null;
            $creator_id = $temp_data->data->creator_id ?? null;
            $creator_guard = $temp_data->data->creator_guard ?? null;
            $api_authenticated_guards = PaymentGatewayConst::apiAuthenticateGuard();
            if($creator_table != null && $creator_id != null && $creator_guard != null) {
                if(!array_key_exists($creator_guard,$api_authenticated_guards)) return Response::success([__('Request user doesn\'t save properly. Please try again')],[],400);
                $creator = DB::table($creator_table)->where("id",$creator_id)->first();
                if(!$creator) return Response::success([__('Request user doesn\'t save properly. Please try again')],[],400);
                $api_user_login_guard = $api_authenticated_guards[$creator_guard];
                Auth::guard($api_user_login_guard)->loginUsingId($creator->id);
            }
            try{
                if(!$temp_data) {
                    if(Transaction::where('callback_ref',$token)->exists()) {
                        return Response::success([__('Successfully Send Remittance')],[],400);
                    }else {
                        return Response::error([__('transaction_record')],[],400);
                    }
                }
                $update_temp_data = json_decode(json_encode($temp_data->data),true);
                $update_temp_data['callback_data']  = $request->all();
                $temp_data->update([
                    'data'  => $update_temp_data,
                ]);
                $temp_data = $temp_data->toArray();
                $instance = PaymentGatewayHelper::init($temp_data)->type(PaymentGatewayConst::TYPESENDREMITTANCE)->responseReceive();

                // return $instance;
            }catch(Exception $e) {
                return Response::error([$e->getMessage()],[],500);
            }
            $share_link   = route('share.link',$instance);
            $download_link   = route('download.pdf',$instance);
            return Response::success(["Payment successful, please go back your app"],[
                'share-link'   => $share_link,
                'download_link' => $download_link,
            ],200);
        }

    }
    public function cancel(Request $request, $gateway) {
        if($request->has('token')) {
            $identifier = $request->token;
            if($temp_data = TemporaryData::where('identifier', $identifier)->first()) {
                $temp_data->delete();
            }
        }
        return redirect()->route('user.send.remittance.index');
    }
    public function callback(Request $request,$gateway) {

        $callback_token = $request->get('token');
        $callback_data = $request->all();
        
        try{
            PaymentGatewayHelper::init([])->type(PaymentGatewayConst::TYPESENDREMITTANCE)->handleCallback($callback_token,$callback_data,$gateway);
        }catch(Exception $e) {
            // handle Error
            logger($e);
        }
    }
    
    /**
     * This method for stripe payment
     * @method GET
     * @param $gateway
     */
    public function payment(Request $request,$gateway){
        $page_title = "Stripe Payment";
        $client_ip = request()->ip() ?? false;
        $user_country = geoip()->getLocation($client_ip)['country'] ?? "";
        $user         = auth()->user();
        $notifications = UserNotification::where('user_id',$user->id)->latest()->take(10)->get();
        $tempData = Session::get('identifier');
        
        $hasData = TemporaryData::where('identifier', $tempData)->where('type',$gateway)->first();
        
        if(!$hasData){
            return redirect()->route('user.send.remittance.index');
        }
        return view('user.sections.money-transfer.automatic.'.$gateway,compact(
            "page_title",
            "hasData",
            'user_country',
            'user',
            'notifications'
        ));
    }
    
    public function handleManualPayment($payment_info) {
        // Insert temp data
        $data = [
            'type'          => PaymentGatewayConst::TYPESENDREMITTANCE,
            'identifier'    => generate_unique_string("temporary_datas","identifier",16),
            'data'          => [
                'gateway_currency_id'    => $payment_info['currency']->id,
                'amount'                 => $payment_info['amount'],
                'form_data'              => $payment_info['form_data']['identifier'],
            ],
        ];

        try{
            TemporaryData::create($data);
        }catch(Exception $e) {
            return redirect()->route('user.send.remittance.index')->with(['error' => ['Failed to save data. Please try again']]);
        }
        return redirect()->route('user.send.remittance.manual.form',$data['identifier']);
    }

    

    public function showManualForm($token) {
        
        $tempData = TemporaryData::search($token)->first();
        if(!$tempData || $tempData->data == null || !isset($tempData->data->gateway_currency_id)) return redirect()->route('user.send.remittance.index')->with(['error' => ['Invalid request']]);
        $gateway_currency = PaymentGatewayCurrency::find($tempData->data->gateway_currency_id);
        if(!$gateway_currency || !$gateway_currency->gateway->isManual()) return redirect()->route('user.send.remittance.index')->with(['error' => ['Selected gateway is invalid']]);
        $gateway = $gateway_currency->gateway;
        if(!$gateway->input_fields || !is_array($gateway->input_fields)) return redirect()->route('user.send.remittance.index')->with(['error' => ['This payment gateway is under constructions. Please try with another payment gateway']]);
        $amount = $tempData->data->amount;

        $page_title = "- Payment Instructions";
        $client_ip      = request()->ip() ?? false;
        $user_country   = geoip()->getLocation($client_ip)['country'] ?? "";
        $kyc_data       = SetupKyc::userKyc()->first();
        $user           = auth()->user();
        $notifications  = UserNotification::where('user_id',$user->id)->latest()->take(10)->get();

        return view('user.sections.money-transfer.manual.payment_confirmation',compact("gateway","page_title","token","amount",'user_country',
        'user',
        'notifications'));
    }

    public function manualSubmit(Request $request,$token) {
       
        $basic_setting = BasicSettings::first();
        $user          = auth()->user();
        $request->merge(['identifier' => $token]);
        $tempDataValidate = Validator::make($request->all(),[
            'identifier'        => "required|string|exists:temporary_datas",
        ])->validate();

        $tempData = TemporaryData::search($tempDataValidate['identifier'])->first();
        if(!$tempData || $tempData->data == null || !isset($tempData->data->gateway_currency_id)) return redirect()->route('user.send.remittance.index')->with(['error' => ['Invalid request']]);
        $gateway_currency = PaymentGatewayCurrency::find($tempData->data->gateway_currency_id);
        if(!$gateway_currency || !$gateway_currency->gateway->isManual()) return redirect()->route('user.send.remittance.index')->with(['error' => ['Selected gateway is invalid']]);
        $gateway = $gateway_currency->gateway;
        $amount = $tempData->data->amount ?? null;
        if(!$amount) return redirect()->route('user.send.remittance.index')->with(['error' => ['Transaction Failed. Failed to save information. Please try again']]);
        
        $this->file_store_location  = "transaction";
        $dy_validation_rules        = $this->generateValidationRules($gateway->input_fields);
        
        $validated  = Validator::make($request->all(),$dy_validation_rules)->validate();
        $get_values = $this->placeValueWithFields($gateway->input_fields,$validated);
       
        
        $data   = TemporaryData::where('identifier',$tempData->data->form_data)->first();
       
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
            if( $basic_setting->email_notification == true){
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
            DB::table("temporary_datas")->where("identifier",$token)->delete();
            DB::commit();
        }catch(Exception $e) {
            DB::rollBack();
            return redirect()->route('user.send.remittance.manual.form',$token)->with(['error' => ['Something went wrong! Please try again']]);
        }
        return redirect()->route("user.payment.confirmation",$trx_id)->with(['success' => ['Successfully send remittance']]);
    }
    public function postSuccess(Request $request, $gateway)
    {
        try{
            $token = PaymentGatewayHelper::getToken($request->all(),$gateway);
            $temp_data = TemporaryData::where("identifier",$token)->first();
            
            Auth::guard($temp_data->data->creator_guard)->loginUsingId($temp_data->data->creator_id);
        }catch(Exception $e) {
            
            return redirect()->route('frontend.index');
        }
        return $this->success($request, $gateway);
    }
    public function postCancel(Request $request, $gateway)
    {
        try{
            $token = PaymentGatewayHelper::getToken($request->all(),$gateway);
            $temp_data = TemporaryData::where("identifier",$token)->first();
            Auth::guard($temp_data->data->creator_guard)->loginUsingId($temp_data->data->creator_id);
        }catch(Exception $e) {
            
            return redirect()->route('frontend.index');
        }
        return $this->cancel($request, $gateway);
    }
    public function cryptoPaymentAddress(Request $request, $trx_id) {

        $page_title = "Crypto Payment Address";
        $transaction = Transaction::where('trx_id', $trx_id)->firstOrFail();
        $client_ip      = request()->ip() ?? false;
        $user_country   = geoip()->getLocation($client_ip)['country'] ?? "";
        $kyc_data       = SetupKyc::userKyc()->first();
        $user           = auth()->user();
        $notifications  = UserNotification::where('user_id',$user->id)->latest()->take(10)->get();

        if($transaction->currency->gateway->isCrypto() && $transaction->details?->payment_info?->receiver_address ?? false) {
            return view('user.sections.send-remittance.payment.crypto.address', compact(
                'transaction',
                'page_title',
                'user_country',
                'user',
                'notifications'
            ));
        }

        return abort(404);
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

        if(!isset($validated['txn_hash'])) return back()->with(['error' => ['Transaction hash is required for verify']]);

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
                                                
        if(!$crypto_transaction) return back()->with(['error' => ['Transaction hash is not valid! Please input a valid hash']]);

        if($crypto_transaction->amount >= $transaction->total_payable == false) {
            if(!$crypto_transaction) return back()->with(['error' => ['Insufficient amount added. Please contact with system administrator']]);
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
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return back()->with(['success' => ['Payment Confirmation Success!']]);
    }
    public function paymentConfirmation(Request $request,$trx_id){
        $page_title    = "| Payment Confirmation";
        $client_ip     = request()->ip() ?? false;
        $user_country  = geoip()->getLocation($client_ip)['country'] ?? "";
        $kyc_data      = SetupKyc::userKyc()->first();
        $user          = auth()->user();
        $notifications = UserNotification::where('user_id',$user->id)->latest()->take(10)->get();
        $transaction   = Transaction::where('trx_id',$trx_id)->first();
        return view('user.sections.payment-confirmation.index',compact(
            'page_title',
            'transaction',
            'user_country',
            'user',
            'notifications'
        ));
    }

    /**
     * Redirect Users for collecting payment via Button Pay (JS Checkout)
     */
    public function redirectBtnPay(Request $request, $gateway)
    {
        try{
            return PaymentGatewayHelper::init([])->type(PaymentGatewayConst::TYPESENDREMITTANCE)->handleBtnPay($gateway, $request->all());
        }catch(Exception $e) {
            return redirect()->route('user.send.remittance.index')->with(['error' => [$e->getMessage()]]);
        }
    }

    
    /**
     * Method for share link page
     * @param string $trx_id
     * @param \Illuminate\Http\Request $request
     */
    public function shareLink(Request $request,$trx_id){
        $page_title         = "| Information";
        $transaction        = Transaction::where('trx_id',$trx_id)->first();
        
        return view('share-link.index',compact(
            'page_title',
            'transaction',
        ));   
    }

    public function downloadPdf($trx_id)
    {
        $transaction             = Transaction::where('trx_id',$trx_id)->first(); 
        $coupon_transaction      = CouponTransaction::with(['coupon','user_coupon'])->where('transaction_id',$transaction->id)->first();
        
        $data   = [
            'transaction'        => $transaction,
            'coupon_transaction' => $coupon_transaction
        ];
        
        $pdf = PDF::loadView('pdf-templates.index', $data);
        
        $basic_settings = BasicSettingsProvider::get();
        
        return $pdf->download($basic_settings->site_name.'-'.$transaction->trx_id.'.pdf');
    }
   
}
