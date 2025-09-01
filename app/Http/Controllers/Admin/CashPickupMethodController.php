<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\Admin\Currency;
use App\Models\Admin\CashPickup;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CashPickupMethodController extends Controller
{
    /**
     * Method for view cash pickup index page
     * @return view
     */
    public function index(){
        $page_title         = "Cash Pickups";
        $cash_pickups       = CashPickup::orderBy('id','desc')->paginate(50);
        $receiver_currency  = Currency::where('status',true)->where('receiver',true)->get();

        return view('admin.sections.cash-pickup.index',compact(
            'page_title',
            'cash_pickups',
            'receiver_currency'
        ));
    }
    /**
     * Method for store cash pickups information
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request){
        $validator      = Validator::make($request->all(),[
            'country'   => 'required',
            'address'   => 'required'
        ]);
        if($validator->fails()) return back()->withErrors($validator)->withInput()->with("modal","add-cash-pickup");
        $validated          = $validator->validate();
        $validated['slug']  = Str::slug($validated['address']);
        if(CashPickup::where('country',$validated['country'])->where('address',$validated['address'])->exists()){
            throw ValidationException::withMessages([
                'address'   => 'Pickup Point already exists'
            ]);
        }

        try{
            CashPickup::create($validated);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Pickup point added successfully.']]);
    }
    /**
     * Method for update cash pickup method
     * @param string
     * @param \Illuminate\Http\Request $request
     */
    public function update(Request $request){
        $validator = Validator::make($request->all(),[
            'target'        => 'required|numeric|exists:cash_pickups,id',
            'edit_address'  => 'required|string|',
            'edit_country'  => 'required|string',
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with("modal","edit-cash-pickup");
        }
        $validated           = $validator->validate();
        
        $slug                = Str::slug($request->edit_name);
        $validated           = replace_array_key($validated,"edit_");
        $validated           = Arr::except($validated,['target']);
        $validated['slug']   = $slug;
        $cash_pickup         = CashPickup::find($request->target);
        if(CashPickup::whereNot('id',$cash_pickup->id)->where('country',$validated['country'])->where('address',$validated['address'])->exists()){
            throw ValidationException::withMessages([
                'address'    => 'Pickup Point already exists'
            ]);
        }
        try{
            $cash_pickup->update($validated);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Pickup Point updated successfully.']]);
    }
    /**
     * Method for delete Cash Pickup
     * @param string
     * @param \Illuminate\Http\Request $request
     */
    public function delete(Request $request){
        $request->validate([
            'target'    => 'required|numeric|',
        ]);
        $cash_pickup = CashPickup::find($request->target);
    
        try {
            $cash_pickup->delete();
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Cash Pickup Deleted Successfully!']]);
    }
    /**
     * Method for status update for Cash pickup
     * @param string
     * @param \Illuminate\Http\Request $request
     */
    public function statusUpdate(Request $request) {
        $validator = Validator::make($request->all(),[
            'data_target'       => 'required|numeric|exists:cash_pickups,id',
            'status'            => 'required|boolean',
        ]);

        if($validator->fails()) {
            $errors = ['error' => $validator->errors() ];
            return Response::error($errors);
        }

        $validated = $validator->validate();


        $cash_pickup = CashPickup::find($validated['data_target']);
                                     
        try{
            $cash_pickup->update([
                'status'        => ($validated['status']) ? false : true,
            ]);
        }catch(Exception $e) {
            $errors = ['error' => ['Something went wrong! Please try again.'] ];
            return Response::error($errors,null,500);
        }

        $success = ['success' => ['Cash pickup status updated successfully!']];
        return Response::success($success);
    }
}
