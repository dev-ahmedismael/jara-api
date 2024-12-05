<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class PurchasedApp extends Model
{
    protected $fillable = ['slug', 'title', 'description', 'data', 'price'];

    protected $casts = [
        'data' => 'array'
    ];
}
