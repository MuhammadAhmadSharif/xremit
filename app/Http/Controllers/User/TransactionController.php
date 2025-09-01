<?php

namespace App\Http\Controllers\User;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\Admin\Currency;
use App\Models\UserNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Method for show transaction page
     * @return view
     */
    public function transaction(){
        $page_title           = "| Transactions";
        $transactions         = Transaction::with(['currency','coupon_transaction'])
                                ->auth()
                                ->orderByDESC('id')->get();
        
        $client_ip            = request()->ip() ?? false;
        $user_country         = geoip()->getLocation($client_ip)['country'] ?? "";
        $user                 = auth()->user();
        $notifications        = UserNotification::where('user_id',$user->id)->latest()->take(10)->get();

        return view('user.sections.transaction.index',compact(
            'page_title',
            'transactions',
            'user_country',
            'user',
            'notifications'
        ));
    }
    /**
     * Method for search transaction data using AJAX
     * @param Illuminate\Http\Request $request
     */
    public function search(Request $request){
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);
        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        
        $transactions    = Transaction::auth()->with(['currency','coupon_transaction'])
                                    ->search($validated['text'])->get();
                                  
        return view('user.components.search-log.index',compact('transactions'));

        
    }
}
