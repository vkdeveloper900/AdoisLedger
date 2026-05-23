<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name', 'party_type', 'mobile', 'mobile2', 'email',
        'address', 'profile_image',
        'bank_account_number', 'bank_ifsc_code', 'bank_branch', 'upi_id',
    ];

    public function ledgerEntries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LedgerEntry::class);
    }

    public function businessProfiles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(BusinessProfile::class);
    }

    public function isVendor(): bool
    {
        return in_array($this->party_type, ['vendor', 'both']);
    }

    public function isCustomer(): bool
    {
        return in_array($this->party_type, ['customer', 'both']);
    }
}
