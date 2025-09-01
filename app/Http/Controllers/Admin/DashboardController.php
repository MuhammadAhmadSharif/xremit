<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Http\Helpers\Response;
use App\Models\Admin\Currency;
use App\Models\Admin\MobileMethod;
use App\Constants\PaymentGatewayConst;
use App\Http\Controllers\Controller;
use App\Models\Admin\RemittanceBank;
use Illuminate\Support\Facades\Auth;
use App\Constants\SupportTicketConst;
use App\Http\Helpers\PushNotificationHelper;
use App\Providers\Admin\BasicSettingsProvider;
use Pusher\PushNotifications\PushNotifications;

class DashboardController extends Controller
{

    public function getAllMonthNames(){
        $monthNames = collect([]);

        for ($monthNumber = 1; $monthNumber <= 12; $monthNumber++) {
            $monthName = Carbon::createFromDate(null, $monthNumber, null)->format('M');
            $monthNames->push($monthName);
        }

        return $monthNames;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title      = "Dashboard"; 
    

        //remittance log

        $total_log               = (Transaction::toBase()->count() == 0 ) ? 1 : Transaction::toBase()->count();
        $under_review_log        = Transaction::toBase()->where('status',global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT)->count();
        $hold_log                = Transaction::toBase()->where('status',global_const()::REMITTANCE_STATUS_HOLD)->count();
        $failed_log              = Transaction::toBase()->where('status',global_const()::REMITTANCE_STATUS_FAILED)->count();
        $percent_under_review    = (($under_review_log * 100) / $total_log);

        if($percent_under_review > 100){
            $percent_under_review = 100;
        }


        $pending_log             = Transaction::toBase()->where('status',global_const()::REMITTANCE_STATUS_PENDING)->count();
        $progress_log            = Transaction::toBase()->where('status',global_const()::REMITTANCE_STATUS_CONFIRM_PAYMENT)->count();
        $settled_log             = Transaction::toBase()->where('status',global_const()::REMITTANCE_STATUS_SETTLED)->count();
        $percent_pending_log     = (($pending_log * 100 ) / $total_log);

        if($percent_pending_log > 100){
            $percent_pending_log = 100;
        }


        $complete_log             = Transaction::toBase()->where('status',global_const()::REMITTANCE_STATUS_COMPLETE)->count();
        $cancel_log               = Transaction::toBase()->where('status',global_const()::REMITTANCE_STATUS_CANCEL)->count();
        $refund_log               = Transaction::toBase()->where('status',global_const()::REMITTANCE_STATUS_REFUND)->count();
        $percent_complete         = (( $complete_log * 100 ) / $total_log);

        if($percent_complete > 100 ){
            $percent_complete = 100;
        }

        $percent_total_logs = (($cancel_log * 100 ) / $total_log);
        if($percent_total_logs > 100){
            $percent_total_logs = 100;
        }

        //user data

        $total_users     = (User::toBase()->count() == 0) ? 1 : User::toBase()->count();
        $unverified_user = User::toBase()->where('email_verified',0)->count();
        $active_user     = User::toBase()->where('status',true)->count();
        $banned_user     = User::toBase()->where('status',false)->count();
        $user_percent    = (($active_user * 100 ) / $total_users);

        if ($user_percent > 100) {
            $user_percent = 100;
        }

        //Remittance Bank

        $total_bank  = (RemittanceBank::toBase()->count() == 0) ? 1 : RemittanceBank::toBase()->count();
        $active_bank = RemittanceBank::toBase()->where('status',true)->count();
        $pending_bank = RemittanceBank::toBase()->where('status',false)->count();
        $percent_bank = (($active_bank * 100) / $total_bank);

        if($percent_bank > 100) {
            $percent_bank = 100;
        }


        //Mobile Method 

        $total_mobile       = (MobileMethod::toBase()->count() == 0) ? 1 : MobileMethod::toBase()->count();
        $active_mobile      = MobileMethod::toBase()->where('status',true)->count();
        $pending_mobile     = MobileMethod::toBase()->where('status',false)->count();
        $percent_mobile     = (($active_mobile * 100 ) / $total_mobile);

        //support ticket

        $total_ticket       = (SupportTicket::toBase()->count() == 0) ? 1 : SupportTicket::toBase()->count();
        $active_ticket      = SupportTicket::toBase()->where('status',SupportTicketConst::ACTIVE)->count();
        $pending_ticket     = SupportTicket::toBase()->where('status',SupportTicketConst::PENDING)->count();

        if($pending_ticket == 0 && $active_ticket != 0){
            $percent_ticket = 100;
        }elseif($pending_ticket == 0 && $active_ticket == 0){
         $percent_ticket = 0;
        }else{
           $percent_ticket = ($active_ticket / ($active_ticket + $pending_ticket)) * 100;
        }
        

        $user_chart = [$active_user, $banned_user,$unverified_user,$total_users];
        $start = strtotime(date('Y-m-01'));
        $end = strtotime(date('Y-m-31'));


        $pending_data  = [];
        $complete_data  = [];
        $month_day  = [];

        while ($start <= $end) {
            $start_date = date('Y-m-d', $start);
            
            
            $pending = Transaction::where('type', PaymentGatewayConst::TYPESENDREMITTANCE)->where('status', 2)->whereDate('created_at',$start_date)->count();
            $complete = Transaction::where('type', PaymentGatewayConst::TYPESENDREMITTANCE)
                                        ->whereDate('created_at',$start_date)
                                        ->where('status', global_const()::REMITTANCE_STATUS_COMPLETE)
                                        ->count();
            
            $pending_data[]     = $pending;
            $complete_data[]    = $complete;
            $month_day[]        = date('Y-m-d', $start);
            $start              = strtotime('+1 day',$start);
        }
        // Chart one
        $chart_one_data = [
            'pending_data'      => $pending_data,
            'complete_data'     => $complete_data,
            
        ];

        $data                       = [
            'total'                 => $total_log,
            'percent_total_logs'    => $percent_total_logs,
            'under_review_log'      => $under_review_log,
            'hold_log'              => $hold_log,
            'failed_log'            => $failed_log,
            'percent_under_review'  => $percent_under_review,

            'pending'           => $pending_log,
            'progress'          => $progress_log,
            'settled'           => $settled_log,
            'percent_pending'   => $percent_pending_log,

            'complete'          => $complete_log,
            'cancel'            => $cancel_log,
            'refund'            => $refund_log,
            'percent_complete'  => $percent_complete,

            'total_users'           => $total_users,
            'unverified_user'       => $unverified_user,
            'active_user'           => $active_user,
            'user_percent'          => $user_percent,

            'total_bank'            => $total_bank,
            'active_bank'           => $active_bank,
            'pending_bank'          => $pending_bank,
            'percent_bank'          => $percent_bank,

            'total_mobile'          => $total_mobile,
            'active_mobile'         => $active_mobile,
            'pending_mobile'        => $pending_mobile,
            'percent_mobile'        => $percent_mobile,

            'total_ticket'          => $total_ticket,
            'active_ticket'         => $active_ticket,
            'pending_ticket'        => $pending_ticket,
            'percent_ticket'        => $percent_ticket,

            'user_chart_data'       => $user_chart,
            'chart_one_data'        => $chart_one_data,
            'month_day'             => $month_day,
            'total_user_count'      => User::all()->count(),
            'total_remittance_count'  => Transaction::all()->count(),
            'total_remittance_bank_count'  => RemittanceBank::all()->count(),
            'total_mobile_method_count'  => MobileMethod::all()->count(),
            'total_support_ticket_count'  => SupportTicket::all()->count(),

        ];
        
        $transactions         = Transaction::latest()->take(5)->get();

        $sender_currency      = Currency::where('status',true)->where('sender',true)->first();
        $receiver_currency    = Currency::where('status',true)->where('receiver',true)->first();

        $months = $this->getAllMonthNames();
        

        return view('admin.sections.dashboard.index',compact(
            'page_title',
            'data',
            'transactions',
            'sender_currency',
            'receiver_currency',
            'months',
        ));
    }

    
    /**
     * Logout Admin From Dashboard
     * @return view
     */
    public function logout(Request $request) {

        // $push_notification_setting = BasicSettingsProvider::get()->push_notification_config;

        // if($push_notification_setting) {
        //     $method = $push_notification_setting->method ?? false;

        //     if($method == "pusher") {
        //         $instant_id     = $push_notification_setting->instance_id ?? false;
        //         $primary_key    = $push_notification_setting->primary_key ?? false;

        //         if($instant_id && $primary_key) {
        //             $pusher_instance = new PushNotifications([
        //                 "instanceId"    => $instant_id,
        //                 "secretKey"     => $primary_key,
        //             ]);

        //             $pusher_instance->deleteUser("".Auth::user()->id."");
        //         }
        //     }

        // }

        $admin = auth()->user();
        try{
            $admin->update([
                'last_logged_out'   => now(),
                'login_status'      => false,
            ]);
        }catch(Exception $e) {
            // Handle Error
        }

        Auth::guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }


    /**
     * Function for clear admin notification
     */
    public function notificationsClear() {
        $admin = auth()->user();

        if(!$admin) {
            return false;
        }

        try{
            $admin->update([
                'notification_clear_at'     => now(),
            ]);
        }catch(Exception $e) {
            $error = ['error' => ['Something went wrong! Please try again.']];
            return Response::error($error,null,404);
        }

        $success = ['success' => ['Notifications clear successfully!']];
        return Response::success($success,null,200);
    }
}
