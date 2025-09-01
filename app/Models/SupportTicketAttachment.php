<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicketAttachment extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'support_ticket_id'     => 'integer',
        'attachment'            => 'string',
        'attachment_info'       => 'object',
        'created_at'      => 'date:Y-m-d',
        'updated_at'      => 'date:Y-m-d', 
    ];
}
