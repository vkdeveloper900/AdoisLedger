<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'unique:users,email,' . $userId],
            'phone'     => ['nullable', 'string', 'max:20'],
            'is_active' => ['nullable', 'boolean'],
            'avatar'    => ['nullable', 'image', 'max:2048'],
            'password'  => $userId
                ? ['nullable', 'confirmed', Password::min(8)]
                : ['required', 'confirmed', Password::min(8)],
        ];
    }
}
