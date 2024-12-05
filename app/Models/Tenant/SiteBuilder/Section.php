<?php

namespace App\Models\Tenant\SiteBuilder;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Section extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'page_id', 'index', 'type', 'properties'
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
