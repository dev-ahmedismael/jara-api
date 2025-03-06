<?php

namespace App\Models\Central\Theme;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Theme extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title', 'primary_color', 'secondary_color', 'tertiary_color'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('themes')->singleFile();
    }
}
