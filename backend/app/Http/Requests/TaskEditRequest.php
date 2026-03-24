<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskEditRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string'],
            'description' => ['sometimes', 'string'],
            'priority'=>['sometimes','in:HIGH,LOW,MEDIUM'],
            'due_date' => ['sometimes', 'date|after_or_equal:today'],
            'user_id' => ['sometimes', 'exists:users,id'],
        ];
    }
}
