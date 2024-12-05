<?php

namespace App\Http\Support\Media;

use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator;

class TenantAwareUrlGenerator extends DefaultUrlGenerator
{
    public function getUrl(): string
    {
        $url = Storage::disk('media')->url($this->getPathRelativeToRoot());


        return $url;
    }
}
