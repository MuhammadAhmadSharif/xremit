<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Response;
use App\Models\Transaction;


class TransactionController extends Controller
{
    /**
     * Method for the transaction list
     */
    public function index(){
        $transaction   = Transaction::where('user_id',auth()->user()->id)->get()->map(function($data){
            
            if ($data->currency->gateway->isTatum($data->currency->gateway) && $data->status == global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT){
                $submit_url = route('api.user.send.remittance.payment.crypto.confirm',$data->trx_id); 
            }
            return [
                'trx_id'              => $data->trx_id,
                'remittance_data'     => $data->remittance_data,
                'request_amount'      => floatval($data->request_amount),
                'exchange_rate'       => floatval($data->exchange_rate),
                'payable'             => floatval($data->payable),
                'fees'                => floatval($data->fees),
                'convert_amount'      => floatval($data->convert_amount),
                'will_get_amount'     => floatval($data->will_get_amount),
                'remark'              => $data->remark,
                'details'             => $data->details,
                'requirements'        => $data->details->payment_info->requirements ?? [],
                'status'              => $data->status,
                'attribute'           => $data->attribute,
                'share_link'          => route('share.link',$data->trx_id),
                'submit_url'          => $submit_url ?? '',
                'download_link'       => route('download.pdf',$data->trx_id),
                'created_at'          => $data->created_at,
                'updated_at'          => $data->updated_at,
                
            ];
        });
        return Response::success(['Transaction Data Fetch Successfully.'],[
            'transaction'  => $transaction,
        ],200);
    }
    /**
     * Method for sow all transaction list
     */
    public function allTransction(){
        $transaction   = Transaction::get()->map(function($data){
            if ($data->currency->gateway->isTatum($data->currency->gateway) && $data->status == global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT){
                $submit_url = route('api.user.send.remittance.payment.crypto.confirm',$data->trx_id); 
            }
            return [
                'trx_id'              => $data->trx_id,
                'remittance_data'     => $data->remittance_data,
                'request_amount'      => floatval($data->request_amount),
                'exchange_rate'       => floatval($data->exchange_rate),
                'payable'             => floatval($data->payable),
                'fees'                => floatval($data->fees),
                'convert_amount'      => floatval($data->convert_amount),
                'will_get_amount'     => floatval($data->will_get_amount),
                'remark'              => $data->remark,
                'details'             => $data->details,
                'requirements'        => $data->details->payment_info->requirements ?? [],
                'status'              => $data->status,
                'attribute'           => $data->attribute,
                'share_link'          => route('share.link',$data->trx_id),
                'submit_url'          => $submit_url ?? '',
                'download_link'       => route('download.pdf',$data->trx_id),
                'created_at'          => $data->created_at,
                'updated_at'          => $data->updated_at,
            ];
        });
        return Response::success(['Transaction Data Fetch Successfully.'],[
            'transaction'  => $transaction,
        ],200);
    }
}
