<?php


use App\Http\Controllers\Api\V1\User\AuthorizationController;
use App\Http\Controllers\Api\V1\User\BeneficiaryController;
use App\Http\Controllers\Api\V1\User\MyCouponController;
use App\Http\Controllers\Api\V1\User\NotificationController;
use App\Http\Controllers\Api\V1\User\ProfileController;
use App\Http\Controllers\Api\V1\User\SendRemittanceController;
use App\Http\Controllers\Api\V1\User\TransactionController;
use Illuminate\Support\Facades\Route;

Route::prefix("user")->name("api.user.")->group(function(){

    Route::controller(SendRemittanceController::class)->prefix('send-remittance')->name('send.remittance.')->group(function(){
        
        // POST Route For Unauthenticated Request
        Route::post('success/response/{gateway}', 'postSuccess')->name('payment.success')->withoutMiddleware(['auth:api']);
        Route::post('cancel/response/{gateway}', 'postCancel')->name('payment.cancel')->withoutMiddleware(['auth:api']);
       
        // Automatic Gateway Response Routes
        Route::get('success/response/{gateway}','success')->withoutMiddleware(['auth:api'])->name("payment.success");
        Route::get("cancel/response/{gateway}",'cancel')->withoutMiddleware(['auth:api'])->name("payment.cancel");


        Route::get('manual/input-fields','manualInputFields');  
    });


    Route::middleware('auth:api')->group(function(){
    
        Route::post('google-2fa/otp/verify', [AuthorizationController::class,'verify2FACode']);

        Route::controller(ProfileController::class)->prefix('profile')->group(function(){
            Route::get('info','profileInfo');
            Route::post('info/update','profileInfoUpdate')->middleware('app.mode');
            Route::post('password/update','profilePasswordUpdate')->middleware('app.mode');
            Route::post('delete-account','deleteProfile')->middleware('app.mode');
            Route::get('/google-2fa', 'google2FA')->middleware('app.mode');
            Route::post('/google-2fa/status/update', 'google2FAStatusUpdate')->middleware('app.mode');

            Route::controller(AuthorizationController::class)->prefix('kyc')->group(function(){
                Route::get('input-fields','getKycInputFields');
                Route::post('submit','KycSubmit');
            });
        });



        // Logout Route
        Route::post('logout',[ProfileController::class,'logout']);

        //send remittance 

        Route::controller(SendRemittanceController::class)->prefix('send-remittance')->group(function(){
            Route::get('index','index');
            Route::post('store','store')->middleware(['kyc.verification.guard']);
            Route::get('beneficiary','beneficiary');
            Route::get('beneficiary-add','beneficiaryAdd');
            Route::post('beneficiary-store','beneficiaryStore');
            Route::post('beneficiary-send','beneficiarySend');
            Route::get('receipt-payment','receiptPayment');
            Route::post('receipt-payment-store','receiptPaymentStore');
            Route::post('submit-data','submitData');
            

            //redirect with Btn Pay
            Route::get('redirect/btn/checkout/{gateway}', 'redirectBtnPay')->name('send.remittance.payment.btn.pay')->withoutMiddleware(['auth:api','verification.guard','kyc.verification.guard','user.google.two.factor']);
            
            // Submit with manual gateway
            Route::post("manual/submit","manualSubmit");

            // Automatic gateway additional fields
            Route::get('payment-gateway/additional-fields','gatewayAdditionalFields');
            
            Route::prefix('payment')->name('send.remittance.payment.')->group(function() {
                Route::post('crypto/confirm/{trx_id}','cryptoPaymentConfirm')->name('crypto.confirm');
            });

            
        });

        // beneficiary
        Route::controller(BeneficiaryController::class)->prefix('beneficiary')->group(function(){
            Route::get('index','index');
            Route::get('receiver-country','receiverCountry');
            Route::get('create','create');
            Route::post('store','store');
            Route::post('update','update');
            Route::post('delete','delete');
        });


        // coupon
        Route::controller(MyCouponController::class)->prefix('my-coupon')->group(function(){
            Route::get('/','index');
        });

        //transaction 
        Route::controller(TransactionController::class)->prefix('transaction')->group(function(){
            Route::get('index','index');
        });

       //notification
       Route::controller(NotificationController::class)->group(function(){
        Route::get('notification','notification');
       });

    });
    
});

