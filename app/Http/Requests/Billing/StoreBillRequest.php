<?php

namespace App\Http\Requests\Billing;

use Illuminate\Foundation\Http\FormRequest;

class StoreBillRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'customer_id'      => ['required', 'exists:customers,id'],
            'date'             => ['required', 'date'],
            'notes'            => ['nullable', 'string', 'max:500'],
            'discount'         => ['nullable', 'numeric', 'min:0'],
            'amount_received'  => ['required', 'integer', 'min:0'],

            // Items
            'items'            => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.qty'      => ['required', 'numeric', 'min:0.001'],
            'items.*.rate'     => ['required', 'numeric', 'min:0'],
            'items.*.unit'     => ['nullable', 'string', 'max:20'],
            'items.*.fat'      => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $discount = (float) ($this->input('discount') ?? 0);
            $subtotal = collect($this->input('items', []))
                ->sum(fn($i) => (float)($i['qty'] ?? 0) * (float)($i['rate'] ?? 0));

            if ($discount > $subtotal) {
                $v->errors()->add('discount', 'Discount cannot exceed subtotal.');
            }

            $total    = (int) floor($subtotal - $discount);
            $received = (int) ($this->input('amount_received') ?? 0);

            if ($received > $total) {
                $v->errors()->add('amount_received', 'Amount received cannot exceed total.');
            }
        });
    }
}
