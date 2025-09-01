<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\Admin\SendingPurpose;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SendingPurposeController extends Controller
{
    /**
     * Method for view sending purpose page
     * @param string
     * @return view
     */
    public function index(){
        $page_title      = "Sending Purpose";
        $sending_purpose = SendingPurpose::orderByDesc('id')->paginate(10);

        return view('admin.sections.sending-purpose.index',compact(
            'page_title',
            'sending_purpose',
        ));
    }
    /**
     * Method for store sending purpose information
     * @param string
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request){
        $validator     = Validator::make($request->all(),[
            'name'     => 'required|string',
        ]);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput()->with('modal','add-sending-purpose');
        }
        $validated      = $validator->validate();
        $validated['slug']           = Str::slug($request->name);

        if(SendingPurpose::where('name',$validated['name'])->exists()){
            throw ValidationException::withMessages([
                'name'  => 'Sending Purpose already exists',
            ]);
        }
        try{
            SendingPurpose::create($validated);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success'   => ['Sending Purpose Added Successfully.']]);
    }
    /**
     * Method for update sending purpose information
     * @param string $slug
     * @param \Illuminate\Http\Request $request
     */
    public function update(Request $request){
        $validator   = Validator::make($request->all(),[
            'target'    => 'required|numeric|exists:sending_purposes,id',
            'edit_name' => 'required|string',
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput()->with('modal','edit-sending-purpose');
        }
        $validated   = $validator->validate();
        $slug      = Str::slug($request->edit_name);
        $validated = replace_array_key($validated,"edit_");
        $validated = Arr::except($validated,['target']);
        $validated['slug']  = $slug ;

        if(SendingPurpose::where('name',$validated['name'])->exists()){
            throw ValidationException::withMessages([
                'name'   => 'Sending purpose already exists',
            ]);
        }

        $sending_purpose  = SendingPurpose::find($request->target);
        try{
            $sending_purpose->update($validated);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success'   => ['Sending purpose updated successfully.']]);
    }
    /**
     * Method for update status 
     * @param string 
     * @param \Illuminate\Http\Request $request
     */
    public function statusUpdate(Request $request){
        $validator    = Validator::make($request->all(),[
            'data_target'  => 'required|numeric|exists:sending_purposes,id',
            'status'       => 'required|boolean',
        ]);

        if($validator->fails()){
            return Response::error(['error' => $validator->errors()]);
        }
        $validated        = $validator->validate();
        $sending_purpose  = SendingPurpose::find($validated['data_target']);

        try{
            $sending_purpose->update([
                'status'    => ($validated['status']) ? false : true, 
            ]);
        }catch(Exception $e){
            return Response::error(['error' => ['Something went wrong! Please try again.']],null,500);
        }
        return Response::success(['success' => ['Sending purpose status updated successfully.']]);
    }
    /**
     * Method for delete sending purpose information
     * @param string 
     * @param \Illuminate\Http\Request $request
     */
    public function delete(Request $request){
        $validator   = Validator::make($request->all(),[
            'target' => 'required|numeric',
        ]);

        $sending_purpose = SendingPurpose::find($request->target);

        try{
            $sending_purpose->delete();
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Sending purpose deleted successfully.']]);
    }
}
