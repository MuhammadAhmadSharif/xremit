<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionSetting extends Model
{
    use HasFactory;


    protected $guarded = ['id'];
    
    protected $casts = [
        'admin_id'       => 'integer',
        'slug'           => 'string',
        'title'          => 'string',
        'fixed_charge'   => 'decimal:16',
        'percent_charge' => 'decimal:16',
        'min_limit'      => 'decimal:16',
        'max_limit'      => 'decimal:16',
        'monthly_limit'  => 'decimal:16',
        'daily_limit'    => 'decimal:16',
        'intervals'      => 'object',
        'status'         => 'integer',
        'feature_text'   => 'string',
        'created_at'          => 'date:Y-m-d',
        'updated_at'          => 'date:Y-m-d',
    ];

    protected $with = ['admin'];


    public function admin() {
        return $this->belongsTo(Admin::class);
    }
}
