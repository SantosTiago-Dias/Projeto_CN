<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'role' => 'required|in:admin,worker',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'O nome é obrigatório.',
            'email.required'    => 'O email é obrigatório.',
            'email.email'       => 'O email deve ser um endereço válido.',
            'email.unique'      => 'Este email já está em uso.',
            'password.required' => 'A palavra-passe é obrigatória.',
            'password.string'   => 'A palavra-passe deve ser um texto.',
            'role.required'     => 'A função é obrigatória.',
            'role.in'           => 'A função deve ser: admin ou worker.',
        ];
    }

}
