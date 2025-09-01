<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Providers\Admin\BasicSettingsProvider;
use Pusher\PushNotifications\PushNotifications;
use App\Http\Controllers\Admin\CookieController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\JournalController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\SetupKycController;
use App\Http\Controllers\Admin\UserCareController;
use App\Http\Controllers\Admin\AdminCareController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StatementController;
use App\Http\Controllers\Admin\SubscribeController;
use App\Http\Controllers\Admin\ExtensionsController;
use App\Http\Controllers\Admin\ServerInfoController;
use App\Http\Controllers\Admin\SetupEmailController;
use App\Http\Controllers\Admin\SetupPagesController;
use App\Http\Controllers\Admin\UsefulLinkController;
use App\Http\Controllers\Admin\AppSettingsController;
use App\Http\Controllers\Admin\CryptoAssetController;
use App\Http\Controllers\Admin\TrxSettingsController;
use App\Http\Controllers\Admin\WebSettingsController;
use App\Http\Controllers\Admin\BroadcastingController;
use App\Http\Controllers\Admin\MobileMethodController;
use App\Http\Controllers\Admin\NewUserBonusController;
use App\Http\Controllers\Admin\SourceOfFundController;
use App\Http\Controllers\Admin\RemittanceLogController;
use App\Http\Controllers\Admin\SetupSectionsController;
use App\Http\Controllers\Admin\SupportTicketController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\CountrySectionController;
use App\Http\Controllers\Admin\RemittanceBankController;
use App\Http\Controllers\Admin\SendingPurposeController;
use App\Http\Controllers\Admin\PaymentGatewaysController;
use App\Http\Controllers\Admin\CashPickupMethodController;
use App\Http\Controllers\Admin\PushNotificationController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AppOnboardScreensController;
use App\Http\Controllers\Admin\CouponTransactionController;
use App\Http\Controllers\Admin\SystemMaintenanceController;
use App\Http\Controllers\Admin\WebJournalCategoryController;
use App\Http\Controllers\Admin\BankMethodAutomaticController;
use App\Http\Controllers\Admin\LiveExchangeRateApiController;
use App\Http\Controllers\Admin\PaymentGatewayCurrencyController;
use App\Http\Controllers\Admin\ReceivingMethodCategoryController;

