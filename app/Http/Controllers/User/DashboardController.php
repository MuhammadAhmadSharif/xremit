<?php
namespace App\Http\Controllers\User;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\Admin\Currency;
use App\Models\Admin\CashPickup;
use App\Models\UserNotification;
use App\Models\Admin\MobileMethod;
use App\Http\Controllers\Controller;
use App\Models\Admin\RemittanceBank;
use Illuminate\Support\Facades\Auth;
use App\Constants\PaymentGatewayConst;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    /**
     * Method for show user dashboard
     * @param string
     * @return view
     */
    public function index()
    {
        $page_title              = "| Dashboard";
        $sender_currency         = Currency::where('status',true)->where('sender',true)->first();
        $receiver_currency       = Currency::where('status',true)->where('receiver',true)->first();
        
        $total_remittances       = Transaction::toBase()->where('user_id',auth()->user()->id)->count();
        $user_remittance         = Transaction::orderByDESC('id')->where('user_id',auth()->user()->id)->get();
        $total_amount            = $user_remittance->sum('request_amount');
        $complete                = Transaction::toBase()->where('user_id',auth()->user()->id)->where('status',global_const()::REMITTANCE_STATUS_COMPLETE)->count();
        $cancel                  = Transaction::toBase()->where('user_id',auth()->user()->id)->where('status',global_const()::REMITTANCE_STATUS_CANCEL)->count();

        //ongoing remittance
        $pending_log             = Transaction::toBase()->where('user_id',auth()->user()->id)->where('status',global_const()::REMITTANCE_STATUS_PENDING)->count();
        $progress_log            = Transaction::toBase()->where('user_id',auth()->user()->id)->where('status',global_const()::REMITTANCE_STATUS_CONFIRM_PAYMENT)->count();
        $settled_log             = Transaction::toBase()->where('user_id',auth()->user()->id)->where('status',global_const()::REMITTANCE_STATUS_SETTLED)->count();
        $under_review_log        = Transaction::toBase()->where('user_id',auth()->user()->id)->where('status',global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT)->count();
        $hold_log                = Transaction::toBase()->where('user_id',auth()->user()->id)->where('status',global_const()::REMITTANCE_STATUS_HOLD)->count();
        $failed_log              = Transaction::toBase()->where('user_id',auth()->user()->id)->where('status',global_const()::REMITTANCE_STATUS_FAILED)->count();
        $refund_log              = Transaction::toBase()->where('user_id',auth()->user()->id)->where('status',global_const()::REMITTANCE_STATUS_REFUND)->count();

        $ongoing_remittance      = $pending_log + $progress_log + $under_review_log;
        $complete_remittance     = $settled_log + $refund_log + $complete;
        $cancel_remittance       = $cancel + $hold_log;

        $transactions = Transaction::with(['currency'])->orderByDESC('id')->where('user_id',auth()->user()->id)->latest()->take(3)->get();

        $start = strtotime(date('Y-m-01'));
        $end = strtotime(date('Y-m-31'));


        $pending_data  = [];
        $complete_data  = [];
        $month_day  = [];

        while ($start <= $end) {
            $start_date = date('Y-m-d', $start);
            // Monthley add money
            $pending = Transaction::where('user_id',auth()->user()->id)->where('type', PaymentGatewayConst::TYPESENDREMITTANCE)->where('status', 2)->whereDate('created_at',$start_date)->count();
            $complete = Transaction::where('user_id',auth()->user()->id)->where('type', PaymentGatewayConst::TYPESENDREMITTANCE)
                                        ->whereDate('created_at',$start_date)
                                        ->where('status', global_const()::REMITTANCE_STATUS_COMPLETE)
                                        ->count();
            $pending_data[]  = $pending;
            $complete_data[]  = $complete;
            $month_day[] = date('Y-m-d', $start);
            $start = strtotime('+1 day',$start);
        }
        // Chart one
        $chart_one_data = [
            'pending_data'  => $pending_data,
            'complete_data'  => $complete_data,
            
        ];


        $data     = [
            'total_remittances'     => $total_remittances,
            'total_amount'          => $total_amount,
            'complete'              => $complete_remittance,
            'cancel'                => $cancel_remittance,
            'ongoing'               => $ongoing_remittance,
            'chart_one_data'   => $chart_one_data,
            'month_day'        => $month_day,
        ];
        
        $client_ip = request()->ip() ?? false;
        $user_country = geoip()->getLocation($client_ip)['country'] ?? "";
        $user         = auth::user();
        $notifications = UserNotification::where('user_id',$user->id)->latest()->take(10)->get();

        return view('user.dashboard',compact(
            "page_title",
            'data',
            'transactions',
            'sender_currency',
            'receiver_currency',
            'user_country',
            'user',
            'notifications'
        ));
    }
    /**
     * Method for logout
     * @param string
     * @param \Illuminate\Http\Request $request
     */
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('user.login');
    }
    
    /**
     * Method for get bank name 
     * @param string
     * @param Illuminate\Http\Request $request
     */
    public function getBankName(Request $request)
    {
        $validator    = Validator::make($request->all(),[
            'country'  => 'required',           
        ]);
        if($validator->fails()) {
            return Response::error($validator->errors()->all());
        }
        $country = RemittanceBank::where('country',$request->country)->where('status',true)->get();
        return Response::success(['Data fetch successfully'],['country' => $country],200);
    }
    /**
     * Method for get mobile method 
     * @param string
     * @param Illuminate\Http\Request $request
     */
    public function getMobileMethod(Request $request){
        $validator   = Validator::make($request->all(),[
            'country' => 'required',
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all());
        }
        $country = MobileMethod::where('country',$request->country)->where('status',true)->get();
        return Response::success(['Data fetch successfully'],['country' => $country],200);
    }
    /**
     * Method for get mobile method 
     * @param string
     * @param Illuminate\Http\Request $request
     */
    public function getPickupPoint(Request $request){
        $validator   = Validator::make($request->all(),[
            'country' => 'required',
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all());
        }
        $country = CashPickup::where('country',$request->country)->where('status',true)->get();
        return Response::success(['Data fetch successfully'],['country' => $country],200);
    }
    /**
     * Method for image validate
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
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
