<?php

namespace App\Models\Tenant\Order;

use App\Models\Tenant\Customer\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chatroom extends Model
{

    protected $fillable = [
        'customer_id', 'order_id'
    ];
    public function order() :BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer() :BelongsTo {
        return $this->BelongsTo(Customer::class);
    }

    public function chat_messages() :HasMany {
        return $this->hasMany(ChatMessage::class);
    }
}
