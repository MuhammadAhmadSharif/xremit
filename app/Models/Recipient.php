<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts    = [
        'id'            => 'integer',
        'user_id'       => 'integer',
        'first_name'    => 'string',
        'middle_name'   => 'string',
        'last_name'     => 'string',
        'email'         => 'string',
        'country'       => 'string',
        'city'          => 'string',
        'state'         => 'string',
        'zip_code'      => 'string',
        'phone'         => 'string',
        'method'        => 'string',
        'mobile_name'   => 'string',
        'account_number'=> 'string',
        'bank_name'     => 'string',
        'iban_number'   => 'string',
        'address'       => 'string',
        'document_type' => 'string',
        'front_image'   => 'string',
        'back_image'    => 'string',
        'created_at'    => 'date:Y-m-d',
        'updated_at'    => 'date:Y-m-d',
    ];

    public function scopeAuth($query){
        $query->where('user_id',auth()->user()->id); 
    }
}
