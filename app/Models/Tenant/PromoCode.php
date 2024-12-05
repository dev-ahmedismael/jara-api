<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $fillable = [
        'code', 'discount', 'discount_type', 'start_date', 'end_date'
    ];
}
