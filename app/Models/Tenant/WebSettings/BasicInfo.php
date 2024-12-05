<?php

namespace App\Models\Tenant\WebSettings;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BasicInfo extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name_ar', 'name_en', 'email', 'phone', 'description'
    ];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('logo')
            ->singleFile();

        $this
            ->addMediaCollection('icon')
            ->singleFile();
    }
}
