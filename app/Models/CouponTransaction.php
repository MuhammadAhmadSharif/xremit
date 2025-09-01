<?php

namespace App\Models;

use App\Models\Admin\Coupon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponTransaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts        = [
        'id'                => 'integer',
        'user_id'           => 'integer',
        'coupon_id'         => 'integer',
        'user_coupons'      => 'integer',
        'transaction_id'    => 'integer'
    ];

    //auth relation
    public function scopeAuth($q){
        return $q->where('user_id',auth()->user()->id);
    }

    //user coupon
    public function user_coupon(){
        return $this->belongsTo(UserCoupon::class,'user_coupon_id');
    }
    //coupon
    public function coupon(){
        return $this->belongsTo(Coupon::class,'coupon_id');
    }
    //transaction
    public function transaction(){
        return $this->belongsTo(Transaction::class,'transaction_id');
    }

    //for search transaction log
    public function scopeSearch($query,$data) {
        return $query->where("trx_id",'LIKE','%'.$data.'%')
                     ->orderBy('id','desc');
    }
}
