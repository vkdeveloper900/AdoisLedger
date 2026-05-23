<?php

namespace App\Http\Requests\Parties;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                 => ['required', 'string', 'max:255'],
            'party_type'           => ['nullable', 'string', 'in:customer,vendor,both'],
            'mobile'               => ['nullable', 'string', 'max:20'],
            'mobile2'              => ['nullable', 'string', 'max:20'],
            'email'                => ['nullable', 'email', 'max:255'],
            'address'              => ['nullable', 'string', 'max:500'],
            'profile_image'        => ['nullable', 'image', 'max:1024'],
            'bank_account_number'  => ['nullable', 'string', 'max:50'],
            'bank_ifsc_code'       => ['nullable', 'string', 'max:20'],
            'bank_branch'          => ['nullable', 'string', 'max:255'],
            'upi_id'               => ['nullable', 'string', 'max:100'],
        ];
    }
}
