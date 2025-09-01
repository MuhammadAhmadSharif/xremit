<?php

namespace App\Http\Controllers\User;

use App\Models\UserCoupon;
use App\Models\Admin\Coupon;
use Illuminate\Http\Request;
use App\Models\UserNotification;
use App\Models\Admin\NewUserBonus;
use App\Http\Controllers\Controller;
use App\Models\CouponTransaction;

class MyCouponController extends Controller
{
    /**
     * Method for view the my coupon page
     * @return view
     */
    public function index(){
        $page_title         = "| My Coupon";
        $bonus              = UserCoupon::with(['new_user_bonus'])->auth()->first();
        if(isset($bonus)){
            $coupon_transaction = CouponTransaction::auth()->with(['user_coupon'])->whereNot('user_coupon_id',null)->count();
            $remaining          = $bonus->new_user_bonus->max_used - $coupon_transaction;
            $coupons            = Coupon::where('status',true)->orderBy('id','desc')->get();
        }else{
            $remaining          = '';
            $coupons            = '';
        }
        $user               = auth()->user();
        $notifications      = UserNotification::where('user_id',$user->id)->latest()->take(10)->get();

        return view('user.sections.my-coupon.index',compact(
            'page_title',
            'bonus',
            'coupons',
            'notifications',
            'remaining'
        ));
    }
}
