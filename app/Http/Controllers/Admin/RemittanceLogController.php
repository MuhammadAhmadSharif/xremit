<?php

namespace App\Http\Controllers\Admin;

use PDF;
use Exception;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\Admin\Currency;
use App\Models\UserNotification;
use App\Http\Controllers\Controller;
use App\Models\Admin\BasicSettings;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\remittanceNotification;
use App\Providers\Admin\BasicSettingsProvider;

class RemittanceLogController extends Controller
{
    /**
     * Method for show send remittance page
     * @param string
     * @return view
     */
    public function index(){
        $page_title           = "All Logs";
        $transactions         = Transaction::doesntHave('coupon_transaction')->orderBy('id','desc')->paginate(10);
        $sender_currency      = Currency::where('status',true)->where('sender',true)->first();
        $receiver_currency    = Currency::where('status',true)->where('receiver',true)->first();
        

        return view('admin.sections.remittance-log.all-logs',compact(
            'page_title',
            'transactions',
            'sender_currency',
            'receiver_currency',
        ));
    }
    /**
     * Method for show send remittance details page
     * @param $trx_id,
     * @param Illuminate\Http\Request $request
     */
    public function details(Request $request, $trx_id){
        $page_title        = "Log Details";
        $transaction       = Transaction::where('trx_id',$trx_id)->first();
        $sender_currency   = Currency::where('status',true)->where('sender',true)->first();
        $receiver_currency = Currency::where('status',true)->where('receiver',true)->first();

        
        return view('admin.sections.remittance-log.details',compact(
            'page_title',
            'transaction',
            'sender_currency',
            'receiver_currency',
        ));
    }
    /**
     * Method for update status 
     * @param $trx_id
     * @param Illuminate\Http\Request $request
     */
    public function statusUpdate(Request $request,$trx_id){
        $basic_settings  = BasicSettings::first();
        
        $validator = Validator::make($request->all(),[
            'status'            => 'required|integer',
        ]);

        if($validator->fails()) {
            $errors = ['error' => $validator->errors() ];
            return Response::error($errors);
        }

        $validated = $validator->validate();
        $transaction   = Transaction::where('trx_id',$trx_id)->first();
        
        $form_data = [
            'trx_id'         => $transaction->trx_id,
            'payable_amount' => $transaction->payable,
            'get_amount'     => $transaction->will_get_amount,
            'status'         => $validated['status'],
        ];
        try{
            
            $transaction->update([
                'status' => $validated['status'],
            ]);
            if($basic_settings->email_notification == true){
                Notification::route("mail",$transaction->remittance_data->sender_email)->notify(new remittanceNotification($form_data));
            }
            if(auth()->check()){
                UserNotification::create([
                    'user_id'  => auth()->user()->id,
                    'message'  => "Your Remittance  (Payable amount: ".get_amount($transaction->payable).",
                    Get Amount: ".get_amount($transaction->will_get_amount).") Successfully Sended.", 
                ]);
            }
        }catch(Exception $e){
            
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Transaction Status updated successfully']]);
    }
    /**
     * Method for show under review page 
     * @param string
     * @return view
     */
    public function reviewPayment(){
        $page_title    = "Review Payment Logs";
        $transactions  = Transaction::doesntHave('coupon_transaction')
                            ->where('status',global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT)
                            ->orderBy('id','desc')->paginate(10);

        return view('admin.sections.remittance-log.review-payment',compact(
            'page_title',
            'transactions',
        ));
    }
    /**
     * Method for show Pending log page 
     * @param string
     * @return view
     */
    public function pending(){
        $page_title    = "Pending Logs";
        $transactions  = Transaction::doesntHave('coupon_transaction')
                            ->where('status',global_const()::REMITTANCE_STATUS_PENDING)
                            ->orderBy('id','desc')->paginate(10);

        return view('admin.sections.remittance-log.pending',compact(
            'page_title',
            'transactions',
        ));
    }
    /**
     * Method for show progress log page 
     * @param string
     * @return view
     */
    public function confirmPayment(){
        $page_title    = "Confirm Payment Logs";
        $transactions  = Transaction::doesntHave('coupon_transaction')
                            ->where('status',global_const()::REMITTANCE_STATUS_CONFIRM_PAYMENT)
                            ->orderBy('id','desc')->paginate(10);

        return view('admin.sections.remittance-log.confirm-payment',compact(
            'page_title',
            'transactions',
        ));
    }
    /**
     * Method for show hold log page 
     * @param string
     * @return view
     */
    public function hold(){
        $page_title    = "Hold Logs";
        $transactions  = Transaction::doesntHave('coupon_transaction')
                            ->where('status',global_const()::REMITTANCE_STATUS_HOLD)
                            ->orderBy('id','desc')->paginate(10);

        return view('admin.sections.remittance-log.hold',compact(
            'page_title',
            'transactions',
        ));
    }
    /**
     * Method for show settle log page 
     * @param string
     * @return view
     */
    public function settled(){
        $page_title    = "Settled Logs";
        $transactions  = Transaction::doesntHave('coupon_transaction')
                            ->where('status',global_const()::REMITTANCE_STATUS_SETTLED)
                            ->orderBy('id','desc')->paginate(10);

        return view('admin.sections.remittance-log.settled',compact(
            'page_title',
            'transactions',
        ));
    }
    /**
     * Method for show Complete log page 
     * @param string
     * @return view
     */
    public function complete(){
        $page_title    = "Complete Logs";
        $transactions  = Transaction::doesntHave('coupon_transaction')
                            ->where('status',global_const()::REMITTANCE_STATUS_COMPLETE)
                            ->orderBy('id','desc')->paginate(10);

        return view('admin.sections.remittance-log.complete',compact(
            'page_title',
            'transactions',
        ));
    }
    /**
     * Method for show canceled log page 
     * @param string
     * @return view
     */
    public function canceled(){
        $page_title    = "Canceled Logs";
        $transactions  = Transaction::doesntHave('coupon_transaction')
                            ->where('status',global_const()::REMITTANCE_STATUS_CANCEL)
                            ->orderBy('id','desc')->paginate(10);

        return view('admin.sections.remittance-log.cancel',compact(
            'page_title',
            'transactions',
        ));
    }
    /**
     * Method for show failed log page 
     * @param string
     * @return view
     */
    public function failed(){
        $page_title    = "Failed Logs";
        $transactions  = Transaction::doesntHave('coupon_transaction')
                            ->where('status',global_const()::REMITTANCE_STATUS_FAILED)
                            ->orderBy('id','desc')->paginate(10);

        return view('admin.sections.remittance-log.failed',compact(
            'page_title',
            'transactions',
        ));
    }
    /**
     * Method for show refunded page 
     * @param string
     * @return view
     */
    public function refunded(){
        $page_title    = "Refunded Logs";
        $transactions  = Transaction::doesntHave('coupon_transaction')
                            ->where('status',global_const()::REMITTANCE_STATUS_REFUND)
                            ->orderBy('id','desc')->paginate(10);

        return view('admin.sections.remittance-log.refunded',compact(
            'page_title',
            'transactions',
        ));
    }
    /**
     * Method for show delayed log page 
     * @param string
     * @return view
     */
    public function delayed(){
        $page_title    = "Delayed Logs";
        $transactions  = Transaction::doesntHave('coupon_transaction')
                            ->where('status',global_const()::REMITTANCE_STATUS_DELAYED)
                            ->orderBy('id','desc')->paginate(10);

        return view('admin.sections.remittance-log.delayed',compact(
            'page_title',
            'transactions',
        ));
    }
    /**
     * Method for remittance log search 
     */
   
