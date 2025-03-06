<?php

namespace App\Models\Tenant\App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class App extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $fillable = ['app_id','title', 'description', 'type', 'auth_url', 'access_token', 'refresh_token', 'expires_in', 'fields', 'price', 'image'];


    protected $casts = [
        'fields' => 'array',
        'expires_in' => 'datetime',
    ];
}
