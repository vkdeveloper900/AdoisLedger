<?php

namespace App\Models;

use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    protected $fillable = [
        'business_profile_id', 'customer_id', 'created_by', 'type',
        'bill_number', 'public_token', 'date', 'status',
        'subtotal', 'discount', 'total_amount', 'amount_received', 'balance',
        'notes', 'posted_by', 'posted_at',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (Transaction $transaction) {
            if (empty($transaction->public_token)) {
                $transaction->public_token = Str::uuid();
            }
        });
    }

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
