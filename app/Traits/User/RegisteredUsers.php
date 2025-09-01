<?php

namespace App\Traits\User;

use Exception;
use App\Models\UserCoupon;

trait RegisteredUsers {

    //function for create user coupon
    protected function createCoupon($user,$bonus){
        $price                      = explode('.',$bonus->price);
        $coupon_name                = strtoupper($user->username).$price[0];
        $data                       = [
            'user_id'               => $user->id,
            'new_user_bonus_id'     => $bonus->id,
            'coupon_name'           => $coupon_name,
            'price'                 => $bonus->price,
        ];
        
        try{
            UserCoupon::insert($data);
        }catch(Exception $e){
            $this->guard()->logout();
            $user->delete();
            return $this->breakAuthentication("Failed to create coupon! Please try again.");
        }

    }

    protected function breakAuthentication($error) {
        return back()->with(['error' => [$error]]);
    }
}