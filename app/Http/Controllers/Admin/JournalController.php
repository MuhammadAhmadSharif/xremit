<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Admin\Journal;
use App\Http\Helpers\Response;
use App\Models\Admin\Language;
use App\Constants\LanguageConst;
use App\Models\Admin\WebJournalCategory;
use Illuminate\Support\Facades\Validator;

class JournalController extends Controller
{
    public function journalCreate(){
        $page_title = "New Journal Create";
        $category   = WebJournalCategory::where('status',true)->get();
        $languages  = Language::get();

        return view('admin.sections.setup-sections.web-journal.create',compact(
            'page_title',
            'category',
            'languages'

        ));
    }
    public function journalStore(Request $request) {

        $basic_field_name = [

            'title'         => "required|string|max:255",
            'description'   => "required|string|max:5000000",
            'tags'          => "required|array",
        ];

        $validator = Validator::make($request->all(),[
            'image'         => "nullable",
            'category'      => 'required',
        ]);

        $validated                              = $validator->validate();

        $data['language']  = $this->contentValidate($request,$basic_field_name);

        // make slug
        $not_removable_lang = LanguageConst::NOT_REMOVABLE;
        $slug_text = $data['language'][$not_removable_lang]['title'] ?? "";
        if($slug_text == "") {
            $slug_text = $data['language'][get_default_language_code()]['title'] ?? "";
            if($slug_text == "") {
                $slug_text = Str::uuid();
            }
        }
        $slug = Str::slug(Str::lower($slug_text));

        if(Journal::where('slug',$slug)->exists()) return back()->with(['error' => ['Journal title is similar. Please update/change this title']]);

        $data['image'] = null;
        if($request->hasFile("image")) {
            $data['image']  = $this->imageValidate($request,"image",null);
        }

        try{
            $update_value = [
                'slug' => $slug,
                'category_id' => $validated['category'],
                'data'=> $data

            ];
            Journal::updateOrCreate($update_value);
        }catch(Exception $e) {

            return back()->with(['error' => ['Something went wrong. Please try again']]);
        }


        return redirect()->route('admin.setup.sections.section','journal')->with(['success' => ['Journal created successfully!']]);
    }

    public function journalStatusUpdate(Request $request){
        $validator = Validator::make($request->all(), [
            'status'                    => 'required|boolean',
            'data_target'               => 'required|string',
        ]);

        if ($validator->stopOnFirstFailure()->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error, null, 400);
        }
        $validated = $validator->validate();


        try {
            $journal = Journal::find($validated['data_target']);
            if($journal) {
                $journal->update([
                    'status'    => ($validated['status'] == true) ? false : true,
                ]);
            }
        } catch (Exception $e) {
            $error = ['error' => ['Something went wrong!. Please try again.']];
            return Response::error($error, null, 500);
        }

        $success = ['success' => ['Journal status updated successfully!']];
        return Response::success($success, null, 200);
    }
    public function journalEdit($id){
        $journals     = Journal::find($id);
        if(!$journals) return back()->with(['error' => ['Journal Does not exists']]);
        $page_title   = "Journal Edit Page";
        $category   = WebJournalCategory::where('status',true)->get();
        $languages    = Language::get();


        return view('admin.sections.setup-sections.web-journal.edit',compact(
            'journals',
            'page_title',
            'category',
            'languages'
        ));
    }
    public function journalUpdate(Request $request,$id){
        $journal            = Journal::find($id);
        $basic_field_name   = [
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'tags'          => 'required|array'
        ];
        $validator = Validator::make($request->all(),[
            'category'      => 'required',
        ]);

        $validated  = $validator->validate();
        $data['language']   = $this->contentValidate($request,$basic_field_name);

        $request->merge(['old_image' => $journal->data->image ?? null]);

        if($request->hasFile("image")){
            $data['image']  = $this->imageValidate($request,"image",$journal->data->image ?? null);
        }else {
            $data['image'] = $journal->data->image ?? null;
        }
        try{
            $update_value = [

                'category_id' => $validated['category'],
                'data'=> $data

            ];
            $journal->update($update_value);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }
        return redirect()->route('admin.setup.sections.section','journal')->with(['success' => ['Journal Data Updated Successfully.']]);
    }



    public function journalDelete(Request $request){

        $request->validate([
            'target'    => "required|string"
        ]);

        try{
            $journal = Journal::find($request->target);
            if($journal) {
                $image_name = $journal->data?->image ?? null;
                if($image_name) {
                    $image_link = get_files_path('site-section') . "/" . $image_name;
                    delete_file($image_link);
                }
                $journal->delete();
            }
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong. Please try again']]);
        }
        return back()->with(['success' => ['Journal deleted successfully!']]);
    }



    /**
     * Method for validate request data and re-decorate language wise data
     * @param object $request
     * @param array $basic_field_name
     * @return array $language_wise_data
     */
    public function contentValidate($request,$basic_field_name,$modal = null) {
        $languages = Language::get();

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
    public function imageValidate($request,$input_name,$old_image = null) {
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
