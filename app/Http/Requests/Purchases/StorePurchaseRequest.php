<?php

namespace App\Http\Requests\Purchases;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vendor_id'            => ['required', 'exists:customers,id'],
            'date'                 => ['required', 'date'],
            'discount'             => ['nullable', 'numeric', 'min:0'],
            'amount_paid'          => ['required', 'integer', 'min:0'],
            'notes'                => ['nullable', 'string', 'max:500'],
            'items'                => ['required', 'array', 'min:1'],
            'items.*.description'  => ['required', 'string', 'max:255'],
            'items.*.qty'          => ['required', 'numeric', 'min:0.001'],
            'items.*.fat'          => ['nullable', 'numeric', 'min:0', 'max:100'],
            'items.*.rate'         => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'vendor_id.required' => 'Please select a vendor.',
            'items.required'     => 'At least one item is required.',
            'items.min'          => 'At least one item is required.',
        ];
    }
}
