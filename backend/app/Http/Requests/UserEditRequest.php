<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserEditRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'name' => 'sometimes',
            'role' => 'sometimes|in:admin,worker',
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'O nome deve ser um texto.',
            'role.in'     => 'A função deve ser: admin ou worker.',
        ];
    }
}