    public function search(Request $request) {
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);
        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        
        $transactions    = Transaction::search($validated['text'])->get();
       
        return view('admin.components.search.remittance-search',compact('transactions'));
        
    }
    /**
     * Method for remittance log search 
     */
    
    public function reviewSearch(Request $request) {
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);
        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        
        $transactions    = Transaction::where('status',global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT)->search($validated['text'])->get();
       
        return view('admin.components.search.review-search',compact('transactions'));
        
    }
    /**
     * Method for remittance log search 
     */
    public function cancelSearch(Request $request) {
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);
        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        
        $transactions    = Transaction::where('status',global_const()::REMITTANCE_STATUS_CANCEL)->search($validated['text'])->get();
       
        return view('admin.components.search.cancel-search',compact('transactions'));
        
    }
    /**
     * Method for remittance log search 
     */
    public function completeSearch(Request $request) {
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);
        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        
        $transactions    = Transaction::where('status',global_const()::REMITTANCE_STATUS_COMPLETE)->search($validated['text'])->get();
       
        return view('admin.components.search.complete-search',compact('transactions'));
        
    }
    /**
     * Method for remittance log search 
     */
    
    public function confirmPaymentSearch(Request $request) {
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);
        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        
        $transactions    = Transaction::where('status',global_const()::REMITTANCE_STATUS_CONFIRM_PAYMENT)->search($validated['text'])->get();
       
        return view('admin.components.search.confirm-payment-search',compact('transactions'));
        
    }
    /**
     * Method for remittance log search 
     */
    
    public function holdSearch(Request $request) {
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);
        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        
        $transactions    = Transaction::where('status',global_const()::REMITTANCE_STATUS_HOLD)->search($validated['text'])->get();
       
        return view('admin.components.search.hold-search',compact('transactions'));
        
    }
    /**
     * Method for remittance log search 
     */
    
    public function settledSearch(Request $request) {
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);
        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        
        $transactions    = Transaction::where('status',global_const()::REMITTANCE_STATUS_SETTLED)->search($validated['text'])->get();
       
        return view('admin.components.search.settled-search',compact('transactions'));
        
    }
    /**
     * Method for remittance log search 
     */
    
    public function pendingSearch(Request $request) {
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);
        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        
        $transactions    = Transaction::where('status',global_const()::REMITTANCE_STATUS_PENDING)->search($validated['text'])->get();
       
        return view('admin.components.search.pending-search',compact('transactions'));
        
    }
    /**
     * Method for remittance log search 
     */
    
    public function delayedSearch(Request $request) {
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);
        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        
        $transactions    = Transaction::where('status',global_const()::REMITTANCE_STATUS_DELAYED)->search($validated['text'])->get();
       
        return view('admin.components.search.delayed-search',compact('transactions'));
        
    }
    /**
     * Method for remittance log search 
     */
    
    public function failedSearch(Request $request) {
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);
        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        
        $transactions    = Transaction::where('status',global_const()::REMITTANCE_STATUS_FAILED)->search($validated['text'])->get();
       
        return view('admin.components.search.failed-search',compact('transactions'));
        
    }
    /**
     * Method for remittance log search 
     */
    
    public function refundedSearch(Request $request) {
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);
        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        
        $transactions    = Transaction::where('status',global_const()::REMITTANCE_STATUS_REFUND)->search($validated['text'])->get();
       
        return view('admin.components.search.refunded-search',compact('transactions'));
        
    }


    public function downloadPdf($trx_id)
    {
        $transaction             = Transaction::where('trx_id',$trx_id)->first(); 
        $sender_currency         = Currency::where('status',true)->where('sender',true)->first();
        $receiver_currency       = Currency::where('status',true)->where('receiver',true)->first();

        $data   = [
            'transaction'        => $transaction,
            'sender_currency'    => $sender_currency,
            'receiver_currency'  => $receiver_currency,
        ];
        
        $pdf = PDF::loadView('pdf-templates.index', $data);
        
        $basic_settings = BasicSettingsProvider::get();
        
        return $pdf->download($basic_settings->site_name.'-'.$transaction->trx_id.'.pdf');
    }
}
