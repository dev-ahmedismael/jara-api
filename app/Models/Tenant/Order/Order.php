<?php

namespace App\Models\Tenant\Order;

use App\Models\Tenant\Consultation\Consultation;
use App\Models\Tenant\Customer\Customer;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use Filterable;

    protected $fillable = [
        'customer_id',
        'consultation_id',
        'type',
        'title',
        'customer_name',
        'paid_amount',
        'start_date',
        'start_time',
        'is_active',
    ];

    public function zoom_meeting(): HasOne {
        return $this->hasOne(ZoomMeeting::class);
    }

    public function chatroom(): HasOne {
        return $this->hasOne(Chatroom::class);
    }

    public function consultation(): BelongsTo {
        return $this->belongsTo(Consultation::class);
    }

    public function customer(): BelongsTo {
        return $this->belongsTo(Customer::class);
    }
}
