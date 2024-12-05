<?php

namespace App\Models\Jara;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'job',
        'phone',
        'email',
        'tenant_id',
        'type'
    ];
}
