<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountrySection extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'id'        => 'integer',
        'key'       => 'string',
        'value'     => 'object',
        'status'    => 'integer'
    ];
}
