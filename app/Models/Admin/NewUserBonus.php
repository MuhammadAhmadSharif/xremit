<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewUserBonus extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts    = [
        'id'            => 'integer',
        'slug'          => 'string',
        'price'         => 'decimal:8',
        'max_used'      => 'integer',
        'last_edit_by'  => 'integer',
        'status'        => 'integer',
    ];

}
