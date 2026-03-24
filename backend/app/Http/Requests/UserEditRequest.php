<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserEditRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'name' => 'required',
            'role' => 'required|in:admin,worker',
        ];
    }
}
