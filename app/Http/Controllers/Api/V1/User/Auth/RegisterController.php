<?php

namespace App\Http\Controllers\Api\V1\User\Auth;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\UserAuthorization;
use App\Models\Admin\NewUserBonus;
use App\Traits\User\LoggedInUsers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Traits\User\RegisteredUsers;

use Illuminate\Support\Facades\Hash;
use App\Models\Admin\AdminNotification;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Providers\Admin\BasicSettingsProvider;
use App\Notifications\User\Auth\SendAuthorizationCode;

class RegisterController extends Controller
{
    use RegisteredUsers,LoggedInUsers;

    protected $basic_settings;

    public function __construct()
    {
        $this->basic_settings = BasicSettingsProvider::get();
    }
    
    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request){
        $basic_settings = $this->basic_settings;
        $passowrd_rule = "required|string|min:6";

        if($basic_settings->secure_password) {
            $passowrd_rule = ["required",Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()];
        }
        $basic_settings       = BasicSettingsProvider::get();
        if($basic_settings->user_registration == false){
            $error = ['User Registration System currently not available.'];
            return Response::error($error);
        } 
        $agree_policy = $this->basic_settings->agree_policy == 1 ? 'required|in:on' : 'nullable';

        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:50',
            'lastname'  => 'required|string|max:50',
            'email'     => 'required|email|max:160|unique:users',
            'password'  => $passowrd_rule,
            'policy'     => $agree_policy,
        ]);

        if($validator->fails()){
            $error =  ['error'=>$validator->errors()->all()];
            return Response::validation($error);
        }

        $validated = $validator->validated();

        //User Create

        $validated = Arr::except($validated,['agree']);

        $validated['firstname']      = $validated['firstname'];
        $validated['lastname']       = $validated['lastname'];
        $validated['email_verified'] = ($basic_settings->email_verification == true) ? 0 : 1;
        $validated['kyc_verified']   = 0;
        $validated['sms_verified']   = 0;
        $validated['status']         = 1;
        $validated['password']       = Hash::make($validated['password']);
        $validated['username']       = make_username($validated['firstname'],$validated['lastname']);

        $user = User::create($validated);

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;

        if ($basic_settings->email_verification == true) {
            $data = [
                'user_id'       => $user->id,
                'code'          => generate_random_code(),
                'token'         => generate_unique_string("user_authorizations","token",200),
                'created_at'    => now(),
            ];
            DB::beginTransaction();
            try{
                UserAuthorization::where("user_id",$user->id)->delete();
                DB::table("user_authorizations")->insert($data);
                $user->notify(new SendAuthorizationCode((object) $data));
                DB::commit();
            }catch(Exception $e) {
                DB::rollBack();
                $error = ['Something went worng! Please try again'];
                return Response::error($error);
            }
        }
        if ($basic_settings->email_verification == 1 && $basic_settings->email_notification == 1) {
            $message =  ['Please check email and verify your account'];
        } else {
            $message =  ['Registration successful'];
        }
        $bonus  = NewUserBonus::where('status',true)->first();
        if($bonus){
            $this->createCoupon($user,$bonus);
        }
        $notification_message = [
            'title'     => "New User" . "(" . $user->username . ")" . " register successfully.",
            'time'      => Carbon::now()->diffForHumans(),
            'image'     => get_image($user->image,'user-profile'),
        ];
        AdminNotification::create([
            'type'      => "New User Register",
            'admin_id'  => 1,
            'message'   => $notification_message,
        ]);

        $data = [
            'token' => $token,
            'image_path' => get_files_public_path('user-profile'),
            'default_image' => get_files_public_path('default'),
            'user' => new UserResource($user)
        ];
        return Response::success($message, $data);
    }
    
}
