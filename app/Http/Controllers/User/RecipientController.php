<?php

namespace App\Http\Controllers\User;

use App\Constants\GlobalConst;
use Exception;
use App\Models\Recipient;
use Illuminate\Http\Request;
use App\Models\TemporaryData;
use App\Http\Helpers\Response;
use App\Models\Admin\Currency;
use App\Models\UserNotification;
use App\Models\Admin\MobileMethod;
use App\Http\Controllers\Controller;
use App\Models\Admin\BankMethodAutomatic;
use App\Models\Admin\CashPickup;
use App\Models\Admin\RemittanceBank;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RecipientController extends Controller
{
    /**
     * Method for show send remittance recipient page
     * @param $identifier
     * @param Illuminate\Http\Request $request
     */
    public function index(Request $request,$identifier){

        $page_title           = "| Recipients";
        $temporary_data       = TemporaryData::where('identifier',$identifier)->first();
        $recipients           = Recipient::auth()->where('method',$temporary_data->type)->where('country',$temporary_data->data->receiver_country)->orderByDESC('id')->get();   
        $client_ip            = request()->ip() ?? false;
        $user_country         = geoip()->getLocation($client_ip)['country'] ?? "";
        $user                 = auth()->user();
        $notifications        = UserNotification::where('user_id',$user->id)->latest()->take(10)->get();

        return view('user.sections.recipient.index',compact(
            'page_title',
            'recipients',
            'user_country',
            'user',
            'notifications',
            'temporary_data'
        ));
    }
    /**
     * Method for add new recipient information for send remittance
     * @param string $identifier
     * @param Illuminate\Http\Request $request
     */
    public function add(Request $request,$identifier){
        $page_title           = "| Add Recipient";
        $temporary_data       = TemporaryData::where('identifier',$identifier)->first();
        $client_ip            = request()->ip() ?? false;
        $user_country         = geoip()->getLocation($client_ip)['country'] ?? "";
        $user                 = auth()->user();
        $notifications        = UserNotification::where('user_id',$user->id)->latest()->take(10)->get();
        $receiver_currency    = Currency::where('status',true)->where('receiver',true)->where('country',$temporary_data->data->receiver_country)->first();


        $country            = get_specific_country($receiver_currency->country);
        $bank_method_automatic = BankMethodAutomatic::where('status',true)->first();
        if(!$bank_method_automatic){
            $automatic_bank_list  = [];
        }else{
            $automatic_bank_list  = getFlutterwaveBanks($country['country_code']) ?? [];
        }
       
        $manual_bank_list   = RemittanceBank::where('country',$receiver_currency->country)->where('status',true)->get();
        if ($manual_bank_list->isNotEmpty()) {
            $manual_bank_list->each(function ($bank) {
                $bank->name = $bank->name . " (Manual)";
            });
    
            $manual_bank_list_array = $manual_bank_list->toArray();
        } else {
            $manual_bank_list_array = [];
        }
        $bank_methods = array_merge($automatic_bank_list, $manual_bank_list_array);
        
        $mobile_methods       = MobileMethod::where('country',$receiver_currency->country)->where('status',true)->get();
        $pickup_points        = CashPickup::where('country',$receiver_currency->country)->where('status',true)->get();
        

        return view('user.sections.recipient.add',compact(
            'page_title',
            'user_country',
            'user',
            'notifications',
            'receiver_currency',
            'user_country',
            'temporary_data',
            'bank_methods',
            'mobile_methods',
            'pickup_points'
        ));
    }
    /**
     * Method for store recipient with the identifier
     * @param $identifier
     */    
    public function store(Request $request,$identifier){
        $temporary_data       = TemporaryData::where('identifier',$identifier)->first();
        if($request->method == global_const()::RECIPIENT_METHOD_BANK ){

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
                'iban_number'     => 'required|string',
                'address'         => 'nullable|string',
                'document_type'   => 'nullable|string',
                'front_image'     => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
                'back_image'      => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
            ]);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput($request->all());
            }
            $validated   = $validator->validate();
            if($request->hasFile('front_image') || $request->hasFile('back_image')){
                $validated['front_image'] = $this->imageValidate($request,"front_image",null);
                $validated['back_image'] = $this->imageValidate($request,"back_image",null);  
            }
            if(Recipient::where('user_id',auth()->user()->id)->where('email',$validated['email'])->where('method',$validated['method'])->where('bank_name',$validated['bank_name'])->exists()){
                throw ValidationException::withMessages([
                    'name'  => "Recipient already exists!",
                ]);
            }
            $validated['user_id'] = auth()->user()->id;
            $validated['method'] = GlobalConst::TRANSACTION_TYPE_BANK;
            try{
                Recipient::create($validated);
            }catch(Exception $e){
                return back()->with(['error' => ['Something went wrong! Please try again.']]);
            }
            return redirect()->route('user.recipient.index',$temporary_data->identifier);
        }elseif($request->method == global_const()::RECIPIENT_METHOD_MOBILE){
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
                'front_image'     => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
                'back_image'      => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
            ]);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput($request->all());
            }
            $validated   = $validator->validate();
            if($request->hasFile('front_image') || $request->hasFile('back_image')){
                $validated['front_image'] = $this->imageValidate($request,"front_image",null);
                $validated['back_image'] = $this->imageValidate($request,"back_image",null);  
            }
            if(Recipient::where('user_id',auth()->user()->id)->where('email',$validated['email'])->where('method',$validated['method'])->where('mobile_name',$validated['mobile_name'])->exists()){
                throw ValidationException::withMessages([
                    'name'  => "Recipient already exists!",
                ]);
            }
            $validated['user_id'] = auth()->user()->id;
            $validated['method'] = GlobalConst::TRANSACTION_TYPE_MOBILE;
            try{
                Recipient::create($validated);
            }catch(Exception $e){
                return back()->with(['error' => ['Something went wrong! Please try again.']]);
            }
            return redirect()->route('user.recipient.index',$temporary_data->identifier);
        }else{
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
                'pickup_point'    => 'required|string',
                'address'         => 'nullable|string',
                'document_type'   => 'nullable|string',
                'front_image'     => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
                'back_image'      => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
            ]);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput($request->all());
            }
            $validated   = $validator->validate();
            if($request->hasFile('front_image') || $request->hasFile('back_image')){
                $validated['front_image'] = $this->imageValidate($request,"front_image",null);
                $validated['back_image'] = $this->imageValidate($request,"back_image",null);  
            }
            if(Recipient::where('user_id',auth()->user()->id)->where('email',$validated['email'])->where('method',$validated['method'])->where('pickup_point',$validated['pickup_point'])->exists()){
                throw ValidationException::withMessages([
                    'name'  => "Recipient already exists!",
                ]);
            }
            $validated['user_id'] = auth()->user()->id;
            $validated['method'] = GlobalConst::TRANSACTION_TYPE_CASHPICKUP;
            try{
                Recipient::create($validated);
            }catch(Exception $e){
                return back()->with(['error' => ['Something went wrong! Please try again.']]);
            }
            return redirect()->route('user.recipient.index',$temporary_data->identifier);
        }   
    }
    /**
     * Method for send recipient information for send remittance
     * @param $identifier
     */    
    public function send(Request $request,$identifier){
        $recipient = Recipient::where('id',$request->id)->first();

        if(!$recipient){
            return back()->with(['error' => ['Please select a recipient']]);
        }
        $temporary_data = TemporaryData::where('identifier',$identifier)->first();
        $data = [
            'type'                  => $temporary_data->type,
            'identifier'            => $temporary_data->identifier,
            'data'                  => [
                'sender_name'       => auth()->user()->fullname,
                'sender_email'      => auth()->user()->email,
                'sender_currency'   => $temporary_data->data->sender_currency,
                'receiver_currency' => $temporary_data->data->receiver_currency,
                'sender_ex_rate'    => $temporary_data->data->sender_ex_rate,
                'sender_base_rate'  => $temporary_data->data->sender_base_rate,
                'receiver_ex_rate'  => $temporary_data->data->receiver_ex_rate,
                'coupon_id'         => $temporary_data->data->coupon_id ?? 0,
                'coupon_type'       => $temporary_data->data->coupon_type ?? 0,
                'first_name'        => $recipient->first_name,
                'middle_name'       => $recipient->middle_name ?? '',
                'last_name'         => $recipient->last_name,
                'email'             => $recipient->email ?? 'N/A',
                'country'           => $recipient->country,
                'city'              => $recipient->city ?? 'N/A',
                'state'             => $recipient->state ?? 'N/A',
                'zip_code'          => $recipient->zip_code ?? 'N/A',
                'phone'             => $recipient->phone ?? 'N/A',
                'method_name'       => $recipient->bank_name ?? ($recipient->mobile_name ?? $recipient->pickup_point),
                'account_number'    => $recipient->iban_number ?? ($recipient->account_number ?? 'N/A'),
                'address'           => $recipient->address ?? 'N/A',
                'document_type'     => $recipient->document_type ?? '',
                'front_image'       => $recipient->front_image ?? '',
                'back_image'        => $recipient->back_image ?? '',
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
            return back()->with(['error' => ['Something went wrong! PLease try again.']]);
        }
        return redirect()->route('user.send.remittance.receipt.payment',$temporary_data->identifier);

    }
    /**
     * Method for show beneficiary side 
     * @return view
     */
    public function show(){
        $page_title           = "| Recipients";
        $recipients           = Recipient::where('user_id',auth()->user()->id)->orderByDESC('id')->paginate(10);   
        $client_ip            = request()->ip() ?? false;
        $user_country         = geoip()->getLocation($client_ip)['country'] ?? "";
        $user                 = auth()->user();
        $notifications        = UserNotification::where('user_id',$user->id)->latest()->take(10)->get();

        return view('user.sections.recipient.show',compact(
            'page_title',
            'recipients',
            'user_country',
            'user',
            'notifications',
            
        ));
    }
    /**
     * Method for create beneficiary information
     * @return view
     */
    public function create(){
        $page_title           = "| Create Recipient";
        $client_ip            = request()->ip() ?? false;
        $user_country         = geoip()->getLocation($client_ip)['country'] ?? "";
        $user                 = auth()->user();
        $notifications        = UserNotification::where('user_id',$user->id)->latest()->take(10)->get();
        $receiver_currency    = Currency::where('status',true)->where('receiver',true)->get();

        return view('user.sections.recipient.create',compact(
            'page_title',
            'user_country',
            'user',
            'notifications',
            'receiver_currency',
            'user_country',
        ));
    }
    /**
     * Method for get the bank list data
     */
    public function getBank(Request $request){
        $validator    = Validator::make($request->all(),[
            'country'  => 'required',           
        ]);
        if($validator->fails()) {
            return Response::error($validator->errors()->all());
        }
        $user_country       = Currency::where('country',$request->country)->first();
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

        return Response::success(['Data fetch successfully'],['bank_list' => $bank_list],200);
    }
    /**
     * Method for store recipient information
     * @return view
     */
    public function recipientDataStore(Request $request){
        
        if($request->method == global_const()::RECIPIENT_METHOD_BANK){
           
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
                'iban_number'     => 'required|string',
                'address'         => 'nullable|string',
                'document_type'   => 'nullable|string',
                'front_image'     => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
                'back_image'      => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
            ]);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput($request->all());
            }
            $validated   = $validator->validate();
            if($request->hasFile('front_image') || $request->hasFile('back_image')){
                $validated['front_image'] = $this->imageValidate($request,"front_image",null);
                $validated['back_image'] = $this->imageValidate($request,"back_image",null);  
            }
            if(Recipient::where('user_id',auth()->user()->id)->where('email',$validated['email'])->where('method',$validated['method'])->where('bank_name',$validated['bank_name'])->exists()){
                throw ValidationException::withMessages([
                    'name'  => "Recipient already exists!",
                ]);
            }
            $validated['user_id'] = auth()->user()->id;
            $validated['method'] = GlobalConst::TRANSACTION_TYPE_BANK;
            try{
                Recipient::create($validated);
            }catch(Exception $e){
                return back()->with(['error' => ['Something went wrong! Please try again.']]);
            }
            return redirect()->route('user.recipient.show')->with(['success' => ['Recipient Created Successfully.']]);
        }elseif($request->method == global_const()::RECIPIENT_METHOD_MOBILE){
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
                'front_image'     => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
                'back_image'      => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
            ]);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput($request->all());
            }
            $validated   = $validator->validate();
            if($request->hasFile('front_image') || $request->hasFile('back_image')){
                $validated['front_image'] = $this->imageValidate($request,"front_image",null);
                $validated['back_image'] = $this->imageValidate($request,"back_image",null);  
            }
            if(Recipient::where('user_id',auth()->user()->id)->where('email',$validated['email'])->where('method',$validated['method'])->where('mobile_name',$validated['mobile_name'])->exists()){
                throw ValidationException::withMessages([
                    'name'  => "Recipient already exists!",
                ]);
            }
            $validated['user_id'] = auth()->user()->id;
            $validated['method'] = GlobalConst::TRANSACTION_TYPE_MOBILE;
            try{
                Recipient::create($validated);
            }catch(Exception $e){

                return back()->with(['error' => ['Something went wrong! Please try again.']]);
            }
            return redirect()->route('user.recipient.show')->with(['success' => ['Recipient Created Successfully.']]);
        }else{
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
                'pickup_point'    => 'required|string',
                'address'         => 'nullable|string',
                'document_type'   => 'nullable|string',
                'front_image'     => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
                'back_image'      => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
            ]);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput($request->all());
            }
            $validated   = $validator->validate();
            if($request->hasFile('front_image') || $request->hasFile('back_image')){
                $validated['front_image'] = $this->imageValidate($request,"front_image",null);
                $validated['back_image'] = $this->imageValidate($request,"back_image",null);  
            }
            if(Recipient::where('user_id',auth()->user()->id)->where('email',$validated['email'])->where('method',$validated['method'])->where('pickup_point',$validated['pickup_point'])->exists()){
                throw ValidationException::withMessages([
                    'name'  => "Recipient already exists!",
                ]);                         
            }
            $validated['user_id'] = auth()->user()->id;
            $validated['method'] = GlobalConst::TRANSACTION_TYPE_CASHPICKUP;
            try{
                Recipient::create($validated);
            }catch(Exception $e){

                return back()->with(['error' => ['Something went wrong! Please try again.']]);
            }
            return redirect()->route('user.recipient.show')->with(['success' => ['Recipient Created Successfully.']]);
        }  
    }
    /**
     * Method for show the edit page for recipient
     * @param $id
     * @param Illuminate\Http\Request $request
     */
    public function edit($id){
        $page_title           = "| Edit Recipient";
        $recipient            = Recipient::where('id',$id)->first();
        $user                 = auth()->user();
        $client_ip            = request()->ip() ?? false;
        $user_country         = geoip()->getLocation($client_ip)['country'] ?? "";
        $notifications        = UserNotification::where('user_id',$user->id)->latest()->take(10)->get();
        $receiver_currency    = Currency::where('status',true)->where('receiver',true)->get();
        $mobile_methods       = MobileMethod::where('country',$recipient->country)->where('status',true)->get();
        $pickup_points        = CashPickup::where('country',$recipient->country)->where('status',true)->get();

        return view('user.sections.recipient.edit',compact(
            'page_title',
            'user_country',
            'user',
            'notifications',
            'receiver_currency',
            'user_country',
            'recipient',
            'mobile_methods',
            'pickup_points'
        ));
    }
    /**
     * Method for update recipient information
     * @param $id
     * @param Illuminate\Http\Request $request
     */
    public function update(Request $request,$id){
        $recipient    = Recipient::where('id',$id)->first();
        if($request->method == global_const()::RECIPIENT_METHOD_BANK ){

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
                'iban_number'     => 'required|string',
                'address'         => 'nullable|string',
                'document_type'   => 'nullable|string',
                'front_image'     => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
                'back_image'      => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
            ]);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput($request->all());
            }
            $validated   = $validator->validate();
            if($request->hasFile('front_image') || $request->hasFile('back_image')){
                $validated['front_image'] = $this->imageValidate($request,"front_image",null);
                $validated['back_image'] = $this->imageValidate($request,"back_image",null);  
            }
            if(Recipient::where('user_id',auth()->user()->id)->where('email',$validated['email'])->where('method',$validated['method'])->where('bank_name',$validated['bank_name'])->exists()){
                throw ValidationException::withMessages([
                    'name'  => "Recipient already exists!",
                ]);
            }
            $validated['user_id'] = auth()->user()->id;
            $validated['method'] = GlobalConst::TRANSACTION_TYPE_BANK;
            try{
                $recipient->update($validated);
            }catch(Exception $e){

                return back()->with(['error' => ['Something went wrong! Please try again.']]);
            }
            return redirect()->route('user.recipient.show')->with(['success' => ['Recipient Updated Successfully.']]);
        }elseif($request->method == global_const()::RECIPIENT_METHOD_MOBILE){
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
                'front_image'     => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
                'back_image'      => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
            ]);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput($request->all());
            }
            $validated   = $validator->validate();
            if($request->hasFile('front_image') || $request->hasFile('back_image')){
                $validated['front_image'] = $this->imageValidate($request,"front_image",null);
                $validated['back_image'] = $this->imageValidate($request,"back_image",null);  
            }
            if(Recipient::where('user_id',auth()->user()->id)->where('email',$validated['email'])->where('method',$validated['method'])->where('mobile_name',$validated['mobile_name'])->exists()){
                throw ValidationException::withMessages([
                    'name'  => "Recipient already exists!",
                ]);
            }
            $validated['user_id'] = auth()->user()->id;
            $validated['method'] = GlobalConst::TRANSACTION_TYPE_MOBILE;
        
            try{
                $recipient->update($validated);
            }catch(Exception $e){

                return back()->with(['error' => ['Something went wrong! Please try again.']]);
            }
            return redirect()->route('user.recipient.show')->with(['success' => ['Recipient Updated Successfully.']]);
        }else{
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
                'pickup_point'    => 'required|string',
                'address'         => 'nullable|string',
                'document_type'   => 'nullable|string',
                'front_image'     => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
                'back_image'      => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
            ]);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput($request->all());
            }
            $validated   = $validator->validate();
            if($request->hasFile('front_image') || $request->hasFile('back_image')){
                $validated['front_image'] = $this->imageValidate($request,"front_image",null);
                $validated['back_image'] = $this->imageValidate($request,"back_image",null);  
            }
            if(Recipient::where('user_id',auth()->user()->id)->where('email',$validated['email'])->where('method',$validated['method'])->where('pickup_point',$validated['pickup_point'])->exists()){
                throw ValidationException::withMessages([
                    'name'  => "Recipient already exists!",
                ]);
            }
            $validated['user_id'] = auth()->user()->id;
            $validated['method'] = GlobalConst::TRANSACTION_TYPE_CASHPICKUP;
        
            try{
                $recipient->update($validated);
            }catch(Exception $e){

                return back()->with(['error' => ['Something went wrong! Please try again.']]);
            }
            return redirect()->route('user.recipient.show')->with(['success' => ['Recipient Updated Successfully.']]);
        }
    }
    /**
     * Method for delete recipient information
     * @param $id
     * @param Illuminate\Http\Request $request
     */
    public function delete($id){
        $recipient    = Recipient::where('id',$id)->first();
        try{
            $recipient->delete();
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }
        return back()->with(['success' => ['Recipient Deleted Successfully.']]);
    }
    /**
     * Method for validate image 
     */
    public function imageValidate($request,$input_name,$old_image = null) {
        if($request->hasFile($input_name)) {
            $image_validated = Validator::make($request->only($input_name),[
                $input_name         => "image|mimes:png,jpg,webp,jpeg,svg",
            ])->validate();

            $image = get_files_from_fileholder($request,$input_name);
            $upload = upload_files_from_path_dynamic($image,'site-section',$old_image);
            return $upload;
        }
        return false;
    }
}
