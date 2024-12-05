<?php

namespace App\Models\Tenant\SiteBuilder;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $fillable = [
        'slug', 'title', 'description', 'display_in_navbar', 'display_in_footer'
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }
}
