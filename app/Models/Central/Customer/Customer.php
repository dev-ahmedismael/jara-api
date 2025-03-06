<?php

namespace App\Models\Central\Customer;

use App\Models\Central\Tenant\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
    ];
    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class, 'customer_tenant')->withPivot('role');
    }
}
