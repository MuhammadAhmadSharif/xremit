<?php

namespace App\Http\Controllers\Api\V1\User;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Providers\Admin\BasicSettingsProvider;

class ProfileController extends Controller
{
    /**
     * Method for get the profile information
     */
    public function profileInfo() {
        $user = auth()->guard("api")->user();

        $response_data = $user->only([
            'id',
            'firstname',
            'lastname',
            'username',
            'email',
            'full_mobile',
            'image',
            'kyc_verified',
            'date_of_birth'
        ]);
        if($response_data['date_of_birth'] != null){
            $response_data['date_of_birth']  = Carbon::parse($user->date_of_birth)->toDateTimeString();
        }
        
        $response_data['country']        = $user->address->country ?? "";
        $response_data['city']           = $user->address->city ?? "";
        $response_data['state']          = $user->address->state ?? "";
        $response_data['zip']    = $user->address->zip ?? "";
        $response_data['address']        = $user->address->address ?? "";
        $response_data['kyc']            = [
            'data'          => $user->kyc->data ?? [],
            'reject_reason' => $user->kyc->reject_reason ?? "", 
        ];

        $image_paths = [
            'base_url'          => url("/"),
            'path_location'     => files_asset_path_basename("user-profile"),
            'default_image'     => files_asset_path_basename("profile-default"),
        ];

        $instructions = [
            'kyc_verified'      => "0: Default, 1: Approved, 2: Pending, 3:Rejected",
        ];

        return Response::success(['Profile info fetch successfully!'],[
            'instructions'  => $instructions,
            'user_info'     => $response_data , 
            'image_paths'   => $image_paths,
            'countries'     => get_all_countries(['id','name','mobile_code']),
        ],200);
    }
    /**
     * Method for update profile information
     */
    public function profileInfoUpdate(Request $request) {
        $validator          = Validator::make($request->all(),[
            'firstname'     => "required|string|max:60",
            'lastname'      => "required|string|max:60",
            'country'       => "required|string|max:50",
            'phone'         => "required|string|max:20",
            'address'       => "nullable|string|max:250",
            'city'          => "nullable|alpha_num|max:50",
            'state'         => "nullable|alpha_num|max:50",
            'zip_code'      => "nullable|string",
            'image'         => "nullable|image|mimes:jpg,png,svg,webp|max:10240",
        ]);

        if($validator->fails()) return Response::error($validator->errors()->all(),[]);

        $validated                  = $validator->validate();
        $validated['phone']         = get_only_numeric_data($validated['phone']);
        $validated['full_mobile']   = get_only_numeric_data($validated['phone']);

        $user = auth()->guard(get_auth_guard())->user();

        if(User::whereNot('id',$user->id)->where("full_mobile",$validated['full_mobile'])->exists()) {
            return Response::error(['Phone number already exists'],[],400);
        }
        if(is_numeric($validated['city'])){
            return Response::error(['The City must only contain letters.'],[],400);
        }
        if(is_numeric($validated['state'])){
            return Response::error(['The State must only contain letters.'],[],400);
        }
        $validated['address']       = [
            'country'               =>$validated['country'],
            'state'                 => $validated['state'] ?? "", 
            'city'                  => $validated['city'] ?? "", 
            'zip'                   => $validated['zip_code'] ?? "", 
            'address'               => $validated['address'] ?? "",
        ];

        if($request->hasFile("image")) {
            $image = upload_file($validated['image'],'junk-files',$user->image);
            $upload_image = upload_files_from_path_dynamic([$image['dev_path']],'user-profile');
            delete_file($image['dev_path']);
            chmod(get_files_path('user-profile') . '/' . $upload_image, 0644);
            $validated['image']     = $upload_image;
        }

        try{
            $user->update($validated);
        }catch(Exception $e) {
            return Response::error(["Something went wrong! Please try again"],[],500);
        }

        return Response::success(['Profile successfully updated!'],[],200);
    }
    /**
     * Method for profile password update
     */
    public function profilePasswordUpdate(Request $request) {
        $basic_settings = BasicSettingsProvider::get();
        $password_rule = "required|string|min:6|confirmed";
        if($basic_settings->secure_password) {
            $password_rule = ["required",Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised(),"confirmed"];
        }

        $validator = Validator::make($request->all(),[
            'current_password'      => "required|string",
            'password'              => $password_rule,
        ]);

        if($validator->fails()) return Response::error($validator->errors()->all(),[]);
        $validated = $validator->validate();

        if(!Hash::check($validated['current_password'],auth()->guard("api")->user()->password)) {
            return Response::error(['Current password didn\'t match'],[],400);
        }

        try{
            auth()->guard("api")->user()->update([
                'password'  => Hash::make($validated['password']),
            ]);
        }catch(Exception $e) {  
            return Response::error(['Something went wrong! Please try again.'],[],500);
        }

        return Response::success(['Password successfully updated!'],[],200);
    }
    /**
     * Method for logout
     */
    public function logout(Request $request) {
        
        $user = Auth::guard(get_auth_guard())->user();
        $token = $user->token();
        try{
            $token->revoke();
        }catch(Exception $e) {
            return Response::error(['Something went wrong! Please try again'],[],500);
        }
        return Response::success(['Logout success!'],[],200);
    }
    /**
     * Method for delete user profile 
     */
    public function deleteProfile(){
        $user = Auth::guard(get_auth_guard())->user();
        if(!$user){
            $message = ['success' =>  ['No user found']];
            return Response::error($message, []);
        }

        try {
            $user->status            = 0;
            $user->deleted_at        = now();
            $user->save();
        } catch (Exception $e) {
            return Response::error(['Something went wrong, please try again!'], []);
        }

        return Response::success(['User deleted successfull'], $user);
    }

    public function google2FA(){
        $user = Auth::guard(get_auth_guard())->user();

        $qr_code = generate_google_2fa_auth_qr();
        $qr_secrete = $user->two_factor_secret;
        $qr_status = $user->two_factor_status;

        $data = [
            'qr_code'    => $qr_code,
            'qr_secrete' => $qr_secrete,
            'qr_status'  => $qr_status,
            'alert' => "Don't forget to add this application in your google authentication app. Otherwise you can't login in your account.",
        ];


        return Response::success(['Data fetch Successfully'], $data);
    }
    public function google2FAStatusUpdate(Request $request){
        $validator = Validator::make($request->all(),[
            'status'        => "required|numeric",
        ]);

        if($validator->fails()){
            return Response::validation(['error' => $validator->errors()->all()]);
        }

        $validated = $validator->validated();

        $user = Auth::guard(get_auth_guard())->user();


        try{
            $user->update([
                'two_factor_status'         => $validated['status'],
                'two_factor_verified'       => true,
            ]);
        }catch(Exception $e) {
           return Response::error(['Something went wrong! Please try again']);
        }

        return Response::success(['Google 2FA Updated Successfully!'],[],200);
    }
}
