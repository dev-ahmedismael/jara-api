<?php

namespace App\Models\Jara;

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
        'type',
        'company_name_ar',
        'company_name_en',
        'license_number',
        'license_document',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('license_document')->singleFile();
    }


    public static function getCustomColumns(): array
    {
        return [
            'id',
            'type',
            'company_name_ar',
            'company_name_en',
            'company_name_en',
            'license_number',
            'license_document',
        ];
    }
}
