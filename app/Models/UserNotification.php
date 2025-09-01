<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'user_id' => 'integer',
        'message' => 'object',
    ];
}
