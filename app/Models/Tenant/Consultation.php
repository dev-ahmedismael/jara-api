<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Consultation extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
        'type',
        'start_date',
        'end_date',
        'booking_last_date',
        'price',
        'enable_comments',
        'enable_rates',
        'status',
        'form_fields'
    ];

    protected $casts = [
        'form_fields' => 'array',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('consultation');
    }
}
