<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankMethodAutomatic extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    protected $casts    = [
        'admin_id'      => 'integer',
        'slug'          => 'string',
        'name'          => 'string',
        'image'         => 'string',
        'config'        => 'object',
        'details'       => 'string',
        'status'        => 'integer',
    ];
}
