<?php

namespace App\Http\Controllers\Api\V1\User;

use Exception;
use Illuminate\Http\Request;
use App\Constants\GlobalConst;
use App\Http\Helpers\Response;
use App\Models\Admin\SetupKyc;
use Illuminate\Support\Carbon;
use App\Models\UserAuthorization;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Providers\Admin\BasicSettingsProvider;
use App\Notifications\User\Auth\SendAuthorizationCode;
use App\Traits\ControlDynamicInputFields;

class AuthorizationController extends Controller
{
    use ControlDynamicInputFields;

    public function resendCode(Request $request) {
        $user = auth()->user();
        $resend = UserAuthorization::where("user_id",$user->id)->first();
        if($resend){
            if(Carbon::now() <= $resend->created_at->addMinutes(GlobalConst::USER_PASS_RESEND_TIME_MINUTE)) {

                $error = ['You can resend verification code after '.Carbon::now()->diffInSeconds($resend->created_at->addMinutes(GlobalConst::USER_PASS_RESEND_TIME_MINUTE)). ' seconds'];
                return Response::error($error,[],400);
            }
        }
        $data = [
            'user_id'       =>  $user->id,
            'code'          => generate_random_code(),
            'token'         => generate_unique_string("user_authorizations","token",200),
            'created_at'    => now(),
        ];
        DB::beginTransaction();
        try{
            if($resend) {
                UserAuthorization::where("user_id", $user->id)->delete();
            }
            DB::table("user_authorizations")->insert($data);
            $user->notify(new SendAuthorizationCode((object) $data));
            DB::commit();
            $message =  ['Varification code send success'];
            return Response::success($message,[],200);
        }catch(Exception $e) {
            DB::rollBack();
            $error = ['Something went worng! Please try again'];
            return Response::error($error,[],400);
        }
    }

    public function verifyCode(Request $request) {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);
        if($validator->fails()){
            $error =  ['error'=>$validator->errors()->all()];
            return Response::validation($error);
        }
        $code = $request->code;
        $user = Auth::user();
        $otp_exp_sec = BasicSettingsProvider::get()->otp_exp_seconds ?? GlobalConst::DEFAULT_TOKEN_EXP_SEC;
        $auth_column = UserAuthorization::where("code",$code)->where('user_id', $user->id)->first();

        if(!$auth_column){
             $error = ['Verification code does not match'];
            return Response::error($error,[],400);
        }
        if($auth_column->created_at->addSeconds($otp_exp_sec) < now()) {
            $error = ['Session expired. Please try again'];
            return Response::error($error,[],400);
        }
        try{
            $auth_column->user->update([
                'email_verified'    => true,
            ]);
            $auth_column->delete();
        }catch(Exception $e) {
            $error = ['Something went worng! Please try again'];
            return Response::error($error,[],400);
        }
        $message =  ['Account successfully verified'];
        return Response::success($message,[],200);
    }

    public function authLogout(Request $request) {
        $user_token = Auth::guard(get_auth_guard())->user()->token();
        $user_token->revoke();
    }


    // User 2Fa authorization
    public function get2FaStatus() {
        $user = auth()->guard(get_auth_guard())->user();

        $message = "Your account secure with google 2FA";
        if($user->two_factor_status == false) $message = "To enable two factor authentication (powered by google) please visit your web dashboard. Click here: " . setRoute("user.authorize.google.2fa");

        return Response::success(['Request response fetch successfully!'],[
            'status' => $user->two_factor_status,
            'message'   => $message,
        ],200);
    }

    public function verifyGoogle2Fa(Request $request) {
        $validator = Validator::make($request->all(),[
            'code'      => "required|integer",
        ]);
        if($validator->fails()) return Response::error($validator->errors()->all(),[]);
        $validated = $validator->validate();

        $code = $validated['code'];

        $user = auth()->guard(get_auth_guard())->user();

        if(!$user->two_factor_secret) {
            return Response::error(['Your secret key not stored properly. Please contact with system administrator'],[],400);
        }

        if(google_2fa_verify($user->two_factor_secret,$code)) {

            $user->update([
                'two_factor_verified'   => true,
            ]);

            return Response::success(['Google 2FA successfully verified!'],[],200);
        }else if(google_2fa_verify($user->two_factor_secret,$code) === false) {
            return Response::error(['Invalid authentication code'],[],400);
        }

        return Response::error(['Failed to login. Please try again'],[],500);
    }
    /**
     * Google 2FA Verification
     *
     * @method GET
     * @return \Illuminate\Http\Response
     */

    public function verify2FACode(Request $request) {

        $validator = Validator::make($request->all(), [
            'otp' => 'required',
        ]);

        if($validator->fails()){
            $error =  ['error'=>$validator->errors()->all()];
            return Response::validation($error);
        }

        $code = $request->otp;
        $user = auth()->guard(get_auth_guard())->user();
        
        if(!$user->two_factor_secret) {
            return Response::error(['Your secret key not stored properly. Please contact with system administrator']);
        }

        if(google_2fa_verify($user->two_factor_secret,$code)) {
            $user->update([
                'two_factor_verified'   => true,
            ]);
            return Response::success(['Two factor verified successfully!'],[],200);
        }

        return Response::error(['Failed to login. Please try again']);
    }
    // Get KYC Input Fields
    public function getKycInputFields() {
        $user = auth()->guard(get_auth_guard())->user();

        $user_kyc = SetupKyc::userKyc()->first();
        $kyc_data = $user_kyc->fields;
        $kyc_fields = array_reverse($kyc_data);

        $data = [
            'status_info'  => '0: Unverified, 1: Verified, 2: Pending, 3: Rejected',
            'kyc_status'   => $user->kyc_verified,
            'input_fields' => $kyc_fields
        ];

        if(!$user_kyc) return Response::success(['User KYC section is under maintenance'], $data);
        if($user->kyc_verified == GlobalConst::VERIFIED) return Response::success(['You are already KYC Verified User'], $data);
        if($user->kyc_verified == GlobalConst::PENDING) return Response::success(['Your KYC information is submitted. Please wait for admin confirmation'], $data);

        return Response::success(['User KYC input fields fetch successfully!'], $data);
    }

    /**
     * Method for kyc submit
     */
    public function KycSubmit(Request $request) {
        $user = auth()->guard(get_auth_guard())->user();

        if($user->kyc_verified == GlobalConst::VERIFIED) return Response::warning(['You are already KYC Verified User']);

        $user_kyc_fields = SetupKyc::userKyc()->first()->fields ?? [];
        $validation_rules = $this->generateValidationRules($user_kyc_fields);

        $validated = Validator::make($request->all(),$validation_rules)->validate();
        $get_values = $this->placeValueWithFields($user_kyc_fields,$validated);

        $create = [
            'user_id'       => auth()->guard(get_auth_guard())->user()->id,
            'data'          => json_encode($get_values),
            'created_at'    => now(),
        ];

        DB::beginTransaction();
        try{
            DB::table('user_kyc_data')->updateOrInsert(["user_id" => $user->id],$create);
            $user->update([
                'kyc_verified'  => GlobalConst::PENDING,
            ]);
            DB::commit();
        }catch(Exception $e) {
            DB::rollBack();
            $user->update([
                'kyc_verified'  => GlobalConst::DEFAULT,
            ]);
            $this->generatedFieldsFilesDelete($get_values);
            return Response::error(['KYC information successfully submitted']);
        }

       return Response::success(['KYC information successfully submitted'],[],200);
    }

}
