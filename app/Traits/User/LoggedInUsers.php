<?php

namespace App\Traits\User;

use Exception;
use App\Models\UserCoupon;
use Jenssegers\Agent\Agent;
use App\Models\UserLoginLog;

trait LoggedInUsers {

    //function refresh user coupon 
    protected function refreshCounpon($user,$bonus){
        $coupon     = UserCoupon::where('user_id',$user->id)->first();
        if(!$coupon){
            $price                      = explode('.',$bonus->price);
            $coupon_name                    = strtoupper($user->username).$price[0];
            $data                           = [
                'user_id'                   => $user->id,
                'new_user_bonus_id'         => $bonus->id,
                'coupon_name'               => $coupon_name,
                'price'                     => $bonus->price,
            ];
            try{
                UserCoupon::insert($data);
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }else{
            $data                           = [
                'user_id'                   => $coupon->user_id,
                'new_user_bonus_id'         => $bonus->id,
                'coupon_name'               => $coupon->coupon_name,
                'price'                     => $bonus->price,
            ];
            try{
               $coupon->update($data);
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }
    }
    protected function createLoginLog($user) {
        $client_ip = request()->ip() ?? false;
        $location = geoip()->getLocation($client_ip);

        $agent = new Agent();
        $mac = "";

        $data = [
            'user_id'       => $user->id,
            'ip'            => $client_ip,
            'mac'           => $mac,
            'city'          => $location['city'] ?? "",
            'country'       => $location['country'] ?? "",
            'longitude'     => $location['lon'] ?? "",
            'latitude'      => $location['lat'] ?? "",
            'timezone'      => $location['timezone'] ?? "",
            'browser'       => $agent->browser() ?? "",
            'os'            => $agent->platform() ?? "",
        ];

        try{
            UserLoginLog::create($data);
        }catch(Exception $e) {

        }
    }
}