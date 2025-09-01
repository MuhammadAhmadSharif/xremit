<?php

namespace App\Models;


use App\Constants\PaymentGatewayConst;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\PaymentGatewayCurrency;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['confirm', 'dynamic_inputs', 'confirm_url'];

    protected $casts = [
        'id'                          => 'integer',
        'admin_id'                    => 'integer',
        'user_id'                     => 'integer',
        'payment_gateway_currency_id' => 'integer',
        'type'                        => "string",
        'remittance_data'             => 'object',
        'trx_id'                      => 'string',
        'request_amount'              => 'decimal:16',
        'exchange_rate'               => 'decimal:16',
        'payable'                     => 'decimal:16',
        'fees'                        => 'decimal:16',
        'convert_amount'              => 'decimal:16',
        'will_get_amount'             => 'decimal:16',
        'remark'                      => 'string',
        'details'                     => 'object',
        'reject_reason'               => 'string',
        'status'                      => 'integer',
        'attribute'                   => 'string',
        'created_at'                  => 'date:Y-m-d',
        'updated_at'                  => 'date:Y-m-d',
    ];

    public function scopeAuth($q){
        return $q->where('user_id',auth()->user()->id);
    }

    public function coupon_transaction(){
        return $this->hasMany(CouponTransaction::class);
    }


    public function getConfirmAttribute()
    {
        if($this->currency == null) return false;
        if($this->currency->gateway->isTatum($this->currency->gateway) && $this->status == PaymentGatewayConst::STATUSWAITING) return true;
    }

    public function getDynamicInputsAttribute()
    {
        if($this->confirm == false) return [];
        $input_fields = $this->details->payment_info->requirements;
        return $input_fields;
    }

    public function getConfirmUrlAttribute()
    {
        if($this->confirm == false) return false;
        return setRoute('api.user.send.remittance.payment.crypto.confirm', $this->trx_id);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(PaymentGatewayCurrency::class,'payment_gateway_currency_id');
    }

    //for search transaction log
    public function scopeSearch($query,$data) {
        return $query->where("trx_id",'LIKE','%'.$data.'%')
                     ->orderBy('id','desc');
    }
    
}
