<?php

namespace App\Models\Central\Setting;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'price', 'email', 'customer_service_email', 'phone'
    ];
}
