<?php

namespace App\Http\Controllers\User;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Validator;

class SecurityController extends Controller
{
    /**
     * Method for show two factor page
     * @param $string
     * @return view
     */
    public function google2FA(){
        $page_title = "Two Factor Authentication";
        $qr_code = generate_google_2fa_auth_qr();
        $client_ip = request()->ip() ?? false;
        $user_country = geoip()->getLocation($client_ip)['country'] ?? "";
        $user         = auth()->user();
        $notifications = UserNotification::where('user_id',$user->id)->latest()->take(10)->get();

        return view('user.sections.two-fa-security.index',compact(
            'page_title',
            'qr_code',
            'user_country',
            'user',
            'notifications'
        ));
    }
    /**
     * Method for update google 2fa status
     * @param string
     * @param Illuminate\Http\Request $request
     */
    public function google2FAStatusUpdate(Request $request) {
        $validated = Validator::make($request->all(),[
            'target'        => "required|numeric",
        ])->validate();

        $user = auth()->user();
        try{
            $user->update([
                'two_factor_status'         => $user->two_factor_status ? 0 : 1,
                'two_factor_verified'       => true,
            ]);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }
        return back()->with(['success' => ['Security Setting Updated Successfully!']]);
    }
}
