<?php

namespace App\Models\Tenant\WebsiteSettings;

use Illuminate\Database\Eloquent\Model;

class MaintenanceMode extends Model
{
    protected $fillable = [
        'is_enabled',
        'message'
    ];
}
