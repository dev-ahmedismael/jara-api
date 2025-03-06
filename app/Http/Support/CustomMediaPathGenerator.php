<?php
namespace App\Http\Support;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Stancl\Tenancy\Facades\Tenancy;
use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;

class CustomMediaPathGenerator extends DefaultPathGenerator
{
    /**
     * Get the path for the given media, relative to the root storage path.
     */
    public function getPath(Media $media): string
    {
        $tenantId = $this->getTenantId();
        $collectionName = $media->collection_name;

        return $tenantId . '/' . $collectionName . '/';
    }

    /**
     * Get the path for conversions of the given media, relative to the root storage path.
     */
    public function getPathForConversions(Media $media): string
    {
        $tenantId = $this->getTenantId();
        $collectionName = $media->collection_name;

        return $tenantId . '/' . $collectionName . '/conversions/';
    }

    /**
     * Get the path for responsive images of the given media, relative to the root storage path.
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        $tenantId = $this->getTenantId();
        $collectionName = $media->collection_name;

        return $tenantId . '/' . $collectionName . '/responsive-images/';
    }

    /**
     * Get the current tenant ID or return 'central' if no tenant is identified.
     */
    private function getTenantId(): string
    {
        $tenant = tenant();
        return $tenant ? $tenant->id : 'central';
    }
}
