<?php

namespace App\Models\Tenant\SiteBuilder;

use Illuminate\Database\Eloquent\Model;

class PolicyPage extends Model
{
    protected $fillable = ['status', 'title', 'content'];
}
