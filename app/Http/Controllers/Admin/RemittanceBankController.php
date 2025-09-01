<?php

namespace App\Http\Controllers\Admin;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\Admin\Currency;
use App\Http\Controllers\Controller;
use App\Models\Admin\RemittanceBank;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RemittanceBankController extends Controller
{
    /**
     * Method for show the remittance bank information
     * @param string
     * @return view
     */
    public function index(){
        $page_title             = "Bank Methods Manual";
        $remittance_banks       = RemittanceBank::orderByDesc('id')->paginate(50);
        $receiver_currency      = Currency::where('status',true)->where('receiver',true)->get();
        
        return view('admin.sections.remittance-bank.index',compact(
            'page_title',
            'remittance_banks',
            'receiver_currency'
        ));
    }
    /**
     * Method for store Remittance Bank 
     * @param string 
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request){
        $validator     = Validator::make($request->all(),[
            'name'     => 'required|string',
            'country'  => 'required|string',
        ]);

        if($validator->fails()) return back()->withErrors($validator)->withInput()->with("modal","add-remittance-bank");

        $validated     = $validator->validate();
        $validated['slug']   = Str::slug($request->name);
        if(RemittanceBank::where('name',$validated['name'])->where('country',$validated['country'])->exists()){
            throw ValidationException::withMessages([
                'name'   => 'Remittance Bank already exists',
            ]);
        }
        try{
            RemittanceBank::create($validated);
        }catch(Exception $e){
            return back()->with(['error'  => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Remittance Bank Added Successfully']]);
    }
    /**
     * Method for update Remittance bank 
     * @param string
     * @param \Illuminate\Http\Request $request 
     */
    public function update(Request $request){

        $validator = Validator::make($request->all(),[
            'target'        => 'required|numeric|exists:remittance_banks,id',
            'edit_name'     => 'required|string|max:80|',
            'edit_country'  => 'required|string',
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with("modal","edit-remittance-bank");
        }

        $validated = $validator->validate();
        
        $slug      = Str::slug($request->edit_name);
        $validated = replace_array_key($validated,"edit_");
        $validated = Arr::except($validated,['target']);
        $validated['slug']   = $slug;

        if(RemittanceBank::where('name',$validated['name'])->where('country',$validated['country'])->exists()){
            throw ValidationException::withMessages([
                'name'    => 'Remittance Bank already exists',
            ]);
        }
        $remittance_bank = RemittanceBank::find($request->target);
        
        try{
            $remittance_bank->update($validated);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return back()->with(['success' => ['Remittance Bank updated successfully!']]);

    }
    /**
     * Method for delete Remittance Bank
     * @param string
     * @param \Illuminate\Http\Request $request
     */
    public function delete(Request $request){
        $request->validate([
            'target'    => 'required|numeric|',
        ]);
           $remittance_bank = RemittanceBank::find($request->target);
    
        try {
            $remittance_bank->delete();
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Remittance Bank Deleted Successfully!']]);
    }
    /**
     * Method for status update for remittance bank
     * @param string
     * @param \Illuminate\Http\Request $request
     */
    public function statusUpdate(Request $request) {
        $validator = Validator::make($request->all(),[
            'data_target'       => 'required|numeric|exists:remittance_banks,id',
            'status'            => 'required|boolean',
        ]);

        if($validator->fails()) {
            $errors = ['error' => $validator->errors() ];
            return Response::error($errors);
        }

        $validated = $validator->validate();


        $remittance_banks = RemittanceBank::find($validated['data_target']);

        try{
            $remittance_banks->update([
                'status'        => ($validated['status']) ? false : true,
            ]);
        }catch(Exception $e) {
            $errors = ['error' => ['Something went wrong! Please try again.'] ];
            return Response::error($errors,null,500);
        }

        $success = ['success' => ['Remittance Bank status updated successfully!']];
        return Response::success($success);
    }
}
