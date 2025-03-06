<?php

namespace App\Models\Central\App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class App extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $fillable = ['title', 'description', 'type', 'auth_url', 'fields', 'price'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('apps')->singleFile();
    }
    protected $casts = [
        'fields' => 'array',
    ];
}
