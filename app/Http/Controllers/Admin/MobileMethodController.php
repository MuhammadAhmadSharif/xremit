<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\Admin\Currency;
use App\Models\Admin\MobileMethod;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MobileMethodController extends Controller
{
    /**
     * Method for show mobile method information
     * @param string
     * @return view
     */
    public function index(){
        $page_title             = "Mobile Method";
        $mobile_methods         = MobileMethod::orderByDesc('id')->paginate(50);
        $receiver_currency      = Currency::where('status',true)->where('receiver',true)->get();
        
        return view('admin.sections.mobile-method.index',compact(
            'page_title',
            'mobile_methods',
            'receiver_currency'
        ));
    }
    /**
     * Method for store mobile method
     * @param string
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request){
        $validator     = Validator::make($request->all(),[
            'name'     => 'required|string',
            'country'  => 'required|string',
        ]);

        if($validator->fails()) return back()->withErrors($validator)->withInput()->with("modal","add-mobile-method");

        $validated     = $validator->validate();

        $validated['slug']   = Str::slug($request->name);

        if(MobileMethod::where('name',$validated['name'])->where('country',$validated['country'])->exists()){
            throw ValidationException::withMessages([
                'name'   => 'Mobile Method already exists',
            ]);
        }

        try{
            MobileMethod::create($validated);
        }catch(Exception $e){
            return back()->with(['error'  => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Mobile Method Added Successfully']]);
    }
    /**
     * Method for update Mobile Method
     * @param string
     * @param \Illuminate\Http\Request $request 
     */
    public function update(Request $request){

        $validator = Validator::make($request->all(),[
            'target'        => 'required|numeric|exists:mobile_methods,id',
            'edit_name'     => 'required|string|max:80|',
            'edit_country'  => 'required|string',
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with("modal","edit-mobile-method");
        }

        $validated = $validator->validate();
        
        $slug      = Str::slug($request->edit_name);
        $validated = replace_array_key($validated,"edit_");
        $validated = Arr::except($validated,['target']);
        $validated['slug']   = $slug;

        if(MobileMethod::where('name',$validated['name'])->where('country',$validated['country'])->exists()){
            throw ValidationException::withMessages([
                'name'     => 'Mobile Method already exists',
            ]);
        }
        
        $mobile_methods = MobileMethod::find($request->target);
        
        try{
            $mobile_methods->update($validated);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return back()->with(['success' => ['Mobile Method updated successfully!']]);

    }
    /**
     * Method for delete Mobile Method 
     * @param string
     * @param \Illuminate\Http\Request $request
     */
    public function delete(Request $request){
        $request->validate([
            'target'    => 'required|numeric|',
        ]);
           $mobile_methods = MobileMethod::find($request->target);
    
        try {
            $mobile_methods->delete();
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Mobile Method Deleted Successfully!']]);
    }
    /**
     * Method for status update for Mobile method
     * @param string
     * @param \Illuminate\Http\Request $request
     */
    public function statusUpdate(Request $request) {
        $validator = Validator::make($request->all(),[
            'data_target'       => 'required|numeric|exists:mobile_methods,id',
            'status'            => 'required|boolean',
        ]);

        if($validator->fails()) {
            $errors = ['error' => $validator->errors() ];
            return Response::error($errors);
        }

        $validated = $validator->validate();


        $mobile_methods = MobileMethod::find($validated['data_target']);

        try{
            $mobile_methods->update([
                'status'        => ($validated['status']) ? false : true,
            ]);
        }catch(Exception $e) {
            $errors = ['error' => ['Something went wrong! Please try again.'] ];
            return Response::error($errors,null,500);
        }

        $success = ['success' => ['Mobile Method status updated successfully!']];
        return Response::success($success);
    }
}
