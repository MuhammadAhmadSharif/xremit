<?php

namespace App\Http\Controllers\User\Auth;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Admin\UsefulLink;
use App\Models\Admin\NewUserBonus;
use App\Models\Admin\SiteSections;
use App\Constants\SiteSectionConst;
use App\Http\Controllers\Controller;
use App\Traits\User\RegisteredUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Models\Admin\AdminNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Http\Helpers\PushNotificationHelper;
use App\Providers\Admin\BasicSettingsProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers,RegisteredUsers;

    protected $basic_settings;

    public function __construct()
    {
        $this->basic_settings = BasicSettingsProvider::get();
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm() {
        $page_title = "| User Registration";
        $footer_section_slug  = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer               = SiteSections::getData($footer_section_slug)->first();
        $subscribe_slug       = Str::slug(SiteSectionConst::SUBSCRIBE_SECTION);
        $subscribe            = SiteSections::getData($subscribe_slug)->first();
        $useful_link          = UsefulLink::where('status',true)->get();
        return view('user.auth.register',compact(
            'page_title',
            'footer',
            'subscribe',
            'useful_link'
        ));
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validated = $this->validator($request->all())->validate();
        $basic_settings             = $this->basic_settings;

        $validated = Arr::except($validated,['agree']);
        $validated['email_verified']    = ($basic_settings->email_verification == true) ? false : true;
        $validated['sms_verified']      = ($basic_settings->sms_verification == true) ? false : true;
        $validated['kyc_verified']      = ($basic_settings->kyc_verification == true) ? false : true;
        $validated['password']          = Hash::make($validated['password']);
        $validated['username']          = make_username($validated['firstname'],$validated['lastname']);

        if(User::where("username",$validated['username'])->exists()) {
            throw ValidationException::withMessages([
                'unknown'       => "Username already exists!",
            ]);
        }

        event(new Registered($user = $this->create($validated)));
        $this->guard()->login($user);

        return $this->registered($request, $user);
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data) {

        $basic_settings = $this->basic_settings;
        $passowrd_rule = "required|string|min:6";
        if($basic_settings->secure_password) {
            $passowrd_rule = ["required",Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()];
        }
        $agree_rule = "nullable";
        if($basic_settings->agree_policy) {
            $agree_rule = 'required|in:on';
        }
        return Validator::make($data,[
            'firstname'     => 'required|string|max:60',
            'lastname'      => 'required|string|max:60',
            'email'         => 'required|string|email|max:150|unique:users,email',
            'password'      => $passowrd_rule,
            'agree'         => $agree_rule ,
        ]);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $lastReferral = User::orderBy('id', 'desc')->value('refferal_user_id');
        $data['refferal_user_id'] = ($lastReferral !== null) ? $lastReferral + 1 : 4000;
        
        return User::create($data);
    }


    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        try{
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
            (new PushNotificationHelper())->prepare([1],[
                'title' => "New User" . "(" . $user->username . ")" . " register successfully.",
                'desc'  => "",
                'user_type' => 'admin',
            ])->send();
            return redirect()->intended(route('user.dashboard'));
        }catch(Exception $e) {
            
            return redirect()->route("user.login")->with(['error' => [$e->getMessage()]]);
        }
        
    }
}