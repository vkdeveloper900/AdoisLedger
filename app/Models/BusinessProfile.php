<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessProfile extends Model
{
    protected $fillable = [
        'name',
        'business_type_id',
        'email',
        'phone',
        'manager_name',
        'address',
        'city',
        'country',
        'bank_name',
        'bank_holder_name',
        'bank_account_number',
        'bank_ifsc_code',
        'bank_branch',
    ];

    public function customers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Customer::class);
    }

    public function getBusinessTypeLabelAttribute(): string
    {
        $match = collect(config('constants.business_types'))->firstWhere('id', $this->business_type_id);
        return $match['label'] ?? 'Unknown';
    }
}
