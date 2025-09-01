<?php

namespace App\Http\Controllers\Admin;

use PDF;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\CouponTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Providers\Admin\BasicSettingsProvider;

class CouponTransactionController extends Controller
{
    /**
     * Method for view for the coupon log index page
     * @return view
     */
    public function index(){
        $page_title         = "Coupon Logs";
        $transactions       = CouponTransaction::with(['user_coupon','coupon','transaction'])
                                ->orderBy('id','desc')->get();
    
        return view('admin.sections.coupon-logs.index',compact(
            'page_title',
            'transactions'
        ));
    }
    /**
     * Method for coupon log details page
     * @param $trx_id
     * @return view
     */
    public function details($trx_id){
        $page_title     = "Coupon Log Details";
        $data   = CouponTransaction::with(['coupon','user_coupon','transaction'])->whereHas('transaction',function($q) use($trx_id){
            $q->where('trx_id',$trx_id);
        })->first();
        if(!$data) return back()->with(['error' => ['Sorry! Data not found.']]);
        
        return view('admin.sections.coupon-logs.details',compact(
            'page_title',
            'data'
        ));

    }
    /**
     * Method for share link page
     * @param string $trx_id
     * @param \Illuminate\Http\Request $request
     */
    public function shareLink(Request $request,$trx_id){
        $page_title         = "| Information";
        $data        = CouponTransaction::with(['coupon','user_coupon','transaction'])->whereHas('transaction',function($q) use ($trx_id){
            $q->where('trx_id',$trx_id);
        })->first();
        
        return view('share-link.coupon-log',compact(
            'page_title',
            'data',
        ));   
    }
    /**
     * Method for download pdf file
     * @param $trx_id
     */
    public function downloadPdf($trx_id){
        $transaction             = CouponTransaction::with(['coupon','user_coupon','transaction'])->whereHas('transaction',function($q) use ($trx_id){
            $q->where('trx_id',$trx_id);
        })->first(); 
        $data   = [
            'transaction'        => $transaction,
        ];
        
        $pdf = PDF::loadView('pdf-templates.coupon', $data);
        
        $basic_settings = BasicSettingsProvider::get();
        
        return $pdf->download($basic_settings->site_name.'-'.$transaction->transaction->trx_id.'.pdf');
    }
    /**
     * Method for download pdf file
     * @param $trx_id
     */
    public function downloadCouponPdf($trx_id){
        $transaction             = CouponTransaction::with(['coupon','user_coupon','transaction'])->whereHas('transaction',function($q) use ($trx_id){
            $q->where('trx_id',$trx_id);
        })->first(); 
        $data   = [
            'transaction'        => $transaction,
        ];
        
        $pdf = PDF::loadView('pdf-templates.coupon', $data);
        
        $basic_settings = BasicSettingsProvider::get();
        
        return $pdf->download($basic_settings->site_name.'-'.$transaction->transaction->trx_id.'.pdf');
    }
    /**
     * Method for remittance log search 
     */
   
     public function search(Request $request) {
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);
        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        $data = $validated['text'];
        $transactions    =  CouponTransaction::with(['coupon','user_coupon','transaction'])->whereHas('transaction',function($q) use ($data){
            $q->where("trx_id",'LIKE','%'.$data.'%')->orderBy('id','desc');
        })->get(); 
       
        return view('admin.components.search.coupon-search',compact('transactions'));
        
    }
}
