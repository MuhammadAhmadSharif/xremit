<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Admin\Journal;
use App\Models\Admin\Language;
use App\Constants\LanguageConst;
use App\Models\Admin\SiteSections;
use App\Constants\SiteSectionConst;
use App\Http\Controllers\Controller;
use App\Models\Admin\WebJournalCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class SetupSectionsController extends Controller
{
    protected $languages;

    public function __construct()
    {
        $this->languages =  Language::get();
    }

    /**
     * Register Sections with their slug
     * @param string $slug
     * @param string $type
     * @return string
     */
    public function section($slug,$type) {
        $sections = [
            'banner'    => [
                'view'      => "bannerView",
                'update'    => "bannerUpdate",
            ],
            'brand'     => [
                'view'      => "brandView",
                'itemStore'    => "brandItemStore",
                'itemDelete'   => 'brandItemDelete',
            ],
            'about'    => [
                'view'      => "aboutView",
                'update'    => "aboutUpdate",
            ],
            'choose'    => [
                'view'      => "chooseView",
                'update'    => "chooseUpdate",
            ],
            'how-its-work'    => [
                'view'      => "howItsWorkView",
                'update'    => "howItsWorkUpdate",
                'itemStore' => "howItsWorkItemStore",
                'itemUpdate' => "howItsWorkItemUpdate",
                'itemDelete' => "howItsWorkItemDelete",
            ],
            'feature'    => [
                'view'      => "featureView",
                'update'    => "featureUpdate",
                'itemStore' => "featureItemStore",
                'itemUpdate' => "featureItemUpdate",
                'itemDelete' => "featureItemDelete",
            ],
            'testimonial'    => [
                'view'      => "testimonialView",
                'update'    => "testimonialUpdate",
                'itemStore' => "testimonialItemStore",
                'itemUpdate' => "testimonialItemUpdate",
                'itemDelete' => "testimonialItemDelete",
            ],
            'journal'        => [
                'view'       => "journalView",
                'update'     => "journalUpdate",
            ],
            'app-download'    => [
                'view'        => "appDownloadView",
                'update'      => "appDownloadUpdate",
            ],
            'footer'          => [
                'view'         => "footerView",
                'update'       => "footerUpdate",    
            ],
            'subscribe'          => [
                'view'         => "subscribeView",
                'update'       => "subscribeUpdate",    
            ],
            'contact'          => [
                'view'         => "contactView",
                'update'       => "contactUpdate",    
            ],
            'login'          => [
                'view'         => "loginView",
                'update'       => "loginUpdate",    
            ],
            'register'          => [
                'view'         => "registerView",
                'update'       => "registerUpdate",    
            ],
        ];

        if(!array_key_exists($slug,$sections)) abort(404);
        if(!isset($sections[$slug][$type])) abort(404);
        $next_step = $sections[$slug][$type];
        return $next_step;
    }

    /**
     * Method for getting specific step based on incomming request
     * @param string $slug
     * @return method
     */
    public function sectionView($slug) {
        $section = $this->section($slug,'view');
        return $this->$section($slug);
    }

    /**
     * Method for distribute store method for any section by using slug
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     * @return method
     */
    public function sectionItemStore(Request $request, $slug) {
        $section = $this->section($slug,'itemStore');
        return $this->$section($request,$slug);
    }

    /**
     * Method for distribute update method for any section by using slug
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     * @return method
     */
    public function sectionItemUpdate(Request $request, $slug) {
        $section = $this->section($slug,'itemUpdate');
        return $this->$section($request,$slug);
    }

    /**
     * Method for distribute delete method for any section by using slug
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     * @return method
     */
    public function sectionItemDelete(Request $request,$slug) {
        $section = $this->section($slug,'itemDelete');
        return $this->$section($request,$slug);
    }

    /**
     * Method for distribute update method for any section by using slug
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     * @return method
     */
    public function sectionUpdate(Request $request,$slug) {
        $section = $this->section($slug,'update');
        return $this->$section($request,$slug);
    }

    /**
     * Mehtod for show banner section page
     * @param string $slug
     * @return view
     */
    public function bannerView($slug) {
        $page_title = "Banner Section";
        $section_slug = Str::slug(SiteSectionConst::BANNER_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.banner-section',compact(
            'page_title',
            'data',
            'languages',
            'slug',
        ));
    }

    /**
     * Mehtod for update banner section information
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function bannerUpdate(Request $request,$slug) {
        $basic_field_name = [
            'heading' => "required|string|max:100",
            'sub_heading' => "required|string|max:1000",
            'button_name' => "required|string|max:50",
            'video_link' => "required|url|max:255"
        ];

        $slug = Str::slug(SiteSectionConst::BANNER_SECTION);
        $section = SiteSections::where("key",$slug)->first();
        if($section != null){
            $data         = json_decode(json_encode($section->value),true);
        }else{
            $data         = [];
        }
        $data['image']    = $section->value->image ?? "";

        if($request->hasFile("image")) {
            $data['image']      = $this->imageValidate($request,"image",$section->value->image ?? null);
        }

        $data['language']  = $this->contentValidate($request,$basic_field_name);
        $update_data['value']  = $data;
        $update_data['key']    = $slug;

        try{
            SiteSections::updateOrCreate(['key' => $slug],$update_data);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }

        return back()->with(['success' => ['Section updated successfully!']]);
    }
    /**
     * Method for show brand section page
     * @param string
     * @param \Illuminate\Http\Request $request
     */
    public function brandView($slug){
        $page_title   = "Brand Section";
        $section_slug = Str::slug(SiteSectionConst::BRAND_SECTION);
        $data         = SiteSections::getData($section_slug)->first();
        $languages    = $this->languages;

        return view('admin.sections.setup-sections.brand-section',compact(
            'page_title',
            'data',
            'languages',
            'slug'
        ));
    }
    /**
     * Mehtod for store brand item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function brandItemStore(Request $request,$slug){
        $slug = Str::slug(SiteSectionConst::BRAND_SECTION);
        $section = SiteSections::where("key",$slug)->first();
        if($section != null){
            $section_data = json_decode(json_encode($section->value),true);
        }else{
            $section_data = [];
        }
        $unique_id = uniqid();


        $validator  = Validator::make($request->all(),[
            'image'           => "nullable|image|mimes:jpg,png,svg,webp|max:10240",
        ]);
        if($validator->fails()) return back()->withErrors($validator->errors())->withInput()->with('modal','add-brand');
        $section_data['items'][$unique_id]['id']           = $unique_id;
        $section_data['items'][$unique_id]['image']        = "";

        if($request->hasFile("image")){
            $section_data['items'][$unique_id]['image']  = $this->imageValidate($request,"image",$section->value->items->image ?? null);
        }
        $update_data['key'] = $slug;
        $update_data['value'] = $section_data;
        try{
            SiteSections::updateOrCreate(['key' => $slug],$update_data);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success'   => ['Brand Item Added']]);
    }
    /**
     * Mehtod for delete testimonial item
     * @param string $slug
     * @return view
     */
    public function brandItemDelete(Request $request,$slug){
        $request->validate([
            'target'  => 'required|string',
        ]);

        $slug     = Str::slug(SiteSectionConst::BRAND_SECTION);
        $section  = SiteSections::getData($slug)->first();
        if(!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value),true);
        if(!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if(!array_key_exists($request->target,$section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        try{
            $image_name = $section_values['items'][$request->target]['image'];
            unset($section_values['items'][$request->target]);
            $image_path = get_files_path('site-section') . '/' . $image_name;
            delete_file($image_path);
            $section->update([
                'value'    => $section_values,
            ]);

        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Brand item deleted successfully!']]);
    }
    /**
     * Method for show about section page
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function aboutView($slug){
        $page_title   = "About Section";
        $section_slug = Str::slug(SiteSectionConst::ABOUT_SECTION);
        $data         = SiteSections::getData($section_slug)->first();
        $languages    = $this->languages;

        return view('admin.sections.setup-sections.about-section',compact(
            'page_title',
            'data',
            'languages',
            'slug'
        ));
    }

    /**
     * Method for update about section information
     * @param string
     * @param \Illuminate\\Http\Request $request
     */
    
    public function aboutUpdate(Request $request,$slug){
        $basic_field_name = [
            'heading'     => 'required|string|max:100',
            'sub_heading' => 'required|string',
        ];

        $slug             = Str::slug(SiteSectionConst::ABOUT_SECTION);
        $section          = SiteSections::where("key",$slug)->first();

        if($section      != null){
            $data         = json_decode(json_encode($section->value),true);
        }else{
            $data         = [];
        }

        $data['image']    = $section->value->image ?? "";
        if($request->hasFile("image")){
            $data['image']= $this->imageValidate($request,"image",$section->value->image ?? null);
        }

        $data['language']     = $this->contentValidate($request,$basic_field_name);
        $update_data['key']   = $slug;
        $update_data['value'] = $data;
        try{
            SiteSections::updateOrCreate(['key' => $slug],$update_data);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with( ['success' => ['Section updated successfully!']]);

    }

    /**
     * Method for show about section page
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function chooseView($slug){
        $page_title   = "Choose Us Section";
        $section_slug = Str::slug(SiteSectionConst::CHOOSE_US_SECTION);
        $data         = SiteSections::getData($section_slug)->first();
        $languages    = $this->languages;

        return view('admin.sections.setup-sections.choose-us-section',compact(
            'page_title',
            'data',
            'languages',
            'slug'
        ));
    }
    /**
     * Method for update choose us section information
     * @param string
     * @param \Illuminate\Http\Request $request
     */
    public function chooseUpdate(Request $request){
        $basic_field_name = [
            'title'       => 'required|string|max:100',
            'heading'     => 'required|string|max:100',
            'sub_heading' => 'required|string',
            'button_name' => 'required|string',
            'video_link'  => 'required|url',
        ];

        $slug             = Str::slug(SiteSectionConst::CHOOSE_US_SECTION);
        $section          = SiteSections::where("key",$slug)->first();
        if($section != null){
            $data         = json_decode(json_encode($section->value),true);
        }else{
            $data         = [];
        }
        $data['image']    = $section->value->image ?? "";
        $data['video_link'] = $request->video_link;
        $data['button_name'] = $request->button_name;
        if($request->hasFile("image")){
            $data['image'] = $this->imageValidate($request,"image",$section->value->image ?? null);
        }
        $data['language']  = $this->contentValidate($request,$basic_field_name);
        $update_data['key'] = $slug;
        $update_data['value'] = $data;
        try{
            SiteSections::updateOrCreate(['key' => $slug],$update_data);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Section updated successfully!']]);
    }
    /**
     * Method for show how-its-work section page
     * @param string $slug
     * @return view
     */
    public function howItsWorkView($slug){
        $page_title    = "How Its Work Section";
        $section_slug  = Str::slug(SiteSectionConst::HOW_ITS_WORK_SECTION);
        $data          = SiteSections::getData($section_slug)->first();
        $languages     = $this->languages;

        return view('admin.sections.setup-sections.how-its-work-section',compact(
            'page_title',
            'data',
            'languages',
            'slug'
        ));
    }
    /**
     * Method for update how its work section information
     * @param string $slug
     * @param \Illuminate\Http\Request $request
     */
    public function howItsWorkUpdate(Request $request,$slug){
        
        $basic_field_name = [
            'title'       => 'required|string|max:100',
            'heading'     => 'required|string|max:100',
            'sub_heading' => 'required|string',
        ];

        $slug     = Str::slug(SiteSectionConst::HOW_ITS_WORK_SECTION);
        $section  = SiteSections::where("key",$slug)->first();

        if($section      != null){
            $data         = json_decode(json_encode($section->value),true);
        }else{
            $data         = [];
        }

        $data['image']    = $section->value->image ?? "";
        if($request->hasFile("image")){
            $data['image']= $this->imageValidate($request,"image",$section->value->image ?? null);
        }

        $data['language']     = $this->contentValidate($request,$basic_field_name);

        $update_data['key']   = $slug;
        $update_data['value'] = $data;

        try{
            SiteSections::updateOrCreate(["key"=>$slug],$update_data);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return back()->with(['success' => ['Section updated successfully!']]);
     
    }
    /**
     * Method for store how its work item
     * @param string $slug
     * @param \Illuminate\Http\Request $request
     */
    public function howItsWorkItemStore(Request $request,$slug){
        $basic_field_name  = [
            'item_title'        => 'required|string|max:100',
            'description'      => 'required|string'
        ];

        $language_wise_data  = $this->contentValidate($request,$basic_field_name,'HowItsWork-add');

        if($language_wise_data instanceof RedirectResponse) return $language_wise_data;

        $slug    = Str::slug(SiteSectionConst::HOW_ITS_WORK_SECTION);
        $section  = SiteSections::where("key",$slug)->first();

        if($section != null){
            $data = json_decode(json_encode($section->value),true);
        }else{
            $data  = [];
        }

        $unique_id = uniqid();
        $validator  = Validator::make($request->all(),[
            'image'           => "nullable|image|mimes:jpg,png,svg,webp|max:10240",
        ]);

        if($validator->fails()) return back()->withErrors($validator->errors())->withInput()->with('modal','HowItsWork-add');

        $data['items'][$unique_id]['language'] = $language_wise_data;
        $data['items'][$unique_id]['id']   = $unique_id;
        $data['items'][$unique_id]['image'] = "";
        if($request->hasFile("image")){
            $data['items'][$unique_id]['image']  = $this->imageValidate($request,"image",$section->value->items->image ?? null);
        }

        $update_data['slug'] = $slug;
        $update_data['value'] = $data;
        try{
            SiteSections::updateOrCreate(['key' => $slug],$update_data);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }
        return back()->with(['success' => ['Item added successfully.']]);
    }
    /**
     * Method for update how its work item update
     * @param string $slug
     * @param \Illuminate\Http\Request $request
     */
    public function howItsWorkItemUpdate(Request $request,$slug){
        $request->validate([
            'target'           => 'required|string',
        ]);

        $basic_field_name      = [
            'item_title_edit'     => "required|string|max:100",
            'description_edit'     => "required|string",
        ];

        

        $slug    = Str::slug(SiteSectionConst::HOW_ITS_WORK_SECTION);
        $section = SiteSections::getData($slug)->first();
        if(!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value),true);
        if(!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if(!array_key_exists($request->target,$section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        $request->merge(['old_image' => $section_values['items'][$request->target]['image'] ?? null]);

        $language_wise_data = $this->contentValidate($request,$basic_field_name,"testimonial-edit");
        if($language_wise_data instanceof RedirectResponse) return $language_wise_data;

        $language_wise_data = array_map(function($language) {
            return replace_array_key($language,"_edit");
        },$language_wise_data);


        $section_values['items'][$request->target]['language']      = $language_wise_data;
        


        if($request->hasFile("image")) {
            $section_values['items'][$request->target]['image']    = $this->imageValidate($request,"image",$section_values['items'][$request->target]['image'] ?? null);
        }

        try{
            $section->update([
                'value' => $section_values,
            ]);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again']]);
        }

        return back()->with(['success' => ['Information updated successfully!']]);
    }
    /**
     * Method for how its work item delete
     * @param string $slug
     * @param \Illuminate\Http\Request $request
     */
    public function howItsWorkItemDelete(Request $request,$slug){
        $request->validate([
            'target'     => 'required|string',
        ]);

        $slug         = Str::slug(SiteSectionConst::HOW_ITS_WORK_SECTION);
        $section      = SiteSections::getData($slug)->first();
        if(!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value),true);
        if(!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if(!array_key_exists($request->target,$section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        try{
            $image_name = $section_values['items'][$request->target]['image'];
            unset($section_values['items'][$request->target]);
            $image_path = get_files_path('site-section') . '/' . $image_name;
            delete_file($image_path);
            $section->update([
                'value'    => $section_values,
            ]);

        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Section item deleted successfully!']]);
    }
    /**
     * method for show feature section page
     * @param string $slug
     * @return view
     */
    public function featureView($slug){
        $page_title    = "Feature Section";
        $section_slug  = Str::slug(SiteSectionConst::FEATURE_SECTION);
        $data          = SiteSections::getData($section_slug)->first();
        $languages     = $this->languages;

        return view('admin.sections.setup-sections.feature-section',compact(
            'page_title',
            'data',
            'languages',
            'slug'
        ));
    }
    /**
     * Method for update feature section information
     * @param string $slug
     * @param \Illuminate\Http\Request $request
     */
    public function featureUpdate(Request $request,$slug){
        $basic_field_name = [
            'title'       => 'required|string|max:100',
            'heading'     => 'required|string',
        ];
        $slug             = Str::slug(SiteSectionConst::FEATURE_SECTION);
        $section          = SiteSections::where("key",$slug)->first();

        if($section != null){
            $data         = json_decode(json_encode($section->value),true);
        }else{
            $data         = [];
        }

        $data['language']   = $this->contentValidate($request,$basic_field_name);
        $update_data['key']  = $slug;
        $update_data['value'] = $data;
        try{
            SiteSections::updateOrCreate(['key'=>$slug],$update_data);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Section updated successfully!']]);
    }
    /**
     * Method for store feature item
     * @param string $slug
     * @param \Illuminate\Http\Request $request
     */
    public function featureItemStore(Request $request,$slug){
        $basic_field_name  = [
            'item_title'        => 'required|string|max:100',
            'description'      => 'required|string'
        ];

        $language_wise_data  = $this->contentValidate($request,$basic_field_name,'HowItsWork-add');

        if($language_wise_data instanceof RedirectResponse) return $language_wise_data;

        $slug    = Str::slug(SiteSectionConst::FEATURE_SECTION);
        $section  = SiteSections::where("key",$slug)->first();

        if($section != null){
            $data = json_decode(json_encode($section->value),true);
        }else{
            $data  = [];
        }

        $unique_id = uniqid();
        $validator  = Validator::make($request->all(),[
            'image'           => "nullable|image|mimes:jpg,png,svg,webp|max:10240",
        ]);

        if($validator->fails()) return back()->withErrors($validator->errors())->withInput()->with('modal','HowItsWork-add');

        $data['items'][$unique_id]['language'] = $language_wise_data;
        $data['items'][$unique_id]['id']   = $unique_id;
        $data['items'][$unique_id]['image'] = "";
        if($request->hasFile("image")){
            $data['items'][$unique_id]['image']  = $this->imageValidate($request,"image",$section->value->items->image ?? null);
        }

        $update_data['slug'] = $slug;
        $update_data['value'] = $data;
        try{
            SiteSections::updateOrCreate(['key' => $slug],$update_data);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }
        return back()->with(['success' => ['Item added successfully.']]);
    }
    /**
     * Method for feature item update
     * @param string $slug
     * @param \Illuminate\Http\Request $request
     */
    public function featureItemUpdate(Request $request,$slug){
        $request->validate([
            'target'           => 'required|string',
        ]);

        $basic_field_name      = [
            'item_title_edit'     => "required|string|max:100",
            'description_edit'     => "required|string",
        ];

        

        $slug    = Str::slug(SiteSectionConst::FEATURE_SECTION);
        $section = SiteSections::getData($slug)->first();
        if(!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value),true);
        if(!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if(!array_key_exists($request->target,$section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        $request->merge(['old_image' => $section_values['items'][$request->target]['image'] ?? null]);

        $language_wise_data = $this->contentValidate($request,$basic_field_name,"testimonial-edit");
        if($language_wise_data instanceof RedirectResponse) return $language_wise_data;

        $language_wise_data = array_map(function($language) {
            return replace_array_key($language,"_edit");
        },$language_wise_data);


        $section_values['items'][$request->target]['language']      = $language_wise_data;
        


        if($request->hasFile("image")) {
            $section_values['items'][$request->target]['image']    = $this->imageValidate($request,"image",$section_values['items'][$request->target]['image'] ?? null);
        }

        try{
            $section->update([
                'value' => $section_values,
            ]);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again']]);
        }

        return back()->with(['success' => ['Information updated successfully!']]);
    }
    /**
     * Method for feature item delete
     * @param string $slug
     * @param \Illuminate\Http\Request $request 
     */
    public function featureItemDelete(Request $request,$slug){
        $request->validate([
            'target'     => 'required|string',
        ]);

        $slug         = Str::slug(SiteSectionConst::FEATURE_SECTION);
        $section      = SiteSections::getData($slug)->first();
        if(!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value),true);
        if(!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if(!array_key_exists($request->target,$section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        try{
            $image_name = $section_values['items'][$request->target]['image'];
            unset($section_values['items'][$request->target]);
            $image_path = get_files_path('site-section') . '/' . $image_name;
            delete_file($image_path);
            $section->update([
                'value'    => $section_values,
            ]);

        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Section item deleted successfully!']]);
    }
    /**
     * Method for show testimonial section page
     * @param string $slug
     * @param \Illuminate\Http\Request $request 
     */
    public function testimonialView($slug){
        $page_title     = "Testimonial Section";
        $section_slug   = Str::slug(SiteSectionConst::TESTIMONIAL_SECTION);
        $data           = SiteSections::getData($section_slug)->first();
        $languages      = $this->languages;

        return view('admin.sections.setup-sections.testimonial-section',compact(
            'page_title',
            'data',
            'languages',
            'slug'
        ));
    }
    /**
     * Mehtod for update testimonial section
     * @param string $slug
     * @param \Illuminate\Http\Request $request
    */
    public function testimonialUpdate(Request $request,$slug){
        $basic_field_name     = [
            'title'           => 'required|string|max:100',
            'heading'         => 'required|string|max:100',
        ];

        $slug      = Str::slug(SiteSectionConst::TESTIMONIAL_SECTION);
        $section   = SiteSections::where('key',$slug)->first();
        if($section != null){
            $data  = json_decode(json_encode($section->value),true);
        }else{
            $data = [];
        }

        $data['language']       = $this->contentValidate($request,$basic_field_name);
        $update_data['key']     = $slug;
        $update_data['value']   = $data;
        try{
            SiteSections::updateOrCreate(['key'=>$slug],$update_data);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Section updated successfully!']]);
        
    }
    /**
     * Mehtod for store testimonial item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
    */
    public function testimonialItemStore(Request $request,$slug) {
        $basic_field_name = [
            'comment'     => "required|string|max:500",
        ];

        $language_wise_data = $this->contentValidate($request,$basic_field_name,"testimonial-add");
        if($language_wise_data instanceof RedirectResponse) return $language_wise_data;
        $slug    = Str::slug(SiteSectionConst::TESTIMONIAL_SECTION);
        $section = SiteSections::where("key",$slug)->first();

        if($section != null) {
            $section_data = json_decode(json_encode($section->value),true);
        }else {
            $section_data = [];
        }
        $unique_id = uniqid();

        $validator  = Validator::make($request->all(),[
            'name'            => "required|string|max:100",
            'designation'     => "required|string|max:100",
            'image'           => "nullable|image|mimes:jpg,png,svg,webp|max:10240",
        ]);

        if($validator->fails()) return back()->withErrors($validator->errors())->withInput()->with('modal','testimonial-add');
        $validated = $validator->validate();

        $section_data['items'][$unique_id]['language']     = $language_wise_data;
        $section_data['items'][$unique_id]['id']           = $unique_id;
        $section_data['items'][$unique_id]['image']        = "";
        $section_data['items'][$unique_id]['name']         = $validated['name'];
        $section_data['items'][$unique_id]['designation']  = $validated['designation'];
        $section_data['items'][$unique_id]['created_at']   = now();
        if($request->hasFile("image")) {
            $section_data['items'][$unique_id]['image']    = $this->imageValidate($request,"image",$section->value->items->image ?? null);
        }

        $update_data['key']     = $slug;
        $update_data['value']   = $section_data;

        try{
            SiteSections::updateOrCreate(['key' => $slug],$update_data);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again']]);
        }

        return back()->with(['success' => ['Item added successfully.']]);
    }
    
    /**
     * Mehtod for update testimonial item
     * @param string $slug
     * @param \Illuminate\Http\Request $request
     */
    public function testimonialItemUpdate(Request $request,$slug){
        $request->validate([
            'target'           => 'required|string',
        ]);

        $basic_field_name      = [
            'comment_edit'     => "required|string|max:255",
        ];

        

        $slug    = Str::slug(SiteSectionConst::TESTIMONIAL_SECTION);
        $section = SiteSections::getData($slug)->first();
        if(!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value),true);

        if(!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if(!array_key_exists($request->target,$section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        $request->merge(['old_image' => $section_values['items'][$request->target]['image'] ?? null]);

        $language_wise_data = $this->contentValidate($request,$basic_field_name,"testimonial-edit");
        if($language_wise_data instanceof RedirectResponse) return $language_wise_data;

        $language_wise_data = array_map(function($language) {
            return replace_array_key($language,"_edit");
        },$language_wise_data);

        $validator  = Validator::make($request->all(),[
            'name_edit'            => "required|string|max:100",
            'designation_edit'     => "required|string|max:100",
        ]);

        if($validator->fails()) return back()->withErrors($validator->errors())->withInput()->with('modal','testimonial-edit');
        $validated = $validator->validate();

        $section_values['items'][$request->target]['language']      = $language_wise_data;
        $section_values['items'][$request->target]['name']          = $validated['name_edit'];
        $section_values['items'][$request->target]['designation']   = $validated['designation_edit'];

        if($request->hasFile("image")) {
            $section_values['items'][$request->target]['image']    = $this->imageValidate($request,"image",$section_values['items'][$request->target]['image'] ?? null);
        }

        try{
            $section->update([
                'value' => $section_values,
            ]);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again']]);
        }

        return back()->with(['success' => ['Information updated successfully!']]);


    }
    /**
     * Mehtod for delete testimonial item
     * @param string $slug
     * @param \Illuminate\Http\Request $request
     */
    public function testimonialItemDelete(Request $request,$slug){
        $request->validate([
            'target'     => 'required|string',
        ]);

        $slug         = Str::slug(SiteSectionConst::TESTIMONIAL_SECTION);
        $section      = SiteSections::getData($slug)->first();
        if(!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value),true);
        if(!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if(!array_key_exists($request->target,$section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        try{
            $image_name = $section_values['items'][$request->target]['image'];
            unset($section_values['items'][$request->target]);
            $image_path = get_files_path('site-section') . '/' . $image_name;
            delete_file($image_path);
            $section->update([
                'value'    => $section_values,
            ]);

        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Section item deleted successfully!']]);
    }
    /**
     *  Method for show journal section page
     * @param string $slug
     * @return view
     */
    public function journalView($slug){
        $page_title       = "Journal Section";
        $section_slug     = Str::slug(SiteSectionConst::JOURNAL_SECTION);
        $data             = SiteSections::getData($section_slug)->first();
        $languages        = $this->languages;
        $category         = WebJournalCategory::get();
        $active_category  = WebJournalCategory::where('status',true)->get();
        $journal          = Journal::orderByDesc("id")->get();
        $journal_active   = Journal::where('status',true)->get();
        $journal_deactive = Journal::where('status',false)->get();


        return view('admin.sections.setup-sections.journal-section',compact(
            'page_title',
            'data',
            'languages',
            'slug',
            'category',
            'active_category',
            'journal',
            'journal_active',
            'journal_deactive',
        ));
    }
    /**
     * Mehtod for update webJournal section page
     * @param string $slug
     * @return view
     */
    public function journalUpdate(Request $request,$slug){

        $basic_field_name = [
            'title'       => 'required|string|max:100',
            'heading'     => 'required|string|max:100',
        ];

        $slug     = Str::slug(SiteSectionConst::JOURNAL_SECTION);
        $section  = SiteSections::where("key",$slug)->first();
        if($section != null ){
            $data    = json_decode(json_encode($section->value),true);
        }else{
            $data    =[];
        }

        $data['language']     = $this->contentValidate($request,$basic_field_name);
        $update_data['key']   = $slug;
        $update_data['value'] = $data;

        try{
            SiteSections::updateOrCreate(["key"=>$slug],$update_data);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return back()->with(['success' => ['Section updated successfully!']]);
     
    }
    /**
     * Method for show subscribe section page
     * @param string $slug
     * @return view
     */
    public function subscribeView($slug){
        $page_title     = "Subscribe Section";
        $section_slug   = Str::slug(SiteSectionConst::SUBSCRIBE_SECTION);
        $data           = SiteSections::getData($section_slug)->first();
        $languages      = $this->languages;

        return view('admin.sections.setup-sections.subscribe-section',compact(
            'page_title',
            'data',
            'languages',
            'slug',
        ));
    }
    /**
     * Method for update subscribe section information
     * @param string $slug
     * @param \Illuminate\Http\Request $request 
     */
    public function subscribeUpdate(Request $request,$slug){
        $basic_field_name    = [
            'title'          => 'required|string|max:100',
            'description'    => 'required|string',
            
        ];
        $slug           = Str::slug(SiteSectionConst::SUBSCRIBE_SECTION);
        $section        = SiteSections::where("key",$slug)->first();
        if($section != null ){
            $data       = json_decode(json_encode($section->value),true);
        }else{
            $data       = [];
        }

        $data['language']      = $this->contentValidate($request,$basic_field_name);
        $update_data['key']    = $slug;
        $update_data['value']  = $data;
        
        try{
            SiteSections::updateOrCreate(['key'=>$slug],$update_data);
        }catch(Exception $e){
            return back()->with(['error'=>'Something went wrong! Please try again.']);
        }
        return back()->with(['success'  =>  ['Section updated successfully!']]);
    }
    /**
     * Method for show footer section 
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function footerView($slug) {
        $page_title = "Footer Section";
        $section_slug  = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $data          = SiteSections::getData($section_slug)->first();
        $languages     = $this->languages;

        return view('admin.sections.setup-sections.footer-section',compact(
            'page_title',
            'data',
            'languages',
            'slug',
        ));
    }
    /**
     * Method for update footer section 
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function footerUpdate(Request $request,$slug) {
        $slug      = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $section   = SiteSections::where('key',$slug)->first();
        if($section != null){
            $data  = json_decode(json_encode($section->value),true);
        }else{
            $data = [];
        }

        $basic_field_name = [
            'description'   => "required|string|max:255",
        ];

        $data['footer']['language']   = $this->contentValidate($request,$basic_field_name);

        $validated = Validator::make($request->all(),[
            'icon'                 => "nullable|array",
            'icon.*'               => "nullable|string|max:200",
            'link'                 => "nullable|array",
            'link.*'               => "nullable|url|max:255",
        ])->validate();


        // generate input fields
        $social_links = [];
        foreach($validated['icon'] ?? [] as $key => $icon) {
            $social_links[] = [
                'icon'          => $icon,
                'link'          => $validated['link'][$key] ?? "",
            ];
        }

        
        $data['social_links']         = $social_links;
        

        $data['footer']['image']      = $section->value->footer->image ?? "";
        if($request->hasFile("image")) {
            $data['footer']['image']  = $this->imageValidate($request,"image",$section->value->footer->image ?? null);
        }
        try{
            SiteSections::updateOrCreate(['key' => $slug],[
                'key'   => $slug,
                'value' => $data,
            ]);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }
        return back()->with(['success' => ['Section updated successfully!']]);
    }
    
    /**
     * Method for view app download section page
     * @param string $slug
     * @return view 
     */
    public function appDownloadView($slug){
        $page_title     = "App Download Section";
        $section_slug   = Str::slug(SiteSectionConst::APP_DOWNLOAD_SECTION);
        $data           = SiteSections::getData($section_slug)->first();
        $languages      = $this->languages;

        return view('admin.sections.setup-sections.app-download-section',compact(
            'page_title',
            'data',
            'languages',
            'slug',
        ));
    }
    /**
     * Method for update app download section information
     * @param string $slug
     * @param \Illuminate\Http\Request $request
     */
    public function appDownloadUpdate(Request $request,$slug){
        $basic_field_name = [
            'title'       => 'required|string|max:100',
            'heading'     => 'required|string|max:255',
            'sub_heading' => "required|string",
        ];

        $slug          = Str::slug(SiteSectionConst::APP_DOWNLOAD_SECTION);
        $section       = SiteSections::where("key",$slug)->first();

        if($section != null){
            $data  = json_decode(json_encode($section->value),true);
        }else{
            $data  = [];
        }
        $validator  = Validator::make($request->all(),[
            'google_play_link' => "required|url",
            'app_store_link'   => "required|url",
            'image'            => "nullable|image|mimes:jpg,png,svg,webp|max:10240",
        ]);

        if($validator->fails()) return back()->withErrors($validator->errors())->withInput();

        $validated = $validator->validate();

        $data['image']    = $section->value->image ?? "";
        $data['google_play_link'] = $validated['google_play_link'];
        $data['app_store_link'] = $validated['app_store_link'];

        if($request->hasFile("image")){
            $data['image']= $this->imageValidate($request,"image",$section->value->image ?? null);
        }

        $data['language']     = $this->contentValidate($request,$basic_field_name);

        $update_data['key']   = $slug;
        $update_data['value'] = $data;
        try{
            SiteSections::updateOrCreate(['key' => $slug],$update_data);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Section updated successfully!']]);


    }
    
/**
     * Method for show contact section page
     * @param string $slug
     * @return view
     */
    public function contactView($slug){
        $page_title      = "Contact Section";
        $section_slug    = Str::slug(SiteSectionConst::CONTACT_SECTION);
        $data            = SiteSections::getData($section_slug)->first();
        $languages       = $this->languages;

        return view('admin.sections.setup-sections.contact-section',compact(
            'page_title',
            'data',
            'languages',
            'slug',
        ));
    }
    /**
     * Method for update contact section information
     * @param string $slug
     * @param \Illuminate\Http\Request $request
     */
    public function contactUpdate(Request $request, $slug){
        $basic_field_name = [
            'title'        => "required|string|max:100",
            'description'  => "required|string",
            
        ];
    
        $slug       = Str::slug(SiteSectionConst::CONTACT_SECTION);
        $section    = SiteSections::where("key",$slug)->first();
        if($section != null) {
            $data = json_decode(json_encode($section->value),true);
        }else {
            $data = [];
        }
        $validated  = Validator::make($request->all(),[
            'phone'            => "required|string|max:100",
            'address'          => "required|string|max:100",
            'email'            => "required|email", 
            'schedule'         => "nullable|array",
            'schedule.*'       => "nullable|string|max:255",  
        ])->validate();;
        
        $schedules = [];
        foreach($validated['schedule'] ?? [] as $key => $schedule) {
            $schedules[] = [
                'schedule'          => $validated['schedule'][$key] ?? "",
                
            ];
        }

        
        $data['schedules']         = $schedules;

        $data['language']            = $this->contentValidate($request,$basic_field_name);
        $data['phone']    = $validated['phone'];
        $data['address']  = $validated['address'];
        $data['email']    = $validated['email'];
        $data['image']    = $section->value->image ?? "";
        if($request->hasFile("image")){
            $data['image'] = $this->imageValidate($request,"image",$section->value->image ?? null);
        }

        $update_data['key']    = $slug;
        $update_data['value']  = $data;
        try{
            SiteSections::updateOrCreate(['key' => $slug],$update_data);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section updated successfully!']]);
    }
    /**
     * Method for show login section page
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function loginView($slug){
        $page_title   = "Login Section";
        $section_slug = Str::slug(SiteSectionConst::LOGIN_SECTION);
        $data         = SiteSections::getData($section_slug)->first();
        $languages    = $this->languages;

        return view('admin.sections.setup-sections.login-section',compact(
            'page_title',
            'data',
            'languages',
            'slug'
        ));
    }

    /**
     * Method for update login section information
     * @param string
     * @param \Illuminate\\Http\Request $request
     */
    
    public function loginUpdate(Request $request,$slug){
        $basic_field_name = [
            'heading'     => 'required|string|max:100'
        ];

        $slug             = Str::slug(SiteSectionConst::LOGIN_SECTION);
        $section          = SiteSections::where("key",$slug)->first();

        if($section      != null){
            $data         = json_decode(json_encode($section->value),true);
        }else{
            $data         = [];
        }

        $data['image']    = $section->value->image ?? "";
        if($request->hasFile("image")){
            $data['image']= $this->imageValidate($request,"image",$section->value->image ?? null);
        }

        $data['language']     = $this->contentValidate($request,$basic_field_name);
        $update_data['key']   = $slug;
        $update_data['value'] = $data;
        try{
            SiteSections::updateOrCreate(['key' => $slug],$update_data);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with( ['success' => ['Section updated successfully!']]);

    }
    /**
     * Method for show register section page
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function registerView($slug){
        $page_title   = "Register Section";
        $section_slug = Str::slug(SiteSectionConst::REGISTER_SECTION);
        $data         = SiteSections::getData($section_slug)->first();
        $languages    = $this->languages;

        return view('admin.sections.setup-sections.register-section',compact(
            'page_title',
            'data',
            'languages',
            'slug'
        ));
    }

    /**
     * Method for update register section information
     * @param string
     * @param \Illuminate\\Http\Request $request
     */
    
    public function registerUpdate(Request $request,$slug){
        $basic_field_name = [
            'heading'     => 'required|string|max:100'
        ];

        $slug             = Str::slug(SiteSectionConst::REGISTER_SECTION);
        $section          = SiteSections::where("key",$slug)->first();

        if($section      != null){
            $data         = json_decode(json_encode($section->value),true);
        }else{
            $data         = [];
        }

        $data['image']    = $section->value->image ?? "";
        if($request->hasFile("image")){
            $data['image']= $this->imageValidate($request,"image",$section->value->image ?? null);
        }

        $data['language']     = $this->contentValidate($request,$basic_field_name);
        $update_data['key']   = $slug;
        $update_data['value'] = $data;
        try{
            SiteSections::updateOrCreate(['key' => $slug],$update_data);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with( ['success' => ['Section updated successfully!']]);

    }
    /**
     * Method for get languages form record with little modification for using only this class
     * @return array $languages
     */
    public function languages() {
        $languages = Language::whereNot('code',LanguageConst::NOT_REMOVABLE)->select("code","name")->get()->toArray();
        $languages[] = [
            'name'      => LanguageConst::NOT_REMOVABLE_CODE,
            'code'      => LanguageConst::NOT_REMOVABLE,
        ];
        return $languages;
    }

    /**
     * Method for validate request data and re-decorate language wise data
     * @param object $request
     * @param array $basic_field_name
     * @return array $language_wise_data
     */
    public function contentValidate($request,$basic_field_name,$modal = null) {
        $languages = $this->languages();

        $current_local = get_default_language_code();
        $validation_rules = [];
        $language_wise_data = [];
        foreach($request->all() as $input_name => $input_value) {
            foreach($languages as $language) {
                $input_name_check = explode("_",$input_name);
                $input_lang_code = array_shift($input_name_check);
                $input_name_check = implode("_",$input_name_check);
                if($input_lang_code == $language['code']) {
                    if(array_key_exists($input_name_check,$basic_field_name)) {
                        $langCode = $language['code'];
                        if($current_local == $langCode) {
                            $validation_rules[$input_name] = $basic_field_name[$input_name_check];
                        }else {
                            $validation_rules[$input_name] = str_replace("required","nullable",$basic_field_name[$input_name_check]);
                        }
                        $language_wise_data[$langCode][$input_name_check] = $input_value;
                    }
                    break;
                } 
            }
        }
        if($modal == null) {
            $validated = Validator::make($request->all(),$validation_rules)->validate();
        }else {
            $validator = Validator::make($request->all(),$validation_rules);
            if($validator->fails()) {
                return back()->withErrors($validator)->withInput()->with("modal",$modal);
            }
            $validated = $validator->validate();
        }

        return $language_wise_data;
    }

    /**
     * Method for validate request image if have
     * @param object $request
     * @param string $input_name
     * @param string $old_image
     * @return boolean|string $upload
     */
    public function imageValidate($request,$input_name,$old_image) {
        if($request->hasFile($input_name)) {
            $image_validated = Validator::make($request->only($input_name),[
                $input_name         => "image|mimes:png,jpg,webp,jpeg,svg",
            ])->validate();

            $image = get_files_from_fileholder($request,$input_name);
            $upload = upload_files_from_path_dynamic($image,'site-section',$old_image);
            return $upload;
        }

        return false;
    }
}
