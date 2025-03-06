<?php

namespace App\Models\Tenant\Theme;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = [
        'theme_id',
        'title',
        'primary_color',
        'secondary_color',
        'tertiary_color',
        'custom_primary_color',
        'custom_secondary_color',
        'custom_tertiary_color',
    ];
}
