<?php

namespace App\Models\Tenant\Order;

use App\Models\Tenant\Customer\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ZoomMeeting extends Model
{
    protected $fillable = [
        'start_url',
        'join_url',
        'order_id',
        'customer_id',
    ];

    public function order():BelongsTo {
        return $this->belongsTo(Order::class);
    }
}
