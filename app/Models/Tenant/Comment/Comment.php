<?php

namespace App\Models\Tenant\Comment;

use App\Models\Tenant\Customer\Customer;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['content', 'customer_id'];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(Customer::class);
    }
}
