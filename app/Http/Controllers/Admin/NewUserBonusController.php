<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\Admin\NewUserBonus;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NewUserBonusController extends Controller
{
    /**
     * Method for view new user bonus page
     * @return view
     */
    public function index(){
        $page_title     = "New User Bonus";
        $bonus          = NewUserBonus::first();

        return view('admin.sections.new-user-bonus.index',compact(
            'page_title',
            'bonus'
        ));

    }
    /**
     * Method for update new user bonus information
     */
    public function update(Request $request){
        $validator      = Validator::make($request->all(),[
            'price'     => 'required',
            'max_used'  => 'required'
        ]);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput($request->all());
        }
        $validated  = $validator->validate();
        $bonus      = NewUserBonus::where('slug',$request->slug)->first();
        if(!$bonus){
            $validated['slug']          = $request->slug;
            $validated['last_edit_by']  = auth()->user()->id;
            try{
                NewUserBonus::create($validated);
            }catch(Exception $e){
                return back()->with(['error' => ['Something went wrong! Please try again.']]);
            }
            return back()->with(['success' => ['New user bonus information updated successfully.']]);
        }else{
            $validated['last_edit_by']  = auth()->user()->id;
            try{
                $bonus->update($validated);
            }catch(Exception $e){
                return back()->with(['error' => ['Something went wrong! Please try again.']]);
            }
            return back()->with(['success' => ['New user bonus information updated successfully.']]);
        }
    }
    /**
     * Method for update new user bonus status
     * @param string
     * @param Illuminate\Http\Request $request
     */
    public function statusUpdate(Request $request){
        if($request->data_target == null){
            $errors = ['error' => ['Please setup new user bonus first.'] ];
            return Response::error($errors,null,500);
        }else{
            $validator          = Validator::make($request->all(),[
                'data_target'   => 'required|numeric|exists:new_user_bonuses,id',
                'status'        => 'required|boolean'
            ]);
            if($validator->fails()){
                $errors     = ['error' => $validator->errors()];
                return Response::error($errors);
            }
            $validated      = $validator->validate();
            $bonus          = NewUserBonus::find($validated['data_target']);
            
            try{
                $bonus->update([
                    'status'    => ($validated['status']) ? false : true,
                ]);
            }catch(Exception $e){
                $errors = ['error' => ['Something went wrong! Please try again.'] ];
                return Response::error($errors,null,500);
            }
            $success = ['success' => ['New user bonus status updated successfully.']];
            return Response::success($success);
        }
        
    }
}
