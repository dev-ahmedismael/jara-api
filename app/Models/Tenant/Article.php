<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Article extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title', 'author', 'article',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('article')->singleFile();
    }
}
