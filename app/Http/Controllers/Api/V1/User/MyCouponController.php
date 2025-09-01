<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Models\UserCoupon;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\CouponTransaction;
use App\Http\Controllers\Controller;

class MyCouponController extends Controller
{
    /**
     * Method for get the coupon data
     * @return response
     */
    public function index(){
        $bonus              = UserCoupon::with(['new_user_bonus'])->auth()->first();
        if(!$bonus) return Response::success(['Your Coupon information'],[
            'coupon'    => [
                'id'            => null,
                'name'          => null,
                'price'         => null,
                'max_limit'     => null,
                'remaining'     => null
            ]
        ]);
        $coupon_transaction = CouponTransaction::auth()->with(['user_coupon'])->whereNot('user_coupon_id',null)->count();
        $remaining          = $bonus->new_user_bonus->max_used - $coupon_transaction;
        $coupon             = [
            'id'            => $bonus->id,
            'name'          => $bonus->coupon_name,
            'price'         => floatval($bonus->price),
            'max_limit'     => $bonus->new_user_bonus->max_used,
            'remaining'     => $remaining
        ];
    
        return Response::success(['Your Coupon information'],[
            'coupon'    => $coupon
        ]);
    }
}
