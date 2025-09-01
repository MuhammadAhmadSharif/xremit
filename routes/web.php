<?php

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TemporaryData;
use App\Models\Admin\Currency;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Models\Admin\TransactionSetting;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Frontend\SiteController;
use App\Http\Controllers\User\RemittanceController;
use App\Http\Controllers\Api\V1\User\SendRemittanceController;
use App\Http\Controllers\User\SendRemittanceController as UserSendRemittanceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [SiteController::class,'index'])->name('index');

Route::get('paystack/pay/callback',[UserSendRemittanceController::class,'paystackPayCallBack'])->name('paystack.pay.callback');
Route::get('api/paystack/pay/callback',[SendRemittanceController::class,'paystackPayCallBack'])->name('api.paystack.pay.callback');


Route::post('frontend/request/send-money',function(Request $request) {

    Session::put('frontend_money_req',$request->all());
    
    $validator = Validator::make($request->all(),[
        'type'           => 'required',
        'send_money'     => 'required',
        'receive_money'  => 'required',
    ]);
    if($validator->fails()){
        return back()->with(['error' => ['Please enter send money.']]);
    }
    
    $validated = $validator->validate();
    $receiver_country = Currency::where('status',true)->where('receiver',true)->where('code',$request->receiver_currency)->first();
    $send_money = $validated['send_money'] / $request->sender_base_rate;
        
    $limit_amount = TransactionSetting::where('title',$validated['type'])->first();
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
        $record = TemporaryData::create($data);
    } catch (Exception $e) {
        return back()->with(['error' => ['Something went wrong! Please try again.']]);
    }

    return redirect()->route('user.recipient.index',$record->identifier);
    
    

})->name('frontend.request.send.money');


//landing page

Route::controller(SiteController::class)->name('frontend.')->group(function(){
    Route::get('about','about')->name('about');
    Route::get('how-it-works','howItWorks')->name('howitworks');
    Route::get('web-journal','webJournal')->name('web.journal');
    Route::get('journal-details/{slug}','journalDetails')->name('journal.details');
    Route::get('journal-category/{slug}','journalCategory')->name('journal.category');
    Route::get('contact','contact')->name('contact');
});

Route::post("coupon/apply",[SiteController::class,'couponApply'])->name("coupon.apply");

Route::post("subscribe",[SiteController::class,'subscribe'])->name("subscribe");
Route::post("contact-request",[SiteController::class,'contactRequest'])->name("contact.request");

Route::get('link/{slug}',[SiteController::class,'link'])->name('link');


Route::controller(RemittanceController::class)->prefix('send-remittance')->name('send.remittance.')->group(function(){
    //ssl commerce
    Route::post('sslcommerz/success','sllCommerzSuccess')->name('ssl.success');
    Route::post('sslcommerz/fail','sllCommerzFails')->name('ssl.fail');
    Route::post('sslcommerz/cancel','sllCommerzCancel')->name('ssl.cancel');
});

//for sslcommerz callback urls(api)
Route::controller(SendRemittanceController::class)->prefix("api-send-remittance")->name("api.send.remittance.")->group(function(){
    //sslcommerz
    Route::post('sslcommerz/success','sllCommerzSuccess')->name('ssl.success');
    Route::post('sslcommerz/fail','sllCommerzFails')->name('ssl.fail');
    Route::post('sslcommerz/cancel','sllCommerzCancel')->name('ssl.cancel');    
});