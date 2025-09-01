<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'id'           => 'integer',
        'category_id'  => 'integer',
        'slug'         => 'string',
        'data'         => 'object',
        'status'       => 'integer',
        'created_at'   => 'date:Y-m-d',
        'updated_at'   => 'date:Y-m-d',
    ];
    
    public function category(){
        return $this->belongsTo(WebJournalCategory::class,'category_id');
    }
}
