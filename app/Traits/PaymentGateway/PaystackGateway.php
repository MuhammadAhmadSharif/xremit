<?php 
namespace App\Traits\PaymentGateway;

use Paystack;
use Exception;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\TemporaryData;
use App\Constants\GlobalConst;
use App\Models\UserNotification;
use App\Models\CouponTransaction;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\BasicSettings;
use App\Constants\PaymentGatewayConst;
use App\Models\Admin\AdminNotification;
use Illuminate\Support\Facades\Redirect;
use App\Notifications\paypalNotification;
use App\Http\Helpers\PushNotificationHelper;
use App\Http\Helpers\Response;
use App\Models\User;
use App\Notifications\User\PaystackEmailNotification;
use Illuminate\Support\Facades\Notification;


trait PaystackGateway {
    
    public function paystackInit($output = null) {
        $gateway = new \stdClass();
        
        foreach ($output['gateway']->credentials as $credential) {
            if ($credential->name === 'secret-key') {
                $gateway->secret_key = $credential->value;
            } elseif ($credential->name === 'email') {
                $gateway->email = $credential->value;
            }
        }
        $amount = get_amount($output['amount']->total_amount, null, 2) * 100;
        $temp_record_token = generate_unique_string('temporary_datas','identifier',60);
        $junkData       = $this->paystackJunkInsert($output,$temp_record_token);
        $url = "https://api.paystack.co/transaction/initialize";
        if(get_auth_guard() == 'api'){
            $fields             = [
                'email'         => auth()->user()->email,
                'amount'        => $amount,
                'currency'      => $output['currency']->currency_code,
                'callback_url'  => route('api.paystack.pay.callback'). '?output='. $junkData->identifier
            ];
        }else{
            $fields             = [
                'email'         => auth()->user()->email,
                'amount'        => $amount,
                'currency'      => $output['currency']->currency_code,
                'callback_url'  => route('paystack.pay.callback'). '?output='. $junkData->identifier
            ];
        }

        $fields_string = http_build_query($fields);

        //open connection
        $ch = curl_init();
        
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer $gateway->secret_key",
            "Cache-Control: no-cache",
        ));
        
        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
        
        //execute post
        $result = curl_exec($ch);
        $response   = json_decode($result);
        
        if($response->status == true) {
            if(get_auth_guard() == 'api'){
                
                $response->data = [
                    'redirect_url' => $response->data->authorization_url,
                    'redirect_links' => '',
                    'gateway_type' => PaymentGatewayConst::AUTOMATIC,
                    'access_code' => $response->data->access_code,
                    'reference' => $response->data->reference,
                ];
                return $response->data;
            }else{
                return redirect($response->data->authorization_url)->with('output',$output);
            }
        } else {
            $output['status'] = 'error';
            $output['message'] = $response->message;
            return back()->with(['error' => [$output['message']]]);
        }
    }
    /**
     * function for junk insert
     */
    public function paystackJunkInsert($output,$temp_identifier){
        $output = $this->output;
        
        $data = [
            'gateway'       => $output['gateway']->id,
            'currency'      => [
                'id'        => $output['currency']->id,
                'alias'     => $output['currency']->alias
            ],
            'payment_method'=> $output['currency'],
            'amount'        => json_decode(json_encode($output['amount']),true),
            'response'      => $output,
            'creator_table' => auth()->guard(get_auth_guard())->user()->getTable(),
            'creator_id'    => auth()->guard(get_auth_guard())->user()->id,
            'creator_guard' => get_auth_guard(),
            'user_record'   => $output['form_data']['identifier'],
        ];
       
        return TemporaryData::create([
            'type'          => PaymentGatewayConst::PAYSTACK,
            'identifier'    => $temp_identifier,
            'data'          => $data,
        ]);
    }
    // function paystack success
    function paystackSuccess($request){
        $reference = $request['reference'];
        $identifier = $request['output'];
        $temp_data  = TemporaryData::where('identifier',$identifier)->first();
        
        $curl = curl_init();
        $secret_key = '';
        foreach ($temp_data->data->response->gateway->credentials as $credential) {
            if ($credential->name === 'secret-key') {
                $secret_key = $credential->value;
                break;
            }
        }
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.paystack.co/transaction/verify/$reference",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer $secret_key",
            "Cache-Control: no-cache",
          ),
        ));
        
        $result = curl_exec($curl);
        $response   = json_decode($result);
        $responseArray = [
            'type' => $temp_data->data->response->type,
            'gateway' => $temp_data->data->response->gateway, // Converts the object to an array
            'currency' => $temp_data->data->response->currency, // Converts the object to an array
            'amount' => $temp_data->data->response->amount, // Converts the object to an array
            'form_data' => [
                'identifier' => $temp_data->data->response->form_data->identifier
            ], // Assuming this is already an array
            'distribute' => $temp_data->data->response->distribute,
            'record_handler' => $temp_data->data->response->record_handler,
            'capture' => $response->data->reference,
            'junk_indentifier' => $identifier
        ];
        if($response->status == true){
            $status = global_const()::REMITTANCE_STATUS_CONFIRM_PAYMENT;
            try{
                $transaction_response = $this->createTransaction($responseArray,$status);
            }catch(Exception $e) {
                throw new Exception($e->getMessage());
            }
            return $transaction_response;
        }
    }
    // Update Code (Need to check)
    public function createTransaction($output, $status,$temp_remove = true) {
        $basic_setting = BasicSettings::first();
        
        $record_handler = $output['record_handler'];
        $data = TemporaryData::where('identifier',$output['form_data']['identifier'])->first();
        $junk_data = TemporaryData::where('identifier',$output['junk_indentifier'])->first();
        
        $user = User::where('id',$junk_data->data->creator_id)->first();
        $inserted_id = $this->$record_handler($output,$status);
     
        UserNotification::create([
            'user_id'  => $user->id,
            'message'  => "Your Remittance  (Payable amount: ".get_amount($output['amount']->total_amount)." ". $data->data->sender_currency .",
            Get Amount: ".get_amount($output['amount']->will_get)." ". $data->data->receiver_currency .") Successfully Sended.", 
        ]);
        $trx_id     = Transaction::where('id',$inserted_id)->first();
        
        if($basic_setting->email_notification == true){
            Notification::route("mail",$user->email)->notify(new paypalNotification($user,$output,$trx_id->trx_id));
        }

        $notification_message = [
            'title'     => "Send Remittance from " . "(" . $user->username . ")" . "Transaction ID :". $trx_id->trx_id . " created successfully.",
            'time'      => Carbon::now()->diffForHumans(),
            'image'     => get_image($user->image,'user-profile'),
        ];
        AdminNotification::create([
            'type'      => "Send Remittance",
            'admin_id'  => 1,
            'message'   => $notification_message,
        ]);
        // (new PushNotificationHelper())->prepare([1],[
        //     'title' => "Send Remittance from " . "(" . $user->username . ")" . "Transaction ID :". $trx_id->trx_id . " created successfully.",
        //     'desc'  => "",
        //     'user_type' => 'admin',
        // ])->send();

        if($temp_remove == true) {
            $this->removeTempData($output);
        }

             

        if($this->requestIsApiUser()) {
            // logout user
            $api_user_login_guard = $this->output['api_login_guard'] ?? null;
            if($api_user_login_guard != null) {
                auth()->guard($api_user_login_guard)->logout();
            }
        }
        return $this->output['trx_id'] ?? "";
        
    }
    public function requestIsApiUser() {
        $request_source = request()->get('r-source');
        if($request_source != null && $request_source == PaymentGatewayConst::APP) return true;
        return false;
    }
    

    public function insertRecordWeb($output, $status) {
        $data  = TemporaryData::where('identifier',$output['form_data']['identifier'])->first();
       
        $user = auth()->guard('web')->user();
        
        
        $trx_id = generateTrxString("transactions","trx_id","SR",8);
       
        DB::beginTransaction();
        try{
            $id = DB::table("transactions")->insertGetId([
                'user_id'                       => $user->id,
                'payment_gateway_currency_id'   => $output['currency']->id,
                'type'                          => $output['type'],
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
                    'user_record'               => $output['form_data']['identifier']
                ]),
                'trx_id'                        => $trx_id,
                'request_amount'                => $data->data->send_money,
                'exchange_rate'                 => $output['amount']->sender_cur_rate,
                'payable'                       => $output['amount']->total_amount,
                'fees'                          => $output['amount']->total_charge,
                'convert_amount'                => $output['amount']->convert_amount,
                'will_get_amount'               => $output['amount']->will_get,
                'remark'                        => $output['gateway']->name,
                'details'                       => json_encode(['gateway_response' => $output['capture'],'data' => $data->data,'user_record' => $output['form_data']['identifier']]),
                'status'                        => $status,
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

            DB::commit();
        }catch(Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
        $this->output['trx_id'] = $trx_id;
        return $id;
    }
    public function insertRecordApi($output, $status) {
        $data  = TemporaryData::where('identifier',$output['form_data']['identifier'])->first();
       
        $junk_data = TemporaryData::where('identifier',$output['junk_indentifier'])->first();
        
        $user = User::where('id',$junk_data->data->creator_id)->first();
        
        $trx_id = generateTrxString("transactions","trx_id","SR",8);
       
        DB::beginTransaction();
        try{
            $id = DB::table("transactions")->insertGetId([
                'user_id'                       => $user->id,
                'payment_gateway_currency_id'   => $output['currency']->id,
                'type'                          => $output['type'],
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
                    'user_record'               => $output['form_data']['identifier']
                ]),
                'trx_id'                        => $trx_id,
                'request_amount'                => $data->data->send_money,
                'exchange_rate'                 => $output['amount']->sender_cur_rate,
                'payable'                       => $output['amount']->total_amount,
                'fees'                          => $output['amount']->total_charge,
                'convert_amount'                => $output['amount']->convert_amount,
                'will_get_amount'               => $output['amount']->will_get,
                'remark'                        => $output['gateway']->name,
                'details'                       => json_encode(['gateway_response' => $output['capture'],'data' => $data->data,'user_record' => $output['form_data']['identifier']]),
                'status'                        => $status,
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

            DB::commit();
        }catch(Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
        $this->output['trx_id'] = $trx_id;
        return $id;
    }

    public function removeTempData($output) {
        try{
            $id = $output['tempData']['id'];
            TemporaryData::find($id)->delete();
        }catch(Exception $e) {
            // handle error
        }
    }
    
}


?>