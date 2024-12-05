<?php

namespace App\Models\Tenant\WebSettings;

use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    protected $fillable = [
        'facebook',
        'x',
        'youtube',
        'instagram',
        'linkedin',
        'snapchat',
        'telegram',
        'tiktok'
    ];
}
