<?php

namespace App\Models;

use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'business_profile_id', 'customer_id', 'created_by', 'type',
        'bill_number', 'date', 'status',
        'subtotal', 'discount', 'total_amount', 'amount_received', 'balance',
        'notes', 'posted_by', 'posted_at',
    ];

    protected $casts = [
        'type' => TransactionType::class,
        'date' => 'date',
        'posted_at' => 'datetime',
    ];

    public function business(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BusinessProfile::class, 'business_profile_id');
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function ledgerEntries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LedgerEntry::class);
    }

    public function isPosted(): bool
    {
        return $this->status === 'posted';
    }
}
