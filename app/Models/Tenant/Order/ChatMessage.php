<?php

namespace App\Models\Tenant\Order;

use App\Models\Tenant\Customer\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $fillable = [
        'message',
        'chatroom_id',
        'customer_id',
    ];

    public function chatroom(): BelongsTo {
        return $this->belongsTo(Chatroom::class);
    }


}
