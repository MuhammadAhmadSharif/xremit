<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\Admin\TransactionSetting;
use Illuminate\Support\Facades\Validator;

class ReceivingMethodCategoryController extends Controller
{
    /**
     * Method for view the receiving method index page
     * @return view
     */
    public function index(){
        $page_title     = "Receiving Method Category";
        $categories     = TransactionSetting::orderBy('id','desc')->get();

        return view('admin.sections.receiving-method-category.index',compact(
            'page_title',
            'categories'
        ));
    }
    /**
     * Method for view the edit receiving method category page
     * @param $slug
     * @return view
     */
    public function edit($slug){
        $page_title = "Receiving Method Category Edit";
        $category   = TransactionSetting::where('slug',$slug)->first();
        if(!$category) return back()->with(['error' => ['Data not found!']]);

        return view('admin.sections.receiving-method-category.edit',compact(
            'page_title',
            'category'
        ));

    }
    /**
     * Method for update receiving method category name
     * @param $slug
     * @param Illuminate\Http\request
     */
    public function update(Request $request,$slug){
        $validator  = Validator::make($request->all(),[
            'title' => 'required|string'
        ]);
        if($validator->fails()) return back()->withErrors($validator)->withInput($request->all());
        $category   = TransactionSetting::where('slug',$slug)->first();
        if(!$category) return back()->with(['error' => ['Data not found!']]);
        $validated  = $validator->validate();
        $validated['slug']  = Str::slug($validated['title']);
        try{
            $category->update([
                'slug'  => $validated['slug'],
                'title' => $validated['title'],
            ]);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return redirect()->route('admin.receiving.method.category.index')->with(['success' => ['Receiving method category updated successfully.']]);
    }
    /**
     * Method for status update for receiving method category
     * @param string
     * @param Illuminate\Http\Request
     */
    public function statusUpdate(Request $request){
        $validator          = Validator::make($request->all(),[
            'data_target'   => 'required|numeric|exists:transaction_settings,id',
            'status'        => 'required|boolean'
        ]);
        if($validator->fails()){
            $errors     = ['error' => $validator->errors()];
            return Response::error($errors);
        }
        $validated      = $validator->validate();

        $category       = TransactionSetting::find($validated['data_target']);
        try{
            $category->update([
                'status'    => ($validated['status']) ? false : true,
            ]);
        }catch(Exception $e){
            $errors     = ['error'   => ['Something went wrong! Please try again.']];
            return Response::error($errors,null,500);
        }
        $success  = ['success' => ['Category status updated successfully.']];
        return Response::success($success);
    }
}
