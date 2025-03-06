<?php

namespace App\Models\Tenant\Customer;

use App\Models\Tenant\Order\Chatroom;
use App\Models\Tenant\Order\Order;
use App\Models\Tenant\Order\ZoomMeeting;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticable;


class Customer extends Authenticable
{
    use HasApiTokens, HasFactory, Notifiable, Filterable;

    protected $fillable = [
        'name', 'phone', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function orders(): HasMany {
     return $this->hasMany(Order::class);

    }

    public function chat_rooms(): HasMany {
     return $this->hasMany(Chatroom::class);
    }


}
