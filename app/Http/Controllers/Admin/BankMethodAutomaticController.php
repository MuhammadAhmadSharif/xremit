<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\Admin\VirtualCardApi;
use App\Models\Admin\BankMethodAutomatic;
use Illuminate\Support\Facades\Validator;

class BankMethodAutomaticController extends Controller
{
    /**
     * Method for view bank method automatic page
     * @return view
     */
    public function index()
    {
        $page_title                 = "Bank Methods Automatic";
        $bank_method_automatic      = BankMethodAutomatic::get();

        return view('admin.sections.bank-method.automatic.index',compact(
            'page_title',
            'bank_method_automatic',
        ));
    }
    /**
     * Method for view bank method automatic edit page
     * @param $slug
     * @param Illuminate\Http\Request $request 
     */
    public function edit($slug)
    {
        $page_title                 = "Bank Methods Automatic Edit";
        $bank_method_automatic      = BankMethodAutomatic::where('slug',$slug)->first();
        if(!$bank_method_automatic) return back()->with(['error' => ['Data not found']]);

        return view('admin.sections.bank-method.automatic.edit',compact(
            'page_title',
            'bank_method_automatic',
        ));
    }
    public function update(Request $request,$slug){
        $validator = Validator::make($request->all(), [
            'api_method'                => 'required|in:flutterwave',
            'flutterwave_secret_key'    => 'required_if:api_method,flutterwave',
            'flutterwave_secret_hash'   => 'required_if:api_method,flutterwave',
            'flutterwave_url'           => 'required_if:api_method,flutterwave',
            
        ]);
        if($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $request->merge(['name'=>$request->api_method]);
        $data = array_filter($request->except('_token','api_method','_method','details','image'));
        $bank_method_automatic = BankMethodAutomatic::where('slug',$slug)->first();

        $bank_method_automatic->details = $request->details;
        $bank_method_automatic->config = $data;

        if ($request->hasFile("image")) {
            try {
                $image = get_files_from_fileholder($request, "image");
                $upload_file = upload_files_from_path_dynamic($image, "card-api");
                $bank_method_automatic->image = $upload_file;
            } catch (Exception $e) {
                return back()->with(['error' => ['Ops! Failed To Upload Image.']]);
            }
        }
        $bank_method_automatic->save();

        return back()->with(['success' => ['Bank Method Updated Successfully']]);
    }
    /**
     * Method for status update for bank method automatic
     * @param string
     * @param \Illuminate\Http\Request $request
     */
    public function statusUpdate(Request $request) {
        $validator = Validator::make($request->all(),[
            'data_target'       => 'required|numeric|exists:bank_method_automatics,id',
            'status'            => 'required|boolean',
        ]);

        if($validator->fails()) {
            $errors = ['error' => $validator->errors() ];
            return Response::error($errors);
        }

        $validated = $validator->validate();


        $bank_method_automatic = BankMethodAutomatic::find($validated['data_target']);

        try{
            $bank_method_automatic->update([
                'status'        => ($validated['status']) ? false : true,
            ]);
        }catch(Exception $e) {
            $errors = ['error' => ['Something went wrong! Please try again.'] ];
            return Response::error($errors,null,500);
        }

        $success = ['success' => ['Bank method automatic status updated successfully!']];
        return Response::success($success);
    }
}
