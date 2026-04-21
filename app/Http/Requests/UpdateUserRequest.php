<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->route('user')),
            ],
            'password' => ['nullable', 'string', 'min:8'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'summary' => ['nullable', 'string', 'max:5000'],
            'authority_level' => ['required', 'string', 'in:civic,warrior,elder'],
        ];
    }
}
