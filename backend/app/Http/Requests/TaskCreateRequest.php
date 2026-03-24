<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'priority'=>['required','in:HIGH,LOW,MEDIUM'],
            'due_date' => ['required', 'date|after_or_equal:today'],
            'user_id' => ['required', 'exists:users,id'],
        ];
    }
}
