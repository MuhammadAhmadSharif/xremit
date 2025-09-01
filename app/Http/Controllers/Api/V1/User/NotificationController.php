<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Response;
use App\Models\UserNotification;


class NotificationController extends Controller
{
    /**
     * Method for show the notification
     */
    public function notification(){
        $notification   = UserNotification::where('user_id',auth()->user()->id)->get()->map(function($data){
            return [
                'id'        => $data->id,
                'message'   => $data->message,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at,
            ];
        });

        return Response::success(['Notification Data Fetch Successfully.'],[
            'notification'   => $notification,
        ],200);
    }
}
