<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'cep' => preg_replace('/\D+/', '', (string) $this->input('cep')),
            'estado' => strtoupper(trim((string) $this->input('estado'))),
        ]);
    }

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
            'rua' => ['nullable', 'string', 'max:255'],
            'bairro' => ['nullable', 'string', 'max:255'],
            'cidade' => ['nullable', 'string', 'max:255'],
            'estado' => ['nullable', 'alpha', 'size:2'],
            'cep' => ['required', 'digits:8'],
        ];
    }
}
