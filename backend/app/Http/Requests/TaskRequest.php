<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'priority'=>['required','in:HIGH,LOW,MEDIUM'],
            'due_date' => ['required', 'date'],
            'user_id' => ['required', 'exists:users,id'],
        ];
    }
}
