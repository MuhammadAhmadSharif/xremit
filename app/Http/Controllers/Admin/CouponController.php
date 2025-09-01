<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\Admin\Coupon;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\CouponTransaction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CouponController extends Controller
{
    /**
     * Method for show all the coupon 
     */
    public function index(){
        $page_title             = "All Coupons";
        $data                   = Coupon::orderByDesc('id')->paginate(15);
        
        

        return  view('admin.sections.coupon.index',compact(
            'page_title',
            'data',
        ));
    }
    /**
     * Method for store Coupon 
     * @param string 
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request){
        $validator     = Validator::make($request->all(),[
            'name'     => 'required|string',
            'price'    => 'required',
            'max_used' => 'required',
        ]);

        if($validator->fails()) return back()->withErrors($validator)->withInput()->with("modal","add-coupon");

        $validated              = $validator->validate();
        $validated['slug']      = Str::slug($request->name);
        if(Coupon::where('name',$validated['name'])->exists()){
            throw ValidationException::withMessages([
                'name'   => 'Coupon already exists',
            ]);
        }
        $validated['name']          = strtoupper($validated['name']);
        $validated['last_edit_by']  = auth()->user()->id;
        try{
            Coupon::create($validated);
        }catch(Exception $e){
            return back()->with(['error'  => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Coupon Added Successfully']]);
    }
    /**
     * Method for update Coupon 
     * @param string
     * @param \Illuminate\Http\Request $request 
     */
    public function update(Request $request){

        $validator = Validator::make($request->all(),[
            'target'        => 'required|numeric|exists:coupons,id',
            'edit_name'     => 'required|string|max:80|',
            'edit_price'    => 'required',
            'edit_max_used' => 'required',
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with("modal","edit-coupon");
        }

        $validated = $validator->validate();
        
        $slug      = Str::slug($request->edit_name);
        $validated = replace_array_key($validated,"edit_");
        $validated = Arr::except($validated,['target']);
        $validated['slug']   = $slug;
        $validated['name']   = strtoupper($validated['name']);
        $coupon = Coupon::find($request->target);
        if(Coupon::whereNot('id',$coupon->id)->where('name',$validated['name'])->exists()){
            throw ValidationException::withMessages([
                'name'    => 'Coupon already exists',
            ]);
        }
        
        try{
            $coupon->update($validated);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return back()->with(['success' => ['Coupon updated successfully!']]);

    }
    /**
     * Method for delete coupon
     * @param string
     * @param \Illuminate\Http\Request $request
     */
    public function delete(Request $request){
        $request->validate([
            'target'    => 'required|numeric|',
        ]);
           $coupon = Coupon::find($request->target);
    
        try {
            $coupon->delete();
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Coupon Deleted Successfully!']]);
    }
    
}
