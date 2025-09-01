<?php

namespace App\Http\Controllers\User;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TemporaryData;
use App\Constants\GlobalConst;
use App\Models\Admin\Currency;
use App\Models\UserNotification;
use App\Models\Admin\SourceOfFund;
use App\Http\Controllers\Controller;
use App\Models\Admin\SendingPurpose;
use App\Constants\PaymentGatewayConst;
use Illuminate\Support\Facades\Session;
use App\Models\Admin\TransactionSetting;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\PaymentGatewayCurrency;
use App\Traits\PaymentGateway\PaystackGateway;

class SendRemittanceController extends Controller
{
    use PaystackGateway;
    /**
     * Method for show send remittance page
     * @param string 
     * @return view
     */
    public function index(){
        $page_title                 = "| Send Remittance";
        $transaction_settings       = TransactionSetting::where('status',true)
                                        ->whereIn('slug', [
                                            GlobalConst::BANK_TRANSFER,
                                            GlobalConst::MOBILE_MONEY,
                                            GlobalConst::CASH_PICKUP
                                        ])->get();

        $sender_currency            = Currency::where('status',true)->where('sender',true)->get();
        $receiver_currency          = Currency::where('status',true)->where('receiver',true)->get();
        $sender_currency_first      = Currency::where('status',true)->where('sender',true)->first();
        $receiver_currency_first    = Currency::where('status',true)->where('receiver',true)->first();
        $client_ip                  = request()->ip() ?? false;
        $user_country               = geoip()->getLocation($client_ip)['country'] ?? "";
        $user                       = auth()->user();
        $notifications              = UserNotification::where('user_id',$user->id)->latest()->take(10)->get();
        $message                    = Session::get('message');
        
        return view('user.sections.send-remittance.index',compact(
            'page_title',
            'transaction_settings',
            'sender_currency',
            'receiver_currency',
            'user_country',
            'user',
            'notifications',
            'sender_currency_first',
            'receiver_currency_first',
            'message'
        ));
    }
    /**
     * Method for store send remittance 
     * @param string
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request){
        
        $validator = Validator::make($request->all(),[
            'type'           => 'required',
            'send_money'     => 'required',
            'receive_money'  => 'required',
        ]);
        if($validator->fails()){
            return back()->with(['error' => ['Please enter send money.']]);
        }
        
        $validated = $validator->validate();
        $send_money = $validated['send_money'] / $request->sender_base_rate;
        $receiver_country = Currency::where('status',true)->where('receiver',true)->where('code',$request->receiver_currency)->first();
        
        $limit_amount = TransactionSetting::where('title',$validated['type'])->where('status',true)->first();
        
        
        
        $intervals = get_intervals_data($send_money,$limit_amount);
        if($intervals == false){
            return back()->with(['error' => ['Please follow the transaction limit.']]);
        }

        $validated['identifier']    = Str::uuid();
        $data = [
            'type'                  => $validated['type'],
            'identifier'            => $validated['identifier'],
            'data'                  => [
                'send_money'        => $validated['send_money'],
                'fees'              => $request->fees,
                'convert_amount'    => $request->convert_amount,
                'payable_amount'    => $request->payable,
                'payable'           => $request->payable,
                'receive_money'     => $request->receive_money,
                'sender_name'       => auth()->user()->fullname,
                'sender_email'      => auth()->user()->email,
                'sender_currency'   => $request->sender_currency,
                'receiver_currency' => $request->receiver_currency,
                'receiver_country'  => $receiver_country->country,
                'sender_ex_rate'    => $request->sender_ex_rate,
                'sender_base_rate'  => $request->sender_base_rate,
                'receiver_ex_rate'  => $request->receiver_ex_rate,
                'coupon_id'         => $request->coupon_id ?? 0,
                'coupon_type'       => $request->coupon_type ?? '',
            ],
            
        ];
        
        try { 
            $temporary_data = TemporaryData::create($data);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return redirect()->route('user.recipient.index',$temporary_data->identifier);
        
    }
    
    /**
     * Method for show receipt payment page
     * @param string
     * @return view
     */
    public function receiptPayment($identifier){
        $page_title        = "| Receipt Payment";
        $temporary_data    = TemporaryData::where('identifier',$identifier)->first();
  
        $sending_purposes  = SendingPurpose::where('status',true)->get();
        $source_of_funds   = SourceOfFund::where('status',true)->get();
        $payment_gateway   = PaymentGatewayCurrency::whereHas('gateway', function ($gateway) {
            $gateway->where('slug', PaymentGatewayConst::remittance_money_slug());
            $gateway->where('status', 1);
        })->get();
        $client_ip         = request()->ip() ?? false;
        $user_country      = geoip()->getLocation($client_ip)['country'] ?? "";
        $user              = auth()->user();
        $notifications     = UserNotification::where('user_id',$user->id)->latest()->take(10)->get();

        return view('user.sections.send-remittance.receipt-payment',compact(
            'page_title',
            'temporary_data',
            'sending_purposes',
            'source_of_funds',
            'payment_gateway',
            'user_country',
            'user',
            'notifications'
        ));
    }
    /**
     * Method for store receipant payment information
     * @param string $identifier
     * @param Illuminate\Http\Request $request
     */
    public function receipantPaymentStore(Request $request,$identifier){
        $temporary_data          = TemporaryData::where('identifier',$identifier)->first();
        $validator               = Validator::make($request->all(),[
            'sending_purpose'    => 'required|integer',
            'source'             => 'required|integer',
            'remark'             => 'nullable|string',
            'payment_gateway'    => 'required|integer',
        ]);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput($request->all());
        }
        $validated   = $validator->validate();
        $currency = PaymentGatewayCurrency::where('id',$validated['payment_gateway'])->first();
        $source_of_fund = SourceOfFund::where('id',$validated['source'])->first();
        $sending_purpose  = SendingPurpose::where('id',$validated['sending_purpose'])->first();
        
        $rate               = $currency->rate / $temporary_data->data->sender_base_rate;
        
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
                'coupon_id'           => $temporary_data->data->coupon_id ?? 0,
                'coupon_type'         => $temporary_data->data->coupon_type ?? '',
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
                'rate'                => $rate,
            ],
            
        ];
        
        try{
            $temporary_data->update($data);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return redirect()->route('user.send.remittance.receipt.preview',$temporary_data->identifier);
           
    }
    /**
     * Method for show receipt preview page
     * @param string
     * @return view
     */
    public function receiptPreview($identifier){
        $page_title           = "| Receipt Preview";
        $sender_currency      = Currency::where('status',true)->where('sender',true)->first();
        $receiver_currency    = Currency::where('status',true)->where('receiver',true)->first();
        $temporary_data       = TemporaryData::where('identifier',$identifier)->first();
        $client_ip            = request()->ip() ?? false;
        $user_country         = geoip()->getLocation($client_ip)['country'] ?? "";
        $user                 = auth()->user();
        $notifications        = UserNotification::where('user_id',$user->id)->latest()->take(10)->get();
       
        return view('user.sections.send-remittance.receipt-preview',compact(
            'page_title',
            'sender_currency',
            'receiver_currency',
            'temporary_data',
            'user_country',
            'user',
            'notifications'
        ));
    }
    /**
     * Method for paystack pay callback
     */
    public function paystackPayCallBack(Request $request){
        $instance = $this->paystackSuccess($request->all());
        return redirect()->route("user.payment.confirmation",$instance)->with(['success' => ['Successfully Send Remittance']]);
    }
}
