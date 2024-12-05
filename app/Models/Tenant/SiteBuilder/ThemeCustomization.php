<?php

namespace App\Models\Tenant\SiteBuilder;

use Illuminate\Database\Eloquent\Model;

class ThemeCustomization extends Model
{
    protected $fillable = [
        'theme_id',
        'theme_name',
        'primary_color',
        'secondary_color',
        'tertiary_color',
        'font'
    ];
}
