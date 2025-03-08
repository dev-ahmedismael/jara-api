<?php

namespace App\Models\Central\Promocode;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{
    use Filterable;
    protected $fillable = [
        'code',
        'discount_type',
        'discount_amount',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

}
