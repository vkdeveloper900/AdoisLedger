<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LedgerEntry extends Model
{
    protected $fillable = [
        'transaction_id', 'business_profile_id', 'customer_id',
        'account_head', 'debit', 'credit', 'running_balance', 'date', 'description',
    ];

    protected $casts = ['date' => 'date'];

    public function transaction(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
