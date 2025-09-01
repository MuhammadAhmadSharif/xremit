<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AdminNotification;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    /**
     * Method for get all the notifications
     * @return view
     */
    public function index(){
        $page_title     = "All Notifications";
        $notifications  = AdminNotification::where('admin_id',auth()->user()->id)->orderBy('id','desc')->paginate(50);

        return view('admin.sections.admin-notification.index',compact(
            'page_title',
            'notifications'
        ));

    }
}
