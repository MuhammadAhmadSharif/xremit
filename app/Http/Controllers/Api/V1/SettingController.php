<?php

namespace App\Http\Controllers\Api\V1;


use Exception;
use App\Http\Helpers\Response;
use App\Models\Admin\UsefulLink;
use App\Models\Admin\AppSettings;
use App\Models\Admin\BasicSettings;
use App\Http\Controllers\Controller;
use App\Models\Admin\AppOnboardScreens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SettingController extends Controller
{
    /**
     * Method for bank list
     */
    public function bankList(Request $request){
        
        $validator      = Validator::make($request->all(),[
            'country_code'    => 'required',
        ]);
        if($validator->fails()) return Response::error($validator->errors()->all(),[]);
        $validated = $validator->validate();
        $bank_list          = getFlutterwaveBanks($request->country_code);
        
        return Response::success([__("Bank List data fetch successfully!")],[
            'bank_list' => $bank_list,
        ],200);
    }

    public function languages(){
        try{
            $api_languages = get_api_languages();
        }catch(Exception $e) {
            return Response::error([$e->getMessage()],[],500);
        }
        return Response::success([__("Language data fetch successfully!")],[
            'languages' => $api_languages,
        ],200);
    }

    public function basicSettings() {
        $basic_settings  = BasicSettings::orderBy("id")->get()->map(function($data){


            return [
                'id'                          => $data->id,
                'site_name'                   => $data->site_name,
                'base_color'                  => $data->base_color,
                'site_logo_dark'              => $data->site_logo_dark,
                'site_logo'                   => $data->site_logo,
                'site_fav_dark'               => $data->site_fav_dark,
                'site_fav'                    => $data->site_fav,
                'email_verification'          => $data->email_verification,
                'created_at'                  => $data->created_at,

            ];
        });
        $basic_seetings_image_paths = [
            'base_url'         => url("/"),
            'path_location'    => files_asset_path_basename("image-assets"),
            'default_image'    => files_asset_path_basename("default"),
        ];
        // splash screen

        $splash_screen   = AppSettings::orderBy("id")->get()->map(function($data){

            return [
                'id'                          => $data->id,
                'version'                     => $data->version,
                'splash_screen_image'         => $data->splash_screen_image,
                'created_at'                  => $data->created_at,
            ];
        });

        // onboard screen

        $onboard_screen   = AppOnboardScreens::where('status',true)->orderBy("id")->get()->map(function($data){

            return [
                'id'                           => $data->id,
                'title'                        => $data->title,
                'sub_title'                    => $data->sub_title,
                'image'                        => $data->image,
                'status'                       => $data->status,
                'last_edit_by'                 => $data->last_edit_by,
                'created_at'                   => $data->created_at,

            ];
        });

        // web links

        $about_page_link   = url('about');

        $privacy_policy = UsefulLink::where('slug','privacy-policy')->first();
        $privacy_policy_link = route('link',$privacy_policy->slug);

        $web_links =[
            [
                'name' => "About Us",
                'link' => $about_page_link,
            ],
            [
                'name' => "Privacy Policy",
                'link' => $privacy_policy_link,
            ]
        ];

        $screen_image_path    = [
            'base_url'                     => url("/"),
            'path_location'                => files_asset_path_basename("app-images"),
            'default_image'                => files_asset_path_basename("default"),
        ];
        return Response::success(['Basic Settings and Screen Data Fetch Successfully.'],[
            'basic_settings'               => $basic_settings,
            'splash_screen'                => $splash_screen,
            'onboard_screen'               => $onboard_screen,
            'web_links'                    => $web_links,
            'basic_seetings_image_paths'   => $basic_seetings_image_paths,
            'app_image_path'               => $screen_image_path,

        ],200);
    }


}
