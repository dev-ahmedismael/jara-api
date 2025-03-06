<?php

namespace App\Models\Tenant\WebsiteSettings;

use Illuminate\Database\Eloquent\Model;

class SocialMediaUrl extends Model
{
    protected $fillable = [
        'facebook',
        'x',
        'instagram',
        'linkedin',
        'youtube',
        'telegram',
        'snapchat',
        'tiktok',
    ];
}
