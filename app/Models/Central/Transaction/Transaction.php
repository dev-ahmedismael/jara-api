<?php

namespace App\Models\Central\Transaction;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use Filterable;

    protected $fillable = [
        'tenant_id',
        'transaction_amount',
        'transaction_date',
        'bank_account_number',
        'balance',
        'next_transaction_date',
    ];
}
