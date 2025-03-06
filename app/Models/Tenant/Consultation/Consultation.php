<?php

namespace App\Models\Tenant\Consultation;

use App\Models\Tenant\Order\Order;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Consultation extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Filterable;

    protected $fillable = [
        'type',
        'title',
        'description',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'expiry_date',
        'expiry_time',
        'price',
        'enable_comments',
        'enable_rating',
        'max_allowed_bookings'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'expiry_date' => 'datetime',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('consultation_images')->singleFile();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
