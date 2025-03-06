<?php

namespace App\Models\Central\Tenant;


use App\Models\Central\Customer\Customer;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase, HasMedia
{
    use HasDatabase, HasDomains, InteractsWithMedia;

    protected $fillable = [
        'id',
        'website_type',
        'license_type',
        'license_name',
        'license_number',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('license_documents')->singleFile();
        $this->addMediaCollection('logo')->singleFile();
    }
    public function customers() : BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'customer_tenant')->withPivot('role');
    }

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'website_type',
            'license_type',
            'license_name',
            'license_number',
        ];
    }
}
