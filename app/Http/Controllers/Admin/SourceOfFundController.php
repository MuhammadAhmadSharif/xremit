<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\Admin\SourceOfFund;
use Illuminate\Support\Facades\Validator;

class SourceOfFundController extends Controller
{
    /**
     * Method for show the remittance bank information
     * @param string
     * @return view
     */
    public function index(){
        $page_title     = "Source Of Fund";
        $source_funds   = SourceOfFund::orderByDesc('id')->paginate(10);

        return view('admin.sections.source-fund.index',compact(
            'page_title',
            'source_funds',
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
        ]);

        if($validator->fails()) return back()->withErrors($validator)->withInput()->with("modal","add-source-fund");

        $validated     = $validator->validate();

        $validated['slug']   = Str::slug($request->name);
        try{
            SourceOfFund::create($validated);
        }catch(Exception $e){
            return back()->with(['error'  => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Source of Fund Added Successfully']]);
    }
    /**
     * Method for update Remittance bank 
     * @param string
     * @param \Illuminate\Http\Request $request 
     */
    public function update(Request $request){

        $validator = Validator::make($request->all(),[
            'target'        => 'required|numeric|exists:source_of_funds,id',
            'edit_name'     => 'required|string|max:80|',
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with("modal","edit-source-fund");
        }

        $validated = $validator->validate();
        
        $slug      = Str::slug($request->edit_name);
        $validated = replace_array_key($validated,"edit_");
        $validated = Arr::except($validated,['target']);
        $validated['slug']   = $slug;

        $source_of_funds = SourceOfFund::find($request->target);
        
        try{
            $source_of_funds->update($validated);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return back()->with(['success' => ['Source of Fund updated successfully!']]);

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
        $source_of_funds = SourceOfFund::find($request->target);
    
        try {
            $source_of_funds->delete();
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Source of fund Deleted Successfully!']]);
    }
    /**
     * Method for status update for remittance bank
     * @param string
     * @param \Illuminate\Http\Request $request
     */
    public function statusUpdate(Request $request) {
        $validator = Validator::make($request->all(),[
            'data_target'       => 'required|numeric|exists:source_of_funds,id',
            'status'            => 'required|boolean',
        ]);

        if($validator->fails()) {
            $errors = ['error' => $validator->errors() ];
            return Response::error($errors);
        }

        $validated = $validator->validate();


        $source_of_funds = SourceOfFund::find($validated['data_target']);

        try{
            $source_of_funds->update([
                'status'        => ($validated['status']) ? false : true,
            ]);
        }catch(Exception $e) {
            $errors = ['error' => ['Something went wrong! Please try again.'] ];
            return Response::error($errors,null,500);
        }

        $success = ['success' => ['Source of fund status updated successfully!']];
        return Response::success($success);
    }
}