// All Admin Route Is Here
Route::prefix('admin')->name('admin.')->group(function () {

    // Dashboard Section
    Route::controller(DashboardController::class)->group(function () {
        Route::get('dashboard', 'index')->name('dashboard');
        Route::post('logout', 'logout')->name('logout');
        Route::post('notifications/clear','notificationsClear')->name('notifications.clear');
    });

    // Admin Profile
    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('change-password', 'changePassword')->name('change.password');
        Route::put('change-password', 'updatePassword')->name('change.password.update');
        Route::put('update', 'update')->name('update');
        //google 2fa
        Route::get('google/2fa','google2FaView')->name('google.2fa.view');
        Route::post('google/2fa','google2FAStatusUpdate')->name('google.2fa.status.update');
    });

    // Setup Currency Section
    Route::controller(CurrencyController::class)->prefix('currency')->name('currency.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::put('status/update', 'statusUpdate')->name('status.update');
        Route::put('update', 'update')->name('update');
        Route::delete('delete','delete')->name('delete');
        Route::post('search','search')->name("search");

        //setup currency precisions
        Route::put('precision/setup', 'setupPrecision')->name('precision.setup');
    });

    // Live Exchange Rate Setup
    Route::controller(LiveExchangeRateApiController::class)->prefix('live/exchange-rate')->name('live.exchange.rate.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('edit/{slug}', 'edit')->name('edit');
        Route::put('update/{slug}', 'update')->name('update');
        Route::put('status/update', 'statusUpdate')->name('status.update');
        Route::post('search','search')->name("search");
        Route::put('module/permission', 'modulePermission')->name('module.permission');
        Route::put('send/request', 'sendRequestApi')->name('send.request');
    });

    // Fees & Charges Section
    Route::controller(TrxSettingsController::class)->prefix('trx-settings')->name('trx.settings.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::put('charges/update', 'trxChargeUpdate')->name('charges.update');
    });

    //Source of Fund
    Route::controller(SourceOfFundController::class)->prefix('source-fund')->name('source.fund.')->group(function(){
        Route::get('/','index')->name('index');
        Route::post('store','store')->name('store');
        Route::put('update','update')->name('update');
        Route::delete('delete','delete')->name('delete');
        Route::put('status/update','statusUpdate')->name('status.update');
    });

    //Sending Purpose
    Route::controller(SendingPurposeController::class)->prefix('sending-purpose')->name('sending.purpose.')->group(function() {
        Route::get('/','index')->name('index');
        Route::post('store','store')->name('store');
        Route::put('update','update')->name('update');
        Route::delete('delete','delete')->name('delete');
        Route::put('status/update','statusUpdate')->name('status.update');
    });

    //coupon
    Route::controller(CouponController::class)->prefix('coupon')->name('coupon.')->group(function(){
        Route::get('/','index')->name('index');
        Route::post('store','store')->name('store');
        Route::put('update','update')->name('update');
        Route::delete('delete','delete')->name('delete');
        Route::put('status/update','statusUpdate')->name('status.update');
    });

    //new user bonus 
    Route::controller(NewUserBonusController::class)->prefix('new-user-bonus')->name('new.user.bonus.')->group(function(){
        Route::get('/','index')->name('index');
        Route::put('update','update')->name('update');
        Route::put('status/update','statusUpdate')->name('status.update');
    });

    //category
    Route::controller(ReceivingMethodCategoryController::class)->prefix('receiving-method-category')->name('receiving.method.category.')->group(function(){
        Route::get('/','index')->name('index');
        Route::get('edit/{slug}', 'edit')->name('edit');
        Route::put('update/{slug}', 'update')->name('update');
        Route::put('status/update','statusUpdate')->name('status.update');
    });

    //bank method automatic
    Route::controller(BankMethodAutomaticController::class)->prefix('bank-method-automatic')->name('bank.method.automatic.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('edit/{slug}', 'edit')->name('edit');
        Route::put('update/{slug}', 'update')->name('update');
        Route::put('status/update','statusUpdate')->name('status.update');
    });

    // Remittance Bank
    Route::controller(RemittanceBankController::class)->prefix('bank-method-manual')->name('remittance.bank.')->group(function (){
        Route::get('/','index')->name('index');
        Route::post('store','store')->name('store');
        Route::put('update','update')->name('update');
        Route::delete('delete','delete')->name('delete');
        Route::put('status/update','statusUpdate')->name('status.update');
    });

    // Mobile Method
    Route::controller(MobileMethodController::class)->prefix('mobile-method-manual')->name('mobile.method.')->group(function (){
        Route::get('/','index')->name('index');
        Route::post('store','store')->name('store');
        Route::put('update','update')->name('update');
        Route::delete('delete','delete')->name('delete');
        Route::put('status/update','statusUpdate')->name('status.update');
    });

    // cashpickup
    Route::controller(CashPickupMethodController::class)->prefix('cash-pickup')->name('cash.pickup.method.')->group(function(){
        Route::get('/','index')->name('index');
        Route::post('store','store')->name('store');
        Route::put('update','update')->name('update');
        Route::delete('delete','delete')->name('delete');
        Route::put('status/update','statusUpdate')->name('status.update');
    });

    //Send Remittance
    Route::controller(RemittanceLogController::class)->prefix('send-remittance')->name('send.remittance.')->group(function(){
        Route::get('/','index')->name('index');
        Route::get('details/{trx_id}','details')->name('details');
        Route::post('status/update/{trx_id}','statusUpdate')->name('status.update');
        Route::get('review-payment','reviewPayment')->name('review.payment');
        Route::get('pending','pending')->name('pending');
        Route::get('confirm-payment','confirmPayment')->name('confirm.payment');
        Route::get('hold','hold')->name('hold');
        Route::get('settled','settled')->name('settled');
        Route::get('complete','complete')->name('complete');
        Route::get('canceled','canceled')->name('canceled');
        Route::get('failed','failed')->name('failed');
        Route::get('refunded','refunded')->name('refunded');
        Route::get('delayed','delayed')->name('delayed');
        Route::post('search','search')->name("search");
        Route::post('review-search','reviewSearch')->name("review.search");
        Route::post('cancel-search','cancelSearch')->name("cancel.search");
        Route::post('complete-search','completeSearch')->name("complete.search");
        Route::post('confirm-payment-search','confirmPaymentSearch')->name("confirm.payment.search");
        Route::post('hold-search','holdSearch')->name("hold.search");
        Route::post('settled-search','settledSearch')->name("settled.search");
        Route::post('pending-search','pendingSearch')->name("pending.search");
        Route::post('delayed-search','delayedSearch')->name("delayed.search");
        Route::post('failed-search','failedSearch')->name("failed.search");
        Route::post('refunded-search','refundedSearch')->name("refunded.search");

    });

    //pdf download
    Route::controller(RemittanceLogController::class)->group(function(){
        Route::get('download-pdf/{trx_id}','downloadPdf')->name('download.pdf');
    });

    //Statements
    Route::controller(StatementController::class)->prefix('statements')->name('statements.')->group(function(){
        Route::get('/','index')->name('index');
        Route::get('statement-filter','statementFilter')->name('filter');
        Route::get('download','download')->name('download');
    });

    //coupon transactions 
    Route::controller(CouponTransactionController::class)->prefix('coupon-logs')->name('coupon.log.')->group(function(){
        Route::get('/','index')->name('index');
        Route::get('details/{trx_id}','details')->name('details');
        Route::post('search','search')->name("search");
    });

    // download coupon logs
    Route::controller(CouponTransactionController::class)->prefix('coupon')->name('coupon.')->group(function(){
        Route::get('download-pdf/{trx_id}','downloadCouponPdf')->name('download.pdf');
    });

    // Subscriber
    Route::controller(SubscribeController::class)->prefix('subscriber')->name('subscriber.')->group(function(){
        Route::get('/','index')->name('index');
        Route::post('send-mail','SendMail')->name('send.mail');
    }); 

    //contact request
    Route::controller(ContactMessageController::class)->prefix('contact')->name('contact.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('reply-message', 'reply')->name('messages.reply');
    });

    // User Care Section
    Route::controller(UserCareController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('active', 'active')->name('active');
        Route::get('banned', 'banned')->name('banned');
        Route::get('email/unverified', 'emailUnverified')->name('email.unverified');
        Route::get('sms/unverified', 'SmsUnverified')->name('sms.unverified');
        Route::get('kyc/unverified', 'KycUnverified')->name('kyc.unverified');
        Route::get('kyc/details/{username}', 'kycDetails')->name('kyc.details');
        Route::get('email-user', 'emailAllUsers')->name('email.users');
        Route::post('email-users/send', 'sendMailUsers')->name('email.users.send')->middleware("mail");
        Route::get('details/{username}', 'userDetails')->name('details');
        Route::post('details/update/{username}', 'userDetailsUpdate')->name('details.update');
        Route::get('login/logs/{username}', 'loginLogs')->name('login.logs');
        Route::get('mail/logs/{username}', 'mailLogs')->name('mail.logs');
        Route::post('send/mail/{username}', 'sendMail')->name('send.mail')->middleware("mail");
        Route::post('login-as-member/{username?}','loginAsMember')->name('login.as.member');
        Route::post('kyc/approve/{username}','kycApprove')->name('kyc.approve');
        Route::post('kyc/reject/{username}','kycReject')->name('kyc.reject');
        Route::post('search','search')->name('search');
    });
    Route::controller(UsefulLinkController::class)->prefix('useful-links')->name('useful.links.')->group(function (){
        Route::get('index','index')->name('index');
        Route::post("store","store")->name("store");
        Route::put("status/update","statusUpdate")->name("status.update");
        Route::get("edit/{slug}","edit")->name("edit");
        Route::post("update/{slug}","update")->name("update");
        Route::delete("delete","delete")->name("delete");
    });

    // Admin Care Section
    Route::controller(AdminCareController::class)->prefix('admins')->name('admins.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('email-admin', 'emailAllAdmins')->name('email.admins');
        Route::delete('admin/delete','deleteAdmin')->name('admin.delete')->middleware('admin.delete.guard');
        Route::post('send/email','sendEmail')->name('send.email')->middleware("mail");
        Route::post('admin/search','adminSearch')->name('search');

        Route::post("store","store")->name("admin.store");
        Route::put("update","update")->name("admin.update");
        Route::put('status/update','statusUpdate')->name('admin.status.update');

        Route::get('role/index','roleIndex')->name('role.index');
        Route::post('role/store','roleStore')->name('role.store');
        Route::put('role/update','roleUpdate')->name('role.update');
        Route::delete('role/remove','roleRemove')->name('role.delete')->middleware('admin.role.delete.guard');

        Route::get('role/permission/index','rolePermissionIndex')->name('role.permission.index');
        Route::post('role/permission/store','rolePermissionStore')->name('role.permission.store');
        Route::put('role/permission/update','rolePermissionUpdate')->name('role.permission.update');
        Route::delete('role/permission/delete','rolePermissionDelete')->name('role.permission.dalete');
        Route::delete('role/permission/assign/delete/{slug}','rolePermissionAssignDelete')->name('role.permission.assign.delete');

        Route::get('role/permission/{slug}','viewRolePermission')->name('role.permission');
        Route::post('role/permission/assign/{slug}','rolePermissionAssign')->name('role.permission.assign');
    });


    // Web Settings Section
    Route::controller(WebSettingsController::class)->prefix('web-settings')->name('web.settings.')->group(function(){
        Route::get('basic-settings','basicSettings')->name('basic.settings');
        Route::put('basic-settings/update','basicSettingsUpdate')->name('basic.settings.update');
        Route::put('basic-settings/activation/update','basicSettingsActivationUpdate')->name('basic.settings.activation.update');
        Route::get('image-assets','imageAssets')->name('image.assets');
        Route::put('image-assets/update','imageAssetsUpdate')->name('image.assets.update');
        Route::get('setup-seo','setupSeo')->name('setup.seo');
        Route::put('setup-seo/update','setupSeoUpdate')->name('setup.seo.update');
    });


    // App Settings Section
    Route::prefix('app-settings')->name('app.settings.')->group(function () {
        Route::controller(AppSettingsController::class)->group(function () {
            Route::get('splash-screen', 'splashScreen')->name('splash.screen');
            Route::put('splash-screen/update', 'splashScreenUpdate')->name('splash.screen.update');
            Route::get('urls', 'urls')->name('urls');
            Route::put('urls/update', 'urlsUpdate')->name('urls.update');

        });

        Route::controller(AppOnboardScreensController::class)->name('onboard.')->group(function () {
            Route::get('onboard-screens', 'onboardScreens')->name('screens');
            Route::post('onboard-screens/store', 'onboardScreenStore')->name('screen.store');
            Route::put('onboard-screen/update', 'onboardScreenUpdate')->name('screen.update');
            Route::put('onboard-screen/status/update', 'onboardScreenStatusUpdate')->name('screen.status.update');
            Route::delete('onboard-screen/delete','onboardScreenDelete')->name('screen.delete');
        });
    });


    // Language Section
    Route::controller(LanguageController::class)->prefix('languages')->name('languages.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store','store')->name('store');
        Route::put('update','update')->name('update');
        Route::put('status/update','statusUpdate')->name('status.update');
        Route::get('info/{code}','info')->name('info');
        Route::post('import','import')->name('import');
        Route::delete('delete','delete')->name('delete');
        Route::post('switch','switch')->name('switch');
        Route::get('download','download')->name('download');
    });

    // System Maintenance
    Route::controller(SystemMaintenanceController::class)->prefix('system-maintenance')->name('system.maintenance.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::put('update', 'update')->name('update');
    });

    // Setup Email Section
    Route::controller(SetupEmailController::class)->prefix('setup-email')->name('setup.email.')->group(function () {
        Route::get('config', 'configuration')->name('config');
        // Route::get('template/default', 'defaultTemplate')->name('template.default');
        Route::put('config/update', 'update')->name('config.update');
        Route::post('test-mail/send','sendTestMail')->name('test.mail.send')->middleware('mail');
    });


    // Setup KYC Section
    Route::controller(SetupKycController::class)->prefix('setup-kyc')->name('setup.kyc.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('edit/{slug}', 'edit')->name('edit');
        Route::put('update/{slug}', 'update')->name('update');
        Route::put('status/update', 'statusUpdate')->name('status.update');
    });


    // Setup Section
    Route::controller(SetupSectionsController::class)->prefix('setup-sections')->name('setup.sections.')->group(function () {
        Route::get('{slug}', 'sectionView')->name('section');
        Route::post('update/{slug}','sectionUpdate')->name('section.update');
        Route::post('item/store/{slug}','sectionItemStore')->name('section.item.store');
        Route::post('item/update/{slug}','sectionItemUpdate')->name('section.item.update');
        Route::delete('item/delete/{slug}','sectionItemDelete')->name('section.item.delete');

        Route::controller(JournalController::class)->prefix('journal')->name('journal.')->group(function () {

            Route::get('create','journalCreate')->name('create');
            Route::post('store','journalStore')->name('store');
            Route::put('status/update','journalStatusUpdate')->name('status.update');
            Route::delete('delete','journalDelete')->name('delete');
            Route::get('edit/{id}','journalEdit')->name('edit');
            Route::post('update/{id}','journalUpdate')->name('update');

        });

        //Web journal category controller
        Route::controller(WebJournalCategoryController::class)->prefix('category')->name('category.')->group(function (){
            Route::get('index','index')->name('index');
            Route::post('store','store')->name('store');
            Route::delete('delete','delete')->name('delete');
            Route::put('status/update','categoryStatusUpdate')->name('status.update');
        });

        //country section controller
        Route::controller(CountrySectionController::class)->prefix('country')->name('section.')->group(function(){
            Route::get('{slug}','view')->name('country');
            Route::post('update/{slug}','countryUpdate')->name('country.update');
        });
    });

    // Setup Pages Controller
    Route::controller(SetupPagesController::class)->prefix('setup-pages')->name('setup.pages.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::put('status/update','statusUpdate')->name('status.update');
    });


    // Extensions Section
    Route::controller(ExtensionsController::class)->prefix('extensions')->name('extensions.')->group(function () {
        Route::get('index', 'index')->name('index');
    });

    // Payment Method Section
    Route::prefix('payment-gateways')->name('payment.gateway.')->group(function () {

        Route::controller(PaymentGatewaysController::class)->group(function () {
            Route::get('{slug}/{type}/create', 'paymentGatewayCreate')->name('create')->whereIn('type', ['automatic', 'manual']);
            Route::post('{slug}/{type}', 'paymentGatewayStore')->name('store')->whereIn('type', ['automatic', 'manual']);
            Route::get('{slug}/{type}', 'paymentGatewayView')->name('view')->whereIn('type', ['automatic', 'manual']); // View Gateway Index Page
            Route::get('{slug}/{type}/{alias}', 'paymentGatewayEdit')->name('edit')->whereIn('type', ['automatic', 'manual']);
            Route::put('{slug}/{type}/{alias}', 'paymentGatewayUpdate')->name('update')->whereIn('type', ['automatic', 'manual']);
            Route::put('status/update', 'paymentGatewayStatusUpdate')->name('status.update');
            Route::delete('remove', 'remove')->name('remove');
        });

        Route::controller(PaymentGatewayCurrencyController::class)->group(function () {
            Route::delete('currency/remove', 'paymentGatewayCurrencyRemove')->name('currency.remove');
        });
    });


    // Push Notification Setup Section
    Route::controller(PushNotificationController::class)->prefix('push-notification')->name('push.notification.')->group(function(){
        Route::get('config','configuration')->name('config');
        Route::put('update','update')->name('update');

        Route::get('/','index')->name('index');
        Route::post('send','send')->name('send');
    });


    // Broadcasting Setup Section
    Route::controller(BroadcastingController::class)->prefix('broadcast')->name('broadcast.')->group(function(){
        Route::put("config/update","configUpdate")->name('config.update');
    });

    //  GDPR Cookie Section
    Route::controller(CookieController::class)->prefix('cookie')->name('cookie.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::put('update', 'update')->name('update');
    });

    // Server Info Section
    Route::controller(ServerInfoController::class)->prefix('server-info')->name('server.info.')->group(function () {
        Route::get('index', 'index')->name('index');
    });

    // Support Ticked Section
    Route::controller(SupportTicketController::class)->prefix('support-ticket')->name('support.ticket.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('active', 'active')->name('active');
        Route::get('pending', 'pending')->name('pending');
        Route::get('solved', 'solved')->name('solved');
        Route::get('conversation/{ticket_id}', 'conversation')->name('conversation');
        Route::post('message/reply','messageReply')->name('messaage.reply');
        Route::post('solve','solve')->name('solve');
    });

    // Extension Section
    Route::controller(ExtensionsController::class)->prefix('extension')->name('extension.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('update/{id}', 'update')->name('update');
        Route::put('status/update', 'statusUpdate')->name('status.update');
    });

    // Cache Clear Section
    Route::get('cache/clear', function () {
        Artisan::all('cache:clear');
        Artisan::all('route:clear');
        Artisan::all('view:clear');
        Artisan::all('config:clear');
        return redirect()->back()->with(['success' => ['Cache Clear Successfully!']]);
    })->name('cache.clear');

    Route::controller(CryptoAssetController::class)->prefix('crypto/assets')->name('crypto.assets.')->group(function() {
        Route::get('gateway/{alias}','gatewayAssets')->name('gateway.index');
        Route::get('gateway/{alias}/generate/wallet','generateWallet')->name('generate.wallet');

        Route::get('wallet/balance/update/{crypto_asset_id}/{wallet_id}','walletBalanceUpdate')->name('wallet.balance.update');
        Route::post('wallet/store','walletStore')->name("wallet.store");
        Route::delete('wallet/delete','walletDelete')->name('wallet.delete');
        Route::put('wallet/status/update','walletStatusUpdate')->name('wallet.status.update');
        Route::get('wallet/transactions/{crypto_asset_id}/{wallet_id}','walletTransactions')->name('wallet.transactions');
        Route::post('wallet/transactions/search/{crypto_asset_id}/{wallet_id}','walletTransactionSearch')->name('wallet.transaction.search');
    });

    //admin notification
    Route::controller(AdminNotificationController::class)->prefix('notifications')->name('notification.')->group(function(){
        Route::get('/','index')->name('index');
    });
});

Route::get('pusher/beams-auth', function (Request $request) {
    if(Auth::check() == false) {
        return response(['Inconsistent request'], 401);
    }
    $userID = Auth::user()->id;

    $basic_settings = BasicSettingsProvider::get();
    if(!$basic_settings) {
        return response('Basic setting not found!', 404);
    }

    $notification_config = $basic_settings->push_notification_config;

    if(!$notification_config) {
        return response('Notification configuration not found!', 404);
    }

    $instance_id    = $notification_config->instance_id ?? null;
    $primary_key    = $notification_config->primary_key ?? null;
    if($instance_id == null || $primary_key == null) {
        return response('Sorry! You have to configure first to send push notification.', 404);
    }
    $beamsClient = new PushNotifications(
        array(
            "instanceId" => $notification_config->instance_id,
            "secretKey" => $notification_config->primary_key,
        )
    );
    $publisherUserId =  make_user_id_for_pusher("admin", $userID);
    try{
        $beamsToken = $beamsClient->generateToken($publisherUserId);
    }catch(Exception $e) {
        return response(['Server Error. Failed to generate beams token.'], 500);
    }

    return response()->json($beamsToken);
})->name('pusher.beams.auth');
