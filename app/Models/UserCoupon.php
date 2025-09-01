<?php

namespace App\Models;

use App\Models\Admin\NewUserBonus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserCoupon extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];

    protected $casts        = [
        'id'                => 'integer',
        'user_id'           => 'integer',
        'new_user_bonus_id' => 'integer',
        'coupon_name'       => 'string',
        'price'             => 'decimal:8',
    ];
    
    //auth
    public function scopeAuth($q){
        return $q->where('user_id',auth()->user()->id);
    }

    //new user bonus
    public function new_user_bonus(){
        return $this->belongsTo(NewUserBonus::class,'new_user_bonus_id');
    }
    //new coupon transactions
    public function coupon_transactions(){
        return $this->hasMany(CouponTransaction::class);
    }

}
