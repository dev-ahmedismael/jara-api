<?php

namespace App\Models\Tenant\WebsiteSettings;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\MediaCollection;

class Page extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = [
        'title',
        'description',
        'images',
        'videos',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('pages');
    }

    public function getImagesUrlsAttribute()
    {
        return $this->getMedia('pages')->map(fn($media) => $media->getUrl())->toArray();
    }

    protected $casts = [
        'videos' => 'array',
    ];
}
