<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'summary' => ['nullable', 'string', 'max:5000'],
            'authority_level' => ['required', 'string', 'in:civic,warrior,elder'],
        ];
    }
}
