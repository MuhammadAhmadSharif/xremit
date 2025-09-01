<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];

    protected $casts    = [
        'id'            => 'integer',
        'slug'          => 'string',
        'name'          => 'string',
        'price'         => 'decimal:8',
        'last_edit_by'  => 'integer',
        'status'        => 'integer',
    ];
}
