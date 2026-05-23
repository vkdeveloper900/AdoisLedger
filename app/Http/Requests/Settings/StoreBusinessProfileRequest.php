<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class StoreBusinessProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                 => ['required', 'string', 'max:255'],
            'business_type_id'     => ['required', 'integer', 'in:1,2,3'],
            'email'                => ['nullable', 'email', 'max:255'],
            'phone'                => ['nullable', 'string', 'max:50'],
            'manager_name'         => ['nullable', 'string', 'max:255'],
            'address'              => ['nullable', 'string', 'max:500'],
            'city'                 => ['nullable', 'string', 'max:100'],
            'country'              => ['required', 'string', 'max:100'],
            'bank_name'            => ['nullable', 'string', 'max:255'],
            'bank_holder_name'     => ['nullable', 'string', 'max:255'],
            'bank_account_number'  => ['nullable', 'string', 'max:50'],
            'bank_ifsc_code'       => ['nullable', 'string', 'max:20'],
            'bank_branch'          => ['nullable', 'string', 'max:255'],
        ];
    }
}
