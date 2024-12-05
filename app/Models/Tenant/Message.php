<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'subject',
        'message',
        'search',
        'sort',
        'filter',
    ];
}
