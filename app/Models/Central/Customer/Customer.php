<?php

namespace App\Models\Central\Customer;

use App\Models\Central\Tenant\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Customer extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    public function registerMediaCollections(): void {
        $this->addMediaCollection('license_documents')->singleFile();
    }
    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class, 'customer_tenant')->withPivot('role');
    }
}
